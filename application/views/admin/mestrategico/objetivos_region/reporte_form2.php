<style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
    .verde{ width:100%; height:5px; background-color:#1c7368;}
    .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
    .siipp{width:120px;}
    .tabla {
        font-size: 7px;
        width: 100%;
    }
}
</style>


<page backtop="50mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <?php echo $cabecera;?>
    </page_header>
    <page_footer>
        <?php echo $pie;?>
    </page_footer>
    <?php echo $oregional;?>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Formulario_N2.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
