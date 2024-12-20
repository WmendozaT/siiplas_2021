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
        <meta name="viewport" content="width=device-width">
        <!--fin de stiloh-->
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
                        <a href="#" title="MANTENIMIENTO"> <span class="menu-item-parent">MANTENIMIENTO</span></a>
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
                    <li>Mantenimiento</li><li>Eventos Planificacion</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <?php echo $eventos; ?>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
    <!-- ========================================================================================================= -->
        <!-- PAGE FOOTER -->
    <!-- ==== MODAL FORMULARIO DE PARTICIPANTES ==== -->
  <div class="modal fade" id="modal_nuevo_form2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio2">
      <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>REGISTRO PARTICIPANTE</center></h2>
              <form action="<?php echo site_url().'/mantenimiento/ceventos_dnp/valida_participante'?>" id="form_nuevo2" name="form_nuevo2" class="smart-form" method="post">
                  <fieldset>          
                    <div class="row">
                        <?php echo $input_id; ?>
                      <section class="col col-3">
                        <label class="label"><b>CI.</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="number" name="ci" id="ci" value="">
                        </label>
                      </section>
                      <section class="col col-6">
                        <label class="label"><b>NOMBRE COMPLETO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="text" name="nombre" id="nombre" value="">
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label"><b>TIPO CERT.</b></label>
                        <select class="form-control" id="tp_cert" name="tp_cert">
                            <option value="1">ORGANIZADOR</option>
                            <option value="2">EXPOSITOR</option>
                            <option value="3" selected>ASISTENTE</option>
                        </select>
                      </section>
                    </div>
                  </fieldset>
        
                  <div id="but">
                    <footer>
                      <button type="button" name="subir_participante" id="subir_participante" class="btn btn-info" >GUARDAR</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                    <div id="loadp2" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO INFORMACI&Oacute;N</b>
                    </div>
                  </div>
              </form>
              </div>
          </div>
      </div>
  </div>

    <!-- ==== MODAL FORMULARIO DE EVENTOS ==== -->
  <div class="modal fade" id="modal_nuevo_form1" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio1">
      <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>REGISTRO EVENTO / <?php echo $this->session->userData('gestion');?></center></h2>
              <form action="<?php echo site_url().'/mantenimiento/ceventos_dnp/valida_evento'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <fieldset>          
                    <div class="row">

                      <section class="col col-3">
                        <label class="label"><b>COD.</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="text" name="cod_evento" id="cod_evento" value="">
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label"><b>ORGANIZADOR</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="text" name="even_org" id="even_org" value="">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>TIPO EVENTO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="text" name="even_tp" id="even_tp" value="">
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label"><b>FECHA IMPRESION</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <input class="form-control"  type="text" name="even_fech_impresion" id="even_fech_impresion" value="">
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-6">
                        <label class="label"><b>TITULO DEL EVENTO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="evento" id="evento" title="TITULO DEL EVENTO"></textarea>
                        </label>
                      </section>
                      <section class="col col-6">
                        <label class="label"><b>FECHA DEL EVENTO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="fecha_even" id="fecha_even" title="FECHA DEL EVENTO"></textarea>
                        </label>
                      </section>
                   
                    </div>
                    
                  </fieldset>
        
                  <div id="but">
                    <footer>
                      <button type="button" name="subir_event" id="subir_event" class="btn btn-info" >GUARDAR</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                    <div id="loadp" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO INFORMACI&Oacute;N</b>
                    </div>
                  </div>
              </form>
              </div>
          </div>
      </div>
    </div>

    <!-- subir listado de participantes --> 
        <div class="row">
            <div class="modal fade" id="exampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" id="csv">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                        </div>
                        <div class="modal-body">
                            <h2><center>SUBIR ARCHIVO .CSV</center></h2>
                        
                            <div class="row">
                                  <form action="<?php echo site_url().'/mantenimiento/ceventos_dnp/importar_participantes' ?>" method="post" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep">
                                    <?php echo $input_id; ?>
                                    <div class="input-group">
                                      <span class="input-group-btn">
                                        <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
                                        <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
                                        <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
                                      </span>
                                      <span class="form-control"></span>
                                    </div>
                                    <hr>
                                    <div >
                                        <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR ARCHIVO.CSV</button><br>
                                        <center><img id="loads1" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                    </div>
                                  </form> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
                </div>
            </div>
        </div>
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
        <script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
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
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/eventos/eventos.js"></script> 
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
<!--        <script type="text/javascript">
        $(function () {
            $("#subir_archivo").on("click", function () {
                var $valid = $("#form_subir_sigep").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    if(document.getElementById('archivo').value==''){
                        alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
                        return false;
                    }
                   
                    archivo = document.getElementById('archivo').value;
                    alertify.confirm("SUBIR ARCHIVO ?", function (a) {
                        if (a) {
                            document.getElementById("loads").style.display = 'block';
                            document.getElementById('subir_archivo').disabled = true;
                            document.getElementById("subir_archivo").value = "Subiendo Archivo...";
                            document.forms['form_subir_sigep'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script> -->
    </body>
</html>
