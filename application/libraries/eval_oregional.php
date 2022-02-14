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
      $tabla.='
        <div id="row">
          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="alert alert-info" role="alert">
              <a href="#" data-toggle="modal" data-target="#modal_update_temporalidad" class="btn btn-primary update_temporalidad" style="width:20%;" name="'.$dep_id.'" id="'.strtoupper($departamento[0]['dep_departamento']).'" title="ACTUALIZAR EVALUACION OBJETIVO REGIONAL" ><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="30"/>&nbsp;ACTUALIZAR TEMPORALIDAD - OBJETIVO REGIONAL</a>    
            </div>
          </article>
        </div>';
      $tabla.=' 
      <div align="right">
        <a href="javascript:abreVentana(\''.site_url("").'/eval_obj/rep_meta_oregional/'.$dep_id.'\');" title="REPORTE EVALUACIÓN META REGIONAL" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>EVALUACI&Oacute;N METAS REGIONALES (.PDF)</b></a>&nbsp;&nbsp;
        <a href="#" data-toggle="modal" data-target="#modal_evaluacion" name="'.$dep_id.'" class="btn btn-default evaluacion" title="MOSTRAR CUADRO DE EVALUACIÓN DE METAS"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;<b>CUADRO DE EVALUACI&Oacute;N (GRAFICO)</b></a>
      </div><br>
      <input name="base" type="hidden" value="'.base_url().'">
      <table class="table table-bordered" border=0.2 style="width:100%;">
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
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">META (I) TRIMESTRE</th>
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">META (II) TRIMESTRE</th>
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">META (III) TRIMESTRE</th>
          <th style="width:10%;color:#FFF; text-align: center" bgcolor="#1c7368">META (IV) TRIMESTRE</th>
          <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
          <th style="width:5%;color:#FFF;" bgcolor="#1c7368"></th>
        </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($lista_ogestion as $row){
          $color='';
          $meta_priorizado=0;
          $metas_prior=$this->model_objetivoregion->get_suma_meta_form4_x_oregional($row['or_id']);
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
            <td style="width:1%; height:10px;" align=center title='.$row['pog_id'].'>'.$nro.' '.$row['og_id'].'</td>
            <td style="width:2%;" align="center">'.$row['acc_codigo'].'</td>
            <td style="width:2%;" align="center">'.$row['og_codigo'].'</td>
            <td style="width:2%; font-size: 11px;" align="center" bgcolor="#f1eeee" title='.$row['or_id'].'><b>'.$row['or_codigo'].'</b></td>
            <td style="width:11%;">'.$row['or_objetivo'].'</td>
            <td style="width:11%;">'.$row['or_resultado'].'</td>
            <td style="width:10%;">'.$row['or_indicador'].'</td>
            <td style="width:10%;">'.$row['or_verificacion'].'</td>
            <td style="width:2%; font-size: 15px;" align=center><b>'.round($row['or_meta'],2).'</b></td>
            <td style="width:2%;">'.$boton_ajustar_apriorizados.'</td>';
            
            
            $tabla.='
          </tr>';
        }
        $tabla.='
        </tbody>
      </table> ';

      return $tabla;
    }


    /*--- GET TEMPORALIDAD PROGRAMADO TRIMESTRAL OBJETIVO REGIONAL ---*/
/*    public function get_temporalidad_objetivo_regional($or_id){
      $tabla='';

      $tabla.='<table border=1>';
      for ($i=1; $i <=4 ; $i++) { 
        $tabla.='
        <>';
      }
      $tabla.='</table>';

      return $tabla;
    }*/












    /*--- GET SUMA TOTAL EVALUADO ---*/
    public function get_suma_total_evaluado($pog_id){
      $sum=0;
      for ($i=1; $i <=$this->tmes; $i++) { 
        $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral($i,$pog_id);
        if(count($obj_gestion_evaluado)!=0){
          $sum=$sum+$obj_gestion_evaluado[0]['ejec_fis'];
        }
      }

      return $sum;
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