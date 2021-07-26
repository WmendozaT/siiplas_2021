<?php
class Producto extends CI_Controller { 
  public function __construct (){ 
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->library('pdf2');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_actividad');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('modificacion/model_modificacion');
        $this->load->model('mestrategico/model_mestrategico');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('ejecucion/model_ejecucion');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->load->library('security');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->rol = $this->session->userData('rol_id');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->conf_form4 = $this->session->userData('conf_form4');
        $this->conf_form5 = $this->session->userData('conf_form5');
        $this->load->library('programacionpoa');
      }else{
        $this->session->sess_destroy();
          redirect('/','refresh');
      }
    }

  /*------- LISTA DE OPERACIONES ----------*/
    public function lista_productos($com_id){  
      $data['componente'] = $this->model_componente->get_componente($com_id);
      if(count($data['componente'])!=0){

        $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
        $proy_id=$data['fase'][0]['proy_id'];
        $data['menu']=$this->genera_menu($proy_id);
        $data['productos'] = $this->model_producto->list_prod($com_id); // Lista de productos
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
        $data['oregional']=$this->verif_oregional($proy_id);  //// Verifica Objetivos regionales
       /* $data['monto_asig']=0;
        $data['monto_prog']=0;
        $data['monto_asig']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],1);
        $data['monto_prog']=$this->model_ptto_sigep->suma_ptto_accion($data['proyecto'][0]['aper_id'],2);

        $monto_a=0;$monto_p=0;
        if(count($data['monto_asig'])!=0){
          $monto_a=$data['monto_asig'][0]['monto'];
        }
        if (count($data['monto_prog'])!=0) {
         $monto_p=$data['monto_prog'][0]['monto']; 
        }

        $data['saldo']= round(($monto_a-$monto_p),2);*/
        $data['indi'] = $this->model_proyecto->indicador(); /// indicador
        $data['metas'] = $this->model_producto->tp_metas(); /// tp metas
        $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
        if(count($this->model_producto->ult_operacion($com_id))!=0){
          $data['cod_ope']=$this->model_producto->ult_operacion($com_id);
        }
        else{
          $data['cod_ope']=0;
        }

        /*--------- Proyecto de Inversion -----------*/
        if($data['proyecto'][0]['tp_id']==1){
          $data['datos_proyecto']='<h1> PROYECTO : <small> '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</small></h1>';
          $data['list_oregional']=$this->lista_oregional_pi($proy_id);
        }
        /*--------- Operacion de Funcionamiento ----------*/
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $data['datos_proyecto']='<h1> '.$data['proyecto'][0]['establecimiento'].' : <small> '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</small></h1>';
          $data['list_oregional']=$this->lista_oregional($proy_id);
        }

        $data['button']=$this->button_form4(count($data['productos']));
        $data['prod'] = $this->operaciones($proy_id,$com_id);
        $this->load->view('admin/programacion/producto/list_productos', $data); /// Gasto Corriente

      }
      else{
        redirect('prog/list_serv/'.$com_id);
      }
  }




    /*--- BOTON REPORTE SEGUIMIENTO POA (MES VIGENTE)---*/
    function button_form4($nro){
      $tabla='';
      if($this->tp_adm==1 || $this->conf_form4==1){
        $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_nuevo_form" class="btn btn-default nuevo_form" title="NUEVO REGISTRO FORM N 4" class="btn btn-success">
                    <img src="'.base_url().'assets/Iconos/add.png" WIDTH="20" HEIGHT="20"/>&nbsp;NUEVO REGISTRO
                  </a>
                  
                  <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="1" title="MODIFICAR REGISTRO" >
                    <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR NUEVAS ACTIVIDADES.CSV
                  </a>';

        if($nro!=0){
          $tabla.=' <a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-default importar_ff" name="2" title="SUBIR ARCHIVO REQUERIMIENTO (GLOBAL)" >
                      <img src="'.base_url().'assets/Iconos/arrow_up.png" WIDTH="30" HEIGHT="20"/>&nbsp;SUBIR REQUERIMIENTOS (GLOBAL)
                    </a>';
        }
      }

      $tabla.='<br><br>';
      
      return $tabla;
    }

    /*--- BOTON REPORTE SEGUIMIENTO POA (MES VIGENTE)---*/
    function button_rep_seguimientopoa($com_id){
      $tabla='';
        $tabla.='
                <a href="javascript:abreVentana(\''.site_url("").'/seguimiento_poa/reporte_seguimientopoa_mensual/'.$com_id.'/'.$this->verif_mes[1].'\');" class="btn btn-default" title="IMPRIMIR SEGUIMIENTO POA">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;&nbsp;<b>IMPRIMIR SEGUIMIENTO POA ('.$this->verif_mes[2].')</b>
                </a>';
      return $tabla;
    }


  /*--- ACTUALIZA CODIGO DE OPERACION ----*/
/*  public function update_codigo($com_id){  
    $productos = $this->model_producto->lista_operaciones($com_id,$this->gestion); // Lista de productos
    $nro=0;
    foreach($productos as $row){
      $nro++;
      $update_prod= array(
        'prod_cod' => $nro,
        'fun_id' => $this->fun_id
      );
      $this->db->where('prod_id', $row['prod_id']);
      $this->db->update('_productos', $update_prod);
    }

    redirect('admin/prog/list_prod/'.$com_id);
  }*/


  /*--- LISTA DE OBJETIVO REGIONAL (GASTO CORRIENTE )-----*/
  public function lista_oregional($proy_id){
    $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
    $tabla='';
    if(count($list_oregional)==1){
      $tabla.=' <section class="col col-3">
                  <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                    <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                  </label>
                </section>'; 
    }
    else{
        $tabla.='<section class="col col-6">
                <label class="label"><b>OBJETIVO REGIONAL</b></label>
                  <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                    <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                    foreach($list_oregional as $row){ 
                      $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                    }
                  $tabla.='
                </select>
              </section>'; 
    }
       
    return $tabla;
  }

  /*---- LISTA DE OBJETIVO REGIONAL (PROYECTO DE INVERSION)-----*/
  public function lista_oregional_pi($proy_id){
    $list_oregional= $this->model_objetivoregion->get_unidad_pregional_programado($proy_id);
    $tabla='';
    if(count($list_oregional)==1){
      $tabla.=' <section class="col col-6">
                  <label class="label"><b>OBJETIVO REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-tag"></i>
                    <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                    <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                  </label>
                </section>'; 
    }
    else{
        $tabla.='<section class="col col-6">
                <label class="label"><b>OBJETIVO REGIONAL</b></label>
                  <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                    <option value="0">SELECCIONE OBJETIVO REGIONAL</option>';
                    foreach($list_oregional as $row){ 
                      $tabla.='<option value="'.$row['or_id'].'">'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</option>';    
                    }
                  $tabla.='
                </select>
              </section>'; 
    }
       
    return $tabla;
  }

  /*----------- VERIFICA LA ALINEACION DE OBJETIVO REGIONAL -----*/
  public function verif_oregional($proy_id){
    $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
    $tabla='';
    $nro=0;
    if(count($list_oregional)!=0){
      foreach($list_oregional as $row){
        $nro++;
        $tabla.='<h1 title='.$row['or_id'].'> '.$nro.'.- OBJETIVO REGIONAL : <small> <b>'.$row['or_codigo'].'</b>.- '.$row['or_objetivo'].'</small></h1>';
      }
    }
    else{
      $tabla.='<h1><small><font color=red>NO ALINEADO A NINGUN OBJETIVO REGIONAL</font></small></h1>';
    }
    
    return $tabla;
  }

  /*--------- VALIDA OPERACIONES (2020) -----------*/
  public function valida_producto(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('tipo_i', 'Tipo de Indicador', 'required|trim');
        $componente = $this->model_componente->get_componente($this->input->post('com_id'));
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        
        $ae=0;
        if($this->gestion==2019){
          $acc=$this->model_mestrategico->get_acciones_estrategicas($this->input->post('acc_id'));
          $ae=$acc[0]['ae'];
        }
        else{
          $get_acc=$this->model_objetivoregion->get_objetivosregional($this->input->post('or_id'));
          if(count($get_acc)!=0){
            $ae=$get_acc[0]['ae'];
          }
        }

        if($this->input->post('tipo_i')==1){
          $tp_met=3;
        }
        else{
          $tp_met=$this->input->post('tp_met');
        }
    

        if ($this->form_validation->run()){
          /*----------------- INSERT OPERACION -------------------*/
          $data_to_store = array(
            'com_id' => $this->input->post('com_id'),
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')), 
            'prod_linea_base' => $this->input->post('lbase'),
            'prod_meta' => $this->input->post('meta'),
            'indi_pei' => 0,
            'prod_ppto' => $this->input->post('ppto'),
            'prod_unidades' => $this->input->post('unidad'),
            'acc_id' => $ae,
            'or_id' => $this->input->post('or_id'),
            'mt_id' => $tp_met,
            'fecha' => date("d/m/Y H:i:s"),
            'prod_cod'=>$this->input->post('cod'),
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('_productos', $data_to_store);
          $id_pr=$this->db->insert_id(); ////// id del producto
          /*------------------------------------------------------*/
          if($this->input->post('tipo_i')==1){
            for ($i=1; $i <=12 ; $i++) {
              if($this->input->post('m'.$i)!=0){
                $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('m'.$i));
              }
            }
          }
          if($this->input->post('tipo_i')==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($this->input->post('m'.$i)!=0){
                  $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('m'.$i));
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($id_pr,$this->gestion,$i,$this->input->post('met'));
              }
            }
          }

          $producto=$this->model_producto->get_producto_id($id_pr);
          if(count($producto)==1){
            $this->session->set_flashdata('success','LA ACTIVIDAD SE REGISTRO CORRECTAMENTE :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR ACTIVIDAD, VUELVA REGISTRAR :(');
          }

          redirect('admin/prog/list_prod/'.$this->input->post('com_id').'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA ACTIVIDAD :(');
          redirect('admin/prog/list_prod/'.$this->input->post('com_id').'');
        }
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar la Actividad !!!!</font></center>";
    }
  }


    /*----- MODIFICAR PRODUCTO (UNIDAD / ESTABLECIMIENTO DE SALUD)(2019) ----*/
    public function update_producto($prod_id){
    $data['producto']=$this->model_producto->get_producto_id($prod_id);
    if(count($data['producto'])!=0){
      $data['programado']=$this->model_producto->producto_programado($data['producto'][0]['prod_id'],$this->gestion);
      $data['componente'] = $this->model_componente->get_componente_pi($data['producto'][0]['com_id']);
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']);

      $data['menu']=$this->genera_menu($data['proyecto'][0]['proy_id']);

      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['metas'] = $this->model_producto->tp_metas(); /// tp metas
      $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
      if($data['producto'][0]['acc_id']!=''){
        $data['ope_acc'] = $this->model_producto->operacion_accion($data['producto'][0]['acc_id']); /// Acciones estrategicas
        if(count($data['ope_acc'])!=0){
          $data['list_aestrategicas']=$this->model_mestrategico->list_acciones_estrategicas($data['ope_acc'][0]['obj_id']);
          $data['indi_pei']=$this->model_mestrategico->list_indicadores_pei2($data['producto'][0]['acc_id']); 
        }
      }
      
      $data['componente'] = $this->model_componente->get_componente($data['producto'][0]['com_id']);
      $data['oregional']=$this->verif_oregional($data['fase'][0]['proy_id']);
      $data['prog']=0;
      $programado=$this->model_producto->suma_programado_producto($prod_id,$this->gestion);
      if(count($programado)!=0){
        $prog=$programado[0]['prog'];
      }

      if($data['producto'][0]['mt_id']==1){
        $data['prog']=$data['producto'][0]['prod_meta'];
      }
      else{
        $data['prog']=$prog;
      }

      if($data['proyecto'][0]['tp_id']==1){
        $data['list_oregional']=$this->model_objetivoregion->get_unidad_pregional_programado($data['fase'][0]['proy_id']); /// Lista de Objetivos Regionales PI
        $this->load->view('admin/programacion/producto/edit_prod_pi', $data); /// Gasto Corriente
      }
      else{
        if($this->gestion==2019){
          $this->load->view('admin/programacion/producto/edit_prod2019', $data); /// Gasto Corriente
        }
        else{
          $data['list_oregional']=$this->model_objetivoregion->list_proyecto_oregional($data['fase'][0]['proy_id']);/// Lista de Objetivos Regionales
          $this->load->view('admin/programacion/producto/edit_prod', $data); /// Gasto Corriente
        }
      }
    }
    else{
      redirect('admin/dashboard');
    } 
  }

  /*-------- VALIDAR MODIFICACION OPERACIÓN-PRODUCTO (2020) --------*/
  public function modificar_producto(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('indicador', 'Indicador', 'required|trim');
        $this->form_validation->set_rules('met', 'Meta', 'required|trim');

        $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
        $componente = $this->model_componente->get_componente($producto[0]['com_id']);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        
        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($this->input->post('or_id'));
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if($this->input->post('tipo_i')==1){
          $tp_met=3;
        }
        else{
          $tp_met=$this->input->post('tp_met');
        }

        if ($this->form_validation->run()){
          /*-------------- UPDATE OPERACION --------------*/
          $update_prod = array(
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_linea_base' => $this->input->post('lb'),
            'indi_pei' => $this->input->post('indi_pei'),
            'prod_meta' => $this->input->post('met'),
            'prod_unidades' => $this->input->post('unidad'),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
            'estado' => 2,
            'or_id' => $this->input->post('or_id'),
            'acc_id' => $ae,
            'fecha' => date("d/m/Y H:i:s"),
            'mt_id' => $tp_met,
            'fun_id' => $this->fun_id,
            'prod_ppto' => $this->input->post('ppto'),
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            );
          $this->db->where('prod_id', $this->input->post('prod_id'));
          $this->db->update('_productos', $update_prod);
          /*----------------------------------------------*/

          $this->model_producto->delete_prod_gest($this->input->post('prod_id'));

          if($this->input->post('tipo_i')==1){
            for ($i=1; $i <=12 ; $i++) {
              if($this->input->post('m'.$i)!=0){
                $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
              }
            }
          }

          if($this->input->post('tipo_i')==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($this->input->post('m'.$i)!=0){
                  $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('met'));
              }
            }
          }  
            
            $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE DATOS DE LA ACTIVIDAD :)');
            redirect('admin/prog/list_prod/'.$producto[0]['com_id'].'');
          }
          else {
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR DATOS DE LA ACTIVIDAD :(');
            redirect('admin/prog/mod_prod/'.$this->input->post('prod_id').'/false');
          }
      }
   }

     /*-------- VALIDAR MODIFICACION OPERACIÓN-PRODUCTO (2019) --------*/
  public function modificar_producto2019(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('indicador', 'Indicador', 'required|trim');
        $this->form_validation->set_rules('met', 'Meta', 'required|trim');

        $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
        $componente = $this->model_componente->get_componente($producto[0]['com_id']);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        $ae=$this->model_mestrategico->get_acciones_estrategicas($this->input->post('acc_id'));

        
        if($this->input->post('tipo_i')==1){
          $tp_met=3;
        }
        else{
          $tp_met=$this->input->post('tp_met');
        }

        if ($this->form_validation->run()){
          /*-------------- UPDATE OPERACION --------------*/
          $update_prod = array(
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_linea_base' => $this->input->post('lb'),
            'indi_pei' => $this->input->post('indi_pei'),
            'prod_meta' => $this->input->post('met'),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
            'estado' => 2,
            'acc_id' => $ae[0]['ae'],
            'fecha' => date("d/m/Y H:i:s"),
            'mt_id' => $tp_met,
            'fun_id' => $this->fun_id,
            );
          $this->db->where('prod_id', $this->input->post('prod_id'));
          $this->db->update('_productos', $update_prod);
          /*----------------------------------------------*/

          $gestion=$fase[0]['pfec_fecha_inicio'];
          $this->model_producto->delete_prod_gest($this->input->post('prod_id'));

          if($this->input->post('tipo_i')==1){
            for ($i=1; $i <=12 ; $i++) {
              if($this->input->post('m'.$i)!=0){
                $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
              }
            }
          }

          if($this->input->post('tipo_i')==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($this->input->post('m'.$i)!=0){
                  $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('met'));
              }
            }
          }  
            
            $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
            $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA OPERACI&Oacute;N :)');
            redirect('admin/prog/list_prod/'.$producto[0]['com_id'].'');
          }
          else {
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR LA OPERACI&Oacute;N :(');
            redirect('admin/prog/mod_prod/'.$this->input->post('prod_id').'/false');
          }
      }
   }
  /*==========================================================================================*/

    /*----------------- LISTA OPERACIONES (2020) ------------------*/
    public function operaciones($proy_id,$com_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $productos = $this->model_producto->lista_operaciones($com_id,$this->gestion); // Lista de productos
      $tabla ='';
      $tabla .='<thead>
                  <tr class="modo1">
                    <th style="width:1%; text-align=center"><b>COD.</b></th>
                    <th style="width:1%; text-align=center"><b>E/B</b></th>
                    <th style="width:2%;"><b>COD. OR.</b></th>
                    <th style="width:2%;"><b>COD. ACT.</b></th>
                    <th style="width:15%;"><b>ACTIVIDAD</b></th>
                    <th style="width:15%;"><b>RESULTADO</b></th>
                    <th style="width:10%;"><b>TIP. IND.</b></th>
                    <th style="width:10%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE '.($this->gestion-1).'</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:10%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                    <th style="width:7%;"><b>ELIMINAR ACTIVIDAD</b></th>
                    <th style="width:7%;"><b>PTTO..</b></th>
                    <th style="width:7%;"><b>NRO. REQ.</b></th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach($productos as $rowp){
                  $cont++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $ptto=0;
                  if(count($monto)!=0){
                    $ptto=$monto[0]['total'];
                  }

                  $color=''; $titulo=''; $por='';
                  if($rowp['indi_id']==2){ // Relativo
                    $por='%';
                    if($rowp['mt_id']==3){
                      if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                        $color='#fbd5d5';
                        $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                      }
                    }
                  }
                  else{ // Absoluto
                    if($sum[0]['meta_gest']!=$rowp['prod_meta'] || $rowp['or_id']==0){
                      $color='#fbd5d5';
                      $titulo='ERROR EN LA DISTRIBUCION O FALTA DE ALINEACION';
                    }
                  }
                  
                  $tabla .='<tr bgcolor="'.$color.'" class="modo1" title='.$titulo.'>';
                    $tabla.='<td align="center"><font color="blue" size="2"><b>'.$rowp['prod_cod'].'</b></font></td>';
                    $tabla.='<td align="center">';
                    $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                    /*if($this->tp_adm==1){
                      $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                    }*/
                    if($rowp['prod_ppto']==1){
                      $tabla.='<a href="'.site_url("").'/prog/requerimiento/'.$proy_id.'/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a>';
                    }
                    $tabla.='</td>';
                    $tabla.='<td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
                    $tabla.='<td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
                    $tabla.='<td style="width:15%;">'.$rowp['prod_producto'].'</td>';
                    $tabla.='<td style="width:15%;">'.$rowp['prod_resultado'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['indi_abreviacion'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['prod_indicador'].'</td>';
                    $tabla.='<td style="width:10%;">'.round($rowp['prod_linea_base'],2).'</td>';
                    $tabla.='<td style="width:10%;">'.round($rowp['prod_meta'],2).'</td>';
                    if(count($programado)!=0){
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['enero'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['febrero'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['marzo'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['abril'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['mayo'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['junio'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['julio'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['agosto'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['septiembre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['octubre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['noviembre'],2).' '.$por.'</td>';
                      $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($programado[0]['diciembre'],2).' '.$por.'</td>';
                    }
                    else{
                      $tabla.='<td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>
                                <td style="width:4%;" bgcolor="#f1bac6">0</td>';
                    }
                    $tabla.='<td style="width:10%;" bgcolor="#e5fde5">'.$rowp['prod_fuente_verificacion'].'</td>';
                    $tabla.='<td style="width:7%;">';
                      if($this->tp_adm==1 || $this->fun_id==715 || $this->fun_id==690){
                        $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" id="'.$proy_id.'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br><br>';
                        $tabla.=' <center>
                                    <input type="checkbox" name="req[]" value="'.$rowp['prod_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
                                  </center>';
                      }
                    $tabla.='</td>';
                    $tabla.='<td>'.number_format($ptto, 2, ',', '.').'</td>';
                    $tabla.='<td style="width:7%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
                  $tabla .='</tr>';
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
                $tabla.='</tbody>';

      return $tabla;
    }

    /*----------------- LISTA OPERACIONES (2019) ------------------*/
/*    public function operaciones2019($proy_id,$com_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $productos = $this->model_producto->list_producto_programado($com_id,$this->gestion); // Lista de productos
      $tabla ='';
      $tabla .='<thead>
                  <tr class="modo1">
                    <th style="width:1%; text-align=center"><b>COD.</b></th>
                    <th style="width:1%; text-align=center"><b>E/B</b></th>
                    <th style="width:20%;"><b>OPERACI&Oacute;N</b></th>
                    <th style="width:20%;"><b>RESULTADO</b></th>
                    <th style="width:10%;"><b>TIP. IND.</b></th>
                    <th style="width:10%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:5%;"><b>PONDERACI&Oacute;N</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:7%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                    <th style="width:7%;"><b>DELETE</b></th>
                    <th style="width:7%;"><b>NRO. REQ.</b></th>
                  </tr>
                </thead>
                <tbody>';
                $cont = 0;
                foreach($productos as $rowp){
                  $cont++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $color='';
                  if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta']){
                    $color='#fbd5d5';
                  }
                  $tabla .='<tr bgcolor="'.$color.'" class="modo1">';
                    $tabla.='<td title="C&Oacute;DIGO OPERACI&Oacute;N : '.$rowp['prod_cod'].'" align="center"><font color="blue" size="2"><b>'.$rowp['prod_cod'].'</b></font></td>';
                    $tabla.='<td align="center">';
                    $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a>';
                    $tabla.='<a href="'.site_url("").'/prog/requerimiento/'.$proy_id.'/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a>';

                    $tabla.='</td>';
                    $tabla.='<td style="width:20%;">'.$rowp['prod_producto'].'</td>';
                    $tabla.='<td style="width:20%;">'.$rowp['prod_resultado'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['indi_abreviacion'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['prod_indicador'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['prod_linea_base'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['prod_meta'].'</td>';
                    $tabla.='<td style="width:10%;">'.$rowp['prod_ponderacion'].'%</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['enero'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['febrero'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['marzo'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['abril'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['mayo'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['junio'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['julio'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['agosto'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['septiembre'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['octubre'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['noviembre'].'</td>';
                    $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.$rowp['diciembre'].'</td>';
                    $tabla.='<td style="width:7%;" bgcolor="#e5fde5">'.$rowp['prod_fuente_verificacion'].'</td>';
                    $tabla.='<td style="width:7%;">';
                      $tabla.='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" id="'.$proy_id.'"><img src="' . base_url() . 'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br><br>';
                      $tabla.='<center>
                                <input type="checkbox" name="req[]" value="'.$rowp['prod_id'].'" onclick="scheck'.$cont.'(this.checked);"/>
                              </center>';
                    $tabla.='</td>';
                    $tabla.='<td style="width:7%;" align="center"><font color="blue" size="2"><b>'.count($this->model_producto->insumo_producto($rowp['prod_id'])).'</b></font></td>';
                  $tabla .='</tr>';
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
                $tabla.='</tbody>';

      return $tabla;
    }*/

    /*--- ELIMINAR TOD@S LOS REQUERIMIENTOS DEL SERVICIO (SOLO REQUERIMIENTOS) (2020) ---*/
    public function delete_insumos_servicios($com_id){
      $productos = $this->model_producto->list_producto_programado($com_id,$this->gestion); // Lista de productos
      $nro=0;$nro_ins=0;
      //echo "eliminar productos";
      foreach($productos as $rowp){
        $insumos=$this->minsumos->lista_insumos_prod($rowp['prod_id']);
        foreach ($insumos as $rowi) {
          /*--------- delete temporalidad --------*/
          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('temporalidad_prog_insumo');

          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('_insumoproducto');

          /*--------- delete Insumos --------*/
          $this->db->where('ins_id', $rowi['ins_id']);
          $this->db->delete('insumos');

          if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
            $nro_ins++;
          }
        }
      }

      $update_prod= array(
        'fun_id' => $this->fun_id,
        'prod_ppto' => 1
      );
      $this->db->where('com_id', $com_id);
      $this->db->update('_productos', $update_prod);


      $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE '.$nro_ins.' REQUERIMIENTOS DEL SERVICIO ');
      redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
    }

    /*--- ELIMINAR LISTA TOTAL DE REQUERIMEITNOS POR UNIDAD*/
    public function delete_list_requerimientos($aper_id){
      $insumos=$this->model_insumo->insumos_por_unidad($aper_id);
      $nro_ins=0;
      foreach ($insumos as $rowi) {
        /*--------- delete temporalidad --------*/
        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('temporalidad_prog_insumo');

        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('_insumoproducto');

        /*--------- delete Insumos --------*/
        $this->db->where('ins_id', $rowi['ins_id']);
        $this->db->delete('insumos');

        if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
          $nro_ins++;
        }
      }

      return $nro_ins;
    }

    /*----------------- LISTA OPERACIONES PI (2019) ------------------*/
/*    public function operaciones_pi2019($proy_id,$com_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $productos = $this->model_producto->list_producto_programado($com_id,$this->gestion); // Lista de productos
      $tabla ='';
        $cont = 0;
        foreach($productos as $rowp){
          $cont++;
          $tabla .='<tr class="modo1">';
            $tabla.='<td title="C&Oacute;DIGO OPERACI&Oacute;N : '.$rowp['prod_cod'].'" align="center"><font color="blue" size="2"><b>'.$rowp['prod_cod'].'</b></font></td>';
            $tabla.='<td align="center">';
            $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a><br>
                     <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br>
                     <a href="'.site_url("").'/prog/list_act/'.$rowp['prod_id'].'" title="ACTIVIDADES DE LA OPERACI&oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/archivo.png" WIDTH="34" HEIGHT="34"/></a></td>';
            $tabla.='<td>'.$rowp['prod_producto'].'</td>';
            $tabla.='<td>'.$rowp['prod_resultado'].'</td>';
            $tabla.='<td>'.$rowp['indi_descripcion'].'</td>';
            $tabla.='<td>'.$rowp['prod_indicador'].'</td>';
            $tabla.='<td>'.$rowp['prod_linea_base'].'</td>';
            $tabla.='<td>'.$rowp['prod_meta'].'</td>';
            $tabla.='<td>'.$rowp['prod_ponderacion'].'%</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['enero'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['febrero'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['marzo'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['abril'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['mayo'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['junio'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['julio'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['agosto'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['septiembre'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['octubre'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['noviembre'].'</td>';
            $tabla.='<td bgcolor="#e5fde5">'.$rowp['diciembre'].'</td>';
            $tabla.='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
          $tabla .='</tr>';
        }

      return $tabla;
    }*/

    /*----------------- LISTA OPERACIONES PI (2020) ------------------*/
    public function operaciones_pi($proy_id,$com_id){
      $tabla ='';
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
      $fase = $this->model_faseetapa->get_id_fase($proy_id); //// recupera datos de la tabla fase activa
      $productos = $this->model_producto->list_operaciones_pi($com_id); // Lista de Operaciones
       $tabla .='<thead>
                  <tr class="modo1">
                    <th style="width:1%; text-align=center"><b>#</b></th>
                    <th style="width:1%; text-align=center"><b>E/B</b></th>
                    <th style="width:2%;"><b>COD. OR.</b></th>
                    <th style="width:2%;"><b>COD. OPE.</b></th>
                    <th style="width:15%;"><b>OPERACI&Oacute;N</b></th>
                    <th style="width:15%;"><b>RESULTADO</b></th>
                    <th style="width:10%;"><b>TIP. IND.</b></th>
                    <th style="width:10%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE '.($this->gestion-1).'</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:10%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                  </tr>
                </thead>
                <tbody>';

        $cont = 0;
        foreach($productos as $rowp){
          $cont++;
          $tabla .='<tr class="modo1">';
            $tabla.='<td>'.$cont.'</td>';
            
            $tabla.='<td align="center">';
            $tabla.='<a href="'.site_url("admin").'/prog/mod_prod/'.$rowp['prod_id'].'" title="MODIFICAR OPERACI&Oacute;N" class="btn btn-default"><img src="'.base_url().'assets/ifinal/modificar.png" WIDTH="33" HEIGHT="34"/></a><br>
                     <a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OPERACI&Oacute;N"  name="'.$rowp['prod_id'].'" ><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br>
                     <a href="'.site_url("").'/prog/requerimiento/'.$proy_id.'/'.$rowp['prod_id'].'" target="_blank" title="REQUERIMIENTOS DE LA ACTIVIDAD" class="btn btn-default"><img src="'.base_url().'assets/ifinal/insumo.png" WIDTH="33" HEIGHT="33"/></a><br>
                    </td>';
            $tabla.='<td style="width:2%;text-align=center"><b><font size=5 color=blue>'.$rowp['or_codigo'].'</font></b></td>';
            $tabla.='<td style="width:2%;text-align=center"><b><font size=5>'.$rowp['prod_cod'].'</font></b></td>';
            $tabla.='<td>'.$rowp['prod_producto'].'</td>';
            $tabla.='<td>'.$rowp['prod_resultado'].'</td>';
            $tabla.='<td>'.$rowp['indi_descripcion'].'</td>';
            $tabla.='<td>'.$rowp['prod_indicador'].'</td>';
            $tabla.='<td>'.$rowp['prod_linea_base'].'</td>';
            $tabla.='<td>'.$rowp['prod_meta'].'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['enero'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['febrero'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['marzo'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['abril'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['mayo'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['junio'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['julio'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['agosto'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['septiembre'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['octubre'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['noviembre'],2).'</td>';
            $tabla.='<td style="width:4%;" bgcolor="#e5fde5">'.round($rowp['diciembre'],2).'</td>';
            $tabla.='<td>'.$rowp['prod_fuente_verificacion'].'</td>';
          $tabla .='</tr>';
        }
        $tabla.='</tbody>';
      return $tabla;
    }

    /*--- ELIMINAR OPERACION PROY. DE INVERSION ---*/
    function delete_operacion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']);

          $nro=0; $nro_ins=0;
          $actividad=$this->model_actividad->list_act_anual($prod_id);
          foreach ($actividad as $rowa) {
            /*---------------------------------------*/
            $insumos = $this->model_actividad->insumo_actividad($rowa['act_id']);
            foreach ($insumos as $rowi) {
              /*--------- delete temporalidad --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('temporalidad_prog_insumo');

              $this->db->where('act_id', $rowa['act_id']);
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('_insumoactividad');

              /*--------- delete Insumos --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('insumos');

              if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
                $nro_ins++;
              }
            }

              /*------ delete Temporalidad Actividad -----*/
              $this->db->where('act_id', $rowa['act_id']);
              $this->db->delete('act_programado_mensual');

              /*------ delete Actividad -----*/
              $this->db->where('act_id', $rowa['act_id']);
              $this->db->delete('_actividades');
          }

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('prod_programado_mensual');

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('_productos');
          
          
          $prod=$this->model_producto->get_producto_id($prod_id);
          if(count($prod)==0){
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
    

    function delete_operacion2(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']);

          $nro=0; $nro_ins=0;
          $actividad=$this->model_actividad->list_act_anual($prod_id);
          foreach ($actividad as $rowa) {
            /*---------------------------------------*/
            $insumos = $this->model_actividad->insumo_actividad($rowa['act_id']);
            foreach ($insumos as $rowi) {
              /*--------- delete temporalidad --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('temporalidad_prog_insumo');

              $this->db->where('act_id', $rowa['act_id']);
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('_insumoactividad');

              /*--------- delete Insumos --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('insumos');

              if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
                $nro_ins++;
              }
            }

              /*------ delete Temporalidad Actividad -----*/
              $this->db->where('act_id', $rowa['act_id']);
              $this->db->delete('act_programado_mensual');

              /*------ delete Actividad -----*/
              $this->db->where('act_id', $rowa['act_id']);
              $this->db->delete('_actividades');
          }

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('prod_programado_mensual');

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('_productos');
          
          
          $prod=$this->model_producto->get_producto_id($prod_id);
          if(count($prod)==0){
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

    /*----- ELIMINAR VARIOS OPERACIONES SELECCIONADOS -----*/
    public function delete_operaciones(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $this->security->xss_clean($post['proy_id']);
          $pfec_id = $this->security->xss_clean($post['pfec_id']);
          $com_id = $this->security->xss_clean($post['com_id']);

          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 

          $nro=0; $nro_ins=0;
          if (!empty($_POST["req"]) && is_array($_POST["req"]) ) {
          foreach ( array_keys($_POST["req"]) as $como){
            /*--------- delete Insumosproducto --------*/
            $insumos = $this->model_producto->insumo_producto($_POST["req"][$como]); /// Insumo del producto
            foreach ($insumos as $rowi) {
              /*--------- delete temporalidad --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('temporalidad_prog_insumo');

              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('_insumoproducto');

              /*--------- delete Insumos --------*/
              $this->db->where('ins_id', $rowi['ins_id']);
              $this->db->delete('insumos');

              if(count($this->minsumos->get_insumo_producto($rowi['ins_id']))==0){
                $nro_ins++;
              }
            }

            /*------ delete Productos -----*/
              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->delete('prod_programado_mensual');

            /*------ delete Productos -----*/
              $this->db->where('prod_id', $_POST["req"][$como]);
              $this->db->delete('_productos');
            
            
            $prod=$this->model_producto->get_producto_id($_POST["req"][$como]);
            if(count($prod)==0){
              $nro++;
            }
            
          }

          $this->session->set_flashdata('success','SE ELIMINO CORRECTAMENTE '.$nro.' OPERACIONES SELECCIONADOS y '.$nro_ins.' REQUERIMIENTOS ');
          redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL ELIMINAR OPERACIONES');
          redirect(site_url("").'/admin/prog/list_prod/'.$com_id);
        }
      }
      else{
        echo "<font color=red><b>Error al Eliminar Operaciones</b></font>";
      }
    }
       

  /*------------ AGREGAR NUEVA OPERACION (2019) -------------------*/
  public function new_productos($com_id){
    $data['enlaces'] = $this->menu_modelo->get_Modulos_programacion(2);
    $data['componente'] = $this->model_componente->get_componente($com_id);

    if(count($data['componente'])!=0){
      $fase=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']);
      $data['nro_fase'] = $this->model_faseetapa->nro_fase($fase[0]['proy_id']); /// nro de fases y etapas registrados
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); 
      $data['id_f'] = $this->model_faseetapa->get_id_fase($fase[0]['proy_id']); //// recupera datos de la tabla fase activa
      $data['indi'] = $this->model_proyecto->indicador(); /// indicador
      $data['metas'] = $this->model_producto->tp_metas(); /// tp metas
      $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
      $data['mod']=1;
      if(count($this->model_producto->ult_operacion($com_id))!=0){
        $data['cod_ope']=$this->model_producto->ult_operacion($com_id);
      }
      else{
        $data['cod_ope']=0;
      }
      
      
      $this->load->view('admin/programacion/producto/form_prod', $data); 
    }
    else{
      redirect('admin/dashboard');
    }
  }

  /*------------- COMBO OBJETIVO ESTRATEGICO -----------------*/
    public function combo_acciones_estrategicos(){
      $salida = "";
      $id_pais = $_POST["elegido"];
      // construimos el combo de ciudades deacuerdo al pais seleccionado
      $combog = pg_query('select *
                          from _acciones_estrategicas
                          where obj_id='.$id_pais.' and acc_estado!=3
                          order by acc_id asc');
      $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE ACCI&Oacute;N ESTRATEGICA', 'cp1252', 'UTF-8') . "</option>";
      while ($sql_p = pg_fetch_row($combog)) {
          $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[2].".- ".$sql_p[3] . "</option>";
      }
      echo $salida;
    }

  /*------------- COMBO INDICADORES PEI -----------------*/
    public function combo_indicadores_pei(){
      $salida = "";
      $acc_id = $_POST["elegido"];

      $combog = pg_query(' select pt.*,rmp.*
                           from _acciones_estrategicas ae
                           Inner Join _resultado_mplazo as rmp On rmp.acc_id=ae.acc_id
                           Inner Join _pterminal_mplazo as pt On pt.rm_id=rmp.rm_id
                           where ae.acc_id='.$acc_id.' and rmp.rm_estado!=\'3\' and pt.ptm_estado!=\'3\'
                           order by pt.ptm_codigo desc');
      $salida .= "<option value=''>" . mb_convert_encoding('SELECCIONE INDICADOR', 'cp1252', 'UTF-8') . "</option>";
      while ($sql_p = pg_fetch_row($combog)) {
          $salida .= "<option value='" . $sql_p[0] . "'>" .$sql_p[2].".- ".$sql_p[5] . "</option>";
      }
      echo $salida;
    }

  /*---------------- VALIDA PRODUCTO (2019) ----------------------*/
  public function valida_producto_pi(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('tipo_i', 'Tipo de Indicador', 'required|trim');
        $componente = $this->model_componente->get_componente_pi($this->input->post('com_id'));
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
        $ae=$this->model_mestrategico->get_acciones_estrategicas($this->input->post('acc_id'));
        $cod_ope=$this->model_producto->ult_operacion($this->input->post('com_id')); /// ultima operacion
        
        if($this->input->post('tipo_i')==1){
          $tp_met=3;
        }
        else{
          $tp_met=$this->input->post('tp_met');
        }
        if ($this->form_validation->run()){
         /*----------------- INSERT OPERACION -------------------*/
          $data_to_store = array(
            'com_id' => $this->input->post('com_id'),
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_formula' => strtoupper($this->input->post('formula')),
            'prod_linea_base' => $this->input->post('lb'),
            'prod_meta' => $this->input->post('met'),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')), 
            'acc_id' => $ae[0]['ae'],
            'mt_id' => $tp_met,
            'fecha' => date("d/m/Y H:i:s"),
            'prod_cod'=>($cod_ope[0]['prod_cod']+1),
            'fun_id' => $this->fun_id,
          );
          $this->db->insert('_productos', $data_to_store);
          $id_pr=$this->db->insert_id(); ////// id del producto
         /*------------------------------------------------------*/
          $conf=$this->model_proyecto->configuracion(); //// configuracion gestion
          $nro_p=$conf[0]['conf_producto']+1;
          $update_conf = array('conf_producto' => $nro_p);
            $this->db->where('ide', $this->session->userdata("gestion"));
            $this->db->update('configuracion', $update_conf);
                  
          $gestion=$fase[0]['pfec_fecha_inicio'];

          if (!empty($_POST["m1"]) && is_array($_POST["m1"]) ){
              foreach ( array_keys($_POST["m1"]) as $como ){
                  if($_POST["m1"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,1,$_POST["m1"][$como]);
                  }
                  if($_POST["m2"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,2,$_POST["m2"][$como]);
                  }
                  if($_POST["m3"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,3,$_POST["m3"][$como]);
                  }
                  if($_POST["m4"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,4,$_POST["m4"][$como]);
                  }
                  if($_POST["m5"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,5,$_POST["m5"][$como]);
                  }
                  if($_POST["m6"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,6,$_POST["m6"][$como]);
                  }
                  if($_POST["m7"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,7,$_POST["m7"][$como]);
                  }
                  if($_POST["m8"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,8,$_POST["m8"][$como]);
                  }
                  if($_POST["m9"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,9,$_POST["m9"][$como]);
                  }
                  if($_POST["m10"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,10,$_POST["m10"][$como]);
                  }
                  if($_POST["m11"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,11,$_POST["m11"][$como]);
                  }
                  if($_POST["m12"][$como]!=0){
                      $this->model_producto->add_prod_gest($id_pr,$gestion,12,$_POST["m12"][$como]);
                  }
                  $gestion++;
              }
          }

          /*========================================= ACTUALIZANDO PONDERACIONES ========================*/
              $productos= $this->model_producto->list_prod($this->input->post('com_id'));
              $sumatoria_total=0;
              foreach ($productos as $rowp){
                  $suma_pa = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
                  $sumatoria_total=$sumatoria_total+$suma_pa[0]['monto_total'];
              }

              $ponderacion=0;
              foreach ($productos as $rowp){
                $suma_prod = $this->model_actividad->suma_monto_ponderado_total($rowp['prod_id']);
                if($sumatoria_total!=0){
                  $ponderacion=round((($suma_prod[0]['monto_total']/$sumatoria_total)*100),2);
                }

                $update_prod = array(
                    'prod_ponderacion' => $ponderacion
                );
                $this->db->where('prod_id', $rowp['prod_id']);
                $this->db->update('_productos', $update_prod);
              }
              /*==============================================================================================*/

              $producto=$this->model_producto->get_producto_id($id_pr);
              if(count($producto)==1){
                $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA OPERACI&Oacute;N :)');
              }
              else{
                $this->session->set_flashdata('danger','NO SE REGISTRO CORRECTAMENTE LA OPERACI&Oacute;N, VUELVA REGISTRAR :(');
              }

              redirect('admin/prog/list_prod/1/'.$fase[0]['pfec_id']."/".$proyecto[0]['proy_id'].'/'.$this->input->post('com_id').'');
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA OPERACI&Oacute;N :(');
          redirect(site_url("").'/admin/prog/new_prod/'.$this->input->post('com_id').'');
        }
        
    }
    else{
      echo "<center><font color='red'>Error, Vuelva a registrar la operaci&oacute;n !!!!</font></center>";
    }
  }


   /*------- Verifica Codigo Operacion ------*/ 
  function verif_codigo(){
    if($this->input->is_ajax_request()){
        $post = $this->input->post();

        $codigo= $this->security->xss_clean($post['codigo']); /// Codigo
        $com_id= $this->security->xss_clean($post['com_id']); /// Componente id

        $verif_com_ope=$this->model_producto->verif_componente_operacion($com_id,$codigo);
        if(count($verif_com_ope)!=0){
          echo "true"; ///// no existe un CI registrado
        }
        else{
          echo "false"; //// existe el CI ya registrado
        } 
    }else{
      show_404();
    }
  }

  
   /*------- VALIDAR MODIFICACION PRODUCTO (2019) --------*/
  public function modificar_producto_pi(){
    if ($this->input->server('REQUEST_METHOD') === 'POST'){
        $this->form_validation->set_rules('prod', 'Producto', 'required|trim');
        $this->form_validation->set_rules('indicador', 'Indicador', 'required|trim');
        $this->form_validation->set_rules('met', 'Meta', 'required|trim');

        $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
        $componente = $this->model_componente->get_componente_pi($producto[0]['com_id']);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);

        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($this->input->post('or_id'));
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if ($this->form_validation->run()){
          /*-------------- UPDATE OPERACION --------------*/
          $update_prod = array(
            'prod_producto' => strtoupper($this->input->post('prod')),
            'prod_resultado' => strtoupper($this->input->post('resultado')),
            'indi_id' => $this->input->post('tipo_i'),
            'prod_indicador' => strtoupper($this->input->post('indicador')),
            'prod_linea_base' => $this->input->post('lb'),
            'prod_meta' => $this->input->post('met'),
            'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')),
            'estado' => 2,
            'or_id' => $this->input->post('or_id'),
            'acc_id' => $ae,
            'fecha' => date("d/m/Y H:i:s"),
            'fun_id' => $this->fun_id,
            );
          $this->db->where('prod_id', $this->input->post('prod_id'));
          $this->db->update('_productos', $update_prod);
          /*----------------------------------------------*/

          $this->model_producto->delete_prod_gest($this->input->post('prod_id'));
          
          for ($i=1; $i <=12 ; $i++) {
            if($this->input->post('m'.$i)!=0){
              $this->model_producto->add_prod_gest($this->input->post('prod_id'),$this->gestion,$i,$this->input->post('m'.$i));
            }
          }

            $producto=$this->model_producto->get_producto_id($this->input->post('prod_id'));
            if($producto[0]['estado']==2){
              $this->session->set_flashdata('success','SE MODIFICO CORRECTAMENTE LA OPERACI&Oacute;N :)');
            }
            else{
              $this->session->set_flashdata('danger','NO SE MODIFICO CORRECTAMENTE LA OPERACI&Oacute;N, VUELVA REGISTRAR :(');
            }

            redirect('admin/prog/list_prod/'.$producto[0]['com_id'].'');
          }
          else {
            $this->session->set_flashdata('danger','ERROR AL MODIFICAR LA OPERACI&Oacute;N :(');
            redirect('admin/prog/mod_prod/'.$this->input->post('prod_id').'/false');
          }
      }
   }

   
 /*------ ELIMINA LOGICAMENTE PRODUCTOS Y SUS DEPENDIENTES ------*/
    function desactiva_producto(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
          $proy_id = $this->security->xss_clean($post['proy_id']); /// proy id

          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); 
          $insumos = $this->model_producto->insumo_producto($prod_id); /// Insumo del producto

          foreach ($insumos as $rowi) {
            /*--------- delete temporalidad --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('temporalidad_prog_insumo');

            $this->db->where('prod_id', $prod_id);
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('_insumoproducto');

            /*--------- delete Insumos --------*/
            $this->db->where('ins_id', $rowi['ins_id']);
            $this->db->delete('insumos');
          }

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('prod_programado_mensual');

          /*------ delete Productos -----*/
            $this->db->where('prod_id', $prod_id);
            $this->db->delete('_productos');

          $prod=$this->model_producto->get_producto_id($prod_id);
          if(count($prod)==0){
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

    /*----- MIGRACION DE REQUERIMIENTOS POR OPERACIONES (2019) -------*/
    function importar_requerimientos_operaciones(){
      if ($this->input->post()) {
          $post = $this->input->post();
          $proy_id = $post['proy_id']; /// proy id
          $pfec_id = $post['pfec_id']; /// pfec id
          $com_id = $post['com_id']; /// com id
          
          $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// DATOS DEL PROYECTO
          $fase = $this->model_faseetapa->get_id_fase($proy_id); //// DATOS DE LA FASE ACTIVA

          $monto_asig=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],1);
          $monto_prog=$this->model_ptto_sigep->suma_ptto_accion($proyecto[0]['aper_id'],2);
          $saldo=round(($monto_asig[0]['monto']-$monto_prog[0]['monto']),2);

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');
          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {

            $lineas = file($archivotmp);
            if($this->suma_monto_total($lineas)<=$saldo){
              /*------------------- Migrando ---------------*/
                $lineas = file($archivotmp);
                $i=0;
                $nro=0;
                //Recorremos el bucle para leer línea por línea
                foreach ($lineas as $linea_num => $linea){ 
                  if($i != 0){
                      $datos = explode(";",$linea);
                      
                      if(count($datos)==21){
                        $cod_ope = (int)$datos[0]; //// Codigo Operacion
                        $cod_partida = (int)$datos[1]; //// Codigo partida
                        $verif_com_ope=$this->model_producto->verif_componente_operacion($com_id,$cod_ope);
                        $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                        $detalle = utf8_encode(trim($datos[3])); //// descripcion
                        $unidad = utf8_encode(trim($datos[4])); //// Unidad
                        $cantidad = (int)$datos[5]; //// Cantidad
                        $unitario = (float)$datos[6]; //// Costo Unitario
                        $total = (float)$datos[7]; //// Costo Total
                        if(!is_numeric($unitario)){
                          if($cantidad!=0){
                            $unitario=round(($total/$cantidad),2); 
                          }
                        }

                        $var=8;
                        for ($i=1; $i <=12 ; $i++) {
                          $m[$i]=(float)$datos[$var]; //// Mes i
                          if($m[$i]==''){
                            $m[$i]=0;
                          }
                          $var++;
                        }

                        $observacion = utf8_encode(trim($datos[20])); //// Observacion

                        if(count($verif_com_ope)==1 & count($par_id)!=0 & $cod_partida!=0){
                           $nro++;
                           $query=$this->db->query('set datestyle to DMY');
                            $data_to_store = array( 
                            'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                            'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                            'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                            'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                            'ins_costo_unitario' => $unitario, /// Costo Unitario
                            'ins_costo_total' => $total, /// Costo Total
                            'ins_tipo' => 1, /// Ins Tipo
                            'ins_unidad_medida' => strtoupper($unidad), /// Insumo Unidad de Medida
                            'par_id' => $par_id[0]['par_id'], /// Partidas
                            'ins_observacion' => strtoupper($observacion), /// Observacion
                            'fecha_creacion' => date("d/m/Y H:i:s"),
                            'fun_id' => $this->session->userdata("fun_id"), /// Funcionario
                            'aper_id' => $proyecto[0]['aper_id'], /// aper id
                            'num_ip' => $this->input->ip_address(), 
                            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                            );
                            $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                            $ins_id=$this->db->insert_id();

                            /*----------------------------------------------------------*/
                            $data_to_store2 = array( ///// Tabla InsumoProducto
                              'prod_id' => $verif_com_ope[0]['prod_id'], /// act_id
                              'ins_id' => $ins_id, /// ins_id
                            );
                            $this->db->insert('_insumoproducto', $data_to_store2);
                           /*----------------------------------------------------------*/
                            $gestion_fase=$fase[0]['pfec_fecha_inicio'];

                            /*---------------- Recorriendo Gestiones de la Fase -----------------------*/
                            for ($g=$fase[0]['pfec_fecha_inicio']; $g <=$fase[0]['pfec_fecha_fin'] ; $g++){
                                $data_to_store = array( 
                                  'ins_id' => $ins_id, /// Id Insumo
                                  'g_id' => $g, /// Gestion
                                  'insg_monto_prog' => $total, /// Monto programado
                                );
                                $this->db->insert('insumo_gestion', $data_to_store); ///// Guardar en Tabla Insumo Gestion
                                $insg_id=$this->db->insert_id();

                                $ptto_fase_gestion = $this->model_faseetapa->fase_gestion($fase[0]['id'],$g); //// DATOS DE LA FASE GESTION
                                $fuentes=$this->model_faseetapa->fase_presupuesto_id($ptto_fase_gestion[0]['ptofecg_id']);

                                if(count($fuentes)==1){
                                  /*------------------- Guardando Fuente Financiamiento ------*/
                                  $query=$this->db->query('set datestyle to DMY');
                                  $data_to_store3 = array( 
                                  'insg_id' => $insg_id, /// Id Insumo gestion
                                  'ifin_monto' => $total, /// Monto programado
                                  'ifin_gestion' => $g, /// Gestion
                                  'ffofet_id' => $fuentes[0]['ffofet_id'], /// ffotet id
                                  'ff_id' => $fuentes[0]['ff_id'], /// ff id
                                  'of_id' => $fuentes[0]['of_id'], /// ff id
                                  'nro_if' => 1, /// Nro if
                                  );
                                  $this->db->insert('insumo_financiamiento', $data_to_store3); ///// Guardar en Tabla Insumo Financiamiento
                                  $ifin_id=$this->db->insert_id();

                                  for ($p=1; $p <=12 ; $p++) { 
                                    if($m[$p]!=0 & is_numeric($unitario)){
                                      $data_to_store4 = array( 
                                        'ifin_id' => $ifin_id, /// Id Insumo Financiamiento
                                        'mes_id' => $p, /// Mes 
                                        'ipm_fis' => $m[$p], /// Valor mes
                                      );
                                      $this->db->insert('ifin_prog_mes', $data_to_store4); ///// Guardar en Tabla Insumo Financiamiento Programado Mes
                                    }
                                  }
                                  /*-----------------------------------------------------------*/ 
                                }
                            }

                        }

                      }

                    }
                    $i++;
                  }

                  redirect('admin/prog/list_prod/1/'.$pfec_id.'/'.$proy_id.'/'.$com_id.'');
                /*--------------------------------------------*/
            }
            else{
              $this->session->set_flashdata('danger','COSTO PROGRAMADO A SUBIR ES MAYOR AL SALDO POR PROGRAMAR. VERIFIQUE PLANTILLA A MIGRAR');
              redirect('admin/prog/list_prod/1/'.$pfec_id.'/'.$proy_id.'/'.$com_id.'/false');
            }
          } 
          elseif (empty($file_basename)) {
            $this->session->set_flashdata('danger','POR FAVOR SELECCIONE ARCHIVO CSV');
            redirect('admin/prog/list_prod/1/'.$pfec_id.'/'.$proy_id.'/'.$com_id.'/false');
          } 
          elseif ($filesize > 100000000) {
            $this->session->set_flashdata('danger','TAMAÑO DEL ARCHIVO');
            redirect('admin/prog/list_prod/1/'.$pfec_id.'/'.$proy_id.'/'.$com_id.'/false');
          } 
          else {
            $mensaje = "SOLO SE PERMITEN ESTOS ARCHIVOS : " . implode(', ', $allowed_file_types);
            $this->session->set_flashdata('danger',$mensaje);
            redirect('admin/prog/list_prod/1/'.$pfec_id.'/'.$proy_id.'/'.$com_id.'/false');
          }

      } else {
          show_404();
      }
    }


    /*----- SUMA TOTAL MONTO REQUERIMIENTOS A IMPORTAR -------*/
    function suma_monto_total($requerimientos){
      $i=0; $suma=0;
      foreach ($requerimientos as $linea_num => $linea){ 
        if($i != 0){
            $datos = explode(";",$linea);
            
            if(count($datos)==21){
                $suma=$suma+(float)$datos[7];
            }
          }

          $i++;
      }

      return $suma;
    }  


    /*------ REPORTE OPERACIONES POR COMPONENTE(2019 - 2020 - 2021) ----*/
    public function reporte_operacion_componente($com_id){
      $data['componente'] = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      if(count($data['componente'])!=0){
        if($this->gestion>2020){
          redirect(site_url("").'/prog/reporte_form4/'.$com_id.''); /// Reporte Form4 2021
        }
        else{
          $this->reporte_poa_2020($com_id); /// Reporte POA 2019-2020
        }
      }
      else{
        echo "Error !!!";
      }
    }


    /*------ Para reporte poa 2019-2020 -----*/
    public function reporte_poa_2020($com_id){
      $data['componente'] = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $data['mes'] = $this->mes_nombre();
      $data['fase']=$this->model_faseetapa->get_fase($data['componente'][0]['pfec_id']); /// DATOS FASE
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['fase'][0]['proy_id']); //// DATOS PROYECTO

      $data['cabecera']=$this->cabecera_2020($com_id,1); /// Cabecera
      if($this->gestion==2019){ /// GESTION 2019
        if($data['proyecto'][0]['tp_id']==1){
          $data['operaciones']=$this->componente_operacion_pi_nuevo($com_id);
        }
        else{
          $data['operaciones']=$this->componente_operacion_nuevo($com_id);
        }
        
        $this->load->view('admin/programacion/producto/reporte_productos', $data);
      }
      else{ /// Para la gestion 2020
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['fase'][0]['proy_id']);
        }

        $data['operaciones']=$this->componente_operaciones($com_id); /// Reporte Gasto Corriente, Proyecto de Inversion 2020
        $this->load->view('admin/programacion/producto/reporte_productos2020', $data);
      }
    }


    /*----- TITULO SERVICIO OPERACION (2020 - Operaciones) tp:1 (pdf), 2:(Excel) -----*/
    public function cabecera_2020($com_id,$tp){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      //$proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']);

      $tabla='';
      $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr>
                      <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b>'.$this->session->userdata('entidad').'</b></td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%"><b>DIR. ADM.</b></td>
                      <td style="width:90%;">: '.strtoupper($proyecto[0]['dep_departamento']).'</td>
                    </tr>
                    <tr style="font-size: 8pt;">
                      <td style="width:10%; height: 1.2%"><b>UNI. EJEC.</b></td>';
                      if($tp==1){  // pdf
                        $tabla.='<td style="width:90%;">: '.strtoupper($proyecto[0]['dist_distrital']).'</td>';
                      }
                      else{ // excel
                        $tabla.='<td style="width:90%;">: '.mb_convert_encoding(strtoupper($proyecto[0]['dist_distrital']), 'cp1252', 'UTF-8').'</td>';
                      }
                      $tabla.='
                    </tr>
                    <tr style="font-size: 8pt;">';
                      if($this->gestion!=2020){
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='PROY. INV. ';
                          }
                          else{
                            $tabla.='ACTIVIDAD ';
                          }
                        $tabla.='</b></td>';
                        $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                      }
                      else{
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='PROY. INV. ';
                          }
                          else{
                            $tabla.=''.$proyecto[0]['tipo_adm'].' ';
                          }
                        $tabla.='</b></td>';
                        if($tp==1){ /// pdf
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']).'</td>';
                          }
                          else{
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.strtoupper($proyecto[0]['act_descripcion']).'-'.$proyecto[0]['abrev'].'</td>';
                          }
                        }
                        else{ /// Excel
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(strtoupper($proyecto[0]['proy_nombre']), 'cp1252', 'UTF-8').'</td>';
                          }
                          else{
                            $tabla.='<td>: '.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.$proyecto[0]['tipo'].' - '.mb_convert_encoding(strtoupper($proyecto[0]['act_descripcion']), 'cp1252', 'UTF-8').'-'.$proyecto[0]['abrev'].'</td>';
                          }
                        }    
                    }
                    $tabla.='
                    </tr>
                    <tr style="font-size: 8pt;">';
                      if($this->gestion!=2020){
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='COMPONENTE ';
                          }
                          else{
                            $tabla.='SUB ACTIVIDAD ';
                          }
                        $tabla.='</b></td>';
                      }
                      else{
                        $tabla.='<td style="height: 1.2%"><b>';
                          if($proyecto[0]['tp_id']==1){
                            $tabla.='COMPONENTE ';
                          }
                          else{
                            $tabla.='SERVICIO ';
                          }
                        $tabla.='</b></td>';
                      }
                      if($tp==1){ // pdf
                        $tabla.='<td>: '.strtoupper($componente[0]['com_componente']).'</td>';
                      }
                      else{ // excel
                        $tabla.='<td>: '.mb_convert_encoding(strtoupper($componente[0]['com_componente']), 'cp1252', 'UTF-8').'</td>';
                      }
                      $tabla.='
                    </tr>
                </table>';
      return $tabla;
    }


    /*----- SERVICIO ACTIVIDAD (2020 - Operaciones, Proyectos de Inversion) - REPORTE ----*/
    public function componente_operaciones($com_id){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $mes = $this->mes_nombre();
      
      $tabla='';
      
      if($proyecto[0]['tp_id']==1){ /// Proyectos de Inversion
        $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%; color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:11%; color:#FFF;">ACTIVIDAD</th>
                    <th style="width:11%; color:#FFF;">RESULTADO</th>
                    <th style="width:11%; color:#FFF;">INDICADOR</th>
                    <th style="width:2%; color:#FFF;">LB.</th>
                    <th style="width:2.5%; color:#FFF;">META</th>
                    <th style="width:2.5%; color:#FFF;">ENE.</th>
                    <th style="width:2.5%; color:#FFF;">FEB.</th>
                    <th style="width:2.5%; color:#FFF;">MAR.</th>
                    <th style="width:2.5%; color:#FFF;">ABR.</th>
                    <th style="width:2.5%; color:#FFF;">MAY.</th>
                    <th style="width:2.5%; color:#FFF;">JUN.</th>
                    <th style="width:2.5%; color:#FFF;">JUL.</th>
                    <th style="width:2.5%; color:#FFF;">AGO.</th>
                    <th style="width:2.5%; color:#FFF;">SEPT.</th>
                    <th style="width:2.5%; color:#FFF;">OCT.</th>
                    <th style="width:2.5%; color:#FFF;">NOV.</th>
                    <th style="width:2.5%; color:#FFF;">DIC.</th>
                    <th style="width:8.5%; color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%; color:#FFF;">PPTO.</th>   
                  </tr>
                </thead>
                <tbody>';
                $operaciones=$this->model_producto->list_operaciones_pi($com_id);  /// 2020
                $nro=0;
                foreach($operaciones as $rowp){
                  $nro++;
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $tp='';
                  if($rowp['indi_id']==2){
                    $tp='%';
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }


                  $tabla.='
                  <tr>
                    <td style="height:12px;">'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 11%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width:11%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width:2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width:2.5%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['enero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['febrero'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['marzo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['abril'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['mayo'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['junio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['julio'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['agosto'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['septiembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['octubre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['noviembre'],2).''.$tp.'</td>
                    <td style="width:2.5%;" align=center>'.round($rowp['diciembre'],2).''.$tp.'</td>
                    <td style="width:8.5%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';            
                }
          $tabla.='
                </tbody>
              </table>';

      }
      else{ //// Gasto Corriente

         $tabla.='<table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                <thead>
                 <tr style="font-size: 7px;" bgcolor=#1c7368 align=center>
                    <th style="width:1%;height:15px;color:#FFF;">#</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACP.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>OPE.</th>
                    <th style="width:2%;color:#FFF;">COD.<br>ACT.</th> 
                    <th style="width:10%;color:#FFF;">ACTIVIDAD</th>
                    <th style="width:9.5%;color:#FFF;">RESULTADO</th>
                    <th style="width:7%;color:#FFF;">UNIDAD RESPONSABLE</th>
                    <th style="width:9%;color:#FFF;">INDICADOR</th>
                    <th style="width:2%;color:#FFF;">LB.</th>
                    <th style="width:3%;color:#FFF;">META</th>
                    <th style="width:3%;color:#FFF;">ENE.</th>
                    <th style="width:3%;color:#FFF;">FEB.</th>
                    <th style="width:3%;color:#FFF;">MAR.</th>
                    <th style="width:3%;color:#FFF;">ABR.</th>
                    <th style="width:3%;color:#FFF;">MAY.</th>
                    <th style="width:3%;color:#FFF;">JUN.</th>
                    <th style="width:3%;color:#FFF;">JUL.</th>
                    <th style="width:3%;color:#FFF;">AGO.</th>
                    <th style="width:3%;color:#FFF;">SEPT.</th>
                    <th style="width:3%;color:#FFF;">OCT.</th>
                    <th style="width:3%;color:#FFF;">NOV.</th>
                    <th style="width:3%;color:#FFF;">DIC.</th>
                    <th style="width:9%;color:#FFF;">VERIFICACI&Oacute;N</th> 
                    <th style="width:5%;color:#FFF;">PPTO.</th>   
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                $operaciones=$this->model_producto->list_operaciones($com_id);
                
                foreach($operaciones as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);
                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=number_format(0, 2, '.', ',');
                  if(count($monto)!=0){
                    $ptto="<b>".number_format($monto[0]['total'], 2, ',', '.')."</b>";
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr style="font-size: 6.5px;" bgcolor="'.$color.'">
                    <td style="height:12px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center; font-size: 8px;" bgcolor="#eceaea"><b>'.$rowp['prod_cod'].'</b></td>
                    <td style="width: 10%; text-align: left;font-size: 7px;">'.$rowp['prod_producto'].'</td>
                    <td style="width: 9.5%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                    <td style="width: 7%; text-align: left;">'.strtoupper($rowp['prod_unidades']).'</td>
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                    <td style="width: 2%; text-align: center;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).'</td>';

                    if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['enero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['febrero'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['marzo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['abril'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['mayo'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['junio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['julio'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['agosto'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['septiembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['octubre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['noviembre'],2).''.$tp.'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5" align=center>'.round($programado[0]['diciembre'],2).''.$tp.'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace" align=center>0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                    <td style="width: 5%; text-align: right;">'.$ptto.'</td>
                  </tr>';

                }
                $tabla.='
                </tbody>
              </table>';
      }
      return $tabla;
    }

    /*--- LISTA DE ACTIVIDADES (2020 - PROY INVERSION) ---*/
    public function list_actividades($prod_id){
      $tabla='';
      $actividades = $this->model_actividad->list_actividad_gestion($prod_id,$this->gestion); /// actividades-Proy Inversion
      
      if(count($actividades)!=0){
        $nro=0;
        foreach($actividades as $row){
          $monto=$this->model_actividad->monto_insumoactividad($row['act_id']);
          $tp='';
          if($row['indi_id']==2){
            $tp='%';
          }

          $ptto=0;
          if(count($monto)!=0){
            $ptto=$monto[0]['total'];
          }

          $tabla.='
            <tr bgcolor="#f5f0f0">
              <td style="height:12px;"></td>
              <td style="width: 2%; text-align: center; font-size: 7.5px;"></td>
              <td></td>
              <td></td>
              <td></td>
              <td style="width: 9%; text-align: left;"></td>
              <td style="width: 9%; text-align: left;"></td>
              <td style="width: 9%; text-align: left;">'.$row['act_actividad'].'</td>
              <td style="width: 9%; text-align: left;">'.$row['act_indicador'].'</td>
              <td style="width: 2%; text-align: center;">'.round($row['act_linea_base'],2).'</td>
              <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($row['act_meta'],2).'</td>
              <td style="width: 3%; text-align: center;">'.round($row['enero'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['febrero'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['marzo'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['abril'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['mayo'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['junio'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['julio'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['agosto'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['septiembre'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['octubre'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['noviembre'],2).''.$tp.'</td>
              <td style="width: 3%; text-align: center;">'.round($row['diciembre'],2).''.$tp.'</td>
              <td style="width: 9%; text-align: left;">'.$row['act_fuente_verificacion'].'</td>
              <td style="width: 5%; text-align: right;">'.number_format($ptto, 2, ',', '.').'</td>
            </tr>';
        }
      }

      return $tabla;
    }



    /*-------------- COMPONENTE OPERACION (2019 - Productos)-----------------*/
    public function componente_operacion_nuevo($com_id){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $mes = $this->mes_nombre();
      $tabla='';
      $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <thead>
                 <tr class="modo1">
                    <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">ACCI&Oacute;N ESTRATEGICA</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACI&Oacute;N</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">RESULTADO</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">INDICADOR</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">LB.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">META</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">ENE.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">FEB.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">MAR.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">ABR.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">MAY.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">JUN.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">JUL.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">AGO.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">SEPT.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">OCT.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">NOV.</th>
                    <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">DIC.</th>
                    <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF">VERIFICACI&Oacute;N</th> 
                    <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PPTO.</th>   
                    <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">COD. PPTO.</th>   
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                if(count($obj_est)!=0){
                  foreach($obj_est as $rowo){
                    $productos=$this->model_producto->list_producto_programado_oestrategico($com_id,$this->gestion,$rowo['obj_id'],$rowo['gi'],$rowo['gf']); /// Productos

                    if(count($productos)!=0){
                      $tabla.='<tr class="modo1" bgcolor="#9cdcd4"><td colspan="22" style="width: 100%; text-align: left" style="height:13px;">OBJ. EST. '.$rowo['obj_codigo'].'.- '.$rowo['obj_descripcion'].'</td></tr>';
                      foreach($productos as $rowp){
                        $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                        $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                        $color=''; $tp='';
                          if($rowp['indi_id']==1){
                            if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                              $color='#fbd5d5';
                            }
                          }
                          elseif ($rowp['indi_id']==2) {
                            $tp='%';
                            if($rowp['mt_id']==3){
                              if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                                $color='#fbd5d5';
                              }
                            }
                          }

                          $ptto=0;
                          if(count($monto)!=0){
                            $ptto=$monto[0]['total'];
                          }

                          $nro++;

                          $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
                            $tabla.=' <td style="width: 1%; text-align: left;" style="height:11px;">'.$nro.'</td>
                                      <td style="width: 10%; text-align: left;">'.$rowp['acc_codigo'].'.- '.$rowp['acc_descripcion'].'</td>
                                      <td style="width: 10%; text-align: left;">'.$rowp['prod_producto'].'</td>
                                      <td style="width: 10%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                                      <td style="width: 10%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['enero'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['febrero'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['marzo'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['abril'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['mayo'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['junio'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['julio'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['agosto'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['septiembre'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['octubre'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['noviembre'],2).''.$tp.'</td>
                                      <td style="width: 3%; text-align: right;">'.round($rowp['diciembre'],2).''.$tp.'</td>
                                      <td style="width: 8%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                                      <td style="width: 4%; text-align: right;">'.number_format($ptto, 2, ',', '.').'</td>
                                      <td style="width: 4%; text-align: center;" bgcolor="#dedcdc">'.$rowp['prod_cod'].'</td>';         
                            $tabla.='</tr>';
                      }
                    }
                  }
                }
                else{
                  $tabla.='<tr class="modo1"><td colspan="22" style="width: 100%; text-align: left;">SIN OPERACIONES</td></tr>';
                }
                $tabla.='
                </tbody>
              </table>';

      return $tabla;
    }

    /*-------------- COMPONENTE OPERACION (2019 - Actividades)-----------------*/
    public function componente_operacion_pi_nuevo($com_id){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
      $componente = $this->model_componente->get_componente_pi($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $mes = $this->mes_nombre();
      $tabla='';
      $tabla.='<table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <thead>
                 <tr class="modo1">
                  <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                  <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">ACCI&Oacute;N ESTRATEGICA</th>
                  <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">OPERACI&Oacute;N</th>
                  <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">RESULTADO</th>
                  <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">ACTIVIDAD</th>
                  <th style="width:7%;" style="background-color: #1c7368; color: #FFFFFF">INDICADOR</th>
                  <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">LB.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">META</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">ENE.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">FEB.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">MAR.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">ABR.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">MAY.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">JUN.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">JUL.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">AGO.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">SEPT.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">OCT.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">NOV.</th>
                  <th style="width:2.5%;" style="background-color: #1c7368; color: #FFFFFF">DIC.</th>
                  <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF">VERIFICACI&Oacute;N</th> 
                  <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PPTO.</th>
                </tr>    
               
                </thead>
                <tbody>';
                $nro=0;
                if(count($obj_est)!=0){
                  foreach($obj_est as $rowo){
                    $tabla.='<tr class="modo1" bgcolor="#9cdcd4"><td colspan="22" style="width: 100%; text-align: left" style="height:13px;">OBJ. EST. '.$rowo['obj_codigo'].'.- '.$rowo['obj_descripcion'].'</td></tr>';
                    $productos=$this->model_producto->list_producto_programado_oestrategico($com_id,$this->gestion,$rowo['obj_id'],$rowo['gi'],$rowo['gf']); /// Productos
                    foreach($productos as $rowp){
                      $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                      $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                      $color=''; $tp='';
                        if($rowp['indi_id']==1){
                          if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta']){
                            $color='#fbd5d5';
                          }
                        }
                        elseif ($rowp['indi_id']==2) {
                          $tp='%';
                          if($rowp['mt_id']==3){
                            if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta']){
                              $color='#fbd5d5';
                            }
                          }
                        }

                        $ptto=0;
                        if(count($monto)!=0){
                          $ptto=$monto[0]['total'];
                        }

                        $nro++;

                        $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
                          $tabla.=' <td style="width: 1%; text-align: left;" style="height:11px;">'.$nro.'</td>
                                    <td style="width: 9%; text-align: left;">'.$rowp['acc_codigo'].'.- '.$rowp['acc_descripcion'].'</td>
                                    <td style="width: 10%; text-align: left;">'.$rowp['prod_producto'].'</td>
                                    <td style="width: 9%; text-align: left;">'.$rowp['prod_resultado'].'</td>
                                    <td style="width: 10%; text-align: left;"></td>
                                    <td style="width: 7%; text-align: left;">'.$rowp['prod_indicador'].'</td>
                                    <td style="width: 3%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['enero'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['febrero'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['marzo'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['abril'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['mayo'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['junio'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['julio'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['agosto'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['septiembre'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['octubre'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['noviembre'],2).''.$tp.'</td>
                                    <td style="width: 2.5%; text-align: right;">'.round($rowp['diciembre'],2).''.$tp.'</td>
                                    <td style="width: 8%; text-align: left;">'.$rowp['prod_fuente_verificacion'].'</td>
                                    <td style="width: 5%; text-align: right;"></td>';         
                          $tabla.='</tr>';
                          $tabla.=''.$this->actividades_2019($rowp['prod_id'],$nro).'';
                    }
                  }
                }
                else{
                  $tabla.='<tr class="modo1"><td colspan="22" style="width: 100%; text-align: left;">SIN OPERACIONES</td></tr>';
                }
                $tabla.='
                </tbody>
              </table>';

      return $tabla;
    }


    /*------ LISTA DE OPERACIONES (2020) -----*/
      public function rep_list_operaciones($com_id,$tp){
        $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
        $componente=$this->model_componente->get_componente_pi($com_id);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
        $tabla='';

        if($tp==1){
          $tab='table border="0" cellpadding="0" cellspacing="0" class="tabla" width:100%;';
        }
        elseif($tp==2){
          $tabla .='<style>
                    table{font-size: 9px;
                      width: 100%;
                      max-width:1550px;
                      overflow-x: scroll;
                      }
                      th{
                        padding: 1.4px;
                        text-align: center;
                        font-size: 9px;
                      }
                    </style>';
          $tab='table border="1" cellpadding="0" cellspacing="0" class="tabla"';
        }
        


        $tabla.='<'.$tab.'>
                  <thead>
                    <tr class="modo1" style="height:45px;">
                      <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:12px;">#</th>
                      <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACE.</th>
                      <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACP.</th>
                      <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF">COD. OPE.</th>
                      <th style="width:2%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACT.</th> 
                      <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">ACTIVIDAD</th>
                      <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">RESULTADO</th>
                      <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">INDICADOR</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">LINEA BASE '.($this->gestion-1).'</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">META</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">ENE.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">FEB.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">MAR.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">ABR.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">MAY.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">JUN.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">JUL.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">AGO.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">SEPT.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">OCT.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">NOV.</th>
                      <th style="width:3%;" style="background-color: #1c7368; color: #FFFFFF">DIC.</th>
                      <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">VERIFICACI&Oacute;N</th> 
                      <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PPTO.</th>   
                    </tr>
                  </thead>
                <tbody>';
                $nro=0;
                $operaciones=$this->model_producto->list_operaciones($com_id);  /// 2020
                foreach($operaciones as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                  $programado=$this->model_producto->producto_programado($rowp['prod_id'],$this->gestion);

                  $color=''; $tp='';
                  if($rowp['indi_id']==1){
                    if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                      $color='#fbd5d5';
                    }
                  }
                  elseif ($rowp['indi_id']==2) {
                    $tp='%';
                    if($rowp['mt_id']==3){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                  }

                  $ptto=0;
                  if(count($monto)!=0){
                    $ptto=$monto[0]['total'];
                  }

                  $color_or='';
                  if($rowp['or_id']==0){
                    $color_or='#fbd5d5';
                  }

                  $nro++;
                  $tabla.=
                  '<tr class="modo1" bgcolor="'.$color.'">
                    <td style="height:40px;" bgcolor='.$color_or.'>'.$nro.'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['acc_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['og_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor='.$color_or.' >'.$rowp['or_codigo'].'</td>
                    <td style="width: 2%; text-align: center;" bgcolor="#dedcdc">'.$rowp['prod_cod'].'</td>
                    <td style="width: 9%; text-align: left;">'.mb_convert_encoding(''.$rowp['prod_producto'], 'cp1252', 'UTF-8').'</td>
                    <td style="width: 9%; text-align: left;">'.mb_convert_encoding(''.$rowp['prod_resultado'], 'cp1252', 'UTF-8').'</td>
                    <td style="width: 9%; text-align: left;">'.mb_convert_encoding(''.$rowp['prod_indicador'], 'cp1252', 'UTF-8').'</td>
                    <td style="width: 3%; text-align: right;">'.round($rowp['prod_linea_base'],2).'</td>
                    <td style="width: 3%; text-align: right;">'.round($rowp['prod_meta'],2).'</td>';

                     if(count($programado)!=0){
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['enero'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['febrero'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['marzo'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['abril'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['mayo'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['junio'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['julio'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['agosto'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['septiembre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['octubre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['noviembre'],2).'</td>';
                      $tabla.='<td style="width:3%;" bgcolor="#e5fde5">'.round($programado[0]['diciembre'],2).'</td>';
                    }
                    else{
                      for ($i=1; $i <=12 ; $i++) { 
                        $tabla.='<td bgcolor="#f5cace">0.00</td>';
                      }
                    }

                    $tabla.='
                    <td style="width: 9%; text-align: left;">'.mb_convert_encoding(''.$rowp['prod_fuente_verificacion'], 'cp1252', 'UTF-8').'</td>
                    <td style="width: 4%; text-align: right;">'.number_format($ptto, 2, ',', '.').'</td>
                  </tr>';
                }
                $tabla .=
                '</tbody>
                </table>';

        return $tabla;
    }

    /*--------------- LISTA DE OPERACIONES PROYECTO DE INVERSION (2019) ------------*/
    public function rep_list_operaciones_pi_2019($com_id,$tp){
      $obj_est=$this->model_producto->list_oestrategico($com_id); /// Objetivos Estrategicos
        $componente=$this->model_componente->get_componente_pi($com_id);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
        $tabla='';

        if($tp==1){
          $tab='table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;"';
        }
        elseif($tp==2){
          $tabla .='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
          $tab='table border="1" cellpadding="0" cellspacing="0" class="tabla"';
        }
        
        $tabla.='<'.$tab.'>
                    <thead>
                        <tr class="modo1" style="height:45px;">
                            <th style="width:1%;" bgcolor="#1c7368"><font color="#ffffff">#</font></th>
                            <th style="width:6%;" bgcolor="#1c7368"><font color="#ffffff">ACCI&Oacute;N ESTRATEGICA</font></th>
                            <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">OPERACI&Oacute;N</font></th>
                            <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">RESULTADO</font></th>
                            <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">ACTIVIDAD</font></th>
                            <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff">INDICADOR</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">LINEA BASE</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">META</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">ENE.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">FEB.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">MAR.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">ABR.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">MAY.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">JUN.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">JUL.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">AGO.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">SEP.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">OCT.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">NOV.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">DIC.</font></th>
                            <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">VERIFICACI&Oacute;N</font></th>
                        </tr>
                    </thead>
                <tbody>';
                $nro=0;
                if(count($obj_est)!=0){
                  foreach($obj_est as $rowo){
                    $tabla.='<tr class="modo1" bgcolor="#c3efea">';
                      $tabla.='<td colspan="21" style="height:15px;">OBJ. EST. '.$rowo['obj_codigo'].'.- '.$rowo['obj_descripcion'].'</td>';
                    $tabla.='</tr>';
                      $productos=$this->model_producto->list_producto_programado_oestrategico($com_id,$this->gestion,$rowo['obj_id']); /// Productos
                      
                      foreach($productos as $rowp){
                        $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                        $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
                        $color=''; $tp='';
                          if($rowp['indi_id']==1){
                            if(($sum[0]['meta_gest']+$rowp['prod_linea_base'])!=$rowp['prod_meta']){
                              $color='#fbd5d5';
                            }
                          }
                          elseif ($rowp['indi_id']==2) {
                            $tp='%';
                            if($rowp['mt_id']==3){
                              if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                                $color='#fbd5d5';
                              }
                            }
                          }

                          $ptto=0;
                          if(count($monto)!=0){
                            $ptto=$monto[0]['total'];
                          }

                          $nro++;
                          $tabla.='<tr class="modo1" bgcolor="'.$color.'" style="height:40px;">';
                          $tabla.='<td style="height:30px;">'.$nro.'</td>
                                    <td>'.$rowp['acc_codigo'].'.- '.$rowp['acc_descripcion'].'</td>
                                    <td>'.$rowp['prod_producto'].'</td>
                                    <td>'.$rowp['prod_resultado'].'</td>
                                    <td></td>
                                    <td>'.$rowp['prod_indicador'].'</td>
                                    <td align="right">'.$rowp['prod_linea_base'].'</td>
                                    <td align="right">'.$rowp['prod_meta'].'</td>
                                    <td align="right">'.$rowp['enero'].''.$tp.'</td>
                                    <td align="right">'.$rowp['febrero'].''.$tp.'</td>
                                    <td align="right">'.$rowp['marzo'].''.$tp.'</td>
                                    <td align="right">'.$rowp['abril'].''.$tp.'</td>
                                    <td align="right">'.$rowp['mayo'].''.$tp.'</td>
                                    <td align="right">'.$rowp['junio'].''.$tp.'</td>
                                    <td align="right">'.$rowp['julio'].''.$tp.'</td>
                                    <td align="right">'.$rowp['agosto'].''.$tp.'</td>
                                    <td align="right">'.$rowp['septiembre'].''.$tp.'</td>
                                    <td align="right">'.$rowp['octubre'].''.$tp.'</td>
                                    <td align="right">'.$rowp['noviembre'].''.$tp.'</td>
                                    <td align="right">'.$rowp['diciembre'].''.$tp.'</td>
                                    <td>'.$rowp['prod_fuente_verificacion'].'</td>';         
                          $tabla.='</tr>';
                          $tabla.=''.$this->actividades_2019($rowp['prod_id'],$nro).'';
                      }
                  }
                }
                else{
                  $tabla.='<tr><td colspan="22">No existen operaciones alineadas, Verifique Operaciones</td></tr>';
                }
                
                $tabla .=
                '</tbody>
                </table>';

        return $tabla;
    }


    /*--- MIGRACION DE OPERACIONES (2020) Y REQUERIMIENTOS  ---*/
    function importar_operaciones_requerimientos(){
      if ($this->input->post()) {
        $post = $this->input->post();
          $com_id = $this->security->xss_clean($post['com_id']); /// com id
          $componente = $this->model_componente->get_componente_pi($com_id);
          $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
          $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']);
          $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($fase[0]['proy_id']); /// Lista de Objetivos Regionales
          $tp = $this->security->xss_clean($post['tp']); /// tipo de migracion

          $tipo = $_FILES['archivo']['type'];
          $tamanio = $_FILES['archivo']['size'];
          $archivotmp = $_FILES['archivo']['tmp_name'];

          $filename = $_FILES["archivo"]["name"];
          $file_basename = substr($filename, 0, strripos($filename, '.'));
          $file_ext = substr($filename, strripos($filename, '.'));
          $allowed_file_types = array('.csv');

          if (in_array($file_ext, $allowed_file_types) && ($tamanio < 90000000)) {
            /*------------------- Migrando ---------------*/
            $lineas = file($archivotmp);
            $i=0;
            $nro=0;
            $guardado=0;
            $no_guardado=0;
            $nro_prod=count($this->model_producto->list_prod($com_id));
            if($nro_prod!=0){
              $ope_ult=$this->model_producto->ult_operacion($com_id);
              $nro_prod=$ope_ult[0]['prod_cod']+1;
            }
            else{
              $nro_prod=1;;
            }

            if($tp==1){  /// Actividades
              foreach ($lineas as $linea_num => $linea){ 
                if($i != 0){
                  $datos = explode(";",$linea);
                  if(count($datos)==21){

                    $cod_or = trim($datos[0]); // Codigo Objetivo Regional
                    $cod_ope = $nro_prod; // Codigo Operacion
                    $descripcion = utf8_encode(trim($datos[2])); //// descripcion Operacion
                    $resultado = utf8_encode(trim($datos[3])); //// descripcion Resultado
                    $unidad = utf8_encode(trim($datos[4])); //// Unidad
                    $indicador = utf8_encode(trim($datos[5])); //// descripcion Indicador
                    $lbase = utf8_encode(trim($datos[6])); //// Linea Base
                    if(trim($datos[6])==''){
                      $lbase = 0; //// Linea Base
                    }

                    $meta = utf8_encode(trim($datos[7])); //// Meta
                    if(trim($datos[7])==''){
                      $meta = 0; //// Meta
                    }

                    $var=8;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=(float)$datos[$var]; //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $var++;
                    }

                    $mverificacion = utf8_encode(trim($datos[20])); //// Medio de verificacion

                    $ae=0;
                    $or_id=0;
                    if(count($list_oregional)!=0){
                      $get_acc=$this->model_objetivoregion->get_alineacion_proyecto_oregional($fase[0]['proy_id'],$cod_or);
                      if(count($get_acc)!=0){
                        $ae=$get_acc[0]['ae'];
                        $or_id=$get_acc[0]['or_id'];
                      }
                    }

                    /*--- INSERTAR DATOS OPERACIONES (ACTIVIDADES 2020) ---*/
                    $query=$this->db->query('set datestyle to DMY');
                    $data_to_store = array(
                      'com_id' => $com_id,
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
                      'prod_cod'=>$cod_ope,
                      'or_id'=>$or_id,
                      'fun_id' => $this->fun_id,
                      'num_ip' => $this->input->ip_address(), 
                      'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    );
                    $this->db->insert('_productos', $data_to_store);
                    $prod_id=$this->db->insert_id(); 

                    for ($p=1; $p <=12 ; $p++) { 
                      if($m[$p]!=0){
                        $this->model_producto->add_prod_gest($prod_id,$this->gestion,$p,$m[$p]);
                      }
                    }

                    $producto=$this->model_producto->get_producto_id($prod_id);
                    if(count($producto)!=0){
                      $guardado++;
                    }
                    else{
                      $no_guardado++;
                    }

                    $nro_prod++;
                  }
                }
                $i++;
              }
              
            }
            else{ /// Requerimientos

            foreach ($lineas as $linea_num => $linea){
              if($i != 0){
                $datos = explode(";",$linea);
                //echo count($datos).'<br>';
                if(count($datos)==20){
                 
                    $prod_cod = (int)$datos[0]; //// Codigo Actividad
                    $cod_partida = (int)$datos[1]; //// Codigo partida
                    $par_id = $this->minsumos->get_partida_codigo($cod_partida); //// DATOS DE LA FASE ACTIVA

                    $detalle = utf8_encode(trim($datos[2])); //// descripcion
                    $unidad = utf8_encode(trim($datos[3])); //// Unidad
                    $cantidad = (int)$datos[4]; //// Cantidad
                    $unitario = $datos[5]; //// Costo Unitario
                    
                    $p_total=($cantidad*$unitario);
                    $total = $datos[6]; //// Costo Total

                    $var=7; $sum_temp=0;
                    for ($i=1; $i <=12 ; $i++) {
                      $m[$i]=$datos[$var]; //// Mes i
                      if($m[$i]==''){
                        $m[$i]=0;
                      }
                      $var++;
                      $sum_temp=$sum_temp+$m[$i];
                    }

                    $observacion = utf8_encode(trim($datos[19])); //// Observacion
                    $verif_cod=$this->model_producto->verif_componente_operacion($com_id,$prod_cod);
                  
                    //echo count($verif_cod).'--'.count($par_id).'--'.$cod_partida.'--'.round($sum_temp,2).'=='.round($total,2);

                    if(count($verif_cod)!=0 & count($par_id)!=0 & $cod_partida!=0 & round($sum_temp,2)==round($total,2)){ /// Verificando si existe Codigo de Actividad, par id, Codigo producto
                     // if($verif_cod[0]['prod_ppto']==1){ /// guardando si tiene programado presupuesto en la operacion
                        $guardado++;
                        /*-------- INSERTAR DATOS REQUERIMIENTO ---------*/
                        $query=$this->db->query('set datestyle to DMY');
                        $data_to_store = array( 
                        'ins_codigo' => $this->session->userdata("name").'/REQ/'.$this->gestion, /// Codigo Insumo
                        'ins_fecha_requerimiento' => date('d/m/Y'), /// Fecha de Requerimiento
                        'ins_detalle' => strtoupper($detalle), /// Insumo Detalle
                        'ins_cant_requerida' => round($cantidad,0), /// Cantidad Requerida
                        'ins_costo_unitario' => $unitario, /// Costo Unitario
                        'ins_costo_total' => $total, /// Costo Total
                        'ins_unidad_medida' => $unidad, /// Unidad de Medida
                        'ins_gestion' => $this->gestion, /// Insumo gestion
                        'par_id' => $par_id[0]['par_id'], /// Partidas
                        'ins_tipo' => 1, /// Ins Tipo
                        'ins_observacion' => strtoupper($observacion), /// Observacion
                        'fun_id' => $this->fun_id, /// Funcionario
                        'aper_id' => $proyecto[0]['aper_id'], /// aper id
                        'num_ip' => $this->input->ip_address(), 
                        'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                        );
                        $this->db->insert('insumos', $data_to_store); ///// Guardar en Tabla Insumos 
                        $ins_id=$this->db->insert_id();

                        /*--------------------------------------------------------*/
                          $data_to_store2 = array( ///// Tabla InsumoProducto
                            'prod_id' => $verif_cod[0]['prod_id'], /// prod id
                            'ins_id' => $ins_id, /// ins_id
                          );
                          $this->db->insert('_insumoproducto', $data_to_store2);
                        /*----------------------------------------------------------*/

                        for ($p=1; $p <=12 ; $p++) { 
                          if($m[$p]!=0 & is_numeric($unitario)){
                            $data_to_store4 = array(
                              'ins_id' => $ins_id, /// Id Insumo
                              'mes_id' => $p, /// Mes 
                              'ipm_fis' => $m[$p], /// Valor mes
                            );
                            $this->db->insert('temporalidad_prog_insumo', $data_to_store4);
                          }
                        }
                     // }

                    }
               

                } /// end dimension (22)
              } /// i!=0

              $i++;

            }

              /// --- ACTUALIZANDO MONEDA PARA CARGAR PRESUPUESTO
              $this->update_ptto_operaciones($com_id);
            } /// end else

            $this->session->set_flashdata('success','SE REGISTRARON '.$guardado.' REQUERIMIENTOS');
            redirect('admin/prog/list_prod/'.$com_id.'');
          }
          else{
            $this->session->set_flashdata('danger','SELECCIONE ARCHIVO ');
            redirect('admin/prog/list_prod/'.$com_id.'');
          }
      }
      else{
        echo "Error !!";
      }
    }

    /*------ ACTUALIZA PRESUPUESTO EXISTENTE DE LAS OPERACIONES -------*/
    public function update_ptto_operaciones($com_id){
      $operaciones=$this->model_producto->list_producto_programado($com_id,$this->gestion);
      foreach($operaciones as $rowp){
        $monto=$this->model_producto->monto_insumoproducto($rowp['prod_id']);
        if(count($monto)==0){
          $update_act= array(
            'prod_ppto' => 0,
            'fun_id' => $this->fun_id
          );
          $this->db->where('prod_id', $rowp['prod_id']);
          $this->db->update('_productos', $update_act);
        }
      }
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
    
    /*--------------- LISTA DE OPERACIONES ANTERIOR (2018) ------------*/
    public function rep_list_operaciones_2018($com_id,$tp){
      //  $productos = $this->model_producto->list_prod($com_id); // Lista de productos
      $productos=$this->model_producto->list_producto_programado($com_id,$this->gestion);
        $componente=$this->model_componente->get_componente($com_id);
        $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']);
        $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
        $tabla='';

        if($tp==1){
          $tab='table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:98%;"';
        }
        elseif($tp==2){
          $tabla .='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
          $tab='table border="1" cellpadding="0" cellspacing="0" class="tabla"';
        }
        
        $tabla.='<'.$tab.'>
                    <thead>
                        <tr class="modo1" style="height:45px;">
                            <th style="width:1%;" bgcolor="#1c7368"><font color="#ffffff">#</font></th>
                            <th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">PRODUCTO</font></th>
                            <th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff">ACTIVIDAD</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">TIP.</font></th>
                            <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff">INDICADOR</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">LINEA BASE</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">META</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">ENE.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">FEB.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">MAR.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">ABR.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">MAY.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">JUN.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">JUL.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">AGO.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">SEP.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">OCT.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">NOV.</font></th>
                            <th style="width:2%;" bgcolor="#1c7368"><font color="#ffffff">DIC.</font></th>
                            <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff">VERIFICACI&Oacute;N</font></th>
                        </tr>
                    </thead>
                <tbody>';
                $nro=0;
                foreach($productos as $rowp){
                  $sum=$this->model_producto->meta_prod_gest($rowp['prod_id']);
                  $color=''; $tp='';
                    if($rowp['indi_id']==1){
                      if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                        $color='#fbd5d5';
                      }
                    }
                    elseif ($rowp['indi_id']==2) {
                      $tp='%';
                      if($rowp['mt_id']==3){
                        if(($sum[0]['meta_gest'])!=$rowp['prod_meta']){
                          $color='#fbd5d5';
                        }
                      }
                    }

                    $nro++;
                    $tabla.='<tr class="modo1" bgcolor="'.$color.'" style="height:40px;">';
                    $tabla.=' <td>'.$nro.'</td>
                              <td>'.$rowp['prod_producto'].'</td>
                              <td></td>
                              <td>'.$rowp['indi_abreviacion'].'</td>
                              <td>'.$rowp['prod_indicador'].'</td>
                              <td>'.$rowp['prod_linea_base'].'</td>
                              <td>'.$rowp['prod_meta'].'</td>
                              <td>'.$rowp['enero'].''.$tp.'</td>
                              <td>'.$rowp['febrero'].''.$tp.'</td>
                              <td>'.$rowp['marzo'].''.$tp.'</td>
                              <td>'.$rowp['abril'].''.$tp.'</td>
                              <td>'.$rowp['mayo'].''.$tp.'</td>
                              <td>'.$rowp['junio'].''.$tp.'</td>
                              <td>'.$rowp['julio'].''.$tp.'</td>
                              <td>'.$rowp['agosto'].''.$tp.'</td>
                              <td>'.$rowp['septiembre'].''.$tp.'</td>
                              <td>'.$rowp['octubre'].''.$tp.'</td>
                              <td>'.$rowp['noviembre'].''.$tp.'</td>
                              <td>'.$rowp['diciembre'].''.$tp.'</td>
                              <td>'.$rowp['prod_fuente_verificacion'].'</td>';         
                    $tabla.='</tr>';
                    if($proyecto[0]['proy_act']==1){
                      $tabla.=''.$this->actividades_2018($rowp['prod_id'],$nro).'';
                    }
                }
                $tabla .=
                '</tbody>
                </table>';

        return $tabla;
    }


    /*----------------- Lista de Actividades -------------*/
    public function actividades_2018($prod_id,$nro){
       $actividad=$this->model_actividad->list_act_anual($prod_id); /// Actividad
       $tabla='';
       $nro_a=0;
       if(count($actividad)!=0){
            foreach ($actividad as $row){
                $programado=$this->model_actividad->actividad_programado($row['act_id'],$this->gestion); /// Actividad Programado
                if(count($programado)!=0){
                  $nro_a++;
                  $tabla.='<tr class="modo1" bgcolor="#e5f3f1">';
                      $tabla.='<td>'.$nro.'.'.$nro_a.'</td>';
                      $tabla.='<td></td>';
                      $tabla.='<td>'.$row['act_actividad'].'</td>';
                      $tabla.='<td>'.$row['indi_abreviacion'].'</td>';
                      $tabla.='<td>'.$row['act_indicador'].'</td>';
                      $tabla.='<td align="right">'.$row['act_linea_base'].'</td>';
                      $tabla.='<td align="right">'.$row['act_meta'].'</td>';
                      $tabla.='<td align="right">'.$programado[0]['enero'].'</td>
                                <td align="right">'.$programado[0]['febrero'].'</td>
                                <td align="right">'.$programado[0]['marzo'].'</td>
                                <td align="right">'.$programado[0]['abril'].'</td>
                                <td align="right">'.$programado[0]['mayo'].'</td>
                                <td align="right">'.$programado[0]['junio'].'</td>
                                <td align="right">'.$programado[0]['julio'].'</td>
                                <td align="right">'.$programado[0]['agosto'].'</td>
                                <td align="right">'.$programado[0]['septiembre'].'</td>
                                <td align="right">'.$programado[0]['octubre'].'</td>
                                <td align="right">'.$programado[0]['noviembre'].'</td>
                                <td align="right">'.$programado[0]['diciembre'].'</td>';
                      
                      $tabla.='<td>'.$row['act_fuente_verificacion'].'</td>';
                  $tabla.='</tr>';
                }
                
            }
       }

       return $tabla;
    }


    /*----------------- Lista de Actividades -------------*/
    public function actividades_2019($prod_id,$nro){
       $actividad=$this->model_actividad->list_act_anual($prod_id); /// Actividad
       $tabla='';
       $nro_a=0;
       if(count($actividad)!=0){
            foreach ($actividad as $row){
                $programado=$this->model_actividad->actividad_programado($row['act_id'],$this->gestion); /// Actividad Programado
                if(count($programado)!=0){
                  $nro_a++;
                  $monto=$this->model_actividad->monto_insumoactividad($row['act_id']);
                  $ptto=0;
                    if(count($monto)!=0){
                      $ptto=$monto[0]['total'];
                    }
                  $tabla.='<tr class="modo1" bgcolor="#f5f5f5">';
                      $tabla.='<td style="width: 1%; text-align: left;" style="height:11px;">'.$nro.'.'.$nro_a.'</td>';
                      $tabla.='<td style="width: 9%; text-align: left;"></td>';
                      $tabla.='<td style="width: 10%; text-align: left;"></td>';
                      $tabla.='<td style="width: 9%; text-align: left;"></td>';
                      $tabla.='<td style="width: 10%; text-align: left;">'.$row['act_actividad'].'</td>';
                      $tabla.='<td style="width: 7%; text-align: left;">'.$row['act_indicador'].'</td>';
                      $tabla.='<td style="width: 3%; text-align: right;">'.$row['act_linea_base'].'</td>';
                      $tabla.='<td style="width: 2.5%; text-align: right;">'.$row['act_meta'].'</td>';
                      $tabla.='<td style="width: 2.5%; text-align: right;">'.$programado[0]['enero'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['febrero'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['marzo'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['abril'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['mayo'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['junio'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['julio'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['agosto'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['septiembre'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['octubre'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['noviembre'].'</td>
                                <td style="width: 2.5%; text-align: right;">'.$programado[0]['diciembre'].'</td>';
                      $tabla.='<td style="width: 8%; text-align: left;">'.$row['act_fuente_verificacion'].'</td>';
                      $tabla.='<td style="width: 5%; text-align: left;">'.number_format($ptto, 2, ',', '.').'</td>';
                  $tabla.='</tr>';
                }
                
            }
       }

       return $tabla;
    }


    /*----------------------------------- ACTIVIDADES ----------------------------*/
    public function temporalizacion_act($act_id,$gestion){
        $act=$this->model_actividad->get_actividad_id($act_id); /// programado
        $programado=$this->model_actividad->actividad_programado($act_id,$gestion); /// Actividad Programado

        $m[0]='g_id';
        $m[1]='enero';
        $m[2]='febrero';
        $m[3]='marzo';
        $m[4]='abril';
        $m[5]='mayo';
        $m[6]='junio';
        $m[7]='julio';
        $m[8]='agosto';
        $m[9]='septiembre';
        $m[10]='octubre';
        $m[11]='noviembre';
        $m[12]='diciembre';

        for ($i=1; $i <=12 ; $i++) { 
            $prog[1][$i]=0;
            $prog[2][$i]=0;
            $prog[3][$i]=0;
        }

        $pa=0;
        if(count($programado)!=0){
            for ($i=1; $i <=12 ; $i++) { 
                $prog[1][$i]=$programado[0][$m[$i]];
            } 
        }
        
        $tr_return = '';
        $tr_return = '';
          for($i = 1 ;$i<=12 ;$i++){
            $tr_return .= '<td bgcolor="#d2f5d2" align="center" title="'.$m[$i].'"><b>'.$prog[1][$i].'</b></td>';
        }
        return $tr_return;
    }


    /*--------------- EXPORTAR OPERACIONES REQUERIMIENTOS (2019) --------------*/
    public function exportar_productos_requerimientos($com_id){
      date_default_timezone_set('America/Lima');
      $componente = $this->model_componente->get_componente($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      $prod=$this->requerimientos_operaciones($com_id);
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");

      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel;charset=UTF-8');
      header("Content-Disposition: attachment; filename=Reporte_requerimientos_Operación_".$componente[0]['com_componente']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");

      echo '
      <table border="1" cellpadding="0" cellspacing="0" class="tabla">
        <tr class="modo1">
          <td colspan="21"></td>
        </tr>
        <tr class="modo1">
          <td colspan="21">
            <FONT FACE="courier new" size="1">
                <b>ENTIDAD : </b>'.$this->session->userdata('entidad').'<br>
                <b>PLAN OPERATIVO ANUAL POA : </b> ' . $this->gestion . '<br>
                <b>REPORTE : </b> REQUERIMIENTOS POR OPERACIONES REGISTRADAS<br>
                <b>ACTIVIDAD : </b>'.$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.mb_convert_encoding(''.$proyecto[0]['proy_nombre'], 'cp1252', 'UTF-8').'<br>
                <b>SUB ACTIVIDAD : </b>'.$componente[0]['serv_cod'].'-'.mb_convert_encoding(''.$componente[0]['com_componente'], 'cp1252', 'UTF-8').'<br>
                </font>
          </td>
        </tr>
      </table><br>';
      echo "".$prod."";
    }

    /*--------- Requerimientos de operaciones del componente seleccionado (2019)-------*/
    public function requerimientos_operaciones($com_id){
      $componente=$this->model_producto->requerimientos_componentes($com_id); /// lista de requerimientos
      $tabla='';
      $tabla .='<style>
                    table{font-size: 9px;
                          width: 100%;
                          max-width:1550px;
                          overflow-x: scroll;
                          }
                          th{
                            padding: 1.4px;
                            text-align: center;
                            font-size: 9px;
                          }
                    </style>';
      $tabla .='<table table border="1" cellpadding="0" cellspacing="0" class="tabla">
                  <thead>
                  <tr class="modo1" style="height:45px;">
                    <th style="width:1%;" bgcolor="#1c7368"><font color="#ffffff"><b>COD. OPE.</b></font></th>
                    <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff"><b>PARTIDA</b></font></th>
                    <th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff"><b>DESCRIPCI&Oacute;N DE LA PARTIDA</b></font></th>
                    <th style="width:10%;" bgcolor="#1c7368"><font color="#ffffff"><b>DETALLE/DESCRIPCI&Oacute;N DEL INSUMOS</b></font></th>
                    <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff"><b>UNIDAD DE MEDIDA</b></font></th>
                    <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff"><b>CANTIDAD</b></font></th>
                    <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff"><b>UNITARIO</b></font></th>
                    <th style="width:5%;" bgcolor="#1c7368"><font color="#ffffff"><b>TOTAL</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>ENE.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>FEB.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>MAR.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>ABR.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>MAY.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>JUN.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>JUL.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>AGO.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>SEP.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>OCT.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>NOV.</b></font></th>
                    <th style="width:4%;" bgcolor="#1c7368"><font color="#ffffff"><b>DIC.</b></font></th>
                    <th style="width:7%;" bgcolor="#1c7368"><font color="#ffffff"><b>OBSERVACI&Oacute;N</b></font></th>
                  </tr>
                </thead>
                <tbody>';
      foreach($componente as $row){
        $color='';
        if($row['ins_costo_total']!=$row['programado_total']){
          $color='#f9c9b6';
        }
         $tabla.='<tr class="modo1" bgcolor="'.$color.'">';
          $tabla.=' <td>'.$row['prod_cod'].'</td>
                    <td>'.$row['par_codigo'].'</td>
                    <td>'.mb_convert_encoding($row['par_nombre'], 'cp1252', 'UTF-8').'</td>
                    <td>'.mb_convert_encoding($row['ins_detalle'], 'cp1252', 'UTF-8').'</td>
                    <td>'.$row['ins_unidad_medida'].'</td>
                    <td>'.$row['ins_cant_requerida'].'</td>
                    <td>'.$row['ins_costo_unitario'].'</td>
                    <td>'.$row['ins_costo_total'].'</td>
                    <td>'.$row['mes1'].'</td>
                    <td>'.$row['mes2'].'</td>
                    <td>'.$row['mes3'].'</td>
                    <td>'.$row['mes4'].'</td>
                    <td>'.$row['mes5'].'</td>
                    <td>'.$row['mes6'].'</td>
                    <td>'.$row['mes7'].'</td>
                    <td>'.$row['mes8'].'</td>
                    <td>'.$row['mes9'].'</td>
                    <td>'.$row['mes10'].'</td>
                    <td>'.$row['mes11'].'</td>
                    <td>'.$row['mes12'].'</td>
                    <td>'.mb_convert_encoding($row['ins_observacion'], 'cp1252', 'UTF-8').'</td>';
          $tabla.='</tr>';

      }
      $tabla.='</tbody>
        </table>';

      return $tabla;
    }
    
    /*--------------- EXPORTAR OPERACIONES (2019) --------------*/
    public function exportar_productos($com_id){
      date_default_timezone_set('America/Lima');
      $componente = $this->model_componente->get_componente($com_id); //// DATOS COMPONENTE
      $fase=$this->model_faseetapa->get_fase($componente[0]['pfec_id']); /// DATOS FASE
      $proyecto = $this->model_proyecto->get_id_proyecto($fase[0]['proy_id']); //// DATOS PROYECTO
      if($proyecto[0]['tp_id']!=4){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($fase[0]['proy_id']); //// DATOS PROYECTO
      }

      $prod=$this->rep_list_operaciones($com_id,2);
      //la fecha de exportación sera parte del nombre del archivo Excel
      $fecha = date("d-m-Y H:i:s");

      //Inicio de exportación en Excel
      header('Content-type: application/vnd.ms-excel;charset=UTF-8');
      header("Content-Disposition: attachment; filename=Reporte_Operación_".$componente[0]['com_componente']."_$fecha.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");

      echo '
      <table border="1" cellpadding="0" cellspacing="0" class="tabla">
        <tr class="modo1">
          <td colspan="24"></td>
        </tr>
        <tr class="modo1">
          <td colspan="24">
           '.$this->cabecera_2020($com_id,2).'
          </td>
        </tr>
      </table><br>';
      echo "".$prod."";
    }


   /*-------------------------------- VALIDA ADICIONAR PRODUCTO POR MODIFICACION ----------------------*/
     public function valida_add_producto(){
      if ($this->input->server('REQUEST_METHOD') === 'POST'){
          $this->form_validation->set_rules('proy_id', 'Proyecto Id', 'required|trim');
          $this->form_validation->set_rules('cite_id', 'Cite Id', 'required|trim');
          $this->form_validation->set_rules('com_id', 'Componente Id', 'required|trim');
          $this->form_validation->set_rules('prod', 'Producto', 'required|trim');

          $fase = $this->model_faseetapa->get_id_fase($this->input->post('proy_id'));
          for ($i=1; $i <=12 ; $i++) { 
            $m[$i]='m'.$i;
          }
          if ($this->form_validation->run()){

              /*---------------- Actualiza Ponderacion de productos -------------*/
                $list_prod=$this->model_producto->list_prod($this->input->post('com_id'));
                if(count($list_prod)!=0){
                  $pond=10;
                }
                else{
                 $pond=100; 
                }
                $ponderacion=$this->model_producto->suma_ponderacion($this->input->post('com_id'));
                foreach ($list_prod as $row) {
                  $update_prod = array(
                    'prod_ponderacion' => round((($row['prod_ponderacion']/100)*90),2), /// Ponderacion
                    'fun_id' => $this->session->userdata("fun_id")
                    );
                  $this->db->where('prod_id', $row['prod_id']);
                  $this->db->update('_productos', $update_prod);
                }
                /*-----------------------------------------------------------------*/

            /*------------------ Adiciona Producto -------------------*/
            $data_to_store = array(
              'com_id' => $this->input->post('com_id'),
              'prod_producto' => strtoupper($this->input->post('prod')),
              'indi_id' => $this->input->post('tipo_i'),
              'prod_indicador' => strtoupper($this->input->post('indicador')),
              'prod_formula' => strtoupper($this->input->post('formula')),
              'prod_linea_base' => $this->input->post('lb'),
              'prod_meta' => $this->input->post('met'),
              'prod_fuente_verificacion' => strtoupper($this->input->post('verificacion')), 
              'prod_supuestos' => strtoupper($this->input->post('supuestos')),
              'pt_id' => $this->input->post('p_t'),
              'prod_ponderacion' => $pond,
              'prod_total_casos' => strtoupper($this->input->post('c_a')),
              'prod_casos_favorables' => strtoupper($this->input->post('c_b')),
              'prod_denominador' => $this->input->post('den'),
              'fun_id' => $this->session->userdata("fun_id"),
              'prod_mod' => 2,
            );
              $this->db->insert('_productos', $data_to_store); 
            /*-------------------------------------------------------*/
            $prod_id=$this->db->insert_id();

            $gestion=$fase[0]['pfec_fecha_inicio'];
            if ( !empty($_POST["m1"]) && is_array($_POST["m1"]) ){
                foreach ( array_keys($_POST["m1"]) as $como ){
                  
                  for ($i=1; $i <=12 ; $i++) { 
                      if($_POST[$m[$i]][$como]!=0 || $_POST[$m[$i]][$como]!=''){
                        $this->model_producto->add_prod_gest($prod_id,$gestion,$i,$_POST[$m[$i]][$como]);
                      }
                  }
                $gestion++;        
              }
            }

            /*--------------------- iNSERT AUDI ADICIONAR PRODUCTOS -------------*/
              $data_to_store2 = array(
                'prod_id' => $prod_id, /// prod_id
                'ope_id' => $this->input->post('cite_id'), /// cite_id
                'num_ip' => $this->input->ip_address(), 
                'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                'fun_id' => $this->session->userdata("fun_id"),
                );
              $this->db->insert('_producto_add', $data_to_store2);
              $proda_id=$this->db->insert_id();

              if(count($this->model_modificacion->get_add_producto($proda_id))==1){
                $this->session->set_flashdata('success','EL PRODUCTO SE AGREGO CORRECTAMENTE');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
              else{
                $this->session->set_flashdata('danger','NO SE GUARDO CORRECTAMENTE, VERIFIQUE DATOS');
                redirect(site_url("admin").'/mod/proyecto_mod/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id'));
              }
          }
          else{
            redirect('admin/mod/add_producto/'.$this->input->post('cite_id').'/'.$this->input->post('proy_id')."/".$this->input->post('com_id').'/error');
          }
      }
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