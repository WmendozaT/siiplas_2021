<?php
class Crep_consultafinanciera extends CI_Controller { 
  public function __construct (){ 
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
        $this->load->model('reportes/mreporte_operaciones/mrep_operaciones');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/insumos/minsumos');
        $this->load->model('programacion/insumos/model_insumo'); /// gestion 2020
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_componente');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_actividad');
        $this->load->model('mantenimiento/model_estructura_org');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('mestrategico/model_objetivoregion');
        $this->load->model('programacion/insumos/model_insumo');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('modificacion/model_modrequerimiento'); /// Gestion 2020
        $this->load->model('modificacion/model_modfisica'); /// Gestion 2020
        $this->load->model('menu_modelo');
        $this->load->model('Users_model','',true);
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->fun_id = $this->session->userdata("fun_id");
        $this->tmes = $this->session->userData('trimestre');
        $this->ppto= $this->session->userData('verif_ppto');
        $this->verif_mes=$this->session->userData('mes_actual'); /// mes por decfecto
        $this->mes_sistema=$this->session->userData('mes'); /// mes sistema
        $this->load->library('genera_informacion');
        }else{
            redirect('/','refresh');
        }
    }
    
    //// INDEX
    public function index(){
      $data['menu']=$this->menu(7);
      $data['list']=$this->menu_nacional();
      $data['mensaje']='<div class="jumbotron"><h1>Consulta Presupuestaria POA '.$this->gestion.'</h1><p>Reporte Presupuestaria POA (Requerimientos) Regional y Distrital.</p><ol style="font-size:16px;"><li>Genera Informacion Presupuestaria por Partidas</li></ol></div>';
      $this->load->view('admin/reportes_cns/rep_consultas_presupuestarias/menu_index', $data);

      //echo $this->consolidado_requerimientos_unidad_partida(15255,4,64);
    }

    //// MENU UNIDADES ORGANIZACIONAL 2020 - 2021
    public function menu_nacional(){
    $tabla='';
    $regionales=$this->model_proyecto->list_departamentos();
    $unidades=$this->model_estructura_org->list_unidades_apertura();
      $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <header><b>CONSULTA PRESUPUESTO POA '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-2">
                    <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                    <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                    <option value="">SELECCIONE REGIONAL</option>
                    <option value="0">INSTITUCIONAL CNS</option>';
                    foreach($regionales as $row){
                      if($row['dep_id']!=0){
                        $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                      }
                    }
                    $tabla.='
                    </select>
                  </section>

                  <section class="col col-2">
                    <label class="label">TIPO DE GASTO</label>
                    <select class="form-control" id="tp_id" name="tp_id" title="SELECCIONE TIPO DE GASTO">
                    </select>
                  </section>

                  <section class="col col-5">
                    <label class="label">UNIDAD / ESTABLECIMIENTO / PROYECTO</label>
                    <select class="form-control" id="aper_id" name="aper_id" title="SELECCIONE UNIDAD, ESTABLECIMIENTO, PROYECTO DE INVERSION">
                    </select>
                  </section>

                  <section class="col col-2">
                    <label class="label">PARTIDA</label>
                    <select class="form-control" id="par_id" name="par_id" title="SELECCIONE TIPO REPORTE">
                    </select>
                  </section>

                </div>
              </fieldset>
          </form>
          </div>
        </article>';
    return $tabla;
  }


    /*--- GET PPTO X CATEGORIS PROGRAMA INSTITUCIONAL---*/
    public function get_ppto_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
     
        $salida='';

          $ppto_pi=$this->model_insumo->consolidado_ppto_x_programas_institucional(1); /// Proyecto de Inversion
          $ppto_gcorriente=$this->model_insumo->consolidado_ppto_x_programas_institucional(4); /// Gasto Corriente
          $regionales=$this->model_proyecto->list_departamentos();

          /// ---- Gasto Corriente
          $tabla='';
          $tabla.='<div class="widget-body">
                    <hr class="simple">
                    <ul id="myTab1" class="nav nav-tabs bordered">
                      <li class="active">
                        <a href="#s1" data-toggle="tab"><b>PPTO. POA GASTO CORRIENTE</b></a>
                      </li>
                      <li>
                        <a href="#s2" data-toggle="tab"><b>PPTO. POA PROYECTO DE INVERSIÓN</b></a>
                      </li>
                    </ul>
        
                    <div id="myTabContent1" class="tab-content padding-10">
                      <div class="tab-pane fade in active" id="s1">
                        <div class="row">
                          <h1><b>INSTITUCIONAL - GASTO CORRIENTE '.$this->gestion.' (APERTURAS PROGRAMATICAS)</b></h1>
                          <hr>
                          <div class="col-sm-6">
                            <center>
                            <table class="table table-bordered" style="width:80%;">
                              <thead>
                                <tr>
                                  <th scope="col" style="width:30%;">PROGRAMA '.$this->gestion.'</th>
                                  <th scope="col" style="width:20%;">PARTIDA</th>
                                  <th scope="col" style="width:20%;">PPTO POA '.$this->gestion.'</th>
                                  <th scope="col" style="width:20%;">PPTO CERT. '.$this->gestion.'</th>
                                </tr>
                              </thead>
                              <tbody>';
                              $total_ppto_gc=0;
                              $total_ppto_gc_cert=0;
                              foreach ($ppto_gcorriente as $row){
                                $programa=$this->model_proyecto->get_programa_padre($row['aper_programa']); /// Get Programa 
                                $get_ppto_partida=$this->model_insumo->get_consolidado_partidas_ppto_x_programas_institucional(4,$row['aper_programa']);
                                $total_ppto_gc=$total_ppto_gc+$row['ppto_poa'];
                                $total_ppto_gc_cert=$total_ppto_gc_cert+$row['ppto_certificado'];
                                $tabla.='
                                <tr>
                                  <td><b>'.$row['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</b></td>
                                  <td bgcolor="#e5f5f1" align=right></td>
                                  <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_poa'], 2, ',', '.').'</td>
                                  <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_certificado'], 2, ',', '.').'</td>
                                </tr>';
                                foreach($get_ppto_partida as $part){
                                  $tabla.='
                                  <tr>
                                    <td></td>
                                    <td>'.$part['par_codigo'].'</td>
                                    <td align=right>'.number_format($part['ppto_poa'], 2, ',', '.').'</td>
                                    <td align=right>'.number_format($part['ppto_certificado'], 2, ',', '.').'</td>
                                  </tr>';
                                }
                              }
                            $tabla.='
                              </tbody>
                              <tr>
                                <td align=right><b>TOTAL (Bs.)</b></td>
                                <td></td>
                                <td align=right><b>'.number_format($total_ppto_gc, 2, ',', '.').'</b></td>
                                <td align=right><b>'.number_format($total_ppto_gc_cert, 2, ',', '.').'</b></td>
                              </tr>
                            </table>
                            </center>
                          </div>

                          <div class="col-sm-6">
                           
                            <form class="form-horizontal">
                              <fieldset>
                                <div class="form-group">
                                  <label class="col-md-4 control-label">SELECCIONE DETALLE PPTO. POA POR REGIONAL</label>
                                  <div class="col-md-6">
                                    <select class="form-control" id="dp_id" name="dp_id" onchange="ver_detalle_ppto_poa(this.value,4)" title="SELECCIONE REGIONAL">
                                      <option value="">Seleccione Regional ...</option>';
                                      foreach($regionales as $row){
                                        if($row['dep_id']!=0){
                                          $tabla.='<option value="'.$row['dep_id'].'" >'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                                        }
                                      }
                                      $tabla.='
                                    </select>
                                  </div>
                                </div>
                              </fieldset>
                            </form>

                            <div id="detalle_ppto4"></div>
                          </div>
                        </div>
                      </div>

                      <div class="tab-pane fade" id="s2">
                        <div class="row">
                          <div class="col-sm-4">
                            <h1><b>INSTITUCIONAL - PROYECTO DE INVERSIÓN '.$this->gestion.' (APERTURAS PROGRAMATICAS)</b></h1>
                            <hr>
                            <center>
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th scope="col" style="width:40%;">PROGRAMA '.$this->gestion.'</th>
                                  <th scope="col" style="width:30%;">PPTO POA '.$this->gestion.'</th>
                                  <th scope="col" style="width:30%;">PPTO CERT. '.$this->gestion.'</th>
                                </tr>
                              </thead>
                              <tbody>';
                              $total_ppto_pi=0;
                              $total_ppto_pi_cert=0;
                              foreach ($ppto_pi as $row){
                                $programa=$this->model_proyecto->get_programa_padre($row['aper_programa']);
                                $total_ppto_pi=$total_ppto_pi+$row['ppto_poa'];
                                $total_ppto_pi_cert=$total_ppto_pi_cert+$row['ppto_certificado'];
                                $tabla.='
                                <tr>
                                  <td>'.$row['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</td>
                                  <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_poa'], 2, ',', '.').'</td>
                                  <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_certificado'], 2, ',', '.').'</td>
                                </tr>';
                              }
                            $tabla.='
                              </tbody>
                              <tr>
                                <td align=right><b>TOTAL (Bs.)</b></td>
                                <td bgcolor="#e5f5f1" align=right><b>'.number_format($total_ppto_pi, 2, ',', '.').'</b></td>
                                <td bgcolor="#e5f5f1" align=right><b>'.number_format($total_ppto_pi_cert, 2, ',', '.').'</b></td>
                              </tr>
                            </table>
                            </center>
                          </div>

                          <div class="col-sm-8">
                           
                            <form class="form-horizontal">
                              <fieldset>
                                <div class="form-group">
                                  <label class="col-md-4 control-label">SELECCIONE DETALLE PPTO. POA POR REGIONAL</label>
                                  <div class="col-md-6">
                                    <select class="form-control" id="dp_id" name="dp_id" onchange="ver_detalle_ppto_poa(this.value,1)" title="SELECCIONE REGIONAL">
                                      <option value="">Seleccione Regional ...</option>';
                                      foreach($regionales as $row){
                                        if($row['dep_id']!=0){
                                          $tabla.='<option value="'.$row['dep_id'].'" >'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                                        }
                                      }
                                      $tabla.='
                                    </select>
                                  </div>
                                </div>
                              </fieldset>
                            </form>

                            <div id="detalle_ppto4"></div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>';
          $salida=$tabla;
        
        $result = array(
          'respuesta' => 'correcto',
          'dep_id' => $dep_id,
          'detalle' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


    /*--- GET DETALLE PPTO X PROGRAMA ---*/
    public function get_ppto_poa_categoria_programatica_regional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $tp_id = $this->security->xss_clean($post['tp_id']); /// tp id
        
        $ppto_prog_regional=$this->model_insumo->consolidado_ppto_x_programas_regional($tp_id,$dep_id);
     
        //$programa=$this->model_proyecto->get_programa_padre($prog);

        $salida='';
        $tabla='';
        $tabla.='<div class="row">
                  <h1><b>REGIONAL - GASTO CORRIENTE '.$this->gestion.' (APERTURAS PROGRAMATICAS)</b></h1>
                  <hr>
                  <div class="col-sm-12">
                    <center>
                    <table class="table table-bordered" style="width:100%;" style="width:80%;">
                      <thead>
                        <tr>
                          <th scope="col" style="width:30%;">PROGRAMA '.$this->gestion.'</th>
                          <th scope="col" style="width:20%;">PARTIDA '.$this->gestion.'</th>
                          <th scope="col" style="width:15%;">PPTO POA '.$this->gestion.'</th>
                          <th scope="col" style="width:15%;">PPTO CERT. '.$this->gestion.'</th>
                        </tr>
                      </thead>
                      <tbody>';
                      $total_ppto_gc=0;
                      $total_ppto_gc_cert=0;
                      foreach ($ppto_prog_regional as $row){
                        $programa=$this->model_proyecto->get_programa_padre($row['aper_programa']); /// Datos del Programa

                        $total_ppto_gc=$total_ppto_gc+$row['ppto_poa'];
                        $total_ppto_gc_cert=$total_ppto_gc_cert+$row['ppto_certificado'];
                        $tabla.='
                        <tr>
                          <td>'.$row['aper_programa'].' '.$programa[0]['aper_proyecto'].' '.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'].'</td>
                          <td bgcolor="#e5f5f1" align=right></td>
                          <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_poa'], 2, ',', '.').'</td>
                          <td bgcolor="#e5f5f1" align=right>'.number_format($row['ppto_certificado'], 2, ',', '.').'</td>
                        </tr>';
                      }
                    $tabla.='
                      </tbody>
                      <tr>
                        <td align=right><b>TOTAL (Bs.)</b></td>
                        <td></td>
                        <td align=right><b>'.number_format($total_ppto_gc, 2, ',', '.').'</b></td>
                        <td align=right><b>'.number_format($total_ppto_gc_cert, 2, ',', '.').'</b></td>
                      </tr>
                    </table>
                    </center>
                  </div>
                </div>';
        
        $result = array(
          'respuesta' => 'correcto',
          'tp_id' => $dep_id,
          'detalle' => $tabla,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }




    /*--- GET LISTA DE UNIDADES, ESTABLECIMIENTOS Y PROYECTOS DE INVERSION (2022)---*/
    public function get_unidades(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $tp_id = $this->security->xss_clean($post['tp_id']); /// Tipo de Gasto
      
        $salida='';
        $unidades=$this->mrep_operaciones->list_poa_gacorriente_pinversion_regional($dep_id,$tp_id);

        if($tp_id==1){
          $salida.= '<option value="">Seleccione Proyectos de Inversión ...</option>
                    <option value=0>CONSOLIDADO REGIONAL</option>';
          foreach ($unidades as $row){
            $salida.= '<option value='.$row['aper_id'].'>'.$row['proy'].'.- '.$row['proyecto'].' - '.$row['aper_id'].'</option>';
          }
        }
        else{
          $salida.= '<option value="">Seleccione Unidad / Establecimiento ...</option>
                    <option value=0>CONSOLIDADO REGIONAL</option>';
          foreach ($unidades as $row){
            $salida.= '<option value='.$row['aper_id'].'>'.$row['prog'].' '.$row['act'].'.- '.$row['tipo'].' '.$row['actividad'].' '.$row['abrev'].'</option>';
          }
        }
        
        //$lista=$this->lista_certificaciones_poa($dist_id,$tp_id);
        $result = array(
          'respuesta' => 'correcto',
          'lista_unidades' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- GET LISTA DE PARTIDAS---*/
    public function get_partidas(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $tp_id = $this->security->xss_clean($post['tp_id']); /// Tipo de Gasto
        $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
      
        $salida='';
        $partidas=$this->model_ptto_sigep->partidas_accion_region($dep_id,$aper_id,$tp_id);

        $salida.= '<option value="">Seleccione Partidas ...</option>
                    <option value=0>TODAS LAS PARTIDAS</option>';
          foreach ($partidas as $row){
            $salida.= '<option value='.$row['par_id'].'>'.$row['codigo'].'.- '.$row['nombre'].'</option>';
          }
        
        $result = array(
          'respuesta' => 'correcto',
          'lista_partidas' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }

    /*--- GET REPORTE PRESUPUESTARIO ---*/
    public function get_reporte_ppto(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
        $tp_id = $this->security->xss_clean($post['tp_id']); /// Tipo de Gasto
        $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
        $par_id = $this->security->xss_clean($post['par_id']); /// par id
      
        $salida='';
        $salida=$this->consolidado_requerimientos_unidad_partida($aper_id,$tp_id,$par_id); /// Lista requerimientos
        
        $result = array(
          'respuesta' => 'correcto',
          'lista_reporte' => $salida,
        );
          
        echo json_encode($result);
      }else{
          show_404();
      }
    }


















 /////========================================== CONSOLIDADO FORMULARIO N5 
    /*-- REPORTE (CONSOLIDADO REQUERIMIENTOS )--*/
    public function consolidado_requerimientos_unidad_partida($aper_id,$tp_id,$par_id){
      $requerimientos=$this->model_insumo->get_lista_requerimientos_unidad_partida($aper_id,$tp_id,$par_id); /// Lista requerimientos

      $tabla='';
      $tabla.='
      <script src = "'.base_url().'mis_js/programacion/programacion/tablas.js"></script>';

      $tabla.='
       <hr>
        <div align=right>
          <a href="'.site_url("").'/exportar_consulta_ppto_poa/'.$aper_id.'/'.$tp_id.'/'.$par_id.'" target=_blank class="btn btn-default" title="EXPORTAR CONSOLIDADO REQUERIMIENTOS"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;EXPORTAR CONSOLIDADO FORMULARION N° 5</a>&nbsp;&nbsp;&nbsp;&nbsp;
        </div>
        <br>
        <table id="dt_basic" class="table table-bordered" style="width:100%;" >
          <thead>
            <tr style="background-color: #66b2e8">
              <th style="width:3%;">COD. DEP.</th>
              <th style="width:3%;">COD. DIST.</th>';
              if($tp_id==1){
                $tabla.='<th style="width:10%;">PROYECTO DE INVERSIÓN</th>';
              }
              else{
                $tabla.='<th style="width:10%;">UNIDAD / ESTABLECIMIENTO</th>';
              }
              $tabla.='
              <th style="width:10%;">UNIDAD RESPONSABLE</th>
              <th style="width:3%;">COD. ACT.</th>
              <th style="width:10%;">DESCRIPCIÓN ACTIVIDAD</th>
              <th style="width:5%;">PARTIDA</th>
              <th style="width:15%;">REQUERIMIENTO</th>
              <th style="width:5%;">UNIDAD DE MEDIDA</th>
              <th style="width:5%;">CANTIDAD</th>
              <th style="width:5%;">PRECIO</th>
              <th style="width:8%;">COSTO TOTAL</th>
              <th style="width:8%;">MONTO CERTIFICADO</th>
              <th style="width:4%;">P. ENE.</th>
              <th style="width:4%;">P. FEB.</th>
              <th style="width:4%;">P. MAR.</th>
              <th style="width:4%;">P. ABR.</th>
              <th style="width:4%;">P. MAY.</th>
              <th style="width:4%;">P. JUN.</th>
              <th style="width:4%;">P. JUL.</th>
              <th style="width:4%;">P. AGOS.</th>
              <th style="width:4%;">P. SEPT.</th>
              <th style="width:4%;">P. OCT.</th>
              <th style="width:4%;">P. NOV.</th>
              <th style="width:4%;">P. DIC.</th>
              <th style="width:8%;">OBSERVACI&Oacute;N</th>
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;
          foreach ($requerimientos as $row){
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                $tabla.='<td>'.$row['dist_cod'].'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.$row['proy_nombre'].'';
                  }
                  else{
                    $tabla.=''.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#e4f3dc"><b>'.$row['prod_cod'].'</b></td>';
                $tabla.='<td bgcolor="#e4f3dc">'.$row['prod_producto'].'</td>';
                $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#f4f5f3"><b>'.$row['par_codigo'].'</b></td>';
                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_detalle']).'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                $tabla.='<td style="font-size: 13px;" align="right" bgcolor="#c1f5ee"><b>'.number_format($row['ins_monto_certificado'], 2, ',', '.').'</b></td>';

                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.number_format($row['mes'.$i], 2, ',', '.').'</td>';
                }

                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_observacion']).'</td>';
            $tabla.='</tr>';
          }
          $tabla.='
          </tbody>
        </table>';


      return $tabla;
    }


  /*---- EXPORTAR DATOS INFORMACION ----*/
  public function exportar_consulta_financiera($aper_id,$tp_id,$par_id){
    $requerimientos=$this->model_insumo->get_lista_requerimientos_unidad_partida($aper_id,$tp_id,$par_id); /// Lista requerimientos
    $tabla='';
    $tabla.='
     <table table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:50%;">
          <thead>
            <tr style="background-color: #66b2e8; height:50px;">
              <th style="width:3%;">COD. DEP.</th>
              <th style="width:3%;">COD. DIST.</th>';
              if($tp_id==1){
                $tabla.='<th style="width:10%;">PROYECTO DE INVERSIÓN</th>';
              }
              else{
                $tabla.='<th style="width:10%;">UNIDAD / ESTABLECIMIENTO</th>';
              }
              $tabla.='
              <th style="width:10%;">UNIDAD RESPONSABLE</th>
              <th style="width:3%;">COD. ACT.</th>
              <th style="width:10%;">DESCRIPCION ACTIVIDAD</th>
              <th style="width:5%;">PARTIDA</th>
              <th style="width:15%;">REQUERIMIENTO</th>
              <th style="width:5%;">UNIDAD DE MEDIDA</th>
              <th style="width:5%;">CANTIDAD</th>
              <th style="width:5%;">PRECIO</th>
              <th style="width:8%;">COSTO TOTAL</th>
              <th style="width:8%;">MONTO CERTIFICADO</th>
              <th style="width:4%;">P. ENE.</th>
              <th style="width:4%;">P. FEB.</th>
              <th style="width:4%;">P. MAR.</th>
              <th style="width:4%;">P. ABR.</th>
              <th style="width:4%;">P. MAY.</th>
              <th style="width:4%;">P. JUN.</th>
              <th style="width:4%;">P. JUL.</th>
              <th style="width:4%;">P. AGOS.</th>
              <th style="width:4%;">P. SEPT.</th>
              <th style="width:4%;">P. OCT.</th>
              <th style="width:4%;">P. NOV.</th>
              <th style="width:4%;">P. DIC.</th>
              <th style="width:8%;">OBSERVACI&Oacute;N</th>
            </tr>
          </thead>
          <tbody id="bdi">';
          $nro=0;
          foreach ($requerimientos as $row){
            $nro++;
            $tabla.='<tr>';
                $tabla.='<td style="height:50px;">'.$row['dep_cod'].'</td>';
                $tabla.='<td>'.$row['dist_cod'].'</td>';
                $tabla.='<td>';
                  if($row['tp_id']==1){
                    $tabla.=''.$row['proy_nombre'].'';
                  }
                  else{
                    $tabla.=''.$row['tipo'].' '.$row['act_descripcion'].' - '.$row['abrev'].'';
                  }
                $tabla.='</td>';
                $tabla.='<td>'.$row['tipo_subactividad'].' '.strtoupper($row['serv_descripcion']).'</td>';
                $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#e4f3dc"><b>'.$row['prod_cod'].'</b></td>';
                $tabla.='<td bgcolor="#e4f3dc">'.mb_convert_encoding(strtoupper($row['prod_producto']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td style="font-size: 15px;" align="center" bgcolor="#f4f5f3"><b>'.$row['par_codigo'].'</b></td>';
                $tabla.='<td bgcolor="#f4f5f3">'.mb_convert_encoding(strtoupper($row['ins_detalle']), 'cp1252', 'UTF-8').'</td>';
                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_unidad_medida']).'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.round($row['ins_cant_requerida'],2).'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>';
                $tabla.='<td bgcolor="#f4f5f3" align="right">'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>';
                $tabla.='<td align="right" bgcolor="#c1f5ee"><b>'.round($row['ins_monto_certificado'],2).'</b></td>';

                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='<td style="width:3%;" bgcolor="#f4f5f3">'.round($row['mes'.$i],2).'</td>';
                }

                $tabla.='<td bgcolor="#f4f5f3">'.strtoupper($row['ins_observacion']).'</td>';
            $tabla.='</tr>';
          }
          $tabla.='
          </tbody>
        </table>';

      date_default_timezone_set('America/Lima');
      header('Content-type: application/vnd.ms-excel');
      header("Content-Disposition: attachment; filename=Datos_requerimientos.xls"); //Indica el nombre del archivo resultante
      header("Pragma: no-cache");
      header("Expires: 0");
      echo "";
      echo $tabla;
  }




    /*--------------------- MENU --------------------*/
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

  /*=========================================================================================================================*/
    public function get_mes($mes_id){
      $mes[1]='ENERO';
      $mes[2]='FEBRERO';
      $mes[3]='MARZO';
      $mes[4]='ABRIL';
      $mes[5]='MAYO';
      $mes[6]='JUNIO';
      $mes[7]='JULIO';
      $mes[8]='AGOSTO';
      $mes[9]='SEPTIEMBRE';
      $mes[10]='OCTUBRE';
      $mes[11]='NOVIEMBRE';
      $mes[12]='DICIEMBRE';

      $dias[1]='31';
      $dias[2]='28';
      $dias[3]='31';
      $dias[4]='30';
      $dias[5]='31';
      $dias[6]='30';
      $dias[7]='31';
      $dias[8]='31';
      $dias[9]='30';
      $dias[10]='31';
      $dias[11]='30';
      $dias[12]='31';

      $valor[1]=$mes[$mes_id];
      $valor[2]=$dias[$mes_id];

      return $valor;
    }
}