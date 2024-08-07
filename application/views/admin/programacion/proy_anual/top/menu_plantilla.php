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
            #mdialTamanio{
          		width: 50% !important;
		      }
		      #mdialTamanio2{
		         width: 60.5% !important;
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
					<li>Programaci&oacute;n POA</li><li>Verificaci&oacute;n de Plantillas de Migraci&oacute;n - <?php echo $this->session->userdata('gestion')?></li>
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
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="col-xs-12 col-sm-6">
								<div class="well well-sm well-light">
									<a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-primary btn-block importar_ff" name="1" style="height:40px;">VER PLANTILLA DE MIGRACI&Oacute;N - ACTIVIDAD.CSV</a>
									<a href="#" data-toggle="modal" data-target="#modal_importar_ff" class="btn btn-primary btn-block importar_ff" name="2" style="height:40px;">VER PLANTILLA DE MIGRACI&Oacute;N - REQUERIMIENTOS (GLOBAL)</a>
								</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

		<!-- MODAL SUBIR PLANTILLA -->
		<div class="modal fade" id="modal_importar_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <h2><div id="titulo"></div></h2>
                    	<div id="datos"></div>
                        <div class="row">
                              <form action="<?php echo site_url().'/insumos/cprog_insumo/ver_operaciones_requerimientos'?>" enctype="multipart/form-data" id="form_subir_prev" name="form_subir_prev" class="form-horizontal" method="post">
                                <input type="hidden" name="tp" id="tp">
                                <fieldset>
		                            <div class="form-group">
		                            	<center><div id="img"></div></center>
		                            </div>
		                        </fieldset>  
                                <hr>
                                <div class="form-group">
                                	<b>SELECCIONAR ARCHIVO CSV</b>
	                                <div class="input-group">
	                                  <span class="input-group-btn">
	                                    <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
	                                    <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
	                                    <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
	                                  </span>
	                                  <span class="form-control"></span>
	                                </div>
	                            </div>
                                
                                <div>
                                    <button type="button" name="subir_archivo_prev" id="subir_archivo_prev" class="btn btn-success" style="width:100%;"><div id="buton"></div></button><br>
                                    <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                </div>
                              </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

	<!-- ======= MODAL VER ARCHIVO ACTIVIDADES =============== -->


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
		  <script type="text/javascript">
		    $(function () {
		        $(".importar_ff").on("click", function (e) {
		          tipo = $(this).attr('name');
		          document.getElementById("tp").value=tipo;
		          if(tipo==1){
		            $('#titulo').html('<center><b>VERIFICAR PLANTILLA MIGRACI&Oacute;N DE FORMULARIO N°4.CSV</b></center>');
		            $('#datos').html('<ul style="font-size: 13px;"><li type="circle"><b>Numero de columnas 22</b></li> <li type="circle"><b>Columna (A) COD. ACP. : Codigo Acción Corto Plazo</b></li> <li type="circle"><b>Columna (B) COD. OPE. : Codigo Operación</b></li> <li type="circle"><b>Columna (C) COD. ACT. : Codigo de Actividad</b></li> <li type="circle"><b>Columnas (H-U), Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>El archivo debe estar en formato .Csv</b></li></ul>');
		            $('#img').html('<img  src="<?php echo base_url() ?>/assets/img/img_migracion/migracion_f4.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
		            $('#buton').html('VER ARCHIVO FORMULARIO N° 4 (ACTIVIDADES.CSV)');
		          }
		          else{
		          	$('#titulo').html('<center><b>VERIFICAR PLANTILLA MIGRACI&Oacute;N DE FORMULARIO N°5.SCV</b></center>');
		            $('#datos').html('<ul style="font-size: 13px;"><li type="circle"><b>Numero de columnas 22</b></li> <li type="circle"><b>Columna (A) COD. ACT. : Codigo de Actividad</b></li> <li type="circle"><b>Columnas (E-S), por tratarse de presupuesto el Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>El archivo debe estar en formato .Csv</b></li></ul>');
		            $('#img').html('<img  src="<?php echo base_url() ?>/assets/img/img_migracion/migracion_form5.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
		            $('#buton').html('VER ARCHIVO DE FORMULARIO N° 5 (REQUERIMIENTOS.CSV)');
		          }
		        });
		    });
		</script>
		 <script type="text/javascript">
	      $(function () {
	        //VISTA PREVIA ARCHIVO
	        $("#subir_archivo_prev").on("click", function () {
	            var $valid = $("#form_subir_prev").valid();
	            if (!$valid) {
	                $validator.focusInvalid();
	            } else {
	              if(document.getElementById('archivo').value==''){
	                alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
	                return false;
	              }

	                alertify.confirm("DESEA VER ARCHIVO PREVIO A LA IMPORTACIÓN?", function (a) {
	                    if (a) {
	                        document.getElementById("load").style.display = 'block';
	                        document.getElementById('subir_archivo_prev').disabled = true;
	                        document.forms['form_subir_prev'].submit();
	                    } else {
	                        alertify.error("OPCI\u00D3N CANCELADA");
	                    }
	                });
	            }
	        });
	      });
	    </script>
<!-- 		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script> -->
	</body>
</html>
