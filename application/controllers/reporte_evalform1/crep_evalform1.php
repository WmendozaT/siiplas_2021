<?php
/*controlador para evaluacion ACP GESTION 2022*/
class Crep_evalform1 extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->load->model('mestrategico/model_objetivogestion');
            $this->load->model('mestrategico/model_objetivoregion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->trimestre = $this->model_evaluacion->trimestre();
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('eval_acp');
        }
        else{
            redirect('/','refresh');
        }
    }

  /// MENU EVALUACIÓN POA FORM 1
  public function menu_eval_acp(){
    $data['menu']=$this->eval_acp->menu(7); //// genera menu
    $data['trimestre']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
    $matriz=$this->matriz_cumplimiento_form1_institucional();
    $detalle_acp=$this->detalle_cumplimiento_form1_institucional($matriz,0); /// Detalle de Form1 Alineados Vista
    $detalle_acp_impresion=$this->detalle_cumplimiento_form1_institucional($matriz,1); /// Detalle de Form1 Alineados Impresion

    $data['matriz']=$matriz; /// matriz para el grafico de evaluacion ACP
    $data['nro']=count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); /// nro de ACP alineados
    
    $tabla='';
    $tabla.=' 
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">
        <div id="cabecera" style="display: none">'.$this->eval_acp->cabecera_reporte_grafico().'</div>
        <form class="smart-form">
         
          <fieldset>   
            <div>'.$this->calificacion_form1_institucional(0).'</div>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <center>
                  <div id="grafico1" style="width: 900px; height: 580px; margin: 10px auto; text-align:center"></div>
                </center>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                <br>
                <h4><b>DETALLE EJECUCION ACCIONES DE CORTO PLAZO '.$this->gestion.'</b></h4>
                <br>
                  <hr>
                  '.$detalle_acp.'
                  <hr>
                  
                  <div id="tabla_impresion_detalle" style="display: none">
                    '.$detalle_acp_impresion.'
                  </div>

              </div>
            </div>
          </fieldset>
        </form>';
    $data['informacion']=$tabla;
    

   $this->load->view('admin/reportes_cns/repevaluacion_form1/rep_menu', $data);
  }


  //// Calificacion Cumplimiento ACP Institucional
  public function calificacion_form1_institucional($tp_rep){
    $prog=$this->model_objetivogestion->get_suma_total_programado_alineado_form1_institucional();
    $ejec=$this->model_objetivogestion->get_suma_total_ejecutado_alineado_form1_institucional();
    $form1_prog=0; $form2_ejec=0;
    $tabla='';

    if(count($ejec)!=0){
      $form1_ejec=$ejec[0]['ejecutado'];
    }

    $eficacia=0;
    if(count($prog)!=0 & $prog[0]['programado_total']!=0){
      $eficacia=round((($form1_ejec/$prog[0]['programado_total'])*100),2);
    }

    $tp='danger';
    $titulo='ERROR EN LOS VALORES';
    if($eficacia<=50){$tp='danger';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> INSATISFACTORIO (0% - 50%)';} /// Insatisfactorio - Rojo
    if($eficacia > 50 & $eficacia <= 75){$tp='warning';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> REGULAR (51% - 75%)';} /// Regular - Amarillo
    if($eficacia > 75 & $eficacia <= 99){$tp='info';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> BUENO (76% - 99%)';} /// Bueno - Azul
    if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='CUMPLIMIENTO : '.$eficacia.'% -> OPTIMO (100%)';} /// Optimo - verde

    $tabla.='<h5 class="alert alert-'.$tp.'" style="font-family: Arial;" align="center"><b>'.$titulo.'</b></h5>';

    return $tabla;
  }

  //// Detalle Ejecucion ACP Institucional 
  public function detalle_cumplimiento_form1_institucional($matriz,$tp_rep){
    /// tp_rep : 0 (vista)
    /// tp_rep : 1 (impresion)
    $tabla='';

    if($tp_rep==0){
    $tabla.='
    <center>
        <style>
          table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            font-family: Arial;
          }
          th{
            padding: 1.4px;
            text-align: center;
            font-size: 10px;
          }
        </style>
      <table class="table table-dark table-borderless" border=0.2 style="width:90%;">
        <thead>
          <tr>
            <th style="width:10%;"></th>
            <th style="width:60%;">DETALLE A.C.P. INSTITUCIONAL</th>
            <th style="width:10%;">PROGRAMADO</th>
            <th style="width:10%;">EJECUTADO</th>
            <th style="width:10%;">(%) CUMPLIMIENTO</th>
          </tr>
        </thead>
        <tbody>';
      for ($i=0; $i < count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); $i++) { 
        $tabla.='<tr>';
        for ($j=0; $j < 5; $j++) { 
          if($j==0){
            $tabla.='<td style="font-size:13px; text-align:center">'.$matriz[$i][$j].'</td>';
          }
          elseif($j==2 || $j==3){
            $tabla.='<td style="font-size:12px; text-align:right">'.$matriz[$i][$j].'</td>';  
          }
          elseif($j==4){
            $tabla.='<td style="font-size:13px; text-align:right"><b>'.$matriz[$i][$j].' %</b></td>';  
          }
          else{
            $tabla.='<td>'.$matriz[$i][$j].'</td>';
          }
        }
        $tabla.='</tr>';
      }
      $tabla.='
        </tbody>
      </table>
    </center>';
    }
    else{
      $tabla.='
      <center>
          <style>
            table{font-size: 10px;
              font-family: Arial;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
          </style>
        <table cellpadding="0" cellspacing="0" class="tabla" border="0.5" style="width:100%;" align=center>
          <thead>
            <tr>
              <th style="width:10%;"></th>
              <th style="width:70%;">DETALLE A.C.P. INSTITUCIONAL</th>
              <th style="width:5%;">(%) CUMP.</th>
            </tr>
          </thead>
          <tbody>';
        for ($i=0; $i < count($this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2()); $i++) { 
          $tabla.='
          <tr>
            <td style="font-size:12px; text-align:center">'.$matriz[$i][0].'</td>
            <td style="font-size:10px;">'.$matriz[$i][1].'</td>
            <td style="font-size:10px; text-align:right"><b>'.$matriz[$i][4].' %</b></td>
          </tr>';
        }
        $tabla.='
          </tbody>
        </table>
      </center>';
    }



    return $tabla;
  }





  //// Matriz lista de cumplimiento de Form 1 Institucional 
  public function matriz_cumplimiento_form1_institucional(){
    $lista_acp=$this->model_objetivogestion->get_list_acp_institucional_alineados_a_form2();
     for ($i=0; $i <count($lista_acp); $i++) { 
      for ($j=0; $j <4 ; $j++) { 
        $matriz[$i][$j]=0;
      } 
     }

     $nro=0;
     foreach($lista_acp as $row){
      $get_trm_ejec=$this->model_objetivogestion->get_ejec_acp_institucional_ejecutado($row['og_id']); /// Temporalidad Ejecutado
      $ejec_form1_institucional=0;
      if(count($get_trm_ejec)!=0){
        $ejec_form1_institucional=$get_trm_ejec[0]['ejecutado'];
      }  


        $matriz[$nro][0]='<b>ACP.- '.$row['og_codigo'].'</b>'; /// cod OG
        $matriz[$nro][1]=$row['og_objetivo']; /// detalle OG
        $matriz[$nro][2]=$row['programado_total']; /// Programado Total
        $matriz[$nro][3]=$ejec_form1_institucional; /// ejecutado Total
        $ejecutado=0;
        if($row['programado_total']!=0){
          $ejecutado=round((($ejec_form1_institucional/$row['programado_total'])*100),2);
        }
        $matriz[$nro][4]=$ejecutado; /// ejecutado Total %

        $nro++;
     }

     return $matriz;
  }


}