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
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
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

        $this->notificaciones=$this->session->userData('estado_notificaciones');
        $this->verif_mes=$this->session->userdata('mes_actual');
        $this->conf_ajuste_poa=$this->session->userdata('conf_ajuste_poa'); 
        $this->conf_credenciales=$this->session->userdata('conf_psw'); /// configuracion de credenciales
        $this->fun_credencial=$this->session->userdata('credencial_funcionario'); /// credenciales del funcionario
        //$this->load->library('genera_informacion');
    }

    public function vaca_404()
    {
        $this->load->view('rewriten_404');
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
                'mes_actual'=>$this->verif_mes_gestion($conf[0]['conf_mes']),
                'estado_notificaciones' => $conf[0]['conf_poa'], /// Estado para las Notificaciones 0:no activo, 1: Habilitado
                'conf_estado' => $conf[0]['conf_estado'], //7 Estado 1: Activo, 0: No activo
                'conf_poa_estado' => $conf[0]['conf_poa_estado'], //7 Estado poa-presupuesto 1: inicial, 2 ajustado, 3 aprobado
                'trimestre' => $conf[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
                'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
                'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
                'verif_ppto' => $conf[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
                'conf_form4' => $conf[0]['conf_form4'], /// Estado de Registro del formulario N4, 0 (Inactivo), 1 (Activo)
                'conf_form5' => $conf[0]['conf_form5'], /// Estado de Registro del formulario N5, 0 (Inactivo), 1 (Activo)
                'conf_mod_ope' => $conf[0]['conf_mod_ope'], /// Estado de Modificacion del formulario N4, 0 (Inactivo), 1 (Activo)
                'conf_mod_req' => $conf[0]['conf_mod_req'], /// Estado de Modificacion del formulario N5, 0 (Inactivo), 1 (Activo)
                'conf_certificacion' => $conf[0]['conf_certificacion'], /// Estado de Certificacion del formulario N5, 0 (Inactivo), 1 (Activo)
                'rd_poa' => $conf[0]['rd_aprobacion_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
                'conf_ajuste_poa' => $conf[0]['conf_ajuste_poa'] /// Ajuste POA
            );
            $this->session->set_userdata($data);

        redirect('admin/dashboard','refresh');
    }


/// cambia mes para ejec inversion
  function update_mes(){
    if($this->input->is_ajax_request() && $this->input->post()){
        $this->form_validation->set_rules('i', 'Mes', 'required|trim');
        $mes_id= $this->security->xss_clean($post['mes_id']);
        $conf=$this->model_proyecto->get_configuracion($this->gestion); 


         /*$data = array(
                'gestion' => $conf[0]['ide'],
                'mes' => $mes_id,
                'mes_actual'=>$this->verif_mes_gestion($mes_id),
                'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
                'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
                'verif_ppto' => $conf[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
                'conf_ajuste_poa' => $conf[0]['conf_ajuste_poa'] /// Ajuste POA
            );
            $this->session->set_userdata($data);*/
            $this->session->set_userdata('mes_actual', $this->verif_mes_gestion($mes_id));

    }else{
        show_404();
    }
  } 
    /*-------- Valida Cambio trimestre Session -----------*/
    public function cambiar_trimestre(){
        $conf=$this->model_proyecto->get_configuracion($this->gestion);

         $data = array(
                'gestion' => $conf[0]['ide'],
                'mes_actual'=>$this->verif_mes_gestion($conf[0]['conf_mes']),
                'estado_notificaciones' => $conf[0]['conf_poa'], /// Estado para las Notificaciones 0:no activo, 1: Habilitado
                'conf_estado' => $conf[0]['conf_estado'], //7 Estado 1: Activo, 0: No activo
                'conf_poa_estado' => $conf[0]['conf_poa_estado'], //7 Estado poa-presupuesto 1: inicial, 2 ajustado, 3 aprobado
                'trimestre' => $this->input->post('trimestre_usu'), /// Trimestre 1,2,3,4
                'tr_id' => ($conf[0]['conf_mes_otro']+$conf[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
                'desc_mes' => $this->mes_texto($conf[0]['conf_mes']),
                'verif_ppto' => $conf[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
                'conf_ajuste_poa' => $conf[0]['conf_ajuste_poa'] /// Ajuste POA
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


            $tabla='
            <style>
                .caja {
                font-family: sans-serif;
                font-size: 28px;
                font-weight: 100;
                color: #000000;
                background: #d1d9dc;
                margin: 0 0 15px;
                overflow: hidden;
                padding: 3px;
                }

                #loading {
                    display: none;
                    position: fixed;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 24px;
                    z-index: 1000;
                }

                #loadingpws {
                    display: none;
                    position: fixed;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    font-size: 24px;
                    z-index: 1000;
                }

                .modal {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.6);
                }

                .modal-content {
                    background: white;
                    width: 400px;
                    margin: 50px auto;
                    padding: 30px;
                    text-align: center;
                    border-radius: 8px;
                }

                .open-btn {
                    background: #4CAF50;
                    color: white;
                    font-size: 16px;
                }

                .close-btn {
                    background: #f44336;
                    color: white;
                }
            </style>';
            $tabla.='
        <div id="kc-content-wrapper">
        <input name="base" type="hidden" value="'.base_url().'">
        <div class="background-siat-login overflow-hidden d-flex justify-content-center align-items-center" style="height: 100vh;">
            <div class="container px-md-5 text-center text-lg-start my-5 ">
                <div class="row gx-lg-5 align-items-center mb-sm-0">
                    <div class="col-lg-6 mb-sm-0 mb-lg-0 text-center mt-lg-0" style="z-index: 10">
                        <div class="imgSiat">
                            <picture>
                                <source srcset="'.base_url().'assets/login_nuevo/img/logo_CNS_header.png" media="(min-width: 992px)" width="200px" height="auto">
                                <source srcset="'.base_url().'assets/login_nuevo/img/logo_CNS_header.png" media="(min-width: 768px)" width="200px" height="auto">
                                <img class="img-fluid animateBolivia" src="'.base_url().'assets/login_nuevo/img/logo_CNS_header.png"alt="logoSiatBolivia" width="200px" height="auto">
                            </picture>
                            
                            <h1 class="my-5 display-5 fw-bold ls-tight text-center titleSiat" style="color: hsl(218, 81%, 95%)">
                                Sistema de Planificaci&oacute;n y Seguimiento al POA
                                <br/>
                                <span style="color: #FFFF">SIIPLAS v2.0</span>
                            </h1>
                            
                            <div class="redesSocialesHeader">
                                <a href="https://www.facebook.com/CNS.Bolivia/" target="_blank"><img class="rrss mx-2" src="'.base_url().'assets/login_nuevo/img/facebook.svg"/ alt="rrssFacebook"></a>
                                <a href="https://www.instagram.com/cnsbolivia/" target="_blank"><img class="rrss mx-2" src="'.base_url().'assets/login_nuevo/img/instagram.svg"/ alt="rrssinstagram"></a>
                                <a href="https://www.youtube.com/channel/UCH8i2IHse60iSiyeYAihomg" target="_blank"><img class="rrss mx-2" src="'.base_url().'assets/login_nuevo/img/youtube.svg"/ alt="rrssYoutube"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-lg-0 position-relative">
                    <br/>
                        <div class="card bg-card">
                            <div class="card-body px-4 py-4 px-md-5">

                                
                                <div id="loading"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
                                <form role="form" action="'.base_url().'index.php/admin/validate" method="post" id="form" class="login-form">
                                    <input type="hidden" name="tp" id="tp" value="0">
                                    <div align=center>
                                        <b style="color:black;">DEPARTAMENTO NACIONAL DE PLANIFICACIÓN - C.N.S.</b>
                                    </div>';
                                        if($this->session->flashdata('success')){
                                            $tabla.='
                                                <div class="alert alert-success" role="alert">
                                                <h4 class="alert-heading">Solicitud Enviada!</h4>
                                                <p>'.$this->session->flashdata('success').'</p>
                                                </div>';
                                            
                                            }
                                            elseif($this->session->flashdata('danger')){
                                                $tabla.='
                                                <div class="alert alert-danger" role="alert">
                                                <h4 class="alert-heading">Solicitud Enviada!</h4>
                                                <p>'.$this->session->flashdata('danger').'</p>
                                                </div>';
                                            }
                                    $tabla.='
                                    <h5 class="text-center fw-bold my-4 titleBienvenido">Bienvenido/a!</h5>
                                    <div class="row align-items-center">
                                        <div class="col">
                                        <div id="form-login-username" class="form-group">      
                                            <input type="radio" name="radio-inline" id="radio0" checked="checked">
                                            <i></i><b>Unidad Administrativa</b></label> &nbsp;&nbsp; 
                                            <input type="radio" name="radio-inline" id="radio1">
                                            <i></i><font color="#146f64"><b>Establecimiento de Salud</b></font></label>
                                        </div>
                                        </div>
                                    </div>

                                    <input id="deviceId" class="dOt" name="deviceId">

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="form-floating mb-2">
                                                <input tabindex="1" type="text" class="form-control form-input-bg" name="user_name" placeholder="USUARIO" minlength="5" maxlength="20" autocomplete="off" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                                <label for="user_name">USUARIO SIIPLAS</label>
                                                <div id="usu" class="text-danger text-start" style="font-size:9px;visibility: hidden;">
                                                   <b> Este campo es requerido</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto pf-0">
                                            <img src="'.base_url().'assets/login_nuevo/img/help.svg" class="tootip" title="USUARIO: Acceso asignado por el Departamento Nacional de Planificación"/>
                                        </div>
                                    </div>

                                    <input id="deviceId" class="dOt" name="deviceId">

                                    <div class="row align-items-center">
                                        <div class="col">
                                            <div class="form-floating mb-2">
                                                <input tabindex="3" id="password" class="form-control form-input-bg" name="password" type="password" autocomplete="off" placeholder="CONTRASEÑA" minlength="6" maxlength="20"/>
                                                <label for="password">PASSWORD</label>
                                                <div id="pass" class="text-danger text-start" style="font-size:9px; visibility: hidden;" style="font-size:8px;">
                                                  <b>  Este campo es requerido</b>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto pf-0">
                                            <img src="'.base_url().'assets/login_nuevo/img/help.svg" onclick="togglePassword(\'password\')" class="tootip" id="toggleIcon" title="CLAVE DE ACCESO: Acceso asignado por el Departamento Nacional de Planificación"/>
                                        </div>
                                    </div>

                                    <div class="text-center py-3">
                                        <p class="caja" id="refreshs" style="text-align:center"><b>'.$data['cod_captcha'].'</b></p>
                                        <input type="hidden" name="captcha" id="captcha"  value="'.$data['captcha'].'" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                    </div>

                                    <div class="mb-4">
                                        <input tabindex="4" id="dat_captcha" name="dat_captcha" type="text" class="form-control form-input-bg text-center" placeholder="Ingrese el texto de la imagen" autofocus minlength="4" maxlength="4" >
                                        <div id="cat" class="text-danger text-start" style="font-size:9px; visibility: hidden;" style="font-size:8px;">
                                            <b>  Este campo es requerido</b>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 mt-2">
                                        <input tabindex="4" class="btn btn-lg mdl-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" name="login" id="kc-login" type="submit" value="INGRESAR"/>
                                    </div>
                                </form>
                                <br>
                                <a href="#" style="color:blue; font-size:11px;" onclick="show()">Olvidaste tu Contraseña?</a>

                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>

            <div id="modal" class="modal">
                
                <div class="modal-content">
                <a href="#" onclick="hide()" align=right>Cerrar (x)</a>
                <hr>
                    <h2>RECUPERAR CONTRASEÑA</h2>
                    <p>Registrar los siguientes campos.</p>

                    <div id="loadingpws" ><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
                    <form role="form" action="'.base_url().'index.php/validatepsw" method="post" id="formpws" class="login-form">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-floating mb-2">
                                    <input tabindex="1" type="text" class="form-control form-input-bg" name="user_namepws" placeholder="USUARIO" minlength="5" maxlength="20" autocomplete="off" style="text-transform:uppercase;" oninput="this.value = this.value.toUpperCase();">
                                    <label for="user_namepws">Usuario SIIPLAS</label>
                                    <div id="usupsw" class="text-danger text-start" style="font-size:9px;visibility: hidden;">
                                       <b> Este campo es requerido</b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input id="deviceId" class="dOt" name="deviceId">

                        <div class="row align-items-center">
                            <div class="col">
                                <div class="form-floating mb-2">
                                    <input tabindex="3" id="emailpws" class="form-control form-input-bg" name="emailpws" type="text" autocomplete="off" placeholder="CORREO ELECTRONICO" minlength="6" maxlength="50"/>
                                    <label for="emailpws">Correo Electronico</label>
                                    <div id="email" class="text-danger text-start" style="font-size:9px; visibility: hidden;" style="font-size:8px;">
                                      <b>  Este campo es requerido</b>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-2">
                            <input tabindex="4" class="btn btn-lg mdl-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent" style="width: 100%;" name="login" id="kc-login" type="submit" value="Enviar"/>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        
        <script>
            function show() {
                document.getElementById("modal").style.display = "block";
            }

            function hide() {
                document.getElementById("modal").style.display = "none";
                window.location.reload();
            }
        </script>';


            $data['formulario']=$tabla;



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
            $data['vector_menus'] = $this->menu_principal_roles(); //// MENU SEGUN EL ROL DEL USUARIO
            $data['resp']=$this->session->userdata('funcionario');
            $data['dist_id']=$this->dist_id;
            $data['res_dep']=$this->tp_resp();
            $ddep = $this->model_proyecto->dep_dist($this->dist_id);
            $data['dep_id']=$ddep[0]['dep_id'];
            $data['tmes']=$this->model_evaluacion->trimestre();
            $data['mes']=$this->verif_mes;
            $data['gestiones']=$this->list_gestiones();
            $data['list_trimestre']=$this->list_trimestre();
            $rol=$this->model_funcionario->get_rol($this->fun_id);
            $distritales=$this->model_proyecto->lista_distritales();

            
            $tabla='';
            $tabla.='
            <div class="modal fade" id="modal_seguimiento_nacional" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    <form id="form_seg" name="form_seg" class="form-horizontal" method="post">
                        <h3 class="alert alert-info"><center>SEGUIMIENTO POA - '.$this->verif_mes[2].' / '.$this->gestion.'</center></h3>   
                        <fieldset>
                          <div class="form-group">
                            <div class="col-md-12">
                              <select name="seg_reg" id="seg_reg" class="form-control" required>
                                <option value="0">seleccionar Distrital...</option>';
                                foreach($distritales as $row){
                                    $tabla.='<option value="'.$row['dist_id'].'">'.strtoupper($row['dist_distrital']).'</option>';
                                }
                            $tabla.='
                              </select>
                            </div>
                          </div>
                        </fieldset>
                    </form>
                  </div>
                </div>
              </div>
            </div>';
            $data['select_distrital']=$tabla;

            $data['mensaje']='';
            $data['seguimiento_poa']='';
            $data['popup_saldos']='';
            $data['popup_credenciales']='';
            $data['solicitudes_pass']='';


            //// ------ SOLICITUDES DE PASSWORD
            $solicitudes_password=$this->model_funcionario->listado_solicitud_contraseñas();

            if(count($solicitudes_password)!=0 & $this->fun_id==399){
                $data['solicitudes_pass']='
                <input name="base" type="hidden" value="'.base_url().'">
                    <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" style="">
                        <div class="modal-dialog modal-login" id="mdialTamanio_psw">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 style="color:blue; text-align:center"><b>Atención de Solicitudes de Contraseña</b></h4>
                                </div>
                                <div class="modal-body">
                                    <div style="color:blue; font-size:10px;">Tienes ('.count($solicitudes_password).') Solicitudes por atender..</div>
                                    <table class="table table-bordered">
                                    <thead>
                                      <tr title="" >
                                        <th scope="col" style="text-align:center;">#</th>
                                        <th scope="col" style="text-align:center;">TRABAJADOR</th>
                                        <th scope="col" style="text-align:center;">USUARIO</th>
                                        <th scope="col" style="text-align:center;">DISTRITAL</th>
                                        <th scope="col" style="text-align:center;">CORREO ELECTRONICO</th>
                                        <th scope="col" style="text-align:center;"></th>
                                      </tr>
                                    </thead>
                                    <tbody>';
                                    $nro=0;
                                    foreach($solicitudes_password as $row){
                                        $nro++;
                                        $data['solicitudes_pass'].='
                                        <tr>
                                            <td>'.$nro.'</td>
                                            <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                                            <td>'.$row['fun_usuario'].'</td>
                                            <td>'.$row['dist_distrital'].'</td>
                                            <td><b>'.$row['email'].'</b></td>
                                            <td>
                                                <a href="javascript:abreVentana(\''.site_url("").'/solpassw/'.$row['fun_id'].'\');" class="btn btn-default" title="GENERAR REPORTE"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/><br><font size=1><b>USUARIO</b></font></a>
                                            </td>
                                        </tr>';
                                    }
                                    $data['solicitudes_pass'].='
                                    </tbody>
                                    </table>                                  
                                </div>
                            </div>
                        </div>
                    </div>';
            }


            //// ------ CREDENCIALES
            if(($this->conf_credenciales==1 || $this->fun_credencial==0) & $this->fun_id!=399){
                $data['popup_credenciales']='
                    <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" style="">
                        <div class="modal-dialog modal-login" id="mdialTamanio_saldos">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 style="color:green; text-align:center"><b>ACTUALIZACIÓN DE CREDENCIALES DE ACCESO AL SIIPLAS.</b></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-success" role="alert">
                                        <p>Estimad@: <b>'.$this->session->userdata('funcionario').'</b>, para garantizar la seguridad de información, se implemento nuevas medidas de seguridad para proteger su cuenta de acceso al Sistema de Planificación <b>SIIPLAS.</b></p><br>
                                        <p>Como parte de estas medidas es necesario que actualice sus Credenciales de Acceso. Esta acción es parte de nuestras politicas vigente en el <b>Plan de Seguridad de la Información PISI.</p>
                                        <hr>

                                        <div style="font-size:15px;"><b>POLITICA DE GESTIÓN DE CONTRASEÑAS - Version 1.1</b></div>
                                        <br>
                                 
                                      <ul>
                                        <li>La contraseña debe estar compuesta por una combinación de letras mayúsculas,minúsculas, números y símbolos especiales como ser: “~ ! @ # $ % ^ & * ( ) _ + - = { } | [ ] \ : " ;  < > ? , . /</li>
                                        <li>Toda contraseña de usuarios internos en servicios, sistemas y/o aplicaciones institucionales de la CNS, debe tener una longitud mínima de doce (12) caracteres alfanuméricos y símbolos especiales.</li>
                                        <li>Se debe implementar un historial de contraseñas en todo sistema institucional de la CNS que haga uso de credenciales de acceso, en el que se guarden las contraseñas antiguas para que los usuarios no reutilicen las mismas.</li>
                                      </ul>

                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                <a href="'.base_url().'index.php/admin/logout" class="btn btn-danger">Cerrar sesion</a>
                                <a href="'.base_url().'index.php/admin/mod_contra" class="btn btn-success">Actualizar ahora</a>
                                </div>
                            </div>
                        </div>
                    </div>';
            }


            ///------- Verificando Saldos
              //if($this->conf_ajuste_poa==1 & $this->dist_id!=10 & $this->dist_id!=5 & $this->gestion>2023){
            if($this->conf_ajuste_poa==1){
                if($this->verif_saldos_disponibles_distrital($this->dep_id,$this->dist_id)==1 & $this->dep_id!=10 & $this->gestion>2023){
                
                    $data['popup_saldos']='
                    <div id="myModal" class="modal fade" data-backdrop="static" data-keyboard="false" style="">
                        <div class="modal-dialog modal-login" id="mdialTamanio_saldos">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 style="color:red"><b>AJUSTAR DISTRIBUCION DE SALDOS - GESTIÓN '.$this->gestion.' !!!</b></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-danger" role="alert">
                                      <p>Hola: <b>'.$this->session->userdata('funcionario').'</b>, la <b>'.strtoupper($ddep[0]['dist_distrital']).'</b> a la fecha presenta saldos en su POA '.$this->gestion.' que deben ser ajustados y/o inscritos a traves de Modificaciones POA, mientras no se encuentre ajustado no podra realizar <b>CERTIFICACIONES POA.</b></p>
                                    </div>
                                    '.$this->lista_unidades_con_saldo($this->dep_id,$this->dist_id).'
                                </div>
                                <div class="modal-footer">
                                <a href="'.base_url().'index.php/admin/logout" class="btn btn-danger">salir de la sesion</a>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }

            if($rol[0]['r_id']==11){ /// Usuario para ejecucion de proyectos de inversion
                $data['mensaje']='<div class="alert alert-success" align="center">
                  <a class="alert-link">EJECUCIÓN FINANCIERA DE INVERSIÓN - '.$this->verif_mes[2].' / '.$this->gestion.'</a>
                </div>';
            }
            else{
                $data['mensaje']=$this->mensaje_sistema();   

                ///===================== CONF NOTIFICACION POA =========================
                $dia_cambios = 6;
                $hoy = date("j");

                if ($this->gestion>2024 & ($hoy == $dia_cambios)) {
                   if($this->dep_id==2){ //// Exclusivo para la Regional LA paz
                        $nro_poa=count($this->model_seguimientopoa->get_seguimiento_poa_mes_regional($this->dep_id,$this->verif_mes[1],$this->gestion));;
                    }
                    else{ /// Listado normal
                        $nro_poa=count($this->model_seguimientopoa->get_seguimiento_poa_mes_distrital($this->dist_id,$this->verif_mes[1],$this->gestion));
                    }
                    
                    if($nro_poa!=0){
                        $data['seguimiento_poa']=$this->mensaje_ejecucion_operaciones_mes($nro_poa);
                    }
                }
                else{
                    $data['seguimiento_poa']='';
                }
                ///=================================================


            }

            $this->load->view('admin/dashboard',$data);
            
        } else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }


    /*----- RESPUESTA A SOLICITUDES -----*/
    public function respuesta_pass($fun_id){
        $funcionario=$this->model_funcionario->get_funcionario($fun_id);
        $historial=$this->model_funcionario->historial_contraseñas($fun_id);
        $tabla='';

        $tabla.='
        <hr>
        Buenas estimad@, le escribimos del <b>Departamento Nacional de Planificación</b>, en atención a solicitud de recuperacion de contraseña al Sistema Siiplas, le enviamos por este medio las credenciales de acceso.
        <hr>
        <div style="font-size:30px;"><b>CREDENCIALES DE ACCESO SIIPLAS</b></div><br>
        <b>NOMBRE: </b>'.$funcionario[0]['fun_nombre'].' '.$funcionario[0]['fun_paterno'].' '.$funcionario[0]['fun_materno'].'<br>
        <b>USUARIO: </b>'.$funcionario[0]['fun_usuario'].'<br>
        <b>CONTRASEÑA: </b>'.$this->encrypt->decode($funcionario[0]['fun_password']).';
        <hr>
        Saludos Cordiales.
        <br>
        Atentamente: <b>Wilmer Mendoza Trujillo - ADMINISTRADOR SIIPLAS</b>
        <br>
        <br>
        <br>
        BOTON';


        $update_psw = array(
                'sol_estado' => 1
              );
              $this->db->where('fun_id', $fun_id);
              $this->db->update('solicitudes_psw', $update_psw);


        echo $tabla;
    }


    /*----- VERIFICA SI EXISTE SALDO A DISTRBUIR (DASHBOARD) -----*/
    public function verif_saldos_disponibles_distrital($dep_id,$dist_id){
      $valor=0;
      $ppto_asignado=0;
      $ppto_programado=0;

      if($dep_id==2){ /// Regional La paz
        $asignado=$this->model_ptto_sigep->suma_ptto_regional($dep_id,1);
        $programado=$this->model_ptto_sigep->suma_ptto_regional($dep_id,2);
      }
      else{
        $asignado=$this->model_ptto_sigep->suma_ptto_distrital($dist_id,1);
        $programado=$this->model_ptto_sigep->suma_ptto_distrital($dist_id,2);
      }


      if(count($asignado)!=0){
        $ppto_asignado=$asignado[0]['asignado'];
      }

      if(count($programado)!=0){
        $ppto_programado=$programado[0]['programado'];
      }

      if(round(($ppto_asignado-$ppto_programado),2)>5 || round(($ppto_asignado-$ppto_programado),2)<0){ /// 
        $valor=1;
      }

      return $valor;
    }


    /*----- UNIDADES CON SALDOS A DISTRBUIR (DASHBOARD) -----*/
    public function lista_unidades_con_saldo($dep_id,$dist_id){
        $unidades=$this->model_ptto_sigep->lista_unidades_con_saldo_a_distribuir($dep_id,$dist_id); /// Lista de unidades con saldo disponible
        $tabla='';
        $tabla.='
        <form action="/examples/actions/confirmation.php" method="post">
            <table class="table table-bordered">
              <thead>
                <tr bgcolor="1c7368">
                  <th style="width:1%;text-align:center;color:white;font-size:9px">#</th>
                  <th style="width:20%;text-align:center;color:white;font-size:9px">UNIDAD / ESTABLECIMIENTO / PROY. INVERSIÓN</th>
                  <th style="width:7%;text-align:center;color:white;font-size:9px">PPTO. ASIGNADO '.$this->gestion.' (APROBADO)</th>
                  <th style="width:7%;text-align:center;color:white;font-size:9px">PPTO. PROGRAMADO '.$this->gestion.' (SIIPLAS)</th>
                  <th style="width:7%;text-align:center;color:white;font-size:9px">SALDO '.$this->gestion.'</th>
                  <th style="width:4%;text-align:center;color:white;font-size:9px">AJUSTAR POA</th>
                  <th style="width:1%;text-align:center;color:white;font-size:9px"></th>
                </tr>
              </thead>
              <tbody>';
              $nro=0;
              foreach ($unidades as $row){
                  $nro++;
                  $bg_color='#eef9f3';
                  if($row['saldo']<0){
                    $bg_color='#f9f1ee';
                  }
                  $tabla.='
                  <tr title='.$row['proy_id'].' bgcolor='.$bg_color.'>
                    <td style="text-align:center;font-size:8.5px">'.$nro.'</td>
                    <td style="font-size:8.5px"><b>'.$row['prog'].' - '.$row['tipo'].' '.$row['proy_nombre'].' '.$row['abrev'].'</b></td>
                    <td style="text-align:right;font-size:8.5px"><b>'.number_format($row['asignado'], 2, ',', '.').'</b></td>
                    <td style="text-align:right;font-size:8.5px"><b>'.number_format($row['programado'], 2, ',', '.').'</b></td>
                    <td style="text-align:right;font-size:8.5px"><b>'.number_format($row['saldo'], 2, ',', '.').'</b></td>
                    <td style="text-align:center">
                        <a href="'.site_url("").'/mod/form5/'.$row['proy_id'].'" title="AJUSTE POA" class="btn btn-default" onClick="imprimir_grafico1()">
                            <img src="'.base_url().'assets/Iconos/page_edit.png" WIDTH="20" HEIGHT="20"/>
                        </a>
                    </td>
                    <td style="text-align:center">
                        <a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$row['proy_id'].'\');" class="btn btn-default" title="CUADRO COMPARATIVO"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="20" HEIGHT="20" /></a>
                    </td>
                  </tr>';
                
              }
              $tabla.='
              </tbody>
            </table>
            <div id="loading_saldo"></div>
        </form>';

        return $tabla;
    }




    /*---- LISTA DE OPERACIONES A SER EJECUTADAS EN EL MES ----*/
    public function mensaje_ejecucion_operaciones_mes($nro){
        $tabla='';
        if($this->fun_id==592 || $this->fun_id==709){ /// Exclusivo La paz
            $req=$this->model_notificacion->nro_requerimientos_acertificar_mensual_x_mes_regional($this->dep_id,$this->verif_mes[1]);
        }
        else{
            $req=$this->model_notificacion->nro_requerimientos_acertificar_mensual_x_mes_distrital($this->dist_id,$this->verif_mes[1]);
        }

        
        $ddep = $this->model_proyecto->dep_dist($this->dist_id);
        //$nro_sol=count($this->model_certificacion->lista_solicitudes_cpoa_distrital($this->dist_id));
        /*$solicitudes_cpoa='';
        if($nro_sol!=0){
            $solicitudes_cpoa='<a href="'.site_url("").'/ejec/mis_solicitudes_certpoa" class="btn btn-primary" target="_blanck" title="LISTA DE SOLICITUDES POA"><img src="'.base_url().'assets/Iconos/email_attach.png" width="20" height="20"/>&nbsp;('.$nro_sol.') Solicitud(es) de Certificación POA</a>';
        }*/


        $tit_requerimiento='';
        
        if(count($req)!=0){
            $tit_requerimiento='y '.$req[0]['requerimientos'].' Requerimientos con un monto de Bs. '.number_format($req[0]['monto'], 2, ',', '.').' que deben ser <b>EVALUADOS</b> y <b>CERTIFICADOS</b>';
        }

        
        $tabla.='
            <div class="alert alert-success" role="alert" title='.$this->dist_id.' style="text-align:justify">
                <h4 class="alert-heading"><b>PROCESO DE NOTIFICACIÓN POA '.$this->verif_mes[2].' / '.$this->gestion.' !!</b></h4>
                <p>Hola '.$this->session->userdata('funcionario').', la '.strtoupper($ddep[0]['dist_distrital']).' tiene programado en su POA '.$this->gestion.' para el mes de '.$this->verif_mes[2].' : '.$nro.' Actividades </b> '.$tit_requerimiento.'. </p>
                <hr>
                <p class="mb-0">
                    <a data-toggle="modal" data-target="#modal_form4_mes" id="'.$this->dist_id.'" class="btn btn-success form4_mes" title=""><img src="'.base_url().'assets/Iconos/application_cascade.png" width="20" height="20"/>&nbsp;<b style="font-size:10px">NOTIFICACIÓN GASTO CORRIENTE</b></a>';
                    if(count($this->model_notificacion->list_requerimiento_pinversion_programado_al_mes_distrital($this->dist_id,$this->verif_mes[1]))!=0){
                       $tabla.='&nbsp;<a data-toggle="modal" data-target="#modal_form5_pi_mes" id="'.$this->dist_id.'" class="btn btn-success pi_mes" title=""><img src="'.base_url().'assets/Iconos/application_cascade.png" width="20" height="20"/>&nbsp;<b style="font-size:10px">NOTIFICACIÓN PROY. INVERSIÓN</b></a>';
                    }
                    $tabla.='
                    
                </p>
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
            else{
                $rol=$this->model_funcionario->get_rol($fun_id);
                if($rol[0]['r_id']==10){ /// REPORTES POA
                    $vector[0]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/consulta/poa_ofc"  onclick="gasto_corriente_ofc()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/proyectos.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;"><b>POA OFICINA CENTRAL / '.$this->gestion.'</b></h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function gasto_corriente_ofc(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                    $vector[1]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/consulta/mis_operaciones"  onclick="gasto_corriente()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/impresora.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;"><b>POA NACIONAL/ '.$this->gestion.'</b></h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function gasto_corriente(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                    $vector[2]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/ejecucion_proyectos_inversion"  onclick="inversion()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/gerencia.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;"><b>PROYECTOS DE INVERSIÓN / '.$this->gestion.'</b></h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function inversion(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                }
                elseif ($rol[0]['r_id']==11) { /// PARA EJECUCION DE PROYECTOS DE INVERSION
                    $vector[0]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/ejec_fin_pi" onclick="inversion()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/proyectos.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">EJECUCIÓN INVERSION '.$this->gestion.'</h1>
                        </div>
                        </a>
                    </div>';
                    ?>
                    <script>
                      function inversion(){
                        document.getElementById("load").style.display = "block";
                      }
                    </script>
                    <?php
                }
                else{
                    foreach ($menus as $fila) {
                        $vector[$n] = $this->html_menu_opciones($fila['mod_id']);
                        $n++;
                    }
                }
            }
          /*  elseif($this->tp_adm==3){
                $vector[0]=
                    '<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/consulta/mis_operaciones"  onclick="reporte_internos()" class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/impresora.png"  style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">RESUMEN POA '.$this->gestion.'</h1>
                        </div>
                        </a>
                    </div>';
            }
            else{
                foreach ($menus as $fila) {
                    $vector[$n] = $this->html_menu_opciones($fila['mod_id']);
                    $n++;
                }
            }*/
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

    /// DASHBOARD SEGUIMIENTO POA (UNIDAD ADMINISTRATIVA)
    public function dashboard_seguimientopoa(){

        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            if($this->session->userdata('tp_usuario')==0){ /// Unidad Administrativa
                $data['resp']=$this->session->userdata('funcionario');
                $link_form1='seguimiento_poa';
                $data['vector_menus'] = $this->menu_principal_roles_seguimientopoa($link_form1,$this->session->userData('com_id'));
                $data['tmes']=$this->model_evaluacion->trimestre();
                $data['com_id']=$this->session->userData('com_id');
                $data['mes']=$this->verif_mes;
                $data['mensaje']=$this->mensaje_sistema();
                $data['gestiones']=$this->list_gestiones();
                $data['list_trimestre']=$this->list_trimestre();
            }
            else{ /// Establecimiento de Salud
                $establecimiento=$this->model_seguimientopoa->get_unidad_programado_gestion($this->session->userData('act_id'));
                $data['resp']=$establecimiento[0]['tipo'].' '.$establecimiento[0]['act_descripcion'].' '.$establecimiento[0]['abrev'];
                $link_form1='seguimiento_establecimientos';
                $data['vector_menus'] = $this->menu_principal_roles_seguimientopoa($link_form1,$establecimiento[0]['com_id']);
                $data['tmes']=$this->model_evaluacion->trimestre();
                $data['com_id']=$establecimiento[0]['com_id'];
                $data['mes']=$this->verif_mes;
                $data['mensaje']=$this->mensaje_sistema();
                $data['gestiones']=$this->list_gestiones();
                $data['list_trimestre']=$this->list_trimestre();
            }

            $this->load->view('admin/dashboard_seguimiento',$data); 

        } else{
            $this->session->sess_destroy();
            redirect('/','refresh');
        }
    }


    /*----- MENU PRINCIPAL SEGUIMIENTO POA -----*/
    public function menu_principal_roles_seguimientopoa($link_seguimiento,$id){
        $vector;
        $n = 0;
        $vector[0]='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/'.$link_seguimiento.'" id="myBtn3" onclick="evaluacion()"  class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/trabajo_social.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">SEGUIMIENTO POA</h1>
                        </div>
                        </a>
                    </div>';
/*        $vector[1]='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/solicitar_certpoa/'.$id.'" id="myBtn3" onclick="evaluacion()"  class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/icon11.jpg" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">SOLICITUD CERTIFICACIÓN POA</h1>
                        </div>
                        </a>
                    </div>';*/
        $vector[1]='<div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <a href="'.base_url().'index.php/reporte_segpoa/'.$id.'" id="myBtn3" onclick="evaluacion()"  class="jarvismetro-tile big-cubes bg-color-greenLight">
                        <div class="well1" align="center">
                            <img class="img-circle" src="'.base_url().'assets/img/impresora.png" style="margin-left:0px; width: 95px"/>
                            <h1 style="font-size: 11px;">REPORTE POA</h1>
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
        
        return $vector;
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

    public function validate_credentials_psw(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $usuario = $this->security->xss_clean($post['user_namepws']); /// usuario
          $email = $this->security->xss_clean($post['emailpws']); /// email

            if (preg_match('/^[A-Za-z0-9.]+$/', $usuario) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $dominio = explode('@', $email)[1];
                if (checkdnsrr($dominio, 'MX')) {
                    $busca_responsable=$this->model_funcionario->fun_usuario($usuario);
                    if(count($busca_responsable)!=0){
                        $data_to_store = array( 
                            'fun_id' => $busca_responsable[0]['fun_id'],
                            'email' => $email,
                            'sol_fecha' => date("d/m/Y H:i:s"),
                            'num_ip' => $this->input->ip_address(), 
                            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                          );
                          $this->db->insert('solicitudes_psw', $data_to_store);
                          $sol_id=$this->db->insert_id();

                          if(count($this->model_funcionario->solicitud_contraseñas($sol_id))!=0){
                            $this->session->set_flashdata('success','En unos minutos el Administrador del Sistema remitira su solicitud al correo electronico registrado !!!');
                          }
                          else{
                            $this->session->set_flashdata('danger','Error al realizar la solicitud, contactese con el administrador !!!');
                          }
                    }
                    else{
                        $this->session->set_flashdata('danger','Usuario no valido !!!');
                    }
                    

                } else {
                    $this->session->set_flashdata('danger','ERROR !!!');
                }

                redirect('/','refresh');

            }

      } else {
          show_404();
      }
    }



    function validate_credentials(){

        $this->load->model('Users_model');
        if(isset($_POST['user_name']) && isset($_POST['password']) && isset($_POST['dat_captcha'])){
            if($this->input->post('user_name') && preg_match('/^[a-zA-Z0-9!@+,:?_.^\/\*&%$]*$/i', $this->input->post('password')) && preg_match('/^[A-Z0-9\/]*$/i', $this->input->post('dat_captcha'))){
                
                if(md5($this->input->post('dat_captcha'))==$this->input->post('captcha')){
                    $user_name = $this->security->sanitize_filename(strtoupper(htmlspecialchars($this->input->post('user_name'))), TRUE) ;
                    $password = $this->security->sanitize_filename($this->input->post('password'), TRUE); 
                 
                    if($this->input->post('tp')==0){ /// Administrador
                        
                        $is_valid = $this->model_funcionario->verificar_loggin($this->security->xss_clean($user_name), $this->security->xss_clean($password));
                        if($is_valid['bool']){
                            $this->session->set_userdata($this->session_administrador($is_valid['fun_id'])); /// Sesion Administrador
                            
                            if($this->session->userData('rol_id')!=9){
                                redirect('admin/dashboard');
                            }
                            else{
                                if(count($this->model_componente->get_componente($this->session->userData('com_id'),$this->session->userData('gestion')))!=0){
                                    redirect('dashboar_seguimiento_poa');
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
                            redirect('dashboar_seguimiento_poa');
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
            'credencial_funcionario' => $data[0]['sw_pass'], // 0,1
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
            'conf_ajuste_poa' => $gestion[0]['conf_ajuste_poa'],
            'conf_psw' => $gestion[0]['conf_psw'], /// Credenciales
            'estado_notificaciones' => $gestion[0]['conf_poa'], /// Estado para las Notificaciones 0:no activo, 1: Habilitado
            'entidad' => $gestion[0]['conf_nombre_entidad'],
            'trimestre' => $gestion[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
            'verif_ppto' => $gestion[0]['ppto_poa'], /// Ppto poa : 0 (Ante proyecto), 1: (Aprobado)
            'conf_poa_estado' => $gestion[0]['conf_poa_estado'], /// Estado Poa Estado : 1 (Inicial), 2: (Ajuste), 3: (Aprobado)
            'conf_form4' => $gestion[0]['conf_form4'], /// Estado de Registro del formulario N4, 0 (Inactivo), 1 (Activo)
            'conf_form5' => $gestion[0]['conf_form5'], /// Estado de Registro del formulario N5, 0 (Inactivo), 1 (Activo)
            'conf_mod_ope' => $gestion[0]['conf_mod_ope'], /// Estado de modificacion del formulario N4, 0 (Inactivo), 1 (Activo)
            'conf_mod_req' => $gestion[0]['conf_mod_req'], /// Estado de modificacion del formulario N5, 0 (Inactivo), 1 (Activo)
            'conf_certificacion' => $gestion[0]['conf_certificacion'], /// Estado de modificacion del formulario N5, 0 (Inactivo), 1 (Activo)
            'tr_id' => ($gestion[0]['conf_mes_otro']+$gestion[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
            'tp_msn' => $gestion[0]['tp_msn'], /// tipo de mensaje 1: rojo, 2: amarillo, 3: verde
            'mensaje' => $gestion[0]['conf_mensaje'], /// Mensaje
            'rd_poa' => $gestion[0]['rd_aprobacion_poa'], /// Resolucion Directorio POA
            'conf_estado' => $conf[0]['conf_estado'], /// Estado de la Gestion (1: activo, 0 No activo)
            'tp_usuario' => 0,
            'img' => base_url().'assets/ifinal/cns_logo.JPG',
           // 'img' => 'assets/ifinal/cns_logo.JPG',
            'mes_actual'=>$this->verif_mes_gestion($gestion[0]['conf_mes']),
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
            'com_id' => $actividad[0]['act_id'],
            'adm' => 2,
            'fun_id' => 399,
            'fun_estado' => 1,
            'dep_id' => $actividad[0]['dep_id'],
            'img' => $actividad[0]['img'],
            'tp_adm' => 0,
            'gestion' => $gestion[0]['ide'],
            'mes' => $gestion[0]['conf_mes'],
            'estado_notificaciones' => $gestion[0]['conf_poa'], /// Estado para las Notificaciones 0:no activo, 1: Habilitado
            'entidad' => $gestion[0]['conf_nombre_entidad'],
            'trimestre' => $gestion[0]['conf_mes_otro'], /// Trimestre 1,2,3,4
            'verif_ppto' => $gestion[0]['ppto_poa'], /// Ppto poa : 0 (Vigente), 1: (Aprobado)
            'tr_id' => ($gestion[0]['conf_mes_otro']+$gestion[0]['conf_mes_otro']*2), /// Trimestre 3,6,9,12
            'tp_msn' => $gestion[0]['tp_msn'], /// tipo de mensaje 1: rojo, 2: amarillo, 3: verde
            'mensaje' => $gestion[0]['conf_mensaje'], /// Mensaje
            'rd_poa' => $gestion[0]['rd_aprobacion_poa'], /// Resolucion Directorio POA
            'tp_usuario' => 1,
            'img' => base_url().'assets/ifinal/cns_logo.JPG',
           // 'img' => 'assets/ifinal/cns_logo.JPG',
            'mes_actual'=>$this->verif_mes_gestion($gestion[0]['conf_mes']),
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
        echo "Trabanajo";
/*        $this->load->model('menu_modelo');
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
        $this->load->view('includes/template', $data);*/
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

                $combog = pg_query('SELECT * FROM pdes WHERE pdes_estado=\'1\' AND ('.$this->gestion.'>=pdes_gestion and '.$this->gestion.'<=pdes_gestion_final) AND pdes_depende=\'' . $id_pais . '\' ');
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

                $combog = pg_query('SELECT * FROM pdes WHERE pdes_estado=\'1\' AND ('.$this->gestion.'>=pdes_gestion and '.$this->gestion.'<=pdes_gestion_final) AND pdes_depende=\'' . $id_pais . '\' ');
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

                $combog = pg_query('SELECT pdes_id FROM pdes WHERE pdes_estado=\'1\' AND ('.$this->gestion.'>=pdes_gestion and '.$this->gestion.'<=pdes_gestion_final) AND pdes_depende=\'' . $id_pais . '\' ');
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
    public function verif_mes_gestion($mes_sistema){
      $valor=$mes_sistema; // numero mes segun el sistema
      //$valor=ltrim(date("m"), "0"); // numero mes por defecto
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