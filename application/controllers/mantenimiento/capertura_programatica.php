<?php
class Capertura_programatica extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('mantenimiento/mapertura_programatica');
        $this->load->model('mantenimiento/munidad_organizacional');
        //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
    }

    /*--------- LISTA DE PROGRAMAS --------*/
    public function main_apertura_programatica_padres(){
        $data['menu'] = $this->menu->genera_menu();
      /*  $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep'] = $this->tp_resp($this->dist);
        $data['list_par_padres'] = $this->model_partidas->lista_padres();
        $data['lista_p'] = $this->model_partidas->lista_partidas();
        $data['partidas']=$this->list_partidas();
        $data['umedidas']=$this->list_umedidas();*/
        $this->load->view('admin/mantenimiento/programas/vlist_programas', $data);
    }







    //FUNCION PARA CONSTRUIR MI VISTA
    function construir_vista($ruta, $data){
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

    //INICIO DE MI VISTA APERTURA PROGRAMATICA
    public function main_apertura_programatica_padresS(){
        $data['lista_apertura'] = $this->mapertura_programatica->lista_aperturas_padres();
        $data['lista_unidad'] = $this->munidad_organizacional->lista_unidad_org();
        $ruta = 'mantenimiento/vlista_programas';
        $this->construir_vista($ruta, $data);
    }

    //FUNCION PARA VERIFICAR SI EXISTE EL CODIGO DE LA APERTURA PROGRAMATICA
    function existe_cod_apertura()
    {
        $post = $this->input->post();
        $cod = $post['aper_programa'];
        $gestion = $post['aper_gestion'];
        $data = $this->mapertura_programatica->verificar_aper($cod, $gestion);
        echo $data;
    }

    //ADICIONAR APERTURA PROGRAMATICA
    function add_apertura(){
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $this->form_validation->set_rules('aper_descripcion', 'descripcion', 'required|trim');
            $this->form_validation->set_rules('aper_programa', 'programa', 'required|trim|integer');
            $this->form_validation->set_rules('aper_gestion', 'sigla', 'required|trim|integer');
            $this->form_validation->set_rules('unidad_o', 'unidad organizacional', 'required');
            //=========================== mensajes =========================================
            $this->form_validation->set_message('required', 'El campo es es obligatorio');
            $this->form_validation->set_message('integer', 'El campo  debe poseer solo numeros enteros');
            if ($this->form_validation->run()) {
                $post = $this->input->post();
                $programa = $post['aper_programa'];
                $descripcion = $post['aper_descripcion'];
                $gestion = $post['aper_gestion'];
                $unidad = $post['unidad_o'];
                //================ evitar enviar codigo malicioso ==========
                $programa = $this->security->xss_clean($programa);
                $descripcion = $this->security->xss_clean(trim($descripcion));
                $gestion = $this->security->xss_clean($gestion);
                if (isset($_REQUEST['modificar'])) {
                    //CASO MODIFICAR APERTURA
                    $aperid = $this->input->post('modificar');
                    $peticion = $this->mapertura_programatica->modificar_apertura($aperid, $descripcion, $unidad);
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
                    echo json_encode($result);
                } else {
                    //CASO ADICIONAR APERTURA
                    $peticion = $this->mapertura_programatica->guardar_apertura($programa, $descripcion, $gestion, $unidad);
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
                    echo json_encode($result);
                }
            } else {
                echo 'DATOS ERRONEOS';
            }
        } else {
            show_404();
        }
    }

    //OBTIENE LOS DATOS DE LA APERTURA PADRE
    function dato_apertura_padre()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $post = $this->input->post();
            $id = $post['id_aper'];//obtiene el id de la apertura
            $dato_aper = $this->mapertura_programatica->dato_apertura($id);
            //caso para modificar el codigo de proyecto y actividades
            foreach ($dato_aper as $row) {
                $result = array(
                    'aper_id' => $row['aper_id'],
                    "aper_gestion" => $row['aper_gestion'],
                    "aper_programa" => $row['aper_programa'],
                    "aper_proyecto" => $row['aper_proyecto'],
                    "aper_actividad" => $row['aper_actividad'],
                    "aper_descripcion" => $row['aper_descripcion'],
                    "uni_id" => $row['uni_id']
                );
            }
            echo json_encode($result);
        } else {
            show_404();
        }
    }

    function modificar_apertura()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $post = $this->input->post();
            $descripcion = $post['aper_descripcion'];
            $unidad = $post['unidad_o'];
            $aper_id = $post['aper_id'];
            //================ evitar enviar codigo malicioso ==========
            $descripcion = $this->security->xss_clean(trim($descripcion));
            $peticion = $this->mapertura_programatica->modificar_apertura($aper_id, $descripcion, $unidad);
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
            echo json_encode($result);
        } else {
            echo 'DATOS ERRONEOS';
        }

    }

    function eliminar_apertura()
    {
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $post = $this->input->post();
            $aper_id = $post['aper_id'];
            $estado = 4;
            $peticion = $this->mapertura_programatica->eliminar_apertura($aper_id,$estado);
            if ($peticion) {
                echo $aper_id;
            } else {
                echo false;
            }

        } else {
            show_404();
        }
    }

    //INICIO DE VISTA APERTURA PROGRAMATICA padre hijos
    function main_apertura_programatica(){
        $lista_aper_padres = $this->mapertura_programatica->lista_aperturas_padres();//lista de aperturas padres
        $data['lista_uni'] = $this->munidad_organizacional->lista_unidad_org();
        $cont = 1;
        $tabla = '';
        foreach ($lista_aper_padres as $row) {
            $tabla .=  '<tr style="background-color: #8fbc8f" id="tr' . $row['aper_id'] . '">';
            $tabla .=  '<td style="color: #FFFFFF">' . ($cont++) . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['aper_gestion'] . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['aper_programa'] . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['aper_proyecto'] . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['aper_actividad'] . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['aper_descripcion'] . '</td>';
            $tabla .=  '<td style="color: #FFFFFF">' . $row['uni_unidad'] . '</td>';
            $tabla .=  '</tr>';
            $lista_aper_hijas = $this->mapertura_programatica->lista_aper_hijas($row['aper_programa'],$row['aper_gestion']);
            foreach($lista_aper_hijas as $row2){
                $tabla .=  '<tr style="background-color: #E6E6FA" id="tr' . $row2['aper_id'] . '">';
                $tabla .=  '<td>'.($cont++).'</td>';
                $tabla .=  '<td>' . $row2['aper_gestion'] . '</td>';
                $tabla .=  '<td>' . $row2['aper_programa'] . '</td>';
                $tabla .=  '<td>' . $row2['aper_proyecto'] . '</td>';
                $tabla .=  '<td>' . $row2['aper_actividad'] . '</td>';
                $tabla .=  '<td>' . $row2['aper_descripcion'] . '</td>';
                $tabla .=  '<td>' . $row2['uni_unidad'] . '</td>';
                $tabla .= '</tr>';
            }

            ///-------------------------------------------------------------------------

        }
        $data['lista_aperturas'] = $tabla;
        $ruta = 'mantenimiento/vlista_apertura_programatica';
        $this->construir_vista($ruta, $data);
    }


}