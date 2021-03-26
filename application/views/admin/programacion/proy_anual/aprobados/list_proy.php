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
              width: 45% !important;
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
					<li>Programaci&oacute;n POA</li><li>Poas Aprobados - <?php echo $this->session->userdata('gestion')?></li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<h3>RESPONSABLE : <?php echo $resp; ?> -> <small><?php echo $res_dep;?></h3>

								<div id="tabs">
									<ul>
										<li>
											<a href="#tabs-c">GASTO CORRIENTE</a>
										</li>
										<li>
											<a href="#tabs-a">PROYECTOS DE INVERSI&Oacute;N PUBLICA</a>
										</li>
									</ul>
									<div id="tabs-c">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken">
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>GASTO CORRIENTE</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<table id="dt_basic3" class="table1 table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;">
																		<a href="<?php echo site_url("").'/rep/comparativo_unidad_ppto/0/4';?>" target="_blank" class="btn btn-default" title="IMPRIMIR GASTO CORRIENTE">
									                                        <img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>
									                                    </a> 
																	</th>
																	<th style="width:5%;" title="REPORTE FORMULARIO 3">FORM. 3</th>
																	<th style="width:5%;" title="REPORTE POA">REP. POA - FORM. 4 Y 5</th>
																	<?php
																		if($this->session->userdata('tp_adm')==1){ ?>
																			<th style="width:5%;" title="RECHAZAR POA">RECHAZAR POA</th>
																			<th style="width:5%;" title="APROBAR POA">APROBAR POA</th>
																			<?php
																		}
																	?>
																	<th style="width:10%;" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA <?php echo $this->session->userdata("gestion");?></th>
																	<th style="width:20%;" title="DESCRIPCI&Oacute;N">DESCRIPCI&Oacute;N</th>
																	<th style="width:10%;" title="NIVEL">ESCALON</th>
																	<th style="width:10%;" title="NIVEL">NIVEL</th>
																	<th style="width:10%;" title="TIPO DE ADMINISTRACIÓN">TIPO DE ADMINISTRACI&Oacute;N</th>
																	<th style="width:10%;" title="UNIDAD ADMINISTRATIVA">UNIDAD ADMINISTRATIVA</th>
																	<th style="width:10%;" title="UNIDAD EJECUTORA">UNIDAD EJECUTORA</th>
																	<th style="width:5%;" title="ESTADO POA"></th>
																</tr>
															</thead>
															<tbody>
															<?php echo $operacion;?>
															</tbody>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-a">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
												<div class="jarviswidget jarviswidget-color-darken" >
					                              <header>
					                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
					                                  <h2 class="font-md"><strong>PROYECTOS DE INVERSI&Oacute;N PUBLICA </strong></h2>  
					                              </header>
													<div>
														<div class="widget-body no-padding">
															<table id="dt_basic" class="table table-bordered" style="width:100%;">
																<thead>
																	<tr style="height:60px;">
																		<th style="width:1%;">
																			<a href="<?php echo site_url("").'/rep/comparativo_unidad_ppto/0/1';?>" target="_blank" class="btn btn-default" title="IMPRIMIR PROYECTOS DE INVERSIÓN">
									                                        <img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>
									                                    </a> 
																		</th>
																		<th style="width:5%;"title="REPORTE POA">REPORTE POA</th>
																		<th style="width:5%;" title="ERROR EN EL POA"></th>
																		<th style="width:10%;" title="APERTURA PROGRAM&Aacute;TICA">CATEGORIA PROGRAM&Aacute;TICA <?php echo $this->session->userdata("gestion");?></th>
																		<th style="width:25%;" title="NOMBRE DEL PROYECTO DE INVERSI&Oacute;N">NOMBRE_PROYECTO_INVERSI&Oacute;N</th>
																		<th style="width:10%;" title="C&Oacute;DIGO SISIN">C&Oacute;DIGO_SISIN</th>
																		<th style="width:15%;" title="UNIDAD ADMINISTRATIVA">UNIDAD_ADMINISTRATIVA</th>
																		<th style="width:15%;" title="UNIDAD EJECUTORA">UNIDAD_EJECUTORA</th>
																		<th style="width:20%;" title="FASE - ETAPA DE LA OPERACI&Oacute;N">FASE_ETAPA</th>
																		<th style="width:10%;" title="GESTION ACTIVA">GESTI&Oacute;N ACTIVA</th>
																	</tr>
																</thead>
																<tbody>
																<?php echo $proyectos;?>
																</tbody>
															</table>
														</div>
														<!-- end widget content -->
													</div>
													<!-- end widget div -->
												</div>
											<!-- end widget -->
											</article>
										</div>
									</div>
									
								</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

		<!-- MODAL REPORTE POA -->
	    <div class="modal fade" id="modal_nuevo_ff" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog" id="mdialTamanio">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                <h2 class="alert alert-info"><center>MI POA - <?php echo $this->session->userData('gestion');?></center></h2>
	                    <div class="row">
	                    	<table style="width:100%; height:50px;">
		                		<tr>
		                			<td style="width:90%;">
		                				<div id="titulo"></div>	
		                			</td>
		                			<td style="width:10%;" align="center">
		                				<div id="caratula"></div>	
		                			</td>
		                		</tr>
		                	</table><br>
	                        <div id="content1"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>

	    <!-- MODAL VISTA ARCHIVO PDF -->
	    <div class="modal fade" id="modal_archivo" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog" id="mdialTamanio">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                <div id="title"></div>
	                    <div class="row"><br>
	                        <div id="content2"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	  <!--  ============================ -->
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
		<script type="text/javascript">
            $(function () {
                $(".enlace").on("click", function (e) {
                    proy_id = $(this).attr('name');
                    establecimiento = $(this).attr('id');
                    
                    $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
                    $('#content1').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones - <br>'+establecimiento+'</div>');
                    
                    var url = "<?php echo site_url("")?>/programacion/proyecto/get_poa";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "proy_id="+proy_id
                    });

                    request.done(function (response, textStatus, jqXHR) {

                    if (response.respuesta == 'correcto') {
                        $('#content1').fadeIn(1000).html(response.tabla);
                        $('#caratula').fadeIn(1000).html(response.caratula);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                  
                });
            });

            $(function () {
                $(".enlace1").on("click", function (e) {
                    proy_id = $(this).attr('name');
                    establecimiento = $(this).attr('id');
                    
                    $('#title').html('<font size=3><b>'+establecimiento+'</b></font>');
                    $('#content2').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Archivo - <br>'+establecimiento+'</div>');
                    
                    var url = "<?php echo site_url("")?>/mantenimiento/cpoas_scanneados/get_pdf_poa_unidad";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "proy_id="+proy_id
                    });

                    request.done(function (response, textStatus, jqXHR) {

                    if (response.respuesta == 'correcto') {
                        $('#content2').fadeIn(1000).html(response.tabla);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR ARCHIVO POA");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                  
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
		        /*----------- RECHAZAR POA ---------------*/
		        $(".neg_ff").on("click", function (e) {
		            reset();
		            var proy_id = $(this).attr('name');
		            var request;
		            alertify.confirm("ESTA SEGURO DE RECHAZAR EL POA Y DEVOLVER AL RESPONSABLE POA ?", function (a) {
		                if (a) { 
		                    var url = "<?php echo site_url("")?>/programacion/proyecto/observar_poa";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "proy_id="+proy_id

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("SE RECHAZO REPORTE POA", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL OBSERVAR !!!", function (e) {
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
		                    alertify.error("OPCIÓN CANCELADA");
		                }
		            });
		            return false;
		        });

		        /*----------- APROBAR POA ---------------*/
		        $(".aprobar_poa").on("click", function (e) {
		            reset();
		            var proy_id = $(this).attr('name');
		            var request;
		            alertify.confirm("ESTA SEGURO DE APROBAR POA ?", function (a) {
		                if (a) { 
		                    var url = "<?php echo site_url("")?>/programacion/proyecto/aprobar_poa";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "proy_id="+proy_id

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("EL POA SE APROBO CORRECTAMENTE ", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL OBSERVAR !!!", function (e) {
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
		                    alertify.error("OPCIÓN CANCELADA");
		                }
		            });
		            return false;
		        });

		    });
		</script>
		<!-- ====================================================================================================== -->
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
