<?php
class Cservicios extends CI_Controller { 
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->library('pdf2');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('mestrategico/model_objetivoregion');
            $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
            $this->load->model('menu_modelo');
            $this->load->model('Users_model','',true);
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->dist = $this->session->userData('dist');
            $this->rol = $this->session->userData('rol_id');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->fun_id = $this->session->userdata("fun_id");
            $this->conf_form4 = $this->session->userData('conf_form4');
            $this->conf_form5 = $this->session->userData('conf_form5');
            $this->load->library('programacionpoa');

            }else{
                $this->session->sess_destroy();
                redirect('/','refresh');
            }
    }

    /*----- VERIFICA EL TIPO DE OPERACION ------*/
    public function verif_tipo_ope($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); // Proy
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            if($data['proyecto'][0]['tp_id']==1){ //// Proyecto
                $this->lista_componentes($proy_id);
            }
            else{ /// Gasto Corriente
                $this->lista_servicios($proy_id);
            }
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }

    }

    /*------- OPERACION DE FUNCIONAMIENTO-----------*/
    /*--------- LISTA DE SERVICIOS (UNIDADES/ESTABLECIMIENTOS)------*/
    public function lista_servicios($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        if(count($data['proyecto'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['oregional']=$this->verif_oregional($proy_id);
            $data['componente']= $this->mis_servicios($proy_id);

            $data['button']='';
            if($this->conf_form4==1 || $this->tp_adm==1){
                $data['button']='
                <br>&nbsp;
                <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="MODIFICAR REGISTRO" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR ACTIVIDADES.CSV
                </a>
                <hr>';
            }

            $this->load->view('admin/programacion/componente/list_componentes', $data);
        }
        else{
            $this->session->set_flashdata('danger','ERROR !!!');
            redirect('admin/proy/list_proy');
        }
    }

    /*----------- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
    public function verif_oregional($proy_id){
        $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
        $tabla='';
        $nro=0;
        foreach($list_oregional as $row){
            $nro++;
            $tabla.='<h1> '.$nro.'.- OPERACI&Oacute;N REGIONAL : <small> '.$row['or_codigo'].' | '.$row['or_codigo'].' .- '.$row['or_objetivo'].'</small></h1>';
        }

        return $tabla;
    }

    /*---- IMPORTAR OPERACIONES POR SERVICIOS -----*/
    function importar_operaciones_global(){
        if ($this->input->post()) {
            $post = $this->input->post();
            $proy_id = $this->security->xss_clean($post['proy_id']);
            $pfec_id = $this->security->xss_clean($post['pfec_id']);
            $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
            $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id); /// Lista de Objetivos Regionales
            
          
            $tipo = $_FILES['archivo']['type'];
            $tamanio = $_FILES['archivo']['size'];
            $archivotmp = $_FILES['archivo']['tmp_name'];

            $filename = $_FILES["archivo"]["name"];
            $file_basename = substr($filename, 0, strripos($filename, '.'));
            $file_ext = substr($filename, strripos($filename, '.'));
            $allowed_file_types = array('.csv');
            if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
               /*------------------------------------------------------------------*/
               $lineas = file($archivotmp);
                $i=0;
                $guardado=0;
                $no_guardado=0;
                //Recorremos el bucle para leer línea por línea
                foreach ($lineas as $linea_num => $linea){ 
                    if($i != 0){ 
                        $datos = explode(";",$linea);
                        if(count($datos)==23){

                            $cod_serv = strval(trim($datos[0])); // Codigo UNIDAD
                            $cod_og = intval(trim($datos[1])); // Codigo ACP
                            $cod_or = intval(trim($datos[2])); // Codigo Objetivo Regional
                            $cod_form4 = intval(trim($datos[3])); // Codigo Form 4
                            $descripcion = strval(utf8_encode(trim($datos[4]))); //// descripcion form4
                            $resultado = strval(utf8_encode(trim($datos[5]))); //// descripcion Resultado
                            $unidad = strval(utf8_encode(trim($datos[6]))); //// Unidad responsable
                            $indicador = strval(utf8_encode(trim($datos[7]))); //// descripcion Indicador
                            $lbase = intval(trim($datos[8])); //// Linea Base
                            $meta = intval(trim($datos[9])); //// Meta
                            $mverificacion = strval(utf8_encode(trim($datos[22]))); //// Medio de verificacion

                            //// Verificando codigo de Unidad
                            if($cod_serv!='' & strlen($cod_serv)!=0 & strlen($cod_serv)==4){
                                $servicio=$this->model_componente->get_fase_componente_nro($pfec_id,$cod_serv,$proyecto[0]['tp_id']);
                                    if(count($servicio)!=0){
                                        $ae=0;
                                        $or_id=0;
                                        if(count($list_oregional)!=0){
                                          $get_acc=$this->model_objetivoregion->get_alineacion_proyecto_oregional($proy_id,$cod_og,$cod_or);
                                          if(count($get_acc)!=0){
                                            $ae=$get_acc[0]['ae'];
                                            $or_id=$get_acc[0]['or_id'];
                                          }
                                        }


                                        if(strlen($descripcion)!=0 & strlen($resultado)!=0){
                                            $query=$this->db->query('set datestyle to DMY');
                                            $data_to_store = array(
                                              'com_id' => $servicio[0]['com_id'],
                                              'prod_producto' => strtoupper($descripcion),
                                              'prod_resultado' => strtoupper($resultado),
                                              'indi_id' => 1,
                                              'prod_indicador' => strtoupper($indicador),
                                              'prod_fuente_verificacion' => strtoupper($mverificacion), 
                                              'prod_linea_base' => $lbase,
                                              'prod_meta' => $meta,
                                              'prod_unidades' => $unidad,
                                              'acc_id' => $ae,
                                              'prod_ppto' => 1,
                                              'fecha' => date("d/m/Y H:i:s"),
                                              'prod_cod'=>$cod_form4,
                                              'or_id'=>$or_id,
                                              'fun_id' => $this->fun_id,
                                              'num_ip' => $this->input->ip_address(), 
                                              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                                            );
                                            $this->db->insert('_productos', $data_to_store);
                                            $prod_id=$this->db->insert_id(); 


                                            $var=10;
                                            for ($i=1; $i <=12 ; $i++) {
                                              $m[$i]=floatval(trim($datos[$var])); //// Mes i
                                              if($m[$i]!=0){
                                                $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$m[$i]);
                                              }
                                              
                                              $var++;
                                            }

                                            $producto=$this->model_producto->get_producto_id($prod_id);
                                            if(count($producto)!=0){
                                              $guardado++;
                                            }
                                            else{
                                              $no_guardado++;
                                            }
                                    }
                                    /// Actualizando codigo de actividades
                                    $this->programacionpoa->update_codigo_actividad($servicio[0]['com_id']);
                                
                                }

                            
                            }
                            /// end codigo de unidad

   
                        }//// End count($datos)==23
                        else{
                            $no_guardado++;
                        } 
                    }
                    $i++;
                }
               /*------------------------------------------------------------------*/
                $this->session->set_flashdata('success','SE REGISTRARON '.$guardado.' ACTIVIDADES');
                redirect(site_url("").'/prog/list_serv/'.$proy_id.'');
            } 
            elseif (empty($file_basename)) {
                $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
                redirect(site_url("").'/prog/list_serv/'.$proy_id.'/false');
            } 

        } else {
            show_404();
        }
    }

    /*-------- VERIFICACION DE CODIGO COMPONENTE (PI) --------*/
    function verif_codigo_componente(){
      if($this->input->is_ajax_request()){
          $post = $this->input->post();
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $pfec_id = $this->security->xss_clean($post['pfec_id']); /// pfec id
          $fase = $this->model_faseetapa->get_fase($pfec_id);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); 

          $variable= $this->model_componente->get_fase_componente_nro($pfec_id,$codigo,1);
          if(count($variable)==0){
            echo "true"; /// Codigo Habilitado
          }
          else{
            echo "false"; /// No Existe Registrado
          }
      }else{
        show_404();
      }
    }

  /*---- SUBACTIVIDAD (2021) ---------*/
  function mis_servicios($proy_id){
    $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
    $fase = $this->model_faseetapa->get_id_fase($proy_id);

    if($this->rol==1 || $proyecto[0]['fun']==$this->fun_id){
        $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);    
    }
    else{
        $componente=$this->model_componente->componentes_fun_id($fase[0]['id'],$this->fun_id);     
    }
    $tabla='';
    $tabla.='<table id="dt_basic4" class="table table table-bordered" width="100%">
                <thead>
                    <tr style="height:45px;">
                        <th style="width:1%;">#</th>
                        <th style="width:5%;">COD. UNIDAD</th>
                        <th style="width:20%;">UNIDAD RESPONSABLE</th>
                        <th style="width:15%;">RESPONSABLE</th>
                        <th style="width:5%;">PONDERACI&Oacute;N</th>
                        <th style="width:5%;">NRO. ACT.</th>
                        <th style="width:5%;">MIS ACTIVIDADES</th>
                        <th style="width:5%;">FORM. POA N 4</th>
                        <th style="width:5%;">FORM. POA N 5</th>
                        <th style="width:5%;">EXCEL ACTIVIDADES</th>
                        <th style="width:5%;">ELIMINAR ACTIVIDADES </th>
                    </tr>
                </thead>
                <tbody>';
                $num=0; $ponderacion=0; $sum=0;
                foreach($componente as $row){
                    $num++;
                    $tabla.='
                    <tr>';
                        if(count($this->model_producto->list_prod($row['com_id']))==0 & $this->tp_adm==1){
                            $tabla.='<td><a href="#" data-toggle="modal" data-target="#modal_neg_ff" class="btn btn-default neg_ff" title="DESHABILITAR SUB-ACTIVIDAD"  name="'.$row['com_id'].'" id="'.count($this->model_producto->list_prod($row['com_id'])).'" ><img src="' . base_url() . 'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></td>';
                        }
                        else{
                            if($this->fun_id==399){
                                $tabla.='<td>';
                                $tp_sact = $this->model_componente->tp_subactividad(); // tp de subactividad
                                  $tabla .='<select class="form-control" onchange="doSelectAlert(event,this.value,'.$row['com_id'].');">';
                                    foreach($tp_sact as $pr){
                                        if($pr['tp_sact']==$row['tp_sact']){
                                          $tabla .="<option value=".$pr['tp_sact']." selected>".$pr['tipo_subactividad']."</option>";
                                        }
                                        else{
                                          $tabla .="<option value=".$pr['tp_sact'].">".$pr['tipo_subactividad']."</option>"; 
                                        }
                                    }
                                  $tabla.='</select>';
                                $tabla.='</td>';
                            }
                            else{
                                $tabla.='<td>'.$num.'</td>';
                            }
                        }
                        $tabla.='
                        <td bgcolor="#d4f1fb" align="center" title="C&Oacute;DIGO UNIDAD : '.$row["serv_descripcion"].'"><font color="blue" size=3><b>'.$row['serv_cod'].'</b></font></td>
                        <td>'.$row['serv_descripcion'].'</td>
                        <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                        <td>'.$row['com_ponderacion'].' %</td>
                        <td align=center bgcolor="#bee6e1"><font size=2 color=blue>'.count($this->model_producto->list_prod($row['com_id'])).'</font></td>
                        <td align="center">
                            <a href="'.site_url("admin").'/prog/list_prod/'.$row['com_id'].'" title="MIS ACTIVIDADES" class="btn btn-default"><img src="'.base_url().'assets/ifinal/archivo.png" WIDTH="34" HEIGHT="34"/></a>
                        </td>
                        <td align="center"><a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$row['com_id'].'\');" title="REPORTE POA FORM 4" class="btn btn-default"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>
                        <td align="center"><a href="javascript:abreVentana(\''.site_url("").'/proy/orequerimiento_proceso/'.$row['com_id'].'\');" title="REPORTE POA FORM 5" class="btn btn-default"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>
                        <td align="center"><a href="'.site_url("").'/prog/exportar_productos/'.$row['com_id'].'" title="EXPORTAR ACTIVIDADES" class="btn btn-default"><img src="' . base_url() . 'assets/ifinal/excel.jpg" WIDTH="38"/></a></td>
                        <td align="center">';
                        if(count($this->model_producto->list_prod($row['com_id']))!=0 & $this->tp_adm==1){
                            $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR TODAS LAS ACTIVIDADES DE LA UNIDAD"  name="'.$row['com_id'].'" id="'.count($this->model_producto->list_prod($row['com_id'])).'" ><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                        }
                        $tabla.='
                        </td>
                    </tr>';
                    $sum=$sum+count($this->model_producto->list_prod($row['com_id']));
                    $ponderacion=$ponderacion+$row['com_ponderacion'];
                }
                $tabla.='    
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.$ponderacion.'%</td>
                    <td align=center><b>'.$sum.'</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>';

    return $tabla;
    }

    /*--- CAMBIA TIPO DE SUBACTIVIDAD (2021) ---*/
    function cambia_tp_sact(){
      if($this->input->is_ajax_request() && $this->input->post()){
          $this->form_validation->set_rules('com_id', 'omponente id', 'required|trim');
          $this->form_validation->set_message('required', 'La selección del campo es obligatorio');
        
          $post = $this->input->post();
          $com_id= $this->security->xss_clean($post['com_id']);
          $tp_id= $this->security->xss_clean($post['tp_id']);
           
          $update_comp = array(
            'tp_sact' => $tp_id,
          );
          $this->db->where('com_id', $com_id);
          $this->db->update('_componentes', $update_comp);
              
      }else{
          show_404();
      }
    }

    /*---- GET VER REQUERIMIENTOS CARGADOS POR UNIDAD RESPONSABLE ----*/
    public function get_ver_requerimientos(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $com_id = $this->security->xss_clean($post['com_id']);
        $componente = $this->model_componente->get_componente($com_id,$this->gestion);
        

        $tabla=$this->programacionpoa->list_requerimientos_componente($componente);

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*------- PROYECTO DE INVERSION -----------*/
    /*--------- LISTA DE COMPONENTES------*/
    public function lista_componentes($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); // Proy
        $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
        if(count($data['fase'])!=0){
            $data['menu']=$this->genera_menu($proy_id);
            $data['unidad']=$this->model_componente->list_subactividades_pi();
            $data['componente']=$this->list_componentes_pi($proy_id); 
            $this->load->view('admin/programacion/componente/list_componentes_pi', $data);
        }
        else{
            redirect('admin/proy/fase_etapa/'.$proy_id); ///// fase sin habilitar
        }
        
    }

    public function lista_componentes_anterior($proy_id){
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id); // Proy
        $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
        $data['menu']=$this->genera_menu($proy_id);
        if($data['fase'][0]['fas_id']==1){ /// fases: preinversion, inversion
            $componente=$this->model_componente->componentes_id($data['fase'][0]['id'],$data['proyecto'][0]['tp_id']);
            if(count($componente)==1){
                redirect('admin/prog/list_prod/'.$componente[0]['com_id'].'');
            }
            else{
                echo "<center>Error al ingresar.....</center>";
            }
        }
        else{
            /*----------------- fase inversion ------------------*/
            $data['unidad']=$this->model_componente->list_subactividades_pi();
            $data['componente']=$this->list_componentes_pi($proy_id); 
            $this->load->view('admin/programacion/componente/list_componentes_pi', $data);
        }
    }

    /*----- COMPONENTES PROYECTOS DE INVERSION -----*/
  function list_componentes_pi($proy_id){
    $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
    $fase = $this->model_faseetapa->get_id_fase($proy_id);

    if($this->rol==1 || $proyecto[0]['fun']==$this->fun_id){
        $componente=$this->model_componente->componentes_id($fase[0]['id'],$proyecto[0]['tp_id']);    
    }
    else{
        $componente=$this->model_componente->componentes_fun_id($fase[0]['id'],$this->fun_id);     
    }

    $tabla='';
    $tabla.='<table id="dt_basic4" class="table table table-bordered" width="100%">
                <thead>
                    <tr style="height:45px;">
                        <th style="width:1%;">#</th>
                        <th style="width:1%;">MODIFICAR</th>
                        <th style="width:15%;">UNIDAD RESPONSABLE</th>
                        <th style="width:15%;">DESCRIPCI&Oacute;N COMPONENTE</th>
                        <th style="width:15%;">RESPONSABLE</th>
                        <th style="width:5%;">PONDERACI&Oacute;N</th>
                        <th style="width:5%;">NRO. ACT.</th>
                        <th style="width:5%;">MIS ACTIVIDADES</th>
                        <th style="width:5%;">FORMULARIO N 4</th>
                        <th style="width:5%;">EXCEL ACTIVIDADES</th>
                        <th style="width:5%;">ELIMINAR ACTIVIDADES</th>
                    </tr>
                </thead>
                <tbody>';
                $num=0; $ponderacion=0; $sum=0;
                foreach($componente as $row){
                    $num++;
                    $tabla.='
                    <tr>';
                        if(count($this->model_producto->list_prod($row['com_id']))==0){
                            $tabla.='<td><a href="#" data-toggle="modal" data-target="#modal_neg_ff" class="btn btn-default neg_ff" title="DESHABILITAR COMPONENTE"  name="'.$row['com_id'].'" id="'.count($this->model_producto->list_prod($row['com_id'])).'" ><img src="' . base_url() . 'assets/img/neg.jpg" WIDTH="35" HEIGHT="35"/></td>';
                        }
                        else{
                            $tabla.='<td>'.$num.'</td>';
                        }
                        $tabla.='
                        <td><a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff" name="'.$row['com_id'].'" title="MODIFICAR COMPONENTE" ><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a></td>
                        <td bgcolor="#d4f1fb" align="center" ><font color="blue" size=2><b>'.$row['serv_cod'].' .- '.$row['serv_descripcion'].'</b></font></td>
                        <td>'.$row['com_componente'].'</td>
                        <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                        <td>'.$row['com_ponderacion'].' %</td>
                        <td align=center bgcolor="#bee6e1"><font size=2 color=blue>'.count($this->model_producto->list_prod($row['com_id'])).'</font></td>
                        <td align="center"><a href="'.site_url("admin").'/prog/list_prod/'.$row['com_id'].'" title="ACTIVIDADES DE LA FASE" class="btn btn-default"><img src="'.base_url().'assets/ifinal/archivo.png" WIDTH="34" HEIGHT="34"/></a></td>
                        <td align="center"><a href="javascript:abreVentana(\''.site_url("").'/prog/rep_operacion_componente/'.$row['com_id'].'\');" title="REPORTE DE ACTIVIDADES" class="btn btn-default"><img src="'.base_url().'assets/ifinal/pdf.png" WIDTH="35" HEIGHT="35"/></a></td>
                        <td align="center"><a href="'.site_url("").'/prog/exportar_productos/'.$row['com_id'].'" title="EXPORTAR ACTIVIDADES" class="btn btn-default"><img src="' . base_url() . 'assets/ifinal/excel.jpg" WIDTH="38"/></a></td>
                        <td align="center">';
                        if(count($this->model_producto->list_prod($row['com_id']))!=0){
                            $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR TODAS LAS ACTIVIDADES DE LA UNIDAD"  name="'.$row['com_id'].'" id="'.count($this->model_producto->list_prod($row['com_id'])).'" ><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>';
                        }
                        $tabla.='
                        </td>
                    </tr>';
                    $sum=$sum+count($this->model_producto->list_prod($row['com_id']));
                    $ponderacion=$ponderacion+$row['com_ponderacion'];
                }
                $tabla.='    
                </tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>'.$ponderacion.'%</td>
                    <td align="center"><b>'.$sum.'</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>';

    return $tabla;
    }

    /*--------- Valida Componente (2021) ------------*/
    public function valida_componente(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $pfec_id = $this->security->xss_clean($post['pfec_id']); /// pfec id
          $descripcion = $this->security->xss_clean($post['descripcion']); /// Descripcion Componente 
          $serv_id = $this->security->xss_clean($post['serv_id']); //// serv id

          if(isset($pfec_id) & isset($descripcion) & isset($serv_id)){
                $fase = $this->model_faseetapa->get_fase($pfec_id);
                $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
                $reponsable=$this->model_proyecto->responsable_proy($fase[0]['proy_id'],2);
                /*--------- COMPONENTE ----------*/
                $data = array(
                    'pfec_id' => $pfec_id,
                    'serv_id' => $serv_id,
                    'com_componente' => strtoupper($descripcion), 
                    'resp_id' => $reponsable[0]['fun_id'], 
                    'fun_id' => $this->fun_id,
                );
                $this->db->insert('_componentes',$data);
                $com_id=$this->db->insert_id();
                /*------------------------------------*/

                if(count($this->model_componente->get_componente($com_id,$this->gestion))!=0){
                    $this->session->set_flashdata('success','EL COMPONENTE SE REGISTRO CORRECTAMENTE');
                    redirect(site_url("").'/prog/list_serv/'.$fase[0]['proy_id']);
                }
                else{
                    $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DEL COMPONENTE');
                    redirect(site_url("").'/prog/list_serv/'.$fase[0]['proy_id']);
                }           
          }
          else{
            $this->session->set_flashdata('danger','NO INGRESAN LOS DATOS ');
            redirect(site_url("").'/prog/list_serv/'.$fase[0]['proy_id']);
          }

      } else {
          show_404();
      }
    }

    /*------------------------- Valida Update Componente (2019) --------------------------*/
    public function valida_update_componente(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']); /// com id
          $serv_id = $this->security->xss_clean($post['mserv_id']); /// Descripcion Componente 
          $comp = $this->security->xss_clean($post['mcomponente']); //// Codigo
          $componente=$this->model_componente->get_componente($com_id,$this->gestion);
            $fase = $this->model_faseetapa->get_fase($componente[0]['pfec_id']);
            $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);

          if(isset($com_id) & isset($serv_id) & isset($componente)){
              
              /*--------- COMPONENTE ----------*/
              $update_comp = array(
                'serv_id' => $serv_id,
                'com_componente' => strtoupper($comp), 
                'estado' => 2,
                'fun_id' => $this->fun_id
                );
                $this->db->where('com_id', $com_id);
                $this->db->update('_componentes', $update_comp);

                $this->session->set_flashdata('success','EL REGISTRO SE MODIFICO CORRECTAMENTE');
                redirect(site_url("").'/prog/list_serv/'.$fase[0]['proy_id']);

          }
          else{
            $this->session->set_flashdata('danger','ERROR EN EL REGISTRO DEL COMPONENTE');
            redirect(site_url("").'/prog/list_serv/'.$fase[0]['proy_id']);
          }

      } else {
          show_404();
      }
    }



 /*====== OTROS ====*/
  /*----------- OBTIENE LOS DATOS DEL COMPONENTE (2019) ------------------*/
    public function get_componente(){
        if($this->input->is_ajax_request() && $this->input->post()){
            $post = $this->input->post();
            $com_id = $post['com_id'];
            $com_id = $this->security->xss_clean($com_id);
            $dato_comp = $this->model_componente->get_componente($com_id,$this->gestion);
            //caso para modificar el codigo de proyecto y actividades
            
            if(count($dato_comp)!=0){
              $result = array(
                  'respuesta' => 'correcto',
                  'componente' => $dato_comp,
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

    /*------------ DELETE SERVICIOS (PRODUCTOS) --------------*/
    function elimina_operaciones_componente(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']);
          $productos = $this->model_producto->list_prod($com_id);

            foreach ($productos as $rowp) {
            $update_prod= array(
                'fun_id' => $this->fun_id,
                'or_id' => 0,
                'estado' => 3
            );
            $this->db->where('prod_id', $rowp['prod_id']);
            $this->db->update('_productos', $this->security->xss_clean($update_prod));

                $insumos = $this->model_producto->insumo_producto($rowp['prod_id']);

                foreach ($insumos as $rowi) {
                    $update_ins= array(
                        'fun_id' => $this->fun_id,
                        'aper_id' => 0,
                        'ins_estado' => 3,
                        'num_ip' => $this->input->ip_address(), 
                        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
                    );
                    $this->db->where('ins_id', $rowi['ins_id']);
                    $this->db->update('insumos', $this->security->xss_clean($update_ins));
                }
            }

            $productos = $this->model_producto->list_prod($com_id);
            if(count($productos)==0){
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

  
    /*------- DESHABILITAR SUB ACTIVIDAD (SERVICIO) ------*/
    function deshabilitar_sactividad(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']);
          $productos = $this->model_producto->list_prod($com_id);

            $update_com= array(
                'fun_id' => $this->fun_id,
                'serv_id' => 0,
                'estado' => 3
            );
            $this->db->where('com_id', $com_id);
            $this->db->update('_componentes', $this->security->xss_clean($update_com));

            $dato_comp = $this->model_componente->get_componente($com_id,$this->gestion);
            if($dato_comp[0]['estado']==3){
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


    /*--------------------- VERIF NRO DE COMPONENTE ---------------------*/
    function verif_nro(){
        if($this->input->is_ajax_request()) {
            $post = $this->input->post();
            $fase_id = $post['fase_id'];
            $nro= $post['nro'];

            $variable= $this->model_componente->componentes_verif_nro($fase_id,$nro);
             if(count($variable)==0){
             echo "true";; ///// Se puede Registrar
             }
             else{
              echo "false";; //// No se puede registrar ya se tiene un registro
             } 
        }else{
          show_404();
      }
    }
    /*-----------------------------------------------------------------*/



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
    /*--------------- GENERA MENU -------------*/
    public function genera_menu($proy_id){
        $id_f = $this->model_faseetapa->get_id_fase($proy_id);
        $enlaces=$this->menu_modelo->get_Modulos_programacion(2);
        $tabla='';
        $tabla.='<nav>
                <ul>
                    <li>
                        <a href='.site_url("admin").'/dashboard'.' title="MENU PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                    </li>
                    <li class="text-center">
                        <a href='.base_url().'index.php/admin/proy/mis_proyectos/1'.' title="PROGRAMACI&Oacute;N POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N POA</span></a>
                    </li>';
                    if(count($id_f)!=0){
                        for($i=0;$i<count($enlaces);$i++){ 
                            $tabla.='
                            <li>
                                <a href="#" >
                                    <i class="'.$enlaces[$i]['o_image'].'"></i> <span class="menu-item-parent">'.$enlaces[$i]['o_titulo'].'</span></a>
                                <ul >';
                                $submenu= $this->menu_modelo->get_Modulos_sub($enlaces[$i]['o_child']);
                                foreach($submenu as $row) {
                                   $tabla.='<li><a href='.base_url($row['o_url'])."/".$id_f[0]['proy_id'].'>'.$row['o_titulo'].'</a></li>';
                                }
                            $tabla.='</ul>
                            </li>';
                        }
                    }
                $tabla.='
                </ul>
            </nav>';

        return $tabla;
    }
}