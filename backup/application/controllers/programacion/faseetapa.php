<?php

class Faseetapa extends CI_Controller {  
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
          $this->load->library('pdf2');
          $this->load->model('programacion/insumos/minsumos');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('programacion/model_faseetapa');
          $this->load->model('programacion/model_componente');
          $this->load->model('programacion/model_producto');
          $this->load->model('programacion/model_actividad');
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion'); /// Gestion
          $this->fun_id = $this->session->userData('fun_id'); /// Fun id
          $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
          $this->adm = $this->session->userData('adm');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
        }
        else{
          redirect('/','refresh');
        }
    }
    
    /*---- LISTA DE FASES DEL PROYECTO DE INVERSION (2019) ----*/
    function list_fase_etapa($id_p){
      $data['menu']=$this->menu(2);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($id_p);
      if(count($data['proyecto'])!=0){
        $data['fases'] = $this->model_faseetapa->fase_etapa_proy($id_p);
        $this->load->view('admin/programacion/proy_anual/fase/list_fase_etapa', $data);
      }
      else{
        redirect('/','refresh');
      }
    }

    /*============================ BORRA DATOS F/E=================================*/
    public function delete_faseetapa($id_p){ 

        $this->db->where('proy_id', $id_p);
        $this->db->delete('_proyectofaseetapacomponente');
    }

  /*------- Formulario de Registro de Fase (2019) ------------*/
  function nueva_fase($id_p){
    $data['menu']=$this->menu(2);
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($id_p);

    if(count($data['proyecto'])!=0){
      $data['fase']=$this->model_faseetapa->fases();
      $diferencia=($data['proyecto'][0]['fin']-$data['proyecto'][0]['inicio']);
      if($diferencia==0){
        $data['dif']='<div class="alert alert-info alert-block">FASE ACTUAL - ANUAL</div>';
      }
      elseif ($diferencia!=0) {
        $data['dif']='<div class="alert alert-info alert-block">FASE ACTUAL - PLURIANUAL</div>'; 
      }

      $data['unidad_org'] = $this->model_proyecto->list_unidad_org(); //// unidad organizacional
      $data['f_top'] = $this->model_proyecto->responsable_proy($id_p,'1'); //// unidad ejecutora
      $this->load->view('admin/programacion/proy_anual/fase/form_fase_add', $data); 
    }
    else{
      redirect('admin/dashboard');
    }
  }

  function fase_presupuesto($pfec_id){
    $data['menu']=$this->menu(2);
    $fase=$this->model_faseetapa->get_fase($pfec_id);
    if(count($fase)!=0){
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
      $data['fase_proyecto']=$this->model_faseetapa->fase_etapa($fase[0]['pfec_id'],$fase[0]['proy_id']);
      $data['presupuesto']=$this->presupuesto($data['proyecto'][0]['proy_id'],$pfec_id);
      
      $this->load->view('admin/programacion/proy_anual/fase/form_fase_update2', $data); 
    }
     
  }
  /*------------ PRESUPUESTO FASE -----------------*/
  public function presupuesto($proy_id,$pfec_id){
    $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
    $ptto_fase =$this->model_faseetapa->ptto_fase($pfec_id);
    $fase =$this->model_faseetapa->get_fase($pfec_id);
    $programado=$this->model_faseetapa->sum_ptto_fase($pfec_id);

    $tabla ='';
    $tabla .='
      <article class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
        <div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false" data-widget-custombutton="false">
        <header>
          <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
          <h2>ASIGNAR PRESUPUESTO</h2>       
        </header>
        <div>
          <div class="jarviswidget-editbox">
          </div>
          <div class="widget-body no-padding">
           <form action="'.site_url('admin').'/proy/add_fe2" id="form_nuevo3" name="form_nuev3" class="smart-form" method="post">
              <input type="hidden" name="proy_id" id="proy_id" value='.$proy_id.'>
              <input type="hidden" name="pfec_id" id="pfec_id" value='.$pfec_id.'>
              <input type="hidden" name="nro" id="nro" value='.count($ptto_fase).'>

              <header>
                <center><div class="alert alert-info">ASIGNAR PRESUPUESTO '.$fase[0]['pfec_fecha_inicio'].' - '.$fase[0]['pfec_fecha_fin'].'</div></center>
              </header>
              <fieldset>
                <section>
                  <div class="row">
                    <label class="label col col-2"><b>PRESUPUESTO TOTAL FASE :</b></label>
                    <div class="col col-2">
                      <label class="input"> <i class="icon-append fa fa-money"></i>
                        <input type="text" name="ptto" id="ptto" value='.$fase[0]['pfec_ptto_fase'].' onkeyup="suma_presupuesto();" disabled="true">
                      </label>
                    </div>
                    <label class="label col col-2"><b>PROGRAMADO TOTAL FASE :</b></label>
                    <div class="col col-2">
                      <label class="input"> <i class="icon-append fa fa-money"></i>
                        <input type="hidden" name="ptto_p" value="'.$programado[0]['programado'].'">
                        <input type="text" id="ptto_p" value="'.$programado[0]['programado'].'" onkeyup="suma_presupuesto_p();" disabled="true">
                      </label>
                    </div>
                    <label class="label col col-2"><b>EJECUTADO TOTAL FASE :</b></label>
                    <div class="col col-2">
                      <label class="input"> <i class="icon-append fa fa-money"></i>
                        <input type="hidden" name="ptto_e" value='.$programado[0]['ejecutado'].'>
                        <input type="text" id="ptto_e" value='.$programado[0]['ejecutado'].' onkeyup="suma_presupuesto_e();" disabled="true">
                      </label>
                    </div>
                  </div>
                </section>
              </fieldset>
              <fieldset>
                <div class="row">';
                $nro=0;
                foreach ($ptto_fase as $row) {
                  $nro++;
                  $tabla .='
                  <section class="col col-2">
                    <label class="input"><b>GESTI&Oacute;N '.$row['g_id'].'</b><hr>
                      <input type="hidden" name="fgp_id[]" value='.$row['ptofecg_id'].'>
                      PROGRAMADO : <input type="text" name="monto[]" id="fgp_id'.$nro.'" value='.$row['pfecg_ppto_total'].' onkeyup="suma_presupuesto();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                      EJECUTADO : <input type="text" name="monto_e[]" id="fgp_id_e'.$nro.'" value='.$row['pfecg_ppto_ejecutado'].' onkeyup="suma_presupuesto_e();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
                    </label>
                  </section>';
                }
              $tabla .='
                </div>
              </fieldset>
              <fieldset>
                <section>
                  <div class="row">
                    <label class="label col col-2"><b>SALDO POR PROGRAMAR:</b></label>
                    <div class="col col-4">
                      <label class="input">
                        <input type="text" name="saldo" id="saldo" value='.($fase[0]['pfec_ptto_fase']-$programado[0]['programado']).' disabled>
                      </label>
                    </div>
                    <div id="tit">';
                        if(($fase[0]['pfec_ptto_fase']-$programado[0]['programado'])==0){
                          $tabla.='<font color="#42F990"> SIN SALDO</font>';
                        }
                        elseif (($fase[0]['pfec_ptto_fase']-$programado[0]['programado'])>0) {
                          $tabla.='<font color="red"> SALDO PENDIENTE</font>';
                        }
                        elseif (($fase[0]['pfec_ptto_fase']-$programado[0]['programado'])<0) {
                          $tabla.='<font color="red"> SALDO SOBREGIRADO</font>';
                        }
                    $tabla.='  
                    </div>
                  </div>
                </section>
              </fieldset>
              <footer>';
                if($this->rol_id==1){
                  if($fase[0]['pfec_ptto_fase']!=0){
                    $tabla .='<div id="but"><button type="button" name="add_form3" id="add_form3" class="btn btn-primary" title="Guardar Presupuesto de la Operaci&oacute;n">Guardar Presupuesto</button></div>';
                  }
                  else{
                    $tabla .='<font color="red">Operaci&oacute;n sin Presupuesto Asigando</font>';
                  }
                }
                if($proyecto[0]['tp_id']==1){
                  $tabla.='<a href="'.base_url().'index.php/admin/proy/list_proy#tabs-a" class="btn btn-default" title="SALIR A LISTA DE PROYECTOS DE INVERSI&Oacute;N"> Cancelar </a>';
                }
                else {
                  $tabla.='<a href="'.base_url().'index.php/admin/proy/list_proy" class="btn btn-default" title="SALIR A LISTA DE PROYECTOS DE ACTIVIDADES"> Cancelar </a>';
                }
              $tabla.='
              
              </footer>
              <center><img id="load3" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="20" height="20"></center>
            </form>
          </div>
        </div>
      </div>
      </article>';

    return $tabla;  
  }
/*=========================================================================================================================*/

/*================================ ELIMINAR TECHO PREUPUESTARIO X ===============================*/
  function delete_recurso($proy_id,$ptofecg_id,$ffofet_id,$nro){
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $lista_fases_gestion=$this->model_faseetapa->list_fases_gestiones($fase[0]['id']);
      foreach ($lista_fases_gestion as $row){
         //  echo "pfec id ".$row['pfec_id']." - ".$row['g_id']."<br>";
          $this->db->where('ptofecg_id', $row['ptofecg_id']);
          $this->db->where('nro', $nro);
          $this->db->delete('_ffofet');
      }

    //  $this->model_audi->store_audi('_ffofet',3,$ptofecg_id); 

    $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE EL RECURSO');
    redirect(site_url("admin") . '/proy/ver_techo_ptto/'.$proy_id.'/'.$ptofecg_id.'/true');
  }


  /*----- ENCENDER FASE ETAPA -----*/
   public function encender_fase(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $id_f = $post['id_f'];
          $id_proy = $post['id_p'];

          $pfec=$this->model_faseetapa->verif_off($id_f,$id_proy);

           if($pfec[0]['pfec_estado'] == 1){
              echo "true"; /////fase encendido
           }
           else{
              $this->model_faseetapa->encender_fase_etapa($id_f,$id_proy);
              echo "false"; ///// fase apagado, actulizado
           }
      
      }else{
        show_404();
      }
    }
    /*----------------------------*/


    /*----- ENCENDER FASE ETAPA (mantenimiento)-----*/
   public function encender_fase_gestion(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $pfec_id = $post['pfec_id'];
          $valor = $post['valor'];
          
          $update_pfe = array(
            'pfec_estado' => $valor
          );
          $this->db->where('pfec_id', $pfec_id);
          $this->db->update('_proyectofaseetapacomponente', $update_pfe);

          echo "true"; /////fase encendido

      }else{
        show_404();
      }
    }
    /*----------------------------*/

    
    /*------- VALIDA FASE ETAPA -------*/
    function add_fase(){ 
      if($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('proy_id', 'Proyecto', 'required|trim');
        $proy_id=$this->security->xss_clean($this->input->post('proy_id'));
        
        if($this->form_validation->run()) {
          $fas_id=$this->security->xss_clean($this->input->post('fase'));
          $etapa_id=$this->security->xss_clean($this->input->post('etapas'));
          $desc=$this->security->xss_clean($this->input->post('desc'));
          $f_inicio=$this->security->xss_clean($this->input->post('f_inicio'));
          $f_final=$this->security->xss_clean($this->input->post('f_final'));
          $monto=$this->security->xss_clean($this->input->post('monto_total'));
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
          $vpoa=$this->model_proyecto->responsable_proy($proy_id,2);

          /*------------------------------------------------*/
            $data_to_store = array(
              'proy_id' => $proy_id,
              'fas_id' => $fas_id,
              'eta_id' => $etapa_id,
              'pfec_descripcion' => strtoupper($desc),
              'pfec_fecha_inicio_ddmmaaa' => $f_inicio,
              'pfec_fecha_fin_ddmmaaa' => $f_final,
              'pfec_fecha_registro' => date('d/m/Y h:i:s'),
              'pfec_ejecucion' => 1,
              'pfec_ptto_fase' => $monto,
              'aper_id' => $proyecto[0]['aper_id'],
              'fun_id' => $this->fun_id,
              'unidad_ejec' => $proyecto[0]['dist_id'],
            );
            $this->db->insert('_proyectofaseetapacomponente', $data_to_store);
            $pfec_id = $this->db->insert_id();

            $fase=$this->model_faseetapa->get_fase($pfec_id);
            $fechas = $this->model_faseetapa->fechas_fase($pfec_id);
            $activo=0;
            if(count($fase)==0){
              $activo=1;
            }

            $update_fase = array(  
              'pfec_fecha_inicio' => $fechas[0]['inicio'],
              'pfec_fecha_fin' => $fechas[0]['final'],
              'pfec_estado' => $activo);

            $this->db->where('proy_id', $proy_id);
            $this->db->where('pfec_id', $pfec_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase);

            /*---------------  fase etapa gestion ----------------*/
              for($i=$fechas[0]['inicio'];$i<=$fechas[0]['final'];$i++){
                $data_to_store2 = array(
                  'pfec_id' => $pfec_id,
                  'g_id' => $i,
                  'fun_id' => $this->fun_id,
                );
                $this->db->insert('ptto_fase_gestion', $data_to_store2);
              }

              /*------------ Fase Pre-Inversion ----------*/
              if($fas_id==1){
                $query=$this->db->query('set client_encoding= WIN1252;');
                $data_to_store3 = array(
                  'pfec_id' => $pfec_id,
                  'com_componente' => 'PRE-INVERSIÓN',
                  'serv_id' => 1,
                  'resp_id' =>$vpoa[0]['fun_id'],
                  'uni_id' =>$proyecto[0]['dist_id'],
                  'fun_id' => $this->fun_id,
                );
                $this->db->insert('_componentes', $data_to_store3);
              }
              /*=================================================================*/
              $this->session->set_flashdata('success','LOS DATOS GENERALES DE LA FASE SE REGISTRARON CORRECTAMENTE');
              redirect('admin/proy/fase_ptto/'.$pfec_id.'');
              /*-----------------------------------------------------------------------*/

        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR FASE');
          redirect(site_url("admin").'/proy/list_proy#tabs-a');
        }
      }
      else{
        echo "<font color='red'><center>ERROR !!!! , POR FAVOR CONTACTESE CON EL DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</center></font>";
      }
    }

  /*------------ VALIDA PRESUPUESTO FASE ------------------*/  
    function add_fase2(){
      if($this->input->post()){
          $this->form_validation->set_rules('pfec_id', 'Fase', 'required|trim');
          if($this->form_validation->run()) {

            $proy_id=$this->security->xss_clean($this->input->post('proy_id'));
            $pfec_id=$this->security->xss_clean($this->input->post('pfec_id'));
            $ptto=$this->security->xss_clean($this->input->post('ptto'));
            $ptto_p=$this->security->xss_clean($this->input->post('ptto_p'));
            $ptto_e=$this->security->xss_clean($this->input->post('ptto_e'));

            $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); //// Datos del proyecto

              //  echo "PROY ID ".$proy_id." PFEC : ".$pfec_id." - PTO P: ".$ptto_p." - PTO E: ".$ptto_e."<br>";
                $update_ptto_fase = array(
                  'pfec_ptto_fase_e' => $ptto_e,
                  'estado' => 2,
                  'fun_id' => $this->fun_id,
                );
                $this->db->where('pfec_id', $pfec_id);
                $this->db->update('_proyectofaseetapacomponente', $update_ptto_fase);

            if (!empty($_POST["fgp_id"]) && is_array($_POST["fgp_id"]) ) {
              foreach ( array_keys($_POST["fgp_id"]) as $como  ) {
              //  echo $_POST["monto"][$como]." - ".$_POST["monto_e"][$como]."<br>";
                $update_ptto = array(
                  'pfecg_ppto_total' => $_POST["monto"][$como],
                  'pfecg_ppto_ejecutado' => $_POST["monto_e"][$como],
                  'fun_id' => $this->fun_id,
                );
                $this->db->where('pfec_id', $pfec_id);
                $this->db->where('ptofecg_id', $_POST["fgp_id"][$como]);
                $this->db->update('ptto_fase_gestion', $update_ptto);
              }
            }

            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE EL PRESUPUESTO PROGRAMADO DE GESTIONES DE LA FASE ACTIVA');
            
            if($proyecto[0]['tp_id']==1){
              redirect(site_url("admin").'/proy/fase_etapa/'.$proy_id);
            }
            else{
              redirect(site_url("admin").'/proy/list_proy');
            }
            
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL GUARDAR EL PRESUPUESTO');
            redirect(site_url("admin").'/proy/newfase/'.$proy_id.'/'.$pfec_id.'/2');
          }
      
      }
      else{
        $this->session->set_flashdata('danger','ERROR AL GUARDAR EL PRESUPUESTO');
        redirect(site_url("admin").'/proy/newfase/'.$proy_id.'/'.$pfec_id.'/2');
      }
    }

  /*----------- MODIFICAR FASE ETAPA (2019) -----------*/  
    function modificar_fase($pfec_id){
      $data['menu']=$this->menu(2);
      $fase=$this->model_faseetapa->get_fase($pfec_id);
      if(count($fase)==1){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// Datos del proyecto
        $data['fase_proyecto'] = $this->model_faseetapa->fase_etapa($pfec_id,$fase[0]['proy_id']); //// Datos de la fase activa
        
        $data['fase']=$this->model_faseetapa->fases();
        $diferencia=($data['proyecto'][0]['fin']-$data['proyecto'][0]['inicio']);
        if($diferencia==0){
          $data['dif']='<div class="alert alert-info alert-block">FASE ACTUAL - ANUAL</div>';
        }
        elseif ($diferencia!=0) {
          $data['dif']='<div class="alert alert-info alert-block">FASE ACTUAL - PLURIANUAL</div>'; 
        }

        $data['nro_fg'] = $this->model_faseetapa->nro_fasegestion($pfec_id);
        $data['nro_fg_act'] = $this->model_faseetapa->nro_fasegestion_actual($pfec_id,$this->gestion); 

        $data['f_gest'] = $this->model_faseetapa->fase_gestion($pfec_id,$this->gestion);
        

        $this->load->view('admin/programacion/proy_anual/fase/form_fase_update', $data);
      }
      else{
        redirect('admin/dashboard');
      }

    }


  /*------------ ACTUALIZAR/MODIFICAR  FASE (2019) ------------*/
  function update_fase_etapa2(){
    if($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('pfec_id', 'Codigo fase ', 'required|trim');

        $pfec_id=$this->security->xss_clean($this->input->post('pfec_id'));
         if ($this->form_validation->run()){
          $fas_id=$this->security->xss_clean($this->input->post('fase'));
          $etapa_id=$this->security->xss_clean($this->input->post('etapas'));
          $ejec_id=$this->security->xss_clean($this->input->post('f_ejec'));
          $desc=$this->security->xss_clean($this->input->post('desc'));
          $f_inicio=$this->security->xss_clean($this->input->post('f_inicio'));
          $f_final=$this->security->xss_clean($this->input->post('f_final'));
          $monto=$this->security->xss_clean($this->input->post('monto_total'));

        //  $this->model_faseetapa->delete_fechas_faseetapa($pfec_id);

          $update_ptto_fase = array(  
            'pfecg_ppto_total' => 0,
            'estado' => 3);

          $this->db->where('pfec_id', $pfec_id);
          $this->db->update('ptto_fase_gestion', $update_ptto_fase);


          $fechas = $this->model_faseetapa->fechas_fase($pfec_id);
          $update_fase = array(  
            'pfec_fecha_inicio' => $fechas[0]['inicio'],
            'pfec_fecha_fin' => $fechas[0]['final']);

          $this->db->where('pfec_id', $pfec_id);
          $this->db->update('_proyectofaseetapacomponente', $update_fase);

          for($i=$fechas[0]['inicio'];$i<=$fechas[0]['final'];$i++){
            $data_to_store2 = array(
              'pfec_id' => $pfec_id,
              'g_id' => $i,
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('ptto_fase_gestion', $data_to_store2);
          }

          $this->session->set_flashdata('success','DATOS DE LAS FASE SE MODIFICARON CORRECTAMETE');
          redirect('admin/proy/fase_ptto/'.$pfec_id.'');

        }
        else{
          echo "Error";
        }
      }
    else{
      echo "error !!! ";
    }
  }
  
  function update_fase_etapa(){
     if($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('pfec_id', 'Codigo fase ', 'required|trim');

        $pfec_id=$this->security->xss_clean($this->input->post('pfec_id'));
        if ($this->form_validation->run()){
          $fas_id=$this->security->xss_clean($this->input->post('fase'));
          $etapa_id=$this->security->xss_clean($this->input->post('etapas'));
          $ejec_id=$this->security->xss_clean($this->input->post('f_ejec'));
          $desc=$this->security->xss_clean($this->input->post('desc'));
          $f_inicio=$this->security->xss_clean($this->input->post('f_inicio'));
          $f_final=$this->security->xss_clean($this->input->post('f_final'));
          $monto=$this->security->xss_clean($this->input->post('monto_total'));
          
          $fase_anterior=$this->model_faseetapa->get_fase($pfec_id);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase_anterior[0]['proy_id']);
          $vpoa=$this->model_proyecto->responsable_proy($fase_anterior[0]['proy_id'],2);

          /*--------- ACTUALIZANDO FASE ETAPA --------*/
          $query=$this->db->query('set datestyle to DMY');
          $update_fase = array( 
            'fas_id' => $fas_id,
            'eta_id' => $etapa_id,
            'pfec_ejecucion' => $ejec_id,
            'pfec_descripcion' => strtoupper($desc),
            'pfec_fecha_inicio_ddmmaaa' => $f_inicio,
            'pfec_fecha_fin_ddmmaaa' => $f_final,
            'pfec_fecha_registro' => date('d/m/Y h:i:s'),
            'pfec_ptto_fase' => $monto,
            'fun_id' => $this->fun_id,
            'estado' => 2);
            $this->db->where('pfec_id', $pfec_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase);
          /*------------------------------------------*/
            $fase_actual=$this->model_faseetapa->get_id_fase($proyecto[0]['proy_id']);
            $fechas = $this->model_faseetapa->fechas_fase($fase_actual[0]['id']);

            $update_fase2 = array(  
              'pfec_fecha_inicio' => $fechas[0]['inicio'],
              'pfec_fecha_fin' => $fechas[0]['final']
            );
            $this->db->where('pfec_id', $pfec_id);
            $this->db->update('_proyectofaseetapacomponente', $update_fase2);
            /*---------------------------------------------*/

            /*------------Actualiza ejecucion delegada a directa borrando los requerimientos -----*/
            if($fase_anterior[0]['pfec_ejecucion']!=$fase_actual[0]['pfec_ejecucion']){
              if($fase_anterior[0]['pfec_ejecucion']==2){
                $insumos=$this->model_componente->list_requerimientos_fase($pfec_id);
                foreach ($insumos as $row) {
                  $update_ins= array(
                      'fun_id' => $this->fun_id,
                      'aper_id' => 0,
                      'ins_estado' => 3
                    );
                    $this->db->where('ins_id', $row['ins_id']);
                    $this->db->update('insumos', $this->security->xss_clean($update_ins));
                    /*-----------------------------------------*/
                   
                    $update_insg= array(
                      'insg_estado' => 3
                    );
                    $this->db->where('ins_id', $row['ins_id']);
                    $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
                  }

                }
              
            }
            /*------------------------------------------------------------------------------------*/

            /*-------------------- cuando la fecha inicial se modifica antes fi2<fi1-----------------*/
            if($fechas[0]['inicio']<$fase_anterior[0]['pfec_fecha_inicio']){
              for($i=$fechas[0]['inicio'];$i<=$fase_anterior[0]['pfec_fecha_inicio'];$i++){
                 $data_to_store2 = array( ///// Tabla ptto_fase_gestion
                    'pfec_id' => $pfec_id,
                    'g_id' => $i,
                    'fun_id' => $this->fun_id,
                  );
                  $this->db->insert('ptto_fase_gestion', $data_to_store2); ///// inserta a ptto_fase_gestion
              }
            }
            /*-------------------- cuando la fecha final se modifica despues ff2>ff1-----------------*/
            if($fechas[0]['final']>$fase_anterior[0]['pfec_fecha_fin']){
                $gestion_final_anterior=$fase_anterior[0]['pfec_fecha_fin']+1;
                for($i=$gestion_final_anterior;$i<=$fechas[0]['final'];$i++){
                   $data_to_store2 = array( ///// Tabla ptto_fase_gestion
                      'pfec_id' => $pfec_id,
                      'g_id' => $i,
                      'fun_id' => $this->fun_id,
                    );
                    $this->db->insert('ptto_fase_gestion', $data_to_store2); ///// inserta a ptto_fase_gestion
                }
            }

            /*----------- cuando la fecha inicial se modifica despues fi2>fi1 --------------*/
            if($fechas[0]['inicio']>$fase_anterior[0]['pfec_fecha_inicio']){
                  $gestion_inicio_anterior=$fase_anterior[0]['pfec_fecha_inicio'];
                  for($i=$gestion_inicio_anterior;$i<$fechas[0]['inicio'];$i++){
                      /*-------- Eliminando gestiones de ptto fase ----------*/
                      $this->db->where('pfec_id', $pfec_id);
                      $this->db->where('g_id', $i);
                      $this->db->delete('ptto_fase_gestion');
                      /*------------------------------------------------------*/ 
                  }  
            }

            /*----------- cuando la fecha final se modifica antes ff2>ff1 --------------*/
            if($fase_anterior[0]['pfec_fecha_fin']>$fechas[0]['final']){
                  $gestion_final_nuevo=$fechas[0]['final']+1;
                  for($i=$gestion_final_nuevo;$i<=$fase_anterior[0]['pfec_fecha_fin'];$i++){
                      /*-------- Eliminando gestiones de ptto fase ----------*/
                      $this->db->where('pfec_id', $pfec_id);
                      $this->db->where('g_id', $i);
                      $this->db->delete('ptto_fase_gestion');
                      /*------------------------------------------------------*/ 
                  }  
            }

            if(($fechas[0]['inicio']==$fase_anterior[0]['pfec_fecha_inicio']) & ($fase_anterior[0]['pfec_fecha_fin']==$fechas[0]['final'])){
              if(count($this->model_faseetapa->ptto_fase($pfec_id))==0){
                /*---- Fase Etapa Gestion -----*/
                for($i=$fase_anterior[0]['pfec_fecha_inicio'];$i<=$fase_anterior[0]['pfec_fecha_fin'];$i++){
                  $data_to_store2 = array( ///// Tabla ptto_fase_gestion
                    'pfec_id' => $pfec_id,
                    'g_id' => $i,
                    'fun_id' => $this->fun_id,
                  );
                  $this->db->insert('ptto_fase_gestion', $data_to_store2);
                  $fecha=$fecha+1;
                }
                /*---------------------------*/
              }
            }

            /*------------------------ ELIMINAR COMPONENTE -------------*/
            if($fase_anterior[0]['fas_id']==$fase_actual[0]['fas_id']){
              if($fase_anterior[0]['fas_id']==1){
                if(count($this->model_componente->componentes_id($pfec_id,$proyecto[0]['tp_id']))==0){
                    $query=$this->db->query('set client_encoding= WIN1252;');
                    $data_to_store3 = array(
                      'pfec_id' => $pfec_id,
                      'com_componente' => 'PRE-INVERSIÓN',
                      'serv_id' => 1,
                      'resp_id' =>$vpoa[0]['fun_id'],
                      'uni_id' =>$proyecto[0]['dist_id'],
                      'fun_id' => $this->fun_id,
                    );
                    $this->db->insert('_componentes', $data_to_store3);
                }
              }
            }
            elseif($fase_anterior[0]['fas_id']!=$fase_actual[0]['fas_id']){

              if($fase_anterior[0]['fas_id']==1){
                if($this->delete_componente_proyecto($pfec_id,$proyecto)){

                }
              }
              elseif ($fase_anterior[0]['fas_id']==2) {
                if($this->delete_componente_proyecto($pfec_id,$proyecto)){
                  $query=$this->db->query('set client_encoding= WIN1252;');
                    $data_to_store3 = array(
                      'pfec_id' => $pfec_id,
                      'com_componente' => 'PRE-INVERSIÓN',
                      'serv_id' => 1,
                      'resp_id' =>$vpoa[0]['fun_id'],
                      'uni_id' =>$proyecto[0]['dist_id'],
                      'fun_id' => $this->fun_id,
                    );
                    $this->db->insert('_componentes', $data_to_store3);
                }
              }
            }

            $this->session->set_flashdata('success','DATOS DE LAS FASE SE MODIFICARON CORRECTAMETE');
            redirect('admin/proy/fase_ptto/'.$pfec_id.'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR DATOS DE LA FASE');
          redirect('admin/proy/update_f/'.$pfec_id.'');
        }
    }
  }

  /*-------------- Elimina Componentes ----------------*/
  public function delete_componente_proyecto($pfec_id,$proyecto){
    $componentes=$this->model_componente->componentes_id($pfec_id,$proyecto[0]['tp_id']);
    foreach($componentes as $rowc){
      $productos = $this->model_producto->list_prod($rowc['com_id']); // Lista de productos
      foreach($productos as $rowp){
        $insumos = $this->model_producto->insumo_producto($rowp['prod_id']); //// Insumo producto
        foreach ($insumos as $row) {
          /*------------ UPDATE REQUERIMIENTO -------*/
          $update_ins= array(
            'fun_id' => $this->fun_id,
            'aper_id' => 0,
            'ins_estado' => 3
          );
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->update('insumos', $this->security->xss_clean($update_ins));
          /*-----------------------------------------*/
         
          $update_insg= array(
            'insg_estado' => 3
          );
          $this->db->where('ins_id', $row['ins_id']);
          $this->db->update('insumo_gestion', $this->security->xss_clean($update_insg));
        }

        /*------------ UPDATE PRODUCTO -------*/
          $update_prod= array(
            'fun_id' => $this->fun_id,
            'estado' => 3
          );
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->update('_productos', $this->security->xss_clean($update_prod));
        /*-----------------------------------------*/
      }

      /*------------ UPDATE PRODUCTO -------*/
        $update_com= array(
          'fun_id' => $this->fun_id,
          'serv_id' => 0,
          'estado' => 3
        );
        $this->db->where('com_id', $rowc['com_id']);
        $this->db->update('_componentes', $this->security->xss_clean($update_com));
      /*-----------------------------------------*/
    }

    if(count($componentes)==0){
      return true;
    }
    else{
      return false;
    }
  }

  /*======================================= OBTIENE DATOS DE LA FASE ACTIVA ============================*/
  public function get_fase_activa(){
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $post = $this->input->post();
            $id_fase = $post['id_fase'];
            $id_proy = $post['id_proy'];
            $dato = $this->model_faseetapa->fase_etapa($id_fase,$id_proy);
                $result = array(
                    'descripcion' => $dato[0]['descripcion'],
                    'eficacia' => $dato[0]['pfec_eficacia'],
                    'financiera' => $dato[0]['pfec_eficiencia'],
                    'ejecucion' => $dato[0]['pfec_eficiencia_pe'],
                    'fisica' => $dato[0]['pfec_eficiencia_fi'],
                );
            echo json_encode($result);
        } else {
            show_404();
        }
    }
  /*=====================================================================================================*/

  /*============================================= ADICIONA INDICADOR====================================*/
      function add_indicador(){
        if ($this->input->is_ajax_request() && $this->input->post()) {
            $post = $this->input->post();
            $id_fase = $post['id_fase'];
            $eficacia = $post['eficacia'];
            $financiera = $post['financiera'];
            $ejecucion = $post['ejecucion'];
            $fisica = $post['fisica'];
            if($this->model_faseetapa->add_indicador_fase($id_fase,$eficacia,$financiera,$ejecucion,$fisica)){
                $result = array(
                    'respuesta' => 'true',
                );
            }else{
                $result = array(
                    'respuesta' => 'false',
                );
            }
            echo json_encode($result);
        } else {
            show_404();
        }
    }
    /*=====================================================================================================*/

   /*================================== VERIFICA LAS DEPENDENCIA DE LA FASE ============================-*/   
    public function verif_fase(){   
      if($this->input->is_ajax_request()){
        $post = $this->input->post();
        $id_f = $post['id_f'];

        $nro=$this->model_componente->componentes_nro($id_f);

         if($nro == 0){
         echo "true"; ///// La fase no tiene dependencias
         }
         else{
          echo "false";; //// La fase tiene dependencias
         } 
      }else{
              show_404();
      }
    }

    /*=================================== ELIMINA FASE ETAPA ==================================================*/
    public function delete_fase(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $pfec_id = $post['pfec_id'];

        $update_ptto = array(
                'estado' => '3',
                'fun_id' => $this->session->userdata("fun_id"),);
        $this->db->where('pfec_id', $pfec_id);
        $this->db->update('ptto_fase_gestion', $update_ptto);

        $update_fase = array(
                'pfec_estado' => '0',
                'estado' => '3',
                'fun_id' => $this->session->userdata("fun_id"),);
        $this->db->where('pfec_id', $pfec_id);
        $this->db->update('_proyectofaseetapacomponente', $update_fase);
        
        $sql = $this->db->get();
       
        if($this->db->query($sql)){
            echo $pfec_id;
        }else{
            echo false;
        }
      }else{
          show_404();
      }
    }

    /*-----  ASIGNAR PREUPUESTO A LA FASE ETAPA  -----*/
    function asignar_presupuesto($id_p){
      $data['menu']=$this->menu(2);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($id_p); ///// datos del proyecto

      if(count($data['proyecto'])!=0){
        $data['titulo_proy']=strtoupper($data['proyecto'][0]['tipo']);
        $data['fase'] = $this->model_faseetapa->get_id_fase($id_p); ///// datos fase encendida
        $data['ffi'] = $this->model_faseetapa->fuentefinanciamiento(); ///// fuente financiamiento
        $data['fof'] = $this->model_faseetapa->organismofinanciador(); ///// organismo financiador
        $data['techo']=$this->techo_add($id_p);
        
        $this->load->view('admin/programacion/proy_anual/fase/fase_asig_ptto', $data);
      }
      else{
        redirect('admin/dashboard');
      }
    }

    /*--------- TECHO PRESUPUESTARIO -----------*/
    public function techo_add($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $fgestion=$this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion);
      $ffofet=$this->model_faseetapa->fase_presupuesto_id($fgestion[0]['ptofecg_id']);
      $ffi = $this->model_faseetapa->fuentefinanciamiento(); ///// fuente financiamiento
      $fof = $this->model_faseetapa->organismofinanciador(); ///// organismo financiador

        $tabla ='';
        $tabla.='
              <form action="'.site_url('').'/proy/add_ptto_techo" id="form_techo" name="form_techo" class="smart-form" method="post">
                <input type="hidden" id="nffofet" value="'.count($ffofet).'"/>
                <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'"/>
                <input type="hidden" name="ptofecg_id" id="ptofecg_id" value="'.$fgestion[0]['ptofecg_id'].'"/>
                <input type="hidden" name="ptto_gestion" id="ptto_gestion" value="'.$fgestion[0]['pfecg_ppto_total'].'"/>
                <input type="hidden" id="contador-filas" value="0" />
                <table class="table table-bordered" id="tabla" style="width:75%;" aling="center">
                  <thead>
                    <tr>
                      <th style="width:1%;">#</th>
                      <th style="width:38%;">FUENTE DE FINANCIAMIENTOfdsfs</th>
                      <th style="width:38%;">ORGANISMO FINANCIADOR</th>
                      <th style="width:20%;">IMPORTE</th>
                      <th style="width:3%;"></th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                $tabla .='</tbody>
                </table><br>
                <table class="table table-bordered" style="width:75%;" aling="center">
                  <tr>
                    <td style="width:77%;"><font color="blue">PRESUPUESTO ASIGNADO '.$this->gestion.'</font></td>
                    <td style="width:20%;"><input type="text" name="ptto" id="ptto" value="'.$fgestion[0]['pfecg_ppto_total'].'" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                  <tr>
                    <td style="width:77%;"><font color="blue">TOTAL</font></td>
                    <td style="width:20%;"><input type="text" name="total" id="total" value="0" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                  <tr>
                    <td style="width:77%;"><font color="blue">SALDO</font></td>
                    <td style="width:20%;"><input type="text" name="saldo" id="saldo" value="'.($fgestion[0]['pfecg_ppto_total']).'" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                </table>
                  <footer>
                    <div id="but" style="display:none;">
                      <button type="button" name="mod_tech" id="mod_tech" class="btn btn-primary">Guardar Techo Presupuestario</button>
                    </div>
                  </footer>
                </form>';

                ?>
                <script type="text/javascript">
                  function suma_monto_techo(trs){
                    ptotal = parseFloat($('[name="ptto_gestion"]').val());
                    nro = parseFloat($('[id="nffofet"]').val());
                    var suma=0;
                    for (var i = 1; i <= trs; i++) {
                      suma=parseFloat(suma)+parseFloat($('[id="impo'+i+'"]').val());
                    }

                    $('[name="total"]').val((suma).toFixed(2));
                    tot = parseFloat($('[name="total"]').val());

                    $('[name="saldo"]').val((parseFloat(ptotal)-parseFloat(tot)).toFixed(2));
                    saldo = parseFloat($('[name="saldo"]').val());

                    /// temporal
                    if(tot==0){
                      $('#but').slideDown();
                    }
                    else{
                      $('#but').slideUp();
                    }
                    /*if(isNaN(tot) || tot=='' || tot<0){
                      $('#but').slideUp();
                    }
                    else{
                      if(isNaN(saldo) || saldo<0){
                        $('#but').slideUp();
                      }
                      else{
                        $('#but').slideDown();
                      }
                      
                    }*/
                  }
                </script>
                <?php  
     
      return $tabla;
    }

     /*----------  TECHO PRESUPUESTARIO UPDATE -------*/
    public function techo_update($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      $fase = $this->model_faseetapa->get_id_fase($proy_id);
      $fgestion=$this->model_faseetapa->fase_gestion($fase[0]['id'],$this->gestion);
      $ffofet=$this->model_faseetapa->fase_presupuesto_id($fgestion[0]['ptofecg_id']);
      $ffi = $this->model_faseetapa->fuentefinanciamiento(); ///// fuente financiamiento
      $fof = $this->model_faseetapa->organismofinanciador(); ///// organismo financiador

      $tabla ='';
      if(count($ffofet)!=0){
        $tabla.='
              <form action="'.site_url('').'/proy/add_ptto_techo" id="form_techo" name="form_techo" class="smart-form" method="post">
                <input type="hidden" id="nffofet" value="'.count($ffofet).'"/>
                <input type="hidden" name="proy_id" id="proy_id" value="'.$proy_id.'"/>
                <input type="hidden" name="ptofecg_id" id="ptofecg_id" value="'.$fgestion[0]['ptofecg_id'].'"/>
                <input type="hidden" name="ptto_gestion" id="ptto_gestion" value="'.$fgestion[0]['pfecg_ppto_total'].'"/>
                <input type="hidden" id="contador-filas" value="'.count($ffofet).'" />
                <table class="table table-bordered" id="tabla" style="width:60%;">
                  <thead>
                    <tr>
                      <th style="width:1%;">#</th>
                      <th style="width:38%;">FUENTE DE FINANCIAMIENTO</th>
                      <th style="width:38%;">ORGANISMO FINANCIADOR</th>
                      <th style="width:20%;">IMPORTE</th>
                      <th style="width:3%;"></th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  $suma=0;
                  foreach($ffofet as $rowf){
                    $ff_ins = $this->model_faseetapa->fuente_insumo($rowf['ffofet_id']); 
                    $suma=$suma+$rowf['ffofet_monto'];
                    $nro++;
                    $tabla .='<tr>';
                      $tabla .='<td><input type="hidden" name="ffofet_id[]" value="'.$rowf['ffofet_id'].'"/>'.$nro.'</td>';
                      $tabla .='<td>
                        <select class="form-control" name="ffin[]" id="fi'.$nro.'" title="Seleccione fuente de Financiamiento" required >
                          <option value="">Seleccione Fuente financiamiento </option>';
                          foreach($ffi as $row){ 
                            if($rowf['ff_id']==$row['ff_id']){
                              $tabla .='<option value="'.$row['ff_id'].'" selected>'.$row['ff_codigo'].' - '.$row['ff_descripcion'].'</option>';  
                            }
                            else{
                              $tabla .='<option value="'.$row['ff_id'].'">'.$row['ff_codigo'].' - '.$row['ff_descripcion'].'</option>';  
                            }
                          }
                        $tabla .='    
                            </select>
                          </td>';
                      $tabla .='<td>
                        <select class="form-control" name="ofin[]" id="ofi'.$nro.'" title="Seleccione organismo Financiador" required >
                          <option value="">Seleccione Organismo Financiador </option>';
                          foreach($fof as $row){
                            if($rowf['of_id']==$row['of_id']){
                              $tabla .='<option value="'.$row['of_id'].'" selected>'.$row['of_codigo'].' - '.$row['of_descripcion'].'</option>';
                            }
                            else{
                              $tabla .='<option value="'.$row['of_id'].'">'.$row['of_codigo'].' - '.$row['of_descripcion'].'</option>';
                            }
                          }
                        $tabla .='    
                            </select>
                          </td>';
                        $tabla .='<td><input type="text" name="importe[]" id="impo'.$nro.'" value="'.$rowf['ffofet_monto'].'"class="form-control" onkeyup="suma_monto_techo();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"/></td>';
                        $tabla .='<td align=center>';
                        if($this->session->userdata('rol_id')==1){
                          if($ff_ins==0){
                            $tabla .= '<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR REQUERIMIENTO"  name="'.$rowf['ffofet_id'].'" id="'.$proy_id.'">
                                        <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                                      </a>';
                          }
                        }
                        $tabla .='</td>';
                    $tabla .='</tr>';
                  }
                $tabla .='</tbody>
                </table><br>
                <table class="table table-bordered" style="width:60%;">
                  <tr>
                    <td style="width:77%;"><font color="blue">PRESUPUESTO ASIGNADO '.$this->gestion.'</font></td>
                    <td style="width:20%;"><input type="text" name="ptto" id="ptto" value="'.$fgestion[0]['pfecg_ppto_total'].'" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                  <tr>
                    <td style="width:77%;"><font color="blue">TOTAL</font></td>
                    <td style="width:20%;"><input type="text" name="total" id="total" value="'.$suma.'" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                  <tr>
                    <td style="width:77%;"><font color="blue">SALDO</font></td>
                    <td style="width:20%;"><input type="text" name="saldo" id="saldo" value="'.($fgestion[0]['pfecg_ppto_total']-$suma).'" class="form-control" disabled/></td>
                    <td style="width:3%;"></td>
                  </tr>
                </table>
                  <footer>
                    <div id="but">
                      <button type="button" name="mod_tech" id="mod_tech" class="btn btn-primary">Modificar Techo Presupuestario</button>
                    </div>
                  </footer>
                </form>';

                ?>
                <script type="text/javascript">
                  function suma_monto_techo(){
                    ptotal = parseFloat($('[name="ptto_gestion"]').val());
                    nro = parseFloat($('[id="nffofet"]').val());
                    new_nro = parseFloat($('[id="contador-filas"]').val());
                    var suma=0;
                  //  alert(nro+new_nro)
                    for (var i = 1; i <= new_nro; i++) {
                      suma=parseFloat(suma)+parseFloat($('[id="impo'+i+'"]').val());
                    }

                    $('[name="total"]').val((suma).toFixed(2));
                    tot = parseFloat($('[name="total"]').val());
                 
                    $('[name="saldo"]').val((parseFloat(ptotal)-parseFloat(tot)).toFixed(2));
                    saldo = parseFloat($('[name="saldo"]').val());

                    /*if(isNaN(tot) || tot=='' || tot<0){
                      $('#but').slideUp();
                    }
                    else{
                      if(isNaN(saldo) || saldo<0){
                        $('#but').slideUp();
                      }
                      else{
                        $('#but').slideDown();
                      } 
                    }*/
                  }
                </script>
                <?php  
      }

      return $tabla;
    }
    /*------------------- VALIDA TECHO PRESUPUESTARIO -----------------*/
    function validar_techo_ptto(){
      if($this->input->post()){
        $proy_id=$this->security->xss_clean($this->input->post('proy_id'));
        $ptofecg_id=$this->security->xss_clean($this->input->post('ptofecg_id'));

        $ffofet=$this->model_faseetapa->fase_presupuesto_id($ptofecg_id);

        if (!empty($_POST["ffofet_id"]) && is_array($_POST["ffofet_id"]) ) {
            foreach ( array_keys($_POST["ffofet_id"]) as $como  ) {
              $get_ffofet=$this->model_faseetapa->get_techo_id($_POST["ffofet_id"][$como]);
              if(count($get_ffofet)!=0){
                $update_ffofet = array(
                  'ff_id' => $_POST["ffin"][$como],
                  'of_id' => $_POST["ofin"][$como],
                  'ffofet_monto' => $_POST["importe"][$como],
                );
                $this->db->where('ffofet_id', $_POST["ffofet_id"][$como]);
                $this->db->update('_ffofet', $update_ffofet);
              }
              else{
                $data = array(
                  'ptofecg_id' => $ptofecg_id,
                  'ff_id' => $_POST["ffin"][$como],
                  'of_id' => $_POST["ofin"][$como],
                  'ffofet_monto' => $_POST["importe"][$como],
                );
                $this->db->insert('_ffofet',$data);
                $ffofet_id=$this->db->insert_id();
              }
          
            }
          }

        //  $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE EL TECHO PRESUPUESTARIO DE LA OPERACIÓN - PROYECTO DE INVERSIÓN');
          redirect(site_url("admin").'/proy/list_proy_fin');
      //  echo 'true';
      }else{
            show_404();
      }
    }


  /*========================================= TECHO PRESUPUESTARIO DE LA FASE ==============================================================*/
  function ver_techo_ptto($id_p,$id_fg){
    $data['menu']=$this->menu(2);
    $data['proyecto'] = $this->model_proyecto->get_id_proyecto($id_p); ///// datos del proyecto
    if(count($data['proyecto'])!=0){
      $data['titulo_proy']=strtoupper($data['proyecto'][0]['tipo']);
      $data['fase'] = $this->model_faseetapa->get_id_fase($id_p); ///// datos fase encendida
      $data['techo']=$this->techo_update($id_p);
      $data['ffi'] = $this->model_faseetapa->fuentefinanciamiento(); ///// fuente financiamiento
      $data['fof'] = $this->model_faseetapa->organismofinanciador(); ///// organismo financiador

      $this->load->view('admin/programacion/proy_anual/fase/fase_edit_ptto', $data); 
    }
    else{
      redirect('admin/dashboard');
    }
  }
  /*===============================================================================================================================================*/
  /*===================================== OBTIENE LOS DATOS DEL TECHO PRESUPUESTO =======================================*/
    public function get_techo_ptto(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $id_ffofet = $post['id_ffofet']; /// id id_ffofet
          $suma_techo = $post['suma_techo']; /// ptto
          $id_ffofet = $this->security->xss_clean($id_ffofet);
          $suma_techo = $this->security->xss_clean($suma_techo);
          $dato_ffofet = $this->model_faseetapa->get_techo_id($id_ffofet);
          //caso para modificar el codigo de proyecto y actividades
          foreach($dato_ffofet as $row){
              $result = array(
                  'ffofet_id' => $row['ffofet_id'],
                  "ptofecg_id" =>$row['ptofecg_id'],
                  "ff_id" =>$row['ff_id'],
                  "of_id" =>$row['of_id'],
                  "et_id" =>$row['et_id'],
                  "monto" =>$suma_techo-$row['ffofet_monto'],
                  "ffofet_monto" =>$row['ffofet_monto']
              );
          }
          echo json_encode($result);
      }else{
          show_404();
      }
    }
  /*==================================================================================================================*/

    /*------ OBTIENE FASE ETAPA COMPONENTE ------*/
    public function obtiene_faseetapa(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos Proyecto
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// Datos de la Fase
          
          if(count($proyecto)!=0){
            $result = array(
              'respuesta' => 'correcto',
              'proyecto' => $proyecto,
              'fase' => $fase
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

    /*------- VALIDA PRESUPUESTO - OPERACION -------*/
    public function valida_presupuesto(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $ptto = $this->security->xss_clean($post['ppto']);
          
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos Proyecto
          $fase=$this->model_faseetapa->get_id_fase($proy_id); /// Datos de Fase

          $query=$this->db->query('set datestyle to DMY');
          /*--------- FASEETAPACOMPONENTE --------*/
          $update_fe = array(
            'pfec_ptto_fase' => $ptto,
            'fun_id' => $this->session->userdata("fun_id"),
            'pfec_fecha_registro' => date('d/m/Y h:i:s'),
            'estado' => 2
          );
          $this->db->where('pfec_id', $fase[0]['id']);
          $this->db->update('_proyectofaseetapacomponente', $update_fe);
          /*--------------------------------------*/
          /*--------- FASE ETAPA GESTION --------*/
          $update_fe = array(
            'pfecg_ppto_total' => $ptto,
            'fun_id' => $this->session->userdata("fun_id"),
            'estado' => 2
          );
          $this->db->where('pfec_id', $fase[0]['id']);
          $this->db->where('g_id', $this->gestion);
          $this->db->update('ptto_fase_gestion', $update_fe);
          /*--------------------------------------*/

          $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE MONTO PRESUPUESTO, APERTURA PROGRAMATICA : '.$proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad']);
          redirect(site_url("admin").'/proy/list_proy');

      } else {
          show_404();
      }
    }
   /*--------------------- ACTUALIZA LAS FUENTES DE FINANCIAMENTO --------------------*/
    public function valida_techo_ptto(){   
      $fase = $this->model_faseetapa->fase_etapa($this->input->post('id_f'),$this->input->post('id_p'));
      $proyecto = $this->model_proyecto->get_id_proyecto($this->input->post('id_p'));
      
      $lista_fases_gestion=$this->model_faseetapa->list_fases_gestiones($this->input->post('id_f'));

      foreach ($lista_fases_gestion as $row){
        //  echo "ptofecg_id : " .$row['ptofecg_id'].'-- Nro. '.$this->model_faseetapa->nro_fuentes($row['ptofecg_id'])."<br>";

        if($row['g_id']==$this->session->userdata("gestion")){$ffofet_id=$row['ptofecg_id'];}
        if($this->model_faseetapa->nro_fuentes($row['ptofecg_id'])==0){
            if ( !empty($_POST["ffofet_id"]) && is_array($_POST["ffofet_id"]) ) {
              foreach ( array_keys($_POST["ffofet_id"]) as $como){
                  $monto_ff=0;
                  if($row['g_id']==$this->session->userdata("gestion")){$monto_ff=$_POST["f_monto"];}
                  $data = array(
                      'ptofecg_id' => $row['ptofecg_id'],
                      'ff_id' => $_POST["ff"][$como],
                      'of_id' =>$_POST["of"][$como],
                      'ffofet_monto' =>$monto_ff,
                  );
                  $this->db->insert('_ffofet',$data);

              //  echo $_POST["ffofet_id"][$como].'--'.$monto_ff.'<br>';
              }
           } 
        }
      }

      echo '<script> alert("SE ACTUALIZO CORRECTAMENTE LA INFORMACION ")</script>';
      redirect(site_url("admin") . '/proy/ver_techo_ptto/'.$this->input->post('id_p').'/'.$ffofet_id.'/true');
      
    }
   /*----------------------------------------------------------------------------------*/

  /*===================================== UPDATE LOS DATOS DEL TECHO PRESUPUESTO =======================================*/
      function update_techo_ptto(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $this->form_validation->set_rules('ffofet_id', 'id de la meta', 'required|trim');
            //=========================== mensajes =========================================
            $this->form_validation->set_message('required', 'El campo es es obligatorio');
            /*if ($this->form_validation->run() ) {*/
                $post = $this->input->post();
                $proy_id = $post['proy_id'];
                $fase_id = $post['fase_id'];
                $ffofet_id = $post['ffofet_id'];
                $ff_id = $post['ff_id'];
                $of_id = $post['of_id'];
                $ffofet_monto = $post['ffofet_monto'];
                //================ evitar enviar codigo malicioso ==========
                $proy_id= $this->security->xss_clean($proy_id);
                $fase_id= $this->security->xss_clean($fase_id);
                $ffofet_id= $this->security->xss_clean($ffofet_id);
                $ff_id= $this->security->xss_clean($ff_id);
                $of_id= $this->security->xss_clean($of_id);
                $ffofet_monto= $this->security->xss_clean($ffofet_monto);
                
                $update_techo = array(
                        'ff_id' => $ff_id,
                        'of_id' => $of_id,
                        'ffofet_monto' => $ffofet_monto,
                        );
                $this->db->where('ffofet_id', $ffofet_id);
                $this->db->update('_ffofet', $update_techo);   

                echo 'true';   
                
        }else{
            show_404();
        }
    }

    /*--------- ELIMINA ASIGNACION PRESUPUESTARIA ----------*/
    function delete_asignacion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();

          $ffofet_id = $this->security->xss_clean($post['ffofet_id']); /// ffofet_id

          $query=$this->db->query('set datestyle to DMY');
          $this->db->where('ffofet_id', $ffofet_id);
          $this->db->delete('_ffofet'); 

          if(count($this->model_faseetapa->get_techo_id($ffofet_id))==0){
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

    /*------------  Funcion para verificar fechas ---------------------*/
    public function verif_fecha($fecha){
        $fecha = $fecha;
        $valores = explode('/', $fecha);

        if(count($valores)==3){
          if(checkdate($valores[1],$valores[0],$valores[2])){
             return 'true';
          }
          else{
              return 'false';
          }
        }
        else{
            return 'false';
        }
    }

    /*------------------------------------- MENU -----------------------------------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++){
          $subenlaces[$enlaces[$i]['o_child']]=$this->menu_modelo->get_Enlaces($enlaces[$i]['o_child'], $this->session->userdata('user_name'));
        }

        $tabla ='';
        for($i=0;$i<count($enlaces);$i++){
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
    /*=============================================================================================*/
}