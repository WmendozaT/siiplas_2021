<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
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


<page backtop="46mm" backbottom="40mm" backleft="8mm" backright="8mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=15%; text-align:center;"">
                            <img src="<?php echo $this->session->userdata('img') ?>" alt="" style="width:35%;">
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
                                        <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "PROY. INV. ";}else{ echo "UNIDAD / ESTABLECIMIENTO ";} ?></b></td>
                                        <td>: <?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['proy_nombre']).' - '.$proyecto[0]['abrev'];?></td>
                                    </tr>
                                    <tr style="font-size: 8pt;">
                                        <td style="height: 1.2%"><b><?php if($proyecto[0]['tp_id']==1){ echo "COMPONENTE ";}else{ echo "SUB-ACTIVIDAD ";} ?></b></td>
                                        <td>: <?php echo strtoupper($componente[0]['com_componente']);?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                
                            </table>
                          </td>
                          <td width=15%; align=left style="font-size: 8px;">
                            &nbsp; <b style="font-size: 9pt;">FORM. POA N°5 </b><br>
                            &nbsp; <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            &nbsp; <b>PAGINAS : </b>[[page_cu]]/[[page_nb]]
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        <div align="center">PLAN OPERATIVO ANUAL <?php echo $this->session->userdata('gestion')?> - PROGRAMACI&Oacute;N F&Iacute;SICO FINANCIERO <?php if($this->session->userdata('gestion')==2020){ echo "<b>( BORRADOR)</b>";} ?></div><br>
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
    
    <?php
        if(count($lista_insumos)!=0){ ?>
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                <thead>
                 <tr class="modo1">
                    <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:11px;"></th>
                    <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">PARTIDA</th>
                    <th style="width:15%;" style="background-color: #1c7368; color: #FFFFFF">DETALLE REQUERIMIENTO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNIDAD</th>
                    <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">CANTIDAD</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">UNITARIO</th>
                    <th style="width:5%;" style="background-color: #1c7368; color: #FFFFFF">TOTAL</th>
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
                    <th style="width:4%;" style="background-color: #1c7368; color: #FFFFFF">COD. ACT.</th>   
                </tr>    
               
                </thead>
                <tbody>
                <?php
                 $cont = 0; $total=0; 
                    foreach ($lista_insumos as $row) {
                        $cont++;
                        if($this->gestion!=2020){
                            $prog = $this->minsumos->get_list_insumo_financiamiento($row['insg_id']);
                        }
                        else{
                            $prog = $this->model_insumo->list_temporalidad_insumo($row['ins_id']);
                        }
                        
                        $total=$total+$row['ins_costo_total'];
                        $color=''; $color_mod='';
                            if(count($prog)!=0){
                                if(($row['ins_costo_total'])!=$prog[0]['programado_total']){
                                    $color='#f5bfb6';
                                }
                            }

                            if($row['ins_mod']==2){
                                $color_mod='#e6e5e5';
                            }
                        ?>
                        <tr class="modo1" bgcolor="<?php echo $color_mod;?>">
                            <td style="width: 1%; text-align: left" style="height:10px;" bgcolor="<?php echo $color;?>"><?php echo $cont;?></td>
                            <td style="width: 4%; text-align: center"><?php echo $row['par_codigo'];?></td>
                            <td style="width: 15%; text-align: left"><?php echo $row['ins_detalle'];?></td>
                            <td style="width: 5%; text-align: left"><?php echo $row['ins_unidad_medida'];?></td>
                            <td style="width: 4%; text-align: right"><?php echo $row['ins_cant_requerida'];?></td>
                            <td style="width: 5%; text-align: right;"><?php echo number_format($row['ins_costo_unitario'], 2, ',', '.');?></td>
                            <td style="width: 5%; text-align: right;"><?php echo number_format($row['ins_costo_total'], 2, ',', '.');?></td>
                            <?php
                                if(count($prog)!=0){ ?>
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
                            <td style="width: 4%; text-align: center;"><?php echo $row['prod_cod'];?></td>
                        </tr>
                        <?php
                    }
                ?>
                </tbody>
                <tr class="modo1">
                    <td colspan="6" style="height:10px;">TOTAL PROGRAMADO OPERACI&Oacute;N</td>
                    <td style="width: 4%; text-align: right;"><?php echo number_format($total, 2, ',', '.'); ?></td>
                    <td colspan="14"></td>
                </tr>
        </table><br>

        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:80%;" align="center">
            <thead>
                <!-- <tr class="modo1">
                    <th style="width:100%; text-align: center" style="background-color: #1c7368; color: #FFFFFF" colspan="4">MONTO CONSOLIDADO POR PARTIDAS</th>
                </tr> -->
                <tr class="modo1">
                    <th style="width:1%;" style="background-color: #1c7368; color: #FFFFFF" style="height:11px;">Nro</th>
                    <th style="width:10%;" style="background-color: #1c7368; color: #FFFFFF">C&Oacute;DIGO</th>
                    <th style="width:50%;" style="background-color: #1c7368; color: #FFFFFF">DETALLE PARTIDA</th>
                    <th style="width:9%;" style="background-color: #1c7368; color: #FFFFFF">MONTO PROGRAMADO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $nro=0; $total=0;
                    foreach ($partidas as $row){ 
                        $nro++; $total=$total+$row['monto'];
                        ?>
                        <tr class="modo1">
                            <td style="width: 1%; text-align: center" style="height:10px;"><?php echo $nro;?></td>
                            <td style="width: 10%; text-align: left;"><?php echo $row['par_codigo'];?></td>
                            <td style="width: 50%; text-align: left;"><?php echo $row['par_nombre'];?></td>
                            <td style="width: 9%; text-align: right;"><?php echo number_format($row['monto'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php
                    }
                ?>
            </tbody>
            <tr class="modo1">
                <td colspan="3" style="height:10px;">TOTAL</td>
                <td align="right"><?php echo number_format($total, 2, ',', '.'); ?></td>
            </tr>
        </table>
            <?php
        }
    ?> 
    

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try
{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Requerimientos.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
