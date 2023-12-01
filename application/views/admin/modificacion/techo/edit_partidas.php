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
            table{font-size: 12px;
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
                        <a href="#" title="MODIFICACIONES"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
                    <li>Modificacion POA</li><li>Techo Presupuestario</li><li>Mis Partidas Asignados</li>
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
                                    <?php echo $titulo; ?>
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
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mod/rep_mod_techo/'.$cite[0]['cppto_id'].'' ?>');" title="IMPRIMIR MODIFICACI&Oacute;N TECHO">IMPRIMIR MOD. TECHO PRESUPUESTARIO</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/proy/ptto_consolidado_comparativo/'.$cite[0]['proy_id'].'' ?>');" title="CUADRO COMPARATIVO PRESUPUESTARIO">CUADRO COMPARATIVO PRESUPUESTARIO</a></li>
                                    <li role="presentation"><a onclick="cerrar()" class="btn btn-danger" style="width:100%;" title="Cerrar Modificacion"><font color="#ffffff">CERRAR MODIFICACI&Oacute;N</font></a></li>
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

                            <div class="well">
                            <div class="row">
                                <form action="<?php echo site_url("").'/modificaciones/cmod_requerimientos/valida_update_partidas_mod'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                                <input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cppto_id'];?>">
                                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <fieldset>
                                    <center><font size="3"><b>TECHO PRESUPUESTARIO - PARTIDAS ASIGNADAS</b></font></center><br>
                                    <?php echo $partidas_asig;?>
                                    </fieldset>
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

        <!-- ============ Modal Reversion de Saldos ========= -->
        <div class="modal fade" id="modal_add_saldo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document" class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                    </div>
                  <div class="modal-body">
                        <form action="<?php echo site_url().'/modificaciones/cmod_requerimientos/guardar_saldo_ppto'?>" method="post" id="form_saldo" name="form_saldo" class="smart-form">
                            <input type="text" name="sp_id" id="sp_id">
                            <input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cppto_id'];?>">
                            <header>
                                <b><div id="titulo"></div></b>
                            </header>
                            <fieldset>
                                 <div class="row">
                                    <section >
                                        <label class="label"><b>SALDO A REVERTIR</b></label>
                                        <label class="input">
                                            <i class="icon-append fa fa-tag"></i>
                                            <input class="form-control" type="text" name="saldo" id="saldo" value="0" onkeypress="return justNumbers(event);" onpaste="return false">
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                            <div class="row">
                                <div id="mbut">
                                    <footer>
                                        <button type="button" name="subir_saldo" id="subir_saldo" class="btn btn-info">REGISTRAR</button>
                                        <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                                    </footer>
                                </div>
                                <div id="load_saldo" style="display: none" align="center">
                                    <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>REGISTRANDO REVERSION PRESUPUESTARIA !!!</b>
                                </div>
                            </div>
                        </form>
                </div>
              </div>
            </div>
        </div>
     <!--  =============== -->


        <!-- MODAL NUEVO REGISTRO DE ACTIVIDADES   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url("").'/modificaciones/cmod_requerimientos/valida_add_partida'?>"  id="form_nuevo2" name="form_nuevo2" class="form-horizontal" method="post">
                        <input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cppto_id'];?>">
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
                                        <input class="form-control" type="text" name="monto" id="monto" onkeyup="verif();" value="0" onkeypress="return justNumbers(event);" onpaste="return false">
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
        <!--  =====================================================  -->

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
        /*------ MODIFICAR REQUERIMIENTO -----*/
          $(function () {
              $(".add_saldo").on("click", function (e) {
                sp_id = $(this).attr('name');
                document.getElementById("sp_id").value=sp_id;
            
                var url = "<?php echo site_url().'/modificaciones/cmod_requerimientos/get_partida'?>";
                  var request;
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: 'json',
                      data: "sp_id="+sp_id
                  });

                  request.done(function (response, textStatus, jqXHR) {

                  if (response.respuesta == 'correcto') {
                    $('#titulo').html('<font size=3><b>PARTIDA : '+response.ppto_asignado[0]['par_codigo']+' - '+response.ppto_asignado[0]['par_nombre'].toUpperCase()+'</b></font>');
                    $('#monto').html('<b>PTTO. ASIGNADO : '+response.ppto_asignado[0]['importe']+'</b>');
                  }
                  else{
                      alertify.error("ERROR AL RECUPERAR DATOS DEL REQUERIMIENTO");
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
                  $("#subir_saldo").on("click", function (e) {
                        saldo = parseFloat($('[name="saldo"]').val()); //// costo Total Programado
                        if(saldo!=0){
                            alertify.confirm("REGISTRAR REVERSIÓN DE SALDO ?", function (a) {
                                if (a) {
                                    document.getElementById("load_saldo").style.display = 'block';
                                    document.getElementById("subir_saldo").value = "REGISTRANDO SALDO...";
                                    document.getElementById('subir_saldo').disabled = true;
                                    document.forms['form_saldo'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });
                        }
                        else{
                            alertify.error("REGISTRE MONTO !!");
                        }
                  });
              });
          });    
        </script>


        <script type="text/javascript">
        function justNumbers(e){
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
             
            return /\d/.test(String.fromCharCode(keynum));
        }

        function cerrar(){
            alertify.confirm("DESEA CERRAR MODIFICACIÓN DEL TECHO PRESUPUESTARIO ?", function (a) {
                if (a) {
                    window.location='<?php echo base_url().'index.php/mod/list_top'?>';
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
        }

        $(document).ready(function () {
            $("#par_id").change(function () {            
            var partida = $(this).val();

            monto = $('[name="monto"]').val(); /// Codigo sub act2
                if(partida!=0 & (monto!='' & monto!=0)){
                    $('#abut').slideDown();
                }
                else{
                    $('#abut').slideUp();
                }
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

        $(function () {
            $("#add_form").on("click", function () {
                alertify.confirm("DESEA REALIZAR MODIFICACI\u00D3N DEL TECHO PRESUPUESTARIO ?", function (a) {
                    if (a) {
                        document.getElementById('add_form').disabled = true;
                        document.forms['form_nuevo'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            });
        });
        </script>

        <!-- Verificando valor del saldo -->
<!--         <script type="text/javascript">
            function guardar(sp_id,nro){
                saldo=parseFloat($('[id="saldo'+nro+'"]').val());
                observacion=$('[id="obs_saldo'+nro+'"]').val();

                alertify.confirm("GUARDAR SALDO PRESUPUESTO NO EJECUTADO POA?", function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/modificaciones/cmod_requerimientos/guardar_saldo_ppto'?>";
                        var request;
                        if (request) {
                            request.abort();
                        }
                        request = $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            data: "sp_id="+sp_id+"&saldo="+saldo+"&obs="+observacion
                        });

                        request.done(function (response, textStatus, jqXHR) {

                        if (response.respuesta == 'correcto') {
                            alertify.alert("SE REGISTRO CORRECTAMENTE ", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                }
                            });
                        }
                        else{
                            alertify.error("ERROR AL GUARDAR SALDO POA");
                        }

                        });
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }

            function verif_monto_saldo(anterior_saldo,nuevo_saldo,nro){
              if(nuevo_saldo> 0 && nuevo_saldo>anterior_saldo){
                $('#but'+nro).slideDown();
              }
              else{
                $('#but'+nro).slideUp();
              }
            }
        </script> -->
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
                    var sp_id = $(this).attr('name');
                    var cite_id = <?php echo $cite[0]['cppto_id'];?>;

                    var request;
                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR MONTO PARTIDA ?", function (a) {
                        if (a) { 
                            url = "<?php echo site_url("")?>/modificaciones/cmod_requerimientos/delete_partida";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "sp_id="+sp_id+"&cite_id="+cite_id

                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("LA PARTIDA FUE ELIMINADO CORRECTAMENTE", function (e) {
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
