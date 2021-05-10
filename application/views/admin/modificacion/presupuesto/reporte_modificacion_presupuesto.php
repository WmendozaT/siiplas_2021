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
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                            <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td width=15%; text-align:center;>
                                <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:50%;">
                              </td>
                              <td width=65%; align=left>
                                <table>
                                    <tr>
                                        <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>DIR. ADM.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($cite[0]['dep_departamento']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>UNI. EJEC.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($cite[0]['dist_distrital']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>No. y FECHA DISPOSICI&Oacute;N</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($cite[0]['resolucion']);?></td>
                                    </tr>
                                </table>
                              </td>
                              <td width=20%; align=left style="font-size: 7.5px;">
                              </td>
                            </tr>
                        </table>
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
