<?php

class mision extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('programacion/model_mision');
         //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(1);
    }
    
    public function vista_mision()
    {   
       
        $data['mision']=$this->model_mision->pei_mision_get();
        $ruta = 'programacion/marco_estrategico/mision';
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

    public function editar_mision()
    {
        $post = $this->input->post();
        $nueva_mision = $post['vmision'];
        $this->model_mision->edita_mision($nueva_mision);
        echo "
            <script>
                alert('Se Actualizo Correctamente');
            </script>
        ";
        $this->vista_mision();
    }
}
    


