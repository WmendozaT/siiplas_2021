<?php
class Cmod_fisica extends CI_Controller {  
    public $rol = array('1' => '3','2' => '6','3' => '4','4' => '10');
    public $temp = array( '1' => 'enero','2' => 'febrero','3' => 'marzo','4' => 'abril','5' => 'mayo','6' => 'junio',
                        '7' => 'julio','8' => 'agosto','9' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' => 'diciembre'); 

    public $prog_mes = array( '1' => 0,'2' => 0,'3' => 0,'4' => 0,'5' => 0,'6' => 0,
                        '7' => 0,'8' => 0,'9' => 0,'10' => 0,'11' => 0,'12' => 0); 

    public $prog_mes_eval = array( '1' => 0,'2' => 0,'3' => 0,'4' => 0,'5' => 0,'6' => 0,
                        '7' => 0,'8' => 0,'9' => 0,'10' => 0,'11' => 0,'12' => 0); 
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null & $this->session->userdata('fun_estado')!=3){
            $this->load->model('Users_model','',true);
            if($this->rolfun($this->rol)){ 
            $this->load->library('pdf2');
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('modificacion/model_modificacion');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_actividad');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('programacion/insumos/minsumos');
            $this->load->model('mestrategico/model_mestrategico');
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
            $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
            $this->load->model('mestrategico/model_objetivoregion'); /// Gestion 2020
            $this->load->model('ejecucion/model_evaluacion'); /// Evaluacion POA
            $this->load->model('ejecucion/model_seguimientopoa');
            $this->load->library('security');
            $this->gestion = $this->session->userData('gestion'); /// Gestion
            $this->fun_id = $this->session->userData('fun_id'); /// Fun id
            $this->rol_id = $this->session->userData('rol_id'); /// Rol Id
            $this->adm = $this->session->userData('adm');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->verif_mes=$this->session->userdata('mes_actual');
            $this->tmes = $this->session->userData('trimestre');
            $this->conf_poa_estado = $this->session->userData('conf_poa_estado'); /// Ajuste POA 1: Inicial, 2 : Ajuste, 3 : aprobado
            $this->conf_mod_ope = $this->session->userData('conf_mod_ope');
            $this->conf_mod_req = $this->session->userData('conf_mod_req');
            $this->fecha_entrada = strtotime("16-09-2021 00:00:00");
            $this->load->library('modificacionpoa');
            }else{
                redirect('admin/dashboard');
            }
        }
        else{
          $this->session->sess_destroy();
          redirect('/','refresh');
        }
    }


    /*--- LISTA SUBACTIVIDADES (2020-2021) ---*/
    public function mis_subactividades($proy_id){
      $data['menu']=$this->modificacionpoa->menu(3); //// genera menu
      $data['proyecto'] = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($data['proyecto'])!=0){
        $data['fase'] = $this->model_faseetapa->get_id_fase($proy_id);
        $titulo='
          <h1> PROYECTO DE INVERSI&Oacute;N : <small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</small>';
        if($data['proyecto'][0]['tp_id']==4){
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
          $titulo='
          <h1> <b>'.$data['proyecto'][0]['tipo_adm'].' : </b><small>'.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' '.$data['proyecto'][0]['abrev'].'</small>';
        }
        
        $componente=$this->model_componente->componentes_id($data['fase'][0]['id'],$data['proyecto'][0]['tp_id']);
        $tabla='';
        $tabla.='<table id="dt_basic4" class="table table table-bordered" width="100%">
                <thead>
                  <tr style="height:25px;">
                    <th style="width:1%;"></th>
                    <th style="width:5%;">Modificar Formulario</th>
                    <th style="width:15%;">UNIDAD RESPONSABLE</th>
                    <th style="width:10%;">RESPONSABLE</th>
                    <th style="width:5%;">PONDERACI&Oacute;N</th>
                    <th style="width:5%;">NRO. REGISTROS</th>
                  </tr>
                </thead>
                <tbody>';
                $nro=0;
                foreach($componente as $row){
                  $nro++;
                  $tabla.='
                  <tr>
                    <td>'.$nro.'</td>
                    <td align=center>';
                      if($this->conf_mod_ope==1 || $this->tp_adm==1){
                        $tabla.='
                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default nuevo_ff" title="MODIFICAR OPERACIONES" name="'.$row['com_id'].'">
                          <img src="'.base_url().'assets/ifinal/mod_money.png" width="35" height="35"/>
                        </a>';
                      }
                      $tabla.='
                    </td>
                    <td>'.$row['tipo_subactividad'].' '.$row['serv_descripcion'].'</td>
                    <td>'.$row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno'].'</td>
                    <td align=center>'.round($row['com_ponderacion'],2).' %</td>
                    <td align=center bgcolor="#bee6e1"><font size=2 color=blue>'.count($this->model_producto->list_prod($row['com_id'])).'</font></td>
                  </tr>';
                }
        $tabla.='</tbody>
              </table>';        

        $data['componentes']=$tabla;
        $data['titulo_proy']=$titulo;
        $this->load->view('admin/modificacion/moperaciones/cite_modfis', $data);  
      }
      else{
        redirect(site_url("").'/mod/list_top');
      }
      
    }




    /*----- VALIDA CITE FISICA 2020 -----*/
    public function valida_cite_modificacion(){
      if ($this->input->post()) {
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']); /// Proy id 
        $com_id = $this->security->xss_clean($post['com_id']); /// Com id 
        $cite = $this->security->xss_clean($post['cite']); /// Cite
        $fecha = $this->security->xss_clean($post['fm']); /// Fecha
        $proyecto = $this->model_proyecto->get_id_proyecto($proy_id); /// Datos Proyecto

        if($proy_id!='' & count($proyecto)!=0){
          /*--- GUARDANDO CITE MODIFICADO - FISICA----*/
          $data_to_store = array(
            'cite_nota' => strtoupper($cite),
            'cite_fecha' => $fecha,
            'g_id' => $this->gestion,
            'fun_id' => $this->fun_id,
            'com_id' => $com_id,
            );
          $this->db->insert('cite_mod_fisica',$data_to_store);
          $cite_id=$this->db->insert_id();
          /*---------------------------------------------------------------*/

          if(count($this->model_modfisica->get_cite_fis($cite_id))==1){
            redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
            redirect(site_url("").'/mod/list_componentes/'.$proy_id.'');
          }
        }
        else{
          $this->session->set_flashdata('danger','ERROR AL REGISTRAR CITE');
          redirect(site_url("").'/mod/list_componentes/'.$proy_id.'');
        }
          
      } else {
          show_404();
      }
    }


    /*------ LISTA DE FORMULARIO N° 4 (2020 - 2021) -------*/
    public function list_operaciones($cite_id){
      $data['cite']=$this->model_modfisica->get_cite_fis($cite_id);
      if(count($data['cite'])!=0){
        $data['menu']=$this->modificacionpoa->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['datos_cite']=$this->modificacionpoa->datos_cite($data['cite']); /// DATOS CITE
        $data['titulo']=$this->modificacionpoa->titulo_cabecera($data['cite']);
        $data['indi'] = $this->model_proyecto->indicador(); /// indicador
        $data['metas'] = $this->model_producto->tp_metas(); /// tp metas

        if($data['cite'][0]['tp_id']==1){
          $data['list_oregional']=$this->lista_oregional_pi($data['cite'][0]['proy_id']);
          $data['objetivos']=$this->model_objetivoregion->get_unidad_pregional_programado($data['proyecto'][0]['proy_id']);
        }
        else{
          $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);
          $data['objetivos']=$this->model_objetivoregion->list_proyecto_oregional($data['proyecto'][0]['proy_id']);
          $data['list_oregional']=$this->lista_oregional($data['proyecto'][0]['proy_id']);
         }
        
        $data['nro'] = $this->model_producto->list_prod($data['cite'][0]['com_id']); // Lista de productos para el codigo
        $data['verif_mod']=$this->modificacionpoa->verif_cite($cite_id); /// Verificando modficaciones para la impresion
        $data['formulario_N4']=$this->modificacionpoa->mis_formulario4($data['cite']); /// Lista Operaciones
        $this->load->view('admin/modificacion/moperaciones/productos/list_productos', $data);


/*
         $cite_id = 496; /// Cite Id
          $prod_id = 61387; /// Prod Id
          $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
          $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Datos del Proyecto


          if($this->copia_operacion($cite,$prod_id,3)){
            $update_prod = array(
              'prod_mod' => 2,
              'estado' => 3,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->fun_id,
              );
            $this->db->where('prod_id', $prod_id);
            $this->db->update('_productos', $update_prod);

            $result = array(
              'respuesta' => 'correcto'
            );
          }
          else{
            $result = array(
              'respuesta' => 'error'
            );
          }

          echo json_encode($result);*/


      }
      else{
        $this->session->set_flashdata('danger','ERROR AL INGRESAR');
        redirect(site_url("").'/mod/list_componentes/'.$cite[0]['proy_id'].'');
      }
    }


    /*---- GET DATOS PRODUCTO FORM 4 ----*/
    public function get_form4_mod(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']);
        $producto=$this->model_producto->get_producto_id($prod_id); /// Get producto
        $temporalidad=$this->model_producto->producto_programado($prod_id,$this->gestion); /// Temporalidad
        
        $sum_temp=0;
        $sum=$this->model_producto->meta_prod_gest($prod_id);
        if(count($sum)!=0){
          $sum_temp=$sum[0]['meta_gest'];
        }

        if(count($temporalidad)!=0){
          for ($i=1; $i <=12 ; $i++) { 
            $this->prog_mes[$i]= $temporalidad[0][$this->temp[$i]];
          }
        }

        for ($i=1; $i <=12 ; $i++) { 
          if($i<$this->verif_mes[1]){ /// Meses ejecutados
          //if($i<2){ /// Meses ejecutados
            $this->prog_mes_eval[$i]=1;
          }
        }

        if(count($producto)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'producto'=>$producto,
            'temp'=>$this->prog_mes,
            'mes'=>$this->temp,
            'mes_actual'=>$this->verif_mes,
            'trimestre'=>$this->tmes,
            'temp_eval'=>$this->prog_mes_eval,
            'sum_temp'=>$sum_temp,
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


    /*----- VALIDAR UPDATE MOD FORM 4 ----*/
    public function valida_update_form4(){
      if($this->input->post()) {
        $post = $this->input->post();
        $prod_id = $this->security->xss_clean($post['prod_id']); /// prod id
        $producto=$this->model_producto->get_producto_id($prod_id);
        $cite_id = $this->security->xss_clean($post['mcite_id']); /// Cite id
        $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite

/*        if($this->verif_mes==3){
          $prod = $this->security->xss_clean($post['mprod']); /// detalle producto
          $resultado = $this->security->xss_clean($post['mresultado']); /// Resultado
          $mverificacion = $this->security->xss_clean($post['mverificacion']); /// Medio de Verificacion
        }
        else{
          $prod = $producto[0]['prod_producto']; /// detalle producto
          $resultado = $producto[0]['prod_resultado']; /// Resultado
          $mverificacion = $producto[0]['prod_fuente_verificacion']; /// Medio de Verificacion
        }*/

        if($this->tmes==1){
          $indi_id = $this->security->xss_clean($post['mtipo_i']); /// Tipo de Indicador
          $linea_base = $this->security->xss_clean($post['mlbase']); /// Linea Base
          $tp_meta = $this->security->xss_clean($post['mtp_met']); /// Tipo de Meta
          $prod = $this->security->xss_clean($post['mprod']); /// detalle producto
          $resultado = $this->security->xss_clean($post['mresultado']); /// Resultado
          $mverificacion = $this->security->xss_clean($post['mverificacion']); /// Medio de Verificacion
        }
        else{
          $indi_id = $producto[0]['indi_id']; /// Tipo de Indicador
          $linea_base = $producto[0]['prod_linea_base']; /// Linea Base
          $tp_meta = $producto[0]['mt_id']; /// Tipo de Meta
          $prod = $producto[0]['prod_producto']; /// detalle producto
          $resultado = $producto[0]['prod_resultado']; /// Resultado
          $mverificacion = $producto[0]['prod_fuente_verificacion']; /// Medio de Verificacion
        }

          $indicador = $this->security->xss_clean($post['mindicador']); /// Indicador
          $unidad = $this->security->xss_clean($post['munidad']); /// Unidad Responsable
          $meta = $this->security->xss_clean($post['mmeta']); /// Meta
          $presupuesto = $this->security->xss_clean($post['mppto']); /// Presupuesto
          $or_id = $this->security->xss_clean($post['mor_id']); /// Objetivo Regional

          $ae=0;
          $get_acc=$this->model_objetivoregion->get_objetivosregional($or_id);
          if(count($get_acc)!=0){
            $ae=$get_acc[0]['ae'];
          }

        if($this->copia_operacion($cite,$prod_id,2)){
          /*--------- Update Producto --------*/
          $update_prod = array(
          //  'com_id' => $com_id, // com id
            'prod_producto' => strtoupper($prod), // Producto
            'prod_resultado' => strtoupper($resultado),
            'indi_id' => $indi_id,
            'prod_indicador' => strtoupper($indicador),
            'prod_unidades' => strtoupper($unidad),
            'prod_linea_base' => $linea_base,
            'prod_meta' => $meta,
            'prod_fuente_verificacion' => strtoupper($mverificacion),
            'estado' => 2,
            'acc_id' => $ae,
            'fecha' => date("d/m/Y H:i:s"),
            'mt_id' => $tp_meta,
            'prod_mod' => 2,
            'or_id' => $or_id,
            'fun_id' => $this->fun_id,
          );
          $this->db->where('prod_id', $prod_id);
          $this->db->update('_productos', $update_prod);
          /*----------------------------------*/

          $mes=0; 
          // $this->verif_mes[1]
          if($indi_id==1){
            for ($i=$this->verif_mes[1]; $i <=12 ; $i++) {
              if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
              
                $this->db->where('prod_id', $prod_id);
                $this->db->where('m_id', $i);
                $this->db->delete('prod_programado_mensual'); 

                if($post['mm'.$i]!=0){
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['mm'.$i]);
                }
              }
            }
          }

          if($indi_id==2){
            if($tp_meta==3){
              for ($i=$this->verif_mes[1]; $i <=12 ; $i++) { 
                if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
                 
                  $this->db->where('prod_id', $prod_id);
                  $this->db->where('m_id', $i);
                  $this->db->delete('prod_programado_mensual'); 

                  if($post['mm'.$i]!=0){
                    $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$post['mm'.$i]);
                  }
                }
              }
            }
            elseif($tp_meta==1){
              if(count($this->model_producto->prod_prog_mensual($prod_id,$this->gestion))!=0){
                for ($i=$this->verif_mes[1]; $i <=12 ; $i++) { 
              
                  if(count($this->model_seguimientopoa->get_seguimiento_poa_mes($prod_id,$i))==0){
          
                    $this->db->where('prod_id', $prod_id);
                    $this->db->where('m_id', $i);
                    $this->db->delete('prod_programado_mensual'); 

                    if($post['mm'.$i]!=0){
                      $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$meta);
                    }
                  }
                }
              }
              else{
                for ($i=1; $i <=12 ; $i++) { 
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$meta);
                }
              }
            }
          } 

          /*-------------- Redireccionando a lista de Operaciones -------*/
          $this->session->set_flashdata('success','LA OPERACIÓN SE MODIFICO CORRECTAMENTE :)');
          redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
        }

      } else {
          show_404();
      }
    }



    /*--- VALIDA NUEVA ACTIVIDAD (2020) ---*/
    public function valida_operacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
        $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']);
        
        $codigo = $this->security->xss_clean($post['cod']); /// Codigo
        $producto = $this->security->xss_clean($post['prod']); /// Actividad
        $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
        $tipo_i = $this->security->xss_clean($post['tipo_i']); /// tipo indicador
        $ppto = $this->security->xss_clean($post['ppto']); /// Presupuesto
        $or_id = $this->security->xss_clean($post['or_id']); /// Objetivo Regional
        $indicador = $this->security->xss_clean($post['indicador']); /// indicador
        $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
        $meta = $this->security->xss_clean($post['meta']); /// met
        $verificacion = $this->security->xss_clean($post['verificacion']); /// verificacion
        $tp_met = $this->security->xss_clean($post['tp_met']); /// Tipo de Meta
        $lb = $this->security->xss_clean($post['lbase']); /// Linea Base

        $ae=0;
        $get_acc=$this->model_objetivoregion->get_objetivosregional($or_id);
        if(count($get_acc)!=0){
          $ae=$get_acc[0]['ae'];
        }

        if($tipo_i==1){
          $tp_met=3;
        }

          /*----- INSERT OPERACION ----*/
          $data_to_store = array(
            'com_id' => $cite[0]['com_id'],
            'prod_producto' => strtoupper($producto),
            'prod_resultado' => strtoupper($resultado),
            'indi_id' => $tipo_i,
            'prod_indicador' => strtoupper($indicador),
            'prod_linea_base' => $lb,
            'prod_meta' => $meta ,
            'prod_unidades' => strtoupper($unidad),
            'prod_fuente_verificacion' => strtoupper($verificacion), 
            'acc_id' => $ae,
            'mt_id' => $tp_met,
            'fecha' => date("d/m/Y H:i:s"),
            'prod_mod' => 2,
            'prod_cod'=>$codigo,
            'fun_id' => $this->fun_id,
            'or_id' => $or_id,
          );
          $this->db->insert('_productos', $data_to_store);
          $prod_id=$this->db->insert_id(); ////// id del producto
          /*---------------------------*/
          
          /*---------------- Temporalidad -------------------*/
          if($this->input->post('tipo_i')==1){
            for ($i=1; $i <=12 ; $i++) {
              if($this->input->post('m'.$i)!=0){
                $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$this->input->post('m'.$i));
              }
            }
          }
          if($this->input->post('tipo_i')==2){
            if($tp_met==3){
              for ($i=1; $i <=12 ; $i++) {
                if($this->input->post('m'.$i)!=0){
                  $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$this->input->post('m'.$i));
                }
              }
            }
            elseif($tp_met==1){
              for ($i=1; $i <=12 ; $i++) {
                $this->model_producto->add_prod_gest($prod_id,$this->gestion,$i,$this->input->post('meta'));
              }
            }
          }
          /*------------------------------------------------*/

          /*--------- iNSERT AUDI ADICIONAR OPERACION -------*/
          $data_to_store2 = array(
            'prod_id' => $prod_id, /// prod_id
            'cite_id' => $cite_id, /// cite_id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_producto_add', $data_to_store2);
          $proda_id=$this->db->insert_id();
          /*-----------------------------------------------*/

          if(count($this->model_modificacion->get_add_producto($proda_id))!=0 & $this->model_producto->get_producto_id($prod_id)!=0){
            $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE LA ACTIVIDAD :)');
          }
          else{
            $this->session->set_flashdata('danger','ERROR AL REGISTRAR LA ACTIVIDAD ...');
          }

          redirect(site_url("").'/mod/lista_operaciones/'.$cite_id.'');
      }
      else{
        echo "string";
      }
    }


    /*---- Eliminar Operacion-Producto ---*/
      function delete_operacion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $cite_id = $post['cite_id']; /// Cite Id
          $prod_id = $post['prod_id']; /// Prod Id
          $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
          $proyecto = $this->model_proyecto->get_id_proyecto($cite[0]['proy_id']); /// Datos del Proyecto


          if($this->copia_operacion($cite,$prod_id,3)){
            $update_prod = array(
              'prod_mod' => 2,
              'estado' => 3,
              'num_ip' => $this->input->ip_address(), 
              'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
              'fun_id' => $this->fun_id,
              );
            $this->db->where('prod_id', $prod_id);
            $this->db->update('_productos', $update_prod);

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


    /*----- REPORTE CITE - MODIFICACION FISICA ----*/
    public function reporte_modificacion_fisica($cite_id){
      $data['cite']=$this->model_modfisica->get_cite_fis($cite_id);
     //echo strtotime($data['cite'][0]['cite_fecha'])."---".$this->fecha_entrada;
        if(count($data['cite'])!=0){
          if($this->fecha_entrada<strtotime($data['cite'][0]['cite_fecha'])){
            $data['cabecera_modpoa']=$this->modificacionpoa->cabecera_modpoa($data['cite'],1);
            
            if(($data['cite'][0]['cite_codigo']!='' && $this->gestion==2022) || $this->tp_adm==1){
              $data['items_modificados']=$this->modificacionpoa->items_modificados_form4($cite_id);
            }
            else{
              $data['items_modificados']='<div style="font-size: 20px;font-family: Arial; color: red; text-align: center;"><b>PARA GENERAR EL DETALLE DE LA MODIFICACIÓN, DEBE CERRAR LA MODIFICACIÓN !!</b></div>';
            }

            $data['pie_mod']=$this->modificacionpoa->pie_modpoa($data['cite'],$data['cite'][0]['cite_codigo']);
            $data['pie_rep']='MOD_POA_FORM4_'.$data['cite'][0]['cite_nota'].' de '.date('d-m-Y',strtotime($data['cite'][0]['cite_fecha'])).' - '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].' | '.$data['cite'][0]['tipo_adm'].' '.$data['cite'][0]['act_descripcion'].' '.$data['cite'][0]['abrev'].'/'.$this->gestion.'';
            $this->load->view('admin/modificacion/moperaciones/reporte_modificacion_poa_form4', $data); 
          }
          else{
              $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']); 
              $data['titulo']=' <tr style="font-size: 8pt;">
                                    <td style="height: 1.2%"><b>PROYECTO</b></td>
                                    <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['proy_sisin'].' '.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['proy_nombre'].'</td>
                                 </tr>
                                 <tr style="font-size: 8pt;">
                                    <td style="height: 1.2%"><b>UNIDAD RESP.</b></td>
                                    <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                                 </tr>';

              if($data['cite'][0]['tp_id']==4){
                $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($data['cite'][0]['proy_id']);
                $data['titulo']='
                                <tr style="font-size: 8pt;">
                                  <td style="height: 1.2%"><b>'.$data['proyecto'][0]['tipo_adm'].' </b></td>
                                  <td style="width:90%;">: '.$data['proyecto'][0]['aper_programa'].' '.$data['proyecto'][0]['aper_proyecto'].' '.$data['proyecto'][0]['aper_actividad'].' '.$data['proyecto'][0]['tipo'].'   '.strtoupper($data['proyecto'][0]['act_descripcion']).' '.$data['proyecto'][0]['abrev'].'</td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="height: 1.2%"><b>SUBACTIVIDAD</b></td>
                                    <td style="width:90%;">: '.$data['cite'][0]['serv_cod'].' '.$data['cite'][0]['tipo_subactividad'].' '.$data['cite'][0]['serv_descripcion'].'</td>
                                </tr>';
              }

              $data['mes'] = $this->mes_nombre();
              $data['actividades']=$this->rep_actividades($cite_id); /// Lista de modificaciones (angiguo 2020-2021)
              $this->load->view('admin/modificacion/moperaciones/reporte_modificacion_operaciones', $data);
          }
        }
        else{
          echo "Error !!!";
        }

    }



    /*--- REPORTE CITE OPERACION (antiguo) ---*/
    public function rep_actividades($cite_id){
      $tabla ='';
      $cite=$this->model_modfisica->get_cite_fis($cite_id);
      $ope_adicionados=$this->model_modfisica->operaciones_adicionados($cite_id);
      if(count($ope_adicionados)!=0){
          $tabla.='
          <div style="font-size: 12px;font-family: Arial;">AGREGADOS ('.count($ope_adicionados).')</div>
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
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
            foreach($ope_adicionados as $rowp){
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
                <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).' '.$tp.'</td>';

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
      </table><br>';
      }

      $ope_modificados=$this->model_modfisica->operaciones_modificados($cite_id);
      if(count($ope_modificados)!=0){
          $tabla.='<div style="font-size: 12px;font-family: Arial;">MODIFICADOS ('.count($ope_modificados).')</div>';
          $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
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
            foreach($ope_modificados as $rowp){
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
                <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).' '.$tp.'</td>';

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
        </table><br>';
      }

      $ope_eliminados=$this->model_modfisica->operaciones_eliminados($cite_id);
      if(count($ope_eliminados)!=0){
          $tabla.='<div style="font-size: 12px;font-family: Arial;">ELIMINADOS ('.count($ope_eliminados).')</div>';
          $tabla.='
          <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
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
            foreach($ope_eliminados as $rowp){
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
                <td style="width: 3%; text-align: center;" bgcolor="#eceaea">'.round($rowp['prod_meta'],2).' '.$tp.'</td>';

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

      $tabla.='<br>
      <div style="font-size: 8px;font-family: Arial;">
      En atención a requerimiento de su unidad, comunicamos a usted que se ha procedido a efectivizar la modificación solicitada, toda vez que:<br>

      &nbsp;&nbsp;&nbsp;a)&nbsp;&nbsp;No compromete u obstaculiza el cumplimiento de los objetivos previstos en la gestión fiscal.<br>
      &nbsp;&nbsp;&nbsp;b)&nbsp;&nbsp;No vulnera o contraviene disposiciones legales.<br>
      &nbsp;&nbsp;&nbsp;c)&nbsp;&nbsp;No genera obligaciones o deudas por las modificaciones efectuadas.<br>
      &nbsp;&nbsp;&nbsp;d)&nbsp;&nbsp;No compromete el pago de obligaciones previstas en el presupuesto.
      </div>';

      return $tabla;
    }

    /*--- CERRAR MODIFICACION FIS (2020) ---*/
     public function cerrar_modificacion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $cite_id = $this->security->xss_clean($post['cite_id']); /// Ins id
        $observacion = $this->security->xss_clean($post['observacion']); /// Observacion
        $cite = $this->model_modfisica->get_cite_fis($cite_id); // Datos Cite

        $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
        if(count($verificando)==0){ // Creando campo para la distrital
          $data_to_store2 = array(
            'dist_id' => $cite[0]['dist_id'], /// dist_id
            'g_id' => $this->gestion, /// gestion
            'mod_ope' => 0, 
            'mod_req' => 0,
            'cert_poa' => 0,
          );
          $this->db->insert('conf_modificaciones_distrital', $data_to_store2);
          $mod_id=$this->db->insert_id();
        }

        if($cite[0]['cite_estado']==0){ /// Pendiente, Insert Codigo
          $verificando=$this->model_modrequerimiento->verif_modificaciones_distrital($cite[0]['dist_id']);
          $nro_mod=$verificando[0]['mod_ope']+1;
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
            'cite_codigo' => 'O_'.$cite[0]['adm'].'-'.$cite[0]['abrev'].'-'.$nro_cdep.''.$nro_mod,
            'cite_observacion' => strtoupper($observacion),
            'cite_estado' => 1,
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_fisica', $this->security->xss_clean($update_cite));
          /*------------------------------------------*/

          /*----- Update Configuracion mod distrital -----*/
          $update_conf= array(
            'mod_ope' => $nro_mod
          );
          $this->db->where('mod_id', $verificando[0]['mod_id']);
          $this->db->update('conf_modificaciones_distrital', $this->security->xss_clean($update_conf));
          /*----------------------------------------------*/
        }
        else{ /// Cerrado, Update Observacion
          $update_cite= array(
            'cite_observacion' => strtoupper($observacion),
            'fun_id'=>$this->fun_id
          );
          $this->db->where('cite_id', $cite_id);
          $this->db->update('cite_mod_fisica', $this->security->xss_clean($update_cite));
        }

        /*----------- redireccionar -------*/
        $this->session->set_flashdata('success','SE CERRO CORRECTAMENTE LA MODIFICACIÓN DE ACTIVIDADES');
        redirect(site_url("").'/mod/ver_mod_poa_fis/'.$cite_id.'');

      }
      else{
        echo "Error !!!";
      }
    }


    /*--- VER MODIFICACION POA---*/
    public function ver_modificacion_poa($cite_id){
      $data['cite'] = $this->model_modfisica->get_cite_fis($cite_id); // Datos Cite
      if(count($data['cite'])!=0){
        $data['menu']=$this->modificacionpoa->menu(3); //// genera menu
        $data['proyecto'] = $this->model_proyecto->get_id_proyecto($data['cite'][0]['proy_id']);
        $data['titulo']=$this->modificacionpoa->titulo_cabecera($data['cite']); /// CABECERA
        $data['datos_cite']=$this->modificacionpoa->datos_cite($data['cite']); /// DATOS CITE
        $this->load->view('admin/modificacion/moperaciones/ver_modificado_poa', $data);
      }
      else{
        redirect(site_url("").'/mod/list_cites/'.$data['cite'][0]['proy_id'].'');
      }
    }





    /*======== FUNCIONES EXTRAS =========*/
    /*------ Funcion Copia Operacion -------*/
    public function copia_operacion($cite,$prod_id,$tip_mod){
      // tip_mod=2 /// modificado
      // tip_mod=3 /// eliminado
      $producto=$this->model_producto->get_producto_id($prod_id);

      $data_to_store = array(
        'prodh_producto' => $producto[0]['prod_producto'],
        'indi_id' => $producto[0]['indi_id'],
        'prodh_indicador' => $producto[0]['prod_indicador'],
        'prodh_formula' => $producto[0]['prod_formula'],
        'prodh_linea_base' => $producto[0]['prod_linea_base'],
        'prodh_meta' => $producto[0]['prod_meta'],
        'prod_fuente_verificacion' => $producto[0]['prod_fuente_verificacion'],
        'pt_id' => $producto[0]['pt_id'],
        'prod_resultado' => $producto[0]['prod_resultado'],
        'acc_id' => $producto[0]['acc_id'],
        'or_id' => $producto[0]['or_id'],
        'prod_cod' => $producto[0]['prod_cod'],
        'prod_observacion' => $producto[0]['prod_observacion'],
        'mt_id' => $producto[0]['mt_id'],
      );
      $this->db->insert('_producto_historial', $data_to_store);
      $prodh_id=$this->db->insert_id();
        
      $prog=$this->model_producto->programado_producto($prod_id);

      foreach ($prog as $row) {
        $data_to_store2 = array(
        'prodh_id' => $prodh_id,
        'm_id' => $row['m_id'],
        'pg_fis' => $row['pg_fis'],
        'g_id' => $row['g_id'],
        );
        $this->db->insert('prod_programado_mensual_historial', $data_to_store2);
      }

      if($tip_mod==2){
        $data_to_store3 = array(
          'prod_id' => $prod_id,
        //  'prodh_id' => $prodh_id,
          'cite_id' => $cite[0]['cite_id'],
          'num_ip' => $this->input->ip_address(), 
          'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          'fun_id' => $this->session->userdata("fun_id"),
        );
        $this->db->insert('_producto_modificado', $data_to_store3);
        $prodm_id=$this->db->insert_id();

        if (count($this->model_modificacion->get_mod_producto($prodm_id))==1) {
          return true;
        }
        else{
          return false;
        }
      }
      else{
        /*---- Insert Producto Delete -----*/
          $data_to_store = array( 
            'prod_id' => $prod_id,
           // 'prodh_id' => $prodh_id,
            'cite_id' => $cite[0]['cite_id'], /// Cite Id
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'fun_id' => $this->fun_id,
            );
          $this->db->insert('_producto_delete', $data_to_store);
          $dlte_id=$this->db->insert_id();
        /*----------------------------------*/

        if (count($this->model_modificacion->get_delete_producto($dlte_id))==1) {
          return true;
        }
        else{
          return false;
        }

      }

    }

    /*--- ACTUALIZA CODIGO DE ACTIVIDAD ----*/
    public function update_codigo($cite_id){
      $cite=$this->model_modfisica->get_cite_fis($cite_id); /// Datos cite
      $productos = $this->model_producto->lista_operaciones($cite[0]['com_id'],$this->gestion); // Lista de productos
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

      $this->session->set_flashdata('success','LOS CÓDIGOS DE ACTIVIDAD SE ACTUALIZARON CORRECATMENTE :)');
      redirect('mod/lista_operaciones/'.$cite[0]['cite_id']);
    }





  /*--- LISTA DE OBJETIVO REGIONAL (GASTO CORRIENTE )-----*/
    public function lista_oregional($proy_id){
      $list_oregional=$this->model_objetivoregion->list_proyecto_oregional($proy_id);
      $tabla='';
      if(count($list_oregional)==1){
        $tabla.=' <section class="col col-3">
                    <label class="label"><b>OPERACIÓN REGIONAL '.$list_oregional[0]['or_id'].'</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="hidden" name="or_id" id="or_id" value="'.$list_oregional[0]['or_id'].'">
                      <input type="text" value="'.$list_oregional[0]['or_codigo'].'.- '.$list_oregional[0]['or_objetivo'].'" disabled>
                    </label>
                  </section>'; 
      }
      else{
          $tabla.='<section class="col col-6">
                  <label class="label"><b>ALINEACIÓN OPERACIÓN REGIONAL '.$this->gestion.'</b></label>
                    <select class="form-control" id="or_id" name="or_id" title="SELECCIONE">
                      <option value="0">SELECCIONE ALINEACIÓN OPERACIÓN</option>';
                      foreach($list_oregional as $row){ 
                        $tabla.='<option value="'.$row['or_id'].'">'.$row['og_codigo'].'.|'.$row['or_codigo'].'. .- '.$row['or_objetivo'].'</option>';    
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
    /*------- GENERAR MENU --------*/

    /*--------------------------------------------------------------------------------*/
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