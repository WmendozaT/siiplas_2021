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
		<meta name="viewport" content="width=device-width">
		<script>
            function abreVentana_obj(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Objetivos Estrategicos" , "width=1000,height=650,scrollbars=SI") ;                                                                   
            }                                                 
        </script>
		<style>
			table{font-size: 9.5px;
            width: 100%;
            max-width:1550px;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9.5px;
            }
            #mdialTamanio{
              width: 80% !important;
            }
		</style>
	</head>
	<body class="">
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
					<li>Mis Operaciones</li><li>T&eacute;cnico de Unidad Ejecutora</li>
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
	                              <h1><small>OBJETIVOS ESTRAT&Eacute;GICOS <?php echo $configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'];?></small></h1>
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
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/me/reporte_objetivos_estrategicos/1';?>');">IMPRIMIR PEI</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/me/reporte_objetivos_estrategicos/2';?>');">IMPRIMIR PEI-POA</a></li>
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
	                    <?php echo $obj_estrategicos;?>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

    <!-- =========================== ADICIONA RESULTADO FINAL ================================ -->
        <div class="modal fade" id="modal_add_rf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <form action="<?php echo site_url().'/mestrategico/cobjetivos_estrategico/valida_add_rfinal'?>" id="form_res" name="form_res" class="smart-form" method="post">
               		<input type="hidden" name="obj_id" id="obj_id">
                    <h2 class="alert alert-info"><center>AGREGAR RESULTADO FINAL </center></h2>
                    <header><div id="oe"></div></header>
						<fieldset>					
							<div class="row">
								<section class="col col-4">
									<label class="label">C&Oacute;DIGO</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="cod" id="cod">
									</label>
								</section>
							</div>
							
							<section>
								<label class="label">RESULTADO FINAL</label>
								<label class="textarea">
									<i class="icon-append fa fa-tag"></i>
									<textarea rows="3" name="resultado" id="resultado"></textarea>
								</label>
							</section>
							
							<section>
								<label class="label">INDICADOR DE IMPACTO</label>
								<label class="textarea">
									<i class="icon-append fa fa-tag"></i>
									<textarea rows="3" name="indicador" id="indicador"></textarea>
								</label>
							</section>

							<div class="row">
								<section class="col col-6">
									<label class="label">META</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="meta" id="meta" value="0">
									</label>
								</section>
								<section class="col col-6">
									<label class="label">TIPO DE INDICADOR</label>
									<label class="input">
										<select class="form-control" id="tipo_i" name="tipo_i">
                                            <option value="">Seleccione Indicador</option>
                                            <?php 
							                    foreach($indi as $row){ ?>
									                <option value="<?php echo $row['indi_id']; ?>"><?php echo $row['indi_descripcion']; ?></option>
									                <?php 	
							                    }
							                ?>
                                      	</select>
									</label>
								</section>
							</div>
						</fieldset>

	                    <fieldset>
	                    	<section>
								<label class="label">TEMPORALIDAD</label>
								<label class="textarea">
									<table class="table table-bordered">
										<thead>
										<tr align="center">
										<?php
											for ($i=$configuracion[0]['conf_gestion_desde']; $i <=$configuracion[0]['conf_gestion_hasta'] ; $i++) { ?>
												<td style="width:10%;"><?php echo $i; ?></td>
												<?php
											}
										?>
										</tr>
										</thead>
										<tbody>
										<tr align="center">
										<?php 
											for ($i=$configuracion[0]['conf_gestion_desde']; $i <=$configuracion[0]['conf_gestion_hasta'] ; $i++) {?>
												<td style="width:10%;"><input type="text" name="<?php echo $i;?>" id="<?php echo $i;?>" class="form-control" value="0" onkeypress="return justNumbers(event);" onpaste="return false" onkeyup="sum_temp();"></td>
												<?php
											}
										?>
										</tbody>
										</tr>
	                                </table>
								</label>
								<input type="hidden" name="tot" id="tot" value="0">
							</section>
						</fieldset>
						<footer>
							<div id="but" style="display: none;">
								<button type="button" name="add_res" id="add_res" class="btn btn-info">REGISTRAR</button>
								<button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
							</div>
						</footer>
                </form>
            </div>
          </div>
        </div>
    </div>

	<!-- =========================== MODIFICA RESULTADO FINAL ================================ -->
        <div class="modal fade" id="modal_mod_rf" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <form action="<?php echo site_url().'/mestrategico/cobjetivos_estrategico/valida_update_rfinal'?>" id="mform_res" name="mform_res" class="smart-form" method="post">
               		<input type="hidden" name="obj_id" id="obj">
               		<input type="hidden" name="rf_id" id="rf">
                    <h2 class="alert alert-info"><center>MODIFICAR RESULTADO FINAL </center></h2>
                    <header><div id="moe"></div></header>
						<fieldset>					
							<div class="row">
								<section class="col col-4">
									<label class="label">C&Oacute;DIGO</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="mcod" id="mcod">
									</label>
								</section>
							</div>
							
							<section>
								<label class="label">RESULTADO FINAL</label>
								<label class="textarea">
									<i class="icon-append fa fa-tag"></i>
									<textarea rows="3" name="mresultado" id="mresultado"></textarea>
								</label>
							</section>
							
							<section>
								<label class="label">INDICADOR DE IMPACTO</label>
								<label class="textarea">
									<i class="icon-append fa fa-tag"></i>
									<textarea rows="3" name="mindicador" id="mindicador"></textarea>
								</label>
							</section>

							<div class="row">
								<section class="col col-6">
									<label class="label">META</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="mmeta" id="mmeta" value="0">
									</label>
								</section>
								<section class="col col-6">
									<label class="label">TIPO DE INDICADOR</label>
									<label class="input">
										<select class="form-control" id="mtipo_i" name="mtipo_i">
                                            <option value="">Seleccione Indicador</option>
                                            <?php 
							                    foreach($indi as $row){ ?>
									                <option value="<?php echo $row['indi_id']; ?>"><?php echo $row['indi_descripcion']; ?></option>
									                <?php 	
							                    }
							                ?>
                                      	</select>
									</label>
								</section>
							</div>
						</fieldset>

	                    <fieldset>
	                    	<section>
								<label class="label">TEMPORALIDAD</label>
								<label class="textarea">
									<table class="table table-bordered">
										<thead>
										<tr align="center">
										<?php
											for ($i=$configuracion[0]['conf_gestion_desde']; $i <=$configuracion[0]['conf_gestion_hasta'] ; $i++) { ?>
												<td style="width:10%;"><?php echo $i; ?></td>
												<?php
											}
										?>
										</tr>
										</thead>
										<tbody>
										<tr align="center">
										<?php $p=0;
											for ($i=$configuracion[0]['conf_gestion_desde']; $i <=$configuracion[0]['conf_gestion_hasta'] ; $i++) { $p++;?>
												<td style="width:10%;"><input type="text" name="<?php echo $i;?>" id="m<?php echo $p;?>" class="form-control" value="0" onkeyup="sum_mtemp();" onkeypress="return justNumbers(event);" onpaste="return false" ></td>
												<?php
											}
										?>
										</tbody>
										</tr>
	                                </table>
								</label>
								<input type="hidden" name="mtotal" id="mtotal" value="0">
							</section>
						</fieldset>
						<footer>
							<div id="mbut">
								<button type="button" name="mod_res" id="mod_res" class="btn btn-info">MODIFICAR</button>
								<button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
							</div>
						</footer>
                </form>
            </div>
          </div>
        </div>
    </div>
    <!-- -------- MODIFICAR OBJETIVO ESTRATEGICO ---------- -->
	<div class="modal animated fadeInDown" id="modal_mod_ff" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body no-padding">
                    <div class="row">
                    	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       	<div class="row">
							<h2 class="row-seperator-header">MODIFICAR REGISTRO - OBJETIVO ESTRATEGICO</h2>
								<div class="row">
									<!-- col -->
									<div class="col-sm-12">
										<!-- row -->
										<div class="row">
											<form action="<?php echo site_url() . '/me/update_objetivo_estrategico'?>" id="form_mod" name="form_mod" class="smart-form" method="post">
												<input type="hidden" name="obj_id" id="obj_id">
												<fieldset>
													<section>
														<label class="input"> <i class="icon-append fa fa-user"></i>
															<input type="text" name="codigo" id="codigo" placeholder="C&Oacute;DIGO">
															<b class="tooltip tooltip-bottom-right">C&Oacute;DIGO</b> </label>
													</section>

													<section>
														<label class="textarea"> <i class="icon-append fa fa-comment"></i>
															<textarea rows="4" name="descripcion" id="descripcion" placeholder="DESCRIPCION - OBJETIVO ESTRATEGICO"></textarea> 
															<b class="tooltip tooltip-bottom-right">DESCRIPCI&Oacute;N</b></label>
													</section>
												</fieldset>
												<footer>
													<div class="col-md-3 pull-left">
							                            <button class="btn btn-ms btn-danger" data-dismiss="modal">CANCELAR</button>
							                        </div>
							                        <div class="col-md-3 pull-right ">
							                            <button type="button" name="mod_ffenviar" id="mod_ffenviar" class="btn btn-success" style="width:100%;">MODIFICAR</button>
				                        				<center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
							                        </div>
												</footer>
											</form>
										</div>
									</div>
								</div>
							</div>
						</article>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>	<!-- PAGE FOOTER -->

    <!-- ------- NUEVO OBJETIVO ESTRATEGICO -------- -->
	<div class="modal animated fadeInDown" id="modal_nuevo_ff" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body no-padding">
                    <div class="row">
                       <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h2 class="row-seperator-header">FORMULARIO DE REGISTRO - OBJETIVO ESTRATEGICO</h2>
								<div class="row">
									<!-- col -->
									<div class="col-sm-12">
										<!-- row -->
										<div class="row">
											<form action="<?php echo site_url().'/me/valida_objetivo_estrategico'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
											   	<input type="hidden" name="gi" id="gi" value="<?php echo $configuracion[0]['conf_gestion_desde'];?>">
											   	<input type="hidden" name="gf" id="gf" value="<?php echo $configuracion[0]['conf_gestion_hasta'];?>">
											   	<fieldset>
													<section>
														<label class="input"> <i class="icon-append fa fa-user"></i>
															<input type="text" name="codigo" id="codigo" placeholder="C&Oacute;DIGO">
															<b class="tooltip tooltip-bottom-right">C&Oacute;DIGO</b> </label>
													</section>

													<section>
														<label class="textarea"> <i class="icon-append fa fa-comment"></i>
															<textarea rows="4" name="descripcion" id="descripcion" placeholder="DESCRIPCION - OBJETIVO ESTRATEGICO"></textarea> 
															<b class="tooltip tooltip-bottom-right">DESCRIPCI&Oacute;N</b></label>
													</section>
												</fieldset>
												<footer>
													<div class="col-md-3 pull-left">
							                            <button class="btn btn-ms btn-danger" data-dismiss="modal">CANCELAR</button>
							                        </div>
							                        <div class="col-md-3 pull-right ">
							                            <button type="button" name="subir_form" id="subir_form" class="btn btn-success" style="width:100%;">GUARDAR</button>
				                        				<center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
							                        </div>
												</footer>
											</form>	
										</div>
										<!-- end row -->
									</div>
									<!-- end col -->
								</div>
                        </article>
                    </div>   
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		function justNumbers(e){
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
            return /\d/.test(String.fromCharCode(keynum));
        }

        function sum_temp(){
        	var sum=0;
        	for (var i = <?php echo $configuracion[0]['conf_gestion_desde'];?>; i <=<?php echo $configuracion[0]['conf_gestion_hasta'];?>; i++) {
        		sum=sum+parseFloat($('[id="'+i+'"]').val());
        	}
        	$('[name="tot"]').val((sum).toFixed(2));
        	total = parseFloat($('[name="tot"]').val());
        	if(isNaN(total) || total==0){
        		$('#but').slideUp();

        	}
        	else{
        		$('#but').slideDown();
        	}
        }
        function sum_mtemp(){
        	var suma=0;
        	for (var i = 1; i <=5; i++) {
        		suma=suma+parseFloat($('[id="m'+i+'"]').val());
        	}

        	$('[id="mtotal"]').val((suma).toFixed(2));
        	total = parseFloat($('[name="mtotal"]').val());
        	if(isNaN(total) || total==0){
        		$('#mbut').slideUp();

        	}
        	else{
        		$('#mbut').slideDown();
        	}
        }
		</script>
		<!-- Modificar Resultado Final -->
		<script type="text/javascript">
		    $(function () {
		        $(".mod_rf").on("click", function (e) {
		            obj_id = $(this).attr('name'); 
		            rf_id = $(this).attr('id'); 
		            document.getElementById("obj").value=obj_id;
		            document.getElementById("rf").value=rf_id;
		            var url = "<?php echo site_url("")?>/me/get_resultado_final";
		            var request;
		            if (request) {
		                request.abort();
		            }
		            request = $.ajax({
		                url: url,
		                type: "POST",
		                dataType: 'json',
		                data: "rf_id=" + rf_id
		            });

		            request.done(function (response, textStatus, jqXHR) {
		            	$('#moe').html('<font size=2><b>OE : </b>'+response.resultado[0]['obj_codigo']+'.- '+response.resultado[0]['obj_descripcion']+'</font>');
		            	document.getElementById("mcod").value = response.resultado[0]['rf_cod'];
		            	document.getElementById("mresultado").value = response.resultado[0]['rf_resultado'];
		            	document.getElementById("mindicador").value = response.resultado[0]['rf_indicador'];
		            	document.getElementById("mmeta").value = response.resultado[0]['rf_meta'];
		            	document.getElementById("mtipo_i").value = response.resultado[0]['indi_id'];
		            	document.getElementById("mtotal").value = response.resultado[0]['programado_total'];
		            	for (var i = 1; i <=5; i++) {
		            		document.getElementById("m"+i+"").value = response.resultado[0]['mes'+i+''];
		            	}
		            });
		            request.fail(function (jqXHR, textStatus, thrown) {
		                console.log("ERROR: " + textStatus);
		            });
		            request.always(function () {
		                //console.log("termino la ejecuicion de ajax");
		            });
		            e.preventDefault();
		            // ====VALIDAR EL FORMULARIO DE MODIFICACION
		            $("#mod_res").on("click", function (e) {
		                var $validator = $("#mform_res").validate({
			                   rules: {
			                	mcod: { //// Codigo
			                        required: true,
			                    },
			                    mresultado: { //// Resultado
			                        required: true,
			                    },
			                    mindicador: { //// Indicador
			                        required: true,
			                    },
			                    mmeta: { //// Meta
			                        required: true,
			                    },
			                    mtipo_i: { //// tip Indicador
			                        required: true,
			                    }
			                },
			                messages: {
			                    mcod: "<font color=red>POR FAVOR REGISTRE C&Oacute;DIGO</font>",
			                    mresultado: "<font color=red>POR FAVOR REGISTRE RESULTADO</font>",
			                    mindicador: "<font color=red>POR FAVOR REGISTRE INDICADOR</font>",
			                    mmeta: "<font color=red>POR FAVOR REGISTRE META</font>",	                    
			                    mtipo_i: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>",	                    
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
		                var $valid = $("#mform_res").valid();
		                if (!$valid) {
		                    $validator.focusInvalid();
		                } else {
		                    alertify.confirm("DESEA MODIFICAR RESULTADO FINAL?", function (a) {
			                    if (a) {
			                        document.getElementById('mod_res').disabled = true;
			                        document.forms['mform_res'].submit(); /// id del formulario
			                    } else {
			                        alertify.error("OPCI\u00D3N CANCELADA");
			                    }
		                	});
		                }
		            });
		        });
		    });
		</script>
		<!-- Adiciona Resultado Final -->
		<script type="text/javascript">
		    $(function () {
		        $(".add_rf").on("click", function (e) {
		            obj_id = $(this).attr('name'); 
		            document.getElementById("obj_id").value=obj_id;
		            var url = "<?php echo site_url("")?>/me/get_objetivo_estrategico";
		            var request;
		            if (request) {
		                request.abort();
		            }
		            request = $.ajax({
		                url: url,
		                type: "POST",
		                dataType: 'json',
		                data: "obj_id=" + obj_id
		            });

		            request.done(function (response, textStatus, jqXHR) {
		            	$('#oe').html('<font size=2><b>OE : </b>'+response.codigo+'.- '+response.descripcion+'</font>');
		            	document.getElementById("cod").value = response.codigo+'.';
		            });
		            request.fail(function (jqXHR, textStatus, thrown) {
		                console.log("ERROR: " + textStatus);
		            });
		            request.always(function () {
		                //console.log("termino la ejecuicion de ajax");
		            });
		            e.preventDefault();
		            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
		            $("#add_res").on("click", function (e) {
		                var $validator = $("#form_res").validate({
			                   rules: {
			                	cod: { //// Codigo
			                        required: true,
			                    },
			                    resultado: { //// Resultado
			                        required: true,
			                    },
			                    indicador: { //// Indicador
			                        required: true,
			                    },
			                    meta: { //// Meta
			                        required: true,
			                    },
			                    tipo_i: { //// tip Indicador
			                        required: true,
			                    }
			                },
			                messages: {
			                    cod: "<font color=red>POR FAVOR REGISTRE C&Oacute;DIGO</font>",
			                    resultado: "<font color=red>POR FAVOR REGISTRE RESULTADO</font>",
			                    indicador: "<font color=red>POR FAVOR REGISTRE INDICADOR</font>",
			                    meta: "<font color=red>POR FAVOR REGISTRE META</font>",	                    
			                    tipo_i: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>",	                    
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
		                var $valid = $("#form_res").valid();
		                if (!$valid) {
		                    $validator.focusInvalid();
		                } else {
		                    alertify.confirm("DESEA REGISTRAR RESULTADO FINAL?", function (a) {
			                    if (a) {
			                        document.getElementById('add_res').disabled = true;
			                        document.forms['form_res'].submit(); /// id del formulario
			                    } else {
			                        alertify.error("OPCI\u00D3N CANCELADA");
			                    }
		                	});
		                }
		            });
		        });
		    });
		</script>

		<!-- Modificar Objetico Estrategico -->
		<script type="text/javascript">
		    $(function () {
		        $(".mod_ff").on("click", function (e) {
		            obj_id = $(this).attr('name'); 
		            document.getElementById("obj_id").value=obj_id;
		            var url = "<?php echo site_url("")?>/me/get_objetivo_estrategico";
		            var request;
		            if (request) {
		                request.abort();
		            }
		            request = $.ajax({
		                url: url,
		                type: "POST",
		                dataType: 'json',
		                data: "obj_id=" + obj_id
		            });

		            request.done(function (response, textStatus, jqXHR) {
		                document.getElementById("codigo").value = response.codigo;
		                document.getElementById("descripcion").value = response.descripcion;
		            });
		            request.fail(function (jqXHR, textStatus, thrown) {
		                console.log("ERROR: " + textStatus);
		            });
		            request.always(function () {
		                //console.log("termino la ejecuicion de ajax");
		            });
		            e.preventDefault();
		            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
		            $("#mod_ffenviar").on("click", function (e) {
		                var $validator = $("#form_mod").validate({
			                   rules: {
			                	codigo: { //// Codigo
			                        required: true,
			                    },
			                    descripcion: { //// Descripcion
			                        required: true,
			                    }
			                },
			                messages: {
			                    desc1: "<font color=red>POR FAVOR REGISTRE C&Oacute;DIGO</font>",
			                    desc2: "<font color=red>POR FAVOR REGISTRE DESCRIPCI&Oacute;N</font>",	                    
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
		                var $valid = $("#form_mod").valid();
		                if (!$valid) {
		                    $validator.focusInvalid();
		                } else {
		                	var obj_id = document.getElementById("obj_id").value;
		                    var codigo = document.getElementById("codigo").value;
		                    var descripcion = document.getElementById("descripcion").value;
		         
		                    alertify.confirm("MODIFICAR OBJETIVO ESTRATEGICO ?", function (a) {
			                    if (a) {
			                        document.getElementById("load").style.display = 'block';
			                        document.getElementById('mod_ffenviar').disabled = true;
			                        document.forms['form_mod'].submit(); /// id del formulario
			                    } else {
			                        alertify.error("OPCI\u00D3N CANCELADA");
			                    }
		                	});
		                }
		            });
		        });
		    });
		</script>

		<!-- Nuevo Registro Objetivo Estrategico -->
		<script type="text/javascript">
		$(function () {
		    $("#subir_form").on("click", function () {
		    	var $validator = $("#form_nuevo").validate({
		                rules: {
		                	codigo: { //// codigo
		                        required: true,
		                    },
		                    descripcion: { //// descripcion
		                        required: true,
		                    }
		                },
		                messages: {
		                    codigo: "<font color=red>REGISTRE C&Oacute;DIGO</font>",
		                    descripcion: "<font color=red>REGISTRE DESCRIPCI&Oacute;N</font>",		                    
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

		            codigo = document.getElementById('codigo').value;
		            descripcion = document.getElementById('descripcion').value;
		            gi = document.getElementById('gi').value;
		            gf = document.getElementById('gf').value;

		                alertify.confirm("GUARDAR OBJETIVO ESTRATEGICO "+gi+" - "+gf+" ?", function (a) {
		                    if (a) {
		                        document.getElementById("load").style.display = 'block';
		                        document.getElementById('subir_form').disabled = true;
		                        document.forms['form_nuevo'].submit();
		                    } else {
		                        alertify.error("OPCI\u00D3N CANCELADA");
		                    }
		                });
		           
		        }
		    });
	    });
		</script>
		<!-- Elimina Objetivo Estrategico -->
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
		            alertify.confirm("DESEA ELIMINAR OBJETIVO ESTRATEGICO ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/me/delete_objetivo_estrategico";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "obj_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("EL OBJETIVO ESTRATEGICO SE ELIMINO CORRECTAMENTE ", function (e) {
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
