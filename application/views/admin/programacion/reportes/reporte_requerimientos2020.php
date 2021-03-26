<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');

//ini_set ("memory_limit", "1000M");
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
    <page backtop="39.5mm" backbottom="31.5mm" backleft="8mm" backright="8mm" pagegroup="new">
        <page_header>
            <br><div class="verde"></div>
            <table class="page_header" border="0">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                            <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td width=15%; text-align:center;"">
                                <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:37%;">
                              </td>
                              <td width=60%; align=left>
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                    <tr>
                                        <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1.2%"><b>DIR. ADM.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1.2%"><b>UNI. EJEC.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
                                    </tr>
                                    <?php
                                    if($this->session->userdata('gestion')!=2020){ ?>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "PROY. INV. ";}else{ echo "ACTIVIDAD ";} ?></b></td>
                                            <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']);?></td>
                                        </tr>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "COMPONENTE ";}else{ echo "SUB-ACTIVIDAD ";} ?></b></td>
                                            <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                        </tr>
                                        <?php
                                    }
                                    else{ ?>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "PROY. INV. ";}else{ echo $proyecto[0]['tipo_adm'];} ?></b></td>
                                            <td>: <?php if($proyecto[0]['tp_id']==1){ echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.strtoupper($proyecto[0]['proy_nombre']); }else{ echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' - '.$proyecto[0]['abrev']; } ?></td>
                                        </tr>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "COMPONENTE ";}else{ echo "SERVICIO ";} ?></b></td>
                                            <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    
                                </table>
                              </td>
                              <td width=15%; align=left style="font-size: 8px;">
                                &nbsp; <b style="font-size: 9pt;">FORMULARIO POA N° 5</b>
                              </td>
                            </tr>
                      </table>
                    </td>
                </tr>
            </table><br>
            <div align="center">PLAN OPERATIVO ANUAL <?php echo $this->session->userdata('gestion')?> - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO
            <?php
                if($proyecto[0]['tp_id']==1){
                    if($proyecto[0]['aper_proy_estado']==1){ echo "<b>(ANTEPROYECTO)</b>";} 
                }
                else{
                    if($proyecto[0]['proy_estado']==1){ echo "<b>(ANTEPROYECTO)</b>";}     
                }
            ?>
            </div>

        </page_header>
        <page_footer>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                                <td style="width:100%;height:10.5px;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                            </tr>
                            <tr>
                                <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:10.5px;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:10.5px;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: left;font-size: 6px;">
                        <?php
                            if($proyecto[0]['tp_id']==1){
                                if($proyecto[0]['aper_proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". Aprobado mediante RD. Nro. 124/2019 de 19/09/2019";
                                }  
                            }
                            else{
                                if($proyecto[0]['proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". Aprobado mediante RD. Nro. 124/2019 de 19/09/2019";
                                }    
                            }
                        ?>
                    </td>
                    <td style="width: 33%; text-align: center;font-size: 6px;">
                        <?php echo $this->session->userdata('sistema')?>
                    </td>
                    <td style="width: 33%; text-align: right;font-size: 6px;">
                        pag. [[page_cu]]/[[page_nb]]
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br><br></td>
                </tr>
            </table>
        </page_footer>
        <?php echo $requerimientos;?> 
    </page>

    <page backtop="40mm" backbottom="33mm" backleft="8mm" backright="8mm" pagegroup="new">
        <page_header>
            <br><div class="verde"></div>
            <table class="page_header" border="0">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                            <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td width=15%; text-align:center;"">
                                <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:37%;">
                              </td>
                              <td width=60%; align=left>
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                    <tr>
                                        <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1.2%"><b>DIR. ADM.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1.2%"><b>UNI. EJEC.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
                                    </tr>
                                    <?php
                                    if($this->session->userdata('gestion')!=2020){ ?>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "PROY. INV. ";}else{ echo "ACTIVIDAD ";} ?></b></td>
                                            <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']);?></td>
                                        </tr>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "COMPONENTE ";}else{ echo "SUB-ACTIVIDAD ";} ?></b></td>
                                            <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                        </tr>
                                        <?php
                                    }
                                    else{ ?>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "PROY. INV. ";}else{ echo $proyecto[0]['tipo_adm'];} ?></b></td>
                                            <td>: <?php if($proyecto[0]['tp_id']==1){ echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' '.strtoupper($proyecto[0]['proy_nombre']); }else{ echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['proy_nombre']).' - '.$proyecto[0]['abrev']; } ?></td>
                                        </tr>
                                        <tr style="font-size: 8pt;">
                                            <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "COMPONENTE ";}else{ echo "SERVICIO ";} ?></b></td>
                                            <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    
                                </table>
                              </td>
                              <td width=15%; align=left style="font-size: 8px;">
                                &nbsp; <b style="font-size: 9pt;">FORMULARIO POA N° 5</b>
                              </td>
                            </tr>
                      </table>
                    </td>
                </tr>
            </table><br>
            <div align="center">PLAN OPERATIVO ANUAL <?php echo $this->session->userdata('gestion')?> - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO
            <?php
                if($proyecto[0]['tp_id']==1){
                    if($proyecto[0]['aper_proy_estado']==1){ echo "<b>(ANTEPROYECTO)</b>";} 
                }
                else{
                    if($proyecto[0]['proy_estado']==1){ echo "<b>(ANTEPROYECTO)</b>";}     
                }
            ?>
            </div>
        </page_header>
        <page_footer>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                                <td style="width:100%;height:10.5px;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                            </tr>
                            <tr>
                                <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:10.5px;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 33%;">
                        <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%;height:10.5px;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
                            </tr>
                            <tr>
                              <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: left;font-size: 6px;">
                        <?php
                            if($proyecto[0]['tp_id']==1){
                                if($proyecto[0]['aper_proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". Aprobado mediante RD. Nro. 124/2019 de 19/09/2019";
                                }  
                            }
                            else{
                                if($proyecto[0]['proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". Aprobado mediante RD. Nro. 124/2019 de 19/09/2019";
                                }    
                            }
                        ?>
                    </td>
                    <td style="width: 33%; text-align: center;font-size: 6px;">
                        <?php echo $this->session->userdata('sistema')?>
                    </td>
                    <td style="width: 33%; text-align: right;font-size: 6px;">
                        <?php echo "SEPT. / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br><br></td>
                </tr>
            </table>
        </page_footer>
        <?php echo $partidas;?> 
    </page>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('FORM 5-'.$proyecto[0]['dep_sigla'].'-'.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].'-'.$componente[0]['com_componente'].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
