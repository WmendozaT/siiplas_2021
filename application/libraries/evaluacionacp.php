<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// EVALUACION POA REGIONAL, DISTRITAL 
class Evaluacionacp extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');
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
    }


    //// LISTADO DE REGIONALES
    public function listado_regionales(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $tabla.='
          <input name="base" type="hidden" value="'.base_url().'">
          <input name="gestion" type="hidden" value="'.$this->gestion.'">
          <article class="col-sm-12">
            <div class="well">
              <form class="smart-form">
                  <header><b>CONSOLIDADO EVALUACI&Oacute;N A.C.P. - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label"><b>DIRECCIÓN ADMINISTRATIVA</b></label>
                        <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                        <option value="">Seleccione Regional ....</option>
                        <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                        foreach($regionales as $row){
                          if($row['dep_id']!=0){
                            $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                          }
                        }
                        $tabla.='
                        </select>
                      </section>
                    </div>
                  </fieldset>
              </form>
            </div>
          </article>';
    return $tabla;
  }




 /*------- CABECERA REPORTE SEGUIMIENTO ACP (GRAFICO)------*/
  function cabecera_reporte_grafico(){
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
                    <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                  </tr>
                  <tr style="font-size: 20px;font-family: Arial;">
                    <td style="height: 5%; text-align:center">EVALUACIÓN DE ACCIONES DE CORTO PLAZO</td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }


    ///==================== PRIMER CUADRO
    /*--- Tabla Evaluacion Meta a nivel Institucional ---*/
    public function tabla_evaluacion_meta_institucional(){
      $lista_ogestion=$this->model_objetivogestion->list_objetivosgestion_general();
      $nro=0;
      foreach($lista_ogestion as $row){
        $suma_mevaluado=$this->get_suma_total_evaluado_institucional($row['og_id']);
        $nro++;
        $tab[$nro][0]=$row['og_id'];
        $tab[$nro][1]='ACP. '.$row['og_codigo'];
        $tab[$nro][2]=$row['og_objetivo'];
        $tab[$nro][3]=$row['og_resultado'];
        $tab[$nro][4]=round($row['og_meta'],2);

        if($row['indi_id']==1){
          $tab[$nro][5]=round($suma_mevaluado,2);
          $tab[$nro][6]=round((($suma_mevaluado/$row['og_meta'])*100),2);
        }
        else{
          $sum_prog=$this->model_objetivogestion->get_suma_temporalidad_ogestion($row['og_id']);
          $tab[$nro][6]=round((($suma_mevaluado/$sum_prog[0]['meta_relativo'])*100),2);
          $tab[$nro][5]=round($tab[$nro][6],2);
        }
        
      }

      return $tab;
    }



    /*--- GET SUMA TOTAL EVALUADO INSTITUCIONAL ---*/
    public function get_suma_total_evaluado_institucional($og_id){
      $sum=0;
        for ($i=1; $i <=$this->tmes; $i++) { 
          $obj_gestion_evaluado=$this->model_objetivogestion->get_objetivo_programado_evaluado_trimestral_institucional($i,$og_id);
          if(count($obj_gestion_evaluado)!=0){
            $sum=$sum+$obj_gestion_evaluado[0]['evaluado'];
          }
        }

      return $sum;
    }


/*--- MATRIZ EVALUACION DE METAS ACP REGIONAL 2022 ---*/
  public function matriz_evaluacion_meta_acp_regional($dep_id){
    $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
    $nro=0;
    foreach($acp_regional as $row){
      $suma_mevaluado=$this->get_suma_total_evaluado($row['pog_id']);
      $nro++;
      $tab[$nro][0]=$row['pog_id']; /// pog_id
      $tab[$nro][1]='<b>ACP. '.$row['og_codigo'].'</b>'; /// codigo
      $tab[$nro][2]=$row['og_objetivo']; /// descripcion
      $tab[$nro][3]=$row['indi_id']; /// indi id
      $tab[$nro][4]=round($row['prog_fis'],2); /// meta acp regional
      $tab[$nro][5]=$suma_mevaluado; /// ejecutado

      $tab[$nro][6]=0; /// % cumplimiento
      if($tab[$nro][4]!=0){
        $tab[$nro][6]=round((($tab[$nro][5]/$tab[$nro][4])*100),2);
      }
    }

    return $tab;
  }


  //// Matriz Grado de Cumplimiento de acp por Regional
  public function matriz_gcumplimiento($acp,$nro){
    $cumplido=0;$proceso=0;$ncumplido=0;
    for ($i=1; $i <=$nro ; $i++) {
      if($acp[$i][4]==$acp[$i][5]){
        $cumplido++;
      }
      elseif(($acp[$i][5]<$acp[$i][4]) & $acp[$i][5]!=0){
        $proceso++;
      }
      elseif ($acp[$i][5]==0) {
        $ncumplido++;
      }
    }

    $matriz[1]=$nro;
    $matriz[2]=$cumplido;
    $matriz[3]=$proceso;
    $matriz[4]=$ncumplido;
    $matriz[5]=round((($cumplido/$nro)*100),2); // % cumplidos
    $matriz[6]=round((($proceso/$nro)*100),2); // % proceso
    $matriz[7]=round((($ncumplido/$nro)*100),2); // % no cumplido

    return $matriz;
  }


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

    /*--- DETALLE ACP REGIONAL ---*/
    public function detalle_acp($eval,$nro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// normal
        $font_size='style="font-size: 9px;"';
        $tab='class="table table-bordered" align=center style="width:90%;"';
      }
      else{ /// impresion
        $font_size='style="font-size: 8px;"';
        $tab='cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center';
      }

      $tabla.='
          <table '.$tab.'>
            <thead>
              <tr align=center bgcolor="#f1eeee">
                <th></th>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<th><b>'.$eval[$i][1].'</b></th>';
                }
                $tabla.='
                </tr>
            </thead>
            <tbody>
              <tr>
                <td align=left><b>META</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right>'.$eval[$i][4].'</td>';
                }
                $tabla.='
              </tr>
              <tr>
                <td align=left><b>EJEC.</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right>'.$eval[$i][5].'</td>';
                }
                $tabla.='
              </tr>
              <tr>
                <td align=left><b>% CUMP.</b></td>';
                for ($i=1; $i <=$nro ; $i++) { 
                  $tabla.='<td align=right><b>'.$eval[$i][6].' %</b></td>';
                }
                $tabla.='
              </tr>
            </tbody>
        </table>';

      return $tabla;
    }


 /*--- Tabla cuadro de evaluacion ---*/
    public function tabla_gcumplimiento($matriz,$tp_cuadro,$tp_rep){
      $tabla='';
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $color='#e9edec';
      } 
      else{ /// Impresion
        $tab='class="change_order_items" border=1 align=center style="width:100%;"';
        $color='#e9edec';
      }

      if($tp_cuadro==1){ /// Cuadro cumplido,no cumplido
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>Nro. A.C.P.</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.($matriz[3]+$matriz[4]).'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }
      else{ /// cuadro completo
        $tabla.='
        <table '.$tab.'>
          <thead>
              <tr align=center bgcolor='.$color.'>
                <th>Nro. A.C.P.</th>
                <th>TOTAL EVALUADAS</th>
                <th>CUMPLIDAS</th>
                <th>EN PROCESO</th>
                <th>NO CUMPLIDAS</th>
                <th>% CUMPLIDAS</th>
                <th>% NO CUMPLIDAS</th>
                </tr>
              </thead>
            <tbody>
              <tr align=right>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[1].'</b></td>
                <td><b>'.$matriz[2].'</b></td>
                <td><b>'.$matriz[3].'</b></td>
                <td><b>'.$matriz[4].'</b></td>
                <td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$matriz[5].'%</b></button></td>
                <td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.($matriz[6]+$matriz[7]).'%</b></button></td>
              </tr>
            </tbody>
        </table>';
      }

      return $tabla;
    }







  /*------ NOMBRE MES -------*/
  public function mes_nombre_completo(){
      $mes[1] = 'ENERO';
      $mes[2] = 'FEBRERO';
      $mes[3] = 'MARZO';
      $mes[4] = 'ABRIL';
      $mes[5] = 'MAYO';
      $mes[6] = 'JUNIO';
      $mes[7] = 'JULIO';
      $mes[8] = 'AGOSTO';
      $mes[9] = 'SEPTIEMBRE';
      $mes[10] = 'OCTUBRE';
      $mes[11] = 'NOVIEMBRE';
      $mes[12] = 'DICIEMBRE';

    return $mes;
  }

  /*------ NOMBRE MES -------*/
  public function mes_nombre(){
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


      /*================================= GENERAR MENU ====================================*/
    function menu($mod){
      $enlaces=$this->menu_modelo->get_Modulos($mod);
      for($i=0;$i<count($enlaces);$i++) {
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
    /*--------------------------------------------------------------------------------*/
    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }
    /*======================================================================================*/
}
?>