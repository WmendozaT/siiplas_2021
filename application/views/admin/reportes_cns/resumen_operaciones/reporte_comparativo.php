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
<page backtop="45mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=20%; text-align:center;>
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:40%;">
                          </td>
                          <td width=80%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr>
                                    <td style="width:100%; height: 1.2%; font-size: 16pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                </tr>
                                <tr>
                                <td style="width:100%; height: 1.2%; font-size: 9pt;"><?php echo $titulo_reporte;?></td>
                                </tr>
                            </table>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table>
        <br><div align="center"><?php echo $titulo;?></div><br>
    </page_header>
    
    <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 50%; text-align: center">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 50%; text-align: right">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="2"><br><br></td>
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
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Ptto_Comparativo_'.$titulo_reporte_pie.'_'.$this->session->userdata('gestion').'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
