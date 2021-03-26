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
                    <a href="<?php echo site_url("admin").'/dashboard';?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
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
                    <li>Evaluaci&oacute;n POA</li><li>Certificaci&oacute;n POA</li><li>Mis Certificaciones POA</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <section id="widget-grid" class="well">
                                <h1>RESPONSABLE : <?php echo $resp; ?> -> <small><?php echo $res_dep;?></small>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                            <div class="well">
                                <div class="btn-group btn-group-justified">
                                    <a class="btn btn-warning" href="<?php echo base_url().'index.php/cert/list_poas'?>"  title="GENERAR NUEVA CERTIFICACI&Oacute;N POA"><i class="fa fa-folder-o"></i> GENERAR NUEVA CERTIFICACI&Oacute;N POA</a>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div class="row">
                    <?php echo $list;?>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                  <h2 class="font-md"><strong>MIS CERTIFICACIONES POA - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                              </header>
                                <div>
                                    <div class="widget-body no-padding">
                                       <div id="lista_certificaciones"></div>
                                    </div>
                                    <!-- end widget content -->
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
        <!-- ========================================================================================================= -->
        <!-- ===== EDITAR CERTIFICACION ===== -->
        <div class="modal fade" id="modal_anular_cert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/ejecucion/cert_poa/valida_anulacion_certpoa'?>" id="form_anular" name="form_anular" class="form-horizontal" method="post">
                    <input type="hidden" name="cert_id" id="cert_id">
                    <div id="titulo_edit"></div>
                    <fieldset>
                        <div class="form-group">
                            <div id="titulo2"></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" id="error1">NRO CITE</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="cite" id="cite_edit" placeholder="XXX-XXXX" title="Registre Cite">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" id="error2">JUSTIFICACI&Oacute;N</label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="justificacion" id="justificacion_edit" placeholder="Justificaci&oacute;n" rows="4"></textarea>
                            </div>
                        </div>
                        <font color=red><b>SOLO PODRA REALIZAR UNA EDICI&Oacute;N DE CERTIFICACI&Oacute;N POA ..</b></font>
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                            <div id="but" style="display: block;">
                                <button class="btn btn-default" data-dismiss="modal" id="mcl_edit" title="CANCELAR">CANCELAR</button>
                                <button type="button" name="anular_edit" id="anular_edit" class="btn btn-info" >EDITAR CERTIFICACI&Oacute;N</button>
                            </div>
                        </div>
                        <div id="loads" style="display: none" align="center">
                            <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>RECUPERANDO ITEMS CERTIFICADOS .....</b>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- =============================== -->
        <!-- ===== ELIMINAR CERIFICACION POA ===== -->
        <div class="modal fade" id="modal_del_cert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                  <div class="modal-body">
                    <form action="<?php echo site_url().'/ejecucion/cert_poa/valida_eliminacion_certpoa'?>" id="form_delete" name="form_delete" class="form-horizontal" method="post">
                        <input type="hidden" name="cpoa_id" id="cpoa_id">
                        <div id="titulo_del"></div>
                        <fieldset>
                            <div class="form-group">
                                <div id="titulo_del2"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" id="error1m">NRO. CITE</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="cite" id="cite" placeholder="XXX-XXXX" title="Registre Cite">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label" id="error2m">JUSTIFICACI&Oacute;N</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="justificacion" id="justificacion" placeholder="Justificaci&oacute;n" rows="4"></textarea>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-actions">
                            <div class="row">
                                <div id="but_del" style="display: block;">
                                    <button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
                                    <button type="button" name="delete" id="delete" class="btn btn-info" >ELIMINAR CERTIFICACI&Oacute;N</button>
                                </div>
                            </div>
                            <div id="load_del" style="display: none" align="center">
                                <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>ELIMINANDO CERTIFICADO .....</b>
                            </div>
                        </div>
                    </form>
                </div>
              </div>
            </div>
        </div>
        <!-- =============================== -->
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
        <!-- Demo purpose only -->
        <script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
        <!-- Voice command : plugin -->
        <script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
           
        <script type="text/javascript">
            function editar_certpoa(cert_id) {
                document.getElementById("cert_id").value = cert_id;
                var url = "<?php echo site_url().'/ejecucion/cert_poa/get_datos_certificado'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "cert_id="+cert_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        $('#titulo_edit').html('<h2 class="alert alert-warning"><center>EDICI&Oacute;N PARCIAL - CERTIFICACI&Oacute;N : '+response.certificado[0]['cpoa_codigo']+'</center></h2>');
                        $('#titulo2').html('<font color="blue" size="3">U.E. '+response.certificado[0]['proy_nombre']+'</font>');
                        document.getElementById("cite_edit").value = '';
                        document.getElementById("justificacion_edit").value = '';
                        $('#error1').html('NRO CITE');
                        $('#error2').html('JUSTIFICACIÓN');
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
                
                // ===VALIDAR EL FORMULARIO DE MODIFICACION
                $("#anular_edit").on("click", function () {
                    var error='false';
                    var cite=document.getElementById('cite_edit').value;
                    var justificacion=document.getElementById('justificacion_edit').value;
                    if(!cite){
                        $('#error1').html('<font color="red" size="1">NRO CITE (*)</font>');
                        document.form_anular.cite_edit.focus() 
                        return 0;
                    }
                    if(!justificacion){
                        $('#error1').html('NRO CITE');
                        $('#error2').html('<font color="red" size="1">JUSTIFICACIÓN (*)</font>');
                        document.form_anular.justificacion_edit.focus() 
                        return 0;
                    }
                 
                    if(cite.length!=0 & justificacion.length!=0){
                        $('#error1').html('NRO CITE');
                        $('#error2').html('JUSTIFICACIÓN');
                         alertify.confirm("DESEA REALIZAR LA MODIFICACIÓN DE LA CERTIFICACI&Oacute;N ?", function (a) {
                            if (a) {
                                document.getElementById("loads").style.display = 'block';
                                document.forms['form_anular'].submit(); /// id del formulario
                                document.getElementById("but").style.display = 'none';
                            } else {
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });
                    }
                    else{
                        alertify.error("REGISTRE DATOS");
                    }
                });
            }
        
            function eliminar_certpoa(cert_id) {
                document.getElementById("cpoa_id").value = cert_id;
                var url = "<?php echo site_url().'/ejecucion/cert_poa/get_datos_certificado'?>";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "cert_id="+cert_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        $('#titulo_del').html('<h2 class="alert alert-danger"><center>ELIMINAR CERTIFICACI&Oacute;N : '+response.certificado[0]['cpoa_codigo']+'</center></h2>');
                        $('#titulo_del2').html('<font color="blue" size="3">U.E. '+response.certificado[0]['proy_nombre']+'</font>');
                        document.getElementById("cite").value = '';
                        document.getElementById("justificacion").value = '';
                        $('#error1m').html('NRO CITE');
                        $('#error2m').html('JUSTIFICACIÓN');
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
                
                // ===VALIDAR EL FORMULARIO DE ELIMINACION
                $("#delete").on("click", function () {
                    var error='false';
                    var cite=document.getElementById('cite').value;
                    var justificacion=document.getElementById('justificacion').value;
                    if(!cite){
                        $('#error1m').html('<font color="red" size="1">NRO CITE (*)</font>');
                        document.form_delete.cite.focus() 
                        return 0;
                    }
                    if(!justificacion){
                        $('#error1m').html('NRO CITE');
                        $('#error2m').html('<font color="red" size="1">JUSTIFICACIÓN (*)</font>');
                        document.form_delete.justificacion.focus() 
                        return 0;
                    }
                 
                    if(cite.length!=0 & justificacion.length!=0){
                        $('#error1').html('NRO CITE');
                        $('#error2').html('JUSTIFICACIÓN');
                         alertify.confirm("DESEA REALIZAR LA ANULACIÓN DE LA CERTIFICACI&Oacute;N ?", function (a) {
                            if (a) {
                                document.getElementById("load_del").style.display = 'block';
                                document.forms['form_delete'].submit(); /// id del formulario
                                document.getElementById("but_del").style.display = 'none';
                            } else {
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });
                    }
                    else{
                        alertify.error("REGISTRE DATOS");
                    }
                });

        }

        $(document).ready(function() {
            pageSetUp();
            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    dist_id=$('[name="dist_id"]').val();
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/ejec/get_uadministrativas", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#dist_id").html(data);
                        $("#tp_id").html('');
                        $("#lista_certificaciones").html('');
                    });
                });
            });
            $("#dist_id").change(function () {
                $("#dist_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/ejec/get_uadministrativas", { elegido: elegido,accion:'tipo' }, function(data){
                        $("#tp_id").html(data);
                        $("#lista_certificaciones").html('');
                    });
                });
            });
            $("#tp_id").change(function () {
                $("#tp_id option:selected").each(function () {
                    dist_id=$('[name="dist_id"]').val();
                    tp_id=$(this).val();
                    $('#lista_certificaciones').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Certificaciones POA ...</div>');
                    var url = "<?php echo site_url("")?>/ejecucion/cert_poa/get_lista_cpoas";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "dist_id="+dist_id+"&tp_id="+tp_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                        if (response.respuesta == 'correcto') {
                            $('#lista_certificaciones').fadeIn(1000).html(response.lista_certpoa);
                        }
                        else{
                            alertify.error("ERROR AL LISTAR CERTIFICACIONES POA");
                        }
                    });  
                });
            });
        })
        </script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <!-- <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script> -->
    </body>
</html>
