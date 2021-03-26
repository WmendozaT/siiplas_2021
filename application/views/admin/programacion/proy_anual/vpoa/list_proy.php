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
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
		<!--estiloh-->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css"> 
		    <!--para las alertas-->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
	    <meta name="viewport" content="width=device-width">
		<!--fin de stiloh-->
          <script>
            function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Proyectos" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }
            function confirmar(){
		        if(confirm('¿Estas seguro de Eliminar el proyecto?'))
		          return true;
		        else
		          return false;
		    }                                             
          </script>
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
              color: #ffffff;
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MEN� PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="#" title="PROGRAMACION"> <span class="menu-item-parent">PROGRAMACI&Oacute;N</span></a>
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
					<li>Programaci&oacute;n POA</li><li>T&eacute;cnico Analista POA</li><li>POA - <?php echo $this->session->userdata('gestion')?></li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
			<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<?php 
			                  if($this->session->flashdata('success')){ ?>
			                    <div class="alert alert-success">
			                      <?php echo $this->session->flashdata('success'); ?>
			                    </div>
			                <?php }
			                    elseif($this->session->flashdata('danger')){ ?>
		                    	<div class="alert alert-danger">
			                      <?php echo $this->session->flashdata('danger'); ?>
			                    </div><?php }
			                ?>

							<div class="well well-sm well-light">
								<h3>RESPONSABLE : <?php echo $resp; ?> -> <small><?php echo $res_dep;?></h3>

								<div id="tabs">
									<ul>
										<li>
											<a href="#tabs-c">OPERACI&Oacute;N DE FUNCIONAMIENTO</a>
										</li>
										<li>
											<a href="#tabs-d">OPERACI&Oacute;N DE FORTALECIMIENTO</a>
										</li>
										<li>
											<a href="#tabs-a">PROYECTOS DE INVERSI&Oacute;N PUBLICA</a>
										</li>
									</ul>
									<div id="tabs-a">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>PROYECTOS DE INVERSI&Oacute;N PUBLICA </strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<table id="dt_basic" class="table table-bordered" style="width:100%;" font-size: "7px";>
															<thead>
																<tr style="height:65px;">
																	<th style="width:3%;" bgcolor="#474544"></th>
																	<th bgcolor="#474544" title="APERTURA PROGRAMATICA">APERTURA PROGRAM&Aacute;TICA <?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544" title="NOMBRE - PROYECTO DE INVERSI&Oacute;N">PROYECTO_DE_INVERSI&Oacute;N</th>
																	<th bgcolor="#474544" title="TIPO DE OPERACI&Oacute;N">TIPO DE OPERACI&Oacute;N</th>
																	<th bgcolor="#474544" title="CODIGO SISIN">C&Oacute;DIGO_SISIN</th>
																	<th bgcolor="#474544" title="RESPONSABLE UNIDAD EJECUTORA">RESPONSABLE (UE)</th>
																	<th bgcolor="#474544">UNIDAD_ADMINISTRATIVA</th>
																	<th bgcolor="#474544">UNIDAD_EJECUTORA</th>
																	<th bgcolor="#474544" title="FASE ACTIVA DEL PROYECTO">FASE_ETAPA</th>
																	<th bgcolor="#474544">NUEVO_CONTINUIDAD</th>
																	<th bgcolor="#474544">ANUAL_PLURIANUAL</th>
																	<th bgcolor="#474544">COSTO TOTAL DEL PROYECTO</th>
																	<th bgcolor="#474544">PRESUPUESTO REQUERIDO&nbsp;<?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544">TECHO ASIGNADO TOTAL</th>
																</tr>
															</thead>
															<tbody>
															<?php echo $proyectos;?>
															</tbody>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-c">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>OPERACI&Oacute;N FUNCIONAMIENTO</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<table id="dt_basic3" class="table1 table-bordered" style="width:100%;" font-size: "7px";>
															<thead>
																<tr style="height:65px;">
																	<th style="width:3%;" bgcolor="#474544"></th>
																	<th bgcolor="#474544">CATEGORIA PROGRAM&Aacute;TICA <?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544">OPERACI&Oacute;N_DE_FUNCIONAMIENTO</th>
																	<th bgcolor="#474544">TIPO DE OPERACI&Oacute;N</th>
																	<th bgcolor="#474544">C&Oacute;DIGO_SISIN</th>
																	<th bgcolor="#474544">RESPONSABLE (UE)</th>
																	<th bgcolor="#474544">UNIDAD_ADMINISTRATIVA</th>
																	<th bgcolor="#474544">UNIDAD_EJECUTORA</th>
																	<th bgcolor="#474544">FASE_ETAPA</th>
																	<th bgcolor="#474544">NUEVO_CONTINUIDAD</th>
																	<th bgcolor="#474544">ANUAL_PLURIANUAL</th>
																	<th bgcolor="#474544">COSTO TOTAL DEL PROYECTO</th>
																	<th bgcolor="#474544">PRESUPUESTO REQUERIDO&nbsp;<?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544">TECHO ASIGNADO TOTAL</th>
																</tr>
															</thead>
															<tbody>
															<?php echo $operacion;?>
															</tbody>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>
									<div id="tabs-d">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>OPERACI&Oacute;N DE FORTALECIMIENTO</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<table id="dt_basic4" class="table1 table-bordered" style="width:100%;" font-size: "7px";>
															<thead>
																<tr style="height:65px;">
																	<th style="width:3%;" bgcolor="#474544"></th>
																	<th bgcolor="#474544">CATEGORIA PROGRAM&Aacute;TICA <?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544">OPERACI&Oacute;N_DE_FORTALECIMIENTO</th>
																	<th bgcolor="#474544">TIPO DE OPERACI&Oacute;N</th>
																	<th bgcolor="#474544">C&Oacute;DIGO_SISIN</th>
																	<th bgcolor="#474544">RESPONSABLE (UE)</th>
																	<th bgcolor="#474544">UNIDAD_ADMINISTRATIVA</th>
																	<th bgcolor="#474544">UNIDAD_EJECUTORA</th>
																	<th bgcolor="#474544">FASE_ETAPA</th>
																	<th bgcolor="#474544">NUEVO_CONTINUIDAD</th>
																	<th bgcolor="#474544">ANUAL_PLURIANUAL</th>
																	<th bgcolor="#474544">COSTO TOTAL DEL PROYECTO</th>
																	<th bgcolor="#474544">PRESUPUESTO REQUERIDO&nbsp;<?php echo $this->session->userdata("gestion");?></th>
																	<th bgcolor="#474544">TECHO ASIGNADO TOTAL</th>
																</tr>
															</thead>
															<tbody>
															<?php echo $fortalecimiento;?>
															</tbody>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>
								</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->

	<!-- ===================================== APROBAR OPERACION ================================================== -->
		<div class="modal fade" id="modal_mod_aper" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							&times;
						</button>
						<h4 class="modal-title" id="myModalLabel">RESPONSABLE <?php echo $resp; ?> DESEA APROBAR LA OPERACI&Oacute;N ?</h4>
					</div>
					<div class="modal-body">
						<form id="mod_formaper" name="mod_formaper" novalidate="novalidate" method="post">
							<input class="form-control" type="hidden" name="id_p" id="id_p">
							<input class="form-control" type="hidden" name="resp" id="resp">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">
							CANCELAR
						</button>
						<button type="submit" name="mod_aperenviar" id="mod_aperenviar" class="btn btn-primary">
							APROBAR OPERACI&Oacute;N
						</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	<!-- ========================================================================================================= -->
	<!-- ================================= OBSERVAR PROYECTO AL TOP =================================================== -->
		<div  class="modal animated fadeInDown" id="modal_mod_aper2" tabindex="-1" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close text-danger" data-dismiss="modal" aria-hidden="true">
						&times;
					</button>
						<center><font size="2"><b>OBSERVAR OPERACI&Oacute;N Y DEVOLVER A RESPONSABLE DE LA UNIDAD EJECUTORA</b></font></center>
				</div>
				<div class="modal-body no-padding">
					<div class="row">
						<form id="mod_formaper2" name="mod_formaper2" novalidate="novalidate" method="post">
							<div id="bootstrap-wizard-1" class="col-sm-12">
								<div class="well">
								<input class="form-control" type="hidden" name="id" id="id">
								<input class="form-control" type="hidden" name="tpo" id="tpo">
									<div class="row">
										<div class="col-sm-3">
											<div class="form-group">
												<img src="<?php echo base_url(); ?>assets/ifinal/responsable.png" WIDTH="70" HEIGHT="70"/>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<LABEL><b style="color: #568a89;"> DE T&Eacute;CNICO ANALISTA POA</b><br>
                                                    <b><font size="3" id="responsable_poa"></font></b></label>
											</div>
										</div>
										<div class="col-sm-3">
											<div class="form-group">
												<img src="<?php echo base_url(); ?>assets/ifinal/archivo1.png" WIDTH="70" HEIGHT="70"/>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<LABEL><b>DESCRIPCI&Oacute;N DE LA OBSERVACI&Oacute;N</b></label>
												<textarea rows="6" class="form-control" name="observacion" id="observacion" style="width:100%;"></textarea> 
											</div>
										</div>
									</div>

								</div> <!-- end well -->
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-md-3 pull-left">
							<button  class="btn btn-danger" data-dismiss="modal">CANCELAR </button>
						</div>
						<div class="col-md-3 pull-right ">
							<button type="submit" name="mod_aperenviar2" id="mod_aperenviar2" class="btn btn-primary"><i class="fa fa-save"></i>
								DEVOLVER
							</button>
						</div>
					</div>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>
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
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
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
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<!-- ======================================= INDICADOR DE DESEMPE�O ======================================= -->
		<script type="text/javascript">
			$(function(){
				//limpiar variable
				var id_aper =''; 
				$(".mod_aper").on("click",function(e){
					//============== ASIGNAR PROYECTO AL VALIDADOR POA ================
					id_proy = $(this).attr('name');
					tp = $(this).attr('id');
					document.getElementById("id_p").value=id_proy;
					document.getElementById("resp").value=tp;
					var url = "<?php echo site_url("admin")?>/proy/get_resp";
					var codigo ='';
					var request;
					if(request){
						request.abort();
					}
					request = $.ajax({
						url:url,
						type:"POST",
						dataType:'json',
						data: "id_p="+id_proy+"&tp="+tp
					});

					request.done(function(response,textStatus,jqXHR){
						$('#responsable').html(response.responsable);
						//document.mod_formaper.modunidad_o.selectedIndex=response.uni_id;
					});
					request.fail(function(jqXHR,textStatus,thrown){
						console.log("ERROR: "+ textStatus);
					});
					request.always(function(){
						//console.log("termino la ejecuicion de ajax");
					});
					e.preventDefault();
					// =============================VALIDAR EL FORMULARIO DE MODIFICACION
					$("#mod_aperenviar").on("click",function(e){
						var $valid = $("#mod_formaper").valid();
						if (!$valid) {
							$validator.focusInvalid();
						} else {
							//==========================================================
							//var aper_programa = document.getElementById("modaper_programa").value
							var id_p = document.getElementById("id_p").value 
							var tp = document.getElementById("resp").value 
							var url = "<?php echo site_url("admin")?>/proy/asig_proy";
							$.ajax({
								type:"post",
								url:url,
								data:{id_p:id_p,tp:tp},
								success:function(data){
									window.location.reload(true);
								}
							});
						}
					});
				});
			});
		</script>
		<!-- ====================================================================================================== -->
		<!-- ======================================= PROYECTO OBSERVADO ======================================= -->
		<script type="text/javascript">
			$(function(){
				//limpiar variable
				var id_aper ='';
				$(".mod_aper2").on("click",function(e){

					
					tp = $(this).attr('id');
					id_p = $(this).attr('name');
					document.getElementById("id").value=id_p; 
					document.getElementById("tpo").value=tp;
					var url = "<?php echo site_url("admin")?>/proy/get_resp";
					var codigo ='';
					var request;
					if(request){
						request.abort();
					}
					request = $.ajax({
						url:url,
						type:"POST",
						dataType:'json',
						data: "id_p="+id_p+"&tp="+tp
					});

					request.done(function(response,textStatus,jqXHR){
						$('#responsable_poa').html(response.responsable);
						//document.mod_formaper.modunidad_o.selectedIndex=response.uni_id;
					});
					request.fail(function(jqXHR,textStatus,thrown){
						console.log("ERROR: "+ textStatus);
					});
					request.always(function(){
						//console.log("termino la ejecuicion de ajax");
					});
					e.preventDefault();
					// =============================VALIDAR EL FORMULARIO DE MODIFICACION
					$("#mod_aperenviar2").on("click",function(e){
						var $validator = $("#mod_formaper2").validate({
							rules: {
								
								observacion: {
									required: true,
								}
							},

							messages: {
								observacion: {required:"Describa la Observacion"},
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
						var $valid = $("#mod_formaper2").valid();
						if (!$valid) {
							$validator.focusInvalid();
						} else {
							//==========================================================
							//var aper_programa = document.getElementById("modaper_programa").value

							var id_p = document.getElementById("id").value
							var tp = document.getElementById("tpo").value
							var observacion = document.getElementById("observacion").value
							var url = "<?php echo site_url("admin")?>/proy/add_obs";
							$.ajax({
								type:"post",
								url:url,
								data:{id:id_p,observacion:observacion,tp:tp},
								success:function(data){
									window.location.reload(true);
								}
							});
						}
					});
				});
			});
		</script>
		<!-- ====================================================================================================== -->
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
			// menu
			$("#menu").menu();
			$('.ui-dialog :button').blur();
			$('#tabs').tabs();
		})
		</script>
	</body>
</html>
