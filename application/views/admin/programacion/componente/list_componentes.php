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
		<script>
			function abreVentana(PDF){             
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte Actividades" , "width=1000,height=650,scrollbars=SI") ;                                                           
            }                                          
          	</script>
		<style type="text/css">
			aside{background: #05678B;}
			table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
          		width: 55% !important;
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
		<!-- Left panel : Navigation area -->
		<aside id="left-panel">
			<!-- User info -->
			<div class="login-info">
				<span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
					<a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <span>
                            <i class="fa fa-user" aria-hidden="true"></i>  <?php echo $this->session->userdata("user_name");?>
                        </span>
					</a> 
				</span>
			</div>

			<?php echo $menu;?>
			<span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
		</aside>

		<!-- MAIN PANEL -->
		<div id="main" role="main">
			<!-- RIBBON -->
			<div id="ribbon">
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy';?>" title="POA">Programaci&oacute;n POA</a></li><li>Programaci&oacute;n F&iacute;sica</a></li><li>Mis Unidades</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<section id="widget-grid" class="well">
							<ul class="nav nav-pills">
							  <li class="active" title="<?php echo $proyecto[0]['act_id'];?>"><a href="#">MIS UNIDADES RESPONSABLES</a></li>
							  <li><a href="#">MIS ACTIVIDADES</a></li>
							</ul>
						</section>
					</article>
					<article class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                      	<section id="widget-grid" class="well">
                          <center>
                          	<div class="dropdown">
							  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:80%;" data-toggle="dropdown" aria-expanded="true">
							    OPCIONES
							    <span class="caret"></span>
							  </button>
							  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
							    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard' ?>">SALIR A MENU PRINCIPAL</a></li>
							    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">LISTA DE UNIDADES / ESTABLECIMIENTOS</a></li>
							    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_importar_ff" class="importar_ff" name="1" title="MODIFICAR REQUERIMIENTO" >SUBIR ACTIVIDAD.CSV</a></li>
							    
							  </ul>
							</div>
			                </center>
                      	</section>
					</article>
				</div>
				
					<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                      <section id="widget-grid" class="well" title="aper : <?php echo $proyecto[0]['aper_id'];?>">
                          <div class="">
                            <h1> <?php echo $proyecto[0]['establecimiento'];?> : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev']?></small></h1>
				            <p>
			                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">LISTA DE OBJETIVOS REGIONALES ALINEADOS</button>
			                </p>
			                <div class="collapse multi-collapse" id="multiCollapseExample1">
			                    <div class="card card-body">
			                      <?php echo $oregional;?>
			                    </div>
			                </div>
                          </div>

                      </section>
					</article>

					<section id="widget-grid" class="">
						<div class="row">
							<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<!-- Widget ID (each widget will need unique ID)-->
								<div class="jarviswidget jarviswidget-color-darken" >
									<header>
										<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
										<h2 class="font-md"><strong>MIS UNIDADES REPONSABLES</strong></h2>               
									</header>
									<div>
										<div class="widget-body no-padding">
												<?php echo $button;?>
											<div class="table-responsive">
												<?php echo $componente;?>
											</div>
										</div>
										<!-- end widget content -->
									</div>
									<!-- end widget div -->
								</div>
								<!-- end widget -->
							</article>
						<!-- WIDGET END -->
						</div>
					</section>

			</div>
			<!-- END MAIN CONTENT -->
		</div>


	<!-- SUBIR PLANTILLA DE MIGRACION -->
	<div class="modal fade" id="modal_importar_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" id="mdialTamanio">
        <div class="modal-content">
          <div class="modal-header">
              <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
              <h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <b>IMPORTAR ARCHIVO FORM 4.CSV</b></h2>
              <section id="widget-grid" class="">
                <div>
                  <h1> <?php echo $proyecto[0]['establecimiento'];?> : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev']?></small></h1>
                </div>
              </section>
              <div class="row">
                <form action="<?php echo site_url().'/programacion/cservicios/importar_operaciones_global'?>" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep" method="post">
                    <input type="hidden" name="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                    <input type="hidden" name="pfec_id" value="<?php echo $proyecto[0]['pfec_id'];?>">
                  <fieldset>
                    <div class="form-group">
                      <center>
                      	<img src="<?php echo base_url(); ?>assets/img/img_migracion/migracion_form4_unidad.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">
                      </center>
                      <hr>
                        <p class="alert alert-info">
                          <i class="fa fa-info"></i> Por favor guardar el archivo (Excel.xls) a extension (.csv) delimitado por (; "Punto y comas"). verificar el archivo .csv para su correcta importaci&oacute;n
                        </p>
                    </div>
                  </fieldset>  
                
                  <div class="form-group">
                    <b>SELECCIONAR ARCHIVO CSV</b>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
                        <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
                        <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
                      </span>
                      <span class="form-control"></span>
                    </div>
                </div>
                  
                  <div>
                      <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR ARCHIVO FORM 4.CSV</button><br>
                      <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                  </div>
                </form> 
              </div>
            </div>
        </div>
      </div>
    </div>


		<!-- END MAIN PANEL -->    
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
		<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<!--alertas -->
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script>
		function doSelectAlert(event,tp_id,com_id) {
		    var option = event.srcElement.children[event.srcElement.selectedIndex];
		    if (option.dataset.noAlert !== undefined) {
		        return;
		    }

		    alertify.confirm("CAMBIAR TIPO DE SUBACTIVIDAD ?", function (a) {
	            if (a) {
                	var url = "<?php echo site_url().'/programacion/cservicios/cambia_tp_sact'?>";
			        $.ajax({
			            type: "post",
			            url: url,
			            data:{com_id:com_id,tp_id:tp_id},
			                success: function (data) {
			                window.location.reload(true);
			            }
			        });
	            } else {
	                alertify.error("OPCI\u00D3N CANCELADA");
	            }
          	});
		}
		</script>
		<script type="text/javascript">
	      $(function () {
	        //SUBIR ARCHIVO
	        $("#subir_archivo").on("click", function () {
	          var $valid = $("#form_subir_sigep").valid();
	          if (!$valid) {
	              $validator.focusInvalid();
	          } else {
	            if(document.getElementById('archivo').value==''){
	              alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
	              return false;
	            }

	            alertify.confirm("REALMENTE DESEA SUBIR ESTE ARCHIVO?", function (a) {
	              if (a) {
	                  document.getElementById("load").style.display = 'block';
	                  document.getElementById('subir_archivo').disabled = true;
	                  document.forms['form_subir_sigep'].submit();
	              } else {
	                  alertify.error("OPCI\u00D3N CANCELADA");
	              }
	            });
	          }
	        });
	      });
	    </script>
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

		        /*----------- ELIMINAR OPERACIONES ---------------*/
		        $(".del_ff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var nro = $(this).attr('id');
		            var request;
		            alertify.confirm("ESTA SEGURO DE ELIMINAR "+nro+" ACTIVIDADES ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/prog/delete_operaciones_componente";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "com_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("LAS OPERACIONES SE ELIMINARON CORRECTAMENTE ", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL ELIMINAR OPERACIONES!!!", function (e) {
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
		                    alertify.error("OPCIÓN CANCELADA");
		                }
		            });
		            return false;
		        });

		        /*----------- DESHABILITAR SUB ACTIVIDAD ---------------*/
		        $(".neg_ff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var request;
		            alertify.confirm("ESTA SEGURO EN DESHABILITAR LA SUB ACTIVIDAD ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/prog/des_sactividad";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "com_id="+name

		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("LAS SUB ACTIVIDAD SE DESHABILITO CORRECTAMENTE ", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL DESHABILITAR !!!", function (e) {
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
		                    alertify.error("OPCIÓN CANCELADA");
		                }
		            });
		            return false;
		        });

		    });
		</script>
		<!-- ============================================================================== -->
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>
