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
      $this->load->model('programacion/model_producto');
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
          <a href="javascript:abreVentana(\''.site_url("").'/rep_eval_oregional/'.$dep_id.'\');" title="REPORTE EVALUACIÓN META REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>EVALUACI&Oacute;N METAS REGIONALES (.PDF)</b></a>&nbsp;&nbsp;
          <a href="#" data-toggle="modal" data-target="#modal_evaluacion" name="'.$dep_id.'" class="btn btn-default evaluacion" title="MOSTRAR CUADRO DE EVALUACIÓN DE METAS"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>CUADRO DE EVALUACI&Oacute;N (GRAFICO)</b></a>
        </div><br>
        <input name="base" type="hidden" value="'.base_url().'">
        <section class="col col-6">
          <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
        </section>
        <div class="table-responsive">
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
            <th style="width:6%;color:#FFF; text-align: center" bgcolor="#1c7368">META (I) TRIMESTRE</th>
            <th style="width:6%;color:#FFF; text-align: center" bgcolor="#1c7368">META (II) TRIMESTRE</th>
            <th style="width:6%;color:#FFF; text-align: center" bgcolor="#1c7368">META (III) TRIMESTRE</th>
            <th style="width:6%;color:#FFF; text-align: center" bgcolor="#1c7368">META (IV) TRIMESTRE</th>
            <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
            <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
            <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
          </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($lista_ogestion as $row){
            $meta='';
            $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
            if($row['indi_id']==2){
              $meta='%';
              $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional_recurrentes($row['or_id']);
            }
            $color='';$grafico='';
            
            $calificacion=$this->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
            $boton_ajustar_apriorizados='
                <center><a href="'.site_url("").'/me/alineacion_ope_acp/'.$row['og_id'].'" target="_blank" class="btn btn-default" title="VER ALINEACION ACP-FORM4"><img src="'.base_url().'assets/Iconos/application_double.png" WIDTH="30" HEIGHT="30"/></a>
                <br>AJUSTAR ALINEACIÓN</center>';

            if(count($metas_prior)!=0){
              if(round($row['or_meta'],2)==$metas_prior[0]['meta_prog_actividades']){
                $boton_ajustar_apriorizados='<div style="font-size: 15px; color:blue" align=center><b>'.round($metas_prior[0]['meta_prog_actividades'],2).''.$meta.'</b></div>';
                $grafico='<br><a href="#" data-toggle="modal" data-target="#modal_cumplimiento" class="btn btn-default" name="'.$row['or_id'].'"  onclick="nivel_cumplimiento('.$row['or_id'].','.$dep_id.');" title="NIVEL DE CUMPLIMIENTO"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="35" HEIGHT="35"/></a>';
              }
              else{
                $boton_ajustar_apriorizados='
                <center><b>('.round($metas_prior[0]['meta_prog_actividades'],2).')</b><br><a href="'.site_url("").'/me/alineacion_ope_acp/'.$row['og_id'].'" target="_blank" class="btn btn-default" title="VER ALINEACION ACP-FORM4"><img src="'.base_url().'assets/Iconos/application_double.png" WIDTH="30" HEIGHT="30"/></a>
                <br>AJUSTAR ALINEACIÓN</center>';
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
              <td style="width:2%; font-size: 15px;" align=center><b>'.round($row['or_meta'],2).' '.$meta.'</b></td>
              <td style="width:2%;" align=center>'.$boton_ajustar_apriorizados.'</td>
              '.$this->get_temporalidad_objetivo_regional($row['or_id'],0).'
              <td style="font-family:Verdana;font-size: 20px;" align=center><b>'.$calificacion[3].' %</b></td>
              <td>
                <a href="#" data-toggle="modal" data-target="#modal_act_priorizados" class="btn btn-default" name="'.$row['or_id'].'"  onclick="ver_actividades_priorizados('.$row['or_id'].','.$dep_id.');" title="VER MIS ACTIVIDADES PRIORIZADOS">ACT. PRIORIZADOS</a>
              </td>
              <td align=center>'.$grafico.'</td>
            </tr>';
          }
          $tabla.='
          </tbody>
        </table>
      </div>';

      return $tabla;
    }



    /*-- ARMANDO TEMPORALIDAD PARA OBJETIVOS REGIONAL POR REGIONAL (TRIMESTRAL) --*/
    public function get_temporalidad_objetivo_regional($or_id,$tp_rep){
      /// tp_rep=0 normal
      /// tp_rep=1 Reporte

      $verif_temp=$this->model_objetivoregion->verif_temporalidad_oregional($or_id);
      $tabla='';

      if(count($verif_temp)!=0){
        for ($i=1; $i <=4 ; $i++) {
         $valor=$this->calificacion_trimestral_acumulado_x_oregional($or_id,$i);

          $color='#f1f5f4';
          if($i<=$this->tmes){
            $color='#e4fdf7';
          }


          if($tp_rep==0){ /// VISTA NORMAL
            $tabla.='
            <td style="width:6%;" bgcolor="'.$color.'" align=center>
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
          else{ /// VISTA PARA REPORTES
            $tabla.='
            <td style="width:4.5%;" align=center>
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:90%;" align=center>
                <tr>
                  <td style="width:50%; font-size: 8px;"><b>P.</b></td>
                  <td style="width:50%;font-size: 8.5px;" align=right><b>'.$valor[1].'</b></td>
                </tr>
                <tr>
                  <td style="width:50%; font-size: 8px;"><b>E.</b></td>
                  <td style="font-size: 8.5px;" align=right><b>'.$valor[2].'</b></td>
                </tr>
              </table>
            </td>';
          }

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


    /*-- ARMANDO TEMPORALIDAD PARA OBJETIVOS REGIONAL POR REGIONAL (ACUMULADO) --*/
    public function get_temporalidad_acumulado_objetivo_regional($or_id,$tp_rep){
      /// tp_rep=0 normal
      /// tp_rep=1 Reporte

      $valor=$this->tabla_trimestral_acumulado_x_oregional($or_id);
      $tabla='';

          if($tp_rep==0){ /// VISTA NORMAL
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:6%;" align=center>
                <table class="table table-bordered" border=0.2 style="width:80%;">
                  <tr>
                    <td style="width:50%;"><b>(%) PROG.</b></td>
                    <td style="width:50%;font-size: 12px; color:blue" align=right><b>'.$valor[5][$i].'%</b></td>
                  </tr>
                  <tr>
                    <td><b>(%) EJEC.</b></td>
                    <td style="font-size: 12px; color:blue" align=right><b>'.$valor[6][$i].'%</b></td>
                  </tr>
                </table>
              </td>';
            }
          }
          else{ /// VISTA PARA REPORTES
            $tabla.='
            <td style="width:4.5%;" align=center>
              <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:90%;" align=center>
                <tr>
                  <td style="width:50%; font-size: 8px;"><b>%P.</b></td>
                  <td style="width:50%;font-size: 8.5px;" align=right><b>'.$valor[5][$i].'%</b></td>
                </tr>
                <tr>
                  <td style="width:50%; font-size: 8px;"><b>%E.</b></td>
                  <td style="font-size: 8.5px;" align=right><b>'.$valor[2][$i].'%</b></td>
                </tr>
              </table>
            </td>';
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

        //  $text=strval( $suma_prog);
          $valor[1]=$suma_prog; /// Programado Acumulado
          $valor[2]=$suma_ejec; /// Ejecutado Acumulado
          $valor[3]=$ejecucion; /// Ejecucion
      }

      return $valor; 
    }


    /*-- GENERA TABLA PARA EVALUACION TRIMESTRAL POR OBJETIVO REGIONAL --*/
    public function tabla_trimestral_acumulado_x_oregional($or_id){

        for ($i=1; $i <=4 ; $i++) { 
          $valor=$this->calificacion_trimestral_acumulado_x_oregional($or_id,$i);
          $matriz[1][$i]=$valor[1];  /// prog
          $matriz[2][$i]=$valor[2];  /// ejec
          $matriz[3][$i]=$valor[3];  /// % cumplimiento trimestral
          $matriz[4][$i]=(100-$valor[3]);  /// % no cumplido
        }

        $total=$matriz[1][4];

        for ($i=1; $i <=4 ; $i++) { 
          $matriz[5][$i]=round((($matriz[1][$i]/$total)*100),2);  /// % Programado con respecto al total acumulado
          $matriz[6][$i]=round((($matriz[2][$i]/$total)*100),2);  /// % Ejecutado con respecto al total acumulado
        }

      return $matriz;
    }
    
    /*-- UPDATE TEMPORALIDAD PARA OBJETIVOS REGIONAL POR REGIONAL --*/
    public function create_temporalidad_oregional($dep_id){
      $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);
      foreach($lista_ogestion as $row){

        if($row['indi_id']==1){
          $denominador=1;
          $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
        }
        else{
          $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional_recurrentes($row['or_id']);
          $denominador=3;
        }


        
        if(count($metas_prior)!=0){
            if(round($row['or_meta'],2)==round($metas_prior[0]['meta_prog_actividades'],2)) { /// META == META ACUMULADO FORN 4
               /// Crear o actualizar la tabla de temporalidad
              if(count($this->model_objetivoregion->verif_temporalidad_oregional($row['or_id']))!=0){
                $this->db->where('or_id', $row['or_id']);
                $this->db->delete('temp_trm_prog_objetivos_regionales');
              }

              if($row['indi_id']==2){
                $denominador=$metas_prior[0]['nro']*3;
              }


              /// creamos registro
                for ($i=1; $i <=4 ; $i++) { 
                  $get_dato_trimestre=$this->model_objetivoregion->get_suma_trimestre_para_oregional($row['or_id'],$i);
                  if(count($get_dato_trimestre)!=0){
                      /*--------------------------------------------------------*/
                      $data_to_store2 = array( ///// Tabla temp prog oregional
                        'or_id' => $row['or_id'], /// or id
                        'trm_id' => $i, /// trimestre
                        'pg_fis' => ($get_dato_trimestre[0]['trimestre']/$denominador), /// valor
                        'g_id' => $this->gestion, /// gestion                
                      );
                      $this->db->insert('temp_trm_prog_objetivos_regionales', $data_to_store2);
                      /*----------------------------------------------------------*/
                  }
                }
              
            }
            else{ /// ELIMINAMOS LA TEMPORALIDAD
              $this->db->where('or_id', $row['or_id']);
              $this->db->delete('temp_trm_prog_objetivos_regionales');
            }
        }
      }

    }



    //// LISTA DE ACTIVIDADES PRIORIZADOS POR OBJ REGIONAL
    public function get_mis_form4_priorizados_x_oregional($or_id,$tp_rep){
      /// tp rep 0: normal
      /// tprep 1: reporte 
      $tabla='';
      $tab='';
      $titulo='';
      $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($or_id);
      $regional=$this->model_proyecto->get_departamento($detalle_oregional[0]['dep_id']);

        $meta='';
        if($detalle_oregional[0]['indi_id']==2){
          $meta='%';
        }

      if($tp_rep==0){
        $tabla='<script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';
        $tab='id="dt_basic" class="table table-bordered" border=0.2 style="width:100%;"';
        $size_th='font-size: 11px;';
        $size_td='font-size: 10.5px;';
        $size_meta='font-size: 16px;';
      }
      elseif($tp_rep==1){
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center';
        $size_th='font-size: 6.7px;';
        $size_td='font-size: 6.5px;';
        $size_meta='font-size: 7px;';
        $titulo='

        <b style="font-family:Arial;font-size: 10px;height:10px;>ACTIVIDADES PRIORIZADOS</b><br>
        <div style="font-family:Arial;font-size: 10px;height:8px;">
          OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['og_codigo'].'.'.$detalle_oregional[0]['or_codigo'].'. '.$detalle_oregional[0]['or_objetivo'].'<br>
          META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).' '.$meta.'<br>
        </div><br>';
      }

        $form4=$this->model_objetivoregion->get_lista_form_priorizados_x_oregional($or_id);
        $tabla.='
        
        '.$titulo.'
        <div class="table-responsive">
        <table '.$tab.'>
          <thead>
            <tr style="'.$size_th.'" bgcolor="#eceaea" >
              <th style="width:1%;height:10px; text-align: center">#</th>
              <th style="width:5%; text-align: center"><b>PROGRAMA</b></th>
              <th style="width:11%; text-align: center"><b>GASTO CORRIENTE / PROY. INVERSIÓN</b></th>
              <th style="width:9%; text-align: center"><b>UNIDAD RESPONSABLE</b></th>
              <th style="width:2%; text-align: center">COD. ACT.</th>
              <th style="width:11%; text-align: center">ACTIVIDAD</th>
              <th style="width:10%; text-align: center">RESULTADO</th>
              <th style="width:7%; text-align: center">RESPONSABLE</th>
              <th style="width:9%; text-align: center">INDICADOR</th>
              <th style="width:3%; text-align: center">META</th>
              <th style="width:2%; text-align: center">ENE.</th>
              <th style="width:2%; text-align: center">FEB.</th>
              <th style="width:2%; text-align: center">MAR.</th>
              <th style="width:2%; text-align: center">ABR.</th>
              <th style="width:2%; text-align: center">MAY.</th>
              <th style="width:2%; text-align: center">JUN.</th>
              <th style="width:2%; text-align: center">JUL.</th>
              <th style="width:2%; text-align: center">AGO.</th>
              <th style="width:2%; text-align: center">SEPT.</th>
              <th style="width:2%; text-align: center">OCT.</th>
              <th style="width:2%; text-align: center">NOV.</th>
              <th style="width:2%; text-align: center">DIC.</th>
              <th style="width:8%; text-align: center">MEDIO DE VERIFICACIÓN</th>
            </tr>
          </thead>
          <tbody>';
        
        $nro=0;$suma_meta=0;
        foreach($form4 as $row){
          $nro++;
          $suma_meta=$suma_meta+$row['prod_meta'];
          $tp_indi='';
          if($row['indi_id']==2){
            $tp_indi='%';
          }

          $tabla.='
          <tr style="'.$size_td.'">
            <td style="width:1%; height:5px;" align=center title='.$row['prod_id'].' bgcolor="#f9fdfc">'.$nro.'</td>';
              if($row['tp_id']==1){
                $tabla.='
                <td style="width:5%;" bgcolor="#f9fdfc"><b>'.$row['aper_programa'].' '.$row['proy_sisin'].' '.$row['aper_actividad'].'</b></td>
                <td style="width:11%;" bgcolor="#f9fdfc"><b>'.$row['proy_nombre'].'</b></td>';
              }
              else{
                $tabla.='
                <td style="width:5%;" bgcolor="#f9fdfc"><b>'.$row['aper_programa'].' '.$row['aper_proyecto'].' '.$row['aper_actividad'].'</b></td>
                <td style="width:11%;" bgcolor="#f9fdfc"><b>'.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'</b></td>';
              }
            $tabla.='
            
            <td style="width:9%;" bgcolor="#f9fdfc"><b>'.$row['serv_cod'].' .- '.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</b></td>
            <td style="width:2%; '.$size_meta.'" align=center><b>'.$row['prod_cod'].'</b></td>
            <td style="width:11%;">'.$row['prod_producto'].'</td>
            <td style="width:10%;">'.$row['prod_resultado'].'</td>
            <td style="width:7%;">'.$row['prod_unidades'].'</td>
            <td style="width:9%;">'.$row['prod_indicador'].'</td>
            <td style="width:3%; '.$size_meta.'" align=right><b>'.round($row['prod_meta'],2).''.$tp_indi.'</b></td>
            '.$this->genera_temporalidad_form4($row['prod_id'],$tp_indi).'
            <td style="width:8%;">'.$row['prod_fuente_verificacion'].'</td>
          </tr>';
        }

        if($row['indi_id']==2){
            $suma_meta=($suma_meta/count($form4));
        }
        
        $tabla.='
          </tbody>
          <tr>
            <td style="height:10px;"colspan=9 align=right><b>META PRIORIZADO : </b></td>
            <td style="width:2%; height:5px;'.$size_meta.'" align=right><b>'.round($suma_meta,2).''.$tp_indi.'</b></td>
            <td colspan=13></td>
          </tr>
        </table>
        </div>';

      return $tabla;
    }

    /// arma temoralidad de formulario N 4
    public function genera_temporalidad_form4($prod_id,$tp_indi){
      $tabla='';

      if(count($this->model_producto->programado_producto($prod_id))!=0){
        for ($i=1; $i <=12 ; $i++) { 
          $mes_prog=$this->model_producto->get_mes_programado_form4($prod_id,$i);
          $mes=0;
          if(count($mes_prog)!=0){
            $mes=$mes_prog[0]['pg_fis'];
          }

          $color='';
          if($i<=($this->tmes*3)){
            $color='#ecfbf9';
          }
          $tabla.='<td align=right bgcolor="'.$color.'"><b>'.round($mes,2).''.$tp_indi.'</b></td>';
        }
      }
      else{
        for ($i=1; $i <=12 ; $i++) { 
          $tabla.='<td align=right> - </td>';
        }
      }

      return $tabla;
    }




//// ======== REPORTE FORMULARIO N2
  //// Cabecera Reporte form2
  public function cabecera_form2($regional){ 
    $tabla='';
       $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
              <td style="width:70%;height: 2%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                      <tr style="font-size: 15px;font-family: Arial;">
                          <td style="width:45%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                      </tr>
                      <tr>
                          <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                      </tr>
                  </table>
              </td>
              <td style="width:30%; height: 2%; font-size: 8px;text-align:right;">
                '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              </td>
          </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px black; text-align: center;">
                <td style="width:10%; text-align:center;">
                </td>
                <td style="width:80%; height: 5%">
                  <table align="center" border="0" style="width:100%;">
                    <tr style="font-size: 23px;font-family: Arial;">
                      <td style="height: 32%;"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                    </tr>
                    <tr style="font-size: 20px;font-family: Arial;">
                      <td style="height: 5%;">OPERACIONES - '.strtoupper($regional[0]['dep_departamento']).'</td>
                    </tr>
                  </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:70%;">
                </td>
                <td style="width:30%; height: 3%">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                      <td align=center style="width:100%;height: 10%;"><b>EVALUACI&Oacute;N FORMULARIO SPO N° 2 </b></td>
                    </tr>
                  </table>
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
           <tr>
              <td style="width:2%;"></td>
              <td style="width:96%;height: 1%;">
                <hr>
              </td>
              <td style="width:2%;"></td>
          </tr>
        </table>';

    return $tabla;
  }




     //// Lista de OPeraciones Regional form 2
  public function rep_lista_form2($dep_id){ 
    $tabla='';
    $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);

    $tabla.='
    <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
      <thead>
        <tr style="font-size: 6.7px;" bgcolor="#eceaea" align=center>
          <th style="width:0.9%;height:20px;">#</th>
          <th style="width:2.2%;"><b>COD. ACE.</b></th>
          <th style="width:2.2%;"><b>COD. ACP.</b></th>
          <th style="width:2.2%;"><b>COD. OPE.</b></th>
          <th style="width:12%;">OPERACI&Oacute;N REGIONAL '.$this->gestion.'</th>
          <th style="width:12%;">PRODUCTO</th>
          <th style="width:12%;">RESULTADO</th>
          <th style="width:12%;">INDICADOR</th>
          <th style="width:4%;">META</th>
          <th style="width:4.5%;">I TRIM.</th>
          <th style="width:4.5%;">II. TRIM.</th>
          <th style="width:4.5%;">III. TRIM.</th>
          <th style="width:4.5%;">IV. TRIM.</th>
          <th style="width:8%;">CALIFICACIÓN</th>
          <th style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</th>
        </tr>
      </thead>
      <tbody>';
    $nro=0;$monto_total=0;
    foreach($lista_ogestion as $row){
      $calificacion=$this->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
      $nro++;
      $tabla.='
      <tr style="font-size: 6.5px;">
        <td style="width:0.9%; height:18px;" align=center>'.$nro.'</td>
        <td style="width:2.2%;" align="center"><b>'.$row['acc_codigo'].'</b></td>
        <td style="width:2.2%; font-size: 8px;" align="center"><b>'.$row['og_codigo'].'</b></td>
        <td style="width:2.2%; font-size: 8px;" align="center"><b>'.$row['or_codigo'].'</b></td>
        <td style="width:12%;">'.$row['or_objetivo'].'</td>
        <td style="width:12%;">'.$row['or_producto'].'</td>
        <td style="width:12%;">'.$row['or_resultado'].'</td>
        <td style="width:12%;">'.$row['or_indicador'].'</td>
        <td style="width:4%; font-size: 8px;" align=center><b>'.round($row['or_meta'],2).'</b></td>
        '.$this->get_temporalidad_objetivo_regional($row['or_id'],1).'
        <td style="font-family:Arial;font-size: 8px;" align=right><b>'.$calificacion[3].'%</b></td>
        <td style="width:10%;">'.$row['or_verificacion'].'</td>
      </tr>';
    }
    $tabla.='
      </tbody>
    </table>';

    return $tabla;
  }



  //// Lista de Formulario N° 4 priorizados por Operaciones Regional form 2
  public function rep_lista_form4_priorizados($or_id,$tp_rep){ 
   
    $priorizados=$this->get_mis_form4_priorizados_x_oregional($or_id,$tp_rep);
 //   $lista_ogestion=$this->model_objetivogestion->get_list_ogestion_por_regional($dep_id);

/*    $tabla.='
    <table cellpadding="0" cellspacing="0" class="tabla" border=0.1 style="width:100%;" align=center>
      <thead>
        <tr style="font-size: 6.7px;" bgcolor="#eceaea" align=center>
          <th style="width:0.9%;height:20px;">#</th>
          <th style="width:2.2%;"><b>COD. ACE.</b></th>
          <th style="width:2.2%;"><b>COD. ACP.</b></th>
          <th style="width:2.2%;"><b>COD. OPE.</b></th>
          <th style="width:12%;">OPERACI&Oacute;N REGIONAL '.$this->gestion.'</th>
          <th style="width:12%;">PRODUCTO</th>
          <th style="width:12%;">RESULTADO</th>
          <th style="width:12%;">INDICADOR</th>
          <th style="width:4%;">META</th>
          <th style="width:4.5%;">I TRIM.</th>
          <th style="width:4.5%;">II. TRIM.</th>
          <th style="width:4.5%;">III. TRIM.</th>
          <th style="width:4.5%;">IV. TRIM.</th>
          <th style="width:8%;">CALIFICACIÓN</th>
          <th style="width:10%;">MEDIO DE VERIFICACI&Oacute;N</th>
        </tr>
      </thead>
      <tbody>';
    $nro=0;$monto_total=0;
    foreach($lista_ogestion as $row){
      $calificacion=$this->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
      $nro++;
      $tabla.='
      <tr style="font-size: 6.5px;">
        <td style="width:0.9%; height:18px;" align=center>'.$nro.'</td>
        <td style="width:2.2%;" align="center"><b>'.$row['acc_codigo'].'</b></td>
        <td style="width:2.2%; font-size: 8px;" align="center"><b>'.$row['og_codigo'].'</b></td>
        <td style="width:2.2%; font-size: 8px;" align="center"><b>'.$row['or_codigo'].'</b></td>
        <td style="width:12%;">'.$row['or_objetivo'].'</td>
        <td style="width:12%;">'.$row['or_producto'].'</td>
        <td style="width:12%;">'.$row['or_resultado'].'</td>
        <td style="width:12%;">'.$row['or_indicador'].'</td>
        <td style="width:4%; font-size: 8px;" align=center><b>'.round($row['or_meta'],2).'</b></td>
        '.$this->get_temporalidad_objetivo_regional($row['or_id'],1).'
        <td style="font-family:Arial;font-size: 8px;" align=right><b>'.$calificacion[3].'%</b></td>
        <td style="width:10%;">'.$row['or_verificacion'].'</td>
      </tr>';
    }
    $tabla.='
      </tbody>
    </table>';*/

    return $priorizados;
  }




  //// Pie Regional form 2
  public function pie_form2($regional){ 
    $tabla='';
    $tabla.='
    <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:97.5%;" align="center">
          <tr>
            <td style="width: 33.3%;">
              <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>';
                  if($regional[0]['dep_id']!=10){
                      $tabla.='<td style="width:100%;height:1.4%"><b>JEFATURA DE SERVICIOS GENERALES</b></td>';
                  }
                  else{
                      $tabla.='<td style="width:100%;height:1.4%"><b>GERENCIA ADMINISTRATIVA FINANCIERA</b></td>';
                  }
                $tabla.='
                </tr>
                <tr>
                    <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                </tr>
              </table>
            </td>
            <td style="width: 33.3%;">
              <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>';
                  if($regional[0]['dep_id']!=10){
                      $tabla.='<td style="width:100%;height:1.4%"><b>JEFATURA MEDICA</b></td>';
                  }
                  else{
                      $tabla.='<td style="width:100%;height:1.4%"><b>GERENCIA DE SERVICIOS DE SALUD</b></td>';
                  }
                $tabla.='
                </tr>
                <tr>
                  <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                </tr>
              </table>
            </td>
            <td style="width: 33.3%;">
              <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>';
                  if($regional[0]['dep_id']!=10){
                    $tabla.='<td style="width:100%; height:1.4%"><b>ADMINISTRADOR REGIONAL</b></td>';
                  }
                  else{
                    $tabla.='<td style="width:100%; height:1.4%"><b>GERENCIA GENERAL</b></td>';
                  }
                $tabla.='
                </tr>
                <tr>
                  <td align=center><br><br><br><br><br><br><b>FIRMA</b></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="width: 33%; height:18px;text-align: left">
              POA - '.$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa').'
            </td>
            <td style="width: 33%; text-align: center">
              '.$this->session->userdata('sistema').'
            </td>
            <td style="width: 33%; text-align: right">
              '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
            </td>
          </tr>
      </table>';

    return $tabla;
  }

  //// Pie Regional act priorizados
  public function pie_form4_priorizados(){ 
    $tabla='';
    $tabla.='
    <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:97.5%;" align="center">
          <tr>
            <td style="width: 33%; height:18px;text-align: left">
              POA - '.$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa').'
            </td>
            <td style="width: 33%; text-align: center">
              '.$this->session->userdata('sistema').'
            </td>
            <td style="width: 33%; text-align: right">
              '.$this->session->userdata('funcionario').' - pag. [[page_cu]]/[[page_nb]]
            </td>
          </tr>
      </table>';

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


    public function get_mes($mes_id){
      $mes[1]='ENERO';
      $mes[2]='FEBRERO';
      $mes[3]='MARZO';
      $mes[4]='ABRIL';
      $mes[5]='MAYO';
      $mes[6]='JUNIO';
      $mes[7]='JULIO';
      $mes[8]='AGOSTO';
      $mes[9]='SEPTIEMBRE';
      $mes[10]='OCTUBRE';
      $mes[11]='NOVIEMBRE';
      $mes[12]='DICIEMBRE';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }
}
?>