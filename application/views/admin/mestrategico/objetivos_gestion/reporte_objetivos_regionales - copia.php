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

</style>

<?php
$nro_pag=0;
foreach($regionales as $row){ $nro_pag++; ?>
<page backtop="48mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                          <td width=15%; text-align:center;"">
                            <img src="<?php echo base_url().'assets/ifinal/cns_logo.JPG'?>" alt="" style="width:40%;">
                          </td>
                          <td width=70%; align=center>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                              </tr>
                              <tr>
                                <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">OBJETIVOS REGIONALES <?php echo $this->session->userdata('gestion');?></td>
                              </tr>
                            </table>
                            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                                <tr style="font-size: 8pt;">
                                    <td style="width:25%; height:17px;" align="left"><b>OBJETIVO ESTRATEGICO</b></td>
                                    <td style="width:75%;" align="left">: <?php echo $obj_estrategico[0]['obj_codigo'].'.- '.$obj_estrategico[0]['obj_descripcion'];?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:25%; height:17px;" align="left"><b>ACCI&Oacute;N ESTRATEGICO</b></td>
                                    <td style="width:75%;" align="left">: <?php echo $accion_estrategica[0]['acc_codigo'].'.- '.$accion_estrategica[0]['acc_descripcion'];?></td>
                                </tr>
                                <tr style="font-size: 8pt;">
                                    <td style="width:25%; height:17px;" align="left"><b>OBJETIVO DE GESTI&Oacute;N</b></td>
                                    <td style="width:75%;" align="left">: <?php echo $ogestion[0]['og_codigo'].'.- '.$ogestion[0]['og_objetivo'];?></td>
                                </tr>
                            </table>
                          </td>
                          <td width=15%; align=left style="font-size: 8px;">
                            &nbsp; <b style="font-size: 9pt;">FORMULARIO N° XX </b><br>
                            &nbsp; <b>FECHA DE IMP. : </b><?php echo date("d").'/'.$mes[ltrim(date("m"), "0")]. "/".date("Y"); ?><br>
                            &nbsp; <b>PAGINAS OR. : </b>[[page_cu]]/[[page_nb]]<br>
                            &nbsp; <b>PAGINAS OG. : </b><?php echo $nro_pag."/".count($regionales);?>
                          </td>
                        </tr>
                  </table>
                </td>
            </tr>
        </table><br>
        
    </page_header>
    <page_footer>
    <hr>
    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
        <tr>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>ELABORADO POR (Jefe Medico)<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>RESPONSABLE</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>REVISADO POR (Jefe de Serv.)<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>NOMBRE :</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO : </b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
            </td>
            <td style="width: 33.3%;">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                    <tr style="font-size: 10px;font-family: Arial; height:65px;">
                        <td style="width:100%;" colspan="2"><b>APROBADO POR (Administrador)<br></b></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>NOMBRE :</b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;">
                        <td><b>CARGO : </b></td>
                        <td></td>
                    </tr>
                    <tr style="font-size: 8.5px;font-family: Arial; height:65px;" align="center">
                        <td colspan="2"><b><br><br>FIRMA</b></td>
                    </tr>
                </table>
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
                pag. [[page_cu]]/[[page_nb]]
            </td>
        </tr>
        <tr>
            <td colspan="3"><br><br><br></td>
        </tr>
    </table>
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
            <table border=1>
            <tr>
                <td>
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                <thead>
                  <tr style="font-size: 8px;" bgcolor="#d8d8d8" align=center>
                    <th style="width:2%;height:15px;">#</th>
                    <th style="width:5%;">COD. O.G.</th>
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
            </table>
            </td>
            </tr>
            
            <tr>
            <td>
            <table cellpadding="0" cellspacing="0" class="tabla" border=0.4 style="width:100%;">
                <thead>
                <tr>
                  <th colspan=4 bgcolor="#e4e2e2" style="height:14px;" align=center>DISTRIBUCI&Oacute;N</th>
                </tr>
                <tr>
                  <th style="width:25%; height:14px;" bgcolor="#e4e2e2" align=center>REGIONAL / DISTRITAL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>PRIMER NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>SEGUNDO NIVEL</th>
                  <th style="width:25%;" bgcolor="#e4e2e2" align=center>TERCER NIVEL</th>
                </tr>
                </thead>
                <tbody>
                    <tr style="text-align: center;">
                        <td style="width: 25%;">
                            <?php
                                $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],0);
                                if(count($nivel)!=0){ ?>
                                    <br>
                                    <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                                      <thead>
                                      <tr style="font-size: 6.8px;">
                                        <th style="width:1%; height:12px;" align=center>#</th>
                                        <th style="width:9%" align=center>TIPO</th>
                                        <th style="width:78%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                                        <th style="width:10%;" align=center>PROG.</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nro_nivel=0;
                                          foreach($nivel as $row_n){
                                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                                            $prog='-'; $bgcolor='';
                                            if(count($uni)!=0){
                                              $prog=$uni[0]['prog_fis'];
                                              $bgcolor='#dcebf9';
                                            }
                                            $nro_nivel++;
                                            echo 
                                            '<tr style="font-size: 6.3px;" bgcolor='.$bgcolor.'>
                                              <td style="width:1%;height:12px; text-align: center;">'.$nro_nivel.'</td>
                                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                                              <td style="width:78%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                                            </tr>';
                                          }
                                        ?>
                                      </tbody>
                                    </table>
                                    <?php
                                }
                            ?>
                        </td>
                        <td style="width: 25%;">
                            <?php
                                $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],1);
                                if(count($nivel)!=0){ ?>
                                    <br>
                                    <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                                      <thead>
                                      <tr style="font-size: 6.8px;">
                                        <th style="width:1%; height:12px;" align=center>#</th>
                                        <th style="width:9%;" align=center>TIPO</th>
                                        <th style="width:78%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                                        <th style="width:10%;" align=center>PROG.</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nro_nivel=0;
                                          foreach($nivel as $row_n){
                                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                                            $prog='-'; $bgcolor='';
                                            if(count($uni)!=0){
                                              $prog=$uni[0]['prog_fis'];
                                              $bgcolor='#dcebf9';
                                            }
                                            $nro_nivel++;
                                            echo 
                                            '<tr style="font-size: 6.3px;" bgcolor='.$bgcolor.'>
                                              <td style="width:1%;height:12px; text-align: center;">'.$nro_nivel.'</td>
                                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                                              <td style="width:78%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                                            </tr>';
                                          }
                                        ?>
                                      </tbody>
                                    </table>
                                    <?php
                                }
                            ?>
                        </td>
                        <td style="width: 25%;">
                            <?php
                                $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],2);
                                if(count($nivel)!=0){ ?>
                                    <br>
                                    <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                                      <thead>
                                      <tr style="font-size: 6.8px;">
                                        <th style="width:1%; height:12px;" align=center>#</th>
                                        <th style="width:9%;" align=center>TIPO</th>
                                        <th style="width:78%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                                        <th style="width:10%;" align=center>PROG.</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nro_nivel=0;
                                          foreach($nivel as $row_n){
                                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                                            $prog='-'; $bgcolor='';
                                            if(count($uni)!=0){
                                              $prog=$uni[0]['prog_fis'];
                                              $bgcolor='#dcebf9';
                                            }
                                            $nro_nivel++;
                                            echo 
                                            '<tr style="font-size: 6.3px;" bgcolor='.$bgcolor.'>
                                              <td style="width:1%;height:12px; text-align: center;">'.$nro_nivel.'</td>
                                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                                              <td style="width:78%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                                            </tr>';
                                          }
                                        ?>
                                      </tbody>
                                    </table>
                                    <?php
                                }
                            ?>
                        </td>
                        <td style="width: 25%;">
                            <?php
                                $nivel=$this->model_objetivoregion->list_unidades_niveles($row['dep_id'],3);
                                if(count($nivel)!=0){ ?>
                                    <br>
                                    <table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:100%;" align=center>
                                      <thead>
                                      <tr style="font-size: 6.8px;">
                                        <th style="width:1%; height:12px;" align=center>#</th>
                                        <th style="width:9%;" align=center>TIPO</th>
                                        <th style="width:78%;" align=center>UNIDAD / ESTABLECIMIENTO</th>
                                        <th style="width:10%;" align=center>PROG.</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                        $nro_nivel=0;
                                          foreach($nivel as $row_n){
                                            $uni=$this->model_objetivoregion->get_unidad_programado($row_or['or_id'],$row_n['act_id']);
                                            $prog='-'; $bgcolor='';
                                            if(count($uni)!=0){
                                              $prog=$uni[0]['prog_fis'];
                                              $bgcolor='#dcebf9';
                                            }
                                            $nro_nivel++;
                                            echo 
                                            '<tr style="font-size: 6.3px;" bgcolor='.$bgcolor.'>
                                              <td style="width:1%;height:12px; text-align: center;">'.$nro_nivel.'</td>
                                              <td style="width:9%; text-align: left;">'.$row_n['tipo'].'</td>
                                              <td style="width:78%; text-align: left;">'.$row_n['act_descripcion'].'</td>
                                              <td style="width:10%; text-align: right;">'.$prog.'</td>
                                            </tr>';
                                          }
                                        ?>
                                      </tbody>
                                    </table>
                                    <?php
                                }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
            <?php
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
