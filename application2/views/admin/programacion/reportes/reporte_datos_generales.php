<?php
ob_start();
?>
    <style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
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
    <page backtop="45mm" backbottom="15mm" backleft="5mm" backright="8mm" pagegroup="new">
        <page_header>
          <br><div class="verde"></div>
          <table class="page_header" border="0">
              <tr>
                <td style="width: 100%; text-align: left">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                      <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                        <td width=20%; text-align:center;"">
                          <!-- <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:50%;"> -->
                        </td>
                        <td width=60%; align=left>
                          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 25pt;" align="center"><b>DATOS GENERALES</b></td>
                            </tr>
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center">PROYECTOS DE INVERSI&Oacute;N - <?php echo $this->session->userdata('gestion')?></td>
                            </tr>
                          </table>
                        </td>
                        <td width=20%; align=left style="font-size: 8px;">
                        </td>
                      </tr>
                  </table>
                </td>
              </tr>
          </table><br>
          <div align="center"></div>

        </page_header>
        <page_footer>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <?php echo $this->session->userdata('sistema')?> - <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                    </td>
                </tr>
                <tr>
                    <td><br><br></td>
                </tr>
            </table>
        </page_footer>
        
        <div style="font-size: 15px;font-family: Arial;height: 2.5%"><b>DATOS GENERALES</b></div>
        <table border="0.1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%; font-size: 11px;">
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>ENTIDAD : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;">CAJA NACIONAL DE SALUD</td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>DIR. ADMINISTRATIVA :</b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>UNIDAD EJECUTORA : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>PROGRAMA : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo $proyecto[0]['aper_programa'].' '.$proyecto[0]['aper_proyecto'].' '.$proyecto[0]['aper_actividad'];?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>PROYECTO / ACTIVIDAD : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['proy_nombre']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>C&Oacute;DIGO SISIN : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['proy_sisin']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>FECHA INICIO : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo date('d/m/Y',strtotime($proyecto[0]['f_inicial'])); ?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>FECHA FINAL : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo date('d/m/Y',strtotime($proyecto[0]['f_final'])); ?></td>
          </tr>
        </table><br>

        <div style="font-size: 15px;font-family: Arial;height: 2.5%"><b>OBJETIVOS DE PROYECTO</b></div>
        <table border="0.1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%; font-size: 11px;">
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>DESCRIPCI&Oacute;N DEL PROBLEMA : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['desc_prob']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>DESCRIPCI&Oacute;N DE LA SOLUCI&Oacute;N :</b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['desc_sol']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>OBJETIVO GENERAL : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['obj_gral']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>OBJETIVO ESPEC&Iacute;FICO : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($proyecto[0]['obj_esp']);?></td>
          </tr>
        </table><br>

        <div style="font-size: 15px;font-family: Arial;height: 2.5%"><b>FASE ACTIVA DEL PROYECTO</b></div>
        <table border="0.1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%; font-size: 11px;">
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>FASE : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($fase[0]['fase']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>ETAPA :</b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($fase[0]['etapa']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>DESCRIPCI&Oacute;N FASE : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo strtoupper($fase[0]['descripcion']);?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>FECHA INICIO : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo date('d/m/Y',strtotime($fase[0]['inicio'])); ?></td>
          </tr>
          <tr>
            <td style="width:25%; height: 2%; font-family: Arial;"><b>FECHA FINAL : </b></td>
            <td style="width:75%; height: 2%; font-family: Arial;"><?php echo date('d/m/Y',strtotime($fase[0]['final'])); ?></td>
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
    $html2pdf->Output('Datos_generales.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
