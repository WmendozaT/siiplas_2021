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
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>

        <meta name="viewport" content="width=device-width">
        <script>
            function abreVentana(PDF){
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte de Evaluacion" , "width=800,height=650,scrollbars=SI") ;                                                                 
            }
            function printDiv(nombreDiv) {
            var contenido= document.getElementById(nombreDiv).innerHTML;
            var contenidoOriginal= document.body.innerHTML;
            document.body.innerHTML = contenido;
            window.print();
            document.body.innerHTML = contenidoOriginal;
            }                                            
        </script>
        <style>
            #areaImprimir_eval{display:none}
            @media print {
            #areaImprimir_eval {display:block}
        }
        </style>
        <style>
            .table1{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
              }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
              width: 90% !important;
            }
            #mdialTamanio_update{
              width: 50% !important;
            }
            #mdialTamanio_trimestre{
              width: 30% !important;
            }
            tr.highlighted td {
                background: red;
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
                        <a href="#" title="REGISTRO DE EJECUCION"> <span class="menu-item-parent">EVALUACI&Oacute;N POA</span></a>
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
                    <li>Registro de Evaluaci&oacute;n</li><li>Evaluaci&oacute;n POA</li><li>Mis Operaciones</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                            <section id="widget-grid" class="well">
                                <div class="">
                                    <?php echo $titulo;?>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <div class="well">
                                <div class="btn-group btn-group-justified">
                                    <center>
                                <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/eval/rep_eval_productos/'.$componente[0]['com_id'];?>');">IMPRIMIR EVALUACI&Oacute;N</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/eval/rep_eval_productos_consolidado/'.$componente[0]['com_id'];?>');">CONSOLIDADO EVALUACI&Oacute;N</a></li>
                                  <?php
                                      if($this->session->userdata('tp_adm')==1){ ?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_tr" title="CAMBIAR TRIMESTRE">CAMBIAR TRIMESTRE</a></li>
                                        <?php
                                      }
                                    ?>
                                  <li role="presentation"><a href="<?php echo base_url().'index.php/eval/mis_operaciones'?>" title="VOLVER ATRAS" >SALIR</a></li>
                                </ul>
                              </div>
                              </center>
                                </div>
                            </div>
                        </article> 
                    </div>
                    <div class="row">
                        <article class="col-sm-12">
                            <?php 
                              if($this->session->flashdata('success')){ ?>
                                <div class="alert alert-success">
                                  <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php }
                                elseif($this->session->flashdata('danger')){ ?>
                                <div class="alert alert-danger">
                                  <?php echo $this->session->flashdata('danger'); ?>
                                </div><?php }
                            ?>
                            <!-- new widget -->
                            <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                    <h2>CUADRO DE EVALUACI&Oacute;N POA </h2>

                                    <ul class="nav nav-tabs pull-right in" id="myTab">
                                        <li class="active">
                                            <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N DE ACTIVIDADES</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA</span></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EJECUCI&Oacute;N PRESUPUESTARIA</span></a>
                                        </li>
                                    </ul>
                                </header>

                                <!-- widget div-->
                                <div class="no-padding">
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        test
                                    </div>
                                    <!-- end widget edit box -->
                                    <div class="widget-body">
                                        <!-- content -->
                                        <div id="myTabContent" class="tab-content">
                                            <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="EFICACIA INSTITUCIONAL A NIVEL DISTRITAL">
                                               <div class="row">
                                                    <h2 class="alert alert-success" align="center">FORMULARIO DE EVALUACI&Oacute;N <?php echo $tmes[0]['trm_descripcion'];?></h2>
                                                   <div class="jarviswidget jarviswidget-color-darken" >
                                                      <header>
                                                          <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                                          <h2 class="font-md"></h2>  
                                                      </header>
                                                      <div>
                                                        <div class="widget-body no-padding">
                                                          <?php echo $productos;?>
                                                        </div>
                                                       </div>
                                                    </div>
                                               </div>
                                            </div>
                                            <!-- end s1 tab pane -->
                                            
                                            <div class="tab-pane fade" id="s2" title="CUADRO DE EFCIACIA">
                                             <br>
                                                <div align="right">
                                                    <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N DE OPERACIONES" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    
                                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <?php echo $calificacion;?>
                                                          <!-- <h2 class="alert alert-success" align="center"><?php echo $tmes[0]['trm_descripcion'];?></h2> -->
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="regresion" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                                <?php echo $tabla_regresion;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="regresion_gestion" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                                <?php echo $tabla_regresion_total;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                            <tr>
                                                              <td>
                                                              <div id="pastel" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                              </td>
                                                            </tr>
                                                            <tr>
                                                              <td>
                                                              <?php echo $tabla_pastel;?>
                                                              </td>
                                                            </tr>
                                                          </table>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                                          <table class="change_order_items" border=1>
                                                          <tr>
                                                            <td>
                                                                <div id="pastel_todos" style="width: 600px; height: 400px; margin: 0 auto"></div>
                                                            </td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                            <div class="table-responsive">
                                                             <?php echo $tabla_pastel_todo;?>
                                                            </div>
                                                            </td>
                                                          </tr>
                                                          </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="s3" title="CUADRO DE EJECUCI&Oacute;N PRESUPUESTARIA">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <br>
                                                        <h2 class="alert alert-success" align="center">CUADRO DE EJECUCI&Oacute;N PRESUPUESTARIA - <?php echo $this->session->userData('gestion');?></h2>
                                                        <div class="row">
                                                            <?php echo $ppto_cert;?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end content -->
                                    </div>
                                </div>
                                <!-- end widget div -->
                            </div>
                            <!-- end widget -->
                        </article>
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
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
        <!-- ================ Modal EVALUAR PRODUCTO ================= -->
        <!-- Absoluto -->
        <div class="modal fade" id="modal_add_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/eval/valida_eval_prod'?>" id="form_eval" name="form_eval" class="form-horizontal" method="post">
                <input type="hidden" name="prod_id" id="prod_id">
                <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                <input type="hidden" name="tprog" id="tprog">
                <input type="hidden" name="total_prog" id="total_prog">
                <input type="hidden" name="tp" id="tp">
                <input type="hidden" name="tmes" id="tmes">
                <input type="hidden" name="tdif" id="tdif">
                <input type="hidden" name="smeta" id="smeta">
                <input type="hidden" name="mt_id" id="mt_id">
                    <h2 class="alert alert-info"><center>EVALUACI&Oacute;N DE ACTIVIDAD <?php echo $tmes[0]['trm_descripcion'];?></center></h2>                           
                    <fieldset>
                        <legend><div id="producto"></div></legend>
                        <div id="tit"></div>
                        <div id="meta"></div>

                        <div class="form-group">
                            <?php
                            if ($this->session->userData('trimestre')>1) { ?>
                                <div id="nprog"></div>
                                <?php
                            } ?>
                            <table class="table table-bordered">
                                    <tr align="center" bgcolor="#404040">
                                        <td style="width:10%;"><font color="#fff">MES</font></td>
                                        <td style="width:10%;"><div id="tp_indi_p"></div></td>
                                        <td style="width:10%;"><div id="tp_indi_e"></div></td>
                                    </tr>
                                <?php
                                    if($this->session->userData('trimestre')==1){ ?>
                                        <tr id="1">
                                            <td><b>ENERO</b></td>
                                            <td><input class="form-control" type="text" id="m1" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e1" id="e1" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="2">
                                            <td><b>FEBRERO</b></td>
                                            <td><input class="form-control" type="text" id="m2" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e2" id="e2" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="3">
                                            <td><b>MARZO</b></td>
                                            <td><input class="form-control" type="text" id="m3" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e3" id="e3" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==2){ ?>
                                        <tr id="4">
                                            <td><b>ABRIL</b></td>
                                            <td><input class="form-control" type="text" id="m4" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e4" id="e4" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="5">
                                            <td><b>MAYO</b></td>
                                            <td><input class="form-control" type="text" id="m5" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e5" id="e5" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="6">
                                            <td><b>JUNIO</b></td>
                                            <td><input class="form-control" type="text" id="m6" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e6" id="e6" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                            </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==3){ ?>
                                        <tr id="7">
                                            <td><b>JULIO</b></td>
                                            <td><input class="form-control" type="text" id="m7" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e7" id="e7" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="8">
                                            <td><b>AGOSTO</b></td>
                                            <td><input class="form-control" type="text" id="m8" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e8" id="e8" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="9">
                                            <td><b>SEPTIEMBRE</b></td>
                                            <td><input class="form-control" type="text" id="m9" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e9" id="e9" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==4){ ?>
                                        <tr id="10">
                                            <td><b>OCTUBRE</b></td>
                                            <td><input class="form-control" type="text" id="m10" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e10" id="e10" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="11">
                                            <td><b>NOVIEMBRE</b></td>
                                            <td><input class="form-control" type="text" id="m11" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e11" id="e11" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="12">
                                            <td><b>DICIEMBRE</b></td>
                                            <td><input class="form-control" type="text" id="m12" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="e12" id="e12" onkeyup="suma_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                    <tr bgcolor="#dedbdb">
                                        <td><b>TOTAL</b></td>
                                        <td><input class="form-control" type="text" id="prog" disabled="true"></td>
                                        <td><input class="form-control" type="text" name="ejec" id="ejec" value="" disabled="true"></td>
                                    </tr>
                            </table>
                        </div>
                        
                        <div id="medio" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Verificaci&oacute;n</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="mverif" id="mverif" placeholder="Medio de Verificacion" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="rel" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">Problemas Presentados</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="prob" id="prob" placeholder="Problemas Presentados" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Acciones Realizadas</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="acciones" id="acciones" placeholder="Acciones Realizadas" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                            <div id="but" style="display:none;">
                                <div class="col-md-12">
                                   <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                   <button type="button" name="subir_eval" id="subir_eval" class="btn btn-info" >EVALUAR OPERACI&Oacute;N</button>
                                </div>
                            </div>
                            <div id="load" style="display: none" align="center">
                                <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>EVALUANDO OPERACI&Oacute;N</b>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>
  
        <!-- =========================================== Modal MODIFICAR EVALUAR OPERACION ================================================= -->
        <div class="modal fade" id="modal_mod_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/eval/valida_meval_prod'?>" id="form_meval" name="form_meval" class="form-horizontal" method="post">
                <input type="hidden" name="id_prod" id="id_prod">
                <input type="hidden" name="tprod_id" id="tprod_id">
                <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                <input type="hidden" name="programado" id="programado">
                <input type="hidden" name="ejecutado" id="ejecutado">
                <input type="hidden" name="mtp" id="mtp">
                <input type="hidden" name="mtmes" id="mtmes">
                <input type="hidden" name="mtdif" id="mtdif">
                <input type="hidden" name="mt_id" id="mmt_id">
                    <h2 class="alert alert-info"><center>MODIFICAR EVALUACI&Oacute;N ACTIVIDAD <?php echo $tmes[0]['trm_descripcion'];?></center></h2>                           
                    <fieldset>
                        <legend><div id="mproducto"></div></legend>
                        <div id="mtit"></div>
                        <div id="mmeta"></div>

                        <div class="form-group">
                            <?php
                            if ($this->session->userData('trimestre')>1) { ?>
                                <div id="vfaltante"></div>
                                <?php
                            } ?>
                            <table class="table table-bordered">
                                    <tr align="center" bgcolor="#404040">
                                        <td style="width:10%;"><font color="#fff">MES</font></td>
                                        <td style="width:10%;"><div id="mtp_indi_p"></div></td>
                                        <td style="width:10%;"><div id="mtp_indi_e"></div></td>
                                    </tr>
                                <?php
                                    if($this->session->userData('trimestre')==1){ ?>
                                        <tr id="t1">
                                            <td>ENERO</td>
                                            <td><input class="form-control" type="text" id="mp1" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me1" id="me1" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t2">
                                            <td>FEBRERO</td>
                                            <td><input class="form-control" type="text" id="mp2" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me2" id="me2" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t3">
                                            <td>MARZO</td>
                                            <td><input class="form-control" type="text" id="mp3" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me3" id="me3" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==2){ ?>
                                        <tr id="t4">
                                            <td>ABRIL</td>
                                            <td><input class="form-control" type="text" id="mp4" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me4" id="me4" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t5">
                                            <td>MAYO</td>
                                            <td><input class="form-control" type="text" id="mp5" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me5" id="me5" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t6">
                                            <td>JUNIO</td>
                                            <td><input class="form-control" type="text" id="mp6" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me6" id="me6" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==3){ ?>
                                        <tr id="t7">
                                            <td>JULIO</td>
                                            <td><input class="form-control" type="text" id="mp7" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me7" id="me7" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t8">
                                            <td>AGOSTO</td>
                                            <td><input class="form-control" type="text" id="mp8" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me8" id="me8" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t9">
                                            <td>SEPTIEMBRE</td>
                                            <td><input class="form-control" type="text" id="mp9" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me9" id="me9" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                    elseif($this->session->userData('trimestre')==4){ ?>
                                        <tr id="t10">
                                            <td>OCTUBRE</td>
                                            <td><input class="form-control" type="text" id="mp10" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me10" id="me10" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t11">
                                            <td>NOVIEMBRE</td>
                                            <td><input class="form-control" type="text" id="mp11" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me11" id="me11" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <tr id="t12">
                                            <td>DICIEMBRE</td>
                                            <td><input class="form-control" type="text" id="mp12" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="me12" id="me12" onkeyup="suma_mod_ejecutado();" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false"></td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                                        <tr bgcolor="#dedbdb">
                                            <td>TOTAL</td>
                                            <td><input class="form-control" type="text" name="mprog" id="mprog" disabled="true"></td>
                                            <td><input class="form-control" type="text" name="mejec" id="mejec" disabled="true"></td>
                                        </tr>
                            </table>
                        </div>
                        
                        <div id="mmedio" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">MEDIO DE VERIFICACI&Oacute;N</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="mmverif" id="mmverif" placeholder="Medio de Verificacion" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        <div id="mrel" style="display:none;">
                            <div class="form-group">
                                <label class="col-md-2 control-label">PROBLEMAS PRESENTADOS</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="mprob" id="mprob" placeholder="Problemas Presentados" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">ACCIONES REALIZADAS</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="macciones" id="macciones" placeholder="Acciones Realizadas" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                        
                    </fieldset>
                    <div class="form-actions">
                        <div class="row">
                            <div id="mbut" style="display:none;">
                                <div class="col-md-12">
                                   <button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
                                   <button type="button" name="subir_meval" id="subir_meval" class="btn btn-info" >MODIFICAR EVALUACIÓN</button>
                                </div>
                            </div>
                            <div id="mload" style="display: none" align="center">
                                <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>MODIFICANDO DATOS DE EVALUACI&Oacute;N</b>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

        <div class="modal fade" id="modal_nuevo_tr" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_trimestre">
            <div class="modal-content" >
                <div class="modal-body">
                    <form action="<?php echo site_url().'/eval/update_trimestre'?>" id="form_trimestre" name="form_trimestre" class="form-horizontal" method="post">
                        <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                        <h4 class="alert alert-info"><center>CAMBIAR TRIMESTRE - <?php echo $tmes[0]['trm_descripcion']; ?></center></h4>   
                        <fieldset>
                          <div class="form-group">
                              <div class="form-group">
                                  <label class="col-md-2 control-label">TRIMESTRE</label>
                                  <div class="col-md-10">
                                      <?php echo $list_trimestre;?>
                                  </div>
                              </div>
                          </div>
                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                              <div class="col-md-12" align="right">
                                <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                <button type="button" name="subir_formt" id="subir_formt" class="btn btn-info" >CAMBIAR TRIMESTRE</button>
                                <center><img id="loadt" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                              </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if(count($verif_eval_ncum)==0){ ?>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">ES NECESARIO REALIZAR LA ACTUALIZACI&Oacute;N DE ACTIVIDADES PROGRAMADAS NO CUMPLIDAS EN EL TRIMESTRE</h4>
                  </div>
                  <form action="<?php echo site_url().'/ejecucion/cevaluacion/update_evaluacion'?>" id="fupdate_eval" name="fupdate_eval"  method="post">
                    <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>">
                  <div class="modal-footer">
                    <div id="but_eval" style="display: block;">
                        <button type="button" name="update_eval" id="update_eval" class="btn btn-default">ACTUALIZAR EVALUACI&Oacute;N</button><br>
                    </div>
                    <div id="load_ev" style="display: none" align="center">
                        <br><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>ACTUALIZANDO ACTIVIDADES NO CUMPLIDAS ....</b>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
            <?php
        }
        ?>
        <div id="areaImprimir_eval">
         <?php echo $print_tabla;?>
        </div>
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>
        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 
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
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script>
            document.getElementById("cl").addEventListener("click", function(){
            window.location.reload(true);
          });
            document.getElementById("mcl").addEventListener("click", function(){
            window.location.reload(true);
          });
        </script>
        <script type="text/javascript">
        /*------ Evaluacion de Operaciones ------*/
        $(function () {
            var prod_id = ''; var proy_id = '';
            $(".add_ff").on("click", function (e) {
                prod_id = $(this).attr('name'); 
            
                document.getElementById("prod_id").value = prod_id;

                var url = "<?php echo site_url("")?>/eval/get_productos";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "prod_id="+prod_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        $('#tit').html('');
                        $('#medio').slideUp();
                        $('#rel').slideUp();
                        $('#but').slideUp();
                        document.getElementById("ejec").value=0;

                        $('#producto').html(response.producto[0]['prod_producto']);
                        $('#meta').html('<h1 align=center><small>'+response.producto[0]['mt_tipo']+'</small></h1>');
                        
                        document.getElementById("total_prog").value = parseFloat(response.tprog_actual)+parseFloat(response.tdif); // tot prog
                        document.getElementById("tmes").value = response.tmes;
                        document.getElementById("tdif").value = response.tdif;
                        document.getElementById("smeta").value = response.sum_total_evaluar;
                        document.getElementById("mt_id").value = response.producto[0]['mt_id'];
                      
                            $('#tp_indi_p').html('<font color="#fff">PROGRAMADO</font>');
                            $('#tp_indi_e').html('<font color="#fff">EJECUTADO</font>');
                        if(response.producto[0]['indi_id']==2){
                            $('#tp_indi_p').html('<font color="#fff">PROGRAMADO (%)</font>');
                            $('#tp_indi_e').html('<font color="#fff">EJECUTADO (%)</font>');
                        }


                        if(response.producto[0]['mt_id']!=1){
                            $('#nprog').html('<font color=red size=2><b>NO EJECUTADO TRIMESTRE ANTERIOR : '+response.tdif+'</b></font>');
                            document.getElementById("prog").value = parseFloat(response.tprog_actual)+parseFloat(response.tdif); // tot prog
                            document.getElementById("tprog").value = parseFloat(response.tprog_actual)+parseFloat(response.tdif); // tot prog
                        }
                        else{
                            $('#nprog').html('');
                            document.getElementById("prog").value = 100; // tot prog
                            document.getElementById("tprog").value = 100; // tot prog
                        }
                        
                        if(response.tmes==1){
                            document.getElementById("m1").value = response.temp_prog[0]['enero'];
                            document.getElementById("m2").value = response.temp_prog[0]['febrero'];
                            document.getElementById("m3").value = response.temp_prog[0]['marzo'];
                            if(response.verif=='si'){
                                document.getElementById("e1").value = response.temp_ejec[0]['enero'];
                                document.getElementById("e2").value = response.temp_ejec[0]['febrero'];
                                document.getElementById("e3").value = response.temp_ejec[0]['marzo'];
                            }
                            else{
                                document.getElementById("e1").value = response.temp_ejec['enero'];
                                document.getElementById("e2").value = response.temp_ejec['febrero'];
                                document.getElementById("e3").value = response.temp_ejec['marzo'];
                            }
                        }
                        if(response.tmes==2){
                            document.getElementById("m4").value = response.temp_prog[0]['abril'];
                            document.getElementById("m5").value = response.temp_prog[0]['mayo'];
                            document.getElementById("m6").value = response.temp_prog[0]['junio'];
                            if(response.verif=='si'){
                                document.getElementById("e4").value = response.temp_ejec[0]['abril'];
                                document.getElementById("e5").value = response.temp_ejec[0]['mayo'];
                                document.getElementById("e6").value = response.temp_ejec[0]['junio'];
                            }
                            else{
                                document.getElementById("e4").value = response.temp_ejec['abril'];
                                document.getElementById("e5").value = response.temp_ejec['mayo'];
                                document.getElementById("e6").value = response.temp_ejec['junio'];
                            }
                        }
                        if(response.tmes==3){
                            document.getElementById("m7").value = response.temp_prog[0]['julio'];
                            document.getElementById("m8").value = response.temp_prog[0]['agosto'];
                            document.getElementById("m9").value = response.temp_prog[0]['septiembre'];
                            if(response.verif=='si'){
                                document.getElementById("e7").value = response.temp_ejec[0]['julio'];
                                document.getElementById("e8").value = response.temp_ejec[0]['agosto'];
                                document.getElementById("e9").value = response.temp_ejec[0]['septiembre'];
                            }
                            else{
                                document.getElementById("e7").value = response.temp_ejec['julio'];
                                document.getElementById("e8").value = response.temp_ejec['agosto'];
                                document.getElementById("e9").value = response.temp_ejec['septiembre'];
                            }
                        }
                        if(response.tmes==4){
                            document.getElementById("m10").value = response.temp_prog[0]['octubre'];
                            document.getElementById("m11").value = response.temp_prog[0]['noviembre'];
                            document.getElementById("m12").value = response.temp_prog[0]['diciembre'];

                            if(response.verif=='si'){
                                document.getElementById("e10").value = response.temp_ejec[0]['octubre'];
                                document.getElementById("e11").value = response.temp_ejec[0]['noviembre'];
                                document.getElementById("e12").value = response.temp_ejec[0]['diciembre'];
                            }
                            else{
                                document.getElementById("e10").value = response.temp_ejec['octubre'];
                                document.getElementById("e11").value = response.temp_ejec['noviembre'];
                                document.getElementById("e12").value = response.temp_ejec['diciembre'];
                            }
                        }
  
                    } else {
                        alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
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
                $("#subir_eval").on("click", function (e) {
                    var $validator = $("#form_eval").validate({
                       rules: {
                            ejec: { //// ejecucion
                                required: true,
                            }
                        },
                        messages: {
                            ejec: "Registre total de ejecucion",                           
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
                    var $valid = $("#form_eval").valid();
                    if (!$valid) {
                        $validator.focusInvalid();
                    } else {
                        
                        var tprog = document.getElementById("tprog").value;
                        var ejec = document.getElementById("ejec").value;

                        if(document.getElementById("tmes").value==1){
                            var e1 = document.getElementById("e1").value;
                            var e2 = document.getElementById("e2").value;
                            var e3 = document.getElementById("e3").value;
                        }
                        if(document.getElementById("tmes").value==2){
                            var e4 = document.getElementById("e4").value;
                            var e5 = document.getElementById("e5").value;
                            var e6 = document.getElementById("e6").value;
                        }
                        if(document.getElementById("tmes").value==3){
                            var e7 = document.getElementById("e7").value;
                            var e8 = document.getElementById("e8").value;
                            var e9 = document.getElementById("e9").value;
                        }
                        if(document.getElementById("tmes").value==4){
                            var e10 = document.getElementById("e10").value;
                            var e11 = document.getElementById("e11").value;
                            var e12 = document.getElementById("e12").value;
                        }

                            var prod_id = document.getElementById("prod_id").value;
                            var com_id = document.getElementById("com_id").value;
                            var tp = document.getElementById("tp").value;
                            if(tp==1){
                                if(document.getElementById("mverif").value==''){
                                    alertify.alert("REGISTRE MEDIOS DE VERIFICACI&Oacute;N") 
                                    document.form_eval.mverif.focus() 
                                    return 0;
                                }
                                alertify.confirm("EVALUAR OPERACI&Oacute;N ?", function (a) {
                                    if (a) {
                                        document.getElementById("load").style.display = 'block';
                                        document.forms['form_eval'].submit();
                                        document.getElementById("but").style.display = 'none';
                                    } else {
                                        alertify.error("OPCI\u00D3N CANCELADA");
                                    }
                                });
                            }
                            else{
                                if(tp==2) {
                                    if(document.getElementById("mverif").value==''){
                                        alertify.alert("REGISTRE MEDIOS DE VERIFICACI&Oacute;N") 
                                        document.form_eval.mverif.focus() 
                                        return 0;
                                    }
                                    if(document.getElementById("prob").value==''){
                                        alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                        document.form_eval.prob.focus() 
                                        return 0;
                                    }
                                }
                                if(tp==3){
                                    if(document.getElementById("prob").value==''){
                                        alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                        document.form_eval.prob.focus() 
                                        return 0;
                                    }
                                    if(document.getElementById("acciones").value==''){
                                        alertify.alert("REGISTRE ACCIONES REALIZADAS") 
                                        document.form_eval.acciones.focus() 
                                        return 0;
                                    }
                                }

                                alertify.confirm("EVALUAR OPERACI&Oacute;N ?", function (a) {
                                    if (a) {
                                        document.getElementById("load").style.display = 'block';
                                        document.forms['form_eval'].submit();
                                        document.getElementById("but").style.display = 'none';
                                    } else {
                                        alertify.error("OPCI\u00D3N CANCELADA");
                                    }
                                });
                        }
                    }
                });
            });
        });
        </script>
        <script type="text/javascript">
            function suma_ejecutado(){ 
            tmes = parseFloat($('[name="tmes"]').val());
            var vi=0;
            var vf=0;
            if(tmes==1){
                vi = 1;vf = 3;
            }
            if(tmes==2){
                vi = 4;vf = 6;
            }
            if(tmes==3){
                vi = 7;vf = 9;
            }
            if(tmes==4){
                vi = 10;vf = 12;
            }

            var suma=0;
            if($('[name="mt_id"]').val()==3){
                prog = parseFloat($('[name="tprog"]').val());

                for (var i = vi; i <= vf; i++) {
                suma=parseFloat(suma)+parseFloat($('[id="e'+i+'"]').val());
                }
                $('[name="ejec"]').val((suma).toFixed(2));

                ejec = parseFloat($('[name="ejec"]').val());

                if(prog>0){
                    if(prog==ejec){
                        $('#tit').html('<center><div class="alert alert-success alert-block">CUMPLIDO</div></center>');
                        $('#medio').slideDown();
                        $('#rel').slideUp();
                        $('#but').slideDown();
                        document.getElementById("tp").value = 1;
                    }
                    else{
                       if((prog>ejec & ejec>0)){
                            $('#tit').html('<center><div class="alert alert-warning alert-block">EN PROCESO</div></center>');
                            $('#medio').slideDown();
                            $('#rel').slideDown();
                            $('#but').slideDown();
                            document.getElementById("tp").value = 2;
                        }
                        else{
                            if(ejec==0){
                                $('#tit').html('<center><div class="alert alert-danger alert-block">NO CUMPLIDO</div></center>');
                                $('#medio').slideUp();
                                $('#rel').slideDown();
                                $('#but').slideDown();
                                document.getElementById("tp").value = 3;
                            }
                            else{
                                $('#tit').html('');
                                $('#medio').slideUp();
                                $('#rel').slideUp();
                                alertify.error("REGISTRE VALOR");
                                $('#but').slideUp();
                                $('[name="ejec"]').val('VERIFIQUE VALOR');
                                document.getElementById("tp").value = '';
                            }
                        } 
                    }
                }
                else{
                    $('#tit').html('');
                    $('#medio').slideUp();
                    $('#rel').slideUp();
                    alertify.error("REGISTRE VALOR");
                    $('#but').slideUp();
                    $('[name="ejec"]').val('VERIFIQUE VALOR');
                    document.getElementById("tp").value = '';
                }
            }
            else{
                prog = parseFloat($('[name="tprog"]').val());
                total_prog = parseFloat($('[name="total_prog"]').val());
                
                for (var i = vi; i <= vf; i++) {
                suma=parseFloat(suma)+parseFloat($('[id="e'+i+'"]').val());
                }
                //$('[name="ejec"]').val((suma).toFixed(2));
                $('[name="ejec"]').val(((suma/total_prog)*100).toFixed(2));
                sw=0;
                
                ejec = parseFloat($('[name="ejec"]').val());

                if(<?php echo $this->session->userData('trimestre');?>==1){
                    if(parseFloat($('[id="e1"]').val())>parseFloat($('[id="m1"]').val())){
                        document.getElementById("1").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("1").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e2"]').val())>parseFloat($('[id="m2"]').val())){
                        document.getElementById("2").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("2").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e3"]').val())>parseFloat($('[id="m3"]').val())){
                        document.getElementById("3").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("3").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e1"]').val())<=parseFloat($('[id="m1"]').val()) & parseFloat($('[id="e2"]').val())<=parseFloat($('[id="m2"]').val()) & parseFloat($('[id="e3"]').val())<=parseFloat($('[id="m3"]').val())){
                        $('#but').slideDown();
                    }
                    else{
                        $('#but').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==2){
                    if(parseFloat($('[id="e4"]').val())>parseFloat($('[id="m4"]').val())){
                        document.getElementById("4").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("4").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e5"]').val())>parseFloat($('[id="m5"]').val())){
                        document.getElementById("5").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("5").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e6"]').val())>parseFloat($('[id="m6"]').val())){
                        document.getElementById("6").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("6").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e4"]').val())<=parseFloat($('[id="m4"]').val()) & parseFloat($('[id="e5"]').val())<=parseFloat($('[id="m5"]').val()) & parseFloat($('[id="e6"]').val())<=parseFloat($('[id="m6"]').val())){
                        $('#but').slideDown();
                    }
                    else{
                        $('#but').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==3){
                    if(parseFloat($('[id="e7"]').val())>parseFloat($('[id="m7"]').val())){
                        document.getElementById("7").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("7").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e8"]').val())>parseFloat($('[id="m8"]').val())){
                        document.getElementById("8").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("8").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e9"]').val())>parseFloat($('[id="m9"]').val())){
                        document.getElementById("9").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("9").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e7"]').val())<=parseFloat($('[id="m7"]').val()) & parseFloat($('[id="e8"]').val())<=parseFloat($('[id="m8"]').val()) & parseFloat($('[id="e9"]').val())<=parseFloat($('[id="m9"]').val())){
                        $('#but').slideDown();
                    }
                    else{
                        $('#but').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==4){
                    if(parseFloat($('[id="e10"]').val())>parseFloat($('[id="m10"]').val())){
                        document.getElementById("10").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("10").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e11"]').val())>parseFloat($('[id="m11"]').val())){
                        document.getElementById("11").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("11").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e12"]').val())>parseFloat($('[id="m12"]').val())){
                        document.getElementById("12").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("12").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="e10"]').val())<=parseFloat($('[id="m10"]').val()) & parseFloat($('[id="e11"]').val())<=parseFloat($('[id="m11"]').val()) & parseFloat($('[id="e12"]').val())<=parseFloat($('[id="m12"]').val())){
                        $('#but').slideDown();
                    }
                    else{
                        $('#but').slideUp();
                        sw=1;
                    }
                }

               // alert(prog+'--'+ejec)
                if(sw==0){
                    if(prog==ejec){
                        $('#tit').html('<center><div class="alert alert-success alert-block">CUMPLIDO</div></center>');
                        $('#medio').slideDown();
                        $('#rel').slideUp();
                        $('#but').slideDown();
                        document.getElementById("tp").value = 1;
                    }
                    else{
                       if((prog>ejec & ejec>0)){
                            $('#tit').html('<center><div class="alert alert-warning alert-block">EN PROCESO</div></center>');
                            $('#medio').slideDown();
                            $('#rel').slideDown();
                            $('#but').slideDown();
                            document.getElementById("tp").value = 2;
                        }
                        else{
                            if(ejec==0){
                                $('#tit').html('<center><div class="alert alert-danger alert-block">NO CUMPLIDO</div></center>');
                                $('#medio').slideUp();
                                $('#rel').slideDown();
                                $('#but').slideDown();
                                document.getElementById("tp").value = 3;
                            }
                            else{
                                $('#tit').html('');
                                $('#medio').slideUp();
                                $('#rel').slideUp();
                                alertify.error("REGISTRE VALOR");
                                $('#but').slideUp();
                                $('[name="ejec"]').val('VERIFIQUE VALOR');
                                document.getElementById("tp").value = '';
                            }
                        } 
                    }
                }
                else{
                    $('#tit').html('');
                    $('#medio').slideUp();
                    $('#rel').slideUp();
                    alertify.error("REGISTRE VALOR");
                    $('#but').slideUp();
                    $('[name="ejec"]').val('VERIFIQUE VALOR');
                    document.getElementById("tp").value = '';
                }
            }
        }
        </script>

        <script type="text/javascript">
        /*------- Modificar Evaluacion de Operaciones -------*/
        $(function () {
            var prod_id = ''; var proy_id = '';
            $(".mod_ff").on("click", function (e) {
                prod_id = $(this).attr('name'); 

                document.getElementById("id_prod").value = prod_id;
             
                var url = "<?php echo site_url("")?>/eval/get_mod_productos";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "prod_id="+prod_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        $('#mproducto').html(response.producto);
                        $('#mmeta').html('<h1 align=center><small>'+response.producto[0]['mt_tipo']+'</small></h1>');
                        document.getElementById("programado").value = parseFloat(response.tprog_actual);
                        document.getElementById("ejecutado").value = parseFloat(response.tejec_actual);
                        document.getElementById("tprod_id").value = response.dato_trimestre[0]['tprod_id'];
                        
                        
                        document.getElementById("mtmes").value = response.tmes;
                        document.getElementById("mtdif").value = response.tdif;
                        document.getElementById("mmt_id").value = response.producto[0]['mt_id'];

                            $('#mtp_indi_p').html('<font color="#fff">PROGRAMADO</font>');
                            $('#mtp_indi_e').html('<font color="#fff">EJECUTADO</font>');
                        if(response.producto[0]['indi_id']==2){
                            $('#mtp_indi_p').html('<font color="#fff">PROGRAMADO (%)</font>');
                            $('#mtp_indi_e').html('<font color="#fff">EJECUTADO (%)</font>');
                        }

                        if(response.producto[0]['mt_id']!=1){
                            document.getElementById("mprog").value = Math.round((parseFloat(response.tprog_actual)+parseFloat(response.tdif)) * 100) / 100;
                            document.getElementById("mejec").value = parseFloat(response.tejec_actual);
                            $('#vfaltante').html('<font color=red size=2><b>NO EJECUTADO TRIMESTRE ANTERIOR : '+response.tdif+'</b></font>');
                        }
                        else{
                            document.getElementById("mprog").value = 100;
                            document.getElementById("mejec").value = Math.round(parseFloat(((response.tejec_actual/response.tprog_actual)*100)) * 100) / 100 ;
                            $('#vfaltante').html('');
                        }

                        if(response.dato_trimestre[0]['tp_eval']==1){
                            $('#mtit').html('<center><div class="alert alert-success alert-block">CUMPLIDO</div></center>');
                            $('#mmedio').slideDown();
                            $('#mrel').slideUp();
                            $('#mbut').slideDown();
                            document.getElementById("mmverif").value = response.dato_trimestre[0]['tmed_verif'];
                            document.getElementById("mtp").value = response.dato_trimestre[0]['tp_eval'];
                        }
                        if (response.dato_trimestre[0]['tp_eval']==2){
                            $('#mtit').html('<center><div class="alert alert-warning alert-block">EN PROCESO</div></center>');
                            $('#mmedio').slideDown();
                            $('#mrel').slideDown();
                            $('#mbut').slideDown();
                            document.getElementById("mmverif").value = response.dato_trimestre[0]['tmed_verif'];
                            document.getElementById("mprob").value = response.dato_trimestre[0]['tprob'];
                            document.getElementById("macciones").value = response.dato_trimestre[0]['tacciones'];
                            document.getElementById("mtp").value = response.dato_trimestre[0]['tp_eval'];
                        }
                        if(response.dato_trimestre[0]['tp_eval']==3){
                            $('#mtit').html('<center><div class="alert alert-danger alert-block">NO CUMPLIDO</div></center>');
                            $('#mmedio').slideUp();
                            $('#mrel').slideDown();
                            $('#mbut').slideDown();
                            document.getElementById("mprob").value = response.dato_trimestre[0]['tprob'];
                            document.getElementById("macciones").value = response.dato_trimestre[0]['tacciones'];
                            document.getElementById("mtp").value = response.dato_trimestre[0]['tp_eval'];
                        }

                        if(response.tmes==1){
                            document.getElementById("mp1").value = response.temp_prog[0]['enero'];
                            document.getElementById("mp2").value = response.temp_prog[0]['febrero'];
                            document.getElementById("mp3").value = response.temp_prog[0]['marzo'];
                            if(response.verif=='si'){
                                document.getElementById("me1").value = response.temp_ejec[0]['enero'];
                                document.getElementById("me2").value = response.temp_ejec[0]['febrero'];
                                document.getElementById("me3").value = response.temp_ejec[0]['marzo'];
                            }
                            else{
                                document.getElementById("me1").value = response.temp_ejec['enero'];
                                document.getElementById("me2").value = response.temp_ejec['febrero'];
                                document.getElementById("me3").value = response.temp_ejec['marzo'];
                            }
                        }
                        if(response.tmes==2){
                            document.getElementById("mp4").value = response.temp_prog[0]['abril'];
                            document.getElementById("mp5").value = response.temp_prog[0]['mayo'];
                            document.getElementById("mp6").value = response.temp_prog[0]['junio'];
                            if(response.verif=='si'){
                                document.getElementById("me4").value = response.temp_ejec[0]['abril'];
                                document.getElementById("me5").value = response.temp_ejec[0]['mayo'];
                                document.getElementById("me6").value = response.temp_ejec[0]['junio'];
                            }
                            else{
                                document.getElementById("me4").value = response.temp_ejec['abril'];
                                document.getElementById("me5").value = response.temp_ejec['mayo'];
                                document.getElementById("me6").value = response.temp_ejec['junio'];
                            }
                        }
                        if(response.tmes==3){
                            document.getElementById("mp7").value = response.temp_prog[0]['julio'];
                            document.getElementById("mp8").value = response.temp_prog[0]['agosto'];
                            document.getElementById("mp9").value = response.temp_prog[0]['septiembre'];
                            if(response.verif=='si'){
                                document.getElementById("me7").value = response.temp_ejec[0]['julio'];
                                document.getElementById("me8").value = response.temp_ejec[0]['agosto'];
                                document.getElementById("me9").value = response.temp_ejec[0]['septiembre'];
                            }
                            else{
                                document.getElementById("me7").value = response.temp_ejec['julio'];
                                document.getElementById("me8").value = response.temp_ejec['agosto'];
                                document.getElementById("me9").value = response.temp_ejec['septiembre'];
                            }
                        }
                        if(response.tmes==4){
                            document.getElementById("mp10").value = response.temp_prog[0]['octubre'];
                            document.getElementById("mp11").value = response.temp_prog[0]['noviembre'];
                            document.getElementById("mp12").value = response.temp_prog[0]['diciembre'];
                            if(response.verif=='si'){
                                document.getElementById("me10").value = response.temp_ejec[0]['octubre'];
                                document.getElementById("me11").value = response.temp_ejec[0]['noviembre'];
                                document.getElementById("me12").value = response.temp_ejec[0]['diciembre'];
                            }
                            else{
                                document.getElementById("me10").value = response.temp_ejec['octubre'];
                                document.getElementById("me11").value = response.temp_ejec['noviembre'];
                                document.getElementById("me12").value = response.temp_ejec['diciembre'];
                            }
                        }
  
                    } else {
                        alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
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
                $("#subir_meval").on("click", function (e) {
                    var $validator = $("#form_meval").validate({
                       rules: {
                            mejec: { //// ejecucion
                                required: true,
                            }
                        },
                        messages: {
                            mejec: "Registre total de ejecucion",                           
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
                    var $valid = $("#form_eval").valid();
                    if (!$valid) {
                        $validator.focusInvalid();
                    } else {
                        
                        var tprog = document.getElementById("mprog").value;
                        var ejec = document.getElementById("mejec").value;

                        if(document.getElementById("mtmes").value==1){
                            var e1 = document.getElementById("me1").value;
                            var e2 = document.getElementById("me2").value;
                            var e3 = document.getElementById("me3").value;
                        }
                        if(document.getElementById("mtmes").value==2){
                            var e4 = document.getElementById("me4").value;
                            var e5 = document.getElementById("me5").value;
                            var e6 = document.getElementById("me6").value;
                        }
                        if(document.getElementById("mtmes").value==3){
                            var e7 = document.getElementById("me7").value;
                            var e8 = document.getElementById("me8").value;
                            var e9 = document.getElementById("me9").value;
                        }
                        if(document.getElementById("mtmes").value==4){
                            var e10 = document.getElementById("me10").value;
                            var e11 = document.getElementById("me11").value;
                            var e12 = document.getElementById("me12").value;
                        }

                            var id_prod = document.getElementById("id_prod").value;
                            var com_id = document.getElementById("com_id").value;
                            var mtp = document.getElementById("mtp").value;
                            if(mtp==1){
                                if(document.getElementById("mmverif").value==''){
                                    alertify.alert("REGISTRE MEDIOS DE VERIFICACI&Oacute;N") 
                                    document.form_eval.mmverif.focus() 
                                    return 0;
                                }
                                alertify.confirm("DESEA MODIFICAR EVALUACIÓN ?", function (a) {
                                    if (a) {
                                        document.getElementById("mload").style.display = 'block';
                                        document.forms['form_meval'].submit();
                                        document.getElementById("mbut").style.display = 'none';
                                    } else {
                                        alertify.error("OPCI\u00D3N CANCELADA");
                                    }
                                });
                            }
                            else{
                                if(mtp==2) {
                                    if(document.getElementById("mmverif").value==''){
                                        alertify.alert("REGISTRE MEDIOS DE VERIFICACI&Oacute;N") 
                                        document.form_eval.mmverif.focus() 
                                        return 0;
                                    }
                                    if(document.getElementById("mprob").value==''){
                                        alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                        document.form_eval.mprob.focus() 
                                        return 0;
                                    }
                                }
                                if(mtp==3){
                                    if(document.getElementById("mprob").value==''){
                                        alertify.alert("REGISTRE PROBLEMAS PRESENTADOS") 
                                        document.form_eval.mprob.focus() 
                                        return 0;
                                    }
                                    if(document.getElementById("macciones").value==''){
                                        alertify.alert("REGISTRE ACCIONES REALIZADAS") 
                                        document.form_eval.macciones.focus() 
                                        return 0;
                                    }
                                }

                                alertify.confirm("DESEA MODIFICAR EVALUACIÓN ?", function (a) {
                                    if (a) {
                                        document.getElementById("mload").style.display = 'block';
                                        document.forms['form_meval'].submit();
                                        document.getElementById("mbut").style.display = 'none';
                                    } else {
                                        alertify.error("OPCI\u00D3N CANCELADA");
                                    }
                                });
                        }
                    }
                });
            });
        });

        function suma_mod_ejecutado(){ 
            tmes = parseFloat($('[name="mtmes"]').val());

            var vi=0;
            var vf=0;
            if(tmes==1){
                vi = 1;vf = 3;
            }
            if(tmes==2){
                vi = 4;vf = 6;
            }
            if(tmes==3){
                vi = 7;vf = 9;
            }
            if(tmes==4){
                vi = 10;vf = 12;
            }

            prog = parseFloat($('[name="programado"]').val());

            if($('[id="mmt_id"]').val()==3){
                var suma=0;
                for (var i = vi; i <= vf; i++) {
                    suma=parseFloat(suma)+parseFloat($('[id="me'+i+'"]').val());
                }
                $('[name="mejec"]').val((suma).toFixed(2));

                ejec = parseFloat($('[name="mejec"]').val());
                prog = Math.round((parseFloat($('[name="programado"]').val())+parseFloat($('[name="mtdif"]').val())) * 100) / 100;
                //prog = (parseFloat()+parseFloat());

                if(prog>0){
                 
                    if(prog==ejec){
                        $('#mtit').html('<center><div class="alert alert-success alert-block">CUMPLIDO</div></center>');
                        $('#mmedio').slideDown();
                        $('#mrel').slideUp();
                        $('#mbut').slideDown();
                        document.getElementById("mtp").value = 1;
                    }
                    else{
                       if((prog>ejec & ejec>0)){
                            $('#mtit').html('<center><div class="alert alert-warning alert-block">EN PROCESO</div></center>');
                            $('#mmedio').slideDown();
                            $('#mrel').slideDown();
                            $('#mbut').slideDown();
                            document.getElementById("mtp").value = 2;
                        }
                        else{
                            if(ejec==0){
                                $('#mtit').html('<center><div class="alert alert-danger alert-block">NO CUMPLIDO</div></center>');
                                $('#mmedio').slideUp();
                                $('#mrel').slideDown();
                                $('#mbut').slideDown();
                                document.getElementById("mtp").value = 3;
                            }
                            else{
                                $('#mtit').html('');
                                $('#mmedio').slideUp();
                                $('#mrel').slideUp();
                                alertify.error("REGISTRE VALOR");
                                $('#mbut').slideUp();
                                $('[name="mejec"]').val('VERIFIQUE VALOR');
                                document.getElementById("mtp").value = '';
                            }
                        } 
                    }
                }
                else{
                    $('#mtit').html('');
                    $('#mmedio').slideUp();
                    $('#mrel').slideUp();
                    alertify.error("REGISTRE VALOR");
                    $('#mbut').slideUp();
                    $('[name="mejec"]').val('VERIFIQUE VALOR');
                    document.getElementById("mtp").value = '';
                }
            }
            else{
                var suma=0;sw=0;
                for (var i = vi; i <= vf; i++) {
                    suma=parseFloat(suma)+parseFloat($('[id="me'+i+'"]').val());
                }
              //  $('[name="ejec"]').val(((suma/total_prog)*100).toFixed(2));
                $('[name="mejec"]').val(((suma/prog)*100).toFixed(2));
                ejec=suma;

                if(<?php echo $this->session->userData('trimestre');?>==1){
                    if(parseFloat($('[id="me1"]').val())>parseFloat($('[id="mp1"]').val())){
                        document.getElementById("t1").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t1").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me2"]').val())>parseFloat($('[id="mp2"]').val())){
                        document.getElementById("t2").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t2").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me3"]').val())>parseFloat($('[id="mp3"]').val())){
                        document.getElementById("t3").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t3").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me1"]').val())<=parseFloat($('[id="mp1"]').val()) & parseFloat($('[id="me2"]').val())<=parseFloat($('[id="mp2"]').val()) & parseFloat($('[id="me3"]').val())<=parseFloat($('[id="mp3"]').val())){
                        $('#mbut').slideDown();
                    }
                    else{
                        $('#mbut').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==2){
                    if(parseFloat($('[id="me4"]').val())>parseFloat($('[id="mp4"]').val())){
                        document.getElementById("t4").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t4").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me5"]').val())>parseFloat($('[id="mp5"]').val())){
                        document.getElementById("t5").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t5").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me6"]').val())>parseFloat($('[id="mp6"]').val())){
                        document.getElementById("t6").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t6").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me4"]').val())<=parseFloat($('[id="mp4"]').val()) & parseFloat($('[id="me5"]').val())<=parseFloat($('[id="mp5"]').val()) & parseFloat($('[id="me6"]').val())<=parseFloat($('[id="mp6"]').val())){
                        $('#mbut').slideDown();
                    }
                    else{
                        $('#mbut').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==3){
                    if(parseFloat($('[id="me7"]').val())>parseFloat($('[id="mp7"]').val())){
                        document.getElementById("t7").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t7").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me8"]').val())>parseFloat($('[id="mp8"]').val())){
                        document.getElementById("t8").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t8").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me9"]').val())>parseFloat($('[id="mp9"]').val())){
                        document.getElementById("t9").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t9").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me7"]').val())<=parseFloat($('[id="mp7"]').val()) & parseFloat($('[id="me8"]').val())<=parseFloat($('[id="mp8"]').val()) & parseFloat($('[id="me9"]').val())<=parseFloat($('[id="mp9"]').val())){
                        $('#mbut').slideDown();
                    }
                    else{
                        $('#mbut').slideUp();
                        sw=1;
                    }
                }
                if(<?php echo $this->session->userData('trimestre');?>==4){
                    if(parseFloat($('[id="me10"]').val())>parseFloat($('[id="mp10"]').val())){
                        document.getElementById("t10").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t10").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me11"]').val())>parseFloat($('[id="mp11"]').val())){
                        document.getElementById("t11").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t11").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me12"]').val())>parseFloat($('[id="mp12"]').val())){
                        document.getElementById("t12").style.backgroundColor = "#fb434a";
                    }
                    else{
                        document.getElementById("t12").style.backgroundColor = "#fff";
                    }

                    if(parseFloat($('[id="me10"]').val())<=parseFloat($('[id="mp10"]').val()) & parseFloat($('[id="me11"]').val())<=parseFloat($('[id="mp11"]').val()) & parseFloat($('[id="me12"]').val())<=parseFloat($('[id="mp12"]').val())){
                        $('#mbut').slideDown();
                    }
                    else{
                        $('#mbut').slideUp();
                        sw=1;
                    }
                }

                if(sw==0){
                    if(prog==ejec){
                        $('#mtit').html('<center><div class="alert alert-success alert-block">CUMPLIDO</div></center>');
                        $('#mmedio').slideDown();
                        $('#mrel').slideUp();
                        $('#mbut').slideDown();
                        document.getElementById("mtp").value = 1;
                    }
                    else{
                       if((prog>ejec & ejec>0)){
                            $('#mtit').html('<center><div class="alert alert-warning alert-block">EN PROCESO</div></center>');
                            $('#mmedio').slideDown();
                            $('#mrel').slideDown();
                            $('#mbut').slideDown();
                            document.getElementById("mtp").value = 2;
                        }
                        else{
                            if(ejec==0){
                                $('#mtit').html('<center><div class="alert alert-danger alert-block">NO CUMPLIDO</div></center>');
                                $('#mmedio').slideUp();
                                $('#mrel').slideDown();
                                $('#mbut').slideDown();
                                document.getElementById("mtp").value = 3;
                            }
                            else{
                                $('#mtit').html('');
                                $('#mmedio').slideUp();
                                $('#mrel').slideUp();
                                alertify.error("REGISTRE VALOR");
                                $('#mbut').slideUp();
                                $('[name="mejec"]').val('VERIFIQUE VALOR');
                                document.getElementById("mtp").value = '';
                            }
                        } 
                    }
                }
                else{
                    $('#mtit').html('');
                    $('#mmedio').slideUp();
                    $('#mrel').slideUp();
                    alertify.error("REGISTRE VALOR");
                    $('#mbut').slideUp();
                    $('[name="mejec"]').val('VERIFIQUE VALOR');
                    document.getElementById("mtp").value = '';
                }
            }

        }
        </script>
        <script type="text/javascript">
            $(document).ready(function() {  
               Highcharts.chart('pastel', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: '<?php echo 'EVALUACIÓN '.$tmes[0]['trm_descripcion'];?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $tabla[6][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f44336',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });

            $(document).ready(function() {  
               Highcharts.chart('pastel_todos', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: '<?php echo 'EVALUACIÓN '.$tmes[0]['trm_descripcion'];?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.name}'
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Actividades',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo (100-($tabla[5][$this->session->userData('trimestre')]+round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2)));?> %',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f98178',
                        },

                        {
                          name: 'EN PROCESO : <?php echo round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2);?> %',
                          y: <?php echo round(($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100,2);?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?> %',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
                          color: '#2CC8DC',
                          sliced: true,
                          selected: true
                        }
                    ]
                }]
              });
            });
        </script>

        <script type="text/javascript">
          var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_gestion',
                defaultSeriesType: 'line'
              },
              title: {
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
              },
              subtitle: {
                text: '<?php echo $componente[0]['com_componente'];?>'
              },
              xAxis: {
                        categories: ['<?php echo $tabla_gestion[1][0];?>', '<?php echo $tabla_gestion[1][1];?>', '<?php echo $tabla_gestion[1][2];?>', '<?php echo $tabla_gestion[1][3];?>', '<?php echo $tabla_gestion[1][4];?>']
                    },
              yAxis: {
                title: {
                  text: 'Promedio (%)'
                }
              },
              tooltip: {
                enabled: false,
                formatter: function() {
                  return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +'%';
                }
              },
              plotOptions: {
                line: {
                  dataLabels: {
                    enabled: true
                  },
                  enableMouseTracking: false
                }
              },

                series: [
                    {
                        name: '% META PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });

        /*--- Regresion Lineal Impresion ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_gestion_print',
                defaultSeriesType: 'line'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
              },
              xAxis: {
                        categories: ['<?php echo $tabla_gestion[1][0];?>', '<?php echo $tabla_gestion[1][1];?>', '<?php echo $tabla_gestion[1][2];?>', '<?php echo $tabla_gestion[1][3];?>', '<?php echo $tabla_gestion[1][4];?>']
                    },
              yAxis: {
                title: {
                  text: 'Promedio (%)'
                }
              },
              tooltip: {
                enabled: false,
                formatter: function() {
                  return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +'%';
                }
              },
              plotOptions: {
                line: {
                  dataLabels: {
                    enabled: true
                  },
                  enableMouseTracking: false
                }
              },

                series: [
                    {
                        name: '% META PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });
        </script>

        <!-- REGRESION LINEAL AL TRIMESTRE -->
        <script type="text/javascript">
          var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion',
                defaultSeriesType: 'line'
              },
              title: {
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$tmes[0]['trm_descripcion'];?>' 
              },
              subtitle: {
                text: '<?php echo $componente[0]['com_componente'];?>'
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][2];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                    xAxis: {
                        categories: ['p :<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>', '<?php echo $tabla[1][4];?>']
                    },
                    <?php
                }
              ?>
              yAxis: {
                title: {
                  text: 'Promedio (%)'
                }
              },
              tooltip: {
                enabled: false,
                formatter: function() {
                  return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +'%';
                }
              },
              plotOptions: {
                line: {
                  dataLabels: {
                    enabled: true
                  },
                  enableMouseTracking: false
                }
              },

                <?php 
                    if($this->session->userdata('trimestre')==1){ ?>
                        series: [
                            {
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                                }
                            ]
                        <?php
                    }
                ?>
            });
          });

        /*---  REGRESION LINEAL AL TRIMESTRE IMPRESION  ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_impresion',
                defaultSeriesType: 'line'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$tmes[0]['trm_descripcion'];?>' 
              },
              <?php 
                if($this->session->userdata('trimestre')==1){ ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==2) { ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][2];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==3) { ?>
                    xAxis: {
                        categories: ['p :<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>']
                    },
                    <?php
                }
                elseif ($this->session->userdata('trimestre')==4) { ?>
                    xAxis: {
                        categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>', '<?php echo $tabla[1][4];?>']
                    },
                    <?php
                }
              ?>
              yAxis: {
                title: {
                  text: 'Promedio (%)'
                }
              },
              tooltip: {
                enabled: false,
                formatter: function() {
                  return '<b>'+ this.series.name +'</b><br/>'+
                    this.x +': '+ this.y +'%';
                }
              },
              plotOptions: {
                line: {
                  dataLabels: {
                    enabled: true
                  },
                  enableMouseTracking: false
                }
              },

                <?php 
                    if($this->session->userdata('trimestre')==1){ ?>
                        series: [
                            {
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                                }
                            ]
                        <?php
                    }
                ?>
            });
          });
        </script>
        <script type="text/javascript">
        $(function () {
            $("#subir_formt").on("click", function () {
              val=document.getElementById("trimestre_usu").value;

              if(val!=0 & val!=''){
                if(document.getElementById("tmes").value!=document.getElementById("trimestre_usu").value){
                  alertify.confirm("CAMBIAR TRIMESTRE ?", function (a) {
                      if (a) {
                          document.getElementById("loadt").style.display = 'block';
                          document.getElementById('subir_formt').disabled = true;
                          document.forms['form_trimestre'].submit();
                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                      }
                  });
                }
                else{
                  alertify.success("TRIMESTRE SELECCIONADO");
                }
              }
              else{
                alertify.error("SELECCIONE TRIMESTRE");
              }
                
            });
        });
    </script>

        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
        <script type="text/javascript">
            $("#update_eval").on("click", function () {
                var $valid = $("#fupdate_eval").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    document.getElementById("load_ev").style.display = 'block';
                    document.forms['fupdate_eval'].submit();
                    document.getElementById("but_eval").style.display = 'none';
                }
            });
        </script>
        <script type="text/javascript">
            <?php
                if(count($verif_eval_ncum)==0){ ?>
                    $(document).ready(function(){
                     $('#myModal').modal({
                        backdrop: false,
                        show: true
                      });

                      $('.modal-dialog').draggable({
                        handle: ".modal-header"
                      });
                    });
                    <?php
                }
            ?>
            
            $(document).ready(function() {
                pageSetUp();
                $("#menu").menu();
                $('.ui-dialog :button').blur();
                $('#tabs').tabs();
            })
        </script>
    </body>
</html>
