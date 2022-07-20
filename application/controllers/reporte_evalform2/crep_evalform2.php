<?php
/*controlador para evaluacion ACP GESTION 2022*/
class Crep_evalform2 extends CI_Controller {  
    public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');

        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
        $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_certificacion');

        $this->load->model('mestrategico/model_objetivogestion');
        $this->load->model('mestrategico/model_objetivoregion');

        $this->pcion = $this->session->userData('pcion');
        $this->gestion = $this->session->userData('gestion');
        $this->adm = $this->session->userData('adm');
        $this->rol = $this->session->userData('rol_id');
        $this->dist = $this->session->userData('dist');
        $this->dist_tp = $this->session->userData('dist_tp');
        $this->tmes = $this->session->userData('trimestre');
        $this->trimestre = $this->model_evaluacion->trimestre();
        $this->fun_id = $this->session->userData('fun_id');
        $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
        $this->tp_adm = $this->session->userData('tp_adm');
        $this->mes = $this->mes_nombre();
        $this->load->library('eval_oregional');
      }
      else{
          redirect('/','refresh');
      }
    }

  /// MENU EVALUACIÓN POA FORM2
  public function menu_eval_form2(){
    $data['menu']=$this->menu(7); //// genera menu
    $regionales=$this->model_proyecto->list_departamentos();
    $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
    $tabla='';
    $tabla.='
      <input name="base" type="hidden" value="'.base_url().'">
      <input name="gestion" type="hidden" value="'.$this->gestion.'">
      <article class="col-sm-12">
        <div class="well">
          <form class="smart-form">
              <header><b>CONSOLIDADO EVALUACI&Oacute;N OPERACIONES - '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</b></header>
              <fieldset>          
                <div class="row">
                  <section class="col col-3">
                    <label class="label"><b>DIRECCIÓN ADMINISTRATIVA</b></label>
                    <select class="form-control" id="d_id" name="d_id" title="SELECCIONE REGIONAL">
                    <option value="">Seleccione Regional ....</option>
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

    $data['regional']=$tabla;
    $data['da']=$this->model_proyecto->list_departamentos();
    $tabla='';
    $tabla.='<div class="well">
              <div class="jumbotron">
                <h1>Evaluaci&oacute;n Operaciones '.$this->gestion.'</h1>
                  <p>
                    Reporte consolidado de evaluaci&oacute;n de Operaciones (formulario N° 2) al '.$this->trimestre[0]['trm_descripcion'].' de '.$this->gestion.' a nivel Institucional, Regional.
                  </p>
              </div>
            </div>';

    $data['titulo_modulo']=$tabla;
    $this->load->view('admin/reportes_cns/repevaluacion_form2/rep_menu', $data);
    //echo count($this->model_objetivogestion->get_list_ogestion_por_regional(2));
    /*$matriz=$this->eval_oregional->matriz_cumplimiento_operaciones_regional(2);
    for ($i=0; $i < count($this->model_objetivogestion->get_list_ogestion_por_regional(2)); $i++) { 
      for ($j=0; $j < 5; $j++) { 
        echo "[".$matriz[$i][$j]."]";
      }
      echo "<br>";
    }*/
   // echo $this->matriz_cumplimiento_operaciones_acp_regional(36,1);
  }



  /*-------- GET CUADRO EVALUACION FORM 2 INSTITUCIONAL --------*/
  public function get_cuadro_evaluacion_formulario2_institucional(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $dep_id = $this->security->xss_clean($post['dep_id']); // dep id, 0: Nacional
        
      $titulo='INSTITUCIONAL  / '.$this->gestion;
      $nro=count($this->model_proyecto->list_departamentos()); /// nro de regionales
      $matriz=$this->matriz_eval_form2(); /// Matriz
      $cabecera=$this->cabecera_reporte_grafico(); /// Cabecera Grafico
      $tabla_vista=$this->tabla_eval_form2($matriz,$nro,0); /// Tabla Vista
      $tabla_impresion=$this->tabla_eval_form2($matriz,$nro,1); /// Tabla Impresion

      //-------
      $matriz_form2_regresion=$this->tabla_trimestral_acumulado_institucional();
      $tabla_vista_acumulado=$this->get_tabla_cumplimiento_form2_priorizados_institucional(0);
      $tabla_vista_acumulado_impresion=$this->get_tabla_cumplimiento_form2_priorizados_institucional(1);


      $tabla='';
      $tabla='
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
          <div id="cabecera" style="display: none">'.$cabecera.'</div>
          <div id="calificacion"></div>
        </header>
        <div>
          <h2>Evaluación del Formulario N° 2 (Operaciones) - '.$titulo.'</h2>
            <div class="jarviswidget-editbox">
            </div>
            <div class="widget-body">
                <ul id="myTab1" class="nav nav-tabs bordered">
                  <li class="active">
                      <a href="#s1" data-toggle="tab"> (%) Cumplimiento de Operaciones</a>
                  </li>
                  <li>
                      <a href="#s2" data-toggle="tab"> Detalle de Cumplimiento</a>
                  </li>
                </ul>

                <div id="myTabContent1" class="tab-content padding-10">
                  <div class="tab-pane fade in active" id="s1">
                    <div align=center>
                      <div id="graf_detalle1">
                        <div id="grafico1" style="width: 950px; height: 550px; margin: 2 auto"></div>
                      </div>
                      '.$tabla_vista.' 
                    </div>
                    <div id="tabla_impresion_detalle1" style="display: none">
                     '.$tabla_impresion.'
                    </div>
                    <div align="right">
                      <button  onClick="imprimir_grafico1()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                  </div>
                  
                  <div class="tab-pane fade" id="s2">
                    <div class="rows" align=center>
                      <div id="graf_detalle2">
                        <div id="grafico2" style="width: 900px; height: 550px; margin: 2 auto"></div>
                      </div>
                      '.$tabla_vista_acumulado.'
                    </div>
                    <div id="tabla_impresion_detalle2" style="display: none">
                     '.$tabla_vista_acumulado_impresion.'
                    </div>
                    <div align="right">
                      <button  onClick="imprimir_grafico2()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                  </div>

                </div>

            </div>
          </div>
      </div>';

      $result = array(
        'respuesta' => 'correcto',
        'titulo'=>$titulo,
        'tabla'=>$tabla,
        'nro'=>$nro,
        'matriz'=>$matriz,
        'matriz_regresion'=>$matriz_form2_regresion,
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }


  /*-------- GET CUADRO EVALUACION FORM 2 REGIONAL --------*/
  public function get_cuadro_evaluacion_formulario2_regional(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $dep_id = $this->security->xss_clean($post['dep_id']); // dep id, 0: Nacional
      $regional=$this->model_proyecto->get_departamento($dep_id);

      $titulo=strtoupper($regional[0]['dep_departamento']).' / '.$this->gestion;
      $nro=count($this->model_objetivogestion->get_list_ogestion_por_regional($dep_id));
      $matriz=$this->eval_oregional->matriz_cumplimiento_operaciones_regional($dep_id);      
      $cabecera=$this->eval_oregional->cabecera_reporte_grafico($regional);
      $calificacion=$this->eval_oregional->calificacion_total_form2_regional($dep_id);


      
      /// --------------------------------------------------------------------------------------------
      $lista='';
      $lista.='<div style="font-family: Arial;">DETALLE DE OPERACIONES REGIONALES '.$this->gestion.'</div>
                <ul>';
                  for ($i=0; $i <$nro; $i++) { 
                    $lista.='<li style="font-family: Arial;font-size: 11px;height: 1%;">OPE. '.$matriz[$i][0].'.'.$matriz[$i][1].'.- '.$matriz[$i][2].' - <b>'.$matriz[$i][4].' %</b></li>';
                  }
                  $lista.='
                </ul>
                <hr>';
      $lista_operaciones=$lista;
      /// ----------------------------------------------------------------------------------------------

      /// --------------------------------------------------------------------------------------------
      $acp_regional=$this->model_objetivogestion->lista_acp_x_regional($dep_id);
      $tabla='';
      $tabla.='
      <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
        <header>
          <div id="cabecera" style="display: none">'.$cabecera.'</div>
        </header>
        <div>
            <h2>Evaluación del Formulario N° 2 (Operaciones) - '.$titulo.'</h2>
              <ul id="myTab1" class="nav nav-tabs bordered">
                <li class="active">
                    <a href="#sA" data-toggle="tab"> (%) Cumplimiento de Operaciones</a>
                </li>
                <li>
                    <a href="#sB" data-toggle="tab"> Detalle de Cumplimiento</a>
                </li>
              </ul>

              <div id="myTabContent1" class="tab-content padding-10">
                <div class="tab-pane fade in active" id="sA">
                    <div id="calificacion">'.$calificacion.'</div>
                    <div class="rows" align=center>
                      <div id="graf_detalle1">
                        <div id="grafico1" style="width: 1000px; height: 900px; margin: 2 auto"></div>
                      </div> 
                    </div>
                    <div id="tabla_impresion_detalle1" style="display: none">
                     '.$lista_operaciones.'
                    </div>
                    <div align="right">
                      <button  onClick="imprimir_grafico1()" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="30" HEIGHT="30"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                </div>
                
                <div class="tab-pane fade" id="sB">
                  <div class="row">
                    <article class="col-sm-12 col-md-12 col-lg-12">
                      <center>
                        <table class="table table-bordered" style="width:50%">
                          <thead>
                            <tr>
                              <th style="width:5%;text-align:center">COGIGO</th>
                              <th style="width:15%;text-align:center">ACCIÓN DE CORTO PLAZO REGIONAL '.$this->gestion.'</th>
                              <th style="width:3%;text-align:center">NRO. OPERACIONES</th>
                              <th style="width:3%;text-align:center">VER OPERACIONES</th>
                              <th style="width:3%;text-align:center">VER CUMPLIMIENTO</th>
                            </tr>
                          </thead>
                          <tbody>';
                        foreach($acp_regional as $row){
                          $lista_form2=$this->model_objetivoregion->list_oregional_regional($row['og_id'],$dep_id);
                          $tabla.='
                          <tr>
                            <td style="font-size: 13px;width:5%;text-align:center" title='.$row['og_id'].'><b>A.C.P. '.$row['og_codigo'].'</b></td>
                            <td>'.$row['og_objetivo'].'</td>
                            <td style="font-size: 11px;width:3%;text-align:right"><b>'.count($lista_form2).'</b></td>
                            <td align=center>
                              <a href="#" data-toggle="modal" data-target="#modal_operaciones" style="font-size: 10px;" class="btn btn-lg btn-default" name="'.$row['og_id'].'"  onclick="ver_lista_operaciones_acp('.$row['og_id'].','.$dep_id.');" title="VER OPERACIONES">VER OPERACIONES</a>
                            </td>
                            <td style="width:3%;text-align:center">
                              <a href="#" data-toggle="modal" data-target="#modal_cumplimiento" class="btn btn-lg btn-default" name="'.$row['og_id'].'"  onclick="nivel_cumplimiento_acp_regional('.$row['og_id'].','.$dep_id.');" title="NIVEL DE CUMPLIMIENTOACCION DE CORTO PLAZO - REGIONAL"><img src="'.base_url().'assets/Iconos/chart_bar.png" WIDTH="30" HEIGHT="30"/></a>
                            </td>
                          </tr>';
                        }
                        $tabla.='
                        </tbody>
                        </table>
                      </center>
                    </article>
                  </div>
                </div>
              </div>
            </div>
            
        </div>';

      $result = array(
        'respuesta' => 'correcto',
        'titulo'=>$titulo,
        'tabla'=>$tabla,
        'nro'=>$nro,
        'matriz'=>$matriz,
        'trimestre'=>$this->model_evaluacion->trimestre(),
        'gestion'=>$this->gestion,
        'regional'=>strtoupper($regional[0]['dep_departamento']),
      );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*---- FUNCION GET NIVEL DE CUMPLIMIENTO DE OPERACIONES POR ACP REGIONAL ---*/
  public function ver_datos_avance_oregional_acp(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $og_id = $this->security->xss_clean($post['og_id']); /// og id
      $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $acp_regional=$this->model_objetivogestion->get_objetivosgestion($og_id);

      $matriz=$this->matriz_cumplimiento_operaciones_acp_regional($og_id,$dep_id);
      $tabla='';
      $tabla.='
      <div class="row" align=center>
        <article class="col-sm-12 col-md-12 col-lg-12">
          <div id="container" style="width: 1100px; height: 650px; margin: 2 auto"></div><hr>

              <table style="width:90%">
                <tr>
                  <td><div style="font-size: 15px;font-family: Arial" align=left><b>DETALLE (%) CUMPLIMIENTO DE OPERACIONES DE LA ACP. '.$acp_regional[0]['og_codigo'].' .- </b>'.$acp_regional[0]['og_objetivo'].'</div></td>
                </tr>
              </table>
              <br>
              <table class="table table-bordered" style="width:90%">
                <thead>
                  <tr>
                    <th style="width:1%;text-align:center">#</th>
                    <th style="width:20%;text-align:center">OPERACIÓN</th>
                    <th style="width:5%;text-align:center">META</th>
                    <th style="width:10%;text-align:center">(%) CUMPLIMIENTO TRIMESTRAL</th>
                    <th style="width:10%;text-align:center">(%) CUMPLIMIENTO GESTIÓN</th>
                  </tr>
                </thead>
                  <tbody>';
                  $nro=0;
                for ($i=0; $i < count($this->model_objetivoregion->list_oregional_regional($og_id,$dep_id)); $i++) { 
                  $nro++;
                  $tabla.='
                  <tr>
                    <td style="font-size: 10px;font-family: Arial;text-align:center">'.$nro.'</td>
                    <td style="font-size: 11px;font-family: Arial;"><b>'.$matriz[$i][0].'.'.$matriz[$i][1].'.- </b>'.$matriz[$i][2].'</td>
                    <td style="font-size: 10px;font-family: Arial;text-align:right">'.$matriz[$i][3].'</td>
                    <td style="font-size: 12px;font-family: Arial;text-align:right;" bgcolor="#e6fdfb"><b>'.$matriz[$i][4].' %</b></td>
                    <td style="font-size: 10px;font-family: Arial;text-align:right">'.$matriz[$i][5].' %</td>
                  </tr>';
                }
                $tabla.='
                </tbody>
              </table>
            </article>
          </div>';


        $result = array(
          'respuesta' => 'correcto',
          'acp_regional' => $acp_regional,
          'tabla' => $tabla,
          'matriz' => $matriz,
          'nro' => count($this->model_objetivoregion->list_oregional_regional($og_id,$dep_id)),
          'trimestre'=>$this->model_evaluacion->trimestre(),
          'gestion'=>$this->gestion,
          'regional'=>strtoupper($regional[0]['dep_departamento']),
          //'lista_acp'=>$this->eval_oregional->ver_relacion_ogestion($dep_id),
        );

      echo json_encode($result);
    }else{
        show_404();
    }
  }



  /*---- MATRIZ DETALLE DE CUMPLIMIENTO DE OPERACIONES POR ACP REGIONAL ---*/
  public function matriz_cumplimiento_operaciones_acp_regional($og_id,$dep_id){
    $tabla='';
    $lista_form2=$this->model_objetivoregion->list_oregional_regional($og_id,$dep_id);

    $nro=0;
    foreach($lista_form2 as $row){
      $calificacion=$this->eval_oregional->calificacion_trimestral_acumulado_x_oregional($row['or_id'],$this->tmes);
      $matriz[$nro][0]=$row['og_codigo'];
      $matriz[$nro][1]=$row['or_codigo'];
      $matriz[$nro][2]=$row['or_objetivo'];
      $matriz[$nro][3]=round($row['or_meta'],2);
      $matriz[$nro][4]=round($calificacion[3],2); /// cumplimiento al trimestre
      $matriz[$nro][5]=round($calificacion[4],2); /// cumplimiento a la gestion
      $nro++;
    }
    
    return $matriz;
  }


  /*---- FUNCION GET LISTA DE OPERACIONES POR ACP --------*/
  public function get_lista_operaciones_acp(){
    if($this->input->is_ajax_request() && $this->input->post()){
      $post = $this->input->post();
      $og_id = $this->security->xss_clean($post['og_id']); /// or id
      $dep_id = $this->security->xss_clean($post['dep_id']); /// Regional

      $acp_regional=$this->model_objetivogestion->get_objetivosgestion($og_id);
      $lista_form2=$this->model_objetivoregion->list_oregional_regional($og_id,$dep_id);

      $tabla='';
      $tabla.='
      <article class="col-sm-12 col-md-12 col-lg-12">
              <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                <header>
                  <span class="widget-icon"> <i class="fa fa-list-alt"></i> </span>
                  <h2>ACP. '.$acp_regional[0]['og_codigo'].'.- '.$acp_regional[0]['og_objetivo'].'</h2>
        
                  <div class="widget-toolbar hidden-phone">
                    <div class="smart-form">
                      <label class="checkbox">
                        <input type="checkbox" name="checkbox">
                        <i></i>Add Padding</label>
                    </div>
                  </div>
        
                </header>
                <div>
                  <div class="jarviswidget-editbox">
                  </div>
                  <div class="widget-body no-padding">
        
                    <div class="panel-group smart-accordion-default" id="accordion-2">';
                      $nro=0;
                      foreach($lista_form2 as $row){
                        $nro++;
                        $in='';
                        $colapsed='class="collapsed"';
                        if($nro==1){
                          $in='in';
                          $colapsed='';
                        }
                        $tabla.='
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-2" href="#'.$nro.'" '.$colapsed.'> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> '.$row['og_codigo'].'.'.$row['or_codigo'].'.- '.$row['or_objetivo'].'</a></h4>
                          </div>
                          <div id="'.$nro.'" class="panel-collapse collapse '.$in.'">
                            <div class="panel-body">
                              
                            </div>
                          </div>
                        </div>';
                      }

                    $tabla.='

                    </div>
        
                  </div>
        
                </div>
        
              </div>
        
            </article>
            ';
      /*foreach($lista_form2 as $row){
        $tabla.=$row['or_objetivo'].'<br>';
      }*/


    /*  $detalle_oregional=$this->model_objetivoregion->get_objetivosregional($or_id); /// Objetivo Regional
      $regional=$this->model_proyecto->get_departamento($dep_id);
      $meta='';
      if($detalle_oregional[0]['indi_id']==2){
        $meta='%';
      }

      $titulo='
      <b style="font-family:Verdana;font-size: 16px;">
        OBJ. REGIONAL ('.strtoupper($regional[0]['dep_departamento']).'): '.$detalle_oregional[0]['or_codigo'].' '.$detalle_oregional[0]['or_objetivo'].'<br>
        META '.$this->gestion.' : '.round($detalle_oregional[0]['or_meta'],2).' '.$meta.'
      </b>';

      $boton_imprimir='
        <hr>
          <div align=right>
            <a href="javascript:abreVentana(\''.site_url("").'/rep_list_form4_priori_oregional/'.$or_id.'\');" title="IMPRIMIR ACP DISTRIBUCION REGIONAL" class="btn btn-default">
              <img src="'.base_url().'assets/Iconos/printer_empty.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR ACTIVIDADES PRIORITARIOS
            </a>
          </div>
        </hr>';*/

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
         /* 'tabla'=>$this->eval_oregional->get_mis_form4_priorizados_x_oregional($or_id,0),
          'titulo'=>$titulo,
          'imprimir_act_priori'=>$boton_imprimir,*/
        );

      echo json_encode($result);
    }else{
        show_404();
    }
  }




































  /*-- GENERA TABLA PARA EVALUACION TRIMESTRAL INSTITUCIONAL --*/
  public function tabla_trimestral_acumulado_institucional(){

    for ($i=0; $i <=4 ; $i++) { 
      $matriz[1][$i]=0;
      $matriz[2][$i]=0;
      $matriz[3][$i]=0;
      $matriz[4][$i]=0;
      $matriz[5][$i]=0;
      $matriz[6][$i]=0;
    }

    for ($i=1; $i <=4 ; $i++) { 
      $valor=$this->calificacion_trimestral_acumulado_institucional($i);
      $matriz[1][$i]=$valor[1];  /// prog
      $matriz[2][$i]=$valor[2];  /// ejec
      $matriz[3][$i]=$valor[3];  /// % cumplimiento trimestral
      $matriz[4][$i]=(100-$valor[3]);  /// % no cumplido
    }

    $total=$matriz[1][4];

    if($total!=0){
      for ($i=1; $i <=4 ; $i++) { 
        $matriz[5][$i]=round((($matriz[1][$i]/$total)*100),2);  /// % Programado con respecto al total acumulado
        $matriz[6][$i]=round((($matriz[2][$i]/$total)*100),2);  /// % Ejecutado con respecto al total acumulado
      }
    }
    else{
      for ($i=1; $i <=4 ; $i++) { 
        $matriz[5][$i]=0;  /// % Programado con respecto al total acumulado
        $matriz[6][$i]=0;  /// % Ejecutado con respecto al total acumulado
      }
    }

    return $matriz;
  }


 /*-- CALIFICACION TRIMESTRAL INSTITUCIONAL --*/
  public function calificacion_trimestral_acumulado_institucional($trimestre){
    $valor = array( '1' => '0','2' => '0','3' => '0','4' => '0');

    if(count($this->model_objetivoregion->get_suma_total_prog_form2_institucional())!=0){
      $suma_total_prog=0; $suma_prog=0; $suma_ejec=0;
      //// Suma total programado por operacion
      $prog_total=$this->model_objetivoregion->get_suma_total_prog_form2_institucional();
      if(count($prog_total)!=0){
        $suma_total_prog=$prog_total[0]['programado_total'];
      }
      ///-----

      for ($i=1; $i <=$trimestre; $i++) {
        $get_trm=$this->model_objetivoregion->get_suma_trimestre_prog_form2_institucional($i); /// Temporalidad Programado
        $get_trm_ejec=$this->model_objetivoregion->get_suma_trimestre_ejec_form2_institucional($i); /// Temporalidad Ejecutado

        if(count($get_trm)!=0){
          $suma_prog=$suma_prog+$get_trm[0]['prog']; 
        }

        if(count($get_trm_ejec)!=0){
          $suma_ejec=$suma_ejec+$get_trm_ejec[0]['ejec'];
        }

        $ejecucion=0;
        if($suma_ejec!=0){
          $ejecucion=round((($suma_ejec/$suma_prog)*100),2);
        }

        $cumplimiento_gestion=0;
        if($suma_total_prog!=0){
          $cumplimiento_gestion=round((($suma_ejec/$suma_total_prog)*100),2);
        }
      }


      $valor[1]=$suma_prog; /// Programado Acumulado al trimestre
      $valor[2]=$suma_ejec; /// Ejecutado Acumulado al trimestre
      $valor[3]=$ejecucion; /// Cumplimiento al trimestre
      $valor[4]=$cumplimiento_gestion; /// Cumplimiento a la Gestion
    }

    return $valor; 
  }


  /*-- VISTA DE TABLA CUMPLIMUENTO PARA IMPRESION (ACUMULADO) --*/
  public function get_tabla_cumplimiento_form2_priorizados_institucional($tp_rep){
    /// tp_rep=0 normal
    /// tp_rep=1 Reporte

    $valor=$this->tabla_trimestral_acumulado_institucional();
    $tabla='';

    if($tp_rep==0){ /// VISTA NORMAL

      $tabla.='
      <center>
        <table class="table table-bordered" border=0.2 style="width:60%;">
          <thead>
            <tr align=center>
              <th style="width:20%; height:30px; text-align:center">I TRIMESTRE</th>
              <th style="width:20%;text-align:center">II TRIMESTRE</th>
              <th style="width:20%;text-align:center">III TRIMESTRE</th>
              <th style="width:20%;text-align:center">IV TRIMESTRE</th>
            </tr>
          </thead>
          <tbody>
            <tr>';
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:6%;" align=center>
                <table class="table table-bordered" border=0.2 style="width:80%;">
                  <tr>
                    <td style="width:50%;"><b>(%) PROG.</b></td>
                    <td style="width:50%;font-size: 12px; color:blue" align=right><b>'.$valor[5][$i].'%</b></td>
                  </tr>
                  <tr>
                    <td><b>(%) CUMP.</b></td>
                    <td style="font-size: 12px; color:blue" align=right><b>'.$valor[6][$i].'%</b></td>
                  </tr>
                </table>
              </td>';
            }
            $tabla.='
            </tr>  
          </tbody>
        </table>
      </center>';
    }
    else{ /// VISTA PARA REPORTES
      $tabla.='
      <table class="change_order_items" border=1 style="width:100%;">
        <thead>
          <tr align=center>
            <th style="width:10%; text-align:center"></th>
            <th style="width:23%; height:20px; text-align:center">I TRIMESTRE</th>
            <th style="width:23%;text-align:center">II TRIMESTRE</th>
            <th style="width:23%;text-align:center">III TRIMESTRE</th>
            <th style="width:23%;text-align:center">IV TRIMESTRE</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width:10%;"><b>(%) PROG.</b></td>';
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:20%; height:20px; color:blue" align=right><b>'.$valor[5][$i].'%</b></td>';
            }
          $tabla.='
          </tr> 
          <tr>
            <td style="width:10%;"><b>(%) CUMP.</b></td>';
            for ($i=1; $i <=4 ; $i++) {
              $tabla.='
              <td style="width:20%; height:20px; color:blue" align=right><b>'.$valor[6][$i].'%</b></td>';
            }
          $tabla.='
          </tr>  
        </tbody>
      </table>';
    }

    return $tabla;
  }



  /// TABLA  EVALUACION DE FORMULARIO 2
  public function tabla_eval_form2($matriz,$nro,$tp_rep){
    $tabla='';
    // tp_rep : 0 normal
    // tp_rep : 1 impresion
    $tyle='class="table table-bordered" border=0.2 style="width:80%;"';
    if($tp_rep==1){
      $tyle='class="change_order_items" border=1 style="width:100%;"';
    }

    $tabla.='
      <center>
        <table '.$tyle.'>
          <thead>
            <tr align=center>
              <th style="width:9.09%;"></th>';
              for ($i=0; $i<$nro; $i++) { 
                $tabla.='<th style="width:9.09%;"><center>'.$matriz[$i][2].'</center></th>';
              }
              $tabla.='
              </tr>
            </thead>
          <tbody>
            <tr>
              <td style="height:20px;"><b>(%) CUMPLIMIENTO</b></td>';
              for ($i=0; $i<$nro; $i++) { 
                $tabla.='<td style="width:9.09%;" align=right><b>'.$matriz[$i][6].' %</b></td>';
              }
              $tabla.='
            </tr>
          </tbody>
        </table>
      </center>';

    return $tabla;
  }


  /// MATRIZ EVALUACION DE FORMULARIO 2
  public function matriz_eval_form2(){
    $regionales=$this->model_proyecto->list_departamentos();
    $nro=0;
    foreach($regionales as $row){
      $calificacion=$this->calificacion_total_form2_regional($row['dep_id']);
      
      $mat[$nro][1]=$row['dep_id'];
      $mat[$nro][2]=strtoupper($row['dep_sigla']);
      $mat[$nro][3]=$calificacion[1]; /// programado trimestral
      $mat[$nro][4]=$calificacion[2]; /// ejecutado trimestral
      $mat[$nro][5]=$calificacion[3]; /// total programado Gestion
      $mat[$nro][6]=$calificacion[4]; /// % cumplimiento
      $nro++;
    }

    return $mat;
  }


  /*--- PARAMETROS DE CALIFICACION OPERACIONES REGIONAL ---*/
  public function calificacion_total_form2_regional($dep_id){
    $prog_trimestre=0; $ejec_trimestre=0;$prog_total_form2=0;

    $prog_total=$this->model_objetivoregion->get_suma_total_prog_form2_regional($dep_id);
    if(count($prog_total)!=0){
      $prog_total_form2=$prog_total[0]['programado_total'];
    }

    for ($i=1; $i <=$this->tmes; $i++) { 
      $prog=$this->model_objetivoregion->get_suma_trimestre_prog_form2_regional($dep_id,$i);
      if(count($prog)!=0){
        $prog_trimestre=$prog_trimestre+$prog[0]['prog'];
      }

      $ejec=$this->model_objetivoregion->get_suma_trimestre_ejec_form2_regional($dep_id,$i);
      if(count($ejec)!=0){
        $ejec_trimestre=$ejec_trimestre+$ejec[0]['ejec'];
      }
    }

    $calif[1]=$prog_trimestre; /// programado trimestral
    $calif[2]=$ejec_trimestre; /// ejecutado trimestral
    $calif[3]=$prog_total_form2; /// total programado Gestion
    $calif[4]=0;

    if($prog_total_form2!=0){
      $calif[4]=round((($calif[2]/$prog_total_form2)*100),2);
    }

    return $calif;
  }


 /*---- CABECERA REPORTE OPERACIONES POR REGIONALES (GRAFICO)----*/
  function cabecera_reporte_grafico(){
    $tabla='';

    $tabla.='
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
        <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                    <tr style="font-size: 15px;font-family: Arial;">
                        <td style="width:45%;height: 20%;">&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                    </tr>
                    <tr>
                        <td style="width:50%;height: 20%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                    </tr>
                </table>
            </td>
            <td style="width:30%; height: 2%; font-size: 8px;text-align:right;">
              '.date("d").' de '.$this->mes[ltrim(date("m"), "0")]. " de " . date("Y").'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px black; text-align: center;">
              <td style="width:10%; text-align:center;">
              </td>
              <td style="width:80%; height: 5%">
                <table align="center" border="0" style="width:100%;">
                  <tr style="font-size: 23px;font-family: Arial;">
                    <td style="height: 32%; text-align:center"><b>PLAN OPERATIVO ANUAL - GESTI&Oacute;N '.$this->gestion.'</b></td>
                  </tr>
                  <tr style="font-size: 20px;font-family: Arial;">
                    <td style="height: 5%; text-align:center">EVALUACIÓN DE OPERACIONES</td>
                  </tr>
                </table>
              </td>
              <td style="width:10%; text-align:center;">
              </td>
          </tr>
      </table>';

    return $tabla;
  }

  /*=== GENERAR MENU ===*/
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

  /*------ NOMBRE MES -------*/
  public function mes_nombre(){
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