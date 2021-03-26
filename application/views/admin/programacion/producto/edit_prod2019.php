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
    <style type="text/css">
      aside{background: #05678B;}
      #col{color: #000000;}
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
        	<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="VOLVER A MIS PROYECTOS">Mis Operaciones</a></li><li><a href="#" title="SUB ACTIVIDADES">Sub Actividades</a></li><li>Modificar Operaci&oacute;n</li> 
        </ol>
      </div>
      <!-- END RIBBON -->	
			<!-- MAIN CONTENT -->
			<div id="content">
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<section id="widget-grid" class="well">
					          <div class="">
					            <h1>APERTURA PROGRAM&Aacute;TICA : <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad']?> - <small><?php echo $proyecto[0]['proy_nombre']?></small><br>
					            <h1> SERVICIO / COMPONENTE : <small><?php echo $componente[0]['com_componente'];?></small>
					          </div>
							</section>
						</article>
						<article class="col-sm-12">
							<form id="formulario" name="formulario" method="post" action="<?php echo site_url("").'/programacion/producto/modificar_producto2019'?>">
							<input class="form-control" type="hidden" name="prod_id" id="prod_id" value="<?php echo $producto[0]['prod_id'];?>">
							<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-columns"></i> </span>
									<h2>MODIFICAR OPERACI&Oacute;N</h2>
								</header>
								<!-- widget div-->
								<div>
									<div class="jarviswidget-editbox">
									</div>
									<div class="widget-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="well">
													<fieldset>
														<section>
															<label class="label" id="col"><b>OPERACI&Oacute;N</b></label>
								                            <textarea rows="4" class="form-control" style="width:100%;" name="prod" id="prod" title="REGISTRE DESCRIPCIÓN DE LA OPERACIÓN"><?php echo $producto[0]['prod_producto'];?></textarea> 
														</section><br>

														<section>
															<label class="label" id="col"><b>RESULTADO</b></label>
								                            <textarea rows="4" class="form-control" style="width:100%;" name="resultado" id="resultado" title="REGISTRE RESULTADO"><?php echo $producto[0]['prod_resultado'];?></textarea> 
														</section><br>
						
														<section>
															<label class="label" id="col"><b>TIPO DE INDICADOR</b></label>
								                            <select class="form-control" id="tipo_i" name="tipo_i" title="Seleccione Tipo de Proyecto">
		                                                        <?php 
												                    foreach($indi as $row){
												                    	if($row['indi_id']==$producto[0]['indi_id']){ ?>
														                    <option value="<?php echo $row['indi_id']; ?>" selected><?php echo $row['indi_descripcion']; ?></option>
														                    <?php 
												                    	}
												                    	else{ ?>
														                    <option value="<?php echo $row['indi_id']; ?>"><?php echo $row['indi_descripcion']; ?></option>
														                    <?php 
												                    	}	
												                    } ?>        
		                                                  	</select>
														</section><br>
														
														
														<section>
															<label class="label" id="col"><b>INDICADOR</b></label>
								                            <textarea rows="4" class="form-control" style="width:100%;" name="indicador" id="indicador" title="REGISTRE DESCRIPCIÓN DEL INDICADOR"><?php echo $producto[0]['prod_indicador'];?></textarea> 
														</section><br>

														<section>
															<div class="col-sm-4">
																<label class="label" id="col"><b>LINEA BASE</b></label>
																<input class="form-control" type="text" name="lb" id="lb" onkeyup="suma_prog();"  value="<?php echo round($producto[0]['prod_linea_base'],2);?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" title="REGISTRE LINEA BASE DE LA OPERACIÓN">
															</div>
														
															<div class="col-sm-4">
																<label class="label" id="col"><b>META</b></label>
																<input class="form-control" type="text" name="met" id="met" value="<?php echo round($producto[0]['prod_meta'],2);?>" placeholder="0 %" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" title="REGISTRE META DE LA OPERACIÓN">
															</div>

															<div class="col-sm-4">
																<label class="label" id="col"><b>PONDERACI&Oacute;N</b></label>
																<input class="form-control" type="number" name="pn_cion" id="pn_cion" value="<?php echo round($producto[0]['prod_ponderacion'],2);?>" placeholder="0 %" disabled="true">
															</div>
														</section><br><br><br><br>

														<section>
															<label class="label" id="col"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
								                            <textarea rows="4" class="form-control" style="width:100%;" name="verificacion" id="verificacion" title="REGISTRE MEDIO DE VERIFICACIÓN"><?php echo $producto[0]['prod_fuente_verificacion'];?></textarea> 
														</section><br>
													</fieldset>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="well">
													<b>ALINEACI&Oacute;N POA - PEI</b>
													<fieldset>
														<div id="atit"></div>
														<section>
															<label class="label" id="col"><b>OBJETIVO ESTRAT&Eacute;GICO</b></label>
								                                <select class="form-control" id="obj_id" name="obj_id" title="SELECCIONE OBJETIVO ESTRATEGICO">
			                                                        <option value="">Seleccione Objetivo Estrategico</option>
			                                                        <?php 
													                    foreach($oestrategicos as $row){ 
													                    	if($row['obj_id']==$ope_acc[0]['obj_id']){ ?>
															                	<option value="<?php echo $row['obj_id']; ?>" selected><?php echo $row['obj_codigo'].'.- '.$row['obj_descripcion']; ?></option>
															                <?php 
													                    	}
													                    	else{ ?>
															                	<option value="<?php echo $row['obj_id']; ?>"><?php echo $row['obj_codigo'].'.- '.$row['obj_descripcion']; ?></option>
															                <?php 
													                    	}		
													                    }
													                ?>
			                                                  	</select>
														</section><br>

														<section>
															<label class="label" id="col"><b>ACCI&Oacute;N ESTRATEGICA - <?php echo $producto[0]['acc_id'];?></b></label>
															<select class="form-control" id="acc_id" name="acc_id" title="SELECCIONE ACCIÓN ESTRATEGICA">
                                                  				<option value="">Seleccione Acci&oacute;n Estrategica</option>
		                                                        <?php 
												                    foreach($list_aestrategicas as $row){ 
												                    	if($row['ae']==$producto[0]['acc_id']){ ?>
															                <option value="<?php echo $row['acc_id']; ?>" selected><?php echo $row['acc_codigo'].'.- '.$row['acc_descripcion']; ?></option>
															                <?php 
												                    	}
												                    	else{ ?>
												                    		<option value="<?php echo $row['acc_id']; ?>"><?php echo $row['acc_codigo'].'.- '.$row['acc_descripcion']; ?></option>
												                    		<?php
												                    	}	
												                    }
												                ?>
                                                  			</select>
															</label>
														</section><br>

														<section>
															<label class="label" id="col"><b>INDICADOR PEI</b></label>
															<select class="form-control" id="indi_pei" name="indi_pei" title="SELECCIONE INDICADOR DE PROCESO">
                                                  				<option value="">Seleccione indicador Pei</option>
		                                                        <?php 
												                    foreach($indi_pei as $row){ 
												                    	if($row['ptm_id']==$producto[0]['indi_pei']){ ?>
															                <option value="<?php echo $row['ptm_id']; ?>" selected><?php echo $row['ptm_codigo'].'.- '.$row['ptm_indicador']; ?></option>
															                <?php 
												                    	}
												                    	else{ ?>
												                    		<option value="<?php echo $row['ptm_id']; ?>"><?php echo $row['ptm_codigo'].'.- '.$row['ptm_indicador']; ?></option>
												                    		<?php
												                    	}	
												                    }
												                ?>
                                                  			</select>
															</label>
														</section><br>
													</fieldset>
												</div><br>
												<div class="well">
													<div class="alert alert-success" role="alert">
													  TEMPORALIDAD - PROGRAMACI&Oacute;N FISICA <?php echo $this->session->userdata('gestion')?>
													</div>
													<fieldset>
														<?php
															if($producto[0]['indi_id']==2){ ?>
																<div id="trep">
																	<section>
																		<label class="label" id="col"><b>TIPO DE META</b></label>
											                            <select class="form-control" id="tp_met" name="tp_met" title="SELECCIONE TIPO DE META">
					                                                        <option value="">Seleccione Tipo de Meta</option>
					                                                        <?php 
															                    foreach($metas as $row){ 
															                    	if($row['mt_id']==$producto[0]['mt_id']){ ?>
															                    		<option value="<?php echo $row['mt_id']; ?>" selected><?php echo $row['mt_tipo']; ?></option>
															                    		<?php
															                    	}
															                    	else{ ?>
															                    		<option value="<?php echo $row['mt_id']; ?>"><?php echo $row['mt_tipo']; ?></option>
															                    		<?php
															                    	}
															                    }
															                ?>
					                                                  	</select>
																	</section><br>	
																</div>
																<?php
															}
															else{ ?>
																<div id="trep" style="display:none;" >
																	<section>
																		<label class="label" id="col"><b>TIPO DE META</b></label>
											                            <select class="form-control" id="tp_met" name="tp_met" title="SELECCIONE TIPO DE META">
					                                                        <option value="">Seleccione Tipo de Meta</option>
					                                                        <?php 
															                    foreach($metas as $row){ 
															                    	if($row['mt_id']==3){ ?>
															                    		<option value="<?php echo $row['mt_id']; ?>" selected><?php echo $row['mt_tipo']; ?></option>
															                    		<?php
															                    	}
															                    	else{ ?>
															                    		<option value="<?php echo $row['mt_id']; ?>"><?php echo $row['mt_tipo']; ?></option>
															                    		<?php
															                    	}
															                    }
															                ?>
					                                                  	</select>
																	</section><br>	
																</div>
																<?php
															}
														?>
														<section>
															<?php
																if(count($programado)!=0){ ?>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>ENERO <b id="m1"></center></th>
																	            <th style="width:20%;"><center>FEBRERO <b id="m2"></center></th>
																	            <th style="width:20%;"><center>MARZO <b id="m3"></center></th>
																	            <th style="width:20%;"><center>ABRIL <b id="m4"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input name="m1" id="ms1" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['enero'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m2" id="ms2" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['febrero'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m3" id="ms3" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['marzo'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m4" id="ms4" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['abril'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>MAYO <b id="m5"></center></th>
																	            <th style="width:20%;"><center>JUNIO <b id="m6"></center></th>
																	            <th style="width:20%;"><center>JULIO <b id="m7"></center></th>
																	            <th style="width:20%;"><center>AGOSTO <b id="m8"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input  name="m5" id="ms5" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['mayo'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m6" id="ms6" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['junio'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m7" id="ms7" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['julio'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m8" id="ms8" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['agosto'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>SEPTIEMBRE <b id="m9"></center></th>
																	            <th style="width:20%;"><center>OCTUBRE <b id="m10"></center></th>
																	            <th style="width:20%;"><center>NOVIEMBRE <b id="m11"></center></th>
																	            <th style="width:20%;"><center>DICIEMBRE <b id="m12"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input  name="m9" id="ms9" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['septiembre'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m10" id="ms10" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['octubre'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m11" id="ms11" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['noviembre'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m12" id="ms12" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="<?php echo $programado[0]['diciembre'];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<?php
																}
																else{?>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>ENERO <b id="m1"></center></th>
																	            <th style="width:20%;"><center>FEBRERO <b id="m2"></center></th>
																	            <th style="width:20%;"><center>MARZO <b id="m3"></center></th>
																	            <th style="width:20%;"><center>ABRIL <b id="m4"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input name="m1" id="ms1" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m2" id="ms2" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m3" id="ms3" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input name="m4" id="ms4" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>MAYO <b id="m5"></center></th>
																	            <th style="width:20%;"><center>JUNIO <b id="m6"></center></th>
																	            <th style="width:20%;"><center>JULIO <b id="m7"></center></th>
																	            <th style="width:20%;"><center>AGOSTO <b id="m8"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input  name="m5" id="ms5" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m6" id="ms6" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m7" id="ms7" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m8" id="ms8" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<table class="table table-bordered table-hover" style="width:100%;" >
																	    <thead>
																	        <tr>
																	            <th style="width:20%;"><center>SEPTIEMBRE <b id="m9"></center></th>
																	            <th style="width:20%;"><center>OCTUBRE <b id="m10"></center></th>
																	            <th style="width:20%;"><center>NOVIEMBRE <b id="m11"></center></th>
																	            <th style="width:20%;"><center>DICIEMBRE <b id="m12"></center></th>
																	        </tr>
																	    </thead>
																	    <tbody>
																	        <tr>
																	            <td><input  name="m9" id="ms9" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m10" id="ms10" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m11" id="ms11" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	            <td><input  name="m12" id="ms12" class="form-control" type="text" onkeyup="suma_prog();" style="width:100%;" value="0" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
																	        </tr>
																	    </tbody>
																	</table>
																	<?php
																}
															?>
															
															<table style="width:100%;" >
																<tr>
																	<td>
																		<input class="form-control" name="total" type="text" id="total" value="<?php echo $prog; ?>" disabled="true" style="width:100%;" >
																	</td>
																</tr>
															</table>
														</section>
													</fieldset>
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="col-sm-12">
										<div id="but" align="right">
											<a href="<?php echo base_url().'index.php/admin/prog/list_prod/'.$producto[0]['com_id'] ?>" title="CANCELAR Y SALIR A MIS ACTIVIDADES" class="btn btn-default">CANCELAR</a>
											<input type="button" value="MODIFICAR OPERACI&Oacute;N" id="btsubmit" class="btn btn-primary" title="GUARDAR REGISTRO"><br><br>
										</div>
									</div>
								</div>
							</div>
							</form>
						</article>
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
		<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
		<script>
			if (!window.jQuery.ui) {
				document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
			}
		</script>
		<!-- IMPORTANT: APP CONFIG -->
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
		<!-- <SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/ejecucion/abm_ejecucion.js" type="text/javascript"></SCRIPT> -->
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
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function () {
	        	$("#tipo_i").change(function () {            
	            var tp_id = $(this).val();
		            if(tp_id==2){
		            	$('#trep').slideDown();
		            }
		            else{
		            	$('#trep').slideUp();
		            	for (var i = 1; i <= 12; i++) {
			                $('[name="m'+i+'"]').val((0).toFixed(0));
			                $("#m"+i).html('');
			                $('[name="m'+i+'"]').prop('disabled', false);
			            }
			            $('[name="total"]').val((0).toFixed(0));
			            $('[name="tp_met"]').val((3).toFixed(0));
		            }
	            });
	        });

	        $(document).ready(function () {
	        	$("#tp_met").change(function () {            
	            var tp_met = $(this).val();
		            if(tp_met==1){
		            	meta = parseFloat($('[name="met"]').val());
		            	for (var i = 1; i <= 12; i++) {
			                $('[name="m'+i+'"]').val((meta).toFixed(0));
			                $("#m"+i).html('%');
			                $('[name="m'+i+'"]').prop('disabled', true);
			            }
			            $('[name="total"]').val((meta).toFixed(0));
		            }
		            else{
		            	for (var i = 1; i <= 12; i++) {
			                $('[name="m'+i+'"]').val((0).toFixed(0));
			                $("#m"+i).html('');
			                $('[name="m'+i+'"]').prop('disabled', false);
			            }
			            $('[name="total"]').val((0).toFixed(0));
		            }
	            });
	        });

			$(document).ready(function() {
				pageSetUp();
				$("#obj_id").change(function () {
	                $("#obj_id option:selected").each(function () {
	                elegido=$(this).val();
	                $.post("<?php echo base_url(); ?>index.php/prog/combo_acciones", { elegido: elegido }, function(data){ 
	                	$("#acc_id").html(data);
	                	});     
	            	});
	            });  
			})

			$(document).ready(function() {
				pageSetUp();
				$("#acc_id").change(function () {
	                $("#acc_id option:selected").each(function () {
	                elegido=$(this).val();
	                $.post("<?php echo base_url(); ?>index.php/prog/combo_indicadores", { elegido: elegido }, function(data){ 
	                	$("#indi_pei").html(data);
	                	});     
	            	});
	            });  
			})
		</script>


		<script>
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
		$(function () {
		    $("#btsubmit").on("click", function (e) {
		        var $validator = $("#formulario").validate({
		            rules: {
		                com_id: {
		                    required: true,
		                },
		                prod: {
		                    required: true,
		                },
		                resultado: {
		                    required: true,
		                },
		                tipo_i: {
		                    required: true,
		                },
		                indicador: {
		                    required: true,
		                },
		                lb: {
		                    required: true,
		                },
		                met: {
		                    required: true,
		                },
		                obj_id: {
		                    required: true,
		                },
		                acc_id: {
		                    required: true,
		                },
		                indi_pei: {
		                    required: true,
		                }
		            },
		            messages: {
		                prod: {required: "<font color=red size=1>REGISTRE OPERACI&Oacute;N - PRODUCTO</font>"},
		                resultado: {required: "<font color=red size=1>REGISTRE RESULTADO</font>"},
		                tipo_i: {required: "<font color=red size=1>SELECCIONE UNIDAD EJECUTORA</font>"},
		                indicador: {required: "<font color=red size=1>REGISTRE INDICADOR</font>"},
		                lb: {required: "<font color=red size=1>REGISTRE LINEA BASE</font>"},
		                met: {required: "<font color=red size=1>REGISTRE META DE LA OPERACI&Oacute;N</font>"},
		                obj_id: {required: "<font color=red size=1>SELECCIONE OBJETIVO ESTRATEGICO</font>"},
		                acc_id: {required: "<font color=red size=1>SELECCIONE ACCI&Oacute;N DE MEDIANO PLAZO</font>"},
		                indi_pei: {required: "<font color=red size=1>SELECCIONE INDICADOR PEI</font>"}
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
		        var $valid = $("#formulario").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } 
		        else {

		        	if(document.formulario.tipo_i.value==1){
		        		meta = parseFloat($('[name="met"]').val());
		        		total = parseFloat($('[name="total"]').val());
		        		if(parseFloat(meta)!=parseFloat(total)){
		        			alertify.error("LA SUMA DE MESES PROGRAMADOS NO ES IGUAL A LA META DE LA OPERACI&Oacute;N") 
				            document.formulario.met.focus() 
				            return 0; 
		        		}
		        	}	
		        	else{
			        	if(document.formulario.tp_met.value==0){
			        		alertify.error("SELECCIONE TIPO DE META") 
				            document.formulario.resultado.focus() 
				            return 0; 
			        	}
			        	if(document.formulario.tipo_i.value==2){
			        		if(document.formulario.tp_met.value==3){
			        			meta = parseFloat($('[name="met"]').val());
				        		total = parseFloat($('[name="total"]').val());
				        		if(parseFloat(meta)!=parseFloat(total)){
				        			alertify.error("LA SUMA DE MESES PROGRAMADOS NO ES IGUAL A LA META DE LA OPERACI&Oacute;N") 
						            document.formulario.met.focus() 
						            return 0; 
				        		}
			        		}
			        	}
			        }

		        	reset();
	                alertify.confirm("GUARDAR DATOS DE LA OPERACI&Oacute;N ?", function (a) {
	                    if (a) {
	                        document.getElementById('btsubmit').disabled = true;
	                        document.formulario.submit();
	                    } else {
	                        alertify.error("OPCI\u00D3N CANCELADA");
	                    }
	                });
		            
		        }
		    });
		});
		</script>
		<script type="text/javascript">
        function suma_prog(){ 
        	linea = parseFloat($('[name="lb"]').val());
        	tp = parseFloat($('[name="tipo_i"]').val());
        	var suma=0;
            for (var i = 1; i <= 12; i++) {
                suma=parseFloat(suma)+parseFloat($('[id="ms'+i+'"]').val());
            }
     		
     		if(tp==1){
     			$('[name="total"]').val((suma+linea).toFixed(2));
     		}
     		else{
     			$('[name="total"]').val((suma).toFixed(2));
     		}
        }
        </script>
	</body>
</html>