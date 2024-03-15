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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
	</head>
	<body class="">
		<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">
				<span id="logo"> <img src="<?php echo base_url(); ?>assets/img/cajalogo.JPG" alt="SmartAdmin"> </span>
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i
	                        class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="#" title="PROGRAMACION DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
		            	} ?>
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
					<li>Programaci&oacute;n del POA</li><li>Marco Estrat&eacute;gico</li><li>Acci&oacute;n de Mediano Plazo</li><li>Modificar Acci&oacute;n</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
	                <?php
	                  $attributes = array('class' => 'form-horizontal', 'id' => 'formulario','name' =>'formulario','enctype' => 'multipart/form-data');
	                  echo validation_errors();
	                  echo form_open('admin/me/res_update', $attributes);
	                ?>
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<!-- row -->
						<form id="formulario" name="formulario" novalidate="novalidate" method="post">
						<input class="form-control" type="hidden" name="r_id" value="<?php echo $resultado[0]['r_id']?>">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>T&Eacute;CNICO RESPONSABLE</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label><font size="1"><b>RESPONSABLE DEL RESULTADO </b></font><font color="blue">(Obligatorio)</font></label>
													<select class="select2" id="fun_id" name="fun_id" title="Seleccione Responsable Operativo">
		                                                <option value="">Seleccione Responsable</option>
		                                                    <?php 
										                    foreach($responsables as $row){
									                    		if($row['fun_id']==$resultado[0]['resp_id']){ ?>
												                     <option value="<?php echo $row['fun_id']?>" selected <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
												                    <?php 
										                    	}
										                    	else{ ?>
												                     <option value="<?php echo $row['fun_id']?>" <?php if(@$_POST['pais']==$row['uni_id']){ echo "selected";} ?> ><?php echo $row['fun_nombre'].' '.$row['fun_paterno'].' '.$row['fun_materno']; ?></option>
												                    <?php 
										                    	}
										                    }
										                    ?>    
		                                            </select>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label><font size="1"><b>UNIDAD ORGANIZACIONAL </b></font><font color="blue">(Obligatorio)</font></label>
												<select class="form-control" id="uni_id" name="uni_id">
												<option value="<?php echo $unidad[0]['uni_id']?>" ><?php echo $unidad[0]['uni_unidad']; ?></option> 
                                                </select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>PEDES</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="row">
										<div class="col-sm-3">
											<label><font size="1"><b>PILAR </b></font><font color="blue">(Obligatorio)</font></label> 
											<select class="select2" id="pedes1" name="pedes1">
                                               <?php 
												if($resultado[0]['pdes_id']!=0){ ?>
													<option value="<?php echo $pdes[0]['id1'] ?>"><?php echo $pdes[0]['id1']?> - Pilar - <?php echo $pdes[0]['pilar'] ?></option> 
                                                 	<option value="">--------------------------------------------</option> 
													<?php
												}
												else{ ?>
													<option value="">No seleccionado </option> 
													<?php
												}
												
													$consulta1 = 'SELECT * FROM "public"."pdes" WHERE pdes_jerarquia=\'1\' AND pdes_gestion='.$this->session->userdata("gestion").' ORDER BY pdes_id ';
													$consulta1=$this->db->query($consulta1);
													$lista_pedes=$consulta1->result_array();
													foreach ($lista_pedes as $pedes)
													{ ?>
													  <option value="<?php echo $pedes['pdes_codigo']?>" <?php if(@$_POST['pais']==$pedes['pdes_id']){ echo "selected";} ?> >
													  	<?php echo $pedes['pdes_codigo'].' - '.$pedes['pdes_nivel'].' - '.$pedes['pdes_descripcion']?></option> 
														<?php 
													} ?>    
                                            </select>
										</div>

										<div class="col-sm-3">
											<div class="form-group">
												<label><font size="1"><b>META </b></font><font color="blue">(Obligatorio)</font></label> 
												<select class="form-control" id="pedes2" name="pedes2" >
												<?php 
													if($resultado[0]['pdes_id']!=0){ ?>
														<option value="<?php echo $pdes[0]['id2'] ?>"><?php echo $pdes[0]['id2']?> - Meta - <?php echo $pdes[0]['meta'] ?></option>  
														<?php
													}
													else{ ?>
														<option value="">No seleccionado </option> 
														<?php
													}
												?>   
                                              	</select>
											</div>
										</div>

										<div class="col-sm-3">
											<div class="form-group">
												<label><font size="1"><b>RESULTADO </b></font><font color="blue">(Obligatorio)</font></label> 
												<select class="form-control" id="pedes3" name="pedes3">
												<?php 
												if($resultado[0]['pdes_id']!=0){ ?>
													<option value="<?php echo $pdes[0]['id3'] ?>"><?php echo $pdes[0]['id3']?> - Resultado - <?php echo $pdes[0]['resultado'] ?></option>    
													<?php
												}
												else{ ?>
													<option value="">No seleccionado </option> 
													<?php
												}
												?> 
                                              	</select>
											</div>
										</div>

										<div class="col-sm-3">
											<div class="form-group">
												<label><font size="1"><b>ACCI&Oacute;N </b></font><font color="blue">(Obligatorio)</font></label> 
												<select class="form-control" id="pedes4" name="pedes4">
												<?php 
												if($resultado[0]['pdes_id']!=0){ ?>
													<option value="<?php echo $pdes[0]['id4'] ?>"><?php echo $pdes[0]['id4']?> - Accion - <?php echo $pdes[0]['accion'] ?></option>   
													<?php
												}
												else{ ?>
													<option value="">No seleccionado </option> 
													<?php
												}
												?>
                                              	</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>ACCI&Oacute;N DE MEDIANO PLAZO</strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="row">
										<div class="col-sm-10">
											<div class="form-group">
												<label><font size="1"><b>ACCI&Oacute;N DE MEDIANO PLAZO </b></font><font color="blue">(Registro Obligatorio)</font></label>
												<textarea rows="5" class="form-control" name="resultado" id="resultado" style="width:100%;"  title="Datos del Resultado"><?php echo $resultado[0]['r_resultado'];?></textarea> 
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label><font size="1"><b>PONDERACI&Oacute;N %</b></font></label>
												<input class="form-control" type="number" name="pn_cion" id="pn_cion" value="<?php echo round($resultado[0]['r_ponderacion'],2);?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
											</div>
										</div>																		
									</div>
								</div>
							</div>
						</article>
							<div class="form-actions">
								<a href="<?php echo base_url().'index.php/admin/me/resultados' ?>" class="btn btn-lg btn-default" title="VOLVER A MIS RESULTADOS"> CANCELAR </a>
								<input type="button" value="MODIFICAR ACCION" id="btsubmit" class="btn btn-primary btn-lg" onclick="valida_envia()" title="GUARDAR REGISTRO">
							</div>
						</form>
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
		<script>
            function valida_envia()
            { 
                if (document.formulario.fun_id.value==""){ 
                    alertify.alert("SELECCIONE RESPONSABLE DE LA ACCIÓN") 
                    document.formulario.fun_id.focus() 
                    return 0; 
                }

                if (document.formulario.pedes1.value==""){ 
                    alertify.alert("SELECCIONE PEDES - PILAR") 
                    document.formulario.pedes1.focus() 
                    return 0; 
                }

                if (document.formulario.pedes2.value==""){ 
                    alertify.alert("SELECCIONE PEDES - META") 
                    document.formulario.pedes2.focus() 
                    return 0; 
                }

                if (document.formulario.pedes3.value==""){ 
                    alertify.alert("SELECCIONE PEDES - RESULTADO") 
                    document.formulario.pedes3.focus() 
                    return 0; 
                }

                if (document.formulario.pedes4.value==""){ 
                    alertify.alert("SELECCIONE PEDES - ACCION") 
                    document.formulario.pedes4.focus() 
                    return 0; 
                }

                if (document.formulario.resultado.value==""){ 
                    alertify.alert("REGISTRE RESULTADO DE MEDIANO PLAZO") 
                    document.formulario.resultado.focus() 
                    return 0; 
                }

                alertify.confirm("MODIFICAR ACCI\u00D3N DE MEDIANO PLAZO ?", function (a) {
                    if (a) {
                        document.formulario.submit();
                        document.getElementById("btsubmit").value = "GUARDANDO...";
						document.getElementById("btsubmit").disabled = true;
						return true;
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });	
            }
          </script>
		<!--================================================== -->
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
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- Demo purpose only -->
		<script src="<?php echo base_url();?>/assets/js/demo.min.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url();?>/assets/js/app.min.js"></script>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		$(document).ready(function() {
			
			pageSetUp();
            $("#fun_id").change(function () {
				$("#fun_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/me/combo_fun_uni", { elegido: elegido,accion:'unidad' }, function(data){
						$("#uni_id").html(data);
					});     
				});
			}); 
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

			$("#ptdi1").change(function () {
				$("#ptdi1 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'ptdi_2' }, function(data){
						$("#ptdi2").html(data);
					});     
				});
			}); 

			$("#ptdi2").change(function () {
				$("#ptdi2 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'ptdi_3' }, function(data){
						$("#ptdi3").html(data);
					});     
				});
			}); 

			$("#ptdi3").change(function () {
				$("#ptdi3 option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'ptdi_4' }, function(data){
						$("#ptdi4").html(data);
					});     
				});
			}); 
			
			$("#clasificador1").change(function () {
				$("#clasificador1 option:selected").each(function () {
				elegido=$(this).val();
				$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'cl2' }, function(data){
				$("#clasificador2").html(data);
				});     
				});
			});

			$("#clasificador2").change(function () {
				$("#clasificador2 option:selected").each(function () {
				elegido=$(this).val();
				$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'cl3' }, function(data){
				$("#clasificador3").html(data);
				});     
				});
			});

			$("#finalidad1").change(function () {
				$("#finalidad1 option:selected").each(function () {
				elegido=$(this).val();
				$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'funcion' }, function(data){
				$("#fun").html(data);
				});     
				});
			});

			$("#fun").change(function () {
				$("#fun option:selected").each(function () {
				elegido=$(this).val();
				$.post("<?php echo base_url(); ?>index.php/admin/combo_clasificador", { elegido: elegido,accion:'clase_fn' }, function(data){
				$("#clase").html(data);
				});     
				});
			});
		})
		</script>
	</body>

</html>
