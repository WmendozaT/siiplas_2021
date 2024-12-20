<?php
ob_start();

echo $certificado;
?>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output(''.$pie_reporte.'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}


