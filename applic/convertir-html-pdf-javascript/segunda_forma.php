<?php 
include('inc/header.php');
?>
<title>Baulphp.com : Convertir HTML a PDF usando Javascript</title>
<?php include('inc/contenedor.php');?>
  <div class="container">
    <h3>Demo: Convertir HTML a PDF con Javascript</h3>
    <hr>
  <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Ver demostración</h3>
        </div>
        <div class="panel-body">
        <div class="row">
    <div class="col-lg-12">
            <div>
            	<button class="btn btn-primary" onclick="generatePDF();">Click a generar PDF</button>
				<button class="btn btn-primary" onclick="convert_HTML_To_PDF();">Convertir HTML a PDF</button>
				
				<!-- HTML content for PDF creation -->
				<div id="content">
					<h1>What is Lorem Ipsum?</h1>
					<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
				</div>
				<div id="elementH"></div>
            </div>
        </div>

        </div>
        
  </div>
  </div>
  
  </div>
  <script src="js/jspdf.min.js"></script> 
<script>
/*
 * Generate 2 pages PDF document
 */
function generatePDF() {
	var doc = new jsPDF();
	

	doc.text(20, 20, 'Hello world!');
	doc.text(20, 30, 'This is client-side Javascript to generate a PDF.');
	
	doc.text(20, 40, 'This is the default font.');
	
	doc.setFont("courier");
	doc.setFontType("normal");
	doc.text(20, 50, 'This is courier normal.');
	
	doc.setFont("times");
	doc.setFontType("italic");
	doc.text(20, 60, 'This is times italic.');
	
	doc.setFont("helvetica");
	doc.setFontType("bold");
	doc.text(20, 70, 'This is helvetica bold.');
	
	doc.setFont("courier");
	doc.setFontType("bolditalic");
	doc.text(20, 80, 'This is courier bolditalic.');
	
	doc.addPage();
	
	doc.setFontSize(24);
	doc.text(20, 20, 'This is a title');
	
	doc.setFontSize(16);
	doc.text(20, 30, 'This is some normal sized text underneath.');
	
	doc.setTextColor(100);
	doc.text(20, 40, 'This is gray.');
	
	doc.setTextColor(150);
	doc.text(20, 50, 'This is light gray.');
	
	doc.setTextColor(255,0,0);
	doc.text(20, 60, 'This is red.');
	
	doc.setTextColor(0,255,0);
	doc.text(20, 70, 'This is green.');
	
	doc.setTextColor(0,0,255);
	doc.text(20, 80, 'This is blue.');
	
	// Save the PDF
	doc.save('documento_generado.pdf');
}

/*
 * Convert HTML content to PDF
 */
function convert_HTML_To_PDF() {
	var doc = new jsPDF();
	var elementHTML = $('#content').html();
	var specialElementHandlers = {
		'#elementH': function (element, renderer) {
			return true;
		}
	};
	doc.fromHTML(elementHTML, 15, 15, {
        'width': 170,
        'elementHandlers': specialElementHandlers
    });
	
	// Save the PDF
	doc.save('conversion-html-a-pdf.pdf');
}
</script>
  <?php include('inc/footer.php');?>