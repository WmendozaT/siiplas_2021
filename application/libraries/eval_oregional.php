<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Eval_oregional extends CI_Controller{
    public function __construct (){
      parent::__construct();
      $this->load->model('programacion/model_proyecto');
      $this->load->model('resultados/model_resultado');
      $this->load->model('mestrategico/model_mestrategico');
      $this->load->model('mestrategico/model_objetivogestion');
      $this->load->model('mestrategico/model_objetivoregion');
      $this->load->model('ejecucion/model_evaluacion');
      $this->load->model('menu_modelo');
      $this->load->library('security');

      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm');
      //$this->rol = $this->session->userData('rol_id');
      $this->dist = $this->session->userData('dist');
      //$this->dist_tp = $this->session->userData('dist_tp');
      $this->tmes = $this->session->userData('trimestre');
      $this->fun_id = $this->session->userData('fun_id');
     // $this->tp_adm = $this->session->userData('tp_adm');
      $this->verif_mes=$this->session->userData('mes_actual');
      $this->resolucion=$this->session->userdata('rd_poa');
      $this->tp_adm = $this->session->userData('tp_adm');
      $this->mes = $this->mes_nombre();
      $this->dep_id = $this->session->userData('dep_id');
      $this->conf_form4 = $this->session->userData('conf_form4');
      $this->conf_form5 = $this->session->userData('conf_form5');
      $this->conf_estado = $this->session->userData('conf_estado'); /// conf estado Gestion (1: activo, 0: no activo)
      $this->fecha_plazo_actualizacion = strtotime(date('2022-03-20'));
    }

    
    /*------- TIPO --------*/
    public function titulo(){
      $tabla='';
      $trimestre=$this->model_evaluacion->trimestre();
      $tabla.='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well">
              <h2>EVALUACI&Oacute;N DE OBJETIVOS REGIONALES (OPERACIONES) '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</h2>
            </div>
        </article>';

      return $tabla;
    } 

    /*-------- LISTA DE REGIONALES ----------*/
    public function regionales(){
      $regionales=$this->model_proyecto->list_departamentos();
      $tabla='
          <div>
            <div id="tabs">
              <ul>
                <li>
                  <a href="#tabs-1" style="width:100%;">CHUQUISACA</a>
                </li>
                <li>
                  <a href="#tabs-2">LA PAZ</a>
                </li>
                <li>
                  <a href="#tabs-3">COCHABAMBA</a>
                </li>
                <li>
                  <a href="#tabs-4">ORURO</a>
                </li>
                <li>
                  <a href="#tabs-5">POTOSI</a>
                </li>
                <li>
                  <a href="#tabs-6">TARIJA</a>
                </li>
                <li>
                  <a href="#tabs-7">SANTA CRUZ</a>
                </li>
                <li>
                  <a href="#tabs-8">BENI</a>
                </li>
                <li>
                  <a href="#tabs-9">PANDO</a>
                </li>
                <li>
                  <a href="#tabs-10">OFICINA NACIONAL</a>
                </li>
              </ul>';
              for ($i=1; $i <=10 ; $i++) { 
                $tabla.='
                <div id="tabs-'.$i.'">
                  <div class="row">
                    '.$this->ver_relacion_ogestion($i).'
                  </div>
                </div>';
              }
              $tabla.='
            </div>
          </div>';

      return $tabla;
    }

    //// REGIONAL ALINEADO A OBJETIVOS REGIONALES 2020-2021
    public function ver_relacion_ogestion($dep_id){
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $tabla='';
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      //$fecha_actual = date('Y-m-d');
      $date_actual = strtotime(date('Y-m-d')); //// fecha Actual

      if(($date_actual<=$this->fecha_plazo_actualizacion) & $this->tp_adm==1) {
          $tabla.='
          <div id="row">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="alert alert-info" role="alert">
                <a href="#" data-toggle="modal" data-target="#modal_update_temporalidad" class="btn btn-primary update_temporalidad" style="width:20%;" name="'.$dep_id.'" id="'.strtoupper($departamento[0]['dep_departamento']).'" title="ACTUALIZAR EVALUACION OBJETIVO REGIONAL" ><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="30"/>&nbsp;ACTUALIZAR TEMPORALIDAD - OBJETIVO REGIONAL</a>    
              </div>
            </article>
          </div>';
      }
      
      $tabla.=' 
      <div align="right">
        <a href="javascript:abreVentana(\''.site_url("").'/eval_obj/rep_meta_oregional/'.$dep_id.'\');" title="REPORTE EVALUACIÓN META REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>EVALUACI&Oacute;N METAS REGIONALES (.PDF)</b></a>&nbsp;&nbsp;
        <a href="#" data-toggle="modal" data-target="#modal_evaluacion" name="'.$dep_id.'" class="btn btn-default evaluacion" title="MOSTRAR CUADRO DE EVALUACIÓN DE METAS"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>CUADRO DE EVALUACI&Oacute;N (GRAFICO)</b></a>
      </div><br>
      <input name="base" type="hidden" value="'.base_url().'">
      <section class="col col-6">
        <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
      </section>
      <table class="table table-bordered" border=0.2 style="width:100%;" id="datos">
        <thead>
        <tr style="font-size: 11px;" >
          <th style="width:1%;height:10px;color:#FFF; text-align: center" bgcolor="#1c7368">N° '.$this->conf_estado.'</th>
          <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>COD. ACE.</b></th>
          <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>COD. ACP.</b></th>
          <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>COD. OPE.</b></th>
          <th style="width:11%;color:#FFF; text-align: center" bgcolor="#1c7368">OPERACI&Oacute;N</th>
          <th style="width:11%;color:#FFF; text-align: center" bgcolor="#1c7368">RESULTADO</th>
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">INDICADOR</th>
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">MEDIO VERIFICACI&Oacute;N</th>
          <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">META</th>
          <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">META ALINEADO</th>
          <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368">META (I) TRIMESTRE</th>
          <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368">META (II) TRIMESTRE</th>
          <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368">META (III) TRIMESTRE</th>
          <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368">META (IV) TRIMESTRE</th>
          <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
          <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
        </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($lista_ogestion as $row){
          $color='';
          $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
          $calificacion=$this->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
          $boton_ajustar_apriorizados='
              <center><a href="'.site_url("").'/me/alineacion_ope_acp/'.$row['og_id'].'" target="_blank" class="btn btn-default" title="VER ALINEACION ACP-FORM4"><img src="'.base_url().'assets/Iconos/application_double.png" WIDTH="30" HEIGHT="30"/></a>
              <br>AJUSTAR ALINEACIÓN</center>';

          if(count($metas_prior)!=0){
              if(round($row['or_meta'],2)==$metas_prior[0]['meta_prog_actividades']){
                $boton_ajustar_apriorizados='<div style="font-size: 15px; color:blue" align=center><b>'.round($metas_prior[0]['meta_prog_actividades'],2).'</b></div>';
              }
          }

          $nro++;
          $tabla.='
          <tr style="font-size: 10px;" bgcolor='.$color.'>
            <td style="width:1%; height:10px;" align=center title='.$row['pog_id'].'>'.$nro.'</td>
            <td style="width:2%;" align="center"><b>'.$row['acc_codigo'].'</b></td>
            <td style="width:2%; font-size: 17px; color:blue" align="center"><b>'.$row['og_codigo'].'</b></td>
            <td style="width:2%; font-size: 17px;" align="center" bgcolor="#f1eeee" title='.$row['or_id'].'><b>'.$row['or_codigo'].'</b></td>
            <td style="width:11%;">'.$row['or_objetivo'].'</td>
            <td style="width:11%;">'.$row['or_resultado'].'</td>
            <td style="width:10%;">'.$row['or_indicador'].'</td>
            <td style="width:10%;">'.$row['or_verificacion'].'</td>
            <td style="width:2%; font-size: 15px;" align=center><b>'.round($row['or_meta'],2).'</b></td>
            <td style="width:2%;">'.$boton_ajustar_apriorizados.'</td>
            '.$this->get_temporalidad_objetivo_regional($row['or_id']).'
            <td style="font-family:Verdana;font-size: 20px;" align=center><b>'.$calificacion[3].' %</b></td>
            <td><a href="#" data-toggle="modal" data-target="#modal_act_priorizados" class="btn btn-default" name="'.$row['or_id'].'"  onclick="ver_actividades_priorizados('.$row['or_id'].','.$dep_id.');" title="VER MIS ACTIVIDADES PRIORIZADOS">ACT. PRIORIZADOS</a></td>
          </tr>';
        }
        $tabla.='
        </tbody>
      </table> ';

      return $tabla;
    }



    /*-- ARMANDO TEMPORALIDAD PARA OBJETIVOS REGIONAL POR REGIONAL --*/
    public function get_temporalidad_objetivo_regional($or_id){
      $verif_temp=$this->model_objetivoregion->verif_temporalidad_oregional($or_id);
      $tabla='';

      if(count($verif_temp)!=0){
        for ($i=1; $i <=4 ; $i++) {
         $valor=$this->calificacion_trimestral_acumulado_x_oregional($or_id,$i);

          $color='#f1f5f4';
          if($i<=$this->tmes){
            $color='#e4fdf7';
          }

          $tabla.='
            <td bgcolor="'.$color.'" align=center>
              <table class="table table-bordered" border=0.2 style="width:80%;">
                <tr>
                  <td style="width:50%;"><b>PROG.</b></td>
                  <td style="width:50%;font-size: 12px; color:blue" align=right><b>'.$valor[1].'</b></td>
                </tr>
                <tr>
                  <td><b>EJEC.</b></td>
                  <td style="font-size: 12px; color:blue" align=right><b>'.$valor[2].'</b></td>
                </tr>
                <tr>
                  <td><b>EFI.</b></td>
                  <td style="font-size: 13px; color:blue" align=right><b>'.$valor[3].'%</b></td>
                </tr>
              </table>
            </td>';
        }
      }
      else{
        for ($i=1; $i <=4 ; $i++) { 
          $color='#fbf6f6';
          if($i<=$this->tmes){
            $color='#f7e0e0';
          }
          $tabla.='<td bgcolor="'.$color.'" align=center title="SIN TEMPORALIDAD">-</td>';
        }
      }

      return $tabla;
    }


    /*-- CALIFICACION TRIMESTRAL POR OBJETIVO REGIONAL --*/
    public function calificacion_trimestral_acumulado_x_oregional($or_id,$trimestre){
      $valor = array( '1' => '0','2' => '0','3' => '0');

      if(count($this->model_objetivoregion->verif_temporalidad_oregional($or_id))!=0){
        $suma_prog=0; $suma_ejec=0;
        
        for ($i=1; $i <=$trimestre ; $i++) {
          $get_trm=$this->model_objetivoregion->get_trm_temporalidad_prog_oregional($or_id,$i); /// Temporalidad Programado
          $get_trm_ejec=$this->model_objetivoregion->get_trm_temporalidad_ejec_oregional($or_id,$i); /// Temporalidad Ejecutado

          if(count($get_trm)!=0){
            $suma_prog=$suma_prog+$get_trm[0]['pg_fis'];
          }

          if(count($get_trm_ejec)!=0){
            $suma_ejec=$suma_ejec+$get_trm_ejec[0]['ejec_fis'];
          }

          $ejecucion=0;
          if($suma_ejec!=0){
            $ejecucion=round((($suma_ejec/$suma_prog)*100),2);
          }
        }

          $valor[1]=$suma_prog; /// Programado Acumulado
          $valor[2]=$suma_ejec; /// Ejecutado Acumulado
          $valor[3]=$ejecucion; /// Ejecucion
      }

      return $valor; 
    }


    /*-- ARMANDO TEMPORALIDAD PARA OBJETIVOS REGIONAL POR REGIONAL --*/
    public function create_temporalidad_oregional($dep_id){
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      foreach($lista_ogestion as $row){

        $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
        if(count($metas_prior)!=0){
            if(round($row['or_meta'],2)==round($metas_prior[0]['meta_prog_actividades'],2)) {
               /// Crear o actualizar la tabla de temporalidad
              if(count($this->model_objetivoregion->verif_temporalidad_oregional($row['or_id']))!=0){
                $this->db->where('or_id', $row['or_id']);
                $this->db->delete('temp_trm_prog_objetivos_regionales');
              }

              /// creamos registro
              for ($i=1; $i <=4 ; $i++) { 
                $get_dato_trimestre=$this->model_objetivoregion->get_suma_trimestre_para_oregional($row['or_id'],$i);
                if(count($get_dato_trimestre)!=0){
                  /*--------------------------------------------------------*/
                  $data_to_store2 = array( ///// Tabla temp prog oregional
                    'or_id' => $row['or_id'], /// or id
                    'trm_id' => $i, /// trimestre
                    'pg_fis' => $get_dato_trimestre[0]['trimestre'], /// valor
                    'g_id' => $this->gestion, /// gestion                
                  );
                  $this->db->insert('temp_trm_prog_objetivos_regionales', $data_to_store2);
                  /*----------------------------------------------------------*/
                }
              }
              
            }
        }
      }

    }



    //// LISTA DE ACTIVIDADES PRIORIZADOS POR OBJ REGIONAL
    public function get_mis_form4_priorizados_x_oregional($or_id){
      $tabla='<script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';
        $form4=$this->model_objetivoregion->get_lista_form_priorizados_x_oregional($or_id);
        $tabla.='
        <hr>
        <div class="table-responsive">
        <table id="dt_basic" class="table table-bordered" border=0.2 style="width:100%;">
          <thead>
            <tr style="font-size: 11px;" >
              <th style="width:1%;height:10px;color:#FFF; text-align: center" bgcolor="#1c7368">#</th>
              <th style="width:5%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>PROGRAMA</b></th>
              <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>GASTO CORRIENTE / PROY. INVERSIÓN</b></th>
              <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368"><b>UNIDAD RESPONSABLE</b></th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">COD. ACT.</th>
              <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">ACTIVIDAD</th>
              <th style="width:9%;color:#FFF; text-align: center" bgcolor="#1c7368">RESULTADO</th>
              <th style="width:5%;color:#FFF; text-align: center" bgcolor="#1c7368">RESPONSABLE</th>
              <th style="width:9%;color:#FFF; text-align: center" bgcolor="#1c7368">INDICADOR</th>
              <th style="width:2.5%;color:#FFF; text-align: center" bgcolor="#1c7368">META</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">ENE.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">FEB.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">MAR.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">ABR.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">MAY.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">JUN.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">JUL.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">AGO.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">SEPT.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">OCT.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">NOV.</th>
              <th style="width:2%;color:#FFF; text-align: center" bgcolor="#1c7368">DIC.</th>
              <th style="width:8%;color:#FFF; text-align: center" bgcolor="#1c7368">MEDIO DE VERIFICACIÓN</th>
            </tr>
          </thead>
          <tbody>';
        
        $nro=0;$suma_meta=0;
        foreach($form4 as $row){
          $nro++;
          $suma_meta=$suma_meta+$row['prod_meta'];
          $tabla.='
          <tr style="font-size: 10px;">
            <td style="width:1%; height:5px;" align=center title='.$row['prod_id'].'>'.$nro.'</td>';
              if($row['tp_id']==1){
                $tabla.='
                <td style="width:5%;"><b>'.$row['aper_programa'].' '.$row['proy_sisin'].' '.$row['aper_actividad'].'</b></td>
                <td style="width:10%;">'.$row['proy_nombre'].'</td>';
              }
              else{
                $tabla.='
                <td style="width:5%;"><b>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</b></td>
                <td style="width:10%;">'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</td>';
              }
            $tabla.='
            
            <td style="width:8%;">'.$row['serv_cod'].' .- '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>
            <td style="width:2%; font-size: 15px;" align=center><b>'.$row['prod_cod'].'</b></td>
            <td style="width:10%;">'.$row['prod_producto'].'</td>
            <td style="width:9%;">'.$row['prod_resultado'].'</td>
            <td style="width:5%;">'.$row['prod_unidades'].'</td>
            <td style="width:9%;">'.$row['prod_indicador'].'</td>
            <td style="width:2%; font-size: 15px;" align=right><b>'.round($row['prod_meta'],2).'</b></td>

            <td style="width:2%;" align=right><b>'.round($row['enero'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['febrero'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['marzo'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['abril'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['mayo'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['junio'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['julio'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['agosto'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['septiembre'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['octubre'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['noviembre'],2).'</b></td>
            <td style="width:2%;" align=right><b>'.round($row['diciembre'],2).'</b></td>
            <td style="width:8%;">'.$row['prod_fuente_verificacion'].'</td>
          </tr>';
        }
        $tabla.='
          </tbody>
          <tr>
            <td colspan=9 align=right><b>META PRIORIZADO : </b></td>
            <td style="width:2%; font-size: 15px;" align=right><b>'.round($suma_meta,2).'</b></td>
            <td colspan=13></td>
          </tr>
        </table>
        </div>';

      return $tabla;
    }

























  /*-------- MENU -----*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++){
            if(count($subenlaces[$enlaces[$i]['o_child']])>0){
                $tabla .='<li>';
                    $tabla .='<a href="#">';
                        $tabla .='<i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>';    
                        $tabla .='<ul>';    
                            foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
                            $tabla .='<li><a href="'.base_url($item['o_url']).'">'.$item['o_titulo'].'</a></li>';
                        }
                        $tabla .='</ul>';
                $tabla .='</li>';
            }
        }

        return $tabla;
    }

        /*------ NOMBRE MES -------*/
    function mes_nombre(){
        $mes[1] = 'ENE.';
        $mes[2] = 'FEB.';
        $mes[3] = 'MAR.';
        $mes[4] = 'ABR.';
        $mes[5] = 'MAY.';
        $mes[6] = 'JUN.';
        $mes[7] = 'JUL.';
        $mes[8] = 'AGOS.';
        $mes[9] = 'SEPT.';
        $mes[10] = 'OCT.';
        $mes[11] = 'NOV.';
        $mes[12] = 'DIC.';
        return $mes;
    }
}
?>