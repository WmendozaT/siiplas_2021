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
		    #comparativo{
		      width: 90% !important;
		    }
		    #mdialTamanio{
              width: 45% !important;
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
		                <a href="#" title="PROGRAMACION"> <span class="menu-item-parent">EVALUACI&Oacute;N POA</span></a>
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
					<li>Evaluaci&oacute;n POA</li><li>Evaluaci&oacute;n Operaciones (Objetivos Regionales)</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<?php echo $titulo;?>
	                </div>
	                <div class="row">
	                   <article class="col-sm-12 col-md-12 col-lg-12">
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
							<div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-list-alt"></i></span>
									<h2><b>EVALUACI&Oacute;N OPERACIONES</b></h2>
								</header>
								<!-- widget div-->
								<div>
									<?php echo $tabla;?>
								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->
						</article>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->

		 <!-- ================ Modal EVALUAR PRODUCTO ================= -->
        <!-- Absoluto -->
          <div class="modal fade" id="modal_add_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		    <div class="modal-dialog modal-lg" role="document">
		      <div class="modal-content">
		      	  <div class="modal-header">
		            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
		          </div>
		          <div class="modal-body">
		            <h2 class="alert alert-info"><center>EVALUAR META REGIONAL - <?php echo $trimestre[0]['trm_descripcion'];?></center></h2>
		              <form action="<?php echo site_url().'/ejecucion/cevaluacion_pei/valida_evalmeta';?>" id="form_eval" name="form_eval" class="smart-form" method="post">
		                  <input type="hidden" name="pog_id" id="pog_id">
		                  <input type="hidden" name="trm_id" id="trm_id">
		                  <input type="hidden" name="evaluado" id="evaluado">
		                  <input type="hidden" name="tp" id="tp" value="0">
		                  <b><div id="regional"></div></b>
		                  <header><b><div id="objetivo"></div></b></header>
			                <fieldset>
			                    <div class="row">
			                      <section class="col col-6">
			                        <label class="label">META REGIONAL</label>
			                        <label class="input">
			                          <i class="icon-append fa fa-tag"></i>
			                          <input type="text" name="meta" id="meta" value="0" title="META REGIONAL" disabled="true">
			                        </label>
			                      </section>
			                      <section class="col col-6">
			                        <label class="label">META EVALUADO</label>
			                        <label class="input">
			                          <i class="icon-append fa fa-tag"></i>
			                          <input type="text" name="ejec_meta" id="ejec_meta" value="0" title="META EVALUADO" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" onkeyup="verif_evaluacion()">
			                        </label>
			                      </section>
			                    </div>
			                    <div class="row">
			                      <section class="col col-6">
			                      </section>
			                      <section class="col col-6">
			                        <div id="valor_evaluado"></div>
			                      </section>
			                    </div>
			                </fieldset>

			                <fieldset>
			                	<div id="tit"></div>
			                	<!-- <div id="medio" style="display:none;"> -->
			                	<div id="cumplido" style="display:none;">
			                		<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="mverif" id="mverif" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
		                        </div>
		                    </fieldset>

	                        <!-- <div id="rel" style="display:none;"> -->
	                        <div id="proceso" style="display:none;">
		                        <fieldset> 
		                        	<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>PROBLEMAS PRESENTADOS</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="prob" id="prob" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
			                	</fieldset>
			                	<fieldset>
			                		<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>ACCIONES REALIZADAS</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="acc" id="acc" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
			                	</fieldset>
	                        </div>

		                  	<div id="but" style="display:none;">
		                    	<footer>
		                      		<button type="button" name="subir_eval" id="subir_eval" class="btn btn-info" >EVALUAR META OBJETIVO</button>
		                      		<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
		                    	</footer>
		                  	</div>
		                  	<div id="load" style="display: none" align="center">
		                       <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>EVALUANDO META</b>
		                    </div>
		              	</form>
		              </div>
		          </div>
		      </div>
		  </div>


  
        <!-- =========================================== Modal MODIFICAR EVALUAR OPERACION ================================================= -->
        <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          	<div class="modal-dialog modal-lg" role="document">
            	<div class="modal-content">
            		<div class="modal-header">
		            	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
		          	</div>
              		<div class="modal-body">
              			<h2 class="alert alert-info"><center>EVALUAR META REGIONAL - <?php echo $trimestre[0]['trm_descripcion'];?></center></h2>
		              	<form action="<?php echo site_url().'/ejecucion/cevaluacion_pei/valida_update_evalmeta';?>" id="form_meval" name="form_meval" class="smart-form" method="post">
		                  <input type="hidden" name="epog_id" id="epog_id">
		                  <input type="hidden" name="mtrm_id" id="mtrm_id">
		                  <input type="hidden" name="mevaluado" id="mevaluado">
		                  <input type="hidden" name="mtp" id="mtp" value="0">
		                  <b><div id="mregional"></div></b>
		                  <header><b><div id="mobjetivo"></div></b></header>
			                <fieldset>
			                    <div class="row">
			                      <section class="col col-6">
			                        <label class="label">META REGIONAL</label>
			                        <label class="input">
			                          <i class="icon-append fa fa-tag"></i>
			                          <input type="text" name="mmeta" id="mmeta" value="0" title="META REGIONAL" disabled="true">
			                        </label>
			                      </section>
			                      <section class="col col-6">
			                        <label class="label">META EVALUADO</label>
			                        <label class="input">
			                          <i class="icon-append fa fa-tag"></i>
			                          <input type="text" name="mejec_meta" id="mejec_meta" value="0" title="META EVALUADO" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" onkeyup="mverif_evaluacion()">
			                        </label>
			                      </section>
			                    </div>
			                    <div class="row">
			                      <section class="col col-6">
			                      </section>
			                      <section class="col col-6">
			                        <div id="mvalor_evaluado"></div>
			                      </section>
			                    </div>
			                </fieldset>

			                <fieldset>
			                	<div id="mtit"></div>
			                	<!-- <div id="medio" style="display:none;"> -->
			                	<div id="mcumplido" style="display:none;">
			                		<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="mmverif" id="mmverif" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
		                        </div>
		                    </fieldset>

	                        <!-- <div id="rel" style="display:none;"> -->
	                        <div id="mproceso" style="display:none;">
		                        <fieldset> 
		                        	<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>PROBLEMAS PRESENTADOS</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="mprob" id="mprob" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
			                	</fieldset>
			                	<fieldset>
			                		<div class="row">
			                			<div class="form-group">
			                                <label class="label"><b>ACCIONES REALIZADAS</b></label>
			                                <div class="col-md-12">
			                                    <textarea class="form-control" name="macc" id="macc" placeholder="" rows="4"></textarea>
			                                </div>
			                            </div>
			                		</div>
			                	</fieldset>
	                        </div>

		                  	<div id="mbut" style="display:none;">
		                    	<footer>
		                      		<button type="button" name="subir_meval" id="subir_meval" class="btn btn-info" >EVALUAR META OBJETIVO</button>
		                      		<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
		                    	</footer>
		                  	</div>
		                  	<div id="mload" style="display: none" align="center">
		                       <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>EVALUANDO META</b>
		                    </div>
		              	</form>
	            	</div>
	         	</div>
	        </div>
	    </div>
		<!-- ========================================================================================================= -->

		<!-- MODAL GRAFICO DE EVALUACION -->
	    <div class="modal fade" id="modal_evaluacion" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog" id="comparativo">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Ventana</b></span></button>
	                </div>
	                <div class="modal-body">
	                	<h2><center>CUADRO DE EVALUACI&Oacute;N DE METAS REGIONAL - <?php echo $this->session->userData('gestion');?></center></h2>
	                    <div class="row">
	                    	<div id="titulo"></div>	
	                    	<br>
	                        <div id="cuadro_comparativo"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	 	<!--  =============== -->


 	<!-- MODAL UPDATE TEMPORALIDAD PROG/EJEC POR OBJETIVO REGIONAL   -->
        <div class="modal fade" id="modal_update_temporalidad" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <form id="form_update" novalidate="novalidate" method="post">
                        <input type="hidden" name="com_id" id="com_id">
                        <div id="content_valida">
                            <center><div class="loading" align="center"><h2>Actualizando Temporalidad Programado/Ejecucion de OBJETIVOS REGIONALES <br><div id="tit"></div></h2><br><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /></div></center>
                        </div>
                        <div id="load_update_temp" style="display: none;"><center><img src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"><hr><b>ACTUALIZANDO TEMPORALIDAD OPERACIONES ...</b></center></div>
                            <p>
                                <div id="but_update_temp" align="right" style="display:none;">
                                    <button type="button" name="but_update" id="but_update" class="btn btn-success">ACEPTAR </button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </p>
                    </form>
                </div>
            </div>
        </div>
     <!--  =============== -->

     <!-- MODAL LISTA DE ACTIVIDADES PRIORIZADOS -->
        <div class="modal fade" id="modal_act_priorizados" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                    <h2 class="alert alert-info"><center>MIS ACTIVIDADES PRIORIZADOS - <?php echo $this->session->userData('gestion');?></center></h2>
                        <div class="row">
                            <div id="titulo"></div>
                            <div id="content1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  =============== -->   
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
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>

		<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 

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
		<script src="<?php echo base_url(); ?>mis_js/seguimientooregional/seguimiento_oregional.js"></script> 
		        <script type="text/javascript">
            function ver_poa(proy_id) {
                $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
                $('#content1').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
                
                var url = "<?php echo site_url("")?>/programacion/proyecto/get_poa";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "proy_id="+proy_id
                });

                request.done(function (response, textStatus, jqXHR) {

                if (response.respuesta == 'correcto') {
                    if(response.proyecto[0]['tp_id']==1){
                        $('#titulo').html('<font size=3><b>'+response.proyecto[0]['aper_programa']+' '+response.proyecto[0]['proy_sisin']+' 000 - '+response.proyecto[0]['proy_nombre']+'</b></font>');
                    }
                    else{
                        $('#titulo').html('<font size=3><b>'+response.proyecto[0]['act_descripcion']+' '+response.proyecto[0]['abrev']+'</b></font>');
                    }
                    
                    $('#content1').fadeIn(1000).html(response.tabla);
                }
                else{
                    alertify.error("ERROR AL RECUPERAR INFORMACION");
                }

                });
            }
        </script>
		<!-- <script type="text/javascript">
            $(function () {
                $(".evaluacion").on("click", function (e) {
                    dep_id = $(this).attr('name');
                    $('#cuadro_comparativo').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Cuadro de Evaluación</div>');
                    
                    var url = "<?php echo site_url("")?>/ejecucion/cevaluacion_pei/get_cuadro_evaluacion";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "dep_id="+dep_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                    	$('#titulo').html('<font size=3><b> REGIONAL '+response.regional[0]['dep_departamento'].toUpperCase()+'</b></font>');
				
                    	
                        $('#cuadro_comparativo').fadeIn(1000).html(response.tabla);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE EVALUACI&Oacute;N");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                  
                });
            });
        </script> -->



		<!-- <script type="text/javascript">
        /*------ Evaluacion de Operaciones ------*/
        $(function () {
            $(".add_ff").on("click", function (e) {
            	pog_id= $(this).attr('name'); 
            	document.getElementById("pog_id").value = pog_id;

                var url = "<?php echo site_url().'/ejecucion/cevaluacion_pei/get_objetivo_regional'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "pog_id="+pog_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {

                    	document.getElementById("ejec_meta").value = '0';
                        document.getElementById("mverif").value = '';
                        document.getElementById("prob").value = '';
                        document.getElementById("acc").value = '';

                        $('#but').slideUp();
		            	$('#cumplido').slideUp();
		            	$('#proceso').slideUp();
	            		document.getElementById("tp").value = 0;
	            		$('#tit').html('');


                        $('#regional').html('REGIONAL : ' +(response.meta_regional[0]['dep_departamento']).toUpperCase());
                        $('#objetivo').html(response.meta_regional[0]['or_codigo']+".- "+response.meta_regional[0]['or_objetivo']);
                        $('#valor_evaluado').html('<span class="label bg-color-green txt-color-white">META TOTAL EVALUADO : '+response.evaluado+'</span>');
                        document.getElementById("meta").value = response.meta_regional[0]['or_meta'];
                       	document.getElementById("trm_id").value = response.trimestre[0]['trm_id'];
                       	document.getElementById("evaluado").value = response.evaluado;

                    } else {
                        alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                    }
                });

                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });
                e.preventDefault();
                // =============================VALIDAR EL FORMULARIO DE MODIFICACION
                $("#subir_eval").on("click", function (e) {
                    var $validator = $("#form_eval").validate({
                       rules: {
                            pog_id: {
                                required: true,
                            },
                            ejec_meta: {
                                required: true,
                            }
                        },
                        messages: {
                            ejec_meta: "Registre ejecución Meta",                           
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
                    var $valid = $("#form_eval").valid();
                    if (!$valid) {
                        $validator.focusInvalid();
                    } else {
                        	
                    	var tp = document.getElementById("tp").value;
                    	if(tp==1){
                    		if(document.getElementById("mverif").value==''){
                                alertify.alert("REGISTRE MEDIO DE VERIFICACI&Oacute;N") 
                                document.form_eval.mverif.focus() 
                                return 0;
                            }
                    	}
                    	if(tp==2 || tp==3){
                    		if(document.getElementById("mverif").value==''){
                                alertify.alert("REGISTRE MEDIO DE VERIFICACI&Oacute;N") 
                                document.form_eval.mverif.focus() 
                                return 0;
                            }
                            if(document.getElementById("prob").value==''){
                                alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                document.form_eval.prob.focus() 
                                return 0;
                            }
                    	}

                        alertify.confirm("EVALUAR META OBJETIVO REGIONAL ?", function (a) {
                            if (a) {
                                document.getElementById("load").style.display = 'block';
                                document.forms['form_eval'].submit();
                                document.getElementById("but").style.display = 'none';
                            } else {
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });
                    }
                });
            });
        });
        </script> -->
       <!--  <script type="text/javascript">
        /*---- Modificar Evaluacion de Operaciones -----*/
        $(function () {
            var prod_id = ''; var proy_id = '';
            $(".mod_ff").on("click", function (e) {
                epog_id= $(this).attr('name'); 
            	document.getElementById("epog_id").value = epog_id; 

                var url = "<?php echo site_url().'/ejecucion/cevaluacion_pei/get_update_objetivo_regional'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "epog_id="+epog_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        
                        document.getElementById("mejec_meta").value = '';
                        document.getElementById("mmverif").value = '';
                        document.getElementById("mprob").value = '';
                        document.getElementById("macc").value = '';

                    	$('#mregional').html('REGIONAL : ' +(response.meta_regional[0]['dep_departamento']).toUpperCase());
                    	$('#mobjetivo').html(response.meta_regional[0]['or_codigo']+".- "+response.meta_regional[0]['or_objetivo']);
                    	//alert(response.meta_regional[0]['or_meta']+'--'+response.total_evaluado)

                    	$('#mvalor_evaluado').html('<span class="label bg-color-green txt-color-white">META TOTAL EVALUADO : '+response.total_evaluado+'</span>');
                    	document.getElementById("mmeta").value = response.meta_regional[0]['or_meta'];
                    	document.getElementById("mtrm_id").value = response.trimestre;
                    	document.getElementById("mejec_meta").value = response.datos_meta_evaluado[0]['ejec_fis'];
                    	document.getElementById("mevaluado").value = (response.total_evaluado-response.datos_meta_evaluado[0]['ejec_fis']);
                    
                    	if(response.datos_meta_evaluado[0]['tp_eval']==1){
                    		$('#mtit').html('<center><div class="alert alert-success alert-block"><b>OBJETIVO CUMPLIDO</b></div></center>');
			            	$('#mcumplido').slideDown();
			            	$('#mproceso').slideUp();
			            	$('#mbut').slideDown();
			            	document.getElementById("mtp").value = 1;
			            	document.getElementById("mmverif").value = response.datos_meta_evaluado[0]['tmed_verif'];
                    	}
                    	else{
                    		if(response.datos_meta_evaluado[0]['tp_eval']==3){
	                    		$('#mtit').html('<center><div class="alert alert-danger alert-block"><b>META NO CUMPLIDO</b></div></center>');
				            	$('#mproceso').slideDown();
				            	$('#mcumplido').slideDown();
				            	$('#mbut').slideDown();
				            	document.getElementById("mtp").value = 3;
				            	document.getElementById("mmverif").value = response.datos_meta_evaluado[0]['tmed_verif'];
				            	document.getElementById("mprob").value = response.datos_meta_evaluado[0]['tprob'];
				            	document.getElementById("macc").value = response.datos_meta_evaluado[0]['tacciones'];
	                    	}
	                    	else{
	                    		$('#mtit').html('<center><div class="alert alert-warning alert-block"><b>META EN PROCESO</b></div></center>');
				            	$('#mproceso').slideDown();
				            	$('#mcumplido').slideDown();
				            	$('#mbut').slideDown();
				            	document.getElementById("mtp").value = 2;
				            	document.getElementById("mmverif").value = response.datos_meta_evaluado[0]['tmed_verif'];
				            	document.getElementById("mprob").value = response.datos_meta_evaluado[0]['tprob'];
				            	document.getElementById("macc").value = response.datos_meta_evaluado[0]['tacciones'];
	                    	}
                    	}
                    	
   
  	
                    } else {
                        alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                    }
                });

                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });
                e.preventDefault();
                // =============================VALIDAR EL FORMULARIO DE MODIFICACION
                $("#subir_meval").on("click", function (e) {
                    var $validator = $("#form_meval").validate({
                       rules: {
                            epog_id: {
                                required: true,
                            },
                            mejec_meta: { //// ejecucion
                                required: true,
                            }
                        },
                        messages: {
                            mejec_meta: "Registre ejecución",                           
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
                    var $valid = $("#form_meval").valid();
                    if (!$valid) {
                        $validator.focusInvalid();
                    } else {
                        
                    	var tp = document.getElementById("mtp").value;
                    	if(tp==1){
                    		if(document.getElementById("mmverif").value==''){
                                alertify.alert("REGISTRE MEDIO DE VERIFICACI&Oacute;N") 
                                document.form_eval.mmverif.focus() 
                                return 0;
                            }
                    	}
                    	if(tp==2 || tp==3){
                    		if(document.getElementById("mmverif").value==''){
                                alertify.alert("REGISTRE MEDIO DE VERIFICACI&Oacute;N") 
                                document.form_eval.mmverif.focus() 
                                return 0;
                            }
                            if(document.getElementById("mprob").value==''){
                                alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                document.form_eval.mprob.focus() 
                                return 0;
                            }
                    	}

                        alertify.confirm("EVALUAR META OBJETIVO REGIONAL ?", function (a) {
                            if (a) {
                                document.getElementById("mload").style.display = 'block';
                                document.forms['form_meval'].submit();
                                document.getElementById("mbut").style.display = 'none';
                            } else {
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });    
                    }
                });
            });
        });
        </script> -->

       <!--  <script type="text/javascript">
            function verif_evaluacion(){

            meta = parseFloat($('[name="meta"]').val()); /// meta           
            meta_evaluado = parseFloat($('[name="evaluado"]').val()); /// meta evaluado anteriormente
            ejec_meta = (parseFloat($('[name="ejec_meta"]').val())); /// ejec meta
            
            $('#valor_evaluado').html('<span class="label bg-color-green txt-color-white">META TOTAL EVALUADO : '+(ejec_meta+meta_evaluado)+'</span>');

            if((ejec_meta+meta_evaluado)<=meta){
            	if((ejec_meta+meta_evaluado)<=meta){
            		$('#tit').html('<center><div class="alert alert-warning alert-block"><b>META EN PROCESO</b></div></center>');
	            	$('#proceso').slideDown();
	            	$('#cumplido').slideDown();
	            	$('#but').slideDown();
	            	document.getElementById("tp").value = 2;
	            }
            	if((ejec_meta+meta_evaluado)==meta){
            		$('#tit').html('<center><div class="alert alert-success alert-block"><b>OBJETIVO CUMPLIDO</b></div></center>');
	            	$('#cumplido').slideDown();
	            	$('#proceso').slideUp();
	            	$('#but').slideDown();
	            	document.getElementById("tp").value = 1;
	            }
	            if((ejec_meta+meta_evaluado)==0){
            		$('#tit').html('<center><div class="alert alert-danger alert-block"><b>OBJETIVO NO CUMPLIDO</b></div></center>');
	            	$('#proceso').slideDown();
	            	$('#cumplido').slideDown();
	            	$('#but').slideDown();
	            	document.getElementById("tp").value = 3;
	            }
            }
            else{
            	$('#but').slideUp();
            	$('#cumplido').slideUp();
            	$('#proceso').slideUp();
            	$('#tit').html('<center><div class="alert alert-danger alert-block">ERROR EN EL VALOR DE LA EVALUACIÓN</div></center>');
            	document.getElementById("tp").value = 0;
            }
        }

        function mverif_evaluacion(){ 
            meta = parseFloat($('[name="mmeta"]').val()); /// meta           
            meta_evaluado = parseFloat($('[name="mevaluado"]').val()); /// meta evaluado anteriormente
            ejec_meta = (parseFloat($('[name="mejec_meta"]').val())); /// ejec meta
            
            $('#mvalor_evaluado').html('<span class="label bg-color-green txt-color-white">META TOTAL EVALUADO : '+(ejec_meta+meta_evaluado)+'</span>');

            if(ejec_meta>0 & ejec_meta!='' & (ejec_meta+meta_evaluado)<=meta){

            	if((ejec_meta+meta_evaluado)<=meta){
            		$('#mtit').html('<center><div class="alert alert-warning alert-block"><b>META EN PROCESO</b></div></center>');
	            	$('#mproceso').slideDown();
	            	$('#mcumplido').slideDown();
	            	$('#mbut').slideDown();
	            	document.getElementById("mtp").value = 2;
	            }
            	if((ejec_meta+meta_evaluado)==meta){
            		$('#mtit').html('<center><div class="alert alert-success alert-block"><b>OBJETIVO CUMPLIDO</b></div></center>');
	            	$('#mcumplido').slideDown();
	            	$('#mproceso').slideUp();
	            	$('#mbut').slideDown();
	            	document.getElementById("mtp").value = 1;
	            }
	            if((ejec_meta+meta_evaluado)==0){
            		$('#mtit').html('<center><div class="alert alert-danger alert-block"><b>OBJETIVO NO CUMPLIDO</b></div></center>');
	            	$('#mproceso').slideDown();
	            	$('#mcumplido').slideDown();
	            	$('#mbut').slideDown();
	            	document.getElementById("mtp").value = 3;
	            }
            }
            else{
            	if(ejec_meta==0){
            		$('#mtit').html('<center><div class="alert alert-danger alert-block"><b>META NO CUMPLIDO</b></div></center>');
	            	$('#mproceso').slideDown();
	            	$('#mcumplido').slideDown();
	            	$('#mbut').slideDown();
	            	document.getElementById("mtp").value = 3;
            	}
            	else{
            		$('#mbut').slideUp();
            	$('#mcumplido').slideUp();
            	$('#mproceso').slideUp();
            	$('#mtit').html('<center><div class="alert alert-danger alert-block">ERROR EN EL VALOR DE LA EVALUACIÓN</div></center>');
            	document.getElementById("mtp").value = 0;
            	$('#mvalor_evaluado').html('<span class="label bg-color-green txt-color-white">META TOTAL EVALUADO : '+parseFloat($('[name="mevaluado"]').val())+'</span>');
            	}
            	
            }
        }
        </script> -->
        <script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				// menu
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>
