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
            #mdialTamanio{
              width: 45% !important;
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
                        <a href="#" title="EVALUACIÓN POA"> <span class="menu-item-parent">REPORTES POA</span></a>
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
                    <li>Reportes POA</li><li>Consolidado POA</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                    <?php echo $list;?>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget jarviswidget-color-darken" >
                              <header>
                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                  <h2 class="font-md"><strong>PROGRAMACI&Oacute;N POA - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                              </header>
                                <div>
                                    <div class="widget-body no-padding">
                                       <div id="lista_consolidado"></div>
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

            <!-- MODAL REPORTE POA -->
            <div class="modal fade" id="modal_poa" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" id="mdialTamanio">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                        </div>
                        <div class="modal-body">
                        <h2 class="alert alert-info"><center>MIS FORMULARIOS POA - <?php echo $this->session->userData('gestion');?></center></h2>
                            <div class="row">
                                <div id="titulo"></div>
                                <div id="content1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
        <script src="<?php echo base_url();?>/assets/js/plugin/select2/select2.min.js"></script>
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
            function ver_poa(proy_id) {
                $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
                $('#content1').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
                
                var url = "<?php echo site_url("")?>/programacion/proyecto/get_poa";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "proy_id="+proy_id
                });

                request.done(function (response, textStatus, jqXHR) {

                if (response.respuesta == 'correcto') {
                    if(response.proyecto[0]['tp_id']==1){
                        $('#titulo').html('<font size=3><b>'+response.proyecto[0]['aper_programa']+' '+response.proyecto[0]['proy_sisin']+' 000 - '+response.proyecto[0]['proy_nombre']+'</b></font>');
                    }
                    else{
                        $('#titulo').html('<font size=3><b>'+response.proyecto[0]['act_descripcion']+' '+response.proyecto[0]['abrev']+'</b></font>');
                    }
                    
                    $('#content1').fadeIn(1000).html(response.tabla);
                }
                else{
                    alertify.error("ERROR AL RECUPERAR INFORMACION");
                }

                });
            }
        </script>
           
        <script type="text/javascript">
        $(document).ready(function() {
            pageSetUp();
            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    dist_id=$('[name="dist_id"]').val();
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/rep/get_uadministrativas", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#dist_id").html(data);
                        $("#tp_id").html('');
                        $("#rep_id").html('');
                        $('#unidad').slideUp();
                        $("#lista_consolidado").html('');
                    });
                });
            });

            $("#dist_id").change(function () {
                $("#dist_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/rep/get_uadministrativas", { elegido: elegido,accion:'rep' }, function(data){
                        $("#rep_id").html(data);
                        $("#tp_id").html('');
                        $('#unidad').slideUp();
                        $("#lista_consolidado").html('');
                    });
                });
            });

            $("#rep_id").change(function () {
                $("#rep_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/rep/get_uadministrativas", { elegido: elegido,accion:'tipo' }, function(data){
                        $("#tp_id").html(data);
                        $('#unidad').slideUp();
                        $("#lista_consolidado").html('');
                    });
                });
            });


            $("#tp_id").change(function () {
                $("#tp_id option:selected").each(function () {
                    dist_id=$('[name="dist_id"]').val();
                    rep_id=$('[name="rep_id"]').val();
                    tp_id=$(this).val();

                    if(rep_id!=4){
                        $('#lista_consolidado').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
                        var url = "<?php echo site_url("")?>/reportes_cns/rep_operaciones/get_lista_reportepoa";
                        var request;
                        if (request) {
                            request.abort();
                        }
                        request = $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            data: "dist_id="+dist_id+"&tp_id="+tp_id+"&tp_rep="+rep_id
                        });

                        request.done(function (response, textStatus, jqXHR) {
                            if (response.respuesta == 'correcto') {
                                $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                            }
                            else{
                                alertify.error("ERROR AL LISTAR");
                            }
                        }); 
                    }
                    else{
                        $('#unidad').slideDown();
                        $("#lista_consolidado").html('');
                        var url = "<?php echo site_url("")?>/reportes_cns/rep_operaciones/get_unidades";
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
                                $('#proy_id').fadeIn(1000).html(response.lista_actividad);
                            }
                            else{
                                alertify.error("ERROR AL LISTAR");
                            }
                        }); 
                    }
                     
                });
            });
        })

            $("#proy_id").change(function () {
                $("#proy_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/rep/get_uadministrativas", { elegido: elegido,accion:'subactividades' }, function(data){
                        $("#sub_act").html(data);
                        $("#lista_consolidado").html('');
                    });
                });
            });

            $("#sub_act").change(function () {
                $("#sub_act option:selected").each(function () {
                    com_id=$(this).val();
                    $('#lista_consolidado').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Lista de Requerimientos Programados / Certificados ...</div>');
                    var url = "<?php echo site_url("")?>/reportes_cns/rep_operaciones/get_subactividad";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "com_id="+com_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                        if (response.respuesta == 'correcto') {
                            $('#lista_consolidado').fadeIn(1000).html(response.lista_requerimientos_certificados);
                        }
                        else{
                            alertify.error("ERROR AL LISTAR");
                        }
                    }); 

                });
            });

        </script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
    </body>
</html>
