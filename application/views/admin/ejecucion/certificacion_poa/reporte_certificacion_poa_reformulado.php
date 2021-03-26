<?php

ob_start();
$msg = "REF-C&Oacute;DIGO:".$datos[1]."-RESP.:".$datos[11]."-NRO.ITEMS:".$nro."";
?>
<style type="text/css">
table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 4mm }
table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 3mm}
</style>
<style>
        .verde{ width:100%; height:5px; background-color:#1c7368;}
        .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
        .siipp{width:120px;}


        .tabla {
        font-size: 7px;
        width: 100%;

        }
        .tabla th {
        padding: 2px;
        font-size: 7px;
        background-color: #1c7368;
        background-repeat: repeat-x;
        color: #FFFFFF;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #558FA6;
        border-bottom-color: #558FA6;
        text-transform: uppercase;
        }
        .tabla .modo1 {
        font-size: 7px;
        font-weight:bold;
       
        background-repeat: repeat-x;
        color: #34484E;
        }
        .tabla .modo1 td {
        padding: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-right-style: solid;
        border-bottom-style: solid;
        border-right-color: #A4C4D0;
        border-bottom-color: #A4C4D0;
        }
        p.oblique {
            font-style: oblique;
        }
    </style>

<page backtop="76mm" backbottom="49mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center;">
                          <td width=18%; text-align:center;">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:40%;">
                          </td>
                          <td width=64%;>
                            <table align="center" border="0">
                                <tr style="font-size: 19px;font-family: Arial;">
                                    <td><b>CERTIFICACI&Oacute;N DEL PLAN OPERATIVO ANUAL - <?php echo $this->session->userdata('gestion')?></b></td>
                                </tr>
                                <tr style="font-size: 15px;font-family: Arial;">
                                    <td>DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N</td>
                                </tr>
                            </table>
                          </td>
                          <td width=18%;>
                                <table border="0">
                                    <tr style="font-size: 9px;font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>CITE.</b></td>
                                        <td style="width:68%;" align="left">: <?php echo $cert_anulado[0]['cite'];?></td>
                                    </tr>
                                    <tr style="font-size: 9px;font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>FECHA CITE</b></td>
                                        <td style="width:68%;" align="left">: <?php echo date('d-m-Y',strtotime($cert_anulado[0]['cpoa_fecha']));?></td>
                                    </tr>
                                    <tr style="font-size: 9px;font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>COD.</b></td>
                                        <td style="width:68%;" align="left">: <b><?php echo $datos[1];?></b></td>
                                    </tr>
                                    <tr style="font-size: 9px;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>FECHA</b></td>
                                        <td style="width:68%;" align="left">: <?php echo date('d-m-Y',strtotime($datos[2]));?></td>
                                    </tr>
                                </table>
                            <!-- <qrcode value="<?php echo $msg; ?>" ec="L" style="width: 15mm;"></qrcode> -->
                          </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div style="font-size: 10px; font-family: Arial;">
                                <br>El presente documento certifica que el Item descrito se encuentra registrado en la programaci&oacute;n F&iacute;sico Financiero se relaciona y responde a las acciones de corto plazo y operaciones establecidas en el Plan Operativo Anual (POA) de la Caja Nacional de Salud.
                                </div>
                            </td>
                        </tr>
                  </table>
                  <div style="border: 1px groove #000;">
                    <table border="0" style="width:99.9%;">
                        <tr style="font-size: 9px;font-family: Arial;" align="center" >
                            <td style="width:16%;"></td>
                            <td bgcolor="#dcefec"><b>La presente CERTIFICACI&Oacute;N deber&aacute; ser utilizada para inicio de procesos de compra de bienes y/o contrataci&oacute;n de servicios a ser concretados a partir de la fecha de su emisi&oacute;n.</b></td>
                        </tr>
                    </table>
                  </div>
                  <hr>
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black;">
                            <td style="width:60%;">
                                <table border="0" style="width:100%;">
                                    <tr style="font-size: 8.5px;font-family: Arial; height: : 30;">
                                        <td style="width:32%;"><b>PROGRAMA</b></td>
                                        <td style="width:68%;">: <?php echo $datos[3];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:32%;"><b>OBJETIVO ESTRATEGICO</b></td>
                                        <td style="width:68%;">: <?php echo $datos[4];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:32%;"><b>ACCI&Oacute;N DE CORTO PLAZO</b></td>
                                        <td style="width:68%;">: <?php echo $datos[5];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:32%;"><b>OPERACI&Oacute;N</b></td>
                                        <td style="width:68%;">: <?php echo $datos[10];?></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:40%;">
                                <table border="0" style="width:100%;">
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>DIR. ADM.</b></td>
                                        <td style="width:70%;">: <?php echo strtoupper($datos[6]);?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>UNI. EJEC.</b></td>
                                        <td style="width:70%;">: <?php echo strtoupper($datos[7]);?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b><?php if($datos[0]==1){echo "PROYECTO DE INVERSI&Oacute;N";}else{echo "ACTIVIDAD";} ?></b></td>
                                        <td style="width:70%;">: <?php echo $datos[8];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b><?php if($datos[0]==1){echo "COMPONENTE";}else{echo "SUB-ACTIVIDAD";} ?></b></td>
                                        <td style="width:70%;">: <?php echo $datos[9];?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table>
        <div align="left" style="font-size: 11px;font-family: Arial;"><b>&nbsp;&nbsp;&nbsp;&nbsp;Descripci&oacute;n de lo solicitado :</b></div>
        
    </page_header>

    <page_footer>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:97%;">
            <tr>
                <td style="width: 2%;"></td>
                <td style="width: 40%;">
                    <b>RECOMENDACIONES</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#dcefec">
                            <td style="width: 100%;">
                                <br><?php echo $datos[13];?><br><br>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 18%;">
                    <b>NRO CITE</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#dcefec">
                            <td style="width: 100%;">
                                <br><?php echo $cert_anulado[0]['cite'];?><br><br>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%;">
                    <b>JUSTIFICACI&Oacute;N</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#dcefec">
                            <td style="width: 100%;">
                                <br><?php echo $cert_anulado[0]['justificacion'];?><br><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 40%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial; height:65px;">
                            <td style="width:100%;" colspan="2"><b>EMITIDO POR<br></b></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;">
                            <td><b>RESPONSABLE</b></td>
                            <td><?php echo $datos[11];?></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;">
                            <td><b>CARGO</b></td>
                            <td><?php echo $datos[12];?></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr style="font-size: 10px;font-family: Arial; height:65px;">
                            <td style="width:100%;" colspan="2"><b>APROBADO POR<br></b></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;">
                            <td><b>NOMBRE :</b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;">
                            <td><b>CARGO : </b></td>
                            <td></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%;" align="center">
                    <qrcode value="<?php echo $msg; ?>" style="border: none; width: 18mm;"></qrcode>
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
                   <?php echo $this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="3"><br><br><br></td>
            </tr>
        </table>
    </page_footer>
    <?php echo $items;?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Certificacion_poa_Reformulado.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
