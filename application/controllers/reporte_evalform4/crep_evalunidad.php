<?php
class Crep_evalunidad extends CI_Controller {  
    public function __construct (){
      parent::__construct();
      if($this->session->userdata('fun_id')!=null){
        $this->load->model('Users_model','',true);
        $this->load->model('menu_modelo');
        $this->load->model('programacion/model_proyecto');
        $this->load->model('programacion/model_faseetapa');
        $this->load->model('programacion/model_producto');
        $this->load->model('programacion/model_componente');
        $this->load->model('mantenimiento/model_ptto_sigep');
        $this->load->model('ejecucion/model_evaluacion');
        $this->load->model('ejecucion/model_certificacion');
        $this->load->model('ejecucion/model_seguimientopoa');

        $this->load->model('reporte_eval/model_evalunidad'); /// Model Evaluacion Unidad

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
        $this->verif_mes=$this->session->userdata('mes_actual');
        $this->mes = $this->mes_nombre();
        $this->load->library('seguimientopoa');
      }
      else{
          redirect('/','refresh');
      }
    }


    // Modulo Evaluacion POA
    public function evaluacion_poa_unidad($proy_id){
      $proyecto = $this->model_proyecto->get_id_proyecto($proy_id);
      if(count($proyecto)!=0){
        redirect('eval/eval_unidad_gcorriente/'.$proy_id.'');
/*        if($proyecto[0]['tp_id']==1){ //// Proyecto de Inversion
          redirect('eval/eval_unidad_pinversion/'.$proy_id.'');
        }
        else{ //// Gasto Corriente
          redirect('eval/eval_unidad_gcorriente/'.$proy_id.'');
        }*/
      }
      else{
        echo "Error !!!";
      }
    }


    // Modulo Evaluacion POA - Gasto Corriente
    public function evaluacion_unidad_gcorriente($proy_id){
      $data['proyecto'] = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
      if(count($data['proyecto'])!=0){
        $data['menu']=$this->menu(4); //// genera menu  
        $data['tmes']=$this->model_evaluacion->trimestre(); /// Datos del Trimestre

        $data['tit_menu']='EVALUACI&oacute;N POA';
        $data['tit']='<li>Evaluaci&oacute;n POA</li><li>formulario N° 4</li>';
        
        /*------ titulo ------*/
        $data['titulo']='';
        $data['titulo'].='
          <h1 title='.$data['proyecto'][0]['aper_id'].'><small>PROGRAMA : </small><b>'.$data['proyecto'][0]['aper_programa'].''.$data['proyecto'][0]['aper_proyecto'].''.$data['proyecto'][0]['aper_actividad'].' - '.$data['proyecto'][0]['tipo'].' '.$data['proyecto'][0]['act_descripcion'].' - '.$data['proyecto'][0]['abrev'].'</b></h1>
          <h2><b>EVALUACI&Oacute;N POA AL '.$data['tmes'][0]['trm_descripcion'].'</b></h2>
          <input name="base" type="hidden" value="'.base_url().'">';
        /*-------------------*/
        

        /*--- Regresion lineal trimestral ---*/
        $matriz=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
        $matriz_gestion=$this->tabla_regresion_lineal_unidad_total($proy_id); /// Matriz para el grafico Total Gestion

        //// ----------------------------------------------------------------
        $titulo = [];
        for ($i = 0; $i <= $this->tmes; $i++) {$titulo[] = $matriz[1][$i];}
        $programacion = [];
        for ($i = 1; $i <= $this->tmes; $i++) {$programacion[] = (int)$matriz[2][$i];}
        $ejecucion = [];
        for ($i = 1; $i <= $this->tmes; $i++) { $ejecucion[] = (int)$matriz[3][$i];}

        /// Tabla Seguimiento acumulado
        $tabla_regresion=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz,$this->tmes,2,0); /// Tabla que muestra el acumulado al trimestres Regresion
        $tabla_pastel=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz,$this->tmes,4,1); /// Tabla Torta
        $tabla_regresion_gestion=$this->seguimientopoa->tabla_acumulada_evaluacion_servicio($matriz_gestion,$this->tmes,3,0); /// Tabla que muestra el cumplimiento Anual


        $data['calificacion']='<div id="calificacion">'.$this->seguimientopoa->calificacion_eficacia($matriz[5][$this->tmes],0).'</div><div id="efi"></div>'; /// calificacion

        /// SERVICIOS
        $mis_unidades=$this->mis_servicios(1,$proy_id); /// Lista de Unidades Responsables
        $matriz_parametros=$this->matriz_eficacia_unidad($proy_id); /// matriz para parametros de cumplimiento
        $parametro_eficacia=$this->parametros_eficacia_unidad($matriz_parametros); /// cuadro Parametro de Eficacia


      $data['s1']=' 
      '.$this->grafico_cumplimiento_poa($titulo,$programacion,$ejecucion,0).'
      '.$this->grafico_cumplimiento_poa_pastel($matriz).'
      '.$this->grafico_cumplimiento_poa_anual($matriz_gestion).'
      '.$this->grafico_cumplimiento_poa_pastel_parametros($matriz_parametros).'

        <div id="cabecera" style="display: none">
          '.$this->seguimientopoa->cabecera_grafico_programa($data['proyecto']).'
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">  
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
              <div class="row" style="align:center">
                <div id="regresion" style="width: 650px; height: 420px; margin: 0 auto"></div>
                <div id="tabla_regresion_impresion">'.$tabla_regresion.'</div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
              <div class="row" style="align:center">
                <div id="pastel_todos" style="width: 600px; height: 420px; margin: 0 auto"></div>
                <div id="tabla_pastel_vista">'.$tabla_pastel.'</div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
              <div class="row" style="align:center">
                <div id="regresion_gestion" style="width: 650px; height: 420px; margin: 0 auto"></div>
                <div id="tabla_regresion_total_impresion">'.$tabla_regresion_gestion.'</div>
              </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
          <div class="row" style="align:center">
            <hr><center><button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/> <b>IMPRIMIR / GUARDAR</b></button></center><hr>
          </div>
        </div>';

        $data['s2']='
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
        </div>';

        $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/rep_unidad', $data);
      }
      else{
        redirect('eval/mis_operaciones');
      }
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
              data: [0,'.json_encode($prog).'],
               color: "#3b82f6",
            marker: {
              lineColor: "#3b82f6"
            },
            lineWidth: 3
          }, {
              name: "NRO. ACT. CUMPLIDAS AL TRIMESTRE",
              data: [0,'.json_encode($ejec).'],
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






    /*--- REPORTE EVALUACION POR UNIDAD 2021---*/
    public function reporte_indicadores_unidad($proy_id){
      $proyecto=$this->model_proyecto->get_id_proyecto($proy_id);
      $nombre_proyecto=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' 000 - '.$proyecto[0]['proy_nombre'];
      
      if($proyecto[0]['tp_id']==4){
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $nombre_proyecto=$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'];
      }

      $tabla=$this->tabla_regresion_lineal_unidad($proy_id); /// Tabla para el grafico al trimestre
      $data['cabecera']=$this->cabecera_eficiencia($nombre_proyecto);
      $data['pie']='<hr>&nbsp;&nbsp;&nbsp;&nbsp;'.$this->session->userData('sistema').'';
      $data['lista']=$this->mis_servicios(0,$proy_id);
      $data['eficacia']=$tabla[5][$this->tmes];
      $data['economia']=$this->economia($proyecto); /// Economia
      //$data['eficiencia']='';
      //$data['eficiencia']=$this->eficiencia($tabla[5][$this->tmes],$data['economia'][3]); /// Eficiencia
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes); /// Datos del Trimestre
     
      $this->load->view('admin/reportes_cns/repevaluacion_institucional_poa/reporte_evaluacion_eficiencia_por_unidad', $data);
    }


    /// Cabecera Reporte de eficiencia
    function cabecera_eficiencia($nombre_proyecto){
      $tabla='';
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
    
      $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:45%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                  </tr>
                  <tr>
                      <td style="width:50%;font-size: 7px;">&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
            <td style="width:80%;height: 5%;font-family: Arial;font-size: 20px;">
              <b>DETALLE DE AVANCE DE EVALUACI&Oacute;N POA</b><br>
              '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'
            </td>
            <td style="width:10%; text-align:center;">
            </td>
          </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
            <td style="width:1%;"></td>
            <td style="width:98%;height: 2%;">
              <div style="font-family: Arial;font-size: 13px;">'.$nombre_proyecto.'</div>
            </td>
            <td style="width:1%;"></td>
          </tr>
        </table>
        <hr>';

      return $tabla;
    }

    /*------- CABECERA REPORTE SEGUIMIENTO POA (GRAFICO)------*/
/*    function cabecera_seguimiento($proyecto,$tipo_titulo){
      $trimestre=$this->model_evaluacion->get_trimestre($this->tmes);
      /// tipo_titulo 1 : Seguimiento Mensual
      /// tipo_titulo 2 : Evaluacion por Trimestre
      /// tipo_titulo 3 : Evaluacion POA Gestion
      
      $nombre_proyecto=$proyecto[0]['aper_programa'].' '.$proyecto[0]['proy_sisin'].' '.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];
      if($proyecto[0]['tp_id']==4){
        $nombre_proyecto=$proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'];
      }

      $tit='';
      if($tipo_titulo==2){
        $tit='<td style="height: 35px;font-size: 18px;"><center><b>EVALUACIÓN POA ACUMULADO</b> '.$trimestre[0]['trm_descripcion'].' / '.$this->gestion.'</center></td>';
      }
      elseif($tipo_titulo==3){
        $tit='<td style="height: 35px;font-size: 23px;"><center><b>EVALUACI&Oacute;N POA - GESTI&Oacute;N '.$this->gestion.'</b></center></td>';
      }

      $tabla='';
      $tabla.='
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr style="border: solid 0px;">              
            <td style="width:70%;height: 2%">
              <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr style="font-size: 10px;font-family: Arial;">
                      <td style="width:45%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$this->session->userData('entidad').'</b></td>
                  </tr>
                  <tr>
                      <td style="width:50%;font-size: 7px;">&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
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
                <td style="width:80%;">
                    <table align="center" border="0" style="width:100%;">
                        <tr style="font-family: Arial;">
                            '.$tit.'
                        </tr>
                    </table>
                </td>
                <td style="width:10%; text-align:center;">
                </td>
            </tr>
        </table>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
          <tr>
            <td style="width:100%;height: 100%;">
              <div style="font-family: Arial;font-size: 15px;">'.$nombre_proyecto.'</div>
            </td>
          </tr>
        </table>';

      return $tabla;
    }*/

      


    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE 2021 POR UNIDAD -----*/
    public function update_evaluacion_trimestral(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $proy_id = $this->security->xss_clean($post['proy_id']);
        $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id);
        $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        
        $componentes=$this->model_componente->lista_subactividad($proy_id);
        foreach($componentes as $rowc){
          $this->seguimientopoa->update_evaluacion_operaciones($rowc['com_id']);
        }
        
        $tabla='';
        $tabla.='
              <hr><h3><b>&nbsp;&nbsp;'.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' '.$proyecto[0]['abrev'].'</b></h3><hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> EVALUACI&Oacute;N POA '.$trimestre[0]['trm_descripcion'].' '.$this->gestion.' ACTUALIZADO !!!</2> 
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


    /*---- FUNCION ACTUALIZA INFORMACION EVALUACION POA AL TRIMESTRE 2021 INSTITUCIONAL -----*/
    public function update_evaluacion_trimestral_institucional(){
      if($this->input->is_ajax_request() && $this->input->post()){
        $post = $this->input->post();
        $dep_id = $this->security->xss_clean($post['dep_id']);
        $tp_id = $this->security->xss_clean($post['tp_id']);
        $trimestre=$this->model_evaluacion->trimestre(); /// Datos del Trimestre
        $this->seguimientopoa->update_evaluacion_poa_regional($dep_id,$tp_id);
        
        $tabla='';
        $tabla.='
              <hr>
              <div class="alert alert-success alert-block" align=center>
                <h2> EVALUACI&Oacute;N POA '.$trimestre[0]['trm_descripcion'].' '.$this->gestion.' ACTUALIZADO !!!</2> 
              </div>
              <hr>
              <p>
                <div id="butt" align="right">
                  <a href="'.site_url("").'/menu_eval_poa" class="btn btn-default" title="SALIR">
                  <img src="'.base_url().'assets/Iconos/cancel.png" WIDTH="25" HEIGHT="25"/> SALIR</a>

                  <a href="javascript:abreVentana_eficiencia(\''.site_url("").'/rep_indicadores_unidad/'.$dep_id.'/0/'.$tp_id.'\');" class="btn btn-default" title="IMPRIMIR CUADRO DE PARAMETROS POR PROGRAMAS">
                  <img src="'.base_url().'assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/> VER CUMPLIMIENTO POR UNIDADES</a>
                </div>
              </p>';

          $result = array(
            'respuesta' => 'correcto',
            'tabla'=>$tabla,
          );

        echo json_encode($result);
      }else{
          show_404();
      }
    }


 public function reporte_indicadores_unidadd($proy_id){
   $tp_id = 4;
        $regionales=$this->model_proyecto->list_departamentos();
        echo count($regionales)."<br>";
        foreach($regionales as $row){
          $this->seguimientopoa->update_evaluacion_poa_regional($row['dep_id'],$tp_id);
          echo $row['dep_id'].'--'.$row['dep_departamento'].'<br>';
        }

       /* $unidades=$this->model_seguimientopoa->list_poa_gacorriente_pinversion_regional(7,4);
      foreach($unidades as $row){
        $componentes=$this->model_componente->lista_subactividad($row['proy_id']);
        foreach($componentes as $rowc){
          $this->seguimientopoa->update_evaluacion_operaciones($rowc['com_id']);
        }
        echo $row['proy_id'].'--'.$row['actividad']."<br>";
      }*/
 }




    /*---- matriz parametros de eficacia Unidad ----*/
    public function matriz_eficacia_unidad($proy_id){
      $componentes=$this->model_componente->proyecto_componente($proy_id); 
      
      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][1]=$i;
        $par[$i][2]=0;
        $par[$i][3]=0;
      }

      $nro_1=0;$nro_2=0;$nro_3=0;$nro_4=0;
      foreach($componentes as $rowc){
        $eval=$this->tabla_regresion_lineal_servicio($rowc['com_id']);
        $eficacia=$eval[5][$this->tmes];
          if($eficacia<=50){$par[1][2]++;} /// Insatisfactorio - Rojo (1)
          if($eficacia > 50 & $eficacia <= 75){$par[2][2]++;} /// Regular - Amarillo (2)
          if($eficacia > 75 & $eficacia <= 99){$par[3][2]++;} /// Bueno - Azul (3)
          if($eficacia > 99 & $eficacia <= 100){$par[4][2]++;} /// Optimo - verde (4)
      }

      for ($i=1; $i <=4 ; $i++) { 
        $par[$i][3]=round((($par[$i][2]/count($componentes))*100),2);
      }

      return $par;
    }

    /*----- Parametros de Eficacia Concolidado por Unidad -----*/
    public function parametros_eficacia_unidad($matriz){
      $insatisfactorio='0% a 50%';
      $regular='51% a 75%';
      $bueno='76% a 99%';

      $tabla='';
      $tabla .='
            <div id="parametro_efi" style="width: 600px; height: 400px; margin: 0 auto"></div>
            <hr>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 33%"><center><b>TIPO DE CALIFICACI&Oacute;N</b></center></th>
                  <th style="width: 33%"><center><b>PARAMETRO</b></center></th>
                  <th style="width: 33%"><center><b>NRO DE UNIDADES</b></center></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>INSATISFACTORIO</td>
                  <td>'.$insatisfactorio.'</td>
                  <td align="center" ><a class="btn btn-danger" style="width: 100%" title="'.$matriz[1][2].' Unidades/Proyectos">'.$matriz[1][2].'</a></td>
                </tr>
                <tr>
                  <td>REGULAR</td>
                  <td>'.$regular.'</td>
                  <td align="center" ><a class="btn btn-warning" style="width: 100%" align="center" title="'.$matriz[2][2].' Unidades/Proyectos">'.$matriz[2][2].'</a></td>
                </tr>
                <tr>
                  <td>BUENO</td>
                  <td>'.$bueno.'</td>
                  <td align="center" ><a class="btn btn-info" style="width: 100%" align="center" title="'.$matriz[3][2].' Unidades/Proyectos">'.$matriz[3][2].'</a></td>
                </tr>
                <tr>
                  <td>OPTIMO </td>
                  <td>100%</td>
                  <td align="center" ><a class="btn btn-success" style="width: 100%" align="center" title="'.$matriz[4][2].' Unidades/Proyectos">'.$matriz[4][2].'</a></td>
                </tr>
                <tr>
                  <td colspan=2 align="center"><b>TOTAL UNIDADES : </b></td>
                  <td align="center"><b>'.($matriz[1][2]+$matriz[2][2]+$matriz[3][2]+$matriz[4][2]).'</b></td>
                </tr>
              </tbody>
            </table>';

      return $tabla;
    }



    
    /*------ eficiencia ------*/
/*    public function eficiencia($eficacia,$economia){
      $eficiencia=0;
      if($eficacia!=0){
        $eficiencia= round(($economia/$eficacia),2);
      }

      return $eficiencia;
    }*/

    /*------ Economia ------*/
    public function economia($proyecto){
      $suma_grupo_partida=$this->model_certificacion->suma_grupo_partida_programado($proyecto[0]['aper_id'],10000); /// suma de Partidas por defecto al trimeste actual
      $monto_grupo_partida=0;
      if(count($suma_grupo_partida)!=0){
        $monto_grupo_partida=$suma_grupo_partida[0]['suma_partida'];
      }

      $suma_ppto_certificado=$this->model_certificacion->suma_monto_certificado_unidad($proyecto[0]['proy_id']); //// Presupuesto Certificado al trimestre vigente
      $monto_ppto_certificado=0;
      if(count($suma_ppto_certificado)!=0){
        $monto_ppto_certificado=$suma_ppto_certificado[0]['ppto_certificado'];
      }

      $suma_ppto_asignado=$this->model_certificacion->monto_total_programado_trimestre($proyecto[0]['aper_id']); //// Presupuesto Asignado POA por trimestre
      $monto_ppto_asignado=0;
      if(count($suma_ppto_asignado)!=0){
        $monto_ppto_asignado=$suma_ppto_asignado[0]['ppto_programado'];
      }

      
      $datos[1]=$monto_grupo_partida+$monto_ppto_certificado; /// Total Certificado
      $datos[2]=$monto_ppto_asignado; /// Total Asignado
      $datos[3]=0;
      if($datos[2]!=0){
        $datos[3]=round((($datos[1]/$datos[2])*100),2);
      }

      return $datos;
    }

    /*--------- Mis Servicios -------------*/
    public function mis_servicios($tp_rep,$proy_id){
      $proyecto = $this->model_proyecto->get_datos_proyecto_unidad($proy_id); 
      $componentes=$this->model_componente->lista_subactividad($proy_id);
      $tabla='';
      // 1 : normal, 2 : Impresion
      if($tp_rep==1){ /// Normal
        $tab='class="table table-bordered" align=center style="width:100%;"';
        $det='';
      } 
      else{ /// Impresion
        $tab='border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align=center';
        $det='
        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.- DETALLE (%) DE CUMPLIMIENTO DE UNIDADES DEPENDIENTES</b>
        </div>';
      }

      $tit='UNIDAD RESPONSABLE';
      $tabla.='
        '.$det.'
        <table '.$tab.'>
          <thead>
          <tr align=center bgcolor=#f4f4f4>
            <th style="width:3%;height:2%;">#</th>
            <th style="width:20%;">'.$tit.'</th>
            <th style="width:8%;">TOTAL PROG.</th>
            <th style="width:8%;">TOTAL EVAL.</th>
            <th style="width:8%;">TOTAL CUMP.</th>
            <th style="width:8%;">EN PROC.</th>
            <th style="width:8%;">NO CUMP.</th>
            <th style="width:8%;">% CUMPLIDO</th>
            <th style="width:8%;">% NO CUMPLIDO</th>
          </tr>
          </thead>
          <tbody>';
          $nro=0;
          foreach($componentes as $rowc){
            $eval=$this->tabla_regresion_lineal_servicio($rowc['com_id']);
            $nro++;
            $tabla.='<tr>';
              $tabla.='<td style="height:2%;" align=center title="'.$rowc['com_id'].'">'.$nro.'</td>';
              $tabla.='<td>'.$rowc['tipo_subactividad'].' '.$rowc['serv_descripcion'].'</td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[2][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[3][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.$eval[7][$this->tmes].'</b></td>';
              $tabla.='<td align=right><b>'.($eval[2][$this->tmes]-($eval[7][$this->tmes]+$eval[3][$this->tmes])).'</b></td>';
              if($tp_rep==1){
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-info"><b>'.$eval[5][$this->tmes].'%</b></button></td>';
                $tabla.='<td><button type="button" style="width:100%;" class="btn btn-danger"><b>'.$eval[6][$this->tmes].'%</b></button></td>';
              }
              else{
                $tabla.='<td align=right style="font-size: 8px;"><b>'.$eval[5][$this->tmes].'%</b></td>';
                $tabla.='<td align=right style="font-size: 8px;"><b>'.$eval[6][$this->tmes].'%</b></td>';
              }
         
            $tabla.='</tr>';
          }
        $tabla.='
          </tbody>
        </table>';
      return $tabla;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_servicio($com_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$this->tmes; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas
        $tr[5][$i]=0; /// eficacia %
        $tr[6][$i]=0; /// no eficacia %
        $tr[7][$i]=0; /// en proceso
        $tr[8][$i]=0; /// en proceso %
      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// eficacia
        }
        $tr[6][$i]=(100-$tr[5][$i]);
        $proceso=$this->obtiene_datos_evaluacíon_servicio($com_id,$i,2);
        $tr[7][$i]=$proceso[2]; /// En Proceso
        if($tr[2][$i]!=0){
          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
        
      }

    return $tr;
    }

    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 - UNIDAD RESPONSABLE -------*/
    public function obtiene_datos_evaluacíon_servicio($com_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evaluacion->nro_operaciones_programadas($com_id,$i);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evaluacion->list_operaciones_evaluadas_servicio_trimestre_tipo($com_id,$i,$tipo_evaluacion));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
    }


    /*--- REGRESIÓN LINEAL PORCENTAJE PROGRAMADO A LA GESTIÓN ---*/
    public function tabla_regresion_lineal_unidad_total($proy_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      $total=0;
      for ($i=1; $i <=4 ; $i++) {
        $programado=$this->model_evalunidad->nro_operaciones_programadas($proy_id,$i);
        if(count($programado)!=0){
          $total=$total+$programado[0]['total'];
        }
      }

      for ($i=0; $i <=4; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// % prog 
        $tr[5][$i]=0; /// % cump 
      }

      for ($i=1; $i <=4; $i++) {
        $valor=$this->obtiene_datos_evaluacíon($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        if($total!=0){
          $tr[4][$i]=round((($valor[1]/$total)*100),2); /// % Prog
          $tr[5][$i]=round((($valor[2]/$total)*100),2); /// % Cumplidas
        }
      }

    return $tr;
    }

    /*------ REGRESIÓN LINEAL PROG - CUMPLIDO 2020 ACUMULADO AL TRIMESTRE -------*/
    public function tabla_regresion_lineal_unidad($proy_id){
      $m[0]='';
      $m[1]='I TRIMESTRE.';
      $m[2]='II TRIMESTRE';
      $m[3]='III TRIMESTRE';
      $m[4]='IV TRIMESTRE';

      for ($i=0; $i <=$this->tmes; $i++){ 
        $tr[1][$i]=$m[$i]; /// Trimestre
        $tr[2][$i]=0; /// Prog
        $tr[3][$i]=0; /// cumplidas
        $tr[4][$i]=0; /// no cumplidas

        $tr[5][$i]=0; /// cumplimiento %
        $tr[6][$i]=0; /// no cumplimiento %
        $tr[7][$i]=0; /// en proceso
        $tr[8][$i]=0; /// en proceso %
      }

      for ($i=1; $i <=$this->tmes; $i++) {
        $valor=$this->obtiene_datos_evaluacíon($proy_id,$i,1);
        $tr[2][$i]=$valor[1]; /// Prog
        $tr[3][$i]=$valor[2]; /// cumplidas
        $tr[4][$i]=($valor[1]-$valor[2]); /// no cumplidas
        if($tr[2][$i]!=0){
          $tr[5][$i]=round((($tr[3][$i]/$tr[2][$i])*100),2); /// cumplimiento %
        }
        $tr[6][$i]=(100-$tr[5][$i]); /// No cumplidas %
        $proceso=$this->obtiene_datos_evaluacíon($proy_id,$i,2); 
        $tr[7][$i]=$proceso[2]; /// En Proceso
        if($tr[2][$i]!=0){
          $tr[8][$i]=round(($tr[7][$i]/$tr[2][$i])*100,2); // En proceso %
        }
      }

    return $tr;
    }


    /*------ OBTIENE DATOS DE EVALUACIÓN 2020 -------*/
    public function obtiene_datos_evaluacíon($proy_id,$trimestre,$tipo_evaluacion){
      $nro_ope_eval=0; $nro_cumplidas=0;
      for ($i=1; $i <=$trimestre; $i++) {
        $programadas=$this->model_evalunidad->nro_operaciones_programadas($proy_id,$i);
        if(count($programadas)!=0){
          $nro_ope_eval=$nro_ope_eval+$programadas[0]['total'];
        }

        if(count($this->model_evalunidad->list_operaciones_evaluadas_unidad_trimestre_tipo($proy_id,$i,$tipo_evaluacion))!=0){
          $nro_cumplidas=$nro_cumplidas+count($this->model_evalunidad->list_operaciones_evaluadas_unidad_trimestre_tipo($proy_id,$i,$tipo_evaluacion));
        }
      }

      $vtrimestre[1]=$nro_ope_eval; // nro evaluadas
      $vtrimestre[2]=$nro_cumplidas; // Cumplidas/Proceso/No Cumplidos

      return $vtrimestre;
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
    /*======================================================================================*/

}