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
          		width: 70% !important;
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


	<!-- ======= MODAL VER ARCHIVO ACTIVIDADES =============== -->
  <div class="modal animated fadeInDown" id="modal_importar_ff" tabindex="-1" role="dialog">
    <link href="<?php echo base_url(); ?>assets/file/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <script src="<?php echo base_url(); ?>assets/file/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/file/js/fileinput.min.js" type="text/javascript"></script> 
    <div class="modal-dialog" id="mdialTamanio2">
        <div class="modal-content">
            <div class="modal-body no-padding">
                <div class="row">
                   <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div id="titulo"></div>
                        <div class="col-sm-12">
                          <!-- well -->
                          <div class="well">
                            <hr>
                            <!-- row -->
                            <div class="row">
                              <!-- col -->
                              <div class="col-sm-12">
                                <!-- row -->
                                <div class="row">
                                  <p class="alert alert-warning">
                                    DETALLES A CONSIDERAR EN EL ARCHIVO EXCEL: 
                                  </p>
                                  <div id="datos"></div>
                                  <hr>
                                  <!-- <img  src="<?php echo base_url() ?>/assets/img/Vista Requerimientos global.jpg" style="border-style:solid;border-width:5px;" style="width:100%;"> -->
                                  <center><div id="img"></div></center>
                                  <hr>
                                  <p class="alert alert-warning">
                                    <i class="fa fa-warning"></i> Por favor guardar el archivo (Excel.xls) a extension (.csv) delimitado por (; "Punto y comas"). verificar el archivo .csv para su correcta importaci&oacute;n
                                  </p>
                                  	<form action="<?php echo site_url().'/insumos/cprog_insumo/ver_operaciones_requerimientos'?>" enctype="multipart/form-data" id="form_subir_prev" name="form_subir_prev" method="post">
                                      <input type="hidden" name="tp" id="tp">
                                      <fieldset>
                                        <section class="form-group">
                                          <label class="label"><b>SUBIR ARCHIVO .CSV </b></label>
                                          <label class="input input-file">
                                              <span class="button">
                                                <input id="archivo" accept=".csv" name="archivo" type="file" class="file">
                                                <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
                                              <b class="tooltip tooltip-top-left">
                                                 <i class="fa fa-warning txt-color-teal"></i> EL ARCHIVO A SUBIR, DEBE SER EXTENSION .CSV
                                              </b>
                                          </label>
                                        </section>
                                      </fieldset>
                                      <div >
                                        <button type="button" name="subir_archivo_prev" id="subir_archivo_prev" class="btn btn-warning" style="width:100%;"><div id="buton"></div></button>
                                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                      </div>
                                  	</form> 
                                </div>
                                <!-- end row -->
                              </div>
                              <!-- end col -->
                            </div>
                            <!-- end row -->
                          </div>
                          <!-- end well -->
                        </div>
                      </div>
                    </article>
                </div>   
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

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
		            $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <font color=blue><b>VERIFICAR PLANTILLA MIGRACI&Oacute;N DE ACTIVIDADES.CSV</b></font></h2>');
		            $('#datos').html('<ul style="font-size: 15px;"><li type="circle"><b>Copiar el contenido de datos del primer Archivo Excel a la plantilla de migración </b></li> <li type="circle"><b>Numero de columnas 21</b></li> <li type="circle"><b>Columna (A) COD. OR. : Codigo de Objetivo Regional</b></li> <li type="circle"><b>Columna (B) COD. ACT. : Codigo Actividad</b></li> <li type="circle"><b>Columnas (G-T), Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>Convertir el Archivo .Xls a .Csv</b></li></ul>');
		            $('#img').html('<img  src="<?php echo base_url() ?>/assets/img/actividades.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
		            $('#buton').html('VER ARCHIVO DE ACTIVIDADES.CSV');
		          }
		          else{
		            $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <font color=blue><b> VERIFICAR PLANTILLA MIGRACI&Oacute;N DE REQUERIMIENTOS.SCV </b></font></h2>');
		            $('#datos').html('<ul style="font-size: 15px;"><li type="circle"><b>Copiar el contenido de datos del primer Archivo Excel a la plantilla de migración </b></li> <li type="circle"><b>Numero de columnas 22</b></li> <li type="circle"><b>Columna (A) COD. ACT. : Codigo de Actividad</b></li> <li type="circle"><b>Columnas (E-S), por tratarse de presupuesto el Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>Convertir el Archivo .Xls a .Csv</b></li></ul>');
		            $('#img').html('<img  src="<?php echo base_url() ?>/assets/img/requerimientos_global.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
		            $('#buton').html('VER ARCHIVO DE REQUERIMIENTOS.CSV');
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
		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
	</body>
</html>
