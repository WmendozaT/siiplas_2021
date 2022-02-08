<?php
ob_start();
?>
<style type="text/css">
<!--
    table.page_header {width: 100%; border: none; border-bottom: solid 0mm; padding: 1mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 1mm}
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


<page backtop="20mm" backbottom="19mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.3%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=15%; text-align:center;"">
                          </td>
                          <td width=70%; align=center>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; font-size: 25pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                              <tr>
                                <td style="width:100%;  font-size: 12pt;" align="center">DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                              </tr>
                            </table>
                          </td>
                          <td width=15%; align=left style="font-size: 8px;">
                          </td>
                        </tr>
                  </table>
                  <!-- strtoupper($row['dep_departamento']). -->
                </td>
            </tr>
        </table>

    </page_header>
        <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 50%; text-align: left">
                    POA - <?php echo $this->session->userdata('gestion').' '.$this->session->userdata('rd_poa')?>
                </td>
                <td style="width: 50%; text-align: center">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><br><br><br><br></td>
            </tr>
        </table>
    </page_footer>
    
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
      <tr>
        <td align="center" style="height: 45%;">
          <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:31%;">
        </td>
      </tr>
      <tr>
        <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
              <tr>
                <td style="width:100%; height: 4%; font-size: 43pt;" align="center"><b>PLAN OPERATIVO ANUAL</b></td>
              </tr>
              <tr>
                <td style="width:100%; height: 4%; font-size: 35pt;" align="center"><b>GESTI&Oacute;N <?php echo $this->session->userdata('gestion');?></b></td>
              </tr>
            </table>
        </td>
      </tr>
    </table><br><br><br><br><br>

    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:90%;" align="center">
      <tr>
        <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <tr>
                    <td style="width:100%; height: 5.5%; font-size: 30pt;" align="center">REGIONAL <?php echo strtoupper($regional[0]['dep_departamento']);?></td>
                </tr>
                <tr>
                    <td style="width:100%; height: 1.2%; font-size: 40pt;" align="center">TOMO I de I</td>
                </tr>
            </table>
            <hr>
        </td>
      </tr>
    </table>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Caratula_principal_'.strtoupper($regional[0]['dep_departamento']).'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
