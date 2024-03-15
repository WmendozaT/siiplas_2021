<?php if (!defined('BASEPATH')) exit('No se permite el acceso directo al script');

///// DASHBOARD SISTEMA SIIPLAS
class Dashboard extends CI_Controller{
    public function __construct (){
        parent::__construct();
        $this->load->model('programacion/model_proyecto');
        $this->load->model('mantenimiento/model_entidad_tras');
        $this->load->model('mantenimiento/model_partidas');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('modificacion/model_modrequerimiento');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_componente');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('programacion/model_producto');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas
        $this->load->model('mantenimiento/model_estructura_org');

        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('ejecucion/model_certificacion');

        $this->load->model('menu_modelo');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        //$this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        //$this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->fun_id = $this->session->userData('fun_id');
       // $this->tp_adm = $this->session->userData('tp_adm');
        $this->verif_mes=$this->session->userData('mes_actual');
        $this->resolucion=$this->session->userdata('rd_poa');
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->mes = $this->mes_nombre();
    }


    /*----- MENU PRINCIPAL SEGUIMIENTO POA -----*/
    public function menu_principal_roles_seguimientopoa(){
        $fun_id = $this->session->userdata('fun_id');
        $menus = $this->model_configuracion->modulos($this->gestion);
        //$menus = $this->model_control_menus->menu_segun_roles($fun_id);
        
        $vector;
        $n = 0;
        $vector[0]=html_menu_opciones(2);
        
        return $vector;
    }

    /*------------- FORMA TABLA MENU SISTEMA ---------------*/
    public function html_menu_opciones($o_filtro){
        switch ($o_filtro) {
            case '1':
                $mod=$this->model_configuracion->get_modulos(1);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/me/mis_ogestion" id="myBtn" onclick="pei()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/proyectos.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function pei(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '2':
                $mod=$this->model_configuracion->get_modulos(2);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/admin/proy/list_proy" id="myBtn2" onclick="programacion()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/programacion.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function programacion(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '3':
                $mod=$this->model_configuracion->get_modulos(3);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/mod/list_top" id="myBtn3" onclick="modificacion()"  class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/registro1.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function modificacion(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '4':
                $mod=$this->model_configuracion->get_modulos(4);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/eval/mis_operaciones" id="myBtn3" onclick="evaluacion()"  class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/trabajo_social.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function evaluacion(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '7':
                $mod=$this->model_configuracion->get_modulos(7);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/rep/list_operaciones_req" id="myBtn6" onclick="reporte()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/impresora.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function reporte(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '10':
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/admin/dm/8/"  onclick="reporte_internos()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/calidad.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">CONTROL DE CALIDAD</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function reporte_internos(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            case '9':
                $mod=$this->model_configuracion->get_modulos(9);
                $enlace = '
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/admin/dm/9/" onclick="mantenimiento()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/mantenimiento1.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">'.$mod[0]['mod_descripcion'].'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function mantenimiento(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                break;
            default:
                $enlace = '';
                break;
        }
        return $enlace;
    }






}
?>