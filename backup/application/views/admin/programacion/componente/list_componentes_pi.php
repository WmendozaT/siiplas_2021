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
                window.open(direccion, "Reporte Operaciones" , "width=1000,height=650,scrollbars=SI") ;                                                           
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a' ?>" title="VOLVER A MIS PROYECTOS">Mi Proyecto</a></li><li>Mis Responsables</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">
				<div class="row">
					<article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<section id="widget-grid" class="well">
							<ul class="nav nav-pills">
							  <li class="active"><a href="#">MIS UNIDADES RESPONSABLES</a></li>
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
							    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a' ?>">LISTA DE PROYECTOS</a></li>
							    <li role="presentation"><a  href="<?php echo base_url(); ?>assets/video/plantilla_producto.xlsx" style="cursor: pointer;" download>DESCARGAR PLANTILLA OPERACIONES POR PROCESOS</a></li>
							  	<li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_importar"  title="IMPORTAR ARCHIVO DE OPERACIONES POR COMPONENTES DEL PROYECTO">IMPORTAR OPERACIONES POR COMPONENTES</a></li>
							  </ul>
							</div>
			                </center>
                      </section>
					</article>
				</div>

					<article class="col-xs-12 col-sm-12 col-md-2 col-lg-12">
                      <section id="widget-grid" class="well">
                          <div class="" >
                            <h1> <?php echo strtoupper($proyecto[0]['tipo']);?> : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></small></h1>
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
										<h2 class="font-md"><strong>UNIDADES RESPONSABLES</strong></h2>               
									</header>
									<div>
										<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success nuevo_ff" title="NUEVO REGISTRO - COMPONENTE" class="btn btn-success" style="width:15.5%;">NUEVA UNIDAD</a><br><br>
										<div class="widget-body no-padding">
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
							</form>
						<!-- WIDGET END -->
						</div>
				</section>

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		<!-- MODAL NUEVO REGISTRO DE UNIDAD RESPONSABLE   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url().'/prog/valida_comp'?>" id="form_nuevo" name="form_nuevo" class="form-horizontal" method="post">
                        <input  type="hidden" name="pfec_id" id="pfec" value="<?php echo $fase[0]['id'];?>">
                        <h2 class="alert alert-info"><center>UNIDAD RESPONSABLE (Agregar)</center></h2>                           
                        <fieldset>
                        	<div id="tit"></div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">UNIDAD RESPONSABLE</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="serv_id" name="serv_id" title="SELECCIONE UNIDAD RESPONSABLE">
                                          <option value="">Seleccione Unidad</option>
                                            <?php 
                                              foreach($unidad as $row){ ?>
                                                <option value="<?php echo $row['serv_id']; ?>"><?php echo $row['serv_cod'].' - '.$row['serv_descripcion'].''; ?></option>
                                                <?php   
                                              }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">DESCRIPCI&Oacute;N COMPONENTE</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="descripcion" id="descripcion" maxlength="200" rows="3" ></textarea>
                                    </div>
                                </div>
                            </div>
                            
                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                                <div id="but">
                                    <div class="col-md-12">
                                       <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                       <button type="button" name="subir_form" id="subir_form" class="btn btn-info" >GUARDAR UNIDAD</button>
                                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
        <!-- ============ Modal Modificar Componente ========= -->
        <div class="modal fade" id="modal_mod_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/prog/valida_update_comp'?>" id="form_mod" name="form_mod" class="form-horizontal" method="post">
                <input type="hidden" name="com_id" id="com_id">

                    <h2 class="alert alert-info"><center>COMPONENTE (Modificar)</center></h2>                           
                    <fieldset>
                    	<div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">UNIDAD RESPONSABLE</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="mserv_id" name="mserv_id" title="SELECCIONE UNIDAD RESPONSABLE">
                                      <option value="">Seleccione Unidad</option>
                                        <?php 
                                          foreach($unidad as $row){ ?>
                                            <option value="<?php echo $row['serv_id']; ?>"><?php echo $row['serv_cod'].' - '.$row['serv_descripcion'].''; ?></option>
                                            <?php   
                                          }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">DESCRIPCI&Oacute;N COMPONENTE</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="mcomponente" id="mcomponente" maxlength="200" rows="3" ></textarea>
                                </div>
                            </div>
                        </div>
                     
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                            <div id="mbut">
                                <div class="col-md-12">
                                   <button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
                                   <button type="button" name="mod_ffenviar" id="mod_ffenviar" class="btn btn-info" >MODIFICAR UNIDAD</button>
                                    <center><img id="loadd" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>
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
		<script type="text/javascript">
        $(function () {
            $("#subir_form").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                        rules: {
                            serv_id: { //// unidad
                            required: true,
                            },
                            descripcion: { //// descripcion Componente
                                required: true,
                            }
                        },
                        messages: {
                            serv_id: "<font color=red>SELECCIONE UNIDAD</font>", 
                            descripcion: "<font color=red>REGISTRE DESCRIPCIÓN DEL COMPONENTE</font>",                     
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

                        alertify.confirm("GUARDAR COMPONENTE ?", function (a) {
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

        <!-- MODIFICAR COMPONENTE -->
        <script type="text/javascript">
            $(function () {
                $(".mod_ff").on("click", function (e) {
                    com_id = $(this).attr('name');
                	var url = "<?php echo site_url() . '/programacion/cservicios/get_componente'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "com_id=" + com_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        document.getElementById("com_id").value = response.componente[0]['com_id'];
                        document.getElementById("mserv_id").value = response.componente[0]['serv_id'];
                        document.getElementById("mcomponente").value = response.componente[0]['com_componente'];
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DEL COMPONENTE");
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
                    $("#mod_ffenviar").on("click", function (e) {
                        var $validator = $("#form_mod").validate({
                               rules: {
                                com_id: { //// com
                                required: true,
                                },
                                mserv_id: { //// codigo
                                    required: true,
                                },
                                mcomponente: { //// descripcion
                                    required: true,
                                }
                            },
                            messages: {
                                com_id: "<font color=red>COMPONENTE ID</font>",
                                mser_id: "<font color=red>UNIDAD RESPONSABLE</font>",
                                mcomponente: "<font color=red>REGISTRE COMPONENTE</font>",                     
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

                            alertify.confirm("MODIFICAR DATOS UNIDAD RESPONSABLE ?", function (a) {
                                if (a) {
                                    document.getElementById("loadd").style.display = 'block';
                                    document.getElementById('mod_ffenviar').disabled = true;
                                    document.forms['form_mod'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>
		<script type="text/javascript">
			function verif_codigo(){ 
            pfec_id = $('[name="pfec_id"]').val(); /// pfec
            cod = $('[name="codigo"]').val(); /// Codigo

                var url = "<?php echo site_url() . '/programacion/cservicios/verif_codigo_componente'?>";
                $.ajax({
                    type:"post",
                    url:url,
                    data:{cod:cod,pfec_id:pfec_id},
                    success:function(datos){
                        
                        if(datos.trim() =='true'){
                        	$('#tit').html('');
                            $('#but').slideDown();
                            
                        }else{
                        	$('#tit').html('<center><div class="alert alert-danger alert-block">CODIGO REGISTRADO</div></center>');
                           	$('#but').slideUp();
                        }
                }});
            }

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
		            alertify.confirm("ESTA SEGURO DE ELIMINAR "+nro+" OPERACIONES ?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("")?>/prog/delete_operaciones_componente_pi";
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
			                    alert(response.respuesta)
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
		            alertify.confirm("ESTA SEGURO EN DESHABILITAR EL COMPONENTE ?", function (a) {
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
			                        alertify.alert("EL COMPONENTE SE DESHABILITO CORRECTAMENTE ", function (e) {
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
