<?php
class Cobjetivo_gestion extends CI_Controller {
  public $rol = array('1' => '3','2' => '4');  
  public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
          $this->load->library('pdf');
          $this->load->library('pdf2');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('resultados/model_resultado');
          $this->load->model('mestrategico/model_mestrategico');
          $this->load->model('mestrategico/model_objetivogestion');
          $this->load->model('mestrategico/model_objetivoregion');
          $this->load->model('menu_modelo');
          $this->load->model('Users_model','',true);
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->fun_id = $this->session->userData('fun_id');
        }else{
            redirect('/','refresh');
        }
    }

    /*------- TIPO DE RESPONSABLE --------*/
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
    
    /*----- LISTA OBJETIVOS DE GESTION ----*/
    public function list_objetivos_gestion(){
      $data['menu']=$this->menu();
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['oestrategicos'] = $this->model_mestrategico->list_objetivos_estrategicos(); /// Objetivos Estrategicos
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $data['ogestion']=$this->mis_ogestion_gral();
      $this->load->view('admin/mestrategico/objetivos_gestion/list_ogestion_general', $data);
    }

     /*---------- LISTA MIS OBJETIVOS DE GESTION ------------*/
    public function mis_ogestion_gral(){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// OBJETIVOS DE GESTION GENERAL

      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>OBJETIVOS DE GESTI&Oacute;N - '.$this->gestion.'</strong></h2>  
                    </header>
                <div>
                  <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - RESULTADO INTERMEDIO">NUEVO OBJETIVO DE GESTI&Oacute;N</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th style="width:1%;">NRO</th>
                          <th style="width:1%;">M/E</th>
                          <th style="width:2%;">OBJETIVOS REGIONALES</th>
                          <th style="width:2%;">REPORTE</th>
                          <th style="width:2%;">COD. O.E.</th>
                          <th style="width:2%;">COD. A.E.</th>
                          <th style="width:2%;">COD. O.G.</th>
                          <th style="width:10%;">OBJETIVO DE GESTI&Oacute;N</th>
                          <th style="width:10%;">PRODUCTO</th>
                          <th style="width:10%;">RESULTADO</th>
                          <th style="width:5%;">TP. INDI.</th>
                          <th style="width:7%;">INDICADOR</th>
                          <th style="width:4%;">LINEA BASE</th>
                          <th style="width:4%;">META</th>
                          <th style="width:4%;" title="CHUQUISACA">CH.</th>
                          <th style="width:4%;" title="LA PAZ">LPZ.</th>
                          <th style="width:4%;" title="COCHABAMBA">CBBA.</th>
                          <th style="width:4%;" title="ORURO">OR.</th>
                          <th style="width:4%;" title="POTOSI">POT.</th>
                          <th style="width:4%;" title="TARIJA">TJA.</th>
                          <th style="width:4%;" title="SANTA CRUZ">SCZ.</th>
                          <th style="width:4%;" title="BENI">BE.</th>
                          <th style="width:4%;" title="PANDO">PN</th>
                          <th style="width:4%;" title="OFICINA NACIONAL">OFN</th>
                          <th style="width:10%;">MEDIO VERIFICACI&Oacute;N</th>
                          <th style="width:5%;">PPTO.<br>'.$this->gestion.'</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($ogestion  as $row){
                          $presupuesto_gc=$this->model_objetivogestion->get_ppto_ogestion_gc($row['og_id']); // ppto Gasto Corriente
                          $ppto_gc=0;$ppto_pi=0;
                          if(count($presupuesto_gc)!=0){
                            $ppto_gc=$presupuesto_gc[0]['presupuesto'];
                          }
                          $nro++;
                          $tabla .='<tr title='.$row['og_id'].'>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-default mod_ff"  title="MODIFICAR DE GESTION" name="'.$row['og_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="30" HEIGHT="30"/></a><br>';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-default del_ff" title="ELIMINAR OBJETIVO DE GESTION"  name="'.$row['og_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="30" HEIGHT="30"/></a><br>';
                            $tabla .='</td>';
                            $tabla .='<td bgcolor="#cef3ee"><br><a href="'.site_url("").'/me/objetivos_regionales/'.$row['og_id'].'" class="btn btn-default" title="OBJETIVOS REGIONALES"><img src="'.base_url().'assets/img/folder.png" WIDTH="30" HEIGHT="30"/></a></td>';
                            $tabla .='<td bgcolor="#cef3ee"><br><a href="javascript:abreVentana(\''.site_url("").'/me/rep_oregionales/'.$row['og_id'].'\');" title="GENERAR REPORTE PDF" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></a></td>';
                            $tabla .='<td>'.$row['obj_codigo'].'</td>';
                            $tabla .='<td>'.$row['acc_codigo'].'</td>';
                            $tabla .='<td><b><font color=blue size=4>'.$row['og_codigo'].'</font></b></td>';
                            $tabla .='<td>'.$row['og_objetivo'].'</td>';
                            $tabla .='<td>'.$row['og_producto'].'</td>';
                            $tabla .='<td>'.$row['og_resultado'].'</td>';
                            $tabla .='<td>'.strtoupper($row['indi_descripcion']).'</td>';
                            $tabla .='<td>'.$row['og_indicador'].'</td>';
                            $tabla .='<td>'.$row['og_linea_base'].'</td>';
                            $tabla .='<td>'.$row['og_meta'].'</td>';
                            
                            for ($i=1; $i <=10 ; $i++) { 
                              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
                              if(count($dep)!=0){
                                $tabla.='<td bgcolor="#e6f5e0"><b>'.$dep[0]['prog_fis'].'</b></td>';
                              }
                              else{
                                $tabla.='<td bgcolor="#e6f5e0"><b>0</b></td>';
                              }
                            }
                            $tabla.='<td>'.$row['og_verificacion'].'</td>';
                            $tabla.='<td align="right">'.number_format(($ppto_gc), 2, ',', '.').'</td>';
                          $tabla.='</tr>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';

      return $tabla;
    }

   /*------- Verifica Codigo Operacion ------*/ 
  function verif_codigo(){
    if($this->input->is_ajax_request()){
        $post = $this->input->post();

        $codigo= $this->security->xss_clean($post['codigo']); /// Codigo

        $verif_cod=$this->model_objetivogestion->get_cod_objetivosgestion($codigo);
        if(count($verif_cod)!=0){
          echo "true"; ///// no existe un CI registrado
        }
        else{
          echo "false"; //// existe el CI ya registrado
        } 
    }else{
      show_404();
    }
  }


    /*============================= POR ACCION ESTRATEGICA =========================*/

    /*----- LISTA OBJETIVOS DE GESTION SEGUN ACCION ESTRATEGICO ----*/
    public function objetivos_gestion($acc_id){
      $data['menu']=$this->menu();
      $data['resp']=$this->session->userdata('funcionario');
      $data['res_dep']=$this->tp_resp();
      $data['configuracion']=$this->model_proyecto->configuracion_session();
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
      $data['ogestion']=$this->mis_ogestion($acc_id);
      $data['indi']= $this->model_proyecto->indicador(); /// indicador
      $this->load->view('admin/mestrategico/objetivos_gestion/list_ogestion', $data);
    }

    /*---------- MIS OBJETIVOS DE GESTION ------------*/
    public function mis_ogestion($acc_id){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion($acc_id); /// OBJETIVOS DE GESTION
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($acc_id); // ACCIONES ESTRATEGICAS
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']); /// OBJETIVOS ESTRATEGICOS

      $tabla ='';
      $tabla .='<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-darken" >
                    <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>OBJETIVOS DE GESTI&Oacute;N - '.$this->gestion.'</strong></h2>  
                    </header>
                <div>
                  <a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success" style="width:14%;" title="NUEVO REGISTRO - RESULTADO INTERMEDIO">NUEVO OBJETIVO DE GESTI&Oacute;N</a><br><br>
                  <div class="widget-body no-padding">
                    <table id="dt_basic" class="table table table-bordered" width="100%">
                      <thead>
                        <tr>
                          <th style="width:1%;">NRO</th>
                          <th style="width:1%;">M/E</th>
                          <th style="width:2%;">OBJETIVOS REGIONALES</th>
                          <th style="width:10%;">OBJETIVO DE GESTI&Oacute;N</th>
                          <th style="width:10%;">PRODUCTO</th>
                          <th style="width:10%;">RESULTADO</th>
                          <th style="width:5%;">TP. INDI.</th>
                          <th style="width:7%;">INDICADOR</th>
                          <th style="width:4%;">LINEA BASE</th>
                          <th style="width:4%;">META</th>
                          <th style="width:4%;" title="CHUQUISACA">CH.</th>
                          <th style="width:4%;" title="LA PAZ">LPZ.</th>
                          <th style="width:4%;" title="COCHABAMBA">CBBA.</th>
                          <th style="width:4%;" title="ORURO">OR.</th>
                          <th style="width:4%;" title="POTOSI">POT.</th>
                          <th style="width:4%;" title="TARIJA">TJA.</th>
                          <th style="width:4%;" title="SANTA CRUZ">SCZ.</th>
                          <th style="width:4%;" title="BENI">BE.</th>
                          <th style="width:4%;" title="PANDO">PN</th>
                          <th style="width:4%;" title="OFICINA NACIONAL">OFN</th>
                          <th style="width:10%;">MEDIO VERIFICACI&Oacute;N</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $nro=0;
                        foreach($ogestion  as $row){
                          $nro++;
                          $tabla .='<tr>';
                            $tabla .='<td>'.$nro.'</td>';
                            $tabla .='<td align="center">';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_mod_ff" class="btn btn-xs mod_ff"  title="MODIFICAR DATOS - OBJETIVOS DE GESTIÓN" name="'.$row['og_id'].'"><img src="' . base_url() . 'assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                              $tabla .='<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR OBJETIVO DE GESTIÓN"  name="'.$row['og_id'].'"><img src="'.base_url().'assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a><br>';
                            $tabla .='</td>';
                            $tabla .='<td bgcolor="#cef3ee"><a href="'.site_url("").'/me/objetivos_regionales/'.$row['og_id'].'" class="btn btn-default" title="OBJETIVOS REGIONALES"><img src="'.base_url().'assets/img/folder.png" WIDTH="35" HEIGHT="35"/></a></td>';
                            $tabla .='<td>'.$row['og_objetivo'].'</td>';
                            $tabla .='<td>'.$row['og_producto'].'</td>';
                            $tabla .='<td>'.$row['og_resultado'].'</td>';
                            $tabla .='<td>'.strtoupper($row['indi_descripcion']).'</td>';
                            $tabla .='<td>'.$row['og_indicador'].'</td>';
                            $tabla .='<td>'.$row['og_linea_base'].'</td>';
                            $tabla .='<td>'.$row['og_meta'].'</td>';
                            
                            for ($i=1; $i <=10 ; $i++) { 
                              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
                              if(count($dep)!=0){
                                $tabla.='<td bgcolor="#e6f5e0"><b>'.$dep[0]['prog_fis'].'</b></td>';
                              }
                              else{
                                $tabla.='<td bgcolor="#e6f5e0"><b>0</b></td>';
                              }
                            }
                            $tabla.='<td>'.$row['og_verificacion'].'</td>';
                          $tabla.='</tr>';
                        }
                      $tabla .='
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </article>';

      return $tabla;
    }
    

    /*------- VALIDA OBJETIVO DE GESTION -------*/
    public function valida_ogestion(){
      if($this->input->post()) {
        $post = $this->input->post();
        $tp = $this->security->xss_clean($post['tp']); /// tipo id
        $form = $this->security->xss_clean($post['form']); /// from 1: por accion estrategica, 0: lista de Objetivos de gestion 
        
        if($tp==1){
          $acc_id = $this->security->xss_clean($post['acc_id']); /// acc id
          $objetivo = $this->security->xss_clean($post['ogestion']); /// Objetivo
          $codigo = $this->security->xss_clean($post['cod']); /// Codigo
          $producto = $this->security->xss_clean($post['producto']); /// Producto
          $resultado = $this->security->xss_clean($post['resultado']); /// Resultado
          $tp_indi = $this->security->xss_clean($post['tp_indi']); /// Tipo de Indicador
          $indicador = $this->security->xss_clean($post['indicador']); /// Indicador
          $lbase = $this->security->xss_clean($post['lbase']); /// Linea Base
          $meta = $this->security->xss_clean($post['meta']); /// Meta
          $verificacion = $this->security->xss_clean($post['verificacion']); /// Verificacion
          $unidad = $this->security->xss_clean($post['unidad']); /// Unidad
          $observacion = $this->security->xss_clean($post['observacion']); /// Observacion

          $data_to_store = array( 
            'acc_id' => $acc_id,
            'og_codigo' => $codigo,
            'og_objetivo' => strtoupper($objetivo),
            'og_producto' => strtoupper($producto),
            'og_resultado' => strtoupper($resultado),
            'indi_id' => $tp_indi,
            'og_indicador' => strtoupper($indicador),
            'og_linea_base' => $lbase,
            'og_meta' => $meta,
            'og_verificacion' => $verificacion,
            'og_unidad' => strtoupper($unidad),
            'og_observacion' => strtoupper($observacion),
            'g_id' => $this->gestion,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
          );
          $this->db->insert('objetivo_gestion', $data_to_store);
          $og_id=$this->db->insert_id();

          for ($i=1; $i <=10 ; $i++) {
            $pfis=$this->security->xss_clean($post['m'.$i]);
            $estado=0;
            if($pfis!=0){
              $estado=1;
            }

            $data_to_store4 = array( 
              'og_id' => $og_id, /// obj id
              'dep_id' => $i, /// dep id 
              'prog_fis' => $pfis, /// Valor mes
              'g_id' => $this->gestion, /// Gestion
              'estado' => $estado, /// Estado
            );
            $this->db->insert('objetivo_programado_mensual', $data_to_store4);
          }

        }
        else{
          $og_id = $this->security->xss_clean($post['mog_id']); /// Obj id
          $acc_id = $this->security->xss_clean($post['macc_id']); /// acc id
          $objetivo = $this->security->xss_clean($post['mogestion']); /// Objetivo
          $producto = $this->security->xss_clean($post['mproducto']); /// Producto
          $resultado = $this->security->xss_clean($post['mresultado']); /// Resultado
          $tp_indi = $this->security->xss_clean($post['mtp_indi']); /// Tipo de Indicador
          $indicador = $this->security->xss_clean($post['mindicador']); /// Indicador
          $lbase = $this->security->xss_clean($post['mlbase']); /// Linea Base
          $meta = $this->security->xss_clean($post['mmeta']); /// Meta
          $verificacion = $this->security->xss_clean($post['mverif']); /// Verificacion
          $unidad = $this->security->xss_clean($post['munidad']); /// Unidad
          $observacion = $this->security->xss_clean($post['mobservacion']); /// Observacion

          $update_og= array(
            'acc_id' => $acc_id,
            'og_objetivo' => strtoupper($objetivo),
            'og_producto' => strtoupper($producto),
            'og_resultado' => strtoupper($resultado),
            'indi_id' => $tp_indi,
            'og_indicador' => strtoupper($indicador),
            'og_linea_base' => $lbase,
            'og_meta' => $meta,
            'og_verificacion' => strtoupper($verificacion),
            'og_unidad' => strtoupper($unidad),
            'og_observacion' => strtoupper($observacion),
            'estado' => 2,
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR'])
          );
          $this->db->where('og_id', $og_id);
          $this->db->update('objetivo_gestion', $update_og);

          for ($i=1; $i <=10 ; $i++) {
            $estado=0;
            $prog_fis=0;

            $pfis=$this->security->xss_clean($post['mm'.$i]);
            if($pfis!=0 & $pfis!=''){
              $estado=1;
              $prog_fis=$pfis;
            }

            $verif=$this->model_objetivogestion->get_ogestion_regional($og_id,$i);
            if(count($verif)!=0){
             // echo $og_id.'--'.$i.'--'.count($verif).'-- estado '.$estado.'--'.$pfis.'<br>';
              $update_ogp= array(
                'prog_fis' => $prog_fis,
                'estado' => $estado
              );
              $this->db->where('pog_id', $verif[0]['pog_id']);
              $this->db->update('objetivo_programado_mensual', $update_ogp);

              /*------- Actualizando meta objetivo regional ----------*/
              if($this->model_objetivoregion->get_oregional_por_progfis($verif[0]['pog_id'])!=0){
                  $update_ogp= array(
                    'or_meta' => $prog_fis,
                    'fun_id' => $this->fun_id
                  );
                  $this->db->where('pog_id', $verif[0]['pog_id']);
                  $this->db->update('objetivos_regionales', $update_ogp);
              }
              /*-------------------------------------------------------*/
            }
            else{
              $data_to_store4 = array( 
                'og_id' => $og_id, /// og id
                'dep_id' => $i, /// dep id 
                'prog_fis' => $prog_fis, /// Valor prog
                'g_id' => $this->gestion, /// Gestion
                'estado' => $estado, /// Estado
              );
              $this->db->insert('objetivo_programado_mensual', $data_to_store4);
            }
          }
        }

        $this->session->set_flashdata('success','SE REGISTRO CORRECTAMENTE)');
        if($form==1){
          redirect(site_url("").'/me/objetivos_gestion/'.$acc_id.'');
        }
        else{
          redirect(site_url("").'/me/mis_ogestion');
        }
        
      } else {
          show_404();
      }
    }

    /*------- GET OBJETIVO GESTION -------*/
    public function get_ogestion(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $og_id = $this->security->xss_clean($post['og_id']); /// Obj id
        $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 
        $ogestion_programado=$this->model_objetivogestion->get_objetivosgestion_temporalidad($og_id);
        $suma=0;
        for ($i=1; $i <=10; $i++) { 
          $dep['reg'.$i.'']=0;
          $dep_verif['verif'.$i.'']=false;
          $titulo['tit'.$i.'']='A PROGRAMAR';
        }

        if(count($ogestion_programado)!=0){
          for ($i=1; $i <=10 ; $i++) { 
            $dep['reg'.$i.'']=$ogestion_programado[0]['reg'.$i.''];
            if(count($this->model_objetivogestion->get_ogestion_oregional_temporalidad($og_id,$i))!=0){
              $dep_verif['verif'.$i.'']=true;
              $titulo['tit'.$i.'']='REGIONAL YA PROGRAMADO';
            }
          }
          $suma=$ogestion_programado[0]['programado_total']+$ogestion[0]['og_linea_base'];
        }

        if(count($ogestion)!=0){
          $result = array(
            'respuesta' => 'correcto',
            'ogestion' => $ogestion,
            'oprogramado' => $dep,
            'verif_programado' => $dep_verif,
            'titulo' => $titulo,
            'suma' => round($suma,2),
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

    /*-------- ELIMINAR OBJETIVO DE GESTION --------*/
    function delete_ogestion(){
      if ($this->input->is_ajax_request() && $this->input->post()) {
          $post = $this->input->post();
          $og_id = $this->security->xss_clean($post['og_id']);

          /*$this->db->where('og_id', $og_id);
          $this->db->delete('objetivo_programado_mensual');*/

          /*----- UPDATE O. GESTION ----*/
          $update_og= array(
            'fun_id' => $this->fun_id,
            'num_ip' => $this->input->ip_address(), 
            'nom_ip' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
            'estado' => 3
          );
          $this->db->where('og_id', $og_id);
          $this->db->update('objetivo_gestion', $update_og);

          $ogestion=$this->model_objetivogestion->get_objetivosgestion($og_id); 
          if(count($ogestion)==0){
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


   /*----- REPORTE objetivo de Gestion GENERAL -----*/
    public function reporte_ogestion(){
      $data['mes'] = $this->mes_nombre();

      $ogestion = $this->model_objetivogestion->list_objetivosgestion_general(); /// OBJETIVOS DE GESTION GENERAL
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;">
        <thead>
          <tr style="font-size: 7px;" bgcolor="#1c7368" align=center>
            <th style="width:1%;height:20px;color:#FFF;">N°</th>
            <th style="width:2.4%;color:#FFF;">COD. O.E.</th>
            <th style="width:2.4%;color:#FFF;">COD. ACE.</th>
            <th style="width:2.4%;color:#FFF;">COD. ACP.</th>
            <th style="width:12%;color:#FFF;">ACCI&Oacute;N A CORTO PLAZO</th>
            <th style="width:5%;color:#FFF;">PRODUCTO</th>
            <th style="width:11%;color:#FFF;">RESULTADO</th>
            <th style="width:10%;color:#FFF;">INDICADOR</th>
            <th style="width:3.3%;color:#FFF;">LINEA BASE</th>
            <th style="width:3.3%;color:#FFF;">META</th>
            <th style="width:3.3%;color:#FFF;" title="CHUQUISACA">CH.</th>
            <th style="width:3.3%;color:#FFF;" title="LA PAZ">LPZ.</th>
            <th style="width:3.3%;color:#FFF;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3.3%;color:#FFF;" title="ORURO">OR.</th>
            <th style="width:3.3%;color:#FFF;" title="POTOSI">POT.</th>
            <th style="width:3.3%;color:#FFF;" title="TARIJA">TJA.</th>
            <th style="width:3.3%;color:#FFF;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3.3%;color:#FFF;" title="BENI">BE.</th>
            <th style="width:3.3%;color:#FFF;" title="PANDO">PN</th>
            <th style="width:3.3%;color:#FFF;" title="OFICINA NACIONAL">OFN</th>
            <th style="width:8%;color:#FFF;">MEDIO VERIFICACI&Oacute;N</th>
            <th style="width:6%;color:#FFF;">PPTO.<br>'.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0; $monto_total=0;
        foreach($ogestion  as $row){
          $presupuesto_gc=$this->model_objetivogestion->get_ppto_ogestion_gc($row['og_id']); // ppto Gasto Corriente
          
            $ppto_gc=0;$ppto_pi=0;
            if(count($presupuesto_gc)!=0){
              $ppto_gc=$presupuesto_gc[0]['presupuesto'];
            }

            $prc='';
            if($row['indi_id']==2){
              $prc='%';
            }
          $nro++;
          $tabla .='<tr style="font-size: 7px;">';
            $tabla .='<td style="width:1%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:2.4%;" align="center">'.$row['obj_codigo'].'</td>';
            $tabla .='<td style="width:2.4%;" align="center">'.$row['acc_codigo'].'</td>';
            $tabla .='<td style="width:2.4%; font-size: 8px;" align="center" bgcolor="#f1eeee"><b>'.$row['og_codigo'].'</b></td>';
            $tabla .='<td style="width:12%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:5%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:11%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:3.3%;" align=center>'.round($row['og_linea_base'],2).'</td>';
            $tabla .='<td style="width:3.3%;" align=center>'.round($row['og_meta'],2).''.$prc.'</td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5" align=center>'.round($dep[0]['prog_fis'],2).''.$prc.'</td>';
              }
              else{
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5" align=center>0</td>';
              }
            }
            $tabla.='<td style="width:8%;">'.$row['og_verificacion'].'</td>';
            $tabla.='<td style="width:6%; text-align: right;">'.number_format($ppto_gc, 2, ',', '.').'</td>';
          $tabla.='</tr>';

          $monto_total=$monto_total+$ppto_gc;
        }
        $tabla.='
        </tbody>
        <tr>
          <td style="height:11px; text-align: right;" colspan=21><b>PRESUPUESTO TOTAL : </b></td>
          <td style="text-align: right;">'.number_format($monto_total, 2, ',', '.').'</td>
        </tr>
       </table>';


      $data['ogestion']=$tabla;
      
      $this->load->view('admin/mestrategico/objetivos_gestion/reporte_ogestion_general', $data);
    }


    /*----- Reporte objetivo de Gestion segun Accion estrategica -----*/
    public function reporte_objetivos_gestion($acc_id){
      $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
      if(count($data['accion_estrategica'])!=0){
        $data['mes'] = $this->mes_nombre();
        $data['accion_estrategica']=$this->model_mestrategico->get_acciones_estrategicas($acc_id);
        $data['obj_estrategico']=$this->model_mestrategico->get_objetivos_estrategicos($data['accion_estrategica'][0]['obj_id']);
        $data['ogestion']=$this->rep_list_ogestion($acc_id);

        $this->load->view('admin/mestrategico/objetivos_gestion/reporte_ogestion', $data); 
      }
      else{
        echo "Error !!!";
      }
    }

    /*----- Reporte Lista de objetivo de Gestion -----*/
    public function rep_list_ogestion($acc_id){
      $ogestion = $this->model_objetivogestion->list_objetivosgestion($acc_id); /// OBJETIVOS DE GESTION
      $acciones = $this->model_mestrategico->get_acciones_estrategicas($acc_id); // ACCIONES ESTRATEGICAS
      $objetivos =$this->model_mestrategico->get_objetivos_estrategicos($acciones[0]['obj_id']); /// OBJETIVOS ESTRATEGICOS
      $tabla='';
      $tabla.='  
      <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
        <thead>
          <tr style="font-size: 8px;" bgcolor="#d8d8d8" align=center>
            <th style="width:2%;height:20px;">#</th>
            <th style="width:3%;">COD. O.E.</th>
            <th style="width:3%;">COD. A.E.</th>
            <th style="width:10%;">OBJETIVO DE GESTI&Oacute;N</th>
            <th style="width:10%;">PRODUCTO</th>
            <th style="width:10%;">RESULTADO</th>
            <th style="width:5%;">TP. INDI.</th>
            <th style="width:9%;">INDICADOR</th>
            <th style="width:3.3%;">LINEA BASE</th>
            <th style="width:3.3%;">META</th>
            <th style="width:3.3%;" title="CHUQUISACA">CH.</th>
            <th style="width:3.3%;" title="LA PAZ">LPZ.</th>
            <th style="width:3.3%;" title="COCHABAMBA">CBBA.</th>
            <th style="width:3.3%;" title="ORURO">OR.</th>
            <th style="width:3.3%;" title="POTOSI">POT.</th>
            <th style="width:3.3%;" title="TARIJA">TJA.</th>
            <th style="width:3.3%;" title="SANTA CRUZ">SCZ.</th>
            <th style="width:3.3%;" title="BENI">BE.</th>
            <th style="width:3.3%;" title="PANDO">PN</th>
            <th style="width:3.3%;" title="OFICINA NACIONAL">OFN</th>
            <th style="width:8%;">MEDIO VERIFICACI&Oacute;N</th>
          </tr>
        </thead>
        <tbody>';
        $nro=0;
        foreach($ogestion  as $row){
          $nro++;
          $tabla .='<tr style="font-size: 7px;">';
            $tabla .='<td style="width:2%; height:15px;" align=center>'.$nro.'</td>';
            $tabla .='<td style="width:3%;" align="center">'.$objetivos[0]['obj_codigo'].'</td>';
            $tabla .='<td style="width:3%;" align="center">'.$acciones[0]['acc_codigo'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_objetivo'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_producto'].'</td>';
            $tabla .='<td style="width:10%;">'.$row['og_resultado'].'</td>';
            $tabla .='<td style="width:5%;">'.strtoupper($row['indi_descripcion']).'</td>';
            $tabla .='<td style="width:9%;">'.$row['og_indicador'].'</td>';
            $tabla .='<td style="width:3.3%;">'.$row['og_linea_base'].'</td>';
            $tabla .='<td style="width:3.3%;">'.$row['og_meta'].'</td>';
            
            for ($i=1; $i <=10 ; $i++) { 
              $dep=$this->model_objetivogestion->get_ogestion_regional($row['og_id'],$i);
              if(count($dep)!=0){
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5">'.$dep[0]['prog_fis'].'</td>';
              }
              else{
                $tabla.='<td style="width:3.3%;" bgcolor="#f5f5f5">0</td>';
              }
            }
            $tabla.='<td style="width:8%;">'.$row['og_verificacion'].'</td>';
          $tabla.='</tr>';
        }
        $tabla.='
        </tbody>
       </table>';

       return $tabla;
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

    /*------------------------------------- MENU -----------------------------------*/
    function menu(){
      $enlaces=$this->menu_modelo->get_Modulos(1);
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

    /*------------------------- COMBO RESPONSABLES ----------------------*/
    public function combo_funcionario_unidad_organizacional($accion=''){ 
      $salida="";
      $accion=$_POST["accion"];
      switch ($accion) {
        case 'unidad':
        $salida="";
          $id_pais=$_POST["elegido"];
          
          $combog = pg_query('SELECT u.*
          from funcionario f
          Inner Join unidadorganizacional as u On u."uni_id"=f."uni_id"
          where  f."fun_id"='.$id_pais.'');
          while($sql_p = pg_fetch_row($combog))
          {$salida.= "<option value='".$sql_p[0]."'>".$sql_p[2]."</option>";}

        echo $salida; 
        //return $salida;
        break;
      }
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

    function rolfunn($tp_rol){
      $valor=false;
      $data = $this->Users_model->get_datos_usuario_roles($this->session->userdata('fun_id'),$tp_rol);
      if(count($data)!=0){
        $valor=true;
      }
      return $valor;
    }
}