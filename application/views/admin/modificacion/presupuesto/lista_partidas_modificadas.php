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
                window.open(direccion, "Reporte Modificación Presupuestaria" , "width=800,height=650,scrollbars=SI") ;
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
                    <li>Modificaciones</li><li>Modificaci&oacute;n Presupuestaria</li><li>Partidas Modificadas</li>
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
                                    <h1>DIRECCI&Oacute;N ADMINISTRATIVA : <small><b><?php echo $cite[0]['dep_cod'].' .- '.strtoupper($cite[0]['dep_departamento']);?></b></small>
                                    <h1>UNIDAD EJECUTORA : <small><b><?php echo $cite[0]['dist_cod'].' .- '.strtoupper($cite[0]['dist_distrital']);?></b></small>
                                    <h1>RESOLUCI&Oacute;N RD : <small><b><?php echo $cite[0]['resolucion'];?></b></small>
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
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mod_ppto/rep_mod_ppto/'.$cite[0]['mp_id'].'';?>');">IMPRIMIR MODIFICACIÓN PRESUPUESTARIA</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mod_ppto/list_mod_ppto';?>');">VOLVER ATRAS</a></li>
                                </ul>
                              </div>
                              </center>
                            </section>
                        </article>

                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="well well-sm well-light">
                                <div class="row">
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                    <div class="jarviswidget jarviswidget-color-darken">
                                      <header>
                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                          <h2 class="font-md"><strong>PARTIDAS PRESUPUESTARIAS A SER DISMINUIDAS</strong></h2>  
                                      </header>
                                        <div>
                                            <div class="widget-body no-padding">
                                                <?php echo $disminuir;?>
                                            </div>
                                            <!-- end widget content -->
                                        </div>
                                        <!-- end widget div -->
                                    </div>
                                    <!-- end widget -->
                                    </article>
                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                    <div class="jarviswidget jarviswidget-color-darken">
                                      <header>
                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                          <h2 class="font-md"><strong>PARTIDAS PRESUPUESTARIAS A SER INCREMENTADAS</strong></h2>  
                                      </header>
                                        <div>
                                            <div class="widget-body no-padding">
                                                <?php echo $incrementar;?>
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
                    var mpa_id = $(this).attr('name');
                    //alert(mpa_id)
                    var partida = $(this).attr('id');
                    var request;

                    // confirm dialog
                    alertify.confirm("APROBAR MODIFICACIÓN PRESUPUESTARIA DE LA PARTIDA "+partida+" ?", function (a) {
                        if (a) { 
                            var url = "<?php echo site_url().'/modificaciones/cmod_presupuestario/update_mod_ppto'?>";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "mpa_id="+mpa_id
                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("LA MODIFICACIÓN PRESUPUESTARIA SE MODIFICO CORRECTAMENTE ", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    })
                                } else {
                                    alertify.error("Error al Modificar");
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
                            alertify.error("OPCIÓN CANCELADA");
                        }
                    });
                    return false;
                });

            });
        </script>

        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
    </body>
</html>

