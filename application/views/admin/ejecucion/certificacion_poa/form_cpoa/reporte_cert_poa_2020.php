<?php

ob_start();
$msg = "CÓDIGO:".$cpoa[0]['cpoa_codigo']." - RESPONSABLE:".$cpoa[0]['fun_nombre']." - NRO.ITEMS:".$nro."";

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

<page backtop="75.5mm" backbottom="49mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
    <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:95.6%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center;">
                          <td style="width:18%;" text-align:center;">
                            <!-- <img src="<?php echo $this->session->userdata('img') ?>" alt="" style="width:40%;"> -->
                          </td>
                          <td style="width:64%;">
                            <table align="center" border="0" style="width:100%;">
                                <tr style="font-size: 19px;font-family: Arial;">
                                    <td><b>CERTIFICACI&Oacute;N DEL PLAN OPERATIVO ANUAL<br><?php echo $this->session->userdata('gestion')?></b></td>
                                </tr>
                                <tr style="font-size: 15px;font-family: Arial;">
                                    <td>DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N</td>
                                </tr>
                            </table>
                          </td>
                          <td style="width:18%;">
                                <table border="0">
                                    <tr style="font-size: 8px; font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>Nro. CERT.</b></td>
                                        <td style="width:68%; font-size: 8px;" align="left">: <b><?php if($cpoa[0]['cpoa_estado']==0){echo "<font color='#eb0000'>SIN CÓDIGO (No Valido)</font>";}else{echo $cpoa[0]['cpoa_codigo'];}?></b></td>
                                    </tr>
                                    <tr style="font-size: 7.5px;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>FECHA</b></td>
                                        <td style="width:68%;" align="left">: <?php echo date('d-m-Y',strtotime($cpoa[0]['cpoa_fecha']));?></td>
                                    </tr>
                                    <tr style="font-size: 7.5px;font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>CITE.</b></td>
                                        <td style="width:68%;" align="left">: <?php echo $cpoa[0]['cpoa_cite'];?></td>
                                    </tr>
                                    <tr style="font-size: 7.5px;font-family: Arial;" bgcolor="#dcefec">
                                        <td style="width:32%;" align="left"><b>FECHA CITE</b></td>
                                        <td style="width:68%;" align="left">: <?php echo date('d-m-Y',strtotime($cpoa[0]['cite_fecha']));?></td>
                                    </tr>
                                </table>
                            <!-- <qrcode value="<?php echo $msg; ?>" ec="L" style="width: 15mm;"></qrcode> -->
                          </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div style="font-size: 10px; font-family: Arial;">
                                <br>&nbsp;&nbsp;El presente documento certifica que el Item descrito se encuentra registrado en la programaci&oacute;n F&iacute;sico Financiero se relaciona y responde a las acciones de corto plazo y operaciones establecidas en el Plan Operativo Anual (POA) de la Caja Nacional de Salud.
                                </div>
                            </td>
                        </tr>
                  </table><br>
                  <div style="border: 1px groove #000;font-size: 9px;font-family: Arial;height:25px;" align="center">
                    <br><b>&nbsp;&nbsp;La presente CERTIFICACI&Oacute;N deber&aacute; ser utilizada para inicio de procesos de compra de bienes y/o contrataci&oacute;n de servicios a ser concretados a partir de la fecha de su emisi&oacute;n.</b>
                  </div>
                  <hr>
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black;">
                            <td style="width:63%;">
                                <table border="0" style="width:100%;">
                                    <tr style="font-size: 8.5px;font-family: Arial; height: : 30;">
                                        <td style="width:30%;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PROGRAMA</b></td>
                                        <td style="width:70%;">: <?php echo $programa[0]['aper_programa'].''.$programa[0]['aper_proyecto'].''.$programa[0]['aper_actividad'].' - '.$programa[0]['aper_descripcion'];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OBJETIVO ESTRATEGICO</b></td>
                                        <td style="width:70%;">: <?php echo $datos[0]['obj_codigo'].'.- '.$datos[0]['obj_descripcion'];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OPERACI&Oacute;N</b></td>
                                        <td style="width:70%;">: <?php echo $datos[0]['or_codigo'].'.- '.$datos[0]['or_objetivo'];?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACTIVIDAD</b></td>
                                        <td style="width:70%;">: <?php echo $datos[0]['prod_cod'].'.- '.$datos[0]['prod_producto'];?></td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width:37%;">
                                <table border="0" style="width:100%;">
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>DIR. ADM.</b></td>
                                        <td style="width:70%;">: <?php echo strtoupper($datos[0]['dep_departamento']);?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b>UNI. EJEC.</b></td>
                                        <td style="width:70%;">: <?php echo strtoupper($datos[0]['dist_distrital']);?></td>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                    <?php
                                        if($datos[0]['tp_id']==1){ ?>
                                        <td style="width:30%;"><b>PROYECTO </b></td>
                                        <td style="width:70%;">: <?php echo $datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' - '.$datos[0]['proy_nombre'];?></td>
                                            <?php
                                        }
                                        else{ ?>
                                            <td style="width:30%;"><b><?php echo $datos[0]['tipo_adm'];?></b></td>
                                            <td style="width:70%;">: <?php echo $datos[0]['aper_programa'].''.$datos[0]['aper_proyecto'].''.$datos[0]['aper_actividad'].' '.$datos[0]['tipo'].' - '.strtoupper($datos[0]['act_descripcion']).' '.$datos[0]['abrev'];?></td>
                                            <?php
                                            
                                        }
                                    ?>
                                    </tr>
                                    <tr style="font-size: 8.5px;font-family: Arial;">
                                        <td style="width:30%;"><b><?php if($datos[0]['tp_id']==1){echo "COMPONENTE ";}else{echo "SERVICIO ";} ?></b></td>
                                        <td style="width:70%;">: <?php echo $datos[0]['com_componente'];?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table>
        
        
    </page_header>

    <page_footer>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;">
            <tr>
                <td style="width: 3%;"></td>
                <td style="width: 55%;">
                    <b>RECOMENDACIONES</b><hr>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr bgcolor="#dcefec">
                            <td style="width: 100%;">
                                <br><?php echo $cpoa[0]['cpoa_recomendacion'];?><br><br>
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
                            <td><?php echo $cpoa[0]['fun_nombre'].' '.$cpoa[0]['fun_paterno'].' '.$cpoa[0]['fun_materno'];?></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;">
                            <td><b>CARGO</b></td>
                            <td><?php echo $cpoa[0]['fun_cargo'];?></td>
                        </tr>
                        <tr style="font-size: 9px;font-family: Arial; height:65px;" align="center">
                            <td colspan="2"><b><br><br>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%;">
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
    <?php if($cpoa[0]['cpoa_estado']==3){echo "<div align='center'>CERTIFICACIÓN ANULADA</div>";} ?>
    <div align="left" style="font-size: 11px;font-family: Arial;"><b>Descripci&oacute;n de lo solicitado :</b></div><br>
    <?php echo $items;?>
    <?php if($cpoa[0]['cpoa_estado']==0){echo "<br><br>(SIN C&Oacute;DIGO DE CERTIFICACIÓN, (Comuniquese con el Administrador SIIPLAS)...)";}?>
  
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Certificación_Poa.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
