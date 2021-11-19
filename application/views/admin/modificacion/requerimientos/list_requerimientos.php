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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!--para las alertas-->
    	<meta name="viewport" content="width=device-width">
		<script type="text/javascript">
		  function abreVentana_comparativo(PDF){             
		      var direccion;
		      direccion = '' + PDF;
		      window.open(direccion, "Cuadro Comparativo" , "width=700,height=600,scrollbars=NO") ; 
		  }
		</script>
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
            #mdialTamanio{
		      width: 80% !important;
		    }
		    #mdialTamanio2{
		      width: 45% !important;
		    }
		    #comparativo{
		      width: 50% !important;
		    }
		    #csv{
		      width: 30% !important;
		    }
		    input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
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
					<li>....</li><li>....</li><li>POAS Aprobados</li><li><?php if($proyecto[0]['tp_id']==1){echo "Mis Componentes";}else{echo "Mis Servicios";} ?></li><li>Mis Requerimientos - <?php echo $this->session->userData('gestion') ?></li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
					<section id="widget-grid" class="well">
						<div title="<?php echo $proyecto[0]['aper_id'];?>">
							<?php echo $datos_cite;?>
							<?php echo $titulo;?>
						</div>
					</section>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-4">
					<div class="well">
						<div class="btn-group btn-group-justified">
							<?php
								if($verif_mod==1){
									if($cite[0]['cite_estado']==1){ ?>
										<a href="#" data-toggle="modal" data-target="#modal_cerrar"  class="btn btn-success" title="MODIFICACION FINANCIERA CERRADA"><i class="fa fa-save"></i> <b>MODIFICACI&Oacute;N CONCLUIDA</b></a>
									<?php
									}
									else{ ?>
										<a href="#" data-toggle="modal" data-target="#modal_cerrar" class="btn btn-warning cerrar" title="CERRAR MODIFICACION FINANCIERA"><i class="fa fa-save"></i> <b>CERRAR MOD.</b></a>		
										<?php
									}
								}
							?>
							<a href="#" data-toggle="modal" data-target="#modal_comparativo" name="<?php echo $cite[0]['proy_id']; ?>" id="<?php echo $tit_comp;?>" class="btn btn-default comparativo" title="MOSTRAR CUADRO COMPARATIVO PRESUPUESTARIA ASIGANDO-POA"><i class="fa fa-clipboard"></i> <b>COMPARATIVO PPTO.</b></a>
							<?php
								if($verif_mod==1){ ?>
									<a href="javascript:abreVentana('<?php echo site_url("").'/mod/rep_mod_financiera/'.$cite[0]['cite_id'].'' ?>');" class="btn btn-default" title="IMPRIMIR REPORTE DE MODIFICACION FINANCIERA"><i class="fa fa-file-pdf-o"></i> <b>PRINT REPORTE</b></a>
									<?php
								}
							?>
						</div><hr>
						<div class="btn-group btn-group-justified">
							<a class="btn btn-default" href="<?php echo base_url(); ?>assets/video/Plantilla_migracion_requerimiento_mod.xlsx" download  title="DESCARGAR ARCHIVO DE MIGRACIÓN EXCEL"><i class="glyphicon glyphicon-download"></i> DESCARGAR ARCHIVO</a>
							<a class="btn btn-danger" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS SELECCIONADOS"><i class="glyphicon glyphicon-trash"></i> DELETE INSUMOS (SELECCIONADOS)</a>
						</div>
					</div>
		        </article>
		        
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="jarviswidget jarviswidget-color-darken">
                      <header>
                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                          <h2 class="font-md"><strong>MIS REQUERIMIENTOS - <?php echo $this->session->userData('gestion') ?></strong></h2>  
                      </header>
						<div>
							<?php 
			                  if($this->session->flashdata('success')){ ?>
			                    <div class="alert alert-success" style="font-size: 5pt;">
			                      	<?php echo $this->session->flashdata('success'); ?>
			                    </div>
			                    <script type="text/javascript">alertify.success("<?php echo '<font size=1>'.$this->session->flashdata('success').'</font>'; ?>")</script>
			                <?php }
			                  elseif($this->session->flashdata('danger')){ ?>
			                      <div class="alert alert-danger">
			                        <?php echo $this->session->flashdata('danger'); ?>
			                      </div>
			                      <script type="text/javascript">alertify.error("<?php echo '<font size=1>'.$this->session->flashdata('danger').'</font>'; ?>")</script>
			                    <?php
			                  }

								if($monto[3]>19){ ?>
									<a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success nuevo_ff" title="NUEVO REGISTRO - REQUERIMIENTOS" style="width:14%;">NUEVO REGISTRO</a>
									<a href="#" data-toggle="modal" data-target="#modal_importar" class="btn btn-default" title="SUBIR ARCHIVO -  REQUERIMIENTO" style="width:14%;">SUBIR ARCHIVO</a><br><br>
									<?php
								}
							?>
							<div class="widget-body no-padding">
								<form id="del_req" name="del_req" novalidate="novalidate" action="<?php echo site_url().'/modificaciones/cmod_insumo/delete_select_requerimientos'?>" method="post">
									<input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
									<?php echo $tabla;?>
									<input type="hidden" name="tot" id="tot" value="0">
			                        <!-- <div class="alert alert-danger" align=right><input type="button" class="btn btn-danger btn-xs" value="ELIMINAR REQUERIMIENTOS SELECCIONADOS .." id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR REQUERIMIENTOS SELECCIONADOS"></div> -->
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

		<!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
        <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
            	<div class="modal-header">
                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                </div>
                <div class="modal-body">
                	<h2 class="alert alert-info"><center>NUEVO REQUERIMIENTO</center></h2>
                    <form action="<?php echo site_url().'/modificaciones/cmod_insumo/valida_add_insumo'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                        <input type="hidden" name="cite_id" id="cite_id" value="<?php echo $cite[0]['cite_id'];?>">
                        <header>
                        	<b>DATOS GENERALES DEL REQUERIMIENTO</b>
                        	<label class="label"><?php echo $titulo;?></label>
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
								<header><b>DISTRIBUCI&Oacute;N FINANCIERA : <?php echo $this->session->userdata('gestion')?></b><br>
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
								<label class="label"><?php echo $titulo;?></label>
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
										<label class="label"><b><?php echo $tit;?></b></label>
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

	    <!-- MODAL COMPARATIVO   -->
	    <div class="modal fade" id="modal_comparativo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	        <div class="modal-dialog" id="comparativo">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
	                </div>
	                <div class="modal-body">
	                	<h2><center>CUADRO COMPARATIVO DE PRESUPUESTO ASIGNADO VS POA - <?php echo $this->session->userData('gestion');?></center></h2>
	                
	                    <div class="row">
	                    	<div id="titulo"></div>	
	                    	<br>
	                        <div id="cuadro_comparativo"></div>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	 	<!--  =============== -->
	 <!-- MODAL CERRAR   -->
        <div class="modal fade" id="modal_cerrar" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	          <div class="modal-dialog" id="csv">
	            <div class="modal-content">
	            	<div class="modal-header">
                    	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
                    </div>
	              <div class="modal-body">
	              		<?php
	              			if($cite[0]['cite_estado']==0){
	              				echo '<h2 class="alert alert-warning"><center>CERRAR MODIFICACI&Oacute;N FINANCIERA</center></h2>';
	              			}
	              			else{
	              				echo '<h2 class="alert alert-success"><center>MODIFICACI&Oacute;N FINANCIERA CONCLUIDA</center></h2>';
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
	                                <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>CERRANDO MODIFICACI&Oacute;N FINANCIERA</b>
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
	        <div class="modal-dialog" id="csv">
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
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		function justNumbers(e){
	        var keynum = window.event ? window.event.keyCode : e.which;
	        if ((keynum == 8) || (keynum == 46))
	        return true;
	             
	        return /\d/.test(String.fromCharCode(keynum));
	    }


		function valida_eliminar(){
			if(document.del_req.tot.value!=0){
				alertify.confirm("ESTA SEGURO DE ELIMINAR "+document.del_req.tot.value+" REQUERIMIENTOS ?", function (a) {
		            if (a) {
		                document.getElementById("btsubmit").value = "ELIMINANDO REQUERIMIENTOS...";
		                document.getElementById("btsubmit").disabled = true;
		                document.del_req.submit();
		                return true;
		            } else {
		                alertify.error("OPCI\u00D3N CANCELADA");
		            }
		         });
			}
			else{
				alertify.error("SELECCIONE REQUERIMIENTOS A ELIMINAR !!! ");
			}
	     }
		</script>

		<!-- CERRAR MODIFICACION FINANCIERA -->
        <script type="text/javascript">
        $(function () {
            $("#cerrar_mod").on("click", function () {
                var $validator = $("#form_cerrar").validate({
                        rules: {
                            cite_id: { //// cite
                            	required: true,
                            },
                            observacion: { //// Observacion
                                required: true,
                            }
                        },
                        messages: {
                            observacion: "<font color=red>REGISTRE OBSERVACIÓN</font>",                     
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

                var $valid = $("#form_cerrar").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                	alertify.confirm("CERRAR MODIFICACIÓN FINANCIERA ?", function (a) {
                        if (a) {
                            document.getElementById("mload").style.display = 'block';
                            document.forms['form_cerrar'].submit();
                            document.getElementById("mbut").style.display = 'none';
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
                $(".comparativo").on("click", function (e) {
                    proy_id = $(this).attr('name');
                    establecimiento = $(this).attr('id');
                    
                    $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
                    $('#cuadro_comparativo').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Cuadro Comparativo Presupuestario - <br>'+establecimiento+'</div>');
                    
                    var url = "<?php echo site_url("")?>/modificaciones/cmod_insumo/get_comparativo_ptto";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "proy_id="+proy_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        $('#cuadro_comparativo').fadeIn(1000).html(response.tabla);
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
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
		<script type="text/javascript">
            /*------ MODIFICAR REQUERIMIENTO -----*/
            $(function () {
                $(".mod_ff").on("click", function (e) {
                    ins_id = $(this).attr('name');
                    document.getElementById("ins_id").value=ins_id;
           			cite_id=document.getElementById("cite_id").value;
           		
           			//alert(ins_id+'--'+cite_id)
           			var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_requerimiento'?>";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "ins_id="+ins_id+"&cite_id="+cite_id
                    });

                    request.done(function (response, textStatus, jqXHR) {

                    if (response.respuesta == 'correcto') {

                    	if(response.verif_cert==1){
                    		$( "#detalle" ).prop( "disabled", true );
                    		//$( "#costou" ).prop( "disabled", true );
                    		$( "#umedida" ).prop( "disabled", true );
                    		$( "#par_padre" ).prop( "disabled", true );
                    		$( "#par_hijo" ).prop( "disabled", true );
                    		$( "#observacion" ).prop( "disabled", true );
                    		if(response.monto_certificado==response.prog[0]['programado_total']){
                    			$( "#cantidad" ).prop( "disabled", true );
                    		}
                    	}
                    	else{
                    		$( "#detalle" ).prop( "disabled", false );
                    		$( "#cantidad" ).prop( "disabled", false );
                    		//$( "#costou" ).prop( "disabled", false );
                    		$( "#umedida" ).prop( "disabled", false );
                    		$( "#par_padre" ).prop( "disabled", false );
                    		$( "#par_hijo" ).prop( "disabled", false );
                    		$( "#observacion" ).prop( "disabled", false );
                    	}

                       document.getElementById("saldo").value = parseFloat(response.monto_saldo).toFixed(2);
                       document.getElementById("sal").value = parseFloat(response.monto_saldo).toFixed(2);
                       document.getElementById("monto_dif").value = parseFloat(response.saldo_dif).toFixed(2);
                       document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
                       document.getElementById("cantidad").value = response.insumo[0]['ins_cant_requerida'];
                       document.getElementById("costou").value = parseFloat(response.insumo[0]['ins_costo_unitario']).toFixed(2);
                       document.getElementById("costot").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
                       document.getElementById("costot2").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
                       document.getElementById("umedida").value = response.insumo[0]['ins_unidad_medida'];
                       document.getElementById("par_padre").value = response.ppdre[0]['par_codigo'];
                       document.getElementById("par_hijo").value = response.insumo[0]['par_id'];
                       document.getElementById("par_id").value = response.insumo[0]['par_id'];
                       document.getElementById("mtot").value = response.prog[0]['programado_total'];
                       document.getElementById("observacion").value = response.insumo[0]['ins_observacion'];
                       document.getElementById("monto_cert").value = response.monto_certificado;
                       $("#par_hijo").html(response.lista_partidas);
                       $("#id").html(response.lista_prod_act);
                       $('#monto').html('<font color=blue size=2><b>MONTO CERTIFICADO : '+response.monto_certificado+'</b></font>');
                       $('#ff').html('FUENTE DE FINANCIAMIENTO : '+response.prog[0]['ff_codigo']+' || ORGANISMO FINANCIADOR : '+response.prog[0]['of_codigo']);
                       if(response.prog[0]['programado_total']!=response.insumo[0]['ins_costo_total']){
                       	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                       	$('#mbut').slideUp();
                       }

                       for (var i = 1; i <=12; i++) {
                       	mes=mes_texto(i);
                       
                       	document.getElementById("mm"+i).value = response.prog[0]['mes'+i];
                     
                       	if(response.verif_mes['verf_mes'+i]==1){
                       		document.getElementById("mm"+i).disabled = true;
                       		$('#mess'+i).html('<font color=red><b>'+mes+' (*)</b></font>');
                       	}
                       	else{
                       		document.getElementById("mm"+i).disabled = false;
                       		$('#mess'+i).html('<b>'+mes+'</b>');
                       	}
                       }

                       if(response.monto_certificado==response.prog[0]['programado_total']){
                       	$('#titulo_req').html('<center><h2 class="alert alert-danger">REQUERIMIENTO CERTIFICADO</h2></center>');
                       	$('#mbut').slideUp();
                       }
                       else{
                       	$('#titulo_req').html('<center><h2 class="alert alert-info">MODIFICAR REQUERIMIENTO</h2></center>');
                       	$('#mbut').slideDown();
                       }

                       	if(response.prog[0]['programado_total']>response.monto_saldo){
				        	$('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL ES MAYOR AL SALDO, VERIFIQUE MONTOS</div></center>');
				        	$('#mbut').slideUp();
				        }
				        else{
				        	if(response.monto_certificado==response.prog[0]['programado_total']){
		                       	$('#titulo_req').html('<center><h2 class="alert alert-danger">REQUERIMIENTO CERTIFICADO</h2></center>');
		                       	$('#mbut').slideUp();
		                    }
		                    else{
		                    	$('#amtit').html('');
					        	$('#mbut').slideDown();
		                    }
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
                                id: { //// id
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
                                ins_id: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>",
                                detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                                cantidad: "<font color=red>CANTIDAD</font>",
                                costou: "<font color=red>COSTO UNITARIO</font>",
                                costot: "<font color=red>COSTO TOTAL</font>",
                                umedida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
                                par_padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                                par_hijo: "<font color=red>SELECCIONE PARTIDA</font>", 
                                id: "<font color=red>SELECCIONE VINCULACIÓN</font>",                     
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
                    
                        	if(dif>=0){
	                        		alertify.confirm("MODIFICAR REQUERIMIENTO ?", function (a) {
	                                if (a) {
	                                    document.getElementById("loadm").style.display = 'block';
	                                    document.getElementById("subir_mins").value = "MODIFICANDO REQUERIMIENTO...";
	                                    document.getElementById('subir_mins').disabled = true;
	                                    document.forms['form_mod'].submit();
	                                } else {
	                                    alertify.error("OPCI\u00D3N CANCELADA");
	                                }
	                            });
                        	}
                        	else{
                        		$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                        		alertify.error("EL MONTO PROGRAMADO NO PUEDE SER MAYO AL MONTO SALDO DE LA OPERACIÓN, VERIFIQUE MONTOS");
                        	}
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
                            cite_id: { //// cite
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
                            ins_um: { //// unidad medida
                                required: true,
                            },
                            padre: { //// par padre
                                required: true,
                            },
                            partida_id: { //// par hijo
                                required: true,
                            },
                            dato_id: { //// dato id
                                required: true,
                            }
                        },
                        messages: {
                            ins_detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                            ins_cantidad: "<font color=red>CANTIDAD</font>",
                            ins_costo_u: "<font color=red>COSTO UNITARIO</font>",
                            costo: "<font color=red>COSTO TOTAL</font>",
                            ins_um: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
                            padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                            partida_id: "<font color=red>SELECCIONE PARTIDA</font>", 
                            dato_id: "<font color=red>ACTIVIDAD</font>",                     
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
                	saldo=document.getElementById("saldo").value;
                    programado=document.getElementById("tot").value;
                    dif=saldo-programado;
                    if(dif>=0){
                    	alertify.confirm("DESEA GUARDAR REQUERIMIENTO ?", function (a) {
	                        if (a) {
	                        	document.getElementById("loada").style.display = 'block';
	                            document.getElementById("subir_ins").value = "GUARDANDO REQUERIMIENTO...";
	                            document.getElementById('subir_ins').disabled = true;
	                            document.forms['form_nuevo'].submit();
	                        } else {
	                            alertify.error("OPCI\u00D3N CANCELADA");
	                        }
	                    });
                    }
                    else{
                    	$('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                        alertify.error("EL MONTO PROGRAMADO NO PUEDE SER MAYO AL MONTO SALDO DE LA OPERACIÓN, VERIFIQUE MONTOS");
                    }
                }
            });
        });
        </script>
        <script>
	    $(function () {
		    //SUBIR ARCHIVO
		    $("#subir_archivo").on("click", function () {
		        var $valid = $("#form_subir_sigep").valid();
		        if (!$valid) {
		            $validator.focusInvalid();
		        } else {
		        	if(document.getElementById('archivo').value==''){
		        		alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
		        		return false;
		        	}
	                alertify.confirm("SUBIR ARCHIVO REQUERIMIENTOS.CSV?", function (a) {
	                    if (a) {
	                        document.getElementById("subir_archivo").value = "AGREGANDO REQUERIMIENTOS...";
	                        document.getElementById("loads").style.display = 'block';
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
		            var ins_id = $(this).attr('name'); // ins id
		            var cite_id = "<?php echo $cite[0]['cite_id'];?>"; // cite id
		            //alert(ins_id)
		            var request;
		            alertify.confirm("ESTA SEGURO DE ELIMINAR EL REQUERIMIENTO ?", function (a) {
		                if (a) {
		                	url = "<?php echo site_url().'/modificaciones/cmod_insumo/delete_requerimiento'?>";
		                    if (request) {
		                        request.abort();
		                    }
		                    request = $.ajax({
		                        url: url,
		                        type: "POST",
		                        dataType: "json",
                    			data: "ins_id="+ins_id+"&cite_id="+cite_id
		                    });

		                    request.done(function (response, textStatus, jqXHR) { 
			                    reset();
			                    if (response.respuesta == 'correcto') {
			                        alertify.alert("EL REQUERIMIENTO SE ELIMINO CORRECTAMENTE ", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
			                    } else {
			                        alertify.alert("ERROR AL ELIMINAR REQUERIMIENTO !!!", function (e) {
			                            if (e) {
			                                window.location.reload(true);
			                            }
			                        });
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
		                    alertify.error("Opcion cancelada");
		                }
		            });
		            return false;
		        });

		    });
		</script>

		<script type="text/javascript">
		/*---------- MONTO PARTIDA (NUEVO) ------------*/
		$(document).ready(function () {
        	$("#partida_id").change(function () {            
            var par_id = $(this).val(); /// Par id
            proy=<?php echo $proyecto[0]['proy_id']; ?>; /// Proy id
            //alert(par_id)
            	var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_monto_partida'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "par_id="+par_id+"&proy_id="+proy
                });

                request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                	$('[name="saldo"]').val((response.monto).toFixed(2));

                	costo = parseFloat($('[name="costot"]').val()); //// costo Total Programado
                	saldo_partida=parseFloat($('[name="saldo"]').val()); /// saldo partida
                	total_programado = parseFloat($('[name="tot"]').val()); /// total programado (Temporalidad)

                	if(response.monto!=0){
                		if((parseFloat(costo).toFixed(2)<=parseFloat(saldo_partida).toFixed(2)) & (parseFloat(costo).toFixed(2)==parseFloat(total_programado).toFixed(2))){
                			$('#atit').html('');
                		}
                		else{
                			$('#atit').html('<center><div class="alert alert-danger alert-block">LOS MONTOS DEBEN SER CORREGIDOS</div></center>');
			            	$('#but').slideUp();
                		}
                	}
                	else{
                		$('#atit').html('<center><div class="alert alert-danger alert-block">NO EXISTE PRESUPUESTO DISPONIBLE EN ESA PARTIDA</div></center>');
			            $('#but').slideUp();
                	}

                }
                else{
                    alertify.error("ERROR AL RECUPERAR MONTO ASIGNADO");
                }

                });
            });
        });

        /*---- MONTO PARTIDA (MODIFICACION) ----*/
		$(document).ready(function () {
        	$("#par_hijo").change(function () {            
            var par_id = $(this).val();
            proy=<?php echo $proyecto[0]['proy_id']; ?>;
            costo = parseFloat($('[name="costot"]').val()); //// costo
            alert(par_id)

            	var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_monto_partida'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "par_id="+par_id+"&proy_id="+proy
                });

                request.done(function (response, textStatus, jqXHR) {

                if (response.respuesta == 'correcto') {
                	par_id1 = parseFloat($('[name="par_id"]').val()); //// par id 
                	costo = parseFloat($('[name="costot"]').val()); //// Costo

                	if(par_id1==par_id){
                		document.getElementById("saldo").value = parseFloat(response.monto+costo).toFixed(2);
	                	document.getElementById("sal").value = parseFloat(response.monto+costo).toFixed(2);
	                	saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
	                	$('[name="monto_dif"]').val((parseFloat($('[name="sal"]').val())-costo).toFixed(2));
                	}
                	else{
                		document.getElementById("saldo").value = parseFloat(response.monto).toFixed(2);
	                	document.getElementById("sal").value = parseFloat(response.monto).toFixed(2);
	                	saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
	                	$('[name="monto_dif"]').val((parseFloat($('[name="sal"]').val())-costo).toFixed(2));
                	}
                	
                	if(costo>saldo_partida){
                		$('#matit').html('<center><div class="alert alert-danger alert-block">MONTO PROGRAMADO SUPERA AL MONTO SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                		$('#mbut').slideUp();
                	}
                	else{
                		programado = parseFloat($('[name="mtot"]').val()); //// saldo partida
                		if(programado!=costo){
				        	$('#matit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
			                $('#mbut').slideUp();
				        }
				        else{
				        	$('[name="monto_dif"]').val((saldo_partida-costo).toFixed(2));
				        	$('#matit').html('');
			                $('#mbut').slideDown();
				        }
                	}
                }
                else{
                    alertify.error("ERROR AL RECUPERAR MONTO ASIGNADO");
                }

                });
            });
        });

		/*---------- PARTIDAS ------------*/
		$(document).ready(function() {
			pageSetUp();
				$("#padre").change(function () {
	                $("#padre option:selected").each(function () {
	                elegido=$(this).val();
	                aper=<?php echo $proyecto[0]['aper_id']; ?>;
	                $('[name="saldo"]').val((0).toFixed(2));
	                $('#atit').html('');
	                $('#but').slideUp();

	                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas_asig", { elegido: elegido,aper:aper }, function(data){ 
	                $("#partida_id").html(data);
	                });     
	            });
            });

			$("#partida_id").change(function () {
                $("#partida_id option:selected").each(function () {
	                elegido=$(this).val();
	                $.post("<?php echo base_url(); ?>index.php/prog/combo_umedida", { elegido: elegido }, function(data){ 
	                $("#ins_um").html(data);
	                });     
	            });
            }); 
		})


		$(document).ready(function() {
			pageSetUp();
			$("#par_padre").change(function () {
                $("#par_padre option:selected").each(function () {
                elegido=$(this).val();
                aper=<?php echo $proyecto[0]['aper_id']; ?>;
               	$('[name="sal"]').val((0).toFixed(2));
               	$('[name="saldo"]').val((0).toFixed(2));
               	$('[name="monto_dif"]').val((0).toFixed(2));
                $('#amtit').html('');
                $('#mbut').slideUp();

                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas_asig", { elegido: elegido,aper:aper }, function(data){ 
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
	        saldo = parseFloat($('[name="saldo"]').val()); //// saldo

	        if(programado!=ctotal){

	        	$('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                $('#but').slideUp();
	        }
	        else{
	        	if(ctotal>saldo){
	        		$('#atit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                	$('#but').slideUp();
	        	}
	        	else{
	        		$('#atit').html('');
                	$('#but').slideDown();
	        	}
	        	
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
	        saldo = parseFloat($('[name="sal"]').val()); //// saldo

	        if(programado!=ctotal){
	        	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                $('#mbut').slideUp();
	        }
	        else{
	        	if(ctotal>saldo){
	        		$('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                	$('#mbut').slideUp();
	        	}
	        	else{
	        		$('#amtit').html('');
	        		$('#mbut').slideDown();
	        	}
	        }
	    }

	    function costo_totalm(){ 
	        s = parseFloat($('[name="sal"]').val()); //// saldo
	        a = parseFloat($('[name="cantidad"]').val()); //// cantidad
	        b = parseFloat($('[name="costou"]').val()); //// Costo
	        
	        $('[name="costot"]').val((b*a).toFixed(2) );
	        $('[name="costot2"]').val((b*a).toFixed(2) );

	        ct = parseFloat($('[name="costot"]').val()); //// total
	        mt = parseFloat($('[name="mtot"]').val()); //// prog

	        saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
	        $('[name="monto_dif"]').val((saldo_partida-ct).toFixed(2) ); // Saldo Disponible

	        if(ct!=mt ||  isNaN(a)){
	        	$('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#mbut').slideUp();
	        }
	        else{
	        	if(ct>saldo_partida){
	        		$('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                	$('#mbut').slideUp();
	        	}
	        	else{
	        		$('#amtit').html('');
	        		$('#mbut').slideDown();
	        	}
	        	
	        }
	    }

	   	function costo_total(){ 
	        a = parseFloat($('[name="ins_cantidad"]').val()); //// cantidad
	        b = parseFloat($('[name="ins_costo_u"]').val()); //// Costo unitario
	        
			$('[name="costo"]').val((b*a).toFixed(2) );
	        $('[name="costo2"]').val((b*a).toFixed(2) );

	        ct = parseFloat($('[name="costo"]').val()); //// total
	        mt = parseFloat($('[name="tot"]').val()); //// prog
	        saldo_partida = parseFloat($('[name="saldo"]').val()); //// saldo partida
	        $('[name="saldo_disp"]').val((saldo_partida-ct).toFixed(2) ); // Saldo Disponible

	        if(ct!=mt ||  isNaN(a) || ct==0){
	        	$('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#but').slideUp();
	        }
	        else{
	        	if(ct>saldo_partida){
	        		$('#atit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                	$('#but').slideUp();
	        	}
	        	else{
	        		$('#atit').html('');
                	$('#but').slideDown();
	        	}
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
			function mes_texto(mes){
	        switch (mes) {
	            case 1:
	                texto = 'ENERO';
	                break;
	            case 2:
	                texto = 'FEBRERO';
	                break;
	            case 3:
	                texto = 'MARZO';
	                break;
	            case 4:
	                texto = 'ABRIL';
	                break;
	            case 5:
	                texto = 'MAYO';
	                break;
	            case 6:
	                texto = 'JUNIO';
	                break;
	            case 7:
	                texto = 'JULIO';
	                break;
	            case 8:
	                texto = 'AGOSTO';
	                break;
	            case 9:
	                texto = 'SEPTIEMBRE';
	                break;
	            case 10:
	                texto = 'OCTUBRE';
	                break;
	            case 11:
	                texto = 'NOVIEMBRE';
	                break;
	            case 12:
	                texto = 'DICIEMBRE';
	                break;
	            default:
	                texto = 'SIN REGISTRO';
	                break;
	        }
	        return texto;
	    }
		</script>
	</body>
</html>