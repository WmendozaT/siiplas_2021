<?php
class Ceventos_dnp extends CI_Controller {
    //public $rol = array('1' => '1');
    public function __construct(){
        parent::__construct();
       // if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
          //  if($this->rolfun($this->rol)){ 
                $this->load->library('pdf');
                $this->load->library('pdf2');
                $this->load->model('Users_model','',true);
                $this->load->model('menu_modelo');
                $this->load->model('mantenimiento/model_configuracion');
                $this->load->model('mantenimiento/model_estructura_org');
                $this->load->model('mantenimiento/model_evento');
                $this->load->library("security");
                $this->gestion = $this->session->userData('gestion');
                $this->rol = $this->session->userData('rol');
                $this->fun_id = $this->session->userData('fun_id');
                $this->tmes = $this->session->userData('trimestre');
            /*}
            else{
                redirect('admin/dashboard');
            }*/
/*        }
        else{
                redirect('/','refresh');
        }*/
    }



  public function valida_ingreso($even_id){
    $data['img2']='<center><img src="'.base_url().'assets/img_v1.1/dnp1.png" class="img-responsive app-center" style="width:150px; height:150px;text-align:center"/></center>';
    $data['img1']='<center><img src="'.base_url().'assets/ifinal/logo_CNS_header.png" class="img-responsive app-center" style="width:95px; height:140px;text-align:center"/></center>';
    $get_evento=$this->model_evento->get_evento($even_id);

    $data['cuerpo']='Error !!';
    if(count($get_evento)!=0){
      $data['cuerpo']='
      <div class="container">
        <div class="barra-color">CERTIFICADO DE PARTICIPACIÓN</div>
        <section class="section-content py-3">
            <div class="row">
            
            <main class="col-lg-7">
                <div class="well" style="background-color: #dcfae3;text-align: justify;">
                    <b style="font-size: 25px;font-family: Arial;">INSTRUCCIONES!</b><br>
                    Si formaste parte del ciclo de capacitación : <b>'.$get_evento[0]['titulo_evento'].'</b>, '.$get_evento[0]['fecha_evento'].', puedes generar tu Certificado de manera fácil con los siguientes pasos:
                    <br><br>
                    <ol>
                        <li><b>Escribe los datos de tu Documento de Identidad</b></li>
                        <li><b>Descarga o imprime tu Certificado</b></li>
                    </ol>
                </div>
            </main>
            <aside class="col-lg-5"> 
                <nav class="sidebar card py-2 mb-4">
                    <article class="col-sm-12 col-md-12 col-lg-12">
                        <div class="widget-body">
                            <form name="form_ci" id="form_ci" method="post" class="form-horizontal">
                              <input type="hidden" name="even_id" id="even_id" value="'.$even_id.'">
                              <input type="hidden" name="base" value="'.base_url().'">
                              <fieldset>
                                  <legend>INGRESE NRO. DE CARNET '.$this->gestion.'</legend>
                                  
                                  <div class="form-group">
                                      <label class="col-md-2 control-label">CI. :</label>
                                      <div class="col-md-10">
                                          <input class="form-control" type="text" name="ci" id="ci" value="" title="REGISTRE CEDULA DE IDENTIDAD" onkeypress="if (this.value.length < 8) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                                      </div>
                                  </div>
                              </fieldset>
                              <div class="form-actions" align="right">
                                  <button type="button" name="verif_ci" id="verif_ci" class="btn btn-primary" style="background-color:#06601a">BUSCAR CERTIFICADO</button>
                              </div>
                            </form>
                        </div>
                    </article>
                </nav>
            </aside>
            <div id="loading"></div>
            </div>
        </section>
      </div>';
    }
    $this->load->view('admin/vista_certificados', $data);
  }





  /// --- get CI
    public function get_ci(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ci = $this->security->xss_clean($post['ci']);
        $even_id = $this->security->xss_clean($post['even_id']);
        $get_ci=$this->model_evento->get_ci_participante_habilitado($ci,$even_id);

        //$get_participante=$this->model_evento->get_participante($ci_id);
        
        $tabla='<div class="alert alert-danger" role="alert">
                <h2><b>NO ENCONTRADO !!</h2><br>
                Si presenta problemas o tiene dudas con la certificación, puede comunicarse con el DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</b>
              </div>';
        if(count($get_ci)!=0){
          
          $update_participante = array(
            'nro_impresion' => ($get_ci[0]['nro_impresion']+1),
          );
          $this->db->where('ci_id', $get_ci[0]['ci_id']);
          $this->db->update('participantes', $update_participante);

          $tabla='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/certificado/'.$get_ci[0]['ci_id'].'"></iframe><br>
          <div class="alert alert-success" role="alert">
            Si presenta problemas o tiene dudas con la certificación, puede comunicarse con el DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</b>
          </div>';
        }

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*------ Listado de Eventos -------*/
    public function menu_eventos(){
      $data['menu']=$this->menu(9);
      $listado_eventos=$this->model_evento->lista_eventos();
      $data['eventos']='';
      $data['eventos']='
          '.$this->style().'
          <style>
            #mdialTamanio1{
                width: 50% !important;
            }
          </style>
          <div class="row">
              <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <section id="widget-grid" class="well">
                      <div class="">
                        <h1><b>LISTADO DE EVENTOS / '.$this->gestion.'</b></h1>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_form1" class="btn btn-default nuevo_form1" title="NUEVO REGISTRO FORM N 4" >
                          <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>REGISTRAR EVENTO</b>
                        </a>
                      </div>
                  </section>
              </article>
          </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-darken" >
              <header>
                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                  <h2 class="font-md">EVENTOS '.$this->gestion.'</strong></h2>  
              </header>
                <div>
                  <div class="widget-body no-padding">
                  <input type="hidden" name="base" value="'.base_url().'">
                    <table id="dt_basic" class="table table-bordered" style="width:100%;">
                        <thead>
                          <tr style="height:35px;" align="center">
                            <th style="width:1%;" align="center">#</th>
                            <th style="width:2%;" align="center">DEL</th>
                            <th style="width:2%;" align="center">CODIGO</th>
                            <th style="width:5%;" title="TIPO"><center>TIPO DE EVENTO</center></th>
                            <th style="width:20%;" title="EVENTO"><center>EVENTO</center></th>
                            <th style="width:20%;" title="ORGANIZADOR"><center>ORGANIZADOR</center></th>
                            <th style="width:15%;" title="TIPO"><center>DATOS DEL EVENTO</center></th>
                            <th style="width:10%;" title="FECHA DE IMPRESION"><center>FECHA DE IMPRESION</center></th>
                            <th style="width:2%;" title="LISTADO"><center>PARTICIPANTES</center></th>
                          </tr>
                        </thead>
                        <tbody>';
                        $nro=0;
                        foreach($listado_eventos as $rowp){
                          $nro++;
                          $data['eventos'].='
                          <tr>
                            <td>'.$nro.'</td>
                            <td></td>
                            <td><input type="text" class="form-control" onkeyup="update_evento(1,'.$rowp['even_id'].',\'cod_even\');" style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="cod_even'.$rowp['even_id'].'" id="cod_even'.$rowp['even_id'].'" title="CODIGO DE EVENTO" value="'.$rowp['cod_evento'].'"></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(2,'.$rowp['even_id'].',\'tp_even\');"  style="width:100%; font-size:12px; color:blue; background-color: #fcfcc3;" name="tp_even'.$rowp['even_id'].'" id="tp_even'.$rowp['even_id'].'" title="TIPO DE EVENTO">'.$rowp['tp_evento'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(3,'.$rowp['even_id'].',\'titulo_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="titulo_even'.$rowp['even_id'].'" id="titulo_even'.$rowp['even_id'].'" title="TITULO DEL EVENTO">'.$rowp['titulo_evento'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(4,'.$rowp['even_id'].',\'org_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="org_even'.$rowp['even_id'].'" id="org_even'.$rowp['even_id'].'" title="ORGANIZADOR">'.$rowp['organizador'].'</textarea></td>
                            <td><textarea rows="5" class="form-control" onkeyup="update_evento(5,'.$rowp['even_id'].',\'dat_even\');"  style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="dat_even'.$rowp['even_id'].'" id="dat_even'.$rowp['even_id'].'" title="DATOS DEL EVENTO">'.$rowp['fecha_evento'].'</textarea></td>
                            <td><input type="text" class="form-control" onkeyup="update_evento(6,'.$rowp['even_id'].',\'imp_even\');" style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="imp_even'.$rowp['even_id'].'" id="imp_even'.$rowp['even_id'].'" title="FECHA DE IMPRESION" value="'.$rowp['fecha_evento_impresion'].'"></td>
                            <td align=center>
                              <a href="'.site_url("").'/participantes_eventosDNP/'.$rowp['even_id'].'" title="LISTA DE PARTICIPANTES" class="btn btn-default"><img src="'.base_url().'assets/ifinal/select.png" WIDTH="38" HEIGHT="38"/></a>
                            </td>
                          </tr>';
                        }
                      $data['eventos'].='
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
          </article>';

      $this->load->view('admin/mantenimiento/eventosDNP/listado_eventos', $data);
    }



  /*--------- VALIDA FORM 4 (2025) -----------*/
  public function valida_evento(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('even_org', 'even_org', 'required|trim');
        $this->form_validation->set_rules('even_tp', 'even_tp', 'required|trim');
      
        if ($this->form_validation->run()){
          header('Content-Type: text/html; charset=utf-8');
          /*------ INSERT EVENTO -------*/
          $data_to_store = array(
            'organizador' => strtoupper($this->input->post('even_org')),
            'tp_evento' => $this->input->post('even_tp'),
            'cod_evento' => strtoupper($this->input->post('cod_evento')),
            'titulo_evento' => strtoupper($this->input->post('evento')),
            'fecha_evento' => $this->input->post('fecha_even'),
            'g_id' => $this->gestion,
            'fecha_evento_impresion' => $this->input->post('even_fech_impresion'),
          );
          $this->db->insert('eventosdnp', $data_to_store);
          $id_event=$this->db->insert_id(); 
          /*-------------------------------------*/

          $evento=$this->model_evento->get_evento($id_event);
          if(count($evento)==1){
            $this->session->set_flashdata('success','CORRECTO !!');
          }
          else{
            $this->session->set_flashdata('danger','ERROR!!');
          }

          redirect('eventosDNP');
        }
        else{
          $this->session->set_flashdata('danger','ERROR!!! :(');
          redirect('eventosDNP');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar !!!!</font></center>";
    }
  }



    /*---- UPDATE DATOS DEL EVENTO----*/
    public function update_datos_evento(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $even_id = $this->security->xss_clean($post['even_id']);
        $nro = $this->security->xss_clean($post['nro']);
        $name_input = $this->security->xss_clean($post['name_input']);
        $detalle = $this->security->xss_clean($post['detalle']); /// input

          if($nro==1){ // cod even
            $campo='cod_evento';
          }
          elseif($nro==2){ /// evento
            $campo='tp_evento';
          }
          elseif($nro==3){
            $campo='titulo_evento';
          }
          elseif($nro==4){
            $campo='organizador';
          }
          elseif($nro==5){
            $campo='fecha_evento';
          }
          elseif($nro==6){
            $campo='fecha_evento_impresion';
          }

          /////
          $update_even = array(
            $campo => $detalle,
          );
          $this->db->where('even_id', $even_id);
          $this->db->update('eventosdnp', $update_even);
          ////


        $result = array(
          'respuesta' => 'correcto',
          //'update_informacion'=>$informacion,
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }



 /*------ Listado de Participantes -------*/
    public function participantes($even_id){
      $data['menu']=$this->menu(9);
      $get_evento=$this->model_evento->get_evento($even_id);
      $data['input_id']='<input class="form-control"  type="hidden" name="even_id" id="even_id" value="'.$even_id.'">';
      $lista_participantes=$this->model_evento->lista_participantes($even_id);
      $data['tp_certificados']=$this->model_evento->tipo_cert();
      $data['eventos']='';
      $data['eventos']='
          '.$this->style().'
          <style>
            #mdialTamanio2{
                width: 40% !important;
            }
          </style>
          <div class="row">
              <article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                  <section id="widget-grid" class="well">
                        <h1><b>EVENTO: </b>'.$get_evento[0]['cod_evento'].' - '.$get_evento[0]['titulo_evento'].'</h1>
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_form2" class="btn btn-default nuevo_form2" title="REGISTRAR PARTICIPANTE" >
                          <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>REGISTRAR PARTICIPANTE</b>
                        </a>
                        <a href="'.site_url("").'/eventosDNP" class="btn btn-default " title="volver atras" >
                          <img src="'.base_url().'assets/Iconos/arrow_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>VOLVER</b>
                        </a>
                  </section>
              </article>
              <article class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <section id="widget-grid" class="well">
                    <button type="button" class="btn btn-primary" style="width:100%;" data-toggle="modal" data-target="#exampleModalCenter">
                        SUBIR PARTICIPANTES.CSV
                    </button>
                </section>
              </article>
          </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="well well-sm well-light">
                  <div class="row">
                      <article class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                      <div class="jarviswidget jarviswidget-color-darken">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                            <h2 class="font-md"><strong>LISTA DE PARTICIPANTES</strong></h2>  
                        </header>
                          <div>
                              <div class="widget-body no-padding">
                                   <input type="hidden" name="base" value="'.base_url().'">
                                    <table id="dt_basic" class="table table-bordered" style="width:100%;">
                                        <thead>
                                          <tr style="height:35px;" align="center">
                                            <th style="width:3%;" align="center">ESTADO</th>
                                            <th style="width:2%;" align="center">ELIMINAR</th>
                                            <th style="width:8%;" align="center">CI</th>
                                            <th style="width:6%;" title="TIPO"><center>TIPO DE CERTIFICADO</center></th>
                                            <th style="width:20%;" title="EVENTO"><center>NOMBRE COMPLETO</center></th>
                                            <th style="width:2%;" title="LISTADO"><center></center></th>
                                          </tr>
                                        </thead>
                                        <tbody>';
                                        $nro=0;
                                        foreach($lista_participantes as $rowp){
                                          $nro++;
                                          $color_estado='';
                                          if($rowp['estado']==3){
                                            $color_estado='#fcd4c8';
                                          }

                                          $data['eventos'].='
                                          <tr bgcolor='.$color_estado.'>
                                            <td>
                                              <select class="form-control" id="tp_estado'.$rowp['ci_id'].'" name="tp_estado'.$rowp['ci_id'].'" onchange="update_select_option(2,this.value,'.$rowp['ci_id'].');" style="width:100%; font-size:12px; color:blue; background-color: #fafcd7;" title="SELECCIONE ESTADO">';
                                                if($rowp['estado']==1){ /// Habilitado
                                                  $data['eventos'].='
                                                    <option value="1" selected>SI</option>
                                                    <option value="3">NO</option>';    
                                                }
                                                elseif($rowp['estado']==3){ /// Habilitado
                                                  $data['eventos'].='
                                                    <option value="1">SI</option>
                                                    <option value="3" selected>NO</option>';    
                                                }

                                              $data['eventos'].='
                                              </select>
                                            </td>
                                            <td>
                                              <center><a name="del_par'.$rowp['ci_id'].'" id="del_par'.$rowp['ci_id'].'" onclick="delete_participante('.$rowp['ci_id'].');" class="btn btn-default" title="ELIMINAR ACTIVIDAD"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a></center>
                                            </td>
                                            <td><input type="number" class="form-control" onkeyup="update_participante(1,'.$rowp['ci_id'].',\'ci_par\');" style="width:100%; font-size:12px; color:blue; background-color: #fcfcc3;" name="ci_par'.$rowp['ci_id'].'" id="ci_par'.$rowp['ci_id'].'" title="CI" value="'.$rowp['ci'].'"></td>
                                            <td>
                                              <select class="form-control" id="tp_par'.$rowp['ci_id'].'" name="tp_par'.$rowp['ci_id'].'" onchange="update_select_option(1,this.value,'.$rowp['ci_id'].');" style="width:100%; font-size:12px; color:blue; background-color: #fafcd7;" title="SELECCIONE TIPO DE CERTIFICADO">
                                                  <option value="">seleccione tipo de certificado ..</option>';
                                                  foreach($data['tp_certificados'] as $row){
                                                    if($rowp['tp_cert']==$row['tp_cert']){
                                                      $data['eventos'].='<option value="'.$row['tp_cert'].'" selected>'.$row['tp_certificado'].'</option>';    
                                                    }
                                                    else{
                                                      $data['eventos'].='<option value="'.$row['tp_cert'].'">'.$row['tp_certificado'].'</option>';    
                                                    }
                                                  }
                                                $data['eventos'].='
                                              </select>
                                              </td>
                                            <td><input type="text" class="form-control" onkeyup="update_participante(3,'.$rowp['ci_id'].',\'nombre_par\');" style="width:100%; font-size:11px; color:blue; background-color: #fcfcc3;" name="nombre_par'.$rowp['ci_id'].'" id="nombre_par'.$rowp['ci_id'].'" title="PARTICIPANTE" value="'.$rowp['nombre_completo'].'"></td>
                                            <td>
                                              <a class="btn btn-info" name="'.$rowp['ci_id'].'" id="'.$even_id.'" onclick="ver_certificado('.$rowp['ci_id'].','.$even_id.');">Ver Certificado</a>
                                            </td>
                                          </tr>';
                                        }
                                      $data['eventos'].='
                                        </tbody>
                                    </table>
                              </div>
                          </div>
                      </div>
                    
                      </article>
                      <article class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        <div class="jarviswidget jarviswidget-color-darken">
                          <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"><strong>CERTIFICADO</strong></h2>  
                          </header>
                            <div>
                                <div class="widget-body no-padding">
                                  <div id="content1"></div>
                                </div>
                             
                            </div>
                        </div>
                      </article>
                  </div>
              </div>
          </article>';


      $this->load->view('admin/mantenimiento/eventosDNP/listado_eventos', $data);
    }


  /*--------- VALIDA REGISTRO PARTICIPANTE -----------*/
  public function valida_participante(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('even_id', 'even_id', 'required|trim');
        $this->form_validation->set_rules('ci', 'ci', 'required|trim');
      
        if ($this->form_validation->run()){
          header('Content-Type: text/html; charset=utf-8');
          /*------ INSERT EVENTO -------*/
          $data_to_store = array(
            'even_id' => strtoupper($this->input->post('even_id')),
            'nombre_completo' => strtoupper($this->input->post('nombre')),
            'ci' => $this->input->post('ci'),
            'tp_cert' => $this->input->post('tp_cert'),
          );
          $this->db->insert('participantes', $data_to_store);
          $ci_id=$this->db->insert_id(); 
          /*-------------------------------------*/

          $participante=$this->model_evento->get_participante($ci_id);
          if(count($participante)==1){
            $this->session->set_flashdata('success','CORRECTO !!');
          }
          else{
            $this->session->set_flashdata('danger','ERROR!!');
          }

          redirect('participantes_eventosDNP/'.$this->input->post('even_id').'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR!!! :(');
          redirect('participantes_eventosDNP/'.$this->input->post('even_id').'');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar !!!!</font></center>";
    }
  }




/// --- MIGRAR ARCHIVO DE PARTICIPANTES
 function importar_participantes(){
    if ($this->input->post()) {
        $post = $this->input->post();
        $even_id = $this->security->xss_clean($post['even_id']);
        $tipo = $_FILES['archivo']['type'];
        $tamanio = $_FILES['archivo']['size'];
        $archivotmp = $_FILES['archivo']['tmp_name'];

        $filename = $_FILES["archivo"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.'));
        $file_ext = substr($filename, strripos($filename, '.'));
        $allowed_file_types = array('.csv');
        if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
          $i=0; $part=0;
          $lineas = file($archivotmp);

          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
              $datos = explode(";",$linea);

                if(count($datos)==3){
                  $ci = intval(trim($datos[0])); //// ci
                  $nombre = trim($datos[1]); //// nombre
                  $tp_cert = trim($datos[2]); //// tp certificado
                  
                  $get_participante=$this->model_evento->get_ci_participante($ci,$even_id);
                  
                  if(count($get_participante)==0){ /// insert
                   
                      header('Content-Type: text/html; charset=utf-8');
                      $data_to_store = array(
                        'even_id'=>$even_id,
                        'nombre_completo' => strtoupper(mb_convert_encoding($nombre, 'UTF-8')),
                        'ci'=>$ci,
                        'tp_cert'=>$tp_cert,
                        'fun_id' => $this->fun_id,
                        'num_ip' => $this->input->ip_address(), 
                        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                      );
                      $this->db->insert('participantes', $data_to_store);
                      $ci_id=$this->db->insert_id(); 
                      $part++;
                  }
                }
              }
              $i++;
            }

            $this->session->set_flashdata('success','SE REGISTRARON '.$part.' PARTICIPANTES');
            redirect(site_url("").'/participantes_eventosDNP/'.$even_id);
        } 
        elseif (empty($file_basename)) {
          echo "<script>alert('SELECCIONE ARCHIVO .CSV')</script>";
        } 
        elseif ($filesize > 100000000) {
          //redirect('');
        } 
        else {
          $mensaje = "Sólo estos tipos de archivo se permiten para la carga: " . implode(', ', $allowed_file_types);
          echo '<script>alert("' . $mensaje . '")</script>';
        }

    } else {
        show_404();
    }
  }

    /*---- UPDATE DATOS DEL PARTICIPANTE----*/
    public function update_datos_participante(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ci_id = $this->security->xss_clean($post['ci_id']);
        $nro = $this->security->xss_clean($post['nro']);
        $name_input = $this->security->xss_clean($post['name_input']);
        $detalle = $this->security->xss_clean($post['detalle']); /// input


          if($nro==1){ // ci
            $campo='ci';
          }
          elseif($nro==3){ /// nombre completo
            $campo='nombre_completo';
          }

          /////
          $update_even = array(
            $campo => $detalle,
          );
          $this->db->where('ci_id', $ci_id);
          $this->db->update('participantes', $update_even);
          ////


        $result = array(
          'respuesta' => 'correcto',
          //'update_informacion'=>$informacion,
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }

    /*---- UPDATE DATOS DE SELECCION----*/
    public function update_tp(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']);
        $ci_id = $this->security->xss_clean($post['ci_id']);
        $id = $this->security->xss_clean($post['id']);

        //tp,id,ci_id
        if($tp==1){ // tipo de certificado
          $campo='tp_cert';
        }
        elseif($tp==2){/// estado del participante
          $campo='estado';
        }
        
        /////
        $update_even = array(
          $campo => $id,
        );
        $this->db->where('ci_id', $ci_id);
        $this->db->update('participantes', $update_even);
        ////

        $result = array(
          'respuesta' => 'correcto',
        );

        echo json_encode($result);
      }else{
        show_404();
      }
    }


  /*------ ELIMINA PARTICIPANTE ------*/
    function elimina_participante(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $ci_id = $this->security->xss_clean($post['ci_id']); /// ci id

          /*------ delete participante -----*/
          $this->db->where('ci_id', $ci_id);
          $this->db->delete('participantes');

          $participante=$this->model_evento->get_participante($ci_id);
          if(count($participante)==0){
            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }

          echo json_encode($result);
      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*-------- GET CERTIFICADO ------------*/
    public function get_certificado(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ci_id = $this->security->xss_clean($post['ci_id']);
        $even_id = $this->security->xss_clean($post['even_id']);
        $get_participante=$this->model_evento->get_participante($ci_id);
        $get_evento=$this->model_evento->get_evento($even_id);
        $tabla='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/certificado/'.$ci_id.'"></iframe>';

        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    ////--------------------------
    public function certificado($ci_id){
      $get_participante=$this->model_evento->get_participante($ci_id);
      if(count($get_participante)!=0 & $get_participante[0]['estado']==1){
          $data['pie_reporte']='CERT-DNP-'.$get_participante[0]['cod_evento'].'-'.$get_participante[0]['g_id'].'_'.$get_participante[0]['ci'].'';
          $data['certificado']='';
          $data['certificado'].='
            <page backcolor="#004640" backleft="5mm" backright="5mm" backtop="5mm" backbottom="5mm" >
            <table style="width: 99%;" border="0">
              <tr>
                  <td style="width: 20%; height: 98%; background: #004640;">
                      <div style="width: 100%; height:35%;border:0px;text-align: center;">
                          <img src="'.getcwd().'/assets/img_v1.1/logo_CNS_header.png" alt="Logo" title="Logo" style="width:80%; height:85%;">
                      </div>
                      <div style="width: 100%; height:35%;border:0px;text-align: center;">
                          <!-- <img src="'.getcwd().'/assets/img_v1.1/dnp1.png" alt="Logo" title="Logo" style="width:102%; height:85%;"> -->
                      </div>
                      <div style="width: 100%; height:15%; text-align: center; color: white;">
                          <b>DNP - '.$get_participante[0]['cod_evento'].' - '.$get_participante[0]['g_id'].'</b><br>
                          <qrcode value="https://planificacion.cns.gob.bo/index.php/certificado/'.$ci_id.'" ec="H" style="width: 40mm;"></qrcode><br>
                          DNP@siiplas
                      </div>
                  </td>
                  <td style="width: 80%; height: 98%; background: #FFFFFF; border: 0px;">
                      <!--  Cabecera   -->
                      <div style="width: 100%; height:15%;border:0px;">
                          <div style="font-size: 38px;font-family: Arial; color: #015045;text-align:center;"><b>CAJA NACIONAL DE SALUD</b></div>
                          <div style="font-size: 30px;font-family: Arial; text-align:center;"><b>OFICINA NACIONAL</b></div>
                          <div style="font-size: 18px;font-family: Arial; text-align:center;">'.$get_participante[0]['organizador'].'</div>
                      </div>
                      <!--  End Cabecera   -->
                      
                      <!--  Cuerpo   -->
                      <div style="width: 100%; height:45%;border:0px;">
                          <table style="width: 100%; height:20%;border:0px;">
                              <tr><td style="width: 31%; text-align:right;font-size: 17px;font-family: Arial;">Confiere el Presente:</td>
                                  <td style="width: 69%;"></td>
                              </tr>
                          </table>
                          <br>
                          <div style="width: 100%; height:15%;border:0px; text-align: center;">
                              <img src="'.getcwd().'/assets/img_v1.1/cert2.JPG" alt="Logo" title="Logo" style="width:70%; height:90%;">
                          </div>
                          <br>

                          <table style="width: 100%; height:10%;border:0px;">
                              <tr><td style="width: 14%; text-align:right;font-size: 18px;font-family: Arial;">A :</td>
                                  <td style="width: 8%;"></td>
                                  <td style="width: 73%;font-size: 21px;font-family: Arial;"><b>'.$get_participante[0]['nombre_completo'].'</b></td>
                              </tr>
                          </table>
                          <br>
                          <table style="width: 100%; height:10%;border:0px;" border="0">
                              <tr><td style="width: 26%; text-align:right;font-size: 18px;font-family: Arial;">En calidad de :</td>
                                  <td style="width: 15%;"></td>
                                  <td style="width: 53%;font-size: 28px;font-family: Arial;"><b>'.$get_participante[0]['tp_certificado'].'</b></td>
                              </tr>
                          </table>
                          <br>
                          <table style="width: 100%; height:20%;text-align: justify;" border="0">
                              <tr><td style="width: 10%;"></td>
                                  <td style="width: 80%;font-size: 20px;font-family: Arial;">En el ciclo de '.$get_participante[0]['tp_evento'].' sobre: <b>"'.$get_participante[0]['titulo_evento'].'"</b></td>
                                  <td style="width: 10%;"></td>
                              </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" border="0">
                              <tr><td style="width: 10%;height:10%;"></td>
                                  <td style="width: 80%;font-size: 19px;font-family: Arial;text-align: justify;">'.$get_participante[0]['fecha_evento'].'</td>
                                  <td style="width: 10%;"></td>
                              </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" border="0">
                              <tr><td style="width: 75%;"></td>
                                  <td style="width: 25%;font-family: Arial;">'.$get_participante[0]['fecha_evento_impresion'].'</td>
                              </tr>
                          </table>
                      </div>
                      <!--  End Cuerpo   -->

                      <!--  Pie   -->
                      <div style=";width: 100%; height:25%;">
                          <div align="center">
                            <table style="width: 100%;" border="0">
                              <tr>
                                  <td style="width: 99%;">';
                                    if($get_participante[0]['estado']==1){
                                     $data['certificado'].='<img src="'.getcwd().'/assets/img_v1.1/firmv1.png" alt="Logo" title="Logo" style="width:100%; height:95%;">';
                                    }
                                  $data['certificado'].='
                                  </td>
                              </tr>
                          </table>  
                          </div>
                          
                      </div>
                  </td>
              </tr>
          </table>
      </page>';
      }
      else{
        $data['pie_reporte']='';
        $data['certificado']='
            <page  backleft="5mm" backright="5mm" backtop="5mm" backbottom="5mm" >
            Certificado no encontrado !!!
            </page>';
      }


      $this->load->view('admin/programacion/reportes/certificados', $data);

    }

  public function style(){
    $tabla='
    <style>
            .table{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
            }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              font-size: 10px;
            }
            td{
              font-size: 10px;
            }
          </style>';
  }



    /*------ NOMBRE MES -------*/
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
    /*---------- Menu --------------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++)
        {
            if(count($subenlaces[$enlaces[$i]['o_child']])>0)
            {
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

    /*----------- Rol Usuario --------------*/
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