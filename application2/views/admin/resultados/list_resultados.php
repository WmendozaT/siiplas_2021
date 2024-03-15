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
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="<?php echo base_url(); ?>assets/img/favicon/favicon.ico" type="image/x-icon">
		<!--estiloh-->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css"> 
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
		<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
	    <meta name="viewport" content="width=device-width">
		<!--fin de stiloh-->
          <script>
		  	function abreVentana(PDF){
				var direccion;
				direccion = '' + PDF;
				window.open(direccion, "Reporte de Acciones de Mediano Plazo" , "width=800,height=650,scrollbars=SI") ;
			}                                                  
          </script>
			<style>
			.table{
			  font-size: 9px;
	          display: inline-block;
	          width:100%;
	          max-width:1550px;
	          }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
              color: #ffffff;
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
					<li>Programaci&oacute;n del Poa</li><li>Marco Estrat&eacute;gico</li><li>Acciones de Mediano Plazo</li>
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
                              <h1><small>ACCIONES DE MEDIANO PLAZO</small></h1>
                            </div>
                        </section>
                    </article>
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                        <section id="widget-grid" class="well">
                          <center>
                            <a href="javascript:abreVentana('<?php echo site_url("admin").'/me/reporte_resultado' ?>');" style="width:100%;" title="REPORTE ACCIONES DE MEDIANO PLAZO" class="btn btn-success">REPORTE ACCIONES</a><br><br>
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
                            <?php
                          }
                        ?>
                        <div class="jarviswidget jarviswidget-color-darken" >
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong> LISTA DE RESULTADOS DE MEDIANO PLAZO </strong></h2>  
                            </header>
                            <div>
                            	<a href='<?php echo site_url("admin").'/me/form1'; ?>' class="btn btn-success" title="NUEVO REGISTRO -  ACCI&Oacute;N DE MEDIANO PLAZO" style="width:13%;">NUEVO REGISTRO</a><br><br>
                               	<div class="widget-body no-padding">
                               	<div class="table-responsive">
									<table id="dt_basic" class="table table-bordered" style="width:100%;" font-size: "7px";>
										<thead>
											<tr>
												<th style="width:3%;" bgcolor="#474544"></th>
												<th bgcolor="#474544">E/B</th>
												<th bgcolor="#474544">ACCI&Oacute;N DE MEDIANO PLAZO</th>
												<th bgcolor="#474544">VINCULACI&Oacute;N AL PEDES</th>
												<th bgcolor="#474544">PONDERACI&Oacute;N</th>
												<th bgcolor="#474544">TECNICO_RESPONSABLE</th>
												<th bgcolor="#474544">UNIDAD_ORGANIZACIONAL</th>
												<th bgcolor="#474544"><center>INDICADORES DEL RESULTADO</center></th>
											</tr>
										</thead>
										<tbody>
										<?php $nro_r=1;$cont_pdes = 0;$cont_ptdi = 0; $nro_in=1;
		                                foreach($resultados  as $rowr){
		                                	$pdes=$this->model_proyecto->datos_pedes($rowr['pdes_id']);
		                                	$nro_i = count($this->model_resultado->list_indicadores($rowr['r_id']))+1;// para el nuevo indicador
		                                	?>
		                                	<tr>
		                                		<td align="center">
		                                			<?php echo $rowr['r_codigo'];?>
		                                		</td>
		                                		<td>
		                                			<center><a href="<?php echo site_url("admin").'/me/update_res/'.$rowr['r_id']; ?>" title="MODIFICAR ACCI&Oacute;N DE MEDIANO PLAZO"><img src="<?php echo base_url(); ?>assets/ifinal/modificar.png" WIDTH="45" HEIGHT="45"/><br>MODIFICAR</a></center>
		                                			<center><a href="#" data-toggle="modal" data-target="#modal_del_ff" class="btn btn-xs del_ff" title="ELIMINAR ACCI&Oacute;N DE MEDIANO PLAZO" name="<?php echo $rowr['r_id'];?>"><img src="<?php echo base_url(); ?>assets/ifinal/eliminar.png" WIDTH="45" HEIGHT="45"/><br><font size="1">ELIMINAR</font></a></center>
		                                			<center><a href='<?php echo site_url("admin").'/me/new_indicador/'.$rowr['r_id'].'/'.$nro_i.''; ?>' title="NUEVO REGISTRO - INDICADOR"><img src="<?php echo base_url(); ?>assets/ifinal/add.jpg" WIDTH="44" HEIGHT="44"/></a><br>NUEVO INDICADOR</center>
		                                		</td>
		                                		<td><?php echo $rowr['r_resultado'];?></td>
		                                		<td><center>
		                                			<div class="buttonclick">
	                                                    <div class="btnapp">
	                                                        <div class="hover-btn">
	                                                            <a href="#" data-toggle="modal" data-target="#pdes<?php echo $cont_pdes; ?>" class="btn btn-lg btn-default">
	                                                                <font size="1"><b><?php echo $pdes[0]['id1'] . ' <br> ' . $pdes[0]['id2'] .' <br> ' . $pdes[0]['id3'] . ' <br> ' . $pdes[0]['id4'] ?></b></font>
	                                                            </a>
	                                                        </div>
	                                                    </div>
	                                                </div>
	                                                </center>
	                                                <div class="modal fade bs-example-modal-lg" id="pdes<?php echo $cont_pdes; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	                                                    <div class="modal-dialog modal-lg" role="document">
	                                                        <div class="modal-content">
	                                                            <div class="texto">
	                                                            	<font size="4">
	                                                                    <div class="row text-center">
	                                                                        <LABEL><b>PLAN ESTRATÉGICO DE DESARROLLO</b></LABEL>
	                                                                    </div>
	                                                                    <P>
	                                                                        <u><b>PILAR</b></u>
	                                                                        :<?php echo $pdes[0]['id1'] . ' - ' . $pdes[0]['pilar'] ?>
	                                                                        <br>
	                                                                        <u><b>META</b></u>
	                                                                        : <?php echo $pdes[0]['id2'] . ' - ' . $pdes[0]['meta'] ?>
	                                                                        <br>
	                                                                        <u><b>RESULTADO</b></u>
	                                                                        : <?php echo $pdes[0]['id3'] . ' - ' . $pdes[0]['resultado'] ?>
	                                                                        <br>
	                                                                        <u><b>ACCION</b></u>
	                                                                        : <?php echo $pdes[0]['id4'] . ' - ' . $pdes[0]['accion'] ?>
	                                                                    </P>
	                                                                </font>
	                                                            </div>
	                                                            
	                                                        </div>
	                                                    </div>
	                                                </div>
		                                		</td>
		                                		<td><?php echo $rowr['r_ponderacion'];?> %</td>
		                                		<td><?php echo $rowr['fun_nombre'].' '.$rowr['fun_paterno'].' '.$rowr['fun_materno'];?></td>
		                                		<td><?php echo $rowr['uni_unidad'];?></td>
		                                		<td>
		                                		<?php
		                                			$indicadores = $this->model_resultado->list_indicadores($rowr['r_id']);// lista de indicadores
		                                			?>
		                                			<table class="table table-bordered">
		                                				<tr bgcolor="#57bbae">
		                                					<td>Nro.</td>
		                                					<td></td>
		                                					<td>INDICADOR</td>
		                                					<td>TIPO DE INDICADOR</td>
		                                					<td>LINEA BASE</td>
		                                					<td>META</td>
		                                					<td>PONDERACI&Oacute;N</td>
		                                					<td>TEMPORALIZACI&Oacute;N DE LAS METAS</td>
		                                				</tr>

		                                			<?php $numeros_i=1;
		                                			foreach($indicadores  as $rowi){
											          ?>
											          <tr bgcolor="#E3F0DE">
											          	<td><?php echo $numeros_i;?></td>
											          	<td>
											          		<center><a href='<?php echo site_url("admin").'/me/update_indicador/'.$rowr['r_id'].'/'.$rowi['in_id'].'/'.$numeros_i.''; ?>' title="MODIFICAR INDICADOR"><img src="<?php echo base_url(); ?>assets/ifinal/modificar.png" WIDTH="35" HEIGHT="35"/></a><br>MODIFICAR</center>
											                <center><a href="#" data-toggle="modal" data-target="#modal_del_ffi" class="btn btn-xs del_ffi" title="ELIMINAR INDICADOR"  name="<?php echo $rowi['in_id'];?>" id="<?php echo $rowr['r_id'];?>"><img src="<?php echo base_url(); ?>assets/ifinal/eliminar.png" WIDTH="35" HEIGHT="35"/><br>Eliminar</a></center>
											            </td>
											          	<td><?php echo strtoupper($rowi['in_indicador']);?></td>
											          	<td><?php echo strtoupper($rowi['indi_descripcion']);?></td>
											          	<td><?php echo $rowi['in_linea_base'];?></td>
											          	<td><?php echo $rowi['in_meta'];?></td>
											          	<td><?php echo $rowi['in_ponderacion'];?>%</td>
											          	<td>
											          	<?php 
											          		$programado=$this->model_resultado->resultado_programado($rowi['in_id']); /// programado
													      $nro=0;
													      $tr_return = '';
													      foreach($programado as $row){
													        $nro++;
													        $matriz [1][$nro]=$row['g_id'];
													        $matriz [2][$nro]=$row['in_prog'];
													      }
													      /*---------------- llenando la matriz vacia --------------*/
													      $g=$rowr['gestion_desde'];
													      for($j = 1; $j<=5; $j++){
													        $matriz_r[1][$j]=$g;
													        $matriz_r[2][$j]='0';  //// P
													        $matriz_r[3][$j]='0';  //// PA
													        $matriz_r[4][$j]='0';  //// %PA
													        $g++;
													      }
													      /*--------------------------------------------------------*/
													      /*------- asignando en la matriz P, PA, %PA ----------*/
													      for($i = 1 ;$i<=$nro ;$i++){
													        for($j = 1 ;$j<=5 ;$j++)
													        {
													          if($matriz[1][$i]==$matriz_r[1][$j])
													          {
													              $matriz_r[2][$j]=round($matriz[2][$i],2);
													          }
													        }
													      }
											          	?>
											          		<table class="table table-bordered">
				                                				<tr bgcolor="#474544">
										                          <th style="width:16.7%;"></th>
										                          <?php
											                        for($i = 1 ;$i<=5 ;$i++){
											                          echo '<th>'.$matriz_r[1][$i].'</th>';
											                        }
										                          ?>
										                        </tr>
										                        <tr bgcolor="#F5F5F5">
										                          <td>P</td>
										                          <?php
											                        for($i = 1 ;$i<=5 ;$i++){
											                          echo '<td>'.$matriz_r[2][$i].'</td>';
											                        }
										                          ?>
										                        </tr>
										                    </table>
											          	</td>
											          </tr>
											          <?php
											          $numeros_i++; $nro_in++;
											        }
		                                			?>
		                                		</table>
		                                		</td>
		                                	</tr>
		                                	<?php
		                                	$nro_r++;
		                                	$cont_pdes++;
		                                	$cont_ptdi++;
		                                }
		                                ?>
										</tbody>
									</table>
								</div>
                               	</div>
                            </div>
                        </div>
                    </article>
						<!-- WIDGET END -->
					</div>
				</section>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
	<!-- ========================================================================================================= -->
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
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
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
		<!-- ====================================================================================================== -->
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

            // ======================== ELIMINAR RESULTADO =============================
            $(".del_ff").on("click", function (e) {
                reset();
                var name = $(this).attr('name');
                var request;
                // confirm dialog
                alertify.confirm("REALMENTE DESEA ELIMINAR ESTA ACCIÓN DE MEDIANO PLAZO ?", function (a) {
                    if (a) { 
                        url = "<?php echo site_url("admin")?>/me/delete_res";
                        if (request) {
                            request.abort();
                        }
                        request = $.ajax({
                            url: url,
                            type: "POST",
                            data: "r_id=" + name

                        });
                        window.location.reload(true);
                        request.done(function (response, textStatus, jqXHR) {
                            $('#tr' + response).html("");
                        });
                        request.fail(function (jqXHR, textStatus, thrown) {
                            console.log("ERROR: " + textStatus);
                        });
                        request.always(function () {
                            //console.log("termino la ejecuicion de ajax");
                        });

                        e.preventDefault();
                        alertify.success("Se eliminó el Resultado Correctamente");

                    } else {
                        // user clicked "cancel"
                        alertify.error("Opcion cancelada");
                    }
                });
                return false;
            });
			// ========================= ELIMINAR INDICADOR ==================================
            $(".del_ffi").on("click", function (e) {
                reset();
                var name = $(this).attr('name');
                var name2 = $(this).attr('id');
                var request;
                // confirm dialog
                alertify.confirm("REALMENTE DESEA ELIMINAR ESTE INDICADOR ?", function (a) {
                    if (a) { 
                        url = "<?php echo site_url("admin")?>/me/delete_ind";
                        if (request) {
                            request.abort();
                        }
                        request = $.ajax({
                            url: url,
                            type: "POST",
                            data: "in_id="+name+"&r_id="+name2

                        });
                        window.location.reload(true);
                        request.done(function (response, textStatus, jqXHR) {
                            $('#tr' + response).html("");
                        });
                        request.fail(function (jqXHR, textStatus, thrown) {
                            console.log("ERROR: " + textStatus);
                        });
                        request.always(function () {
                            //console.log("termino la ejecuicion de ajax");
                        });

                        e.preventDefault();
                        alertify.success("Se eliminó el Indicador Correctamente");

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
	
				$('#dt_basic').dataTable({
					"ordering": false,
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});
		})

		</script>
	</body>
</html>
