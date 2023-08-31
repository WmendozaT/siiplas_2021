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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i
	                        class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <?php
						if($proyecto[0]['proy_estado']==1){ ?>
							<a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="MIS PROYECTOS"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
							<?php
						}
						elseif ($proyecto[0]['proy_estado']==2){ ?>
							<a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa' ?>" title="MIS PROYECTOS"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<?php
					if($proyecto[0]['proy_estado']==1){ ?>
						<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="MIS PROYECTOS">Programaci&oacute;n de Operaciones</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">T&eacute;cnico de Unidad Ejecutora</a></li><li>Fases del Proyecto de Inversi&oacute;n</li>
						<?php
					}
					elseif ($proyecto[0]['proy_estado']==2){ ?>
						<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa' ?>" title="MIS PROYECTOS">Programaci&oacute;n de Operaciones</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">T&eacute;cnico Analista POA</a></li><li>Fases del Proyecto de Inversi&oacute;n</li>
						<?php }
					?>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
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
                                    <li class="active"><a href="#" title="FASES DEL PROYECTO DE INVERSI&Oacute;N"><i class="glyphicon glyphicon-ok"></i><font size="2">&nbsp;FASES DEL PROYECTO&nbsp;</font></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
					<!-- row -->
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<section id="widget-grid" class="well">
				                <div class="">
				                  	<h1> PROYECTO : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small>
				                	<h1> FECHA INICIO : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_inicial'])); ?></small> || FECHA FINAL : <small><?php echo date('d-m-Y',strtotime($proyecto[0]['f_final'])); ?></small></h1>
				                </div>
				            </section>
	                    </article>
						<!-- NEW WIDGET START -->
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
			                      </div>
			                      <?php }
			                ?>

								<div class="jarviswidget jarviswidget-color-darken" >
									<header>
										<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
										<h2 class="font-md"><strong>FASES DEL PROYECTO</strong></h2>				
									</header>
										<!-- widget content -->
									<div class="widget-body">
									<div class="table-responsive">
										<a href='<?php echo site_url("admin").'/proy/newfase/'.$proyecto[0]['proy_id'];?>' title="REGISTRAR NUEVA FASE" class="btn btn-primary">NUEVO REGISTRO</a><hr>
										<table class="table table-bordered">
											<thead>			                
												<tr>
													<th style="width:1%;"></th>
													<th style="width:10%;">FASE</th>
													<th style="width:10%;">ETAPA</th>
													<th style="width:15%;">DESCRIPCI&Oacute;N FASE</th>
													<th style="width:13%;">UNIDAD EJECUTORA</th>
													<th style="width:10%;">FECHA INICIO</th>
													<th style="width:10%;">FECHA CONCLUSI&Oacute;N</th>
													<th style="width:10%;">EJECUCI&Oacute;N</th>
													<th style="width:10%;">ANUAL/PLURIANUAL</th>
													<th style="width:10%;">GESTI&Oacute;N</th>
													<th style="width:1%;">EDITAR</th>
													<th style="width:1%;">ELIMINAR</th>
													<th style="width:1%;">FASE</th>
													<th style="width:1%;">PROG.FIS.</th>
												</tr>
											</thead>
											<tbody>
												<?php $num=1;
			                                      foreach($fases as $row){
			                                       echo '<tr>';
			                                        echo '<td><h1>'.$num.'</h1></td>';
			                                        echo '<td><font size="1">'.$row['fase'].'</font></td>';
			                                        echo '<td><font size="1">'.$row['etapa'].'</font></td>';
			                                        echo '<td><font size="1">'.$row['descripcion'].'</font></td>';
			                                        echo '<td><font size="1">'.$row['uni_unidad'].'</font></td>';
			                                        echo '<td><font size="1">'.date('d-m-Y',strtotime($row['inicio'])).'</font></td>';
			                                        echo '<td><font size="1">'.date('d-m-Y',strtotime($row['final'])).'</font></td>';
			                                        echo '<td><font size="1">'.$row['ejec'].'</font></td>'; 
			                                       	echo '<td><font size="1">'.$this->model_faseetapa->calcula_ap($row['pfec_fecha_inicio'],$row['pfec_fecha_fin']).'</font></td>';
			                                       	echo '<td><font size="1"><b>'.$row['aper_gestion'].'</b></font></td>'; 
			                                        ?>
			                                        <td align="center">
			                                           	<a href='<?php echo site_url("admin").'/proy/update_f/'.$row['id']; ?>' title="MODIFICAR FASE <?php echo strtoupper($row['fase']);?>" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a>
			                                        </td>
			                                        <td align="center">
			                                           	<a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR FASE <?php echo strtoupper($row['fase']);?>" name="<?php echo $row['id']; ?>"><img src="<?php echo base_url(); ?>assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/></a>
			                                        </td>
			                                        <?php 
			                                        if($row['pfec_estado']=='0'){ ?>
			                                           <td align="center">
			                                           		<a href='#' class="btn btn-default fase" name="<?php echo $row['id']?>" id="<?php echo $row['proy_id']?>" title="FASE APAGADO"><img src="<?php echo base_url(); ?>assets/Iconos/lightbulb_off.png" WIDTH="30" HEIGHT="30"/></a>
			                                           </td>
			                                           <td align="center">
			                                           		
			                                           </td>
			                                        <?php }
			                                        elseif ($row['pfec_estado']=='1'){ ?>
			                                           <td align="center">
			                                           		<a href='#' class="btn btn-default fase" name="<?php echo $row['id']?>" id="<?php echo $row['proy_id']?>" title="FASE ENCENDIDO PARA TRABAJAR LA PROGRAMACION FISICA"><img src="<?php echo base_url(); ?>assets/Iconos/lightbulb.png" WIDTH="30" HEIGHT="30"/></a>
			                                           </td>
			                                           <td align="center">
			                                           		<a href='<?php echo site_url("").'/prog/list_serv/'.$proyecto[0]['proy_id']; ?>' title="PROGRAMACION FISICA POA" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/ifinal/bien.png" WIDTH="30" HEIGHT="30"/></a>
			                                           </td>
			                                        <?php }
			                                      echo '</tr>';
			                                      $num=$num+1;
			                                      } ?>
											</tbody>
										</table>
										</div>
									</div>
								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->
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
		<!-- END PAGE FOOTER -->
		<!--================================================== -->
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
		<script>
		$(".fase").click(function(){
           var fc = $(this).attr('name'); 
           var proy = $(this).attr('id'); 
           var request; 
           $.ajax({
           type: "POST",
           url: "<?php echo site_url("admin")?>/proy/off",
           data:{id_f:fc,id_p:proy},
           dataType: 'json',
           success:function(datos){                    
                if (datos==true) {
                    alertify.success("LA FASE YA SE ENCUENTRA ENCENDIDO")
                } else { 
                    window.location.reload(true);                     
                }
           }
           });

           return false;
           })
		</script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
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
		<!--================= ELIMINACION DE LAS METAS =========================================-->
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

		        // =====================================================================
		        $(".del_ff").on("click", function (e) {
		            reset();
		            var name = $(this).attr('name');
		            var request;
		            // confirm dialog
		            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
		                if (a) { 
		                    url = "<?php echo site_url("admin")?>/proy/delete_fase";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        data: "pfec_id=" + name

		                    });
		                    window.location.reload(true);
		                    request.done(function (response, textStatus, jqXHR) {
		                        $('#tr' + response).html("");
		                    });
		                    request.fail(function (jqXHR, textStatus, thrown) {
		                        console.log("ERROR: " + textStatus);
		                    });
		                    request.always(function () {
		                        //console.log("termino la ejecuicion de ajax");
		                    });

		                    e.preventDefault();
		                    alertify.success("Se eliminó el registro correctamente");

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
