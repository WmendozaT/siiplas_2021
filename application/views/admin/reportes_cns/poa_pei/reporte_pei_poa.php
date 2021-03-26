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


<page backtop="50mm" backbottom="18mm" backleft="8mm" backright="8mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=19%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:27%;">
                          </td>
                          <td width=60%; align=left>
                           <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                              <tr>
                                <td style="font-size: 15pt;"align="center"><?php echo $titulo;?></td>
                              </tr>
                            </table>
                          </td>
                          <td width=19%; align=left style="font-size: 8px;">
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
            <tr>
                <td style="width:10%;" style="height: 1.5%;" align="left"><b>MISI&Oacute;N:</b></td><td style="width:90%;"><?php echo $conf[0]['conf_mision'];?></td>
            </tr>
            <tr>
                <td style="height: 1.5%;" align="left"><b>VISI&Oacute;N:</b></td><td><?php echo $conf[0]['conf_vision'];?></td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            
            <tr>
                <td style="width: 66%; text-align: left;">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 33%; text-align: right">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y"); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="2"><br><br><br><br></td>
            </tr>
        </table>
    </page_footer>
    
   
    <?php echo $pei_poa;?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Requerimientos.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
