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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/estil.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
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
	                <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MEN&Uacute; PRINCIPAL">
	                <i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
	            	</li>
		            <li class="text-center">
		                <a href="<?php echo base_url().'index.php/admin/proy/list_proy_fin' ?>" title="MIS OPERACIONES"><span class="menu-item-parent">PROGRAMACI&Oacute;N</span></a>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_fin' ?>" title="MIS OPERACIONES">Programaci&oacute;n de Operaciones</a></li><li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_fin' ?>">Responsable Analista Financiero</a></li><li>Asignar Techo Presupuesto</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
							<section id="widget-grid" class="well">
				                <div class="">
				                  	<h1> <?php echo $titulo_proy;?> : <small><?php echo $proyecto[0]['proy_nombre']?></small></h1>
				                  	<h1> APERTURA PROGRAM&Aacute;TICA : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></small>
				                	<?php
				                	if($proyecto[0]['tp_id']==1){ ?>
				                		<h1> FASE : <small><?php echo $fase[0]['fase'] ?></small> || ETAPA : <small><?php echo $fase[0]['etapa'] ?></small></h1>
				                		<h1> FECHA INICIO FASE : <small><?php echo date('d/m/Y',strtotime($fase[0]['inicio'])) ?></small> || FECHA FINAL FASE : <small><?php echo date('d/m/Y',strtotime($fase[0]['final'])) ?></small></h1>
				                		<?php
				                	}
				                	else{ ?>
				                		<h1> FECHA INICIO OPERACI&Oacute;N: <small><?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?></small> || FECHA FINAL OPERACI&Oacute;N: <small><?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?></small></h1>
				                		<?php
				                	}
				                	?>
				                </div>
				            </section>
	                    </article>
	                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
	                        <section id="widget-grid" class="well">
	                        	<?php
	                        		if($proyecto[0]['tp_id']==1){ ?>
	                        			<a href="<?php echo site_url("admin").'/proy/list_proy_fin#tabs-a'; ?>" class="btn btn-success" title="Lista de Operaciones" style="width:100%;">VOLVER ATRAS</a>
	                        			<?php
	                        		}
	                        		else{ ?>
	                        			<a href="<?php echo site_url("admin").'/proy/list_proy_fin'; ?>" class="btn btn-success" title="Lista de Operaciones" style="width:100%;">VOLVER ATRAS</a>
	                        			<?php
	                        		}
	                        	?>
	                        </section>
	                    </article>
	                    
						<article class="col-xs-12 col-sm-12 col-md-10 col-lg-12">
						    <div class="jarviswidget" id="wid-id-3" data-widget-editbutton="false" data-widget-custombutton="false">
						        <header>
						          <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
						          <h2>ASIGNAR TECHO PRESUPUESTO - <?php echo $this->session->userdata('gestion')?></h2>       
						        </header>
						        <div>
						          <div class="jarviswidget-editbox">
						          </div>
						          <div class="widget-body no-padding">
						          	<header>
						          		<br>
						                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						                <button id="add" class="btn btn-sm btn-success" title="Adicionar una columna para asignar presupuesto">Agregar</button>
										<button id="del" class="btn btn-sm btn-danger" title="Elimina la ultima columna">Eliminar</button>
						            	<hr>
						            </header>
						          	<fieldset>
						            <section>
						                <div class="row" align="center">
						                  	<?php echo $techo;?>
						                </div>
						            </section>
						            </fieldset>
						          	
						          </div>
						      </div>
						  	</div>
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
		<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
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

		<script type="text/javascript">
			$(function () {
				$("#mod_tech").on("click", function () {
			        var $valid = $("#form_techo").valid();
			        if (!$valid) {
			            $validator.focusInvalid();
			        } else {

			        	var ptotal = document.getElementById('ptto_gestion').value;
			        	var nro = document.getElementById('contador-filas').value;

			        //	alert(ptotal+'-'+nro)
			        	var suma=parseFloat($('[id="total"]').val());
			            for (var i = 1; i <= nro; i++) {
			            	if((document.getElementById('fi'+i).value=='') || (document.getElementById('ofi'+i).value=='')){
			            	//if((document.getElementById('fi'+i).value=='') || (document.getElementById('ofi'+i).value=='') || (document.getElementById('impo'+i).value=='' || document.getElementById('impo'+i).value==0)){
			            		$('#ctr'+i).css('background-color', '#f39191');
			            		alertify.error("REGISTRE FUENTE DE FINANCIAMIENTO - ORGANISMO FINANCIADOR - IMPORTE DE LA CASILLA "+(parseInt(i)+parseInt(1)));
			            		document.form_techo.cite_id.focus() 
                    			return 0;
			            	}
			            	else{
			            		$('#ctr'+i).css('background-color', '');
			            	}

			            	suma=parseFloat(suma)+parseFloat($('[id="impo'+i+'"]').val());
			            	//	$('[name="total"]').val((suma).toFixed(2));
			            	//	$('[name="saldo"]').val((parseFloat(ptotal)-parseFloat(suma)).toFixed(2));
			            	//alert(parseFloat(ptotal)+'--'+parseFloat(suma))
			            }

			            var total=parseFloat($('[id="total"]').val());
			            var ptto=parseFloat($('[id="ptto_gestion"]').val());
			          	if(parseFloat(total)<=parseFloat(ptto+1)){
			          		alertify.confirm("GUARDAR ASIGNACIÓN DE TECHO PRESUPUESTARIO ?", function (a) {
			                    if (a) {
			                    //    document.getElementById("load3").style.display = 'block';
			                        document.getElementById('mod_tech').disabled = true;
			                        document.forms['form_techo'].submit();
			                    } else {
			                        alertify.error("OPCI\u00D3N CANCELADA");
			                    }
	                		});	
			          	}
			          	else{
			          		alertify.error("EL MONTO PROGRAMADO NO PUEDE SER MENOR A LA SUMA DEL PRESUPUESTO ");
			          	}
			        }
			    });
		    });
		    
			$(document).ready(function(){
			$("#add").click(function(){
			// Obtenemos el numero de columnas (td) que tiene la primera fila
			// (tr) del id "tabla"
			var tds=$("#tabla tr:first td").length;
			var simp=0;
			// Obtenemos el total de filas (tr) del id "tabla"
			var trs=$("#tabla tr").length;
			cant = $('#contador-filas').val();
			cant++;

			var nuevaFila="<tr id='ctr"+(cant)+"'>";
			
			$('#contador-filas').val(cant)
			nuevaFila+=

			"<td><input type='hidden' name='ffofet_id[]' value='0'>"+(cant)+"</td>"+
			"<td><select class='form-control' name='ffin[]' id='fi"+(cant)+"' title='Seleccione Fuente de Financiamiento' required ><option value=''>Seleccione Fuente financiamiento </option><?php
				foreach($ffi as $row){
					?><option value='<?php echo $row['ff_id']; ?>'><?php echo $row['ff_codigo'].' - '.$row['ff_descripcion']; ?></option><?php
				}
			  ?></select></td>"+
			"<td><select class='form-control' name='ofin[]' id='ofi"+(cant)+"' title='Organismo Financiador' required ><option value=''>Seleccione Organismo Financiador</option><?php
				foreach($fof as $row){
					?><option value='<?php echo $row['of_id']; ?>'><?php echo $row['of_codigo'].' - '.$row['of_descripcion']; ?></option><?php
				}
			  ?></select></td>"+
			"<td><input class='form-control' type='text' name='importe[]' id='impo"+(cant)+"' value='0' onkeyup='suma_monto_techo("+trs+");' required onkeypress='if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }' onpaste='return false'/></td>"+
			"<td></td>";
			// Añadimos una columna con el numero total de columnas.
			// Añadimos uno al total, ya que cuando cargamos los valores para la
			// columna, todavia no esta añadida

			nuevaFila+="</tr>";
			$("#tabla").append(nuevaFila);
				/*imp = parseFloat($('[id="impo'+cant+'"]').val());
				simp=simp+imp;
				$('[name="total"]').val((simp).toFixed(2));*/
			});
			/**
			* Funcion para eliminar la ultima columna de la tabla.
			* Si unicamente queda una columna, esta no sera eliminada
			*/
			$("#del").click(function(){
			// Obtenemos el total de filas (tr) del id "tabla"
			//nffofet
			var trs=$("#tabla tr").length;
			var nro_ffofet=parseInt($('[id="nffofet"]').val());
		
			if(trs>(nro_ffofet+1)){
			//	alert(trs-nro_ffofet)
			ptto = parseFloat($('[id="ptto_gestion"]').val());
			total = parseFloat($('[id="total"]').val());
			imp = parseFloat($('[id="impo'+cant+'"]').val());

			$('[name="total"]').val((total-imp).toFixed(2));
			$('[name="saldo"]').val((ptto-parseFloat($('[id="total"]').val())).toFixed(2));

			// Eliminamos la ultima fila
			cant--;
			$('#contador-filas').val(cant)
			$("#tabla tr:last").remove();

				if(cant==0){
					$('#but').slideUp();
				}
			}
			});
			});
		</script>
	</body>
</html>
