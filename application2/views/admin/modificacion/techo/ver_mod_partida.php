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
            <!--para las alertas-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <meta name="viewport" content="width=device-width">
        <!--fin de stiloh-->
          <script>
            function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Proyectos" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }                                          
          </script>
            <style>
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
            hr {border: 0; height: 12px; box-shadow: inset 0 12px 12px -12px green;}
            #col{
              color: #1c7368;
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
                        <a href="#" title="REPORTE GERENCIAL"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
                    <li>....</li><li>Mis Operaciones</li><li>T&eacute;cnico de Unidad Ejecutora</li><li>Modificar Techo Presupuestario</li>
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
                                    <?php
                                        if($proyecto[0]['tp_id']==4){ ?>
                                            <h1> UNIDAD ORGANIZACIONAL : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small></h1>
                                            <?php
                                        }
                                        else{ ?>
                                            <h1> PROYECTO DE INVERSI&Oacute;N : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small></h1>
                                            <?php
                                        }
                                    ?>
                                    <h1> PRESUPUESTO ASIGNADO - <?php echo $this->session->userdata("gestion");?></h1>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <section id="widget-grid" class="well">
                                <a href="<?php echo site_url("").'/ptto_asig_poa'; ?>" class="btn btn-success" title="Lista de Unidades" style="width:100%;">VOLVER ATRAS</a>
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

                            <div class="well">
                            <div class="row">
                                <form action="<?php echo site_url("").'/mantenimiento/cptto_poa/valida_update_partidas_mod'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                            <!-- <form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/mantenimiento/cptto_poa/valida_update_partidas_mod'?>" class="smart-form" method="post"> -->
                                <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
                                    <center><font size="3"><b>DATOS DE CITE</b></font></center><hr>
                                    <fieldset>
                                        <section>
                                            <div class="row" title="<?php echo $proyecto[0]['aper_id'];?>">
                                                <label class="label col col-2">CITE</label>
                                                <div class="col col-10">
                                                    <label class="input"> <i class="icon-append fa fa-user"></i>
                                                        <input type="text" name="cite" id="cite" placeholder="XX-XX-XXX">
                                                    </label>
                                                </div>
                                            </div>
                                        </section>
                                        <section>
                                            <div class="row">
                                                <label class="label col col-2">FECHA</label>
                                                <div class="col col-10">
                                                    <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                    <input type="text" name="fm" id="fm" class="form-control datepicker" data-dateformat="dd/mm/yy" onKeyUp="this.value=formateafecha(this.value);" placeholder="dd/mm/YY">
                                                </label>
                                                </div>
                                            </div>
                                        </section>
                                    </fieldset>
                                    <hr>
                                    <fieldset>
                                        <section>
                                            <div class="row">
                                                <label class="label col col-2">BUSCADOR</label>
                                                <div class="col col-10">
                                                    <label class="input">
                                                        <input type="text" class="form-control" id="kwd_search" value="" style="width:100%;" placeholder="Buscador de Partidas"/>
                                                    </label>
                                                </div>
                                            </div>
                                        </section>
                                    </fieldset>
                                    <hr>
                                </article>

                                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                    <center><font size="3"><b>TECHO PRESUPUESTARIO - PARTIDAS ASIGNADAS</b></font></center><hr>
                                    <?php echo $partidas_asig;?>
                                </article>
                                <footer>
                                    <div id="but" style="display:none;">
                                        <button type="button" name="add_form" id="add_form" class="btn btn-primary">MODIFICAR TECHO PRESUPUESTARIO</button>
                                    </div>
                                </footer>
                            </form> 
                            </div>
                        </div>
                        </article>
                        <!-- WIDGET END -->
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
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
        <!-- Voice command : plugin -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script type="text/javascript">
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function() {
            pageSetUp();
        })
        </script>
        <script type="text/javascript">
            function update_partidas(){
                alertify.confirm("DESEA MODIFICAR TECHO PRESUPUESTARIO ?", function (a) {
                    if (a) {
                        document.getElementById("btsubmit").value = "ACTUALIZANDO TECHO PRESUPUESTARIO...";
                        document.getElementById("btsubmit").disabled = true;
                        document.formulario.submit();
                        return true;
                    } 
                    else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });    
            }
        </script>
        <script type="text/javascript">
        function validarFormatoFecha(campo) {
          var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
          if ((campo.match(RegExPattern)) && (campo!='')) {
                return true;
          } else {
                return false;
          }
        }

        function existeFecha(fecha){
          var fechaf = fecha.split("/");
          var day = fechaf[0];
          var month = fechaf[1];
          var year = fechaf[2];
          var date = new Date(year,month,'0');
          if((day-0)>(date.getDate()-0)){
                return false;
          }
          return true;
        }
        $(function () {
            $("#add_form").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                    rules: {
                        cite: { //// Cite
                            required: true,
                        },
                        fm: { //// Fecha de Solicitud
                            required: true,
                        }
                    },
                    messages: {
                        cite: "<font color=red>REGISTRE NRO CITE</font>",
                        fm: "<font color=red>SELECCIONE FECHA CITE</font>",                          
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
                    
                    proy_id = document.getElementById('proy_id').value;
                    cite = document.getElementById('cite').value;
                    fecha = document.getElementById('fm').value;
                    if(validarFormatoFecha(fecha)){
                          if(existeFecha(fecha)){
                                alertify.confirm("DESEA REALIZAR MODIFICACI\u00D3N DEL TECHO PRESUPUESTARIO ?", function (a) {
                                    if (a) {
                                        document.getElementById('add_form').disabled = true;
                                        document.forms['form_nuevo'].submit();
                                    } else {
                                        alertify.error("OPCI\u00D3N CANCELADA");
                                    }
                                });
                          }else{
                                alertify.error("La fecha introducida no existe.");
                          }
                    }else{
                          alertify.error("El formato de la fecha es incorrecto.");
                    }
                }
            });
        });
        </script>

        <script type="text/javascript">
        $(document).ready(function(){
          $("#kwd_search").keyup(function(){
            if( $(this).val() != ""){
              // Show only matching TR, hide rest of them
              $("#table tbody>tr").hide();
              $("#table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
            }
            else{
              $("#table tbody>tr").show();
            }
          });
        });
        $.extend($.expr[":"], 
        {
            "contains-ci": function(elem, i, match, array){
            return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
          }
        });
        </script>
    </body>
</html>
