<?php
ob_start();
?>
    <style type="text/css">
        table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 0mm }
        table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}

        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}

        .tabla {
        font-size: 7px;
        width: 100%;
        }
    </style>

<page backtop="45mm" backbottom="20mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0" style="width:100%;">
            <tr>
                <td style="width: 100%; text-align: left">

                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td style="width:15%; text-align:center;">
                           <!--  <img src="<?php echo $this->session->userdata('img') ?>" alt="" style="width:40%;"> -->
                          </td>
                          <td style="width:65%;" align=left>
                            <?php echo $cabecera;?>
                          </td>
                          <td style="width:15%;font-size: 8px;" align=left>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center"><b>SEGUIMIENTO A LA EVALUACI&Oacute;N POA</b> - <?php echo "GESTI&Oacute;N " .$this->session->userdata('gestion')?><br>(Reporte ayuda Memoria, no imprimir para Evaluación POA)
        </div><br>
    </page_header>
    <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33%; text-align: left">
                    <?php echo "POA - ".$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa'); ?>
                </td>
                <td style="width: 33%; text-align: center">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 33%; text-align: right">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="3"><br><br></td>
            </tr>
        </table>
    </page_footer>
    <?php echo $operaciones;?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('MIS OPERACIONES -'.$proyecto[0]['dep_sigla'].'-'.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].'-'.$componente[0]['com_componente'].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
