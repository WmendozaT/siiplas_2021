<!DOCTYPE html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->

	<title> SIPLAS </title>
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
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
	<!--///////////////css-->
	<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
	<!--//////////////fin css-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css">

	<!--para las alertas-->
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
	<meta name="viewport" content="width=device-width">
</head>
<body class="desktop-detected smart-style-default">
<!-- HEADER -->
<header id="header">
	<div id="logo-group" class="col-md-1">
		<span id="logo"> <img src="<?php echo base_url(); ?>assets/img/cajalogo.JPG" WIDTH="40" HEIGHT="40" alt="SmartAdmin"> </span>
	</div>
	<div class="col-md-4 " style="font-size:18px;margin-top:10px;margin-bottom:-10px;">
		<span style="font-size:14px;margin-top:100px;"> <!-- User image size is adjusted inside CSS, it should stay as is -->
            <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                <i style="font-size:20px;" class="glyphicon glyphicon-th-large txt-color-blue"></i>
				<span>
                    Menu
                </span>
                <i class="fa fa-angle-down"></i>
            </a>
        </span>
		<span>
			&nbsp;&nbsp;&nbsp; 
			<div class="badge bg-color-blue">
				<span style="font-size:15px;">Fecha Sesión: <?php echo $this->session->userdata('desc_mes').'/'.$this->session->userdata('gestion');?></span>
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
				<!--<li class="divider"></li>-->
				<!--<li>
					<a href="javascript:void(0);"><i class="fa fa-power-off"></i> Cerrar Opción</a>
				</li>-->
			</ul>
		</div>
	</div>
	<!-- pulled right: nav area -->
	<div class="pull-right col-md-4">
		<!-- collapse menu button -->
		<div id="hide-menu" class="btn-header pull-right">
            <span> 
				<a href="javascript:void(0);" data-action="toggleMenu" title="Menu">
					<i class="fa fa-reorder"></i>
				</a>
			</span>
		</div>
		<!-- end collapse menu -->

		<!-- logout button -->
		<div id="logout" class="btn-header transparent pull-right">
            <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Salir" data-action="userLogout"
					  data-logout-msg="Seguro de Salir?"><i class="fa fa-sign-out"></i></a> </span>
		</div>
		<!-- end logout button -->

		<!-- fullscreen button -->
		<div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Pantalla Completa"><i
							class="fa fa-arrows-alt"></i></a> </span>
		</div>
		<!-- end fullscreen button -->

	</div>
	<!-- end pulled right: nav area -->

</header>
<!-- END HEADER -->
