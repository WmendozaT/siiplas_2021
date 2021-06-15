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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!--para las alertas-->
    	<meta name="viewport" content="width=device-width">
		<style>
			table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
			overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
              color: #ffffff;
            }
            td{
              font-size: 10px;
            }
            #mdialTamanio{
              width: 35% !important;
            }
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="<?php echo base_url().'index.php/admin/dm/2/' ?>" title="MODIFICACIONES"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
					<li>Modificaciones</li><li>Modificaci&oacute;n Presupuestaria</li>
				</ol>
			</div>

			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
							<div class="well well-sm well-light">
								<h3>MODIFICACI&Oacute;N PRESUPUESTARIA <?php echo $this->session->userdata('gestion')?></h3>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
							<div class="well well-sm well-light">
								<button type="button" class="btn btn-primary" style="width:100%;" data-toggle="modal" data-target="#exampleModalCenter">
                                	SUBIR PRESUPUESTO MODIFICADO
                            	</button>
							</div>
						</article>
					</div>
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						</article>
						<article class="col-xs-12 col-sm-12 col-md-10 col-lg-8">
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
								<?php echo $lista_cites;?>
							</div>
						</article>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
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

		<div class="modal fade" id="exampleModalCenter" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <h2><center>SUBIR ARCHIVO PPTO MODIFICADO.CSV</center></h2>
                    
                        <div class="row">
                            <script src="<?php echo base_url(); ?>assets/file_nuevo/jquery.min.js"></script>
                              <form action="<?php echo site_url().'/modificaciones/cmod_presupuestario/importar_archivo_modpresupuestario'?>" method="post" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep" class="form-horizontal">
                                <fieldset>
		                            <div class="form-group">
		                                <label class="col-md-2 control-label">DA</label>
	                                    <div class="col-md-10">
	                                        <select class="form-control" id="dep_id" name="dep_id" title="Seleccione Direccion Administrativa">
	                                            <option value="">Seleccione DA</option>
	                                            <?php 
	                                                foreach($list_dep as $row){ ?>
	                                                    <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_cod'].' .-'.$row['dep_departamento']; ?></option>
	                                            <?php } ?>        
	                                        </select>
	                                    </div>
		                            </div>
		                            <div class="form-group">
		                                <label class="col-md-2 control-label">UE</label>
	                                    <div class="col-md-10">
	                                        <select class="form-control" id="ue_id" name="ue_id" title="Seleccione Unidad Ejecutora">       
	                                        </select>
	                                    </div>
		                            </div>
		                            <div class="form-group">
	                                    <div class="form-group">
	                                        <label class="col-md-2 control-label">RESOLUCI&Oacute;N </label>
	                                        <div class="col-md-10">
	                                            <input class="form-control" type="text" name="rd" id="rd" maxlength="100" placeholder="XXX-XXXX">
	                                        </div>
	                                    </div>
	                                </div>
		                            
		                        </fieldset>  
                                <hr>
                                <div class="form-group">
                                	ARCHIVO CSV
	                                <div class="input-group">
	                                  <span class="input-group-btn">
	                                    <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
	                                    <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
	                                    <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
	                                  </span>
	                                  <span class="form-control"></span>
	                                </div>
	                            </div>
                                
                                <div >
                                    <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR MODIFICACI&Oacute;N .CSV</button><br>
                                    <center><img id="loads" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                </div>
                              </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal_mod_ffsa" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog" id="mdialTamanio">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                	
	                    <div class="row">
	                    <form action="<?php echo site_url().'/mnt/valida_actividad'?>" id="form_nuevo" name="form_nuevo" class="form-horizontal" method="post">
                        <h2 class="alert alert-info"><div id="tit_rd" align="center"></div></h2>
	                	<input type="hidden" name="mp_id" id="mp_id">                         
                        <fieldset>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">REGIONAL</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="reg_id" name="reg_id" title="Seleccione Regional">
                                            <option value="">Seleccione Regional</option>
                                           	<?php 
                                                foreach($list_dep as $row){ ?>
                                                    <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
                                            <?php } ?>    
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">UNIDAD EJECUTORA</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="uejec_id" name="uejec_id" title="Seleccione Unidad Ejecutora">
                                            <option value="">No seleccionado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                                <div id="but">
                                    <div class="col-md-12">
                                       <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                       <button type="button" name="subir_form" id="subir_form" class="btn btn-info" >GENERAR REPORTE</button>
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
	    </div>


		<!-- END PAGE FOOTER -->
	</div>
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
		<script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
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
            $(function () {
                $(".mod_ffsa").on("click", function (e) {
                    mp_id = $(this).attr('name');
                    rd = $(this).attr('id');
                    document.getElementById("mp_id").value=mp_id;
                    $('#tit_rd').html(''+rd+'');
                    // =VALIDAR EL FORMULARIO DE MODIFICACION
                    $("#mod_ffsaenviar").on("click", function (e) {
                        var $validator = $("#form_modsa").validate({
                               rules: {
                                serv_id: { //// Servicio
                                required: true,
                                },
                                sub_cod: { //// Codigo
                                    required: true,
                                },
                                sub_desc: { //// Descripcion
                                    required: true,
                                },
                                tp: { //// Tipo
                                    required: true,
                                }
                            },
                            messages: {
                                serv_id: "<font color=red>SERVICIOL</font>",
                                sub_cod: "<font color=red>REGISTRE CÓDIGO SUB ACTIVIDAD</font>", 
                                sub_desc: "<font color=red>DESCRIPCIÓN SUB ACTIVIDAD</font>",
                                tp: "<font color=red>SELECCIONE TIPO</font>",                     
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
                        var $valid = $("#form_modsa").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {
                            alertify.confirm("MODIFICAR DATOS SUB - ACTIVIDAD ?", function (a) {
                                if (a) {
                                    document.getElementById("loadsa").style.display = 'block';
                                    document.getElementById('mod_ffsaenviar').disabled = true;
                                    document.forms['form_modsa'].submit();
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
        $(document).ready(function() {
            pageSetUp();
            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#ue_id").html(data);
                    });     
                });
            });
        })
        </script>
        <script type="text/javascript">
        $(function () {
            $("#subir_archivo").on("click", function () {
            	var $validator = $("#form_subir_sigep").validate({
                    rules: {
                        ue_id: { //// ue
                            required: true,
                        },
                        rd: { //// Resolucion
                            required: true,
                        },
                        archivo: { //// Archivo
                            required: true,
                        }
                    },
                    messages: {
                        ue_id: "<font color=red>SELECCIONE UNIDAD EJECUTORA</font>",
                        rd: "<font color=red>REGISTRE RESOLUCION</font>", 
                        archivo: "<font color=red>SELECCIONE ARCHIVO</font>",                    
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

                var $valid = $("#form_subir_sigep").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                	if(document.getElementById('archivo').value==''){
                        alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
                        return false;
                    }
                    alertify.confirm("SUBIR ARCHIVO ?", function (a) {
                        if (a) {
                            document.getElementById("loads").style.display = 'block';
                            document.getElementById('subir_archivo').disabled = true;
                            document.getElementById("subir_archivo").value = "Subiendo Archivo...";
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

                $(".del_ff").on("click", function (e) {
                    reset();
                    var mp_id = $(this).attr('name');
                    var request;

                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR LA MODIFICACIÓN PRESUPUESTARIA ?", function (a) {
                        if (a) { 
                            url = "<?php echo site_url("")?>/mod_ppto/delete_mod_ppto";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "mp_id="+mp_id
                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("EL CITE DE MODIFICACION SE ELIMINO CORRECTAMENTE.. ", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    })
                                } else {
                                    alertify.error("Error al Eliminar");
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
