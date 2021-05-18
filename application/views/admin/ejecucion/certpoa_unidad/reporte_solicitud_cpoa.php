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

<?php
    if($verif_certificacion){ ?>
    <page backtop="115mm" backbottom="50mm" backleft="2.6mm" backright="2.6mm" pagegroup="new">
        <page_header>
        <br><div class="verde"></div>
            <?php echo $cabecera_cpoa; ?>
        </page_header>

        <page_footer>
            <?php echo $pie_certpoa;?>
        </page_footer>
        <?php echo $items_certificados;?>
    </page>
    <?php
    }

    if($verif_solicitud){ ?>
         <page backtop="142mm" backbottom="60mm" backleft="2.6mm" backright="2.6mm" pagegroup="new">
            <page_header>
            <br><div class="verde"></div>
                <?php echo $cabecera; ?>
                <br>
                <?php echo $datos_unidad_articulacion;?>
            </page_header>

            <page_footer>
                <?php echo $conformidad;?>
                <hr>
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 7px;font-family: Arial;">
                        <td style="width: 50%; text-align: left;" >
                            &nbsp;&nbsp;<b><?php echo $this->session->userdata('sistema')?></b>
                        </td>
                        <td style="width: 50%; text-align: right">
                           pag. [[page_cu]]/[[page_nb]]
                        </td>
                    </tr>
                </table>
                <br>
            </page_footer>
                <?php echo $items;?>
                <br>
                <div style="font-size: 20px;font-family: Arial; color: red; text-align: center;"  ><b>DOCUMENTO NO VALIDO PARA PROCESOS ADMINISTRATIVOS !!!</b></div>
        </page>
        <?php
    }

$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output($pie_reporte.'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
