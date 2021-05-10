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
    <page backtop="45mm" backbottom="23mm" backleft="8mm" backright="8mm" pagegroup="new">
        <page_header>
            <br><div class="verde"></div>
            <table class="page_header" border="0">
                <tr>
                    <td style="width: 100%; text-align: left">
                        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                            <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                              <td width=15%; text-align:center;"">
                                <!-- <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:50%;"> -->
                              </td>
                              <td width=65%; align=left>
                                <table>
                                    <tr>
                                        <td colspan="2" style="width:100%; height: 1.2%; font-size: 14pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>DIR. ADM.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="width:10%; height: 1%"><b>UNI. EJEC.</b></td>
                                        <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <?php
                                            if($proyecto[0]['tp_id']==1){ ?>
                                                <td style="height: 1%"><b>PROY. INV. </b></td>
                                                <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre'];?></td>
                                                <?php
                                            }
                                            else{ ?>
                                                <td style="height: 1%"><b><?php echo $proyecto[0]['tipo_adm']; ?></b></td>
                                                <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' - '.$proyecto[0]['abrev'];?></td>
                                                <?php
                                            }
                                        ?>
                                    </tr>
                                </table>
                              </td>
                              <td width=20%; align=left style="font-size: 7.5px;">
                                <!-- &nbsp; <b style="font-size: 9pt;">FORMULARIO POA - 05 </b> -->
                              </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table><br>
            <?php echo $titulo;?>

        </page_header>
        <page_footer>
            <table style="width:50%;font-size: 6px;font-family: Arial;" align="left" border="0">
                <tr>
                    <td style="width:10%;"></td><td style="width:25%;"><b>SALDO PPTO. NEGATIVO</b></td><td style="width:15%;" bgcolor="#f1aba4"></td>
                </tr>
                <tr>
                    <td></td><td><b>SALDO PPTO. POSITIVO</b></td><td bgcolor="#d3f1a4"></td>
                </tr>
            </table>
            <hr>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
                <tr>
                    <td colspan="3"><br></td>
                </tr>
                <tr>
                    <td style="width: 33%; text-align: left">
                        <?php
                            if($proyecto[0]['tp_id']==1){
                                if($proyecto[0]['aper_proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa');
                                }  
                            }
                            else{
                                if($proyecto[0]['proy_estado']==1){
                                    echo "POA - ".$this->session->userdata('gestion')."";
                                }
                                else{
                                    echo "POA - ".$this->session->userdata('gestion').". ".$this->session->userdata('rd_poa');
                                }    
                            }
                        ?>
                    </td>
                    <td style="width: 33%; text-align: center">
                        <?php echo $this->session->userdata('sistema')?>
                    </td>
                    <td style="width: 33%; text-align: right">
                        pag. [[page_cu]]/[[page_nb]]
                        <!-- <?php echo "SEPT. / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]] -->
                        <!-- <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]] -->
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><br><br></td>
                </tr>
            </table>
        </page_footer>
        <?php echo $tabla;?> 
    </page>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('REP -'.$proyecto[0]['dep_sigla'].'-'.$proyecto[0]['tipo'].' '.$proyecto[0]['proy_nombre'].'_CONSOLIDADO_PARTIDA_CONSOLIDADO.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
