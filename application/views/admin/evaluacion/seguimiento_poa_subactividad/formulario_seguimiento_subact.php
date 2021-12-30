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
            #mdialTamanio{
              width: 45% !important;
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
        <?php echo $menu; ?>

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
                    <li>Seguimiento y Evaluaci&oacute;n POA</li><li>Formulario N° 4</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section id="widget-grid" class="well">
                                <div class="">
                                    <?php echo $titulo;?>
                                    <?php echo $formularios_seguimiento.' '.$salir;?>
                                </div>
                            </section>
                        </article>
                    </div>
                    <?php echo $update_eval;?>
                    <div class="row">
                        <article class="col-sm-12">
                            <?php 
                              if($this->session->flashdata('success')){ ?>
                                <div class="alert alert-success">
                                  <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php }
                                elseif($this->session->flashdata('danger')){ ?>
                                <div class="alert alert-danger">
                                  <?php echo $this->session->flashdata('danger'); ?>
                                </div><?php }
                            ?>
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2>SEGUIMIENTO POA </h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">FORMULARIO SEGUIMIENTO POA</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE SEGUIMIENTO POA (MENSUAL)</span></a>
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
                                               <div class="row">
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

        <!-- MODAL UPDATE SEG POA   -->
        <div class="modal fade" id="modal_update_eval" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <form id="form_update" novalidate="novalidate" method="post">
                        <input type="hidden" name="com_id" id="com_id">
                        <div id="content_valida">
                            <center><div class="loading" align="center"><h2>Actualizando Evaluaci&oacute;n  POA <br><div id="tit"></div></h2><br><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /></div></center>
                        </div>
                        <div id="load" style="display: none;"><center><img src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"><hr><b>ACTUALIZANDO FORMULARIO SEGUIMIENTO POA ...</b></center></div>
                            <p>
                                <div id="but" align="right" style="display:none;">
                                    <button type="button" name="but_update" id="but_update" class="btn btn-success">ACEPTAR EVALUACI&Oacute;N</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </p>
                    </form>
                </div>
            </div>
        </div>
     <!--  =============== -->
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
    </body>
</html>
