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
        <script>
            function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Evaluacion" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }                                            
        </script>
        <meta name="viewport" content="width=device-width">
        <style>
            #areaImprimir{display:none}
        @media print {
            #areaImprimir {display:block}
        }
            #areaImprimir_eval{display:none}
        @media print {
            #areaImprimir_eval {display:block}
        }
        @media print {
            #areaImprimir_eficacia {display:block}
        }
        </style>
        <style>
         hr{ 
            height:3px;  
            background-color:#1C7366; 
            }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              font-size: 9px;
              text-align: center;
            }
            td{
              padding: 1.4px;
              font-size: 9px;
            }
        </style>
        <script type="text/javascript">
        function printDiv(nombreDiv) {
            var contenido= document.getElementById(nombreDiv).innerHTML;
            var contenidoOriginal= document.body.innerHTML;
            document.body.innerHTML = contenido;
            window.print();
            document.body.innerHTML = contenidoOriginal;
        }
        </script>
    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">
                    <!-- <span id="logo"> <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="SmartAdmin"> </span> -->
                </div>
                <div class="col-md-4 " style="font-size:18px;margin-top:10px;margin-bottom:-10px;">
                    <span>
                        &nbsp;&nbsp;&nbsp; 
                        <div class="badge bg-color-blue">
                            <span style="font-size:15px;"><b>Fecha Sesi&oacute;n: <?php echo $this->session->userdata('desc_mes').' / '.$this->session->userdata('gestion');?></b></span>
                        </div>
                    </span>
                    <div class="project-context hidden-xs">
                        <span class="project-selector dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:19px;">
                            <i class="fa fa-lg fa-fw fa-calendar txt-color-blue"></i>
                        </span>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="<?php echo base_url();?>index.php/cambiar_gestion">Cambiar Gestión</a>
                            </li>
                        </ul>
                    </div>
                </div>
          <!-- pulled right: nav area -->
          <div class="pull-right">
            <!-- collapse menu button -->
            <div id="hide-menu" class="btn-header pull-right">
              <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
            </div>
            <!-- end collapse menu -->
            <!-- logout button -->
            <div id="logout" class="btn-header transparent pull-right">
              <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
            </div>
            <!-- end logout button -->
            <!-- search mobile button (this is hidden till mobile view port) -->
            <div id="search-mobile" class="btn-header transparent pull-right">
              <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
            </div>
            <!-- end search mobile button -->
            <!-- fullscreen button -->
            <div id="fullscreen" class="btn-header transparent pull-right">
              <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
            </div>
            <!-- end fullscreen button -->
          </div>
          <!-- end pulled right: nav area -->
        </header>
        <!-- END HEADER -->
        <!-- Left panel : Navigation area -->
        <aside id="left-panel">
            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                            <span>
                                <i class="fa fa-user" aria-hidden="true"></i>  <?php echo $this->session->userdata("user_name");?>
                            </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                </span>
            </div>
            <nav>
                <ul>
                    <li class="">
                    <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                    </li>
                    <li class="text-center">
                        <a href="#" title="REGISTRO DE EJECUCION"> <span class="menu-item-parent"><?php echo $tit_menu; ?></span></a>
                    </li>
                    <?php echo $menu;?>
                </ul>
            </nav>
            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
        </aside>

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
                    <?php echo $tit;?>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                            <section id="widget-grid" class="well">
                                <div class="">
                                    <?php echo $titulo;?>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <section id="widget-grid" class="well">
                                <a href="<?php echo base_url();?>index.php/eval/mis_operaciones" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR A LISTA DE POAS</a>
                            </section>
                        </article>
                    </div>
                    <div class="row">
                        <article class="col-sm-12">
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2 title="<?php echo "aper_id : ".$proyecto[0]['aper_id']." -- proy id : ".$proyecto[0]['proy_id'];?>">EVALUACI&Oacute;N POA POR UNIDAD / PROYECTO</h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1" title="CUADRO DE AVANCE POR UNIDAD"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO EJECUCI&Oacute;N POR UNIDAD</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">MIS SUBACTIVIDADES</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">PARAMETROS DE EFICACIA</span></a>
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
                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="EFICACIA INSTITUCIONAL A NIVEL UNIDAD">
                                                <hr>
                                                    <div align="right">
                                                        <a href="#" onclick="printDiv('areaImprimir')" title="IMPRIMIR CUADRO DE EFICACIA DE UNIDAD" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;
                                                    </div>
                                                <hr>

                                                <?php echo $calificacion;?>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="regresion" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                                <?php echo $tabla_regresion;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="regresion_gestion" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                                <?php echo $tabla_regresion_total;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            <hr>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                            <tr>
                                                              <td>
                                                              <div id="pastel" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td>
                                                                <?php echo $tabla_pastel;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="pastel_todos" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                                <?php echo $tabla_pastel_todo;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                    </div>    
                                                </div>
                                            </div>
                                            <!-- end s1 tab pane -->
                                
                                            <div class="tab-pane fade" id="s2" title="CUADRO MIS MIS SUBACTIVIDADES">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                      <hr>
                                                        <div align="right">
                                                          <!-- <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N DE OPERACIONES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp; -->
                                                        </div>
                                                        <br>
                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                <h2 class="alert alert-success" align="center">DETALLE DE EVALUACI&Oacute;N POR SUBACTIVIDAD</b></h2>
                                                                <?php echo $mis_servicios;?>
                                                            </div>
                                                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                                <h2 class="alert alert-success" align="center">INDICADORES</b></h2>
                                                                <div style="font-size: 12px;font-family: Arial; height: 2.5%;"><b>(%) EFICACIA</b> - Actividades Cumplidas</div>
                                                                <table border="1">
                                                                    <tr>
                                                                        <td align="center" style="width:100%; height: 1.2%; font-size: 80px;"><b><?php echo $tabla[5][$this->session->userData('trimestre')];?>%</b></td>
                                                                    </tr>
                                                                </table>
                                                                <hr>
                                                            
                                                                <div style="font-size: 12px;font-family: Arial; height: 2.5%;"><b>(%) ECONOMIA - </b>Presupuesto Ejecutado</div>
                                                                <table border="1">
                                                                    <tr>
                                                                        <td align="center" style="width:100%; height: 1.2%; font-size: 80px;"><b><?php echo $economia[3];?>%</b></td>
                                                                    </tr>
                                                                </table>
                                                                <hr>

                                                                <div style="font-size: 12px;font-family: Arial; height: 2.5%;"><b> EFICIENCIA</b></div>
                                                                <table border="1">
                                                                    <tr>
                                                                        <td align="center" style="width:100%; height: 1.2%; font-size: 80px;"><b><?php echo $eficiencia;?></b></td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade" id="s3" title="PARAMETROS DE EFICACIA">
                                                <hr>
                                                <div class="row">
                                                <?php echo $parametro_eficacia;?>
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
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
    <!-- ========================================================================================================= -->
        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
                </div>
            </div>
        </div>
        <div id="areaImprimir">
            <?php echo $print_tabla;?>
        </div>
        
        <div id="areaImprimir_eval">
            <?php echo $eval_unidad;?>
        </div>
        <!-- END PAGE FOOTER -->
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
        
        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
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
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>

        
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
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
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

        /*--- Regresion Lineal Impresion ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_gestion_print',
                defaultSeriesType: 'line'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion');?>'
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
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$tmes[0]['trm_descripcion'];?>'
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
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                                }
                            ]
                        <?php
                    }
                ?>
            });
          });

        /*---  REGRESION LINEAL AL TRIMESTRE IMPRESION  ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_impresion',
                defaultSeriesType: 'line'
              },
              title: {
                text: '' 
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$tmes[0]['trm_descripcion'];?>'
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
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
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
               Highcharts.chart('pastel', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: '<?php echo 'EVALUACIÓN '.$tmes[0]['trm_descripcion'];?>'
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
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $tabla[6][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f44336',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });

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
                    text: '<?php echo 'EVALUACIÓN '.$tmes[0]['trm_descripcion'];?>'
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
                    text: 'PARAMETRO DE EFICACIA AL <?php echo $tmes[0]['trm_descripcion'];?>'
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

            $(document).ready(function() {  
               Highcharts.chart('parametro_efi_print', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: 'PARAMETRO DE EFICACIA AL <?php echo $tmes[0]['trm_descripcion'];?>'
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
        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
    </body>
</html>
