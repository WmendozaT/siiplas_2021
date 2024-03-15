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
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <style type="text/css">
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
			<!-- pulled right: nav area -->
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
		                <a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="PROGRAMACION DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="Programacion de Proyectos">Programaci&oacute;n de Proyectos</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Datos Generales de Proyecto de Inversi&oacute;n</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
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
                                    <li class="active"><a href="#" title="DATOS GENERALES"><i class="glyphicon glyphicon-ok"></i><font size="2">&nbsp;DATOS GENERALES&nbsp;</font></a></li>
                                    <li><a href="#" title="OBJETIVOS DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;<s>&nbsp;OBJETIVOS DEL PROYECTO&nbsp;</s></font></a></li>
                                    <li><a href="#" title="FASES DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;<s>FASES DEL PROYECTO&nbsp;</s></font></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
					<div class="row">
						<article class="col-sm-12">
							<form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/programacion/proyecto/valida_proyecto'?>">
							<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-columns"></i> </span>
									<h2>REGISTRO - PROYECTO DE INVERSI&Oacute;N</h2>
								</header>
								<!-- widget div-->
								<div>
									<div class="jarviswidget-editbox">
									</div>
									<div class="widget-body">
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  DATOS GENERALES - <?php echo $titulo;?>
											</div>
										</div>
										<div class="row">
											<input type="hidden" name="tp_id" id="tp_id" value="<?php echo $tp_id;?>">
											<input class="form-control" type="hidden" name="gestion" id="gestion" value="<?php echo $this->session->userdata("gestion") ?>">
											<input type="hidden" name="form" id="form" value="1">
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<b>UNIDAD ORGANICA</b>
														<section>
															<label class="label" id="col"><b>REGIONAL</b></label>
								                                <select class="form-control" id="dep_id" name="dep_id" title="Seleccione Regional">
										                            <option value="">Seleccione Regional</option>
										                            <?php 
																		foreach($list_dep as $row){ ?>
																			<option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
																	<?php } ?>        
										                        </select>
														</section><br>

														<section>
															<label class="label" id="col"><b>DIRECCI&Oacute;N ADMINISTRATIVA</b></label>
								                            <select class="form-control" id="dist_id" name="dist_id" title="Seleccione Dirección Administrativa">
								                            	<option value="">No seleccionado</option>
								                            </select>
														</section><br>
						
														<section>
															<label class="label" id="col"><b>UNIDAD EJECUTORA</b></label>
								                            <select class="form-control" id="ue_id" name="ue_id" title="Seleccione Unidad Ejecutora">
								                            	<option value="">No seleccionado</option>
								                            </select>
														</section><br>

														<div id="pi" style="display:none;" >
															<section>
																<label class="label" id="col"><b>PROYECTO DE INVERSI&Oacute;N</b></label>
									                            <textarea rows="4" class="form-control" style="width:100%;" name="nombre" id="nombre" title="REGISTRE NOMBRE DE PROYECTO DE INVERSI&Oacute;N"></textarea> 
															</section>
														</div>
													</fieldset>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="well">
													<b>APERTURA PROGRAM&Aacute;TICA - <?php echo $this->session->userdata('gestion')?></b>
													<fieldset>
														<div id="atit"></div>
														<section>
															<label class="label" id="col"><b>PROGRAMA</b></label>
								                                <select class="form-control" name="prog" id="prog">
							                                    <option value="">Seleccione Programa</option>
											                    <?php
																	foreach($programas as $row) { ?>
																		<option value="<?php echo $row['aper_id']?>"><?php echo $row['aper_programa'].' - '.$row['aper_descripcion']; ?></option>
																		<?php	
																	} 
																?>    
											                    </select>
														</section><br>

														<section>
															<label class="label" id="col"><b>PROYECTO</b></label>
															<input class="form-control" type="text" name="proy" id="proy" value="9999" onkeyup="verif_apertura();" data-mask="9999" data-mask-placeholder= "9" placeholder="Programa" title="Ingrese Proyecto">
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>ACTIVIDAD</b></label>
															<input class="form-control" type="hidden" name="act" id="act" value="000">
															<input class="form-control" type="text" id="acti" value="000" disabled="true">
															</label>
														</section>
													</fieldset>
												</div><br>
												<div class="well">
													<fieldset>
														<section>
															<label class="label" id="col"><b>C&Oacute;DIGO SISIN</b></label>
																<input class="form-control" type="text" name="cod_sisin" id="cod_sisin"  value="9999-99999-99999" data-mask="9999-99999-99999" data-mask-placeholder= "X"   title="REGISTRE C&Oacute;DIGO SISIN">
															</label>
														</section>
													</fieldset>
												</div>
											</div>
										</div>
				
										<hr>
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  TIEMPO PROGRAMADO DEL PROYECTO DE INVERSI&Oacute;N
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="input"" id="col"><b>FECHA INICIAL</b></label>
						                                    <div class="input-group" >
																<input type="text" name="ini" id="to" class="form-control" data-dateformat="mm/dd/yy" value="01/01/2019" onKeyUp="this.value=formateafecha(this.value);" placeholder="dd/mm/YY" title="FECHA INICIO DE LA OPERACION" >
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															</div>
														</section>
													</fieldset>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="input"" id="col"><b>FECHA FINAL</b></label>
						                                    <div class="input-group" >
																<input type="text" name="fin" id="from" class="form-control" data-dateformat="mm/dd/yy" value="12/30/2019" onKeyUp="this.value=formateafecha(this.value);" placeholder="dd/mm/YY" title="FECHA FINAL DE LA OPERACION" >
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															</div>
														</section>
													</fieldset>
												</div>
											</div>
										</div>

										<hr>
										<div class="col-sm-12">
											<div id="but" style="display:none;" align="right">
												<a href='<?php echo site_url("admin").'/proy/list_proy#tabs-a'; ?>' title="CANCELAR Y SALIR A MIS PROYECTOS" class="btn btn-default">CANCELAR</a>
												<input type="button" value="GUARDAR PROYECTO" id="btsubmit" class="btn btn-primary" title="GUARDAR REGISTRO"><br><br>
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
		<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
		<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/programacion/programacion.js" type="text/javascript"></SCRIPT>
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

        <script src="<?php echo base_url();?>/assets/form/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url();?>/assets/form/js/scripts.js"></script>

		<script type="text/javascript">
		$(document).ready(function() {
			pageSetUp();
			$("#dep_id").change(function () {
				$("#dep_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_administrativas", { elegido: elegido,accion:'distrital' }, function(data){
						$("#dist_id").html(data);
					});     
				});
			});
			$("#ue_id").change(function () {
				$("#ue_id option:selected").each(function () {
					ue_id=$(this).val();
					if(ue_id!="" || ue_id!=0){
						$('#pi').slideDown();
					}
					else{
						$('#pi').slideUp();
					}  
				});
			});
			$("#dep_id").change(function () {
				$("#dep_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
						$("#ue_id").html(data);
						$('#pi').slideUp();
					});

				});
			});
			$("#dep_id").change(function () {
				$("#dep_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_resp_tue", { elegido: elegido,accion:'resp' }, function(data){
						$("#fun_id_1").html(data);
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
		                dep_id: {
		                    required: true,
		                },
		                dist_id: {
		                    required: true,
		                },
		                ue_id: {
		                    required: true,
		                },
		                nombre: {
		                    required: true,
		                },
		                prog: {
		                    required: true,
		                },
		                proy: {
		                    required: true,
		                },
		                cod_sisin: {
		                    required: true,
		                },
		                ini: {
		                    required: true,
		                },
		                final: {
		                    required: true,
		                }
		            },
		            messages: {
		                dep_id: {required: "<font color=red size=1>SELECCIONE REGIONAL</font>"},
		                dist_id: {required: "<font color=red size=1>SELECCIONE DIRECCIÓN ADMINISTRATIVA</font>"},
		                ue_id: {required: "<font color=red size=1>SELECCIONE UNIDAD EJECUTORA</font>"},
		                nombre: {required: "<font color=red size=1>REGISTRE PROYECTO DE INVERSIÓN</font>"},
		                prog: {required: "<font color=red size=1>SELECCIONE PROGRAMA</font>"},
		                proy: {required: "<font color=red size=1>REGISTRE CODIGO PROYECTO</font>"},
		                cod_sisin: {required: "<font color=red size=1>REGISTRE CODIGO SISIN</font>"},
		                ini: {required: "<font color=red size=1>SELECCIONE FECHA INICIAL</font>"},
		                final: {required: "<font color=red size=1>SELECCIONE FECHA FINAL</font>"}
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

		        	var fecha_inicial = document.formulario.ini.value.split("/")  //fecha inicial
			        var fecha_final = document.formulario.fin.value.split("/")  /*fecha final*/

			        if(document.formulario.gestion.value<parseInt(fecha_inicial[2])){
			            alertify.error("NO SE PUEDE REGISTRAR DATOS POSTERIORES A "+document.formulario.gestion.value+", CONTACTESE CON EL ADMINISTRADOR");
			            document.formulario.f_ini.focus() 
			            return 0;
			        }

		        	reset();
	                alertify.confirm("GUARDAR DATOS DEL PROYECTO ?", function (a) {
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
        function verif_apertura(){
        	/*
			0 : Valor defecto
			1 : Error (Existe apertura)
			2 : Todo Bien
        	*/
        	prog=document.getElementById("prog").value;
      		proy=document.getElementById("proy").value;
      		act='000';

      	//	alert(prog+'-'+proy+'-'+act)
      		if(document.getElementById("proy").value=='9999'){
      			$('[name="tp_aper"]').val((0).toFixed(0));
      			$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA '+prog+''+proy+''+act+' DISPONIBLE POR DEFECTO</div></center>');	
      			$('#but').slideDown();
      		}
      		else{
      			//alert(prog+'-'+proy+'-'+act)
      			var url = "<?php echo site_url("")?>/programacion/proyecto/verif_apg_pi";
				$.ajax({
					type:"post",
					url:url,
					data:{prog:prog,proy:proy,act:act},
					success:function(datos){

						if(datos.trim() =='true'){
							$('[name="tp_aper"]').val((1).toFixed(0));
							$('#atit').html('<center><div class="alert alert-danger alert-block">APERTURA PROGRAMATICA YA SE ENCUENTRA REGISTRADO</div></center>');
							$('#but').slideUp();
							$('#nbut').slideDown();
						}else{
							$('[name="tp_aper"]').val((2).toFixed(0));
							$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA '+prog+''+proy+''+act+' DISPONIBLE</div></center>');
							$('#but').slideDown();
							$('#nbut').slideUp();
						}
				}});
      		}
        }
        $(document).ready(function () {
        	$("#prog").change(function () {            
            var prog = $(this).val(); /// Programa
                proy = $('[name="proy"]').val(); /// proyecto
                act='000';
             	
            //	alert(prog+'-'+proy+'-'+act)
            if(document.getElementById("proy").value=='9999'){
      			$('[name="tp_aper"]').val((0).toFixed(0));
      			$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA '+prog+''+proy+''+act+' DISPONIBLE POR DEFECTO</div></center>');	
      			$('#but').slideDown();
      		}
      		else{
      			//alert(prog+'-'+proy+'-'+act)
      			var url = "<?php echo site_url("")?>/programacion/proyecto/verif_apg_pi";
				$.ajax({
					type:"post",
					url:url,
					data:{prog:prog,proy:proy,act:act},
					success:function(datos){

						if(datos.trim() =='true'){
							$('[name="tp_aper"]').val((1).toFixed(0));
							$('#atit').html('<center><div class="alert alert-danger alert-block">APERTURA PROGRAMATICA YA SE ENCUENTRA REGISTRADO</div></center>');
							$('#but').slideUp();
							$('#nbut').slideDown();
						}else{
							$('[name="tp_aper"]').val((2).toFixed(0));
							$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA '+prog+''+proy+''+act+' DISPONIBLE</div></center>');
							$('#but').slideDown();
							$('#nbut').slideUp();
						}
				}});
      		}
                  
            });
        });
		</script>
	</body>
</html>
