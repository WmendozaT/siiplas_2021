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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<style>
			.table{
              font-size: 9px;
              display: inline-block;
              width:100%;
              max-width:1550px;
              }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9px;
            }
		</style>
	</head>
	<body class="">
		<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">
			<!-- 	<span id="logo"> <img src="<?php echo base_url(); ?>assets/img/cajalogo.JPG" alt="SmartAdmin"> </span> -->
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
                            <i class="fa fa-user" aria-hidden="true"></i> <?php echo $this->session->userdata("user_name");?>
                        </span>
						<i class="fa fa-angle-down"></i>
					</a> 
				</span>
			</div>

			<nav>
				<ul>
					<li class="">
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i
	                        class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="#" title="PROGRAMACION DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li>Marco Estrategico</li><li>Objetivos Estrategicos</li><li>Acciones Estrat&eacute;gicas</li><li>Resultados de Mediano Plazo</li><li>Nuevo</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
	                <?php
	                  $attributes = array('class' => 'form-horizontal', 'id' => 'formulario','name' =>'formulario','enctype' => 'multipart/form-data');
	                  echo validation_errors();
	                  echo form_open('me/valida_resultado_mplazo', $attributes);
	                ?>
				<!-- widget grid -->
				<section id="widget-grid" class="">
				<div class="row">
					<!-- row -->
						<form id="formulario" name="formulario" novalidate="novalidate" method="post">
						<input type="hidden" name="acc_id" id="acc_id" value="<?php echo $accion_estrategica[0]['acc_id'];?>">
						<input type="hidden" name="pdes_id" id="pdes_id" value="<?php echo $accion_estrategica[0]['pdes_id'];?>">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>T&Eacute;CNICO RESPONSABLE DEL RESULTADO</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="well">
										<div class="row">
											<div class="col-sm-6">
												<div class="form-group">
													<label><font size="1"><b>RESPONSABLE DEL RESULTADO </b></font><font color="blue">(Obligatorio)</font></label>
														<select class="select2" id="fun_id" name="fun_id" title="Seleccione Responsable Operativo">
			                                                <option value="">Seleccione Responsable</option>
		                                                    <?php 
										                    foreach($responsables as $row){ ?>
										                        <option value="<?php echo $row['fun_id']?>" <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
										                    <?php }
										                    ?>    
			                                            </select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="form-group">
													<label><font size="1"><b>UNIDAD ORGANIZACIONAL </b></font><font color="blue">(Obligatorio)</font></label>
													<select class="form-control" id="uni_id" name="uni_id">
													<option value="<?php echo $this->session->userdata("uni_id")?>" ><?php echo $this->session->userdata("unidad"); ?></option> 
	                                                  </select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article>
						
						<article class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>RESULTADO DE MEDIANO PLAZO</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="well">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label><font size="1"><b>RESULTADO DE MEDIANO PLAZO </b></font><font color="blue">(Registro Obligatorio)</font></label>
												<textarea rows="5" class="form-control" name="resultado" id="resultado" style="width:100%;"  title="Ingrese Resultado de Mediano Plazo" maxlength="600"></textarea> 
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
											<label><font size="1"><b>TIPO DE INDICADOR</b></font></label>
												<select class="form-control" id="tipo_i" name="tipo_i">
                                                    <option value="">Seleccione Indicador</option>
                                                    <?php 
									                    foreach($indi as $row){ ?>
											                <option value="<?php echo $row['indi_id']; ?>"><?php echo $row['indi_descripcion']; ?></option>
											                <?php 	
									                    }
									                ?>
                                              	</select>
											</div>
										</div>
									
										<div class="col-sm-8">
											<div class="form-group">
												<label><font size="1"><b>INDICADOR</b></font></label>
												<textarea rows="4" class="form-control" style="width:100%;" name="indicador" id="indicador" maxlength="200"></textarea> 
											</div>
										</div>

										<div id="rel" style="display:none;">
											<div class="row">
												<div class="col-sm-9">
													<div class="form-group">
														<label><font size="1"><b>FORMULA</b></font></label>
														<textarea rows="3" class="form-control" style="width:100%;" name="formula" id="formula" maxlength="200"></textarea> 
													</div>
												</div>
												<div class="col-sm-3">
													<div class="form-group">
														<label><font size="1"><b>DENOMINADOR</b></font></label>
														<label class="radio state-success"><input type="radio" name="den" value="0"checked><i></i>Variable</label>
														<label class="radio state-success"><input type="radio" name="den" value="1"><i></i>Fijo</label>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-sm-4">
												<div class="form-group">
												<label><font size="1"><b>LINEA BASE</b></font></label>
													<input class="form-control" type="text" name="lb" id="lb" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"  onpaste="return false">
												</div>
											</div>
										
											<div class="col-sm-4">
												<div class="form-group">
													<div ><label><font size="1"><b>META</b></font></label></div>
													<input class="form-control" type="text" name="met" id="met" value="0" onkeyup="suma_programado()" placeholder="0 %" onkeyup="javascript:costo_unitario();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
												</div>
											</div>

											<div class="col-sm-4">
												<div class="form-group">
													<label><font size="1"><b>PONDERACI&Oacute;N</b></font></label>
													<input class="form-control" type="text" name="pn_cion" id="pn_cion" value="0" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-sm-12">
												<div class="form-group">
												<label><font size="1"><b>FUENTE DE VERIFICACI&Oacute;N</b></font></label>
													<textarea rows="4" class="form-control" style="width:100%;" name="verificacion" id="verificacion" maxlength="150"></textarea> 
												</div>
											</div>
										</div>
										<div id="rel2" style="display:none;">
											<div class="row">
												<div class="col-sm-12">
													<label><font size="1"><b>CARACTERISTICAS</b></font></label>
													<div class="form-group">
														<div class="col-sm-6">
															<div class="form-group">
																<label><font size="1"><b>CASOS FAVORABLES</b></font></label>
																<textarea rows="3" name="c_a" id="c_a" class="form-control" style="width:100%;" ></textarea> 
															</div>
														</div>
													
														<div class="col-sm-6">
															<div class="form-group">
																<label><font size="1"><b>CASOS DESFAVORABLES</b></font></label>
																<textarea rows="3" name="c_b" id="c_b" class="form-control" style="width:100%;"></textarea> 
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>																		
									</div>
								</div>
								</div>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>TEMPORALIDAD</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div>
									<center><strong>INDICADOR <b id="titulo_indicador"></b></strong></center>		
									</div><br>
									<table class="table table-bordered table-hover" style="width:100%;" >
									    <thead>
									        <tr>
									            <th style="width:20%;"><center>GESTI&Oacute;N <?php echo $configuracion[0]['conf_gestion_desde'];?><b id="m1"></center></th>
									            <th style="width:20%;"><center>GESTI&Oacute;N <?php echo $configuracion[0]['conf_gestion_desde']+1;?><b id="m2"></center></th>
									            <th style="width:20%;"><center>GESTI&Oacute;N <?php echo $configuracion[0]['conf_gestion_desde']+2;?><b id="m3"></center></th>
									            <th style="width:20%;"><center>GESTI&Oacute;N <?php echo $configuracion[0]['conf_gestion_desde']+3;?><b id="m4"></center></th>
									            <th style="width:20%;"><center>GESTI&Oacute;N <?php echo $configuracion[0]['conf_gestion_desde']+4;?><b id="m5"></center></th>
									        </tr>
									    </thead>
									    <tbody>
									        <tr>
									            <td><input  name="g1" class="form-control" type="text" onkeyup="suma_programado();" style="width:100%;" value="0"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="g2" class="form-control" type="text" onkeyup="suma_programado();" style="width:100%;" value="0"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="g3" class="form-control" type="text" onkeyup="suma_programado();" style="width:100%;" value="0"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="g4" class="form-control" type="text" onkeyup="suma_programado();" style="width:100%;" value="0"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="g5" class="form-control" type="text" onkeyup="suma_programado();" style="width:100%;" value="0"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									        </tr>
									    </tbody>
									</table>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label><font size="1" color="blue"><b>SUMA TOTAL PROGRAMADO + LINEA BASE</b></font></label>
										<input class="form-control" name="total" type="text" id="total" value="0" disabled="true" >
									</div>
								</div>
								<div class="form-actions">
								<a href="<?php echo base_url().'index.php/me/resultados_mplazo/'.$accion_estrategica[0]['acc_id'] ?>" class="btn btn-lg btn-default" title="VOLVER A MIS PROYECTOS"> CANCELAR </a>
								<input type="button" value="GUARDAR RESULTADO" id="btsubmit" class="btn btn-primary btn-lg" onclick="valida_envia()" title="GUARDAR REGISTRO">
							</div>
						</article>
						</form>
					</div>
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
		<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/resultados/resultados_mc_plazo.js" type="text/javascript"></SCRIPT>
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
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
            $("#fun_id").change(function () {
				$("#fun_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/me/combo_fun_uni", { elegido: elegido,accion:'unidad' }, function(data){
						$("#uni_id").html(data);
					});     
				});
			}); 
		})
		</script>
	</body>
</html>
