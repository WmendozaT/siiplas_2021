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
          <style>
            table{font-size: 9.5px;
            width: 100%;
            max-width:1550px;
            overflow-x: scroll;
            font-family: Copperplate;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 9.5px;
            }
            input[type="checkbox"] {
              display:inline-block;
              width:20px;
              height:20px;
              margin:-1px 6px 0 0;
              vertical-align:middle;
              cursor:pointer;
            }
            #mdialTamanio{
              width: 70% !important;
            }
          </style>
          <script language="javascript">
            function doSearch(){
              var tableReg = document.getElementById('datos');
              var searchText = document.getElementById('searchTerm').value.toLowerCase();
              var cellsOfRow="";
              var found=false;
              var compareWith="";
         
              // Recorremos todas las filas con contenido de la tabla
              for (var i = 1; i < tableReg.rows.length; i++){
                cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                found = false;
                // Recorremos todas las celdas
                for (var j = 0; j < cellsOfRow.length && !found; j++){
                  compareWith = cellsOfRow[j].innerHTML.toLowerCase();
                  // Buscamos el texto en el contenido de la celda
                  if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)){
                    found = true;
                  }
                }
                if(found) {
                  tableReg.rows[i].style.display = '';
                } else {
                  // si no ha encontrado ninguna coincidencia, esconde la
                  // fila de la tabla
                  tableReg.rows[i].style.display = 'none';
                }
              }
            }
          </script>

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
                    <li>Evaluaci&oacute;n POA</li><li>...</li><li>Mis Certificaciones POA</li><li>....</li><li>Editar Certificaci&oacute;n POA</li><li>Mis Requerimientos</li>
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
                                    <h1><b>RESPONSABLE : </b><?php echo $resp; ?> -> <small><?php echo $res_dep;?></small>
                                    <?php echo $titulo;?>
                                </div>
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
                              <h2>MIS REQUERIMIENTOS</h2>
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
                                    <input type="hidden" name="tot" id="tot" value="<?php echo $nro_cert;?>">
                                    <input type="hidden" name="tot_temp" id="tot_temp" value="<?php echo $nro_meses;?>">
                                    <div id="but">
                                      <input type="button" value="GUARDAR CERTIFICACI&Oacute;N POA" id="btsubmit" class="btn btn-success" title="GUARDAR CERTIFICACIÓN POA">
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
                        <header><b>TEMPORALIDAD PRESUPUESTARIA : <?php echo $this->session->userdata('gestion')?></b><br>
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
        <script type="text/javascript">
          $(document).ready(function() {
            pageSetUp();
          })
        </script>

       <!-- MODIFICAR REQUERIMIENTO -->
      <script type="text/javascript">
          $(function () {
              $(".mod_ins").on("click", function (e) {
                ins_id = $(this).attr('name');
                document.getElementById("ins_id").value=ins_id;
                cpoaa_id=document.getElementById("cpoaa_id").value;

                var url = "<?php echo site_url().'/ejecucion/cert_poa/get_requerimiento_cert'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "ins_id="+ins_id+"&cpoaa_id="+cpoaa_id
                });

                request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                  if(response.verif_cert==1){
                   
                    $( "#detalle" ).prop( "disabled", true );
                    $( "#umedida" ).prop( "disabled", true );
                  }
                  else{
                
                    $( "#detalle" ).prop( "disabled", false );
                    $( "#umedida" ).prop( "disabled", false );
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
                   document.getElementById("mtot").value = response.prog[0]['programado_total'];
                   document.getElementById("monto_cert").value = response.monto_certificado;
                   $('#monto').html('<span class="label bg-color-blueDark pull-right" style="color: #fff;">MONTO YA CERTIFICADO : '+response.monto_certificado+' Bs. &nbsp;&nbsp;| &nbsp;&nbsp; MONTO SELECCIONADO : '+response.monto_certificado_item+' Bs.</span>');
                  // $('#mbut').slideDown();

                   if(response.prog[0]['programado_total']!=response.insumo[0]['ins_costo_total']){
                    $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                    $('#mbut').slideUp();
                   }

                  // alert(response.prog[0]['programado_total']+'--'+response.insumo[0]['ins_costo_total'])

                   for (var i = 1; i <=12; i++) {
                    mes=mes_texto(i);
                   
                    document.getElementById("mm"+i).value = response.prog[0]['mes'+i];
                 
                    if(response.verif_mes['verf_mes'+i]==1){
                      document.getElementById("mm"+i).disabled = true;
                      $('#mess'+i).html('<font color=red><b>'+mes+'</b> (Ya Certificado)</font>');
                    }
                    else{
                      if(response.verif_mes['verf_mes'+i]==2){
                        $('#mess'+i).html('<font color=blue><b>'+mes+'</b> (Editable)</font>');
                      }
                      else{
                        $('#mess'+i).html('<b>'+mes+'</b> (Editable)');
                      }
                      document.getElementById("mm"+i).disabled = false;
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
                // =======VALIDAR EL FORMULARIO DE MODIFICACION
                $("#subir_mins").on("click", function (e) {
                    var $validator = $("#form_mod").validate({
                         rules: {
                          ins_id: { //// Insumo
                          required: true,
                          },
                          detalle: { //// Detalle
                              required: true,
                          },
                          umedida: { //// unidad medida
                              required: true,
                          }
                        },
                        messages: {
                            detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                            umedida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",                    
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
                            alertify.confirm("MODIFICAR DATO DEL REQUERIMIENTO ?", function (a) {
                                if (a) {
                                    document.getElementById("loadm").style.display = 'block';
                                    document.forms['form_mod'].submit();
                                    document.getElementById("mbut").style.display = 'none';

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
        </script>

        <script type="text/javascript">
        function seleccionar_temporalidad(tins_id,cpoa_id,ins_id,nro,estaChequeado){
          val = parseInt($('[name="tot_temp"]').val());
          
          if (estaChequeado == true) {
            val = val + 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f2fded";
            $('[name="tot_temp"]').val((val).toFixed(0));
            total = parseFloat($('[name="tot_temp"]').val());
            if(total==0){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }
          }

          else {
            //alert('no seleccionado')
            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                var nro_check=0;
                  for (var i = 1; i <=12; i++) {
                    if(response.temporalidad['verf_mes'+i]!=3 & response.temporalidad['verf_mes'+i]!=2){
                      if((document.getElementById("ipmm"+i+""+ins_id).checked) == true){
                        nro_check=nro_check+1;
                      }
                    }
                  }

                  if(nro_check==0){
                    document.getElementById("tr"+nro).style.backgroundColor = "#f59787";
                  }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }

            }); 
          }
        }


        function seleccionarFila(ins_id,nro,cpoa_id,estaChequeado) {
          if (estaChequeado == true) { 
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='block';
            }

            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                  if(response.verif_cert==1){
                    document.getElementById("tr"+nro).style.backgroundColor = "#f2fded";
                  }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }
            }); 
          } 
          else {
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='none';
            }

            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                if(response.verif_cert==0){
                  val = parseInt($('[name="tot_temp"]').val());
                  for (var i = 1; i <=12; i++) {
                    if(response.temporalidad['verf_mes'+i]==0 & response.temporalidad['verf_mes'+i]!=3 & response.temporalidad['verf_mes'+i]!=2){
                      if((document.getElementById("ipmm"+i+""+ins_id).checked) == true){
                    
                        document.getElementById("ipmm"+i+""+ins_id).checked = false;
                        val = val - 1;
                  
                        $('[name="tot_temp"]').val((val).toFixed(0));
                        total = parseFloat($('[name="tot_temp"]').val());

                        if(total==0){
                          $('#but').slideUp();
                        }
                        else{
                          $('#but').slideDown();
                        }
                      }
                    }
                  }
                }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }
            }); 

          }

          val = parseInt($('[name="tot"]').val());
          if (estaChequeado == true) {
            val = val + 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f5c9c2";
          } else {
            val = val - 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f59787";
          }
          $('[name="tot"]').val((val).toFixed(0));

          total = parseFloat($('[name="tot"]').val());
            if(total<=0){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }
        }


    </script>
    <script>
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
    $(function () {
        $("#btsubmit").on("click", function (e) {
            var $validator = $("#cert_form").validate({
                rules: {
                  cite_cpoa: {
                      required: true,
                  },
                  rec: {
                      required: true,
                  },
                  cite_fecha: {
                      required: true,
                  }
                },
                messages: {
                  cite_cpoa: {required: "<font color=red size=1>REGISTRE NRO. DE CITE</font>"},
                  rec: {required: "<font color=red size=1>REGISTRE RECOMENDACI&Oacute;N</font>"},
                  cite_fecha: {required: "<font color=red size=1>REGISTRE FECHA CITE</font>"}
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
            var $valid = $("#cert_form").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } 
            else {
              reset();
                alertify.confirm("GENERAR CERTIFICACI&Oacute;N POA ?", function (a) {
                    if (a) {
                        //document.getElementById('btsubmit').disabled = true;
                        document.cert_form.submit();
                        document.getElementById("load").style.display = 'block';
                       document.getElementById("but").style.display = 'none';
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        });
    });

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
