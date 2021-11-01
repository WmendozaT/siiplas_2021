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
          function abreVentana(PDF){             
            var direccion;
            direccion = '' + PDF;  
            window.open(direccion, "Ver Alineacion POA" , "width=700,height=600,scrollbars=NO") ; 
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
		</style>
	</head>
	<body class="">
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


			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>PEI</li><li>Mis Acciones de Corto Plazo</li><li>Alineación POA-PEI</li>
				</ol>
			</div>
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<div class="row">
						<?php echo $titulo; ?>
	                </div>
	                <div class="row">
	                	<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget jarviswidget-color-darken" >
                          <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"></h2>  
                          </header>
                          <div>
                            <div class="widget-body no-padding">
                              <div class="table-responsive">
                                <table id="datatable_fixed_column" class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="REGIONAL"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="COD. OPERACION"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="OPERACIÓN"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="INDICADOR"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="PRODUCTO"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="RESULTADO"/>
                                            </th>
                                            <th></th>

                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="CODIGO SISIN"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="GASTO CORRIENTE"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="UNIDAD RESPONSABLE"/>
                                            </th>

                                            <th></th>

                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="COD. ACT."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="ACTIVIDAD"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="INDICADOR"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="UNIDAD RESPONSABLE"/>
                                            </th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="MEDIO DE VERIFICACIÓN"/>
                                            </th>
                                        </tr>                          
                                        <tr>
                                            <th style="width:1%;">COD. A.C.P.</th>
                                            <th style="width:5%;">REGIONAL</th>
                                            <th style="width:5%;">COD. OPE.</th>
                                            <th style="width:8%;">OPERACIÓN</th>
                                            <th style="width:8%;">INDICADOR</th>
                                            <th style="width:8%;">PRODUCTO</th>
                                            <th style="width:8%;">RESULTADO</th>
                                            <th style="width:3%;">META OPERACI&Oacute;N</th>

                                            <th style="width:5%;">CÓDIGO SISIN</th>
                                            <th style="width:10%;">GASTO CORRIENTE / PROY. INVERSIÓN</th>
                                            <th style="width:10%;">UNIDAD RESPONSABLE</th>
                                            <th style="width:3%;">IR A FORMULARIO</th>

                                            <th style="width:5%;">COD. ACT.</th>
                                            <th style="width:10%;">ACTIVIDAD</th>
                                            <th style="width:10%;">INDICADOR</th>
                                            <th style="width:10%;">UNIDADES RESPONSABLES</th>
                                            <th style="width:5%;">L.B.</th>
                                            <th style="width:5%;">META</th>
                                            <th style="width:5%;">TIPO DE META</th>
                                            <th style="width:10%;">MEDIO DE VERIFICACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                   		<?php echo $tabla; ?>
                                    </tbody>
                                </table>
                               </div>
                            </div>
                           </div>
                          </div>
                        </article>
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->

	<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->

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
		        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<!-- PAGE RELATED PLUGIN(S) -->

		 <script type="text/javascript">
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        $(document).ready(function() {
            
            pageSetUp();
            /* BASIC ;*/
                var responsiveHelper_dt_basic = undefined;
                var responsiveHelper_datatable_fixed_column = undefined;
                var responsiveHelper_datatable_col_reorder = undefined;
                var responsiveHelper_datatable_tabletools = undefined;
                
                var breakpointDefinition = {
                    tablet : 1024,
                    phone : 480
                };
    
            /* END BASIC */
            
            /* COLUMN FILTER  */
            var otable = $('#datatable_fixed_column').DataTable({
                "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
                        "t"+
                        "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
                "autoWidth" : true,
                "preDrawCallback" : function() {
                    // Initialize the responsive datatables helper once.
                    if (!responsiveHelper_datatable_fixed_column) {
                        responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
                    }
                },
                "rowCallback" : function(nRow) {
                    responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
                },
                "drawCallback" : function(oSettings) {
                    responsiveHelper_datatable_fixed_column.respond();
                }       
            
            });
            
            // custom toolbar
            $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
            // Apply the filter
            $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
                otable
                    .column( $(this).parent().index()+':visible' )
                    .search( this.value )
                    .draw();
                    
            } );
            /* END COLUMN FILTER */   
        })
        </script>
       
	</body>
</html>
