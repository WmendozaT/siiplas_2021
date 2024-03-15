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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="Programacion POA">Programaci&oacute;n POA</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Datos Generales</li>
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
                                    <li><a href="<?php echo base_url().'index.php/admin/proy/servicios/'.$proyecto[0]['proy_id'].'' ?>" title="SERVICIOS"><font size="2">&nbsp;SERVICIOS DE UNIDAD / ESTABLECIMIENTOS</font></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
					<div class="row">
						<article class="col-sm-12">
							<form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/programacion/proyecto/valida_update_operacion'?>">
							<input type="hidden" name="id" id="id" value="<?php echo $proyecto[0]['proy_id'] ?>">
							<input type="hidden" name="aper_programa" id="aper_programa" value="<?php echo $proyecto[0]['aper_programa'] ?>">
							<input type="hidden" name="cod_ant" id="cod_ant" value="<?php echo $proyecto[0]['proy_codigo'] ?>">
							<input type="hidden" name="act_ant" id="act_ant" value="<?php echo $proyecto[0]['act_id'] ?>">
							<input type="hidden" name="tp_id" id="tp_id" value="<?php echo $proyecto[0]['tp_id'];?>">
							<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-columns"></i> </span>
									<h2>MODIFICAR DATOS GENERALES</h2>
								</header>
								<!-- widget div-->
								<div>
									<div class="jarviswidget-editbox">
									</div>
									<div class="widget-body">
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  DATOS GENERALES POA - <?php echo $this->session->userdata("gestion").' : '.$proyecto[0]['proy_nombre'];?>
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

														<section>
															<label class="label" id="col"><b>C&Oacute;DIGO UNIDAD / ESTABLECIMIENTO</b></label>
															<input class="form-control" type="hidden" name="codigo" id="codigo" value="<?php echo $proyecto[0]['proy_codigo'] ?>">
															<input class="form-control" type="text" id="cod" value="<?php echo $proyecto[0]['proy_codigo'] ?>" disabled="true">
															</label>
														</section><br>

														<section>
															<label class="label" id="col"><b>UNIDAD / ESTABLECIMIENTO</b></label>
								                            <select class="form-control" id="act_id" name="act_id" title="Seleccione Actividad Institucional">
								                            	<option value="">No seleccionado</option>
								                            	<?php 
															        foreach($list_actividades as $row){
															            if($row['act_id']==$proyecto[0]['act_id']){ ?>
														                    <option value="<?php echo $row['act_id']; ?>" selected><?php echo $row['act_cod'].'.- '.$row['act_descripcion'];?></option>
														                    <?php 
												                    	}
												                    	else{ ?>
														                    <option value="<?php echo $row['act_id']; ?>"><?php echo $row['act_cod'].'.- '.$row['act_descripcion'];?></option>
														                    <?php 
												                    	}
															        }
															    ?>
								                            </select>
														</section>
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
															<input type="text" class="form-control" maxlength="10" name="proy" id="proy" disabled="true" value="0000" title="Proyecto - <?php echo $this->session->userdata('gestion')?>"> 
															</label>
														</section><br>
														<section>
															<label class="label" id="col"><b>ACTIVIDAD</b></label>
															<input class="form-control" type="hidden" name="act" id="act" value="<?php echo $proyecto[0]['aper_actividad']; ?>">
															<input class="form-control" type="text" id="acti" disabled="true" value="<?php echo $proyecto[0]['aper_actividad']; ?>">
															</label>
														</section>
													</fieldset>
												</div><br>
												<div class="well">
													<b>VINCULACI&Oacute;N POA - PEI</b>
													<fieldset>
														<section>
															<label class="label" id="col"><b>OBJETIVO REGIONAL <?php echo $this->session->userdata('gestion').'---'.$proyecto[0]['por_id']?> </b></label>
								                            <select class="form-control" id="por_id" name="por_id" title="Seleccione vonculacion a Objetivo Regional">
								                            	<?php
								                            		if(count($oregional_prog)!=0){ ?>
								                            			<option value="<?php echo $oregional_prog[0]['por_id'];?>"><?php echo 'O.R. : '.$oregional_prog[0]['or_objetivo'].' - O.G. : '.$oregional_prog[0]['og_objetivo']; ?></option>
								                            			<?php
								                            		}
								                            		else{ ?>
								                            			<option value="0">NINGUNA VINCULACI&Oacute;N </option>
								                            			<?php
								                            		}
								                            	?>
								                            </select>
														</section>
													</fieldset>
												</div>
											</div>
										</div>
				
										<hr>
										<div class="col-sm-12">
											<div class="alert alert-success" align="center">
											  RESPONSABLES POA
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<div class="well">
													<section>
														<label class="label" id="col"><b>RESPONSABLE DE UNIDAD EJECUTORA</b></label>
							                                <select class="form-control" name="fun_id_1" id="fun_id_1" title="Seleccione Responsable Regional">
								                            	<option value="">No seleccionado</option>
								                            	<?php 
																foreach($fun1 as $row) {
																	if($row['fun_id']==$resp1[0]['fun_id']) { ?>
																	<option value="<?php echo $row['fun_id']?>" selected <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
																	<?php }
																	else { ?>
																	<option value="<?php echo $row['fun_id']?>" <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
																	<?php }
																} ?> 
								                            </select>
							                                
													</section>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="well">
													<section>
														<label class="label" id="col"><b>RESPONSABLE ANALISTA POA</b></label>
							                                <select class="form-control" name="fun_id_2" id="fun_id_2">
						                                    <option value="">Seleccione Resp. Analista POA</option>
										                    <?php
															foreach($fun2 as $row) {
																if($row['fun_id']==$resp2[0]['fun_id']){ ?>
																<option value="<?php echo $row['fun_id']?>" selected <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
																<?php }
																else { ?>
																<option value="<?php echo $row['fun_id']?>" <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
																<?php }	
															} ?>    
										                    </select>
													</section>
												</div>
											</div>
										</div>
										<hr>
										<div class="col-sm-12">
											<div id="but" align="right">
												<a href='<?php echo site_url("admin").'/proy/list_proy'; ?>' title="CANCELAR Y SALIR A MIS OPERACIONES" class="btn btn-default">CANCELAR</a>
												<input type="button" value="MODIFICAR DATOS UNIDAD / ESTABLECIMIENTO" id="btsubmit" class="btn btn-primary" onclick="valida_envia()" title="MODIFICAR REGISTRO"><br><br>
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
        	$("#act_id").change(function () {            
            var act_id = $(this).val();

            	var url = "<?php echo site_url("")?>/mnt/get_actividad";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "act_id=" + act_id
                });

                request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                	if(response.actividad[0]['act_cod']!=0){
                		document.getElementById("codigo").value = response.actividad[0]['act_cod'];
                    	document.getElementById("cod").value = response.actividad[0]['act_cod'];

                    	if(response.actividad[0]['act_cod'].length==3){
                    		document.getElementById("act").value = response.actividad[0]['act_cod'];
                    		document.getElementById("acti").value = response.actividad[0]['act_cod'];
                    	}
                    	if(response.actividad[0]['act_cod'].length==2){
                    		var cad='0';
                    		document.getElementById("act").value = cad.concat(response.actividad[0]['act_cod']);
                    		document.getElementById("acti").value = cad.concat(response.actividad[0]['act_cod']);
                    	}
                    	if(response.actividad[0]['act_cod'].length==1){
                    		var cad='00';
                    		document.getElementById("act").value = cad.concat(response.actividad[0]['act_cod']);
                    		document.getElementById("acti").value = cad.concat(response.actividad[0]['act_cod']);
                    	}

                    	$('#atit').html('<center><div class="alert alert-success alert-block">UNIDAD/ESTABLECIMIENTO DISPONIBLE REGISTRAR</div></center>');
                    	if(prog!=''){
                    		$('#but').slideDown();
                    	}
                    	else{
                    		$('#but').slideUp();
                    	}
	                    $('#but').slideDown();
                	}
                	else{
                		document.getElementById("codigo").value = 'SIN CODIGO';
                    	document.getElementById("cod").value = 'SIN CODIGO';
                    	document.getElementById("act").value = 999;
                    	document.getElementById("acti").value = 999;
                    	$('#atit').html('<center><div class="alert alert-warning alert-block">APERTURA PROGRAMÁTICA POR DEFECTO</div></center>');
                        $('#but').slideDown();
                	}
                    
                }
                else{
                    alertify.error("ERROR AL RECUPERAR DATOS DE LA UNIDAD");
                }

                });
            });
        });
        $(document).ready(function () {
        	$("#prog").change(function () {
            var aper = $(this).val();
                cod = $('[name="codigo"]').val(); /// Codigo nuevo

                cod1 = $('[name="cod_ant"]').val(); /// Codigo antiguo
                aper1 = parseFloat($('[name="aper_prog"]').val()); /// aper antiguo

                if(aper!=''){
                	$('#but').slideDown();
                	if(cod=='SIN CODIGO'){
	                	if(aper!=aper1 || cod!=cod1){
		            		var url = "<?php echo site_url("")?>/mnt/verif";
			                $.ajax({
			                    type:"post",
			                    url:url,
			                    data:{cod:cod,aper:aper},
			                    success:function(datos){
			                        
			                        if(datos.trim() =='true'){
			                            $('#atit').html('<center><div class="alert alert-danger alert-block">PROGRAMA REGISTRADO</div></center>');
			                            $('#but').slideUp();
			                            
			                        }else{
			                            $('#atit').html('<center><div class="alert alert-success alert-block">PROGRAMA DISPONIBLE REGISTRAR</div></center>');
			                            $('#but').slideDown();
			                        }
			                }});  
		            	}
	                } 
                }
                else{
                	$('#but').slideUp();
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
					$('#act_id').html('');
					$('#por_id').html('');

					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_administrativas", { elegido: elegido,accion:'distrital' }, function(data){
						$("#dist_id").html(data);
					});     
				});
			});
			$("#ue_id").change(function () {
				$("#ue_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_act", { elegido: elegido,accion:'actividad' }, function(data){
						$("#act_id").html(data);
					});     
				});
			});
			$("#act_id").change(function () {
				$("#act_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/me/combo_oregional", { elegido: elegido,accion:'oregional' }, function(data){
						$("#por_id").html(data);
					});     
				});
			});
			$("#dep_id").change(function () {
				$("#dep_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
						$("#ue_id").html(data);
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
		function valida_envia(){
			if (document.formulario.act_id.value==""){ 
	            alertify.error("SELECCIONE UNIDAD / ESTABLECIMIENTO");
	            document.formulario.act_id.focus() 
	            return 0; 
	        }

	        if (document.formulario.fun_id_1.value==""){ 
	            alertify.error("SELECCIONE RESPONSABLE DE UNIDAD EJECUTORA");
	            document.formulario.fun_id_1.focus() 
	            return 0; 
	        }

	        if (document.formulario.fun_id_2.value==""){ 
	            alertify.error("SELECCIONE RESPONSABLE ANALISTA POA");
	            document.formulario.fun_id_2.focus() 
	            return 0; 
	        }

			if (document.formulario.por_id.value==""){ 
	            alertify.error("SELECCIONE VINCULACIÓN A OBJETIVO REGIONAL");
	            document.formulario.por_id.focus() 
	            return 0; 
	        }

	        if (document.formulario.prog.value==""){ 
	            alertify.error("SELECCIONE PROGRAMA");
	            document.formulario.prog.focus() 
	            return 0; 
	        }

            alertify.confirm("MODIFICAR DATOS DE LA UNIDAD / ESTABLECIMIENTO ?", function (a) {
                if (a) {
                        document.getElementById('btsubmit').disabled = true;
                        document.formulario.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
        }
		</script>
		<script>
        function verif_apertura(){
        	/*
			0 : Valor defecto
			1 : Error (Existe apertura)
			2 : Todo Bien
        	*/
        	prog=document.getElementById("prog").value 
      		proy='0000'
      		act=document.getElementById("act").value
      		if(document.getElementById("act").value=='999'){
      			$('[name="tp_aper"]').val((0).toFixed(0));
      			$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA '+prog+''+proy+''+act+' DISPONIBLE POR DEFECTO</div></center>');	
      			$('#but').slideDown();
      		}
      		else{
      			//alert(prog+'-'+proy+'-'+act)
      			var url = "<?php echo site_url("admin")?>/proy/verif";
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
							$('#atit').html('<center><div class="alert alert-success alert-block">APERTURA PROGRAMATICA DISPONIBLE</div></center>');
							$('#but').slideDown();
							$('#nbut').slideUp();
						}
				}});
      		}
        }
		</script>
	</body>
</html>
