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
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <meta name="viewport" content="width=device-width">
        <?php echo $style;?>
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
                    <li>Plan Operativo Anual <?php echo $this->session->userdata('gestion');?></li><li>Ejecución Presupuestaria</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
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
                        <?php echo $formulario;?>
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
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



        <!-- ============ Modal Subir Fotos del Proyecto ========= -->
        <div class="modal fade" id="modal_mod_fotos" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="csv">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <h2 class="alert alert-info"><center>SUBIR FOTO DEL PROYECTO</center></h2>
                        
                        <form class="form-horizontal" action="<?php echo site_url().'/ejecucion/cejecucion_pi/add_img' ?>" method="post" enctype="multipart/form-data" id="form_subir_img" name="form_subir_img">
                            <input type="hidden" name="p_id" id="p_id">
                            <fieldset>
                                <legend><div id="proyecto"></div></legend>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><b>Imagen</b></label>
                                    <div class="col-md-10">
                                        <input type="file" class="btn btn-default" id="archivo" name="archivo" accept="image/png, .jpeg, .jpg, .png, .PNG, image/gif" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());">
                                        <p class="help-block">
                                            Seleccione foto del proyecto.
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><b>Descripcion de la Imagen</b></label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="detalle_imagen" id="detalle_imagen" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2 control-label"><b>imagen portada para la Ficha Técnica ?</b></label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="tp_img" name="tp_img" title="SELECCIONE OPCION">
                                          <option value="0">1.- NO</option>
                                          <option value="1">2.- SI (Imprimir en Ficha Técnica)</option>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
                                        <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-info">SUBIR ARCHIVO</button>
                                    </div>
                                </div>
                              </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- ============ Modal Avances del Proyecto (IMAGENES)========= -->
        <div class="modal fade" id="modal_mod_imagenes" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="imagenes_pi">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <h2 class="alert alert-info"><center>GALERIA - AVANCE DEL PROYECTO</center></h2>
                        
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form_subir_img" name="form_subir_img">
                            <input type="hidden" name="id_proy" id="id_proy">
                            <fieldset>
                                <legend><div id="dat_proyecto"></div></legend>
                                <div id="lista_galeria"></div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- ============ Modal EJECUCION PRESUPUESTARIA A NIVEL DEL PROYECTO========= -->
        <div class="modal fade" id="modal_mod_ejec_ppto" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="imagenes_pi">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <h2 class="alert alert-info"><center>EJECUCIÓN PRESUPUESTARIA</center></h2>
                        
                        <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form_subir_img" name="form_subir_img">
                            <input type="hidden" name="id" id="id">
                            <fieldset>
                                <legend><div id="datos_proyecto"></div></legend>
                                <div id="ejec_mensual" style="width: 1000px; height: 680px; margin: 0 auto"></div></div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <hr>
                                        <div id="detalle_ejecucion"></div>
                                        <br>
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>


    <!-- ============ Modal ejecucion Presupuestaria ========= -->
    <div class="modal fade" id="modal_mod_ppto_pi" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" id="ejecucion_ppto">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>REGISTRO DE EJECUCÍON PROYECTO DE INVERSIÓN - <?php echo $this->verif_mes[2].' / '.$this->session->userData('gestion') ?></center></h2>
            
                <form class="form-horizontal" action="<?php echo site_url().'/ejecucion/cejecucion_pi/valida_update_pi'?>" method="post" id="form_ejec" name="form_ejec">
                    <input type="hidden" name="proy_id" id="proy_id">
                    <fieldset>
                        <legend>DATOS GENERALES DEL PROYECTO</legend>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Código SISIN</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="cod_sisin" id="cod_sisin"  disabled=true>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Proyecto de Inversión</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="proy_nombre" id="proy_nombre" rows="3" disabled=true></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Costo Total Proyecto</label>
                            <div class="col-md-10">
                                <input class="form-control" name="ppto_total" id="ppto_total" type="text" disabled=true>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Fase Proyecto</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="fase" id="fase" rows="2" disabled=true></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Estado del Proyecto</label>
                            <div class="col-md-10">
                                <div id="estado"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Avance Físico (%)</label>
                            <div class="col-md-10">
                                <input class="form-control" name="ejec_fis" id="ejec_fis" type="text" onkeypress="if (this.value.length < 4) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset>
                        <legend>EJECUCIÓN PRESUPUESTARIA - GESTIÓN : <?php echo $this->verif_mes[2].' / '.$this->session->userData('gestion') ?></legend>
                        <div id="lista_partidas"></div>
                    </fieldset>


                    <div class="form-actions" id="button">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
                            <button type="button" name="subir_ejec" id="subir_ejec" class="btn btn-info">GUARDAR EJECUCION</button>
                        </div>
                    </div>
                  </div>
                  <div id="load"></div>
                </form>

            </div>
          </div>
        </div>
    </div>
    <!-- ======================================================== -->


    <!-- END PAGE FOOTER -->
    <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
    <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url(); ?>assets/js/plugin/pace/pace.min.js"></script>
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

    <!-- IMPORTANT: APP CONFIG -->
    <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>

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
    <!-- browser msie issue fix -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
    <!-- FastClick: For mobile devices -->
    <script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
    <!-- MAIN APP JS FILE -->
    <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
    <script src="<?php echo base_url(); ?>mis_js/ejec_proyectos/ejec_financiera_pi.js"></script> 
</html>
