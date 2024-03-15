<?php
ob_start();
?>
<style type="text/css">
    .verde{ width:100%; height:5px; background-color:#1c7368;}
    table.page_header {width: 100%; border: none; border-bottom: solid 0mm; padding: 1mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 1mm}
</style>


<?php echo $cuerpo; ?>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Caratula_POA_'.strtoupper($regional[0]['dep_departamento']).'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
