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
		<script type="text/javascript">
			function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Proyectos" , "width=800,height=650,scrollbars=SI") ;                                                               
            }
		</script>
		<!--para las alertas-->
    	<meta name="viewport" content="width=device-width">
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="<?php echo base_url().'index.php/admin/dm/2/' ?>" title="MODIFICACIONES"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
					<li>Modificaciones</li><li>Modificaciones del POA</li><li>Mis Modificaciones</li>
				</ol>
			</div>

			<!-- MAIN CONTENT -->
			<div class="container">
				<div id="content"><br>
				<div class="row">
					<div class="col-sm-12">
						<div class="well well-sm">
							<h3 class="text-primary">REPORTE - MODIFICACIONES POA</h3>
							<table class="table table-bordered">
								<tbody>
									<!-- new tr -->
									<tr>
										<td style="width:70%;">
										<p style="font-size: 14px; font-family: Arial;">
											Muestra el listado de operaciones y requerimientos modificados mensual por cada regional.
										</p>
										</td>

										<td style="width:30%;">
											<a href="#" style="width:100%;" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal"> SELECCIONAR REGIONAL</a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="well well-sm">
							<h3 class="text-primary">REPORTE - CONSOLIDADO DE MODIFICACIONES POA - <?php echo $this->session->userdata('gestion')?></h3>
							<table class="table table-bordered">
								<tbody>
									<!-- new tr -->
									<tr>
										<td style="width:70%;">
										<p style="font-size: 14px; font-family: Arial;">
											Muestra el consolidado de numero de operaciones y requerimientos que fueron modificados mensualmente por cada regional.
										</p>
										</td>

										<td style="width:30%;">
											<a href="javascript:abreVentana('<?php echo base_url().'index.php/mod/rep_consolidado'?>');"   style="width:100%;" class="btn btn-primary"> GENERAR REPORTE </a>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		
		<!-- MODAL REGIONAL -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLabel">BUSQUEDA REPORTE MODIFICACIONES : <?php echo $this->session->userdata('gestion')?></h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <form action="<?php echo site_url().'/mod/valida_busqueda'?>" id="form_mod" name="form_mod" class="form-horizontal" method="post">
		          <div class="form-group">
		          	<label for="recipient-name" class="col-form-label">REGIONAL</label>
		          	<select class="form-control" id="dep_id" name="dep_id" title="Seleccione Regional">
			            <option value="">Seleccione Regional</option>
			            <?php 
							foreach($regionales as $row){ 
								if($row['dep_estado']==1){
									if($this->session->userdata('dep_id')==$row['dep_id']){ ?>
										<option value="<?php echo $row['dep_id']; ?>" selected><?php echo $row['dep_departamento']; ?></option>
										<?php
									}
									else{
										?>
										<option value="<?php echo $row['dep_id']; ?>"><?php echo $row['dep_departamento']; ?></option>
										<?php
									} 
								}
							}
						?>        
			        </select>
		          </div>

		          	<?php 
		          	if($this->session->userData('tp_adm')==1){ ?>
		          		<div id="mes" style="display:none;">
		          		<?php
		          	}
		          	else{ ?>
		          		<div id="mes">
		          		<?php
		          	}
		          	?>
		          	<div class="form-group">
			            <label for="recipient-name" class="col-form-label">MES</label>
			          	<select class="form-control" id="mes_id" name="mes_id" title="Seleccione Mes">
				            <option value="">Seleccione Mes</option>
				            <option value="0">TODOS LOS MESES</option>
				            <?php 
								foreach($meses as $row){ 
									if($this->session->userdata('mes')==$row['m_id']){ ?>
										<option value="<?php echo $row['m_id']; ?>" selected><?php echo $row['m_descripcion']; ?></option>
										<?php
									}
									else{ ?>
										<option value="<?php echo $row['m_id']; ?>"><?php echo $row['m_descripcion']; ?></option>
										<?php
									}
								}
							?>        
				        </select>
			        </div>
		          </div>
		        </form>
		      </div>
		      <div class="modal-footer">
		      	<div id="send_not">
		        	<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload()">Cerrar</button>
		        </div>
		        <div id="send_ok" style="display:none;">
		        	<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="location.reload()">Cerrar</button>
		        	<button type="button" name="subir_mod" id="subir_mod" class="btn btn-primary" title="Generar Busqueda de Modificaciones">Generar Busqueda</button>
		        	<center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<!-- END MODAL REGIONAL -->
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
		<script type="text/javascript">
			$("#dep_id").change(function () {
		        $("#dep_id option:selected").each(function () {
		            elegido = $(this).val();
		            if(elegido == '' ){
		            	$('#mes').slideUp();
		            	$('#send_ok').slideUp();
		            	$('#send_not').slideDown();
		            }
		            else{
		            	$('#mes').slideDown();
		            }
		        });
		    });
		    $("#mes_id").change(function () {
		        $("#mes_id option:selected").each(function () {
		            elegido = $(this).val();
		            if(elegido == '' ){
		            	$('#send_ok').slideUp();
		            	$('#send_not').slideDown();
		            }
		            else{
		            	$('#send_ok').slideDown();
		            	$('#send_not').slideUp();
		            }
		        });
		    });
		</script>
		<!-- IMPORTANT: APP CONFIG -->
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
		<!-- Demo purpose only -->
		<script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
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
		    //NACIONAL
		    $("#subir_mod_nal").on("click", function () {
		    	var $validator = $("#form_mod_nacional").validate({
		                rules: {
		                    fi: { //// Fecha Inicial
		                        required: true,
		                    },
		                    ff: { //// Fecha Final
		                        required: true,
		                    }
		                },
		                messages: {
		                    fi: "<font color=red>SELECIONE FECHA INICIAL</font>",
		                    ff: "<font color=red>SELECCIONE FECHA FINAL</font>",		                    
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

		        var $valid = $("#form_mod_nacional").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } else {
		        	fecha_ini = document.getElementById('from').value; /// Fecha Inicial
		        	fecha_fin = document.getElementById('to').value; /// Fecha Final

		        	if(validarFormatoFecha(fecha_ini) & validarFormatoFecha(fecha_fin)){
		        		if(existeFecha(fecha_ini) & existeFecha(fecha_fin)){
		        			alertify.confirm("GENERAR BUSQUEDA DE REPORTES DE MODIFICACIONES?", function (a) {
			                    if (a) {
			                        document.getElementById("load1").style.display = 'block';
			                        document.getElementById('subir_mod_nal').disabled = true;
			                        document.forms['form_mod_nacional'].submit();
			                    } else {
			                        alertify.error("OPCI\u00D3N CANCELADA");
			                    }
			                });
		        		}
		        		else {
	                        alertify.error("ERROR EN LAS FECHAS SELECCIONADAS");
	                	}
		        	}
		        	else {
	                        alertify.error("ERROR EN LAS FECHAS SELECCIONADAS");
	                }
		        }
		    });
	    });

		$(function () {
		    //REGIONAL
		    $("#subir_mod").on("click", function () {
		        var $valid = $("#form_mod").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } else {
		        	
		        	alertify.confirm("GENERAR BUSQUEDA DE REPORTES DE MODIFICACIONES?", function (a) {
	                    if (a) {
	                        document.getElementById("load").style.display = 'block';
	                        document.getElementById('subir_mod').disabled = true;
	                        document.forms['form_mod'].submit();
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
				 // Date Range Picker
				$("#from").datepicker({
				    defaultDate: "+1w",
				    changeMonth: true,
				    numberOfMonths: 3,
				    prevText: '<i class="fa fa-chevron-left"></i>',
				    nextText: '<i class="fa fa-chevron-right"></i>',
				    onClose: function (selectedDate) {
				        $("#to").datepicker("option", "maxDate", selectedDate);
				    }
			
				});
				$("#to").datepicker({
				    defaultDate: "+1w",
				    changeMonth: true,
				    numberOfMonths: 3,
				    prevText: '<i class="fa fa-chevron-left"></i>',
				    nextText: '<i class="fa fa-chevron-right"></i>',
				    onClose: function (selectedDate) {
				        $("#from").datepicker("option", "minDate", selectedDate);
				    }
				});
			})
		</script>
	</body>
</html>
