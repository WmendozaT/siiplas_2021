<?php

ob_start();
?>
<style type="text/css">
<!--
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
-->

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


<page backtop="50mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.3%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=19%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:40%;">
                          </td>
                          <td width=60%; align=center>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">OBJETIVOS DE GESTI&Oacute;N <?php echo $this->session->userdata('gestion');?></td>
                              </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr style="font-size: 8pt;">
                                    <td style="width:25%; height:17px;" align="left"><b>OBJETIVO ESTRATEGICO</b></td>
                                    <td style="width:75%;" align="left">: <?php echo $obj_estrategico[0]['obj_codigo'].'.- '.$obj_estrategico[0]['obj_descripcion'];?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:25%; height:17px;" align="left"><b>ACCI&Oacute;N ESTRATEGICO</b></td>
                                    <td style="width:75%;" align="left">: <?php echo $accion_estrategica[0]['acc_codigo'].'.- '.$accion_estrategica[0]['acc_descripcion'];?></td>
                                </tr>
                            </table>
                          </td>
                          <td width=19%; align=left style="font-size: 8px;">
                            &nbsp; <b style="font-size: 9pt;">FORMULARIO N° XX </b><br>
                            &nbsp; <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            &nbsp; <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        
    </page_header>
    <page_footer>
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>ELABORADO POR<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>RESPONSABLE</b></td>
                        <td><?php echo $this->session->userdata('funcionario'); ?></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO</b></td>
                        <td><?php echo $this->session->userdata('cargo'); ?></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>REVISADO POR<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>NOMBRE :</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO : </b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>APROBADO POR<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>NOMBRE :</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO : </b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3"><br></td>
        </tr>
        <tr style="font-size: 7px;font-family: Arial;">
            <td style="text-align: left" colspan="2">
                <?php echo $this->session->userdata('sistema')?>
            </td>
            <td style="width: 20%; text-align: right">
                pag. [[page_cu]]/[[page_nb]]
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br><br></td>
        </tr>
    </table>
    </page_footer>
    <?php echo $ogestion;?>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Foda.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
