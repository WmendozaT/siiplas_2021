<?php

class Cpoa extends CI_Controller
{
    var $gestion;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mantenimiento/mpoa');
        $this->load->model('mantenimiento/mapertura_programatica');
        $this->load->model('mantenimiento/munidad_organizacional');
        //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
        $this->gestion = $this->session->userData('gestion');
    }
    //FUNCION PARA CONSTRUIR MI VISTA
    function construir_vista($ruta, $data)
    {
        //----------------------------------- MENU-------------------------------
        $menu['enlaces'] = $this->menu->get_enlaces();
        $menu['subenlaces'] = $this->menu->get_sub_enlaces();
        $menu['titulo'] = 'MANTENIMIENTO';
        //-----------------------------------------------------------------------
        //armar vista
        $this->load->view('includes/header');
        $this->load->view('includes/menu_lateral', $menu);
        $this->load->view($ruta, $data);//contenido
        //$this->load->view('admin/mantenimiento/vprueba');//contenido
        $this->load->view('includes/footer');
    }
    //INICIO DE MI PAGINA POA
    function index()
    {
        $data['lista_poa'] = $this->mpoa->lista_poa();
        $data['list_aper'] = $this->mapertura_programatica->get_aper_noasignados();
        $data['list_uni'] = $this->munidad_organizacional->lista_unidad_org();
        $ruta = 'mantenimiento/vlista_poa';
        $this->construir_vista($ruta, $data);
    }
    //ACTUALIZAR MI UNIDAD ORGANIZACIONAL AL MOMENTO DE AÑADIR UNA NUEVA APERTURA
    function obtener_unidad(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $aper_id = $post['aper_id'];
            //obtener la id unidad organizacional
            $dato_poa = $this->mapertura_programatica->dato_apertura($aper_id);
            $uni_id = $dato_poa[0]['uni_id'];
            //obtener los datos de la unidad organizacional
            $dato_unidad = $this->munidad_organizacional->get_unidad_org($uni_id);
            $data = array(
              'uni_id' => $dato_unidad[0]['uni_id'],
              'unidad' => $dato_unidad[0]['uni_unidad']
            );
            echo json_encode($data);

        }else{
            show_404();
        }
    }
    //GUARDAR CARPETA POA
    function guardar_poa(){
        if($this->input->is_ajax_request() && $this->input->post()){
            if(!isset($_REQUEST['modificar'])) {
                $this->form_validation->set_rules('aper_programatica', 'apertura prog', 'required');
            }
            $this->form_validation->set_rules('poa_fecha', 'fecha', 'required');
            //=========================== mensajes =========================================
            $this->form_validation->set_message('required', 'El campo es es obligatorio');
            if ($this->form_validation->run() ) {
                $poa_fecha =  $this->input->post('poa_fecha');
                $poa_fecha = $this->security->xss_clean($poa_fecha);
                //======================= MODIFICAR ===========================================
                if(isset($_REQUEST['modificar'])){
                    $poaid = $this->input->post('modificar');
                    //CASO ADICIONAR ADICIONAR
                    $peticion = $this->mpoa->modificar_poa($poaid,$poa_fecha);
                    //si el modelo responde correctamente enviamos correcto con json
                    if ($peticion) {
                        $result = array(
                            'respuesta' => 'correcto'
                        );
                    } else {
                        $result = array(
                            'respuesta' => 'error'
                        );
                    }
                }else{
                    $aper_programatica =  $this->input->post('aper_programatica');
                    //CASO ADICIONAR ADICIONAR
                    $peticion = $this->mpoa->guardar_poa($aper_programatica,$poa_fecha);
                    //si el modelo responde correctamente enviamos correcto con json
                    if ($peticion) {
                        $result = array(
                            'respuesta' => 'correcto'
                        );
                    } else {
                        $result = array(
                            'respuesta' => 'error'
                        );
                    }
                }
                echo json_encode($result);

            } else {
                echo'DATOS ERRONEOS';
            }
        }else{
            show_404();
        }
    }
    // OBTENER DATOS DE MI CARPETA POA FILTRADO POR ID POA
    function get_poa(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $poa_id = $post['poa_id'];
            //estructurar la fecha
            $dato_poa = $this->mpoa->dato_poa($poa_id,$this->gestion);
            $poa_fecha = str_replace("-", "/", $dato_poa[0]['poa_fecha_creacion']);
                $result = array(
                    'poa_id' => $dato_poa[0]['poa_id'],
                    "poa_codigo" =>$dato_poa[0]['poa_codigo'],
                    "fecha" =>$poa_fecha,
                    "aper_descripcion" =>$dato_poa[0]['aper_descripcion']
                );
            echo json_encode($result);

        }else{
            show_404();
        }
    }
    //ELIMINAR CARPETA POA
    function eliminar_poa(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $poa_id = $post['poa_id'];
            $peticion = $this->mpoa->eliminar_poa($poa_id,$this->gestion);
            if ($peticion) {
                $result = array(
                    'respuesta' => 'correcto'
                );
            } else {
                $result = array(
                    'respuesta' => 'error'
                );
            }
            echo json_encode($result);
        }else{
            show_404();
        }
    }
    //LISTA RED DE OBJETIVOS
    function red_objetivos(){
        //-----------------------------------------------------------------------
        $data['lista_poa'] = $this->mpoa->lista_poa();
        $ruta = 'mantenimiento/vred_objetivos';
        $this->construir_vista($ruta, $data);
    }
    //ASIGNAR OBJETIVO ESTRATEGICO A LA CARPETA POA
    function asignar_obj_poa($poa_id){
        $data['dato_poa'] = $this->mpoa->dato_poa($poa_id,$this->gestion);
        //lista de pbjetivos estrategicos filtrado por poa_id
        $data['lista_obje']=$this->mpoa->get_list_objetivos_estrategicos($poa_id);
        $ruta = 'mantenimiento/vasignar_obje_poa';
        $this->construir_vista($ruta, $data);
    }
    //ASIGNAR O QUITAR OBJETIVO ESTRATEGICO DE LA CARPETA POA
    function alta_baja_objetivo(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $accion = $post['accion'];
            $obj_id = $post['obj_id'];
            $poa_id = $post['poa_id'];
            $dato_poa = $this->mpoa->dato_poa($poa_id,$this->gestion);
            //accion = 1 ALTA
            if($accion == 1){
                $this->mpoa->asignar_obj($poa_id,$obj_id);
            }else{
                //verificar si existe el id
                if($this->mpoa->verificar_objetivo_estartegico($obj_id,$dato_poa[0]['aper_id']) == 1){
                    //no se puede
                    $respuesta = array('dato'=>'1');
                }else{
                    $this->mpoa->quitar_obj($poa_id,$obj_id);
                    $respuesta = array('dato'=>'0');
                }
                echo json_encode($respuesta);
            }
        }else{
            show_404();
        }
    }
}