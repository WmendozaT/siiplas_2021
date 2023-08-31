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
					<li>Marco Estrategico</li><li>Objetivos Estrategicos</li><li>Acciones Estrat&eacute;gicas</li>
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
	                            	<h1><small>OBJETIVO ESTRAT&Eacute;GICO : </small> <?php echo $obj_estrategico[0]['obj_codigo'].'.- '.$obj_estrategico[0]['obj_descripcion'];?></h1>
	                              	<h1><small>ACCIONES ESTRAT&Eacute;GICAS :</small> <?php echo $configuracion[0]['conf_gestion_desde'].' - '.$configuracion[0]['conf_gestion_hasta'];?></h1>
	                            </div>
	                        </section>
	                    </article>
	                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
	                        <section id="widget-grid" class="well">
	                          <a href="<?php echo base_url().'index.php/me/objetivos_estrategicos' ?>" class="btn btn-success" title="Volver a Objetivos Estrategicos" style="width:100%;">ATRAS</a> 
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
	                    <?php echo $acciones_estrategicas;?>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

	<div class="modal animated fadeInDown" id="modal_mod_ff" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body no-padding">
                    <div class="row">
                    	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                       	<div class="row">
							<h2 class="row-seperator-header">MODIFICAR REGISTRO - ACCIONES ESTRATEGICAS</h2>
								<div class="row">
									<!-- col -->
									<div class="col-sm-12">
										<!-- row -->
										<div class="row">
											<form action="<?php echo site_url().'/me/update_acciones_estrategicas'?>" id="form_mod" name="form_mod" class="smart-form" method="post">
												<input type="hidden" name="acc_id" id="acc_id">
												<input type="hidden" name="obj_id" id="obj_id" value="<?php echo $obj_estrategico[0]['obj_id'];?>">
												<fieldset>
													<section>
														RESULTADO FINAL
														<select class="form-control" id="rf_id" name="rf_id">
				                                            <option value="">Seleccione Resultado</option>
				                                            <?php 
											                    foreach($resultado_final as $row){ ?>
													                <option value="<?php echo $row['rf_id']; ?>"><?php echo $row['rf_cod'].'.- '.$row['rf_resultado']; ?></option>
													                <?php 	
											                    }
											                ?>
				                                      	</select>
													</section>

													<section>
														<label class="input"> <i class="icon-append fa fa-user"></i>
															<input type="text" name="codigo" id="codigo" placeholder="C&Oacute;DIGO" onkeypress="if (this.value.length < 2) { return soloNumeros(event);}else{return false; }" onpaste="return false" required="true">
															<b class="tooltip tooltip-bottom-right">C&Oacute;DIGO</b> </label>
													</section>

													<section>
														<label class="textarea"> <i class="icon-append fa fa-comment"></i>
															<textarea rows="4" name="descripcion" id="descripcion" placeholder="DESCRIPCION - ACCI&acute;N ESTRATEGICA"></textarea> 
															<b class="tooltip tooltip-bottom-right">DESCRIPCI&Oacute;N</b></label>
													</section>
													<div class="alert alert-info" role="alert">
													  	VINCULACI&Oacute;N AL PEDES
													</div>

													<section>
														<font color="blue">PILAR</font>
														<select class="form-control" id="pdes1" name="pdes1">
		                                                    <option value="">Seleccione PILAR</option> 
		                                                     <?php
																$consulta1 = 'SELECT * FROM "public"."pdes" WHERE pdes_jerarquia=\'1\' AND pdes_depende=\'0\' AND pdes_estado!=\'0\' ORDER BY pdes_id ';
																  $consulta1=$this->db->query($consulta1);
																  $lista_pedes=$consulta1->result_array();
																  foreach ($lista_pedes as $pedes){ ?>
																  <option value="<?php echo $pedes['pdes_codigo']?>" <?php if(@$_POST['pais']==$pedes['pdes_id']){ echo "selected";} ?> >
																  <?php echo $pedes['pdes_codigo'].' - '.$pedes['pdes_nivel'].' - '.$pedes['pdes_descripcion']?></option> 
																<?php } ?>     
		                                              	</select>
													</section>
													<section>
														<font color="blue">META</font>
														<select class="form-control" id="pdes2" name="pdes2">
															<?php
																$consulta1 = 'SELECT * FROM "public"."pdes" WHERE pdes_jerarquia=\'2\' AND pdes_estado!=\'0\' ORDER BY pdes_id ';
																  $consulta1=$this->db->query($consulta1);
																  $lista_pedes=$consulta1->result_array();
																  foreach ($lista_pedes as $pedes){ ?>
																  <option value="<?php echo $pedes['pdes_codigo']?>" <?php if(@$_POST['pais']==$pedes['pdes_id']){ echo "selected";} ?> >
																  <?php echo $pedes['pdes_codigo'].' - '.$pedes['pdes_nivel'].' - '.$pedes['pdes_descripcion']?></option> 
																<?php } ?> 
														</select>
													</section>
													<section>
														<font color="blue">RESULTADO</font>
														<select class="form-control" id="pdes3" name="pdes3">
															<?php
																$consulta1 = 'SELECT * FROM "public"."pdes" WHERE pdes_jerarquia=\'3\' AND pdes_estado!=\'0\' ORDER BY pdes_id ';
																  $consulta1=$this->db->query($consulta1);
																  $lista_pedes=$consulta1->result_array();
																  foreach ($lista_pedes as $pedes){ ?>
																  <option value="<?php echo $pedes['pdes_codigo']?>" <?php if(@$_POST['pais']==$pedes['pdes_id']){ echo "selected";} ?> >
																  <?php echo $pedes['pdes_codigo'].' - '.$pedes['pdes_nivel'].' - '.$pedes['pdes_descripcion']?></option> 
																<?php } ?> 
														</select>
													</section>
													<section>
														<font color="blue">ACCI&Oacute;N</font>
														<select class="form-control" id="pdes4" name="pdes4">
															<?php
																$consulta1 = 'SELECT * FROM "public"."pdes" WHERE pdes_jerarquia=\'4\' AND pdes_estado!=\'0\' ORDER BY pdes_id ';
																  $consulta1=$this->db->query($consulta1);
																  $lista_pedes=$consulta1->result_array();
																  foreach ($lista_pedes as $pedes){ ?>
																  <option value="<?php echo $pedes['pdes_codigo']?>"><?php echo $pedes['pdes_codigo'].' - '.$pedes['pdes_nivel'].' - '.$pedes['pdes_descripcion']?></option> 
																<?php } ?> 
														</select>
												</fieldset>
												<footer>
													<div id="mbut">
														<div class="col-md-3 pull-left">
							                            <button class="btn btn-ms btn-danger" data-dismiss="modal">CANCELAR</button>
								                        </div>
								                        <div class="col-md-3 pull-right ">
								                            <button type="button" name="mod_ffenviar" id="mod_ffenviar" class="btn btn-success" style="width:100%;">MODIFICAR</button>
								                        </div>
													</div>
							                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
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

	<div class="modal animated fadeInDown" id="modal_nuevo_ff" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body no-padding">
                    <div class="row">
                       <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h2 class="row-seperator-header">FORMULARIO DE REGISTRO - ACCIONES ESTRATEGICAS</h2>
								<div class="row">
									<!-- col -->
									<div class="col-sm-12">
										<!-- row -->
										<div class="row">
											<form action="<?php echo site_url().'/me/valida_acciones_estrategicas'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
											   	<input type="hidden" name="gi" id="gi" value="<?php echo $configuracion[0]['conf_gestion_desde'];?>">
											   	<input type="hidden" name="gf" id="gf" value="<?php echo $configuracion[0]['conf_gestion_hasta'];?>">
											   	<input type="hidden" name="obj_id" id="obj_id" value="<?php echo $obj_estrategico[0]['obj_id'];?>">
											   	<fieldset>
													<section>
														RESULTADO FINAL
														<select class="form-control" id="resf" name="resf">
				                                            <option value="">Seleccione Resultado</option>
				                                            <?php 
											                    foreach($resultado_final as $row){ ?>
													                <option value="<?php echo $row['rf_id']; ?>"><?php echo $row['rf_cod'].'.- '.$row['rf_resultado']; ?></option>
													                <?php 	
											                    }
											                ?>
				                                      	</select>
													</section>

													<section>
														C&Oacute;DIGO
														<label class="input"> <i class="icon-append fa fa-user"></i>
															<input type="text" name="codigo" id="mcodigo" placeholder="C&Oacute;DIGO" onkeypress="if (this.value.length < 2) { return soloNumeros(event);}else{return false; }" onpaste="return false" required="true">
															<b class="tooltip tooltip-bottom-right">C&Oacute;DIGO</b> </label>
													</section>

													<section>
														ACCI&Oacute;N ESTRATEGICA
														<label class="textarea"> <i class="icon-append fa fa-comment"></i>
															<textarea rows="4" name="descripcion" id="descripcion" placeholder="DESCRIPCION - ACCI&Oacute;N ESTRATEGICA"></textarea> 
															<b class="tooltip tooltip-bottom-right">DESCRIPCI&Oacute;N</b></label>
													</section><br>

													<div class="alert alert-info" role="alert">
													  	VINCULACI&Oacute;N AL PEDES
													</div>

													<?php echo $listado; ?>
												</fieldset>
												<footer>
													<div id="but" style="display: none;">
														<div class="col-md-3 pull-left">
								                            <button class="btn btn-ms btn-danger" data-dismiss="modal">CANCELAR</button>
								                        </div>
								                        <div class="col-md-3 pull-right ">
								                            <button type="button" name="subir_form" id="subir_form" class="btn btn-success" style="width:100%;">GUARDAR</button>
								                        </div>
								                    </div>
							                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
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
		<!-- ================== MODAL SUBIR ARCHIVO PDES ========================== -->
	  	<div class="modal fade" id="modal_importar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog modal-dialog-centered" role="document" class="modal-dialog modal-sm">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                	<h2><center>SUBIR ARCHIVO PDES.CSV</center></h2>
	                
	                    <div class="row">
	                    	<script src="<?php echo base_url(); ?>assets/file_nuevo/jquery.min.js"></script>
	                    		<form action="<?php echo site_url().'/mestrategico/cacciones_estrategicas/valida_add_pdes';?>" method="post" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep">
								<div class="input-group">
								  <span class="input-group-btn">
								    <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
								    <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
								  	<input name="MAX_FILE_SIZE" type="hidden" value="20000" />
								  </span>
								  <span class="form-control"></span>
								</div>
								<hr>
								<div >
	                                <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR REQUERIMIENTOS .CSV</button><br>
			                        <center><img id="loads" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
	                            </div>
                              </form> 
	                    </div>
	                </div>
	            </div>
	        </div>
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
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
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
		 
		<!-- get resultado final -->
		<script type="text/javascript">
			$(function () {
			    //SUBIR ARCHIVO
			    $("#subir_archivo").on("click", function () {
			      var $valid = $("#form_subir_sigep").valid();
			      if (!$valid) {
			          $validator.focusInvalid();
			      } else {
			        if(document.getElementById('archivo').value==''){
			          alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
			          return false;
			        }
			          alertify.confirm("SUBIR ARCHIVO REQUERIMIENTOS.CSV?", function (a) {
			              if (a) {
			                  document.getElementById("subir_archivo").value = "AGREGANDO REQUERIMIENTOS...";
			                  document.getElementById("loads").style.display = 'block';
			                  document.getElementById('subir_archivo').disabled = true;
			                  document.forms['form_subir_sigep'].submit();
			              } else {
			                  alertify.error("OPCI\u00D3N CANCELADA");
			              }
			          });
			      }
			    });
			  });

			$("#resf").change(function () {
			    $("#resf option:selected").each(function () {
			        elegido = $(this).val();
			        if(elegido!=0){
			        	var url = "<?php echo site_url("")?>/me/get_resultado_final";
			            var request;
			            if (request) {
			                request.abort();
			            }
			            request = $.ajax({
			                url: url,
			                type: "POST",
			                dataType: 'json',
			                data: "rf_id=" + elegido
			            });

			            request.done(function (response, textStatus, jqXHR) {
			            	//document.getElementById("mcodigo").value = response.resultado[0]['rf_cod'];
			            	$('#but').slideDown();
			            });
			        	
			        }
			        else{
			        	//document.getElementById("mcodigo").value = '';
			        	$('#but').slideUp();
			        }

			    });
			});
			$("#rf_id").change(function () {
			    $("#rf_id option:selected").each(function () {
			        elegido = $(this).val();
			        if(elegido!=0){
			        	var url = "<?php echo site_url("")?>/me/get_resultado_final";
			            var request;
			            if (request) {
			                request.abort();
			            }
			            request = $.ajax({
			                url: url,
			                type: "POST",
			                dataType: 'json',
			                data: "rf_id=" + elegido
			            });

			            request.done(function (response, textStatus, jqXHR) {
			            	//document.getElementById("codigo").value = response.resultado[0]['rf_cod'];
			            	$('#mbut').slideDown();
			            });
			        	
			        }
			        else{
			        	//document.getElementById("codigo").value = '';
			        	$('#mbut').slideUp();
			        }

			    });
			});
		</script>

		<!-- modificar accion -->
		<script type="text/javascript">
		    $(function () {
		        $(".mod_ff").on("click", function (e) {
		            acc_id = $(this).attr('name'); 
		            document.getElementById("acc_id").value=acc_id;
		            var url = "<?php echo site_url("")?>/me/get_acciones_estrategicas";
		            var request;
		            if (request) {
		                request.abort();
		            }
		            request = $.ajax({
		                url: url,
		                type: "POST",
		                dataType: 'json',
		                data: "acc_id=" + acc_id
		            });

		            request.done(function (response, textStatus, jqXHR) {
		                document.getElementById("rf_id").value = response.rf_id;
		                document.getElementById("codigo").value = response.codigo;
		                document.getElementById("descripcion").value = response.descripcion;
		                document.getElementById("pdes1").value = response.id1;
		                document.getElementById("pdes2").value = response.id2;
		                document.getElementById("pdes3").value = response.id3;
		                document.getElementById("pdes4").value = response.id4;
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
			                	rf_id: { //// rf
			                        required: true,
			                    },
			                    codigo: { //// Codigo
			                        required: true,
			                    },
			                    descripcion: { //// Descripcion
			                        required: true,
			                    },
			                    pdes1: { //// Pedes 1
			                        required: true,
			                    },
			                    pdes2: { //// Pedes 2
			                        required: true,
			                    },
			                    pdes3: { //// Pedes 3
			                        required: true,
			                    }
			                },
			                messages: {
			                    rf_id: "<font color=red>SELECCIONE RESULTADO FINAL</font>",
			                    codigo: "<font color=red>REGISTRE C&Oacute;DIGO</font>",
			                    descripcion: "<font color=red>REGISTRE DESCRIPCI&Oacute;N</font>",
			                    pdes1: "<font color=red>SELECCIONE VINCULACION AL PDES - PILAR</font>",
		                    	pdes2: "<font color=red>SELECCIONE VINCULACION AL PDES - META</font>",
		                    	pdes3: "<font color=red>SELECCIONE VINCULACION AL PDES - RESULTADO</font>",	                    
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
		                    alertify.confirm("MODIFICAR ACCIÓN ESTRATEGICA ?", function (a) {
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

		<!-- Nueva accion -->
		<script type="text/javascript">
		$(function () {
		    $("#subir_form").on("click", function () {
		    	var $validator = $("#form_nuevo").validate({
		                rules: {
		                	resf: { //// res
		                        required: true,
		                    },
		                    codigo: { //// codigo
		                        required: true,
		                    },
		                    descripcion: { //// descripcion
		                        required: true,
		                    },
		                    obj_id: { //// Objetivo Id
		                        required: true,
		                    },
		                    pedes1: { //// Pedes 1
		                        required: true,
		                    },
		                    pedes2: { //// Pedes 2
		                        required: true,
		                    },
		                    pedes3: { //// Pedes 3
		                        required: true,
		                    }
		                },
		                messages: {
		                    resf: "<font color=red>SELECCIONE RESULTADO FINAL</font>",
		                    codigo: "<font color=red>REGISTRE C&Oacute;DIGO</font>",
		                    descripcion: "<font color=red>REGISTRE DESCRIPCI&Oacute;N</font>",
		                    obj_id: "<font color=red>OBJETIVO ID</font>",
		                    pedes1: "<font color=red>SELECCIONE VINCULACION AL PDES - PILAR</font>",
		                    pedes2: "<font color=red>SELECCIONE VINCULACION AL PDES - META</font>",
		                    pedes3: "<font color=red>SELECCIONE VINCULACION AL PDES - RESULTADO</font>",		                    
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
	                alertify.confirm("GUARDAR ACCIÓN ESTRATEGICA DE MEDIANO PLAZO ?", function (a) {
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
		<!-- Eliminar accion -->
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
		            alertify.confirm("DESEA ELIMINAR ACCIÓN ESTRATÉGICA ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/me/delete_acciones_estrategicas";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "acc_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("LA ACCIÓN ESTRATEGICA SE ELIMINO CORRECTAMENTE ", function (e) {
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
		<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			pageSetUp();
            $("#pedes1").change(function () {
				$("#pedes1 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_2' }, function(data){
						$("#pedes2").html(data);
					});     
				});
			});
			$("#pedes2").change(function () {
				$("#pedes2 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_3' }, function(data){
						$("#pedes3").html(data);
					});     
				});
			});  
			$("#pedes3").change(function () {
				$("#pedes3 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_3' }, function(data){
						$("#pedes4").html(data);
					});     
				});
			});



			$("#pdes1").change(function () {
				$("#pdes1 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_2' }, function(data){
						$("#pdes2").html(data);
					});     
				});
			});
			$("#pdes2").change(function () {
				$("#pdes2 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_3' }, function(data){
						$("#pdes3").html(data);
					});     
				});
			});  
			$("#pdes3").change(function () {
				$("#pdes3 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'pedes_3' }, function(data){
						$("#pdes4").html(data);
					});     
				});
			});
		})
		</script>
	</body>
</html>
