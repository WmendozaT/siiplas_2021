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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
	    <meta name="viewport" content="width=device-width">
		<style>
			table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
		</style>
	</head>
	<body class="">
		<header id="header">
			<div id="logo-group">
				<!-- <span id="logo"> <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="SmartAdmin"> </span> -->
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
		                <a href="#" title="PROGRAMACION"> <span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li>...</li><li>Objetivos Estrategicos</li><li>Acciones Estrat&eacute;gicas</li><li>Resultados de Mediano Plazo</li><li>Producto Terminal</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
	                        <section id="widget-grid" class="well">
	                            <div class="">
	                            	<h1><small>OBJETIVO ESTRAT&Eacute;GICO : <?php echo $obj_estrategico[0]['obj_descripcion'];?></small></h1>
	                              	<h1><small>ACCI&Oacute;N ESTRAT&Eacute;GICA : <?php echo $accion_estrategica[0]['acc_descripcion'];?></small></h1>
	                              	<h1><small>RESULTADO DE MEDIANO PLAZO : <?php echo $resultado[0]['rm_resultado'] ?></small></h1>
	                              	<h1><small>PRODUCTO TERMINAL : <?php echo $configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'];?></small></h1>
	                            </div>
	                        </section>
	                    </article>
	                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <section id="widget-grid" class="well">
                              <center>
                                <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/me/rep_pterminal_mp/'.$resultado[0]['rm_id'];?>');">IMPRIMIR RESULTADOS</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/me/resultados_mplazo/'.$accion_estrategica[0]['acc_id']; ?>">VOLVER ATRAS</a></li>
                                </ul>
                              </div>
                              </center>
                            </section>
                        </article>
	                </div>
	                <div class="row">
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
	                    <?php echo $pterminal_mp;?>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

		<!-- END MAIN PANEL -->
		<!-- ========================================================================================================= -->
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
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
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
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
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

		        // =====================================================================
		        $(".del_ff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var request;
		            // confirm dialog
		            alertify.confirm("DESEA ELIMINAR PRODUCTO TERMINAL ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/me/delete_resultado_mplazo";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "rm_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("EL PRODUCTO TERMINAL SE ELIMINO CORRECTAMENTE ", function (e) {
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
	</body>
</html>
