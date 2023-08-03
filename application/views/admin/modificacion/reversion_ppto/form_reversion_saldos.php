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
				<article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
          <section id="widget-grid" class="well">
            <a href="<?php echo base_url().'index.php/mod/list_top'?>" title="SALIR" class="btn btn-default" style="width:100%;"><img src="<?php echo base_url(); ?>assets/Iconos/arrow_turn_left.png" WIDTH="20" HEIGHT="20"/>&nbsp;<b>SALIR</b></a>
          </section>
        </article>
			</div>
			<div class="row">
				<?php 
          if($this->session->flashdata('success')){ ?>
            <div class="alert alert-success">
              <?php echo $this->session->flashdata('success'); ?>
            </div>
        <?php }
          elseif($this->session->flashdata('danger')){ ?>
            <div class="alert alert-danger">
              <?php echo $this->session->flashdata('danger'); ?>
            </div>
          <?php }
        ?>
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

				    const certppto = $('#certpptos').val();
				    if (!certppto) {
				      	alertify.error("REGISTRE CODIGO DE LA CERTIFICACION PRESUPUESTARIA");
			        	$('#certpptos').focus();
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
		const base = $('#base').val();
		if(valor!=0 && valor!=''){
			if((parseFloat($('[id="saldo'+nro+'"]').val())<parseFloat($('[id="ppto_cert'+nro+'"]').val()))) {
				$('#buton_save').slideDown();
				$('#verif'+nro).html('<br><img src="'+base+'/assets/img/ok1.jpg" alt="loading" style="width:45px; height:38px;"/><br><font color=green size="1px">SALDO A REVERTIR</font>');
				document.getElementById("saldo"+nro).style.backgroundColor = "#DBFCF9";

				///--------------------------------
				sum=0;
				for (var i = 1; i <= $('#total_partidas').val(); i++) {
					sum=parseFloat(sum)+parseFloat($('[id="saldo'+i+'"]').val());
				}

				$('[name="suma"]').val(sum)

				/// -------------------------------
			}
			else{
				$('#buton_save').slideUp();
				$('#verif'+nro).html('<br><img src="'+base+'/assets/img/neg.jpg" alt="loading" style="width:45px; height:38px;"/><br><font color=red size="1px">ERROR EN EL SALDO</font>');
				document.getElementById("saldo"+nro).style.backgroundColor = "#fdeaeb";
			}
		}
		else{
			$('#buton_save').slideUp();
			$('#verif'+nro).html('');
			document.getElementById("saldo"+nro).style.backgroundColor = "#ffffff";
		}

	}

	///----------------------
  </script>
  <script>
		function valida_envia(){
    		alertify.confirm("GENERAR REVERSIÓN DE SALDOS ?", function (a) {
        if (a) {
                document.getElementById('btsubmit').disabled = true;
                document.miFormulario.submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
      }
		</script>
	<script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
	</body>
</html>