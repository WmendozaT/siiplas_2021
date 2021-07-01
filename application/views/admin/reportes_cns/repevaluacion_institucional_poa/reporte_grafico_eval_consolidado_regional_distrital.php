<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title><?php echo $this->session->userData('sistema');?></title>
    </head>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-production.min.css"> 
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-skins.min.css">
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/demo.min.css">
    <!--estiloh-->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">

    <style type="text/css">
      table{font-size: 9.5px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
      }
      th{
        padding: 1.4px;
        text-align: center;
        font-size: 9.5px;
      }
    </style>
<body>

<div id="content">
<!-- widget grid -->
    <section id="widget-grid" class="">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <section id="widget-grid" class="well">
            <div class="">
              <?php echo $base; ?>
              <?php echo $titulo;?>
            </div>
          </section>
        </article>
        <div class="row">
            <article class="col-sm-12">
                <!-- new widget -->
                <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                        <h2>CUADRO DE EVALUACI&Oacute;N POA </h2>
                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N POA I (TRIMESTRAL)</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N POA II (TRIMESTRAL)</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N POA (GESTI&Oacute;N)</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s4"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet"><?php echo $titulo_indicador;?></span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s5"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">PARAMETROS DE EFICACIA</span></a>
                            </li>
                        </ul>
                    </header>

                    <!-- widget div-->
                    <div class="no-padding">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            test
                        </div>
                        <!-- end widget edit box -->
                        <div class="widget-body">
                            <!-- content -->
                            <div id="myTabContent" class="tab-content">
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                  <div id="eficacia"><?php echo $calificacion;?></div><div id="efi"></div>
                                </div>
                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="CUADRO DE EVALUACI&Oacute;N POA">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                      <div id="cabecera" style="display: none"><?php echo $cabecera_regresion;?></div>
                                        <hr>
                                        <table>
                                            <tr>
                                                <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE AVANCE EVALUACI&Oacute;N POA AL <?php echo $trimestre[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                                            </tr>
                                        </table>
                                        <hr>
                                        <div id="evaluacion_trimestre">
                                            <div id="chartContainer" style="width: 1000px; height: 390px; margin: 0 auto"></div>
                                        </div>

                                        <div id="evaluacion_trimestre">
                                            <div id="regresion" style="width: 600px; height: 390px; margin: 0 auto"></div>
                                        </div>
                                        <hr>
                                        <div class="table-responsive" id="tabla_regresion_vista">
                                            <?php echo $tabla_regresion;?>
                                        </div>
                                        <div id="tabla_regresion_impresion" style="display: none">
                                            <?php echo $tabla_regresion_impresion;?>
                                        </div>
                                        <hr>
                                        <div align="right">
                                            <button id="btnregresion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/camera.png" WIDTH="25" HEIGHT="25" title="CAPTURA DE PANTALLA"/></button>
                                            <button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="25" HEIGHT="25" title="IMPRIMIR GRAFICO"/></button>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                                <!-- end s1 tab pane -->

                                <div class="tab-pane fade" id="s2" title="CUADRO DE EVALUACI&Oacute;N POA">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                      <div id="cabecera1" style="display: none"><?php echo $cabecera_pastel;?></div>
                                        <hr>
                                        <table>
                                            <tr>
                                                <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DETALLE EVALUACI&Oacute;N POA AL <?php echo $trimestre[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                                            </tr>
                                        </table>
                                        
                                        <hr>
                                        <div id="pastel_canvas">
                                            <center><div id="pastel_todos" style="width: 600px; height: 420px; margin: 0 auto"></div></center>
                                        </div>
                                        
                                        <div id="evaluacion_pastel" style="display: none">
                                          <div id="pastel_todosprint" style="width: 600px; height: 420px; margin: 0 auto"></div>
                                        </div>
                                        <hr>
                                        <div class="table-responsive" id="tabla_pastel_vista">
                                            <?php echo $tabla_pastel_todo;?>
                                        </div>
                                        <div id="tabla_pastel_impresion" style="display: none">
                                            <?php echo $tabla_pastel_todo_impresion;?>
                                        </div>
                                        <hr>
                                        <div align="right">
                                            <button id="btnpastel" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/camera.png" WIDTH="25" HEIGHT="25" title="CAPTURA DE PANTALLA"/></button>
                                            <button id="btnImprimir_evaluacion_pastel" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>
                                        </div>
                                    </div>          
                                  </div>
                                </div>
                                <!-- end s2 tab pane -->
                                
                                <div class="tab-pane fade" id="s3" title="CUADRO EVALUACIÓN DE OPERACIONES">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                            <div id="cabecera2" style="display: none"><?php echo $cabecera_regresion_total;?></div>
                                            <hr>
                                            <table>
                                                <tr>
                                                    <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE EVALUACI&Oacute;N POA <?php echo $this->session->userData('gestion');?></b></td>
                                                </tr>
                                            </table>
                                            <hr>
                                            <div id="evaluacion_gestion">
                                              <div id="regresion_gestion" style="width: 700px; height: 400px; margin: 0 auto"></div>
                                            </div>
                                            <hr>
                                            <div class="table-responsive" id="tabla_regresion_total_vista">
                                                <?php echo $tabla_regresion_total;?>
                                            </div>
                                            <div id="tabla_regresion_total_impresion" style="display: none">
                                                <?php echo $tabla_regresion_total_impresion;?>
                                            </div>
                                          <hr>
                                            <div align="right">
                                                <button id="btnImprimir_evaluacion_gestion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (GESTIÓN)</b></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s4" title="CUADRO EVALUACIÓN DE OPERACIONES">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      
                                        <div align="left" id="boton_eficacia">
                                          <a href="#" class="btn btn-default eficacia" name="<?php echo $id;?>" id="<?php echo $tp;?>" title="CUADRO DE EFICIENCIA Y EFICACIA" style="width:35%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;<?php echo $boton;?></a>
                                        </div>

                                        <div align="right" id="print_eficacia" style="display: none">
                                          <a href="javascript:abreVentana_eficiencia('<?php echo site_url("").'/rep_eval_poa/rep_eficacia/'.$tp.'/'.$id.'' ?>');" class="btn btn-default" title="IMPRIMIR REPORTE DE MODIFICACION FINANCIERA"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE INDICADOR</a>
                                        </div>
                                    <br>

                                        <div class="row">
                                          <div id="lista"></div>
                                        </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="s5" title="CUADRO PARAMETRO DE EFICACIA">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <div id="cabecera2" style="display: none"><?php echo $cabecera_eficacia;?></div>
                                      <hr>
                                      <table>
                                          <tr>
                                              <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO PARAMETRO DE EFICACIA POA AL <?php echo $trimestre[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                                          </tr>
                                      </table>
                                      <hr>
                                      <?php echo $parametro_eficacia;?>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <!-- end content -->
                        </div>
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
        </div>
    </section>
</div>

<script>
if (!window.jQuery) {
    document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');
}
</script>
<script>
if (!window.jQuery.ui) {
    document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
}
</script>

<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>

<script src="<?php echo base_url(); ?>assets/captura/html2canvas.min.js"></script>
<script src="<?php echo base_url(); ?>assets/captura/canvasjs.min.js"></script>
<script src="<?php echo base_url(); ?>assets/captura/jsPdf.debug.js"></script>
<script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/reporte_evaluacionpoa.js"></script> 


 <!-- REGRESION LINEAL A LA GESTIÓN -->
  <script type="text/javascript">
    var chart1;
    $(document).ready(function() {
      chart1 = new Highcharts.Chart({
        chart: {
          renderTo: 'regresion_gestion',
          defaultSeriesType: 'line'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        xAxis: {
                  categories: ['<?php echo $tabla_gestion[1][0];?>', '<?php echo $tabla_gestion[1][1];?>', '<?php echo $tabla_gestion[1][2];?>', '<?php echo $tabla_gestion[1][3];?>', '<?php echo $tabla_gestion[1][4];?>']
              },
        yAxis: {
          title: {
            text: 'Promedio (%)'
          }
        },
        tooltip: {
          enabled: false,
          formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
              this.x +': '+ this.y +'%';
          }
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            },
            enableMouseTracking: false
          }
        },

          series: [
              {
                  name: '% PROGRAMADAS',
                  data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
              },
              {
                  name: '% CUMPLIDAS',
                  data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
              }
          ]
      });
    });
  </script>

  <!-- REGRESION LINEAL AL TRIMESTRE -->
  <script type="text/javascript">
    var chart1;
    $(document).ready(function() {
      chart1 = new Highcharts.Chart({
        chart: {
          renderTo: 'regresion',
          defaultSeriesType: 'line'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        <?php 
          if($this->session->userdata('trimestre')==1){ ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==2) { ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][2];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==3) { ?>
              xAxis: {
                  categories: ['p :<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==4) { ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>', '<?php echo $tabla[1][4];?>']
              },
              <?php
          }
        ?>
        yAxis: {
          title: {
            text: 'Promedio (%)'
          }
        },
        tooltip: {
          enabled: false,
          formatter: function() {
            return '<b>'+ this.series.name +'</b><br/>'+
              this.x +': '+ this.y +'%';
          }
        },
        plotOptions: {
          line: {
            dataLabels: {
              enabled: true
            },
            enableMouseTracking: false
          }
        },

          <?php 
              if($this->session->userdata('trimestre')==1){ ?>
                  series: [
                      {
                        name: 'OPE. PROGRAMADAS AL TRIMESTRE',
                        data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                      },
                      {
                        name: 'OPE. CUMPLIDAS AL TRIMESTRE',
                        data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                      }
                  ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==2) { ?>
                      series: [
                          {
                            name: 'OPE. PROGRAMADAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                          },
                          {
                            name: 'OPE. CUMPLIDAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                          }
                      ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==3) { ?>
                      series: [
                          {
                            name: 'OPE. PROGRAMADAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                          },
                          {
                            name: 'OPE. CUMPLIDAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                          }
                      ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==4) { ?>
                      series: [
                          {
                            name: 'OPE. PROGRAMADAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                          },
                          {
                            name: 'OPE. CUMPLIDAS AL TRIMESTRE',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                          }
                      ]
                  <?php
              }
          ?>
      });
    });
  </script>
    <script type="text/javascript">

  </script>
  <script type="text/javascript">
    $(document).ready(function() {  
       Highcharts.chart('parametro_efi', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: ''
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Unidades',
            data: [
                {
                  name: 'INSATISFACTORIO : <?php echo $matriz[1][3];?> %',
                  y: <?php echo $matriz[1][3];?>,
                  color: '#f95b4f',
                },

                {
                  name: 'REGULAR : <?php echo $matriz[2][3];?> %',
                  y: <?php echo $matriz[2][3];?>,
                  color: '#edd094',
                },

                {
                 name: 'BUENO : <?php echo $matriz[3][3];?> %',
                  y: <?php echo $matriz[3][3];?>,
                  color: '#afd5e5',
                },

                {
                  name: 'OPTIMO : <?php echo $matriz[4][3];?> %',
                  y: <?php echo $matriz[4][3];?>,
                  color: '#4caf50',
                  sliced: true,
                  selected: true
                }
            ]
        }]
      });
    });


</script>

</body>
</html>