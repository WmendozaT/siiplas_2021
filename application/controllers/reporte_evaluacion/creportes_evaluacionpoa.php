<?php
class Creportes_evaluacionpoa extends CI_Controller {  
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
            $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->load->library('reportes_evaluacionpoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_indicadores_unidades($id,$tp){

      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
      $titulo_rep='CUADRO DE CUMPLIMIENTO POR UNIDADES';
      $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($id,$tp,$titulo_rep);
      $data['pie']=$this->reportes_evaluacionpoa->pie_evaluacionpoa();
      $tabla='';
      $unidades=$this->model_evalinstitucional->list_unidades_organizacionales($tp,$id);

      $tabla.='
      <div style="font-size: 13px;font-family: Arial;height:20px;">&nbsp;&nbsp;&nbsp;&nbsp;DETALLE DE CUMPLIMIENTO</div>
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
         <thead>
            <tr style="font-size: 9.5px;" align=center >
              <th style="width:2%;height:15px;">#</th>
              <th style="width:13%;">DISTRITAL</th>
              <th style="width:20%;">GASTO CORRIENTE / PROY. INV.</th>
              <th style="width:10%;">METAS. PROGR.</th>
              <th style="width:10%;">METAS CUMP.</th>
              <th style="width:10%;">METAS NO CUMP.</th>
              <th style="width:10%;">% CUMP.</th>
              <th style="width:10%;">% ECONOMIA</th>
              <th style="width:10%;">EFICIENCIA</th>
            </tr>
          </thead>
          <tbody>';
          $nro=0; $sum_cert=0;$sum_asig=0;
          foreach($unidades as $row){
            $eficacia=$this->reportes_evaluacionpoa->eficacia_por_unidad($row['proy_id']); /// Eficacia
            $economia=$this->reportes_evaluacionpoa->economia_por_unidad($row['aper_id'],$row['proy_id']); /// Economia
            $eficiencia=$this->reportes_evaluacionpoa->eficiencia_unidad($eficacia[5][$this->tmes],$economia[3]); /// Eficiencia

            $nro++;
            $tabla.='<tr style="font-size: 9.5px;" >';
            $tabla.='<td style="width:2%;height:10px;" align=center>'.$nro.'</td>';
            $tabla.='<td style="width:13%;">'.strtoupper($row['dist_distrital']).'</td>';
            $tabla.='<td style="width:20%;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[2][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[3][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[4][$this->tmes].'</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficacia[5][$this->tmes].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$economia[3].'%</b></td>';
            $tabla.='<td style="width:10%;" align=right><b>'.$eficiencia.'</b></td>';
            $tabla.='</tr>';
          }
      $tabla.='
          </tbody>
        </table>';

        $data['operaciones']=$tabla;



      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_indicadores_parametros', $data);


    }


    



    /*--- REPORTE EVALUACION POR CATEGORIA PROGRAMATICA ---*/
    public function reporte_categoria_programaticas($id,$tp){
      if($tp==0){
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_regional($id,4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_regional($lista_programas);
      }
      elseif($tp==1) {
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_distrital($id,4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_distrital($lista_programas);
      }
      else{
        $lista_programas=$this->model_evalprograma->lista_apertura_programas_institucional(4);
        $matriz_programas=$this->reportes_evaluacionpoa->matriz_programas_institucional($lista_programas);
      }

      $data['cabecera']=$this->reportes_evaluacionpoa->cabecera_evaluacion_trimestral($id,$tp);
      $data['pie']=$this->reportes_evaluacionpoa->pie_evaluacionpoa();
      $data['operaciones']=$this->reportes_evaluacionpoa->tabla_apertura_programatica_reporte($matriz_programas,count($lista_programas));
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_indicadores_parametros', $data);
    }

}