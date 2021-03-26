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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<style type="text/css">
        	hr {border: 0; height: 12px; box-shadow: inset 0 12px 12px -12px blue;}
            #col{
              color: #0555c7;
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
		                <?php
						if($proyecto[0]['proy_estado']==1){ ?>
							<a href='<?php echo site_url("admin").'/proy/list_proy#tabs-a'; ?>' title="MIS PROYECTOS DE INVERSI&Oacute;N"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
							<?php
						}
						elseif ($proyecto[0]['proy_estado']==2){ ?>
							<a href='<?php echo site_url("admin").'/proy/list_proy_poa#tabs-a'; ?>' title="MIS PROYECTOS DE INVERSI&Oacute;N"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
							<?php
						}
						?>
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
					<?php
					if($proyecto[0]['proy_estado']==1){ ?>
						<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a'?>" title="MIS PROYECTOS DE INVERSI&Oacute;N">Programaci&oacute;n de Proyectos</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Fases del Proyecto de Inversi&oacute;n (Agregar)</li>
						<?php
					}
					elseif ($proyecto[0]['proy_estado']==2){ ?>
						<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa#tabs-a'?>" title="MIS PROYECTOS DE INVERSI&Oacute;N">Programaci&oacute;n de Proyectos</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">T&eacute;cnico Analista POA</a></li><li>Fases del Proyecto de Inversi&oacute;n (Agregar)</li>
						<?php }
					?>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<div class="row">
                        <nav role="navigation" class="navbar navbar-default navbar-inverse">
                            <div class="navbar-header">
                                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>
                     
                            <div id="navbarCollapse" class="collapse navbar-collapse">
                                <ul class="nav navbar-nav">
                                    <li><a href="<?php echo base_url().'index.php/admin/proy/edit/'.$proyecto[0]['proy_id'].''?>" title="DATOS GENERALES DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;DATOS GENERALES&nbsp;</font></a></li>
                                    <li><a href="<?php echo base_url().'index.php/admin/proy/proyecto_pi/'.$proyecto[0]['proy_id'].'' ?>" title="OBJETIVOS DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;OBJETIVOS DEL PROYECTO&nbsp;</font></a></li>
                                    <li class="active"><a href="<?php echo base_url().'index.php/admin/proy/fase_etapa/'.$proyecto[0]['proy_id'].'' ?>" title="LISTA DE FASES DEL PROYECTO DE INVERSI&Oacute;N"><i class="glyphicon glyphicon-ok"></i><font size="2">&nbsp;FASES DEL PROYECTO&nbsp;</font></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>


					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
							<section id="widget-grid" class="well">
				                <div class="">
				                  	<h1> PROYECTO : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small>
				                	<h1> FECHA INICIO : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_inicial'])); ?></small> || FECHA FINAL : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_final'])); ?></small></h1>
				                </div>
				            </section>
	                    </article>
	                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
	                        <section id="widget-grid" class="well">
	                        	<a href="<?php echo site_url("admin").'/proy/fase_etapa/'.$proyecto[0]['proy_id']; ?>" class="btn btn-success" title="Lista de Fases del Proyecto" style="width:100%;">VOLVER A LISTA DE FASES</a>
	                        </section>
	                    </article>
	                </div>
				</section>

				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-sm-12">
							<?php 
			                  if($this->session->flashdata('success')){ ?>
			                    <div class="alert alert-success">
			                      <?php echo $this->session->flashdata('success'); ?>
			                    </div>
			                <?php }
			                  elseif($this->session->flashdata('danger')){ ?>
			                    <div class="alert alert-danger">
			                        <?php echo $this->session->flashdata('danger'); ?>
			                    </div>
			                    <?php }
			                ?>
							<form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/programacion/faseetapa/add_fase'?>">
							<input type="hidden" name="proy_id" value="<?php echo $proyecto[0]['proy_id']?>">
							<input type="hidden" name="fi" id="fi" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?>">	
							<input type="hidden" name="ff" id="ff" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?>">
							<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-columns"></i> </span>
									<h2>REGISTRO - FASE </h2>
								</header>
								<!-- widget div-->
								<div>
									<div class="jarviswidget-editbox">
									</div>
									<div class="widget-body">
										<div class="col-sm-12">
											<div class="col-sm-12" align="center" id="tiempo"><?php echo $dif;?></div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="label" id="col"><b>FASE</b></label>
								                                <select name="fase" id="fase" class="form-control" title="SELECCIONE FASE">
																	<option value="">SELECCIONE  FASE</option>
                                                     				<?php 
																        foreach($fase as $row){ ?>
																        	<option value="<?php echo $row['fas_id']; ?>"><?php echo strtoupper($row['fas_fase']); ?></option>
																        <?php }
																    ?>  
																</select>
														</section><br>

														<section>
															<label class="label" id="col"><b>ETAPA</b></label>
								                            <select class="form-control" id="etapas" name="etapas" title="SELECCIONE ETAPA">
								                            	<option value="">No seleccionado</option>
								                            </select>
														</section><br>
														<div id="fa" style="display:none;">
															<section>
																<label class="label" id="col"><b>DESCRIPCI&Oacute;N FASE</b></label>
									                            <textarea rows="4" name="desc" id="desc" class="form-control" style="width:100%;" maxlength="200" ></textarea> 
															</section>
														</div>
													</fieldset>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="label" id="col"><b>FECHA INICIO FASE</b></label>
								                                <div class="input-group">
																	<input type="text" name="f_inicio" id="f_inicio" placeholder="Seleccione Fecha inicial"  class="form-control datepicker" data-dateformat="dd/mm/yy" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?>">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																</div>
														</section><br>

														<section>
															<label class="label" id="col"><b>FECHA FINAL FASE</b></label>
																<div class="input-group">
																	<input type="text" name="f_final" id="f_final" placeholder="Seleccione Fecha final" class="form-control datepicker" data-dateformat="dd/mm/yy" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?>">
																	<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																</div>
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>PRESUPUESTO PROGRAMADO FASE </b></label>
																<input class="form-control" type="text" name="monto_total" id="monto_total" value="0" placeholder="0" onkeypress="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>PRESUPUESTO EJECUTADO FASE </b></label>
																<input class="form-control" type="text" value="0" placeholder="0" disabled="true">
															</label>
														</section>
													</fieldset>
												</div><br>
											</div>
										</div>
										<hr>
										<div class="col-sm-12">
											<div id="but" style="display:none;" align="right">
												<a href='<?php echo site_url("admin").'/proy/list_proy#tabs-a'; ?>' title="CANCELAR Y SALIR A MIS PROYECTOS" class="btn btn-default">CANCELAR</a>
												<input type="button" value="GUARDAR FASE Y PROGRAMAR PRESUPUESTO" id="btsubmit" class="btn btn-primary" title="GUARDAR REGISTRO"><br><br>
											</div>
										</div>
									</div>
								</div>
							</div>
							</form>
						</article>
					</div>
				</section>
				<!-- end widget grid -->					
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
		<!--================================================== -->
		<script>
			if (!window.jQuery) {
				document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"><\/script>');
			}
		</script>

		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="<?php echo base_url();?>/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
		<!-- BOOTSTRAP JS -->
		<script src="<?php echo base_url();?>/assets/js/bootstrap/bootstrap.min.js"></script>
		<!-- CUSTOM NOTIFICATION -->
		<script src="<?php echo base_url();?>/assets/js/notification/SmartNotification.min.js"></script>
		<!-- JARVIS WIDGETS -->
		<script src="<?php echo base_url();?>/assets/js/smartwidgets/jarvis.widget.min.js"></script>
		<!-- EASY PIE CHARTS -->
		<script src="<?php echo base_url();?>/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		<!-- SPARKLINES -->
		<script src="<?php echo base_url();?>/assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>
		<!-- JQUERY VALIDATE -->
		<script src="<?php echo base_url();?>/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
		<!-- JQUERY MASKED INPUT -->
		<script src="<?php echo base_url();?>/assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
		<!-- JQUERY SELECT2 INPUT -->
		<script src="<?php echo base_url();?>/assets/js/plugin/select2/select2.min.js"></script>
		<!-- JQUERY UI + Bootstrap Slider -->
		<script src="<?php echo base_url();?>/assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
		<!-- browser msie issue fix -->
		<script src="<?php echo base_url();?>/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
		<!-- FastClick: For mobile devices -->
		<script src="<?php echo base_url();?>/assets/js/plugin/fastclick/fastclick.min.js"></script>
		<!-- Demo purpose only -->
		<script src="<?php echo base_url();?>/assets/js/demo.min.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url();?>/assets/js/app.min.js"></script>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			pageSetUp();
			$("#fase").change(function () {
                $("#fase option:selected").each(function () {
                elegido=$(this).val();
                $.post("<?php echo base_url(); ?>index.php/admin/combo_fase_etapas", { elegido: elegido }, function(data){
                $("#etapas").html(data);
                });     
            });
            });    
		})
		</script>
		<script>
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
		$(function () {
		    $("#btsubmit").on("click", function (e) {
		        var $validator = $("#formulario").validate({
		            rules: {
		                fase: {
		                    required: true,
		                },
		                etapas: {
		                    required: true,
		                },
		                desc: {
		                    required: true,
		                },
		                f_inicio: {
		                    required: true,
		                },
		                f_final: {
		                    required: true,
		                },
		                monto_total: {
		                    required: true,
		                }
		            },
		            messages: {
		                fase: {required: "<font color=red size=1>SELECCIONE FASE DEL PROYECTO</font>"},
		                etapas: {required: "<font color=red size=1>SELECCIONE ETAPAS DE LA FASE</font>"},
		                desc: {required: "<font color=red size=1>REGISTRE DESCRIPCI&Oacute;N DE LA FASE</font>"},
		                f_inicio: {required: "<font color=red size=1>SELECCIONE FECHA INICIAL DE FASE</font>"},
		                f_final: {required: "<font color=red size=1>SELECCIONE FECHA FINAL DE LA FASE</font>"},
		                monto_total: {required: "<font color=red size=1>REGISTRE COSTO TOTAL DE LA FASE</font>"}
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
		        var $valid = $("#formulario").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } 
		        else {

		        	reset();
	                alertify.confirm("GUARDAR DATOS DE LA FASE Y PROGRAMAR PRESUPUESTO POR GESTIONES ?", function (a) {
	                    if (a) {
	                        document.getElementById('btsubmit').disabled = true;
	                        document.formulario.submit();
	                    } else {
	                        alertify.error("OPCI\u00D3N CANCELADA");
	                    }
	                });
		            
		        }
		    });
		});
		</script>

		<script>
		$(document).ready(function () {
        	$("#etapas").change(function () {            
            var et = $(this).val();
	            if(et!=""){
	            	$('#fa').slideDown();
	            	$('#but').slideDown();
	            }
	            else{
	            	$('#but').slideUp();
	            }
            
            });

        	$("#f_inicio").change(function () {            
            var finicio = $(this).val();
            fi = $('[name="fi"]').val(); /// fecha inicial proyecto
            var fecha_inip = fi.split("/")  //fecha inicial
            var fecha_inif = finicio.split("/")  //fecha inicial fase

	            if(fecha_inif[2]<fecha_inip[2]){
	            	alertify.error("LA FECHA INICIAL NO PUEDE SER ANTES DE LA FECHA INICIAL DEL PROYECTO, VERIFIQUE DATOS");
	            	$('#but').slideUp();
	            }
	            else{
	            	$('#but').slideDown();
	            }
            });

            $("#f_final").change(function () {            
            var ffinal = $(this).val();
            ff = $('[name="ff"]').val(); /// fecha final proyecto
            var fecha_finp = ff.split("/")  //fecha final
            var fecha_finf = ffinal.split("/")  //fecha final fase

	            if(fecha_finf[2]>fecha_finp[2]){
	            	alertify.error("LA FECHA FINAL NO PUEDE SER POSTERIOR A LA FECHA FINAL DEL PROYECTO, VERIFIQUE DATOS");
	            	$('#but').slideUp();
	            }
	            else{
	            	$('#but').slideDown();
	            }
            });
        });
        </script>
	</body>
</html>
