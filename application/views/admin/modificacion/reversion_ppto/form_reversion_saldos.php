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
<!-- 		<style>
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
		</style> -->
	</head>
	<body class="">
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
					<li>Modificaciones</li><li>....</li><li>POAS Aprobados</li><li>Reversión de Saldos</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
			<div class="row">
				<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
					<section id="widget-grid" class="well">
						<h2><b> FORMULARIO REVERSION DE SALDOS POR CERTIFICACION POA - GESTI&Oacute;N <?php echo $this->session->userData('gestion') ?><b></h2>
						<?php echo $titulo;?>
					</section>
				</article>
				<article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
          <section id="widget-grid" class="well">
            <a href="<?php echo base_url().'index.php/mod/list_top'?>" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;SALIR A LISTA DE POAS</a>
          </section>
        </article>
			</div>
			<div class="row">
				<?php echo $tabla; ?>
			</div>
			<!-- END MAIN CONTENT -->
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
		<script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
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
		<!-- MAIN APP JS FILE -->
		<script src="<?php echo base_url();?>/assets/js/app.min.js"></script>
		<!-- <script src="<?php echo base_url(); ?>mis_js/modificacionpoa/modppto.js"></script>  -->

     <script type="text/javascript">
        $(function () {
    		$("#generar_datos").on("click", function () {
    				const base = $('#base').val();
    				const proy_id = $('#proy_id').val();
    				const certpoa = $('#certpoa').val();
				    if (certpoa==0) {
				      	alertify.error("SELECCIONE CERTIFICACION POA");
			        	$('#certpoa').focus(); 
			          return 0;
				    }

				    const certppto = $('#certppto').val();
				    if (!certppto) {
				      	alertify.error("REGISTRE CODIGO DE LA CERTIFICACION PRESUPUESTARIA");
			        	$('#certppto').focus();
			          return 0;
				    }


		      var $valid = $("#formulario").valid();
		      if (!$valid) {
		          $validator.focusInvalid();
		      } else {

		      	alertify.confirm("GENERAR REVERSIÓN DE CERTIFICACIÓN POA ?", function (a) {
              if (a) { 
              		$("#boton").html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Generando Formulario ...</center>');
              	//alert(proy_id+'--'+certpoa)
                  var url = base+"index.php/modificaciones/cmod_presupuestario/aperturar_cert_poa";
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: "json",
                      data: "proy_id="+proy_id+"&cpoa_id="+certpoa+"&cppto="+certppto
                  });

                  request.done(function (response, textStatus, jqXHR) { 
                    
                    if (response.respuesta == 'correcto') {
                    	//alert(response.datos)

                        $("#cuerpo1").html(response.cuerpo1);
                        $("#form_certpoa").html(response.datos);
                    } else {
                        alertify.error("Error");
                    }
                  });
              
              } else {
                  // user clicked "cancel"
                  alertify.error("Opcion cancelada");
              }
          });
          return false;
      }
    });
	});


 //// Verificando valor ejecutado por partida
function verif_ppto_cert(nro,valor,ppto_cert){
	
	if(valor!=0 && valor!=''){
		if(valor<ppto_cert){
			$('#but'+nro).slideDown();
		}
		else{
			$('#but'+nro).slideUp();
		}
	}
	else{
		$('#but'+nro).slideUp();
	}


 /// tp 0 : Registro
 /// tp 1 : modifcacion
/* $('#button').slideDown();
  document.getElementById("tr_color_partida"+sp_id).style.backgroundColor = "#ffffff"; /// color de fila
  if(ejecutado!= ''){
    var url = base+"index.php/ejecucion/cejecucion_pi/verif_valor_ejecutado_x_partida";
      var request;
      if (request) {
        request.abort();
      }
      request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "ejec="+ejecutado+"&sp_id="+sp_id+"&mes_id="+mes_id
      });

      request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {
          $('#button').slideDown();
          document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#ffffff";
      }
      else{
          alertify.error("ERROR EN EL DATO REGISTRADO !");
          document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#fdeaeb";
          $('#button').slideUp();
      }

    });
  }
  else{
    $('#button').slideUp();
    document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#fdeaeb";
  }*/
}
  </script>

		<!-- Demo purpose only -->
		<!-- <script src="<?php echo base_url();?>/assets/js/demo.min.js"></script> -->
		<!-- MAIN APP JS FILE -->
<!-- 		<script src="<?php echo base_url();?>/assets/js/app.min.js"></script>
		<script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script> -->
<!-- 		<script type="text/javascript">
		function validarFormatoFecha(campo) {
	      var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
	      if ((campo.match(RegExPattern)) && (campo!='')) {
	            return true;
	      } else {
	            return false;
	      }
		}
		function existeFecha(fecha){
	      var fechaf = fecha.split("/");
	      var day = fechaf[0];
	      var month = fechaf[1];
	      var year = fechaf[2];
	      var date = new Date(year,month,'0');
	      if((day-0)>(date.getDate()-0)){
	            return false;
	      }
	      return true;
		}
		$(function () {
			$(".nuevo_ff").on("click", function (e) {
				com_id = $(this).attr('name');
		        document.getElementById("com_id").value=com_id;

		    $("#add_form").on("click", function () {
		    	var $validator = $("#form_nuevo").validate({
		                rules: {
		                    cite: { //// Cite
		                        required: true,
		                    },
		                    fm: { //// Fecha de Solicitud
		                        required: true,
		                    }
		                },
		                messages: {
		                    cite: "<font color=red>REGISTRE CITE</font>",
		                    fm: "<font color=red>SELECCIONE FECHA</font>",		                    
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
		        	fecha = document.getElementById('fm').value;
		            if(validarFormatoFecha(fecha)){
					      if(existeFecha(fecha)){
					            alertify.confirm("DESEA INGRESAR A REALIZAR LA MODIFICACI\u00D3N DE REQUERIMIENTOS ?", function (a) {
				                    if (a) {
				                        document.getElementById("load").style.display = 'block';
				                        document.getElementById('add_form').disabled = true;
				                        document.forms['form_nuevo'].submit();
				                    } else {
				                        alertify.error("OPCI\u00D3N CANCELADA");
				                    }
				                });
					      }else{
					            alertify.error("La fecha introducida no existe.");
					      }
					}else{
					      alertify.error("El formato de la fecha es incorrecto.");
					}
		        }
		    });
		    });
	    });
		</script> -->
<!-- 		<script type="text/javascript">
			$(document).ready(function() {
				pageSetUp();
			})
		</script> -->
		<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>