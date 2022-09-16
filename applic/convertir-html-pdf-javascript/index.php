<?php 
include('inc/header.php');
?>
<title>Baulphp.com : Convertir HTML a PDF usando Javascript</title>
<?php include('inc/contenedor.php');?>
  <div class="container">
    <h3>Demo: Convertir HTML a PDF con Javascript</h3>
    <hr>
    <form name="foo" method="post" class="input-form" enctype="multipart/form-data">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Subir archivo</h3>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-6">
              <div class="form-group has-success">
                <input type="file" id="fileUpload" name="file"  class="form-control" accept=".html,.htm">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group has-success">
                <div class="preview hidden">
                <p><strong>Vista previa</strong></p>
                <hr>
                  <div id="previewHtmlContent"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 left">
              <input type="button" value="Mostrar Previsualización"  class="btn btn-info" id="previewHtml">
              <span id="error-message" class="error"></span>
              <input type="button" value="Crear PDF"  class="btn btn-info hidden" id="convertHtmlToPDF">
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script src="js/jspdf.umd.min.js"></script> 
  <script src="js/html2canvas.min.js"></script> 
  <script src="js/convertir.js"></script>
  <?php include('inc/footer.php');?>