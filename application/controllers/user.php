<?php
class User extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Users_model', '', true);
        $this->load->model('mantenimiento/model_funcionario');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('mantenimiento/model_configuracion');
        $this->load->model('ejecucion/model_seguimientopoa');
        $this->load->model('programacion/model_producto');
        $this->load->model('ejecucion/model_notificacion');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('programacion/model_componente');
        
        $this->load->model('model_control_menus');
        $this->load->library('session');
        $this->load->library('encrypt');
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion'); /// Gestion
        $this->ppto_poa = $this->session->userData('ppto_poa'); /// PPTO
        $this->adm = $this->session->userData('adm');
        $this->dist_id = $this->session->userData('dist');
        $this->dep_id = $this->session->userData('dep_id');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userData('fun_id'); /// 592 REG LA PAZ

        $this->modulo = $this->session->userdata('modulos');
        $this->tp_adm = $this->session->userdata("tp_adm");
        $this->tmes = $this->session->userData('trimestre');
        $this->verif_mes=$this->verif_mes_gestion();
    }

    /*----------- Lista de Gestiones Disponibles ---------*/
    public function list_gestiones(){
        $listar_gestion= $this->model_configuracion->lista_gestion();
        $tabla='';

        $tabla.='
                <input type="hidden" name="gest" id="gest" value="'.$this->gestion.'">
                <select name="gestion_usu" id="gestion_usu" class="form-control" required>
                <option value="0">seleccionar gestión</option>'; 
        foreach ($listar_gestion as $row) {
            if($row['ide']==$this->gestion){
                $tabla.='<option value="'.$row['ide'].'" select >'.$row['ide'].'</option>';
            }
            else{
                $tabla.='<option value="'.$row['ide'].'" >'.$row['ide'].'</option>';
            }
        };
        $tabla.='</select>';
        return $tabla;
    }


    /*--- Lista de Gestiones Disponibles ---*/
    public function list_trimestre(){
        $listar_trimestre= $this->model_configuracion->get_mes_trimestre();
        $tmes=$this->model_evaluacion->trimestre();
        $tabla='';

        $tabla.='
                <input type="hidden" name="tmes" id="tmes" value="'.$this->tmes.'">
                <select name="trimestre_usu" id="trimestre_usu" class="form-control" required>
                <option value="0">seleccionar Trimestre</option>'; 
        foreach ($listar_trimestre as $row) {
                if($row['trm_id']!=0 & $row['trm_id']<4){
                    if($row['trm_id']==$tmes[0]['trm_id']){
                        $tabla.='<option value="'.$row['trm_id'].'" select>'.$row['trm_descripcion'].'</option>';
                    }
                    else{
                        $tabla.='<option value="'.$row['trm_id'].'" >'.$row['trm_descripcion'].'</option>';
                    }
                }
        };
        $tabla.='</select>';
        return $tabla;
    }


    /*-------- Valida Cambio gestion Session -----------*/
    public function cambiar_gestion(){
        $conf=$this->model_proyecto->get_configuracion($this->input->post('gestion_usu'));

         $data = array(
                'gestion' => $conf[0]['ide'],
                'mes' => $conf[0]['conf_mes'],
                'trimestre' => $conf[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
                'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
                'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
                'verif_ppto' => $conf[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
                'rd_poa' => $conf[0]['rd_aprobacion_poa'] /// Ppto poa : 0 (Vigente), 1: (Aprobado)
            );
            $this->session->set_userdata($data);

        redirect('admin/dashboard','refresh');
    }

    /*-------- Valida Cambio trimestre Session -----------*/
    public function cambiar_trimestre(){
        $conf=$this->model_proyecto->get_configuracion($this->gestion);

         $data = array(
                'gestion' => $conf[0]['ide'],
                'trimestre' => $this->input->post('trimestre_usu'), /// Trimestre 1,2,3,4
                'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
                'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
                'verif_ppto' => $conf[0]['ppto_poa'] /// Ppto poa : 0 (Vigente), 1: (Aprobado)
            );
            $this->session->set_userdata($data);

        redirect('admin/dashboard','refresh');
    }

    public function index(){
        if ($this->session->userdata('is_logged_in')) {
            redirect('admin/dashboard');
        } else {
            
            $captcha= $this->generar_captcha(array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'),4);
            
            $data['cod_captcha']=$captcha;
            $data['captcha']=md5($captcha);

            $this->load->view('admin/login',$data);
        }
    }


    /// GET CAPTCHA
    public function get_captcha(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $captcha= $this->generar_captcha(array(0,1,2,3,4,5,6,7,8,9,'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R'),4);
         
          $result = array(
          'respuesta' => 'correcto',
          'cod_captcha' => $captcha,
          'captcha' => md5($captcha),
        );
          
        echo json_encode($result);
 
      }else{
        show_404();
      }
    }


    //// GENERAR CAPTCHA
    function generar_captcha($chars,$length){
        $captcha=null;
        for ($i=0; $i <$length ; $i++) { 
            $rand= rand(0,count($chars)-1);
            $captcha .=$chars[$rand];
        }

        return $captcha;
    }


    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist_id);
      if($this->adm==1){
        $titulo='<b>RESPONSABLE :</b> NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='<b>RESPONSABLE :</b> '.strtoupper($ddep[0]['dist_distrital']);
      }

      return $titulo;
    }


    /*-------------- DASHBOARD ---------------*/
    public function dashboard_index(){
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $data['vector_menus'] = $this->menu_principal_roles();
            $data['resp']=$this->session->userdata('funcionario');
            $data['dist_id']=$this->dist_id;
            $data['res_dep']=$this->tp_resp();
            $ddep = $this->model_proyecto->dep_dist($this->dist_id);
            $data['dep_id']=$ddep[0]['dep_id'];
            $data['tmes']=$this->model_evaluacion->trimestre();
            //$data['conf'] = $this->model_configuracion->get_configuracion_session();
            $data['gestiones']=$this->list_gestiones();
            $data['list_trimestre']=$this->list_trimestre();
            
            if($this->gestion>2020){
                $data['mensaje']='';
                $data['seguimiento_poa']='';
                if($this->fun_id==592){ //// Exclusivo para la Regional LA paz
                    $nro_poa=count($this->model_seguimientopoa->get_seguimiento_poa_mes_regional($this->dep_id,$this->verif_mes[1],$this->gestion));
                }
                else{ /// Listado normal
                    $nro_poa=count($this->model_seguimientopoa->get_seguimiento_poa_mes_distrital($this->dist_id,$this->verif_mes[1],$this->gestion));
                }
                
                if($nro_poa!=0){
                    $data['seguimiento_poa']=$this->mensaje_ejecucion_operaciones_mes($nro_poa);
                }
            }
            else{
                $data['mensaje']=$this->mensaje_sistema();
                $data['seguimiento_poa']='';
            }
            
            $this->load->view('admin/dashboard',$data);
        } else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }


    /*---- LISTA DE OPERACIONES A SER EJECUTADAS EN EL MES ----*/
    public function mensaje_ejecucion_operaciones_mes($nro){
        if($this->fun_id==592){ /// Exclusivo La paz
            $req=$this->model_notificacion->nro_requerimientos_acertificar_mensual_x_mes_regional($this->dep_id,$this->verif_mes[1]);
        }
        else{
            $req=$this->model_notificacion->nro_requerimientos_acertificar_mensual_x_mes_distrital($this->dist_id,$this->verif_mes[1]);
        }

        
        $ddep = $this->model_proyecto->dep_dist($this->dist_id);
        $tit_requerimiento='';
        
        if(count($req)!=0){
            $tit_requerimiento='y '.$req[0]['requerimientos'].' Requerimientos con un monto de Bs. '.number_format($req[0]['monto'], 2, ',', '.').' que debe ser certificados ';
        }

        $tabla='';
        $tabla.='
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">SEGUIMIENTO y CERTIFICACI&Oacute;N POA '.$this->gestion.'!</h4>
                <p>Hola '.$this->session->userdata('funcionario').', la '.strtoupper($ddep[0]['dist_distrital']).' tiene programado en su POA '.$this->gestion.' para el mes de '.$this->verif_mes[2].' : '.$nro.' Operaciones a ser ejecutados '.$tit_requerimiento.',
                las mismas se las deben realizar a traves del modulo de EVALUACI&Oacute;N y CERTIFICACI&Oacute;N POA. </p>
                <hr>
                <p class="mb-0"><a href="#" data-toggle="modal" data-target="#modal_ope_mes" id="'.$this->dist_id.'" class="ope_mes" title="">Ver Operaciones Programadas</a></p>
            </div>';

        return $tabla;
    }


    /*---- MENSAJE SISTEMA ----*/
    public function mensaje_sistema(){
        $conf = $this->model_configuracion->get_configuracion_session();
        $tabla='';
        
        if($conf[0]['tp_msn']==1){ 
            $tabla.='
            <div class="alert alert-danger" align="center">
              <a class="alert-link">'.$conf[0]['conf_mensaje'].'</a>
            </div>';
        }
        elseif ($conf[0]['tp_msn']==2) {
            $tabla.='
            <div class="alert alert-warning" align="center">
              <a class="alert-link">'.$conf[0]['conf_mensaje'].'</a>
            </div>';
        }
        elseif ($conf[0]['tp_msn']==3) {
            $tabla.='
            <div class="alert alert-success" align="center">
              <a class="alert-link">'.$conf[0]['conf_mensaje'].'</a>
            </div>';
        }

        return $tabla;
    }


    /*------------- MENU PRINCIPAL -------------*/
    public function menu_principal_roles(){
        $fun_id = $this->session->userdata('fun_id');
        $menus = $this->model_configuracion->modulos($this->gestion);
        //$menus = $this->model_control_menus->menu_segun_roles($fun_id);
        
        $vector;
        $n = 0;
        if(count($menus)!=0){
            if($this->tp_adm==1){
                $menus=$this->model_configuracion->list_modulos();
                foreach ($menus as $fila) {
                    $vector[$n] = $this->html_menu_opciones($fila['mod_id']);
                    $n++;
                }
            }
            elseif($this->tp_adm==3){
                $vector[0]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/consulta/mis_operaciones"  onclick="reporte_internos()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/impresora.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">REPORTES POA-INTERNOS</h1>
                        </div>
                        </a>
                    </div>';
            }
            else{
                foreach ($menus as $fila) {
                    $vector[$n] = $this->html_menu_opciones($fila['mod_id']);
                    $n++;
                }
            }
        }
        else{
            if($fun_id==399){
                $vector[0]=
                    '<div class="alert alert-danger" role="alert" align=center>
                      NO EXISTEN MODULOS HABILITADOS PARA LA GESTI&Oacute;N '.$this->gestion.'
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/admin/dm/9/" onclick="mantenimiento()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/mantenimiento1.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">MANTENIMIENTO</h1>
                        </div>
                        </a>
                    </div>';
            }
            else{
                $vector[0]=
                '<div class="alert alert-danger" role="alert" align=center>
                    NO EXISTEN MODULOS HABILITADOS PARA LA GESTI&Oacute;N '.$this->gestion.', CONTACTESE CON EL DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N
                </div>';
            }
            
        }
        
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


    public function dashboard_menu($opcion){
        if($this->session->userdata('logged')){
            $this->load->model('menu_modelo');
            $dato_menu['se'] = '1900';//sesion
            $dato_menu['ay'] = '1900';//ayuda
            switch ($opcion) {
                case 1:
                    $enlaces = $this->menu_modelo->get_Modulos(1);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                  //  $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'PROGRAMACION POA';
                    break;
                case 2:
                    $enlaces = $this->menu_modelo->get_Modulos(2);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                  //  $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'MODIFICACIONES DEL POA';
                    break;
                case 3:
                $enlaces = $this->menu_modelo->get_Modulos(3);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                   // $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'REGISTRO DE EJECUCIÒN';
                    break;
                case 4:

                $enlaces = $this->menu_modelo->get_Modulos(4);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                   // $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'ADMINISTRACI&Oacute;N DE PROYECTOS PLURIANUALES';
                    break;
                case 6:

                    $enlaces = $this->menu_modelo->get_Modulos(6);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                    //$data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'SISTEMA DE INFORMACI&Oacute;N GEOGRAFICA';
                    break;

                case 7:

                    $enlaces = $this->menu_modelo->get_Modulos(7);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                   // $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'REPORTES';
                    break;
                case 9:

                    $enlaces = $this->menu_modelo->get_Modulos(9);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                   // $data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'MANTENIMIENTO';
                    break;

                case 10:

                    $enlaces = $this->menu_modelo->get_Modulos(10);
                    //GUARDA LOS ENLACES PADRES
                    $data['enlaces'] = $enlaces;
                    for ($i = 0; $i < count($enlaces); $i++) {
                        $subenlaces[$enlaces[$i]['o_child']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
                    }
                    $data['subenlaces'] = $subenlaces;
                    //$data['datos'] = $this->model_pei->pei_mision_get();
                    $data['titulo'] = 'REPORTES';
                    break;

            }
            //$data['main_content'] = 'admin/menu_opcion';
            //$this->load->view('includes/template', $data);
            $this->load->view('includes/header');
            $this->load->view('includes/menu_lateral',$data);
            $this->load->view('admin/menu_opcion');
            $this->load->view('includes/footer');
        } else {
            redirect('admin/dashboard');
        }
    }

    function __encrip_password($password){
        return md5($password);
    }

    function validate_credentials(){
        $this->load->model('Users_model');
        if(isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['dat_captcha']) && isset($_POST['tp'])){
            if($this->input->post('user_name') && preg_match('/^[a-zA-Z0-9_.\/]*$/i', $this->input->post('password')) && preg_match('/^[A-Z0-9\/]*$/i', $this->input->post('dat_captcha'))){
                
                if(md5($this->input->post('dat_captcha'))==$this->input->post('captcha')){
                    $user_name = $this->security->sanitize_filename(strtoupper(htmlspecialchars($this->input->post('user_name'))), TRUE) ;
                    $password = $this->security->sanitize_filename($this->input->post('password'), TRUE); 
                 
                    if($this->input->post('tp')==0){ /// Administrador
                        
                        $is_valid = $this->model_funcionario->verificar_loggin($this->security->xss_clean($user_name), $this->security->xss_clean($password));
                        if($is_valid['bool']){
                            $this->session->set_userdata($this->session_administrador($is_valid['fun_id']));
                            if($this->session->userData('rol_id')!=9){
                                redirect('admin/dashboard');
                            }
                            else{
                                if(count($this->model_componente->get_datos_componente($this->session->userData('com_id'),$this->session->userData('gestion')))!=0){
                                    redirect('seguimiento_poa');
                                }
                                else{
                                    $this->session->sess_destroy();
                                    $this->session->set_flashdata('warning', 'RESPONSABLE NO HABILITADO PARA LA PRESENTE GESTION');
                                    redirect('default_controller', 'refresh');
                                }
                            }
                            
                        }
                        else{
                            $this->session->sess_destroy();
                            $this->session->set_flashdata('warning', 'DATOS DE USUARIO SON INCORRECTOS !!!');
                            redirect('default_controller', 'refresh');
                        }
                    }
                    else{ //// Establecimiento de salud
                        $gestion = $this->Users_model->obtener_gestion();
                        $is_valid=$this->model_estructura_org->verif_establecimiento_ingreso($user_name,$password,$gestion[0]['ide']);
          
                        if($is_valid['bool']){
                            $this->session->set_userdata($this->session_establecimiento($is_valid['act_id']));
                            redirect('seguimiento_establecimientos');
                        }
                        else{
                            $this->session->sess_destroy();
                            $this->session->set_flashdata('warning', 'DATOS INCORRECTOS !!!');
                            redirect('default_controller', 'refresh');
                        }
                    }
                }
                else{
                    $this->session->sess_destroy();
                    $this->session->set_flashdata('warning', 'DATOS DE CÓDIGO SON INCORRECTOS !!!');
                    redirect('default_controller', 'refresh');
                }

            }
            else{
                $this->session->sess_destroy();
                $this->session->set_flashdata('danger','DATOS NO VALIDOS !!');
                redirect('default_controller', 'refresh');
            }
            
        }
        else{
            $this->session->sess_destroy();
            $this->session->set_flashdata('danger','DATOS NO VALIDOS !!');
            redirect('default_controller', 'refresh');
        }
        
    }

    /// Sesion Administrador
    public function session_administrador($fun_id){
        $this->session->sess_destroy();
        $data = $this->Users_model->get_datos_usuario($fun_id);

        $gestion = $this->Users_model->obtener_gestion();
        $entidad = $this->Users_model->get_entidad($gestion[0]['ide']);
        $conf = $this->model_configuracion->get_configuracion();
        $modulos = $this->model_configuracion->modulos($conf[0]['ide']);
        $entidad = $entidad->conf_nombre_entidad;
        $data = array(
            'user_name' => $data[0]['fun_usuario'],
            'funcionario' => $data[0]['fun_nombre']." ".$data[0]['fun_paterno'],
            'usuario' => $data[0]['fun_usuario'],
            'cargo' => $data[0]['fun_cargo'],
            'fun_estado' => $data[0]['fun_estado'],
            'com_id' => $data[0]['cm_id'], /// componente para el seguimeinto
            'fun_id' => $data[0]['fun_id'],
            'tp_rep' => 1, /// tp rep 1:borrador, 0: Limpio
            'rol_id' => $data[0]['rol_id'],
            'adm' => $data[0]['fun_adm'],
            'tp_adm' => $data[0]['tp_adm'],
            'dist' => $data[0]['fun_dist'], /// Distrital
            'name_distrital' => $data[0]['dist_distrital'],
            'dist_tp' => $data[0]['dist_tp'],
            'dep_id' => $data[0]['dep_id'],
            'gestion' => $gestion[0]['ide'],
            'mes' => $gestion[0]['conf_mes'],
            'entidad' => $gestion[0]['conf_nombre_entidad'],
            'trimestre' => $gestion[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
            'verif_ppto' => $gestion[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
            'tr_id' => ($gestion[0]['conf_mes_otro']+$gestion[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
            'tp_msn' => $gestion[0]['tp_msn'], /// tipo de mensaje 1: rojo, 2: amarillo, 3: verde
            'mensaje' => $gestion[0]['conf_mensaje'], /// Mensaje
            'rd_poa' => $gestion[0]['rd_aprobacion_poa'], /// Resolucion Directorio POA
            'tp_usuario' => 0,
            'mes_actual'=>$this->verif_mes_gestion(),
            'modulos' => $modulos,
            'desc_mes' => $this->mes_texto($gestion[0]['conf_mes']),
            'name' => 'SIIPLAS V1.0',
            'direccion' => 'DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N',
            'sistema' => 'SISTEMA DE PLANIFICACI&Oacute;N DE SALUD - SIIPLAS V2.0',
            'sistema_pie' => 'SIIPLAS - Sistema de Planificaci&oacute;n de Salud',
            'logged' => true
        );

        return $data;
    }


    /// Sesion Establecimiento
    public function session_establecimiento($act_id){
        $this->session->sess_destroy();
        $gestion = $this->Users_model->obtener_gestion();
        $actividad=$this->model_estructura_org->datos_unidad_organizacional($act_id,$gestion[0]['ide']);
        
        $data = array(
            'user_name' => $actividad[0]['dato_ingreso'],
            'act_id' => $actividad[0]['act_id'],
            'usuario' => $actividad[0]['tipo'].' '.$actividad[0]['act_descripcion'].' '.$actividad[0]['abrev'],
            'estado' => $actividad[0]['act_estado'],
            'dist' => $actividad[0]['dist_id'], /// Distrital
            'name_distrital' => $actividad[0]['dist_distrital'],
            'adm' => 2,
            'fun_id' => 399,
            'dep_id' => $actividad[0]['dep_id'],
            'img' => $actividad[0]['img'],
            'tp_adm' => 0,
            'gestion' => $gestion[0]['ide'],
            'mes' => $gestion[0]['conf_mes'],
            'entidad' => $gestion[0]['conf_nombre_entidad'],
            'trimestre' => $gestion[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
            'verif_ppto' => $gestion[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
            'tr_id' => ($gestion[0]['conf_mes_otro']+$gestion[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
            'tp_msn' => $gestion[0]['tp_msn'], /// tipo de mensaje 1: rojo, 2: amarillo, 3: verde
            'mensaje' => $gestion[0]['conf_mensaje'], /// Mensaje
            'rd_poa' => $gestion[0]['rd_aprobacion_poa'], /// Resolucion Directorio POA
            'tp_usuario' => 1,
            'mes_actual'=>$this->verif_mes_gestion(),
            'desc_mes' => $this->mes_texto($gestion[0]['conf_mes']),
            'name' => 'SIIPLAS V1.0',
            'direccion' => 'DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N',
            'sistema' => 'SISTEMA DE PLANIFICACI&Oacute;N DE SALUD - SIIPLAS V2.0',
            'sistema_pie' => 'SIIPLAS - Sistema de Planificaci&oacute;n de Salud',
            'logged' => true
        );

        return $data;
    }



    function logout(){
        $this->session->sess_destroy();
        redirect('admin/dashboard');
    }

    function tasks(){
        $this->load->view('ajax/notify/tasks');
    }

    function notifications(){
        $this->load->view('ajax/notify/notifications');
    }

    function mail(){
        $this->load->view('ajax/notify/mail');
    }

    function menu(){
        $this->load->model('menu_modelo');
        $enlaces = $this->menu_modelo->get_Modulos();
        $data['enlaces'] = $enlaces;
        for ($i = 0; $i < count($enlaces); $i++) {
            $subenlaces[$enlaces[$i]['idchild']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['idchild'], $this->session->userdata('user_name'));
        }
        $data['subenlaces'] = $subenlaces;/**/
        //$this->load->view('menu',$data);
    }

    function menu_enlace($sup){
        $this->load->model('menu_modelo');
        $data['enlaces'] = $this->menu_modelo->get_Enlaces(0);
    }

    function mision(){
        $this->load->model('menu_modelo');
        $enlaces = $this->menu_modelo->get_Modulos(1);
        $data['enlaces'] = $enlaces;
        for ($i = 0; $i < count($enlaces); $i++) {
            $subenlaces[$enlaces[$i]['idchild']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['idchild'], $this->session->userdata('user_name'));
        }
        $data['subenlaces'] = $subenlaces;
        $data['datos'] = $this->model_pei->pei_mision_get();
        $data['titulo'] = 'PROGRAMACI�N';

        //load the view
        $data['main_content'] = 'admin/marco_institucional/mision/mision';
        $this->load->view('includes/template', $data);
    }

    function vision(){

        $this->load->model('menu_modelo');
        $enlaces = $this->menu_modelo->get_Modulos(1);
        $data['enlaces'] = $enlaces;
        for ($i = 0; $i < count($enlaces); $i++) {
            $subenlaces[$enlaces[$i]['idchild']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['idchild'], $this->session->userdata('user_name'));
        }
        $data['subenlaces'] = $subenlaces;
        $data['datos'] = $this->model_pei->pei_mision_get();
        $data['titulo'] = 'PROGRAMACI�N';
        //load the view
        $data['main_content'] = 'admin/marco_institucional/vision/vision';
        $this->load->view('includes/template', $data);
    }
    /*------------------------------------------------------*/
    /*  mision registrar o editar
    /*------------------------------------------------------*/
    function pei_accion($accion){
        $json = array();
        switch ($accion) {
            case 'mision_editar':
                $json['msj'] = 'mision editado';
                $json['success'] = true;
                //$data['material']=$this->model_pei->proveedores_editar();

                echo json_encode($json);
                break;
            case 'mision_guardar':
                $json['msj'] = 'mision Agregado';
                $json['success'] = true;
                $data['material'] = $this->model_pei->pei_mision_edit();
                echo json_encode($json);
                break;
            case 'vision_editar':
                $json['msj'] = 'vision editado';
                $json['success'] = true;
                //$data['material']=$this->model_pei->proveedores_editar();

                echo json_encode($json);
                break;
            case 'vision_guardar':
                $json['msj'] = 'vision Agregado';
                $json['success'] = true;
                $data['material'] = $this->model_pei->pei_vision_edit();
                echo json_encode($json);
                break;
        }
    }
    /*------------------------------------------------------*/
    /*  acerca de ..
    /*------------------------------------------------------*/
    function acerca() {
        $this->load->model('menu_modelo');
        $enlaces = $this->menu_modelo->get_Modulos(1);
        $data['enlaces'] = $enlaces;
        for ($i = 0; $i < count($enlaces); $i++) {
            $subenlaces[$enlaces[$i]['idchild']] = $this->menu_modelo->get_Enlaces($enlaces[$i]['idchild'], $this->session->userdata('user_name'));
        }
        $data['subenlaces'] = $subenlaces;
        $data['datos'] = $this->model_pei->pei_mision_get();
        //load the view
        $data['main_content'] = 'admin/pei/ayuda/acerca_de';
        $this->load->view('includes/template', $data);
    }

    public function combo_fases_etapas(){
        //echo "urbanizaciones";
        $salida = "";
        $id_pais = $_POST["elegido"];
        // construimos el combo de ciudades deacuerdo al pais seleccionado
        $combog = pg_query("SELECT * FROM _etapas WHERE eta_clase=$id_pais");
        $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE FASE', 'cp1252', 'UTF-8') . "</option>";
        while ($sql_p = pg_fetch_row($combog)) {
            $salida .= "<option value='" . $sql_p[0] . "'>" . $sql_p[1] . "</option>";
        }
        echo $salida;
    }

    public function combo_clasificador($accion = ''){
        $salida = "";
        $accion = $_POST["accion"];

        switch ($accion) {
            case 'cl2':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM _clasificadorsectorial WHERE nivel=\'2\' AND codsec=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Sub Sector', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[5] . "'>" . mb_convert_encoding($sql_p[0] . " - " . $sql_p[9] . " - " . $sql_p[6], 'cp1252', 'UTF-8') . "</option>";
                }

                echo $salida;
                //return $salida;
                break;

            case 'cl3':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM _clasificadorsectorial WHERE nivel=\'3\' AND codsubsec=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Actividad Economica', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[0] . "'>" . $sql_p[0] . " - " . $sql_p[9] . " - " . $sql_p[1] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'funcion':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM _finalidadfuncion WHERE  fifu_depende=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Funcion', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[0] . "'>" . $sql_p[0] . " - " . $sql_p[2] . " - " . $sql_p[4] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'clase_fn':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM _finalidadfuncion WHERE  fifu_depende=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[0] . "'>" . $sql_p[0] . " - " . $sql_p[2] . " - " . $sql_p[4] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            ////////////////////// PEDES//////////////////////////////////

            case 'pedes_2':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM pdes WHERE pdes_depende=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Meta', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[7] . "'>" . $sql_p[7] . " - " . $sql_p[2] . " - " . $sql_p[3] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'pedes_3':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM pdes WHERE pdes_depende=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Resultado', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[7] . "'>" . $sql_p[7] . " - " . $sql_p[2] . " - " . $sql_p[3] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'pedes_4':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT pdes_id FROM pdes WHERE pdes_depende=\'' . $id_pais . '\'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Accion', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[7] . "'>" . $sql_p[7] . " - " . $sql_p[2] . " - " . $sql_p[3] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            /////---------------------------------------------- PEI
            case 'pei_2':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM pei WHERE pei_depende=\'' . $id_pais . '\' and pei_gestion='.$this->session->userdata("gestion").'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Resultado de Mediano Plazo', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[7] . "'>" . $sql_p[7] . " - " . $sql_p[2] . " - " . $sql_p[3] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

            case 'pei_3':
                $salida = "";
                $id_pais = $_POST["elegido"];

                $combog = pg_query('SELECT * FROM pei WHERE pei_depende=\'' . $id_pais . '\' and pei_gestion='.$this->session->userdata("gestion").'');
                $salida .= "<option value=''>" . mb_convert_encoding('Seleccione Estrategia', 'cp1252', 'UTF-8') . "</option>";
                while ($sql_p = pg_fetch_row($combog)) {
                    $salida .= "<option value='" . $sql_p[7] . "'>" . $sql_p[7] . " - " . $sql_p[2] . " - " . $sql_p[3] . "</option>";
                }
                echo $salida;
                //return $salida;
                break;

        }
        /**/
    }

    function login_exit() {
        $this->load->view('admin/login');
    }

    public function mes_texto($mes){
        switch ($mes) {
            case '1':
                $texto = 'Enero';
                break;
            case '2':
                $texto = 'Febrero';
                break;
            case '3':
                $texto = 'Marzo';
                break;
            case '4':
                $texto = 'Abril';
                break;
            case '5':
                $texto = 'Mayo';
                break;
            case '6':
                $texto = 'Junio';
                break;
            case '7':
                $texto = 'Julio';
                break;
            case '8':
                $texto = 'Agosto';
                break;
            case '9':
                $texto = 'Septiembre';
                break;
            case '10':
                $texto = 'Octubre';
                break;
            case '11':
                $texto = 'Noviembre';
                break;
            case '12':
                $texto = 'Diciembre';
                break;
            default:
                $texto = 'Sin Mes asignado';
                break;
        }
        return $texto;
    }


    /*--- verifica datos del mes y año ---*/
    public function verif_mes_gestion(){
      //$valor=9;
      $valor=ltrim(date("m"), "0"); // numero mes
      $mes=$this->mes_nombre_completo($valor);

      $datos[1]=$valor; // numero del mes
      $datos[2]=$mes[$valor]; // mes
      $datos[3]=$this->gestion; // Gestion

      return $datos;
    }

    /*------ NOMBRE MES -------*/
    function mes_nombre_completo(){
        $mes[1] = 'ENERO';
        $mes[2] = 'FEBRERO';
        $mes[3] = 'MARZO';
        $mes[4] = 'ABRIL';
        $mes[5] = 'MAYO';
        $mes[6] = 'JUNIO';
        $mes[7] = 'JULIO';
        $mes[8] = 'AGOSTO';
        $mes[9] = 'SEPTIEMBRE';
        $mes[10] = 'OCTUBRE';
        $mes[11] = 'NOVIEMBRE';
        $mes[12] = 'DICIEMBRE';

      return $mes;
    }
}