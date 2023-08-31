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
<!--           <style>
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
          </style> -->
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
                    <li>Evaluaci&oacute;n POA</li><li>...</li><li>Mis Certificaciones POA</li><li>Generar Certificacion POA</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-10">
                            <section id="widget-grid" class="well">
                                <?php echo $titulo;?>
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
                        <?php echo $formulario; ?>
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
          /*------ LISTADO DE REQUERIMIENTOS CON TEMPORALIDAD DISTRIBUIDA ------*/
        $(function () {
          $(".update_eval").on("click", function (e) {
              prod_id = $(this).attr('name');
              cpoa_id = $(this).attr('id');
              document.getElementById("load_insumo").style.display = 'block';
              document.getElementById("btn_insumos").style.display = 'none';
              var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/get_insumos";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "prod_id="+prod_id+"&cpoa_id="+cpoa_id
              });

              request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                document.getElementById("load_insumo").style.display = 'none';
                  $('#lista').fadeIn(1000).html(response.lista);
              }
              else{
                  alertify.error("ERROR AL RECUPERAR DATOS");
              }

              });
              request.fail(function (jqXHR, textStatus, thrown) {
                  console.log("ERROR: " + textStatus);
              });
              request.always(function () {
              });
              e.preventDefault();
          });
        });


        //// Subir Requerimientos a Lista de Certificados (nuevo)
        function add_item_cpoa(ins_id,cpoa_id,nro,estaChequeado) {
            //alert(ins_id+'---'+nro+'----'+estaChequeado)
            
            if (estaChequeado == true) { //// adicionar
                document.getElementById("tr"+nro).style.backgroundColor = "#c6f1d7";
               check=1;
            }
            else{
                document.getElementById("tr"+nro).style.backgroundColor = "";
                check=0;
            }
           // alert(ins_id+'--'+cpoa_id+'--'+check)
            $('#vista_previa').fadeIn(1000).html('<center><img src="<?php echo base_url()?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/><font color=blue>Actualizando Vista Previa ......</font></center>');
            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/adiciona_cancela_items";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "ins_id="+ins_id+"&check="+check+"&cpoa_id="+cpoa_id
              });

              request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                  $('#vista_previa').fadeIn(1000).html(response.vista_previa);
              }
              else{
                  alertify.error("ERROR AL RECUPERAR DATOS");
              }

              });
        }






        //// a modificar
/*        function seleccionarFilacompleta(ins_id,nro,estaChequeado) {
          if (estaChequeado == true) { 
            document.getElementById("tr"+nro).style.backgroundColor = "#c6f1d7";
          }
          else{
            document.getElementById("tr"+nro).style.backgroundColor = "";
          }

          valf = parseInt($('[name="tot"]').val());
          valm = parseInt($('[name="tot_temp"]').val());
          if (estaChequeado == true) {
            valf = valf + 1;
            valm = valm + 1;
          } else {
            valf = valf - 1;
            valm = valm - 1;
          }

          $('[name="tot"]').val((valf).toFixed(0));
          $('[name="tot_temp"]').val((valm).toFixed(0));
          
          totalf = parseFloat($('[name="tot"]').val());
          total = parseFloat($('[name="tot_temp"]').val());
          if(total==0 || totalf==0){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }
        }*/

        function seleccionarFila(ins_id, estaChequeado) {
          if (estaChequeado == true) {            
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='block';
            }
          } 
          else {
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='none';
            }
          }

          val = parseInt($('[name="tot"]').val());
          if (estaChequeado == true) {
            val = val + 1;
          } else {
            val = val - 1;
          }
          $('[name="tot"]').val((val).toFixed(0));
          totalf = parseFloat($('[name="tot"]').val());
          total = parseFloat($('[name="tot_temp"]').val());
          if(totalf==0 || total==0){
            $('#but').slideUp();
          }
          else{
            $('#but').slideDown();
          }
        }

        //// Subir Requerimientos a Lista de Certificados distribucion de meses (nuevo)
        function seleccionar_temporalidad(tins_id,cpoa_id,estaChequeado) {
          //alert(tins_id+'---'+cpoa_id+'---'+estaChequeado)
          if (estaChequeado == true) { 
            check=1;
          }
          else{
            check=0;
          }

            $('#vista_previa').fadeIn(1000).html('<center><img src="<?php echo base_url()?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/><font color=blue>Actualizando Vista Previa ......</font></center>');
            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/adiciona_cancela_meses_items";
          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "tins_id="+tins_id+"&check="+check+"&cpoa_id="+cpoa_id
          });

          request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              $('#vista_previa').fadeIn(1000).html(response.vista_previa);
          }
          else{
              alertify.error("ERROR AL RECUPERAR DATOS");
          }

          });




/*          if (estaChequeado == true) { 
            val = parseInt($('[name="tot_temp"]').val());
            var url = "<?php echo site_url("")?>/ejecucion/ccertificacion_poa/verif_mes_certificado";
            $.ajax({
              type:"post",
              url:url,
              data:{tins_id:tins_id},
              success:function(datos){
                if(datos.trim() =='true'){ /// habilitado para certificar

                  val = val + 1;
                  $('[name="tot_temp"]').val((val).toFixed(0));
                  total = parseFloat($('[name="tot_temp"]').val());
                  totalf = parseFloat($('[name="tot"]').val());
                  if(total==0 || totalf==0){
                    $('#but').slideUp();
                  }
                  else{
                    $('#but').slideDown();
                  }

                }else{ /// inhabilitado (ya se certifico anteriormente)
                   alertify.error("EL MES SELECCIONADO YA FUE CERTIFICADO ANTERIORMENTE !!!");
                  val = val - 1;
                  $('[name="tot_temp"]').val((val).toFixed(0));
                  total = parseFloat($('[name="tot_temp"]').val());
                  totalf = parseFloat($('[name="tot"]').val());
                  if(total==0 || totalf==0){
                    $('#but').slideUp();
                  }
                  else{
                    $('#but').slideDown();
                  }
                }
            }});
          } 
          else {
            val = val - 1;
            $('[name="tot_temp"]').val((val).toFixed(0));
            total = parseFloat($('[name="tot_temp"]').val());
            totalf = parseFloat($('[name="tot"]').val());

            if(total==0 || totalf==0){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }
          }
*/
          
          
        }
        </script>



    <script>
        function guardar(){
            if(document.form_cpoa.recomendacion.value==''){
                alertify.error("REGISTRE RECOMENDACION") 
                document.form_cpoa.recomendacion.focus() 
                return 0; 
            }

            alertify.confirm("GENERAR REPORTE DE CERTIFICACION POA ?", function (a) {
                if (a) {
                    document.form_cpoa.submit();
                   /* document.getElementById("load").style.display = 'block';
                    document.getElementById("but").style.display = 'none';*/
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
        }


    $(function () {
        $("#subir_form1").on("click", function () {
            var $validator = $("#cert_form").validate({
                rules: {
                    cite_cpoa: { //// Nota cite
                        required: true,
                    },
                    cite_fecha: { //// Fecha cite
                        required: true,
                    }
                },
                messages: {
                    cite_cpoa: "<font color=red>Registre Cite</font>", 
                    cite_fecha: "<font color=red>Seleccione Fecha</font>",                    
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
            } else {

                alertify.confirm("VALIDAR DATOS CITE ?", function (a) {
                    if (a) {
                        document.getElementById('subir_form1').disabled = true;
                        document.forms['cert_form'].submit();
                        document.getElementById("load").style.display = 'block';
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        });
    });
    </script>
    </body>
</html>
