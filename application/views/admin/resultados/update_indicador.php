<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
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
		<style>
      	table{font-size: 9px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9px;
            }
      </style>
	</head>
	<body class="">
		<!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
		<!-- HEADER -->
		<header id="header">
			<div id="logo-group">
				<span id="logo"> <img src="<?php echo base_url(); ?>assets/img/cajalogo.JPG" alt="SmartAdmin"> </span>
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
		                <a href="#" title="PROGRAMACION DEL POA"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
		            </li>
					<?php
		                for($i=0;$i<count($enlaces);$i++)
		                {
		                    if(count($subenlaces[$enlaces[$i]['o_child']])>0)
		                    {
			            ?>
			            <li>
			              	<a href="#" >
			              		<i class="<?php echo $enlaces[$i]['o_image']?>"></i> <span class="menu-item-parent"><?php echo $enlaces[$i]['o_titulo']; ?></span></a>
			              	<ul >
			              	<?php
			                foreach ($subenlaces[$enlaces[$i]['o_child']] as $item) {
			                ?>
			                <li><a href="<?php echo base_url($item['o_url']); ?>"><?php echo $item['o_titulo']; ?></a></li>
			                <?php } ?>
			                </ul>
			            </li>
			            <?php 
		                    }
		            	} ?>
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
					<li>Programaci&oacute;n del POA</li><li>Marco Estrat&eacute;gico</li><li>Acci&oacute;n de Mediano Plazo</li><li>Registro de Indicadores (Modificar)</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">

	                <?php
	                  $attributes = array('class' => 'form-horizontal', 'id' => 'formulario','name' =>'formulario','enctype' => 'multipart/form-data');
	                  echo validation_errors();
	                  echo form_open('admin/me/update_ind', $attributes);
	                ?>
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
					<!-- row -->
						<h2 class="alert alert-success">ACCI&Oacute;N DE MEDIANO PLAZO : <?php echo strtoupper($resultado[0]['r_resultado']);?></h2>
						<form id="formulario" name="formulario" novalidate="novalidate" method="post">
						<input class="form-control" type="hidden" name="r_id" value="<?php echo $resultado[0]['r_id']?>">
						<input class="form-control" type="hidden" name="in_id" value="<?php echo $indicador[0]['in_id']?>">
						<input class="form-control" type="hidden" name="nro_ind" value="<?php echo $resultado[0]['nro_ind']?>">
						<input class="form-control" type="hidden" name="nro" value="<?php echo $nro_indicador?>">	
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-7">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>MODIFICAR INDICADOR <?php echo $nro_indicador;?></strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">
									<div class="row">
										<div class="well">
											<div class="row">
												<div class="col-sm-4">
													<div class="form-group">
													<label><font size="1"><b>TIPO DE INDICADOR</b></font></label>
														<select class="form-control" id="tipo_i" name="tipo_i">
	                                                        <option value="">Seleccione Indicador</option>
	                                                        <?php 
											                    foreach($indi as $row)
											                    {
											                    	if($row['indi_id']==$indicador[0]['indi_id'])
											                    	{
											                    		?>
													                     <option value="<?php echo $row['indi_id']; ?>" selected><?php echo $row['indi_descripcion']; ?></option>
													                    <?php 
											                    	}
											                    	else
											                    	{
											                    		?>
													                     <option value="<?php echo $row['indi_id']; ?>"><?php echo $row['indi_descripcion']; ?></option>
													                    <?php 
											                    	}	
											                    }
											                ?>
	                                                  	</select>
													</div>
													<div id="medida">
														<?php
														if($indicador[0]['tp_med']==1)
														{
															?>
																<div class="form-group">
																<label><font size="1"><b>Seleccione tipo de Medida</b></font></label>
																	<select class="form-control" id="tp_medida" name="tp_medida">
				                                                        <option value="0">Seleccione</option>
				                                                        <option value="1" selected>LB + PROG. = META</option>
				                                                        <option value="2">LB = META</option>
				                                                  	</select>
																</div>
															<?php
														}
														else
														{
															?>
															<div class="form-group">
																<label><font size="1"><b>Seleccione tipo de Medida</b></font></label>
																	<select class="form-control" id="tp_medida" name="tp_medida">
				                                                        <option value="0">Seleccione</option>
				                                                        <option value="1">LB + PROG. = META</option>
				                                                        <option value="2" selected>LB = META</option>
				                                                  	</select>
																</div>
															<?php
														}
														?>
													</div>
												</div>
											
												<div class="col-sm-8">
													<div class="form-group">
														<label><font size="1"><b>INDICADOR</b></font></label>
														<textarea rows="4" class="form-control" style="width:100%;"  name="indicador" id="indicador"><?php echo $indicador[0]['in_indicador']; ?></textarea> 
													</div>
												</div>
											</div>
											<?php
											if($indicador[0]['indi_id']==2){ ?>
											<div id="rel">
												<div class="row">
													<div class="col-sm-3">
														<div class="form-group">
														<label><font size="1"><b>Seleccione Constante</b></font></label>
															<select class="form-control" id="valor_i" name="valor_i">
															<option value="0">Seleccione</option>
															<?php
															$um[1]='100';$um[2]='1000';$um[3]='10000';$um[4]='100000';
															for ($i=1; $i <=4 ; $i++){ 
																if($indicador[0]['valor_ind']==$i){ ?>
																	<option value="<?php echo $i;?>" selected><?php echo $um[$i];?></option>
																	<?php
																}
																else{ ?>
																	<option value="<?php echo $i;?>"><?php echo $um[$i];?></option>
																	<?php
																}
															}
															?>
		                                                  	</select>
														</div>
													</div>
													<div class="col-sm-7">
														<div class="form-group">
															<label><font size="1"><b>FORMULA</b></font></label>
															<textarea rows="4" class="form-control" style="width:100%;" name="formula" id="formula"><?php echo $indicador[0]['in_formula']; ?></textarea> 
														</div>
													</div>
													<div class="col-sm-2">
														<div class="form-group">
															<label><font size="1"><b>DENOMINADOR</b></font></label>
															<?php 
																if($indicador[0]['in_denominador']==0){
																	?>
																	<label class="radio state-success"><input type="radio" name="den" value="0"checked><i></i>Variable</label>
																	<label class="radio state-success"><input type="radio" name="den" value="1"><i></i>Fijo</label>
																	<?php
																}
																elseif ($indicador[0]['in_denominador']==1){
																	?>
																	<label class="radio state-success"><input type="radio" name="den" value="0"><i></i>Variable</label>
																	<label class="radio state-success"><input type="radio" name="den" value="1"checked><i></i>Fijo</label>
																	<?php
																}
															?>
														</div>
													</div>
													
												</div>
											</div>
												<?php
											}
											else{ ?>
											<div id="rel" style="display:none;">
												<div class="row">
													<div class="col-sm-3">
														<div class="form-group">
														<label><font size="1"><b>Seleccione Constante</b></font></label>
															<select class="form-control" id="valor_i" name="valor_i">
		                                                        <option value="0">Seleccione</option>
		                                                        <option value="1">100</option>
		                                                        <option value="2">1000</option>
		                                                        <option value="3">10000</option>
		                                                        <option value="4">100000</option>
		                                                  	</select>
														</div>
													</div>
													<div class="col-sm-7">
														<div class="form-group">
															<label><font size="1"><b>FORMULA</b></font></label>
															<textarea rows="4" class="form-control" style="width:100%;" name="formula" id="formula"><?php echo $indicador[0]['in_formula']; ?></textarea> 
														</div>
													</div>
													<div class="col-sm-2">
														<div class="form-group">
															<label><font size="1"><b>DENOMINADOR</b></font></label>
															<?php 
																if($indicador[0]['in_denominador']==0){
																	?>
																	<label class="radio state-success"><input type="radio" name="den" value="0"checked><i></i>Variable</label>
																	<label class="radio state-success"><input type="radio" name="den" value="1"><i></i>Fijo</label>
																	<?php
																}
																elseif ($indicador[0]['in_denominador']==1){
																	?>
																	<label class="radio state-success"><input type="radio" name="den" value="0"><i></i>Variable</label>
																	<label class="radio state-success"><input type="radio" name="den" value="1"checked><i></i>Fijo</label>
																	<?php
																}
															?>
														</div>
													</div>
													
												</div>
											</div>

												<?php
											}
											?>

											<div class="row">
											<?php
											if($indicador[0]['tp_med']==1){
												?>
												<div id="lb1">
													<div class="col-sm-4">
														<div class="form-group">
														<label><font size="1"><b>LINEA BASE</b></font></label>
															<input class="form-control" type="text" name="lb" id="lb" onchange="suma_programado();" value="<?php echo round($indicador[0]['in_linea_base'],2);?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label><font size="1"><b>META</b></font></label>
															<input class="form-control" type="text" name="met" id="met" value="<?php echo round($indicador[0]['in_meta'],2);?>" placeholder="0 %" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
												</div>
												<div id="lb2" style="display:none;">
													<div class="col-sm-4">
														<div class="form-group">
														<label><font size="1"><b>LINEA BASE</b></font></label>
															<input class="form-control" type="text" name="lb2" id="lb2" value="<?php echo round($indicador[0]['in_meta'],2);?>"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label><font size="1"><b>META</b></font></label>
															<input class="form-control" type="text" name="met2" id="met2" onchange="suma_programado2();" value="<?php echo round($indicador[0]['in_meta'],2);?>" placeholder="0 %" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
												</div>
												<?php
											}
											elseif($indicador[0]['tp_med']==2){ ?>
												<div id="lb2">
													<div class="col-sm-4">
														<div class="form-group">
														<label><font size="1"><b>LINEA BASE</b></font></label>
															<input class="form-control" type="text" name="lb2" id="lb2" value="<?php echo round($indicador[0]['in_linea_base'],2);?>"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label><font size="1"><b>META</b></font></label>
															<input class="form-control" type="text" name="met2" id="met2" onchange="suma_programado2();" value="<?php echo round($indicador[0]['in_meta'],2);?>" placeholder="0 %" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
												</div>
												<div id="lb1" style="display:none;">
													<div class="col-sm-4">
														<div class="form-group">
														<label><font size="1"><b>LINEA BASE</b></font></label>
															<input class="form-control" type="text" name="lb" id="lb" value="<?php echo round($indicador[0]['in_linea_base'],2);?>"  onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
													<div class="col-sm-4">
														<div class="form-group">
															<label><font size="1"><b>META</b></font></label>
															<input class="form-control" type="text" name="met" id="met" onchange="suma_programado();" value="<?php echo round($indicador[0]['in_meta'],2);?>" placeholder="0 %" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
														</div>
													</div>
												</div>
												<?php
											}
											
											?>
												<div class="col-sm-4">
													<div class="form-group">
														<label><font size="1"><b>PONDERACI&Oacute;N %</b></font></label>
														<input class="form-control" type="number" name="pn_cion" id="pn_cion" value="<?php echo round($indicador[0]['in_ponderacion'],2);?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false">
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
													<label><font size="1"><b>FUENTE DE VERIFICACI&Oacute;N</b></font></label>
														<textarea rows="4" class="form-control" style="width:100%;"  name="verificacion" id="verificacion"><?php echo $indicador[0]['in_fuente']; ?></textarea> 
													</div>
												</div>
											
												<div class="col-sm-6">
													<div class="form-group">
														<label><font size="1"><b>SUPUESTOS</b></font></label>
														<textarea rows="4" class="form-control" style="width:100%;" name="supuestos" id="supuestos"><?php echo $indicador[0]['in_supuestos']; ?></textarea> 
													</div>
												</div>
											</div>

											<?php
											if($indicador[0]['indi_id']==2){ ?>
											<div id="rel2">
												<div class="row">
													<div class="col-sm-12">
														<label><font size="1"><b>CARACTERISTICAS</b></font></label>
														<div class="form-group">
															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>Denominador</b></font></label>
																	<textarea rows="3" name="c_a" id="c_a" class="form-control" style="width:100%;"><?php echo $indicador[0]['in_total_casos']; ?></textarea> 
																</div>
															</div>
														
															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>Numerador</b></font></label>
																	<textarea rows="3" name="c_b" id="c_b" class="form-control" style="width:100%;"><?php echo $indicador[0]['in_casos_fav']; ?></textarea> 
																</div>
															</div>
														
														</div>
													</div>
												</div>
											</div>
												<?php
											}
											else { ?>
											<div id="rel2" style="display:none;">
												<div class="row">
													<div class="col-sm-12">
														<label><font size="1"><b>CARACTERISTICAS</b></font></label>
														<div class="form-group">
															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>Denominador</b></font></label>
																	<textarea rows="3" name="c_a" id="c_a" class="form-control" style="width:100%;"><?php echo $indicador[0]['in_total_casos']; ?></textarea> 
																</div>
															</div>
														
															<div class="col-sm-6">
																<div class="form-group">
																	<label><font size="1"><b>Numerador</b></font></label>
																	<textarea rows="3" name="c_b" id="c_b" class="form-control" style="width:100%;"><?php echo $indicador[0]['in_casos_fav']; ?></textarea> 
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
												<?php
											}
											?>
									</div>
									</div>
								</div>
							</div>
						</article>
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-5">
							<div class="jarviswidget jarviswidget-color-darken" >
								<header>
									<span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
									<h2 class="font-md"><strong>TEMPORALIDAD </strong></h2>				
								</header>
								<!-- widget content -->
								<div class="widget-body">

								<div>
									<center><strong>INDICADOR <b id="titulo_indicador"></b></strong></center>		
								</div><br>
								<table class="table table-bordered table-hover" style="width:100%;" >
								    <thead>
								        <tr>
								        <?php
											if($indicador[0]['valor_ind']==0){ ?>
												<th style="width:20%;"><center>GESTI&Oacute;N <b id="m1"><br><?php echo $resultado[0]['gestion_desde'];?></center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m2"><br><?php echo $resultado[0]['gestion_desde']+1;?></center></th>
								           		<th style="width:20%;"><center>GESTI&Oacute;N <b id="m3"><br><?php echo $resultado[0]['gestion_desde']+2;?></center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m4"><br><?php echo $resultado[0]['gestion_desde']+3;?></center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m5"><br><?php echo $resultado[0]['gestion_desde']+4;?></center></th>
												<?php
											}
											elseif($indicador[0]['valor_ind']==1){ ?>
												<th style="width:20%;"><center>GESTI&Oacute;N <?php echo $resultado[0]['gestion_desde'];?><b id="m1"> %</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <?php echo $resultado[0]['gestion_desde']+1;?><b id="m2"> %</center></th>
								           		<th style="width:20%;"><center>GESTI&Oacute;N <?php echo $resultado[0]['gestion_desde']+2;?><b id="m3"> %</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <?php echo $resultado[0]['gestion_desde']+3;?><b id="m4"> %</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <?php echo $resultado[0]['gestion_desde']+4;?><b id="m5"> %</center></th>
												<?php
											}
											elseif($indicador[0]['valor_ind']==2){ ?>
												<th style="width:20%;"><center>GESTI&Oacute;N <b id="m1"><br><?php echo $resultado[0]['gestion_desde'];?> /1.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m2"><br><?php echo $resultado[0]['gestion_desde']+1;?> /1.000</center></th>
								           		<th style="width:20%;"><center>GESTI&Oacute;N <b id="m3"><br><?php echo $resultado[0]['gestion_desde']+2;?> /1.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m4"><br><?php echo $resultado[0]['gestion_desde']+3;?> /1.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m5"><br><?php echo $resultado[0]['gestion_desde']+4;?> /1.000</center></th>
												<?php
											}
											elseif($indicador[0]['valor_ind']==3){ ?>
												<th style="width:20%;"><center>GESTI&Oacute;N <b id="m1"><br><?php echo $resultado[0]['gestion_desde'];?> /10.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m2"><br><?php echo $resultado[0]['gestion_desde']+1;?> /10.000</center></th>
								           		<th style="width:20%;"><center>GESTI&Oacute;N <b id="m3"><br><?php echo $resultado[0]['gestion_desde']+2;?> /10.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m4"><br><?php echo $resultado[0]['gestion_desde']+3;?> /10.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m5"><br><?php echo $resultado[0]['gestion_desde']+4;?> /10.000</center></th>
												<?php
											}
											elseif($indicador[0]['valor_ind']==4){ ?>
												<th style="width:20%;"><center>GESTI&Oacute;N <b id="m1"><br><?php echo $resultado[0]['gestion_desde'];?> /100.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m2"><br><?php echo $resultado[0]['gestion_desde']+1;?> /100.000</center></th>
								           		<th style="width:20%;"><center>GESTI&Oacute;N <b id="m3"><br><?php echo $resultado[0]['gestion_desde']+2;?> /100.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m4"><br><?php echo $resultado[0]['gestion_desde']+3;?> /100.000</center></th>
								            	<th style="width:20%;"><center>GESTI&Oacute;N <b id="m5"><br><?php echo $resultado[0]['gestion_desde']+4;?> /100.000</center></th>
												<?php
											}
											?>
								            
								        </tr>
								    </thead>
								    </table>
								    <?php
								      $programado=$this->model_resultado->resultado_programado($indicador[0]['in_id']); /// programado
								      $nro=0;
								      $suma = 0;
								      foreach($programado as $row)
								      {
								        $nro++;
								        $matriz [1][$nro]=$row['g_id'];
								        $matriz [2][$nro]=$row['in_prog'];
								        $suma=$suma+$row['in_prog'];
								      }
								      /*---------------- llenando la matriz vacia --------------*/
								      $g=$resultado[0]['gestion_desde'];
								      for($j = 1; $j<=5; $j++)
								      {
								        $matriz_r[1][$j]=$g;
								        $matriz_r[2][$j]='0';  //// P
								        $g++;
								      }
								      /*--------------------------------------------------------*/
								      /*------- asignando en la matriz P, PA, %PA ----------*/
								      for($i = 1 ;$i<=$nro ;$i++)
								      {
								        for($j = 1 ;$j<=5 ;$j++)
								        {
								          if($matriz[1][$i]==$matriz_r[1][$j])
								          {
								              $matriz_r[2][$j]=round($matriz[2][$i],2);
								          }
								        }
								      }
								      $suma=$suma+$indicador[0]['in_linea_base'];
								    ?>
								    <tbody>
								    <?php

										if($indicador[0]['tp_med']==1){ ?>
											<div id="prog1">
												<table class="table table-bordered table-hover" style="width:100%;" >
													<tr>
											            <td><input  name="g1" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="<?php echo $matriz_r[2][1]; ?>" onpaste="return false"></td>
											            <td><input  name="g2" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="<?php echo $matriz_r[2][2]; ?>" onpaste="return false"></td>
											            <td><input  name="g3" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="<?php echo $matriz_r[2][3]; ?>" onpaste="return false"></td>
											            <td><input  name="g4" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="<?php echo $matriz_r[2][4]; ?>" onpaste="return false"></td>
											            <td><input  name="g5" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="<?php echo $matriz_r[2][5]; ?>" onpaste="return false"></td>
											        </tr>
											    </table>

										        <div class="col-sm-12">
													<div class="form-group">
														<label><font size="2" color="blue"><b>SUMA TOTAL PROGRAMADO</b></font></label>
														<input class="form-control"name="total" type="text" id="total" value="<?php echo round($suma,2);?>" disabled="true" >
													</div>
												</div>
											</div>

											<div id="prog2" style="display:none;">
												<table class="table table-bordered table-hover" style="width:100%;" >
													<tr>
											            <td><input  name="g6" class="form-control" type="number" style="width:100%;" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true"></td>
											            <td><input  name="g7" class="form-control" type="number" style="width:100%;" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true"></td>
											            <td><input  name="g8" class="form-control" type="number" style="width:100%;" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true"></td>
											            <td><input  name="g9" class="form-control" type="number" style="width:100%;" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true"></td>
											            <td><input  name="g10" class="form-control" type="number" style="width:100%;" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true"></td>
											        </tr>
											    </table>
											    <div class="col-sm-12">
													<div class="form-group">
														<label><font size="2" color="blue"><b>SUMA TOTAL PROGRAMADO</b></font></label>
														<input class="form-control"name="total2" type="text" id="total2" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true" >
													</div>
												</div>
											</div>
											<?php
										}
										elseif($indicador[0]['tp_med']==2){ ?>
												<div id="prog2">
											        <table class="table table-bordered table-hover" style="width:100%;" >
													<tr>
											            <td><input  name="g6" class="form-control" type="number" style="width:100%;" value="<?php echo $matriz_r[2][1]; ?>" disabled="true"></td>
											            <td><input  name="g7" class="form-control" type="number" style="width:100%;" value="<?php echo $matriz_r[2][2]; ?>" disabled="true"></td>
											            <td><input  name="g8" class="form-control" type="number" style="width:100%;" value="<?php echo $matriz_r[2][3]; ?>" disabled="true"></td>
											            <td><input  name="g9" class="form-control" type="number" style="width:100%;" value="<?php echo $matriz_r[2][4]; ?>" disabled="true"></td>
											            <td><input  name="g10" class="form-control" type="number" style="width:100%;" value="<?php echo $matriz_r[2][5]; ?>" disabled="true"></td>
											        </tr>
												    </table>
												    <div class="col-sm-12">
														<div class="form-group">
															<label><font size="2" color="blue"><b>SUMA TOTAL PROGRAMADO</b></font></label>
															<input class="form-control"name="total2" type="text" id="total2" value="<?php echo round($indicador[0]['in_meta'],2);?>" disabled="true" >
														</div>
													</div>
											    </div>
											    <div id="prog1" style="display:none;">
													<table class="table table-bordered table-hover" style="width:100%;" >
														<tr>
												            <td><input  name="g1" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="0" onpaste="return false"></td>
												            <td><input  name="g2" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="0" onpaste="return false"></td>
												            <td><input  name="g3" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="0" onpaste="return false"></td>
												            <td><input  name="g4" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="0" onpaste="return false"></td>
												            <td><input  name="g5" class="form-control" type="number" onchange="suma_programado();" style="width:100%;" value="0" onpaste="return false"></td>
												        </tr>
												    </table>

											        <div class="col-sm-12">
														<div class="form-group">
															<label><font size="2" color="blue"><b>SUMA TOTAL PROGRAMADO</b></font></label>
															<input class="form-control"name="total" type="text" id="total" value="<?php echo round($indicador[0]['in_linea_base'],2);?>" disabled="true" >
														</div>
													</div>
												</div>
											<?php
										}
									?>	
								        
							</div>
							<div class="form-actions">
								<a href="<?php echo base_url().'index.php/admin/me/resultados' ?>" class="btn btn-lg btn-default" title="VOLVER A RESULTADOS"> CANCELAR </a>
								<input type="button" value="MODIFICAR INDICADOR <?php echo $nro_indicador;?>" id="btsubmit" class="btn btn-primary btn-lg" onclick="valida_envia_indicador()" title="MODIFICAR INDICADOR">
								</div>
							</div>			
						</article>
						</form>
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
		<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/ejecucion/abm_ejecucion.js" type="text/javascript"></SCRIPT>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
		<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/acciones/acciones_mp.js" type="text/javascript"></SCRIPT>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
		<script src="<?php echo base_url();?>/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
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
	</body>

</html>
