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



<page backtop="50mm" backbottom="50mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <?php echo $cabecera; ?>         
    </page_header>
    
    <page_footer>
    <table style="width:98%;font-size: 6px;font-family: Arial;" align="center" border="0">
        <tr>
            <td style="width:98%;"><b>COD. OE. : </b>C&oacute;digo Objetivo Estrategico Institucional</td>
        </tr>
        <tr>
            <td><b>COD. ACE. : </b>C&oacute;digo Acci&oacute;n Estrategica</td>
        </tr>
        <tr>
            <td><b>COD. ACP. : </b>C&oacute;digo Acci&oacute;n de Corto Plazo</td>
        </tr>
    </table>
    <hr>
    <table border="0.8" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
            <td style="width: 30%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial;">
                        <td style="width:100%;"><b><br>ELABORADO POR<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial;" align="center">
                        <td><b><br><br><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 70%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial;">
                        <td style="width:100%;"><b><br>APROBADO POR<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial;" align="center">
                        <td><b><br><br><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
        <tr>
            <td colspan="3"><br></td>
        </tr>
        <tr>
            <td style="width: 33%; text-align: left">
                POA - <?php echo $this->session->userdata('gestion')?>. Aprobado mediante RD. Nro. 124/2019 de 19/09/2019
            </td>
            <td style="width: 33%; text-align: center">
                <?php echo $this->session->userdata('sistema')?>
            </td>
            <td style="width: 33%; text-align: right">
                <?php echo "SEPT. / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br></td>
        </tr>
    </table>
    </page_footer>
    <?php echo $lista;?>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Formulario N° 1 POA '.$gestion.'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
