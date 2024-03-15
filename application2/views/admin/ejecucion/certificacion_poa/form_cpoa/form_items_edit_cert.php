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
        <meta name="viewport" content="width=device-width">
    </head>
    <body class="">
        <header id="header">
            <div class="pull-right">
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
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
                      <a href="#" title="EVALUACIÓN POA"> <span class="menu-item-parent">EVALUACI&Oacute;N POA</span></a>
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
                    <li>Evaluaci&oacute;n POA</li><li>Mis Certificaciones POA</li><li>Editar Certificaci&oacute;n POA</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                            <section id="widget-grid" class="well">
                                <?php echo $titulo.' '.$opciones_update;?>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                            <div class="well">
                                <div class="btn-group btn-group-justified">
                                    <a class="btn btn-default" href="<?php echo base_url();?>index.php/cert/list_poas" title="SALIR"><i class="fa fa-caret-square-o-left"></i> SALIR</a>
                                </div>
                            </div>
                        </article>
                    </div>
                    
                    <div class="row">
                      <?php 
                          if($this->session->flashdata('danger')){ ?>
                              <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('danger'); ?>
                              </div>
                              <script type="text/javascript">alertify.error("<?php echo '<font size=2>'.$this->session->flashdata('danger').'</font>'; ?>")</script>
                            <?php
                          }
                        ?>    
                      <article class="col-sm-12 col-md-12 col-lg-12">
                          <div class="jarviswidget" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-custombutton="false">
                            <header>
                              <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                              <h2>MIS REQUERIMIENTOS CERTIFICADOS</h2>
                            </header>
                            <div>
                              <div class="jarviswidget-editbox">
                              </div>
                              <div class="widget-body no-padding">
                              <form id="cert_form" name="cert_form" novalidate="novalidate" action="<?php echo site_url().'/ejecucion/ccertificacion_poa/valida_reformulado_cpoa'?>" method="post" class="smart-form">
                                <input type="hidden" name="cpoaa_id" id="cpoa_id" value="<?php echo $cert_editado[0]['cpoaa_id'];?>">
                                <input type="hidden" name="tp_id" id="tp_id" value="<?php echo $datos[0]['tp_id'];?>">
                                <fieldset>
                                  <div>
                                    <h2 class="alert alert-success"><center>MODIFICAR CERTIFICACI&Oacute;N POA - <b><?php echo $cert_editado[0]['cpoa_codigo'];?></b></center></h2>
                                  </div>
                                  <div class="row">
                                    <section class="col col-3">
                                      <label class="label" style="color:#1c8def;"><b>NOTA / CITE</b></label>
                                      <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <input type="text" name="cite_cpoa" id="cite_cpoa" value="<?php echo $cert_editado[0]['cite_cert_mod'];?>" placeholder="XX-XX-XXX" disabled="true">
                                      </label>
                                    </section>
                                    <section class="col col-3">
                                      <label class="label" style="color:#1c8def;"><b>FECHA CITE</b></label>
                                      <div class="row">
                                        <div class="col col-10">
                                          <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                          <input type="text" name="cite_fecha" id="cite_fecha" class="form-control datepicker" data-dateformat="dd/mm/yy" onKeyUp="this.value=formateafecha(this.value);" value="<?php echo date('d/m/Y',strtotime($cert_editado[0]['cite_fecha'])); ?>" placeholder="dd/mm/YY" title="SELECCIONE FECHA CITE" disabled="true">
                                        </label>
                                        </div>
                                      </div>
                                    </section>
                                    <section class="col col-6">
                                      <label class="label" style="color:#1c8def;"><b>JUSTIFICACI&Oacute;N</b></label>
                                      <label class="input">
                                        <i class="icon-append fa fa-tag"></i>
                                        <textarea class="form-control" name="rec" id="rec" maxlength="2000" rows="3" style="width:100%;" placeholder="JUSTIFICACIÓN A LA MODIFICACIÓN...." disabled="true"><?php echo $cert_editado[0]['justificacion'];?></textarea>
                                      </label>
                                    </section>
                                  </div>
                                </fieldset>

                                  <fieldset>
                                    <section class="col col-6">
                                      <input id="searchTerm" type="text" onkeyup="doSearch()" class="form-control" placeholder="BUSCADOR DE ITEM...."/><br>
                                    </section>
                                    <div class="row" align="center">
                                      <div class="table-responsive" align="center">
                                        <center>
                                          <?php echo $requerimientos;?>
                                        </center>
                                      </div>
                                    </div>
                                  </fieldset>
                                  <hr>
                                    <article class="col-sm-3">
                                      <h1 class="page-title txt-color-blueDark"><i class="fa fa-desktop fa-fw "></i> NOTA  IMPORTANTE !! </h1>
                                      <div class="alert alert-success fade in">
                                        <i class="fa-fw fa fa-check"></i>
                                        <strong>Valido : </strong> Requerimientos a Reformular..
                                      </div>
                                
                                      <div class="alert alert-danger fade in">
                                        <i class="fa-fw fa fa-times"></i>
                                        <strong>No Valido : </strong> Requerimiento a no ser Reformulado..
                                      </div>
                                
                                    </article>
                                  <hr>
                                  <footer>
                                    <input type="hidden" name="tot" id="tot" value="<?php echo count($lista);?>">
                                    <input type="hidden" name="tot_temp" id="tot_temp" value="<?php echo $nro_meses;?>">
                                    <div id="but" <?php echo $display;?>>
                                      <input type="button" value="GUARDAR CERTIFICACI&Oacute;N POA" id="btsubmit_edit" class="btn btn-success" title="GUARDAR CERTIFICACIÓN POA">
                                      <a href="<?php echo base_url().'index.php/cert/list_poas'; ?>" class="btn btn-default" title="MIS OPERACIONES"> CANCELAR </a>
                                    </div>
                                  </footer>
                                  <div id="load" style="display: none" align="center">
                                    <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GENERANDO CERTIFICACI&Oacute;N POA ....</b>
                                  </div>
                              </form>
                              </div>
                            </div>
                          </div>
                        </article>
                        
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
    <!-- ========================================================================================================= -->
    
        <!-- ===== MODIFICAR DATOS REQUERIMIENTO ===== -->
        <div class="modal fade" id="modal_mod_ins" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; Salir Formulario</span></button>
              </div>
              <div class="modal-body">
                <form action="<?php echo site_url().'/modificaciones/cmod_insumo/valida_update_insumo_cpoa'?>" id="form_mod" name="form_mod" class="smart-form" method="post">
                    <input type="hidden" name="ins_id" id="ins_id">
                    <input type="hidden" name="cpoaa_id" id="cpoaa_id" value="<?php echo $cert_editado[0]['cpoaa_id'];?>">

                    <h2 class="alert alert-warning"><center>MODIFICAR DATOS REQUERIMIENTO</center></h2>
                    
                    <fieldset>
                        <div class="row">
                          <section class="col col-8">
                            <label class="label"><font color="blue"><b>DETALLE </b>(Editable)</font></label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="2" name="detalle" id="detalle" title="MODIFICAR DETALLE DEL REQUERIMIENTO"></textarea>
                            </label>
                          </section>
                          <section class="col col-2">
                            <label class="label">MONTO SALDO PARTIDA</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="hidden" name="saldo" id="saldo">
                              <input type="text" name="sal" id="sal" disabled="true">
                            </label>
                          </section>
                          <section class="col col-2">
                            <label class="label">SALDO</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="monto_dif" id="monto_dif" disabled="true">
                            </label>
                          </section>
                        </div>
                        
                        <div class="row">
                          <section class="col col-3">
                            <label class="label">CANTIDAD</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="cantidad" id="cantidad" onkeypress="return justNumbers(event);" title="MODIFICAR CANTIDAD" disabled="true">
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label">COSTO UNITARIO</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="costou" id="costou" onkeypress="return justNumbers(event);" onpaste="return false" title="MODIFICAR COSTO UNITARIO" disabled="true">
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label">COSTO TOTAL</label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="hidden" name="costot" id="costot">
                              <input type="text" name="costot2" id="costot2" disabled="true">
                            </label>
                          </section>
                          <section class="col col-3">
                            <label class="label"><font color="blue"><b>UNIDAD DE MEDIDA </b>(Editable)</font></label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="umedida" id="umedida" title="MODIFICAR UNIDAD DE MEDIDA">
                            </label>
                          </section>
                        </div>

                        <br>
                        <div id="amtit"></div>
                        <header><b>DISTRIBUCI&Oacute;N PRESUPUESTARIA : <?php echo $this->session->userdata('gestion')?></b><br>
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

                      <div id="mbut" style="display:none;">
                          <footer>
                            <button type="button" name="subir_mins" id="subir_mins" class="btn btn-info" >MODIFICAR REQUERIMIENTO</button>
                            <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                          </footer>
                      </div>
                      <div id="loadm" style="display: none" align="center">
                          <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>MODIFICANDO DATOS DEL REQUERIMIENTO ...</b>
                      </div>
                </form>
            </div>
          </div>
        </div>
    </div>





    <!---- MODAL SUBIR ARCHIVO ---->
    <div class="modal fade" id="modal_importar_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" id="mdialTamanio2">
        <div class="modal-content">
          <div class="modal-header">
              <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
              <h2><div id="titulo"></div></h2>
              <section id="widget-grid" class="">
                <h1><?php echo 'AJUSTE CERTIFICACIÓN POA <b> N° : '.$cpoa[0]['cpoa_codigo']; ?></b></h1>
              </section>
              <div class="row">
                <form action="<?php echo site_url().'/modificaciones/cmod_insumo/importar_ajuste_cpoa'?>" enctype="multipart/form-data" id="form_subir_ajuste" name="form_subir_ajuste" method="post">  
                  <input type="hidden" name="cpoaa_id" value="<?php echo $cert_editado[0]['cpoaa_id'];?>">
                  <fieldset>
                    <div class="form-group">
                      <center><div id="img"></div></center>
                      <hr>
                        <p class="alert alert-info">
                          <i class="fa fa-info"></i> Por favor guardar el archivo (Excel.xls) a extension (.csv) delimitado por (; "Punto y comas"). verificar el archivo .csv para su correcta importaci&oacute;n
                        </p>
                    </div>
                  </fieldset>  
                
                  <div class="form-group">
                    <b>SELECCIONAR ARCHIVO CSV</b>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
                        <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
                        <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
                      </span>
                      <span class="form-control"></span>
                    </div>
                </div>
                  
                  <div>
                      <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;">SUBIR ARCHIVO DE AJUSTE.CSV</button><br>
                      <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                  </div>
                </form> 
              </div>
            </div>
        </div>
      </div>
    </div>






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
        <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
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
        <!-- browser msie issue fix -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
        <!-- FastClick: For mobile devices -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
        <!-- Demo purpose only -->
        <script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/certificacionpoa/certpoa.js"></script> 
    </body>
</html>
