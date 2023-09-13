<?php
ob_start();
?>
<style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
    .verde{ width:100%; height:5px; background-color:#1c7368;}
    .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
</style>

<page backtop="35mm" backbottom="20mm" backleft="5mm" backright="5mm" pagegroup="new">
    <?php echo $cabecera; ?>
    <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 50%; text-align: left; font-size:9px;">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 50%; text-align: right; font-size:9px;">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="2"><br></td>
            </tr>
        </table>
    </page_footer>

    <?php echo $aling_prog.'<br>'.$aling_og;?>

</page>
<?php
$content = ob_get_clean();
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Alineacion_poa_'.$departamento[0]['dep_departamento'].'_'.$this->session->userdata('gestion').'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
