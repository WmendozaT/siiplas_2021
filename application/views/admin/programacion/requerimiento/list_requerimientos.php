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
          <?php echo $stylo;?>
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
				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Programaci&oacute;n POA</a></li><li>Unidades</li><li>Actividades</li><li>Requerimientos</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
				            <section id="widget-grid" class="well">
				                <div class="">
				                  <?php echo $datos; ?>
				                  <h1> PRESUPUESTO ASIGNADO : <small><?php echo number_format($monto_a, 2, ',', '.'); ?></small>&nbsp;&nbsp;-&nbsp;&nbsp;PRESUPUESTO PROGRAMADO : <small><?php echo number_format($monto_p, 2, ',', '.'); ?></small>&nbsp;&nbsp;-&nbsp;&nbsp;SALDO : <small><?php echo number_format(($monto_a-$monto_p), 2, ',', '.'); ?></small></h1>
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
				                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard';?>">SALIR A MENU PRINCIPAL</a></li>
				                  	<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy'; ?>" title="LISTA DE POAS">LISTA DE POAS - <?php echo $this->session->userData('gestion');?></a></li>
				                	<li ><a onclick="eliminar_requerimientos()" class="btn btn-danger" style="width:100%;" title="Cerrar Modificacion"><font color="#ffffff">ELIMINAR TODOS LOS REQUERIMIENTOS   </font></a></li>
				                	<!-- <?php
					                  	if($this->session->userdata('tp_adm')==1){ ?>
					                  		<li ><a onclick="eliminar_requerimientos()" class="btn btn-danger" style="width:100%;" title="Cerrar Modificacion"><font color="#ffffff">ELIMINAR TODOS LOS REQUERIMIENTOS   </font></a></li>
					                  	<?php
					                  	}
					                ?> -->
					               <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/prog/list_prod/'.$componente[0]['com_id']; ?>" title="VOLVER ATRAS">VOLVER ATRAS</a></li>
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
			               <div class="well">
	                		<div class="row">
								<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
									<div class="jarviswidget jarviswidget-color-darken">
		                              <header>
		                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
		                                  <h2 class="font-md"><strong></strong></h2>  
		                              </header>
										<div>
											<?php echo $button;?>
										
											<div class="widget-body no-padding">
												<form id="del_req" name="del_req" novalidate="novalidate" action="<?php echo site_url().'/insumos/cprog_insumo/delete_requerimientos'?>" method="post">
													<input type="hidden" name="prod_id" id="prod_id" value="<?php echo $producto[0]['prod_id'];?>">
													<?php echo $requerimientos;?>
													<input type="hidden" name="tot" id="tot" value="0">
							                        <div class="alert alert-danger" align=right><input type="button" class="btn btn-danger btn-xs" value="ELIMINAR REQUERIMIENTOS" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS SELECCIONADOS"></div>
							                    </form>
											</div>
											<!-- end widget content -->
										</div>
										<!-- end widget div -->
									</div>
									<!-- end widget -->
								</article>
							</div>
							</div>
						</article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>

		<!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
        <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
            	<div class="modal-header">
		            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
		        </div>
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>NUEVO REGISTRO - REQUERIMIENTO</center></h2>
                    <form action="<?php echo site_url().'/programacion/crequerimiento/valida_insumo'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                        <input type="hidden" name="prod_id" id="proy_id" value="<?php echo $producto[0]['prod_id'];?>">
                        <header><b>DATOS GENERALES DEL REQUERIMIENTO</b><br><label class="label"><b>C&Oacute;DIGO DE ACTIVIDAD : <?php echo $producto[0]['prod_cod'];?></b></label></header>
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
											<select class="form-control" id="um_id" name="um_id" title="SELECCIONE UNIDAD DE MEDIDA">
		                                        <option value="">Seleccione</option>
		                                    </select>
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><font color=blue><b>MONTO SALDO (TECHO)</b></font></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="text" name="saldo" id="saldo" disabled="true" value="<?php echo round(($monto_a-$monto_p),2);?>">
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
								</div>
								<br>
								<div id="atit"></div>
								<header><b>DISTRIBUCI&Oacute;N FINANCIERA: <?php echo $this->session->userdata('gestion')?></b><br>
								<label class="label"><div id="ff"></div></label>
								</header>
								<br>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>PROGRAMADO TOTAL</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="tot" id="tot" value="0" disabled="true">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>ENERO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>FEBRERO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>MARZO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>ABRIL</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>MAYO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>JUNIO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>JULIO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>AGOSTO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>SEPTIEMBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>OCTUBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>NOVIEMBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="m11" id="m11" value="0" onkeyup="suma_programado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>DICIEMBRE</b></label>
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
								<center><img id="loadi" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="45" height="45"></center>
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
		            	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
		        	</div>
	              <div class="modal-body">
	              	<h2 class="alert alert-info"><center>MODIFICAR REGISTRO - REQUERIMIENTO</center></h2>
	                <form action="<?php echo site_url().'/programacion/crequerimiento/valida_update_insumo'?>" method="post" id="form_mod" name="form_mod" class="smart-form">
						<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
						<input type="hidden" name="id" id="id" value="<?php echo $id;?>">
						<input type="hidden" name="ins_id" id="ins_id">
							<header><b>DATOS GENERALES DEL REQUERIMIENTO</b><br><label class="label"><b>C&Oacute;DIGO DE ACTIVIDAD : <?php echo $producto[0]['prod_cod'];?></b></label></header>
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
									<section class="col col-3">
										<label class="label"><b>UNIDAD DE MEDIDA</b></label>
										<label class="input">
											<input type="text" name="iumedida" id="iumedida" title="MODIFICAR UNIDAD DE MEDIDA">
											<!-- <select class="form-control" id="mum_id" name="mum_id" title="SELECCIONE UNIDAD DE MEDIDA">
		                                    </select> -->
										</label>
									</section>
									<section class="col col-3">
										<label class="label"><b><font color="blue">MONTO SALDO (TECHO)</font></b></label>
										<label class="input">
											<i class="icon-append fa fa-tag"></i>
											<input type="hidden" name="saldo" id="saldo">
											<input type="text" name="sal" id="sal" disabled="true">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="label"><b>DETALLE</b></label>
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
										<label class="label"><b>OBSERVACI&Oacute;N</b></label>
										<label class="textarea">
											<i class="icon-append fa fa-tag"></i>
											<textarea rows="2" name="observacion" id="observacion"></textarea>
										</label>
									</section>
								</div>
								<br>
								<div id="amtit"></div>
								<header><b>DISTRIBUCI&Oacute;N FINANCIERA: <?php echo $this->session->userdata('gestion')?></b><br>
								<label class="label"><div id="ff"></div></label>
								</header>
								<br>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>PROGRAMADO TOTAL</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mtot" id="mtot" value="0" disabled="true">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>ENERO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>FEBRERO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>MARZO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>ABRIL</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>MAYO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>JUNIO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
								</div>
								<div class="row">
									<section class="col col-2">
										<label class="label"><b>JULIO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>AGOSTO</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>SEPTIEMBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>OCTUBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>NOVIEMBRE</b></label>
										<label class="input">
											<i class="icon-append fa fa-money"></i>
											<input type="text" name="mm11" id="mm11" value="0" onkeyup="suma_programado_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
										</label>
									</section>
									<section class="col col-2">
										<label class="label"><b>DICIEMBRE</b></label>
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
								<center><img id="loadm" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="45" height="45"></center>
							</div>
						</form>
	            </div>
	          </div>
	        </div>
	    </div>
	    <!-- ======================================================== -->
    <!-- ================== MODAL SUBIR ARCHIVO ========================== -->
     	<div class="modal animated fadeInDown" id="modal_importar_ff" tabindex="-1" role="dialog">
        <div class="modal-dialog" id="mdialTamanio2">
            <div class="modal-content">
                <div class="modal-body no-padding">
                    <div class="row">
                       <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="row">
                            <h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> IMPORTAR REQUERIMIENTOS DE LA ACTIVIDAD (.CSV) </h2>
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
                                    	<font color="#1b5f56" size="2.5"><b>C&Oacute;DIGO ACTIVIDAD : </b><?php echo $producto[0]['prod_cod'];?><br>
                                    	<b>ACTIVIDAD : </b><?php echo $producto[0]['prod_producto'];?></font>
                                      <form action="<?php echo site_url() . '/insumos/cprog_insumo/importar_requerimientos_a_una_actividad' ?>" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep" method="post">
                                          <input class="form-control" type="hidden" name="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                                          <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
                                          <fieldset>
                                          	<br>
	                                        <div class="input-group">
	                                          <span class="input-group-btn">
	                                            <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
	                                            <input  id="archivo_csv" accept=".csv" name="archivo_csv" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
	                                            <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
	                                          </span>
	                                          <span class="form-control"></span>
	                                        </div>
	                                    	</br>
	                                      </fieldset>
                                          <div >
                                            <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;" title="SUBIR REQUERIMIENTOS A LA OPERACI&Oacute;N">SUBIR REQUERIMIENTOS .CSV</button>
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
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script src="<?php echo base_url(); ?>mis_js/programacionpoa/form5.js"></script> 

		<script type="text/javascript">
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
                       document.getElementById("saldo").value = parseFloat(response.monto_saldo).toFixed(2);
                       document.getElementById("sal").value = parseFloat(response.monto_saldo).toFixed(2);
                       document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
                       document.getElementById("cantidad").value = response.insumo[0]['ins_cant_requerida'];
                       document.getElementById("costou").value = parseFloat(response.insumo[0]['ins_costo_unitario']).toFixed(2);
                       document.getElementById("costot").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
                       document.getElementById("costot2").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
                       document.getElementById("par_padre").value = response.ppdre[0]['par_codigo'];
                       $("#par_hijo").html(response.lista_partidas);
                       document.getElementById("iumedida").value = response.insumo[0]['ins_unidad_medida'];
                       //$("#mum_id").html(response.lista_umedida);
                       document.getElementById("mtot").value = response.prog[0];
                       document.getElementById("observacion").value = response.insumo[0]['ins_observacion'];
                       //$('#ff').html('FUENTE DE FINANCIAMIENTO : '+response.prog[0]['ff_codigo']+' || ORGANISMO FINANCIADOR : '+response.prog[0]['of_codigo']);
                       if(response.prog[0]!=response.insumo[0]['ins_costo_total']){
                       	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                       	$('#mbut').slideUp();
                       }

                       for (var i = 1; i <=12; i++) {
                       	document.getElementById("mm"+i).value = response.prog[i];
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
                                mum_id: { //// unidad medida
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
                                mum_id: "<font color=red>SELECCIONE UNIDAD DE MEDIDA</font>",
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
                        	saldo=document.getElementById("sal").value;
                        	programado=document.getElementById("mtot").value;
                        	dif=saldo-programado;
                    
                    		$('#amtit').html('');
                        		alertify.confirm("MODIFICAR REQUERIMIENTO ?", function (a) {
                                if (a) {
                                	document.getElementById("loadm").style.display = 'block';
                                    document.getElementById('subir_mins').disabled = true;
                                    document.getElementById("subir_mins").value = "MODIFICANDO DATOS REQUERIMIENTO...";
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
                    var ins_id = $(this).attr('name');
                    var proy_id = <?php echo $proyecto[0]['proy_id'];?>;
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
                                data: "ins_id="+ins_id+"&proy_id="+proy_id
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
	        //VISTA PREVIA ARCHIVO
	        $("#subir_archivo_prev").on("click", function () {
	            var $valid = $("#form_subir_prev").valid();
	            if (!$valid) {
	                $validator.focusInvalid();
	            } else {
	              if(document.getElementById('archivo').value==''){
	                alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
	                return false;
	              }

	                alertify.confirm("DESEA VER ARCHIVO PREVIO A LA IMPORTACIÓN?", function (a) {
	                    if (a) {
	                        document.getElementById("load").style.display = 'block';
	                        document.getElementById('subir_archivo_prev').disabled = true;
	                        document.forms['form_subir_prev'].submit();
	                    } else {
	                        alertify.error("OPCI\u00D3N CANCELADA");
	                    }
	                });
	            }
	        });

	        $("#subir_archivo").on("click", function () {
	            var $valid = $("#form_subir_sigep").valid();
	            if (!$valid) {
	                $validator.focusInvalid();
	            } else {
	              if(document.getElementById('archivo_csv').value==''){
	                alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
	                return false;
	              }

	                alertify.confirm("SUBIR ARCHIVO AL SISTEMA ?", function (a) {
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
		<!-- ====================================================================================================== -->
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
		<script type="text/javascript">
			// DO NOT REMOVE : GLOBAL FUNCTIONS!
			$(document).ready(function() {
				pageSetUp();
				$("#menu").menu();
				$('.ui-dialog :button').blur();
				$('#tabs').tabs();
			})
		</script>
		<script type="text/javascript">

		</script>
	</body>
</html>
