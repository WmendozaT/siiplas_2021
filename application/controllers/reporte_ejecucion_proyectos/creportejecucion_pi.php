<?php
class Creportejecucion_pi extends CI_Controller {  
  public $rol = array('1' => '1','2' => '11'); 
  public function __construct (){
    parent::__construct();
    if($this->session->userdata('fun_id')!=null && ($this->session->userdata('fun_id')!=415 || $this->session->userdata('fun_id')!=1076 || $this->session->userdata('fun_id')!=1302)){
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
          $this->load->model('programacion/insumos/model_insumo');
          $this->pcion = $this->session->userData('pcion');
          $this->gestion = $this->session->userData('gestion');
          $this->adm = $this->session->userData('adm');
          $this->rol = $this->session->userData('rol_id');
          $this->dist = $this->session->userData('dist');
          $this->dist_tp = $this->session->userData('dist_tp');
          $this->dep_id = $this->session->userData('dep_id');
          $this->tmes = $this->session->userData('trimestre');
          //$this->mes = $this->mes_nombre();
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
    $dep_id=1;
    //echo $this->tmes;
    $techo_ini_reg=$this->model_insumo->techo_ppto_inicial_inversion_regional($dep_id);
    echo $techo_ini_reg[0]['techo_ppto_inicial'].'<br>';
    for ($i=1; $i <=4; $i++) { 
      $ppto_trimestre=$this->model_insumo->ppto_inicial_inversion_regional_trimestre($dep_id,$i);
      echo "[".round((($ppto_trimestre[0]['ppto_inicial_trimestre']/$techo_ini_reg[0]['techo_ppto_inicial'])*100),2)."]<br>";
    }


    //$this->load->view('admin/reportes_cns/repejecucion_pi/menu_pi', $data); 
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
    $calificacion=$this->ejecucion_finpi->calificacion_pi_regional_institucional($dep_id); /// % CUMPLIMIENTO

    if($dep_id==0){ /// INSTITUCIONAL
      $nro_reg=count($this->model_ptto_sigep->list_regionales());
      $matriz_reg=$this->ejecucion_finpi->matriz_detalle_proyectos_clasificado_regional();

      $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('CONSOLIDADO INSTITUCIONAL','');

      /// s1
      $ppto_programado_poa_inicial=$this->model_insumo->temporalidad_inicial_pinversion_institucional(); /// ppto poa Inicial
      $ppto_programado_poa=$this->model_insumo->temporalidad_programado_form5_institucional(); /// ppto poa (Actual)
      $ppto_ejecutado_sigep=$this->model_ptto_sigep->get_ppto_ejecutado_institucional(); /// Ppto Ejecutado sigep

      $matriz=$this->ejecucion_finpi->matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep);
      $cuadro_consolidado=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion($matriz); /// tabla vista
      $cuadro_consolidado_impresion=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion_impresion($matriz); /// tabla impresion


      ///// s3
      $tabla_detalle_ejec=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,0); /// Tabla Vista
      $tabla_detalle_ejec_impresion=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,1); /// Tabla Impresion Grafico 0
      $grafico_avance_proyectos='<div id="graf_proyectos"><div id="proyectos" style="width: 1100px; height: 700px; margin: 0 auto"></div></div>';

      ///// s4
      $tabla_detalle_proy_impresion=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,2); /// Tabla Impresion Grafico 1
      $tabla_detalle_ppto_impresion=$this->tabla_detalle_institucional_impresion($matriz_reg,$nro_reg,3); /// Tabla Impresion Grafico 2

       //// s5
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_institucional());
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_institucional(); /// Matriz consolidado de partidas Nacional
      $tabla_partidas=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,0); /// Tabla Clasificacion de partidas asignados por regional
      $tabla_partidas_impresion=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico
      $grafico_consolidado_partidas='
        <div id="partidas" style="width: 1000px; height: 750px; margin: 0 auto"></div>
        <div style="display: none"><div id="partidas_impresion"  style="width: 680px; height: 750px; margin: 0 auto"></div></div>';

      //// s6
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_institucional(); /// ejecutado mensual
      $tabla_ejec_mensual=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,0);
      $grafico_ejec_mensual='<div id="graf_ppto_mensual"><div id="ejec_mensual" style="width: 900px; height: 550px; margin: 2 auto"></div></div>';
     
      $tabla='
      <h2>Ejecucion Presupuestaria Institucional al mes de '.$this->verif_mes[2].' / '.$this->gestion.'</h2>
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
                      <a href="#s1" data-toggle="tab"> Ejecución Financiera (ptto. ejec. / ppto. inicial)</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> (%) Cumplimiento (ptto. ejec. / ppto. inicial)</a>
                  </li>
                  <li>
                      <a href="#s3" data-toggle="tab"> Ejecucion Financiera por Regional</a>
                  </li>
                  <li>
                      <a href="#s4" data-toggle="tab"> Detalle Ejecución por Regional</a>
                  </li>
                  <li>
                      <a href="#s5" data-toggle="tab"> Consolidado de Ejecución por Partidas</a>
                  </li>
                  <li>
                      <a href="#s6" data-toggle="tab"> Consolidado de Ejecución por Meses</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                  <div id="efi">'.$calificacion.'</div>
                  <div class="tab-pane fade in active" id="s1">
                    <div class="row">
                      
                      <div class="col-sm-12">
                        <div>
                          <div id="distribucion_ppto_ejecutado_inicial" style="width: 950px; height: 500px; margin: 0 auto" align="center"></div>
                          <div style="display: none"><div id="distribucion_ppto_ejecutado_inicial_impresion"  style="width: 700px; height: 350px; margin: 0 auto"></div></div>
                        </div>
                      </div>

                      <div align="right" id="botton">
                        <button onClick="imprimir_ejecucion_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>

                      <div class="col-sm-12">
                        <hr>
                        <div class="table-responsive" id="cuadro_consolidado_vista"></div>
                        <div id="cuadro_consolidado_impresion" style="display: none"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="s2">
                    <div class="row">

                      <div class="col-sm-12">
                        <div>
                          <div id="cumplimiento_mensual_ppto_inicial_ejecutado" style="width: 1050px; height: 650px; margin: 0 auto" align="center"></div>
                          <div style="display: none"><div id="cumplimiento_mensual_ppto_inicial_ejecutado_impresion"  style="width: 700px; height: 350px; margin: 0 auto" align="center"></div></div>
                        </div>
                      </div>

                      <div class="col-sm-12">
                        <hr>
                        <div class="table-responsive" id="cuadro_consolidado_vista"></div>
                        <div id="cuadro_consolidado_impresion" style="display: none"></div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="s3">
                    <div class="row">

                       <a href="'.site_url("").'/xls_rep_ejec_fin_pi/0/3" target=black title="EXPORTAR DETALLE" class="btn btn-default">
                          <img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;EXPORTAR CONSOLIDADO A DETALLE (EXCEL)
                        </a>

                        <a href="'.site_url("").'/xls_rep_ejec_fin_pi_resumen" target=black title="EXPORTAR DETALLE" class="btn btn-default">
                          <img src="'.base_url().'assets/Iconos/page_excel.png" WIDTH="20" HEIGHT="20"/>&nbsp;EXPORTAR CONSOLIDADO RESUMEN (EXCEL)
                        </a>
                        <hr>
                      <article class="col-sm-12">
                        '.$grafico_avance_proyectos.'

                        <center>
                          <br>
                          <table style="width:60%;">
                            <tr>
                              <td align="right">
                                <button  onClick="imprimir_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>
                              </td>
                            </tr>
                          </table>
                          <br>
                        </center>
                        
                        '.$tabla_detalle_ejec.'
                        <div id="tabla_impresion_ejecucion" style="display: none">
                          '.$tabla_detalle_ejec_impresion.'
                        </div>
                      </article>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="s4">
                    <article class="col-sm-12 col-md-12 col-lg-6">
                      <div class="rows" align=center>
                        <div id="graf_detalle_nro_proyectos">
                          <div id="detalle_proyectos1" style="width: 650px; height: 650px; margin: 2 auto"></div>
                          <div style="display: none"><div id="detalle_proyectos1_impresion"  style="width: 100px; height: 100px; margin: 0 auto" align="center"></div></div>
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
                          <div style="display: none"><div id="detalle_proyectos2_impresion"  style="width: 100px; height: 100px; margin: 0 auto" align="center"></div></div>
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
                  
                  <div class="tab-pane fade" id="s5">
                    <div class="row">
                      <article class="col-sm-12">
                        '.$grafico_consolidado_partidas.'
                          <div align="right">
                            <button  onClick="imprimir_partida()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </div>
                        <hr>
                        '.$tabla_partidas.'
                        <div id="tabla_impresion_partida" style="display: none">
                          '.$tabla_partidas_impresion.'
                        </div>
                      </article>

                    </div>
                  </div>

                  <div class="tab-pane fade" id="s6">
                    <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-12">
                      <div class="rows" align=center>
                      '.$grafico_ejec_mensual.'
                      </div>
                    </article>
                    <div align="right">
                      <button onClick="imprimir_ejecucion_mensual()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                     <hr>
                     '.$tabla_ejec_mensual.'
                      <div id="tabla_impresion_ejecucion_mensual" style="display: none">
                        '.$tabla_ejec_mensual.'
                      </div>
                    </div>
                  </div>

                </div>
            </div>
          </div>
      </div>';


      $result = array(
        'respuesta' => 'correcto',
        'mes' => $this->verif_mes[2].'/'.$this->gestion,

        /// s1
        'matriz1' => $matriz,
        'cuadro_consolidado' => $cuadro_consolidado,
        'cuadro_consolidado_impresion' => $cuadro_consolidado_impresion,

        'nro_reg'=>$nro_reg,
        'matriz_reg'=>$matriz_reg,

        'nro_part'=>$nro,
        'matriz_part'=>$matriz_partidas,

        'vector_meses'=>$vector_meses,
        //'vector_meses_acumulado'=>$vector_meses_acumulado,

        'lista_reporte' => $tabla,
      );

    }
    else{  /// REGIONAL
      $cabecera_grafico=$this->ejecucion_finpi->cabecera_reporte_grafico('REGIONAL '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion,'');
      

      /// s1
      $ppto_programado_poa_inicial=$this->model_insumo->temporalidad_inicial_pinversion_regional($dep_id); /// ppto poa Inicial
      $ppto_programado_poa=$this->model_insumo->temporalidad_programado_form5_regional($dep_id); /// ppto poa (Actual)
      $ppto_ejecutado_sigep=$this->model_ptto_sigep->get_ppto_ejecutado_regional($dep_id); /// Ppto Ejecutado sigep

      $matriz=$this->ejecucion_finpi->matriz_consolidado_ejecucion_pinversion($ppto_programado_poa_inicial,$ppto_programado_poa,$ppto_ejecutado_sigep);
      $cuadro_consolidado=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion($matriz); /// tabla vista
      $cuadro_consolidado_impresion=$this->ejecucion_finpi->tabla_consolidado_ejecucion_pinversion_impresion($matriz); /// tabla impresion


      /// s3
      $nro_proy=count($this->model_proyecto->list_proy_inversion_regional($dep_id)); /// nro de proyectos
      $matriz_proyectos=$this->ejecucion_finpi->matriz_proyectos_inversion_regional($dep_id); /// proyectos
      $tabla_detalle_ejec=$this->ejecucion_finpi->tabla_detalle_proyectos_impresion($matriz_proyectos,$nro_proy,0); /// Tabla detalle tabla
      $tabla_detalle_ejec_impresion=$this->ejecucion_finpi->tabla_detalle_proyectos_impresion($matriz_proyectos,$nro_proy,1); /// Tabla Impresion tabla
      $grafico_avance_proyectos='<div id="graf_proyectos"><div id="proyectos" style="width: 1100px; height: 700px; margin: 0 auto"></div></div>';


      /// s4
      $lista_detalle=$this->ejecucion_finpi->detalle_avance_fisico_financiero_pi($dep_id); /// vista Ejecucion Fisico y Financiero

      //// s5
      $nro=count($this->model_ptto_sigep->lista_consolidado_partidas_ppto_asignado_gestion_regional($dep_id));
      $matriz_partidas=$this->ejecucion_finpi->matriz_consolidado_partidas_prog_ejec_regional($dep_id); /// Matriz consolidado de partidas
      $tabla_partidas=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,0); /// Tabla Clasificacion de partidas asignados por regional
      $tabla_partidas_impresion=$this->ejecucion_finpi->tabla_consolidado_de_partidas($matriz_partidas,$nro,2); /// Tabla Clasificacion de partidas asignados por regional Grafico
      
      $grafico_consolidado_partidas='
        <div id="partidas" style="width: 1000px; height: 750px; margin: 0 auto"></div>
        <div style="display: none"><div id="partidas_impresion"  style="width: 700px; height: 600px; margin: 0 auto"></div></div>';

      //// s6
      $vector_meses=$this->ejecucion_finpi->vector_consolidado_ppto_mensual_regional($dep_id); /// ejecutado mensual
      $tabla_ejec_mensual=$this->ejecucion_finpi->detalle_temporalidad_mensual_regional($vector_meses,0);
      $grafico_ejec_mensual='<div id="graf_ppto_mensual"><div id="ejec_mensual" style="width: 900px; height: 550px; margin: 2 auto"></div></div>';
      
      $tabla='
      <h2>Ejecucion Financiera - '.strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion.'</h2>
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
            <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
            <h2>Ejecucion Financiera</h2>
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
                      <a href="#s1" data-toggle="tab"> Ejecución Financiera (ptto. ejec. / ppto. inicial)</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> (%) Cumplimiento (ptto. ejec. / ppto. inicial)</a>
                  </li>
                  <li>
                      <a href="#s3" data-toggle="tab"> Detalle Ejecución por Proyectos</a>
                  </li>
                  <li>
                      <a href="#s4" data-toggle="tab"> Detalle de Proyectos</a>
                  </li>
                  <li>
                      <a href="#s5" data-toggle="tab"> Consolidado de Ejecución por Partidas</a>
                  </li>
                  <li>
                      <a href="#s6" data-toggle="tab"> Consolidado de Ejecución por Meses</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                <div id="efi">'.$calificacion.'</div>
                <div class="tab-pane fade in active" id="s1">
                  <div class="row">
                    
                    <div class="col-sm-12">
                      <div>
                        <div id="distribucion_ppto_ejecutado_inicial" style="width: 1050px; height: 650px; margin: 0 auto" align="center"></div>
                        <div style="display: none"><div id="distribucion_ppto_ejecutado_inicial_impresion"  style="width: 700px; height: 350px; margin: 0 auto"></div></div>
                      </div>
                    </div>

                    <div align="right" id="botton">
                      <button onClick="imprimir_ejecucion_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>

                    <div class="col-sm-12">
                      <hr>
                      <div class="table-responsive" id="cuadro_consolidado_vista"></div>
                      <div id="cuadro_consolidado_impresion" style="display: none"></div>
                    </div>

                      
                  </div>
                </div>

                <div class="tab-pane fade" id="s2">
                  <div class="row">

                    <div class="col-sm-12">
                      <div>
                        <div id="cumplimiento_mensual_ppto_inicial_ejecutado" style="width: 1000px; height: 600px; margin: 0 auto" align="center"></div>
                        <div style="display: none"><div id="cumplimiento_mensual_ppto_inicial_ejecutado_impresion"  style="width: 700px; height: 350px; margin: 0 auto" align="center"></div></div>
                      </div>
                    </div>

                  </div>
                </div>

                <div class="tab-pane fade" id="s3">
                    <div class="row">

                      <article class="col-sm-12">
                        '.$grafico_avance_proyectos.'
                        <center>
                          <br>
                          <table style="width:80%;">
                            <tr>
                              <td align="right">
                                <button  onClick="imprimir_proyectos()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>
                              </td>
                            </tr>
                          </table>
                          <br>
                        </center>
                        '.$tabla_detalle_ejec.'
                        <div id="tabla_impresion_ejecucion" style="display: none">
                          '.$tabla_detalle_ejec_impresion.'
                        </div>
                      </article>
                    </div>
                </div>


                  <div class="tab-pane fade" id="s4">
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
                  
                 <div class="tab-pane fade" id="s5">
                    <div class="row">
                      <article class="col-sm-12">
                        '.$grafico_consolidado_partidas.'
                          <div align="right">
                            <button  onClick="imprimir_partida()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          </div>
                        <hr>
                        '.$tabla_partidas.'
                        <div id="tabla_impresion_partida" style="display: none">
                          '.$tabla_partidas_impresion.'
                        </div>
                      </article>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="s6">
                    <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-12">
                      <div class="rows" align=center>
                      '.$grafico_ejec_mensual.'
                      </div>
                    </article>
                    <div align="right">
                      <button onClick="imprimir_ejecucion_mensual()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                     <hr>
                     '.$tabla_ejec_mensual.'
                      <div id="tabla_impresion_ejecucion_mensual" style="display: none">
                        '.$tabla_ejec_mensual.'
                      </div>
                    </div>
                  </div>

                </div>
            </div>
          </div>
      </div>';


      $result = array(
        'respuesta' => 'correcto',
        'regional' => strtoupper($regional[0]['dep_departamento']),
        'mes' => $this->verif_mes[2].'/'.$this->gestion,

        /// s1
        'matriz1' => $matriz,
        'cuadro_consolidado' => $cuadro_consolidado,
        'cuadro_consolidado_impresion' => $cuadro_consolidado_impresion,
        ////

        'nro_proy'=>$nro_proy,
        'matriz_proy'=>$matriz_proyectos,
        ////

        'nro'=>$nro,
        'matriz'=>$matriz_partidas,
        ////

        'vector_meses'=>$vector_meses,
        //'vector_meses_acumulado'=>$vector_meses_acumulado,
        'lista_reporte' => $tabla,
      );
    }

    echo json_encode($result);
  }else{
      show_404();
  }
}



  /*-- CALIFICACION EJECUCION POR PROYECTO --*/
/*  public function calificacion_pi_regional_institucional($dep_id){
    if($dep_id==0){ /// Institucional
      $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_institucional_pi_aprobados(1); /// monto total asignado poa
      $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep_institucional(); /// monto total ejecutado poa
    }
    else{ /// Regional
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $total_ppto_asignado=$this->model_ptto_sigep->suma_ptto_regional_pi_aprobados($dep_id,1); /// monto total asignado poa
      $total_ppto_ejecutado=$this->model_ptto_sigep->suma_monto_ejecutado_total_ppto_sigep_regional($dep_id); /// monto total ejecutado poa
    }

    $eficacia=0;
    if(count($total_ppto_asignado)!=0 & count($total_ppto_ejecutado)!=0){
      $eficacia=round((($total_ppto_ejecutado[0]['ejecutado_total']/$total_ppto_asignado[0]['asignado']))*100,2);
    }

    $titulo='';
    if($eficacia<=50){$tp='danger';$titulo='CUMPLIMIENTO TOTAL : '.$eficacia.'% (INSATISFACTORIO)';} /// Insatisfactorio - Rojo
    if($eficacia > 50 & $eficacia <= 75){$tp='warning';$titulo='CUMPLIMIENTO TOTAL : '.$eficacia.'% (REGULAR)';} /// Regular - Amarillo
    if($eficacia > 75 & $eficacia <= 99){$tp='info';$titulo='CUMPLIMIENTO TOTAL : '.$eficacia.'% (BUENO))';} /// Bueno - Azul
    if($eficacia > 99 & $eficacia <= 101){$tp='success';$titulo='CUMPLIMIENTO TOTAL : '.$eficacia.'% (OPTIMO)';} /// Optimo - verde

    $tabla='
      <hr>
      <div class="alert alert-'.$tp.'" role="alert" align="center">
        <h2><b>'.$titulo.'</b></h2>
      </div>';

    return $tabla;
  }*/



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
    /// tipo : 0 Ejecucion de ppto por regional vista
    /// tipo : 1 Ejecucion de ppto por regional impresion
    /// tipo : 2 Distribucion nro de proyectos
    /// tipo : 3 Distribucion presupuesto

    $tabla='';
    if($tipo==0){
      $tabla.='
      <center>
      <table class="table table-bordered" style="width:60%;">
        <thead>
          <tr>
            <th style="width:30%;">REGIONAL</th>
            <th style="width:15%;">PRESUPUESTO <br> ASIGNADO '.$this->gestion.'</th>
            <th style="width:15%;">PRESUPUESTO <br> EJECUTADO '.$this->gestion.'</th>
            <th style="width:10%;">(%) EJECUCIÓN '.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_asig=0;
        $ppto_ejec=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:30%;"><b>'.$matriz[$i][1].'</b></td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                <td style="width:10%; font-size:12px" align=right><b>'.$matriz[$i][9].' %</b></td>
              </tr>';
              $ppto_asig=$ppto_asig+$matriz[$i][7];
              $ppto_ejec=$ppto_ejec+$matriz[$i][8];
          }

          $cum_inst=0;
          if($ppto_asig!=0){
            $cum_inst=round((($ppto_ejec/$ppto_asig)*100),2);
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_asig, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_ejec, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>'.$cum_inst.'%</b></td>
          </tr>
      </table>
      </center>';
    }
    elseif($tipo==1){
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:80%;">
        <thead>
          <tr>
            <th style="width:30%;">REGIONAL</th>
            <th style="width:15%;">PRESUPUESTO <br> ASIGNADO '.$this->gestion.'</th>
            <th style="width:15%;">PRESUPUESTO <br> EJECUTADO '.$this->gestion.'</th>
            <th style="width:10%;">(%) EJECUCIÓN '.$this->gestion.'</th>
          </tr>
        </thead>
        <tbody>';
        $ppto_asig=0;
        $ppto_ejec=0;
          for ($i=0; $i <$nro ; $i++) { 
            $tabla.='
              <tr>
                <td style="width:30%;"><b>'.$matriz[$i][1].'</b></td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][7], 2, ',', '.').'</td>
                <td style="width:15%;" align=right>Bs. '.number_format($matriz[$i][8], 2, ',', '.').'</td>
                <td style="width:10%; font-size:12px" align=right><b>'.$matriz[$i][9].' %</b></td>
              </tr>';
              $ppto_asig=$ppto_asig+$matriz[$i][7];
              $ppto_ejec=$ppto_ejec+$matriz[$i][8];
          }

          $cum_inst=0;
          if($ppto_asig!=0){
            $cum_inst=round((($ppto_ejec/$ppto_asig)*100),2);
          }
          $tabla.='
        </tbody>
          <tr>
            <td align:right><b>TOTAL</b></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_asig, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>Bs. '.number_format($ppto_ejec, 2, ',', '.').'</b></td>
            <td style="font-size:12px" align=right><b>'.$cum_inst.'%</b></td>
          </tr>
      </table>
      </center>';
    }
    elseif($tipo==2){
      $tabla.='
      <center>
      <table class="change_order_items" border=1 style="width:60%;">
        <thead>
          <tr>
            <th style="width:40%;">REGIONAL</th>
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
                <td style="width:40%;"><b>'.$matriz[$i][1].'</b></td>
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
            <th style="width:40%;">REGIONAL</th>
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
                <td style="width:40%;"><b>'.$matriz[$i][1].'</b></td>
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
            <td align=right>'.number_format($ppto_total, 2, ',', '.').'</td>
            <td align=right><b>'.$disribucion.' %</b></td>
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