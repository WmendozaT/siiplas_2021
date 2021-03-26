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
    	<script>
		  	function abreVentana(PDF){
				var direccion;
				direccion = '' + PDF;
				window.open(direccion, "Reporte de Cites Generados" , "width=800,height=650,scrollbars=SI") ;
			}                                                  
        </script>
    	<style>
    		.table1{
	          display: inline-block;
	          width:100%;
	          max-width:1550px;
	          overflow-x: scroll;
	        }
	        table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
			overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              font-size: 10px;
            }
            td{
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
		                <a href="<?php echo base_url().'index.php/admin/dm/2/' ?>" title="REPORTES"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
					<li>Reportes</li><li>Reportes Gerenciales</li><li>Resumen Poa - Pei</li><li>Regionales</li>
				</ol>
			</div>

			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="well well-sm well-light">
								<h3><?php echo $this->session->userdata('entidad')?> - FUNCIONAMIENTO<small></h3>
								<div id="tabs">
									<ul>
										<li>
											<a href="#tabs-k" style="width:100%;">NIVEL INSTITUCIONAL</a>
										</li>
										<li>
											<a href="#tabs-a">OFICINA NACIONAL</a>
										</li>
										<li>
											<a href="#tabs-b">LA PAZ</a>
										</li>
										<li>
											<a href="#tabs-c">COCHABAMBA</a>
										</li>
										<li>
											<a href="#tabs-d">CHUQUISACA</a>
										</li>
										<li>
											<a href="#tabs-e">ORURO</a>
										</li>
										<li>
											<a href="#tabs-f">POTOSI</a>
										</li>
										<li>
											<a href="#tabs-g">TARIJA</a>
										</li>
										<li>
											<a href="#tabs-h">SANTA CRUZ</a>
										</li>
										<li>
											<a href="#tabs-i">BENI</a>
										</li>
										<li>
											<a href="#tabs-j">PANDO</a>
										</li>
									</ul>
									<div id="tabs-k">
										<div class="row">
												<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oestrategicos';?>');" title="DETALLE ALINEACION-EVALUACION A NIVEL DE OBJETIVOS ESTRATEGICOS" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
												<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_institucional';?>');" title="DETALLE DE OPERACIONES A NIVEL INSTITUCIONAL" class="btn btn-primary" style="width:18%;">DETALLE INSTITUCIONAL</a>
											<hr>
											<?php echo $institucional;?>
										</div>
									</div>
									<div id="tabs-a">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken">
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>OFICINA NACIONAL</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/10';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/10';?>');" title="DETALLE ACCION DE CORTO PLAZO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/10';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic1" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:55px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:20%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $onacional;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-b">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL LA PAZ</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/2';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/2';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL - ACCIÓN DE CORTO PLAZO" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/2';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic2" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $lpz;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>
									
									<div id="tabs-c">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL COCHABAMBA</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/3';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/3';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/3';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic3" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $cba;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-d">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL CHUQUISACA</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/1';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/1';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/1';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic4" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $ch;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>
									
									<div id="tabs-e">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL ORURO</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/4';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/4';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/4';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic5" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $oru;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-f">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL POTOSI</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/5';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/5';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/5';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic6" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $pts;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-g">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL TARIJA</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/6';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/6';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/6';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic7" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $tj;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-h">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL SANTA CRUZ</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/7';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/7';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/7';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic8" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $scz;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-i">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL BENI</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/8';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/8';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/8';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic9" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $bn;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

									<div id="tabs-j">
										<div class="row">
											<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<div class="jarviswidget jarviswidget-color-darken" >
				                              <header>
				                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
				                                  <h2 class="font-md"><strong>REGIONAL PANDO</strong></h2>  
				                              </header>
												<div>
													<div class="widget-body no-padding">
														<hr>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_oe_regional/9';?>');" title="DETALLE POR OBJ. ESTRATEGICO A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE OBJETIVO ESTRATEGICO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional/9';?>');" title="DETALLE DE OPERACIONES A NIVEL REGIONAL" class="btn btn-primary" style="width:18%;">DETALLE ACCI&Oacute;N CORTO PLAZO</a>
															<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_detalle_regional_operaciones/9';?>');" title="DETALLE DE OPERACIONES A NIVEL DE ACTIVIDADES" class="btn btn-primary" style="width:18%;">DETALLE OPERACIONES</a>
														<table id="dt_basic10" class="table table-bordered" style="width:100%;">
															<thead>
																<tr style="height:65px;">
																	<th style="width:1%;" title="">#</th>
																	<th style="width:10%;" title="PROGRAMA">PROGRAMA <?php echo $this->session->userdata('gestion')?></th>
																	<th style="width:10%;" title="ACTIVIDAD">ACTIVIDAD</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 1">OE. 1</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 2">OE. 2</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 3">OE. 3</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 4">OE. 4</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 5">OE. 5</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 6">OE. 6</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 7">OE. 7</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 8">OE. 8</th>
																	<th style="width:5%;" title="OBJETIVO ESTRATEGICO 9">OE. 9</th>
																	<th style="width:5%;" title="TOTAL OPERACIONES ALINEADOS">TOTAL</th>
																	<th style="width:5%;" title=""></th>
																</tr>
															</thead>
															<?php echo $pnd;?>
														</table>
													</div>
													<!-- end widget content -->
												</div>
												<!-- end widget div -->
											</div>
											<!-- end widget -->
											</article>
										</div>
									</div>

								</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
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
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
		
		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				// menu
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>
