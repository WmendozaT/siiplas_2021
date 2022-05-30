<?php
class Cejecucion_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null){
        $this->load->model('Users_model','',true);
        if($this->rolfun($this->rol)){ 
        $this->load->library('pdf2');
        $this->load->model('menu_modelo');
        $this->load->model('consultas/model_consultas');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->pcion = $this->session->userData('pcion');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
        $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
        $this->ppto= $this->session->userData('verif_ppto'); 
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->load->library('ejecucion_finpi');
        }else{
            redirect('admin/dashboard');
        }
    }
    else{
        redirect('/','refresh');
    }
  }


  /*------- formulario ejecucion financiera -------*/
  public function formulario_ejecucion_ppto(){
    $data['menu']=$this->ejecucion_finpi->menu_pi();
    $data['style']=$this->ejecucion_finpi->style();
    $data['formulario']=$this->ejecucion_finpi->formulario();

    $this->load->view('admin/ejecucion_pi/form_ejec_fin_pi', $data);

  }



  /*--- VERIFICANDO EL VALOR A EJECUTAR POR PARTIDA ---*/
  function verif_valor_ejecutado_x_partida(){
    if($this->input->is_ajax_request()){
      /// tp 0 : registro
      /// tp 1 : modificacion

      $post = $this->input->post();
      $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
      $tp = $this->security->xss_clean($post['tp']); /// tp
      $ejec= $this->security->xss_clean($post['ejec']);/// prod id
      $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
      
      $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep
      $monto_total_ejecutado=$this->model_ptto_sigep->suma_monto_ppto_ejecutado($sp_id); /// monto total ejecutado

      $monto_ejecutado=0;
      if(count($monto_total_ejecutado)!=0){
        $monto_ejecutado=$monto_total_ejecutado[0]['ejecutado'];
      }        

      if(($ejec+$monto_ejecutado)<=$get_partida_sigep[0]['importe']){
        echo "true";
      }
      else{
        echo "false";
      }

    }else{
      show_404();
    }
  }



  /// data: "sp_id="+sp_id+"&ejec="+ejec+"&obs="+observacion
  /*---- VALIDA ADD MOD EJECUCION PRESUPUESTARIA ----*/
  public function guardar_ppto_ejecutado(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $sp_id = $this->security->xss_clean($post['sp_id']);
      $ejec = $this->security->xss_clean($post['ejec']);
      $obs = $this->security->xss_clean($post['obs']);
      $mes_id=$this->verif_mes[1];
      
      $ppto_ejec_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$mes_id); ///

      /// ----- Eliminando Registro de ejecucion --------
        $this->db->where('sp_id', $sp_id);
        $this->db->where('m_id', $this->verif_mes[1]);
        $this->db->delete('ejecucion_financiera_sigep');
      /// -----------------------------------
        if($ejec!=0){
          /// ----- Registro de Ejecucion --------
          $data_to_store = array(
            'sp_id' => $sp_id, /// Id sigep partida
            'm_id' => $this->verif_mes[1], /// Mes 
            'ppto_ejec' => $ejec, /// Valor ejecutado
            'fun_id' => $this->fun_id, /// fun id
          );
          $this->db->insert('ejecucion_financiera_sigep', $data_to_store);
          /// -----------------------------------
        }
      
        /// ----- Registro de Ejecucion --------
        $data_to_store = array(
          'sp_id' => $sp_id, /// Id sigep partida
          'observacion' => strtoupper($obs), /// Observacion
          'm_id' => $this->verif_mes[1], /// Mes 
          'fun_id' => $this->fun_id, /// fun id
        );
        $this->db->insert('obs_ejecucion_financiera_sigep', $data_to_store);
        /// -----------------------------------

        $ppto_ejec_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$mes_id); /// Registro
        $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($sp_id,$mes_id); /// Observacion

        $monto_ejec=0;
        $observacion_registrado='';

        if(count($ppto_ejec_mensual)!=0){
          $monto_ejec=$ppto_ejec_mensual[0]['ppto_ejec'];
        }

        if(count($obs_ejec_mensual)!=0){
          $observacion_registrado=$obs_ejec_mensual[0]['observacion'];
        }



      $result = array(
        'respuesta' => 'correcto',
        'ppto_mes'=>round($monto_ejec,2),
        'obs_mes'=>strtoupper($observacion_registrado),
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }

    /*----------------------------------------*/
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

}