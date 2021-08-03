<style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
    .verde{ width:100%; height:5px; background-color:#1c7368;}
    .blanco{ width:100%; height:5px; background-color:#F1F2F1;}
    .siipp{width:120px;}
    .tabla {
        font-size: 7px;
        width: 100%;
    }
}
</style>

<?php
$nro_pag=0;
foreach($regionales as $row){ $nro_pag++; ?>
<page backtop="70mm" backbottom="30mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <?php echo $cabecera;?>
    </page_header>
    <page_footer>
        <?php echo $pie;?>
    </page_footer>
    
    <?php
        $oregional=$this->model_objetivoregion->list_oregional_regional($og_id,$row['dep_id']);  ?>
            <div style="font-size: 12px;font-family: Arial; height:20px;"><b>REGIONAL : </b><?php echo strtoupper($row['dep_departamento']).' |<b> META REGIONAL : </b>'.$row['prog_fis'];?></div>
        <?php
    $nro=0;
        if(count($oregional)!=0){
          foreach($oregional as $row_or){
            $nro++;
            ?>
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                <thead>
                  <tr style="font-size: 8px;" bgcolor="#d8d8d8" align=center>
                    <th style="width:2%;height:15px;">#</th>
                    <th style="width:5%;">COD. ACP.</th>
                    <th style="width:15%;">OBJETIVO REGIONAL <?php echo $this->session->userdata('gestion')?> / OPERACI&Oacute;N</th>
                    <th style="width:15%;">PRODUCTO</th>
                    <th style="width:14%;">RESULTADO (LOGROS)</th>
                    <th style="width:13%;">INDICADOR</th>
                    <th style="width:5%;">LINEA BASE</th>
                    <th style="width:5%;">META</th>
                    <th style="width:13%;">MEDIO DE VERIFICACI&Oacute;N</th>
                    <th style="width:13%;">OBSERVACIONES DETALLE DE DISTRIBUCI&Oacute;N</th>
                  </tr>
                </thead>
              <tbody>
                <tr style="font-size: 7px;">
                <td style="width:2%; height:15px;" align=center><?php echo $nro;?></td>
                <td style="width:5%;"><?php echo $row_or['og_codigo'];?></td>
                <td style="width:15%;"><?php echo $row_or['or_objetivo'];?></td>
                <td style="width:15%;"><?php echo $row_or['or_producto'];?></td>
                <td style="width:14%;"><?php echo $row_or['or_resultado'];?></td>
                <td style="width:13%;"><?php echo $row_or['or_indicador'];?></td>
                <td style="width:5%;"><?php echo $row_or['or_linea_base'];?></td>
                <td style="width:5%;"><?php echo $row_or['or_meta'];?></td>
                <td style="width:13%;"><?php echo $row_or['or_verificacion'];?></td>
                <td style="width:13%;"><?php echo $row_or['or_observacion'];?></td>
              </tr>
              </tbody>
            </table><br>
            
            <?php
                $num=0;
                $distritales=$this->model_proyecto->list_distritales($row['dep_id']);
                foreach($distritales as $rowd){
                    $niveles=$this->model_objetivoregion->list_niveles();
                 echo '
                 <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                    <thead>
                    <tr>
                      <th colspan=4 bgcolor="#e4e2e2" style="height:12px;" align=center>DISTRIBUCI&Oacute;N - '.strtoupper($rowd['dist_distrital']).'</th>
                    </tr>
                    <tr>
                      <th style="width:25%; height:12px;" bgcolor="#e4e2e2" align=center>REGIONAL / DISTRITAL</th>
                      <th style="width:25%;" bgcolor="#e4e2e2" align=center>PRIMER NIVEL</th>
                      <th style="width:25%;" bgcolor="#e4e2e2" align=center>SEGUNDO NIVEL</th>
                      <th style="width:25%;" bgcolor="#e4e2e2" align=center>TERCER NIVEL</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">';
                        foreach($niveles as $rown){
                            $nivel=$this->model_objetivoregion->list_unidades_distrital_niveles($rowd['dist_id'],$rown['tn_id']);
                            echo '
                            <td style="width:25%;">
                                <table class="tabla" cellpadding="0" cellspacing="0" border=0.2 style="width:100%; font-size: 6.3px;">
                                  <thead>
                                  <tr>
                                    <th style="width:10px; height:10px;">#</th>
                                    <th style="width:30px;">CAT.</th>
                                    <th style="width:135px;">UNIDAD / ESTABLECIMIENTO</th>
                                    <th style="width:50px;">PROG.</th>
                                  </tr>
                                  </thead>
                                  <tbody>';
                                  $nro=0;
                                  foreach($nivel as $rowu){
                                    $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$rowu['act_id']);
                                    $color='';$valor_prog=0;
                                    if(count($uni)!=0){
                                        if($uni[0]['or_estado']==1){
                                            $color='#cbf7cb';      
                                        }
                                      $valor_prog=$uni[0]['prog_fis'];
                                    }
                                    $nro++;
                                    echo
                                    '<tr bgcolor='.$color.'>
                                      <td style="width:10px;">'.$nro.'</td>
                                      <td style="width:30px;">'.$rowu['aper_programa'].'</td>
                                      <td style="width:135px;text-align: left;">'.$rowu['tipo'].' '.$rowu['act_descripcion'].'</td>
                                      <td style="width:50px;">'.$valor_prog.'</td>
                                    </tr>';
                                  }
                                  echo'
                                  </tbody>
                                </table>
                            </td>';
                        }
                        echo '
                        </tr>
                    </tbody>
                </table><br>';   
                
                }
          }
        }
        else{ ?>
            <div style="font-size: 9px;font-family: Arial; height:20px;">SIN REGISTROS</div>
            <?php
        }
    ?>
    
</page>
        <?php
    }

$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Foda.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
