<?php
class Cevaluacion_form1 extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
      $this->load->model('programacion/model_proyecto');
      $this->load->model('programacion/model_faseetapa');
      $this->load->model('programacion/model_actividad');
      $this->load->model('programacion/model_producto');
      $this->load->model('programacion/model_componente');
      $this->load->model('ejecucion/model_evaluacion');
      $this->load->model('ejecucion/model_ejecucion');
      $this->load->model('mestrategico/model_mestrategico');
      $this->load->model('mestrategico/model_objetivogestion');
      $this->load->model('mestrategico/model_objetivoregion');
      $this->load->model('mantenimiento/model_configuracion');
      $this->load->model('menu_modelo');
      $this->load->model('Users_model','',true);
      $this->load->library('security');
      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm');
      $this->dep_id = $this->session->userData('dep_id');
      $this->rol = $this->session->userData('rol_id');
      $this->dist = $this->session->userData('dist');
      $this->dist_tp = $this->session->userData('dist_tp');
      $this->tmes = $this->session->userData('trimestre');
      $this->fun_id = $this->session->userData('fun_id');
      $this->tp_adm = $this->session->userData('tp_adm');
      $this->conf_estado = $this->session->userData('conf_estado'); /// conf estado Gestion (1: activo, 0: no activo)
      $this->fecha_final_evaluacionacp = strtotime(date('2022-05-9'));
      $this->load->library('eval_acp');

      }else{
        $this->session->sess_destroy();
        redirect('/','refresh');
      }
    }


  /*-- MENU ACP --*/
  public function menu_acp(){
    $data['menu']=$this->eval_acp->menu(4); //// genera menu
    $data['titulo']=$this->eval_acp->titulo();
    $configuracion=$this->model_configuracion->get_configuracion_session();
    $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);

    if($this->tp_adm==1){
      $data['tabla']=$this->eval_acp->regionales();
    }
    else{

      $date_actual = strtotime(date('Y-m-d')); //// fecha Actual
      $date_inicio = strtotime($configuracion[0]['eval_inicio']); /// Fecha Inicio
      $date_final = strtotime($configuracion[0]['eval_fin']); /// Fecha Final

      if (($date_actual >= $date_inicio) && ($date_actual <= $this->fecha_final_evaluacionacp) || $this->tp_adm==1){
        $data['tabla']=$this->eval_acp->formulario_n1_regional($this->dep_id);
      }
      else{
        $data['tabla']='<center>
          <div class="widget-body">
            <font color=red size="4"><b>EVALUACION A.C.P. '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.' CERRADO !!!!!</b></font>
          </div>
          <iframe id="ipdf" width="100%" height="1000px;" src="'.base_url().'index.php/rep_eval_obj/evaluacion_objetivos/'.$this->dep_id.'"></iframe></center>';

       // $data['tabla']=$this->eval_acp->cerrado($this->dep_id); 
      }
    }

    $this->load->view('admin/evaluacion/evaluacion_form1/menu_regionales', $data);
//    echo $this->eval_acp->formulario_n1_regional(10);
  }



  /*---- FUNCION GET LISTA DE FORMULARIO 1 POR REGIONAL --------*/
  public function get_lista_form1_x_regionales(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
      $departamento=$this->model_proyecto->get_departamento($dep_id);
      $date_actual = strtotime(date('Y-m-d')); //// fecha Actual

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$this->eval_acp->formulario_n1_regional($dep_id),
        );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*------- GET OBJETIVO REGIONAL -------*/
  public function get_datos_acp_regional(){
    if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $pog_id = $this->security->xss_clean($post['pog_id']);
        $ejec = $this->security->xss_clean($post['ejec']); /// valor ejecutado
        
        $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($pog_id); /// meta acp regional
        $acp_regional=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id); /// acp


        if(count($meta_regional)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'acp_regional' => $acp_regional, /// Datos Objetivo de Gestion ACP
            'meta_regional' => $meta_regional, /// Datos Meta Regional
            'trimestre' => $this->model_evaluacion->trimestre(), /// Datos Trimestre
            'evaluado' => $this->eval_acp->get_suma_evaluado($pog_id,$this->tmes), /// Valor Evaluado con anterioridad : tipo indicador : 0
            'calificacion' => $this->eval_acp->calcula_calificacion_acp_regional_al_registrar($acp_regional[0]['tp_indi_og'],$this->eval_acp->get_suma_evaluado($pog_id,$this->tmes),$ejec,$meta_regional[0]['prog_fis']), /// Valor Evaluado 
          );
        }
        else{
          $result = array(
            'respuesta' => 'error',
          );
        }

        echo json_encode($result);
    }else{
        show_404();
    }
  }







  /*---- VALIDA ADD/UPDATE EVALUACION POA ACP 2022 ----*/
  public function valida_update_evaluacion_acp(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $pog_id = $this->security->xss_clean($post['pog_id']);
      $ejec_meta = $this->security->xss_clean($post['ejec']);
      $mverificacion = $this->security->xss_clean($post['mv']);
      $tp = $this->security->xss_clean($post['tp']);

      $this->db->where('pog_id', $pog_id);
      $this->db->where('trm_id', $this->tmes);
      $this->db->delete('objetivo_programado_gestion_evaluado');

      $suma_ejec=$this->eval_acp->get_suma_evaluado($pog_id,$this->tmes); ///suma de ejecucion registrado al trimestre anterior
      $meta_regional=$this->model_objetivoregion->get_oregional_por_progfis($pog_id); /// Meta Regional
      $acp_regional=$this->model_objetivogestion->get_objetivo_temporalidad($pog_id); /// acp

      $tp_eval=0;
      if($acp_regional[0]['tp_indi_og']==0){ /// acumulado
        if($meta_regional[0]['prog_fis']==($suma_ejec+$ejec_meta)){
          $tp_eval=1;
        }
      }

      elseif($acp_regional[0]['tp_indi_og']==1){
        if($ejec_meta<=$meta_regional[0]['prog_fis']){
          $tp_eval=1;
        }
        else{
         $tp_eval=0; 
        }

      }
      elseif ($acp_regional[0]['tp_indi_og']==2) {
        if($ejec_meta>=$meta_regional[0]['prog_fis'] & $ejec_meta<=100){
          $tp_eval=1;
        }
        else{
          $tp_eval=0;
        }
      }


      $data = array(
        'pog_id' => $pog_id,
        'ejec_fis' => $ejec_meta, 
        'trm_id' => $this->tmes,
        'tp_eval' => $tp_eval, 
        'tmed_verif' => strtoupper($mverificacion),
      );
      $this->db->insert('objetivo_programado_gestion_evaluado',$data);
      $epog_id=$this->db->insert_id();

      $eval_registrado=$this->model_evaluacion->get_evaluacion_meta_oregional($epog_id);
      

      if(count($eval_registrado)!=0){
        $result = array(
        'respuesta' => 'correcto',
        'meta_regional' => $meta_regional, /// Datos Meta Regional
        'info_evaluado' => $eval_registrado, /// Informacion evaluada al trimestre
        'evaluado' => $this->eval_acp->get_suma_evaluado($pog_id,$this->tmes), /// Valor Evaluado
        'calificacion' => $this->eval_acp->calificacion_acp_regional($pog_id), /// Valor Evaluado 
        );
      }
      else{
        $result = array(
        'respuesta' => 'error',
       
        );
      }

      echo json_encode($result);

    }else{
        show_404();
    }
  }


}