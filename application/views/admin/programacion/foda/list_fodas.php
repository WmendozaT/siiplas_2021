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
				window.open(direccion, "Reporte Foda" , "width=800,height=650,scrollbars=SI") ;
			}
			function confirmar(){
		        if(confirm('¿Estas seguro de Eliminar el proyecto?'))
		          return true;
		        else
		          return false;
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
            #mdialTamanio{
		      width: 40% !important;
		    }
		    #mdialTamanio2{
		      width: 40% !important;
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
		                <a href="#" title="PROGRAMACION DEL POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N</span></a>
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
					<li>Programaci&oacute;n POA</li><li>Analisis de Prob. y Causas</li><li>Formulario - SPO 03 - <?php echo $this->session->userdata('gestion')?></li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
							<div class="well well-sm well-light">
								<h2><b>ANALISIS DE PROBLEMAS Y SUS CAUSAS</b></h2>
								<h3><b>UNIDAD ORG.</b></small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></h3><hr>
								<a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-default" title="NUEVO REGISTRO - FODA">
									<img src="<?php echo base_url(); ?>assets/Iconos/add.png" WIDTH="25" HEIGHT="25"/>&nbsp;NUEVO REGISTRO
								</a>
								<a href="javascript:abreVentana('<?php echo site_url("").'/as/rep_list_foda/'.$proyecto[0]['proy_id'].''?>');" title="IMPRIMIR" class="btn btn-default">
									<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;IMPRIMIR FORM. SPO 3
								</a>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                            <section id="widget-grid" class="well">
                                <a href="<?php echo base_url();?>index.php/admin/analisis_sit" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR</a>
                            </section>
                        </article>
					</div>
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<div id="tabs">
									<ul>
										<li>
											<a href="#tabs-a">LISTA DE PROBLEMAS</a>
										</li>
									</ul>

									<div id="tabs-a">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></article>
											<?php echo $fodas;?>
										</div>
									</div>
								</div>
							</div>
						</article>
                    </div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

		<!-- MODAL MODIFICACION DE CAUSAS/ACCIONES   -->
        <div class="modal fade" id="modal_mod_cff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>MODIFICAR CAUSAS - ACCIONES</center></h2>
                		<form action="<?php echo site_url().'/analisis_situacion/canalisis_situacion/valida_causas'?>" method="post" id="form_mcausas" name="form_mcausas" class="smart-form">
							<input type="hidden" name="ca_id" id="ca_id">
							<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
							<input type="hidden" name="tp" id="tp" value="2">
							<header>
								<b>UNIDAD / ESTABLECIMIENTO : </b><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?><br>
								<div id="mcprob"></div>
							</header>
							<fieldset>
								<section>
									<label class="label"><b>CAUSAS DE LOS PROBLEMAS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="mcausas" id="mcausas"></textarea>
									</label>
								</section>
								<section>
									<label class="label"><b>ACCIONES RECOMENDADAS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="macciones" id="macciones"></textarea>
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="button" name="subir_mcausas" id="subir_mcausas" class="btn btn-info">MODIFICAR</button>
								<button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
							</footer>
						</form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
		<!-- MODAL NUEVO REGISTRO DE CAUSAS/ACCIONES   -->
        <div class="modal fade" id="modal_nuevo_cff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>REGISTRAR CAUSAS - ACCIONES</center></h2>
                		<form action="<?php echo site_url().'/analisis_situacion/canalisis_situacion/valida_causas'?>" method="post" id="form_causas" name="form_causas" class="smart-form">
							<input type="hidden" name="prob_cid" id="prob_cid">
							<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
							<input type="hidden" name="tp" id="tp" value="1">
							<header>
								<b>UNIDAD / ESTABLECIMIENTO : </b><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?><br>
								<div id="prob"></div>
							</header>
							<fieldset>
								<section>
									<label class="label"><b>CAUSAS DE LOS PROBLEMAS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="causas" id="causas"></textarea>
									</label>
								</section>
								<section>
									<label class="label"><b>ACCIONES RECOMENDADAS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="acciones" id="acciones"></textarea>
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="button" name="subir_causas" id="subir_causas" class="btn btn-info">REGISTRAR</button>
								<button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
							</footer>
						</form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->

		<!-- MODAL NUEVO REGISTRO DE PROBLEMAS   -->
        <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>REGISTRO PROBLEMA</center></h2>
                		<form action="<?php echo site_url().'/analisis_situacion/canalisis_situacion/valida_problema'?>" method="post" id="form_prob" name="form_prob" class="smart-form">
							<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
							<input type="hidden" name="tp" id="tp" value="1">
							<header>
								UNIDAD / ESTABLECIMIENTO : <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?>
							</header>
							<fieldset>
								<section>
									<label class="label"><b>PROBLEMAS IDENTIFICADOS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="problema"></textarea>
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="button" name="subir_prob" id="subir_prob" class="btn btn-info" >GUARDAR</button>
								<button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
							</footer>
						</form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
        <!-- MODAL UPDATE REGISTRO DE PROBLEMAS   -->
        <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>MODIFICAR PROBLEMA</center></h2>
                		<form action="<?php echo site_url().'/analisis_situacion/canalisis_situacion/valida_problema'?>" method="post" id="form_mprob" name="form_mprob" class="smart-form">
							<input type="hidden" name="prob_id" id="prob_id">
							<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
							<input type="hidden" name="tp" id="tp" value="2">
							<header>
								UNIDAD / ESTABLECIMIENTO : <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?>
							</header>
							<fieldset>
								<section>
									<label class="label"><b>PROBLEMAS IDENTIFICADOS</b></label>
									<label class="textarea"> <i class="icon-append fa fa-comment"></i>
										<textarea rows="4" name="mproblema" id="mproblema"></textarea>
									</label>
								</section>
							</fieldset>
							<footer>
								<button type="button" name="subir_mprob" id="subir_mprob" class="btn btn-info" >MODIFICAR</button>
								<button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
							</footer>
						</form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->

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
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		
		<!-- Elimina Objetivo Estrategico -->
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

		        // ===============================
		        $(".del_ff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var request;
		            // confirm dialog
		            alertify.confirm("DESEA ELIMINAR PROBLEMA ?", function (a) {
		                if (a) {
		                	url = "<?php echo site_url().'/analisis_situacion/canalisis_situacion/delete_problemas';?>";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "prob_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("REGISTRO PROBLEMA ELIMINADO", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL ELIMINAR!!!", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
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

		        $(".del_cff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var request;
		            // confirm dialog
		            alertify.confirm("DESEA ELIMINAR CAUSAS - ACCIONES ?", function (a) {
		                if (a) {
		                	url = "<?php echo site_url().'/analisis_situacion/canalisis_situacion/delete_causas_acciones';?>";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "ca_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("REGISTRO (CAUSAS - ACCIONES) ELIMINADAS", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL ELIMINAR!!!", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
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
		<!-- MODIFICAR CAUSAS - ACCIONES -->
        <script type="text/javascript">
            $(function () {
                $(".mod_cff").on("click", function (e) {
                    ca_id = $(this).attr('name');
                    document.getElementById("ca_id").value=ca_id;
                    var url = "<?php echo site_url().'/analisis_situacion/canalisis_situacion/get_causas';?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "ca_id=" + ca_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                    	$('#mcprob').html('<b>PROBLEMA IDENTIFICADO : </b>'+response.causas[0]['problema']+'');
                    	document.getElementById("mcausas").value = response.causas[0]['causas'];
                    	document.getElementById("macciones").value = response.causas[0]['acciones'];
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
                    $("#subir_mcausas").on("click", function (e) {
                        var $validator = $("#form_mcausas").validate({
                               rules: {
                                ca_id: { //// ca
                                	required: true,
                                },
                                mcausas: { //// Causas
                                    required: true,
                                },
                                macciones: { //// Acciones
                                    required: true,
                                }
                            },
                            messages: {
                                mcausas: "<font color=red>REGISTRE CAUSAS</font>",
                                mcausas: "<font color=red>REGISTRE ACCIONES</font>",
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
                        var $valid = $("#form_mcausas").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {
                            alertify.confirm("MODIFICAR DATOS ?", function (a) {
                                if (a) {
                                    document.getElementById('subir_mcausas').disabled = true;
                                    document.forms['form_mcausas'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>
		<!-- REGISTRAR CAUSAS-ACCIONES -->
        <script type="text/javascript">
            $(function () {
                $(".nuevo_cff").on("click", function (e) {
                    prob_id = $(this).attr('name');
                    document.getElementById("prob_cid").value=prob_id;
                    var url = "<?php echo site_url().'/analisis_situacion/canalisis_situacion/get_problema'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "prob_id=" + prob_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                    	$('#prob').html('<b>PROBLEMA IDENTIFICADO : </b>'+response.problema[0]['problema']+'');
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DEL PROBLEMA");
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
                    $("#subir_causas").on("click", function (e) {
                        var $validator = $("#form_causas").validate({
                               rules: {
                                prob_cid: { //// prob
                                	required: true,
                                },
                                causas: { //// causas
                                    required: true,
                                },
                                acciones: { //// acciones
                                    required: true,
                                }
                            },
                            messages: {
                                causas: "<font color=red>REGISTRE CAUSAS</font>",
                                acciones: "<font color=red>REGISTRE ACCIONES</font>",
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
                        var $valid = $("#form_causas").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {
                            alertify.confirm("REGISTRAR CAUSAS - ACCIONES ?", function (a) {
                                if (a) {
                                    document.getElementById('subir_causas').disabled = true;
                                    document.forms['form_causas'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>
		<!-- MODIFICAR PROBLEMA -->
        <script type="text/javascript">
            $(function () {
                $(".mod_ff").on("click", function (e) {
                    prob_id = $(this).attr('name');
                    document.getElementById("prob_id").value=prob_id;
                    var url = "<?php echo site_url().'/analisis_situacion/canalisis_situacion/get_problema'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "prob_id=" + prob_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                    	document.getElementById("mproblema").value = response.problema[0]['problema'];
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DEL PROBLEMA");
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
                    $("#subir_mprob").on("click", function (e) {
                        var $validator = $("#form_mprob").validate({
                               rules: {
                                proy_id: { //// proy
                                	required: true,
                                },
                                prob_id: { //// prob
                                    required: true,
                                },
                                mproblema: { //// problema
                                    required: true,
                                }
                            },
                            messages: {
                                mproblema: "<font color=red>REGISTRE PROBLEMAS</font>",                   
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
                        var $valid = $("#form_mprob").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {
                            alertify.confirm("MODIFICAR DATOS ?", function (a) {
                                if (a) {
                                    document.getElementById('subir_mprob').disabled = true;
                                    document.forms['form_mprob'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>
		<script type="text/javascript">
        $(function () {
            $("#subir_prob").on("click", function () {
                var $validator = $("#form_prob").validate({
                        rules: {
                            tp: { //// tp
                            required: true,
                            },
                            problema: { //// problema
                                required: true,
                            }
                        },
                        messages: {
                            problema: "<font color=red>REGISTRE PROBLEMA</font>",                    
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

                var $valid = $("#form_prob").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    alertify.confirm("GUARDAR PROBLEMA ?", function (a) {
                        if (a) {
                            document.getElementById('subir_prob').disabled = true;
                            document.forms['form_prob'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>
        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		<script type="text/javascript">
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
	</body>
</html>
