<?php
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_TEMP_DIR", "/tmp");
class Cestructura_organizacional extends CI_Controller {
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
                $this->load->model('mestrategico/model_objetivoregion');
                $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
                $this->load->library("security");
                $this->fun_id = $this->session->userData('fun_id');
                $this->gestion = $this->session->userData('gestion');
                $this->adm = $this->session->userData('adm');
                $this->dist = $this->session->userData('dist');
                $this->rol = $this->session->userData('rol_id');
                $this->dist_tp = $this->session->userData('dist_tp');
            }
            else{
                redirect('admin/dashboard');
            }
        }
        else{
                redirect('/','refresh');
        }
    }

    /*------------- Tipo de Responsable -----------------*/
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

    /*----------- COMBO ACTIVIDADES POR UNIDADES EJECUTORAS --------*/
    public function combo_act($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'actividad':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('select *
                              from unidad_actividad ua
                              Inner Join uni_gestion as ug On ug.act_id=ua.act_id
                              where ua.dist_id='.$id_pais.' and ua.act_estado!=3
                              order by ua.te_id, ua.act_cod asc');
          $salida.= "<option value=''>Seleccione Unidad / Centro</option>";
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[2].".-".$sql_p[3]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
    }

    /*---------- Lista - Unidad Organizacional ----------*/
    public function list_estructura(){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['list_dep']=$this->model_proyecto->list_departamentos();
      $data['lista_establecimiento'] = $this->model_estructura_org->list_tp_establecimiento(); /// Tipo de Establecimiento
      $data['unidad_ejec'] = $this->model_estructura_org->list_unidades(2); /// Unidades Ejecutoras
    //  $data['programas'] = $this->model_proyecto->list_prog($this->gestion); ///// lista aperturas padres
      $data['actividades']=$this->list_actividades(1);
      
   //   $data['sub_actividades']=$this->list_sub_actividades();
      $data['servicios']=$this->list_sub_actividades(1);

      $this->load->view('admin/mantenimiento/unidad_organizacional/vlist_unidad_organizacional', $data);
    }

    /*--------- Valida UO Actividad ----------*/
    public function valida_actividad(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $descripcion = $this->security->xss_clean($post['descripcion']); /// Descripcion Actividad 
          $ue_id = $this->security->xss_clean($post['ue_id']); //// Unidad Ejecutora
          $codigo = $this->security->xss_clean($post['codigo']); //// Codigo
          $te_id = $this->security->xss_clean($post['te_id']); //// tp Establecimiento

          /*--------- UNIDAD ACTIVIDAD ----------*/
          $data = array(
            'dist_id' => $ue_id,
            'act_cod' => $codigo,
            'act_descripcion' => strtoupper($descripcion), 
            'act_estado' => 1,
            'fun_id' => $this->fun_id,
            'te_id' => $te_id,
          );
          $this->db->insert('unidad_actividad',$data);
          $act_id=$this->db->insert_id();
          /*------------------------------------*/

          $act=$this->model_estructura_org->get_actividad($act_id);
          if($te_id==21){
            $act=$this->model_estructura_org->datos_unidad_organizacional($act_id);
          }


          if(count($act)!=0){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA ACTIVIDAD');
            redirect(site_url("").'/estructura_org');
          }
          else{
            $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DE LA ACTIVIDAD');
            redirect(site_url("").'/estructura_org');
          }

      } else {
          show_404();
      }
    }

    public function valida_actividad_anterior(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['codigo']); /// Codigo
          $descripcion = $this->security->xss_clean($post['descripcion']); /// Descripcion Actividad 
          $aper_id = $this->security->xss_clean($post['aper_id']); /// Apertura
          $ue_id = $this->security->xss_clean($post['ue_id']); //// Unidad Ejecutora

          /*--------- UNIDAD ACTIVIDAD ----------*/
          $data = array(
            'dist_id' => $ue_id,
            'act_cod' => $codigo,
            'act_descripcion' => strtoupper($descripcion), 
            'act_estado' => 1,
            'fun_id' => $this->fun_id,
          );
          $this->db->insert('unidad_actividad',$data);
          $act_id=$this->db->insert_id();
          /*------------------------------------*/

          /*--------- ACTIVIDAD APERTURA GESTION ----------*/
          $data = array(
            'act_id' => $act_id,
            'aper_id' => $aper_id,
            'g_id' => $this->gestion,
          );
          $this->db->insert('actividad_apertura_gestion',$data);
          $aa_id=$this->db->insert_id();
          /*------------------------------------*/


          if(count($this->model_estructura_org->get_actividad($act_id))!=0){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA ACTIVIDAD');
            redirect(site_url("").'/estructura_org');
          }
          else{
            $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DE LA ACTIVIDAD');
            redirect(site_url("").'/estructura_org');
          }

      } else {
          show_404();
      }
    }


    /*---- Valida Update  SubActividad ----*/
    public function valida_updateactividad(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cod = $this->security->xss_clean($post['scod']); /// Codigo
          $sdesc = $this->security->xss_clean($post['sdesc']); //// Descripcion
          $tp = $this->security->xss_clean($post['stp']); //// tp
          if(isset($cod) & isset($sdesc) & isset($tp)){
            /*--------- UNIDAD SUBACTIVIDAD ----------*/
            $data = array(
              'serv_cod' => $cod,
              'serv_descripcion' => strtoupper($sdesc), 
              'serv_tp' => $tp,
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('servicios_actividad',$data);
            $serv_id=$this->db->insert_id();
            /*------------------------------------*/

            if(count($this->model_estructura_org->get_servicio_actividad_id($serv_id))!=0){
              $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA SUB-ACTIVIDAD');
              redirect(site_url("").'/estructura_org#tabs-b');
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL INGRESAR DATOS DE SUBACTIVIDAD');
              redirect(site_url("").'/estructura_org#tabs-b');
            }
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL INGRESAR DATOS DE SUBACTIVIDAD');
            redirect(site_url("").'/estructura_org#tabs-b');
          }

      } else {
          show_404();
      }
    }

    /*------------------------- Valida Update UO Actividad --------------------------*/
    public function valida_update_actividad(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $act_id = $this->security->xss_clean($post['act_id']); /// act id
          $descripcion = $this->security->xss_clean($post['desc']); /// Descripcion Actividad 
          $ue_id = $this->security->xss_clean($post['ue']); //// Unidad Ejecutora
          $codigo = $this->security->xss_clean($post['cod']); //// Codigo
          $te_id = $this->security->xss_clean($post['mte_id']); //// Tipo est
            /*------------- Update Unidad Actividad ------------*/
            $update_prod = array(
            'dist_id' => $ue_id,
            'act_cod' => $codigo,
            'act_descripcion' => $descripcion,
            'act_estado' => 2,
            'te_id' => $te_id,
            'fun_id' => $this->fun_id
            );
            $this->db->where('act_id', $act_id);
            $this->db->update('unidad_actividad', $update_prod);
            /*--------------------------------------------------*/

            $act=$this->model_estructura_org->get_actividad($act_id);
            if($te_id==21){
              $act=$this->model_estructura_org->datos_unidad_organizacional($act_id);
            }
            
            if($act[0]['act_estado']==2){
                $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA ACTIVIDAD');
                redirect(site_url("").'/estructura_org');
              }
              else{
                $this->session->set_flashdata('danger','ERROR AL MODIFICAR LA ACTIVIDAD');
                redirect(site_url("").'/estructura_org');
              }

      } else {
          show_404();
      }
    }

    /*------------------------- Valida Update UO Sub Actividad --------------------------*/
    public function valida_update_sub_actividad(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $serv_id = $this->security->xss_clean($post['serv_id']); /// serv_id
          $codigo = $this->security->xss_clean($post['sub_cod']); /// Codigo
          $descripcion = $this->security->xss_clean($post['sub_desc']); //// Descripcion
          $tipo = $this->security->xss_clean($post['tp']); //// Tipo
          
            /*------------- Update Unidad Sub Actividad ------------*/
            $update_sact = array(
            'serv_cod' => $codigo,
            'serv_descripcion' => $descripcion,
            'serv_tp' => $tipo,
            'serv_estado' => 2,
            'fun_id' => $this->fun_id
            );
            $this->db->where('serv_id', $serv_id);
            $this->db->update('servicios_actividad', $update_sact);
            /*--------------------------------------------------*/

            $sact=$this->model_estructura_org->get_servicio_actividad_id($serv_id);
            if($sact[0]['serv_estado']==2){
                $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA SUB-ACTIVIDAD');
                redirect(site_url("").'/estructura_org#tabs-b');
              }
              else{
                $this->session->set_flashdata('danger','ERROR AL MODIFICAR LA SUB-ACTIVIDAD');
                redirect(site_url("").'/estructura_org#tabs-b');
              }

      } else {
          show_404();
      }
    }

    /*------------- LISTA DE ACTIVIDADES --------------*/
    public function list_actividades($tp){
        $departamento=$this->model_proyecto->list_departamentos();
        $tabla='';
        $nro_dep=0;
        foreach($departamento  as $rowd){
          if($rowd['dep_id']!=0){
            $dist=$this->model_estructura_org->list_unidades_adm_ue(1,$rowd['dep_id']);
            $nro_dep++;
            $tabla.=
            '<tr bgcolor="#d6f5d6" class="modo1">
              <td>'.$nro_dep.'</td>
              <td>'.strtoupper($rowd['dep_departamento']).'</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>';
              if($tp==1){
                $tabla.='
                <td></td>
                <td></td>
                <td></td>';
              }
              $tabla.='
            </tr>';
            $nro_dist=0;
            foreach($dist as $rowds){
              $ue=$this->model_estructura_org->list_unidades_adm_ue(2,$rowds['dep_id']);
              $nro_dist++;
              $tabla.=
              '<tr bgcolor="#ecf7ec" class="modo1">
                <td></td>
                <td>'.strtoupper($rowd['dep_departamento']).'</td>
                <td><b>'.$rowds['dist_cod'].' - '.strtoupper($rowds['dist_distrital']).'</b></td>
                <td></td>
                <td></td>
                <td></td>';
                if($tp==1){
                  $tabla.='
                  <td></td>
                  <td></td>
                  <td></td>';
                }
              $tabla.='
              </tr>';
              $nro_ue=0;
              foreach($ue  as $rowue){
                $ue=$this->model_estructura_org->list_actividades($rowue['dist_id']);
                if(count($ue)!=0){
                  $nro_ue++;
                    $tabla.=
                    '<tr bgcolor="#f7f9f7" class="modo1">
                      <td></td>
                      <td>'.strtoupper($rowd['dep_departamento']).'</td>
                      <td></td>
                      <td></td>
                      <td><b>'.$rowue['dist_cod'].' - '.strtoupper($rowue['dist_distrital']).'</b></td>
                      <td></td>';
                      if($tp==1){
                        $tabla.='
                        <td></td>
                        <td></td>
                        <td></td>';
                      }
                      $tabla.='
                    </tr>';
                    $nro_act=0;
                    foreach($ue  as $rowa){
                      $verif=$this->model_estructura_org->verif_uni_gestion($rowa['act_id']);
                      $nro_act++;
                      $tabla.=
                      '<tr class="modo1">
                        <td></td>
                        <td>'.strtoupper($rowd['dep_departamento']).'</td>
                        <td>'.strtoupper($rowue['dist_distrital']).'</td>
                        <td></td>
                        <td title='.$rowa['act_id'].'>'.strtoupper($rowa['act_descripcion']).' - '.$rowa['abrev'].'</td>
                        <td>'.$rowa['tipo'].' - '.$rowa['establecimiento'].'</td>';
                        if($tp==1){
                          $tabla.='
                          <td align="center"><a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn-default mod_ff" name="'.$rowa['act_id'].'" id="'.$rowa['te_id'].'" title="MODIFICAR ACTIVIDAD" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>
                          <td align="center">
                            <div class="checkbox">
                              <label>';
                              if(count($verif)!=0){
                                $tabla.='<input type="checkbox"  onclick="scheck'.$nro_act.'(this.checked,'.$rowa['act_id'].');" title="UNIDAD ACTIVA" checked>';
                              }
                              else{
                                $tabla.='<input type="checkbox"  onclick="scheck'.$nro_act.'(this.checked,'.$rowa['act_id'].');" title="UNIDAD INACTIVA">';
                              }
                              $tabla.='
                              </label>
                            </div>
                          </td>
                          <td align="center"><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn-default del_ff" title="ELIMINAR ACTIVIDAD"  name="'.$rowa['act_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a></td>';
                        }
                        $tabla.='
                      </tr>';
                      ?>
                      <script>
                        function scheck<?php echo $nro_act;?>(estaChequeado,id) {
                          valor=0;
                          titulo='DESACTIVAR UNIDAD / ESTABLECIMIENTO';
                          if (estaChequeado == true) {
                            valor=1;
                            titulo='ACTIVAR UNIDAD / ESTABLECIMIENTO';
                          }

                          var OK = confirm(titulo);
                            if (OK) {
                                var url = "<?php echo site_url().'/mantenimiento/cestructura_organizacional/estado_unidad'?>";
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    data:{id:id,estado:valor},
                                    success: function (data) {
                                        window.location.reload(true);
                                    }
                                });
                            }
                        }
                      </script>
                    <?php
                    }
                  }
                }
            }
          }
        }

        return $tabla;
    }

    /*--- ACTIVAR, DESACTIVAR UNIDAD/ESTABLECIMIENTO -----*/
    function estado_unidad(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('id', 'id unidad', 'required|trim');
          $this->form_validation->set_rules('estado', 'estado', 'required|trim');
          
          $post = $this->input->post();
          $id= $this->security->xss_clean($post['id']);
          $estado_activo= $this->security->xss_clean($post['estado']);
         
          if($estado_activo==1){ /// Activar unidad a la gestion
              $data_to_store = array( 
              'act_id' => $id,
              'g_id' => $this->gestion,
            );
            $this->db->insert('uni_gestion', $data_to_store);
          }
          else{ /// Desactivar unidad a la gestion
            $this->db->where('act_id', $id);
            $this->db->where('g_id', $this->gestion);
            $this->db->delete('uni_gestion');
          }
    
      }else{
          show_404();
      }
    }

    /*------------- LISTA DE SUB ACTIVIDADES --------------*/
    public function list_sub_actividades($tp_vista){
      $tabla='';
      $servicios = $this->model_estructura_org->list_servicios(); /// Lista Servicios
      $nro=0;
      foreach($servicios  as $row){
          $tp='ADMINISTRACI&Oacute;N REGIONAL';
          if($row['serv_tp']==1){
            $tp='ADMINISTRACI&Oacute;N OFICINA NACIONAL';
          }
          $nro++;
          $tabla.='
          <tr style="height:50px;" class="modo1">
              <td>'.$nro.' ('.$row['serv_id'].')</td>
              <td>'.$row['serv_cod'].'</td>
              <td>'.strtoupper($row['serv_descripcion']).'</td>
              <td align="center">'.$tp.'</td>';
              if($tp_vista==1){
                $tabla.='
                <td align="center">
                  <div class="checkbox">
                    <label>';
                    if($row['activo']==1){
                      $tabla.='<input type="checkbox"  onclick="scheck_serv'.$nro.'(this.checked,'.$row['serv_id'].');" title="SERVICIO ACTIV0" checked>';
                    }
                    else{
                      $tabla.='<input type="checkbox"  onclick="scheck_serv'.$nro.'(this.checked,'.$row['serv_id'].');" title="SERVICIO INACTIVO">';
                    }
                    $tabla.='
                    </label>
                  </div>
                </td>
                <td align="center"><a href="#" data-toggle="modal" data-target="#modal_mod_ffsa" class="btn-default mod_ffsa" name="'.$row['serv_id'].'" title="MODIFICAR SUB ACTIVIDAD" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>';
              }
          $tabla.='    
          </tr>';
          ?>
            <script>
              function scheck_serv<?php echo $nro;?>(estaChequeado,id) {
                valor=0;
                titulo='DESACTIVAR SERVICIO / SUB ACTIVIDAD ';
                if (estaChequeado == true) {
                  valor=1;
                  titulo='ACTIVAR SERVICIO / SUB ACTIVIDAD';
                }

                var OK = confirm(titulo);
                  if (OK) {
                      var url = "<?php echo site_url().'/mantenimiento/cestructura_organizacional/estado_servicio'?>";
                      $.ajax({
                          type: "post",
                          url: url,
                          data:{id:id,estado:valor},
                          success: function (data) {
                              window.location.reload(true);
                          }
                      });
                  }
              }
            </script>
          <?php
        }

      return $tabla;
    }

    /*--- ACTIVAR, DESACTIVAR SERVICIO -----*/
    function estado_servicio(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('id', 'id unidad', 'required|trim');
          $this->form_validation->set_rules('estado', 'estado', 'required|trim');
          
          $post = $this->input->post();
          $id= $this->security->xss_clean($post['id']);
          $estado_activo= $this->security->xss_clean($post['estado']);
         
          $update_servicio = array(
            'activo' => $estado_activo,
          );
          $this->db->where('serv_id', $id);
          $this->db->update('servicios_actividad', $update_servicio);
              
      }else{
          show_404();
      }
    }

    /*------------------------- Subir Archivo Servicios -----------------*/
    function importar_archivo_servicios(){
      if ($this->input->post()) {
          $post = $this->input->post();
        
          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
             /*--------------------------------------------------------------*/
              $lineas=$this->subir_archivo($archivotmp);
              $this->session->set_flashdata('success','SE SUBIO CORRECTAMENTE '.$lineas.' SERVICIOS');
              redirect(site_url("").'/estructura_org');
             /*--------------------------------------------------------------*/
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

    /*---------------- Archivo Servicios --------------------*/
    public function subir_archivo($archivotmp){  
        $i=0;
        $nro=0;
        $lineas = file($archivotmp);
        
        foreach ($lineas as $linea_num => $linea){ 
          if($i != 0){ 
              $datos = explode(";",$linea);
              if(count($datos)==2){
                  $servicio=$datos[0]; /// Servicio
                  $serv_cod=mb_convert_encoding(''.$datos[1], 'cp1252', 'UTF-8'); /// Codigo Servicio
                 
                  $serv=$this->model_estructura_org->get_servicio_actividad_cod($serv_cod);
                  if(count($serv)==0){
                    /*------------- Insert Servicio ---------------*/
                    $query=$this->db->query('set client_encoding= WIN1252;');
                    $data_to_store = array( 
                      'serv_cod' => $serv_cod,
                      'serv_descripcion' => $servicio,
                      'serv_tp' => 0,
                      'fun_id' => $this->fun_id,
                    );
                    $this->db->insert('servicios_actividad', $data_to_store);
                    $serv_id=$this->db->insert_id();
                    /*---------------------------------------------*/

                    if(count($this->model_estructura_org->get_servicio_actividad_cod($serv_cod))!=0){
                      $nro++;
                    }
                  }
              }
          }
          else{
              $query=$this->db->query('set client_encoding= WIN1252;');
              $data_to_store = array( 
                'serv_cod' => 0,
                'serv_descripcion' => 'NO SELECCIONADO',
                'serv_tp' => 0,
                'fun_id' => $this->fun_id,
              );
              $this->db->insert('servicios_actividad', $data_to_store);
          }
          $i++;
        }
        return $nro;
     }

    /*-- GET ACTIVIDAD UNIDAD - ESTABLECIMIENTO (2020) --*/
    public function get_actividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $act_id = $this->security->xss_clean($post['act_id']);
        $te_id = $this->security->xss_clean($post['te_id']);
        
        $actividad= $this->model_estructura_org->get_actividad($act_id); /// Con apertura Programatica alineado
        if($te_id==21){
          $actividad= $this->model_estructura_org->datos_unidad_organizacional($act_id);
        }
        
        if(count($actividad)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'actividad' => $actividad,
          );
        }
        else{
          $result = array(
            'respuesta' => 'error',
          );
        }
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--------------- GET SUB ACTIVIDAD UO -------------------*/
    public function get_sub_actividad(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $serv_id = $this->security->xss_clean($post['serv_id']);
        $sub_actividad= $this->model_estructura_org->get_servicio_actividad_id($serv_id);

        if(count($sub_actividad)!=0){
          $result = array(
              'respuesta' => 'correcto',
              'sub_actividad' => $sub_actividad,
          );
        }
        else{
          $result = array(
              'respuesta' => 'error',
          );
        }
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*-------- VERIFICACION ACTIVIDAD-APERTURA PROGRAMATICA GESTION --------*/
    function verif_actividad_apertura(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['cod']);
          $aper_id= $this->security->xss_clean($post['aper']);

          $variable= $this->model_estructura_org->get_aper_act_gestion($aper_id,$codigo);
          if(count($variable)!=0){
            echo "true"; /// Existe Registrado
          }
          else{
            echo "false"; /// No Existe Registrado
          }
      }else{
        show_404();
      }
    }

    /*-------- VERIFICACION DE CODIGO ACTIVIDAD --------*/
    function verif_codigo_actividad(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $ue = $this->security->xss_clean($post['ue']); /// Unidad Ejecutora

          $variable= $this->model_estructura_org->get_ue_codigo_actividad($ue,$codigo);
          if(count($variable)!=0){
            echo "true"; /// Existe Registrado
          }
          else{
            echo "false"; /// No Existe Registrado
          }
      }else{
        show_404();
      }
    }

    /*-------- VERIFICACION DE CODIGO SUB ACTIVIDAD --------*/
    function verif_codigo_sub_actividad(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo

          $variable= $this->model_estructura_org->get_servicio_actividad_cod($codigo);
          if(count($variable)!=0){
            echo "true"; /// Existe Registrado
          }
          else{
            echo "false"; /// No Existe Registrado
          }
      }else{
        show_404();
      }
    }

    /*-------- ELIMINAR ACTIVIDAD --------*/
    function delete_actividad(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $act_id = $this->security->xss_clean($post['act_id']);
          
          $update_act= array(
           'act_estado' => 3,
           'fun_id' => $this->fun_id
          );
          $this->db->where('act_id', $act_id);
          $this->db->update('unidad_actividad', $this->security->xss_clean($update_act));
          /*-----------------------------------------------------------------*/

          $result = array(
            'respuesta' => 'correcto'
           );

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*---------- Lista - Tipo de Establecimiento ----------*/
    public function list_establecimiento(){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['programas'] = $this->model_proyecto->list_prog($this->gestion); ///// lista aperturas padres
      
      $tabla='';
      $establecimientos=$this->model_estructura_org->list_tp_establecimiento();
      $tabla.='
      <table class="table table-bordered" style="width:75%;" align=center>
        <thead>
          <tr>
            <th>#</th>
            <th>ESCALON DE COMPLEJIDAD</th>
            <th>ESTABLECIMIENTO</th>
            <th>TIPO</th>
            <th>TIPO ADMINISTRACI&Oacute;N</th>
            <th>NIVEL</th>
            <th>PROGRAMA</th>
            <th>NRO. SERVICIOS</th>
            <th></th>
            <th></th>
          </tr>
        <thead>
        <tbody>';
        $nro=0;
      foreach($establecimientos  as $row){
        if($row['te_id']!=0){
          $aper=$this->model_estructura_org->relacion_establecimiento_apertura($row['te_id']);
          $btn='default'; $desc_aper='NN';
          if(count($aper)!=0){
            $btn='success';
            $desc_aper='NN';
          }

          $nro++;
          $tabla.='
          <tr>
            <td title='.$row['te_id'].'>'.$nro.'</td>
            <td>'.$row['escalon'].'</td>
            <td>'.$row['establecimiento'].'</td>
            <td>'.$row['tipo'].'</td>
            <td>'.$row['tipo_adm'].'</td>
            <td>'.$row['nivel'].'</td>
            <td><a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-'.$btn.' mod_ff" name="'.$row['te_id'].'" title="ASIGNAR PROGRAMA" >ASIGNAR PROGRAMA</a></td>
            <td align=center><b><font color=blue size=3>'.count($this->model_estructura_org->list_establecimiento_servicio($row['te_id'])).'</font></b></td>
            <td><center><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" name="'.$row['te_id'].'" id="'.strtoupper($row['establecimiento']).' ('.strtoupper($row['tipo']).')">VER SERVICIOS</a></center></td>
            <td><center><a href="'.site_url("").'/servicios/'.$row['te_id'].'" title="SELECCIONE SERVICIOS" class="btn btn-default">SELECCIONAR SERVICIOS</a></center></td>
          </tr>';
        }
        
      }
      $tabla.='</tbody>
        </table>';

      $data['establecimiento']=$tabla;
      $this->load->view('admin/mantenimiento/unidad_organizacional/establecimiento', $data);
    }

    /*-------- GET LISTA DE SERVICIOS ------------*/
    public function get_servicios(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $te_id = $this->security->xss_clean($post['te_id']);

        $tabla=$this->mis_servicios($te_id);
        $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------- GET PROGRAMA ASIGNADO -------*/
    public function get_programa(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $te_id = $this->security->xss_clean($post['te_id']); /// Tipo de Establecimiento
        $relacion=$this->model_estructura_org->relacion_establecimiento_apertura($te_id);
        $aper_id=0;
        $ae_id=0;
        if(count($relacion)!=0){
          $aper_id=$relacion[0]['aper_id'];
          $ae_id=$relacion[0]['ae_id'];
        }

        $result = array(
          'respuesta' => 'correcto',
          'aper_id' => $aper_id,
          'ae_id' => $ae_id,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- VALIDA UPDATE ASIGNACION DE PROGRAMA PADRE A ESTABLECIMIENTO ---*/
    public function valida_programa(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $te_id = $this->security->xss_clean($post['te_id']); /// te id 
          $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id 

          $this->db->where('te_id', $te_id);
          $this->db->where('g_id', $this->gestion);
          $this->db->delete('aper_establecimiento');

          $data_to_store = array(
              'te_id' => $te_id,
              'aper_id' => $aper_id,
              'g_id' => $this->gestion,
            );
            $this->db->insert('aper_establecimiento',$data_to_store);
            $ae_id=$this->db->insert_id();

          $this->session->set_flashdata('success','SE ACTUALIZO CORRECTAMENTE');
          redirect(site_url("").'/tp_establecimientos');
          
      } else {
          show_404();
      }
    }

    /*---------- Mis Servicios Seleccionados ----------*/
    public function mis_servicios($te_id){ 
      $tabla='';
      $servicios=$this->model_estructura_org->list_establecimiento_servicio($te_id);
      $tabla.='<center><table class="table table-bordered" style="width:80%;">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">C&Oacute;DIGO</th>
                    <th scope="col">SERVICIO / SUB ACTIVIDAD</th>
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($servicios as $row){
                  $nro++;
                  $tabla.=
                  '<tr>
                    <td>'.$nro.'</td>
                    <td>'.$row['serv_cod'].'</td>
                    <td>'.$row['serv_descripcion'].'</td>
                  </tr>';
                }
                $tabla.='
                </tbody>
              </table></center>';

      return $tabla;
    }

    /*---------- Lista - adicionar servicios ----------*/
    public function list_servicios($te_id){ 
      $data['menu']=$this->menu(9);
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $tabla='';
      $servicios = $this->model_estructura_org->list_servicios(); /// Lista Servicios
      $nro=count($this->model_estructura_org->list_establecimiento_servicio($te_id));
      $tabla='';
      $establecimientos=$this->model_estructura_org->list_tp_establecimiento();
      $tabla.='
      BUSCAR SERVICIO : <input type="text" class="form-control" id="kwd_search" value="" style="width:50%;"/><br>
      <form id="del_req" name="del_req" novalidate="novalidate" method="post" action="'.site_url("").'/mantenimiento/cestructura_organizacional/valida_relacion">
      <input type="hidden" name="te_id" id="te_id" value="'.$te_id.'">
      <table class="table table-bordered" style="width:50%;" align=center id="table">
        <thead>
          <tr>
            <th>#</th>
            <th>C&Oacute;DIGO</th>
            <th>SERVICIO / SUB ACTIVIDAD</th>
            <th></th>
          </tr>
        <thead>
        <tbody>';
        $cont=0;
        foreach($servicios  as $row){
            $veri_cs=$this->model_estructura_org->get_establecimiento_servicio($te_id,$row['serv_id']);
            $cont++;
            $tabla.='
            <tr style="height:50px;" class="modo1">
                <td>'.$cont.'</td>
                <td>'.$row['serv_cod'].'</td>
                <td>'.strtoupper($row['serv_descripcion']).'</td>
                <td align="center">';
                if($row['serv_id']!=0){
                  if(count($veri_cs)!=0){
                  $tabla.='<input type="checkbox" name="serv[]" value="'.$row['serv_id'].'" onclick="scheck'.$cont.'(this.checked);" title="SELECCIONAR SERVICIO - SUB ACTIVIDAD" checked/>';
                  }
                  else{
                    $tabla.='<input type="checkbox" name="serv[]" value="'.$row['serv_id'].'" onclick="scheck'.$cont.'(this.checked);" title="SELECCIONAR SERVICIO - SUB ACTIVIDAD" />';
                  }
                }
                $tabla.='
                </td>
            </tr>';
            ?>
              <script>
                function scheck<?php echo $cont;?>(estaChequeado) {
                  val = parseInt($('[name="tot"]').val());
                  if (estaChequeado == true) {
                    val = val + 1;
                  } else {
                    val = val - 1;
                  }
                  $('[name="tot"]').val((val).toFixed(0));
                }
              </script>
            <?php
          }
        $tabla.='</tbody>
        </table>
          <input type="hidden" name="tot" id="tot" value="'.$nro.'">
          <div class="alert alert-success" align=right>
          <input type="button" class="btn btn-lg btn-primary btn-xs" value="ASIGNAR SERVICIOS " id="btsubmit" onclick="valida_servicios()" title="ASIGNAR SERVICIOS"></div>
        </form>';

      $data['servicio']=$tabla;
      $this->load->view('admin/mantenimiento/unidad_organizacional/lista_servicios', $data);
    }

    /*---------- VALIDA RELACION SERVICIOS --------------.*/
    public function valida_relacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $te_id = $post['te_id']; /// te id

        $this->db->where('te_id', $te_id);
        $this->db->delete('establecimiento_servicio');

        if (!empty($_POST["serv"]) && is_array($_POST["serv"]) ) {
          foreach ( array_keys($_POST["serv"]) as $como){
            $data_to_store = array( 
              'te_id' => $te_id ,
              'serv_id' => $_POST["serv"][$como],
            );
            $this->db->insert('establecimiento_servicio', $data_to_store);
          }
          $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE');
          redirect(site_url("").'/servicios/'.$te_id.'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL ASIGNAR SERVICIOS');
          redirect(site_url("").'/servicios/'.$te_id.'');
        }
      }
      else{
        echo "<font color=red><b>Error al Asignar Servicios</b></font>";
      }
    }

    /*----------- REPORTE TIPO DE ESTABLECIMIENTO (2020) ----------*/
    public function reporte_list_establecimiento(){
      $data['mes'] = $this->mes_nombre();
      $tabla='';
      $establecimiento=$this->model_estructura_org->list_tp_establecimiento();
      foreach ($establecimiento as $row){
        if($row['te_id']!=0){
          $tabla.='<div style="font-size: 10px;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$row['escalon'].' - '.$row['establecimiento'].'</div>';
          $servicios=$this->model_estructura_org->list_establecimiento_servicio($row['te_id']);
           $tabla.='
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align="center">
                <thead>
                 <tr class="modo1">
                    <th style="background-color: #1c7368; color: #FFFFFF" style="height:12px;width:5%;">#</th>
                    <th style="background-color: #1c7368; color: #FFFFFF; width:10%;">C&Oacute;DIGO</th>
                    <th style="background-color: #1c7368; color: #FFFFFF; width:85%;">SERVICIO / SUB ACTIVIDAD</th>  
                </tr>    
               
                </thead>
                <tbody>';
                if(count($servicios)!=0){
                  $nro=0;
                  foreach ($servicios as $rows){
                    $nro++;
                    $tabla.='
                      <tr class="modo1">
                        <td style="width: 5%; text-align: left;" style="height:12px;">'.$nro.'</td>
                        <td style="width: 10%; text-align: left;" style="height:12px;">'.$rows['serv_cod'].'</td>
                        <td style="width: 85%; text-align: left;" style="height:12px;">'.$rows['serv_descripcion'].'</td>
                      </tr>';
                  }
                }
                else{
                  $tabla.='<tr class="modo1"><td colspan=3 style="width:100%;height:12px;">Sin Servicios</td></tr>';
                }
              $tabla.='
                </tbody>
              </table><br>';
                  
        }
        
      
      }

      $data['establecimientos']=$tabla;  
      
      $this->load->view('admin/mantenimiento/unidad_organizacional/reporte_establecimiento', $data);
    }






    /*---------------- REPORTE OBJETIVOS ESTRATEGICOS --------------------*/
    public function reporte_estructura($tipo){
        if($tipo==1){
          $tit='Lista_Actividades';
        }
        elseif($tipo==2){
          $tit='Lista_Sub_Actividades';
        }
        elseif($tipo==3){
          $tit='';
        }
        else{
          $tit='Lista_Unidades_medida'; 
        }
      
      $html = $this->estructura($tipo);
      echo $html;
     /* $dompdf = new DOMPDF();
      $dompdf->load_html($html);
      $dompdf->set_paper('letter', 'portrait');
      ini_set('memory_limit','700M');
      ini_set('max_execution_time', 900000);
      $dompdf->render();
      $dompdf->stream("".$tit.".pdf", array("Attachment" => false));*/
    }

    /*--------------------- ACTIVIDADES - UO ---------------------*/
    function estructura($tipo){
      if($tipo==1){
        $tabla = $this->actividades();
        $titulo='ACTIVIDADES - '.$this->gestion;
      }
      elseif($tipo==2){
        $tabla = $this->rep_servicios();
        $titulo='SUB ACTIVIDADES';
      }
      elseif($tipo==3){
        $tabla=$this->lista_unidad_servicio();
      }
      else{
        $tabla=$this->lista_unidad_medida();
      }

      $html=$tabla;
      return $html;
    }

    /*---- LISTA DE UNIDAD DE MEDIDA -----*/
    function lista_unidad_medida(){
      $tabla='';
      $lista_umedida=$this->model_insumo->list_unidadmedida();
      $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;font-size: 8pt;" align="center" >
                  <thead>
                    <tr class="modo1">
                      <th style="width:1%;">#</th>
                      <th style="width:5%;">UNIDAD MANEJO</th>
                      <th style="width:5%;">ABREVIACION</th>
                    </tr>
                  </thead>
                  <tbody>';
                  $nro=0;
                  foreach ($lista_umedida as $row){
                    $nro++;
                    $tabla.='
                    <tr>
                      <td>'.$nro.'</td>
                      <td>'.$row['um_descripcion'].'</td>
                      <td>'.$row['um_abrev'].'</td>
                    </tr>';
                  }
                  $tabla.='
                  </tbody>
                </table>';


      return $tabla;
    }

    /*---- LISTA DE UNIDAD CON SUS SERVICIOS -----*/
    function lista_unidad_servicio(){
      $tabla='';
      $unidad_servicio=$this->model_estructura_org->lista_unidad_servicio_poa();
      $tabla.='<table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;font-size: 8pt;" align="center" >
                  <thead>
                    <tr class="modo1">
                      <th style="width:5%;">COD. DA.</th>
                      <th style="width:5%;">COD. UE.</th>
                      <th style="width:5%;">COD. PROG.</th>
                      <th style="width:5%;">COD. PROY.</th>
                      <th style="width:5%;">COD. ACT.</th>
                      <th style="width:5%;">COD. SUBACT.</th>
                      <th style="width:10%;">DESCRIPCIÓN DA</th>
                      <th style="width:10%;">DESCRIPCIÓN UE</th>
                      <th style="width:10%;">DESCRIPCIÓN PROGRAMA</th>
                      <th style="width:15%;">DESCRIPCIÓN ACTIVIDAD</th>
                      <th style="width:15%;">DESCRIPCIÓN SUBACTIVIDAD</th>
                    </tr>
                  </thead>
                  <tbody>';
                  foreach ($unidad_servicio as $row){
                    $tabla.='
                    <tr>
                      <td>'.$row['dep_id'].'</td>
                      <td>'.$row['dist_id'].'</td>
                      <td>'.$row['aper_programa'].'</td>
                      <td>'.$row['aper_proyecto'].'</td>
                      <td>'.$row['aper_actividad'].'</td>
                      <td>'.$row['serv_cod'].'</td>
                      <td>'.strtoupper($row['dep_departamento']).'</td>
                      <td>'.strtoupper($row['dist_distrital']).'</td>
                      <td></td>
                      <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td>'.$row['serv_descripcion'].'</td>
                    </tr>';
                  }
                  $tabla.='
                  </tbody>
                </table>';


      return $tabla;
    }



    /*---- REPORTE SERVICIOS - ACTIVIDADES -----*/
    function actividades(){
      $actividades=$this->list_actividades(2);
      $tabla='';
      $serv=$this->list_sub_actividades(2);
      $tabla.='<div class="table-responsive">
                  <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;font-size: 8pt;" align="center" >
                      <thead>
                        <tr class="modo1">
                          <th style="width:1%;">#</th>
                          <th style="width:10%;">REGIONAL</th>
                          <th style="width:15%;">UNIDAD ADMINISTRATIVA</th>
                          <th style="width:15%;">UNIDAD EJECUTORA</th>
                          <th style="width:35%;">UNIDAD, ESTABLECIMIENTO</th>
                        </tr>
                      </thead>
                      <tbody>'.$actividades.'</tbody>
                    </table>
                </div>';

      return $tabla;
    }


    /*------------ REPORTE SERVICIOS - SUB ACTIVIDADES ----------*/
    function rep_servicios(){
      $tabla='';
      $serv=$this->list_sub_actividades(2);
      $tabla.='<div class="table-responsive">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:70%;" align="center">
                      <thead>                             
                        <tr class="modo1">
                          <th style="width:5%;">#</th>
                          <th style="width:10%;" align="center">C&Oacute;DIGO</th>
                          <th style="width:20%;">SUB ACTIVIDAD</th>
                          <th style="width:10%;">TIPO</th>
                        </tr>
                      </thead>
                      <tbody>'.$serv.'</tbody>
                    </table>
                </div>';

      return $tabla;
    }

    /*-------- MENU ---------*/
    function menu($mod){
        $enlaces=$this->menu_modelo->get_Modulos($mod);
        for($i=0;$i<count($enlaces);$i++)
        {
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

    function estilo_vertical(){
        $estilo_vertical = '<style>
        body{
            font-family: sans-serif;
            }
        table{
            font-size: 8px;
            width: 100%;
            background-color:#fff;
        }
        .mv{font-size:10px;}
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}

        .titulo_pdf {
            text-align: left;
            font-size: 8px;
        }
        .tabla {
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 8px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 6px;
        background-color: #1c7368;
        background-repeat: repeat-x;
        color: #FFFFFF;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #558FA6;
        border-bottom-color: #558FA6;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 6px;
        font-weight:bold;
       
        background-image: url(fondo_tr01.png);
        background-repeat: repeat-x;
        color: #34484E;
       
        }
        .tabla .modo1 td {
        padding: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #A4C4D0;
        border-bottom-color: #A4C4D0;
        }
    </style>';
        return $estilo_vertical;
    }
}