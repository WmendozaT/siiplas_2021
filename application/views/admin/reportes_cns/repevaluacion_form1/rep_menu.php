<!DOCTYPE html>
<html lang="en-us">
   <!--  REPORTE PARA LA GESTION 2021 -->
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
    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
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
                        <a href="#" title="REPORTE DE EVALAUCIÓN"> <span class="menu-item-parent">REPORTES POA</span></a>
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
                   <li>Reportes POA</li><li>Evaluaci&oacute;n A.c.p.</li><li>Accion de Corto Plazo</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
                <div id="content">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php echo $informacion;?>
                                                <button onClick="imprimir_grafico()" >IMPRIMIR CUADRO</button>
                        <!-- <div class="well well-sm well-light">
                            <form class="smart-form">
                            <header><h2><b>EVALUACI&Oacute;N DE A.C.P. (FORMULARIO N° 1) </b> - INSTITUCIONAL</h2></header>
                            <fieldset>          
                                <div class="row">
                                    <div id="tabs">
                                        <ul>
                                            <li>
                                                <a href="#tabs-a"><b>CUMPLIMIENTO TRIMESTRAL</b></a>
                                            </li>
                                            <li>
                                                <a href="#tabs-b"><b>CUMPLIMIENTO ANUAL</b></a>
                                            </li>
                                        </ul>
                                        <div id="tabs-a">
                                            <div class="row">
                                              hhfghfg
                                            </div>
                                        </div>

                                        <div id="tabs-b">
                                            <div class="row">
                                                <?php echo $informacion;?>
                                                <button onClick="imprimir_grafico()" >IMPRIMIR CUADRO</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                            </form>
                        </div> -->
                    </article>
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
        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/seguimientoacp/evaluacionacp.js"></script>
        <!-- browser msie issue fix -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>

        <script type="text/javascript">
        Highcharts.chart('grafico1', {
            chart: {
                type: 'bar'
            },
            title: {
                text: '<b>EVALUACIÓN A.C.P. INSTITUCIONAL - GESTIÓN <?php echo $this->session->userData('gestion');?></b>'
            },
            subtitle: {
                text: '<b>CUMPLIMIENTO DE ACCIONES DE CORTO PLAZO AL <?php echo $trimestre[0]['trm_descripcion'].' / '.$this->session->userData('gestion');?></b>'
            },
            xAxis: {
              categories: [
                <?php 
                  for ($i=0; $i <$nro ; $i++){ 
                    ?>
                      '<?php echo $matriz[$i][0];?> ',
                    <?php
                  } 
                ?>
              ],
              title: {
                  text: null
              }
            },
            yAxis: {
              min: 0,
              title: {
                  text: 'CUMPLIMIENTO (%)',
                  align: 'high'
              },
              labels: {
                  overflow: 'Acciones de Corto Plazo Institucional'
              }
            },
            tooltip: {
                valueSuffix: '%'
            },
            plotOptions: {
              bar: {
                  dataLabels: {
                    enabled: true
                  }
              }
            },

            credits: {
                enabled: false
            },

            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                },
                column: {
                    borderRadius: '55%'
                }
            },

            series: [{
              name: 'CUMPLIMIENTO %',
              color: '#296860',
              data: [
                <?php 
                  for ($i=0; $i <$nro ; $i++){ 
                    ?>
                      <?php echo $matriz[$i][4];?>,
                    <?php
                  } 
                ?>
              ]
            }]
        });
        </script>
        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            $(document).ready(function() {
                pageSetUp();
                $("#menu").menu();
                $('.ui-dialog :button').blur();
                $('#tabs').tabs();
            })
        </script>
    </body>
</html>
