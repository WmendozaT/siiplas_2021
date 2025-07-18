<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $this->session->userdata('name')?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/dashboard/bootstrap.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/dashboard/navbar-fixed-top.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
    <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
      <script language="javascript">
        function doSearch(nro){
          var tableReg = document.getElementById('datos'+nro);
          var searchText = document.getElementById('searchTerm'+nro).value.toLowerCase();
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
        function abreVentana(PDF){             
          var direccion;
          direccion = '' + PDF;
          window.open(direccion, "IMPRESION" , "width=800,height=700,scrollbars=NO") ; 
        }

        function abreVentana2(PDF) {
          var direccion = '' + PDF;
          var ventana = window.open(direccion, "IMPRESION", "width=800,height=700,scrollbars=NO");

          // Esperar a que la ventana se cargue completamente
            ventana.onload = function() {
              ventana.print(); // Imprimir automáticamente

              // Cerrar la ventana después de un breve retraso
              setTimeout(function() {
                  ventana.close(); // Cerrar la ventana
              }, 2000); // Ajusta el tiempo según sea necesario
          };
      }
      </script>
      <style>
        #mdialTamanio{
          width: 80% !important;
        }
        #mdialTamanio_psw{
          width: 40% !important;
        }
        #mdialTamanio_saldos{
          width: 65% !important;
        }
        table{
          font-size: 10px;
          width: 100%;
          max-width:1550px;;
          overflow-x: scroll;
        }
        th{
          padding: 1.4px;
          font-size: 10px;
        }
        td{
          font-size: 10px;
        }
        #myModal {
          background: #000000;
          opacity:0.9
        }
      </style>
  </head>

  <body >
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><font color="#1c7368"><b><?php echo $this->session->userdata('name')?></b></font></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#"><b>Home</b></a></li>
            <?php
              if($this->session->userdata('tp_adm')==1 || $this->session->userdata('rol_id')!=10){ ?>
                <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" title="CAMBIAR GESTI&Oacute;N">Gesti&oacute;n</a></li>
                <?php
              }

              if($this->session->userdata('tp_adm')==1 || $this->session->userdata('rol_id')!=10){ ?>
                <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_tr" title="CAMBIAR TRIMESTRE">Trimestre</a></li>
                <?php
              }

              if($this->session->userdata('tp_adm')==1){ ?>
                <li><a href="#" data-toggle="modal" data-target="#modal_seguimiento_nacional" title="SEGUIMIENTO POA NACIONAL" class="seg_uni"><b>Seguimiento POA NACIONAL</b></a></li>
                <?php
              }
              else{?>
                <li><a href="#" data-toggle="modal" data-target="#modal_seguimiento" id="<?php echo $dist_id; ?>" title="SEGUIMIENTO POA" class="seg_uni"><b>Seguimiento POA</b></a></li>
                <?php
              }

            ?>
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Descarga de Archivos / Documentos">Descargas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <!-- <li><a href="<?php echo base_url(); ?>assets/video/FORM_POA_N°4_ACTIVIDADES.xlsx" style="cursor: pointer;" download><b>Descargar Formulario N°4 POA <?php echo $this->session->userdata("gestion");?> (Actividades)</b></a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/FORM_POA_N°5_PROG FISICO FINANCIERA.xlsx" style="cursor: pointer;" download><b>Descargar Formulario N°5 POA <?php echo $this->session->userdata("gestion");?> (Requerimientos)</b></a></li> -->
                <li><a href="<?php echo base_url(); ?>assets/video/PLANTILLA_MIGRACION_FORM5.xlsx" style="cursor: pointer;" download><b>Descargar Plantilla para migrar Requerimientos</b></a></li>
                <li class="divider"></li>
                <li><a href="<?php echo base_url(); ?>assets/video/FORM_MOD_4_Y_5_2025.xlsx" style="cursor: pointer;" download>Descargar Formulario de Modificacion POA <?php echo $this->session->userdata("gestion");?></a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/FORM_SOL_POA_5_2025.xlsx" style="cursor: pointer;" download>Descargar Formulario de Certificacion POA <?php echo $this->session->userdata("gestion");?></a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/FORM_JUST_EDICION_CPOAS_2025.docx" style="cursor: pointer;" download>Descargar Formulario de Edicion de Cert. POA <?php echo $this->session->userdata("gestion");?></a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/FORM_JUST_SALDOS_CPOAS_2025.docx" style="cursor: pointer;" download>Descargar Formulario de Reversion de Saldos POA <?php echo $this->session->userdata("gestion");?></a></li>
                
               <!--  <li class="divider"></li>
                <li class="dropdown-header">Archivos/Tutoriales</li>
                <li><a href="<?php echo base_url(); ?>assets/video/SEGUIMIENTO_POA.pdf" style="cursor: pointer;" download>Manual Notificacion POA</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/SEGUIMIENTO_POA_2021_ES.pdf" style="cursor: pointer;" download>Seguimiento POA</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/plantilla_migracion_poa.xlsx" style="cursor: pointer;" download>Plantilla de Migracion POa 2022</a></li> -->
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="<?php echo base_url(); ?>index.php/admin/logout" title="CERRAR SESI&Oacute;N"><b>SALIR</b></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <div class="row box-green1">
        <div class="col-md-8">
          <!-- <?php echo $_SERVER["HTTP_HOST"].''.$_SERVER["REQUEST_URI"].'-----'.base_url(); ?> -->
          <h3>BIENVENIDO : <?php echo $resp; ?></h3>
          <h4><?php echo $res_dep; ?></h4>
          <h4><b>CARGO : </b><?php echo $this->session->userdata("cargo");?></h4>
          <h4><b>MES / GESTI&Oacute;N VIGENTE : </b><?php echo $mes[2].' / '.$this->session->userdata("gestion");?></h4>
          <h4><b>TRIMESTRE VIGENTE : </b><?php echo $tmes[0]['trm_descripcion'];?></h4>
        </div>
        <div class="col-md-4" align="center">
          <img src="<?php echo base_url('assets/img_v1.1/moni.png');?>" style="width:85%;">
        </div>
      </div>
      <div id="load" class="col-lg-12" id="load" style="display: none" align="center">
        <img  src="<?php echo base_url()?>/assets/img_v1.1/loading.gif" width="60" height="60" title="ESPERE UN MOMENTO, LA PAGINA SE ESTA CARGANDO.."><br><font size="1"><b>ESPERE UN MOMENTO, CARGANDO MODULO ........</b></font>
      </div>
    </div>
        
    <section id="widget-grid" class="well">
      <!-- row -->
      
      <?php echo $mensaje;?>
      <?php 
        if($this->session->userdata('estado_notificaciones')==1){
          echo $seguimiento_poa;
        }
      ?>

      <div class="row">
        <?php 
          $rol = $this->session->userdata('rol_id');
          if($rol!=''){
            for ($i=0; $i < count($vector_menus); $i++) { 
              echo $vector_menus[$i]; 
            }
          }
          else{ ?>
            <div class="alert alert-danger alert-block">
              <a class="close" data-dismiss="alert" href="#">×</a>
              <h4 class="alert-heading">Error!</h4>
              EL USUARIO RESPONSABLE NO CUENTA CON UN ROL ESPECIFICO, CONTACTESE CON EL ADMINISTRADOR
            </div>
            <?php
          } 
        ?>
      </div>
      <!-- end row -->
    </section>

    </div> <!-- /container -->

        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <form action="<?php echo site_url().'/cambiar_session'?>" id="form_nuevo" name="form_nuevo" class="form-horizontal" method="post">
                    <h3 class="alert alert-info"><center>CAMBIAR GESTI&Oacute;N - <?php echo $this->session->userdata('gestion')?></center></h3>   
                    <fieldset>
                      <div class="form-group">
                          <div class="form-group">
                            <label class="col-md-2 control-label">GESTI&Oacute;N</label>
                            <div class="col-md-10">
                                <?php echo $gestiones;?>
                            </div>
                          </div>
                      </div>
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                          <div class="col-md-12" align="right">
                            <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                            <button type="button" name="subir_form" id="subir_form" class="btn btn-info" >CAMBIAR GESTI&Oacute;N</button>
                            <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                          </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL SEGUIMIENTO POA FORM 4 -->
        <div class="modal fade" id="modal_form4_mes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
              </div>
              <div class="modal-body" align="center">
                <div id="operaciones"></div>
              </div>
            </div>
          </div>
        </div>


        <!-- MODAL SEGUIMIENTO POA FORM 5 (PROYECTOS DE INVERSION) -->
        <div class="modal fade" id="modal_form5_pi_mes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
              </div>
              <div class="modal-body" align="center">
                <div id="pinversion"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL SEGUIMIENTO A UNIDADES -->
        <div class="modal fade" id="modal_seguimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_saldos">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
              </div>
              <div class="modal-body" align="center">
                <div id="seg"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal_nuevo_tr" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-body">
                <form action="<?php echo site_url().'/cambiar_session_trimestre'?>" id="form_trimestre" name="form_trimestre" class="form-horizontal" method="post">
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

        <!--   SEGUIMIENTO A UNIDADES / ESTABLECIMIENTOS POR REGIONAL -->
        <?php echo $select_distrital; ?>

      <!-- Modal listado de Unidades para el seguimiento a Nivel Nacional -->
      <div class="modal fade" id="modal_respuesta" tabindex="-1" role="dialog" aria-labelledby="respuestaModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document" id="mdialTamanio_saldos"> <!-- Modal grande -->
              <div class="modal-content">
                  <div class="modal-body">
                      <div id="responsee"></div> <!-- Div para mostrar la respuesta -->
                  </div>
                  <div class="modal-footer">
                      <div id="botones"></div>
                  </div>
              </div>
          </div>
      </div>


    <!--  MODAL DE solicitudes     -->
    <?php echo $solicitudes_pass; ?>
    <!--  END MODAL  -->

    <!--  MODAL DE ALERTA CREDENCIALES     -->
    <?php echo $popup_credenciales; ?>
    <!--  END MODAL  -->

    <!--  MODAL DE ALERTA DE SALDOS     -->
    <?php echo $popup_saldos; ?>
    <!--  END MODAL  -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/dashboard/jquery-1.js"></script>
    <script src="<?php echo base_url(); ?>assets/dashboard/bootstrap.js"></script>

    <script>
      if (!window.jQuery) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"><\/script>');
      }
    </script>
    <script>
      if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
      }
    </script>
    <script type="text/javascript">
        function imprimir_grafico1() {
          $('#loading_saldo').html('<center><img src="<?php echo base_url() ?>/assets/img/loading.gif" alt="loading" style="width:10%;"/><br/>Un momento por favor, Cargando Información </center>');
        }
        $( document ).ready(function() {
            $('#myModal').modal('toggle')
        });

        /*------ Evaluacion de Formulario N 4 ------*/
        $(function () {
          var prod_id = ''; var proy_id = '';
          $(".form4_mes").on("click", function (e) {
              dist_id = $(this).attr('id');
              $('#operaciones').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando lista de Actividades a ejecutar este mes ...</div>');
              var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_form4_gc_mes";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "dist_id="+dist_id
              });

              request.done(function (response, textStatus, jqXHR) { 
                  if (response.respuesta == 'correcto') {
                      $('#operaciones').html(response.tabla);
                  } else {
                      alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                  }
              });
          });


          //// Proyectos de Inversion
          $(".pi_mes").on("click", function (e) {

            dist_id = $(this).attr('id');

            $('#pinversion').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando lista de Proyectos de Inversión a ejecutar este mes ...</div>');
            var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_form5_pi_mes";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "dist_id="+dist_id
            });

            request.done(function (response, textStatus, jqXHR) { 
                if (response.respuesta == 'correcto') {
                    $('#pinversion').html(response.tabla);
                } else {
                    alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                }
            });

          });


          //// Seguimiento POA a unidades por responsable poa Regional
          $(".seg_uni").on("click", function (e) {
            dist_id = $(this).attr('id');

            $('#seg').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando lista de Proyectos de Inversión a ejecutar este mes ...</div>');
            var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_unidades_seguimiento_poa_mensual";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "dist_id="+dist_id
            });

            request.done(function (response, textStatus, jqXHR) { 
                if (response.respuesta == 'correcto') {
                    $('#seg').html(response.tabla);
                } else {
                    alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                }
            });

          });
        });
      </script>

    <!-- Seguimiento POA a Unidades Nacional -->
    <script>
    $(document).ready(function() {
        $('#seg_reg').change(function() {
            var selectedValue = $(this).val(); // Obtener el valor seleccionado
            if (selectedValue !== "0") { // Verifica si se ha seleccionado una opción válida
                $.ajax({
                    url: '<?php echo site_url("")?>/ejecucion/cseguimiento/get_unidades_seguimiento_poa_mensual_nacional', // Cambia esto a la ruta de tu script PHP
                    type: 'POST',
                    data: { value: selectedValue },
                    dataType: 'json', // Esperar una respuesta en formato JSON
                    success: function(response) {
                        if (response.status === 'success') {
                            //alert(response.message)
                            $('#responsee').html(response.message);
                            $('#botones').html(response.button);
                            //$('#responsee').text(response.message).show(); // Mostrar el mensaje en el modal
                        } else {
                            $('#response').text("Error en la respuesta.").show(); // Mensaje de error
                        }
                        $('#modal_respuesta').modal('show'); // Muestra el modal
                    },
                    error: function() {
                        $('#response').text("Error al procesar la solicitud.").show(); // Mensaje de error
                        $('#modal_respuesta').modal('show'); // Muestra el modal
                    }
                });
            }
        });
    });
    </script>

    <script type="text/javascript">
      $(function () {
          $("#subir_form").on("click", function () {
            val=document.getElementById("gestion_usu").value;

            if(val!=0 & val!=''){
              if(document.getElementById("gest").value!=document.getElementById("gestion_usu").value){
                alertify.confirm("CAMBIAR GESTI&Oacute;N ?", function (a) {
                    if (a) {
                        document.getElementById("load").style.display = 'block';
                        document.getElementById('subir_form').disabled = true;
                        document.forms['form_nuevo'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
              }
              else{
                alertify.success("GESTI&Oacute;N SELECCIONADA");
              }
            }
            else{
              alertify.error("SELECCIONE GESTI&Oacute;N");
            }
              
          });

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

</body>
</html>