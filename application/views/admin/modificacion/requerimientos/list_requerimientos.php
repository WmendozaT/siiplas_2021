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
		<!--para las alertas-->
    	<meta name="viewport" content="width=device-width">
    	<?php echo $style;?>
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
					<span> <a href="javascript:void(0);" data-action="toggleMenu" title="Menu"><i class="fa fa-reorder"></i></a> </span>
				</div>
				<!-- end collapse menu -->
				<!-- logout button -->
				<div id="logout" class="btn-header transparent pull-right">
					<span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Salir" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
				</div>
				<!-- end logout button -->
				<!-- search mobile button (this is hidden till mobile view port) -->
				<div id="search-mobile" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
				</div>
				<!-- end search mobile button -->
				<!-- fullscreen button -->
				<div id="fullscreen" class="btn-header transparent pull-right">
					<span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Pantalla Completa"><i class="fa fa-arrows-alt"></i></a> </span>
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
		                <a href="#" title="MODIFICACIONES"> <span class="menu-item-parent">MODIFICACIONES</span></a>
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
					<li>....</li><li>....</li><li>POAS Aprobados</li><li>Unidad Responsable</li><li>Mis Requerimientos - <?php echo $this->session->userData('gestion') ?></li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
					<?php echo $cabecera; ?>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
					<?php echo $opciones; ?>
		        </article>
		        
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<?php 
	                  if($this->session->flashdata('success')){ ?>
	                    <div class="alert alert-success">
	                      	<?php echo $this->session->flashdata('success'); ?>
	                    </div>
	                    <script type="text/javascript">alertify.success("<?php echo '<font size=2>'.$this->session->flashdata('success').'</font>'; ?>")</script>
	                <?php }
	                  elseif($this->session->flashdata('danger')){ ?>
	                      <div class="alert alert-danger">
	                        <?php echo $this->session->flashdata('danger'); ?>
	                      </div>
	                      <script type="text/javascript">alertify.error("<?php echo '<font size=2>'.$this->session->flashdata('danger').'</font>'; ?>")</script>
	                    <?php
	                  }
	                ?>
	               <div class="well well-sm well-light">
						<div id="tabs">
							<ul>
								<li>
									<a href="#tabs-a"><b>MIS REQUERIMIENTOS</b></a>
								</li>
								<li>
									<a href="#tabs-c"><b>VER CUADRO COMPARATIVO DE PRESUPUESTO</b></a>
								</li>
							</ul>

							<div id="tabs-a">
								<div class="row">
									<div >
            			
										<div class="jarviswidget jarviswidget-color-darken">
			                              <header>
			                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
			                                  <h2 class="font-md"><strong></strong></h2>  
			                              </header>
											<div>
												<div class="widget-body no-padding">
													<form id="del_req" name="del_req" novalidate="novalidate" action="<?php echo site_url().'/modificaciones/cmod_insumo/delete_select_requerimientos'?>" method="post">
														<input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
														<?php echo $tabla;?>
														<input type="hidden" name="tot" id="tot" value="0">
								                    </form>
												</div>
												<!-- end widget content -->
											</div>
											<!-- end widget div -->
										</div>
										<!-- end widget -->
								
									</div>
								</div>
							</div>

							<div id="tabs-c">
								<div class="row">
									<hr>
									<div align="left" id="boton_comparativo">
                                        <a href="#" class="btn btn-default boton_cuadro_comparativo" title="CUADRO COMPARATIVO PPTO." style="width:40%;"> <img src="<?php echo base_url(); ?>assets/Iconos/arrow_refresh.png" WIDTH="25" HEIGHT="25"/>&nbsp;&nbsp;ACTUALIZAR CONSOLIDADO DE PRESUPUESTO POR PARTIDAS</a>
                                    </div>
                                    <div class="row">
                                      <div id="partidas"></div>
                                    </div>
								</div>
							</div>
							
						</div>
					</div>

				</article>


			</div>
		</div>

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

		<!--  Lista de Certificaciones POAS   -->
		<div class="modal fade" id="modal_certpoas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document" class="modal-dialog modal-sm">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" id="exampleModalLongTitle">MIS CERTIFICACIONES POAS</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <div class="modal-body" id="cpoas"></div>
		      </div>
		    </div>
		  </div>
		</div>

		<!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
        <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
            	<div class="modal-header">
                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                </div>
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>NUEVO REGISTRO - REQUERIMIENTO</center></h2>
                    <form action="<?php echo site_url().'/modificaciones/cmod_insumo/valida_add_insumo'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                        <input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
                        <header>
                        	<b>DATOS GENERALES DEL REQUERIMIENTO</b>
                        	<!-- <label class="label"><?php echo $titulo;?></label> -->
                        </header>
							<fieldset>
								<div class="row">
									<section class="col col-3">
										<label class="label"><b>GRUPO PARTIDA</b></label>
										<label class="input">
											<select class="form-control" id="padre" name="padre" title="SELECCIONE GRUPO DE PARTIDA">
		                                        <option value="">Seleccione</option>
		                                        <?php 
		                                            foreach($part_padres as $row){ 
		                                            	if($row['par_codigo']!=0){ ?>
		                                                <option value="<?php echo $row['par_codigo'];?>"><?php echo $row['par_codigo'].' - '.$row['par_nombre'];?></option>
		                                        		<?php }
		                                           	} ?>        
		                                    </select>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><b>PARTIDA</b></label>
										<label class="input">
											<select class="form-control" id="partida_id" name="partida_id" title="SELECCIONE PARTIDA">
		                                        <option value="">Seleccione Partida</option>        
		                                    </select>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><b>UNIDAD DE MEDIDA</b></label>
										<label class="input">
											<select class="form-control" id="ins_um" name="ins_um" title="SELECCIONE UNIDAD DE MEDIDA">
		                                        <option value="">Seleccione</option>
		                                    </select>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><font color=blue><b>MONTO SALDO (PARTIDA)</b></font></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="saldo" id="saldo" disabled="true" value="">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="label"><b>DETALLE</b></label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="ins_detalle" id="ins_detalle" title="REGISTRAR DETALLE DEL REQUERIMIENTO"></textarea>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>CANTIDAD</b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="ins_cantidad" id="ins_cantidad" onkeyup="costo_total()" value="0" onkeypress="return justNumbers(event);" title="REGISTRAR CANTIDAD">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>COSTO UNITARIO <font color="blue">(2 decimales)</font></b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="ins_costo_u" id="ins_costo_u" onkeyup="costo_total()" value="0" onkeypress="return justNumbers(event);" onpaste="return false" title="REGISTRAR COSTO UNITARIO">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>COSTO TOTAL</b></label>
										<label class="input">
											<input type="hidden" name="costo" id="costo">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="costo2" id="costo2" value="0" disabled="true">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="label"><b>OBSERVACI&Oacute;N</b></label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="ins_observacion" id="ins_observacion"></textarea>
										</label>
									</section>
									<?php echo $lista;?>
								</div>
								<br>
								
								<div id="atit"></div>
								<header><b>DISTRIBUCI&Oacute;N PRESUPUESTARIA : <?php echo $this->session->userdata('gestion')?></b><br>
								<label class="label"><div id="ff"></div></label>
								</header>
								<br>
								<div class="row">
									<section class="col col-2">
										<label class="label">PROGRAMADO TOTAL</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="tot" id="tot" value="0" disabled="true">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label">ENERO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">FEBRERO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">MARZO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">ABRIL</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">MAYO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">JUNIO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label">JULIO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">AGOSTO</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">SEPTIEMBRE</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">OCTUBRE</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">NOVIEMBRE</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m11" id="m11" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label">DICIEMBRE</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m12" id="m12" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
								</div>

							</fieldset>
							
							<div id="but" style="display:none;">
								<footer>
									<button type="button" name="subir_ins" id="subir_ins" class="btn btn-info" >GUARDAR NUEVO REQUERIMIENTO</button>
									<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
								</footer>
								<center><img id="loada" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="40" height="40"></center>
							</div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
        <!-- ============ Modal Modificar requerimiento ========= -->
        <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	          <div class="modal-dialog" id="mdialTamanio">
	            <div class="modal-content">
	            	<div class="modal-header">
                    	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                    </div>
	              <div class="modal-body">
	              	<div id="titulo_req"></div>
	               		<form action="<?php echo site_url().'/modificaciones/cmod_insumo/valida_update_insumo'?>" method="post" id="form_mod" name="form_mod" class="smart-form">
						<input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
						<input type="hidden" name="ins_id" id="ins_id">
						<input type="hidden" name="par_id" id="par_id">
							<header><b>DATOS GENERALES DEL REQUERIMIENTO</b>
								<!-- <label class="label"><?php echo $titulo;?></label> -->
							</header>
							<fieldset>
								<div class="row">
									<section class="col col-3">
										<label class="label"><b>GRUPO PARTIDA</b></label>
										<label class="input">
											<select class="form-control" id="par_padre" name="par_padre" title="SELECCIONE GRUPO DE PARTIDA">
		                                        <option value="">Seleccione Grupo Partida</option>
		                                        <?php 
		                                            foreach($part_padres as $row){ ?>
		                                                <option value="<?php echo $row['par_codigo'];?>" <?php if(@$_POST['pais']==$row['par_codigo']){ echo "selected";} ?>><?php echo $row['par_codigo'].' - '.$row['par_nombre'];?></option>
		                                        <?php } ?>        
		                                    </select>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><b>PARTIDA</b></label>
										<label class="input">
											<select class="form-control" id="par_hijo" name="par_hijo" title="SELECCIONE PARTIDA">       
		                                    </select>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>UNIDAD DE MEDIDA</b></label>
										<label class="input">
											<input type="text" name="umedida" id="umedida" title="MODIFICAR UNIDAD DE MEDIDA">
											<!-- <select class="form-control" id="mum_id" name="mum_id" title="SELECCIONE UNIDAD DE MEDIDA">
		                                    </select> -->
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b><font color="blue">MONTO SALDO PARTIDA</font></b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="hidden" name="saldo" id="saldo">
											<input type="text" name="sal" id="sal" disabled="true">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>SALDO</b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="monto_dif" id="monto_dif" disabled="true">
										</label>
									</section>
								</div>


								<div class="row">
									<section class="col col-6">
										<label class="label"><b>DETALLE REQUERIMIENTO</b></label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="detalle" id="detalle" title="MODIFICAR DETALLE DEL REQUERIMIENTO"></textarea>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>CANTIDAD</b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="cantidad" id="cantidad" onkeyup="costo_totalm()" onkeypress="return justNumbers(event);" title="MODIFICAR CANTIDAD">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>COSTO UNITARIO <font color="blue">(2 decimales)</font></b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="costou" id="costou" onkeyup="costo_totalm()" onkeypress="return justNumbers(event);" onpaste="return false" title="MODIFICAR COSTO UNITARIO">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>COSTO TOTAL</b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="hidden" name="costot" id="costot">
											<input type="text" name="costot2" id="costot2" disabled="true">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="label">OBSERVACI&Oacute;N</label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="observacion" id="observacion"></textarea>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><b>ALINEACIÓN FORM 4 (ACTIVIDAD)</b></label>
										<label class="input">
											<select class="form-control" id="id" name="id" title="SELECCIONE VINCULACIÓN">       
		                                    </select>
										</label>
									</section>
								</div>
							
								<div id="amtit"></div>
								<header><b>DISTRIBUCI&Oacute;N PRESUPUESTARIA : <?php echo $this->session->userdata('gestion')?></b><br>
								<label class="label"><div id="ff"></div></label>
								</header>
								<br>
								<div class="row">
									<section class="col col-2">
										<label class="label">PROGRAMADO TOTAL</label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mtot" id="mtot" value="0" disabled="true">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><div id="mess1"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess2"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess3"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess4"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess5"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess6"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><div id="mess7"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess8"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess9"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess10"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess11"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm11" id="mm11" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><div id="mess12"></div></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm12" id="mm12" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>" disabled>
										</label>
									</section>
								</div>
								<div id="monto"></div>
								<input type="hidden" name="monto_cert" id="monto_cert">
							</fieldset>
							<div id="mbut">
								<footer>
									<button type="button" name="subir_mins" id="subir_mins" class="btn btn-info" >MODIFICAR REQUERIMIENTO</button>
									<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
								</footer>
								<center><img id="loadm" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="40" height="40"></center>
							</div>
						</form>
	            </div>
	          </div>
	        </div>
	    </div>
	    <!-- ======================================================== -->

	 <!-- MODAL CERRAR   -->
        <div class="modal fade" id="modal_cerrar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	          <div class="modal-dialog modal-dialog-centered" role="document" class="modal-dialog modal-sm">
	            <div class="modal-content">
	            	<div class="modal-header">
                    	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                    </div>
	              <div class="modal-body">
	              		<?php
	              			if($cite[0]['cite_estado']==0){
	              				echo '<h2 class="alert alert-warning"><center>CERRAR MODIFICACI&Oacute;N PRESUPUESTARIA</center></h2>';
	              			}
	              			else{
	              				echo '<h2 class="alert alert-success"><center>MODIFICACI&Oacute;N PRESUPUESTARIA CONCLUIDA</center></h2>';
	              			}
	              		?>
	               		<form action="<?php echo site_url().'/modificaciones/cmod_insumo/cerrar_modificacion'?>" method="post" id="form_cerrar" name="form_cerrar" class="smart-form">
						<input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
							<header><b>C&Oacute;DIGO : </b><?php if($cite[0]['cite_estado']==0){echo "<font color=red>SIN CÓDIGO</font>";}else{echo "<font color=green>".$cite[0]['cite_codigo']."</font>";} ?></header>
							<fieldset>
								<div class="row">
									<section >
										<label class="label"><b>OBSERVACI&Oacute;N</b></label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="4" name="observacion" id="observacion" title="OBSERVACI&Oacute;N"><?php echo $cite[0]['cite_observacion'];?></textarea>
										</label>
									</section>
								</div>
							</fieldset>
							<div class="row">
	                            <div id="mbut">
	                                <footer>
										<button type="button" name="cerrar_mod" id="cerrar_mod" class="btn btn-info" >CERRAR MODIFICACI&Oacute;N</button>
										<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
									</footer>
	                            </div>
	                            <div id="mload" style="display: none" align="center">
	                                <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>CERRANDO MODIFICACI&Oacute;N PRESUPUESTARIA</b>
	                            </div>
	                        </div>
						</form>
	            </div>
	          </div>
	        </div>
	    </div>
	 <!--  =============== -->

	    <!-- ================== MODAL SUBIR ARCHIVO ========================== -->
	  	<div class="modal fade" id="modal_importar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog modal-dialog-centered" role="document" class="modal-dialog modal-sm">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                	<h2><center>SUBIR ARCHIVO REQUERIMIENTO.CSV</center></h2>
	                
	                    <div class="row">
	                    	<script src="<?php echo base_url(); ?>assets/file_nuevo/jquery.min.js"></script>
	                    		<form action="<?php echo site_url().'/modificaciones/cmod_insumo/valida_add_requerimientos';?>" method="post" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep">
	                            <input type="hidden" id="cite_id" name="cite_id" value="<?php echo $cite[0]['cite_id'];?>" />
								
								<div class="input-group">
								  <span class="input-group-btn">
								    <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
								    <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
								  	<input name="MAX_FILE_SIZE" type="hidden" value="20000" />
								  </span>
								  <span class="form-control"></span>
								</div>
								<hr>
								<div >
	                                <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR REQUERIMIENTOS .CSV</button><br>
			                        <center><img id="loads" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
	                            </div>
                              </form> 
	                    </div>
	                </div>
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
		
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script src="<?php echo base_url(); ?>mis_js/modificacionpoa/modform5.js"></script> 
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		
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