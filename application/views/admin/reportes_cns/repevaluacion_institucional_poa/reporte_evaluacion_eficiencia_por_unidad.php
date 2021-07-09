<?php
ob_start();
?>
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
    <page backtop="49mm" backbottom="30mm" backleft="2.6mm" backright="2.6mm" pagegroup="new">
        <page_header>
        <br><div class="verde"></div>
           <?php echo $cabecera; ?>
        </page_header>

        <page_footer>
            <?php echo $pie;?>
        </page_footer>
        <?php echo $lista;?><br>

        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.- (%) EFICACIA</b>
        </div>
        <table border="0" style="width:100%;">
            <tr>
                <td style="width:20%;"></td>
                <td align="center" style="width:60%; height: 4%; ">
                    <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="width:100%;height: 50%;font-size: 20px;">
                               <?php echo $eficacia;?>% Cumplimiento de Actividades
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%;"></td>
            </tr>
        </table>
    
        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.- (%) ECONOMIA</b>
        </div>
        <table border="0" style="width:100%;">
            <tr>
                <td style="width:20%;"></td>
                <td align="center" style="width:60%; height: 4%; ">
                    <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="width:100%;height: 50%;font-size: 20px;">
                               <?php echo $economia[3];?>% Presupuesto Ejecutado
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%;"></td>
            </tr>
        </table>

        <div style="font-size: 10px;font-family: Arial;height: 2.5%;">
          <b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.- EFICIENCIA</b>
        </div>
        <table border="0" style="width:100%;">
            <tr>
                <td style="width:20%;"></td>
                <td align="center" style="width:60%; height: 4%; ">
                    <table border="0.2" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="width:100%;height: 50%;font-size: 20px;">
                               <?php echo $eficiencia;?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%;"></td>
            </tr>
        </table>
    </page>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Evaluacion_.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
