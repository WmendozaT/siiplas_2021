<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Capertura_programatica extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pdf');
        $this->load->library('pdf2');
        $this->load->model('mantenimiento/mapertura_programatica');
        $this->load->model('mantenimiento/munidad_organizacional');
        //llamar a mi menu
        $this->load->library('menu');
        $this->menu->const_menu(9);
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

    //INICIO DE MI VISTA APERTURA PROGRAMATICA
    public function main_apertura_programatica_padres(){
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
    function add_apertura()
    {
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


    private $estilo_vertical = '<style>
        body{
            font-family: sans-serif;
            }
        @page {
        margin: 40px 40px;
        }
        header {
            position: fixed;
            left: 0px;
            top: -20px;
            right: 0px;
            height: 20px;
            background-color: #fff;
            }
        header h1{
            margin: 5px 0;
        }
        header h2{
            margin: 0 0 10px 0;
        }
        th{
                padding: 1px;
                text-align: center;
                font-size: 9px;
                color: #ffffff;
            }
        footer {
            position: fixed;
            left: 0px;
            bottom: -82px;
            right: 0px;
            height: 100px;
            border-bottom: 1px solid #ddd;
        }
        footer .page:after {
            content: counter(page) ;
        }
        footer table {
            width: 100%;
        }
        footer p {
            text-align: right;
        }
        footer .izq {
            text-align: left;
        }
        table{
            font-size: 7px;
            width: 100%;
            background-color:#fff;
        }
        th, td {
                padding: 1px;
                text-align: center;
                font-size: 7px;
            }
        .mv{font-size:10px;}
        .verde{ width:100%; height:5px; background-color:#e0dbdb;}
        .rojo{ width:100%; height:5px; background-color:#1c7368;}
        .siipp{width:120px;}
        .header_table {
            color: #ffffff;
            text-align: center;
            align-items: center;
            align-self: center;
            font-weight: bold;
            background-color: #696969;
            align-content: center;
        }
        .header_subtable {
            color: #ffffff;
            text-align: center;
            align-items: center;
            align-self: center;
            font-weight: bold;
            background-color: #a59393;
            align-content: center;
        }
        .titulo_pdf {
            text-align: left;
            font-size: 10px;
        }
        .datos_principales {
            text-align: center;
            font-size: 9px;
        }
        .pdes_titulo{
            text-decoration: underline;
            font-weight: bold;
            text-transform: uppercase;
        }
        .td_pdes{
            text-align: left;  
        }

        .indi_desemp{
            font-size: 14px;
            border: 1px solid #CCC;
            background-color: #E0E0E0;
            padding: .5em;
        }
        table.fixed { table-layout:fixed; }
        table.fixed td { overflow: hidden; }
        .sub_table{
            border-bottom:1px solid black;
        }
        .contenedor_principal{
            border-style: solid;
            border-width:1px;
        }
        .titulo_dictamen{
            padding:4px;
            background-color: #454545;
            border-style: solid;margin: 2px;
            border-width: 1px;
            color: white;
            font-size: 10px;
            border-color: black;
        }
        .contenedor_datos{
            padding:0px;
            margin:1px;
        }
        .table_contenedor{
            border-style: solid;
            margin: 0px;
            padding: 1px;
            border-width: 1px;
            border-collapse: collapse;
        }
        .collapse_t td{
            border-style: solid;
            margin: 0px;
            padding: 3px;
            border-width: 1px;
            border-collapse: collapse;
        }
        .fila_unitaria{
            text-align:left;
        }
        .lista{
            text-align:left;
            padding-left: 8;
            list-style-type:square;
            margin:2px;
        }
    </style>';


/*==================================== REPORTE - APERTURA PROGRAMATICA ===================================================*/
    public function reporte_apertura_programatica()
    {
        $gestion = $this->session->userdata('gestion');
        $html = '';
        $html .= '
        <html>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <head>'.$this->estilo_vertical.'</head>
            <body>
                <header>
                </header>
                <div class="rojo"></div>
                <div class="verde"></div>
                <table width="100%">
                    <tr>
                        <td width=20%;>
                        </td>
                        <td width=60%; class="titulo_pdf">
                            <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                            <b>POA - PLAN OPERATIVO ANUAL : </b> '.$gestion.'<br>
                            <b>SISTEMA DE PLANIFICACI&Oacute;N DE SALUD POR RESULTADOS  - SIPLAS </b><br>
                            <b>REPORTE : </b> APERTURAS PROGRAM&Aacute;TICA - '.$gestion.'<br>
                        </td>
                        <td width=20%;>
                            <img src="'.base_url().'assets/ifinal/cns.png" alt="" width="100px">
                        </td>
                    </tr>
                </table>

                
                <footer>
                    <table class="table table-bordered" width="100%">
                        <tr>
                            <td><p class="izq">SIPLAS Sistema de Planificaci&oacute;n de Salud Por Resultados</p></td>
                            <td><p class="page">Pagina </p></td>
                        </tr>
                    </table>
                </footer>
                
                <br>
                <div class="contenedor_principal">
                    <table class="datos_principales" style="table-layout:fixed;" border="1">
                      <tr class="titulo_dictamen">
                        <th style="width:5%"></th>
                        <th style="width:10%">GESTI&Oacute;N</th>
                        <th style="width:10%">PROGRAMA</th>
                        <th style="width:10%">PROYECTO</th>
                        <th style="width:10%">ACTIVIDAD</b></th>
                        <th>DESCRIPCI&Oacute;N</th>
                        <th>UNIDAD ORGANIZACIONAL</th>
                      </tr>';
                        $lista_aper_padres = $this->mapertura_programatica->lista_aperturas_padres();//lista de aperturas padres
                        $cont = 1;
                        foreach ($lista_aper_padres as $row) {
                            $html .= '<tr style="background-color: #8fbc8f" id="tr' . $row['aper_id'] . '">';
                            $html .= '<td style="color: #FFFFFF">' . ($cont++) . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['aper_gestion'] . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['aper_programa'] . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['aper_proyecto'] . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['aper_actividad'] . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['aper_descripcion'] . '</td>';
                            $html .= '<td style="color: #FFFFFF">' . $row['uni_unidad'] . '</td>';
                            $html .= '</tr>';
                            $lista_aper_hijas = $this->mapertura_programatica->lista_aper_hijas($row['aper_programa'],$row['aper_gestion']);
                            foreach($lista_aper_hijas as $row2){
                                $html .= '<tr style="background-color: #E6E6FA" id="tr' . $row2['aper_id'] . '">';
                                $html .= '<td>'.($cont++).'</td>';
                                $html .= '<td>' . $row2['aper_gestion'] . '</td>';
                                $html .= '<td>' . $row2['aper_programa'] . '</td>';
                                $html .= '<td>' . $row2['aper_proyecto'] . '</td>';
                                $html .= '<td>' . $row2['aper_actividad'] . '</td>';
                                $html .= '<td>' . $row2['aper_descripcion'] . '</td>';
                                $html .= '<td>' . $row2['uni_unidad'] . '</td>';
                                $html .= '</tr>';
                            }
                        }
                $html .= '   </table>

                    <table class="table_contenedor" style="margin-top: 10px;>
                        <tr>
                            <td class="fila_unitaria">FIRMAS:</td>
                        </tr>
                    </table>
                    <table style="width: 80%;margin: 0 auto;margin-top: 100px;margin-bottom: 50px;">
                        <tr>
                            <td style="width:30%">
                                <hr>
                            </td>
                            <td style="width:3%"></td>
                            <td style="width:30%">
                                <hr>
                            </td>
                            <td style="width:3%"></td>
                            <td style="width:30%">
                                <hr>
                            </td>
                        </tr>
                    </table>

                </div>
            </body>
        </html>';
        $dompdf = new DOMPDF();
        $dompdf->load_html($html);
        $dompdf->set_paper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream("dictamen_proyecto.pdf", array("Attachment" => false));
    }


}