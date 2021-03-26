<?php

ob_start();
?>
<style type="text/css">
<!--
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 0mm }
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


<page backtop="42mm" backbottom="33mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=18%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:45%;">
                          </td>
                          <td width=64%; align=center>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 17pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr style="font-size: 8pt;">
                                    <td style="width:20%;" align="left"><b>DIR. ADM.</b></td>
                                    <td style="width:70%;" align="left">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:20%;" align="left"><b>UNI. EJEC.</b></td>
                                    <td style="width:70%;" align="left">: <?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:20%;" align="left"><b><?php echo $proyecto[0]['tipo_adm'];?></b></td>
                                    <td style="width:70%;" align="left">: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' - '.$proyecto[0]['abrev'];?></td>
                                </tr>
                            </table>
                          </td>
                          <td width=18%; align=left style="font-size: 8px;">
                            &nbsp; <b style="font-size: 7.5pt;">FORMULARIO POA N° 3</b>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center">ANALISIS DE PROBLEMAS Y SUS CAUSAS</div><br>
    </page_header>
    <page_footer>
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
            <td style="width: 50%;">
                <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial;">
                        <td style="width:100%;height:13px;"><b>ELABORADO POR<br></b></td>
                    </tr>
                   
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td><b><br><br><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial;">
                        <td style="width:100%;height:13px;"><b>APROBADO POR<br></b></td>
                    </tr>
                   
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td><b><br><br><br><br>FIRMA</b></td>
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
                pag. [[page_cu]]/[[page_nb]]
            </td>
        </tr>
        <tr>
            <td colspan="2"><br><br></td>
        </tr>
    </table>
    </page_footer>
    <?php echo $foda;?>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('FORM 3-'.$proyecto[0]['dep_sigla'].'-'.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
