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
            <li><a href="#about">About</a></li>
            <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" title="CAMBIAR GESTI&Oacute;N">Gesti&oacute;n</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Descarga de Archivos / Documentos">Descargas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>assets/video/crear_responsables.mp4" style="cursor: pointer;" download>Crear Responsables</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/configurar_csv.mp4" style="cursor: pointer;" download>Configurar equipo a .CSV</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Programaci&oacute;n POA</li>
                <li><a href="<?php echo base_url(); ?>assets/video/PLANTILLA-OPERACIONES.xlsx" style="cursor: pointer;" download>Plantila de Migracion - Operaciones</a></li>
                <li><a href="<?php echo base_url(); ?>assets/video/PLANTILLA-PLANTILLA_REQUERIMIENTOS.xlsx" style="cursor: pointer;" download>Plantila de Migracion - Requerimientos</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Default</a></li>
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
				<h3>BIENVENIDO : <?php echo $resp; ?></h3>
				<h4><?php echo $res_dep; ?></h4>
				<h4><b>CARGO : </b><?php echo $this->session->userdata("cargo");?></h4>
				<h4><b>GESTI&Oacute;N ACTUAL : </b><?php echo $this->session->userdata("gestion");?></h4>
				<h4><b>TRIMESTRE VIGENTE : </b><?php echo $tmes[0]['trm_descripcion'];?></h4>
			</div>
			<div class="col-md-4" align="center">
				<img src="<?php echo base_url('assets/img_v1.1/moni.png');?>" style="width:85%;">
			</div>
		</div>
    	<div id="content" class="col-lg-12">
    	</div>
    </div>
      	
    <section id="widget-grid" class="well">
			<!-- row -->
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
      document.getElementById("myBtn").addEventListener("click", function(){
      $("#content").html("<center><img id='load' src='<?php echo base_url() ?>/assets/img/loading.gif' width='40' height='40'><br>CARGANDO ....</center>");
      });
      document.getElementById("myBtn2").addEventListener("click", function(){
      $("#content").html("<center><img id='load' src='<?php echo base_url() ?>/assets/img/loading.gif' width='40' height='40'><br>CARGANDO ....</center>");
      });
      document.getElementById("myBtn3").addEventListener("click", function(){
      $("#content").html("<center><img id='load' src='<?php echo base_url() ?>/assets/img/loading.gif' width='40' height='40'><br>CARGANDO ....</center>");
      });
      document.getElementById("myBtn6").addEventListener("click", function(){
      $("#content").html("<center><img id='load' src='<?php echo base_url() ?>/assets/img/loading.gif' width='40' height='40'><br>CARGANDO ....</center>");
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
        });
        </script>

</body>
</html>