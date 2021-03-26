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

    .circulo, .ovalo {
    border: 2px solid #888888;
    margin: 2%;
    height: 42px;
    border-radius: 11px;
  }
  .circulo {
    width: 100px;      
  }
  .ovalo {
    width: 150px;
  }

  .circulo1, .ovalo {
    border: 2px solid #000;
    margin: 2%;
    height: 45px;
    border-radius: 11px;
    background:#f5c9c8;
    font-size: 8px;
  }
  .circulo2, .ovalo {
    border: 2px solid #000;
    margin: 2%;
    height: 45px;
    border-radius: 11px;
    background:#ece396;
    font-size: 8px;
  }
  .circulo3, .ovalo {
    border: 2px solid #000;
    margin: 2%;
    height: 45px;
    border-radius: 11px;
    background:#b4dff3;
    font-size: 8px;
  }
  .circulo4, .ovalo {
    border: 2px solid #000;
    margin: 2%;
    height: 45px;
    border-radius: 11px;
    background:#a5efa8;
    font-size: 8px;
  }
</style>

<page backtop="48mm" backbottom="40mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=18%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:30%;">
                          </td>
                          <td width=64%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr>
                                    <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                </tr>
                                <tr>
                                    <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">REGIONAL - <?php echo strtoupper($dep[0]['dep_departamento']); ?></td>
                                </tr>
                            </table>
                          </td>
                          <td width=18%; align=left style="font-size: 8px;">
                            <div class="circulo" style="width:100%;"><br>
                            &nbsp; <b>RESP. </b><?php echo $this->session->userdata('funcionario');?><br>
                            &nbsp; <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            &nbsp; <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                            </div>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center" style="font-size: 11pt;"><b>EVALUACI&Oacute;N DE OPERACIONES ACUMULADA POR CATEGORIA PROGRAM&Aacute;TICA - <?php echo $this->session->userdata('gestion')?></b></div><br>
    </page_header>
    <page_footer>
      <hr>
        <table border="0" style="width:99%;">
            <tr>
                <td style="font-size: 7px;"><br><?php echo $this->session->userdata('sistema')?><br><br></td>
            </tr>
        </table>
    </page_footer>
   <?php echo $evaluacion; ?>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Evaluacion_operaciones_regional.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
