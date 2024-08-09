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
                        <div class="jarviswidget jarviswidget-color-darken" >
                          <header>
                              <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                              <h2 class="font-md"></h2>  
                          </header>
                          <div>
                            <div class="widget-body no-padding">
                              <div class="table-responsive">
                                <table id="datatable_fixed_column" class="table table-bordered" width="100%">
                                <!-- <table id="datatable_fixed_column" class="table table-bordered" width="100%"> -->
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="COD. ACT."/>
                                            </th>
                                            <th class="hasinput">
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="ACTIVIDAD"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="RESULTADO"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="UNIDAD RESPONSABLE"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="TIP. INDI."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="INDICADOR"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="MEDIO DE VERIFICACION"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="META"/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="ENE."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="FEB."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="MAR."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="ABR."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="MAY."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="JUN."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="JUL."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="AGO."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="SEPT."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="OCT."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="NOV."/>
                                            </th>
                                            <th class="hasinput">
                                                <input type="text" class="form-control" placeholder="DIC."/>
                                            </th>
                                            
                                        </tr>                          
                                        <tr>
                                            <th style="width:1%; text-align: center;">ELI.</th>
                                            <th style="width:3%; text-align: center;">COD. ACT.</th>
                                            <th style="width:3%; text-align: center;">ALINEACION POA-ACP</th>
                                            <th style="width:10%; text-align: center;">ACTIVIDAD</th>
                                            <th style="width:10%; text-align: center;">RESULTADO</th>
                                            <th style="width:8%; text-align: center;">UNIDAD RESPONSABLE</th>
                                            <th style="width:5%; text-align: center;">TIPO INDICADOR</th>
                                            <th style="width:5%; text-align: center;">INDICADOR</th>
                                            <th style="width:7%; text-align: center;">MEDIO DE VERIFICACIÓN</th>
                                            <th style="width:3.3%; text-align: center;">META</th>
                                            <th style="width:3.3%; text-align: center;">ENE.</th>
                                            <th style="width:3.3%; text-align: center;">FEB.</th>
                                            <th style="width:3.3%; text-align: center;">MAR.</th>
                                            <th style="width:3.3%; text-align: center;">ABR.</th>
                                            <th style="width:3.3%; text-align: center;">MAY.</th>
                                            <th style="width:3.3%; text-align: center;">JUN.</th>
                                            <th style="width:3.3%; text-align: center;">JUL.</th>
                                            <th style="width:3.3%; text-align: center;">AGO.</th>
                                            <th style="width:3.3%; text-align: center;">SEPT.</th>
                                            <th style="width:3.3%; text-align: center;">OCT.</th>
                                            <th style="width:3.3%; text-align: center;">NOV.</th>
                                            <th style="width:3.3%; text-align: center;">DIC.</th>
                                            
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
        <!-- END PAGE FOOTER -->

          <!-- ==== MODAL FORMULARIO DE REGISTRO FORM 4 ==== -->
    <div class="modal fade" id="modal_nuevo_form" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio">
      <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>NUEVO REGISTRO ACTIVIDAD (FORMULARIO N° 4 / <?php echo $this->session->userData('gestion');?>)</center></h2>
              <form id="form_nuevo2025" name="form_nuevo2025" class="smart-form" method="post">
                  <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>"> 
                  <fieldset>          
                    <div class="row">
                      <section class="col col-3">
                        <label class="label"><b>ACTIVIDAD</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="descripcion" id="descripcion" title="REGISTRAR ACTIVIDAD" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"></textarea>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>RESULTADO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="resultado" id="resultado" title="REGISTRAR RESULTADO" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"></textarea>
                        </label>
                      </section>
                      <section class="col col-1">
                        <label class="label"><b>TIPO DE INDICADOR</b></label>
                        <select class="form-control" id="indi_id" name="indi_id" title="SELECCIONE TIPO DE INDICADOR" style="width:100%; font-size:10px; color:blue; background-color: #e3fcf8;">
                            <option value="">Seleccione Tipo de Indicador</option>
                            <?php 
                              foreach($indi as $row){ ?>
                              <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                            <?php } ?>        
                        </select>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>INDICADOR</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="indicador" id="indicador" title="REGISTRE DESCRIPCIÓN INDICADOR" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"></textarea>
                        </label>
                      </section>
                      <?php echo $uni_resp; ?>
                      <section class="col col-2">
                        <label class="label"><b>MEDIO DE VERIFICACION</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="3" name="m_verificacion" id="m_verificacion" title="REGISTRE MEDIO DE VERIFICACION" style="width:100%; font-size:11px; color:blue; background-color: #e3fcf8;"></textarea>
                        </label>
                      </section>
                    </div>
                  </fieldset>
        
                    <footer>
                      <button type="button" name="subir_form4_nuv" id="subir_form4_nuv" class="btn btn-info" >GUARDAR INFORMACI&Oacute;N</button>
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
