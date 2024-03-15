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
                window.open(direccion, "Reporte de Evaluacion" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }
            function printDiv(nombreDiv) {
            var contenido= document.getElementById(nombreDiv).innerHTML;
            var contenidoOriginal= document.body.innerHTML;
            document.body.innerHTML = contenido;
            window.print();
            document.body.innerHTML = contenidoOriginal;
            }                                            
        </script>
        <style>
            #areaImprimir_eval{display:none}
            @media print {
            #areaImprimir_eval {display:block}
        }
        </style>
        <style>
            .table1{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
              }
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
              width: 90% !important;
            }
            #mdialTamanio_update{
              width: 50% !important;
            }
            #mdialTamanio_trimestre{
              width: 30% !important;
            }
            tr.highlighted td {
                background: red;
            }
        </style>
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
                        <a href="#" title="REGISTRO DE EJECUCION"> <span class="menu-item-parent">EVALUACI&Oacute;N POA</span></a>
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
                    <li>Registro de Evaluaci&oacute;n</li><li>Evaluaci&oacute;n POA</li><li>Mis Operaciones</li>
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
                            <div class="well">
                                <div class="btn-group btn-group-justified">
                                    <center>
                                <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/eval/rep_eval_productos/'.$componente[0]['com_id'];?>');">IMPRIMIR EVALUACI&Oacute;N</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/eval/rep_eval_productos_consolidado/'.$componente[0]['com_id'];?>');">CONSOLIDADO EVALUACI&Oacute;N</a></li>
                                  <?php
                                      if($this->session->userdata('tp_adm')==1){ ?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_tr" title="CAMBIAR TRIMESTRE">CAMBIAR TRIMESTRE</a></li>
                                        <?php
                                      }
                                    ?>
                                  <li role="presentation"><a href="<?php echo base_url().'index.php/eval/mis_operaciones'?>" title="VOLVER ATRAS" >SALIR</a></li>
                                </ul>
                              </div>
                              </center>
                                </div>
                            </div>
                        </article> 
                    </div>
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
                                    <h2>CUADRO DE EVALUACI&Oacute;N POA </h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N DE OPERACIONES</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EJECUCI&Oacute;N PRESUPUESTARIA</span></a>
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
                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="EFICACIA INSTITUCIONAL A NIVEL DISTRITAL">
                                               <div class="row">
                                                    <h2 class="alert alert-success" align="center">FORMULARIO DE EVALUACI&Oacute;N <?php echo $tmes[0]['trm_descripcion'];?></h2>
                                                   <div class="jarviswidget jarviswidget-color-darken" >
                                                      <header>
                                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                                          <h2 class="font-md"></h2>  
                                                      </header>
                                                      <div>
                                                        <div class="widget-body no-padding">
                                                          <?php echo $productos;?>
                                                        </div>
                                                       </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <!-- end s1 tab pane -->
                                            
                                            <div class="tab-pane fade" id="s2" title="CUADRO DE EFCIACIA">
                                             <br>
                                                <div align="right">
                                                    <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N DE OPERACIONES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <?php echo $calificacion;?>
                                                          <!-- <h2 class="alert alert-success" align="center"><?php echo $tmes[0]['trm_descripcion'];?></h2> -->
                                                        </div>
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

                                                    <hr>

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

                                            <div class="tab-pane fade" id="s3" title="CUADRO DE EJECUCI&Oacute;N PRESUPUESTARIA">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <br>
                                                        <h2 class="alert alert-success" align="center">CUADRO DE EJECUCI&Oacute;N PRESUPUESTARIA - <?php echo $this->session->userData('gestion');?></h2>
                                                        <div class="row">
                                                            <?php echo $ppto_cert;?>
                                                        </div>
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
        <!-- ================ Modal EVALUAR PRODUCTO ================= -->

        <div class="modal fade" id="modal_nuevo_tr" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_trimestre">
            <div class="modal-content" >
                <div class="modal-body">
                    <form action="<?php echo site_url().'/eval/update_trimestre'?>" id="form_trimestre" name="form_trimestre" class="form-horizontal" method="post">
                        <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                        <h4 class="alert alert-info"><center>CAMBIAR TRIMESTRE - <?php echo $tmes[0]['trm_descripcion']; ?></center></h4>   
                        <fieldset>
                          <div class="form-group">
                              <div class="form-group">
                                  <label class="col-md-2 control-label">TRIMESTRE</label>
                                  <div class="col-md-10">
                                      <?php echo $list_trimestre;?>
                                  </div>
                              </div>
                          </div>
                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                              <div class="col-md-12" align="right">
                                <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                <button type="button" name="subir_formt" id="subir_formt" class="btn btn-info" >CAMBIAR TRIMESTRE</button>
                                <center><img id="loadt" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                              </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if(count($verif_eval_ncum)==0){ ?>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">ES NECESARIO REALIZAR LA ACTUALIZACI&Oacute;N DE ACTIVIDADES PROGRAMADAS NO CUMPLIDAS EN EL TRIMESTRE</h4>
                  </div>
                  <form action="<?php echo site_url().'/ejecucion/cevaluacion/update_evaluacion'?>" id="fupdate_eval" name="fupdate_eval"  method="post">
                    <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                  <div class="modal-footer">
                    <div id="but_eval" style="display: block;">
                        <button type="button" name="update_eval" id="update_eval" class="btn btn-default">ACTUALIZAR EVALUACI&Oacute;N</button><br>
                    </div>
                    <div id="load_ev" style="display: none" align="center">
                        <br><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>ACTUALIZANDO ACTIVIDADES NO CUMPLIDAS ....</b>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            <?php
        }
        ?>
        <div id="areaImprimir_eval">
         <?php echo $print_tabla;?>
        </div>
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
        <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 
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
        <script>
            document.getElementById("cl").addEventListener("click", function(){
            window.location.reload(true);
          });
            document.getElementById("mcl").addEventListener("click", function(){
            window.location.reload(true);
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
                text: '<?php echo $componente[0]['com_componente'];?>'
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
                        name: '% META PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS',
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
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
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
                        name: '% META PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS',
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
                text: '<?php echo $componente[0]['com_componente'];?>'
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
        $(function () {
            $("#subir_formt").on("click", function () {
              val=document.getElementById("trimestre_usu").value;

              if(val!=0 & val!=''){
                if(document.getElementById("tmes").value!=document.getElementById("trimestre_usu").value){
                  alertify.confirm("CAMBIAR TRIMESTRE ?", function (a) {
                      if (a) {
                          document.getElementById("loadt").style.display = 'block';
                          document.getElementById('subir_formt').disabled = true;
                          document.forms['form_trimestre'].submit();
                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                      }
                  });
                }
                else{
                  alertify.success("TRIMESTRE SELECCIONADO");
                }
              }
              else{
                alertify.error("SELECCIONE TRIMESTRE");
              }
                
            });
        });
    </script>

        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
        <script type="text/javascript">
            $("#update_eval").on("click", function () {
                var $valid = $("#fupdate_eval").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    document.getElementById("load_ev").style.display = 'block';
                    document.forms['fupdate_eval'].submit();
                    document.getElementById("but_eval").style.display = 'none';
                }
            });
        </script>
        <script type="text/javascript">
            <?php
                if(count($verif_eval_ncum)==0){ ?>
                    $(document).ready(function(){
                     $('#myModal').modal({
                        backdrop: false,
                        show: true
                      });

                      $('.modal-dialog').draggable({
                        handle: ".modal-header"
                      });
                    });
                    <?php
                }
            ?>
            
            $(document).ready(function() {
                pageSetUp();
                $("#menu").menu();
                $('.ui-dialog :button').blur();
                $('#tabs').tabs();
            })
        </script>
    </body>
</html>
