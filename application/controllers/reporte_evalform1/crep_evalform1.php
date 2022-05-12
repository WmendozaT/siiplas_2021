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
      $data['regional']=$this->eval_acp->listado_regionales();
      $data['da']=$this->model_proyecto->list_departamentos();
      $tabla='';
      $tabla.='<div class="well">
                <div class="jumbotron">
                  <h1>Evaluaci&oacute;n A.C.P. '.$this->gestion.'</h1>
                    <p>
                      Reporte consolidado de evaluaci&oacute;n de Acciones de Corto Plazo acumulado al '.$this->trimestre[0]['trm_descripcion'].' de '.$this->gestion.' a nivel Institucional, Regional.
                    </p>
                </div>
              </div>';

      $data['titulo_modulo']=$tabla;

      $this->load->view('admin/reportes_cns/repevaluacion_form1/rep_menu', $data);
    }


    /*-------- GET CUADRO EVALUACION A.CP.--------*/
    public function get_cuadro_evaluacion_objetivos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id, 0: Nacional
        
        $tabla='<center><iframe id="ipdf" width="100%" height="800px;" src="'.base_url().'index.php/rep_eval_obj/evaluacion_objetivos/'.$dep_id.'"></iframe></center>';

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


  //// EVALUACIÓN ACP REGIONAL INSTITUCIONAL - IFRAME
  public function evaluacion_objetivos($id){
    $data['trimestre']=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
    $tabla='';
    $dep_id=$id;
    if($id!=0){ //// REGIONAL
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $data['titulo_graf']=strtoupper($regional[0]['dep_departamento']);
      $data['cabecera']=$this->eval_acp->cabecera_reporte_grafico(); /// Cabecera Grafico
      $data['nro']=count($this->model_objetivogestion->lista_acp_x_regional($dep_id));
      $data['eval']=$this->eval_acp->matriz_evaluacion_meta_acp_regional($dep_id);
      
      $data['detalle_eval']=$this->get_detalle_eval_trimestre($dep_id);
    }
    else{ ///// INSTITUCIONAL
      $data['titulo_graf']='INSTITUCIONAL';
      $data['cabecera']=$this->eval_acp->cabecera_reporte_grafico(); /// Cabecera Grafico
      $data['nro']=count($this->model_objetivogestion->list_objetivosgestion_general());
      $data['eval']=$this->eval_acp->tabla_evaluacion_meta_institucional();
      $data['detalle_eval']='';
    }
    
    $data['tabla']=$this->eval_acp->detalle_acp($data['eval'],$data['nro'],1);
    
    ///----- Para impresion
    $tabla.='<div style="font-family: Arial;">DETALLE A.C.P. '.$data['titulo_graf'].' / '.$this->gestion.'</div>
              <ul>';
              for ($i=1; $i <=$data['nro'] ; $i++) { 
               
                $tabla.='<li style="font-family: Arial;font-size: 11px;height: 1%;">'.$data['eval'][$i][1].'.- '.$data['eval'][$i][2].' - <b>'.$data['eval'][$i][6].' %</b></li>';
              }
              $tabla.='
              </ul>
            <hr>';
    $data['detalle_acp']=$tabla;
    //// -------------------


    $data['matriz_pastel']=$this->eval_acp->matriz_gcumplimiento($data['eval'],$data['nro']);
    $data['tabla_pastel']=$this->eval_acp->tabla_gcumplimiento($data['matriz_pastel'],1,1);
    $data['tabla_pastel_todo']=$this->eval_acp->tabla_gcumplimiento($data['matriz_pastel'],2,1);

    $this->load->view('admin/reportes_cns/repevaluacion_form1/reporte_grafico_eval_consolidado_regional_form1', $data);
  }

  /*--- Detalle de evaluacion de informacion del trimestre ---*/
  public function get_detalle_eval_trimestre($dep_id){
    $tabla='';
    $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
    $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);

    $tabla.='
    <div style="height:45px; font-size: 18px;font-family: Arial;"><b>DETALLE DE EVALUACION TRIMESTRAL '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></div>
    <table class="table table-bordered" align=center style="width:90%;">';
    foreach($acp_regional as $oge){
      $acp_eval_regional=$this->model_evaluacion->get_meta_oregional($oge['pog_id'],$this->tmes);/// datos de evaluacion al trimestre actual
      $indi='';
      if($oge['indi_id']==2){
        $indi='%';
      }

      $dato_evaluado='<font color=red>SIN REGISTRO</font>';
      $ejec='';

      if(count($acp_eval_regional)!=0){
        $dato_evaluado=$acp_eval_regional[0]['tmed_verif'];
        $ejec=' <b>| CUMPLIDO : '.round($acp_eval_regional[0]['ejec_fis'],2).' '.$indi.'</b>';
      }

      

      $tabla.='
      <tr style="height:25px; font-size: 13px;font-family: Arial;" bgcolor="#f0f0f0">
        <td><b>A.C.P. '.$oge['og_codigo'].'.- </b>'.$oge['og_objetivo'].' <b>| META : </b>'.round($oge['prog_fis'],2).' '.$indi.'</td>
      </tr>
      <tr style="height:35px; font-size: 10px;font-family: Arial;">
        <td><font color=blue>EVAL.: </font>'.$dato_evaluado.''.$ejec.'</td>
      </tr>';
    }

    $tabla.='</table>';

    return $tabla;
  }
}