<?php
class Cmod_presupuestario extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4'); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('mantenimiento/model_entidad_tras');
            $this->load->model('mantenimiento/model_partidas');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->adm = $this->session->userData('adm');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->dep_id = $this->session->userData('dep_id'); /// Dep ID
            $this->load->library('menu');
            $this->menu->const_menu(3);
            
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }

  /*--- CITE MOD. PRESUPUESTARIA 2020-2021 ---*/
  public function lista_mod_ppto(){
    $data['menu'] = $this->menu->genera_menu();
    $data['list_dep']=$this->model_proyecto->list_departamentos();
    $data['lista_cites']=$this->lista_cites_modificados(); /// List.
    $data['list_dep']=$this->model_proyecto->list_departamentos();
  
    $this->load->view('admin/modificacion/presupuesto/list_modificaciones', $data);
  }


  /*---- Lista de Proyectos de Inversion (2020) -----*/
    public function lista_cites_modificados(){
      $cites=$this->model_modrequerimiento->list_cites_mod_presupuestaria();
      $tabla='';

      $tabla.='
        <input name="base" type="hidden" value="'.base_url().'">
        <table id="dt_basic" class="table table-bordered" style="width:100%;">
            <thead>
              <tr>
                <th style="width:1%;" bgcolor="#474544" title="#">#</th>
                <th style="width:10%;" bgcolor="#474544" title="DA">DA</th>
                <th style="width:10%;" bgcolor="#474544" title="UE">UE</th>
                <th style="width:10%;" bgcolor="#474544" title="RESOLUCION">RESOLUCI&Oacute;N</th>
                <th style="width:5%;" bgcolor="#474544" title=""></th>
                <th style="width:5%;" bgcolor="#474544" title=""></th>
                <th style="width:5%;" bgcolor="#474544" title=""></th>
                <th style="width:5%;" bgcolor="#474544" title=""></th>
              </tr>
            </thead>
            <tbody>';
            $nro=0;
            foreach($cites as $row){
              $tabla.='<tr>
              <td style="height:30px;" align=center>'.$nro.'</td>
              <td>'.$row['dep_cod'].' .-'.strtoupper($row['dep_departamento']).'</td>
              <td>'.$row['dist_cod'].' .-'.strtoupper($row['dist_distrital']).'</td>
              <td>'.strtoupper($row['resolucion']).'</td>
              <td align=center>';
                if($this->adm==1){
                  $tabla.='
                  <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR MODIFICACIÓN PRESUPUESTARIA"  name="'.$row['mp_id'].'" >
                    <img src="'.base_url().'assets/img/delete.png" width="30" height="30"/>
                  </a>';
                }
                $tabla.='
              </td>
              <td align=center>';
                if($this->adm==1){
                  $tabla.='<a href="'.site_url("").'/mod_ppto/ver_partidas_mod/'.$row['mp_id'].'" title="VER PARTIDAS MODIFICADOS" class="btn btn-default"><img src="'.base_url().'assets/ifinal/select.png" WIDTH="30" HEIGHT="30"/></a>';
                }
              $tabla.='
              </td>
              <td align=center><a href="javascript:abreVentana(\''.site_url("").'/mod_ppto/rep_mod_ppto/'.$row['mp_id'].'\');" title="REPORTE MODIFICACIÓN PRESUPUESTARIA" class="btn btn-default"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="30" HEIGHT="30"/></a></td>
              <td align=center>
                <a href="#" data-toggle="modal" data-target="#modal_regional" class="btn btn-default" onclick="generar_modppto_regional('.$row['mp_id'].');" >CLASIFICAR POR DISTRITAL</a>
              </td>'; 
            }
            $tabla.='
            </tbody>
          </table>';
      
      return $tabla;
    }



  /*---- Obtiene Datos la solicitud de Certificacion POA ---*/
  public function get_datos_modificacion_presupuestaria(){
    if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $mp_id = $this->security->xss_clean($post['mp_id']);

        $modificacion=$this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id);


        if(count($modificacion)!=0){
          $distritales=$this->model_proyecto->list_distritales($modificacion[0]['dep_id']);
          $dist='';
          $dist.='
          <select class="form-control" id="uejec_id" name="uejec_id" title="Seleccione Unidad Ejecutora">
            <option value="">No seleccionado</option>';
              foreach($distritales as $row){
                if($modificacion[0]['dist_id']==$row['dist_id']){
                  $dist.='<option value="'.$row['dist_id'].'" selected>'.$row['dist_distrital'].'</option>';
                }
                else{
                  $dist.='<option value="'.$row['dist_id'].'">'.$row['dist_distrital'].'</option>';
                }
                
              }
            $dist.='
          </select>';
          $result = array(
            'respuesta' => 'correcto',
            'modificacion' => $modificacion,
            'distritales' => $dist
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



  /*--- LISTA DE PARTIDAS MODIFICADAS 2020-2021 ---*/
  public function partidas_modificadas($mp_id){
    $data['menu'] = $this->menu->genera_menu();
    $data['cite'] = $this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id);
    if(count($data['cite'])!=0){
      $data['disminuir']=$this->mis_partidas($mp_id,1);
      $data['incrementar']=$this->mis_partidas($mp_id,0);
      
      $this->load->view('admin/modificacion/presupuesto/lista_partidas_modificadas', $data);
    }
    else{
      echo "Error !!!";
    }
  }


  /*--- MIS PARTIDAS (DISMINUIDAS-INCREMENTADAS) ---*/
  public function mis_partidas($mp_id,$tp){
    /// tp 0 (Incrementar), tp 1 (Redduccion)
    $tabla='';
    $cite = $this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id);
    $partidas=$this->model_modrequerimiento->list_tipo_partidas_modificadas($mp_id,$tp);
    $signo='';
    $tab='dt_basic';
    if($tp==1){
      $signo='-';
      $tab='dt_basic1';
    }


    $tabla.='<table id='.$tab.' class="table table-bordered" style="width:100%;">
                <thead>
                    <tr style="height:45px;">
                        <th style="width:1%;">DA</th>
                        <th style="width:1%;">UE</th>
                        <th style="width:1%;">PROG.</th>
                        <th style="width:1%;">ACT.</th>
                        <th style="width:13%;">ACTIVIDAD</th>
                        <th style="width:2%;">PARTIDA</th>
                        <th style="width:12%;">DESCRIPCI&Oacute;N PARTIDA</th>
                        <th style="width:5%;">PPTO ACTUAL</th>
                        <th style="width:5%;">IMPORTE</th>
                        <th style="width:5%;">PPTO A MODIFICAR</th>
                        <th style="width:3%;"></th>
                        <th style="width:3%;">REP. POA</th>
                    </tr>
                </thead>
                <tbody>';
                foreach ($partidas as $row){
                  $partida_actual=$this->model_ptto_sigep->get_ppto_partida_asig_unidad($row['dep_id'],$row['aper_id'],$row['par_id']);
                  $presupuesto_actual_registrado=0;
                  if(count($partida_actual)!=0){
                    $presupuesto_actual_registrado=$partida_actual[0]['ppto_asignado'];
                  }

                  if($tp==0){
                    $monto_final_preaprobado=($presupuesto_actual_registrado+$row['importe']);
                  }
                  else{
                    $monto_final_preaprobado=($presupuesto_actual_registrado-$row['importe']);
                  }

                  $tabla.='
                  <tr title="'.$row['mpa_id'].'">
                      <td align=center>'.strtoupper($row['dep_cod']).'</td>
                      <td align=center>'.strtoupper($row['dist_cod']).'</td>
                      <td align=center>'.$row['aper_programa'].'</td>
                      <td align=center>'.$row['aper_actividad'].'</td>
                      <td align=left bgcolor="#eaeff3">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                      <td align=center>'.$row['par_codigo'].'</td>
                      <td align=left>'.strtoupper($row['par_nombre']).'</td>';
                        if($row['activo_mpa']==0){
                          $tabla.='
                          <td align=right bgcolor="#eaeff3"><b>'.number_format($presupuesto_actual_registrado, 2, ',', '.').'</b></td>
                          <td align=right><b>'.$signo.''.number_format($row['importe'], 2, ',', '.').'</b></td>
                          <td align=right><b>'.number_format($monto_final_preaprobado, 2, ',', '.').'</b></td>
                          <td align=center>
                            <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ACTUALIZAR MODIFICACI&Oacute;N"  name="'.$row['mpa_id'].'" id="'.$row['par_codigo'].'">
                              <img src="'.base_url().'assets/ifinal/ok1.png" width="30" height="30"/>
                            </a>
                          </td>';
                        }
                        else{
                          $tabla.='
                          <td align=right bgcolor="#eaeff3"><b>'.number_format($presupuesto_actual_registrado, 2, ',', '.').'</b></td>
                          <td align=right><b><font color=blue>'.$signo.''.number_format($row['importe'], 2, ',', '.').'</font></b></td>
                          <td align=right><b></b></td>
                          <td align=center><b><font color=blue>ACTUALIZADO</font></b></td> ';
                        }
                        
                        $tabla.='<td>';
                        if($tp==1){
                          $tabla.='<a href="javascript:abreVentana(\''.site_url("").'/proy/ptto_consolidado_comparativo/'.$row['proy_id'].'\');"  title="REPORTE CONSOLIDADO COMPARATIVO PTTO POR PARTIDAS"><img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="25" HEIGHT="25"/></a>';
                        }
                        else{
                          $tabla.='';
                        }
                      $tabla.='</td>

                      
                  </tr>';
                }
                $tabla.='
                </tbody>
            </table>';

    return $tabla;
  }


    /*--- SUBIR ARCHIVO SIGEP (MODIFICACION PRESUPUESTARIA) ---*/
    function importar_archivo_modpresupuestario(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $ue_id = $this->security->xss_clean($post['ue_id']);
          $rd = $this->security->xss_clean($post['rd']);
          $tp_id = $this->security->xss_clean($post['tp_id']);

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
              if($this->verificando_mod_ppto($archivotmp)!=0){
                /*-------------------- Guardando Datos ------------------*/
                  $query=$this->db->query('set datestyle to DMY');
                  $data_to_store = array( 
                    'dist_id' => $ue_id,
                    'g_id' => $this->gestion,
                    'resolucion' => $rd,
                    'fun_id' => $this->fun_id,
                  );
                  $this->db->insert('modificacion_presupuestaria', $data_to_store);
                  $mp_id=$this->db->insert_id();
                  /*-------------------------------------------------------*/

                  if($this->add_modificacion_presupuestaria($archivotmp,$mp_id,$tp_id)!=0){
                    $this->session->set_flashdata('success','ARCHIVO CARGADO CORRECTAMENTE ....');
                    redirect(site_url("").'/mod_ppto/list_mod_ppto');
                  }
                  else{
                    echo "No existe Archivo de importacion1 ..";
                  }
              }
              else{
                echo "No existe Archivo de importacion2 ..";
              }
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


    /*--- AGREGANDO MODIFICACIONES PRESUPUESTARIAS ---*/
    public function add_modificacion_presupuestaria($archivotmp,$mp_id){  
        $i=0;
        $nro=0;
        $lineas = file($archivotmp);
        
        if($tp_id==1){ /// Proyecto de Inversion
          echo "trabajando para pi";
        }
        else{ /// Gasto Corriente
          foreach ($lineas as $linea_num => $linea){ 
            if($i != 0){ 
                $datos = explode(";",$linea);
                if(count($datos)==6){
                    $prog=trim($datos[0]); /// prog
                    $act=trim($datos[1]); /// act
                    $cod_part=trim($datos[2]); /// partida
                    $importe=(float)$datos[3]; /// Monto
                    $tp=trim($datos[4]); /// tp : 1 (reduccion), 0 (Adicion)
                    $activo=trim($datos[5]); /// tp : 1 (activo), 0 (No activo)

                    if(strlen($prog)==3 & strlen($act)==3 & $importe!=0 & is_numeric($cod_part)){
                      $aper=$this->model_ptto_sigep->get_apertura($prog,'00',$act);
                      //$aper=$this->model_ptto_sigep->get_apertura($prog,'0098',$act);
                      //$aper=$this->model_ptto_sigep->get_apertura($prog,$proy,$act);
                      if(count($aper)!=0){
                          $partida = $this->model_insumo->get_partida_codigo($cod_part); //// DATOS DE LA PARTIDA
                          $par_id=0;
                          if(count($partida)!=0){
                            $par_id=$partida[0]['par_id'];
                          }

                          $query=$this->db->query('set datestyle to DMY');
                          $data_to_store = array( 
                            'mp_id' => $mp_id,
                            'aper_id' => $aper[0]['aper_id'],
                            'aper_programa' => $prog,
                            'aper_proyecto' => '00',
                            'aper_actividad' => $act,
                            'par_id' => $par_id,
                            'partida' => $cod_part,
                            'importe' => $importe,
                            'tipo' => $tp,
                            'activo_mpa' => $activo,
                          );
                          $this->db->insert('partidas_presupuestarias_modificadas', $data_to_store);
                          $mpa_id=$this->db->insert_id();

                      $nro++;
                      }
                    }
                }
            }
            $i++;
          }
        }



        return $nro;
     }

    /*--- Verificando nro de Modificaciones ---*/
    public function verificando_mod_ppto($archivotmp){  
        $i=0;
        $nro=0;
        $lineas = file($archivotmp);
        foreach ($lineas as $linea_num => $linea){ 
          if($i != 0){ 
              $datos = explode(";",$linea);
              if(count($datos)==6){
                $nro++;
              }
          }

          $i++;
        }
        return $nro;
    }



    /*------------------- Valida Cite Para Modificacion ---------------*/
    public function valida_cite_modificacion(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id 
          $inst_id = $this->security->xss_clean($post['inst_id']); /// inst_id
          $cite = $this->security->xss_clean($post['cite']); /// Cite
          $fecha = $this->security->xss_clean($post['fm']); /// Fecha
          $com_id = $this->security->xss_clean($post['com_id']); /// Com id
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);

          if($proy_id!='' & count($proyecto)!=0){
            /*------ GUARDANDO CITE MODIFICADO (con estado inactivo)-------*/
            $data_to_store = array(
              'insc_cite' => strtoupper($cite),
              'insc_fecha' => $fecha,
              'fun_id' => $this->fun_id,
              'proy_id' => $proy_id,
              'insc_estado' => 0,  //// 1: activo, 0: Inactivo
              'g_id' => $this->gestion,
              'com_id' => $com_id,
            );
            $this->db->insert('_insumo_mod_cite',$data_to_store);
            $insc_id=$this->db->insert_id();
            /*---------------------------------------------------------------*/

            if(count($this->model_modificacion->get_cite_insumo($insc_id))==1){
              redirect(site_url("").'/mod/mod_requerimiento/'.$insc_id.'/'.$inst_id.'');
            }
            else{
              $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
              redirect(site_url("").'/mod/procesos/'.$proy_id.'');
            }
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
            redirect(site_url("").'/mod/procesos/'.$proy_id.'');
          }
          
      } else {
          show_404();
      }
    }


    /*------ ACTUALIZA MODIFICACION PARTIDA ------*/
    function update_mod_ppto(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $mpa_id = $this->security->xss_clean($post['mpa_id']); // mpa id
          $get_partida=$this->model_modrequerimiento->get_partida_mppto($mpa_id); // ger partida a ser modificado
          $verif_cite_modificacion=$this->model_modrequerimiento->verif_get_cite_modifcado($get_partida[0]['mp_id'],$get_partida[0]['proy_id']); // Verificando si ya existe registro por el moduflo de modificaciones
          
          if(count($verif_cite_modificacion)==0){ /// Registrando Cite Presupuesto (Modificacion poa)
            /*--------- GUARDANDO CITE PRESUPUESTO ---------*/
            $data_to_store = array(
              'proy_id' => $get_partida[0]['proy_id'],
              'cppto_cite' => strtoupper($get_partida[0]['resolucion']),
              'mp_id' => $get_partida[0]['mp_id'],
              'cppto_fecha' => date("d/m/Y H:i:s"),
              'fun_id' => $this->fun_id,
              );
            $this->db->insert('ppto_cite',$data_to_store);
            $cppto_id=$this->db->insert_id();
            /*----------------------------------------------*/
          }
          else{
            $cppto_id=$verif_cite_modificacion[0]['cppto_id'];
          }

          $partida_actual=$this->model_ptto_sigep->get_partida_asignado_unidad($get_partida[0]['aper_id'],$get_partida[0]['par_id']);


          if(count($partida_actual)!=0){ /// Existe partida registrado
              if($get_partida[0]['tipo']==0){
                $signo='';
                $monto_final=($partida_actual[0]['importe']+$get_partida[0]['importe']);
              }
              else{
                $signo='-';
                $monto_final=($partida_actual[0]['importe']-$get_partida[0]['importe']);
              }


              $data_to_store2 = array(
                'cppto_id' => $cppto_id,
                'sp_id' => $partida_actual[0]['sp_id'],
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'ppto_ini' => $partida_actual[0]['importe'], 
                'monto_dif' => $signo.''.$get_partida[0]['importe'],
                'ppto_final' => $monto_final,
              );
              $this->db->insert('ppto_mod',$data_to_store2);
              /*----------------------------------------*/

              /*--------- Update ppto Sigep ----------*/
              $update_ppto= array(
                'importe' => $monto_final,
                'estado' => 2,
                'fun_id' => $this->fun_id
              );
              $this->db->where('sp_id', $partida_actual[0]['sp_id']);
              $this->db->update('ptto_partidas_sigep', $this->security->xss_clean($update_ppto));
              /*----------------------------------------*/

          }
          else{ /// Registrar Partida

            /*-------- Insert ppto_adicionado ----------*/
            $data_to_store = array(
              'aper_id' => $get_partida[0]['aper_id'],
              'aper_programa' => $get_partida[0]['aper_programa'],
              'aper_proyecto' => $get_partida[0]['aper_proyecto'],
              'aper_actividad' => $get_partida[0]['aper_actividad'],
              'par_id' => $get_partida[0]['par_id'],
              'partida' => $get_partida[0]['partida'],
              'importe' => $get_partida[0]['importe'],
              'g_id' => $this->gestion,
              'estado' => 1,
              'fun_id' => $this->fun_id,
            );
            $this->db->insert('ptto_partidas_sigep',$data_to_store);
            $sp_id=$this->db->insert_id();
            /*------------------------------------------*/

            /*-------- Insert ppto_modifcado ----------*/
              $data_to_store2 = array(
                'cppto_id' => $cppto_id,
                'sp_id' => $sp_id,
                'ppto' => $get_partida[0]['importe'],
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              );
              $this->db->insert('ppto_add',$data_to_store2);
              $appto_id=$this->db->insert_id();
            /*----------------------------------------*/
          }

          /*--------- Update ppto A subir ----------*/
          $update_mppto= array(
            'activo_mpa' => 1
          );
          $this->db->where('mpa_id', $mpa_id);
          $this->db->update('partidas_presupuestarias_modificadas', $this->security->xss_clean($update_mppto));
          /*----------------------------------------*/


          $result = array(
              'respuesta' => 'correcto'
          );

        echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }




    /*------ ELIMINAR MODIFICACION PRESUPUESTARIA ------*/
    function delete_modificacion_presupuestaria(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $mp_id = $this->security->xss_clean($post['mp_id']); // mp id

          /*-------- DELETE PARTIDAS MODIFICADAS --------*/  
          $this->db->where('mp_id', $mp_id);
          $this->db->delete('partidas_presupuestarias_modificadas');
          /*------------------------------------------*/

          /*-------- DELETE PARTIDAS MODIFICADAS --------*/  
          $this->db->where('mp_id', $mp_id);
          $this->db->delete('modificacion_presupuestaria');
          /*------------------------------------------*/

          if(count($this->model_modrequerimiento->list_partidas_modificadas($mp_id))==0){
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



    /*------- REPORTE MODIFICACION POR PARTIDAS (2020) Consolidado------*/
    public function reporte_mod_ppto($mp_id){
        $data['mes'] = $this->mes_nombre();
        $data['cite'] = $this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id); /// DATOS CITE MOD PPTO
        
        if(count($data['cite'])!=0){
          $data['cabecera']='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                            <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td width=15%; text-align:center;>
                               
                              </td>
                              <td width=65%; align=left>
                                <table>
                                    <tr>
                                        <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>DIR. ADM.</b></td>
                                        <td style="width:90%;">: '.strtoupper($data['cite'][0]['dep_departamento']).'</td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>UNI. EJEC.</b></td>
                                        <td style="width:90%;">: '.strtoupper($data['cite'][0]['dist_distrital']).'</td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>No. y FECHA DISPOSICI&Oacute;N</b></td>
                                        <td style="width:90%;">: '.strtoupper($data['cite'][0]['resolucion']).'</td>
                                    </tr>
                                </table>
                              </td>
                              <td width=20%; align=left style="font-size: 7.5px;">
                              </td>
                            </tr>
                        </table>';
          $data['reduccion']=$this->mis_partidas_modificadas($mp_id,1,0);
          $data['incremento']=$this->mis_partidas_modificadas($mp_id,0,0);
          $this->load->view('admin/modificacion/presupuesto/reporte_modificacion_presupuesto', $data);
        }
        else{
            echo "<b>ERROR !!!!!</b>";
        }
    }


    /*----- REPORTE - MODIFICACION DE PARTIDAS -----*/
    public function mis_partidas_modificadas($mp_id,$tp,$dist_id){
      $cite = $this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id);
      if($dist_id==0){
        $partidas=$this->model_modrequerimiento->list_tipo_partidas_modificadas($mp_id,$tp);
      }
      else{
        $partidas=$this->model_modrequerimiento->list_tipo_partidas_modificadas_clasificado_distrital($mp_id,$tp,$dist_id);
      }
      
      $signo='';
      $titulo='INCREMENTADAS';
      if($tp==1){
        $signo='-';
        $titulo='DISMINUIDAS';
      }

        $tabla='';
        $tabla.='
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                    <tr style="font-size: 8px;">
                      <th style="height:15px;" colspan=10>PARTIDAS PRESUPUESTARIAS A SER '.$titulo.'</th>
                    </tr>
                    <tr style="font-size: 8px;" align=center>
                      <th style="width:3%;height:11px;">ENT.</th>
                      <th style="width:3%;">DA</th>
                      <th style="width:3%;">UE</th>
                      <th style="width:3%;">PROG.</th>
                      <th style="width:2.5%;">ACT.</th>
                      <th style="width:25%;">ACTIVIDAD</th>
                      <th style="width:5%;">PARTIDA</th>
                      <th style="width:25%;">DESCRIPCI&Oacute;N PARTIDA</th>
                      <th style="width:10%;">IMPORTE</th>
                      <th style="width:10%;"></th>
                    </tr>
                </thead>
                <tbody>';
                $nro=0; $total=0;
                  foreach ($partidas as $row){ 
                    
                    if($row['activo_mpa']==0){
                      $color='';
                      $tit='NO ACTUALIZADO';
                    }
                    else{
                      $color='bgcolor="#ebfdeb"';
                      $tit='ACTUALIZADO';
                    }

                   /* $tit='NO ACTUALIZADO';
                    $color='';
                    if($row['dist_id']==13){
                      $color='bgcolor="#e2f3da"';
                    }*/

                    $nro++;
                    $total=$total+$row['importe'];

                    $tabla.=
                    '<tr style="font-size: 6.9px;" '.$color.'>
                        <td style="width: 3%;text-align: center">417</td>
                        <td style="width: 3%;height:10px; text-align: center">'.strtoupper($row['dep_cod']).'</td>
                        <td style="width: 3%; text-align: center;">'.strtoupper($row['dist_cod']).'</td>
                        <td style="width: 3%; text-align: center;">'.strtoupper($row['aper_programa']).'</td>
                        <td style="width: 3%; text-align: center;">'.strtoupper($row['aper_actividad']).'</td>
                        <td style="width: 25%; text-align: left;">'.$row['tipo'].' '.$row['act_descripcion'].' '.$row['abrev'].'</td>
                        <td style="width: 5%; text-align: center;">'.$row['par_codigo'].'</td>
                        <td style="width: 25%; text-align: left;">'.$row['par_nombre'].'</td>
                        <td style="width: 10%; text-align: right;">'.$signo.''.number_format($row['importe'], 2, ',', '.').'</td>
                        <td style="width: 10%; text-align: left;">'.$tit.'</td>
                    </tr>';
                  }
            $tabla.=
                '</tbody>
                    <tr style="font-size: 7px;" >
                      <td style="width: 50%;height:13px; text-align: right;" colspan=8><b>TOTAL PROGRAMADO</b></td>
                      <td style="width: 9%; text-align: right;"><b>'.number_format($total, 2, ',', '.').'</b></td>
                      <td></td>
                    </tr>
            </table>';
        return $tabla;
    }



    /*-- REPORTE MODIFICACION PPTO Clasificado por Regional --*/
    public function reporte_mod_ppto_clasificado($mp_id,$dist_id){
      $data['mes'] = $this->mes_nombre();
      $data['cite'] = $this->model_modrequerimiento->get_cites_mod_presupuestaria($mp_id); /// DATOS CITE MOD PPTO
      
      if(count($data['cite'])!=0){
        $distrital=$this->model_proyecto->dep_dist($dist_id);
        $data['cabecera']='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
          <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
            <td width=15%; text-align:center;>
             
            </td>
            <td width=65%; align=left>
              <table>
                  <tr>
                      <td colspan="2" style="width:100%; height: 1.2%; font-size: 16pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1%"><b>No. RESOLUCIÓN : </b></td>
                      <td style="width:90%;">: '.strtoupper($data['cite'][0]['resolucion']).'</td>
                  </tr>
                  <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1%"><b>FILTRADO POR : </b></td>
                      <td style="width:90%;">: '.strtoupper($distrital[0]['dist_distrital']).'</td>
                  </tr>
              </table>
            </td>
            <td width=20%; align=left style="font-size: 7.5px;">
            </td>
          </tr>
        </table>';

        $data['reduccion']=$this->mis_partidas_modificadas($mp_id,1,$dist_id);
        $data['incremento']=$this->mis_partidas_modificadas($mp_id,0,$dist_id);
      
        $this->load->view('admin/modificacion/presupuesto/reporte_modificacion_presupuesto', $data);
      }
      else{
          echo "<b>ERROR !!!!!</b>";
      }
    }





    /*----- formulario para ppto por saldo revertidos -----*/
    public function form_ppto_revertido($proy_id){
      /// tp 0: Modificacion POA
      /// tp 1: Modificacion POA (Reversion de saldos)
      $data['menu'] = $this->menu->genera_menu(); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);

      if(count($data['proyecto'])!=0){
        if($data['proyecto'][0]['tp_id']==1){
          $titulo='
          <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$data['proyecto'][0]['proy_sisin'].' - '.$data['proyecto'][0]['proy_nombre'].'</small>';
        }
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $titulo='
          <h1> <b>'.$data['proyecto'][0]['tipo_adm'].' : </b><small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'].'</small>';
        }
        $data['titulo']=$titulo;


        $lista_certpoa=$this->model_certificacion->get_lista_certificacion_poa_unidad_no_revertidos($proy_id); /// lista certificacion para reversion
        $tabla='';
        $tabla.='
            <article class="col-sm-12 col-md-12 col-lg-5">
              <div id="cuerpo1">
                <input type="hidden" name="base" id="base" value="'.base_url().'">
                <div class="jarviswidget" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                  <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>Reversión de Presupuesto POA</h2>
                  </header>
                  <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">

                      <form class="form-horizontal" id="formulario">
                        <input type="hidden" id="proy_id" name="proy_id" value="'.$proy_id.'">
                        <fieldset>
                          <legend>Seleccione la siguiente informacion :</legend>
                          
                          <div class="form-group">
                            <label class="col-md-3 control-label"><b>Nota CITE</b></label>
                            <div class="col-md-9">
                              <input type="text" placeholder= "XXX" class="form-control" id="cite" name="cite">
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-3 control-label"><b>Seleccione Certificacion POA</b></label>
                            <div class="col-md-9">
                              <select class="select2" id="certpoa" name="certpoa">
                                <option value="0">Seleccione Certificacion POA...</option>';
                                foreach ($lista_certpoa as $row){
                                  $tabla.='<option value="'.$row['cpoa_id'].'"><b>'.$row['cpoa_codigo'].'</b></option>';
                                }
                                $tabla.='
                              </select>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-md-3 control-label"><b>Certificación Presupuestaria</b></label>
                            <div class="col-md-9">
                              <input type="text"  class="form-control" data-mask="999999-999999-'.$this->gestion.'" data-mask-placeholder= "X" id="certpptos" name="certpptos">
                                <p class="note">
                                  Datos a registrar XXXXXX-XXXXXX-'.$this->gestion.'
                                </p>
                            </div>
                          </div>
                        </fieldset>

                        <div class="form-actions" id="boton">
                          <div class="row">
                            <div class="col-md-12">
                              <a href="'.base_url().'index.php/mod/list_top" title="SALIR" class="btn btn-default">&nbsp;Salir</a>
                              <button class="btn btn-primary" type="button" name="generar_datos" id="generar_datos">
                                <i class="fa fa-save"></i>
                                Generar Formulario de Reversión
                              </button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </article>

            <article class="col-sm-12 col-md-12 col-lg-7">
              <div id="form_certpoa"></div>
            </article>';

          $data['tabla']=$tabla;

        $this->load->view('admin/modificacion/reversion_ppto/form_reversion_saldos', $data); 
      }
      else{
        redirect('mod/list_top');
      }

    }


    /*------ APERTURAR CERTIFICACION POA ------*/
    function aperturar_cert_poa(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']); // proy_id
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); // datos del proyecto

          $cite = $this->security->xss_clean($post['cite']); // cite
          $cpoa_id = $this->security->xss_clean($post['cpoa_id']); // cpoa_id
          $certificacion=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id); /// Datos Generales de la Certificacion POA
          $partidas_certificadas=$this->model_certificacion->get_lista_detalle_partidas_certificados_cpoa($cpoa_id);

          $cppto = $this->security->xss_clean($post['cppto']); // certificacion presupuestaria

          $cuerpo1='';
          $cuerpo1.='
          <div class="jarviswidget" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
            <header>
              <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
              <h2><b>Certificación POA</b></h2>
            </header>
            <div>
              <div class="jarviswidget-editbox">
              </div>
              <div class="widget-body">

                <div align="right"><a href="'.base_url().'index.php/mod/add_ppto_reversion/'.$proy_id.'" title="NUEVA REVERSIÓN" class="btn btn-default"><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;Generar Nueva Reversión</a></div>
                <hr>
                <iframe width="100%"  height="700px;" src="'.base_url().'index.php/cert/rep_cert_poa/'.$cpoa_id.'"></iframe>
              </div>
            </div>
          </div>';


          $tabla='';
          $tabla.='
          <div class="jarviswidget" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                  <h2><b>Formulario de Reversión por Partidas</b></h2>
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body">

                    <form action="'.site_url("").'/modificaciones/cmod_presupuestario/valida_reversion_saldos" id="miFormulario" name="miFormulario" class="smart-form" method="post">
                    <input type="hidden" name="base" id="base" value="'.base_url().'">
                    <input type="hidden" name="cite" id="cite" value="'.$cite.'">
                    <input type="hidden" name="cpoa_id" id="cpoa_id" value="'.$cpoa_id.'">
                    <input type="hidden" name="cert_ppto" id="cert_ppto" value="'.$cppto.'">
                    <div class="alert alert-block alert-success">
                      <a class="close" data-dismiss="alert" href="#">×</a>
                      <h4 class="alert-heading"><i class="fa fa-check-square-o"></i> CERT. POA N° .- '.$certificacion[0]['cpoa_codigo'].'</h4>
                    </div>
                      
                      <header>
                      Registre los saldo a ser revertidos por partida
                      </header>';
                      $nro=0;
                      foreach ($partidas_certificadas as $row){
                        $nro++;
                        $tabla.='
                        <fieldset>
                          <input type="hidden" name="par_id[]" value="'.$row['par_id'].'">
                          <b style="font-size: 15px;font-family: Arial;">PARTIDA : '.$row['par_codigo'].' '.strtoupper($row['par_nombre']).'</b>
                          
                          <div class="row">
                            <section class="col col-3">
                              <label class="label" style="font-size: 11px;font-family: Arial;color:blue"><b>PPTO. CERTIFICADO</b></label>
                              <label class="input"> <i class="icon-append fa fa-money"></i>
                                <input type="hidden" name="ppto_cert[]" id="ppto_cert'.$nro.'" value="'.$row['ppto_partida_certificado'].'">
                                <input type="text" value="'.number_format($row['ppto_partida_certificado'], 2, ',', '.').'" disabled="true">
                              </label>
                            </section>
                            <section class="col col-3">
                              <label class="label" style="font-size: 11px;font-family: Arial;color:green;"><b>SALDO A REVERTIR</b></label>
                              <label class="input"> <i class="icon-append fa fa-money"></i>
                                <input type="number" name="saldo[]" id="saldo'.$nro.'" value="0" onkeyup="verif_ppto_cert('.$nro.',this.value,'.$row['ppto_partida_certificado'].');">
                              </label>
                            </section>
                            <section class="col col-3">
                              <div id="verif'.$nro.'"></div>
                            </section>
                          </div>
                        </fieldset>';
                      }
                    $tabla.='
                      <input type="hidden" name="total_partidas" id="total_partidas" value="'.count($partidas_certificadas).'">
                      <input type="hidden" name="suma" id="suma" value="0">
                      <div id="buton_save" style="display:none;">
                        <footer>
                          <input type="button" value="GENERAR REVERSIÓN DE SALDOS" id="btsubmit" class="btn btn-success" onclick="valida_envia()">
                          <button type="button" class="btn btn-default">
                            <b>SALIR</b>
                          </button>
                        </footer>
                      </div>
                    </form>
                  </div>
                </div>
              </div>';

          $result = array(
            'respuesta' => 'correcto',
            'cuerpo1' => $cuerpo1,
            'datos' => $tabla
          );
          
          echo json_encode($result);

      } else {
          echo 'DATOS ERRONEOS';
      }
    }


    /*----- formulario reporte de reversion -----*/
    public function form_reporte_revertido($cppto_id){
      $data['menu'] = $this->menu->genera_menu(); //// genera menu
      $data['cite']=$this->model_ptto_sigep->get_cite_techo($cppto_id);
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);

      if(count($data['cite'])!=0){
        if($data['proyecto'][0]['tp_id']==1){
          $titulo='
          <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$data['proyecto'][0]['proy_sisin'].' - '.$data['proyecto'][0]['proy_nombre'].'</small>';
        }
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);
          $titulo='
          <h1> <b>'.$data['proyecto'][0]['tipo_adm'].' : </b><small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'].'</small>';
        }
        $data['titulo']=$titulo;

        $data['tabla']='

          <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6">
              <div class="well">

              <ul class="demo-btns text-left">
                <li>
                  <a href="'.base_url().'index.php/mod/add_ppto_reversion/'.$data['cite'][0]['proy_id'].'" class="btn btn-default" target=_black rel="tooltip" data-placement="top" data-original-title="<h1><b>GENERAR NUEVA REVERSION</b></h1>" data-html="true"><img src="'.base_url().'assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="25"/>&nbsp;<b>GENERAR NUEVA REVERSION DE SALDOS</b></a>
                </li>
                <li>
                  <a href="'.base_url().'index.php/mod/form5/'.$data['cite'][0]['proy_id'].'" class="btn btn-default" target=_black rel="tooltip" data-placement="top" data-original-title="<h1><b>REGISTRAR DATOS CITE</b></h1>" data-html="true"><img src="'.base_url().'assets/Iconos/application_form_add.png" WIDTH="25" HEIGHT="25"/>&nbsp;<b>REGISTRAR MODIFICACION POA (REVERSION)</b></a>
                </li>
              </ul>
              </div>
            </div>
          </div>
  
        <iframe id="ipdf" width="100%"  height="1000px;" src="'.base_url().'index.php/mod/rep_mod_techo/'.$cppto_id.'"></iframe>';

        $this->load->view('admin/modificacion/reversion_ppto/form_reversion_saldos', $data); 
      }
      else{
        redirect('mod/list_top');
      }
    }

    /*----- Valida reversion de saldos ------*/
    public function valida_reversion_saldos(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $cite = $this->security->xss_clean($post['cite']); // cite
          $cpoa_id = $this->security->xss_clean($post['cpoa_id']); // id cert poa
          $cert_ppto = $this->security->xss_clean($post['cert_ppto']); // cert presupuestaria
          
          $cpoa=$this->model_certificacion->get_datos_certificacion_poa($cpoa_id);
         
          if (!empty($_POST["par_id"]) && is_array($_POST["par_id"]) && count($this->model_ptto_sigep->partidas_proyecto($cpoa[0]['aper_id']))!=0) {
            
            /// ------ GENERANDO CITE DE MODIFICACION
               $data_to_store = array(
                'proy_id' => $cpoa[0]['proy_id'],
                'cppto_cite' => 'REV. SALDOS - '.$cite,
                'cppto_fecha' => date("d/m/Y H:i:s"),
                'tp' => 1,
                'observacion' => 'Reversion de Saldos de las siguientes Certificaciones: CERTIFICACION POA : N° '.$cpoa[0]['cpoa_codigo'].' y CERTIFICACION PRESUPUESTARIA : N° '.$cert_ppto,
                'fun_id' => $this->fun_id,
                );
              $this->db->insert('ppto_cite',$data_to_store);
              $cppto_id=$this->db->insert_id();
            /// -------------------------------------

            $nro=0;
            foreach ( array_keys($_POST["par_id"]) as $como){
              if($_POST["saldo"][$como]!=0){ /// saldo != 0
                $get_ppto_partida_asignado=$this->model_ptto_sigep->get_partida_asignado_unidad($cpoa[0]['aper_id'],$_POST["par_id"][$como]);

                if(($_POST["saldo"][$como]<$_POST["ppto_cert"][$como]) && count($get_ppto_partida_asignado)!=0){
                    //echo $get_ppto_partida_asignado[0]['sp_id'].'----->'.$_POST["par_id"][$como].'---'.$_POST["saldo"][$como].'<br>';
                  $partida_ppto=$this->model_ptto_sigep->get_sp_id($get_ppto_partida_asignado[0]['sp_id']);

                  /*------- Insert historial de saldos -------*/
                  $data_to_store = array(
                    'sp_id' => $get_ppto_partida_asignado[0]['sp_id'],
                    'monto_revertido' => $_POST["saldo"][$como],
                    'ppto_anterior' => $partida_ppto[0]['importe'],
                    'cppto_id' => $cppto_id,
                  );
                  $this->db->insert('saldo_partida',$data_to_store);
                  /*------------------------------------------*/

                  $saldo=$this->model_ptto_sigep->suma_saldo_revertido($get_ppto_partida_asignado[0]['sp_id']);

                  if(count($saldo)!=0){
                    $update_saldo = array(
                      'ppto_saldo_ncert' => $saldo[0]['saldo']
                    );
                    $this->db->where('sp_id', $get_ppto_partida_asignado[0]['sp_id']);
                    $this->db->update('ptto_partidas_sigep', $update_saldo);
                  }

                  ///---- update certificacion poa
                  $update_cpoa = array(
                      'cpoa_rev' => 1
                    );
                    $this->db->where('cpoa_id', $cpoa_id);
                    $this->db->update('certificacionpoa', $update_cpoa);
                  ///-----------------------------


                  $nro++;
                }
              }
            }

          $this->session->set_flashdata('success','REVERSION DE  '.$nro.' PARTIDAS');
          redirect(site_url("").'/mod/form_reporte_revertido/'.$cppto_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL GENERAR REVERSION DE PARTIDAS');
          redirect(site_url("").'/mod/add_ppto_reversion/'.$cpoa[0]['proy_id']);
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Operaciones</b></font>";
      }
    }




    /*------------------------------*/
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

}