<?php

class entidad_transferencia extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->library("email");
         //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
     }
   
    public function lista_enditada_transferencia()
    {
        $data['lista_et'] = $this->model_entidad_tras->lista_entidad_tras();
        $ruta = 'mantenimiento/vlista_entidad_transferencia';
        $this->construir_vista($ruta,$data);
    }
    function construir_vista($ruta,$data){

        
        //----------------------------------- MENU-------------------------------
        $menu['enlaces'] = $this->menu->get_enlaces();
        $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        $menu['titulo'] = 'MANTENIMIENTO';
        //-----------------------------------------------------------------------
        //armar vista
        $this->load->view('includes/header');
        $this->load->view('includes/menu_lateral',$menu);
        $this->load->view($ruta,$data);//contenido
        //$this->load->view('admin/mantenimiento/vprueba');//contenido
        $this->load->view('includes/footer');

    }
    function verificar_cod_et(){
        //si no es una peticiÃ³n ajax mostramos un error 404
        if($this->input->is_ajax_request() && $this->input->post('et_codigo'))
        {
            //en otro caso procesamos la peticiÃ³n
            $post = $this->input->post();
            $cod = $post['et_codigo'];
            $fecha = $post['et_gestion'];
            $cod = $this->security->xss_clean($cod);
            $fecha = $this->security->xss_clean($fecha);
            //$gestion = $post['aper_gestion'];
            $data = $this->model_entidad_tras->verificar_etcod($cod,$fecha);
            //=========== SI EL MODELO responde correctamente SEGUIMOS
            if(count($data)== 0){
                echo '1';
            }else{
                echo '0';
            }
        }else{
            show_404();
        }
    }
    function add_et(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $this->form_validation->set_rules('etdescripcion', 'descripcion', 'required|trim');
            $this->form_validation->set_rules('etsigla', 'sigla', 'required|trim');
            $this->form_validation->set_rules('etcodigo', 'codigo', 'required|trim|integer');
            $this->form_validation->set_rules('etgestion', 'sigla', 'required|trim|integer');
            //=========================== mensajes =========================================
            $this->form_validation->set_message('required', 'El campo es es obligatorio');
            $this->form_validation->set_message('integer', 'El campo  debe poseer solo numeros enteros');
            if ($this->form_validation->run() ) {
                $etdescripcion=  $this->input->post('etdescripcion');
                $etsigla =  $this->input->post('etsigla');
                $etcodigo =  $this->input->post('etcodigo');
                $etgestion =  $this->input->post('etgestion');
                //=================enviar  evitar codigo malicioso ==========
                $etdescripcion = $this->security->xss_clean(trim($etdescripcion));
                $etsigla = $this->security->xss_clean(trim($etsigla));
                $etcodigo = $this->security->xss_clean($etcodigo);
                $etgestion = $this->security->xss_clean($etgestion);
                //======================= MODIFICAR=
                if(isset($_REQUEST['modificar'])){
                    $etid = $this->input->post('modificar');
                    $verificar = $this->model_entidad_tras->mod_et($etid,$etdescripcion,$etsigla,$etgestion,$etcodigo);
                }else{
                    $verificar =  $this->model_entidad_tras->add_et($etdescripcion,$etsigla,$etcodigo,$etgestion);
                }
                echo 'true';
            } else {
                echo'DATOS ERRONEOS';
            }
        }else{
            show_404();
        }
    }
    function get_et(){
        if($this->input->is_ajax_request() && $this->input->post())
        {
            $post = $this->input->post();
            $cod = $post['id_et'];
            $id = $this->security->xss_clean($cod);
            $dato_et = $this->model_entidad_tras->dato_et($id);
            foreach($dato_et as $row){
                $result = array(
                    'et_id' => $row['et_id'],
                    "et_descripcion" =>$row['et_descripcion'],
                    "et_sigla" =>$row['et_sigla'],
                    "et_codigo" =>$row['et_codigo'],
                    "et_gestion" =>$row['et_gestion']
                );
            }
            echo json_encode($result);
        }else{
            show_404();
        }
    }
    function del_et(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $postid = $post['postid'];
            $sql = 'UPDATE entidadtransferencia SET et_estado = 0 WHERE et_id = '.$postid;
            if($this->db->query($sql)){
                echo $postid;

            }else{
                echo false;
            }
        }else{
            show_404();
        }
    }




public function enviar(){
  $config = array(
     'protocol' => 'smtp',
     'smtp_host' => 'smtp.googlemail.com',
     'smtp_user' => 'hardy_0002009@hotmail.com', //Su Correo de Gmail Aqui
     'smtp_pass' => 'hardyivan000', // Su Password de Gmail aqui
     'smtp_port' => '465',
     'smtp_crypto' => 'ssl',
     'mailtype' => 'html',
     'wordwrap' => TRUE,
     'charset' => 'utf-8'
     );
     $this->load->library('email', $config);
     $this->email->set_newline("\r\n");
     $this->email->from('hardy_0002009@hotmail.com');
     $this->email->subject('Asunto del correo');
     $this->email->message('Hola desde correo');
     $this->email->to('hardy_0002009@hotmail.com');
     if($this->email->send(FALSE)){
         echo "enviado<br/>";
         echo $this->email->print_debugger(array('headers'));
     }else {
         echo "fallo <br/>";
         echo "error: ".$this->email->print_debugger(array('headers'));
     }
}
   
    

    }