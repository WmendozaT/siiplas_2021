$(document).ready(function(){
	
	$(document).on('click', '#previewHtml', function(){
		previewHTMLFile();
	});
	
	$(document).on('click', '#convertHtmlToPDF', function(){
		converHTMLToPDF();
	});
	
});

function previewHTMLFile() {	
	var filePath = $('#fileUpload').get(0).files[0];	
	var fileContents;
	$("#error-message").html("");
	$("#fileUpload").css("border", "#a6a6a6 1px solid");
	if ($(filePath).length != 0) {
		var reader = new FileReader();
		reader.onload = function(e) {			
			fileContents = e.target.result;			
			$(".preview").show();
			$("#previewHtmlContent").html(fileContents);			
			$("#previewHtml").addClass('hidden');
			$("#convertHtmlToPDF").removeClass('hidden');
			$(".preview").removeClass('hidden');
			$(".preview").addClass("borde");
			
		}
		reader.readAsText(filePath);
	} else {
		$("#error-message").html("required.").show();
		$("#fileUpload").css("border", "#d96557 1px solid");
	}
}

function converHTMLToPDF() {
	const { jsPDF } = window.jspdf;
	var pdf = new jsPDF('l', 'mm', [1200, 1210]);
	var pdfjs = document.querySelector('#previewHtmlContent');		
	pdf.html(pdfjs, {
		callback: function(pdf) {
			pdf.save("output.pdf");
		},
		x: 10,
		y: 10
	});
}