<?php

class nueva_session extends CI_Controller{

    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model', '', true);
        $this->load->model('model_pei');
        $this->load->library('session');
    }

    public function cambiar_gestion(){
        $nueva_gestion = strtoupper($this->input->post('gestion_usu'));
        $this->session->set_userdata('gestion', $nueva_gestion);

        redirect('cambiar_gestion','refresh');
    }
    
}