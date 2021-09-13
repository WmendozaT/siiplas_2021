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

<page backtop="45mm" backbottom="49mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:91.8%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=14%; text-align:center;"">
                            <!-- <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:50%;"> -->
                          </td>
                          <td width=76%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:93%;" align="center">
                                <tr>
                                    <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>DIR. ADM.</b></td>
                                    <td style="width:82.5%;">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>UNI. EJEC.</b></td>
                                    <td style="width:82.5%;">: <?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
                                </tr>
                                <?php echo $titulo;?>
                                <tr style="font-size: 8pt;">
                                    <td style="width:17.5%; height: 1.2%"><b>CITE FORM. MOD. N°8</b></td>
                                    <td style="width:82.5%;">: <?php echo strtoupper($cite[0]['cite_nota']);?> || <b>FECHA : </b><?php echo date('d/m/Y',strtotime($cite[0]['cite_fecha']));?></td>
                                </tr>
                            </table>
                          </td>
                          <td style="width:19%; font-size: 8.5px;" align="left">
                            <b style="font-size: 11px;">CÓDIGO N°: <?php if($cite[0]['cite_estado']==0){echo " ---------";}else{echo $cite[0]['cite_codigo'];} ?></b><br>
                            <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center" style="font-size: 15pt;"><b>MODIFICACI&Oacute;N DE REQUERIMIENTOS <?php echo $this->session->userdata('gestion')?></b></div>
    </page_header>
    <page_footer>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
            <tr>
                <td style="width: 2%;"></td>
                <td style="width: 80%;">
                    <b>OBERVACI&Oacute;N</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#dcefec">
                            <td style="width: 100%; height:15px;">
                                <?php echo strtoupper($cite[0]['cite_observacion']);?>
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
                            <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                        </tr>
                       
                        <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                            <td><br><br><br>
                                <table>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>RESPONSABLE : </b></td>
                                        <td><?php echo $cite[0]['fun_nombre'].' '.$cite[0]['fun_paterno'].' '.$cite[0]['fun_materno'];?></td>
                                    </tr>
                                    <tr style="font-size: 8px;font-family: Arial; height:65px;">
                                        <td><b>CARGO : </b></td>
                                        <td><?php echo $cite[0]['fun_cargo'];?></td>
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
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$cite[0]['fun_nombre'].' '.$cite[0]['fun_paterno']; ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="2"><br></td>
            </tr>
        </table>
    </page_footer>
    <?php echo $requerimientos; ?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Modificacion_requerimientos.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
