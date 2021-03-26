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


<page backtop="50mm" backbottom="29mm" backleft="10mm" backright="10mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=20%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:35%;">
                          </td>
                          <td width=80%; align=left>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr>
                                    <td colspan="2" style="width:100%; height: 1.5%; font-size: 9pt;"><b><?php echo $this->session->userdata('entidad')?></b></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:10%; height: 1.5%"><b>DIR. ADM.</b></td>
                                    <td style="width:90%;">: <?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="height: 1.5%"><b>ACTIVIDAD</b></td>
                                    <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.strtoupper($proyecto[0]['proy_nombre']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="height: 1.5%"><b>SERVICIO</b></td>
                                    <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="height: 1.5%"><b>OPERACI&Oacute;N</b></td>
                                    <td>: <?php echo strtoupper($producto[0]['prod_producto']);?></td>
                                </tr>
                            </table>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center">PLAN OPERATIVO ANUAL <?php echo $this->session->userdata('gestion')?> - PROGRAMACI&Oacute;N FISICO FINANCIERO</div><br>
    </page_header>
    <page_footer>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33%;">
                    <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                            <td style="width:100%;"><b>JEFATURA DE UNIDAD O AREA / REP. DE AREA REGIONALES</b></td>
                        </tr>
                        <tr>
                            <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%;">
                    <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                          <td style="width:100%;"><b>JEFATURA DE DEPARTAMENTOS / SERV. GENERALES REGIONAL / JEFATURA MEDICA </b></td>
                        </tr>
                        <tr>
                          <td align=center><br><br><br><br><br><b>FIRMA</b></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%;">
                    <table border="1" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                        <tr>
                          <td style="width:100%;"><b>GERENCIA GENERAL / GERENCIAS DE AREA / ADMINISTRADOR REGIONAL </b></td>
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
                <td style="width: 33%; text-align: left">
                    POA - <?php echo $this->session->userdata('gestion')?>, Aprobado mediante RD. Nro 116/18 de 05.09.2018
                </td>
                <td style="width: 33%; text-align: center">
                    <?php echo $this->session->userdata('sistema')?>
                </td>
                <td style="width: 33%; text-align: right">
                    <?php echo $mes[ltrim(date("m"), "0")]. " / " . date("Y").', '.$this->session->userdata('funcionario'); ?> - pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td colspan="3"><br></td>
            </tr>
        </table>
    </page_footer>
        
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
        <thead>
         <!-- <tr><td colspan="21">PLAN OPERATIVO ANUAL <?php echo $this->session->userdata('gestion')?> - PROGRAMACI&Oacute;N FISICO FINANCIERO</td></tr> -->
         <tr class="modo1">
            <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF"></th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
            <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNIDAD</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>
            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNITARIO</th>
            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">TOTAL</th>
            <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">TOTAL PROG.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">ENE.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">FEB.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">MAR.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">ABR.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">MAY.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">JUN.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">JUL.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">AGO.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">SEPT.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">OCT.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">NOV.</th>
            <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">DIC.</th>
            <th style="width:8%;" style="background-color: #1c7368; color: #FFFFFF">OBSERVACIONES</th>   
        </tr>    
       
        </thead>
        <tbody>
        <?php
         $cont = 0; $total=0;
            foreach ($lista_insumos as $row) {
                $cont++;
                $prog = $this->minsumos->get_list_insumo_financiamiento($row['insg_id']);
                $total=$total+$row['ins_costo_total'];
                ?>
                <tr class="modo1">
                    <td style="width: 1%; text-align: left" style="height:14px;"><?php echo $cont;?></td>
                    <td style="width: 4%; text-align: center"><?php echo $row['par_codigo'];?></td>
                    <td style="width: 15%; text-align: left"><?php echo $row['ins_detalle'];?></td>
                    <td style="width: 5%; text-align: left"><?php echo $row['ins_unidad_medida'];?></td>
                    <td style="width: 4%; text-align: right"><?php echo $row['ins_cant_requerida'];?></td>
                    <td style="width: 5%; text-align: right;"><?php echo number_format($row['ins_costo_unitario'], 2, ',', '.');?></td>
                    <td style="width: 5%; text-align: right;"><?php echo number_format($row['ins_costo_total'], 2, ',', '.');?></td>
                    <?php
                        if(count($prog)!=0){ ?>
                        <td style="width: 5%; text-align: right;"><?php echo number_format($prog[0]['programado_total'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes1'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes2'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes3'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes4'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes5'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes6'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes7'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes8'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes9'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes10'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes11'], 2, ',', '.'); ?></td>
                        <td style="width: 4%; text-align: right;"><?php echo number_format($prog[0]['mes12'], 2, ',', '.'); ?></td>
                        <?php
                        }
                        else{ ?>
                        <td style="width: 5%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                        <td style="width: 4%; text-align: right; color: red">0.00</td>
                            <?php
                        }

                    ?>
                    
                    <td style="width: 8%; text-align: left;"><?php echo $row['ins_observacion'];?></td>
                </tr>
                <?php
            }
        ?>
        </tbody>
        <tr class="modo1">
            <td colspan="6">TOTAL PROGRAMADO OPERACI&Oacute;N</td>
            <td style="width: 4%; text-align: right;"><?php echo number_format($total, 2, ',', '.'); ?></td>
            <td colspan="14"></td>
        </tr>
</table>
</page>
<?php
$content = ob_get_clean();

//require_once(dirname(__FILE__).'/../html2pdf.class.php');


require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try
{
    $html2pdf = new HTML2PDF('L', 'A4', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('groups.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
