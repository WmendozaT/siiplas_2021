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
            <script>
            function abreVentana(PDF){
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte Unidad Organizacional" , "width=800,height=650,scrollbars=SI") ;
            }                                                 
            </script>
            <style>
            .table1{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
              }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
              width: 95% !important;
            }
            </style>
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
                    <li>Mantenimiento</li><li>Programas</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section id="widget-grid" class="well">
                                <h2><b>CATEGORIAS PROGRAMATICAS GESTI&Oacute;N - <?php echo $this->session->userData('gestion');?></b></h2>
                            </section>
                        </article>

                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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
                            <div class="well well-sm well-light">
                                <div class="row">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                        <div class="jarviswidget jarviswidget-color-darken">
                                          <header>
                                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                              <h2 class="font-md"></h2>  
                                          </header>
                                            <div>
                                                <div class="widget-body no-padding">
                                                    <?php echo $lista;?>
                                                </div>
                                                <!-- end widget content -->
                                            </div>
                                            <!-- end widget div -->
                                        </div>
                                        <!-- end widget -->
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                    </article>
                                </div>
                            </div>
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


        <!-- MODAL NUEVO REGISTRO PROGRAMA   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url().'/mantenimiento/capertura_programatica/valida_programa'?>" id="form_nuevo" name="form_nuevo" class="form-horizontal" method="post">
                        <input type="hidden" name="tp" id="tp" value="1">
                        <h2 class="alert alert-info"><center>APERTURA PROGRAMATICA (Agregar)</center></h2>                           
                        <fieldset>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">PROGRAMA</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" maxlength="3" size="3" name="prog" id="prog">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">DESCRIPCI&Oacute;N</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="desc" id="desc">
                                    </div>
                                </div>
                            </div>
                        </fieldset>                   
                        <div class="form-actions">
                            <div class="row">
                                <div>
                                    <div class="col-md-12">
                                       <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                       <button type="button" name="subir_form" id="subir_form" class="btn btn-info" >GUARDAR INFORMACI&Oacute;N</button>
                                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  ======================================  -->

        <!-- ======== MODAL UPDATE PROGRAMA ========= -->
        <div class="modal fade" id="modal_mod_programa" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/mantenimiento/capertura_programatica/valida_programa'?>" id="form_mod" name="form_mod" class="form-horizontal" method="post">
                    <input type="hidden" name="aper_id" id="aper_id">
                    <input type="hidden" name="tp" id="tp" value="2">
                    <h2 class="alert alert-info"><center>APERTURA PROGRAMATICA (Modificar)</center></h2>                           
                    
                    <fieldset>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">PROGRAMA</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" maxlength="2" size="2" name="mprog" id="mprog">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">DESCRIPCI&Oacute;N</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="mdesc" id="mdesc">
                                </div>
                            </div>
                        </div>
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
                                <button type="button" name="mod_ffenviar" id="mod_ffenviar" class="btn btn-info" >MODIFICAR INFORMACI&Oacute;N</button>
                                <center><img id="loadd" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

        <!--  =====================================================  -->
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
        <!-- FastClick: For mobile devices -->
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- AGREGAR NUEVA ACTIVIDAD -->
        <script type="text/javascript">
        $(function () {
            $("#subir_form").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                        rules: {
                            prog: { //// codigo
                                required: true,
                            },
                            desc: { //// descripcion subActividad
                                required: true,
                            }
                        },
                        messages: {
                            prog: "<font color=red>REGISTRE PROGRAMA</font>",
                            desc: "<font color=red>REGISTRE DESCRIPCIÓN PROGRAMA</font>",                   
                        },
                        highlight: function (element) {
                            $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                        },
                        unhighlight: function (element) {
                            $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                        },
                        errorElement: 'span',
                        errorClass: 'help-block',
                        errorPlacement: function (error, element) {
                            if (element.parent('.input-group').length) {
                                error.insertAfter(element.parent());
                            } else {
                                error.insertAfter(element);
                            }
                        }
                    });

                var $valid = $("#form_nuevo").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("GUARDAR DATOS DE PROGRAMA ?", function (a) {
                        if (a) {
                            document.getElementById("load").style.display = 'block';
                            document.getElementById('subir_form').disabled = true;
                            document.forms['form_nuevo'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>
        <!-- MODIFICAR UNIDAD DE MEDIDAS -->
        <script type="text/javascript">
            $(function () {
                $(".mod_prog").on("click", function (e) {
                    aper_id = $(this).attr('name');
                    document.getElementById("aper_id").value=aper_id;
                    var url = "<?php echo site_url().'/mantenimiento/capertura_programatica/get_dato_programa'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "aper_id="+aper_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        document.getElementById("mprog").value = response.programa[0]['aper_programa'];
                        document.getElementById("mdesc").value = response.programa[0]['aper_descripcion'];
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                    // =============================VALIDAR EL FORMULARIO DE MODIFICACION
                    $("#mod_ffenviar").on("click", function (e) {
                        var $validator = $("#form_mod").validate({
                               rules: {
                                aper_id: { //// 
                                required: true,
                                },
                                mprog: { ////
                                    required: true,
                                },
                                mdesc: { //// 
                                    required: true,
                                }
                            },
                            messages: {
                                mprog: "<font color=red>REGISTRE DATO</font>",
                                mdesc: "<font color=red>REGISTRE DATO</font>",                    
                            },
                            highlight: function (element) {
                                $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                            },
                            unhighlight: function (element) {
                                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                            },
                            errorElement: 'span',
                            errorClass: 'help-block',
                            errorPlacement: function (error, element) {
                                if (element.parent('.input-group').length) {
                                    error.insertAfter(element.parent());
                                } else {
                                    error.insertAfter(element);
                                }
                            }
                        });
                        var $valid = $("#form_mod").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {

                            alertify.confirm("MODIFICAR DATOS DEL PROGRAMA ?", function (a) {
                                if (a) {
                                    document.getElementById("loadd").style.display = 'block';
                                    document.getElementById('mod_ffenviar').disabled = true;
                                    document.forms['form_mod'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>
         <!-- ELIMINAR ACTIVIDAD INSTITUCIONAL -->
        <script type="text/javascript">
            $(function () {
                function reset() {
                    $("#toggleCSS").attr("href", "<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css");
                    alertify.set({
                        labels: {
                            ok: "ACEPTAR",
                            cancel: "CANCELAR"
                        },
                        delay: 5000,
                        buttonReverse: false,
                        buttonFocus: "ok"
                    });
                }

                $(".del_ff").on("click", function (e) {
                    reset();
                    var aper_id = $(this).attr('name');
                    var request;
                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR PROGRAMA ?", function (a) {
                        if (a) { 
                            var url = "<?php echo site_url().'/mantenimiento/capertura_programatica/delete_dato_programa'?>";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "aper_id="+aper_id

                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    window.location.reload(true);
                                    alertify.success("Eliminacion correcta !!!");
                                    
                                } else {
                                    alertify.error("Error al Eliminar");
                                }
                            });
                            request.fail(function (jqXHR, textStatus, thrown) {
                                console.log("ERROR: " + textStatus);
                            });
                            request.always(function () {
                                //console.log("termino la ejecuicion de ajax");
                            });

                            e.preventDefault();

                        } else {
                            // user clicked "cancel"
                            alertify.error("Opcion cancelada");
                        }
                    });
                    return false;
                });

            });
        </script>
    </body>
</html>

