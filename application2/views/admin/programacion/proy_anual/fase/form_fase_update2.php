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
		<style type="text/css">
        	hr {border: 0; height: 12px; box-shadow: inset 0 12px 12px 12px #1c7368;}
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÃš PRINCIPAL"><i
	                        class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <?php
						if($proyecto[0]['proy_estado']==1){ ?>
							<a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="PROGRAMACI&Oacute;N DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
							<?php
						}
						elseif ($proyecto[0]['proy_estado']==2){ ?>
							<a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa' ?>" title="PROGRAMACI&Oacute;N DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
							<?php }
						?>
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
					<li>Programaci&oacute;n de Proyectos de Inversi&oacute;n</li><li><a href="#" title="MIS PROYECTOS">T&eacute;cnico de Unidad Ejecutora</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/fase_etapa/'.$proyecto[0]['proy_id'] ?>" title="MIS FASES Y ETAPAS">Fases del Proyecto de Inversi&oacute;n (Presupuesto)</a></li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<?php
						if($proyecto[0]['tp_id']==1){ ?>
							<div class="row">
		                        <nav role="navigation" class="navbar navbar-default navbar-inverse">
		                            <div class="navbar-header">
		                                <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
		                                    <span class="sr-only">Toggle navigation</span>
		                                    <span class="icon-bar"></span>
		                                    <span class="icon-bar"></span>
		                                    <span class="icon-bar"></span>
		                                </button>
		                            </div>
		                     
		                            <div id="navbarCollapse" class="collapse navbar-collapse">
		                                <ul class="nav navbar-nav">
		                                    <li><a href="<?php echo base_url().'index.php/admin/proy/edit/'.$proyecto[0]['proy_id'].''?>" title="DATOS GENERALES DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;DATOS GENERALES&nbsp;</font></a></li>
		                                    <li><a href="<?php echo base_url().'index.php/admin/proy/proyecto_pi/'.$proyecto[0]['proy_id'].'' ?>" title="OBJETIVOS DEL PROYECTO DE INVERSI&Oacute;N"><font size="2">&nbsp;OBJETIVOS DEL PROYECTO&nbsp;</font></a></li>
		                                    <li class="active"><a href="<?php echo base_url().'index.php/admin/proy/fase_etapa/'.$proyecto[0]['proy_id'].'' ?>" title="LISTA DE FASES DEL PROYECTO DE INVERSI&Oacute;N"><i class="glyphicon glyphicon-ok"></i><font size="2">&nbsp;FASES DEL PROYECTO&nbsp;</font></a></li>
		                                </ul>
		                            </div>
		                        </nav>
		                    </div>
							<?php
						}
					?>
					
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
							<section id="widget-grid" class="well">
				                <div class="">
				                  	<h1> PROYECTO : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small>
				                	<h1> FECHA INICIO : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_inicial'])); ?></small> || FECHA FINAL : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_final'])); ?></small></h1>
				                	<?php
				                	if($proyecto[0]['tp_id']==1){ ?>
				                		<h1> FASE : <small><?php echo $fase_proyecto[0]['fase'] ?></small> || ETAPA : <small><?php echo $fase_proyecto[0]['etapa'] ?></small></h1>
				                		<h1> FECHA INICIO FASE : <small><?php echo date('d/m/Y',strtotime($fase_proyecto[0]['inicio'])) ?></small> || FECHA FINAL FASE : <small><?php echo date('d/m/Y',strtotime($fase_proyecto[0]['final'])) ?></small></h1>
				                		<?php
				                	}
				                	else{ ?>
				                		<h1> FECHA INICIO OPERACI&Oacute;N: <small><?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?></small> || FECHA FINAL OPERACI&Oacute;N: <small><?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?></small></h1>
				                		<?php
				                	}
				                	?>
				                </div>
				            </section>
	                    </article>
	                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
	                        <section id="widget-grid" class="well">
	                        	<?php
	                        		if($proyecto[0]['tp_id']==1){ ?>
	                        			<a href="<?php echo site_url("admin").'/proy/update_f/'.$fase_proyecto[0]['id']; ?>" class="btn btn-success" title="Lista de Fases del Proyecto" style="width:100%;">VOLVER A DATOS DE LA FASE</a>
	                        			<?php
	                        		}
	                        		else{ ?>
	                        			<a href="<?php echo site_url("admin").'/proy/list_proy'; ?>" class="btn btn-success" title="Lista de Operaciones" style="width:100%;">SALIR A LISTA DE ACTIVIDADES</a>
	                        			<?php
	                        		}
	                        	?>
	                        </section>
	                    </article>
	                </div>
				</section>
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<?php echo $presupuesto;?>
					</div>
					<!-- end row -->
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
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
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
		<script type="text/javascript">
		$(function () {
			$("#add_form3").on("click", function () {
		        var $valid = $("#form_nuevo3").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } else {
		            var ptto = document.getElementById('ptto').value;
		            var nro = document.getElementById('nro').value;
		            var suma=0;
	                for (var i = 1; i <= nro; i++) {
	                	if(document.getElementById('fgp_id'+i).value!=0){
	                		suma=parseFloat(suma)+parseFloat(document.getElementById('fgp_id'+i).value);
	                	}
	                }
	                
	                if(parseFloat(suma)<=parseFloat(ptto)){
		            	pfec_id = document.getElementById('pfec_id').value;
		            	proy_id = document.getElementById('proy_id').value;
		            	ptto_p = document.getElementById('ptto_p').value;
		            	ptto_e = document.getElementById('ptto_e').value;

		            	alertify.confirm("DESEA GUARDAR EL PRESUPUESTO DE LA FASE ?", function (a) {
		                    if (a) {
		                        document.getElementById("load3").style.display = 'block';
		                        document.getElementById('add_form3').disabled = true;
		                        document.forms['form_nuevo3'].submit();
		                    } else {
		                        alertify.error("OPCI\u00D3N CANCELADA");
		                    }
		                });	
	                }
	                else{
	                	alertify.error("ERROR EN LA SUMA PROGRAMADO POR GESTIONES SUPERA AL PTTO TOTAL");
	                }
		        }
		    });
	    });
		function suma_presupuesto(){ 
	    	ptotal = parseFloat($('[name="ptto"]').val());
	    	nro = parseFloat($('[name="nro"]').val());
	    	var suma=0;
	    	for (var i = 1; i <= nro; i++) {
            	suma=parseFloat(suma)+parseFloat($('[id="fgp_id'+i+'"]').val());
            }

	    	$('[name="ptto_p"]').val((suma).toFixed(2));
	    	$('[id="ptto_p"]').val((suma).toFixed(2));
	    	$('[name="saldo"]').val((ptotal-suma).toFixed(2));
	    	
	    	prog=parseFloat($('[name="ptto_p"]').val());
	    	saldo=parseFloat($('[name="saldo"]').val());

	    	if(saldo==0){
	    		$('#tit').html('<font color="#42F990"> SIN SALDO</font>');
	    	}
	    	if(saldo>0){
	    		$('#tit').html('<font color="red"> SALDO PENDIENTE</font>');
	    	}
	    	if(saldo<0){
	    		$('#tit').html('<font color="red"> SALDO SOBREGIRADO</font>');
	    	}

	    	if(isNaN(prog)){
	    		$('#but').slideUp();
	    	}
	    	else{
	    		if(prog<=ptotal){
	    			$('#but').slideDown();
	    		}
	    		else{
	    			$('#but').slideUp();
	    		}
	    	}
	    }

	    function suma_presupuesto_e(){ 
	    	ptotal = parseFloat($('[name="ptto"]').val());
	    	prog=parseFloat($('[name="ptto_p"]').val());
	    	nro = parseFloat($('[name="nro"]').val());
	    	var suma=0;
	    	for (var i = 1; i <= nro; i++) {
            	suma=parseFloat(suma)+parseFloat($('[id="fgp_id_e'+i+'"]').val());
            }

	    	$('[name="ptto_e"]').val((suma).toFixed(2));
	    	$('[id="ptto_e"]').val((suma).toFixed(2));
	    	ejecutado=parseFloat($('[name="ptto_e"]').val());

	    	if(isNaN(ejecutado)){
	    		$('#but').slideUp();
	    	}
	    	else{
	    		if(ejecutado<=prog){
	    			$('#but').slideDown();
	    		}
	    		else{
	    			$('#but').slideUp();
	    		}
	    	}
	    }
		</script>
	</body>
</html>
