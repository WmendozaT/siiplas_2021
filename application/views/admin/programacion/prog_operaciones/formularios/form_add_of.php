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
        <style type="text/css">
        	hr {border: 2; height: 12px; box-shadow: inset 0 12px 12px -12px;}
        </style>
        <style>
        	table{font-size: 10px;
            width: 100%;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
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
		                <a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="PROGRAMACION DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy'?>" title="Programacion POA">Programaci&oacute;n POA</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Datos Generales</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				            <section id="widget-grid" class="well">
				                <div class="">
        							<h4><b>CARGO : </b><?php echo $this->session->userdata("cargo");?></h4>
        							<h4><b>REPONSABLE : </b><?php echo $this->session->userdata("user_name");?></h4>
				                </div>
				            </section>
				        </article>
				        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <section id="widget-grid" class="well">
                                <a href="<?php echo base_url();?>index.php/admin/proy/list_proy" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR A LISTA POAS</a>
                            </section>
                        </article>
				   
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<div class="row">
									<?php echo $form;?>
								</div>
							</div>
						</article>
						<!-- WIDGET END -->
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
		<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
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
        <script src="<?php echo base_url();?>/assets/form/js/jquery.backstretch.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>

        <script type="text/javascript">
        $(function () {
            $("#subir_form1").on("click", function () {
                var $validator = $("#form1").validate({
                        rules: {
                            act_id: { //// act id
                            required: true,
                            }
                        },
                        messages: {
                            unidad: "<font color=red>SELECCIONE UNIDAD / ESTABLECIMIENTO</font>",                  
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

                var $valid = $("#form1").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                	alertify.confirm("GUARDAR DATOS ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form1').disabled = true;
                            document.forms['form1'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });    
            $(document).ready(function () {
        	$("#act_id").change(function () {  
            	var act_id = $(this).val();
            	
            	if(act_id!=0 || act_id!=""){
            		var url = "<?php echo site_url("")?>/programacion/proyecto/get_actividad";
	                var request;
	                if (request) {
	                    request.abort();
	                }
	                request = $.ajax({
	                    url: url,
	                    type: "POST",
	                    dataType: 'json',
	                    data: "act_id=" + act_id
	                });

	                request.done(function (response, textStatus, jqXHR) {
	                	alert(response.respuesta)
	                if (response.respuesta == 'correcto') {
	                	document.getElementById("uni_id").value = response.actividad[0]['act_id'];
	                	
	                	if(response.actividad[0]['act_cod'].length==3 || response.actividad[0]['act_cod'].length==4){
	                		var cad='';
	                    }
	                    if(response.actividad[0]['act_cod'].length==2){
	                    	var cad='0';
	                    }
	                    if(response.actividad[0]['act_cod'].length==1){
	                    	var cad='00';
	                    }


	                    cod = cad.concat(response.actividad[0]['act_cod']);
	                    $('[name="act"]').val(cod);
		                    var url = "<?php echo site_url("admin")?>/proy/verif";
							$.ajax({
								type:"post",
								url:url,
								data:{prog:response.actividad[0]['aper_programa'],proy:response.actividad[0]['aper_proyecto'],act:cod},
								success:function(datos){
									if(datos.trim() =='true'){
										$('#programa').html('<center><div class="alert alert-warning alert-block"><h1>'+response.actividad[0]['aper_programa']+''+response.actividad[0]['aper_proyecto']+''+cod+' - '+response.actividad[0]['tipo']+' '+response.actividad[0]['act_descripcion']+' - '+response.actividad[0]['abrev']+'</h1></div></center>');
					                	document.getElementById("prog").value = response.actividad[0]['aper_programa'];
					                    $('#oregional').html(response.oregional);
					                    $('#servicios').html(response.servicios);
					                    $('#but').slideDown();
									}
									else{
										$('#programa').html('<center><div class="alert alert-danger alert-block">'+response.actividad[0]['aper_programa']+''+response.actividad[0]['aper_proyecto']+''+cod+' - '+response.actividad[0]['tipo']+''+response.actividad[0]['act_descripcion']+' (ESTABLECIMIENTO YA REGISTRADO !!!)</div></center>');
										$('#oregional').html('');
					                    $('#servicios').html('');
										$('#but').slideUp();
									}
							}});
	                }
	                else{
	                    alertify.error("ERROR AL RECUPERAR DATOS DE LA UNIDAD / ESTABLECIMIENTO");
	                }

	                });
            	}
            	else{
            		$('#programa').html('<center><div class="alert alert-danger alert-block">SELECCIONE UNIDAD / ESTABLECIMIENTO</div></center>');
            		$('#oregional').html('');
					$('#servicios').html('');
					$('#but').slideUp();
            	}
            	
            });
        });
        </script>
		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				$("#reg_id").change(function () {
					$("#reg_id option:selected").each(function () {
						reg_id=$(this).val();
						$("#act_id").html('Seleccione');
						$('#oregional').html('');
					    $('#servicios').html('');
					    $('#programa').html('');
						$('#uni').slideUp();
						$('#but').slideUp();

						if(reg_id!=0 || reg_id!=""){
							var url = "<?php echo site_url("")?>/programacion/proyecto/como_unidad";
			                var request;
			                if (request) {
			                    request.abort();
			                }
			                request = $.ajax({
			                    url: url,
			                    type: "POST",
			                    dataType: 'json',
			                    data: "reg_id=" + reg_id
			                });

			                request.done(function (response, textStatus, jqXHR) {
			                if (response.respuesta == 'correcto') {
			                	$('#uni').slideDown();
			                	$("#act_id").html(response.unidades);
			                }
			                else{
			                    alertify.error("ERROR AL RECUPERAR DATOS DE LA UNIDAD / ESTABLECIMIENTO");
			                }

			                }); 
						}
						else{
							$("#act_id").html('');
							$('#oregional').html('');
					        $('#servicios').html('');
							$('#uni').slideUp();
							$('#but').slideUp();
						}
						    
					});
				});
			})
		</script>
	</body>
</html>
