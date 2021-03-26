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
            tr:hover td { background: #dce7f9; color: #339; }
            input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
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
                    <li>Mantenimiento</li><li>Partidas</li>
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
                                  <h1>RESPONSABLE : <?php echo $resp; ?> -> <small><?php echo $res_dep;?></small>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                            <section id="widget-grid" class="well">
                              <center>
                                <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/1';?>');">IMPRIMIR ACTIVIDADES</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/2';?>');">IMPRIMIR SUB ACTIVIDADES</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/3';?>');">VER SUBACTIVIDAD</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/4';?>');">VER UNIDAD DE MEDIDA</a></li>
                                </ul>
                              </div>
                              </center>
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
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                    <div class="jarviswidget jarviswidget-color-darken">
                                      <header>
                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                          <h2 class="font-md"><strong>PARTIDAS - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                                      </header>
                                        <div>
                                            <div class="widget-body no-padding">
                                                <?php echo $partidas;?>
                                            </div>
                                            <!-- end widget content -->
                                        </div>
                                        <!-- end widget div -->
                                    </div>
                                    <!-- end widget -->
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                    <div class="jarviswidget jarviswidget-color-darken">
                                      <header>
                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                          <h2 class="font-md"><strong>UNIDADES DE MANEJO - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                                      </header>
                                        <div>
                                            <div class="widget-body no-padding">
                                                <?php echo $umedidas;?>
                                            </div>
                                            <!-- end widget content -->
                                        </div>
                                        <!-- end widget div -->
                                    </div>
                                    <!-- end widget -->
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

        <!-- ======== MODAL MOD UMEDIDA ========= -->
        <div class="modal fade" id="modal_mod_umedida" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/mantenimiento/partidas/valida_umedida'?>" id="form_mod" name="form_mod" class="form-horizontal" method="post">
                <input type="hidden" name="um_id" id="um_id">
                    <h2 class="alert alert-info"><center>UNIDAD DE MANEJO (Modificar)</center></h2>                           
                    <fieldset>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">UNIDAD DE MEDIDA</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="umedida" id="umedida" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">ABREVIATURA</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="abrev" id="abrev" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">ID ERP</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="erp_id" id="erp_id" >
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
        <!-- browser msie issue fix -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
        <!-- FastClick: For mobile devices -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- Demo purpose only -->
        <script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <!-- MODIFICAR UNIDAD DE MEDIDAS -->
        <script type="text/javascript">
            $(function () {
                $(".mod_um").on("click", function (e) {
                    um_id = $(this).attr('name');
                    document.getElementById("um_id").value=um_id;
                    var url = "<?php echo site_url().'/mantenimiento/partidas/get_umedida'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "um_id="+um_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        document.getElementById("umedida").value = response.unidad[0]['um_descripcion'];
                        document.getElementById("abrev").value = response.unidad[0]['um_abrev'];
                        document.getElementById("erp_id").value = response.unidad[0]['erp_id'];
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE LA UNIDAD DE MANEJO");
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
                                um_id: { //// 
                                required: true,
                                },
                                umedida: { ////
                                    required: true,
                                },
                                abrev: { //// 
                                    required: true,
                                }
                            },
                            messages: {
                                umedida: "<font color=red>REGISTRE DATO</font>",
                                abrev: "<font color=red>REGISTRE DATO</font>",                    
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

                            alertify.confirm("MODIFICAR DATOS UNIDAD DE MANEJO ?", function (a) {
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

        <!-- AGREGAR NUEVA ACTIVIDAD -->
        <script type="text/javascript">
        $(function () {
            $("#subir_form").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                        rules: {
                            dep_id: { //// codigo
                            required: true,
                            },
                            ue_id: { //// descripcion Actividad
                                required: true,
                            },
                            codigo: { //// Codigo
                                required: true,
                            },
                            descripcion: { //// Programa
                                required: true,
                            },
                            te: { //// Establecimiento
                                required: true,
                            }
                        },
                        messages: {
                            dep_id: "<font color=red>SELECCIONE REGIONAL</font>",
                            ue_id: "<font color=red>SELECCIONE UNIDAD EJECUTORA</font>", 
                            codigo: "<font color=red>REGISTRE CÓDIGO</font>", 
                            descripcion: "<font color=red>REGISTRE DESCRIPCIÓN</font>", 
                            te: "<font color=red>SELECCIONE TIPO DE ESTABLECIMIENTO</font>",                     
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

                    alertify.confirm("GUARDAR DATOS DE UNIDAD, ESTABLECIMIENTO, COMPRA DE SERVICIO ?", function (a) {
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
                    var name = $(this).attr('name');
                    var request;
                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR ACTIVIDAD INSTITUCIONAL?", function (a) {
                        if (a) { 
                            url = "<?php echo site_url("")?>/mnt/delete_actividad";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "act_id="+name

                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("EL OBJETIVO ESTRATEGICO SE ELIMINO CORRECTAMENTE ", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    })
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
        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
    </body>
</html>

