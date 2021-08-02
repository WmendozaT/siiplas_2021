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
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
	    <meta name="viewport" content="width=device-width">
	    <script type="text/javascript">
		  function abreVentana_comparativo(PDF){             
		      var direccion;
		      direccion = '' + PDF;
		      
		      window.open(direccion, "Objetivos Regionales" , "width=700,height=600,scrollbars=NO") ; 
		  }
		</script>
		<style>
			table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
	          width: 70% !important;
	        }
	        #mdialTamanio2{
	          width: 45% !important;
	        }
		</style>
	</head>
	<body class="">
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="#" title="PROGRAMACION"> <span class="menu-item-parent">PROGRAMACI&Oacute;N DEL POA</span></a>
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
					<li>Mis Acciones de Corto Plazo</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">

	                <div class="row">
	                    <?php 
	                    echo $titulo;
	                    
		                  if($this->session->flashdata('success')){ ?>
		                    <div class="alert alert-success">
		                      	<?php echo $this->session->flashdata('success'); ?>
		                    </div>
		                    <script type="text/javascript">alertify.success("<?php echo '<font size=2>'.$this->session->flashdata('success').'</font>'; ?>")</script>
		                <?php 
		                    }
		                  elseif($this->session->flashdata('danger')){ ?>
		                      <div class="alert alert-danger">
		                        <?php echo $this->session->flashdata('danger'); ?>
		                      </div>
		                      <script type="text/javascript">alertify.error("<?php echo '<font size=2>'.$this->session->flashdata('danger').'</font>'; ?>")</script>
		                    <?php
		                  }
		                ?>
	                    <?php echo $ogestion;?>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
	</div>
	<!-- END MAIN PANEL -->

<!-- MODAL NUEVO REGISTRO DE OBJETIVOS DE GESTION   -->
  <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio">
      <div class="modal-content">
      	  <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>NUEVO REGISTRO - ACCI&Oacute;N DE CORTO PLAZO</center></h2>
              <form action="<?php echo site_url().'/mestrategico/cobjetivo_gestion/valida_ogestion';?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="tp" id="tp" value="1">
                  <input type="hidden" name="form" id="form" value="0"> 
                  <header><b>ALINEACION PEI</b></header>
                  <fieldset>
                  	<div class="row">
	                      <section class="col col-4">
	                        <label class="label">OBJETIVO ESTRATEGICO</label>
	                        <select class="form-control" id="obj_id" name="obj_id" title="SELECCIONE OBJETIVO ESTRATEGICO">
	                          <option value="">Seleccione Objetivo Estrategico</option>
	                            <?php 
	                              foreach($oestrategicos as $row){ ?>
	                                <option value="<?php echo $row['obj_id']; ?>"><?php echo $row['obj_codigo'].'.- '.$row['obj_descripcion']; ?></option>
	                                <?php   
	                              }
	                            ?>
	                        </select>
	                      </section>
	                      <section class="col col-4">
	                        <label class="label">ACCI&Oacute;N ESTRATEGICA</label>
	                        <select class="form-control" id="acc_id" name="acc_id" title="SELECCIONE ACCIÓN ESTRATEGICA"></select>
	                      </section>
                  	</div>
                  </fieldset>

                  <header><b>DATOS GENERALES ACCI&Oacute;N DE CORTO PLAZO</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-1">
                        <label class="label">C&Oacute;DIGO</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="cod" id="cod" value="0" title="CODIGO ACP" onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }" onpaste="return false" onkeyup="verif_codigo()">
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">ACCI&Oacute;N DE CORTO PLAZO</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="ogestion" id="ogestion" title="REGISTRAR OBJETIVO"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">PRODUCTO</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="producto" id="producto" title="REGISTRAR PRODUCTO"></textarea>
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">RESULTADO</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="resultado" id="resultado" title="REGISTRAR RESULTADO"></textarea>
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-3">
                        <label class="label">TIPO DE INDICADOR</label>
                        <select class="form-control" id="tp_indi" name="tp_indi" title="SELECCIONE TIPO DE INDICADOR">
                            <option value="">Seleccione Tipo de Indicador</option>
                            <?php 
                              foreach($indi as $row){ ?>
                              <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                            <?php } ?>        
                        </select>
                      </section>
                      <section class="col col-5">
                        <label class="label">INDICADOR</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="indicador" id="indicador" title="REGISTRE INDICADOR"></textarea>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">LINEA BASE</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="lbase" id="lbase" value="0" title="REGISTRE LINEA BASE">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">META</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="meta" id="meta" value="0" title="REGISTRE META" onkeyup="fmeta()">
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-4">
                        <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="verificacion" id="verificacion" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">UNIDAD/AREA RESPONSABLE</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="unidad" id="unidad" title="REGISTRE UNIDAD RESPONSABLE"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="observacion" id="observacion" title="REGISTRE OBSERVACIÓN"></textarea>
                        </label>
                      </section>
                    </div>
                    <br>
                    <div id="atit"></div>
                    <header><b>DISTRIBUCI&Oacute;N REGIONAL : <?php echo $this->session->userdata('gestion')?></b><br>
                    	<label class="label"><div id="ff"></div></label>
                    </header><br>

                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><font color=blue><b>PROGRAMADO TOTAL</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="tot" id="tot" value="0" disabled="true">
                        </label>
                      </section>


                      <section class="col col-2">
                        <label class="label">CHUQUISACA</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">LA PAZ</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">COCHABAMBA</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">ORURO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">POTOSI</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-2">
                      </section>

                      <section class="col col-2">
                        <label class="label">TARIJA</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">SANTA CRUZ</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">BENI</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">PANDO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">OFICINA NACIONAL</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>

                    <header><b>TEMPORALIDAD FÍSICA : <?php echo $this->session->userdata('gestion')?></b><br>
                    </header>
                    <br>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">TOTAL</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="total_temp" id="total_temp" value="0" disabled="true">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>ENERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes1" id="mes1" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ENERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>FEBRERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes2" id="mes2" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MARZO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes3" id="mes3" value="0" onkeyup="suma_programado_temporalidad()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MARZO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>ABRIL</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes4" id="mes4" value="0" onkeyup="suma_programado_temporalidad()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ABRIL - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MAYO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes5" id="mes5" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MAYO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>JUNIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes6" id="mes6" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JUNIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>JULIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes7" id="mes7" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JULIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>AGOSTO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes8" id="mes8" value="0" onkeyup="suma_programado_temporalidad()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>SEPTIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes9" id="mes9" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>OCTUBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes10" id="mes10" value="0" onkeyup="suma_programado_temporalidad()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>NOVIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes11" id="mes11" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>DICIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="mes12" id="mes12" value="0" onkeyup="suma_programado_temporalidad()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                  </fieldset>
        
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_act" id="subir_act" class="btn btn-info" >GUARDAR OBJETIVO GESTI&Oacute;N</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                  </div>
              </form>
              </div>
          </div>
      </div>
  </div>
  <!--  ========================================================= -->
  <!-- ============ Modal Modificar Objetivo de Gestion ========= -->
      <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
            <div class="modal-header">
            	<button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          	</div>
              <div class="modal-body">
                <h2 class="alert alert-info"><center>MODIFICAR ACCI&Oacute;N DE CORTO PLAZO</center></h2>
                <form action="<?php echo site_url().'/mestrategico/cobjetivo_gestion/valida_ogestion';?>" method="post" id="form_mod" name="form_mod" class="smart-form">
                <input type="hidden" name="tp" id="tp" value="2"> 
              	<input type="hidden" name="mog_id" id="mog_id">
              	<input type="hidden" name="form" id="form" value="0"> 

              	<header><b>ALINEACION PEI</b></header>
              	<fieldset>
	              	<div class="row">
                      <section class="col col-4">
                        <label class="label">OBJETIVO ESTRATEGICO</label>
                        <select class="form-control" id="mobj_id" name="mobj_id" title="SELECCIONE OBJETIVO ESTRATEGICO">
                          <option value="">Seleccione Objetivo Estrategico</option>
                            <?php 
                              foreach($oestrategicos as $row){ ?>
                                <option value="<?php echo $row['obj_id']; ?>"><?php echo $row['obj_codigo'].'.- '.$row['obj_descripcion']; ?></option>
                                <?php   
                              }
                            ?>
                        </select>
                      </section>
                      <section class="col col-4">
                        <label class="label">ACCI&Oacute;N ESTRATEGICA</label>
                        <select class="form-control" id="macc_id" name="macc_id" title="SELECCIONE ACCIÓN ESTRATEGICA"></select>
                      </section>
	              	</div>
              	</fieldset>
                <header><b>DATOS GENERALES ACCI&Oacute;N DE CORTO PLAZO</b></header>
                <fieldset>          
                  <div class="row">
                  	<section class="col col-1">
                        <label class="label">C&Oacute;DIGO</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="mcod" id="mcod" disabled="true">
                        </label>
                      </section>
                    <section class="col col-3">
                      <label class="label">ACCI&Oacute;N DE CORTO PLAZO</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="mogestion" id="mogestion" title="MODIFICAR OBJETIVO DE GESTION"></textarea>
                      </label>
                    </section>
                    <section class="col col-4">
                      <label class="label">PRODUCTO</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="mproducto" id="mproducto" title="MODIFICAR PRODUCTO"></textarea>
                      </label>
                    </section>
                    <section class="col col-4">
                      <label class="label">RESULTADO</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="3" name="mresultado" id="mresultado" title="MODIFICAR RESULTADO"></textarea>
                      </label>
                    </section>
                  </div>

                  	<div class="row">
                      <section class="col col-3">
                        <label class="label">TIPO DE INDICADOR</label>
                        <select class="form-control" id="mtp_indi" name="mtp_indi" title="SELECCIONE TIPO DE INDICADOR">
                            <option value="">Seleccione Tipo de Indicador</option>
                            <?php 
                              foreach($indi as $row){ ?>
                              <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                            <?php } ?>        
                        </select>
                      </section>
                      <section class="col col-5">
                        <label class="label">INDICADOR</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="mindicador" id="mindicador" title="REGISTRE INDICADOR"></textarea>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">LINEA BASE</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="mlbase" id="mlbase" value="0" title="REGISTRE LINEA BASE">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">META</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="mmeta" id="mmeta" value="0" title="REGISTRE META" onkeyup="fmmeta()">
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-4">
                        <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="mverif" id="mverif" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">UNIDAD/AREA RESPONSABLE</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="munidad" id="munidad" title="REGISTRE UNIDAD RESPONSABLE"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="mobservacion" id="mobservacion" title="REGISTRE OBSERVACIÓN"></textarea>
                        </label>
                      </section>
                    </div>
                  <br>
                  <div id="amtit"></div>
                  <header><b>DISTRIBUCIÓN REGIONAL : <?php echo $this->session->userdata('gestion')?></b><br>
                  <label class="label"><div id="ff"></div></label>
                  </header>
                  <br>
                  <div class="row">
                  	<section class="col col-2">
                      <label class="label"><font color="blue"><b>PROGRAMADO TOTAL</b></font></label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mtot" id="mtot" value="0" disabled="true">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">CHUQUISACA</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">LA PAZ</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">COCHABAMBA</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">ORURO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">POTOSI</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                  </div>
                  <div class="row">
                  	<section class="col col-2">
                    </section>
                    <section class="col col-2">
                      <label class="label">TARIJA</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">SANTA CRUZ</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">BENI</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">PANDO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">OFICINA NACIONAL</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title >
                      </label>
                    </section>
                  </div>

                  	<header><b>TEMPORALIDAD F&Iacute;SICA : <?php echo $this->session->userdata('gestion')?></b><br>
		              </header>
		              <br>
		              <div class="row">
		                <section class="col col-2">
		                  <label class="label"><b>PROGRAMADO TOTAL</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="total_temp_mod" id="total_temp_mod" value="0" disabled="true">
		                  </label>
		                </section>
		              </div>
		              <div class="row">
		                <section class="col col-2">
		                  <label class="label"><b>ENERO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes1" id="mmes1" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ENERO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>FEBRERO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes2" id="mmes2" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="FEBRERO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>MARZO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes3" id="mmes3" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MARZO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>ABRIL</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes4" id="mmes4" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ABRIL - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>MAYO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes5" id="mmes5" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MAYO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>JUNIO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes6" id="mmes6" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JUNIO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		              </div>
		              <div class="row">
		                <section class="col col-2">
		                  <label class="label"><b>JULIO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes7" id="mmes7" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JULIO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>AGOSTO</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes8" id="mmes8" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>SEPTIEMBRE</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes9" id="mmes9" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>OCTUBRE</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes10" id="mmes10" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>NOVIEMBRE</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes11" id="mmes11" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		                <section class="col col-2">
		                  <label class="label"><b>DICIEMBRE</b></label>
		                  <label class="input">
		                    <i class="icon-append fa fa-money"></i>
		                    <input type="text" name="mmes12" id="mmes12" value="0" onkeyup="suma_programado_temporalidad_modificado()" onkeypress="return justNumbers(event);" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
		                  </label>
		                </section>
		              </div>
                </fieldset>
                <div id="mbut">
                  <footer>
                    <button type="button" name="subir_mact" id="subir_mact" class="btn btn-info" >MODIFICAR ACCI&Oacute;N DE CORTO PLAZO</button>
                    <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                  </footer>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
    <!-- ======================================================== -->
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
		<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
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
		<script src="<?php echo base_url(); ?>mis_js/accionespoa/ogestion.js"></script> 
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>
