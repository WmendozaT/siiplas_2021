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
                window.open(direccion, "Reporte Cuadro Comparativo de Partidas" , "width=800,height=650,scrollbars=SI") ;                                                                 
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
                        <a href="#" title="REPORTE GERENCIAL"> <span class="menu-item-parent">MANTENIMIENTO</span></a>
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
                    <li>Mantenimiento</li><li>Ptto. Asignado POA</li><li>....</li><li>Cuadro Comparativo POA - PRESUPUESTO (Final)</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
            <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <section id="widget-grid" class="well">
                                <div class="" title="aper : <?php echo $proyecto[0]['aper_id'];?>">
                                    <?php
                                        if($proyecto[0]['tp_id']==4){ ?>
                                            <h1> <?php echo $proyecto[0]['tipo_adm'];?> : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' - '.$proyecto[0]['abrev'];?></small></h1>
                                            <?php
                                        }
                                        else{ ?>
                                            <h1> PROYECTO DE INVERSI&Oacute;N : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small></h1>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                            <section id="widget-grid" class="well">
                              <style type="text/css">#graf{font-size: 80px;}</style> 
                              <center>
                                <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:100%;" data-toggle="dropdown" aria-expanded="true">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_mod_req/'.$proyecto[0]['proy_id'].'' ?>');">IMPRIMIR MODIFICACI&Oacute;N REQUERIMIENTO</a></li>
                                  <li role="presentation"><a href="<?php echo site_url("").'/ptto_asig_poa'; ?>" style="width:100%;" title="Volver Atras">VOLVER ATRAS</a></li>
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
                            <?php echo $boton; ?>
                            <div class="well well-sm well-light">
                                <br>
                                <b>BUSCAR PARTIDA : </b><input type="text" class="form-control" id="kwd_search" value="" style="width:40%;"/><br>
                                <?php echo $partidas;?>
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

        <!-- MODAL NUEVO REGISTRO DE ACTIVIDADES   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url("").'/mantenimiento/cptto_poa/valida_add_partida'?>"  id="form_nuevo2" name="form_nuevo2" class="form-horizontal" method="post">
                        <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                        <h2 class="alert alert-info"><center>AGREGAR NUEVA PARTIDA</center></h2>                           
                        <fieldset>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">PARTIDA</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="par_id" name="par_id" title="SELECCIONE PARTIDA">
                                                <option value="0">Seleccione Grupo Partida</option>
                                                <?php 
                                                    foreach($list_partidas as $row){ ?>
                                                    <option value="<?php echo $row['par_id'];?>"><?php echo $row['par_codigo'].' - '.$row['par_nombre'];?></option>
                                                        <?php
                                                    } ?>        
                                            </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">MONTO ASIGNADO</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="monto" id="monto" onkeyup="verif();" value="0">
                                    </div>
                                </div>
                            </div>
                            
                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                                <div id="but">
                                    <div class="col-md-12">
                                        <div id="abut" style="display:none;">
                                            <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                            <button type="button" name="subir_form2" id="subir_form2" class="btn btn-info" >GUARDAR NUEVA PARTIDA</button>
                                            <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>


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
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
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

                $(".update_ff").on("click", function (e) {
                    reset();
                    var sp_id = $(this).attr('name');
                    var partida = $(this).attr('id');
                    var monto= parseFloat($('[name="monto'+sp_id+'"]').val()); //// total
                    var request;

                    // confirm dialog
                    alertify.confirm("AJUSTAR PRESUPUESTO DE LA PARTIDA "+partida+" ?", function (a) {
                        if (a) { 
                            var url = "<?php echo site_url().'/mantenimiento/cptto_poa/update_ppto_aprobado'?>";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "sp_id="+sp_id+"&ppto="+monto
                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("EL PRESUPUESTO SE AJUSTO CORRECTAMENTE ", function (e) {
                                        if (e) {
                                            document.getElementById("load"+sp_id).style.display = 'block';
                                         //   document.getElementById(+sp_id).value = "AJUSTANDO MONTO"
                                            window.location.reload(true);
                                        }
                                    })
                                } else {
                                    alertify.error("Error al Ajustar");
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


                $(".add_ff").on("click", function (e) {
                    reset();
                    var sp_id = $(this).attr('name');
                    var partida = $(this).attr('id');
                    var monto= parseFloat($('[name="monto'+sp_id+'"]').val()); //// total
                    var request;

                    // confirm dialog
                    alertify.confirm("AJUSTAR PRESUPUESTO DE LA PARTIDA "+partida+" ?", function (a) {
                        if (a) { 
                            var url = "<?php echo site_url().'/mantenimiento/cptto_poa/add_ppto_aprobado'?>";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "sp_id="+sp_id+"&ppto="+monto
                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("SE ADICIONO  CORRECTAMENTE ", function (e) {
                                        if (e) {
                                            document.getElementById("loadd"+sp_id).style.display = 'block';
                                         //   document.getElementById(+sp_id).value = "AJUSTANDO MONTO"
                                            window.location.reload(true);
                                        }
                                    })
                                } else {
                                    alertify.error("Error al Ajustar");
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

            function verif(){ 
                partida = $('[name="par_id"]').val(); /// Codigo sub act1
                monto = $('[name="monto"]').val(); /// Codigo sub act2
                if(partida!=null & (monto!='' & monto!=0 & monto>=50)){
                    $('#abut').slideDown();
                }
                else{
                    $('#abut').slideUp();
                }
            }

        </script>

        <script type="text/javascript">
        $(function () {
            $("#subir_form2").on("click", function () {
                var $valid = $("#form_nuevo2").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("AGREGAR PARTIDA ?", function (a) {
                        if (a) {
                            document.getElementById("load").style.display = 'block';
                            document.getElementById('subir_form2').disabled = true;
                            document.forms['form_nuevo2'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
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
