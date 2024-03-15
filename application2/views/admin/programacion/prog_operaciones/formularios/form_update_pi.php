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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="Programacion de Proyectos">Programaci&oacute;n de Proyectos</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Datos Generales del Proyecto</li>
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
                                    <li class="active"><a href="#" title="DATOS GENERALES DE PROYECTO"><i class="glyphicon glyphicon-ok"></i><font size="2">&nbsp;DATOS GENERALES&nbsp;</font></a></li>
                                    <li><a href="<?php echo base_url().'index.php/admin/proy/proyecto_pi/'.$proyecto[0]['proy_id'].'' ?>" title="OBJETIVOS PROBLEMAS DEL PROYECTO DE INVERSI&Oacute;N"></i><font size="2">&nbsp;OBJETIVOS DEL PROYECTO&nbsp;</font></a></li>
                                    <li><a href="<?php echo base_url().'index.php/admin/proy/fase_etapa/'.$proyecto[0]['proy_id'].'' ?>" title="FASES DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;FASES DEL PROYECTO&nbsp;</font></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
					<div class="row">
						<article class="col-sm-12">
							<form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/programacion/proyecto/valida_update_proyecto'?>">
							<input type="hidden" name="form" id="form" value="1">
							<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'] ?>">
							<input type="hidden" name="aper_id" id="aper_id" value="<?php echo $prog_padre[0]['aper_id'] ?>">
							<input type="hidden" name="aper_proy" id="aper_proy" value="<?php echo $proyecto[0]['aper_proyecto'] ?>"> <!-- anterior -->

							<input type="hidden" name="gestion" id="gestion" value="<?php echo $this->session->userdata("gestion") ?>">
							<input type="hidden" name="gi" id="gi" value="<?php echo $proyecto[0]['inicio'] ?>">
							<input type="hidden" name="gf" id="gf" value="<?php echo $proyecto[0]['fin'] ?>">
							<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-columns"></i> </span>
									<h2>MODIFICACI&Oacute;N - PROYECTO DE INVERSI&Oacute;N</h2>
								</header>
								<!-- widget div-->
								<div>
									<div class="jarviswidget-editbox">
									</div>
									<div class="widget-body">
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  DATOS GENERALES - <?php echo strtoupper($proyecto[0]['tipo']).' : '.$proyecto[0]['proy_nombre'];?>
											</div>
										</div>
										<div class="row">
											
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<b>UNIDAD ORGANICA</b>
														<section>
															<label class="label" id="col"><b>REGIONAL</b></label>
								                                <select class="form-control" id="dep_id" name="dep_id" title="Seleccione Regional">
										                            <option value="">Seleccione Regional</option>
										                            <?php 
																        foreach($list_dep as $row){
																            if($row['dep_id']==$proyecto[0]['dep_id']){ ?>
															                    <option value="<?php echo $row['dep_id']; ?>" selected><?php echo $row['dep_departamento']; ?></option>
															                    <?php 
													                    	}
													                    	else{ ?>
															                    <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
															                    <?php 
													                    	}
																        }
																    ?>       
										                        </select>
														</section><br>

														<section>
															<label class="label" id="col"><b>DIRECCI&Oacute;N ADMINISTRATIVA</b></label>
								                            <select class="form-control" id="dist_id" name="dist_id" title="Seleccione Dirección Administrativa">
								                            	<option value="">No seleccionado</option>
								                            	<?php 
															        foreach($list_dist as $row){
															            if($row['dist_id']==$proyecto[0]['dist_id']){ ?>
														                    <option value="<?php echo $row['dist_id']; ?>" selected><?php echo $row['dist_cod'].' - '.$row['dist_distrital']; ?></option>
														                    <?php 
												                    	}
												                    	else{ ?>
														                    <option value="<?php echo $row['dist_id']; ?>"><?php echo $row['dist_cod'].' - '.$row['dist_distrital']; ?></option>
														                    <?php 
												                    	}
															        }
															    ?>
								                            </select>
														</section><br>
						
														<section>
															<label class="label" id="col"><b>UNIDAD EJECUTORA</b></label>
								                            <select class="form-control" id="ue_id" name="ue_id" title="Seleccione Unidad Ejecutora">
								                            	<option value="">No seleccionado</option>
								                            	<?php 
															        foreach($unidad_ejec as $row){
															            if($row['dist_id']==$proyecto[0]['dist_id']){ ?>
														                    <option value="<?php echo $row['dist_id']; ?>" selected><?php echo $row['dist_cod'].' - '.$row['dist_distrital']; ?></option>
														                    <?php 
												                    	}
												                    	else{ ?>
														                    <option value="<?php echo $row['dist_id']; ?>"><?php echo $row['dist_cod'].' - '.$row['dist_distrital']; ?></option>
														                    <?php 
												                    	}
															        }
															    ?>
								                            </select>
														</section><br>

														<div id="pi">
															<section>
																<label class="label" id="col"><b>PROYECTO DE INVERSI&Oacute;N</b></label>
									                            <textarea rows="4" class="form-control" style="width:100%;" name="nombre" id="nombre" title="REGISTRE NOMBRE DE PROYECTO DE INVERSI&Oacute;N"><?php echo $proyecto[0]['proy_nombre'];?></textarea> 
															</section>
														</div>
													</fieldset>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="well">
													<b>APERTURA PROGRAM&Aacute;TICA - <?php echo $this->session->userdata('gestion')?></b>
													<input class="form-control" type="hidden" name="tp_aper" id="tp_aper" value="0">
					                                <input class="form-control" type="hidden" name="aper_prog" id="aper_prog" value="<?php echo $prog_padre[0]['aper_id'] ?>" >
													<fieldset>
														<div id="atit"></div>
														<section>
															<label class="label" id="col"><b>PROGRAMA</b></label>
								                                <select class="form-control" name="prog" id="prog">
							                                    <option value="">Seleccione Programa</option>
											                    <?php
																	foreach($programas as $row) { 
																		if($row['aper_programa']==$proyecto[0]['aper_programa']){ ?>
														                    <option value="<?php echo $row['aper_id']; ?>" selected><?php echo $row['aper_programa'].' - '.$row['aper_descripcion']; ?></option>
														                    <?php 
												                    	}
												                    	else{ ?>
														                    <option value="<?php echo $row['aper_id']; ?>"><?php echo $row['aper_programa'].' - '.$row['aper_descripcion']; ?></option>
														                    <?php 
												                    	}	
																	} 
																?>    
											                    </select>
														</section><br>
														<section>
															<label class="label" id="col"><b>PROYECTO</b></label>
																<input class="form-control" type="text" name="proy" id="proy" value="<?php echo $proyecto[0]['aper_proyecto'];?>" onkeyup="verif_apertura();" data-mask="9999" data-mask-placeholder= "9" placeholder="Programa" title="Ingrese Proyecto">
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>ACTIVIDAD</b></label>
															<input class="form-control" type="text" id="act" name="act" disabled="true" value="000">
															</label>
														</section>
													</fieldset>
												</div><br>
												<div class="well">
													<fieldset>
														<section>
															<label class="label" id="col"><b>C&Oacute;DIGO SISIN</b></label>
																<?php 
																	if($proyecto[0]['proy_sisin']==''){ ?>
																		<input class="form-control" type="text" name="cod_sisin" id="cod_sisin"  value="9999-99999-99999" data-mask="9999-99999-99999" data-mask-placeholder= "X"   title="REGISTRE C&Oacute;DIGO SISIN">
																		<?php
																	}
																	else{?>
																		<input class="form-control" type="text" name="cod_sisin" id="cod_sisin"  value="<?php echo $proyecto[0]['proy_sisin'] ?>" data-mask="9999-99999-99999" data-mask-placeholder= "X"   title="REGISTRE C&Oacute;DIGO SISIN">
																		<?php
																	}
																?>
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>PRESUPUESTO TOTAL PROYECTO</b></label>
																<input class="form-control" type="text" name="ppto_proy" id="ppto_proy" value="<?php echo round($proyecto[0]['proy_ppto_total'],2);?>" onkeypress="if (this.value.length < 50) { return numerosDecimales(event);}else{return false; }" onpaste="return false" title="Ingrese Costo Total Proyecto">
															</label>
														</section>
													</fieldset>
												</div>
											</div>
										</div>
				
										<hr>
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  TIEMPO PROGRAMADO DE LA OPERACI&Oacute;N
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="input" id="col"><b>FECHA INICIAL</b></label>
						                                    <div class="input-group">
						                                    	<?php
						                                    		if(count($fase)==0 || count($fase)==1){ ?>
						                                    			<input type="text" name="ini" id="f_ini" placeholder="Seleccione Fecha inicial" class="form-control datepicker" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?>" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" title="MODIFICAR FECHA INICIAL DEL PROYECTO">
						                                    			<?php
						                                    		}
						                                    		else{ ?>
						                                    			<input class="form-control" type="hidden" name="ini" id="f_ini"  value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?>">
						                                    			<input type="text" placeholder="Seleccione Fecha inicial" class="form-control datepicker" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?>" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" disabled="true" title="NOSE PUEDE MODIFICAR FECHA INICIAL DEL PROYECTO">
						                                    			<?php
						                                    		}
						                                    	?>
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
															<label class="input" id="col"><b>FECHA FINAL</b></label>
						                                    <div class="input-group">
						                                    	<?php
						                                    		if(count($fase)==0 || count($fase)==1){ ?>
						                                    			<input type="text" name="final" id="f_final" placeholder="Seleccione Fecha final" class="form-control datepicker" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?>" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" title="MODIFICAR FECHA FINAL DEL PROYECTO">
						                                    			<?php
						                                    		}
						                                    		else{ ?>
						                                    			<input class="form-control" type="hidden" name="final" id="f_final"  value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?>">
						                                    			<input type="text" placeholder="Seleccione Fecha inicial" class="form-control datepicker" value="<?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?>" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" disabled="true" title="NOSE PUEDE MODIFICAR FECHA FINAL DEL PROYECTO">
						                                    			<?php
						                                    		}
						                                    	?>
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															</div>
														</section>
													</fieldset>
												</div>
											</div>
										</div>
										<hr>
										<div class="col-sm-12">
											<div id="but" align="right">
												<a href='<?php echo site_url("admin").'/proy/list_proy#tabs-a'; ?>' title="CANCELAR Y SALIR A MIS POAS" class="btn btn-default">CANCELAR</a>
												<input type="button" value="MODIFICAR REGISTRO" id="btsubmit" class="btn btn-primary" title="MODIFICAR REGISTRO"><br><br>
											</div>
										</div>
									</div>
								</div>
							</div>
							</form>
						</article>
					</div>
					<!-- end row -->
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
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script type="text/javascript">
        $(document).ready(function () {
        	$("#prog").change(function () {            
            var aper = $(this).val();
            	aper1 = parseFloat($('[name="aper_id"]').val()); /// aper antiguo
                proy = $('[name="proy"]').val(); /// Codigo proyecto nuevo
                proy1 = $('[name="aper_proy"]').val(); /// Codigo proyecto antiguo

                if(aper!=aper1 || proy!=proy1){
            		if(proy=='9999'){
		      			$('[name="tp_aper"]').val((0).toFixed(0));
		      			$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA DISPONIBLE POR DEFECTO</div></center>');	
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
								}else{
									$('[name="tp_aper"]').val((2).toFixed(0));
									$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA DISPONIBLE</div></center>');
									$('#but').slideDown();
								}
						}});
		      		}
            	}
            });
        });
        </script>
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
		        	var fecha_inicial = document.formulario.f_ini.value.split("/")  //fecha inicial
			        var fecha_final = document.formulario.f_final.value.split("/")  /*fecha final*/

			        if(parseInt(fecha_final[2])<parseInt(fecha_inicial[2])){
			            alertify.error('Error!!  en las Fechas, verifique las gestiones del proyecto')
			            document.formulario.f_final.focus() 
			            return 0;
			        }

			        if(document.formulario.gestion.value<parseInt(fecha_inicial[2])){
			            alertify.error("No puede Modificar la gestion Inicial, el sistema esta actualmente en la gestion "+document.formulario.gestion.value)
			            document.formulario.f_ini.focus() 
			            return 0;
			        }

			        if(parseInt(fecha_inicial[2])<'2008'){
			            alertify.error('Error!!  Gestion Inicial, verifique dato')
			            document.formulario.f_ini.focus() 
			            return 0;
			        }

			        if(parseInt(fecha_final[2])>'2027' ){
			            alertify.error('Error!! Gestion final, verifique dato')
			            document.formulario.f_final.focus() 
			            return 0;
			        }

			        if(document.formulario.gestion.value<parseInt(fecha_inicial[2]) || parseInt(fecha_inicial[2])<"<?php echo  $proyecto[0]['inicio']?>" || parseInt(fecha_inicial[2])>"<?php echo  $proyecto[0]['inicio']?>"){
			            alertify.alert("Se modificara la gestion de inicio del "+document.formulario.gi.value+" a "+parseInt(fecha_inicial[2]))
			        }

			        if(parseInt(fecha_final[2])<document.formulario.gf.value || parseInt(fecha_final[2])>document.formulario.gf.value){
			            alertify.alert("Se modificara la gestion final del "+document.formulario.gf.value+" a "+parseInt(fecha_final[2]))
			        }

/*			        prog=document.getElementById("prog").value; /// Nuevos
      				proy=document.getElementById("proy").value; /// Nuevos

      				alert(prog+'--'+proy)*/

		        	reset();
	                alertify.confirm("GUARDAR DATOS DEL PROYECTO ?", function (a) {
	                    if (a) {
	                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
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
        	prog_ant=document.getElementById("aper_id").value; /// Antiguos
      		proy_ant=document.getElementById("aper_proy").value; /// Antiguos

        	prog=document.getElementById("prog").value; /// Nuevos
      		proy=document.getElementById("proy").value; /// Nuevos

      		act='000';

      		if(prog_ant!=prog || proy_ant!=proy){
      			if(proy=='9999'){
      			$('[name="tp_aper"]').val((0).toFixed(0));
      			$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA DISPONIBLE POR DEFECTO</div></center>');	
      			$('#but').slideDown();
	      		}
	      		else{
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
						}
						else{
							$('[name="tp_aper"]').val((2).toFixed(0));
							$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA DISPONIBLE</div></center>');
							$('#but').slideDown();
						}
					}});
	      		}
      		}
      		
        }
		</script>
	</body>
</html>
