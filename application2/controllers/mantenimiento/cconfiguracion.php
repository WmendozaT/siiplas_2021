<?php
class Cconfiguracion extends CI_Controller {
    public $rol = array('1' => '1');
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
              $this->load->library('pdf');
              $this->load->library('pdf2');
              $this->load->model('Users_model','',true);
              $this->load->model('menu_modelo');
              $this->load->model('mantenimiento/model_configuracion');
              $this->load->model('mantenimiento/model_estructura_org');
              $this->load->model('programacion/model_proyecto');
              $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
              $this->load->library("security");
              $this->gestion = $this->session->userData('gestion');
              $this->adm = $this->session->userData('adm');
              $this->rol = $this->session->userData('rol_id');
              $this->dist = $this->session->userData('dist');
              $this->dist_tp = $this->session->userData('dist_tp');
              $this->tmes = $this->session->userData('trimestre');
              $this->fun_id = $this->session->userData('fun_id');
              $this->entidad = $this->session->userData('entidad');
              $this->resolucion=$this->session->userdata('rd_poa');
              $this->sistema=$this->session->userdata('sistema');
              $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            }
            else{
                redirect('admin/dashboard');
            }
        }
        else{
                redirect('/','refresh');
        }
    }

    /*------- CONFIGURACIÓN SISTEMA ----------*/
    public function main_configuracion(){ 
      $data['menu']=$this->menu(9);
      if($this->fun_id==399){
        $data['conf'] = $this->model_configuracion->get_configuracion_session();
        $data['mes'] = $this->model_configuracion->get_mes();
        $data['trimestre'] = $this->model_configuracion->get_mes_trimestre();
        $data['gestion'] = $this->model_configuracion->get_gestion();
        $data['modulos'] = $this->conf_modulos();

        $data['responsables_evaluadores'] = $this->responsables_evaluadores(); /// Lista de Responsables para evaluar

        //phpinfo();
        $this->load->view('admin/mantenimiento/configuracion/vmain_configuracion', $data);
      }
      else{
        redirect('admin/dashboard');
      }
      
    }






    /*----- LISTA DE PERSONAL A EVALUAR ----*/
    public function responsables_evaluadores(){ 
      $responsables=$this->model_configuracion->get_list_responsables_evaluacion(); // responsables
      $uresponsables=$this->model_configuracion->get_list_uresponsables_evaluacion(); // unidades responsables
      $tabla='';

      $tabla.=' <section class="col col-4">
                  <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR DE RESPONSABLES...."/><br>
                </section>
                <table class="table table-bordered" id="resp">
                  <thead>
                    <tr>
                      <th scope="col" style="width:1%;">#</th>
                      <th scope="col" style="width:20%;">RESPONSABLE</th>
                      <th scope="col" style="width:15%;">DISTRITAL</th>
                      <th scope="col" style="width:15%;">USUARIO</th>
                      <th scope="col" style="width:5%;"></th>
                    </tr>
                  </thead>
                    <tbody>';
                    $nro=0;
                    foreach($responsables as $row){
                      $nro++;
                      $tabla.='<tr>';
                        $tabla.='<td>'.$nro.'</td>';
                        $tabla.='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                        $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
                        $tabla.='<td><b>'.strtoupper($row['fun_usuario']).'</b></td>';
                        $tabla.='<td>';
                        if(count($this->model_configuracion->get_responsables_evaluacion($row['id']))!=0){
                          $tabla.='<div class="checkbox">
                                      <label>
                                        <input type="checkbox" name="fun[]" value="'.$row['id'].'" checked="checked">
                                        <i></i>
                                      </label>
                                    </div>';
                        }
                        else{
                          $tabla.='<div class="checkbox">
                                      <label>
                                        <input type="checkbox" name="fun[]" value="'.$row['id'].'">
                                        <i></i>
                                      </label>
                                    </div>';
                        }
                        $tabla.='</td>';
                      $tabla.='</tr>';
                    }
                    /// Unidades Responsables
                    foreach($uresponsables as $row){
                      $nro++;
                      $tabla.='<tr bgcolor="#e7eef7">';
                        $tabla.='<td>'.$nro.'</td>';
                        $tabla.='<td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>';
                        $tabla.='<td>'.strtoupper($row['dist_distrital']).'</td>';
                        $tabla.='<td><b>'.strtoupper($row['fun_usuario']).'</b></td>';
                        $tabla.='<td>';
                        if(count($this->model_configuracion->get_responsables_evaluacion($row['id']))!=0){
                          $tabla.='<div class="checkbox">
                                      <label>
                                        <input type="checkbox" name="fun[]" value="'.$row['id'].'" checked="checked">
                                        <i></i>
                                      </label>
                                    </div>';
                        }
                        else{
                          $tabla.='<div class="checkbox">
                                      <label>
                                        <input type="checkbox" name="fun[]" value="'.$row['id'].'">
                                        <i></i>
                                      </label>
                                    </div>';
                        }
                        $tabla.='</td>';
                      $tabla.='</tr>';
                    }
            $tabla.='
                  </tbody>
                </table>';

      return $tabla;
    }


     function importar_archivo_pdf3(){
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('dist_id', 'id distrital', 'required|trim');

            if ($this->form_validation->run()){
              $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
              $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
              $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
              $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo
              $allowed_file_types = array('.pdf','.docx','.doc','.xlsx','.xls','.jpg','.JPG','.png','.PNG','.JPEG','.JPG'); 

              if($filename!=''){
                echo $filesize."<br>";
                echo $this->input->post('dist_id');
              }
              else
              {
                echo "Error 2";
                //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/1/0');  ///// nose selecciono archivo
              }
            }
            else{
              echo "Error1";  
            }
              
              //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/false');  
          }
    }


    /*--- MIGRACION DE REQUERIMIENTOS A UNA ACTIVIDAD (2020) ---*/
    function importar_archivo_pdf2(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $dist_id = $post['dist_id']; /// proy id

/*            echo "upload_max_filesize = 2000M";
echo "post_max_size = 2000M";*/

            $target_path = "scanneados/";
            $target_path = $target_path . basename($dist_id.'_'.$this->gestion.''.$_FILES['uploadedfile']['name']); 
            $filesize = $_FILES["uploadedfile"]["size"]; //// Tamaño del archivo

            echo $_FILES["uploadedfile"]["size"];
           /* if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)){

                echo "<span style='color:green;'>El archivo ". basename( $_FILES['uploadedfile']['name']). " ha sido subido</span><br>";
            }
            else{
                echo "Ha ocurrido un error, trate de nuevo!";
            }*/

      }
      else{
        echo "error !!";
      }

    }

    function importar_archivo_pdf(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $dist_id = $post['dist_id']; /// proy id


              $filename = $_FILES["file1"]["name"]; ////// datos del archivo 
              $file_basename = substr($filename, 0, strripos($filename, '.')); ///// nombre del archivo
              $file_ext = substr($filename, strripos($filename, '.')); ///// Extension del archivo
              $filesize = $_FILES["file1"]["size"]; //// Tamaño del archivo
              $allowed_file_types = array('.pdf','.docx','.doc','.xlsx','.xls','.jpg','.JPG','.png','.PNG','.JPEG','.JPG'); 

              if($filename!=''){
                echo $filesize."<br>";
                echo $dist_id;
              }
              else{
                echo "Error 2";
                //redirect('admin/proy/proyecto/'.$this->input->post('id').'/8/1/0');  ///// nose selecciono archivo
              }
      }
      else{
        echo "error !!";
      }

    }

    /*------------ CONFIGURAR MODULOS ------------*/
    public function conf_modulos(){ 
        $tabla='';
        $modulos = $this->model_configuracion->list_modulos();
        
        $tabla.='<table class="table table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">DESCRIPCI&Oacute;N MODULO</th>
                      <th scope="col"></th>
                    </tr>
                  </thead>
                    <tbody>';
                    $nro=0;
                    foreach($modulos as $row){
                        $verif=$this->model_configuracion->verif_modulo($row['mod_id']);
                        $nro++;
                        $tabla.='<tr>';
                            $tabla.='<td><input type="hidden" name="id[]" value="'.$row['mod_id'].'"/>'.$nro.'</td>';
                            $tabla.='<td>'.$row['mod_descripcion'].'</td>';
                            if(count($verif)!=0){
                                $tabla.='
                                    <td>
                                    <center>
                                        <input type="checkbox"  onclick="scheck'.$nro.'(this.checked,'.$nro.');" title="MODULO ACTIVADO" checked/>
                                        <input type="hidden" name="mod[]" id="vmod'.$nro.'" value="1"/>
                                    </center>
                                    </td>';
                            }
                            else{
                                $tabla.='
                                    <td>
                                    <center>
                                        <input type="checkbox" onclick="scheck'.$nro.'(this.checked,'.$nro.');" title="MODULO DESACTIVADO"/>
                                        <input type="hidden" name="mod[]" id="vmod'.$nro.'" value="0"/>
                                    </center>
                                    </td>';
                            }
                            
                        $tabla.='</tr>';
                        ?>
                        <script>
                            function scheck<?php echo $nro;?>(estaChequeado,nro) {
                              val = parseInt($('[name="tot"]').val());
                              if (estaChequeado == true) {
                                val = val + 1;
                                $('[id="vmod'+nro+'"]').val((1).toFixed());
                                valor = parseFloat($('[id="vmod'+nro+'"]').val());
                              } else {
                                val = val - 1;
                                $('[id="vmod'+nro+'"]').val((0).toFixed());
                                valor = parseFloat($('[id="vmod'+nro+'"]').val());
                              }

                              $('[name="tot"]').val((val).toFixed(0));
                              total = parseFloat($('[name="tot"]').val());
                              if(total!=0){
                                $('#but').slideDown();
                              }
                              else{
                                $('#but').slideUp();
                              }
                            }
                        </script>
                        <?php
                    }
                $tabla.='
                    </tbody>
                    <input type="hidden" name="tot" id="tot" value="'.count($this->model_configuracion->verif_nro_modulo()).'">
                </table>';

    return $tabla;
    }


  /*------ VALIDA DATOS DE EVALUACION ------*/
  public function update_datos_evaluacion(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $ide = $this->security->xss_clean($post['ide']);
      $fini = $this->security->xss_clean($post['ini']);
      $ffin = $this->security->xss_clean($post['fin']);

      /*--- Update configuracion ---*/
      $update_configuracion= array(
        'eval_inicio' => $fini, /// Fecha Inicio
        'eval_fin' => $ffin /// Fecha Final
      );
      $this->db->where('ide', $ide);
      $this->db->update('configuracion', $this->security->xss_clean($update_configuracion));
      /*-----------------------------*/

      /*--- eliminar Responsables ---*/
      $this->db->where('ide', $this->gestion);
      $this->db->delete('resp_evaluacion');
      /*---------------------------*/

      /*-----  LISTA DE RESPONSABLES EVALUACION POA -----*/
      if (!empty($_POST["fun"]) && is_array($_POST["fun"]) ) {
        foreach ( array_keys($_POST["fun"]) as $como){
           $data_to_store = array( 
          'ide' => $ide,
          'fun_id' => $_POST["fun"][$como],
        );
        $this->db->insert('resp_evaluacion', $data_to_store);
        }

        redirect('Configuracion#tab-r8');
      }
      else{
        echo "No ingresa";
      }

    }
    else{
      echo "Error !!!";
    }
  }




    /*---- Update Configuracion de Modulos ----*/
    public function update_mod(){
      if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('ide', 'id configuracion', 'required|trim');
            $this->model_configuracion->delete_modulos();

        $nro=0;
        if (!empty($_POST["id"]) && is_array($_POST["id"]) ) {
          foreach ( array_keys($_POST["id"]) as $como){

            if($_POST["mod"][$como]!=0){
                $data_to_store = array( 
                'ide' => $this->gestion,
                'mod_id' => $_POST["id"][$como],
                );
                $this->db->insert('confi_modulo', $data_to_store);
            }
          }

        //    $this->session->set_userdata('modulos', $nueva_gestion);
            $this->session->set_flashdata('success','SE ACTUALIZO CORRECTAMENTE LA CONFIGURACIÓN DE MODULOS');
            redirect('Configuracion#tab-r7');
        }
        else{
            $this->session->set_flashdata('danger','ERROR AL ACTUALIZAR MODULOS');
            redirect('Configuracion#tab-r7');
        }
      }
      else{
        echo "<font color=red><b>Error al Asignar Servicios</b></font>";
      }
    }

    /*---- Update Configuracion de Certificacion ----*/
    public function update_certificacion(){
      if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('ide', 'id configuracion', 'required|trim');
            $this->model_configuracion->delete_opciones_cert();

        $nro=0;
        if (!empty($_POST["id"]) && is_array($_POST["id"]) ) {
          foreach ( array_keys($_POST["id"]) as $como){

            if($_POST["cert"][$como]!=0){
                $data_to_store = array( 
                'ide' => $this->gestion,
                'dist_id' => $_POST["id"][$como],
                );
                $this->db->insert('confi_cert', $data_to_store);
            }
          }

            $this->session->set_flashdata('success','SE ACTUALIZO CORRECTAMENTE LA CONFIGURACIÓN DE REGIONALES, DISTRITALES');
            redirect('Configuracion#tab-r8');
        }
        else{
            $this->session->set_flashdata('danger','ERROR AL ACTUALIZAR MODULOS');
            redirect('Configuracion#tab-r8');
        }
      }
      else{
        echo "<font color=red><b>Error al Configurar Regionales, Distritales</b></font>";
      }
    }


    public function update_conf(){ 
        if ($this->input->server('REQUEST_METHOD') === 'POST'){
            $this->form_validation->set_rules('ide', 'id configuracion', 'required|trim');
            $this->form_validation->set_rules('tp', 'tipo', 'required|trim');
           
            if ($this->form_validation->run()){
                if($this->input->post('tp')==2){
                    $update_conf = array(   
                                'conf_mes' => $this->input->post('mes_id'),
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE EL MES ACTIVO');
                    redirect('Configuracion');
                }
                elseif($this->input->post('tp')==1){
                    $update_conf = array(   
                                'conf_estado' => 0,
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);


                    $update_conf = array(   
                                'conf_estado' => 1,
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('gest_id'));
                    $this->db->update('configuracion', $update_conf);

                    redirect('/','refresh');
                }
                elseif($this->input->post('tp')==3){
                    $update_conf = array(   
                                'conf_sigla_entidad' => $this->input->post('sigla'),
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA SIGLA INSTITUCIONAL');
                    redirect('Configuracion');
                }
                elseif($this->input->post('tp')==4){
                    $update_conf = array(   
                                'conf_nombre_entidad' => $this->input->post('entidad'),
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE EL NOMBRE DE LA ENTIDAD');
                    $this->session->set_flashdata('1');
                    redirect('Configuracion');
                }
                elseif($this->input->post('tp')==5){
                    $update_conf = array(   
                                'conf_gestion_desde' => $this->input->post('gi'),
                                'conf_gestion_hasta' => $this->input->post('gf'),
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LAS GESTIONES PARA LOS OBETIVOS ESTRATEGICOS');
                    redirect('Configuracion');
                }
                elseif($this->input->post('tp')==6){
                    $update_conf = array(   
                                'conf_mes_otro' => $this->input->post('tmes_id'),
                                'fun_id' => $this->session->userdata("fun_id"));
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_userdata('trimestre', $this->input->post('tmes_id'));
                    $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA CONFIGURACION TRIMESTRAL');
                    redirect('Configuracion');
                }
                elseif($this->input->post('tp')==7){

                    $update_conf = array(   
                      'conf_mensaje' => $this->input->post('msn'),
                      'tp_msn' => $this->input->post('tp_msn'),
                      'fun_id' => $this->fun_id);
                    $this->db->where('ide', $this->input->post('ide'));
                    $this->db->update('configuracion', $update_conf);

                    $this->session->set_flashdata('success','SE MODIFICO EL MENSAJE DEL SISTEMA');
                    redirect('Configuracion');
                }
            }
        }
        else{
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR SISTEMA !!!!');
            redirect('Configuracion');
        }
    }
    public function activar_gestion(){
        if($this->input->is_ajax_request()){
            $post = $this->input->post();
            $ide = $post['ide'];
            $bool = $this->model_configuracion->activa_funcionario($ide);
            // $bool = true;
            if($bool){
                $data = array(
                    'resultado' => 'Operación Correcta',
                    'ide' => $ide
                    );
                echo json_encode($data);
            }
            else{
                $data = array(
                    'resultado' => 'Operación Incorrecto'
                    );
                echo json_encode($data);
            }
        } else {
            show_404();
        }
    }

    public function desactivar_gestion(){
        if($this->input->is_ajax_request()){
            $post = $this->input->post();
            $ide = $post['ide'];
            $bool = $this->model_configuracion->desactiva_funcionario($ide);
            // $bool = true;
            if($bool){
                $data = array(
                    'resultado' => 'Operación Correcta',
                    'ide' => $ide
                    );
                echo json_encode($data);
            }
            else{
                $data = array(
                    'resultado' => 'Operación Incorrecto'
                    );
                echo json_encode($data);
            }
        } else {
            show_404();
        }
    }
   
    public function configuracion_gestion(){
        //////////////////////////////gestion y mes actual/////////
        $gestion= $this->model_configuracion->gestion_actual();
        // $data['ges'] = $gestion;
        $data['mes_db'] = $this->mes_texto($gestion[0]['conf_mes']);
        $data['gestion_db'] = $gestion[0]['ide'];
        $data['gestion_lista'] = $this->model_configuracion->get_gestion_todo();
        //////////////////////vista gestion/////////////////////////
        $listar_gestion = $this->model_configuracion->lista_gestion();
        $tabla = '';
        $tabla.='';
        $tabla.='<form   method="post" action="'.base_url().'index.php/Configuracion_mod">
                <select name="actu_gest" class="form-control" required>
                 <option value="'.$data['gestion_db'].'">Seleccionar gestion</option>'; 
        foreach ($listar_gestion as $row) {
        $tabla.='<option value="'.$row['ide'].'" >'.$row['ide'].'</option>';
              };
        $tabla.='  </select>
                    <br>
                    <BUTTON class="btn btn-xs btn-success">
                        <div class="btn-hover-postion1">
                           Modificar
                        </div>
                    </BUTTON>
            </form>';
        ////////////////////end vista gestion/////////////////////////
        ///////////////////////vista mes/////////////////////////
            $tablas='';
        $tablas.='';
        $tablas.='<form   method="post" action="'.base_url().'index.php/Configuracion_mod_mes">
                <select name="actu_gest_mes" class="form-control">
                 <option value="1">Seleccionar Mes</option>
                 <option value="1">Enero</option> 
                 <option value="2">Febrero</option> 
                 <option value="3">Marzo</option> 
                 <option value="4">Abril</option> 
                 <option value="5">Mayo</option> 
                 <option value="6">Junio</option> 
                 <option value="7">Julio</option> 
                 <option value="8">Agosto</option> 
                 <option value="9">Septiembre</option> 
                 <option value="10">Octubre</option> 
                 <option value="11">Noviembre</option> 
                 <option value="12">Diciembre</option>  
                </select>
                <br>
                    <BUTTON class="btn btn-xs btn-success">
                        <div class="btn-hover-postion1">
                           Modificar
                        </div>
                    </BUTTON>
            </form>';
        ///////////////////////end mes///////////////////////////
        $data['mes']=$tablas;
        $data['gestion']=$tabla;
        $ruta = 'mantenimiento/vlista_configuracion';
        $this->construir_vista($ruta,$data);
    }

        /*----- Tipo de Responsable ------*/
    public function tp_resp(){
      $ddep = $this->model_proyecto->dep_dist($this->dist);
      if($this->adm==1){
        $titulo='RESPONSABLE NACIONAL';
      }
      elseif($this->adm==2){
        $titulo='RESPONSABLE '.strtoupper($ddep[0]['dist_distrital']);
      }
      return $titulo;
    }


    /*------- PRESENTACION POA (CARATULA REGIONAL) ----------*/
    public function presentacion_poa(){ 
        $data['menu']=$this->menu(7);
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep']=$this->tp_resp();
        $data['regiones']=$this->departamentos();

        $this->load->view('admin/mantenimiento/caratula_poa/regiones', $data);
    }

     //// DEPARTAMENTOS
    public function departamentos(){
      $regiones=$this->mrep_operaciones->regiones();
      $nro=0;
      $tabla ='';
      $tabla.='<article class="col-sm-12 col-md-3 col-lg-3">
                <div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">
                  <header>
                    <span class="widget-icon"> <i class="fa fa-eye"></i> </span>
                    <h1>&nbsp;&nbsp;GENERAR CARATULA POA REGIONAL '.$this->gestion.'</h1>
                  </header>
                  <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
          
                      <form method="post" >
                        <fieldset>
                          <div class="form-group">
                            <label>REGIONAL</label>
                              <select class="form-control" name="dep_id" id="dep_id">
                                <option value="">Selecciones Regional</option>';
                                foreach($regiones as $rowp){
                                  $tabla.='<option value='.$rowp['dep_id'].'>'.$rowp['dep_departamento'].'</option>';
                                }
                              $tabla.='
                              </select>
                          </div>
                        </fieldset>
                      </form>
                    </div>
          
                  </div>
                </div>
                </article>

                <article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                  <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
                      <header>
                          <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                          <h2></h2>
                      </header>
                      <div>
                          <div class="jarviswidget-editbox">
                          </div>
                          <div class="widget-body">
                            <div id="tit"></div>
                            <div id="content1"></div>
                          </div>
                      </div>
                  </div>
                </article>';

      return $tabla;
    }


    /*-------- GET CARATULA POA ------------*/
    public function get_caratula_poa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $regional=$this->model_proyecto->get_departamento($dep_id);

        $tabla='<hr><iframe id="ipdf" width="100%"  height="800px;" src="'.base_url().'index.php/mnt/caratula_poa/'.$dep_id.'"></iframe>';
        $result = array(
          'respuesta' => 'correcto',
          'regional'=>$regional,
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*-------- CARATULA POA (Gasto Corriente)---------*/
    public function ver_caratula_poa($dep_id){
      $data['regional']=$this->model_proyecto->get_departamento($dep_id);
      $distritales=$this->model_proyecto->list_distritales($dep_id);
      //$data['mes'] = $this->mes_nombre();
      $tabla='';

      if($dep_id==2 || $dep_id==5 || $dep_id==7){
        $tabla.='
        <page backtop="185mm" backbottom="19mm" backleft="5mm" backright="5mm" pagegroup="new">
          <page_header>
            <br><div class="verde"></div>
            '.$this->cabecera_rep_caratulapoa().'
          </page_header>';
          $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
            <tr>
              <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
                <hr style="border:3px">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                      <td style="width:100%; height: 5.5%; font-size: 40px;" align="center"><b>REGIONAL '.strtoupper($data['regional'][0]['dep_departamento']).'</b></td>
                  </tr>
                  <tr>
                      <td style="width:100%; height: 1.2%; font-size: 45px;" align="center">TOMO I de II</td>
                  </tr>
                </table>
                <hr style="border:3px">
              </td>
            </tr>
          </table>
          '.$this->pie_rep_caratulapoa().'
        </page>';
        $tabla.='
        <page backtop="185mm" backbottom="19mm" backleft="5mm" backright="5mm" pagegroup="new">
          <page_header>
            <br><div class="verde"></div>
            '.$this->cabecera_rep_caratulapoa().'
          </page_header>';
          $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
            <tr>
              <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
                <hr style="border:3px">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                      <td style="width:100%; height: 5.5%; font-size: 40px;" align="center"><b>REGIONAL '.strtoupper($data['regional'][0]['dep_departamento']).'</b></td>
                  </tr>
                  <tr>
                      <td style="width:100%; height: 1.2%; font-size: 45px;" align="center">TOMO II de II</td>
                  </tr>
                </table>
                <hr style="border:3px">
              </td>
            </tr>
          </table>
          '.$this->pie_rep_caratulapoa().'
        </page>';
      }
      else{
        $tabla.='
        <page backtop="185mm" backbottom="19mm" backleft="5mm" backright="5mm" pagegroup="new">
          <page_header>
            <br><div class="verde"></div>
            '.$this->cabecera_rep_caratulapoa().'
          </page_header>';
          $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
            <tr>
              <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
                <hr style="border:3px">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                      <td style="width:100%; height: 5.5%; font-size: 40px;" align="center"><b>REGIONAL '.strtoupper($data['regional'][0]['dep_departamento']).'</b></td>
                  </tr>
                  <tr>
                      <td style="width:100%; height: 1.2%; font-size: 45px;" align="center">TOMO I de I</td>
                  </tr>
                </table>
                <hr style="border:3px">
              </td>
            </tr>
          </table>
          '.$this->pie_rep_caratulapoa().'
        </page>';
      }
      

      foreach($distritales as $row){
        $tabla.='
        <page backtop="185mm" backbottom="19mm" backleft="5mm" backright="5mm" pagegroup="new">
          <page_header>
            <br><div class="verde"></div>
            '.$this->cabecera_rep_caratulapoa().'
          </page_header>';
          $tabla.='
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
            <tr>
              <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
                <hr style="border:2px">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                  <tr>
                      <td style="width:100%; height: 5.5%; font-size: 32pt;" align="center">'.strtoupper($row['dist_distrital']).'</td>
                  </tr>
                </table>
                <hr style="border:2px">
              </td>
            </tr>
          </table>
          '.$this->pie_rep_caratulapoa().'
        </page>';
      }

      
      $data['cuerpo']=$tabla;

      $this->load->view('admin/mantenimiento/caratula_poa/caratula_regional', $data);
    }



    /*--- CABECERA ----*/
    public function cabecera_rep_caratulapoa(){
      $tabla='
        <table class="page_header" border="0">
          <tr>
            <td style="width: 100%; text-align: left">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                <tr>
                  <td style="width:100%; font-size: 38px;" align="center"><b>'.$this->entidad.'</b></td>
                </tr>
                <tr>
                  <td style="width:100%;  font-size: 20px;" align="center">DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                </tr>
                <tr>
                  <td style="width:100%;" align="center"><br><br></td>
                </tr>
                <tr>
                  <td style="width:100%;" align="center"><img src="'.getcwd().'/assets/ifinal/CnsLogo.JPG" alt="" style="width:45%;"></td>
                </tr>
                <tr>
                  <td style="width:100%; font-size: 50px;" align="center"><b>PLAN OPERATIVO ANUAL</b></td>
                </tr>
                <tr>
                  <td style="width:100%; font-size: 40px;" align="center">GESTI&Oacute;N '.$this->gestion.'</td>
                </tr>
              </table>
            </td>
          </tr>
        </table>';

      return $tabla;
    }

    /*--- PIE REPORTE ----*/
    public function pie_rep_caratulapoa(){
      $tabla='
        <page_footer>
          <hr>
          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99%;" align="center">
            <tr>
              <td style="width: 50%; text-align: left; font-size:9px;">
                Wmendoza7 - <b>POA - '.$this->gestion.' '.$this->resolucion.'</b>
              </td>
              <td style="width: 50%; text-align: right; font-size:9px;">
                <b>'.$this->sistema.'</b>
              </td>
            </tr>
            <tr>
              <td colspan="2"><br></td>
            </tr>
          </table>
        </page_footer>';

      return $tabla;
    }


    /*-------- CARATULA POA (Proyectos de Inversion)---------*/
    public function ver_caratula_pi($dep_id){
        $data['regional']=$this->model_proyecto->get_departamento($dep_id);
        $data['mes'] = $this->mes_nombre();
        $this->load->view('admin/mantenimiento/caratula_poa/caratula_distrital_pi', $data);
    }

    /*--- TIPO DE PRESUPUESTO (ANTEPROYECTO, APROBADO)---*/
    function valida_update_ppto(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $ppto = $post['ppto'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'ppto_poa' => $ppto,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('verif_ppto', $ppto);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DEL POA---*/
    function valida_update_estadopoa(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_poa_estado' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_poa_estado', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DEL FORMULARIO N° 4 ---*/
    function valida_update_estadoform4(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_form4' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_form4', $estado);

          echo "true";

      }else{
        show_404();
      }
    }

    /*--- ESTADO DEL FORMULARIO N° 4 ---*/
    function valida_update_estadoform5(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_form5' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_form5', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DEL FORMULARIO MODIFICACION N° 4 ---*/
    function valida_update_estadomodform4(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_mod_ope' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_mod_ope', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DEL FORMULARIO MODIFICACION N° 5 ---*/
    function valida_update_estadomodform5(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_mod_req' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_mod_req', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DEL FORMULARIO CERTIFCACION POA N° 5 ---*/
    function valida_update_estadocertform5(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_certificacion' => $estado,
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_certificacion', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- ESTADO DE LA NOTIFICACION POA ---*/
    function valida_update_notificacionpoa(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_poa' => $estado, //// Notificaciones poa
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_poa', $estado);

          echo "true";

      }else{
        show_404();
      }
    }


    /*--- AJUSTE DE SALDOS POA ---*/
    function valida_update_saldospoa(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $estado = $post['estado'];
          $g_id= $post['g_id'];

          $update_conf = array(
            'conf_ajuste_poa' => $estado, //// ajuste poa
            'fun_id' => $this->fun_id
          );
          $this->db->where('ide', $this->gestion);
          $this->db->update('configuracion', $update_conf);

          $this->session->set_userdata('conf_ajuste_poa', $estado);

          echo "true";

      }else{
        show_404();
      }
    }

    function mes_nombre(){
        $mes[1] = 'ENE.';
        $mes[2] = 'FEB.';
        $mes[3] = 'MAR.';
        $mes[4] = 'ABR.';
        $mes[5] = 'MAY.';
        $mes[6] = 'JUN.';
        $mes[7] = 'JUL.';
        $mes[8] = 'AGOS.';
        $mes[9] = 'SEPT.';
        $mes[10] = 'OCT.';
        $mes[11] = 'NOV.';
        $mes[12] = 'DIC.';
        return $mes;
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

    /*------- MENU -------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++) {
            if(count($subenlaces[$enlaces[$i]['o_child']])>0){
                $tabla .='<li>';
                    $tabla .='<a href="#">';
                        $tabla .='<i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>';    
                        $tabla .='<ul>';    
                            foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
                            $tabla .='<li><a href="'.base_url($item['o_url']).'">'.$item['o_titulo'].'</a></li>';
                        }
                        $tabla .='</ul>';
                $tabla .='</li>';
            }
        }

        return $tabla;
    }

    function rolfun($rol){
      $valor=false;
      for ($i=1; $i <=count($rol) ; $i++) { 
        $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$rol[$i]);
        if(count($data)!=0){
          $valor=true;
          break;
        }
      }
      return $valor;
    }
}