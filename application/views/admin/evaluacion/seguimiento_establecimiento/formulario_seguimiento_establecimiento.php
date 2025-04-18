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
        <style>
            #mdialTamanio{
              width: 45% !important;
            }
            .movimiento {
                position: relative;
                animation: mover 5s linear infinite;
            }
            @keyframes mover {
                0% { left: 0; }
                50% { left: 45%; }
                100% { left: 0; }
            }
            table {
                width: 100%; /* Ajusta el ancho de la tabla */
            }
            th, td {
                border: 2px solid #000;
                padding: 2px;
                border-color: #1c7368;
            }

        /* Estilo del popup */
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid #ccc;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            width: 80%; /* Ajusta el ancho según sea necesario */
            height: 80%; /* Ajusta la altura según sea necesario */
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .loading_rep {
            display: none;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            color: #007bff;
        }
        iframe {
            width: 100%;
            height: 90%; /* Ajusta la altura según sea necesario */
            border: none;
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
                    <li><a href="<?php echo base_url().'index.php/dashboar_seguimiento_poa';?>" title="MENU PRINCIPAL">DASHBOARD</a></li><li>Seguimiento POA - Formulario N° 4</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="well">
                       <?php echo $titulo.' '.$formularios_seguimiento.' '.$formulario_seguimiento_mensual.' '.$salir;?>
                    </div>
                        <div class="row">
                           <!--  <?php echo $update_eval;?> -->
                            <article class="col-sm-12">
                                <!-- new widget -->
                                <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                                    <header>
                                        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                                        <h2>SEGUIMIENTO POA </h2>

                                        <ul class="nav nav-tabs pull-right in" id="myTab">
                                            <li class="active">
                                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">FORMULARIO SEGUIMIENTO POA</span></a>
                                            </li>
                                            <li>
                                                <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO EVALUACIÓN POA</span></a>
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
                                                <div id="calificacion"></div>
                                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="SEGUIMIENTO POA">
                                                    <?php echo $form4_programados;?>
                                                </div>
                                                <!-- end s1 tab pane -->

                                                <div class="tab-pane fade" id="s3" title="CUADRO DE EVALUACION POA">
                                                   <hr>
                                                   <?php echo $s2; ?>
                                                </div>
                                                <!-- end s3 tab pane -->
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
        <!-- END PAGE FOOTER -->

       <div class="overlay" id="overlay" onclick="hidePopup()"></div>
        <div class="popup" id="popup">
            <h2>Reporte de Seguimiento POA</h2>
            <div class="loading_rep" id="loading_rep"><?php echo $cargando; ?></div>
            <iframe id="reportIframe" src="" onload="hideLoading()"></iframe>
            <button onclick="hidePopup()">Cerrar</button>
        </div>
    <!-- ========================= -->
        <div class="modal fade" id="modal_nuevo_ff2" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" style="width:85%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
                    </div>
                    <div class="modal-body">
                        <div id="calificacion_form4"></div>
                        <div class="row">
                            <div id="temporalidad"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL UPDATE SEG POA   -->
        <div class="modal fade" id="modal_update_eval" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" id="mdialTamanio">
                <div class="modal-content">
                    <form id="form_update" novalidate="novalidate" method="post">
                        <input type="hidden" name="com_id" id="com_id">
                        <div id="content_valida">
                            <center><div class="loading" align="center"><h2>Actualizando Evaluaci&oacute;n  POA <br><div id="tit"></div></h2><br><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /></div></center>
                        </div>
                        <div id="load" style="display: none;"><center><img src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"><hr><b>ACTUALIZANDO FORMULARIO SEGUIMIENTO POA ...</b></center></div>
                            <p>
                                <div id="but" align="right" style="display:none;">
                                    <button type="button" name="but_update" id="but_update" class="btn btn-success">ACEPTAR EVALUACI&Oacute;N</button>&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            </p>
                    </form>
                </div>
            </div>
        </div>

     <!--  ==================== -->
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
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
        <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>

        <script src="<?php echo base_url(); ?>assets/captura/html2canvas.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/captura/canvasjs.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/captura/jsPdf.debug.js"></script>

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
        <!-- <script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/seguimiento.js"></script>  -->
        <script src="<?php echo base_url(); ?>mis_js/seguimientopoa/seguimiento.js"></script> 
        <script>
            function showPopup() {
                const com_id = '<?php echo $com_id; ?>'; // Asegúrate de que estas variables estén definidas
                const mes = '<?php echo $this->verif_mes[1]; ?>'; // Asegúrate de que estas variables estén definidas
                const reportUrl = "<?php echo site_url(""); ?>/seguimiento_poa/reporte_seguimientopoa_mensual/" + com_id + "/" + mes;

                document.getElementById('reportIframe').src = reportUrl; // Establece la URL del iframe
                document.getElementById('overlay').style.display = 'block';
                document.getElementById('popup').style.display = 'block';
                document.getElementById('loading_rep').style.display = 'block'; // Muestra el loading
            }

            function hideLoading() {
                document.getElementById('loading_rep').style.display = 'none'; // Oculta el loading
            }

            function hidePopup() {
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('popup').style.display = 'none';
                document.getElementById('reportIframe').src = ''; // Limpia el src del iframe al cerrar
                hideLoading(); // Asegúrate de ocultar el loading al cerrar
            }
        </script>
    </body>
</html>