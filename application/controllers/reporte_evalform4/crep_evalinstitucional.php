<?php
class Crep_evalinstitucional extends CI_Controller {  
    public function __construct (){
        parent::__construct();
        if($this->session->userdata('fun_id')!=null){
            $this->load->model('Users_model','',true);
            $this->load->model('menu_modelo');
            $this->load->model('programacion/model_proyecto');
            $this->load->model('programacion/model_faseetapa');
            $this->load->model('programacion/model_producto');
            $this->load->model('programacion/model_componente');
            $this->load->model('mantenimiento/model_estructura_org');
            $this->load->model('ejecucion/model_seguimientopoa');

            $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad
            $this->load->model('reporte_eval/model_evalinstitucional'); /// Model Evaluacion Institucional
            $this->load->model('mantenimiento/model_ptto_sigep');
            $this->load->model('ejecucion/model_evaluacion');
            $this->load->model('ejecucion/model_certificacion');
            $this->load->model('reporte_eval/model_evalprograma'); /// Model Evaluacion Programas

            $this->pcion = $this->session->userData('pcion');
            $this->gestion = $this->session->userData('gestion');
            $this->adm = $this->session->userData('adm');
            $this->rol = $this->session->userData('rol_id');
            $this->dist = $this->session->userData('dist');
            $this->dist_tp = $this->session->userData('dist_tp');
            $this->tmes = $this->session->userData('trimestre');
            $this->fun_id = $this->session->userData('fun_id');
            $this->tr_id = $this->session->userData('tr_id'); /// Trimestre Eficacia
            $this->tp_adm = $this->session->userData('tp_adm');
            $this->trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
            $this->load->library('evaluacionpoa');
        }
        else{
            redirect('/','refresh');
        }
    }

    /// MENU EVALUACIÓN POA 
    public function menu_eval_poa(){
      $data['menu']=$this->menu(7); //// genera menu
      $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
      $data['regional']=$this->evaluacionpoa->listado_regionales();
      $data['da']=$this->model_proyecto->list_departamentos();
      $tabla='';
      $tabla.='<div class="well" id="update_eval">
                  <div class="jumbotron">
                    <h1>Evaluaci&oacute;n POA '.$this->gestion.'</h1>
                    <p>
                        Reporte consolidado de evaluaci&oacute;n POA acumulado al '.$trimestre[0]['trm_descripcion'].' de '.$this->gestion.' a nivel Institucional, Regional y distrital.
                    </p>';
                    if($this->tp_adm==1){
                      $tabla.='
                      <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#exampleModalCenter">
                        Actualizar Datos de Evaluación POA (Actividades)
                      </button>';
                    }
                    $tabla.=' 
                  </div>
              </div>';

      $data['titulo_modulo']=$tabla;

      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_menu', $data);
    }


    /*--- GET IFRAME EVALUACION INTITUCIONAL REGIONAL DISTRITAL 2021 ---*/
    public function get_cuadro_evaluacion_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id=$this->security->xss_clean($post['dep_id']); // dep id
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $tp_id = $this->security->xss_clean($post['tp_id']); // tipo de operacion

        $tabla='<center><iframe id="ipdf" width="99%" height="1000px;" src="'.base_url().'index.php/rep_eval_poa/iframe_rep_evaluacionpoa/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'"></iframe></center>';
        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
        );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


    //// IFRAME NACIONAL, REGIONAL DISTRITAL - EVAL POA 2025
    public function iframe_evaluacion_poa($dep_id,$dist_id,$tp_id){
        $data['trimestre']=$this->trimestre;

        if($dep_id==0){ /// INSTITUCIONAL
        
        $matriz=$this->evaluacionpoa->tabla_regresion_lineal_nacional(); /// Tabla para el grafico al trimestre
        $matriz_gestion=$this->evaluacionpoa->tabla_regresion_lineal_nacional_total(); /// Tabla para el grafico Total Gestion
        

        $data['titulo']=
          '<h2><b>CONSOLIDADO INSTITUCIONAL - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $cabecera_regresion=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',1);
        //$data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',1);
        $cabecera_regresion_total=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',3);
        //$data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',4);
        $matriz_parametros=$this->evaluacionpoa->matriz_eficacia_institucional();
        $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_INSTITUCIONAL';

        }
        elseif($dep_id!=0 & $dist_id==0){ /// REGIONAL
          $data['departamento']=$this->model_proyecto->get_departamento($dep_id);

          $matriz=$this->evaluacionpoa->tabla_regresion_lineal_regional($dep_id); /// Tabla para el grafico al trimestre
          $matriz_gestion=$this->evaluacionpoa->tabla_regresion_lineal_regional_total($dep_id); /// Tabla para el grafico Total Gestion
          
          $data['boton1']='VER DETALLE DE CUMPLIMIENTO POR UNIDADES';
          $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';
          $data['titulo']=
            '<h2><b>CONSOLIDADO REGIONAL '.strtoupper($data['departamento'][0]['dep_departamento']).' - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

          $cabecera_regresion=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
         // $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
          $cabecera_regresion_total=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],3);
          //$data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],4);
          $matriz_parametros=$this->evaluacionpoa->matriz_eficacia_regional($dep_id); /// matriz de parametros
          $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['departamento'][0]['dep_departamento']);

        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// DISTRITAL
          $data['distrital']=$this->model_proyecto->dep_dist($dist_id);

          $matriz=$this->evaluacionpoa->tabla_regresion_lineal_distrital($dist_id); /// Tabla para el grafico al trimestre
          $matriz_gestion=$this->evaluacionpoa->tabla_regresion_lineal_distrital_total($dist_id); /// Tabla para el grafico Total Gestion
          $data['boton1']='VER DETALLE DE CUMPLIMIENTO POR UNIDADES';
          $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';
          $data['titulo']=
            '<h2>'.strtoupper($data['distrital'][0]['dist_distrital']).' <b>- '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

          $cabecera_regresion=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
          //$data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
          $cabecera_regresion_total=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],3);
         // $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],4);
          $matriz_parametros=$this->evaluacionpoa->matriz_eficacia_distrital($dist_id); /// matriz de parametros
          $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['distrital'][0]['dist_distrital']);

        }

        $tabla_regresion=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($matriz,$this->tmes,2,0); /// Tabla que muestra el acumulado por trimestres Regresion
       // $data['tabla_regresion_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion
        
        $tabla_regresion_gestion=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($matriz_gestion,$this->tmes,3,0); /// Tabla que muestra el cumplimiento Anual  
        //$data['tabla_regresion_total']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],$this->tmes,3,1); /// Tabla que muestra el acumulado Gestion 
        //$data['tabla_regresion_total_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],$this->tmes,3,0); /// Tabla que muestra el acumulado Gestion Impresion
        
        $tabla_pastel=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($matriz,$this->tmes,4,1); /// Tabla Torta
        //$data['tabla_pastel_todo_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

        $boton_parametros_unidad='
              <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_unidad/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
              <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

        $boton_parametros_prog='
              <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_programa/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
              <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

         $data['base']='
        


        <input name="tit" type="hidden" value="'.$tit.'">
        <input name="dep_id" type="hidden" value="'.$dep_id.'">
        <input name="dist_id" type="hidden" value="'.$dist_id.'">
        <input name="tp_id" type="hidden" value="'.$tp_id.'">';
        $calificacion=$this->evaluacionpoa->calificacion_eficacia($matriz[5][$this->tmes]); /// calificacion
        $matriz_parametros=$this->evaluacionpoa->parametros_eficacia($matriz_parametros,1); /// parametros de cumplimiento


        $titulo = [];
        for ($i = 0; $i <= $this->tmes; $i++) {$titulo[] = $matriz[1][$i];}
        $programacion = [];
        for ($i = 0; $i <= $this->tmes; $i++) {$programacion[] = (int)$matriz[2][$i];}
        $ejecucion = [];
        for ($i = 0; $i <= $this->tmes; $i++) { $ejecucion[] = (int)$matriz[3][$i];}

        $data['formulario']='
        '.$this->grafico_cumplimiento_poa($titulo,$programacion,$ejecucion,0).'
        '.$this->grafico_cumplimiento_poa_pastel($matriz).'
        '.$this->grafico_cumplimiento_poa_anual($matriz_gestion).'
        '.$this->grafico_cumplimiento_poa_pastel_parametros($matriz_parametros).'

              <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                        <h2>Evaluacion POA</h2>
                    </header>
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->
                        <!-- widget content -->
                        <div class="widget-body">
                            <p><input name="base" type="hidden" value="'.base_url().'"></p>
                            <hr class="simple">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a href="#s1" data-toggle="tab"> Detalle Evaluación POA</a>
                                </li>
                                <li>
                                    <a href="#s3" data-toggle="tab"> Parametros de cumplimiento</a>
                                </li>
                                <li>
                                    <a href="#s4" data-toggle="tab"> Detalle por Programas</a>
                                </li>
                                <li>
                                    <a href="#s5" data-toggle="tab"> Detalle Ejecucion Cert. POA.</a>
                                </li>
                                <li>
                                    <a href="#s6" data-toggle="tab"> Detalle Ejecucion Partidas.</a>
                                </li>
                            </ul>
    
                            <div id="myTabContent1" class="tab-content padding-10">
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                  <div id="eficacia">'.$calificacion.'</div><div id="efi"></div>
                                
                                    <div align="right" title="CAPTURAR PANTALLA">
                                        <button id="btnregresion" class="btn btn-default"><img src="'.base_url().'assets/Iconos/camera.png" WIDTH="25" HEIGHT="25" title="CAPTURA DE PANTALLA"/></button>
                                    </div>
                                  <hr>
                                </div>
                                <div class="tab-pane fade in active" id="s1">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                              <div class="row" style="align:center">
                                                <div id="regresion" style="width: 700px; height: 420px; margin: 0 auto"></div>
                                                <div id="tabla_regresion_impresion">'.$tabla_regresion.'</div>
                                              </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                              <div class="row" style="align:center">
                                                <div id="pastel_todos" style="width: 650px; height: 420px; margin: 0 auto"></div>
                                                <div id="tabla_pastel_vista">'.$tabla_pastel.'</div>
                                              </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                                              <div class="row" style="align:center">
                                                <div id="regresion_gestion" style="width: 700px; height: 420px; margin: 0 auto"></div>
                                                <div id="tabla_regresion_total_impresion">'.$tabla_regresion_gestion.'</div>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
                                          <div class="row" style="align:center">
                                            <hr><center><button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/> <b>IMPRIMIR / GUARDAR</b></button></center><hr>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                

                               <div class="tab-pane fade" id="s3">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
                                          <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                                            <div class="row" style="align:center">
                                            '.$parametro_eficacia.'
                                            </div>
                                          </div>
                                          <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                                            <div class="row" style="align:center">
                                            '.$mis_unidades.'
                                            </div>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s4">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div align="left" id="boton_eficacia_prog">
                                              <a href="#" class="btn btn-default eficacia_prog" title="CUADRO DE EFICIENCIA Y EFICACIA" style="width:40%;"> <img src="'.base_url().'assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp; --</a>
                                            </div>
                                            <div id="lista_prog"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div id="parametros_prog"></div>
                                            <div align="right" id="print_eficacia_prog" style="display: none">
                                              '.$boton_parametros_prog.'
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s5">
                                    
                                    <div align="left" id="boton_ejec_certpoa">
                                        <a href="#" class="btn btn-default ejecucion_certpoa" title="CUADRO DE EJECUCION DE CERTIFICACION POA" style="width:25%;"> <img src="'.base_url().'assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;EJECUCIÓN DE CERTIFICACIÓN POA</a>
                                    </div>
                                    <div id="lista_certpoa"></div>
                                   
                                </div>

                                <div class="tab-pane fade" id="s6">
                                    
                                    <div align="left" id="boton_ejec_partidas">
                                        <a href="#" class="btn btn-default ejecucion_partidas" title="CUADRO DE EJECUCION POR PARTIDAS" style="width:25%;"> <img src="'.base_url().'assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;EJECUCIÓN POR PARTIDAS</a>
                                    </div>
                                    <div id="lista_partidas"></div>
                                   
                                </div>
                            </div>
    
                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->  ';


        
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);
    }

 /// grafico PARAMETROS DE CUMPLIMIENTO PASTEL
    public function grafico_cumplimiento_poa_pastel_parametros($matriz){
      $tabla='      
      <script  src="'.base_url().'assets/js/libs/jquery-2.0.2.min.js"></script>
      <script  src="'.base_url().'assets/js/libs/jquery-ui-1.10.3.min.js"></script>
      <script  src="'.base_url().'assets/highcharts/js/highcharts.js"></script>
      <script>
      $(function() {
      Highcharts.chart("parametro_efi", {
        chart: {
          type: "pie",
          backgroundColor: "#f0f0f0", // Fondo plomo claro
          spacing: [30, 10, 15, 10], // Espaciado superior aumentado
          options3d: { enabled: true } // Deshabilitar 3D
        },
        title: {
            text: "PARAMETRO DE EFICACIA",
            align: "center",
            verticalAlign: "top",
            margin: 10,
            style: {
              color: "#333333",
              fontSize: "24px",
              fontWeight: "600",
              fontFamily: "Arial, sans-serif",
              textTransform: "uppercase"
            },
            y: 10 // Posición vertical ajustada
        },
        tooltip: {
          useHTML: true,
          backgroundColor: "#ffffff",
          borderWidth: 0,
          borderRadius: 8,
          shadow: {
            color: "rgba(0,0,0,0.1)",
            width: 5,
            offsetX: 2,
            offsetY: 2
          },
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: "pointer",
            innerSize: "39%", // Efecto donut moderno
            dataLabels: {
              enabled: true,
              format: "<b>{point.name}</b>",
              style: {
                color: "#2d3748",
                fontSize: "12px",
                fontWeight: "500",
                textOutline: "none"
              },
              distance: 20,
              connectorWidth: 1,
              connectorColor: "#cbd5e0"
            },
            borderWidth: 2,
            borderColor: "#ffffff" // Borde blanco entre secciones
          }
        },
        series: [{
          type: "pie",
          name: "Unidades",
          data: [
              {
                name: "INSATISFACTORIO : '.$matriz[1][3].' %",
                y: '.$matriz[1][3].',
                color: "#ef4444", // Rojo mejorado
                className: "slice-emergencia"
              },

              {
                name: "REGULAR : '.$matriz[2][3].' %",
                y: '.$matriz[2][3].',
                color: "#f59e0b",
              },

              {
                name: "BUENO : '.$matriz[3][3].' %",
                y: '.$matriz[3][3].',
                color: "#10b981",
              },

              {
                name: "OPTIMO : '.$matriz[4][3].' %",
                y: '.$matriz[4][3].',
                color: "#4caf50",
                sliced: true,
                selected: true
              }
          ]
        }],
            responsive: {
            rules: [{
              condition: {
                maxWidth: 600
              },
              chartOptions: {
                title: {
                  style: { fontSize: "18px" },
                  margin: 20,
                  y: 10
                },
                plotOptions: {
                  pie: {
                    dataLabels: {
                      distance: 15,
                      style: { fontSize: "10px" }
                    }
                  }
                }
              }
            }]
          }
      });
      });
      </script>';

      return $tabla;
    }
    
/// grafico Regresion ANUAL Cumplimiento POA
    public function grafico_cumplimiento_poa_anual($matriz_gestion){
      $tabla='      
      <script  src="'.base_url().'assets/js/libs/jquery-2.0.2.min.js"></script>
      <script  src="'.base_url().'assets/js/libs/jquery-ui-1.10.3.min.js"></script>
      <script  src="'.base_url().'assets/highcharts/js/highcharts.js"></script>
      <script>
      $(function() {

      Highcharts.chart("regresion_gestion", {
          chart: {
              type: "line",
               backgroundColor: "#f0f0f0",
              spacing: [40, 20, 15, 45],
              style: {
              fontFamily: "Segoe UI, Arial, sans-serif"
            }
          },
          title: {
              text: "CUMPLIMIENTO AL POA - GESTION '.$this->gestion.'",
              align: "center",
            style: {
              color: "#1e293b",
              fontSize: "18px",
              fontWeight: 600
            },
            margin: 30
          },
          xAxis: {
              categories: ["","I TRIMESTRE","II TRIMESTRE","III TRIMESTRE","IV TRIMESTRE"],
              title: {
              text: "(%) CUMPLIMIENTO DE ACTIVIDADES TRIMESTRAL RESPECTO A LA GESTION",
              style: {
                color: "#475569",
                fontSize: "12px"
              }
            },
            gridLineWidth: 1,
            gridLineColor: "#f1f5f9",
            labels: {
              style: {
                color: "#64748b",
                fontWeight: 500
              }
            }
          },
          yAxis: {
              title: {
              text: "(%) de cumplimiento a alcanzar",
              style: {
                color: "#475569",
                fontSize: "12px"
              }
            },
            labels: {
              format: "{value}%",  // Agregar símbolo %
              style: {
                color: "#64748b"
              }
            },
            gridLineColor: "#f1f5f9"
          },
          tooltip: {
            useHTML: true,
            backgroundColor: "#ffffff",
            borderWidth: 0,
            shadow: {
              color: "rgba(0,0,0,0.1)",
              width: 3,
              offsetX: 2,
              offsetY: 2
            },
          },
          plotOptions: {
            line: {
              dataLabels: {
                enabled: true,
                format: "{y}%",  // Agregar símbolo %
                style: {
                  color: "#1e293b",
                  fontSize: "12px",
                  textOutline: "none"
                },
                align: "center",
                y: -10  // Posición vertical
              },
              marker: {
                symbol: "circle",
                radius: 6,
                fillColor: "#ffffff",
                lineWidth: 2,
                lineColor: null  // Hereda color de serie
              },
              animation: {
                duration: 1000
              }
            }
          },
          series: [{
              name: "(%) PROGRAMACIÓN AL TRIMESTRE",
              data: [0,'.$matriz_gestion[4][1].','.$matriz_gestion[4][2].','.$matriz_gestion[4][3].','.$matriz_gestion[4][4].'],
               color: "#3b82f6",
            marker: {
              lineColor: "#3b82f6"
            },
            lineWidth: 3
          }, {
              name: "(%) CUMPLIMIENTO AL TRIMESTRE",
              data: [0,'.$matriz_gestion[5][1].','.$matriz_gestion[5][2].','.$matriz_gestion[5][3].','.$matriz_gestion[5][4].'],
               color: "#10b981",
            marker: {
              lineColor: "#10b981"
            },
            lineWidth: 3
          }],
          legend: {
            align: "right",
            verticalAlign: "top",
            itemStyle: {
              color: "#475569",
              fontWeight: 500
            },
            itemMarginBottom: 15
          },
          credits: {
            enabled: false
          },
          responsive: {
            rules: [{
              condition: {
                maxWidth: 768
              },
              chartOptions: {
                title: {
                  style: { fontSize: "18px" }
                },
                dataLabels: {
                  style: { fontSize: "10px" }
                }
              }
            }]
          }
      });

      });
      </script>';

      return $tabla;
    }


/// grafico Cumplimiento POA PASTEL
    public function grafico_cumplimiento_poa_pastel($matriz){
      $tabla='      
      <script  src="'.base_url().'assets/js/libs/jquery-2.0.2.min.js"></script>
      <script  src="'.base_url().'assets/js/libs/jquery-ui-1.10.3.min.js"></script>
      <script  src="'.base_url().'assets/highcharts/js/highcharts.js"></script>
      <script>
      $(function() {
      Highcharts.chart("pastel_todos", {
        chart: {
          type: "pie",
          backgroundColor: "#f0f0f0", // Fondo plomo claro
          spacing: [30, 10, 15, 10], // Espaciado superior aumentado
          options3d: { enabled: true } // Deshabilitar 3D
        },
        title: {
            text: "DETALLE CUMPLIMIENTO POA (Trimestre)",
            align: "center",
            verticalAlign: "top",
            margin: 10,
            style: {
              color: "#333333",
              fontSize: "24px",
              fontWeight: "600",
              fontFamily: "Arial, sans-serif",
              textTransform: "uppercase"
            },
            y: 10 // Posición vertical ajustada
        },
        tooltip: {
          useHTML: true,
          backgroundColor: "#ffffff",
          borderWidth: 0,
          borderRadius: 8,
          shadow: {
            color: "rgba(0,0,0,0.1)",
            width: 5,
            offsetX: 2,
            offsetY: 2
          },
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: "pointer",
            innerSize: "39%", // Efecto donut moderno
            dataLabels: {
              enabled: true,
              format: "<b>{point.name}</b>",
              style: {
                color: "#2d3748",
                fontSize: "12px",
                fontWeight: "500",
                textOutline: "none"
              },
              distance: 20,
              connectorWidth: 1,
              connectorColor: "#cbd5e0"
            },
            borderWidth: 2,
            borderColor: "#ffffff" // Borde blanco entre secciones
          }
        },
        series: [{
          type: "pie",
          name: "Actividades",
          data: [
              {
                name: "NO CUMPLIDO : '.(100-$matriz[5][$this->tmes]-$matriz[8][$this->tmes]).' %",
                y: '.(100-$matriz[5][$this->tmes]-$matriz[8][$this->tmes]).',
                color: "#ef4444", // Rojo mejorado
                className: "slice-emergencia"
              },

              {
                name: "EN PROCESO : '.$matriz[8][$this->tmes].' %",
                y: '.$matriz[8][$this->tmes].',
                color: "#f59e0b",
              },

              {
                name: "CUMPLIDO : '.$matriz[5][$this->tmes].' %",
                y: '.$matriz[5][$this->tmes].',
                color: "#10b981",
                sliced: true,
                selected: true
              }
          ]
        }],
            responsive: {
            rules: [{
              condition: {
                maxWidth: 600
              },
              chartOptions: {
                title: {
                  style: { fontSize: "18px" },
                  margin: 20,
                  y: 10
                },
                plotOptions: {
                  pie: {
                    dataLabels: {
                      distance: 15,
                      style: { fontSize: "10px" }
                    }
                  }
                }
              }
            }]
          }
      });
      });
      </script>';

      return $tabla;
    }

  /// grafico Regresion Cumplimiento POA
    public function grafico_cumplimiento_poa($titulo,$prog,$ejec,$tp){
      $tabla='      
      <script  src="'.base_url().'assets/js/libs/jquery-2.0.2.min.js"></script>
      <script  src="'.base_url().'assets/js/libs/jquery-ui-1.10.3.min.js"></script>
      <script  src="'.base_url().'assets/highcharts/js/highcharts.js"></script>
      <script>
      $(function() {

      Highcharts.chart("regresion", {
          chart: {
              type: "line",
               backgroundColor: "#f0f0f0",
              spacing: [40, 20, 15, 45],
              style: {
              fontFamily: "Segoe UI, Arial, sans-serif"
            }
          },
          title: {
              text: "CUMPLIMIENTO DE ACTIVIDADES AL TRIMESTRE",
              align: "center",
            style: {
              color: "#1e293b",
              fontSize: "18px",
              fontWeight: 600
            },
            margin: 30
          },
          xAxis: {
              categories: '.json_encode($titulo).',
              title: {
              text: "Periodos de Evaluación",
              style: {
                color: "#475569",
                fontSize: "12px"
              }
            },
            gridLineWidth: 1,
            gridLineColor: "#f1f5f9",
            labels: {
              style: {
                color: "#64748b",
                fontWeight: 500
              }
            }
          },
          yAxis: {
              title: {
              text: "Nro de Actividades",
              style: {
                color: "#475569",
                fontSize: "12px"
              }
            },
            labels: {
              style: {
                color: "#64748b"
              }
            },
            gridLineColor: "#f8fafc"
          },
          tooltip: {
            useHTML: true,
            backgroundColor: "#ffffff",
            borderWidth: 0,
            shadow: {
              color: "rgba(0,0,0,0.08)",
              width: 3,
              offsetX: 2,
              offsetY: 2
            },
          },
          plotOptions: {
            line: {
              dataLabels: {
                enabled: true,
                style: {
                  color: "#1e293b",
                  fontSize: "12px",
                  textOutline: "none"
                },
                formatter: function() {
                  return this.y + (this.y > 0 ? " act." : "");
                }
              },
              marker: {
                symbol: "circle",
                radius: 6,
                fillColor: "#ffffff",
                lineWidth: 2
              },
              animation: {
                duration: 800
              }
            }
          },
          series: [{
              name: "NRO. ACT. PROGRAMADO AL TRIMESTRE",
              data: '.json_encode($prog).',
               color: "#3b82f6",
            marker: {
              lineColor: "#3b82f6"
            },
            lineWidth: 3
          }, {
              name: "NRO. ACT. CUMPLIDAS AL TRIMESTRE",
              data: '.json_encode($ejec).',
               color: "#10b981",
            marker: {
              lineColor: "#10b981"
            },
            lineWidth: 3
          }],
          legend: {
            align: "right",
            verticalAlign: "top",
            itemStyle: {
              color: "#475569",
              fontWeight: 500
            },
            itemMarginBottom: 15
          },
          credits: {
            enabled: false
          },
          responsive: {
            rules: [{
              condition: {
                maxWidth: 768
              },
              chartOptions: {
                title: {
                  style: { fontSize: "18px" }
                },
                dataLabels: {
                  style: { fontSize: "10px" }
                }
              }
            }]
          }
      });

      });
      </script>';

      return $tabla;
    }



























      /// formulario anterior
      public function iframe_evaluacion_poa_ANTERIOR($dep_id,$dist_id,$tp_id){
        $data['trimestre']=$this->trimestre;

        if($dep_id==0){ /// INSTITUCIONAL
        
        $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_nacional(); /// Tabla para el grafico al trimestre
        $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_nacional_total(); /// Tabla para el grafico Total Gestion
        
        $data['boton1']='CARGAR % CUMPLIMIENTO POR REGIONAL';
        $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';

        $data['titulo']=
          '<h2><b>CONSOLIDADO INSTITUCIONAL - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

        $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',1);
        $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',1);
        $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',3);
        $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(2,'',4);
        $data['matriz_parametro_unidad']=$this->evaluacionpoa->matriz_eficacia_institucional();
        $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_INSTITUCIONAL';

        }
        elseif($dep_id!=0 & $dist_id==0){ /// REGIONAL
          $data['departamento']=$this->model_proyecto->get_departamento($dep_id);

          $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_regional($dep_id); /// Tabla para el grafico al trimestre
          $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_regional_total($dep_id); /// Tabla para el grafico Total Gestion
          
          $data['boton1']='VER DETALLE DE CUMPLIMIENTO POR UNIDADES';
          $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';
          $data['titulo']=
            '<h2><b>CONSOLIDADO REGIONAL '.strtoupper($data['departamento'][0]['dep_departamento']).' - '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

          $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
          $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],1);
          $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],3);
          $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(0,$data['departamento'],4);
          $data['matriz_parametro_unidad']=$this->evaluacionpoa->matriz_eficacia_regional($dep_id); /// matriz de parametros
          $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['departamento'][0]['dep_departamento']);

        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// DISTRITAL
          $data['distrital']=$this->model_proyecto->dep_dist($dist_id);

          $data['tabla']=$this->evaluacionpoa->tabla_regresion_lineal_distrital($dist_id); /// Tabla para el grafico al trimestre
          $data['tabla_gestion']=$this->evaluacionpoa->tabla_regresion_lineal_distrital_total($dist_id); /// Tabla para el grafico Total Gestion
          $data['boton1']='VER DETALLE DE CUMPLIMIENTO POR UNIDADES';
          $data['boton2']='CARGAR % CUMPLIMIENTO POR PROGRAMAS';
          $data['titulo']=
            '<h2>'.strtoupper($data['distrital'][0]['dist_distrital']).' <b>- '.$data['trimestre'][0]['trm_descripcion'].' / '.$this->gestion.'</b></h2>';

          $data['cabecera_regresion']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
          $data['cabecera_pastel']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],1);
          $data['cabecera_regresion_total']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],3);
          $data['cabecera_eficacia']=$this->evaluacionpoa->cabecera_evaluacionpoa(1,$data['distrital'],4);
          $data['matriz_parametro_unidad']=$this->evaluacionpoa->matriz_eficacia_distrital($dist_id); /// matriz de parametros
          $tit='EVAL_'.$data['trimestre'][0]['trm_descripcion'].'_'.strtoupper($data['distrital'][0]['dist_distrital']);

        }

        $data['tabla_regresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,1); /// Tabla que muestra el acumulado por trimestres Regresion
        $data['tabla_regresion_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],2,0); /// Tabla que muestra el acumulado por trimestres Regresion
          
        $data['tabla_regresion_total']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,1); /// Tabla que muestra el acumulado Gestion 
        $data['tabla_regresion_total_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla_gestion'],3,0); /// Tabla que muestra el acumulado Gestion Impresion
          
        $data['tabla_pastel_todo']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,1); /// Tabla que muestra el acumulado por trimestres Pastel todo
        $data['tabla_pastel_todo_impresion']=$this->evaluacionpoa->tabla_acumulada_evaluacion_regional_distrital($data['tabla'],4,0); /// Tabla que muestra el acumulado por trimestres Pastel todo Impresion

        $data['boton_parametros_unidad']='
              <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_unidad/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
              <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

        $data['boton_parametros_prog']='
              <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_programa/'.$dep_id.'/'.$dist_id.'/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
              <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></a>';

        $data['base']='
        <input name="base" type="hidden" value="'.base_url().'">
        <input name="tabla2" type="hidden" value="'.$data['tabla'][2][$this->session->userData('trimestre')].'">
        <input name="tabla3" type="hidden" value="'.$data['tabla'][3][$this->session->userData('trimestre')].'">
        <input name="tabla4" type="hidden" value="'.$data['tabla'][4][$this->session->userData('trimestre')].'">
        <input name="tabla5" type="hidden" value="'.$data['tabla'][5][$this->session->userData('trimestre')].'">
        <input name="tabla6" type="hidden" value="'.$data['tabla'][6][$this->session->userData('trimestre')].'">
        <input name="tabla7" type="hidden" value="'.$data['tabla'][7][$this->session->userData('trimestre')].'">
        <input name="tabla8" type="hidden" value="'.$data['tabla'][8][$this->session->userData('trimestre')].'">

        <input name="tit" type="hidden" value="'.$tit.'">
        <input name="dep_id" type="hidden" value="'.$dep_id.'">
        <input name="dist_id" type="hidden" value="'.$dist_id.'">
        <input name="tp_id" type="hidden" value="'.$tp_id.'">';
        $data['calificacion']=$this->evaluacionpoa->calificacion_eficacia($data['tabla'][5][$this->tmes]); /// Parametros de Eficacia
        $data['parametro_eficacia']=$this->evaluacionpoa->parametros_eficacia($data['matriz_parametro_unidad'],1);
        
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_grafico_eval_consolidado_regional_distrital', $data);
    }


    /*-- GET CUADRO DE EFICIENCIA Y EFICACIA por UNIDAD NACIONA, REGIONAL, DISTRITAL --*/
    public function get_unidades_eficiencia(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']); // dep id
        $dist_id = $this->security->xss_clean($post['dist_id']); // dist id
        $tp_id = $this->security->xss_clean($post['tp_id']); /// tipo id
        
        $matriz='No encontrado !!';
        $tabla='No encontrado !!';

        if($dep_id==0){ /// Institucional
         // $matriz=$this->evaluacionpoa->matriz_eficacia_institucional();
          $tabla=$this->evaluacionpoa->eficacia_regionales();
        }
        elseif($dep_id!=0 & $dist_id==0){ /// Regional
         // $matriz=$this->evaluacionpoa->matriz_eficacia_regional($dep_id); /// matriz de parametros
          $tabla=$this->evaluacionpoa->unidades_dist_reg(0,$dep_id,$tp_id); //// Lista de Unidades - Regional
        }
        elseif ($dep_id!=0 & $dist_id!=0) { /// Distrital
         // $matriz=$this->evaluacionpoa->matriz_eficacia_distrital($dist_id); /// matriz de parametros
          $tabla=$this->evaluacionpoa->unidades_dist_reg(1,$dist_id,$tp_id); //// Lista de Unidades - Distrital
        }

       // $parametro_eficacia=$this->evaluacionpoa->parametros_eficacia($matriz,1);

        $result = array(
          'respuesta' => 'correcto',
          'tabla'=>$tabla,
         // 'parametro_eficacia'=>$parametro_eficacia,
        );
          
        echo json_encode($result);
      }else{
          show_404();
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

    /*
    /*================================= GENERAR MENU ====================================*/
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
    /*--------------------------------------------------------------------------------*/
}