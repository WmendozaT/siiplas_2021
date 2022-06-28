<?php
class Creportejecucion_pi extends CI_Controller {  
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
          $this->mes = $this->mes_nombre();
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


/*------- menu Proyectos de Inversion -------*/
public function menu_pi(){
  $data['menu']=$this->menu(7);
  $data['list']=$this->menu_nacional();

  $tabla='
    <input name="base" type="hidden" value="'.base_url().'">
    <input name="mes" type="hidden" value="'.$this->verif_mes[1].'">
    <input name="descripcion_mes" type="hidden" value="'.$this->verif_mes[2].'">
    <input name="gestion" type="hidden" value="'.$this->gestion.'">
    <div id="update_eval">
      <div class="jumbotron">
        <h1>Seguimiento a Proyectos de Inversión '.$this->gestion.'</h1>
        <p>
          Muestra el avance de ejecución Presupuestaria de Proyectos de Inversion al mes de <b>'.$this->verif_mes[2].' / '.$this->gestion.'</b>, a nivel Nacional, Regional y Distrital.
        </p>
      </div>
    </div>';
   
    $data['titulo_modulo']=$tabla;

    $this->load->view('admin/reportes_cns/repejecucion_pi/menu_pi', $data); 
}

  //// MENU UNIDADES ORGANIZACIONAL 2022
  public function menu_nacional(){
  $tabla='';
  $regionales=$this->model_proyecto->list_departamentos();
    $tabla.='
    <article class="col-sm-12">
      <div class="well">
        <form class="smart-form">
            <header><b>SEGUIMIENTO A PROYECTOS DE INVERSIÓN '.$this->gestion.'</b></header>
            <fieldset>          
              <div class="row">
                <section class="col col-3">
                  <label class="label">DIRECCIÓN ADMINISTRATIVA</label>
                  <select class="form-control" id="dep_id" name="dep_id" title="SELECCIONE REGIONAL">
                  <option value="">SELECCIONE REGIONAL</option>
                  <option value="0">0.- INSTITUCIONAL C.N.S.</option>';
                  foreach($regionales as $row){
                    if($row['dep_id']!=0){
                      $tabla.='<option value="'.$row['dep_id'].'">'.$row['dep_id'].'.- '.strtoupper($row['dep_departamento']).'</option>';
                    }
                  }
                  $tabla.='
                  </select>
                </section>
              </div>
            </fieldset>
        </form>
        </div>
      </article>';
  return $tabla;
}


/*--- GET DETALLE DE EJECUCION PRESUPUESTARIA DE PROYECTOS DE INVERSION REGIONAL, INSTITUCIONAL---*/
public function get_detalle_ejecucion_ppto_pi_regional_institucional(){
  if($this->input->is_ajax_request() && $this->input->post()){
    $post = $this->input->post();
    $dep_id = $this->security->xss_clean($post['dep_id']);
    $regional=$this->model_proyecto->get_departamento($dep_id);

    if($dep_id==0){
      $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('CONSOLIDADO INSTITUCIONAL');

      ///// s1
      $nro_reg=count($this->model_ptto_sigep->list_regionales());
      $matriz_reg=$this->matriz_detalle_proyectos_clasificado_regional();
      $tabla_detalle_proy_impresion=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,1); /// Tabla Impresion Grafico 1
      $tabla_detalle_ppto_impresion=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,2); /// Tabla Impresion Grafico 2

       //// s2
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_institucional());
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_institucional(); /// Matriz consolidado de partidas Nacional
      $consolidado=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,0); /// Tabla Clasificacion de partidas asignados por regional
      $tabla_consolidado_grafico=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico
      $grafico_consolidado_partidas='<div id="graf_partida"><div id="container" style="width: 1000px; height: 680px; margin: 0 auto"></div></div>';

      //// s3
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_institucional(); /// ejecutado mensual
      $vector_meses_acumulado=$this->ejecucion_finpi->vector_consolidado_ppto_acumulado_mensual_institucional(); /// ejecutado mensual Acumulado
      $tabla1=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,0);
      $grafico_mes='<div id="graf_ppto_mensual"><div id="ejec_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';
      $grafico_mes_acumulado='<div id="graf_ppto_mensual_acumulado"><div id="ejec_acumulado_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';

      $tabla='
      <h2>Ejecucion Presupuestaria Institucional al mes de '.$this->mes[2].' / '.$this->gestion.'</h2>
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
            <h2>Ejecucion Presupuestaria</h2>
            <div id="cabecera" style="display: none">'.$cabecera_grafico.'</div>
        </header>
        <div>

            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body">
                <p>
                <div style="font-size: 25px;font-family: Arial¨;"><b></b></div>
                </p>
                <hr class="simple">
                <ul id="myTab1" class="nav nav-tabs bordered">
                  <li class="active">
                      <a href="#s1" data-toggle="tab"> Detalle Proyectos</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> Consolidado por Partidas</a>
                  </li>
                  <li>
                      <a href="#s3" data-toggle="tab"> Consolidado por Meses</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                  <div class="tab-pane fade in active" id="s1">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                        <div id="graf_detalle_nro_proyectos">
                          <div id="detalle_proyectos1" style="width: 630px; height: 630px; margin: 2 auto"></div>
                        </div>
                      </div>
                      <div id="tabla_impresion_detalle1" style="display: none">
                        '.$tabla_detalle_proy_impresion.'
                      </div>
                      <div align="right">
                        <button  onClick="imprimir_distribucion_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                      <hr>
                    </article>
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                        <div id="graf_detalle_nro_ppto">
                          <div id="detalle_proyectos2" style="width: 630px; height: 630px; margin: 2 auto"></div>
                        </div>
                      </div>
                      <div id="tabla_impresion_detalle2" style="display: none">
                        '.$tabla_detalle_ppto_impresion.'
                      </div>
                      <div align="right">
                        <button  onClick="imprimir_distribucion_ppto()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                      <hr>
                    </article>
                    <hr>
                    <div class="row">
                      <div class="table-responsive" align=center>
                      '.$this->tabla_detalle_proyectos_clasificado_institucional($matriz_reg,$nro_reg).'
                      </div>
                    </div>
                  
                  </div>
                  
                  <div class="tab-pane fade" id="s2">
                    <div class="row">
                      <article class="col-sm-12">
                        '.$grafico_consolidado_partidas.'
                          <div align="right">
                            <button  onClick="imprimir_partida()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </div>
                        <hr>
                        '.$consolidado.'
                        <div id="tabla_impresion_partida" style="display: none">
                          '.$tabla_consolidado_grafico.'
                        </div>
                      </article>

                    </div>
                  </div>

                  <div class="tab-pane fade" id="s3">
                    <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes.'
                      </div>
                    </article>
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes_acumulado.'
                      </div>
                    </article>
                    <div align="right">
                      <button  onClick="imprimir_ejecucion_mensual()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                     <hr>
                     '.$tabla1.'
                      <div id="tabla_impresion_ejecucion_mensual" style="display: none">
                        '.$tabla1.'
                      </div>
                    </div>
                  </div>

                </div>
            </div>
          </div>
      </div>';


      $result = array(
        'respuesta' => 'correcto',
        'nro_reg'=>$nro_reg,
        'matriz_reg'=>$matriz_reg,

        'nro_part'=>$nro,
        'matriz_part'=>$matriz_partidas,

        'vector_meses'=>$vector_meses,
        'vector_meses_acumulado'=>$vector_meses_acumulado,

        'lista_reporte' => $tabla,

      );

    }
    else{
      $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('REGIONAL '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion);
      /// s1
      $lista_detalle=$this->ejecucion_finpi->detalle_avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero

      //// s2
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id));
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_regional($dep_id); /// Matriz consolidado de partidas
      $consolidado=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,0); /// Tabla Clasificacion de partidas asignados por regional
      $tabla_consolidado_grafico=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico
      $grafico_consolidado_partidas='<div id="graf_partida"><div id="container" style="width: 900px; height: 660px; margin: 0 auto"></div></div>';


      //// s3
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_regional($dep_id); /// ejecutado mensual
      $vector_meses_acumulado=$this->ejecucion_finpi->vector_consolidado_ppto_acumulado_mensual_regional($dep_id); /// ejecutado mensual Acumulado
      $tabla1=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,$dep_id);
      $grafico_mes='<div id="graf_ppto_mensual"><div id="ejec_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';
      $grafico_mes_acumulado='<div id="graf_ppto_mensual_acumulado"><div id="ejec_acumulado_mensual" style="width: 680px; height: 420px; margin: 2 auto"></div></div>';

      $tabla='
      <h2>Ejecucion Presupuestaria - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'</h2>
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
            <h2>Ejecucion Presupuestaria</h2>
            <div id="cabecera" style="display: none">'.$cabecera_grafico.'</div>
        </header>
        <div>
            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body">
                <p>
                <div style="font-size: 25px;font-family: Arial¨;"><b></b></div>
                </p>
                <hr class="simple">
                <ul id="myTab1" class="nav nav-tabs bordered">
                  <li class="active">
                      <a href="#s1" data-toggle="tab"> Detalle Proyectos</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> Consolidado por Partidas</a>
                  </li>
                  <li>
                      <a href="#s3" data-toggle="tab"> Consolidado por Meses</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                  <div class="tab-pane fade in active" id="s1">
                      <div class="row">
                        <div class="table-responsive" align=center>
                          <table style="width:100%;" border=0>
                            <tr>
                              <td style="width:100%;" align=right>
                                <a href="javascript:abreVentana(\''.site_url("").'/reporte_detalle_ppto_pi/'.$dep_id.'/3\');" title="GENERAR REPORTE" class="btn btn-default">
                                  <img src="'.base_url().'assets/ifinal/requerimiento.png" WIDTH="23" HEIGHT="24"/>&nbsp;GENERAR REPORTE (PDF)
                                </a>
                                <a href="'.site_url("").'/xls_rep_ejec_fin_pi/'.$dep_id.'/3" target=black title="EXPORTAR DETALLE" class="btn btn-default">
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
                      <article class="col-sm-12">
                        '.$grafico_consolidado_partidas.'
                          <div align="right">
                            <button  onClick="imprimir_partida()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </div>
                        <hr>
                        '.$consolidado.'
                        <div id="tabla_impresion_partida" style="display: none">
                          '.$tabla_consolidado_grafico.'
                        </div>
                      </article>

                    </div>
                  </div>

                  <div class="tab-pane fade" id="s3">
                    <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes.'
                      </div>
                    </article>
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                      '.$grafico_mes_acumulado.'
                      </div>
                    </article>
                    <div align="right">
                      <button  onClick="imprimir_ejecucion_mensual()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                     <hr>
                     '.$tabla1.'
                      <div id="tabla_impresion_ejecucion_mensual" style="display: none">
                        '.$tabla1.'
                      </div>
                    </div>
                  </div>

                </div>
            </div>
          </div>
      </div>';


      $result = array(
        'respuesta' => 'correcto',
        'nro'=>$nro,
        'matriz'=>$matriz_partidas,
        'vector_meses'=>$vector_meses,
        'vector_meses_acumulado'=>$vector_meses_acumulado,
        'lista_reporte' => $tabla,
      );
    }

    echo json_encode($result);
  }else{
      show_404();
  }
}




 /*--- GET MATRIZ DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL ---*/
public function matriz_detalle_proyectos_clasificado_regional(){
  $regionales=$this->model_ptto_sigep->list_regionales();
  $nro_proyectos_nacional=count($this->model_proyecto->list_proy_inversion()); /// Nro de proyectos aprobados NACIONAL
  $ppto_asignado_proyectos_nacional=$this->model_ptto_sigep->get_ppto_asignado_proyectos_inversion_aprobados(); /// Ppto Asignado de proyectos aprobados NACIONAL
  
  //// Armando Matriz Vacio
  for ($i=0; $i <=10 ; $i++) { 
    for ($j=0; $j <count($this->model_ptto_sigep->list_regionales()) ; $j++) { 
      $matriz[$i][$j]=0;
    }
  }
  //// -------------------


  $nro=0;
  foreach($regionales as $row){
    $nro_proy=0;
    if(count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']))!=0){
      $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($row['dep_id']));
    }

    $modificacion_partida=$this->ejecucion_finpi->detalle_modificacion_ppto_x_regional($row['dep_id']); //// Modificacion de partidas

    $ejecucion=$this->model_ptto_sigep->get_ppto_ejecutado_regional($row['dep_id']); //// ejecucion de Presupuesto
    $ejec_ppto=0;
    if(count($ejecucion)!=0){
      $ejec_ppto=$ejecucion[0]['ejecutado_total'];
    }

    $avance_financiero=0; /// Avance Financiero
    if($modificacion_partida[3]!=0){
      $avance_financiero=round((($ejec_ppto/$modificacion_partida[3])*100),2);
    }
    
    $porcentaje_distribucion_proyectos=0; //// numero de proyectos
    if($nro_proyectos_nacional!=0){
      $porcentaje_distribucion_proyectos=round(($nro_proy/$nro_proyectos_nacional)*100,2);
    }


    $porcentaje_distribucion_ppto=0; /// porcentaje de ppto asignado por regional
    if(count($ppto_asignado_proyectos_nacional)!=0){
      $porcentaje_distribucion_ppto=round(($modificacion_partida[3]/$ppto_asignado_proyectos_nacional[0]['ppto_asignado_gestion'])*100,2);
    }

    $matriz[$nro][0]=$row['dep_id']; //// dep id
    $matriz[$nro][1]=strtoupper($row['dep_departamento']); /// regional
    $matriz[$nro][2]=$row['dep_sigla']; /// regional
    $matriz[$nro][3]=$nro_proy; /// nro de proyectos aprobados
    $matriz[$nro][4]=$porcentaje_distribucion_proyectos; /// porcentaje de distribucion de proyectos con respecto al total
    $matriz[$nro][5]=$modificacion_partida[1]; /// ppto inicial
    $matriz[$nro][6]=$modificacion_partida[2]; /// ppto modificado
    $matriz[$nro][7]=$modificacion_partida[3]; /// ppto vigente
    $matriz[$nro][8]=$ejec_ppto; /// ppto ejecutado
    $matriz[$nro][9]=$avance_financiero; /// avance financiero
    $matriz[$nro][10]=$porcentaje_distribucion_ppto; /// porcentaje de distribucion del presupuesto con respecto al total

    $nro++;
  }

  return $matriz;
}


/*--- GET TABLA DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL VISTA---*/
public function tabla_detalle_proyectos_clasificado_institucional($matriz,$nro){
  $tabla='';
  $tabla.='
      <style>
        table{font-size: 10px;
        width: 100%;
        max-width:1550px;;
        overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          text-align: center;
          font-size: 10px;
        }
      </style>
      <table class="table table-bordered" style="width:100%;">
        <thead>
          <tr>
            <th style="width:10%;">DEPARTAMENTO</th>
            <th style="width:7%;">N° DE PROYECTOS</th>
            <th style="width:7%;">PORCENTAJE DISTRIBUCIÓN</th>
            <th style="width:10%;">PPTO. INICIAL '.$this->gestion.'</th>
            <th style="width:10%;">PPTO. MODIFICADO '.$this->gestion.'</th>
            <th style="width:10%;">PPTO. VIGENTE '.$this->gestion.'</th>
            <th style="width:10%;">PPTO. EJECUTADO</th>
            <th style="width:5%;">AVANCE FINANCIERO</th>
            <th style="width:5%;">PORCENTAJE DISTRIBUCIÓN PPTO.</th>
          </tr>
        </thead>
        <tbody>';
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td>'.$matriz[$i][1].'</td>
                <td align=right>'.$matriz[$i][3].'</td>
                <td align=right>'.$matriz[$i][4].' %</td>
                <td align=right>Bs. '.number_format($matriz[$i][5], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][6], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                <td align=right>'.$matriz[$i][9].' %</td>
                <td align=right>'.$matriz[$i][10].' %</td>
              </tr>';    
          }
          $tabla.='
        </tbody>
      </table>';

  return $tabla;
}


  /*--- GET TABLA DATOS CONSOLIDADOS DE PROYETCOS DE INVERSION INSCRITOS CALSIFICADOS POR REGIONAL IMPRESION ---*/
  public function tabla_detalle_institucional_impresion($matriz,$nro,$tipo){
    /// tipo : 1 Distribucion nro de proyectos
    /// tipo : 2 Distribucion presupuesto

    $tabla='';
    if($tipo==1){
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:40%;">DEPARTAMENTO</th>
            <th style="width:10%;">N° DE PROYECTOS</th>
            <th style="width:10%;">PORCENTAJE DISTRIBUCIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $nro_proy=0;
        $disribucion=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:40%;">'.$matriz[$i][1].'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][3].'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][4].' %</td>
              </tr>';
              $nro_proy=$nro_proy+$matriz[$i][3];
              $disribucion=$disribucion+$matriz[$i][4];
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td align=right>'.$nro_proy.'</td>
            <td align=right>'.$disribucion.' %</td>
          </tr>
      </table>
      </center>';
    }
    else{
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:40%;">DEPARTAMENTO</th>
            <th style="width:10%;">PPTO. ASIGNADO</th>
            <th style="width:10%;">PORCENTAJE DISTRIBUCIÓN</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_total=0;
        $disribucion=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:40%;">'.$matriz[$i][1].'</td>
                <td style="width:10%;" align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td style="width:10%;" align=right>'.$matriz[$i][10].' %</td>
              </tr>';
              $ppto_total=$ppto_total+$matriz[$i][7];
              $disribucion=$disribucion+$matriz[$i][10];
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td align=right>'.$ppto_total.'</td>
            <td align=right>'.$disribucion.' %</td>
          </tr>
      </table>
      </center>';
    }

    return $tabla;
  } 
























  /*========= GENERAR MENU ==========*/
  function menu($mod){
    $enlaces=$this->menu_modelo->get_Modulos($mod);
    for($i=0;$i<count($enlaces);$i++) {
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
  /*-----------------------------------*/

  /*-----------------*/
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

}