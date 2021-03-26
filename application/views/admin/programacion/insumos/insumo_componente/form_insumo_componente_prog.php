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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
    	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
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
		            	} 
		            ?>
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
					<li><a href="<?php echo base_url().'index.php/admin/proy/list_proy_poa' ?>" title="MIS OPERACIONES">...</a></li><li>.....</li><li>Insumo Componente</li><li>Nuevo Requerimiento - Programaci&oacute;n Presupuestaria</li>
				</ol>
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
				<!-- widget grid -->
				<section id="widget-grid" class="">
					<!-- row -->
					<div class="row">
						<article class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
				            <section id="widget-grid" class="well">
				                <div class="">
				                  <h1> CATEGORIA PROGRAM&Aacute;TICA : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad']?></small></h1>
				                  <h1> <?php echo $titulo_proy;?> : <small><?php echo $proyecto[0]['proy_nombre']?></small></h1>
				                  <h1> COMPONENTE : <small><?php echo $dato_com->com_componente?></small></h1>
				                  <h1> REQUERIMIENTO : <small><?php echo $insumo[0]['ins_detalle'];?></small>&nbsp;&nbsp;GESTI&Oacute;N (ES) : <small><?php echo $fase[0]['pfec_fecha_inicio'].' - '.$fase[0]['pfec_fecha_fin'];?></small></h1>
				                </div>
				            </section>
				        </article>

				        <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
				            <section id="widget-grid" class="well">
				              <style type="text/css">#graf{font-size: 80px;}</style> 
				              <center>
				                <div class="dropdown">
				                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
				                  OPCIONES INSUMO
				                  <span class="caret"></span>
				                </button>
				                	<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
				                  		<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard' ?>">SALIR A MENU PRINCIPAL</a></li>
				                  		<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/operacion/'.$proyecto[0]['proy_id'].'/'.$fase[0]['id']; ?>">MI OPERACI&Oacute;N </a></li>
				                  		<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/mis_proyectos/1'; ?>">LISTA DE OPERACIONES</a></li>
				                  		<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/'.$atras.''; ?>">VOLVER ATRAS</a></li>
				                	</ul>
				              </div>
				              </center>
				            </section>
				        </article>
				        
				        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
								<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-0" data-widget-editbutton="false">
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h2>PROGRAMACI&Oacute;N PRESUPUESTARIA </h2>
								</header>
								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
									</div>
									<!-- end widget edit box -->
									<!-- widget content -->
									<div class="widget-body">
									<div class="row">
										<nav role="navigation" class="navbar navbar-default navbar-inverse">
									        <div class="navbar-header">
									            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
									                <span class="sr-only">Toggle navigation</span>
									                <span class="icon-bar"></span>
									                <span class="icon-bar"></span>
									                <span class="icon-bar"></span>
									            </button>
									        </div>
									 
									        <div id="navbarCollapse" class="collapse navbar-collapse">
									            <ul class="nav navbar-nav">
									            <?php  
									            	foreach ($list_ig as $row)  
									            	{
									            		if($row['g_id']==$gestion)
								            			{
								            				?>
								            				<li class="active"><a href="<?php echo base_url().'index.php/prog/ins_c_prog/'.$proyecto[0]['proy_id'].'/'.$dato_com->com_id.'/'.$insumo[0]['ins_id'].'/'.$row['insg_id'].'' ?>" title="GESTION <?php echo $row['g_id'];?>"><i class="glyphicon glyphicon-ok"></i>&nbsp;GESTI&Oacute;N <?php echo $row['g_id'];?>&nbsp;</a></li>
								            				<?php
								            			}
								            			else
								            			{
								            				?>
								            				<li><a href="<?php echo base_url().'index.php/prog/ins_c_prog/'.$proyecto[0]['proy_id'].'/'.$dato_com->com_id.'/'.$insumo[0]['ins_id'].'/'.$row['insg_id'].'' ?>" title="GESTION <?php echo $row['g_id'];?>" >&nbsp;GESTI&Oacute;N <?php echo $row['g_id'];?>&nbsp;</a></li>
								            				<?php
								            			}
									            	}
									            ?>
									            </ul>
									        </div>
										</nav>
									</div>
										<div class="table-responsive">
										<table class="table table-bordered" style="width:100%;" >
											<thead>
												<tr>
													<td colspan="13"><center><strong>TOTAL TECHO MENSUAL : <?php echo number_format($techo_mes[0], 2, ',', '.');?> Bs.</strong></center></td>
												</tr>
												<tr>
													<th style="width:8.33%;">ENE.</th>
													<th style="width:8.33%;">FEB.</th>
													<th style="width:8.33%;">MAR.</th>
													<th style="width:8.33%;">ABR.</th>
													<th style="width:8.33%;">MAY.</th>
													<th style="width:8.33%;">JUN.</th>
													<th style="width:8.33%;">JUL.</th>
													<th style="width:8.33%;">AGOST.</th>
													<th style="width:8.33%;">SEPT.</th>
													<th style="width:8.33%;">OCT.</th>
													<th style="width:8.33%;">NOV.</th>
													<th style="width:8.33%;">DIC.</th>
												</tr>
											</thead>
											<tbody>
												<?php
												for ($i=1; $i <=12 ; $i++)
												{ 
													echo '<td><input type="text"class="form-control"  value='.number_format($techo_mes[$i], 2, ',', '.').' disabled></td>';
												}
												?>
											</tbody>
										</table>
										<h2 class="alert alert-success"><center>COSTO GESTI&Oacute;N <?php echo $gestion;?> PROGRAMADO : <?php echo number_format($insumo_gest[0]['insg_monto_prog'], 2, ',', '.');?> Bs.</center></h2>
										<form action="<?php echo site_url("") . '/insumos/cprog_insumos_delegado/guardar_insumo_programado' ?>" id="ins_form_prog" name="ins_form_prog" novalidate="novalidate" method="post">
		                            	<input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>"> <!-- proy id -->
		                            	<input type="hidden" name="com_id" id="com_id" value="<?php echo $dato_com->com_id;?>"> <!-- fase id -->
		                            	<input type="hidden" name="ins_id" id="ins_id" value="<?php echo $insumo[0]['ins_id'];?>"> <!-- ins id -->
		                            	<input type="hidden" name="insg_id" id="insg_id" value="<?php echo $insumo_gest[0]['insg_id'];?>"> <!-- insg id -->
		                            	<input type="hidden" name="cant_fin" id="cant_fin" value="<?php echo count($lista_fuentes_techo) ?>"> <!-- nro de ff, of registrados -->
		                            	<input type="hidden" name="c_prog_gest" id="c_prog_gest" value="<?php echo $insumo_gest[0]['insg_monto_prog'];?>"> <!-- costo programado gestion -->
		                            	<input type="hidden" name="gestion" id="gestion" value="<?php echo $gestion;?>"> <!-- gestion -->
		                            	<input type="hidden" name="gestion_vigente" id="gestion_vigente" value="<?php echo $this->session->userdata("gestion");?>"> <!-- gestion Activa-->
										<?php
                                    	$cont = 1;
                                    	foreach ($list_fuentes as $row) 
                                    	{
                            		  		$ifp = $this->minsumos->get_insumo_financiamiento($insumo_gest[0]['insg_id'],$row['ffofet_id'],$gestion,$cont); //// Vista Insumo Financiamiento Programado
                            		  		$suma_prog = $this->minsumos->suma_monto_prog_insumo($row['ffofet_id'],$gestion,$fase[0]['pfec_ejecucion'],$proyecto[0]['proy_act']); //// Suma Programado Insumo
                            		  		if($suma_prog[0]['programado']==''){$suma_prog[0]['programado']=0;}
                            		  	//	echo "Prog. ".$suma_prog[0]['programado'];
                            		  		$et=0;$monto_asigando=0; $textcolor=''; $ifin_id=0;
                            		  		for ($i=1; $i <=12 ; $i++) {$valor_mes[$i]=0;}
                            		  		if(count($ifp)!=0)
                            		  		{
                            		  			$et=$ifp[0]['et_id'];$monto_asigando=$ifp[0]['ifin_monto']; $textcolor='#E5F4FB';$ifin_id=$ifp[0]['ifin_id'];
                            		  			for ($i=1; $i <=12 ; $i++) {$valor_mes[$i]=$ifp[0][$vmes[$i]];}
                            		  		}
                                    		?>
                                    		<table class="table table-bordered" style="width:100%;" >
	                                            <thead>
	                                            <tr style=" background:#568a89;">
	                                                <th style="text-align:center;"><b style="color:#fff;"><?php echo $cont;?></th>
	                                                <th style="text-align:center;"><b style="color:#fff;">FUENTE FINANCIAMIENTO</th>
	                                                <th style="text-align:center;"><b style="color:#fff;">ORGANISMO FINANCIADOR</th>
	                                                <th style="text-align:center;"><b style="color:#fff;">IMPORTE</th>
	                                                <th style="text-align:center;"><b style="color:#fff;">SALDO POR PROGRAMAR</th>
	                                            </tr>
	                                            </thead>
	                                            <tbody>
	                                            <tr style=" background:#568a89;">
	                                                <td style="text-align: center; "><b style="color:#fff;">
	                                                    <?php echo 'Nro. ' . $cont ?></b>
	                                                </td>
	                                                <td style="text-align: center; "><b style="color:#fff;">
	                                                    <?php echo $row['ff_codigo'] . '  ' . $row['ff_descripcion'] ?></b>
	                                                </td>
	                                                <td style="text-align: center;"><b style="color:#fff;">
	                                                    <?php echo $row['of_codigo'] . '  ' . $row['of_descripcion'] ?></b>
	                                                </td>
	                                                <td style="text-align: center;"><b style="color:#fff;">
	                                                    <?php echo number_format($row['ffofet_monto'], 2, ',', '.'); ?></b>
	                                                </td>
	                                                <td style="text-align: center;"><b style="color:#fff;">
	                                                	<?php echo number_format($row['ffofet_monto']-$suma_prog[0]['programado']+$insumo[0]['ins_costo_total'], 2, ',', '.'); ?></b>
	                                                </td>
	                                            </tr>
	                                            	<!-- <input type="hidden" name="<?php echo 'saldo_prog' . $cont ?>" id="<?php echo 'saldo_prog' . $cont ?>" value="<?php echo $row['saldo'] ?>"> -->
	                                            	<input type="hidden" name="import[]" id="import<?php echo $cont;?>" value="<?php echo $row['ffofet_monto']?>">
	                                            </tbody>
	                                        </table><br>
	                                        
                                    	
                                            <input type="hidden" name="ffofet_id[]" id="ffofet_id[]" value="<?php echo $row['ffofet_id'] ?>">
                                            <input type="hidden" name="ff[]" id="ff[]" value="<?php echo $row['ff_id'] ?>">
                                            <input type="hidden" name="of[]" id="of[]" value="<?php echo $row['of_id'] ?>">
                                    		
		                            		<input type="hidden" name="ifin_id[]" value="<?php echo $ifin_id; ?>"> <!-- Id insumo fin -->
		                            		<input type="hidden" id="saldo_monto<?php echo $cont;?>" value="<?php echo $row['ffofet_monto']-$suma_prog[0]['programado']+$insumo[0]['ins_costo_total']; ?>"> <!-- Saldo por programar -->
                        					<div class="well">
                            		  			<div class="row">
			                            			<div class="col-sm-8">
			                            				<div class="form-group">
				                                        <label><b>ENTIDAD DE TRANSFERENCIA <?php echo $cont;?></b></label>
			                                            	<select name="et[]" id="et[]" class="select2">
	                                                            <option value="0"> 0 - Sin Entidad de Transferencia</option>
	                                                            <?php
	                                                            foreach ($lista_entidad as $row) 
	                                                            {
	                                                            	if($row['et_id']==$et)
	                                                            	{
	                                                            		?>
		                                                                <option value="<?php echo $row['et_id'];?>" selected><?php echo $row['et_codigo'] . " - " . $row['et_descripcion'] ?></option>
		                                                                <?php
	                                                            	}
	                                                            	else
	                                                            	{
	                                                            		?>
		                                                                <option value="<?php echo $row['et_id'];?>"><?php echo $row['et_codigo'] . " - " . $row['et_descripcion'] ?></option>
		                                                                <?php
	                                                            	}
	                                                            }
	                                                            ?>
	                                                        </select>
				                                    	</div>
				                                    </div>
				                                    <div class="col-sm-4">
				                                    	<div class="form-group">
			                                            <LABEL><b>MONTO ASIGNADO <?php echo $cont;?><font color="blue"> (Autom&aacute;tico)</font></b></label>
			                                            <input type="text" name="monto_asig[]" id="ins_monto<?php echo $cont ?>"  onkeypress="if (this.value.length < 12) { return numerosDecimales(event);}else{return false; }" class="form-control" value="<?php echo $monto_asigando;?>" style="width:100%; background-color:<?php echo $textcolor?>;">
			                                        	</div>
			                                        </div>
			                                    </div>
		                            		</div></br>
		                            		<table class="table table-bordered table-hover" style="width:100%;" >
											    <thead>
											        <tr>
											            <th style="width:16.6%;"><center>ENERO</center></th>
											            <th style="width:16.6%;"><center>FEBRERO</center></th>
											            <th style="width:16.6%;"><center>MARZO</center></th>
											            <th style="width:16.6%;"><center>ABRIL</center></th>
											            <th style="width:16.6%;"><center>MAYO</center></th>
											            <th style="width:16.6%;"><center>JUNIO</center></th>
											        </tr>
											    </thead>
											    <tbody>
											        <tr>
											            <td><input  name="m1[]" id="ene<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>;" value="<?php echo $valor_mes[1];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $gestion;?>"></td>
											            <td><input  name="m2[]" id="feb<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>;" value="<?php echo $valor_mes[2];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $gestion;?>"></td>
											            <td><input  name="m3[]" id="mar<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[3];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $gestion;?>"></td>
											            <td><input  name="m4[]" id="abr<?php echo $cont;?>"class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[4];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $gestion;?>"></td>
											        	<td><input  name="m5[]" id="may<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[5];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $gestion;?>"></td>
											            <td><input  name="m6[]" id="jun<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[6];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $gestion;?>"></td>
											        </tr>
											    </tbody>
											</table>
											<table class="table table-bordered table-hover" style="width:100%;" >
											    <thead>
											        <tr>
											            <th style="width:16.6%;" ><center>JULIO</center></th>
											            <th style="width:16.6%;"><center>AGOSTO</center></th>
											            <th style="width:16.6%;"><center>SEPTIEMBRE</center></th>
											            <th style="width:16.6%;"><center>OCTUBRE</center></th>
											            <th style="width:16.6%;"><center>NOVIEMBRE</center></th>
											            <th style="width:16.6%;"><center>DICIEMBRE </center></th>
											        </tr>
											    </thead>
											    <tbody>
											        <tr>
											            <td><input  name="m7[]" id="jul<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[7];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $gestion;?>"></td>
											            <td><input  name="m8[]" id="agost<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[8];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $gestion;?>"></td>
											        	<td><input  name="m9[]" id="sept<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[9];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $gestion;?>"></td>
											            <td><input  name="m10[]" id="oct<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[10];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $gestion;?>"></td>
											            <td><input  name="m11[]" id="nov<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[11];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $gestion;?>"></td>
											            <td><input  name="m12[]" id="dic<?php echo $cont;?>" class="form-control" type="text" onkeyup="javascript:suma_monto<?php echo $cont;?>();" style="width:100%; background-color:<?php echo $textcolor?>" value="<?php echo $valor_mes[12];?>" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $gestion;?>"></td>
											        </tr>
											    </tbody>
											</table>
										
											<script type="text/javascript">
						                        function suma_monto<?php echo $cont;?>()
						                        { 
						                            m1=parseFloat($('[id="ene<?php echo $cont;?>"]').val());
						                            m2=parseFloat($('[id="feb<?php echo $cont;?>"]').val());
						                            m3=parseFloat($('[id="mar<?php echo $cont;?>"]').val());
						                            m4=parseFloat($('[id="abr<?php echo $cont;?>"]').val());
						                            m5=parseFloat($('[id="may<?php echo $cont;?>"]').val());
						                            m6=parseFloat($('[id="jun<?php echo $cont;?>"]').val());
						                            m7=parseFloat($('[id="jul<?php echo $cont;?>"]').val());
						                            m8=parseFloat($('[id="agost<?php echo $cont;?>"]').val());
						                            m9=parseFloat($('[id="sept<?php echo $cont;?>"]').val());
						                            m10=parseFloat($('[id="oct<?php echo $cont;?>"]').val());
						                            m11=parseFloat($('[id="nov<?php echo $cont;?>"]').val());
						                            m12=parseFloat($('[id="dic<?php echo $cont;?>"]').val());

						                            $('[id="ins_monto<?php echo $cont;?>"]').val((m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12).toFixed(2) );

						                            monto_asig=parseFloat($('[id="ins_monto<?php echo $cont;?>"]').val());
						                            saldo_monto_fuente=parseFloat($('[id="saldo_monto<?php echo $cont;?>"]').val());
						                            
						                          //  alert(saldo_monto_fuente+'---'+monto_asig)
						                        }
						                    </script>
                        		  		<?php
                                        $cont++;
                                    	}
                                    	?>
                            	
                                    	<input type="hidden" name="suma_monto_total" id="suma_monto_total" class="form-control" value="0">
                                    	</form>
                                    	
							            <div class="form-actions">
											<a href="<?php echo base_url().'index.php/prog/ins_com/'.$proy_id.'/'.$com_id.''; ?>" class="btn btn-lg btn-default" title="REQUERIMIENTOS DE LA OPERACION"> CANCELAR </a>
											<a href="<?php echo base_url().'index.php/'.$atras.''; ?>" class="btn btn-lg btn-success" title="VOLVER ATRAS"> VOLVER ATRAS </a>
											<input type="button" value="GUARDAR PROGRAMACION FINANCIERA : <?php echo $gestion;?>" id="btsubmit" class="btn btn-primary btn-lg" onclick="valida_envia_programado()" title="GUARDAR PROGRAMACION FINANCIERA <?php echo $gestion;?>">
										</div>
										</div>
									</div>
									<!-- end widget content -->
								</div>
								<!-- end widget div -->
								</div>
							</article>
				        </div>

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
		<!-- END PAGE FOOTER -->
		<!--================================================== -->
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
		<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
		<script src="<?php echo base_url(); ?>mis_js/programacion/insumos/insumos_componentes.js"></script>
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
		<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
		<!-- Voice command : plugin -->
		<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function() {
            
            pageSetUp();
            $("#ins_partidas").change(function () {
                $("#ins_partidas option:selected").each(function () {
                elegido=$(this).val();
                $.post("<?php echo base_url(); ?>index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
                $("#ins_partidas_dependientes").html(data);
                });     
            });
            });  
    })
		</script>
</html>
