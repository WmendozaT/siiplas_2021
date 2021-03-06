<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
        <title><?php echo $this->session->userdata('name')?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-production.min.css"> 
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-skins.min.css">
        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/demo.min.css">
        <!--estiloh-->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" /> -->
        <meta name="viewport" content="width=device-width">
        <style>
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
        </style>

    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
            <!-- pulled right: nav area -->
            <div class="pull-right">
                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->
                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Salir" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <!-- end logout button -->
                <!-- search mobile button (this is hidden till mobile view port) -->
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <!-- end search mobile button -->
                <!-- fullscreen button -->
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
                <!-- end fullscreen button -->
            </div>
            <!-- end pulled right: nav area -->
        </header>
        <!-- END HEADER -->
        <!-- Left panel : Navigation area -->
        <?php echo $menu;?>

        <!-- MAIN PANEL -->
        <div id="main" role="main">
            <!-- RIBBON -->
            <div id="ribbon">
                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
                        <i class="fa fa-refresh"></i>
                    </span> 
                </span>
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li>Seguimiento y Evaluaci&oacute;n POA - FORM. N?? 4</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <section id="widget-grid" class="well">
                                <div class="">
                                    <?php echo $titulo;?>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                            <section id="widget-grid" class="well" align="center">
                                <?php echo $formularios_poa;?>
                            </section>
                            <section id="widget-grid" class="well" align="center">
                                <?php echo $formularios_seguimiento;?>
                            </section>
                        </article>
                        <article class="col-sm-12">
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2><b>SEGUIMIENTO POA <?php echo $this->session->userData('mes_actual')[2].' / '.$this->session->userData('gestion');?></b></h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">FORMULARIO SEGUIMIENTO POA</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE SEGUIMIENTO POA (MENSUAL)</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s4"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s5"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N POA (GESTI&Oacute;N)</span></a>
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

                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="SEGUIMIENTO POA">
                                               <div class="row"><br>
                                                    <div align="right">
                                                        <?php echo $boton_reporte_seguimiento_poa;?>
                                                    </div>
                                                    <div class="jarviswidget jarviswidget-color-darken" >
                                                      <?php echo $operaciones_programados;?>
                                                    </div>
                                               </div>
                                            </div>
                                            <!-- end s1 tab pane -->

                                            <div class="tab-pane fade" id="s3" title="CUADRO DE SEGUIMIENTO POA">
                                               <div class="row">
                                                <div class="well">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                                        <div id="cabecera" style="display: none"><?php echo $cabecera1;?></div>
                                                        <hr>
                                                        <table>
                                                            <tr>
                                                                <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE SEGUIMIENTO POA AL MES DE <?php echo $this->session->userData('mes_actual')[2].' DE '.$this->session->userData('gestion');?></b></td>
                                                            </tr>
                                                        </table>
                                                        <hr>
                                                            <div id="Seguimiento">
                                                                <div id="container" style="width: 700px; height: 400px; margin: 0 auto" align="center"></div>
                                                            </div>
                                                        <hr>
                                                            <div class="table-responsive" id="tabla_componente_vista">
                                                                <?php echo $tabla_temporalidad_componente;?>
                                                            </div>
                                                            <div id="tabla_componente_impresion" style="display: none">
                                                                <?php echo $tabla_temporalidad_componente_impresion;?>
                                                            </div>
                                                        <hr>
                                                        <div align="right">
                                                            <button id="btnImprimir_seguimiento" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE SEGUIMIENTO MENSUAL POA</b></button>
                                                        </div>
                                                    </div>
                                                </div>
                                               </div>
                                            </div>
                                            <!-- end s3 tab pane -->

                                            <div class="tab-pane fade" id="s4" title="CUADRO DE EVALUACI??N POA">
                                               <div class="row">
                                                <div class="well">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div id="cabecera2" style="display: none"><?php echo $cabecera2;?></div>
                                                        <hr>
                                                        <table>
                                                            <tr>
                                                                <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE AVANCE EVALUACI&Oacute;N POA AL <?php echo $tmes[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                                                            </tr>
                                                        </table>
                                                        <hr>
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
                                                            <button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</b></button>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                        <div id="cabecera2" style="display: none"><?php echo $cabecera2;?></div>
                                                        <hr>
                                                        <table>
                                                            <tr>
                                                                <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DETALLE EVALUACI&Oacute;N POA AL <?php echo $tmes[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                                                            </tr>
                                                        </table>
                                                        <hr>
                                                        <div id="evaluacion_pastel">
                                                            <div id="pastel_todos" style="width: 600px; height: 420px; margin: 0 auto"></div>
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
                                                            <button id="btnImprimir_evaluacion_pastel" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</b></button>
                                                        </div>
                                                    </div>
                                                </div>
                                               </div>
                                            </div>
                                            <!-- end s4 tab pane -->

                                            <div class="tab-pane fade" id="s5" title="CUADRO DE EVALUACI??N POA GESTI??N">
                                               <div class="row">
                                                <div class="well">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                                        <div id="cabecera3" style="display: none"><?php echo $cabecera3;?></div>
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
                                                            <button id="btnImprimir_evaluacion_gestion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (GESTI??N)</b></button>
                                                        </div>
                                                    </div>
                                                </div>
                                               </div>
                                            </div>
                                            <!-- end s5 tab pane -->

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
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
    <!-- ========================================================================================================= -->
        <div class="modal fade" id="modal_nuevo_ff2" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" style="width:85%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="calificacion"></div>
                        <div class="row">
                            <div id="temporalidad"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('direccion').' - '.$this->session->userData('gestion') ?></span>
                </div>
            </div>
        </div>
        <!-- END PAGE FOOTER -->
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>
        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
        <!-- BOOTSTRAP JS -->
        <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
        <!-- CUSTOM NOTIFICATION -->
        <script src="<?php echo base_url(); ?>assets/js/notification/SmartNotification.min.js"></script>
        <!-- JARVIS WIDGETS -->
        <script src="<?php echo base_url(); ?>assets/js/smartwidgets/jarvis.widget.min.js"></script>
        <!-- EASY PIE CHARTS -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
        <!-- SPARKLINES -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY SELECT2 INPUT -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/select2/select2.min.js"></script>
        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
        <!-- browser msie issue fix -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
        <!-- FastClick: For mobile devices -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- Demo purpose only -->
        <script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/seguimientopoa/seguimiento.js"></script> 
        
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
                                name: 'NRO ACT. PROGRAMADO EN EL TRIMESTRE',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'NRO ACT. CUMPLIDO EN EL TRIMESTRE',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO EN EL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO EN EL TRIMESTRE',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO EN EL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO EN EL TRIMESTRE',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO EN EL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO EN EL TRIMESTRE',
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
            $(document).ready(function() {  
               Highcharts.chart('pastel_todos', {
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
                    name: 'Actividades',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo (100-($tabla[5][$this->session->userData('trimestre')]+round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2)));?> %',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f98178',
                        },

                        {
                          name: 'EN PROCESO : <?php echo round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2);?> %',
                          y: <?php echo round(($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100,2);?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?> %',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
        </script>
        <script type="text/javascript">
            Highcharts.chart('container', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 0,
                    beta: 0,
                    depth: 100
                }
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: Highcharts.getOptions().lang.shortMonths,
                labels: {
                    skew3d: true,
                    style: {
                        fontSize: '16px'
                    }
                }
            },
            yAxis: {
                title: {
                  text: 'cumplimiento (%)'
                }
            },
            xAxis: {
                categories: [
                    'ENE.', 
                    'FEB.', 
                    'MAR.', 
                    'ABR.', 
                    'MAY.', 
                    'JUN.', 
                    'JUL.', 
                    'AGO.', 
                    'SEPT.', 
                    'OCT.', 
                    'NOV.', 
                    'DIC.'
                ]
            },
            series: [{
                name: 'Eficiencia',
                data: [
                    <?php  
                        for ($i=1; $i <=12 ; $i++) { 
                            if($i==12){
                                echo $matriz_temporalidad_subactividad[4][$i];
                            }
                            else{
                               echo $matriz_temporalidad_subactividad[4][$i].','; 
                            }
                            
                        }
                    ?>
                ]
            }]
        });
        </script>

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
                        name: '% META PROGRAMADAS EN EL TRIMESTRE',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS EN EL TRIMESTRE',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });
        </script>
    </body>
</html>
