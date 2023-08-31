<?php
class Trabajando extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->library('menu');
        $this->menu->const_menu(9);
   	}
    public function vista()
    {
        // $gestion = $this->session->userdata('gestion');
        // $operaciones = $this->model_para_vistas->get_operaciones($gestion); 
        // $data['operaciones'] = $operaciones->result_array();
        $data['titulo']='OPERACIONES';
        $ruta = 'trabajando';
        $this->construir_vista($ruta,$data);   
    }

    function construir_vista($ruta,$data)
    {
        // $menu['enlaces'] = $this->menu->get_enlaces();
        // $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        // $menu['titulo'] = 'REPORTES';
        $this->load->view('includes/header');
        $this->load->view($ruta, $data);
        $this->load->view('includes/footer');
    }

    public function error(){
        $data['error']='error';
        $this->load->view('rewriten_404', $data);
    }
}