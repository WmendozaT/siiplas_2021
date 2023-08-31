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
		<!-- FAVICONS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<style>
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
                            <i class="fa fa-user" aria-hidden="true"></i>  <?php echo $this->session->userdata("user_name");?>
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
					<li>...</li><li>Red de Acciones</li><li>Acciones de Mediano Plazo</li><li>Resultados de Corto Plazo</li><li>Temporalidad de Corto Plazo</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
	                <?php
	                  $attributes = array('class' => 'form-horizontal', 'id' => 'formulario','name' =>'formulario','enctype' => 'multipart/form-data');
	                  echo validation_errors();
	                  echo form_open('prog/valida_temporalidad_cplazo', $attributes);
	                ?>
				<!-- widget grid -->
				<section id="widget-grid" class="">
				<div class="row">
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section id="widget-grid" class="well">
                                <div class="">
                                  <h1> RESULTADOS DE CORTO PLAZO</h1>
                                  <h1> PROGRAMA : <small><?php echo $dato_poa[0]['aper_programa'] . $dato_poa[0]['aper_proyecto'] . $dato_poa[0]['aper_actividad'] . " - " . $dato_poa[0]['aper_descripcion'];?></small>
                                  <h1> OBJETIVO ESTRAT&Eacute;GICO : <small><?php echo $obj_estrategico[0]['obj_descripcion'];?></small></h1>
                                  <h1> ACCI&Oacute;N ESTRAT&Eacute;GICA DE MEDIANO PLAZO : <small><?php echo $accion_estrategica[0]['acc_descripcion'];?></small></h1>
                                  <h1> RESULTADO DE CORTO PLAZO : <small><?php echo $resultado[0]['rm_resultado'];?></small> || PROGRAMADO GESTI&Oacute;N <?php echo $this->session->userData('gestion'); ?>: <small><?php echo $prog[0]['rmp_prog'];?></small></h1>
                                </div>
                            </section>
                        </article>
                    </div>
                    <div class="row">
					<!-- row -->
						<form id="formulario" name="formulario" novalidate="novalidate" method="post">
						<input type="hidden" name="rm_id" value="<?php echo $resultado[0]['rm_id']?>">
						<input type="hidden" name="poa_id" value="<?php echo $dato_poa[0]['poa_id']?>">
						<input type="hidden" name="acc_id" value="<?php echo $accion_estrategica[0]['acc_id']?>">
						<input type="hidden" name="lb" value="<?php echo $resultado[0]['rm_linea_base']?>">
						<input type="hidden" name="rmp_id" value="<?php echo $prog[0]['rmp_id']?>">
						<input type="hidden" name="g_id" value="<?php echo $prog[0]['rmp_prog']?>">
						<article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						</article>
							<article class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<?php 
		                          if($this->session->flashdata('success')){ ?>
		                            <div class="alert alert-success">
		                                <?php echo $this->session->flashdata('success'); ?>
		                            </div>
		                            <script type="text/javascript">alertify.success("<?php echo '<font size=2>'.$this->session->flashdata('success').'</font>'; ?>")</script>
		                        <?php 
		                            }
		                          elseif($this->session->flashdata('danger')){ ?>
		                              <div class="alert alert-danger">
		                                <?php echo $this->session->flashdata('danger'); ?>
		                              </div>
		                              <script type="text/javascript">alertify.error("<?php echo '<font size=2>'.$this->session->flashdata('danger').'</font>'; ?>")</script>
		                            <?php
		                          }
                        		?>
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>TEMPORALIDAD DE CORTO PLAZO</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<table class="table table-bordered" style="width:100%;" >
									    <thead>
									        <tr>
									            <th style="width:20%;"><center>ENERO</center></th>
									            <th style="width:20%;"><center>FEBRERO</center></th>
									            <th style="width:20%;"><center>MARZO</center></th>
									            <th style="width:20%;"><center>ABRIL</th>
									        </tr>
									    </thead>
									    <tbody>
									        <tr>
									            <td><input  name="m1" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[1]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m2" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[2]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m3" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[3]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m4" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[4]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            </tr>
									    </tbody>
									</table>
									<table class="table table-bordered table-hover" style="width:100%;" >
									    <thead>
									        <tr>
									            <th style="width:20%;"><center>MAYO</center></th>
									            <th style="width:20%;"><center>JUNIO</center></th>
									            <th style="width:20%;"><center>JULIO</center></th>
									            <th style="width:20%;"><center>AGOSTO</center></th>
									            </tr>
									    </thead>
									    <tbody>
									        <tr>
									            <td><input name="m5" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[5]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input name="m6" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[6]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input name="m7" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[7]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input name="m8" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[8]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            </tr>
									    </tbody>
									</table>
									<table class="table table-bordered table-hover" style="width:100%;" >
									    <thead>
									        <tr>
									            <th style="width:20%;"><center>SEPTIEMBRE</center></th>
									            <th style="width:20%;"><center>OCTUBRE</center></th>
									            <th style="width:20%;"><center>NOVIEMBRE</center></th>
									            <th style="width:20%;"><center>DICIEMBRE</center></th>
									            </tr>
									    </thead>
									    <tbody>
									        <tr>
									            <td><input  name="m9" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[9]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m10" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[10]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m11" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[11]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            <td><input  name="m12" class="form-control" type="text" onkeyup="suma_programado_mensual();" style="width:100%;" value="<?php echo $temporalidad[12]; ?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
									            </tr>
									    </tbody>
									</table>
								</div>
								<div class="col-sm-12">
									<div class="form-group">
										<label><font size="1" color="blue"><b>SUMA TOTAL PROGRAMADO</b></font></label>
										<input class="form-control" name="total" type="text" id="total" value="<?php echo $temporalidad[0];?>" disabled="true" >
									</div>
								</div>
								<div class="form-actions">
								<a href="<?php echo base_url().'index.php/prog/resultado_cplazo/'.$dato_poa[0]['poa_id'].'/'.$accion_estrategica[0]['acc_id'].'' ?>" class="btn btn-default" title="VOLVER A MIS RESULTADOS"> CANCELAR </a>
								<input type="button" value="GUARDAR TEMPORALIDAD" id="btsubmit" class="btn btn-primary" onclick="valida_envia_temporalidad()" title="GUARDAR TEMPORALIDAD">
								<center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
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
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
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
