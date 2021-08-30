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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
	    <meta name="viewport" content="width=device-width">
		<!--fin de stiloh-->
          <script>
		  	function abreVentana(PDF){
				var direccion;
				direccion = '' + PDF;
				window.open(direccion, "Reporte Alineación POA" , "width=800,height=650,scrollbars=SI") ;
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
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
		      width: 80% !important;
		    }
		    #mdialTamanio2{
		      width: 45% !important;
		    }
		    /*hr {border: 0; height: 12px; box-shadow: inset 0 12px 12px -12px #1c7368;}*/
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
	                <a href="<?php echo site_url("admin").'/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="#" title="PROGRAMACION DEL POA"> <span class="menu-item-parent">REPORTES</span></a>
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
					<li>Reportes</li><li>Prohramación POA</li><li>Resumen Actividades por Categoria Programatica</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				            <section id="widget-grid" class="well">
				                <div class="">
        							<h4><b>CARGO : </b><?php echo $this->session->userdata("cargo");?></h4>
        							<h4><b>REPONSABLE : </b><?php echo $this->session->userdata("user_name");?></h4>
				                </div>
				            </section>
				        </article>
                    </div>
                    <div class="row">
                        <article class="col-sm-12">
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2>REGIONALES</h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">RESUMEN DE ALINEACI&OacuteN POA</span></a>
                                        </li>
                                    </ul>

                                </header>
                                <!-- widget div-->
                                <div class="no-padding">
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        test
                                    </div>
                                    <!-- end widget edit box -->
                                    <div class="widget-body">
                                        <!-- content -->
                                        <div id="myTabContent" class="tab-content">
                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="LISTA DE ESTABLECIMIENTOS DE SALUD">
                                                <h2 class="alert alert-info" align="center">ALINEACI&Oacute;N DE ACTIVIDADES POR PROGRAMAS Y ACCIÓN DE CORTO PLAZO - <?php echo $this->session->userData('gestion') ?></h2>
                                                <div class="row">
                                                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
														<div>
															<div id="tabs">
																<ul>
																	<li>
																		<a href="#tabs-a" style="width:100%;">INSTITUCIONAL</a>
																	</li>
																	<li>
																		<a href="#tabs-b" style="width:100%;">OFICINA NACIONAL</a>
																	</li>
																	<li>
																		<a href="#tabs-c">LA PAZ</a>
																	</li>
																	<li>
																		<a href="#tabs-d">COCHABAMBA</a>
																	</li>
																	<li>
																		<a href="#tabs-e">CHUQUISACA</a>
																	</li>
																	<li>
																		<a href="#tabs-f">ORURO</a>
																	</li>
																	<li>
																		<a href="#tabs-g">POTOSI</a>
																	</li>
																	<li>
																		<a href="#tabs-h">TARIJA</a>
																	</li>
																	<li>
																		<a href="#tabs-i">SANTA CRUZ</a>
																	</li>
																	<li>
																		<a href="#tabs-j">BENI</a>
																	</li>
																	<li>
																		<a href="#tabs-k">PANDO</a>
																	</li>
																</ul>
																<div id="tabs-a">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/0'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/0'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>

																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL INSTITUCIONAL</center></b><hr>

					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                            <?php echo $institucional_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $institucional_og;?>
					                                                        </section>
					                                                    </div>
					                                                </div>
																</div>

																<div id="tabs-b">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/10'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/10'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL DE OFICINA NACIONAL</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $ofn_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $ofn_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>
																
																<div id="tabs-c">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/2'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/2'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL LA PAZ</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $lpz_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $lpz_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-d">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/3'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/3'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL COCHABAMBA</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $cbb_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $cbb_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>
																
																<div id="tabs-e">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/1'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/1'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL CHUQUISACA</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $ch_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $ch_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-f">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/4'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/4'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL ORURO</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $or_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $or_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-g">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/5'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/5'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL POTOSI</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $pot_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $pot_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-h">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/6'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/6'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL TARIJA</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $tja_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $tja_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-i">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/7'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/7'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL SANTA CRUZ</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $scz_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $scz_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-j">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/8'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/8'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL BENI</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $be_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $be_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

																<div id="tabs-k">
																	<div class="row">
																		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
																		</div>
																		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
																			<div class="btn-group">
																				<button class="btn btn-default">
																					<img src="<?php echo base_url(); ?>assets/Iconos/printer_empty.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA
																				</button>
																				<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
																					<span class="caret"></span>
																				</button>
																				<ul class="dropdown-menu">
																					<li>
																						<a href="javascript:abreVentana('<?php echo site_url("").'/rep/rep_alineacion_poa/9'?>');" style="width:100%;" title="IMPRIMIR ALINEACION POA">&nbsp;&nbsp;IMPRIMIR ALINEACI&Oacute;N POA</a>
																					</li>
																					<li>
																						<a href="<?php echo site_url("").'/rep/exportar_alineacion_poa/9'?>" style="width:100%;" title="EXPORTAR ALINEACION POA">&nbsp;&nbsp;EXPORTAR ALINEACI&Oacute;N POA</a>
																					</li>
																				</ul>
																			</div>
																		</div>
																		<hr><b><center>RESUMEN DE ACTIVIDADES A NIVEL REGIONAL PANDO</center></b><hr>
																		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
					                                                        <section id="widget-grid" class="">
					                                                        	<?php echo $pa_prog;?>
					                                                        </section>
					                                                    </div>
					                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					                                                        <section id="widget-grid">
					                                                            <?php echo $pa_og;?>	
					                                                        </section>
					                                                    </div>
				                                                    </div>
																</div>

															</div>
														</div>
													</article>
                                                </div>
                                            
                                            </div>
                                        </div>
                                        <!-- end content -->
                                    </div>
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
		<script type="text/javascript">
            /*------------ MODIFICAR REQUERIMIENTO ----------------*/
            $(function () {
                $(".enlace").on("click", function (e) {
                    dep_id = $(this).attr('name');
                    region = $(this).attr('id');
                    
                    $('#content1').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Establecimientos - <br>'+region+'</div>');
                    
                    var url = "<?php echo site_url("")?>/programacion/cunidad_organizacional/get_unidades";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "dep_id="+dep_id
                    });

                    request.done(function (response, textStatus, jqXHR) {

                    if (response.respuesta == 'correcto') {
                        //$('#content1').html(response.tabla);
                        $('#content1').fadeIn(1000).html(response.tabla);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS");
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
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
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
