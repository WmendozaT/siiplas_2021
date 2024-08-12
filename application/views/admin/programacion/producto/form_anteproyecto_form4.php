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
        <?php echo $stylo;?>




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
                    <li>Programacion POA</li><li>Formulario N° 4</li><li>Ante Proyecto POA</li>
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
    <div class="jarviswidget jarviswidget-color-darken">
        <header>
            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
            <h2 class="font-md"></h2>  
        </header>
        <div>
            <div class="widget-body no-padding">
                <div style="display: flex; flex-direction: column; width: 100%;">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="datatable_fixed_column" class="table table-bordered" style="width: 130%; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="COD. ACT."/>
                                    </th>
                                    <th class="hasinput" style="width:10%; text-align: center;"></th>
                                    <th class="hasinput" style="width:12%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="ACTIVIDAD"/>
                                    </th>
                                    <th class="hasinput" style="width:12%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="RESULTADO"/>
                                    </th>
                                    <th class="hasinput" style="width:10%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="UNIDAD RESPONSABLE"/>
                                    </th>
                                    <th class="hasinput" style="width:8%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="TIP. INDI."/>
                                    </th>
                                    <th class="hasinput" style="width:8%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="INDICADOR"/>
                                    </th>
                                    <th class="hasinput" style="width:8%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="MEDIO DE VERIFICACION"/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="META"/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="ENE."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="FEB."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="MAR."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="ABR."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="MAY."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="JUN."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="JUL."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="AGO."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="SEPT."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="OCT."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="NOV."/>
                                    </th>
                                    <th class="hasinput" style="width:5%; text-align: center;">
                                        <input type="text" class="form-control" placeholder="DIC."/>
                                    </th>
                                </tr>                          
                                <tr>
                                    <th style="width:5%; text-align: center;">COD. ACT.</th>
                                    <th style="width:10%; text-align: center;">COD.<br>OPE.</th>
                                    <th style="width:12%; text-align: center;">ACTIVIDAD</th>
                                    <th style="width:12%; text-align: center;">RESULTADO</th>
                                    <th style="width:10%; text-align: center;">UNIDAD RESPONSABLE</th>
                                    <th style="width:8%; text-align: center;">TIPO INDICADOR</th>
                                    <th style="width:8%; text-align: center;">INDICADOR</th>
                                    <th style="width:8%; text-align: center;">MEDIO DE VERIFICACIÓN</th>
                                    <th style="width:5%; text-align: center;">META</th>
                                    <th style="width:5%; text-align: center;">ENE.</th>
                                    <th style="width:5%; text-align: center;">FEB.</th>
                                    <th style="width:5%; text-align: center;">MAR.</th>
                                    <th style="width:5%; text-align: center;">ABR.</th>
                                    <th style="width:5%; text-align: center;">MAY.</th>
                                    <th style="width:5%; text-align: center;">JUN.</th>
                                    <th style="width:5%; text-align: center;">JUL.</th>
                                    <th style="width:5%; text-align: center;">AGO.</th>
                                    <th style="width:5%; text-align: center;">SEPT.</th>
                                    <th style="width:5%; text-align: center;">OCT.</th>
                                    <th style="width:5%; text-align: center;">NOV.</th>
                                    <th style="width:5%; text-align: center;">DIC.</th>
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
    </div>
</article>
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        <!-- END PAGE FOOTER -->

        <!-- ==== MODAL FORMULARIO DE REGISTRO FORM 4 ==== -->
        <div class="modal fade" id="modal_nuevo_form" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog" id="mdialTamanio">
          <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>NUEVO REGISTRO FORM N° 4 - ACTIVIDAD <?php echo $this->session->userData('gestion');?></center></h2>
              <form action="<?php echo site_url().'/programacion/producto/valida_producto'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>"> 
                  <header><b>DATOS GENERALES </b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label"><b>ACTIVIDAD</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="prod" id="prod" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="REGISTRAR ACTIVIDAD"></textarea>
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label"><b>RESULTADO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="resultado" id="resultado" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="REGISTRAR RESULTADO"></textarea>
                        </label>
                      </section>
                      <?php echo $uni_resp;?>
                      <section class="col col-3">
                        <label class="label"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="verificacion" id="verificacion" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                        </label>
                      </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label"><b>META</b></label>
                            <label class="input">
                              <i class="icon-append fa fa-tag"></i>
                              <input type="text" name="meta" id="meta" value="0" onkeyup="verif_suma_programado()" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="REGISTRE META">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label"><b>TIPO DE INDICADOR</b></label>
                            <select class="form-control" id="tipo_i" name="tipo_i" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="SELECCIONE TIPO DE INDICADOR">
                                <option value="">Seleccione Tipo de Indicador</option>
                                <?php 
                                  foreach($indi as $row){ ?>
                                  <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                                <?php } ?>        
                            </select>
                        </section>
                        <div id="trep" style="display:none;" >
                        <section class="col col-3">
                          <label class="label"><b>TIPO DE META</b></label>
                            <select class="form-control" id="tp_met" name="tp_met" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="SELECCIONE TIPO DE META">
                              <option value="">Seleccione Tipo de Meta</option>
                                <?php 
                                  foreach($metas as $row){ 
                                    if($row['mt_id']==3){ ?>
                                      <option value="<?php echo $row['mt_id']; ?>" selected><?php echo $row['mt_tipo']; ?></option>
                                      <?php
                                    }
                                    else{ ?>
                                      <option value="<?php echo $row['mt_id']; ?>"><?php echo $row['mt_tipo']; ?></option>
                                      <?php
                                    }
                                  }
                                ?>
                          </select>
                        </section> 
                        </div>
                        <section class="col col-3">
                            <label class="label"><b>INDICADOR</b></label>
                            <label class="textarea">
                              <i class="icon-append fa fa-tag"></i>
                              <textarea rows="3" name="indicador" id="indicador" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;" title="REGISTRE DESCRIPCIÓN INDICADOR"></textarea>
                            </label>
                        </section>
                    </div>
                 
                    <div id="atit"></div>
                    <header><b>TEMPORALIDAD - <?php echo $this->session->userdata('gestion')?></b><br>
                      <label class="label"><div id="ff"></div></label>
                    </header>
                    <br>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">TOTAL TEMPORALIDAD</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="total" id="total" value="0" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  disabled="true">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>ENERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="ENERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>FEBRERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MARZO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="MARZO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>ABRIL</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="ABRIL - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MAYO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="MAYO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>JUNIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="JUNIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>JULIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="JULIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>AGOSTO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>SEPTIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>OCTUBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>NOVIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m11" id="m11" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>DICIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m12" id="m12" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 5) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"  title="DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                  </fieldset>
        
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_ope" id="subir_ope" class="btn btn-info" >GUARDAR DATOS ACTIVIDAD</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                    <div id="loadp" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO INFORMACI&Oacute;N</b>
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
    </div>



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
        <!-- Voice command : plugin -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/programacionpoa/form4.js"></script> 
    </body>
</html>
