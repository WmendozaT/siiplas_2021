<?php
ob_start();
?>
<style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
}
</style>
<style>
    .verde{ width:100%; height:5px; background-color:#1c7368;}
    .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
    .siipp{width:120px;}
    .tabla {
    font-size: 7px;
    width: 100%;
    }
</style>

<?php
    if($proyecto[0]['ta_id']!=2 & $proyecto[0]['ta_id']!=5){
        echo $principal;
    }
    
    echo $cuerpo;
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Notificacion_POA_'.$verif_mes[2].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
