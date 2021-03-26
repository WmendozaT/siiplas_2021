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
                    <li>Reportes</li><li>Reporte Evaluaci&oacute;n Distrital</li><li>Distrital <?php echo $dist[0]['dist_distrital']; ?></li><li><?php if($proyecto[0]['tp_id']==1){echo "Proyecto de Inversi&oacute;n";}else{echo "Unidad Organizacional";} ?></li>
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
                                    <h1> DISTRITAL : <small><?php echo strtoupper($dist[0]['dist_distrital']);?></small>
                                    <h1> <?php echo strtoupper($proyecto[0]['tipo']);?> : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></small></h1>
                                </div>
                            </section>
                        </article>
                    </div>
                    <div class="row">
                        <article class="col-sm-12">
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2>EVALUACI&Oacute;N POR UNIDAD / PROYECTO</h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1" title="CUADRO DE AVANCE POR UNIDAD"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO EJECUCI&Oacute;N POR UNIDAD</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s2" title="EVALUACI&Oacute;N DE OPERACIONES"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA DE OPERACIONES</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s4" title="EVALUACI&Oacute;N POR PROCESOS"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EVALUACI&Oacute;N DE OPERACIONES POR SUB-ACTIVIDADES</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3" title="EFICIA A NIVEL DE PROCESOS"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE AVANCE DE OPERACIONES A DETALLE</span></a>
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
                                                        <a href="#" onclick="printDiv('areaImprimir')" title="IMPRIMIR CUADRO DE EFICACIA DE UNIDAD" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE AVANCE DE RESULTADOS</a>&nbsp;&nbsp;
                                                        <a href="<?php echo site_url("").'/rep_distrital/'.$dist[0]['dist_id'];?>" title="SALIR A EVALUACI&Oacute;N DISTRITAL" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR</a>
                                                    </div>
                                                <hr>
                                                <h2 class="alert alert-success" align="center"><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></h2>
                                                <div class="row">
                                                <?php
                                                    if($this->session->userdata('trimestre')==1 || $this->session->userdata('trimestre')==2){ ?>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                            <div class="well padding-10">
                                                                <div id="regresion_lineal" style="width: 900px; height: 400px; margin: 0 auto"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                            <div class="well padding-10">
                                                                <div id="g_efi" style="width: 900px; height: 400px; margin: 0 auto"></div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    else{ ?>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="well padding-12">
                                                                <div id="regresion_lineal" style="width: 1500px; height: 450px; margin: 0 auto"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <hr>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                            <div class="well padding-12">
                                                                <div id="g_efi" style="width: 1500px; height: 450px; margin: 0 auto"></div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                ?>
                                                </div>
                                            <hr>
                                            <div class="table-responsive">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4" >
                                                        <h1 class="page-title txt-color-blueDark" style="background-color: <?php echo $color; ?>; height: 25px; padding: 4px; border-radius: 5px;"><font color=#fff>CALIFICACI&Oacute;N AL <?php echo $trimestre[0]['trm_descripcion'];?>&nbsp;&nbsp;:&nbsp;&nbsp;  
                                                            <b><?php echo $tabla[3][$tr];?>%</b></font>
                                                        </h1>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <?php 
                                                                for ($i=1; $i <=12 ; $i++) { ?>
                                                                   <th><center><?php echo $tabla[4][$i]; ?></center></th>
                                                                   <?php
                                                                }
                                                            ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>PROGRAMACI&Oacute;N ACUMULADA</td>
                                                           <?php 
                                                                for ($i=1; $i <=12 ; $i++) { 
                                                                    if($i<=$tr){ ?>
                                                                        <td bgcolor="#eaf7e4" title="Trimestre Evaluado"><?php echo $tabla[1][$i];?>%</td>
                                                                        <?php
                                                                    }
                                                                    else{ ?>
                                                                        <td><?php echo $tabla[1][$i];?>%</td>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tr>
                                                        <tr>
                                                            <td>EJECUCI&Oacute;N ACUMULADA</td>
                                                            <?php 
                                                                for ($i=1; $i <=12 ; $i++) { if($i<=$tr){ ?>
                                                                        <td bgcolor="#eaf7e4" title="Trimestre Evaluado"><?php echo $tabla[2][$i];?>%</td>
                                                                        <?php
                                                                    }
                                                                    else{ ?>
                                                                        <td><?php echo $tabla[2][$i];?>%</td>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tr>
                                                        <tr bgcolor="#eaf7e4">
                                                            <td>EJECUCI&Oacute;N</td>
                                                            <?php 
                                                                for ($i=1; $i <=12 ; $i++) { 
                                                                    if($i<=$tr){ ?>
                                                                        <td title="Trimestre Evaluado"><b><?php echo $tabla[3][$i];?>%</b></td>
                                                                        <?php
                                                                    }
                                                                    else{ ?>
                                                                        <td><?php echo $tabla[3][$i];?>%</td>
                                                                        <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            </div>
                                            <!-- end s1 tab pane -->
                                            <div class="tab-pane fade" id="s2" title="EVALUACI&Oacute;N DE OPERACIONES">
                                                <hr>
                                                    <div align="right">
                                                        <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N DE OPERACIONES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N ACUMULADO</a>&nbsp;&nbsp;
                                                        <a href="<?php echo site_url("").'/rep_distrital/'.$dist[0]['dist_id'];?>" title="SALIR A EVALUACI&Oacute;N DISTRITAL" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR</a>
                                                    </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <?php echo $evaluacion;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end s2 tab pane -->

                                            <div class="tab-pane fade" id="s4" title="EVALUACI&Oacute;N POR PROCESOS - COMPONENTES">
                                                <hr>
                                                <div align="right"><a href="javascript:abreVentana('<?php echo site_url("").'/print_eval_componentes/'.$proyecto[0]['proy_id'];?>');" title="LISTA DE SUB-ACTIVIDADES, SERVICIOS, COMPONENTES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR EVALUACI&Oacute;N DE OPERACIONES POR SERVICIO/COMPONENTE</a>&nbsp;&nbsp;</div><hr>
                                                <h2 class="alert alert-success" align="center"><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></h2>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <h1 class="page-title txt-color-blueDark"> El siguiente cuadro muestra la evaluaci&oacute;n Acumulada de Operaciones al <?php echo $trimestre[0]['trm_descripcion'];?></h1>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <?php echo $eval_componente;?>
                                                </div>
                                            </div>
                                            <!-- end s3 tab pane -->

                                            <div class="tab-pane fade" id="s3" title="LISTA DE SERVICIOS">
                                                <hr><h2 class="alert alert-success" align="center">LISTA DE PROCESOS/OPERACIONES - <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></h2>
                                                <!-- <div align="right"><a href="#" onclick="printDiv('areaImprimir_eficacia')" title="IMPRIMIR CUADRO DE EFICACIA" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/iconos/printer.PNG" WIDTH="20" HEIGHT="20"/> IMPRIMIR</a>&nbsp;&nbsp;</div><hr> -->
                                                <div class="row">
                                                    <?php echo $componente;?>
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
        <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 
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
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script type="text/javascript">
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'g_efi',
                defaultSeriesType: 'column'
              },
              title: {
                text: 'PROGRAMADO - EJECUTADO - EFICACIA'
              },
              subtitle: {
                text: ''
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
                    },
                    <?php
                }
              ?>
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
                    this.x +': '+ this.y +' (%).';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.04,
                  borderWidth: 0
                }
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                        },
                        {
                            name: 'EFICACIA EN %',
                            data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                        }
                    ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>]
                            },
                            {
                                name: 'EFICACIA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>]
                            }
                        ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>]
                            },
                            {
                                name: 'EFICACIA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>, <?php echo $tabla[3][7];?>, <?php echo $tabla[3][8];?>, <?php echo $tabla[3][9];?>]
                            }
                        ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>, <?php echo $tabla[3][7];?>, <?php echo $tabla[3][8];?>, <?php echo $tabla[3][9];?>, <?php echo $tabla[3][10];?>, <?php echo $tabla[3][11];?>, <?php echo $tabla[3][12];?>]
                            }
                        ]
                    <?php
                }
              ?>
            });
          });
        </script>
        <script type="text/javascript">
          var chart;
          $(document).ready(function() {
            chart = new Highcharts.Chart({
              chart: {
                renderTo: 'g_efi2',
                defaultSeriesType: 'column'
              },
              title: {
                text: 'PROGRAMADO - EJECUTADO - EFICACIA'
              },
              subtitle: {
                text: ''
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                    xAxis: {
                        categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
                    },
                    <?php
                }
              ?>
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
                    this.x +': '+ this.y +' (%).';
                }
              },
              plotOptions: {
                column: {
                    borderRadius: 5,
                    pointPadding: 0.04,
                  borderWidth: 0
                }
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                        },
                        {
                            name: 'EFICACIA EN %',
                            data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                        }
                    ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>]
                            },
                            {
                                name: 'EFICACIA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>]
                            }
                        ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>]
                            },
                            {
                                name: 'EFICACIA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>, <?php echo $tabla[3][7];?>, <?php echo $tabla[3][8];?>, <?php echo $tabla[3][9];?>]
                            }
                        ]
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                        series: [
                            {
                                name: 'PROGRAMACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                            },
                            {
                                name: 'EVALUACIÓN ACUMULADA EN %',
                                data: [ <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>, <?php echo $tabla[3][5];?>, <?php echo $tabla[3][6];?>, <?php echo $tabla[3][7];?>, <?php echo $tabla[3][8];?>, <?php echo $tabla[3][9];?>, <?php echo $tabla[3][10];?>, <?php echo $tabla[3][11];?>, <?php echo $tabla[3][12];?>]
                            }
                        ]
                    <?php
                }
              ?>
            });
          });
        </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion_lineal',
            defaultSeriesType: 'line'
          },
          title: {
            text: 'CUADRO DE AVANCE PROGRAMADO - EJECUTADO'
          },
          subtitle: {
            text: 'A NIVEL UNIDAD/PROYECTO'
          },
          <?php 
            if($this->session->userdata('trimestre')==1){ ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==2) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==3) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==4) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
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
                        name: 'PROGRAMACIÓN ACUMULADA EN %',
                        data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>]
                    },
                    {
                        name: 'EVALUACIÓN ACUMULADA EN %',
                        data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                    }
                ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==2) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>]
                        }
                    ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==3) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>]
                        }
                    ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==4) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                        }
                    ]
                <?php
            }
          ?>
        });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion_lineal2',
            defaultSeriesType: 'line'
          },
          title: {
            text: 'CUADRO DE AVANCE PROGRAMADO - EJECUTADO'
          },
          subtitle: {
            text: 'A NIVEL DE UNIDAD/PROYECTO'
          },
          <?php 
            if($this->session->userdata('trimestre')==1){ ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==2) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==3) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.']
                },
                <?php
            }
            elseif ($this->session->userdata('trimestre')==4) { ?>
                xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
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
                        name: 'PROGRAMACIÓN ACUMULADA EN %',
                        data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>]
                    },
                    {
                        name: 'EVALUACIÓN ACUMULADA EN %',
                        data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                    }
                ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==2) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>]
                        }
                    ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==3) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>]
                        }
                    ]
                <?php
            }
            elseif ($this->session->userdata('trimestre')==4) { ?>
                    series: [
                        {
                            name: 'PROGRAMACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                        },
                        {
                            name: 'EVALUACIÓN ACUMULADA EN %',
                            data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                        }
                    ]
                <?php
            }
          ?>
        });
      });
    </script>
    <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
    </body>
</html>
