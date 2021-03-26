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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
	    <meta name="viewport" content="width=device-width">
		<style>
			.verticalText {
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			}

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
	          width: 70% !important;
	        }
	        #mdialTamanio2{
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
					<li>...</li><li>....</li><li>....</li><li>Objetivos de Gesti&oacute;n</li><li>Mis Objetivos Regional</li>
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
	                            	<h1>OBJETIVO ESTRAT&Eacute;GICO : <small><?php echo $obj_estrategico[0]['obj_codigo'].' .- '.$obj_estrategico[0]['obj_descripcion'];?></small></h1>
	                              	<h1>ACCIONES ESTRAT&Eacute;GICAS : <small><?php echo $accion_estrategica[0]['acc_codigo'].' .- '.$accion_estrategica[0]['acc_descripcion'];?></small></h1>
	                              	<h1>OBJETIVO DE GESTI&Oacute;N : <small><?php echo $ogestion[0]['og_codigo'].' .- '.$ogestion[0]['og_objetivo'];?></small></h1>
	                              	<h1>OBJETIVOS REGIONALES / OPERACI&Oacute;N - GESTI&Oacute;N <?php echo $this->session->userData('gestion') ?></h1>
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
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/me/rep_objetivos_gestion/';?>');">IMPRIMIR RESULTADOS</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/me/mis_ogestion'; ?>">VOLVER ATRAS</a></li>
                                </ul>
                              </div>
                              </center>
                            </section>
                        </article>
	                </div>
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
								<div id="tabs">
									<?php echo $regionales;?>
								</div>
							</div>
						</article>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
	</div>
	<!-- END MAIN PANEL -->
	<!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
  	<div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    	<div class="modal-dialog" id="mdialTamanio">
      		<div class="modal-content">
      	  		<div class="modal-header">
            		<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          		</div>
          		<div class="modal-body">
            	<h2 class="alert alert-info"><center>NUEVO OBJETIVO REGIONAL</center></h2>
            		<div class="row">
            			<div class="">
            				<div id="content1"></div>
            			</div>
            		</div>
            	</div>
          	</div>
      	</div>
  	</div>
  <!--  ========================================================= -->
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
		<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
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
		</script>
		<script type="text/javascript">
            /*--- OBTIENE FORMULARIO-OBJETIVO REGIONAL ---*/
            $(function () {
                $(".enlace").on("click", function (e) {
                    dep_id = $(this).attr('name');
                    og_id = $(this).attr('id');
                    
                    $('#content1').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Formulario</div>');
                    
                    var url = "<?php echo site_url("")?>/mestrategico/cobjetivo_regional/get_formulario";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "dep_id="+dep_id+"&og_id="+og_id
                    });

                    request.done(function (response, textStatus, jqXHR) {

                    if (response.respuesta == 'correcto') {
                        //$('#content1').html(response.tabla);
                        $('#content1').fadeIn(1000).html(response.tabla);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DEL REQUERIMIENTO");
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
        </script>
        <script type="text/javascript">
	  	  function verif_meta(){ 
	  	  	meta_oregional = parseFloat($('[name="meta_reg"]').val()); //// meta regional
	  	  	meta = parseFloat($('[name="meta"]').val()); //// meta 
	  	  	programado = parseFloat($('[name="total"]').val()); //// programado total
	  	  	if(meta!=0){
	  	  		if(meta<=meta_oregional){
		  	  		$('#atit').html('');
		            $('#but').slideDown();

		            if(programado!=meta){
		            	$('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DEL OBJETIVO REGIONAL</div></center>');
		            	$('#but').slideUp();
		        	}
		        	else{
		            	$('#atit').html('');
		            	$('#but').slideDown();
		        	}
		  	  	}
		  	  	else{
		  	  		$('#atit').html('<center><div class="alert alert-danger alert-block">LA META DEL OBJETIVO REGIONAL ES MAYOR A LA META DE GESTIÓN, VERIFIQUE DATOS...</div></center>');
		            $('#but').slideUp();
		  	  	}
	  	  	}
	  	  	else{
	  	  		$('#atit').html('<center><div class="alert alert-danger alert-block">LA META DEL OBJETIVO REGIONAL ES MAYOR A LA META DE GESTIÓN, VERIFIQUE DATOS...</div></center>');
		        $('#but').slideUp();
	  	  	}
	  	  	
	  	  }
	      
	      function suma_programado(){ 
	        sum=0;
	        linea = parseFloat($('[name="lbase"]').val()); //// linea base
	        nro = parseFloat($('[name="nro"]').val()); //// nro

	        for (var i = 1; i<=nro; i++) {
	            sum=parseFloat(sum)+parseFloat($('[id="uni'+i+'"]').val());
	        }

	        $('[name="total"]').val((sum+linea).toFixed(2));
	        $('[id="sum"]').val((sum+linea).toFixed(2));
	        programado = parseFloat($('[name="total"]').val()); //// programado total
	        meta = parseFloat($('[name="meta"]').val()); //// Meta 
	       
	        if(programado!=0){
          		if(programado!=meta){
	            	$('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DEL OBJETIVO REGIONAL</div></center>');
	            	$('#but').slideUp();
	        	}
	        	else{
	            	$('#atit').html('');
	            	$('#but').slideDown();
	        	}
          	}
          	else{
          		$('#but').slideUp();
          	}
	          
	      }
	    </script>
	    <script type="text/javascript">
	    $(function () {
	        $("#subir_fregional").on("click", function () {
	        	alert('hola')
	            var $validator = $("#form_nuevo").validate({
	                  rules: {
	                      oregional: { //// Objetivo regional
	                      	required: true,
	                      },
	                      producto: { //// producto
	                         required: true,
	                      },
	                      resultado: { //// resultado
	                          required: true,
	                      },
	                      indicador: { //// Indicador
	                          required: true,
	                      },
	                      verificacion: { //// verificacion
	                          required: true,
	                      },
	                      lbase: { //// linea base
	                          required: true,
	                      },
	                      meta: { //// meta
	                          required: true,
	                      }
	                  },
	                  messages: {
	                    oregional: "<font color=red>REGISTRE OBJETIVO REGIONAL</font>", 
	                    producto: "<font color=red>REGISTRE PRODUCTO</font>", 
	                    resultado: "<font color=red>REGISTRE RESULTADO</font>",
	                    indicador: "<font color=red>REGISTRE DETALLE DEL INDICADOR</font>",
	                    verificacion: "<font color=red>REGISTRE MEDIO DE VERIFICACI&Oacute;N</font>",
	                    lbase: "<font color=red>REGISTRE LINEA BASE</font>",
	                    meta: "<font color=red>REGISTRE META</font>",                     
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
	            	meta = parseFloat($('[name="meta"]').val()); //// meta 
	            	if(meta!=0 || meta!=''){
	            		alertify.confirm("GUARDAR OBJETIVO REGIONAL ?", function (a) {
		                    if (a) {
		                    //    document.getElementById("load").style.display = 'block';
		                        document.getElementById('subir_fregional').disabled = true;
		                        document.forms['form_nuevo'].submit();
		                    } else {
		                        alertify.error("OPCI\u00D3N CANCELADA");
		                    }
		                });
	            	}
	            }
	        });
	    });
	  </script>
		<script type="text/javascript">
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
