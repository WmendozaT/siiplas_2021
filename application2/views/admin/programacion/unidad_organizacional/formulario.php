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
		<!--estiloh-->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
<!-- 		<link href="<?php echo base_url(); ?>assets/file/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    	<script src="<?php echo base_url(); ?>assets/file/jquery.min.js"></script>
    	<script src="<?php echo base_url(); ?>assets/file/js/fileinput.min.js" type="text/javascript"></script>  -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
	    <meta name="viewport" content="width=device-width">
		<!--fin de stiloh-->
          <script>
		  	function abreVentana(PDF){
				var direccion;
				direccion = '' + PDF;
				window.open(direccion, "REPORTE DATOS UNIDAD" , "width=800,height=650,scrollbars=SI") ;
			}                                                  
          </script>
			<style>
			table{font-size: 12px;
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
		      width: 80% !important;
		    }
		    #mdialTamanio2{
		      width: 45% !important;
		    }
		    
            #col{
              color: #1c7368;
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
		                <a href="#" title="PROGRAMACION DEL POA"> <span class="menu-item-parent">PROGRAMACI&Oacute;N</span></a>
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
					<li>Programaci&oacute;n POA</li><li>Unidad Organizacional</li><li>Mis Unidades, Centros</li><li>Datos Unidad</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
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
                                <a href="<?php echo base_url();?>index.php/prog/unidad" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR</a>
                            </section>
                        </article>
				   
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<div id="tabs">
									<ul>
										<li>
											<a href="#tabs-a"><b>I.- IDENTIFICACI&Oacute;N</b></a>
										</li>
										<li>
											<a href="#tabs-b"><b>II.- DATOS DEMOGRAFICOS</b></a>
										</li>
										<li>
											<a href="#tabs-c"><b>III.- RECURSOS F&Iacute;SICOS</b></a>
										</li>
										<li>
											<a href="#tabs-d"><b>IV.- REFERENCIA DE PACIENTES</b></a>
										</li>
										<li>
											<a href="#tabs-e"><b>V.- OFERTA DE SERVICIOS</b></a>
										</li>
										<li>
											<a href="#tabs-f"><b>VI.- GALERIA DE IMAGENES</b></a>
										</li>
										<?php
											if($this->session->userdata("tp_adm")==1){
												echo '	<li>
															<a href="#tabs-g"><b>VII.- SEGUIMIENTO POA</b></a>
														</li>';
											}
										?>
									</ul>
									<div id="tabs-a">
										<div class="row">
											<?php echo $identificacion;?>
										</div>
									</div>

									<div id="tabs-b">
										<div class="row">
											<?php echo $datos_demograficos;?>
										</div>
									</div>
									
									<div id="tabs-c">
										<div class="row">
											<article class="col-sm-1">
								            </article>
								            <article class="col-sm-10">
								            <?php 
							                  if($this->session->flashdata('success')){ ?>
							                    <div class="alert alert-success">
							                      	<?php echo $this->session->flashdata('success'); ?>
							                    </div>
							                <?php }
							                  elseif($this->session->flashdata('danger')){ ?>
							                      <div class="alert alert-danger">
							                        <?php echo $this->session->flashdata('danger'); ?>
							                      </div>
							                    <?php
							                  }
							                ?>
								            <div class="well">
								              <form action="<?php echo site_url("")?>/programacion/cunidad_organizacional/modificar_datos" id="form3" name="form3" class="smart-form" method="post">
								                  <input type="hidden" name="uni_id" id="uni_id" value="<?php echo $unidad[0]['act_id'];?>">
								                  <input type="hidden" name="tp" id="tp" value="3">
								                  <header><b>ANTECEDENTES DE LA INFRAESTRUCTURA</b></header>
								                  <fieldset>          
								                    <div class="row">
								                      <section class="col col-6">
								                        <label class="label">FECHA CREACI&Oacute;N</label>
								                          <div class="input-group">
								                            <input type="text" name="f_creacion" id="f_creacion" placeholder="Seleccione Fecha creacion" class="form-control datepicker" data-dateformat="dd/mm/yy" value="<?php echo date('d/m/Y',strtotime($unidad[0]['fecha_creacion'])); ?>" onKeyUp="this.value=formateafecha(this.value)">
								                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								                          </div>
								                      </section>
								                      <section class="col col-6">
								                        <label class="label">FECHA DE ULTIMO MANTENIMIENTO</label>
								                          <div class="input-group">
								                            <input type="text" name="f_mantenimiento" id="f_mantenimiento" placeholder="Seleccione Fecha ultimo mantenimiento" class="form-control datepicker" data-dateformat="dd/mm/yy" value="<?php echo date('d/m/Y',strtotime($unidad[0]['fecha_mantenimiento'])) ?>" onKeyUp="this.value=formateafecha(this.value);">
								                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								                          </div>
								                      </section>
								                    </div>
								                    <div class="row">
								                      <section class="col col-6">
								                        <label class="label">ESTADO ACTUAL</label>
								                        <select class="form-control" id="eu_id" name="eu_id" title="SELECCIONE ESTADO ACTUAL">
								                        <?php
								                           foreach($estado as $row){
								                            if($row['eu_id']==$unidad[0]['eu_id']){ ?>
								                            	<option value="<?php echo $row['eu_id'];?>" selected><?php echo $row['descripcion'];?></option>
								                              <?php
								                            }
								                            else{ ?>
								                            	<option value="<?php echo $row['eu_id'];?>"><?php echo $row['descripcion'];?></option>
								                              <?php
								                            }
								                          } 
								                        ?>
								                        </select>
								                      </section>
								                      <section class="col col-6">
								                        <label class="label">TIPO DE TENENCIA</label>
								                        <label class="input">
								                          <i class="icon-append fa fa-tag"></i>
								                          <input type="text" name="tp_tcia" id="tp_tcia" title="TIPO DE TENENCIA" value="<?php echo $unidad[0]['tp_tcia'];?>">
								                        </label>
								                      </section>
								                    </div>
								                  <fieldset>
								                  	<footer>
							                  		<?php
							                  			if($unidad[0]['te_id']!=0){ ?>
							                  				<button type="button" name="subir_form3" id="subir_form3" class="btn btn-info">GUARDAR DATOS</button>
							                      			<a href="'.base_url().'index.php/prog/unidad" title="SALIR" class="btn btn-default">CANCELAR</a>
							                  				<?php
							                  			}
							                  		?>
								                    </footer>
								              </form>
								            </div>
								            </article>
										</div>
									</div>

									<div id="tabs-d">
										<div class="row">
											<?php echo $referencia_pacientes;?>
										</div>
									</div>
									
									<div id="tabs-e">
										<div class="row">
											<?php echo $servicios;?>
										</div>
									</div>

									<div id="tabs-f">
										<div class="row">
								            <?php echo $galeria; ?>
										</div>
									</div>

									<?php
										if($this->session->userdata("tp_adm")==1){
										echo '<div id="tabs-g">
												<div class="row">
										            '.$clave_seguimiento.'
												</div>
											</div>';
										}
									?>
								</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

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

		<script src="<?php echo base_url(); ?>assets/file/fileinput.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/file/piexif.min.js"></script>


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
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		<!-- the fileinput plugin initialization -->
		<script type="text/javascript">
		function justNumbers(e){
            var keynum = window.event ? window.event.keyCode : e.which;
            if ((keynum == 8) || (keynum == 46))
            return true;
             
            return /\d/.test(String.fromCharCode(keynum));
        }

      	function comprueba_extension() {
	      	alertify.confirm("SUBIR ARCHIVO IMAGEN DEL ESTABLECIMIENTO ?", function (a) {
	          	if (a) {
	              //============= GUARDAR DESPUES DE LA VALIDACION ===============
	              formulario.submit();
	              document.getElementById("btsubmit").value = "SUBIENDO ARCHIVO...";
	              document.getElementById("btsubmit").disabled = true;
	              return true; 
	          	} else {
	              alertify.error("OPCI\u00D3N CANCELADA");
	          	}
	      	});
    	}


        $(function () {
            $("#subir_form1").on("click", function () {
                var $validator = $("#form1").validate({
                        rules: {
                            unidad: { //// unidad
                            required: true,
                            },
                            tp_ubi: { //// tipo ubicacion
                                required: true,
                            },
                           	prov_id: { //// provincia
                                required: true,
                            },
                            fono: { //// fono
                                required: true,
                            },
                            muni_id: { //// municipio
                                required: true,
                            },
                            direccion: { //// direccion
                                required: true,
                            }

                        },
                        messages: {
                            unidad: "<font color=red>REGISTRE UNIDAD / ESTABLECIMIENTO</font>", 
                            tp_ubi: "<font color=red>TIPO DE UBICACIÓN</font>", 
                         	fono: "<font color=red>REGISTRE FONO</font>", 
                            prov_id: "<font color=red>SELECCIONE PROVINCIA</font>",
                            muni_id: "<font color=red>SELECCIONE MUNICIPIO</font>",
                            direccion: "<font color=red>REGISTRE DIRECCIÓN</font>",                    
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
                	if(document.form1.tp_ubi.value==0){
                		alertify.error("SELECCIONE TIPO DE UBICACI&Oacute;N") 
					    document.form1.tp_ubi.focus() 
					    return 0;
                	}
                	if(document.form1.tp_est.value==0){
                		alertify.error("SELECCIONE TIPO DE ESTABLECIMIENTO") 
					    document.form1.tp_est.focus() 
					    return 0;
                	}

                	if(document.form1.muni_id.value!=''){
                		if(document.form1.comu_id.value==0){
                			if(document.form1.comunidad.value==0){
                				alertify.error("REGISTRE COMUNIDAD") 
					            document.form1.comunidad.focus() 
					            return 0;
                			}
                		}
		        	}

                    alertify.confirm("GUARDAR DATOS - IDENTIFICACIÓN ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form1').disabled = true;
                            document.forms['form1'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });


            $("#subir_form2").on("click", function () {
                var $validator = $("#form2").validate({
                        rules: {
                            ptotal_asig_est: { //// pob tot asig
                            	required: true,
                            },
                            num_fam_asig_est: { //// Num fam asig
                                required: true,
                            },
                            pob_asig_red: { //// Pob asig red
                                required: true,
                            },
                            
                           /* cie_mce1: { //// cie1
                                required: true,
                            },
                            diag_mce1: { //// Diagnostico 1
                                required: true,
                            },
                            fre_mce1: { //// Frecuencia 1
                                required: true,
                            },

                            cie_mce2: { //// cie2
                                required: true,
                            },
                            diag_mce2: { //// Diagnostico 2
                                required: true,
                            },
                            fre_mce2: { //// Frecuencia 2
                                required: true,
                            },

                            cie_mce3: { //// cie3
                                required: true,
                            },
                            diag_mce3: { //// Diagnostico 3
                                required: true,
                            },
                            fre_mce3: { //// Frecuencia 3
                                required: true,
                            },*/

                           /* cie_mce4: { //// cie4
                                required: true,
                            },
                            diag_mce4: { //// Diagnostico 4
                                required: true,
                            },
                            fre_mce4: { //// Frecuencia 4
                                required: true,
                            },

                            cie_mce5: { //// cie5
                                required: true,
                            },
                            diag_mce5: { //// Diagnostico 5
                                required: true,
                            },
                            fre_mce5: { //// Frecuencia 5
                                required: true,
                            },

                            cie_mce6: { //// cie6
                                required: true,
                            },
                            diag_mce6: { //// Diagnostico 6
                                required: true,
                            },
                            fre_mce6: { //// Frecuencia 6
                                required: true,
                            },

                            cie_mce7: { //// cie7
                                required: true,
                            },
                            diag_mce7: { //// Diagnostico 7
                                required: true,
                            },
                            fre_mce7: { //// Frecuencia 7
                                required: true,
                            },

                            cie_mce8: { //// cie8
                                required: true,
                            },
                            diag_mce8: { //// Diagnostico 8
                                required: true,
                            },
                            fre_mce8: { //// Frecuencia 8
                                required: true,
                            },*/

                            /*------------*/
                            /*cie_mue1: { //// cie1
                                required: true,
                            },
                            diag_mue1: { //// Diagnostico 1
                                required: true,
                            },
                            fre_mue1: { //// Frecuencia 1
                                required: true,
                            },

                            cie_mue2: { //// cie2
                                required: true,
                            },
                            diag_mue2: { //// Diagnostico 2
                                required: true,
                            },
                            fre_mue2: { //// Frecuencia 2
                                required: true,
                            }*/
                          /*  ,

                            cie_mue3: { //// cie3
                                required: true,
                            },
                            diag_mue3: { //// Diagnostico 3
                                required: true,
                            },
                            fre_mue3: { //// Frecuencia 3
                                required: true,
                            },

                            cie_mue4: { //// cie4
                                required: true,
                            },
                            diag_mue4: { //// Diagnostico 4
                                required: true,
                            },
                            fre_mue4: { //// Frecuencia 4
                                required: true,
                            },

                            cie_mue5: { //// cie5
                                required: true,
                            },
                            diag_mue5: { //// Diagnostico 5
                                required: true,
                            },
                            fre_mue5: { //// Frecuencia 5
                                required: true,
                            }*/

                        },
                        messages: {
                            ptotal_asig_est: "<font color=red>REGISTRE POBLACIÓN TOTAL ASIGNADA</font>", 
                            num_fam_asig_est: "<font color=red>REGISTRE NÚMERO DE FAMILIAS</font>", 
                            pob_asig_red: "<font color=red>REGISTRE POBLACIÓN ASIGNADA A LA RED</font>",  

                            /*cie_mce1: "<font color=red>REGISTRE CIE-10/1</font>",  
                            diag_mce1: "<font color=red>REGISTRE DIAGNOSTICO 1 </font>",  
                            fre_mce1: "<font color=red>REGISTRE FRECUENCIA 1</font>", 

                            cie_mce2: "<font color=red>REGISTRE CIE-10/2</font>",  
                            diag_mce2: "<font color=red>REGISTRE DIAGNOSTICO 2 </font>",  
                            fre_mce2: "<font color=red>REGISTRE FRECUENCIA 2</font>", 

                            cie_mce3: "<font color=red>REGISTRE CIE-10/3</font>",  
                            diag_mce3: "<font color=red>REGISTRE DIAGNOSTICO 3 </font>",  
                            fre_mce3: "<font color=red>REGISTRE FRECUENCIA 3</font>", */

                          /*  cie_mce4: "<font color=red>REGISTRE CIE-10/4</font>",  
                            diag_mce4: "<font color=red>REGISTRE DIAGNOSTICO 4 </font>",  
                            fre_mce4: "<font color=red>REGISTRE FRECUENCIA 4</font>", 

                            cie_mce5: "<font color=red>REGISTRE CIE-10/5</font>",  
                            diag_mce5: "<font color=red>REGISTRE DIAGNOSTICO 5 </font>",  
                            fre_mce5: "<font color=red>REGISTRE FRECUENCIA 5</font>", 

                            cie_mce6: "<font color=red>REGISTRE CIE-10/6</font>",  
                            diag_mce6: "<font color=red>REGISTRE DIAGNOSTICO 6 </font>",  
                            fre_mce6: "<font color=red>REGISTRE FRECUENCIA 6</font>", 

                            cie_mce7: "<font color=red>REGISTRE CIE-10/7</font>",  
                            diag_mce7: "<font color=red>REGISTRE DIAGNOSTICO 7 </font>",  
                            fre_mce7: "<font color=red>REGISTRE FRECUENCIA 7</font>", 

                            cie_mce8: "<font color=red>REGISTRE CIE-10/8</font>",  
                            diag_mce8: "<font color=red>REGISTRE DIAGNOSTICO 8 </font>",  
                            fre_mce8: "<font color=red>REGISTRE FRECUENCIA 8</font>",*/


                           /* cie_mue1: "<font color=red>REGISTRE CIE-10/1</font>",  
                            diag_mue1: "<font color=red>REGISTRE DIAGNOSTICO 1 </font>",  
                            fre_mue1: "<font color=red>REGISTRE FRECUENCIA 1</font>", 

                            cie_mue2: "<font color=red>REGISTRE CIE-10/2</font>",  
                            diag_mue2: "<font color=red>REGISTRE DIAGNOSTICO 2 </font>",  
                            fre_mue2: "<font color=red>REGISTRE FRECUENCIA 2</font>", */
/*
                            cie_mue3: "<font color=red>REGISTRE CIE-10/3</font>",  
                            diag_mue3: "<font color=red>REGISTRE DIAGNOSTICO 3 </font>",  
                            fre_mue3: "<font color=red>REGISTRE FRECUENCIA 3</font>", 

                            cie_mue4: "<font color=red>REGISTRE CIE-10/4</font>",  
                            diag_mue4: "<font color=red>REGISTRE DIAGNOSTICO 4 </font>",  
                            fre_mue4: "<font color=red>REGISTRE FRECUENCIA 4</font>", 

                            cie_mue5: "<font color=red>REGISTRE CIE-10/5</font>",  
                            diag_mue5: "<font color=red>REGISTRE DIAGNOSTICO 5 </font>",  
                            fre_mue5: "<font color=red>REGISTRE FRECUENCIA 5</font>",  */              
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

                var $valid = $("#form2").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    alertify.confirm("GUARDAR DATOS - DATOS DEMOGRAFICOS ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form2').disabled = true;
                            document.forms['form2'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });

            $("#subir_form3").on("click", function () {
                var $validator = $("#form3").validate({
                    rules: {
                        f_creacion: { //// Fecha creacion
                        	required: true,
                        },
                        f_mantenimiento: { //// Fecha mantenimiento
                            required: true,
                        },
                        eu_id: { //// estado de unidad
                            required: true,
                        }
                    },
                    messages: {
                        f_creacion: "<font color=red>FECHA DE CREACI&Oacute;N</font>", 
                        f_mantenimiento: "<font color=red>FECHA DE ULTIMO MANTENIMIENTO</font>", 
                        eu_id: "<font color=red>ESTADO ACTUAL</font>",                  
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

                var $valid = $("#form3").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                	var fecha_inicial = document.form3.f_creacion.value.split("/")  //fecha inicial
	        		var fecha_final = document.form3.f_mantenimiento.value.split("/")  /*fecha final*/

	        		if(parseInt(fecha_final[2])<parseInt(fecha_inicial[2])) {
			            alertify.error('Error!!  Verifique las Fechas ....')
			            document.form3.f_final.focus() 
			            return 0;
			        }

                    alertify.confirm("GUARDAR DATOS - RECURSOS F&Iacute;SICOS ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form3').disabled = true;
                            document.forms['form3'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });


            $("#subir_form4").on("click", function () {
                var $validator = $("#form4").validate({
                    rules: {
                        distancia: { //// Distancia
                            required: true,
                        },
                        tiempo_horas: { //// Tiempo en horas
                            required: true,
                        },
                        medio_transporte: { //// medio_transporte
                            required: true,
                        }
                    },
                    messages: {
                        distancia: "<font color=red>DISTANCIA EN KILOMETROS</font>", 
                        tiempo_horas: "<font color=red>TIEMPO EN HORAS</font>", 
                        medio_transporte: "<font color=red>MEDIO DE TRANSPORTE</font>",                  
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

                var $valid = $("#form4").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("GUARDAR DATOS - REFERENCIA DE PACIENTES ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form4').disabled = true;
                            document.forms['form4'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });

            $("#subir_form7").on("click", function () {
                var $validator = $("#form7").validate({
                    rules: {
                        usuario: { //// Distancia
                            required: true,
                        },
                        clave: { //// Tiempo en horas
                            required: true,
                        }
                    },
                    messages: {
                        usuario: "<font color=red>REGISTRAR USUARIO ESTABLECIMIENTO</font>", 
                        clave: "<font color=red>CLAVE</font>",                 
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

                var $valid = $("#form7").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("GUARDAR DATOS INGRESO ESTABLECIMIENTO ?", function (a) {
                        if (a) {
                            document.getElementById('subir_form7').disabled = true;
                            document.forms['form7'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>
		<script type="text/javascript">
		$( function() {
		    $("#comu_id").change( function() {
		        if ($(this).val() === "0") {
		        	$("#comunidad").prop("disabled", false);
		        } else {
		            $("#comunidad").prop("disabled", true);
		        }
		    });
		});

		$(document).ready(function() {
			pageSetUp();
            $("#prov_id").change(function () {
				$("#prov_id option:selected").each(function () {
					elegido=$(this).val();
					$.post(
						"<?php echo base_url(); ?>index.php/prog/combo_ubicacion", { elegido: elegido,accion:'prov'}, function(data){
						$("#muni_id").html(data);
					});   
				});
			});
			$("#muni_id").change(function () {
				$("#muni_id option:selected").each(function () {
					elegido=$(this).val();
					$.post("<?php echo base_url();?>index.php/prog/combo_ubicacion", { elegido: elegido,accion:'muni' }, function(data){
						$("#comu_id").html(data);
					});     
				});
			});
		})
		</script>
		<script type="text/javascript">
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
	</body>
</html>
