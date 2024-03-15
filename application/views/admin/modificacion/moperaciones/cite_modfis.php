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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!--para las alertas-->
    	<meta name="viewport" content="width=device-width">
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
              background-color: #dedada;
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
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Salir" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->
				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->
				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i></a> </span>
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
		                <a href="#" title="PROGRAMACION"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
					<li>Modificaciones</li><li>Modificaci&oacute;n POA</li><li>Mis Subactividades</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
					<section id="widget-grid" class="well">
						<div class="">
							<?php echo $titulo_proy;?>
						</div>
					</section>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                    <div class="well">
                        <div class="btn-group btn-group-justified">
                            <a class="btn btn-default" href="<?php echo base_url();?>index.php/mod/list_top" title="SALIR A LISTA DE CITES"><i class="fa fa-caret-square-o-left"></i> SALIR</a>
                        </div>
                    </div>
                </article>
			</div>
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-1">
				</article>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
					<h2 class="alert alert-success"><center>MODIFICACI&Oacute;N POA (OPERACIONES) - <?php echo $this->session->userData('gestion') ?> </center></h2>
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget jarviswidget-color-darken" >
						<header>
							<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
							<h2 class="font-md"><strong>SUBACTIVIDADES</strong></h2>               
						</header>
						<div>
							<div class="widget-body no-padding">
								<div class="table-responsive">
									<?php echo $componentes;?>
								</div>
							</div>
							<!-- end widget content -->
						</div>
						<!-- end widget div -->
					</div>
					<!-- end widget -->
				</article>
			</div>
			<!-- END MAIN CONTENT -->
			</div>
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

		<!-- ============================================ Modal NUEVO COMPONENTE  =============================================== -->
	    <div class="modal animated fadeInDown" id="modal_nuevo_ff" tabindex="-1" role="dialog">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close text-danger " data-dismiss="modal" aria-hidden="true">
	                        &times;
	                    </button>
	                    <h4 class="modal-title text-center text-info">
	                        <b>INGRESAR DATOS DE CITE</b>
	                    </h4>
	                </div>
	                <div class="modal-body no-padding">
	                    <div class="row">
	                        <div id="bootstrap-wizard-1" class="col-sm-12">
	                        	<div class="well">
	                        		<form action="<?php echo site_url().'/modificaciones/cmod_fisica/valida_cite_modificacion'?>"  id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
									   	<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
									   	<input type="hidden" name="com_id" id="com_id">
									   	<fieldset>
											<section>
												<div class="row">
													<label class="label col col-2">CITE</label>
													<div class="col col-10">
														<label class="input"> <i class="icon-append fa fa-user"></i>
															<input type="text" name="cite" id="cite" placeholder="XX-XX-XXX" title="REGISTRE NUMERO DE CITE">
														</label>
													</div>
												</div>
											</section>
											<section>
												<div class="row">
													<label class="label col col-2">FECHA</label>
													<div class="col col-10">
														<label class="input"> <i class="icon-append fa fa-calendar"></i>
														<input type="text" name="fm" id="fm" class="form-control datepicker" data-dateformat="dd/mm/yy" onKeyUp="this.value=formateafecha(this.value);" placeholder="dd/mm/YY" title="SELECCIONE FECHA DE MODIFICACI&Oacute;N DE CITE">
													</label>
													</div>
												</div>
											</section>
										</fieldset>
										
										<footer>
											<button type="button" name="add_form" id="add_form" class="btn btn-primary">Ingresar a Modificar</button>
											<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
										</footer>
										<center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="35" height="35"></center></td>
									</form>	
								</div>
	                        </div>
	                    </div>
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div>

		<!-- END PAGE FOOTER -->
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
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
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
		<script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
		})
		</script>
		<!-- MODIFICAR COMPONENTE -->
        <script type="text/javascript">
        function validarFormatoFecha(campo) {
	      var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	      if ((campo.match(RegExPattern)) && (campo!='')) {
	            return true;
	      } else {
	            return false;
	      }
		}
		function existeFecha(fecha){
	      var fechaf = fecha.split("/");
	      var day = fechaf[0];
	      var month = fechaf[1];
	      var year = fechaf[2];
	      var date = new Date(year,month,'0');
	      if((day-0)>(date.getDate()-0)){
	            return false;
	      }
	      return true;
		}
        $(function () {
			$(".nuevo_ff").on("click", function (e) {
				com_id = $(this).attr('name'); 
		        document.getElementById("com_id").value=com_id;
		    $("#add_form").on("click", function () {
		    	var $validator = $("#form_nuevo").validate({
		                rules: {
		                    cite: { //// Cite
		                        required: true,
		                    },
		                    fm: { //// Fecha de Solicitud
		                        required: true,
		                    }
		                },
		                messages: {
		                    cite: "<font color=red>REGISTRE CITE</font>",
		                    fm: "<font color=red>SELECCIONE FECHA</font>",		                    
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

		        var $valid = $("#form_nuevo").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } else {
		        	
		            proy_id = document.getElementById('proy_id').value;
		            com_id = document.getElementById('com_id').value;
		            cite = document.getElementById('cite').value;
		            fecha = document.getElementById('fm').value;
		            if(validarFormatoFecha(fecha)){
					      if(existeFecha(fecha)){
					            alertify.confirm("DESEA INGRESAR A REALIZAR LA MODIFICACI\u00D3N DE OPERACIONES ?", function (a) {
				                    if (a) {
				                        document.getElementById("load").style.display = 'block';
				                        document.getElementById('add_form').disabled = true;
				                        document.forms['form_nuevo'].submit();
				                    } else {
				                        alertify.error("OPCI\u00D3N CANCELADA");
				                    }
				                });
					      }else{
					            alertify.error("La fecha introducida no existe.");
					      }
					}else{
					      alertify.error("El formato de la fecha es incorrecto.");
					}
		        }
		    });
		    });
	    });
        </script>
		<!-- ============================================================================== -->
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>