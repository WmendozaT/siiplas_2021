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


<page backtop="42.5mm" backbottom="35mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.3%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=19%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:35%;">
                          </td>
                          <td width=60%; align=center>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 17pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">PLAN OPERATIVO ANUAL - <?php echo $this->session->userdata('gestion');?></td>
                              </tr>
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 13pt;" align="center"><b>EVALUACI&Oacute;N DE OBJETIVOS REGIONALES </b></td>
                              </tr>
                            </table>
                          </td>
                          <td width=19%; align=left style="font-size: 8px;">
                                &nbsp; <b style="font-size: 7pt;">EVAL. FORMULARIO POA N° 2<br>
                                &nbsp; <?php echo $trimestre[0]['trm_descripcion']; ?></b>
                          </td>
                        </tr>
                  </table>
                  <!-- strtoupper($row['dep_departamento']). -->
                </td>
            </tr>
        </table><br>
        <div align="center"><b>
        <?php
            if($regional[0]['dep_id']!=10){
                echo 'METAS REGIONAL '.strtoupper($regional[0]['dep_departamento']).'';
            }
            else{
                echo 'METAS '.strtoupper($regional[0]['dep_departamento']).'';
            }
        ?>
        </b></div>
    </page_header>
        <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33%;">
                    <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <?php
                                if($regional[0]['dep_id']!=10){
                                    echo '<td style="width:100%;height:1.4%"><b>JEFATURA DE SERVICIOS GENERALES</b></td>';
                                }
                                else{
                                    echo '<td style="width:100%;height:1.4%"><b>GERENCIA ADMINISTRATIVA FINANCIERA</b></td>';
                                }
                            ?>
                        </tr>
                        <tr>
                            <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%;">
                    <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <?php
                            if($regional[0]['dep_id']!=10){
                                echo '<td style="width:100%;height:1.4%"><b>JEFATURA MEDICA</b></td>';
                            }
                            else{
                                echo '<td style="width:100%;height:1.4%"><b>GERENCIA DE SERVICIOS DE SALUD</b></td>';
                            }
                            ?>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%;">
                    <table border="0.7" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                        <?php
                            if($regional[0]['dep_id']!=10){
                                echo '<td style="width:100%; height:1.4%"><b>ADMINISTRADOR REGIONAL</b></td>';
                            }
                            else{
                                echo '<td style="width:100%; height:1.4%"><b>GERENCIA GENERAL</b></td>';
                            }
                        ?>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3"><br><br></td>
            </tr>
            <tr>
                <td style="width: 33%; text-align: left">
                    POA - <?php echo $this->session->userdata('gestion')?>. Aprobado mediante RD. Nro. 124/2019 de 19/09/2019
                </td>
                <td style="width: 33%; text-align: center">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 33%; text-align: right">
                    <?php echo "SEPT. / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="3"><br><br></td>
            </tr>
        </table>
    </page_footer>
    <?php echo $oregional;?><br>
    <table style="width:98%;font-size: 6px;font-family: Arial;" align="center" border="0">
        <tr>
            <td style="width:98%;"><b>COD. ACE. : </b>C&oacute;digo Acci&oacute;n Estrategica</td>
        </tr>
        <tr>
            <td><b>COD. ACP. : </b>C&oacute;digo Acci&oacute;n de Corto Plazo</td>
        </tr>
        <tr>
            <td><b>COD. OPE. : </b>C&oacute;digo Operaci&oacute;n</td>
        </tr>
    </table>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('formulario POA - N 2.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
