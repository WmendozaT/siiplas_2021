<?php
class Ccertificacion_poa extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');
  public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3 & $this->session->userdata('tp_usuario')==0){
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
      $this->load->model('menu_modelo');
      $this->load->model('Users_model','',true);
      $this->load->library('security');
      $this->gestion = $this->session->userData('gestion');
      $this->adm = $this->session->userData('adm');
      $this->rol = $this->session->userData('rol_id');
      $this->dist = $this->session->userData('dist');
      $this->dist_tp = $this->session->userData('dist_tp');
      $this->tp_adm = $this->session->userData('tp_adm');
      $this->fun_id = $this->session->userData('fun_id');
      $this->load->library('certificacionpoa');
      }
      else{
          $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }

    /*--------------------------- TIPO DE RESPONSABLE ---------------------------*/
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

    /*------ LISTA DE POAS APROBADOS -------*/
    public function list_poas_aprobados(){
      if($this->rolfunn(3)){
        $data['menu']=$this->menu(4);
        $data['resp']=$this->session->userdata('funcionario');
        $data['reg'] = $this->model_proyecto->dep_dist($this->dist);
        $data['res_dep']=$this->tp_resp();

        $data['titulo']='SELECCIONAR ACTIVIDAD - '.$this->gestion.'';
        if($this->gestion>2020){
          $data['titulo']='SELECCIONAR OPERACI&Oacute;N - '.$this->gestion.'';
        }

        $data['proyectos']=$this->list_pinversion(4);
        $data['operacion']=$this->list_unidades_es(4);

        //echo $this->mis_productos(1609);
        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/list_poas_aprobados', $data);
      }
      else{
        redirect('admin/dashboard');
      }   
    }


    /*---- Lista de Unidades / Establecimientos de Salud (2020-2021) -----*/
    public function list_unidades_es($proy_estado){
        $unidades=$this->model_proyecto->list_unidades(4,$proy_estado);
        $tabla='';
        
        if($this->gestion>2020){ /// 2021
           $tabla.='
            <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
              <thead>
                <tr style="height:35px;">
                  <th style="width:1%;" bgcolor="#474544">#</th>
                  <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
                  <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">PROGRAMA '.$this->gestion.'</th>
                  <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N ACTIVIDAD">ACTIVIDAD</th>
                  <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
                  <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
                  <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
                  <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
                  <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
                </tr>
              </thead>
              <tbody>';
                $nro=0;
                foreach($unidades as $row){
                  if($row['proy_estado']==4){
                    $nro++;
                    $tabla.='
                      <tr style="height:45px;">
                        <td align=center title="'.$row['proy_id'].'-'.$row['aper_id'].'"><b>'.$nro.'</b></td>
                        <td align=center>
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" title="SELECCIONAR ACTIVIDAD"> 
                          <i class="glyphicon glyphicon-list"></i> SELECCIONAR OPERACION</a>
                        </td>
                        <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                        <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                        <td>'.$row['escalon'].'</td>
                        <td>'.$row['nivel'].'</td>
                        <td>'.$row['tipo_adm'].'</td>
                        <td>'.strtoupper($row['dep_departamento']).'</td>
                        <td>'.strtoupper($row['dist_distrital']).'</td>
                      </tr>';
                  }
                }
              $tabla.='
              </tbody>
            </table>';
        }
        else{ /// 2020
           $tabla.='
            <table id="dt_basic1" class="table1 table-bordered" style="width:100%;">
              <thead>
                <tr style="height:35px;">
                  <th style="width:1%;" bgcolor="#474544">#</th>
                  <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
                  <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
                  <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">UNIDAD / ESTABLECIMIENTO DE SALUD</th>
                  <th style="width:10%;" bgcolor="#474544" title="NIVEL">ESCALON</th>
                  <th style="width:10%;" bgcolor="#474544" title="NIVEL">NIVEL</th>
                  <th style="width:10%;" bgcolor="#474544" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
                  <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
                  <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
                </tr>
              </thead>
              <tbody>';
                $nro=0;
                foreach($unidades as $row){
                  if($row['proy_estado']==4){
                    $nro++;
                    $tabla.='
                      <tr style="height:45px;">
                        <td align=center><b>'.$nro.'</b></td>
                        <td align=center>
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id=" '.$row['tipo'].' '.strtoupper($row['proy_nombre']).' - '.$row['abrev'].'" title="SELECCIONAR ACTIVIDAD"> 
                          <i class="glyphicon glyphicon-list"></i> MIS ACTIVIDADES</a>
                        </td>
                        <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                        <td>'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                        <td>'.$row['escalon'].'</td>
                        <td>'.$row['nivel'].'</td>
                        <td>'.$row['tipo_adm'].'</td>
                        <td>'.strtoupper($row['dep_departamento']).'</td>
                        <td>'.strtoupper($row['dist_distrital']).'</td>
                      </tr>';
                  }
                }
              $tabla.='
              </tbody>
            </table>';
        }




       
      return $tabla;
    }

    /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function list_pinversion($proy_estado){
      $proyectos=$this->model_proyecto->list_pinversion(1,$proy_estado);
      $tabla='';
      if($this->gestion>2020){ /// 2021
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">PROGRAMA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">DESCRIPCI&Oacute;N FASE</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                  <tr style="height:35px;">
                    <td><center>'.$nro.'</center></td>
                    <td align=center>';
                      if($row['pfec_estado']==1){
                        $tabla.='
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                          <i class="glyphicon glyphicon-list"></i> SELECCIONAR OPERACIÓN</a>';
                      }
                      else{
                        $tabla.='FASE NO ACTIVA';
                      }
                    $tabla.='
                    </td>
                <td><center>'.$row['aper_programa'].' '.$row['proy_sisin'].' 00</center></td>
                <td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='<td title='.$row['pfec_id'].'>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      }
      else{ /// 2020
        $tabla.='
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#474544">#</th>
              <th style="width:5%;" bgcolor="#474544" title="SELECCIONAR"></th>
              <th style="width:10%;" bgcolor="#474544" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA '.$this->gestion.'</th>
              <th style="width:20%;" bgcolor="#474544" title="DESCRIPCI&Oacute;N">PROYECTO DE INVERSI&Oacute;N</th>
              <th style="width:10%;" bgcolor="#474544" title="SISIN">C&Oacute;DIGO_SISIN</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
              <th style="width:10%;" bgcolor="#474544" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
              <th style="width:10%;" bgcolor="#474544" title="FASE - ETAPA DE LA OPERACI&Oacute;N">FASE_ETAPA</th>
            </tr>
          </thead>
          <tbody>';
            $nro=0;
            foreach($proyectos as $row){
              $nro++;
              $tabla.='
                  <tr style="height:35px;">
                    <td><center>'.$nro.'</center></td>
                    <td align=center>';
                      if($row['pfec_estado']==1){
                        $tabla.='
                          <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success enlace" style="color: green; background-color: #eeeeee;border-bottom-width: 5px;" name="'.$row['proy_id'].'" id="'.strtoupper($row['proy_nombre']).'">
                          <i class="glyphicon glyphicon-list"></i> MIS ACTIVIDADES</a>';
                      }
                      else{
                        $tabla.='FASE NO ACTIVA';
                      }
                    $tabla.='
                    </td>
                <td><center>'.$row['aper_programa'].''.$row['aper_proyecto'].''.$row['aper_actividad'].'</center></td>
                <td>'.$row['proy_nombre'].'</td>';
                $tabla.='<td>'.$row['proy_sisin'].'</td>';
                $tabla.='<td>'.$row['dep_cod'].' '.strtoupper($row['dep_departamento']).'</td>';
                $tabla.='<td>'.$row['dist_cod'].' '.strtoupper($row['dist_distrital']).'</td>';
                $tabla .='<td title='.$row['pfec_id'].'>'.strtoupper($row['pfec_descripcion']).'</td>';
              $tabla.='</tr>';
            }
          $tabla.='
          </tbody>
        </table>';
      }

      return $tabla;
    }


  /*---- Lista de Proyectos de Inversion (2020) -----*/
  public function list_items_cert($prod_id){
    $data['datos']=$this->model_certificacion->get_datos_unidad_prod($prod_id);
    if(count($data['datos'])!=0){
        $data['menu']=$this->menu(4);
        $data['resp']=$this->session->userdata('funcionario');
        $data['res_dep']=$this->tp_resp();
        $data['titulo']=$this->titulo_cabecera($data['datos']);
        $requerimientos=$this->model_certificacion->requerimientos_operacion($prod_id);
        $this->update_gestion_temporalidad($requerimientos);
        $data['requerimientos'] = $this->certificacionpoa->list_requerimientos_prelista($prod_id); /// para listas mayores a 500
        $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_prevista', $data);

        /*if(count($requerimientos)>500){
          $data['requerimientos'] = $this->list_requerimientos_prelista($prod_id); /// para listas mayores a 500
          $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_prevista', $data);
        }
        else{
          $data['requerimientos'] = $this->list_requerimientos($prod_id);
          $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_cert', $data);
        }*/
    }
    else{
      echo "Error !!!";
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



  /*------ VALIDA CERTIFICACION POA (2020 - 2021) ------*/
  public function valida_cpoa(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $tp_id = $this->security->xss_clean($post['tp_id']);
      $cite_poa = $this->security->xss_clean($post['cite_cpoa']);
      $cite_fecha = $this->security->xss_clean($post['cite_fecha']);
      $cite_recomendacion = $this->security->xss_clean($post['rec']);
      $total = $this->security->xss_clean($post['tot']);

      if($tp_id==1){
        $datos=$this->model_certificacion->get_datos_pi_prod($prod_id); /// Gasto Proyecto de Inversión
      }
      else{
        $datos=$this->model_certificacion->get_datos_unidad_prod($prod_id); /// Gasto Corriente
      }
      
      /*------ INSERTANDO CERTIFICADO ------*/
        $data_to_store = array( 
          'proy_id' => $datos[0]['proy_id'],
          'aper_id' => $datos[0]['aper_id'], /// aper del programa padre
          'cpoa_fecha' => date("d/m/Y H:i:s"),
          'cpoa_gestion' => $this->gestion,
          'cpoa_estado' => 0, /// 0 : en proceso, 1 elaborado, 2, modificado, 3 Eliminado
          'fun_id' => $this->fun_id,
          'com_id' => $datos[0]['com_id'],
          'cpoa_cite' => strtoupper($cite_poa),
          'cite_fecha' => $cite_fecha,
          'cpoa_recomendacion' => strtoupper($cite_recomendacion),
          'prod_id' => $prod_id,
        );
        $this->db->insert('certificacionpoa', $data_to_store);
        $cpoa_id=$this->db->insert_id();
      /*-------------------------------------*/
      /*----- DETALLE CERTIFICACION POA -----*/
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
          if(count($lista_temporalidad)>1){
            for ($i=1; $i <=12 ; $i++) {
              if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){
                /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                $data_to_store = array(
                  'cpoad_id' => $cpoad_id,
                  'tins_id' => $_POST["ipm".$i."".$_POST["ins"][$como]],
                );
                $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
                /*--------------------------------------------*/
              } 
            }
          }
          else{
            /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
              $data_to_store = array(
                'cpoad_id' => $cpoad_id,
                'tins_id' => $lista_temporalidad[0]['tins_id'],
              );
              $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
            /*--------------------------------------------*/
          }
          
        }

        if(count($this->model_certificacion->get_lista_detalle_cert_poa($cpoa_id)==$total)){
            $this->session->set_flashdata('success','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
          }
          else{
            $this->session->set_flashdata('default','LA CERTIFICACIÓN POA SE GENERO EXITOSAMENTE ... ');
          }

          /*----- Update Codigo Certificacion POA ---*/
          if($datos[0]['dist_id']!=0){
            $this->generar_certificacion_poa($cpoa_id);
          }
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


  /*------ VALIDA CERTIFICACION POA (2020) ------*/
  public function valida_reformulado_cpoa(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $cpoaa_id = $this->security->xss_clean($post['cpoaa_id']); /// Id Certificación poa Anulado
      $tp_id = $this->security->xss_clean($post['tp_id']); /// Tipo de Anulacion
      $total = $this->security->xss_clean($post['tot']); /// Total Items

      $cert_anulado=$this->model_certificacion->get_cert_poa_editado($cpoaa_id); /// Datos de la Certificación Anulado
      $cpoa=$this->model_certificacion->get_certificacion_poa($cert_anulado[0]['cpoa_id']); /// Datos de la Certificación POA
      $cite_mod_req = $this->model_modrequerimiento->get_cite_insumo($cert_anulado[0]['cite_id']); // Datos Cite Modificación de requerimiento

      //if($total!=0){
          $this->delete_certificacion_item($cert_anulado[0]['cpoa_id']); // Eliminando anterior Registro Certificación POA
          if (!empty($_POST["ins"]) && is_array($_POST["ins"])) {
            foreach ( array_keys($_POST["ins"]) as $como){

            /*--- verifica el numero de meses Certificados ---*/
            $nro_mes=0;
            for ($i=1; $i <=12 ; $i++) {
              if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){
                
                if(count($this->model_certificacion->get_mes_certificado($_POST["ipm".$i."".$_POST["ins"][$como]]))==0){
                  $nro_mes++;
                }
              } 
            }
            /*------------------------------------------------*/

            /*---------- GUARDANDO ITEMS CERTIFICADOS ---------*/
              if($nro_mes!=0){

                $data_to_store = array( 
                  'cpoa_id' => $cert_anulado[0]['cpoa_id'],
                  'ins_id' => $_POST["ins"][$como],
                  'fun_id' => $this->fun_id,
                );
                $this->db->insert('certificacionpoadetalle', $data_to_store);
                $cpoad_id=$this->db->insert_id();

                for ($i=1; $i <=12 ; $i++) {
                  if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){

                    if(count($this->model_certificacion->get_mes_certificado($_POST["ipm".$i."".$_POST["ins"][$como]]))==0){
                      /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                      $data_to_store = array(
                        'cpoad_id' => $cpoad_id,
                        'tins_id' => $_POST["ipm".$i."".$_POST["ins"][$como]],
                      );
                      $this->db->insert('cert_temporalidad_prog_insumo', $data_to_store);
                      /*--------------------------------------------*/
                    }
                  } 
                }
              }
            /*-----------------------------------------------*/
            }

            if(count($this->model_modrequerimiento->list_requerimientos_modificados($cite_mod_req[0]['cite_id']))!=0){
              $this->genera_codigo_modreq($cite_mod_req,$cert_anulado[0]['justificacion']);
            }
            
            redirect('cert/ver_cpoa/'.$cert_anulado[0]['cpoa_id'].'');
          }
          else{
            redirect('ejec/menu_cpoa'); /// Error al Reformular
          }

/*      }
      else{
        redirect('ejec/menu_cpoa'); /// Error al Reformular
      }*/
    }
    else{
      redirect('ejec/menu_cpoa'); /// Error al Reformular
    }
  }


  /*--- ELIMINA ITEMS CERTIFICADOS ---*/
  public function delete_certificacion_item($cpoa_id){
    $list_cpoas_anterior=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);

    foreach ($list_cpoas_anterior as $row){
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


  /*-------- VER CERTIFICACION POA --------*/
  public function ver_certificacion_poa($cpoa_id){
    $data['cpoa']=$this->model_certificacion->get_certificacion_poa($cpoa_id);

    if(count($data['cpoa'])!=0){
      $data['menu']=$this->menu(4);
      $data['resp']=$this->session->userdata('funcionario');
      $data['reg'] = $this->model_proyecto->dep_dist($this->dist);
      $data['res_dep']=$this->tp_resp();

      $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/ver_certificado_poa', $data);
    }
    else{
      echo "Error !!!";
    }
  }


    /*----- REPORTE CERTIFICADO POA PDF -------*/
    public function reporte_cpoa($cpoa_id){
      $data['cpoa']=$this->model_certificacion->get_certificacion_poa($cpoa_id); /// Datos Certificacion
      if (count($data['cpoa'])!=0) {
          $data['programa'] = $this->model_ejecucion->get_apertura_programatica($data['cpoa'][0]['aper_id']);
          $data['datos']=$this->model_certificacion->get_datos_unidad_prod($data['cpoa'][0]['prod_id']); // Datos completos hasta apertura
          $data['items']=$this->mis_items_certificados($cpoa_id);
          $data['nro']=count($this->model_certificacion->lista_items_certificados($cpoa_id));
          if($this->gestion!=2021){ /// Gestion 2020
            $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_cert_poa_2020', $data);
          }
          else{ /// Gestion 2021
            $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/reporte_cert_poa', $data);
          }
          
      }
      else{
        echo "Error !!!";
      }
    }


     /*-------- LISTA DE ITEMS CERTIFICADOS 2020 -------*/
    public function mis_items_certificados($cpoa_id){
      $tabla='';
      $cpoa=$this->model_certificacion->get_certificacion_poa($cpoa_id); /// Datos Certificacion
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


    /*-------- EDICION DE CERTIFICACIÓN POA 2020 -------*/
    public function modificar_cpoa($cpoaa_id){
      $data['cert_editado']=$this->model_certificacion->get_cert_poa_editado($cpoaa_id);
      if(count($data['cert_editado'])!=0 & $data['cert_editado'][0]['cpoa_estado']!=3){
        $cpoa=$this->model_certificacion->get_certificacion_poa($data['cert_editado'][0]['cpoa_id']); /// Datos Certificacion

        $data['datos']=$this->model_certificacion->get_datos_unidad_prod($data['cert_editado'][0]['prod_id']); /// Datos completos de la Unidad/ Proyectos de Inversión
          $data['menu']=$this->menu(4);
          $data['resp']=$this->session->userdata('funcionario');
          $data['res_dep']=$this->tp_resp();
          $data['titulo']=$this->titulo_cabecera($data['datos']);
          $data['requerimientos'] = $this->list_requerimientos_certificados($data['cert_editado'][0]['cpoa_id']); /// Lista de Items Certificados
          $data['nro_cert'] = count($this->model_certificacion->lista_items_certificados($data['cert_editado'][0]['cpoa_id'])); // Nro de Items Certificados
          $data['nro_meses'] = $this->model_certificacion->get_nro_mes_certificado_cpoa($data['cert_editado'][0]['cpoa_id']); // Nro de Meses
          $this->load->view('admin/ejecucion/certificacion_poa/form_cpoa/form_items_edit_cert', $data);
      }
      else{
        redirect('ejec/menu_cpoa');
      }
    }

    /*------- LISTA DE REQUERIMIENTOS CERTIFICADOS (REFORMULACION) ------*/
  public function list_requerimientos_certificados($cpoa_id){
    $tabla='';
    $requerimientos=$this->model_certificacion->requerimientos_modificar_cpoa($cpoa_id);

    $tabla.='
      <table class="table table-bordered" style="width:97%;" align="center" id="datos">
        <thead >
          <tr>
            <th style="width:1%;">#</th>
            <th style="width:2%;"></th>
            <th style="width:2%;"></th>
            <th style="width:4%;">PARTIDA</th>
            <th style="width:16%;">REQUERIMIENTO</th>
            <th style="width:5%;">UNIDAD DE MEDIDA</th>
            <th style="width:3%;">CANTIDAD</th>
            <th style="width:5%;">COSTO UNITARIO</th>
            <th style="width:5%;">COSTO TOTAL</th>
            <th style="width:5%;">MONTO CERTIFICADO</th>
            <th style="width:4.5%;">ENE.</th>
            <th style="width:4.5%;">FEB.</th>
            <th style="width:4.5%;">MAR.</th>
            <th style="width:4.5%;">ABR.</th>
            <th style="width:4.5%;">MAY.</th>
            <th style="width:4.5%;">JUN.</th>
            <th style="width:4.5%;">JUL.</th>
            <th style="width:4.5%;">AGO.</th>
            <th style="width:4.5%;">SEPT.</th>
            <th style="width:4.5%;">OCT.</th>
            <th style="width:4.5%;">NOV.</th>
            <th style="width:4.5%;">DIC.</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($requerimientos as $row){
          $monto_certificado=0;$verif=0; $color_tr=''; $tit='';
          $mcertificado=$this->model_certificacion->get_insumo_monto_certificado($row['ins_id']);
          if(count($mcertificado)!=0){
            $monto_certificado=$mcertificado[0]['certificado'];
          }

          $bgcolor='#f2fded';
          if(count($this->model_certificacion->get_insumo_monto_cpoa_certificado($row['ins_id'],$cpoa_id))==0){
            $bgcolor='#f59787';
          }

          $nro++;
          $tabla.='
          <tr bgcolor='.$bgcolor.' title='.$row['ins_id'].' id="tr'.$nro.'" >
            <td>'.$nro.'</td>
            <td>
              <input type="checkbox" name="ins[]" id="check'.$row['ins_id'].'" value="'.$row['ins_id'].'" onclick="seleccionarFila(this.value,'.$nro.','.$cpoa_id.',this.checked);" checked="checked"/><br>
              <input type="hidden" name="ins'.$row['ins_id'].'" id="ins'.$row['ins_id'].'" value="'.$row['ins_id'].'">
            </td>
            <td>
              <a href="#" data-toggle="modal" data-target="#modal_mod_ins" class="btn-default mod_ins" name="'.$row['ins_id'].'" id="btn_m" title="MODIFICAR REQUERIMIENTO - '.$row['ins_id'].'" disabled="true"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a>
            </td>
            <td style="font-size: 12px;" align=center><b>'.$row['par_codigo'].'</b></td>
            <td>'.$row['ins_detalle'].'</td>
            <td>'.$row['ins_unidad_medida'].'</td>
            <td align=right>'.$row['ins_cant_requerida'].'</td>
            <td align=right>'.$row['ins_costo_unitario'].'</td>
            <td align=right>'.$row['ins_costo_total'].'</td>
            <td align=right bgcolor="#e7f5f3"><b>'.number_format($monto_certificado, 2, ',', '.').'</b></td>';
            for ($i=1; $i <=12 ; $i++) {
              $color=''; 
              $m=$this->model_certificacion->get_insumo_programado_mes($row['ins_id'],$i);
              $tabla.='
              <td align=right>
                <table align=right>
                  <tr>
                    <td>
                      <div id="m'.$i.''.$row['ins_id'].'">';
                      if(count($m)!=0){
                        if(count($this->model_certificacion->get_mes_certificado($m[0]['tins_id']))==0){
                          $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);"/>';
                        }
                        elseif(count($this->model_certificacion->get_mes_certificado_cpoa($cpoa_id,$m[0]['tins_id']))==1){
                          $tabla.='<input type="checkbox" name="ipm'.$i.''.$row['ins_id'].'" id="ipmm'.$i.''.$row['ins_id'].'" title="Item Seleccionado" value="'.$m[0]['tins_id'].'" onclick="seleccionar_temporalidad(this.value,'.$cpoa_id.','.$row['ins_id'].','.$nro.',this.checked);" checked="checked"/>';
                          $color='green';
                        }
                      }
              $tabla.='
                    </td>
                    <td align=right >';
                    if(count($m)!=0){
                      $tabla.='<font color="'.$color.'">'.number_format($m[0]['ipm_fis'], 2, ',', '.').'</font>';
                    }
                    else{
                      $tabla.='0,00';
                    }
              $tabla.='
                    </td>
                  </tr>
                </table>
              </td>';
            }
            $tabla.='
          </tr>';
        }
      $tabla.='
        </tbody>
      </table>';

    return $tabla;
  }



  /*======= FUNCIONES EXTRAS ======*/
  /*-------- MENU -----*/
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

  /*------ TITULO CABECERA (2020)-----*/
  public function titulo_cabecera($datos){
    $tabla='';
    if($this->gestion>2020){ /// 2021
      if($datos[0]['tp_id']==1){ /// Proyecto de Inversion
        $tabla.=' <h1><b>PROYECTO : </b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['proy_sisin'].' 00 - '.$datos[0]['proy_nombre'].'</small>
                  <h1><b>UNIDAD RESPONSABLE : </b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                  <h1><b>OPERACI&Oacute;N : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $tabla.=' <h1><b>ACTIVIDAD : <b><small>'.$datos[0]['aper_programa'].' '.$datos[0]['aper_proyecto'].' '.$datos[0]['aper_actividad'].' - '.$datos[0]['tipo'].' '.$datos[0]['act_descripcion'].' '.$datos[0]['abrev'].'</small></h1>
                  <h1><b>SUBACTIVIDAD : <b><small>'.$datos[0]['serv_cod'].' '.$datos[0]['tipo_subactividad'].' '.$datos[0]['serv_descripcion'].'</small></h1>
                  <h1><b>OPERACI&Oacute;N : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
    }
    else{ /// 2020
      if($datos[0]['tp_id']==1){ /// Proyecto de Inversion
        $tabla.=' <h1><b>APERTURA PROGRAM&Aacute;TICA : </b><small>'.$datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' - '.$datos[0]['proy_nombre'].'</small>
                  <h1><b>COMPONENTE : </b><small>'.$datos[0]['com_componente'].'</small></h1>
                  <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
      else{ /// Gasto Corriente
        $tabla.=' <h1><b> '.$datos[0]['tipo_adm'].' : <b><small>'.$datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' - '.$datos[0]['tipo'].' '.$datos[0]['act_descripcion'].' '.$datos[0]['abrev'].'</small></h1>
                  <h1><b> SERVICIO : <b><small>'.$datos[0]['com_componente'].'</small></h1>
                  <h1><b>ACTIVIDAD : </b><small>'.$datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'].'</small></h1>';
      }
    }

    return $tabla;
  }

  /*--- GENERAR CÓDIGO CERTIFICACION POA ---*/
  public function generar_certificacion_poa($cpoa_id){
    $get_cpoa=$this->model_certificacion->get_certificacion_poa($cpoa_id);
    $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
    if(count($verificando)==0){ // Creando campo para la distrital
      $data_to_store2 = array(
        'dist_id' => $get_cpoa[0]['dist_id'], /// dist_id
        'g_id' => $this->gestion, /// gestion
        'mod_ope' => 0, 
        'mod_req' => 0,
        'cert_poa' => 0,
      );
      $this->db->insert('conf_modificaciones_distrital', $data_to_store2);
      $mod_id=$this->db->insert_id();
    }

    if($get_cpoa[0]['cpoa_estado']==0){
        $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
        $nro_cpoa=$verificando[0]['cert_poa']+1;
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

        if($this->gestion>2020){
          $codigo='CPOA/'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
        }
        else{
          $codigo='CPOA_'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
        }

        if(count($this->model_certificacion->get_codigo_certpoa($codigo))==0){
            /*---- Update Estado Certificacion POA ----*/
            $update_cpoa= array(
              'cpoa_codigo' => $codigo,
              'cpoa_estado' => 1,
              'fun_id'=>$this->fun_id
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
    }
  }

  /// ACTUALIZA CERTIFICACION POA 
  public function generar_codigo($cpoa_id){
    $get_cpoa=$this->model_certificacion->get_certificacion_poa($cpoa_id);
    if(count($get_cpoa)!=0){
        if($get_cpoa[0]['cpoa_estado']==0){
          $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($get_cpoa[0]['dist_id']);
          $nro_cpoa=$verificando[0]['cert_poa']+1;
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

          $codigo='CPOA/'.$get_cpoa[0]['adm'].'-'.$get_cpoa[0]['abrev'].'-'.$nro_cdep.''.$nro_cpoa;
          
          if(count($this->model_certificacion->get_codigo_certpoa($codigo))==0){
              /*---- Update Estado Certificacion POA ----*/
              $update_cpoa= array(
                'cpoa_codigo' => $codigo,
                'cpoa_estado' => 1,
                'fun_id'=>$this->fun_id
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




    /*-------- GET DATOS OPERACIONES 2021 --------*/
    public function get_actividades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
        
        if($proyecto[0]['tp_id']==1){
          $tabla=$this->mis_productos($proy_id); /// Mis operaciones por Subactividad
        }
        else{
          $presupuesto=$this->model_certificacion->saldo_presupuesto_unidad($proy_id);
          if(($presupuesto[0]['saldo']>0 || $presupuesto[0]['saldo']==0) & count($presupuesto)!=0){
            $tabla=$this->mis_productos($proy_id); /// Mis operaciones por Subactividad
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



  /*------ GET PRODUCTOS -----*/
    public function mis_productos($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// PROYECTO
      $titulo='UNIDAD RESPONSABLE';
      if($proyecto[0]['tp_id']==4){
        $titulo='SUBACTIVIDAD';
      }


      $productos = $this->model_certificacion->list_operaciones_x_subactividad_ppto($proy_id); /// PRODUCTOS
      $tabla='';
      if($this->gestion>2020){ /// 2021
      $tabla='          
          ';
      $tabla.='
      <form >
        <section class="col col-6">
          <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR...." style="width:45%;"/><br>
        </section>
        <table class="table table-bordered" border=1 style="width:100%;" id="datos">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#3276b1">#</th>
              <th style="width:9%;" bgcolor="#3276b1" title="SUB ACTIVIDAD">'.$titulo.'</th>';
              if($proyecto[0]['tp_id']==1){
                $tabla.='<th style="width:10%;" bgcolor="#3276b1" title="COMPONENTE">COMPONENTE</th>';
              }
              $tabla.='
              <th style="width:1%;" bgcolor="#3276b1" title="CÓDIGO">COD. OPE.</th>
              <th style="width:17%;" bgcolor="#3276b1" title="OPERACIÓN">OPERACI&Oacute;N</th>
              <th style="width:17%;" bgcolor="#3276b1" title="RESULTADO">RESULTADO</th>
              <th style="width:3%;" bgcolor="#3276b1" title="MONTO PRESUPUESTO POA">PPTO. POA '.$this->gestion.'</th>
              <th style="width:3%;" bgcolor="#3276b1" title="ITEMS A CERTIFICAR"></th>
              <th style="width:1%;" bgcolor="#3276b1"></th>
            </tr>
          </thead>
          <tbody>
            <tbody>'; 
            $nro=0;
            foreach($productos as $row){
              $nro++;
              $tabla.=
              '<tr>
                <td align=center>'.$nro.'</td>
                <td><b>'.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</b></td>';
                if($proyecto[0]['tp_id']==1){
                  $tabla.='<td>'.$row['com_componente'].'</td>';
                }
                $tabla.='
                <td align=center><span class="badge bg-color-blue txt-color-white">'.$row['prod_cod'].'</span></td>
                <td>'.$row['prod_producto'].'</td>
                <td>'.$row['prod_resultado'].'</td>
                <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                <td align=center><a class="btn btn-primary" href="'.site_url("").'/cert/form_items/'.$row['prod_id'].'" id="myBtn'.$row['prod_id'].'" title="INGRESAR A LISTA DE ITEMS A CERTIFICAR" style="width:100%;"><i class="fa fa-lg fa-fw fa-list-alt"></i> CERTIFICAR ITEMS</a></td>
                <td align=center><img id="load'.$row['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="35" height="35" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
              </tr>';

              $tabla.=' <script>
                          document.getElementById("myBtn'.$row['prod_id'].'").addEventListener("click", function(){
                            this.disabled = true;
                            document.getElementById("load'.$row['prod_id'].'").style.display = "block";
                            document.getElementById("mload").style.display = "block";
                          });
                        </script>';
            }
            $tabla.='
            </tbody>
          </table>
        </form>';
      }
      else{ /// 2020
      $tabla.='
        <table class="table table-bordered" border=1 style="width:100%;">
          <thead>
            <tr>
              <th style="width:1%;" bgcolor="#3276b1">#</th>
              <th style="width:9%;" bgcolor="#3276b1" title="SERVICIO / COMPONENTE">SERVICIO / COMPONENTE </th>
              <th style="width:1%;" bgcolor="#3276b1" title="CÓDIGO">COD. ACT.</th>
              <th style="width:17%;" bgcolor="#3276b1" title="ACTIVIDAD">ACTIVIDAD</th>
              <th style="width:17%;" bgcolor="#3276b1" title="RESULTADO">RESULTADO</th>
              <th style="width:3%;" bgcolor="#3276b1" title="MONTO PRESUPUESTO POA">PPTO. POA</th>
              <th style="width:3%;" bgcolor="#3276b1" title="ITEMS A CERTIFICAR"></th>
              <th style="width:1%;" bgcolor="#3276b1"></th>
            </tr>
          </thead>
          <tbody>
            <tbody>'; 
            $nro=0;
            foreach($productos as $row){
              $nro++;
              $tabla.=
              '<tr bgcolor=#eef3f9>
                <td align=center>'.$nro.'</td>
                <td><b>'.$row['com_componente'].'</b></td>
                <td align=center><span class="badge bg-color-blue txt-color-white">'.$row['prod_cod'].'</span></td>
                <td>'.$row['prod_producto'].'</td>
                <td>'.$row['prod_resultado'].'</td>
                <td align=right>'.number_format($row['monto'], 2, ',', '.').'</td>
                <td align=center><a class="btn btn-primary" href="'.site_url("").'/cert/form_items/'.$row['prod_id'].'" id="myBtn'.$row['prod_id'].'" title="INGRESAR A LISTA DE ITEMS A CERTIFICAR" style="width:100%;"><i class="fa fa-lg fa-fw fa-list-alt"></i> CERTIFICAR ITEMS</a></td>
                <td align=center><img id="load'.$row['prod_id'].'" style="display: none" src="'.base_url().'/assets/img/loading.gif" width="35" height="35" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."></td>
              </tr>';

              $tabla.=' <script>
                          document.getElementById("myBtn'.$row['prod_id'].'").addEventListener("click", function(){
                            this.disabled = true;
                            document.getElementById("load'.$row['prod_id'].'").style.display = "block";
                            document.getElementById("mload").style.display = "block";
                          });
                        </script>';
            }
            $tabla.='
            </tbody>
          </table>';
      }

      return $tabla;
    }

  //// CERTIFICACION POA POR SUBACTIVIDAD

  /*------ SOLICITAR CERTIFICACION POA  -------*/
  public function solicitar_certpoa($com_id){
    $componente = $this->model_componente->get_componente($com_id);
    if(count($componente)!=0){
      $data['menu'] = $this->certificacionpoa->menu_segpoa($com_id);
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($componente[0]['proy_id']);
      $titulo=$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].' / '.$componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion'];
      $data['loading']='<div id="loading" style="display:none;" style="width:20%;"><section id="widget-grid" class="well" align="center"><img src="'.base_url().'/assets/img/cargando-loading-039.gif" width="40%" height="30%"></section></div>';
      $data['select_ope']=$this->certificacionpoa->select_mis_productos($com_id,$titulo); /// Seleccion de productos
      $data['loading_form']='<div id="load" style="display: none" align="center">
                              <br><img  src="'.base_url().'/assets/img_v1.1/preloader.gif" width="100"><br><b>GENERANDO SOLICITUD DE CERTIFICACI&Oacute;N POA ....</b>
                            </div>';

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

      $tabla=$this->formulario_certpoa($prod_id);
      $result = array(
        'respuesta' => 'correcto',
        'requerimientos'=>$tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*------ FORMULARIO CERTIFICACION POA  -------*/
  public function formulario_certpoa($prod_id){
     /// para listas mayores a 500
    $tabla='';
    $tabla.='
        <input type="hidden" name="tot" id="tot" value="0">
        <input type="hidden" name="tot_temp" id="tot_temp" value="0">
        <input type="hidden" name="prod_id" id="prod_id" value="'.$prod_id.'">
        <fieldset>
          <div class="alert alert-success alert-block">
            <h4 class="alert-heading">SOLICITAR CERTIFICACIÓN POA!</h4>
            Seleccione los siguientes Requerimientos Disponibles para su solicitud POA
          </div>
          <section class="col col-6">
            <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador de Requerimientos...."/><br>
          </section>
          <div class="row" align="center">
            <div class="table-responsive" align="center">
              <center>
                '.$this->certificacionpoa->list_requerimientos_prelista($prod_id).'
              </center>
            </div>
          </div>
        </fieldset>
        ';
    return  $tabla;
  }

  /*------ VALIDA SOLICITUD DE CERTIFICACION POA (2020 - 2021) ------*/
  public function valida_solicitud(){
    if ($this->input->post()) {
      $post = $this->input->post();
      $prod_id = $this->security->xss_clean($post['prod_id']);
      $total = $this->security->xss_clean($post['tot']);
      $producto=$this->model_producto->get_producto_id($post['prod_id']);

      /*---- insertando solicitud ---*/
      $data_to_store = array( 
        'com_id' => $producto[0]['com_id'],
        'prod_id' => $prod_id,
        'g_id' => $this->gestion,
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
              for ($i=1; $i <=12 ; $i++) {
                if(!empty($_POST["ipm".$i."".$_POST["ins"][$como]])){
                  /*-------- GUARDANDO ITEMS PROGRAMADOS -------*/
                  $data_to_store = array(
                    'req_id' => $req_id,
                    'tins_id' => $_POST["ipm".$i."".$_POST["ins"][$como]],
                  );
                  $this->db->insert('temporalidad_req_solicitado', $data_to_store);
                  /*--------------------------------------------*/
                } 
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
    $solicitud = $this->model_certificacion->get_solicitud_cpoa($sol_id);
    
    if(count($solicitud)!=0){
      $data['menu'] = $this->certificacionpoa->menu_segpoa($solicitud[0]['com_id']);
      $data['titulo']='<h1><b>SOLICITUD DE CERTIFICACIÓN POA</b></h1>';
      $data['cuerpo']='
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <iframe id="ipdf" width="100%"  height="1000px;" src="'.site_url().'/reporte_solicitud_poa/'.$sol_id.'"></iframe>
        </article>';

      $this->load->view('admin/ejecucion/certpoa_unidad/ver_solicitudpoa', $data);
    }
    else{
      echo "Error !!!";
    }
  }


  /*------ FORMULARIO SOLICITUD CERTIFICACION POA  -------*/
  public function reporte_solicitud_certpoa($sol_id){
    $data['solicitud'] = $this->model_certificacion->get_solicitud_cpoa($sol_id);
    $data['componente'] = $this->model_componente->get_componente($data['solicitud'][0]['com_id']);
    $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['componente'][0]['proy_id']);
    $data['producto']=$this->model_producto->get_producto_id($data['solicitud'][0]['prod_id']);

    $data['formulario']='';
    $this->load->view('admin/ejecucion/certpoa_unidad/reporte_solicitud_cpoa', $data);
   /* $tabla='Hola mundo';


    return $tabla;*/
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