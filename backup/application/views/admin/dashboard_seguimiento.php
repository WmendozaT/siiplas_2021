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
          <h4><b>CARGO : </b>SEGUIMIENTO POA</h4>
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
          for ($i=0; $i < count($vector_menus); $i++) { 
            echo $vector_menus[$i]; 
          }
        ?>
      </div>
      <!-- end row -->
    </section>

    </div> <!-- /container -->

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
</body>
</html>