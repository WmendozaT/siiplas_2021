<?php
class Cejecucion_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null){
        $this->load->model('Users_model','',true);
        if($this->rolfun($this->rol)){ 
          $this->load->library('pdf2');
          $this->load->model('menu_modelo');
          $this->load->model('consultas/model_consultas');
          $this->load->model('programacion/model_proyecto');
          $this->load->model('programacion/model_faseetapa');
          $this->load->model('programacion/model_actividad');
          $this->load->model('programacion/model_producto');
          $this->load->model('programacion/model_componente');
          $this->load->model('mantenimiento/model_ptto_sigep');
          $this->pcion = $this->session->userData('pcion');
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->dep_id = $this->session->userData('dep_id');
          $this->tmes = $this->session->userData('trimestre');
          $this->fun_id = $this->session->userData('fun_id');
          $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
          $this->ppto= $this->session->userData('verif_ppto'); 
          $this->verif_mes=$this->session->userData('mes_actual');
          $this->tp_adm = $this->session->userData('tp_adm');
          $this->load->library('ejecucion_finpi');
        }else{
            redirect('admin/dashboard');
        }
    }
    else{
        redirect('/','refresh');
    }
  }


  /*------- formulario ejecucion financiera -------*/
  public function formulario_ejecucion_ppto(){
    $data['menu']=$this->ejecucion_finpi->menu_pi();
    $data['style']=$this->ejecucion_finpi->style();
    $data['formulario']=$this->ejecucion_finpi->formulario();

    $this->load->view('admin/ejecucion_pi/form_ejec_fin_pi', $data);

  }



  /*---- VERIFICA MONTO A EJECUTAR POR PARTIDA ----*/
  public function verif_valor_ejecutado_x_partida(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
      $aper_id = $this->security->xss_clean($post['aper_id']); /// aper id
      $ejec= $this->security->xss_clean($post['ejec']);/// valor a actualizar
      $mes_id= $this->security->xss_clean($post['mes_id']);/// mes id
      
      /// Datos - Programado y Ejecutado por partidas
      $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep

      //// --- suma total del monto ejecutado antes del mes vigente
      $monto_total_ejec_partida=$this->get_monto_ejec_partida_hasta_mes_anterior($sp_id,$mes_id);
      //// ----------------------------------------------------------

      if(($ejec+$monto_total_ejec_partida)<=$get_partida_sigep[0]['importe']){
        $result = array(
          'respuesta' => 'correcto',
          'ejecucion_total_partida'=>round(($ejec+$monto_total_ejec_partida),2),
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


  /*---- MONTO TOTAL EJECUTADO AL MES ANTERIOR POR PARTIDA----*/
  public function get_monto_ejec_partida_hasta_mes_anterior($sp_id,$mes_id){
    $suma_monto_ejecutado=0;
    for ($i=1; $i <$mes_id ; $i++) { 
      $ejec_mes=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$i);
      $monto_mes=0;
      if(count($ejec_mes)!=0){
        $monto_mes=$ejec_mes[0]['ppto_ejec'];
      }

      $suma_monto_ejecutado=$suma_monto_ejecutado+$monto_mes;
    }

    return $suma_monto_ejecutado;
  }



  /*---- VALIDA ADD MOD EJECUCION PRESUPUESTARIA ----*/
  public function guardar_ppto_ejecutado(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $sp_id = $this->security->xss_clean($post['sp_id']);
      $ejec = $this->security->xss_clean($post['ejec']);
      $obs = $this->security->xss_clean($post['obs']);
      $aper_id = $this->security->xss_clean($post['aper_id']);
      $mes_id=$this->verif_mes[1];
      
      $ppto_ejec_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$mes_id); ///

      /// ----- Eliminando Registro de ejecucion --------
        $this->db->where('sp_id', $sp_id);
        $this->db->where('m_id', $this->verif_mes[1]);
        $this->db->delete('ejecucion_financiera_sigep');
      /// -----------------------------------
        if($ejec!=0){
          /// ----- Registro de Ejecucion --------
          $data_to_store = array(
            'sp_id' => $sp_id, /// Id sigep partida
            'm_id' => $this->verif_mes[1], /// Mes 
            'ppto_ejec' => $ejec, /// Valor ejecutado
            'fun_id' => $this->fun_id, /// fun id
          );
          $this->db->insert('ejecucion_financiera_sigep', $data_to_store);
          /// -----------------------------------
        }
      
        /// ----- Registro de Ejecucion --------
        $data_to_store = array(
          'sp_id' => $sp_id, /// Id sigep partida
          'observacion' => strtoupper($obs), /// Observacion
          'm_id' => $this->verif_mes[1], /// Mes 
          'fun_id' => $this->fun_id, /// fun id
        );
        $this->db->insert('obs_ejecucion_financiera_sigep', $data_to_store);
        /// -----------------------------------

        $ppto_ejec_mensual=$this->model_ptto_sigep->get_monto_ejecutado_ppto_sigep($sp_id,$mes_id); /// Registro
        $obs_ejec_mensual=$this->model_ptto_sigep->get_obs_ejecucion_financiera_sigep($sp_id,$mes_id); /// Observacion

        $monto_ejec=0;
        $observacion_registrado='';

        if(count($ppto_ejec_mensual)!=0){
          $monto_ejec=$ppto_ejec_mensual[0]['ppto_ejec'];
        }

        if(count($obs_ejec_mensual)!=0){
          $observacion_registrado=$obs_ejec_mensual[0]['observacion'];
        }


        //// --- suma total del monto ejecutado antes del mes vigente
        $monto_total_ejec_partida=$this->get_monto_ejec_partida_hasta_mes_anterior($sp_id,$mes_id);
        //// ----------------------------------------------------------

        /// --- monto ejecutado del proyecto mensual
        $monto_ejec_mensual=$this->model_ptto_sigep->suma_monto_ejecutado_mes_ppto_sigep($aper_id,$mes_id);
        $ppto_ejec_mensual=0;

        if(count($monto_ejec_mensual)!=0){
          $ppto_ejec_mensual=$monto_ejec_mensual[0]['ejecutado_mes'];
        }


        /// --- monto total ejecutado del proyecto
        $monto_ejec_total=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep($aper_id);
        $ppto_ejec_total=0;

        if(count($monto_ejec_total)!=0){
          $ppto_ejec_total=$monto_ejec_total[0]['ejecutado_total'];
        }


        /// Porcentaje de Avance por partidas
        $get_partida_sigep=$this->model_ptto_sigep->get_sp_id($sp_id); /// Get partida sigep
        $porcentaje_avance_fin=0;
        if(count($get_partida_sigep)!=0){
          $porcentaje_avance_fin=round(((($monto_total_ejec_partida+$monto_ejec)/$get_partida_sigep[0]['importe'])*100),2);
        }


      $result = array(
        'respuesta' => 'correcto',
        'ppto_ejec_mes'=>round($ppto_ejec_mensual,2), /// monto ejecutado en el mes por proyecto de inversion
        'ppto_ejec_total_pi'=>round($ppto_ejec_total,2), /// monto ejecutado total por proyecto de inversion
        'ppto_total_ejec_partida'=>round(($monto_total_ejec_partida+$monto_ejec),2), /// ejecucion mes
        'porcentaje_ejec_partida'=>$porcentaje_avance_fin, /// Avance Financiero ppto partida
        'ppto_mes'=>round($monto_ejec,2), /// ejecucion mes
        'obs_mes'=>strtoupper($observacion_registrado), /// observacion mes
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


  /*----  GET DETALLE EJECUCION PRESUPUESTARIA POR PARTIDA----*/
  public function get_detalle_ejecucion_partida(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $sp_id = $this->security->xss_clean($post['sp_id']); /// partida id
      $temporalidad_ejec=$this->model_ptto_sigep->get_temporalidad_ejec_ppto_partida($sp_id);

      $tabla='';
      $tabla.='<div class="table-responsive" align=center>
                <table class="table table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th style="width:7%;">ENE.</th>
                    <th style="width:7%;">FEB.</th>
                    <th style="width:7%;">MAR.</th>
                    <th style="width:7%;">ABR.</th>
                    <th style="width:7%;">MAY.</th>
                    <th style="width:7%;">JUN.</th>
                    <th style="width:7%;">JUL.</th>
                    <th style="width:7%;">AGO.</th>
                    <th style="width:7%;">SEPT.</th>
                    <th style="width:7%;">OCT.</th>
                    <th style="width:7%;">NOV.</th>
                    <th style="width:7%;">DIC.</th>
                    <th style="width:10%;">TOTAL</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>';
                for ($i=1; $i <=12 ; $i++) { 
                  $tabla.='
                  <td align=right><b>Bs. '.number_format($temporalidad_ejec[0]['m'.$i], 2, ',', '.').'</b></td>';
                }

      $tabla.=' <td align=right style="color:blue;"><b>Bs. '.number_format($temporalidad_ejec[0]['ejecutado_total'], 2, ',', '.').'</b></td>
                </tr>
                </tbody>
                </table>
              </div>';

      $result = array(
        'respuesta' => 'correcto',
        'tabla'=>$tabla,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*---- VALIDA UPDATE DATOS PROYECTOS DE INVERSION ----*/
  //data: "proy_id="+proy_id+"&estado="+estado+"&fis="+avance_fisico
  public function guardar_datos_proyecto(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $proy_id = $this->security->xss_clean($post['proy_id']);
      $estado = $this->security->xss_clean($post['estado']);
      $avance_fisico = $this->security->xss_clean($post['fis']);
      $mes_id=$this->verif_mes[1];
      

      $update_proyect = array(
        'avance_fisico' => $avance_fisico,
        'proy_estado' => $estado
      );
      $this->db->where('proy_id', $proy_id);
      $this->db->update('_proyectos', $update_proyect);


      $proyecto=$this->model_proyecto->get_id_proyecto($proy_id);

      $result = array(
        'respuesta' => 'correcto',
        'proyecto'=>$proyecto,
       // 'obs_mes'=>strtoupper($observacion_registrado),
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


///// =============== REPORTES
/// Menu Reportes 
public function menu_rep_ejecucion_ppto(){
  $data['menu']=$this->ejecucion_finpi->menu_pi();
  $data['opciones']=$this->ejecucion_finpi->listado_opciones_reportes($this->dep_id);
  $regional=$this->model_proyecto->get_departamento($this->dep_id);
  $tabla='';
  $tabla.='<div class="well">
            <div class="jumbotron">
              <h1>Regional '.strtoupper($regional[0]['dep_departamento']).' - '.$this->verif_mes[2].' / '.$this->gestion.'</h1>
                <p>
                  Reporte consolidado de ejecución Presupuestaria de Proyectos de Inversion, gestión '.$this->gestion.' a nivel Regional.
                </p>
            </div>
          </div>';

  $data['titulo_modulo']=$tabla;
  //echo $this->ejecucion_finpi->reporte_consolidado_partidas($this->dep_id);
  $this->load->view('admin/ejecucion_pi/rep_menu', $data);
}


/*----  GET TIPO DE REPORTE ----*/
public function get_tp_reporte(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $rep_id = $this->security->xss_clean($post['rep_id']); /// tipo de reporte
    $dep_id = $this->security->xss_clean($post['dep_id']); /// regional
    $tabla='';

    $regional=$this->model_proyecto->get_departamento($dep_id);

    if($rep_id==1){
      $titulo='MIS PROYECTOS DE INVERSIÓN - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->proyectos_inversion($dep_id,1); /// vista Lista de Proyectos
      $consolidado='trabajando ...';
    }
    elseif ($rep_id==2) {
      $titulo='EJECUCIÓN FÍSICA Y FINANCIERA - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->avance_fisico_financiero_pi($dep_id,1); /// vista Ejecucion Fisico y Financiero
      $consolidado='trabajando ...';
    }
    elseif ($rep_id==3) {
      $titulo='DETALLE EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'';
      $lista_detalle=$this->ejecucion_finpi->detalle_avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero
      $consolidado=$this->ejecucion_finpi->reporte_consolidado_partidas($dep_id); /// Clasificacion de partidas asignados por regional
    }


     $tabla='
      <input name="base" type="hidden" value="'.base_url().'">
       <div class="row">
          <article class="col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                    <h2>Ejecucion Presupuestaria</h2>
                </header>
                <div>
                    <div class="jarviswidget-editbox">
                    </div>
                    <div class="widget-body">
                        <p>
                        <div style="font-size: 25px;font-family: Arial¨;"><b>'.$titulo.'</b></div>
                        </p>
                        <hr class="simple">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                          <li class="active">
                              <a href="#s1" data-toggle="tab"> Detalle Ejecución</a>
                          </li>
                          <li>
                              <a href="#s2" data-toggle="tab"> Detalle consolidado</a>
                          </li>
                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                          <div class="tab-pane fade in active" id="s1">
                              <div class="row">
                                <div class="table-responsive" align=center>
                                  <table style="width:90%;">
                                    <tr>
                                      <td align=right>
                                        <a href="'.site_url("").'/xls_rep_ejec_fin_pi/'.$dep_id.'/'.$rep_id.'" target=black title="EXPORTAR DETALLE" class="btn btn-default">
                                          <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;EXPORTAR DETALLE (EXCEL)
                                        </a>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td><hr></td>
                                    </tr>
                                    <tr>
                                      <td>
                                        <form class="smart-form" method="post">
                                          <section class="col col-3">
                                            <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="Buscador...."/>
                                          </section>
                                        </form>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
                                '.$lista_detalle.'
                              </div>
                          </div>
                          
                          <div class="tab-pane fade" id="s2">
                            <div class="row">
                            '.$consolidado.'
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>
              </div>
          </article>
        </div>';

    $result = array(
      'respuesta' => 'correcto',
      'tabla'=>$tabla,
    );

    echo json_encode($result);
  }else{
      show_404();
  }
}


  /*--- REPORTE FICHA TECNICA PROY INVERSION ---*/
  public function ficha_tecnica_pi($proy_id){
    $regional=$this->model_proyecto->get_departamento($this->dep_id);
    $data['titulo_pie_rep']='Ficha_Tecnica_PI'.strtoupper($regional[0]['dep_departamento']).' '.$this->gestion;
    $data['cabecera']=$this->ejecucion_finpi->cabecera_ficha_tecnica();
    $data['pie']=$this->ejecucion_finpi->pie_ficha_tecnica();
    $data['datos_proyecto']=$this->ejecucion_finpi->datos_proyecto_inversion($proy_id);

    $this->load->view('admin/ejecucion_pi/reporte_ficha_tecnica_pi', $data);
  }


  /*---- EXPORTAR A EXCEL REPORTE SEGUN EL TIPO ----*/
  public function exportar_ejecucion_pi($dep_id,$tip){
    date_default_timezone_set('America/Lima');
    $fecha = date("d-m-Y H:i:s");
    $regional=$this->model_proyecto->get_departamento($dep_id);
    $tabla='';

    if($tip==1){
      $tabla=$this->ejecucion_finpi->reporte1_excel($dep_id);
    }
    elseif ($tip==2) {
      $tabla=$this->ejecucion_finpi->reporte2_excel($dep_id);
    }
    else{
      $tabla=$this->ejecucion_finpi->reporte3_excel($dep_id);
    }

    header('Content-type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=Detalle_ejec_pi_".strtoupper($regional[0]['dep_departamento'])."/".$this->gestion."_$fecha.xls"); //Indica el nombre del archivo resultante
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "";
    ini_set('max_execution_time', 0); 
    ini_set('memory_limit','3072M');
    echo $tabla;
  }


    /*----------------------------------------*/
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

}