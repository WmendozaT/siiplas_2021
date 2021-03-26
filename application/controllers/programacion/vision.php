<?php

class vision extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('programacion/model_vision');
         //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(1);
     }
   
    public function vista_vision()
    {   
       
        $data['vision']=$this->model_vision->pei_vision_get();
        $ruta = 'programacion/marco_estrategico/vision';
        $this->construir_vista($ruta,$data);
    }   
   function construir_vista($ruta,$data)
   {
       //----------------------------------- MENU-------------------------------
        $menu['enlaces'] = $this->menu->get_enlaces();
        $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        $menu['titulo'] = 'PROGRAMACIÒN ';
        //-----------------------------------------------------------------------
        //armar vista
        $this->load->view('includes/header');
        $this->load->view('includes/menu_lateral',$menu);
        $this->load->view($ruta,$data);//contenido
        //$this->load->view('admin/mantenimiento/vprueba');//contenido
        $this->load->view('includes/footer');
    }
    public function editar_vision()
    {
        $post = $this->input->post();
        $nueva_vision = $post['vvision'];
        $this->model_vision->edita_vision($nueva_vision);
        echo "
            <script>
                alert('Se Actualizo Correctamente');
            </script>
        ";
        $this->vista_vision();
    }
}
    


