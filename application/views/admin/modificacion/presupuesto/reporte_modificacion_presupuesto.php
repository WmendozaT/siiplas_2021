<?php
ob_start();
?>
    <style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 0mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
    }

    .verde{ width:100%; height:5px; background-color:#1c7368;}
    .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
    .siipp{width:120px;}

    .tabla {
        font-size: 7px;
        width: 100%;
    }
    </style>
    <page backtop="35mm" backbottom="16mm" backleft="8mm" backright="8mm" pagegroup="new">
        <page_header>
            <br><div class="verde"></div>
            <table class="page_header" border="0">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <?php echo $cabecera;?>
                    </td>
                </tr>
            </table>

        </page_header>
        <page_footer>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: left">
                        <?php echo "POA - ".$this->session->userdata('gestion'); ?>
                    </td>
                    <td style="width: 33%; text-align: center">
                        <?php echo $this->session->userdata('sistema')?>
                    </td>
                    <td style="width: 33%; text-align: right">
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br><br></td>
                </tr>
            </table>
        </page_footer>
        <?php echo $reduccion;?><br>
        <?php echo $incremento;?> 
    </page>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output(strtoupper ($cite[0]['abrev'].' - '.$cite[0]['dist_distrital']).' MOD PPTO - RD '.strtoupper($cite[0]['resolucion']).'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
