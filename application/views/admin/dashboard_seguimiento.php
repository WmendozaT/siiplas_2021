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
        </script>
      <style>
        #mdialTamanio{
          width: 80% !important;
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
      </style>
  </head>

  <body>
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
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" title="CAMBIAR GESTI&Oacute;N">Gesti&oacute;n</a></li>
            <?php
              if($this->session->userdata('tp_adm')==1){ ?>
                <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" title="CAMBIAR GESTI&Oacute;N">Gesti&oacute;n</a></li>
                <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_tr" title="CAMBIAR TRIMESTRE">Trimestre</a></li>
                <?php
              }
            ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Descarga de Archivos / Documentos">Descargas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>assets/video/configurar_csv.mp4" style="cursor: pointer;" download>Configurar equipo a .CSV</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Archivos/Tutoriales</li>
                <li><a href="<?php echo base_url(); ?>assets/video/SEGUIMIENTO_POA.pdf" style="cursor: pointer;" download>Manual Notificacion POA</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/SEGUIMIENTO_POA_2021_ES.pdf" style="cursor: pointer;" download>Seguimiento POA</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/plantilla_migracion_poa.xlsx" style="cursor: pointer;" download>Plantilla de Migracion POa 2022</a></li>
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
          <h2><b>BIENVENIDO : <?php echo $resp; ?></b></h2>
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
      <div class="row" >
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

        <div class="modal fade" id="modal_ope_mes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
        /*------ Evaluacion de Operaciones ------*/
        $(function () {
            var prod_id = ''; var proy_id = '';
            $(".ope_mes").on("click", function (e) {
                dist_id = $(this).attr('id');
                $('#operaciones').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando lista de Operaciones a ejecutar este mes ...</div>');
                var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_operaciones_mes";
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