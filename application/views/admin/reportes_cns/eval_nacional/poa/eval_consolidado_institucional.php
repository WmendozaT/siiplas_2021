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
        <meta name="viewport" content="width=device-width">
        <script>
            function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Evaluacion Institucional" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }                                            
        </script>
        <style>
            #areaImprimir_eval_reg{display:none}
        @media print {
            #areaImprimir_eval_reg {display:block}
        }

            #areaImprimir_eval{display:none}
        @media print {
            #areaImprimir_eval {display:block}
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
            <div class="pull-right">
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
            </div>
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
                        <a href="#" title="REGISTRO DE EJECUCION"> <span class="menu-item-parent">REPORTES</span></a>
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
                    <li>Reportes</li><li>Rep. Eval. POA</li><li>Evaluaci&oacute;n Operaciones a nivel Institucional</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section id="widget-grid" class="well">
                                <h2>REPORTE CONSOLIDADO DE OPERACIONES A NIVEL INSTITUCIONAL</h2>
                            </section>
                        </article>
                    </div>
                    <div class="row">
                        <article class="col-sm-12">
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2>CONSOLIDADO A NIVEL INSTITUCIONAL - <?php echo $this->session->userdata('gestion')?></h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA POR PROGRAMAS</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s5"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA POR REGIONALES</span></a>
                                        </li>

                                        <li>
                                            <a data-toggle="tab" href="#s6"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO GR&Aacute;FICO EFICACIA POR REGIONALES</span></a>
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
                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="CUADRO DE EVALUACION POR CATEGORIA PROGRAMATICA">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <hr>
                                                            <div align="right">
                                                                <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACION PROGRAMAS A NIVEL INSTITUCIONAL" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EVAL. DE OPERACIONES POR PROGRAMAS</a>&nbsp;&nbsp;
                                                            </div>
                                                        <hr>
                                                        <h2 class="alert alert-info" align="center"> LISTA DE APERTURAS PROGRAM&Aacute;TICAS </h2>
                                                        <h1 class="page-title txt-color-blueDark"> El siguiente cuadro muestra el consolidado de evaluaci&oacute;n Trimestral y Acumulada por Categoria Programatica al <?php echo $trimestre[0]['trm_descripcion'];?> a nivel Institucional</h1>
                                                        <center>
                                                            <div class="table-responsive"><?php echo $eval_programas;?></div>
                                                        </center>
                                                        <hr>
                                                        <?php echo $graf_eval_programas;?>
                                                    </div>
                                                </div>
                                            <hr>
                                            </div>
                                            <!-- end s1 tab pane -->

                                            <div class="tab-pane fade" id="s5" title="CONSLIDADO POR ">
                                                <hr>
                                                <div align="right">
                                                    <a href="javascript:abreVentana('<?php echo site_url("").'/print_rep_reg_inst/';?>');" title="LISTA DE REGIONALES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR EVALUACI&Oacute;N DE REGIONALES</a>&nbsp;&nbsp;
                                                    <a href="#" onclick="printDiv('areaImprimir_eval_reg')" title="IMPRIMIR CUADRO DE EVALUACION POR REGIONALES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EFICACIA DE OPERACIONES POR REGIONALES</a>&nbsp;&nbsp;
                                                </div>
                                                <hr>
                                                <h2 class="alert alert-info" align="center"> LISTA DE REGIONALES </h2>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <h1 class="page-title txt-color-blueDark"> El siguiente cuadro muestra la evaluaci&oacute;n Acumulada de Operaciones al <?php echo $trimestre[0]['trm_descripcion'];?></h1>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <?php echo $eval_regional;?>
                                                </div>
                                            </div>
                                            <!-- end s4 tab pane -->

                                            <div class="tab-pane fade" id="s6" title="CUADRO EVALUACION DE OPERACIONES POR REGIONAL">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <hr>
                                                            <div align="right">
                                                                <a href="#" onclick="printDiv('areaImprimir_eval_reg')" title="IMPRIMIR CUADRO DE EVALUACION TOTAL A NIVEL INSTITUCIONAL" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EFICACIA DE OPERACIONES</a>&nbsp;&nbsp;
                                                            </div>
                                                        <hr>
                                                        <h2 class="alert alert-info" align="center">CUADRO DE EVALUACI&Oacute;N ACUMULADA AL  - <?php echo $trimestre[0]['trm_descripcion'];?> POR REGIONAL</h2>
                                                        <div id="container_reg" style="width: 1600px; height: 500px; margin: 0 auto"></div><hr>
                                                        <div id="container_reg_cum" style="width: 1600px; height: 500px; margin: 0 auto"></div>
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
        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
                </div>
            </div>
        </div>
        <div id="areaImprimir_eval">
            <?php echo $print_eval_institucional;?>
        </div>
        <div id="areaImprimir_eval_reg">
            <?php echo $print_eval_regional;?>
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
        <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 
        <!-- IMPORTANT: APP CONFIG -->
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
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <!-- GRAFICO PARAMETROS DE EFICACIA INSTITUCIONAL -->
        <script type="text/javascript">
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_reg',
                defaultSeriesType: 'column'
              },
              title: {
                text: 'PROGRAMADO - EJECUTADO'
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: ['<?php echo $matriz[1][1];?>','<?php echo $matriz[2][1];?>','<?php echo $matriz[3][1];?>','<?php echo $matriz[4][1];?>','<?php echo $matriz[5][1];?>','<?php echo $matriz[6][1];?>','<?php echo $matriz[7][1];?>','<?php echo $matriz[8][1];?>','<?php echo $matriz[9][1];?>','<?php echo $matriz[10][1];?>']
              },
              yAxis: {
                min: 0,
                title: {
                  text: 'Operaciones'
                }
              },
              legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
              },
              tooltip: {
                formatter: function() {
                  return ''+
                    this.x +': '+ this.y +' Ope.';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.05,
                  borderWidth: 0
                }
              },
              series: [{
                name: 'OPERACIONES PROGRAMADAS',color: '#61d1e4',
                data: [<?php echo $matriz[1][5];?>, <?php echo $matriz[2][5];?>, <?php echo $matriz[3][5];?>,<?php echo $matriz[4][5];?>,<?php echo $matriz[5][5];?>,<?php echo $matriz[6][5];?>,<?php echo $matriz[7][5];?>,<?php echo $matriz[8][5];?>,<?php echo $matriz[9][5];?>,<?php echo $matriz[10][5];?>],
                }, {
                    name: 'OPERACIONES EJECUTADAS',color: '#04B404',
                    data: [<?php echo $matriz[1][6];?>,<?php echo $matriz[2][6];?>,<?php echo $matriz[3][6];?>,<?php echo $matriz[4][6];?>,<?php echo $matriz[5][6];?>,<?php echo $matriz[6][6];?>,<?php echo $matriz[7][6];?>,<?php echo $matriz[8][6];?>,<?php echo $matriz[9][6];?>,<?php echo $matriz[10][6];?>],
                 
                }]
            });
          });
        </script>
        <script type="text/javascript">
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_reg_cum',
                defaultSeriesType: 'column'
              },
              title: {
                text: 'AVANCE DE CUMPLIMIENTO'
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: ['<?php echo $matriz[1][1];?>','<?php echo $matriz[2][1];?>','<?php echo $matriz[3][1];?>','<?php echo $matriz[4][1];?>','<?php echo $matriz[5][1];?>','<?php echo $matriz[6][1];?>','<?php echo $matriz[7][1];?>','<?php echo $matriz[8][1];?>','<?php echo $matriz[9][1];?>','<?php echo $matriz[10][1];?>']
              },
              yAxis: {
                min: 0,
                title: {
                  text: ''
                }
              },
              legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
              },
              tooltip: {
                formatter: function() {
                  return ''+
                    this.x +': '+ this.y +' (%).';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.05,
                  borderWidth: 0
                }
              },
              series: [{
                name: 'CUMPLIDO (%)',color: '#8feadf',
                data: [<?php echo $matriz[1][7];?>, <?php echo $matriz[2][7];?>, <?php echo $matriz[3][7];?>,<?php echo $matriz[4][7];?>,<?php echo $matriz[5][7];?>,<?php echo $matriz[6][7];?>,<?php echo $matriz[7][7];?>,<?php echo $matriz[8][7];?>,<?php echo $matriz[9][7];?>,<?php echo $matriz[10][7];?>],
                }, {
                    name: 'NO CUMPLIDO (%)',color: '#f12f2f',
                    data: [<?php echo $matriz[1][8];?>,<?php echo $matriz[2][8];?>,<?php echo $matriz[3][8];?>,<?php echo $matriz[4][8];?>,<?php echo $matriz[5][8];?>,<?php echo $matriz[6][8];?>,<?php echo $matriz[7][8];?>,<?php echo $matriz[8][8];?>,<?php echo $matriz[9][8];?>,<?php echo $matriz[10][8];?>],
                 
                }]
            });
          });
        </script>
        <script type="text/javascript">
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'container_reg_cum_print',
                defaultSeriesType: 'column'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: ''
              },
              xAxis: {
                categories: ['<?php echo $matriz[1][1];?>','<?php echo $matriz[2][1];?>','<?php echo $matriz[3][1];?>','<?php echo $matriz[4][1];?>','<?php echo $matriz[5][1];?>','<?php echo $matriz[6][1];?>','<?php echo $matriz[7][1];?>','<?php echo $matriz[8][1];?>','<?php echo $matriz[9][1];?>','<?php echo $matriz[10][1];?>']
              },
              yAxis: {
                min: 0,
                title: {
                  text: ''
                }
              },
              legend: {
                layout: 'vertical',
                backgroundColor: '#FFFFFF',
                align: 'left',
                verticalAlign: 'top',
                x: 100,
                y: 70,
                floating: true,
                shadow: true
              },
              tooltip: {
                formatter: function() {
                  return ''+
                    this.x +': '+ this.y +' (%).';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.05,
                  borderWidth: 0
                }
              },
              series: [{
                name: 'CUMPLIDO (%)',color: '#8feadf',
                data: [<?php echo $matriz[1][7];?>, <?php echo $matriz[2][7];?>, <?php echo $matriz[3][7];?>,<?php echo $matriz[4][7];?>,<?php echo $matriz[5][7];?>,<?php echo $matriz[6][7];?>,<?php echo $matriz[7][7];?>,<?php echo $matriz[8][7];?>,<?php echo $matriz[9][7];?>,<?php echo $matriz[10][7];?>],
                }, {
                    name: 'NO CUMPLIDO (%)',color: '#f12f2f',
                    data: [<?php echo $matriz[1][8];?>,<?php echo $matriz[2][8];?>,<?php echo $matriz[3][8];?>,<?php echo $matriz[4][8];?>,<?php echo $matriz[5][8];?>,<?php echo $matriz[6][8];?>,<?php echo $matriz[7][8];?>,<?php echo $matriz[8][8];?>,<?php echo $matriz[9][8];?>,<?php echo $matriz[10][8];?>],
                 
                }]
            });
          });
        </script>
    </body>
</html>
