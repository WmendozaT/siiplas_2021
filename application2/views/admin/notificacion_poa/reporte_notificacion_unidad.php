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


<page backtop="50" backbottom="60.5mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:91.8%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=14%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:60%;">
                          </td>
                          <td width=76%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:93%;" align="center">
                                <?php echo $titulo;?>
                            </table>
                          </td>
                          <td style="width:19%; font-size: 8.5px;" align="left">
                            <b style="font-size: 11.5px;"></b><br>
                            <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center" style="font-size: 15pt;"><b>NOTIFICACI&Oacute;N PARA SOLICITUD DE CERTIFICACI&Oacute;N POA<br><?php echo $datos_mes[2].' - '.$datos_mes[3];?></b></div>
    </page_header>
    <page_footer>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
            <tr>
                <td style="width: 3%;"></td>
                <td style="width: 65%;">
                    <b>ACLARACI&Oacute;N</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#e2f5f9">
                            <td style="width: 100%;height:15px">
                                Se recomienda revisar el detalle de los items registrados en el POA <?php echo $this->session->userdata('gestion')?>, antes de solicitar la Certificaci&oacute;n POA
                            </td>
                        </tr>
                    </table><br>
                    <b>IMPORTANTE</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#e2f5f9">
                            <td style="width: 100%;height:15px">
                                En caso de contar con m&aacute;s de 3 meses de retraso, se recomendara por el area pertinente que se realice una llamada de atenci&oacute;n al responsable de la unidad ejecutora 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 50%;">
                    <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="width:100%;height:13px;"><b>ELABORADO Y APROBADO POR<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><br><br><br>
                                <table>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>RESPONSABLE : </b></td>
                                        <td></td>
                                    </tr>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>CARGO : </b></td>
                                        <td></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="width:100%;height:13px;"><b>FIRMA / SELLO DE RECEPCION DE LA UNIDAD SOLICITANTE (FECHA)<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                            <td><b><br><br><br><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br></td>
            </tr>
            <tr style="font-size: 7px;font-family: Arial;">
                <td style="text-align: left" >
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 20%; text-align: right">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y"); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="2"><br><br><br></td>
            </tr>
        </table>
    </page_footer>
    <?php echo $monto_pendiente.'<br>'.$requerimientos;?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Notificación '.$titulo_pie.'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
