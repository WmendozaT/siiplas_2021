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
		<!-- FAVICONS -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
    	<style type="text/css">
	    table{
	        font-size: 9px;
	        width: 100%;
	        max-width:1550px;;
	        overflow-x: scroll;
	        }
	    th{
	        padding: 1.4px;
	        text-align: center;
	        font-size: 9px;
	        }
	    #mdialTamanio{
		      width: 80% !important;
		    }
		#mdialTamanio2{
		      width: 45% !important;
		    }
		    hr {border: 0; height: 12px; box-shadow: inset 0 12px 12px -12px #1c7368;}
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
		                <a href="<?php echo base_url().'index.php/admin/proy/list_proy' ?>" title="MIS PROYECTOS"><span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a'?>" title="MIS PROYECTOS">Mis Proyectos</a></li><li>Programaci&oacute;n de Requerimientos - Nivel de Componentes</li><li>Insumo Componente</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<!-- row -->
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				            <section id="widget-grid" class="well">
				                <div class="">
				                  <h1> PROGRAMACI&Oacute;N DE REQUERIMIENTO A NIVEL COMPONENTES - DELEGADO</h1>
				                  <h1> PROYECTO DE INVERSI&Oacute;N : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></small>
				                  <h1> COMPONENTE : <small><?php echo 'COD. COMP. '.$componente[0]['com_nro'].'.- '.$componente[0]['com_componente'];?></small>
				                </div>
				            </section>
				        </article>
				        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
				            <section id="widget-grid" class="well">
				              <style type="text/css">#graf{font-size: 80px;}</style> 
				              <center>
				                <div class="dropdown">
				                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
				                  OPCIONES
				                  <span class="caret"></span>
				                </button>
				                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard' ?>">SALIR A MENU PRINCIPAL</a></li>
				                  <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a'?>" title="LISTA DE OPERACIONES">LISTA DE PROYECTOS</a></li>
				                  <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_importar_ff"  title="IMPORTAR ARCHIVO DE OPERACIONES">IMPORTAR REQUERIMIENTOS AL PROYECTO</a></li>
				                	<?php 
					                  	if($this->session->userdata('rol_id')==1){ ?>
					                  		<li role="presentation"><a onclick="eliminar_requerimientos()" class="btn btn-danger" style="width:100%;" title="Cerrar Modificacion"><font color="#ffffff">ELIMINAR TODOS LOS REQUERIMIENTOS</font></a></li>
					                  	<?php
					                  	}
					                ?>
				                </ul>
				              </div>
				              </center>
				            </section>
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
			                <?php
			                	if($verif==1){ ?>
			                		<div class="well well-sm well-light">
										<?php echo $fuentes;?>
										<div id="tabs">
											<ul>
												<li>
													<a href="#tabs-a">REQUERIMIENTOS DEL PROYECTO</a>
												</li>
												<li>
													<a href="#tabs-b">CONSOLIDADO PARTIDAS DEL PROYECTO</a>
												</li>
												<li>
													<a href="#tabs-c">CONSOLIDADO PARTIDAS DEL PROYECTO</a>
												</li>
											</ul>
											<div id="tabs-a">
												<div class="row">
													<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
													<div class="jarviswidget jarviswidget-color-darken">
						                              <header>
						                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
						                                  <h2 class="font-md"><strong></strong></h2>  
						                              </header>
														<div>
															<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success nuevo_ff" title="NUEVO REGISTRO - REQUERIMIENTOS" class="btn btn-success" style="width:15.5%;">NUEVO REGISTRO</a><br><br>
															<div class="widget-body no-padding">
																<?php echo $requerimientos;?>
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
													<a href="javascript:abreVentana('<?php echo site_url("").'/prog/rep_requerimientos_del/'.$componente[0]['com_id'].'';?>');" title="REPORTE REQUERIMIENTOS DEL PROYECTO - PDF" class="btn btn-primary" style="width:15.5%;">REQUERIMIENTOS PDF</a>
													<a href="javascript:abreVentana('<?php echo site_url("").'/prog/rep_partidas_del/'.$componente[0]['com_id'].'';?>');" title="REPORTE CONSOLIDADO DE PARTIDAS DEL PROYECTO - PDF" class="btn btn-primary" style="width:15.5%;">CONSOLIDADO PDF</a>
													<a href='<?php echo site_url("").'/prog/xcel_partidas_del/'.$componente[0]['com_id'].''; ?>' title="REPORTE CONSOLIDADO DE PARTIDAS DEL PROYECTO DE INVERSI&Oacute;N - EXCEL" class="btn btn-primary" style="width:15.5%;">CONSOLIDADO EXCEL</a>
													<hr>
													<?php echo $partidas_del;?>
												</div>
											</div>
											
											<div id="tabs-c">
												<div class="row">
													<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
														<div class="jarviswidget jarviswidget-color-darken">
							                              <header>
							                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
							                                  <h2 class="font-md"><strong>CONSOLIDADO POR PARTIDAS</strong></h2>  
							                              </header>
															<div>
																<div class="widget-body no-padding">
																	<?php echo $partidas_pi;?>
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
			                		<?php
			                	}
			                	else{ ?>
			                		<h2 class="alert alert-danger"><center>REGISTRE FUENTE DE FINANCIAMIENTO</center></h2>
			                		<?php
			                	}
			                ?>
						</article>
				        
					</div>
				</section>
				<!-- end widget grid -->
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->


		<!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>NUEVO REQUERIMIENTO</center></h2>
                    <form action="<?php echo site_url().'/prog/valida_insumo_delegado'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                        <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                        <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                        
							<fieldset>					
								<div class="row">
									<section class="col col-9">
										<label class="label">DETALLE</label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="ins_detalle" id="ins_detalle" title="REGISTRAR DETALLE DEL REQUERIMIENTO"></textarea>
										</label>
									</section>
									<section class="col col-3">
										<label class="label">MONTO SALDO</label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="saldo" id="saldo" disabled="true" onkeypress="if (this.value.length < 7) { return numerosDecimales(event);}else{return false; }">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-3">
										<label class="label">CANTIDAD</label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="ins_cantidad" id="ins_cantidad" onkeyup="costo_total()" value="0" onkeypress="return justNumbers(event);" title="MODIFICAR CANTIDAD">
										</label>
									</section>
									<section class="col col-3">
										<label class="label">COSTO UNITARIO</label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="ins_costo_u" id="ins_costo_u" onkeyup="costo_total()" value="0" onkeypress="return justNumbers(event);" onpaste="return false" title="MODIFICAR COSTO UNITARIO">
										</label>
									</section>
									<section class="col col-3">
										<label class="label">COSTO TOTAL</label>
										<label class="input">
											<input type="hidden" name="costo" id="costo">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="costo2" id="costo2" value="0" disabled="true">
										</label>
									</section>
									<section class="col col-3">
										<label class="label">UNIDAD DE MEDIDA</label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="ins_u_medida" id="ins_u_medida" title="REGISTRAR UNIDAD DE MEDIDA">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-3">
										<label class="label">GRUPO PARTIDA</label>
										<label class="input">
											<select class="form-control" id="padre" name="padre" title="SELECCIONE GRUPO DE PARTIDA">
		                                        <option value="">Seleccione Grupo Partida</option>
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
										<label class="label">PARTIDA</label>
										<label class="input">
											<select class="form-control" id="partida_id" name="partida_id" title="SELECCIONE PARTIDA">
		                                        <option value="">Seleccione Partida</option>        
		                                    </select>
										</label>
									</section>
									<section class="col col-6">
										<label class="label">OBSERVACI&Oacute;N</label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="ins_observacion" id="ins_observacion"></textarea>
										</label>
									</section>
								</div>
								<br>
								<div id="atit"></div>
								<header><b>TEMPORALIDAD FINANCIERA: <?php echo $this->session->userdata('gestion')?></b><br>
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
									<button type="button" name="subir_ins" id="subir_ins" class="btn btn-info" >GUARDAR REQUERIMIENTO</button>
									<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
								</footer>
							</div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->

        <!-- ============ Modal Modificar requerimiento ========= -->
        <div class="modal fade" id="modal_mod_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-body">
              	<h2 class="alert alert-info"><center>MODIFICAR REQUERIMIENTO</center></h2>
                <form action="<?php echo site_url().'/prog/valida_update_insumo_delegado'?>" method="post" id="form_mod" name="form_mod" class="smart-form">
					<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
					<input type="hidden" name="ins_id" id="ins_id">
						
						<fieldset>					
							<div class="row">
								<section class="col col-9">
									<label class="label">DETALLE</label>
									<label class="textarea">
										<i class="icon-append fa fa-tag"></i>
										<textarea rows="2" name="detalle" id="detalle" title="MODIFICAR DETALLE DEL REQUERIMIENTO"></textarea>
									</label>
								</section>
								<section class="col col-3">
									<label class="label">MONTO SALDO</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="hidden" name="saldo" id="saldo">
										<input type="text" name="sal" id="sal" disabled="true">
									</label>
								</section>
							</div>

							<div class="row">
								<section class="col col-3">
									<label class="label">CANTIDAD</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="cantidad" id="cantidad" onkeyup="costo_totalm()" onkeypress="return justNumbers(event);" title="MODIFICAR CANTIDAD">
									</label>
								</section>
								<section class="col col-3">
									<label class="label">COSTO UNITARIO</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="costou" id="costou" onkeyup="costo_totalm()" onkeypress="return justNumbers(event);" onpaste="return false" title="MODIFICAR COSTO UNITARIO">
									</label>
								</section>
								<section class="col col-3">
									<label class="label">COSTO TOTAL</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="hidden" name="costot" id="costot">
										<input type="text" name="costot2" id="costot2" disabled="true">
									</label>
								</section>
								<section class="col col-3">
									<label class="label">UNIDAD DE MEDIDA</label>
									<label class="input">
										<i class="icon-append fa fa-tag"></i>
										<input type="text" name="umedida" id="umedida" title="MODIFICAR UNIDAD DE MEDIDA">
									</label>
								</section>
							</div>

							<div class="row">
								<section class="col col-3">
									<label class="label">GRUPO PARTIDA</label>
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
									<label class="label">PARTIDA</label>
									<label class="input">
										<select class="form-control" id="par_hijo" name="par_hijo" title="SELECCIONE PARTIDA">
	                                        <option value="">Seleccione Partida</option>
	                                        <?php 
	                                            foreach($part_hijos as $row){ ?>
	                                                <option value="<?php echo $row['par_id']; ?>"><?php echo $row['par_codigo'].' - '.$row['par_nombre'];?></option>
	                                        <?php } ?>        
	                                    </select>
									</label>
								</section>
								<section class="col col-6">
									<label class="label">OBSERVACI&Oacute;N</label>
									<label class="textarea">
										<i class="icon-append fa fa-tag"></i>
										<textarea rows="2" name="observacion" id="observacion"></textarea>
									</label>
								</section>
							</div>
							<br>
							<div id="amtit"></div>
							<header><b>TEMPORALIDAD FINANCIERA: <?php echo $this->session->userdata('gestion')?></b><br>
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
									<label class="label">ENERO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">FEBRERO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">MARZO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">ABRIL</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">MAYO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">JUNIO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
							</div>
							<div class="row">
								<section class="col col-2">
									<label class="label">JULIO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">AGOSTO</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">SEPTIEMBRE</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">OCTUBRE</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">NOVIEMBRE</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm11" id="mm11" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
								<section class="col col-2">
									<label class="label">DICIEMBRE</label>
									<label class="input">
										<i class="icon-append fa fa-money"></i>
										<input type="text" name="mm12" id="mm12" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
									</label>
								</section>
							</div>

						</fieldset>
						
						<div id="mbut">
							<footer>
								<button type="button" name="subir_mins" id="subir_mins" class="btn btn-info" >MODIFICAR REQUERIMIENTO</button>
								<button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
							</footer>
						</div>
					</form>
	            </div>
	          </div>
	        </div>
	    </div>
	    <!-- ======================================================== -->

	    <!-- ================== MODAL SUBIR ARCHIVO ========================== -->
     	<div class="modal animated fadeInDown" id="modal_importar_ff" tabindex="-1" role="dialog">
	        <link href="<?php echo base_url(); ?>assets/file/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
	        <script src="<?php echo base_url(); ?>assets/file/jquery.min.js"></script>
	        <script src="<?php echo base_url(); ?>assets/file/js/fileinput.min.js" type="text/javascript"></script> 
	        <div class="modal-dialog" id="mdialTamanio2">
	            <div class="modal-content">
	                <div class="modal-body no-padding">
	                    <div class="row">
	                       <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	                        <div class="row">
	                            <h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> IMPORTAR REQUERIMIENTOS A LA FASE (.CSV) </h2>
	                            <div class="col-sm-12">
	                              <!-- well -->
	                              <div class="well">
	                                <!-- row -->
	                                <div class="row">
	                                  <!-- col -->
	                                  <div class="col-sm-12">
	                                    <p class="alert alert-info">
	                                      <i class="fa fa-info"></i> Por favor guardar el archivo (Excel.xls) a extension (.csv) delimitado por (; "Punto y comas"). verificar el archivo .csv para su correcta importaci&oacute;n
	                                    </p>
	                                    <!-- row -->
	                                    <div class="row">
	                                    	<font color="#1b5f56" size="2"><b>C&Oacute;DIGO DEL COMPONENTE: </b><?php echo $componente[0]['com_nro'];?><br>
	                                    	<b>COMPONENTE: </b><?php echo $componente[0]['com_componente'];?></font>
	                                      <form action="<?php echo site_url() . '/insumos/cprog_insumos_delegado/importar_requerimientos'?>" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep" method="post">
	                                          <input class="form-control" type="hidden" name="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
	                                          <input class="form-control" type="hidden" name="com_id" value="<?php echo $componente[0]['com_id'];?>">
	                                          <fieldset>
	                                            <section class="form-group">
	                                              <label class="label"><b>SUBIR ARCHIVO .CSV </b></label>
	                                              <label class="input input-file">
	                                                    <span class="button">
	                                                        <input id="archivo" accept=".csv" name="archivo" type="file" class="file">
	                                                        <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
	                                                  <b class="tooltip tooltip-top-left">
	                                                      <i class="fa fa-warning txt-color-teal"></i> EL ARCHIVO A SUBIR, DEBE SER EXTENSION .CSV
	                                                  </b>
	                                              </label>
	                                            </section>
	                                          </fieldset>
	                                          <div >
	                                            <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;" title="SUBIR REQUERIMIENTOS A LA FASE">SUBIR REQUERIMIENTOS .CSV</button>
	                                            <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
	                                          </div>
	                                      </form> 
	                                    </div>
	                                    <!-- end row -->
	                                  </div>
	                                  <!-- end col -->
	                                </div>
	                                <!-- end row -->
	                              </div>
	                              <!-- end well -->
	                            </div>
	                          </div>
	                        </article>
	                    </div>   
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div>
    	<!--================================================== -->

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
			if (!window.jQuery) {document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');}
		</script>
		<script>
			if (!window.jQuery.ui) {document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');}
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
		<!-- Demo purpose only -->
		<script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
			function eliminar_requerimientos(){
			alertify.confirm("DESEA ELIMINAR TODOS LOS REQUERIMIENTOS ?", function (a) {
		        if (a) {
		        	window.location='<?php echo base_url().'index.php/prog/delete_ins_del/'.$componente[0]['com_id'].''?>';
		        } else {
		            alertify.error("OPCI\u00D3N CANCELADA");
		        }
		    });
			}
            /*------------ MODIFICAR REQUERIMIENTO ----------------*/
            $(function () {
                $(".mod_ff").on("click", function (e) {
                    ins_id = $(this).attr('name');
                    document.getElementById("ins_id").value=ins_id;
           			proy_id=document.getElementById("proy_id").value;


                    var url = "<?php echo site_url("")?>/prog/get_requerimiento";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "ins_id="+ins_id+"&proy_id="+proy_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                       document.getElementById("saldo").value = response.monto_saldo;
                       document.getElementById("sal").value = response.monto_saldo;
                       document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
                       document.getElementById("cantidad").value = response.insumo[0]['ins_cant_requerida'];
                       document.getElementById("costou").value = response.insumo[0]['ins_costo_unitario'];
                       document.getElementById("costot").value = response.insumo[0]['ins_costo_total'];
                       document.getElementById("costot2").value = response.insumo[0]['ins_costo_total'];
                       document.getElementById("umedida").value = response.insumo[0]['ins_unidad_medida'];
                       document.getElementById("par_padre").value = response.ppdre[0]['par_codigo'];
                       document.getElementById("par_hijo").value = response.insumo[0]['par_id'];
                       document.getElementById("mtot").value = response.prog[0]['programado_total'];
                       document.getElementById("observacion").value = response.insumo[0]['ins_observacion'];
                       $('#ff').html('FUENTE DE FINANCIAMIENTO : '+response.prog[0]['ff_codigo']+' || ORGANISMO FINANCIADOR : '+response.prog[0]['of_codigo']);
                       if(response.prog[0]['programado_total']!=response.insumo[0]['ins_costo_total']){
                       	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                       	$('#mbut').slideUp();
                       }

                       for (var i = 1; i <=12; i++) {
                       	document.getElementById("mm"+i).value = response.prog[0]['mes'+i];
                       }
                       
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DEL REQUERIMIENTO");
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
                    $("#subir_mins").on("click", function (e) {
                        var $validator = $("#form_mod").validate({
                               rules: {
                                ins_id: { //// Insumo
                                required: true,
                                },
                                proy_id: { //// Proyecto
                                    required: true,
                                },
                                detalle: { //// Detalle
                                    required: true,
                                },
                                cantidad: { //// Cantidad
                                    required: true,
                                },
                                costou: { //// Costo U
                                    required: true,
                                },
                                costot: { //// costo tot
                                    required: true,
                                },
                                umedida: { //// unidad medida
                                    required: true,
                                },
                                par_padre: { //// par padre
                                    required: true,
                                },
                                par_hijo: { //// par hijo
                                    required: true,
                                }
                            },
                            messages: {
                                ins_id: "<font color=red>INSUMO/font>",
                                detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                                cantidad: "<font color=red>CANTIDAD</font>",
                                costou: "<font color=red>COSTO UNITARIO</font>",
                                costot: "<font color=red>COSTO TOTAL</font>",
                                umedida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
                                par_padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                                par_hijo: "<font color=red>SELECCIONE PARTIDA</font>",                     
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

                            alertify.confirm("MODIFICAR REQUERIMIENTO ?", function (a) {
                                if (a) {
                                //    document.getElementById("loadd").style.display = 'block';
                                    document.getElementById('subir_mins').disabled = true;
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
        <!-- AGREGAR NUEVO REQUERIMIENTO -->
        <script type="text/javascript">
        $(function () {
            $("#subir_ins").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                        rules: {
                            com_id: { //// componente
                            required: true,
                            },
                            proy_id: { //// proyecto
                                required: true,
                            },
                            ins_detalle: { //// Detalle
                                required: true,
                            },
                            ins_cantidad: { //// Cantidad
                                required: true,
                            },
                            ins_costo_u: { //// Costo U
                                required: true,
                            },
                            costo: { //// costo tot
                                required: true,
                            },
                            ins_u_medida: { //// unidad medida
                                required: true,
                            },
                            padre: { //// par padre
                                required: true,
                            },
                            partida_id: { //// par hijo
                                required: true,
                            }
                        },
                        messages: {
                            ins_detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                            ins_cantidad: "<font color=red>CANTIDAD</font>",
                            ins_costo_u: "<font color=red>COSTO UNITARIO</font>",
                            costo: "<font color=red>COSTO TOTAL</font>",
                            ins_u_medida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
                            padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                            partida_id: "<font color=red>SELECCIONE PARTIDA</font>",                     
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
                    alertify.confirm("GUARDAR REQUERIMIENTO ?", function (a) {
                        if (a) {
                            document.getElementById("subir_ins").value = "GUARDANDO REQUERIMIENTO...";
                            document.getElementById('subir_ins').disabled = true;
                            document.forms['form_nuevo'].submit();
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
                    var name = $(this).attr('name');
                    var request;
                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR REQUERIMIENTO ?", function (a) {
                        if (a) { 
                            url = "<?php echo site_url("")?>/prog/delete_ins_ope";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "ins_id="+name

                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("EL REQUERIMIENTO SE ELIMINO CORRECTAMENTE ", function (e) {
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
	    <script type="text/javascript">
	      $(function () {
	        //SUBIR ARCHIVO
	        $("#subir_archivo").on("click", function () {
	            var $valid = $("#form_subir_sigep").valid();
	            if (!$valid) {
	                $validator.focusInvalid();
	            } else {
	              if(document.getElementById('archivo').value==''){
	                alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
	                return false;
	              }

	                alertify.confirm("REALMENTE DESEA SUBIR ESTE ARCHIVO?", function (a) {
	                    if (a) {
	                        document.getElementById("load").style.display = 'block';
	                        document.getElementById('subir_archivo').disabled = true;
	                        document.forms['form_subir_sigep'].submit();
	                    } else {
	                        alertify.error("OPCI\u00D3N CANCELADA");
	                    }
	                });
	            }
	        });
	      });
	    </script>
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
		<script type="text/javascript">
		$(document).ready(function() {
			pageSetUp();
			$("#padre").change(function () {
                $("#padre option:selected").each(function () {
                elegido=$(this).val();
                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
                $("#partida_id").html(data);
                });     
            });
            });  
		})

		$(document).ready(function() {
			pageSetUp();
			$("#par_padre").change(function () {
                $("#par_padre option:selected").each(function () {
                elegido=$(this).val();
                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
                $("#par_hijo").html(data);
                });     
            });
            });  
		})

		function suma_programado(){ 
	        sum=0;
	        for (var i = 1; i<=12; i++) {
	        	sum=parseFloat(sum)+parseFloat($('[name="m'+i+'"]').val());
	        }

	        $('[name="tot"]').val((sum).toFixed(2));
	        programado = parseFloat($('[name="tot"]').val()); //// programado total
	        ctotal = parseFloat($('[name="costo"]').val()); //// Costo Total

	        if(programado!=ctotal){
	        	$('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                $('#but').slideUp();
	        }
	        else{
	        	$('#atit').html('');
                $('#but').slideDown();
	        }
	    }

	    function suma_programado_modificado(){ 
	        sum=0;
	        for (var i = 1; i <=12; i++) {
	        	sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
	        }

	        $('[name="mtot"]').val((sum).toFixed(2));
	        programado = parseFloat($('[name="mtot"]').val()); //// programado total
	        ctotal = parseFloat($('[name="costot"]').val()); //// Costo Total

	        if(programado!=ctotal){
	        	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                $('#mbut').slideUp();
	        }
	        else{
	        	$('#amtit').html('');
                $('#mbut').slideDown();
	        }
	    }

	    function costo_totalm(){ 
	        a = parseFloat($('[name="cantidad"]').val()); //// Meta
	        b = parseFloat($('[name="costou"]').val()); //// Costo
	        if (a!=0 && a>0 ){
	            $('[name="costot"]').val((b*a).toFixed(2) );
	            $('[name="costot2"]').val((b*a).toFixed(2) );
	        }

	        ct = parseFloat($('[name="costot"]').val()); //// total
	        mt = parseFloat($('[name="mtot"]').val()); //// prog
	        if(ct!=mt ||  isNaN(a)){
	        	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#mbut').slideUp();
	        }
	        else{
	        	$('#amtit').html('');
                $('#mbut').slideDown();
	        }

	    }

	   	function costo_total(){ 
	        a = parseFloat($('[name="ins_cantidad"]').val()); //// cantidad
	        b = parseFloat($('[name="ins_costo_u"]').val()); //// Costo unitario
	        if (a!=0 && a>0 ){
	            $('[name="costo"]').val((b*a).toFixed(2) );
	            $('[name="costo2"]').val((b*a).toFixed(2) );
	        }

	        ct = parseFloat($('[name="costo"]').val()); //// total
	        mt = parseFloat($('[name="tot"]').val()); //// prog
	        if(ct!=mt ||  isNaN(a) || ct==0){
	        	$('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#but').slideUp();
	        }
	        else{
	        	$('#atit').html('');
                $('#but').slideDown();
	        }

	    }

	    function verif(){ 
			a = parseFloat($('[name="costot"]').val()); //// total
	        b = parseFloat($('[name="mtot"]').val()); //// prog
	        if(a!=b){
	        	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#mbut').slideUp();
	        }
	        else{
	        	$('#amtit').html('');
                $('#mbut').slideDown();
	        }
	    }
		</script>
	</body>
</html>
