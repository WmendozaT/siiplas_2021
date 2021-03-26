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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
    	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
    	<style type="text/css">
	    table{
	        font-size: 9px;
	        width: 100%;
	        max-width:1550px;;
	        overflow-x: scroll;
	        }
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
		                <a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="MIS PROYECTOS"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
		            </li>
					<?php
		                for($i=0;$i<count($enlaces);$i++)
		                {
		                    if(count($subenlaces[$enlaces[$i]['o_child']])>0)
		                    {
		            		?>
		            		<li>
		              			<a href="#" >
		              			<i class="<?php echo $enlaces[$i]['o_image']?>"></i> <span class="menu-item-parent"><?php echo $enlaces[$i]['o_titulo']; ?></span></a>
		              			<ul >
		              			<?php
		                		foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
		                		?>
		                		<li><a href="<?php echo base_url($item['o_url']); ?>"><?php echo $item['o_titulo']; ?></a></li>
		                		<?php } ?>
		                		</ul>
		            		</li>
		            		<?php 
		                	}
		            	} 
		            ?>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa' ?>" title="MIS OPERACIONES">...</a></li><li>Programaci&oacute;n de Requerimientos - Nivel de Componentes</li><li>Insumo Componente</li><li>Nuevo Requerimiento - Activos Fijos</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<!-- row -->
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				            <section id="widget-grid" class="well">
				                <div class="">
				                  <h1> PROGRAMACI&Oacute;N DE REQUERIMIENTO A NIVEL COMPONENTES - NUEVO INSUMO</h1>
				                  <h1> CATEGORIA PROGRAM&Aacute;TICA : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad']?></small>
				                  <h1> <?php echo $titulo_proy;?> : <small><?php echo $proyecto[0]['proy_nombre']?></small>
				                  <h1> COMPONENTE : <small><?php echo $dato_com->com_componente?></small>
				                </div>
				            </section>
				        </article>
				    </div>
				       <div class="row">
						<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false" data-widget-deletebutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-check"></i> </span>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox"></div>
									<div class="widget-body">
										<div class="row">
											<form action="<?php echo site_url("") . '/insumos/cprog_insumos_delegado/guardar_insumo' ?>" id="ins_form_nuevo" name="ins_form_nuevo" novalidate="novalidate" method="post">
			                            	<input type="hidden" name="mod" id="mod" value="1"> <!-- modulo -->
			                            	<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proy_id ?>">
			                            	<input type="hidden" name="com_id" id="com_id" value="<?php echo $com_id ?>">
			                            	<!-- <input type="hidden" name="cant_fin" id="cant_fin" value="<?php echo count($techo) ?>"> -->
			                            	<input type="hidden" name="ins_tipo" id="ins_tipo" value="<?php echo $ins_tipo ?>">
			                            	<input type="hidden" name="saldo_fin" id="saldo_fin" value="<?php echo $sumatorias[3]; ?>">
			                            	<input type="hidden" name="gestiones" id="gestiones" value="<?php echo $gestiones ?>">
			                            	<input type="hidden" name="gv" id="gv" value="<?php echo $this->session->userdata('gestion') ?>"> <!-- gestion vigente -->
			                            	<input type="hidden" name="gp" id="gp" value="0"> <!-- ptto gestion -->

												<div id="bootstrap-wizard-1" class="col-sm-9">
													<div class="well">
														<div class="row">
															<div class="col-sm-3">
																<div class="form-group">
																	<label><font size="1"><b>FECHA REQUERIMIENTO </b></font><font color="blue">(dd/mm/yy)</font></label>
																	<div class="input-group">
							                                            <input type="text" name="ins_fecha" id="ins_fecha" value="<?php echo date('d/m/Y') ?>"
							                                                   class="form-control datepicker" data-dateformat="dd/mm/yy"
							                                                   onKeyUp="this.value=formateafecha(this.value);"
							                                                   title="Seleccione fecha">
							                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							                                        </div>
																</div>
															</div>

															<div class="col-sm-3">
																<div class="form-group">
																	<label><font size="1"><b>SALDO POR PROGRAMAR </b></font></label>
																	<div class="input-group">
																		<input class="form-control" name="saldo_programar" type="text" value="<?php echo number_format($sumatorias[3], 2, ',', '.'); ?>" disabled="disabled">
																	</div>
																</div>
															</div>

															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>DESCRIPCI&Oacute;N DEL REQUERIMIENTO (INSUMO) </b></font></label>
																		<textarea name="ins_detalle" id="ins_detalle" rows="2" class="form-control" maxlength="300"></textarea>
																</div>
															</div>
														</div>
													</div><br>
													<div class="well">
														<div class="row">
															<div class="col-sm-2">
																<div class="form-group">
																	<label><font size="1"><b>CANTIDAD REQUERIDA </b></font></label>
																	<input class="form-control" type="text" name="ins_cantidad" id="ins_cantidad" value="0" onblur="costo_total()" onkeypress="if (this.value.length < 7) { return numerosDecimales(event);}else{return false; }">
																</div>
															</div>
															<div class="col-sm-2">
																<div class="form-group"> 
																	<label><font size="1"><b>COSTO UNITARIO</b></font></label>
																	<input class="form-control" type="text" name="ins_costo_unitario" id="ins_costo_unitario" value="0" onblur="costo_total()" onkeyup="if (this.value.length < 15) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
																</div>
															</div>
															<div class="col-sm-2">
																<div class="form-group"> 
																	<label><font size="1"><b>COSTO TOTAL</b></font></label>
																	<input class="form-control" type="hidden" name="ins_costo_total" id="ins_costo_total" value="0">
	                                            					<input class="form-control" type="text" name="ins_costo_total2" id="ins_costo_total2" value="0" disabled="true">
																</div>
															</div>
															<div class="col-sm-6">
																<div class="form-group"> 
																	<label><font size="1"><b>UNIDAD DE MEDIDA</b></font></label>
																	<input name="ins_unidad_medida" id="ins_unidad_medida" class="form-control" maxlength="100">
																</div>
															</div>
														</div>
													</div><br>

													<div class="well">
														<div class="row">
															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>SELECCIONE GRUPO </b></font><font color="blue">(Obligatorio)</font></label>
																	<div >
																		<select name="ins_partidas" id="ins_partidas" class="select2">
						                                                    <option value="">Seleccione una Partida</option>
						                                                    <?php
						                                                    foreach ($lista_partidas as $row) {
						                                                        ?>
						                                                        <option value="<?php echo $row['par_codigo'] ?>"<?php if(@$_POST['pais']==$row['par_codigo']){ echo "selected";} ?> selected><?php echo $row['par_codigo'].'-'.$row['par_nombre']?></option>  
						                                                        <?php
						                                                    }
						                                                    ?>
						                                                </select>
																	</div>
																</div>
															</div>

															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>SELECIONE PARTIDA </b></font><font color="blue">(Obligatorio)</font></label>
																	<select name="ins_partidas_dependientes" id="ins_partidas_dependientes" class="select2">
					                                                    <option value="">Seleccione</option>
					                                                </select>
																</div>
															</div>
														</div>

													</div>><br> <!-- end well -->

													<div class="well">
														<div class="row">
															<div class="col-sm-12">
																<div class="form-group">
																	<label><font size="1"><b>OBSERVACI&Oacute;N </b></font></label>
																		<textarea name="ins_obs" id="ins_obs" rows="2" class="form-control" maxlength="300"></textarea>
																</div>
															</div>
														</div>
													</div>
															
												</div><br>
												<div id="bootstrap-wizard-1" class="col-sm-3">
													<div class="well">
														<div class="row">
														<?php  $cont_gest=1;
										            		for ($i=$fase[0]['pfec_fecha_inicio']; $i <=$fase[0]['pfec_fecha_fin'] ; $i++)
										            		{ 
										            			if($i==$gestion)
										            			{
										            				?>
										            				<div class="col-sm-12">
									                            		<div class="">
										                            		<div class="row">
											                                    <div class="form-group">
										                                            <LABEL><b><font color="blue">GESTI&Oacute;N <?php echo $i;?></font></b></label>
										                                            <input class="form-control" type="text" name="gest[]" id="gestion<?php echo $cont_gest;?>" value="0" onblur="javascript:suma_presupuesto();" autofocus onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
										                                        	<input class="form-control" type="hidden" name="gestv<?php echo $cont_gest;?>"  value="<?php echo $i;?>">
										                                        </div>
										                                    </div>
									                            		</div>
									                            	</div>
										            				<?php
										            			}
										            			else
										            			{
										            				?>
										            				<div class="col-sm-12">
									                            		<div class="">
										                            		<div class="row">
											                                    <div class="form-group">
										                                            <LABEL><b>GESTI&Oacute;N <?php echo $i;?></b></label>
										                                            <input class="form-control" type="text" name="gest[]" id="gestion<?php echo $cont_gest;?>" value="0" onblur="javascript:suma_presupuesto();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
										                                        	<input class="form-control" type="hidden" name="gestv<?php echo $cont_gest;?>"  value="<?php echo $i;?>">
										                                        </div>
										                                    </div>
									                            		</div>
									                            	</div>
										            				<?php
										            			}

										            			$cont_gest++;
										            		}
											            	?>	
														</div>
													</div><br> <!-- end well -->
													<div class="well">
													<div class="row">
														<div class="col-sm-12">
						                            		<div class="">
							                                    <div class="form-group">
						                                            <LABEL><b>SUMA PROGRAMADO DE GESTIONES</b></label>
						                                            <input type="text" name="suma_monto_total" id="suma_monto_total" class="form-control" value="0">
						                                        </div>
						                                    </div>
						                            	</div>
						                            	<div class="col-sm-12">
						                            		<div class="">
							                                    <div class="form-group">
						                                            <LABEL><b>SALDO</b></label>
						                                            <input type="text" name="saldo" id="saldo" class="form-control" value="0">
						                                        </div>
						                                    </div>
						                            	</div>
													</div>
													</div>
												</div>
												<div  class="col-sm-12">
													<div class="form-actions">
														<a href="<?php echo base_url().'index.php/prog/ins_com/'.$proy_id.'/'.$com_id.''; ?>" class="btn btn-lg btn-default" title="REQUERIMIENTOS DE LA OPERACION"> CANCELAR </a>
														<input type="button" value="GUARDAR REQUERIMIENTO" id="btsubmit" class="btn btn-primary btn-lg" onclick="valida_envia()" title="GUARDAR REQUERIMIENTO">
													</div>
												</div>
											</form>
										</div>
				
									</div>
									<!-- end widget content -->
								</div>
								<!-- end widget div -->
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
		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url(); ?>assets/js/plugin/pace/pace.min.js"></script>

		<script>
			if (!window.jQuery) {document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');}
		</script>
		<script>
			if (!window.jQuery.ui) {document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');}
		</script>
		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>mis_js/programacion/insumos/insumos_componentes.js"></script>
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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
			
			pageSetUp();
			$("#ins_partidas").change(function () {
                $("#ins_partidas option:selected").each(function () {
                elegido=$(this).val();
                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
                $("#ins_partidas_dependientes").html(data);
                });     
            });
            });  
		})
		</script>
	</body>
</html>
