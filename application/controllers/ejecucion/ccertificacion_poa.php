<?php
class Ccertificacion_poa extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
      $this->load->library('pdf2');
      $this->load->model('programacion/model_proyecto');
      $this->load->model('programacion/model_faseetapa');
      $this->load->model('programacion/model_actividad');
      $this->load->model('programacion/model_producto');
      $this->load->model('programacion/model_componente');
      $this->load->model('programacion/model_mantenimiento');
      $this->load->model('ejecucion/model_certificacion');
      $this->load->model('ejecucion/model_ejecucion');
      $this->load->model('programacion/insumos/minsumos');
      $this->load->model('mestrategico/model_mestrategico');
      $this->load->model('mantenimiento/model_partidas');
      $this->load->model('mantenimiento/model_ptto_sigep');
      $this->load->model('modificacion/model_modificacion');
      $this->load->model('mantenimiento/model_configuracion');
      $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
      $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
      $this->load->model('mantenimiento/model_funcionario');
      $this->load->model('menu_modelo');
      $this->load->model('Users_model','',true);
      $this->load->library('security');
      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm');
      $this->rol = $this->session->userData('rol_id');
      $this->dep_id = $this->session->userData('dep_id');
      $this->dist = $this->session->userData('dist');
      $this->dist_tp = $this->session->userData('dist_tp');
      $this->tp_adm = $this->session->userData('tp_adm');
      $this->fun_id = $this->session->userData('fun_id');
      $this->verif_ppto = $this->session->userData('verif_ppto'); /// AnteProyecto Ptto POA : 0, Ptto Aprobado Sigep : 1
      $this->conf_form4 = $this->session->userData('conf_form4');
      $this->conf_form5 = $this->session->userData('conf_form5');
      $this->conf_poa_estado = $this->session->userData('conf_poa_estado'); /// Ajuste POA 1: Inicial, 2 : Ajuste, 3 : aprobado

      $this->load->library('certificacionpoa');

      $this->fecha_entrada = strtotime("31-05-2021 00:00:00");
      }
      else{
          $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }



    /*------ LISTA DE POAS APROBADOS -------*/
    public function list_poas_aprobados(){
      if($this->rolfunn(3)){
        $data['menu']=$this->certificacionpoa->menu(4);
        $data['resp']=$this->session->userdata('funcionario');
        $data['reg'] = $this->model_proyecto->dep_dist($this->dist);
        $data['res_dep']=$this->certificacionpoa->tp_resp();

        $data['titulo']='SELECCIONAR ALINEACIÓN ACTIVIDAD - '.$this->gestion.'';

        $data['proyectos']=$this->certificacionpoa->list_pinversion(4); /// Proyectos de Inversion
        $data['operacion']=$this->certificacionpoa->list_unidades_es(4); /// Gasto Corriente

        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/list_poas_aprobados', $data);
      }
      else{
        redirect('admin/dashboard');
      }   
    }


  /*--- GET LISTA DE CERTIFICACIONES POR ITEMS ---*/
  public function get_lista_certificaciones_por_items(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $ins_id = $this->security->xss_clean($post['ins_id']); // ins id
      //$insumo= $this->minsumos->get_requerimiento($ins_id); /// Datos requerimientos productos
      $insumo= $this->model_insumo->get_requerimiento($ins_id); /// Datos requerimientos productos

        $tabla='';
        $ins_certificado=$this->model_certificacion->verif_insumo_certificados($ins_id);
        if(count($ins_certificado)!=0){
          $tabla.='
            <b style="font-size:15px;">'.$insumo[0]['par_codigo'].' - '.$insumo[0]['ins_detalle'].'</b>
            <center>
              <table>
                <tr>';
                foreach ($ins_certificado as $row){
                  $tabla.='
                  <td>
                    <center>
                      <table class="table table-bordered" style="width:85%;">
                        <tr>
                          <td align=center>
                            <b>'.$row['cpoa_codigo'].'</b>
                          </td>
                        </tr>
                        <tr>
                          <td align=center>
                            <a href="javascript:abreVentana(\''. site_url("").'/cert/rep_cert_poa/'.$row['cpoa_id'].'\');" title="CERTIFICADO POA APROBADO"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="60" HEIGHT="60"/></a>
                          </td>
                        </tr>
                      </table>
                    </center>
                  </td>';
                }
            $tabla.='
                </tr>
              </table>
            </center>';
        }

      $result = array(
        'respuesta' => 'correcto',
        'lista'=>$tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


  //// formulario (Anterior)
/*  public function list_items_cert2($prod_id){
    $data['datos']=$this->model_certificacion->get_datos_unidad_prod($prod_id);
    if(count($data['datos'])!=0){
        $data['menu']=$this->certificacionpoa->menu(4);
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep']='hola mundo';
        $data['titulo']='hola mundo';
        $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
        $this->update_gestion_temporalidad($requerimientos);
        $data['requerimientos'] = $this->list_requerimientos_prelista($prod_id); /// para listas mayores a 500
        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/items_disponibles', $data);
    }
    else{
      echo "Error !!!";
    }
  }*/



  /*---- formulario Datos Cite -----*/
  public function list_items_cert($prod_id){
    $data['datos']=$this->model_certificacion->get_datos_unidad_prod($prod_id);
    if(count($data['datos'])!=0){
        $data['base']='<input name="base" type="hidden" value="'.base_url().'">';
        $data['menu']=$this->certificacionpoa->menu(4);
        $data['titulo']=$this->certificacionpoa->titulo_cabecera($data['datos']);

        
        /// actualizando ejcucion de items
        $requerimientos=$this->model_insumo->insumos_por_unidad($data['datos'][0]['aper_id']);/// TODOS LOS ITEMS DE LA UNIDAD
        foreach ($requerimientos as $row) {
          $tp_ejec=0;
          if($row['ins_costo_total']==$row['ins_monto_certificado']){
              $tp_ejec=1;
          }

          $update_ins = array(
            'ins_ejec_cpoa' => $tp_ejec
          );
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->update('insumos', $update_ins);
        }
        /// ------------------------------


        $data['formulario']='';
        $data['formulario'].='
        <article class="col-sm-12 col-md-12 col-lg-3">
        </article>
        <article class="col-sm-12 col-md-12 col-lg-6">

        <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
        <header>
          <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
          <h2>VALIDAR DATOS CITE </h2>
        </header>
        <div>
          <div class="jarviswidget-editbox">
          </div>
          <div class="widget-body no-padding">
            
            <form id="cert_form" name="cert_form" action="'.site_url().'/ejecucion/ccertificacion_poa/valida_datos_cite" method="post" class="smart-form">';
              if($this->conf_poa_estado==2){
                $data['formulario'].='
                 <br>
                <div class="alert alert-block alert-warning">
                  <a class="close" data-dismiss="alert" href="#">×</a>
                  <h4 class="alert-heading"><i class="fa fa-check-square-o"></i> Certificación POA (con cargo a presupesto aprobado 2024)!</h4>
                </div>';
              }
              $data['formulario'].='
              <header>REGISTRE LOS DATOS DE LA NOTA CITE DE SOLICITUD</header>
              <input type="hidden" name="prod_id" id="prod_id" value="'.$data['datos'][0]['prod_id'].'">
              <input type="hidden" name="tp_id" id="tp_id" value="'.$data['datos'][0]['tp_id'].'">
              <input type="hidden" name="dep_id" id="dep_id" value="'.$data['datos'][0]['dep_id'].'">
              <fieldset>          
                <div class="row">
                  <section class="col col-6">
                    <label class="label">Nro. CITE</label>
                    <label class="input">
                      <i class="icon-append fa fa-user"></i>
                      <input type="text" name="cite_cpoa" id="cite_cpoa" maxlength="17" onpaste="return false" >
                    </label>
                  </section>
                  <section class="col col-6">
                    <label class="label">Fecha CITE</label>
                    <label class="input">
                      <i class="icon-append fa fa-envelope-o"></i>
                      <input type="text" name="cite_fecha" id="cite_fecha" placeholder="FECHA CITE" class="form-control datepicker" data-dateformat="dd/mm/yy" onKeyUp="this.value=formateafecha(this.value);" value="'.date('d/m/Y').'" placeholder="dd/mm/YY" title="SELECCIONE FECHA CITE">
                    </label>
                  </section>
                </div>

              </fieldset>
              
              <footer>
                <button type="button" name="subir_form1" id="subir_form1" class="btn btn-info">VALIDAR DATOS CITE</button>
              </footer>
              <div id="load" style="display: none" align="center">
                <br><img  src="'.base_url().'/assets/img_v1.1/preloader.gif" width="100px"><br><b>Generando listado de Requerimientos ....</b>
              </div>
            </form>           
            
          </div>
        </div>
      </div>

        </article>
        <article class="col-sm-12 col-md-12 col-lg-3">
        </article>';

      //  echo $data['requerimientos'];
        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_prevista', $data);
    }
    else{
      echo "Error !!!";
    }
  }



  /// valida datos CIte
  public function valida_datos_cite(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $tp_id = $this->security->xss_clean($post['tp_id']);
      $cite_poa = $this->security->xss_clean($post['cite_cpoa']);
      $cite_fecha = $this->security->xss_clean($post['cite_fecha']);
      $dep_id = $this->security->xss_clean($post['dep_id']);
     // $prod_id = filter_var($prod_id, FILTER_SANITIZE_NUMBER_INT);
     // $tp_id = filter_var($tp_id, FILTER_SANITIZE_NUMBER_INT); 
     // $cite_poa = $cite_poa;
     
      if($tp_id==1){
        $datos=$this->model_certificacion->get_datos_pi_prod($prod_id); /// Gasto Proyecto de Inversión
      }
      else{
        $datos=$this->model_certificacion->get_datos_unidad_prod($prod_id); /// Gasto Corriente
      }

      $jef_id=0;
      if($dep_id==10){ /// of nacional
        $jefatura=$this->model_funcionario->get_jefe_DNP();
        $jef_id=$jefatura[0]['jef_id'];
      }
      /*------ INSERTANDO CERTIFICADO ------*/
      $data_to_store = array( 
        'proy_id' => $datos[0]['proy_id'],
        'aper_id' => $datos[0]['aper_id'], /// aper del programa padre
        'cpoa_fecha' => date("d/m/Y H:i:s"), /// fecha de registro (por defecto)
        'cpoa_gestion' => $this->gestion,
        'cpoa_estado' => 0, /// 0 : en proceso, 1 elaborado, 2, modificado, 3 Eliminado
        'fun_id' => $this->fun_id,
        'com_id' => $datos[0]['com_id'],
        'cpoa_cite' => $cite_poa,
        'cite_fecha' => $cite_fecha, /// fecha del Cite
        'prod_id' => $prod_id,
        'jef_id' => $jef_id,
      );
      $this->db->insert('certificacionpoa', $data_to_store);
      $cpoa_id=$this->db->insert_id();
      
      $get_cpoa=$this->model_certificacion->get_certificado_poa($cpoa_id);
      if(count($get_cpoa)!=0){
        redirect('cert/lista_requerimientos/'.$cpoa_id.'');
      }
      else{
        $this->session->set_flashdata('error','ERROR ... ');
        redirect('cert/form_items/'.$prod_id);
      }

    }
    else{
      echo "Error !!!";
    }
  }

  /// valida Certificacion POA
  public function valida_form_cpoa(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $cpoa_id = $this->security->xss_clean($post['cpoa_id']);
      $recomendacion = $this->security->xss_clean($post['recomendacion']);
      
      $this->certificacionpoa->generar_certificacion_poa($cpoa_id);

      $update_cpoa= array(
        'cpoa_recomendacion' => $recomendacion,
      );
      $this->db->where('cpoa_id', $cpoa_id);
      $this->db->update('certificacionpoa', $this->security->xss_clean($update_cpoa));

      /*--- Redirecciona Vista a Certificacion POA ---*/
      redirect('cert/ver_cpoa/'.$cpoa_id.'');
    }
    else{
      echo "Error !!!";
    }
  }

  //// redirecciona a la vista para Certificacion POA
  public function lista_requerimientos_cpoa($cpoa_id){
    if($this->fun_id==399 || $this->fun_id==401){
      //$this->lista_requerimientos_cpoa2($cpoa_id); //// Generacion normal de Certificacion POA
      $this->lista_requerimientos_cpoa_cert_rapida($cpoa_id); //// manera rapida
    }
    else{
      $this->lista_requerimientos_cpoa2($cpoa_id); //// Generacion normal de Certificacion POA
    }
  }

  //// lista de Requerimientos a Certificar de Manera Rapida
  public function lista_requerimientos_cpoa_cert_rapida($cpoa_id){
    $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id);
    if(count($cpoa)!=0){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cpoa[0]['proy_id']); /// PROYECTO
        //$data['base']='<input name="base" type="text" value="'.base_url().'">';
        $data['menu']=$this->certificacionpoa->menu(4);
        $data['titulo']='
            <div role="alert">
              <h1><b>CERTIFICACION POA N° CITE </b> : <small><font color="#000">'.strtoupper($cpoa[0]['cpoa_cite']).'</font> | </small>'.strtoupper($cpoa[0]['proy_nombre'].' - '.$cpoa[0]['tipo_subactividad'].' '.$cpoa[0]['serv_descripcion']).'</h1>
            </div> ';

        $requerimientos = $this->certificacionpoa->items_disponibles_a_certificar_select_rapido($cpoa); /// Items Disponibles (Seleccion Rapida)

        if($cpoa[0]['cpoa_estado']==1){
          redirect('cert/ver_cpoa/'.$cpoa_id.''); /// redireccionando al reporte de certificacion poa
        }
        else{
          $data['formulario']='
            <div class=row>
            <input name="base" type="hidden" value="'.base_url().'">
            <div class="col-sm-12">
            
            <div class="well">
              <h1 class="semi-bold"><b>REQUERIMIENTOS DISPONIBLES PARA SU CERTIFICACION POA</b></h1>
              '.$requerimientos.'
              </div>
            </div>

          </div>';
        }

        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_prevista', $data);

    }
    else{
      echo "Error !!!";
    }
  }

  //// lista de Requerimientos a Certificar
  public function lista_requerimientos_cpoa2($cpoa_id){
    $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id);
    if(count($cpoa)!=0){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($cpoa[0]['proy_id']); /// PROYECTO
        //$data['base']='<input name="base" type="text" value="'.base_url().'">';
        $data['menu']=$this->certificacionpoa->menu(4);
        $data['titulo']='
            <div role="alert">
              <h1><b>CERTIFICACION POA N° CITE </b> : <small><font color="#000">'.strtoupper($cpoa[0]['cpoa_cite']).'</font> | </small>'.strtoupper($cpoa[0]['proy_nombre'].' - '.$cpoa[0]['tipo_subactividad'].' '.$cpoa[0]['serv_descripcion']).'</h1>
            </div> ';

        $requerimientos = $this->certificacionpoa->items_disponibles_a_certificar($cpoa); /// Items Disponibles item por item
        //$requerimientos = $this->certificacionpoa->items_disponibles_a_certificar_select_rapido($cpoa); /// Items Disponibles (Seleccion Rapida)

        if($cpoa[0]['cpoa_estado']==1){
          redirect('cert/ver_cpoa/'.$cpoa_id.''); /// redireccionando al reporte de certificacion poa
        }
        else{
          $data['formulario']='
            <div class=row>
            <input name="base" type="hidden" value="'.base_url().'">
            <article class="col-sm-12 col-md-12 col-lg-12">
              <!-- Widget ID (each widget will need unique ID)-->
              <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-hand-o-up"></i> </span>
                  <h2><b>VISTA PREVIA - CERTIFICACION POA </b></h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">
                    <div class="alert alert-block alert-success">
                      <a class="close" data-dismiss="alert" href="#">×</a>
                      <h4 class="alert-heading"><i class="fa fa-check-square-o"></i> REQUERIMIENTOS SELECCIONADOS! ('.$cpoa[0]['prod_id'].')</h4>
                      <p>
                        Antes de Generar la Certificacion POA Verifique los datos completos de los items seleccionados!!
                      </p>
                    </div>
                    <div id="vista_previa">'.$this->certificacionpoa->vista_previa_items_certificados($cpoa_id).'</div>
                  </div>
                </div>
              </div>
            </article>

            <div class="col-sm-12">
            
            <div class="well">
              <h1 class="semi-bold"><b>REQUERIMIENTOS DISPONIBLES PARA SU CERTIFICACION POA</b></h1>
              '.$requerimientos.'
              </div>
            </div>

          </div>';
        }

        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_prevista', $data);

    }
    else{
      echo "Error !!!";
    }
  }




    /*--- GET ADICIONA O ANULA 1 ITEM SELECCIONADO---*/
    public function adiciona_cancela_items(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $ins_id = $this->security->xss_clean($post['ins_id']);
        $cpoa_id = $this->security->xss_clean($post['cpoa_id']);
        $check = $this->security->xss_clean($post['check']); /// 1: adiciona, 0: elimina
        
        $get_cert_detalle=$this->model_certificacion->get_certificado_poa_detalle($cpoa_id,$ins_id);
        $lista_temporalidad=$this->model_insumo->lista_prog_fin($ins_id);
       
          if($check==1){ /// adiciona requerimiento

            if(count($get_cert_detalle)==0){ /// ingresa siempre y cuando no haya registro del item

              $update_ins = array(
                'ins_monto_certificado' => $lista_temporalidad[0]['ipm_fis'],
                'ins_ejec_cpoa' => 1
              );
              $this->db->where('ins_id', $ins_id);
              $this->db->update('insumos', $update_ins);

              $data_to_store = array( 
                'cpoa_id' => $cpoa_id,
                'ins_id' => $ins_id,
                'ifin_id' => 0,
                'fun_id' => $this->fun_id,
              );
              $this->db->insert('certificacionpoadetalle', $data_to_store);
              $cpoad_id=$this->db->insert_id();

              /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                $data_to_store = array(
                  'cpoad_id' => $cpoad_id,
                  'tins_id' => $lista_temporalidad[0]['tins_id'],
                );
                $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
              /*--------------------------------------------*/

              /// Actualizando el estado de la temporalidad a 1
                $update_proyect = array(
                  'estado_cert' => 1
                );
                $this->db->where('tins_id', $lista_temporalidad[0]['tins_id']);
                $this->db->where('ins_id', $ins_id);
                $this->db->update('temporalidad_prog_insumo', $update_proyect);
            }
            
          }
          else{ /// quita requerimiento a certificar
            if(count($get_cert_detalle)==1){ /// ingresa siempre y cuando no haya registro del item

              $update_ins = array(
                'ins_monto_certificado' => 0,
                'ins_ejec_cpoa' => 0
              );
              $this->db->where('ins_id', $ins_id);
              $this->db->update('insumos', $update_ins);


              /// Actualizando el estado de la temporalidad a 1
              $update_proyect = array(
                'estado_cert' => 0
              );
              $this->db->where('tins_id', $lista_temporalidad[0]['tins_id']);
              $this->db->where('ins_id', $ins_id);
              $this->db->update('temporalidad_prog_insumo', $update_proyect);

              ///-----------------------------------------
              $this->db->where('tins_id',$lista_temporalidad[0]['tins_id']);
              $this->db->delete('cert_temporalidad_prog_insumo');

              ///-----------------------------------------
              $this->db->where('cpoad_id',$get_cert_detalle[0]['cpoad_id']);
              $this->db->delete('certificacionpoadetalle');
            }
            
          }
          
          $lista='';
          $requerimientos=$this->model_certificacion->lista_items_certificados($cpoa_id); /// lista de items certificados  
          if(count($requerimientos)!=0){
            $lista.=$this->certificacionpoa->vista_previa_items_certificados($cpoa_id);
          }

        //$lista='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/reporte_solicitud_poa_aprobado/'.$cpoa_id.'"></iframe>';
        $result = array(
          'respuesta' => 'correcto',
          'vista_previa' => $lista,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }



    /*--- GET ADICIONA O ANULA ITEM SELECCIONADO POR MESES ---*/
    public function adiciona_cancela_meses_items(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $tins_id = $this->security->xss_clean($post['tins_id']);
        $cpoa_id = $this->security->xss_clean($post['cpoa_id']);
        $check = $this->security->xss_clean($post['check']); /// 1: adiciona, 0: elimina

        $get_temp_ins=$this->model_certificacion->get_id_insumo_programado_mes($tins_id); /// get mes seleccionado
        $get_insumo=$this->model_insumo->get_insumo_producto($get_temp_ins[0]['ins_id']); /// get datos insumo

          if($check==1){ /// adiciona requerimiento

            //if(count($get_temp_ins)==0){ /// ingresa siempre y cuando no haya registro del item

              $get_cert_detalle=$this->model_certificacion->get_certificado_poa_detalle($cpoa_id,$get_temp_ins[0]['ins_id']); // verificando el registro del detalle de certificacion poa
              if(count($get_cert_detalle)==0){

                $data_to_store = array( 
                  'cpoa_id' => $cpoa_id,
                  'ins_id' => $get_temp_ins[0]['ins_id'],
                  'ifin_id' => 0,
                  'fun_id' => $this->fun_id,
                );
                $this->db->insert('certificacionpoadetalle', $data_to_store);
                $cpoad_id=$this->db->insert_id();
              }
              else{
                $cpoad_id=$get_cert_detalle[0]['cpoad_id'];
              }


              /*-------- GUARDANDO ITEMS MES PROGRAMADOS -------*/
                $data_to_store = array(
                  'cpoad_id' => $cpoad_id,
                  'tins_id' => $tins_id,
                );
                $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
              /*--------------------------------------------*/

              /// Actualizando el estado de la temporalidad a 1
                $update_proyect = array(
                  'estado_cert' => 1
                );
                $this->db->where('tins_id', $tins_id);
                $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
                $this->db->update('temporalidad_prog_insumo', $update_proyect);

              /// Actualizando el monto total certificado
                $update_ins = array(
                  'ins_monto_certificado' => $get_insumo[0]['ins_monto_certificado']+$get_temp_ins[0]['ipm_fis']
                );
                $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
                $this->db->update('insumos', $update_ins);

                $get_insumo=$this->model_insumo->get_insumo_producto($get_temp_ins[0]['ins_id']); /// get datos insumo
                $ejec=0;
                if($get_insumo[0]['ins_costo_total']==$get_insumo[0]['ins_monto_certificado']){
                  $ejec=1;
                }

                $update_ins = array(
                  'ins_ejec_cpoa' => $ejec
                );
                $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
                $this->db->update('insumos', $update_ins);
            //}
            
          }
          else{ /// quita requerimiento a certificar
            //if(count($get_temp_ins)==1){ /// ingresa siempre y cuando no haya registro del item

              $update_ins = array(
                'ins_monto_certificado' => $get_insumo[0]['ins_monto_certificado']-$get_temp_ins[0]['ipm_fis'],
                'ins_ejec_cpoa' => 0
              );
              $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
              $this->db->update('insumos', $update_ins);


              /// Actualizando el estado de la temporalidad a 1
              $update_proyect = array(
                'estado_cert' => 0
              );
              $this->db->where('tins_id', $tins_id);
              $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
              $this->db->update('temporalidad_prog_insumo', $update_proyect);

              ///-----------------------------------------
              $this->db->where('tins_id',$tins_id);
              $this->db->delete('cert_temporalidad_prog_insumo');

              ///-----------------------------------------

              $get_cert_detalle=$this->model_certificacion->get_certificado_poa_detalle($cpoa_id,$get_temp_ins[0]['ins_id']); // verificando el registro del detalle de certificacion poa

              if(count($this->model_certificacion->get_list_cert_temporalidad_prog_insumo($get_cert_detalle[0]['cpoad_id']))==0){
                $this->db->where('cpoad_id',$get_cert_detalle[0]['cpoad_id']);
                $this->db->delete('certificacionpoadetalle');
              }


               /// Actualizando el monto total certificado
                $update_ins = array(
                  'ins_monto_certificado' => $get_insumo[0]['ins_monto_certificado']-$get_temp_ins[0]['ipm_fis']
                );
                $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
                $this->db->update('insumos', $update_ins);

                
                $update_ins = array(
                  'ins_ejec_cpoa' => 0
                );
                $this->db->where('ins_id', $get_temp_ins[0]['ins_id']);
                $this->db->update('insumos', $update_ins);
              
            //}
            
          }
          
          $lista='';
          $requerimientos=$this->model_certificacion->lista_items_certificados($cpoa_id); /// lista de items certificados  
          if(count($requerimientos)!=0){
            $lista.=$this->certificacionpoa->vista_previa_items_certificados($cpoa_id);
          }

       
        $result = array(
          'respuesta' => 'correcto',
          'vista_previa' => $lista,
        );
          
        echo json_encode($result);

      }else{
          show_404();
      }
    }



    /*-------- GET INSUMOS CON TEMPORALIDAD DISTRIBUIDA --------*/
    public function get_insumos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $cpoa_id = $this->security->xss_clean($post['cpoa_id']);
       
        $result = array(
          'respuesta' => 'correcto',
          'lista' => $this->certificacionpoa->list_requerimientos_temporalidad_variada($prod_id,$cpoa_id),
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }



/// ACTUALIZA CERTIFICACION POA (para reportes sin Codigo)
  public function generar_codigo($cpoa_id){
    $get_cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id);
    if(count($get_cpoa)!=0){
        if($get_cpoa[0]['cpoa_estado']==0){
          $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
          $nro_cpoa=$verificando[0]['cert_poa']+2;
          $nro_cdep='';
          if($nro_cpoa<10){
            $nro_cdep='000';
          }
          elseif($nro_cpoa<100) {
            $nro_cdep='00';
          }
          elseif($nro_cpoa<1000){
            $nro_cdep='0';
          }

          //$codigo='CPOA.'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
          
          if($this->gestion>2024){
            $codigo='DNP.'.$nro_cdep.''.$nro_cpoa.'-'.$get_cpoa[0]['adm'].'.'.$get_cpoa[0]['abrev']; /// 2025
          }
          else{
            $codigo='CPOA.'.$nro_cdep.''.$nro_cpoa.'-'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev']; /// 2024
          }
          
          if(count($this->model_certificacion->get_codigo_certpoa($codigo))==0){
              /*---- Update Estado Certificacion POA ----*/
              $update_cpoa= array(
                'cpoa_codigo' => $codigo,
                'cpoa_estado' => 1,
                'fun_id'=>$get_cpoa[0]['fun_id']
              );
              $this->db->where('cpoa_id', $cpoa_id);
              $this->db->update('certificacionpoa', $this->security->xss_clean($update_cpoa));
              /*-----------------------------------------*/

              /*----- Update Configuracion Cert distrital -----*/
              $update_conf= array(
                'cert_poa' => $nro_cpoa
              );
              $this->db->where('mod_id', $verificando[0]['mod_id']);
              $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
              /*----------------------------------------------*/
          }

          $this->session->set_flashdata('danger','EL CÓDIGO SE GENERO CORRECTAMENTE');
          redirect('cert/ver_cpoa/'.$cpoa_id.'');
        }
    }
    else{
      $this->session->set_flashdata('danger','ERROR AL GENERAR CÓDIGO');
      redirect('ejec/menu_cpoa');
    }

  }


  /*------- ACTUALIZA GESTION EN LA TEMPORALIDAD 2021 ------*/
  public function update_gestion_temporalidad($requerimientos){
    foreach($requerimientos as $row){
      $update_poa = array(
        'g_id' => $this->gestion,
      );
      $this->db->where('ins_id', $row['ins_id']);
      $this->db->update('temporalidad_prog_insumo', $update_poa);
    }
  }



  //// Valida CPOA Normal
  public function valida_cpoas(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $cpoa_id = $this->security->xss_clean($post['cpoa_id']);
     // $datos=$this->model_certificacion->get_datos_unidad_prod($prod_id); /// Gasto Corriente
     
      if (!empty($_POST["ins"]) && is_array($_POST["ins"]) ) {
        foreach ( array_keys($_POST["ins"]) as $como){

        $data_to_store = array( 
          'cpoa_id' => $cpoa_id,
          'ins_id' => $_POST["ins"][$como],
          'ifin_id' => 0,
          'fun_id' => $this->fun_id,
        );
        $this->db->insert('certificacionpoadetalle', $data_to_store);
        $cpoad_id=$this->db->insert_id();

         $lista_temporalidad=$this->model_insumo->lista_prog_fin($_POST["ins"][$como]);
          /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
            $data_to_store = array(
              'cpoad_id' => $cpoad_id,
              'tins_id' => $lista_temporalidad[0]['tins_id'],
            );
            $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
          /*--------------------------------------------*/

          /// Actualizando el estado de la temporalidad
            $update_proyect = array(
              'estado_cert' => 1
            );
            $this->db->where('tins_id', $lista_temporalidad[0]['tins_id']);
            $this->db->where('ins_id', $_POST["ins"][$como]);
            $this->db->update('temporalidad_prog_insumo', $update_proyect);

           // echo "ins_id : ".$_POST["ins"][$como]." -----uno tins_id ".$lista_temporalidad[0]['tins_id']."<br>";
          

          $get_cert_insumo=$this->model_insumo->lista_prog_fin_certificado($_POST["ins"][$como]);
          if(count($get_cert_insumo)!=0){
            /// Actualizando monto certificado por insumo
              $update_insumo = array(
                'ins_monto_certificado' => $get_cert_insumo[0]['monto_certificado'],
                'ins_ejec_cpoa' => 1
              );
              $this->db->where('ins_id', $_POST["ins"][$como]);
              $this->db->update('insumos', $update_insumo);
          }
        }

          if(count($this->model_certificacion->get_lista_detalle_cert_poa($cpoa_id)!=0)) {
            $this->session->set_flashdata('success','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
          }
          else{
            $this->session->set_flashdata('default','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
          }

          /*----- Update Codigo Certificacion POA ---*/
          $this->certificacionpoa->generar_certificacion_poa($cpoa_id);
          /*----------------------------------*/
          /*--- Redirecciona Vista a Certificacion POA ---*/
          redirect('cert/ver_cpoa/'.$cpoa_id.'');
      }
      else{
        echo "No ingresa";
      }

    }
    else{
      echo "Error !!!";
    }
  }




/// valida a corregir
// public function valida_cpoas(){
//     if ($this->input->post()) {
//       $post = $this->input->post();
//       $prod_id = $this->security->xss_clean($post['prod_id']);
//       $tp_id = $this->security->xss_clean($post['tp_id']);
//       $cite_poa = $this->security->xss_clean($post['cite_cpoa']);
//       $cite_fecha = $this->security->xss_clean($post['cite_fecha']);
//       $cite_recomendacion = $this->security->xss_clean($post['rec']);
//       $total = $this->security->xss_clean($post['tot']);

//       if($tp_id==1){
//         $datos=$this->model_certificacion->get_datos_pi_prod($prod_id); /// Gasto Proyecto de Inversión
//       }
//       else{
//         $datos=$this->model_certificacion->get_datos_unidad_prod($prod_id); /// Gasto Corriente
//       }
      
//       /*------ INSERTANDO CERTIFICADO ------*/
//         $data_to_store = array( 
//           'proy_id' => $datos[0]['proy_id'],
//           'aper_id' => $datos[0]['aper_id'], /// aper del programa padre
//           'cpoa_fecha' => date("d/m/Y H:i:s"),
//           'cpoa_gestion' => $this->gestion,
//           'cpoa_estado' => 0, /// 0 : en proceso, 1 elaborado, 2, modificado, 3 Eliminado
//           'fun_id' => $this->fun_id,
//           'com_id' => $datos[0]['com_id'],
//           'cpoa_cite' => strtoupper($cite_poa),
//           'cite_fecha' => $cite_fecha,
//           'cpoa_recomendacion' => strtoupper($cite_recomendacion),
//           'prod_id' => $prod_id,
//         );
//         $this->db->insert('certificacionpoa', $data_to_store);
//         $cpoa_id=$this->db->insert_id();
//       /*-------------------------------------*/
//       /*----- DETALLE CERTIFICACION POA -----*/
//       if (!empty($_POST["ins"]) && is_array($_POST["ins"]) ) {
//         foreach ( array_keys($_POST["ins"]) as $como){
//         $data_to_store = array( 
//           'cpoa_id' => $cpoa_id,
//           'ins_id' => $_POST["ins"][$como],
//           'ifin_id' => 0,
//           'fun_id' => $this->fun_id,
//         );
//         $this->db->insert('certificacionpoadetalle', $data_to_store);
//         $cpoad_id=$this->db->insert_id();

//          $lista_temporalidad=$this->model_insumo->lista_prog_fin($_POST["ins"][$como]);
//           /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
//             $data_to_store = array(
//               'cpoad_id' => $cpoad_id,
//               'tins_id' => $lista_temporalidad[0]['tins_id'],
//             );
//             $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
//           /*--------------------------------------------*/

//           /// Actualizando el estado de la temporalidad
//             $update_proyect = array(
//               'estado_cert' => 1
//             );
//             $this->db->where('tins_id', $lista_temporalidad[0]['tins_id']);
//             $this->db->where('ins_id', $_POST["ins"][$como]);
//             $this->db->update('temporalidad_prog_insumo', $update_proyect);

//            // echo "ins_id : ".$_POST["ins"][$como]." -----uno tins_id ".$lista_temporalidad[0]['tins_id']."<br>";
          

//           $get_cert_insumo=$this->model_insumo->lista_prog_fin_certificado($_POST["ins"][$como]);
//           if(count($get_cert_insumo)!=0){
//             /// Actualizando monto certificado por insumo
//               $update_insumo = array(
//                 'ins_monto_certificado' => $get_cert_insumo[0]['monto_certificado']
//               );
//               $this->db->where('ins_id', $_POST["ins"][$como]);
//               $this->db->update('insumos', $update_insumo);
//           }
          

//         }

//           if(count($this->model_certificacion->get_lista_detalle_cert_poa($cpoa_id)==$total)){
//             $this->session->set_flashdata('success','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
//           }
//           else{
//             $this->session->set_flashdata('default','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
//           }

//           /*----- Update Codigo Certificacion POA ---*/
//           if($datos[0]['dist_id']!=0){
//             $this->certificacionpoa->generar_certificacion_poa($cpoa_id);
//           }
//           /*----------------------------------*/
//           /*--- Redirecciona Vista a Certificacion POA ---*/
//           redirect('cert/ver_cpoa/'.$cpoa_id.'');
//       }
//       else{
//         echo "No ingresa";
//       }

//     }
//     else{
//       echo "Error !!!";
//     }
//   }


  /*--- ACTUALIZA EL MONTO CERTIFICADO A CADA REQUERIMIENTO (NUEVO 2021) ---*/
  public function actualizar_monto_certificado_por_insumo($proy_id){
    $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
    if(count($proyecto)!=0){
        $insumos=$this->model_insumo->insumos_por_unidad($proyecto[0]['aper_id']);
        foreach ($insumos as $ins){
          echo $ins['ins_id'].' '.$ins['ins_detalle']."<br>";
          $temp=$this->model_insumo->lista_prog_fin($ins['ins_id']);
          foreach ($temp as $row){
            $cert=''; $estado=0;
            if(count($this->model_certificacion->get_mes_certificado($row['tins_id']))==1){
              $estado=1;
              $cert='CERTIFICADO';
            }

            /// Actualizando el estado de la temporalidad
            $update_temp = array(
              'estado_cert' => $estado
            );
            $this->db->where('tins_id', $row['tins_id']);
            $this->db->update('temporalidad_prog_insumo', $update_temp);

            echo "temp : ".$row['tins_id']." - ".$row['mes_id']." - ".$row['estado_cert']." - ".$cert."<br>";
          }

          echo "------------------------<br>";
            
            $m_cert=$this->model_insumo->lista_prog_fin_certificado($ins['ins_id']); /// Monto Certificado
            if(count($m_cert)!=0){
              /// Actualizando el estado de la temporalidad
              $update_ins = array(
                'ins_monto_certificado' => $m_cert[0]['monto_certificado']
              );
              $this->db->where('ins_id', $ins['ins_id']);
              $this->db->update('insumos', $update_ins);
            }
          echo "------------------------<br>";
        }
    }
    else{
      redirect('cert/list_poas');
    }
  }






  /*--- VALIDA MODIFICACION DE CERTIFICACION POA (2020) ---*/
  public function valida_reformulado_cpoa(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $cpoaa_id = $this->security->xss_clean($post['cpoaa_id']); /// Id Certificación poa Anulado
      $tp_id = $this->security->xss_clean($post['tp_id']); /// Tipo de Anulacion
      $total = $this->security->xss_clean($post['tot']); /// Total Items

      $cert_anulado=$this->model_certificacion->get_cert_poa_editado($cpoaa_id); /// Datos de la Certificación Anulado
      $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cert_anulado[0]['cpoa_id']); /// Datos de la Certificación POA
      $cite_mod_req = $this->model_modrequerimiento->get_cite_insumo($cert_anulado[0]['cite_id']); // Datos Cite Modificación de requerimiento

        $this->delete_certificacion_item($cert_anulado[0]['cpoa_id']); // Eliminando anterior Registro Certificación POA
        if (!empty($_POST["ins"]) && is_array($_POST["ins"])) {
          foreach ( array_keys($_POST["ins"]) as $como){
            $data_to_store = array( 
              'cpoa_id' => $cert_anulado[0]['cpoa_id'],
              'ins_id' => $_POST["ins"][$como],
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('certificacionpoadetalle', $data_to_store);
            $cpoad_id=$this->db->insert_id();


            $temp=$this->model_insumo->lista_prog_fin($_POST["ins"][$como]);
            if(count($temp)==1){
              /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
              $data_to_store = array(
                'cpoad_id' => $cpoad_id,
                'tins_id' => $temp[0]['tins_id'],
              );
              $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
              /*--------------------------------------------*/

              /// Actualizando el estado de la temporalidad
              $update_proyect = array(
                'estado_cert' => 1
              );
              $this->db->where('tins_id', $temp[0]['tins_id']);
              $this->db->where('ins_id', $_POST["ins"][$como]);
              $this->db->update('temporalidad_prog_insumo', $update_proyect);
            }
            else{

                for ($i=1; $i <=12 ; $i++) {
                  if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){

                //    echo $_POST["ins"][$como].'------'.$_POST["ipm".$i."".$_POST["ins"][$como]]."<br>";
                    if(count($this->model_certificacion->get_mes_certificado($_POST["ipm".$i."".$_POST["ins"][$como]]))==0){

                      $data_to_store = array(
                        'cpoad_id' => $cpoad_id,
                        'tins_id' => $_POST["ipm".$i."".$_POST["ins"][$como]],
                      );
                      $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
                     

                      /// Actualizando el estado de la temporalidad
                      $update_proyect = array(
                        'estado_cert' => 1
                      );
                      $this->db->where('tins_id', $_POST["ipm".$i."".$_POST["ins"][$como]]);
                      $this->db->where('ins_id', $_POST["ins"][$como]);
                      $this->db->update('temporalidad_prog_insumo', $update_proyect);

                    }
                  } 
                }

            }
           
            $get_cert_insumo=$this->model_insumo->lista_prog_fin_certificado($_POST["ins"][$como]);
            if(count($get_cert_insumo)!=0){
              /// Actualizando monto certificado por insumo
                $update_insumo = array(
                  'ins_monto_certificado' => $get_cert_insumo[0]['monto_certificado']
                );
                $this->db->where('ins_id', $_POST["ins"][$como]);
                $this->db->update('insumos', $update_insumo);
            }

          }



          /// Actualizando el estado de la certificacion a reformulado
            $update_cerpoa = array(
              'cpoa_ref' => 1
            );
            $this->db->where('cpoa_id', $cert_anulado[0]['cpoa_id']);
            $this->db->update('certificacionpoa', $update_cerpoa);
          ////-------------------------------------------------------

          if(count($this->model_modrequerimiento->get_cite_insumo($cite_mod_req[0]['cite_id']))!=0){
            $this->genera_codigo_modreq($cite_mod_req,$cert_anulado[0]['justificacion']);
          }
          
          redirect('cert/ver_cpoa/'.$cert_anulado[0]['cpoa_id'].''); /// redireccionar al reporte
        }
        else{
          redirect('ejec/menu_cpoa'); /// Error al Reformular
        }
    }
    else{
      redirect('ejec/menu_cpoa'); /// Error al Reformular
    }
  }



  /*--- ELIMINA ITEMS CERTIFICADOS ---*/
  public function delete_certificacion_item($cpoa_id){
    $list_cpoas_anterior=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);

    foreach ($list_cpoas_anterior as $row){
      $temp_cert=$this->model_certificacion->get_meses_certificacion_items($row['cpoad_id']);
      $sum_cert=0;
      foreach ($temp_cert as $row_temp){
        $sum_cert=$sum_cert+$row_temp['ipm_fis'];
        $update_temp = array(
          'estado_cert' => 0,
        );
        $this->db->where('tins_id', $row_temp['tins_id']);
        $this->db->update('temporalidad_prog_insumo', $update_temp);
        /*----------------------------------*/
      }

      ///-----------------------------------------------------------
        $update_ins = array(
          'ins_monto_certificado' => ($row['ins_monto_certificado']-$sum_cert),
        );
        $this->db->where('ins_id', $row['ins_id']);
        $this->db->update('insumos', $update_ins);
      ///-----------------------------------------------------------
        
      $this->db->where('cpoad_id',$row['cpoad_id']);
      $this->db->delete('cert_temporalidad_prog_insumo');

      $this->db->where('cpoad_id',$row['cpoad_id']);
      $this->db->delete('certificacionpoadetalle');
    }
  }


  /*--- GENERA CODIGO DE MODIFICACIÓN REQUERIMIENTO ---*/
  public function genera_codigo_modreq($cite,$justificacion){
      if($cite[0]['cite_estado']==0){ /// Pendiente, Insert Codigo
      $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
      $nro_mod=$verificando[0]['mod_req']+1;
      $nro_cdep='';
      if($nro_mod<10){
        $nro_cdep='000';
      }
      elseif($nro_mod<100) {
        $nro_cdep='00';
      }
      elseif($nro_mod<1000){
        $nro_cdep='0';
      }

      /*--------------- Update cite ---------------*/
      $update_cite= array(
        'cite_codigo' => 'R_'.$cite[0]['adm'].'-'.$cite[0]['abrev'].'-'.$nro_cdep.''.$nro_mod,
        'cite_observacion' => strtoupper($justificacion),
        'cite_estado' => 1,
        'fun_id'=>$this->fun_id
      );
      $this->db->where('cite_id', $cite[0]['cite_id']);
      $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
      /*------------------------------------------*/

      /*----- Update Configuracion mod distrital -----*/
      $update_conf= array(
        'mod_req' => $nro_mod
      );
      $this->db->where('mod_id', $verificando[0]['mod_id']);
      $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
      /*----------------------------------------------*/
    }
    else{ /// Cerrado, Update Observacion
      $update_cite= array(
        'cite_observacion' => strtoupper($justificacion),
        'fun_id'=>$this->fun_id
      );
      $this->db->where('cite_id', $cite[0]['cite_id']);
      $this->db->update('cite_mod_requerimientos', $this->security->xss_clean($update_cite));
    }
  }


  /*---- VER CERTIFICACION POA MODIFICADO ----*/
  public function ver_certificacion_poa($cpoa_id){
    $certificacion=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos de Certificacion POa 

    if(count($certificacion)!=0){
      $data['menu']=$this->certificacionpoa->menu(4);
      $data['titulo']='<h1><b>RESPONSABLE : '.$this->session->userdata('funcionario').' -> </b><small>'.$this->certificacionpoa->tp_resp().'</small>';
      $data['opciones']=' <a class="btn btn-default" href="'.base_url().'index.php/cert/form_items/'.$certificacion[0]['prod_id'].'" target="_blank" title="NUEVA CERTIFICACI&Oacute;N"><i class="fa fa-rotate-left"></i> NUEVA CERTIFICACI&Oacute;N</a>
                <a class="btn btn-default" href="'.base_url().'index.php/ejec/menu_cpoa" title="SALIR"><i class="fa fa-caret-square-o-left"></i> SALIR</a>';

      $data['cuerpo']='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/cert/rep_cert_poa/'.$certificacion[0]['cpoa_id'].'"></iframe>'; /// Antiguo
      /*if(strtotime($certificacion[0]['cpoa_fecha'])>$this->fecha_entrada){
        $data['cuerpo']='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/cert/rep_cert_poa_editado/'.$certificacion[0]['cpoa_id'].'"></iframe>'; /// nuevo
      }
      else{
        $data['cuerpo']='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/cert/rep_cert_poa/'.$certificacion[0]['cpoa_id'].'"></iframe>'; /// Antiguo
      }*/

      $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/ver_certificado_poa', $data);
    }
    else{
      echo "Error !!!";
    }
  }

    /*----- REPORTE CERTIFICADO POA EDITADO/MODIFICADO PDF -------*/
    public function reporte_cpoa_editado($cpoa_id){
      $certificacion=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
      if (count($certificacion)!=0) {
        $data['verif_certificacion_final']=true;
        $data['cabecera_cpoa']=$this->certificacionpoa->cabecera_certpoa($certificacion,$certificacion[0]['cpoa_codigo']); /// Cabecera Certificacion POA
        $data['items_certificados_final']=$this->certificacionpoa->items_certificados($cpoa_id); /// Lista de items certificados Editado
        $data['pie_certpoa']=$this->certificacionpoa->pie_certificacion_poa($certificacion,2); /// Pie Certificacion POA
        $data['pie_reporte']='Certificacion_Poa_editado'.$certificacion[0]['tipo_subactividad'].' '.$certificacion[0]['serv_descripcion'].' '.$certificacion[0]['abrev'];
        $data['verif_certificacion_original']=false; /// Certificado original editado
        $data['verif_modificacion']=false; //// modificacion poa
        $data['verif_solicitud']=false; /// Solicitud POA

        $cert_edit=$this->model_certificacion->get_datos_certificado_anulado($cpoa_id);
        
        if(count($cert_edit)!=0){
          $cite = $this->model_modrequerimiento->get_cite_insumo($cert_edit[0]['cite_id']); /// cite modificacion poa
          $datos_modificacion=$this->model_modrequerimiento->get_cite_insumo($cert_edit[0]['cite_id']);
          $data['cabecera_modpoa']=$this->certificacionpoa->cabecera_modpoa($datos_modificacion);
          $data['pie_mod']=$this->certificacionpoa->pie_modpoa($datos_modificacion,$certificacion[0]['cpoa_codigo']);

          if($cite[0]['tp_reporte']==0){ /// anterior formato 2022
            if(count($this->model_modrequerimiento->list_requerimientos_modificados($cert_edit[0]['cite_id']))!=0){
              /*------------ Modificación POA ------------*/
              $data['verif_modificacion']=true; //// modificacion poa
              $data['items_modificados']=$this->certificacionpoa->items_modificados_edicionpoa($cert_edit[0]['cite_id']); /// items modificados 2022
            }
          }
          else{ /// Nuevo formato de modificacion 2023
            if(count($this->model_modrequerimiento->list_form5_historial_modificados($cert_edit[0]['cite_id'],2))!=0){
              /*------------ Modificación POA ------------*/
              $data['verif_modificacion']=true; //// modificacion poa
              $data['items_modificados']=$this->certificacionpoa->items_form5_historial($cert_edit[0]['cite_id']); /// items modificados 2023
              
            }
          }


          /*------------ Certificacion POA Original ------------*/
          $data['verif_certificacion_original']=true; /// Certificado original editado
          $data['cabecera_cpoa_original']=$this->certificacionpoa->cabecera_certpoa($certificacion,$cert_edit[0]['codigo_cert_anterior']); /// Cabecera Certificacion POA Original
          $data['items_certificados_original']=$this->certificacionpoa->items_certificados_original_guardados($cpoa_id,1); /// Lista de items certificados Original
          $data['pie_certpoa_original']=$this->certificacionpoa->pie_certificacion_poa($certificacion,1); /// Pie Certificacion POA
        }


        if($certificacion[0]['sol_id']!=0){
          $data['verif_solicitud']=true;
          $data['solicitud'] = $this->model_certificacion->get_solicitud_cpoa($certificacion[0]['sol_id']);
          $data['cabecera']=$this->certificacionpoa->cabecera_solicitudpoa($data['solicitud']);
          $data['datos_unidad_articulacion']=$this->certificacionpoa->datos_unidad_organizacional($data['solicitud']); /// Datos de Articulacion POa
           $data['items']=$this->certificacionpoa->items_certificados_original_guardados($cpoa_id,0); /// Lista de items certificados Original
          //$data['items']=$this->certificacionpoa->lista_solicitud_requerimientos($certificacion[0]['sol_id']); /// Requerimientos solicitados
          $data['conformidad']=$this->certificacionpoa->conformidad_solicitud($data['solicitud']); /// firma unidad
          $data['pie_reporte']='Certificacion_Poa_Aprobado '.$data['solicitud'][0]['tipo_subactividad'].' '.$data['solicitud'][0]['serv_descripcion'].' '.$data['solicitud'][0]['abrev'];
        }

        /*echo $data['items_certificados_final'];
        echo '<br>';
        echo $data['items_modificados'];
        echo '<br>';
        echo $data['items_certificados_original'];*/

        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_solicitud_cpoa_editado', $data);   
      }
      else{
        echo "Error !!!";
      }
    }


  /*---- VER CERTIFICACION POA ELIMINADO ----*/
  public function ver_certificacion_poa_anulado($cpoa_id){
    $data['menu']=$this->certificacionpoa->menu(4);
    $cert_edit=$this->model_certificacion->get_datos_certificacion_poa_anulados($cpoa_id);
    $data['titulo']='<h1><b>RESPONSABLE : '.$this->session->userdata('funcionario').' -> </b><small>'.$this->certificacionpoa->tp_resp().'</small>';
    $data['opciones']=' <a class="btn btn-default" href="'.base_url().'index.php/cert/form_items/'.$cert_edit[0]['prod_id'].'" target="_blank" title="NUEVA CERTIFICACI&Oacute;N"><i class="fa fa-rotate-left"></i> NUEVA CERTIFICACI&Oacute;N</a>
                <a class="btn btn-default" href="'.base_url().'index.php/ejec/menu_cpoa" title="SALIR"><i class="fa fa-caret-square-o-left"></i> SALIR</a>';

    $data['cuerpo']='<iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/cert/rep_cert_poa_anulado/'.$cpoa_id.'"></iframe>'; /// nuevo
    $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/ver_certificado_poa', $data);
  }

  
    /*----- REPORTE CERTIFICADO POA ELIMINADO PDF -------*/
    public function reporte_cpoa_anulado($cpoa_id){
      $cert_edit=$this->model_certificacion->get_datos_certificacion_poa_anulados($cpoa_id);

      if(count($cert_edit)!=0){
        $data['verif_certificacion_final']=false;
        $data['verif_certificacion_original']=true; /// Certificado original editado
        $data['verif_modificacion']=false; //// modificacion poa
        $data['verif_solicitud']=false; /// Solicitud POA

        $data['cabecera_cpoa_original']=$this->certificacionpoa->cabecera_certpoa($cert_edit,$cert_edit[0]['cpoa_codigo']); /// Cabecera Certificacion POA Original
        $data['items_certificados_original']=$this->certificacionpoa->items_certificados_original_guardados($cpoa_id,1); /// Lista de items certificados Original
        $data['pie_certpoa_original']=$this->certificacionpoa->pie_certificacion_poa($cert_edit,1); /// Pie Certificacion POA
        $data['pie_reporte']='Certificacion_Poa_anulado'.$cert_edit[0]['tipo_subactividad'].' '.$cert_edit[0]['serv_descripcion'].' '.$cert_edit[0]['abrev'];

        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_solicitud_cpoa_editado', $data); 
      }
      else{
        echo "Error !!!";
      }
      
    }




    /*----- REPORTE CERTIFICADO POA PDF -------*/
    public function reporte_cpoa($cpoa_id){
      $data['cpoa']=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
      if (count($data['cpoa'])!=0) {
          $data['programa'] = $this->model_certificacion->get_apertura_programatica($data['cpoa'][0]['aper_id']);
          $data['datos']=$this->model_certificacion->get_datos_unidad_prod($data['cpoa'][0]['prod_id']); // Datos completos hasta apertura
          $data['items']=$this->mis_items_certificados($cpoa_id);
          $data['nro']=count($this->model_certificacion->lista_items_certificados($cpoa_id));
     
          if($this->gestion==2020){ /// Gestion 2020
            $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_cert_poa_2020', $data);
          }
          else{ /// Gestion 2023
            if(strtotime($data['cpoa'][0]['cpoa_fecha'])>$this->fecha_entrada){
              redirect('reporte_solicitud_poa_aprobado/'.$cpoa_id); /// Reporte nuevo
            }
            else{
              $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_cert_poa', $data);  
            }
          }
          
      }
      else{
        echo "Error !!!";
      }
    }


     /*-------- LISTA DE ITEMS CERTIFICADOS 2020 -------*/
    public function mis_items_certificados($cpoa_id){
      $tabla='';
      $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
      if($cpoa[0]['cpoa_estado']==3){
        $requerimientos=$this->model_certificacion->lista_items_certificados_anulados($cpoa_id); /// lista de items certificados Eliminados
      }
      else{
        $requerimientos=$this->model_certificacion->lista_items_certificados($cpoa_id); /// lista de items certificados  
      }

      $tabla.='<table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="left">
                <thead>
                <tr class="modo1" align="center">
                  <th style="width:2%;background-color: #1c7368; color: #FFFFFF;height:15px;">#</th>
                  <th style="width:10%;background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                  <th style="width:50%;background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                  <th style="width:10%;background-color: #1c7368; color: #FFFFFF">MONTO TOTAL PROGRAMADO</th>
                </tr>
                </thead>
                <tbody>';
                $nro=0;$suma_monto=0;
                foreach($requerimientos as $row){
                  $nro++;
                  $suma_monto=$suma_monto+$row['ins_costo_total'];
                  $bgcolor='';
                  if(count($this->model_certificacion->get_verif_modreq_certpoa($cpoa_id,$row['ins_id']))!=0){
                    $bgcolor='#ecebea';
                  }
                  
                  $tabla.=
                  '<tr class="modo1" bgcolor='.$bgcolor.'>
                    <td style="width: 2%;" style="height:10px;" align="center">'.$nro.'</td>
                    <td style="width: 10%; font-size: 9.5px;" align="center"><b>'.$row['par_codigo'].'</b></td>
                    <td style="width: 50%;">'.$row['ins_detalle'].'</td>
                    <td style="width: 10%;"align="right">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                  </tr>';
                }
      $tabla.=' </tbody>
                <tr>
                  <td colspan=3 style="height:10px;">TOTAL PROGRAMADO</td>
                  <td align=right><b>'.number_format($suma_monto, 2, ',', '.').'</b></td>
                </tr>
              </table><br>';

      $rango=$this->model_certificacion->datos_complementarios_cpoa($cpoa_id);
      if(count($rango)!=0){
        $tabla.='
        <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="right">
          <tr class="modo1" align="center">
            <td style="width:10%;background-color: #ece9e9;height:10px;">MES INICIO</td>
            <td style="width:10%;background-color: #ece9e9;">MES FINAL</td>
            <td style="width:20%;background-color: #ece9e9;">MONTO TOTAL CERTIFICADO</td>
          </tr>
          <tr class="modo1">
            <td style="height:10px;">'.$rango[0]['inicio_mes'].'</td>
            <td>'.$rango[0]['final_mes'].'</td>
            <td align=right><b>'.number_format($rango[0]['monto_total_certificado'], 2, ',', '.').'</b></td>
          </tr>
        </table>';
      }

      return $tabla;
    }


    /*-------- FORMULARIO MODIFICACION DE CERTIFICACIÓN POA 2023 -------*/
    public function modificar_cpoa($cpoaa_id){
      $data['cert_editado']=$this->model_certificacion->get_cert_poa_editado($cpoaa_id);
      if(count($data['cert_editado'])!=0 & $data['cert_editado'][0]['cpoa_estado']!=3){
        $data['cpoa']=$this->model_certificacion->get_datos_certificacion_poa($data['cert_editado'][0]['cpoa_id']); /// Datos Certificacion
          $data['datos']=$this->model_certificacion->get_datos_unidad_prod($data['cert_editado'][0]['prod_id']); /// Datos completos de la Unidad/ Proyectos de Inversión
          $data['menu']=$this->certificacionpoa->menu(4);
          $data['titulo']=$this->certificacionpoa->titulo_cabecera($data['datos']);
          $data['lista']=$this->model_certificacion->requerimientos_modificar_cpoa($data['cert_editado'][0]['cpoa_id']); /// Lista Requerimientos
          $data['requerimientos'] = $this->certificacionpoa->list_requerimientos_certificados($data['lista'],$data['cert_editado'][0]['cpoa_id']); /// Lista de Items Certificados
         // $data['nro_cert'] = count($this->model_certificacion->lista_items_certificados($data['cert_editado'][0]['cpoa_id'])); // Nro de Items Certificados
          $data['nro_meses'] = $this->model_certificacion->get_nro_mes_certificado_cpoa($data['cert_editado'][0]['cpoa_id']); // Nro de Meses
          
          $data['display']='';
          if(count($data['lista'])==0){
            $data['display']='style="display: none"';
          }

          $data['opciones_update']='';

          if(count($data['lista'])>100){
            $data['opciones_update']='
            <a href="'.site_url("").'/cert/exportar_items_certificados/'.$data['cert_editado'][0]['cpoa_id'].'" target=_blank class="btn btn-default" title="EXPORTAR ITEMS CERTIFICADOS"><img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="19" HEIGHT="19"/>&nbsp;<b style="font-size:9px">EXPORTAR INFORMACION (EXCEL)</b></a>
            <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" title="SUBIR ARCHIVO EXCEL">
              <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="25" HEIGHT="20"/>&nbsp;<b style="font-size:9px">SUBIR ARCHIVO.CSV</b>
            </a>';
          }
          $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_edit_cert', $data);
      }
      else{
        redirect('ejec/menu_cpoa');
      }
    }

   






 /*---COMBO DE UNIDADES / ESTABLECIMIENTOS SEGUN SU REGIONAL (2020)---*/
    public function get_programado_temporalidad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        
        $ins_id = $this->security->xss_clean($post['ins_id']);  /// ins id
        $cpoa_id = $this->security->xss_clean($post['cpoa_id']); /// cpoa id

        $monto_certificado=$this->model_certificacion->get_insumo_monto_cpoa_certificado($ins_id,$cpoa_id);
        $verif_cert=0;
        if(count($monto_certificado)!=0){
          $verif_cert=1;
        }


        for ($i=1; $i <=12 ; $i++) { 
          $pmes=$this->model_certificacion->get_insumo_programado_mes($ins_id,$i);
          if(count($pmes)!=0){
            if(count($this->model_certificacion->get_mes_certificado_cpoa($cpoa_id,$pmes[0]['tins_id']))!=0){
              $verf['verf_mes'.$i]=1; /// Mes Certificado Actual formulario
            }
            elseif(count($this->model_certificacion->get_mes_certificado($pmes[0]['tins_id']))!=0){
              $verf['verf_mes'.$i]=2; // Mes que ya fue Certificado en otra certificación
            }
            else{
              $verf['verf_mes'.$i]=0; // Mes disponible a certificar
            }
          }
          else{
            $verf['verf_mes'.$i]=3; // Mes no Programado
          }
        }


        $result = array(
          'respuesta' => "correcto",
          'temporalidad' => $verf,
          'verif_cert' => $verif_cert,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


  /*======= FUNCIONES EXTRAS ======*/
    /*-------- GET ACTIVIDADES DE ALINEACION PARA CERTIFICACION --------*/
    public function get_actividades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
        
        if($proyecto[0]['tp_id']==1){
          $tabla=$this->certificacionpoa->mis_formulariosN4($proy_id); /// Mis operaciones por Unidad Responsable
        }
        else{
          $presupuesto=$this->model_certificacion->saldo_presupuesto_unidad($proy_id);

          if((($presupuesto[0]['saldo']>0 || $presupuesto[0]['saldo']==0) & count($presupuesto)!=0) || $proyecto[0]['proy_id']==2978){
            $tabla=$this->certificacionpoa->mis_formulariosN4($proy_id); /// Mis Formularios n° 4 por Unidad Responsable
          }
          else{
            $tabla='<div class="alert alert-danger" role="alert">
                      SE DEBE AJUSTAR EL PRESUPUESTO POA DEBIDO A QUE EXISTE UN SOBREGIRO NEGATIVO : '.number_format($presupuesto[0]['saldo'], 2, ',', '.').' Bs.
                    </div>';
          }
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


    /*-------- EXPORTAR ITEMS CERTIFICADOS -------*/
    public function exportar_certificacion($cpoa_id){
      $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
      $lista_requerimientos=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id); /// Lista Requerimientos
      if(count($lista_requerimientos)!=0){
        
          $tabla='';
          $tabla.='
          <style>
            table{font-size: 9px;
              width: 100%;
              max-width:1550px;
              overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
          </style>
          <table table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;">
            <thead>
            <tr>
              <th bgcolor=green style="color:white">#</th>
              <th bgcolor=green style="color:white">PARTIDA</th>
              <th bgcolor=green style="color:white">DETALLE REQUERIMIENTO</th>
              <th bgcolor=green style="color:white">UNIDAD DE MEDIDA</th>
              <th bgcolor=green style="color:white">CANTIDAD</th>
              <th bgcolor=green style="color:white">COSTO UNITARIO</th>
              <th bgcolor=green style="color:white">COSTO TOTAL</th>
              <th bgcolor=green style="color:white">ENE.</th>
              <th bgcolor=green style="color:white">FEB.</th>
              <th bgcolor=green style="color:white">MAR.</th>
              <th bgcolor=green style="color:white">ABR.</th>
              <th bgcolor=green style="color:white">MAY.</th>
              <th bgcolor=green style="color:white">JUN.</th>
              <th bgcolor=green style="color:white">JUL.</th>
              <th bgcolor=green style="color:white">AGO.</th>
              <th bgcolor=green style="color:white">SEPT.</th>
              <th bgcolor=green style="color:white">OCT.</th>
              <th bgcolor=green style="color:white">NOV.</th>
              <th bgcolor=green style="color:white">DIC.</th>
            </tr>
            </thead>
            <tbody>';
            foreach($lista_requerimientos as $row){
              $temporalidad=$this->model_insumo->list_temporalidad_insumo($row['ins_id']);
              $tabla.='
              <tr>
                <td bgcolor="#fbe3e3">'.$row['ins_id'].'</td>
                <td align=right bgcolor="#fbe3e3">'.$row['par_codigo'].'</td>
                <td>'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>
                <td>'.mb_convert_encoding(strtoupper($row['ins_unidad_medida']), 'cp1252', 'UTF-8').'</td>
                <td align=right bgcolor="#fbe3e3">'.round($row['ins_cant_requerida'],2).'</td>
                <td align=right bgcolor="#fbe3e3">'.round($row['ins_costo_unitario'],2).'</td>
                <td align=right bgcolor="#fbe3e3">'.round($row['ins_costo_total'],2).'</td>';

                if(count($temporalidad)!=0){
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td align=right bgcolor="#fbe3e3">'.round($temporalidad[0]['mes'.$i],2).'</td>';
                  }
                }
                else{
                  for ($i=1; $i <=12 ; $i++) { 
                    $tabla.='<td bgcolor=red>-</td>';
                  }
                }
                
                $tabla.='
              </tr>';
            }

          $tabla.='
            </tbody>
          </table>';

          header('Content-type: application/vnd.ms-excel');
          header("Content-Disposition: attachment; filename=CERT_POA ".$cpoa[0]['cpoa_codigo']." ".$cpoa[0]['cpoa_fecha'].".xls"); //Indica el nombre del archivo resultante
          header("Pragma: no-cache");
          header("Expires: 0");
          echo "";
          ini_set('max_execution_time', 0); 
          ini_set('memory_limit','3072M');
          echo $tabla;

      }
      else{
        redirect('ejec/menu_cpoa');
      }
    }

































  //// VALIDAR SOLICITUD DE CERTIFICIÓN POA (ADMINISTRADOR)

  /*-- VALIDAR SOLICITUD DE CERTIFICACIÓN POA --*/
  public function ver_mis_solicitudes_certpoa(){
    $data['menu']=$this->certificacionpoa->menu(4);
    $data['titulo']='<h1>RESPONSABLE : '.$this->session->userdata('funcionario').' -> <small>'.$this->certificacionpoa->tp_resp().'</small></h1>
                    <input type="hidden" name="resp" id="resp" value="'.$this->session->userdata('funcionario').'">';

    $data['alert']='<h2 class="alert alert-success"><center>LISTA DE SOLICITUDES DE CERTIFICACIÓN POA '.$this->gestion.'</center></h2>';
    $data['opcion']='Hola Mundo';
    if($this->tp_adm==1){ /// Nacional
      $data['regional']=$this->select_regionales(); /// Lista de regionales
      $data['items']='';
    }
    else{ /// Regional
      $data['regional']='';
      $data['items']=$this->certificacionpoa->lista_solicitudes_certificacionespoa_regional($this->dep_id);
    }

    $data['loading']='<div id="loading" style="display:none;" style="width:20%;"><section id="widget-grid" class="well" align="center"><img src="'.base_url().'/assets/img/cargando-loading-039.gif" width="40%" height="30%"></section></div>';

    $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/mis_solicitudes_cpoa', $data);
  }



  //// Seleccion de Regionales
  public function select_regionales(){
    $regionales=$this->model_proyecto->list_departamentos();
    $tabla='';
    $tabla='
      <form class="form-horizontal">
        <input name="base" type="hidden" value="'.base_url().'">
        <fieldset>
          <div class="form-group">
            <label class="col-md-2 control-label">SELECCIONE REGIONAL</label>
            <div class="col-md-2">
              <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                <option value="0">SELECCIONE REGIONAL</option>';
                foreach($regionales as $row){
                  if($row['dep_id']!=0){
                    $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                  }
                }
                $tabla.='
              </select>
            </div>
          </div>
        </fieldset>
      </form>';
    return $tabla;
  }

  /*-------- GET CUADRO SOLICITUDES DE CERTIFICACION POA --------*/
  public function get_cuadro_solicitudes_certificacionpoa(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $dep_id = $this->security->xss_clean($post['dep_id']); // dep id

      $tabla='<hr>'.$this->certificacionpoa->lista_solicitudes_certificacionespoa_regional($dep_id); /// Lista de Solicitudes POA por Regional

      $result = array(
        'respuesta' => 'correcto',
        'tabla'=>$tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }




  //// CERTIFICACION POA POR SUBACTIVIDAD (UNIDAD RESPONSABLE)
  /*------ SOLICITAR CERTIFICACION POA  -------*/
  public function solicitar_certpoa($com_id){

    $componente = $this->model_componente->get_componente($com_id,$this->gestion);
    if(count($componente)!=0){

      $data['menu'] = $this->certificacionpoa->menu_segpoa($com_id,2);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']);
      $titulo='<div style="font-family: new times roman;">'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].' / '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</div>';
      
      $presupuesto=$this->model_certificacion->saldo_presupuesto_unidad($componente[0]['proy_id']);
      if(($presupuesto[0]['saldo']>0 || $presupuesto[0]['saldo']==0) & count($presupuesto)!=0){
        $data['select_ope']=$this->certificacionpoa->select_mis_productos($com_id,$titulo,0); /// Seleccion de productos
      }
      else{
        $data['select_ope']='
          <div class="alert alert-danger" role="alert">
            SE DEBE AJUSTAR EL PRESUPUESTO POA DEBIDO A QUE EXISTE UN SOBREGIRO NEGATIVO : '.number_format($presupuesto[0]['saldo'], 2, ',', '.').' Bs.
          </div>';
      }

      $data['loading']='
        <div id="loading" style="display:none;" style="width:20%;"><section id="widget-grid" class="well" align="center"><img src="'.base_url().'/assets/img/cargando-loading-039.gif" width="40%" height="30%"></section></div>';
      
      $data['paso3']='
        <div class="well" align="Left" id="paso3" style="display: none">
          <fieldset>
            <span class="badge bg-color-green" style="font-size: 35px;">Paso 3)</span> <span class="badge bg-color-green" style="font-size: 25px;"> Porfavor verifique los datos de los items seleccionados, como:</span><br><br>
            <p class="alert alert-info" style="font-size: 20px;">
              <strong>
              Detalle de Requerimiento, Unidad de medida, Precio unitario, Costo Total y el mes programado a certificar.
              </strong>
            </p>
          </fieldset>

          <fieldset class="demo-switcher-1">
            <div class="form-group">
              <label class="col-md-6 control-label" >
               <div style="font-family: new times roman; font-size: 19px;"><b>ESTA SEGURO EN GENERAR LA SOLICITUD DE CERTIFICACIÓN POA ?</b></div>
              </label>
              <div class="col-md-6">
                <label class="radio radio-inline" style="font-family: new times roman; font-size: 18px;">
                  <input type="radio" class="paso3" id="check1" name="paso3" style="width: 20px; height: 20px" value="si">
                  <span>&nbsp;SI</span>
                </label>
                <label class="radio radio-inline" style="font-family: new times roman; font-size: 18px;">
                  <input type="radio" class="paso3" id="check2" name="paso3" style="width: 20px; height: 20px" value="no">
                  <span>&nbsp;NO</span>  
                </label>
              </div>
            </div>
          </fieldset>
        </div>';

      $data['loading_form']='<div id="load" style="display: none" align="center">
                              <br><img  src="'.base_url().'/assets/img_v1.1/preloader.gif" width="100"><br><b>GENERANDO SOLICITUD DE CERTIFICACI&Oacute;N POA ....</b>
                            </div>';

      $this->load->view('admin/ejecucion/certpoa_unidad/formulario_certificacionpoa', $data);
    }
    else{
      echo "Error !!!";
    }
  }


  /*---- SOLICITAR CERTIFICACION POA - PROG. 72 BIENES Y SERVICIOS (2022) ----*/
  public function solicitar_certpoa_prog72($com_id){

    $componente = $this->model_componente->get_componente($com_id,$this->gestion);
    if(count($componente)!=0){

      $data['menu'] = $this->certificacionpoa->menu_segpoa($com_id,2);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad(2651);/// ID PROG 72 (GESTION 2022)
      $titulo='<div style="font-family: new times roman;">PROGRAMA '.$proyecto[0]['aper_programa'].' : '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].' / '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'].'</div>';
      
      $presupuesto=$this->model_certificacion->saldo_presupuesto_unidad($componente[0]['proy_id']);
      if(($presupuesto[0]['saldo']>0 || $presupuesto[0]['saldo']==0) & count($presupuesto)!=0){
        $data['select_ope']=$this->certificacionpoa->select_mis_productos($com_id,$titulo,1); /// Seleccion de productos
      }
      else{
        $data['select_ope']='
          <div class="alert alert-danger" role="alert">
            SE DEBE AJUSTAR EL PRESUPUESTO POA DEBIDO A QUE EXISTE UN SOBREGIRO NEGATIVO : '.number_format($presupuesto[0]['saldo'], 2, ',', '.').' Bs.
          </div>';
      }

      $data['loading']='
        <div id="loading" style="display:none;" style="width:20%;"><section id="widget-grid" class="well" align="center"><img src="'.base_url().'/assets/img/cargando-loading-039.gif" width="40%" height="30%"></section></div>';
      
      $data['paso3']='
        <div class="well" align="Left" id="paso3" style="display: none">
          <fieldset>
            <span class="badge bg-color-green" style="font-size: 35px;">Paso 3)</span> <span class="badge bg-color-green" style="font-size: 25px;"> Porfavor verifique los datos de los items seleccionados, como:</span><br><br>
            <p class="alert alert-info" style="font-size: 20px;">
              <strong>
              Detalle de Requerimiento, Unidad de medida, Precio unitario, Costo Total y el mes programado a certificar.
              </strong>
            </p>
          </fieldset>

          <fieldset class="demo-switcher-1">
            <div class="form-group">
              <label class="col-md-6 control-label" >
               <div style="font-family: new times roman; font-size: 19px;"><b>ESTA SEGURO EN GENERAR LA SOLICITUD DE CERTIFICACIÓN POA ?</b></div>
              </label>
              <div class="col-md-6">
                <label class="radio radio-inline" style="font-family: new times roman; font-size: 18px;">
                  <input type="radio" class="paso3" id="check1" name="paso3" style="width: 20px; height: 20px" value="si">
                  <span>&nbsp;SI</span>
                </label>
                <label class="radio radio-inline" style="font-family: new times roman; font-size: 18px;">
                  <input type="radio" class="paso3" id="check2" name="paso3" style="width: 20px; height: 20px" value="no">
                  <span>&nbsp;NO</span>  
                </label>
              </div>
            </div>
          </fieldset>
        </div>';

      $data['loading_form']='<div id="load" style="display: none" align="center">
                              <br><img  src="'.base_url().'/assets/img_v1.1/preloader.gif" width="100"><br><b>GENERANDO SOLICITUD DE CERTIFICACI&Oacute;N POA ....</b>
                            </div>';

   // $tabla=$this->formulario_certpoa(66458,1);
      $this->load->view('admin/ejecucion/certpoa_unidad/formulario_certificacionpoa', $data);
    }
    else{
      echo "Error !!!";
    }
  }

  /*-------- GET CUADRO CERTIFICACION POA --------*/
  public function get_cuadro_certificacionpoa(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']); // prod id
      $tp = $this->security->xss_clean($post['tp']); // tp
      if($tp==0){
        $producto=$this->model_producto->get_producto_id($prod_id);
        $com_id = $producto[0]['com_id']; // filtro normal
      }
      else{
        $com_id = $this->security->xss_clean($post['com_id']); // com id  del servicio a buscar en el programa 72
      }

      
      $tabla=$this->formulario_certpoa($prod_id,$tp,$com_id);
      
      $result = array(
        'respuesta' => 'correcto',
        'requerimientos'=>$tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*------ FORMULARIO CERTIFICACION POA (SOLICITUD) -------*/
  public function formulario_certpoa($prod_id,$tp,$com_id){
     /// para listas mayores a 500
    $tabla='';
    $tabla.='
        <div class="well">
        <input type="hidden" name="tot" id="tot" value="0">
        <input type="hidden" name="tot_temp" id="tot_temp" value="0">
        <input type="hidden" name="prod_id" id="prod_id" value="'.$prod_id.'">
        <input type="hidden" name="com_id" id="com_id" value="'.$com_id.'">
        <input type="hidden" name="tp" id="tp" value="'.$tp.'">
        <fieldset>
          <span class="badge bg-color-green" style="font-size: 35px;">Paso 2)</span> <span class="badge bg-color-green" style="font-size: 25px;"> Seleccione Items a certificar </span><hr>
        </fieldset>
        <fieldset>
          <section class="col col-4">
            <input id="searchTerm" type="text" onkeyup="doSearch()" style="width:35%;" class="form-control" placeholder="Buscador de Requerimientos...."/><br>
          </section>
          <div class="row" align="center">
            <div class="table-responsive" align="center">
              <center>
                '.$this->certificacionpoa->list_requerimientos_2022($prod_id,$tp,$com_id).'
              </center>
            </div>
          </div>
        </fieldset>
        </div>';
    return  $tabla;
  }

    /*--- VERIFICANDO MES CERTIFICADO ---*/
    function verif_mes_certificado(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $tins_id = $post['tins_id']; /// tins id

          if(count($this->model_certificacion->get_mes_certificado($tins_id))==0){
            echo "true"; /////  Se puede certificar el mes
          }
          else{
            echo "false"; //// ya se encuentra certificado
          }
 
      }else{
        show_404();
      }
    }

  /*------ VALIDA SOLICITUD DE CERTIFICACION POA (2020 - 2021) ------*/
  public function valida_solicitud(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $com_id = $this->security->xss_clean($post['com_id']);
      $tp = $this->security->xss_clean($post['tp']);
      $total = $this->security->xss_clean($post['tot']);
      $producto=$this->model_producto->get_producto_id($post['prod_id']);
      $verif_nro_cite=$this->model_modrequerimiento->verif_modificaciones_distrital($producto[0]['dist_id']);
      $nro_cite='Sin Cite';
      if(count($verif_nro_cite)!=0){
        $nro_cite=$producto[0]['abrev'].'-'.($verif_nro_cite[0]['cite_certpoa']+1).'/'.$this->gestion;

        /*---------------------------------------------*/
          $update_mod = array(
            'cite_certpoa' => ($verif_nro_cite[0]['cite_certpoa']+1)
          );
          $this->db->where('g_id', $this->gestion);
          $this->db->where('dist_id', $producto[0]['dist_id']);
          $this->db->update('conf_modificaciones_distrital', $update_mod);
        /*---------------------------------------------*/
      }

      /*---- insertando solicitud ---*/
      $data_to_store = array( 
        'com_id' => $com_id,
        'prod_id' => $prod_id,
        'g_id' => $this->gestion,
        'cite' => $nro_cite,
        'tp' => $tp, /// normal o prog 72
        'num_ip' => $this->input->ip_address(), 
        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
      );
      $this->db->insert('solicitud_cpoa_subactividad', $data_to_store);
      $sol_id=$this->db->insert_id();
      /*-----------------------------*/


      if (!empty($_POST["ins"])) {
        foreach (array_keys($_POST["ins"]) as $como){
            $data_to_store = array( 
              'sol_id' => $sol_id,
              'ins_id' => $_POST["ins"][$como],
            );
            $this->db->insert('requerimiento_solicitado', $data_to_store);
            $req_id=$this->db->insert_id();

            $lista_temporalidad=$this->model_insumo->lista_prog_fin($_POST["ins"][$como]);
              
              if(count($lista_temporalidad)>1){
                $nro=0;
                for ($i=1; $i <=12 ; $i++) {
                  if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){
                    /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                    $data_to_store = array(
                      'req_id' => $req_id,
                      'tins_id' => $_POST["ipm".$i."".$_POST["ins"][$como]],
                    );
                    $this->db->insert('temporalidad_req_solicitado', $data_to_store);
                    $nro++;
                    /*--------------------------------------------*/
                  } 
                }

                if($nro==0){
                  $this->db->where('req_id', $req_id);
                  $this->db->delete('requerimiento_solicitado');
                }
              }
              else{
                /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                  $data_to_store = array(
                    'req_id' => $req_id,
                    'tins_id' => $lista_temporalidad[0]['tins_id'],
                  );
                  $this->db->insert('temporalidad_req_solicitado', $data_to_store);
                /*--------------------------------------------*/
              }
        }

        $this->session->set_flashdata('danger','SE GENERO CORRECTAMNETE LA SOLCITUD DE CERTIFICACIÓN POA');
        redirect('solicitud_poa/'.$sol_id.'');
      
      }
      else{
        echo "No ingresa";
      }

    }
    else{
      echo "Error !!!";
    }
  }

  /*------ SOLICITUD CERTIFICACION POA  -------*/
  public function solicitud_certpoa($sol_id){
    $solicitud = $this->model_certificacion->get_solicitud_cpoa_aux($sol_id);
    if(count($solicitud)!=0){
        $data['menu'] = $this->certificacionpoa->menu_segpoa($solicitud[0]['com_id'],2);
        $data['li']='<li>Solicitar Certificación POA</li><li>Solicitud de Certificación POA</li>';
        if(count($solicitud)!=0 & $solicitud[0]['estado']!=3){
          
          $data['titulo']='<div style="font-size: 15px; font-family: Arial;"><b>SOLICITUD DE CERTIFICACIÓN POA GENERADO </b>(En menos de 24 horas se tendra aprobado su solicitud)</div>';
          $data['opcion']='
            <div class="btn-group btn-group-justified">
              <a href="'.site_url("").'/solicitar_certpoa/'.$solicitud[0]['com_id'].'" class="btn btn-default" style="width:35%;" title="NUEVA SOLICITUD CERTIFICACION POA"  name="'.$sol_id.'">
                <img src="'.base_url().'assets/img/add_icon.png" width="20" height="20"/>&nbsp;&nbsp;<b>NUEVA SOLICITUD</b>
              </a>
         
              <a href="#" class="btn btn-default del_solicitud" style="width:35%;" title="ELIMINAR SOLICITUD CERTIFICACION POA"  name="'.$sol_id.'">
                <img src="'.base_url().'assets/img/delete.png" width="20" height="20"/>&nbsp;&nbsp;<b>ANULAR SOLICITUD</b>
              </a>

              <a href="'.site_url("").'/dashboar_seguimiento_poa" class="btn btn-success" style="width:30%;" title="SALIR A MENU">
                <i class="fa fa-caret-square-o-left"></i>&nbsp;&nbsp;<b>SALIR A MENU</b>
              </a>
            </div>';
          $data['cuerpo']='
            <input name="base" type="hidden" value="'.base_url().'">
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <span class="badge bg-color-green" style="font-size: 35px;">Paso 5)</span> <span class="badge bg-color-green" style="font-size: 25px;"> Solicitud Generada Exitosamente, comuniquese con el Dpto. o Unidad de Planificación para su aprobación..</span><hr>
              <iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>
            </article>';
        }
        else{
          $data['titulo']='<div style="font-size: 15px; font-family: Arial;"><b>SOLICITUD DE CERTIFICACIÓN POA GENERADO </b>(En menos de 24 horas se tendra aprobado su solicitud)</div>';
          $data['opcion']='<a href="'.base_url().'index.php/solicitar_certpoa/'.$solicitud[0]['com_id'].'" title="VOLVER ATRAS" class="btn btn-default" style="width:100%;"><img src="'.base_url().'assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;GENERAR SOLICITUD</a>';
          $data['cuerpo']='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="alert alert-danger" role="alert">
                              LA SOLICITUD DE CERTIFICACION POA FUE ANULADA...
                            </div>
                          </article>';
        }

        $this->load->view('admin/ejecucion/certpoa_unidad/ver_solicitudpoa', $data);
    }
    else{
      redirect('seguimiento_poa');
    }
  }


  /*------ REPORTE SOLICITUD CERTIFICACION POA  -------*/
  public function reporte_solicitud_certpoa($sol_id){
    /// solicitud: 0 -> revision
    /// Solicitud: 1 -> Aprobado
    /// Solicitud: 2 -> Rechazado
    $data['solicitud'] = $this->model_certificacion->get_solicitud_cpoa($sol_id);
    if($data['solicitud'][0]['estado']==0 || $data['solicitud'][0]['estado']==2){ /// Revision o Rechazado
        $data['cabecera']=$this->certificacionpoa->cabecera_solicitudpoa($data['solicitud']);
        $data['datos_unidad_articulacion']=$this->certificacionpoa->datos_unidad_organizacional($data['solicitud']); /// Datos de Articulacion POa
        $data['items']=$this->certificacionpoa->lista_solicitud_requerimientos($sol_id); /// Requerimientos solicitados
        $data['conformidad']=$this->certificacionpoa->conformidad_solicitud($data['solicitud']); /// firma unidad
        
        $data['verif_solicitud']=true;
        $data['verif_certificacion']=false;
        $data['pie_reporte']='Solicitud_Certificacion_Poa '.$data['solicitud'][0]['tipo_subactividad'].' '.$data['solicitud'][0]['serv_descripcion'].' '.$data['solicitud'][0]['abrev'];
    }
    else{ /// Aprobado
        $certpoa=$this->model_certificacion->get_solicitud_certificado($sol_id);
        $certificacion=$this->model_certificacion->get_datos_certificacion_poa($certpoa[0]['cpoa_id']); /// Datos Certificacion
        if(count($certificacion)!=0){
            $data['verif_solicitud']=false;
            $data['verif_certificacion']=true;
            
            $data['cabecera_cpoa']=$this->certificacionpoa->cabecera_certpoa($certificacion,$certificacion[0]['cpoa_codigo']); /// Cabecera Certificacion POA
            $data['items_certificados']=$this->certificacionpoa->items_certificados($certpoa[0]['cpoa_id']); /// Lista de items certificados
            $data['pie_certpoa']=$this->certificacionpoa->pie_certificacion_poa($certificacion,1); /// Pie Certificacion POA
            $data['pie_reporte']='Certificacion_Poa'.$certificacion[0]['tipo_subactividad'].' '.$certificacion[0]['serv_descripcion'].' '.$certificacion[0]['abrev'];
        }
        else{ /// No aparece la certificacion poa
            $data['verif_solicitud']=false;
            $data['verif_certificacion']=false;
            $data['pie_reporte']='Certificacion_Poa no encontrada !!!';
        }
    }
    
   
    $this->load->view('admin/ejecucion/certpoa_unidad/reporte_solicitud_cpoa', $data);
  }

  

  /*------ REPORTE SOLICITUD CERTIFICACION POA APROBADO -------*/
  public function reporte_solicitud_probado_certpoa($cpoa_id){
    $certificacion=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Certificacion
    // cpoa_ref: 0 (Certificacion normal)
    // cpoa_ref: 1 (Certfiicacion editado)

    if(count($certificacion)!=0){
      if($certificacion[0]['cpoa_ref']==0){ /// Certificacion Normal
          $data['verif_certificacion']=true;
          $data['cabecera_cpoa']=$this->certificacionpoa->cabecera_certpoa($certificacion,$certificacion[0]['cpoa_codigo']); /// Cabecera Certificacion POA
          $data['items_certificados']=$this->certificacionpoa->items_certificados($cpoa_id); /// Lista de items certificados
          $data['pie_certpoa']=$this->certificacionpoa->pie_certificacion_poa($certificacion,1); /// Pie Certificacion POA
          $data['pie_reporte']='Certificacion_Poa'.$certificacion[0]['tipo_subactividad'].' '.$certificacion[0]['serv_descripcion'].' '.$certificacion[0]['abrev'];
          $data['verif_solicitud']=false;

          if($certificacion[0]['sol_id']!=0){
            $data['verif_solicitud']=true;
            $data['solicitud'] = $this->model_certificacion->get_solicitud_cpoa($certificacion[0]['sol_id']);
            $data['cabecera']=$this->certificacionpoa->cabecera_solicitudpoa($data['solicitud']);
            $data['datos_unidad_articulacion']=$this->certificacionpoa->datos_unidad_organizacional($data['solicitud']); /// Datos de Articulacion POa
            $data['items']=$this->certificacionpoa->lista_solicitud_requerimientos($certificacion[0]['sol_id']); /// Requerimientos solicitados
            $data['conformidad']=$this->certificacionpoa->conformidad_solicitud($data['solicitud']); /// firma unidad
            $data['pie_reporte']='Certificacion_Poa_Aprobado '.$data['solicitud'][0]['tipo_subactividad'].' '.$data['solicitud'][0]['serv_descripcion'].' '.$data['solicitud'][0]['abrev'];
          }

          $this->load->view('admin/ejecucion/certpoa_unidad/reporte_solicitud_cpoa', $data);
      }
      else{ //// Certificado Editado
        redirect('cert/rep_cert_poa_editado/'.$cpoa_id.''); /// redireccionar al reporte
      } 
    }
    else{
      echo "Error !!!";
    }
  }


  /*--- ANULA LA SOLICITUD DE CERTIFCACION POA ---*/
  function anula_solicitud_cpoa(){
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post = $this->input->post();
      $sol_id = $post['sol_id']; /// Solicitud Id
      $solicitud=$this->model_certificacion->get_solicitud_cpoa($sol_id); // solicitud
      $requerimientos=$this->model_certificacion->get_lista_requerimientos_solicitados($sol_id); // Requerimientos

      foreach($requerimientos as $row){
        $this->db->where('req_id', $row['req_id']);
        $this->db->delete('temporalidad_req_solicitado');

        $this->db->where('req_id', $row['req_id']);
        $this->db->delete('requerimiento_solicitado');
      }

      $update_proy = array(
        'estado' => 3,
        'fecha_proceso' => date("d/m/Y H:i:s"),
        'num_ip' => $this->input->ip_address(), 
        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
      );
      $this->db->where('sol_id', $sol_id);
      $this->db->update('solicitud_cpoa_subactividad', $update_proy);


      $result = array(
        'respuesta' => 'correcto'
      );

      echo json_encode($result);
    }
  }


  /*------ MIS SOLICITUDES CERTIFICACION POA  -------*/
  public function mis_solicitudes_certificacionespoa($com_id){
    $componente = $this->model_componente->get_componente($com_id,$this->gestion);
    if(count($componente)!=0){
      $data['menu'] = $this->certificacionpoa->menu_segpoa($com_id,2);
      $data['li']='<li>Mis Solicitudes de Certificación POA</li>';
      $data['titulo']='<div style="font-size: x-large; font-variant: small-caps;"><b>MIS SOLICITUDES </b>(En menos de 24 horas se tendra aprobado su solicitud)</div>';
      $data['opcion']='
        <a href="'.base_url().'index.php/solicitar_certpoa/'.$com_id.'" title="GENERAR NUEVA SOLICITUD" class="btn btn-default" style="width:45%;"><img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;GENERAR SOLICITUD</a>
        <a href="'.site_url().'/dashboar_seguimiento_poa" class="btn btn-success" style="width:45%;" title="SALIR A MENU">
          <i class="fa fa-caret-square-o-left"></i>&nbsp;&nbsp;<b>SALIR A MENU</b>
        </a>';
      
      $data['cuerpo']='<article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        '.$this->certificacionpoa->lista_solicitudes_certificacionespoa($com_id).'<br>
                      </article>
                      <article class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="well" id="ver"></div>
                      </article>';

    // echo $com_id;
      $this->load->view('admin/ejecucion/certpoa_unidad/ver_solicitudpoa', $data);
    }
    else{
      echo "Error !!!";
    }
  }


  /*-------- GET VER REPORTE DE SOLICITUD DE CERTIFICACION POA --------*/
  public function get_ver_solicitudcpoa(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $sol_id = $this->security->xss_clean($post['sol_id']); // sol id
      $solicitud=$this->model_certificacion->get_solicitud_cpoa($sol_id); // solicitud
      $resp='error';

      if(count($solicitud)!=0){
        $resp='correcto';
        if($solicitud[0]['estado']==0){
          $tabla='
          <div class="alert alert-warning alert-block">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <h4 class="alert-heading">Solicitud de Certificación POA en proceso de Revisión</h4>
              </div>
          <iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>';
        }
        elseif($solicitud[0]['estado']==1){
          $tabla='
          <div class="alert alert-success alert-block">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <h4 class="alert-heading">Solicitud e Certificación POA aprobado !!!</h4>
              </div>
          <iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>';
        }
        elseif($solicitud[0]['estado']==2){
          $tabla='
          <div class="alert alert-warning alert-block">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <h4 class="alert-heading">la Solicitud fue rechazado !!!</h4>
              </div>
          <iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>';
        }
        else{
          $tabla='
              <div class="alert alert-danger alert-block">
                <a class="close" data-dismiss="alert" href="#">×</a>
                <h4 class="alert-heading">Solicitud e Certificación POA no encontrado !!!</h4>
              </div>';
        }
      }


      $result = array(
        'respuesta' => $resp,
        'tabla'=> $tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


  /*---- Obtiene Datos la solicitud de Certificacion POA ---*/
  public function get_datos_solicitud(){
    if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $sol_id = $this->security->xss_clean($post['sol_id']);

        $sol_poa=$this->model_certificacion->get_solicitud_cpoa($sol_id);

        if(count($sol_poa)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'solicitud' => $sol_poa
          );
        }
        else{
          $result = array(
              'respuesta' => 'error'
          );
        }

        echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*--- APROBAR LA SOLICITUD DE CERTIFCACION POA ---*/
  function aprobar_solicitud_cpoa(){
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post = $this->input->post();
      $sol_id = $this->security->xss_clean($post['sol_id']); /// Solicitud Id
      $recomendacion = $this->security->xss_clean($post['recomendacion']); /// Recomendacion
      $sello = $this->security->xss_clean($post['sello']); /// sello
      $solicitud=$this->model_certificacion->get_solicitud_cpoa($sol_id); // solicitud
      $requerimientos=$this->model_certificacion->get_lista_requerimientos_solicitados($sol_id); // Requerimientos

      /*------ GUARDANDO CERTIFICADO POA ------*/
        $data_to_store = array( 
          'proy_id' => $solicitud[0]['proy_id'],
          'aper_id' => $solicitud[0]['aper_id'], /// aper del programa padre
          'cpoa_fecha' => date("d/m/Y H:i:s"),
          'cpoa_gestion' => $this->gestion,
          'cpoa_estado' => 0, /// 0 : en proceso, 1 elaborado, 2, modificado, 3 Eliminado
          'fun_id' => $this->fun_id,
          'com_id' => $solicitud[0]['com_id'],
          'cpoa_cite' => $solicitud[0]['cite'],
          'cite_fecha' => $solicitud[0]['fecha'],
          'prod_id' => $solicitud[0]['prod_id'],
          'sol_id' => $solicitud[0]['sol_id'],
          'cpoa_recomendacion' => $recomendacion,
          'cpoa_sello' => $sello,
        );
        $this->db->insert('certificacionpoa', $data_to_store);
        $cpoa_id=$this->db->insert_id();
      /*-------------------------------------*/

      foreach($requerimientos as $row){
        $data_to_store = array( 
          'cpoa_id' => $cpoa_id,
          'ins_id' => $row['ins_id'],
          'ifin_id' => 0,
          'fun_id' => $this->fun_id,
        );
        $this->db->insert('certificacionpoadetalle', $data_to_store);
        $cpoad_id=$this->db->insert_id();

          /*---- Temporalidad ----*/
          $temporalidad=$this->model_certificacion->get_lista_temporalidad_solicitados($row['req_id']);
          foreach($temporalidad as $rowt){
            $data_to_store = array(
              'cpoad_id' => $cpoad_id,
              'tins_id' => $rowt['tins_id'],
            );
            $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
          }
          /*----------------------*/
      
          /*--------- Update Req. estado -------*/
          $update_solreq = array(
            'req_estado' => 1
          );
          $this->db->where('req_id', $row['req_id']);
          $this->db->update('requerimiento_solicitado', $update_solreq);
          /*------------------------------------*/
      }

      /*--------- Update Solicitud -------*/
        $update_solicitud = array(
          'estado' => 1,
          'fecha_proceso' => date("d/m/Y H:i:s")
        );
        $this->db->where('sol_id', $sol_id);
        $this->db->update('solicitud_cpoa_subactividad', $update_solicitud);
      /*------------------------------------*/

      $this->certificacionpoa->generar_certificacion_poa($cpoa_id);


      $cert='<hr><iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa_aprobado/'.$cpoa_id.'"></iframe>';

      $result = array(
        'respuesta' => 'correcto',
        'certpoa' => $cert
      );

      echo json_encode($result);
    }
  }


  /*--- RECHAZAR LA SOLICITUD DE CERTIFCACION POA (Administrador)---*/
  function rechazar_solicitud_cpoa(){
    if ($this->input->is_ajax_request() && $this->input->post()) {
      $post = $this->input->post();
      $sol_id = $post['sol_id']; /// Solicitud Id
      $observacion = $post['observacion']; /// Observacion
      $solicitud=$this->model_certificacion->get_solicitud_cpoa($sol_id); // solicitud

      /*--------- Update Solicitud (Rechazar) -------*/
        // estado : 0 (Solicitud)
        // estado : 1 (Aprobado)
        // estado : 2 (Rechazado)
        // estado : 3 (Eliminado)

        $update_solicitud = array(
          'estado' => 2,
          'aclaracion' => $observacion,
        //  'fun_id' => $this->fun_id,
          'fecha_proceso' => date("d/m/Y H:i:s")
        );
        $this->db->where('sol_id', $sol_id);
        $this->db->update('solicitud_cpoa_subactividad', $update_solicitud);
      /*------------------------------------*/
      $cert='<hr><iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>';

     // $cert='Hola';
      $result = array(
        'respuesta' => 'correcto',
        'certpoa' => $cert
      );

      echo json_encode($result);

    }
  }

////================== CERTIFICACION POA POR PARTIDAS

  /*--- lista de partidas globales ---*/
  public function certificar_partidas($proy_id){
  $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); /// Datos Proyecto

/*    if(count($proyecto)!=0){
        $insumos=$this->model_insumo->insumos_por_unidad($proyecto[0]['aper_id']);
        foreach ($insumos as $ins){
          echo $ins['ins_id'].' '.$ins['ins_detalle']."<br>";
          $temp=$this->model_insumo->lista_prog_fin($ins['ins_id']);
          foreach ($temp as $row){
            $cert=''; $estado=0;
            if(count($this->model_certificacion->get_mes_certificado($row['tins_id']))==1){
              $estado=1;
              $cert='CERTIFICADO';
            }

            /// Actualizando el estado de la temporalidad
            $update_temp = array(
              'estado_cert' => $estado
            );
            $this->db->where('tins_id', $row['tins_id']);
            $this->db->update('temporalidad_prog_insumo', $update_temp);

            echo "temp : ".$row['tins_id']." - ".$row['mes_id']." - ".$row['estado_cert']." - ".$cert."<br>";
          }

          echo "------------------------<br>";
            
            $m_cert=$this->model_insumo->lista_prog_fin_certificado($ins['ins_id']); /// Monto Certificado
            if(count($m_cert)!=0){
              /// Actualizando el estado de la temporalidad
              $update_ins = array(
                'ins_monto_certificado' => $m_cert[0]['monto_certificado']
              );
              $this->db->where('ins_id', $ins['ins_id']);
              $this->db->update('insumos', $update_ins);
            }
          echo "------------------------<br>";
        }
    }
    else{
      redirect('cert/list_poas');
    }*/
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

    /*--------- rol funcionario ----------*/
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

    function rolfunn($tp_rol){
      $valor=false;
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$tp_rol);
      if(count($data)!=0){
        $valor=true;
      }
      return $valor;
    }
}