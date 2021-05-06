<?php
ob_start();
?>
<style type="text/css">
    table.page_header {width: 100%; border: none; border-bottom: solid 1mm; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #739e73; border-top: solid 1mm #AAAADD; padding: 2mm}
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
    if($proyecto[0]['ta_id']!=2 & $proyecto[0]['ta_id']!=5){
        echo $principal;
    }
    
$nro_pag=0;
foreach($subactividades as $rowu){ $nro_pag++; ?>
<page backtop="55mm" backbottom="15mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
        <br><div class="verde"></div>
        <table class="page_header" border="0" style="width:100%;">
          <tr>
            <td style="width:15%; text-align:center;">
              
            </td>
              <td style="width: 70%; text-align: left">
                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                  <tr>
                    <td style="width:100%; font-size:30px;" align=center>
                      <b><?php echo $this->session->userdata('entidad');?></b>
                    </td>
                  </tr>
                  <tr>
                    <td style="width:100%; font-size:15px;" align=center>
                      DEPARTAMENTO NACIONAL DE PLANIFICACI&Oacute;N
                    </td>
                  </tr>
                </table>
              </td>
            <td style="width:15%;font-size: 8px;" align=center>
            </td>
          </tr>
        </table><br>
        <table border=0 style="width:92.5%;" align=center>
          <tr>
            <td style="width:95%;font-size: 10px;" align=right><?php echo strtoupper($proyecto[0]['dep_departamento']).' '.$mes[ltrim(date("m"), "0")]. " de " . date("Y"); ?></td>
          </tr>
        </table><br>
    </page_header>
    <page_footer>
    <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:96%;" align="center">
            <tr>
                <td style="width: 33%; text-align: left">
                    <?php echo $this->session->userdata('gestion').". ".$this->session->userdata('rd_poa');?>
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
    $titulo1=strtoupper($rowu['tipo_subactividad']).' '.strtoupper($rowu['serv_descripcion']).' - '.$proyecto[0]['abrev'];
    $titulo2=strtoupper($rowu['tipo_subactividad']).' '.strtoupper($rowu['serv_descripcion']).' - '.$proyecto[0]['abrev'];
    if($proyecto[0]['ta_id']==2){ /// Establecimiento de salud
        $titulo1=$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'];
        $titulo2=$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'];
    }
/*    elseif($proyecto[0]['ta_id']==5){
        $titulo1='JEFE MEDICO REGIONAL '.$proyecto[0]['abrev'];
        $titulo2=$proyecto[0]['tipo'].' '.strtoupper($proyecto[0]['act_descripcion']).' '.$proyecto[0]['abrev'];
    }*/

        $operaciones=$this->model_seguimientopoa->operaciones_programados_x_mes($rowu['com_id'],$verif_mes[1]); /// lISTA DE OPERACIONES
        echo '
            <table border=0 style="width:97%;" align=center>
                <tr>
                    <td style="width:95%;"><b>Señor(es) : </b> <br>'.$titulo1.'<br>Presente .-</td>
                </tr>
                <tr>
                    <td style="width:95%;"><br><br></td>
                </tr>
                <tr>
                    <td style="width:95%; font-size: 16px;font-family: Arial;" align=right><b>REF. NOTIFICACI&Oacute;N PARA SEGUIMIENTO POA '.$verif_mes[2].' '.$this->session->userdata('gestion').'</b></td>
                </tr>
                <tr>
                    <td style="width:95%;"><br></td>
                </tr>
                <tr>
                    <td style="width:95%;text-align: justify;">
                    El Departamento Nacional de Planificaci&oacute;n en el marco de sus competencias viene fortaleciendo las tareas de monitoreo y supervisi&oacute;n 
                    a traves del Sistema de Planificaci&oacute;n <b>SIIPLAS</b>, en este sentido recordamos a usted efectuar el seguimiento al cumplimiento del POA <b>'.$verif_mes[2].'</b> '.$this->session->userdata('gestion').', de 
                    <b>'.$titulo2.'</b> a su cargo, haciendo enfasis en la programaci&oacute;n mensual y periodo de ejecuci&oacute;n de cada operaci&oacute;n.
                    </td>
                </tr>
            </table>';

        echo '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:98%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                    <th style="width:2%; height:20px;"></th>
                    <th style="width:3%;"><b>COD. OR.</b></th>
                    <th style="width:3%;"><b>COD. OPE.</b></th>
                    <th style="width:30%;">OPERACI&Oacute;N</th>
                    <th style="width:20%;">INDICADOR</th>
                    <th style="width:20%;">MEDIO DE VERIFICACI&Oacute;N</th>
                    <th style="width:5%;">META ANUAL</th>
                    <th style="width:5%;">PROG. MES</th>
                    <th style="width:5%;">EJEC.</th>
                  </tr>
                </thead>
                <tbody>';
                $nro_ope=0;
                foreach ($operaciones as $row) {
                    $ejec=$this->model_producto->verif_ope_evaluado_mes($row['prod_id'],$this->verif_mes[1]);
                    $evaluado=0;
                      if(count($ejec)!=0){
                        $evaluado=$ejec[0]['pejec_fis'];
                      }

                    $nro_ope++;
                    echo '
                        <tr>
                            <td align=center style="height:20px; width:2%;">'.$nro_ope.'</td>
                            <td align=center style="font-size: 10px; width:3%;">'.$row['or_codigo'].'</td>
                            <td align=center style="font-size: 10px; width:3%;">'.$row['prod_cod'].'</td>
                            <td style="width:30%;">'.$row['prod_producto'].'</td>
                            <td style="width:20%;">'.$row['prod_indicador'].'</td>
                            <td style="width:20%;">'.$row['prod_fuente_verificacion'].'</td>
                            <td style="width:5%;" align=right>'.round($row['prod_meta'],2).'</td>
                            <td style="width:5%;font-size: 9px;" align=right>'.round($row['pg_fis'],2).'</td>
                            <td style="width:5%;" align=right>'.round($evaluado,2).'</td>
                        </tr>';
                }
        echo '  </tbody>
            </table>';

        $requerimientos=$this->model_notificacion->list_requerimiento_mes($proyecto[0]['proy_id'],$rowu['com_id'],$verif_mes[1]);
        if(count($requerimientos)!=0){

        echo '<br>
            <table border=0 style="width:97%;" align=center>
                <tr>
                    <td style="width:95%;text-align: justify;">
                    En el mismo sentido, efectuar las gestiones en el plazo programado para la ejecuci&oacute;n de la Solicitud de CERTIFICACIÓN POA
                    <b>'.$verif_mes[2].' '.$this->session->userdata('gestion').'</b>. Recordar que en ambos casos para fines de control y gestión por resultados la 
                    responsabilidad del cumplimiento corresponde a su autoridad.
                    </td>
                </tr>
            </table>';

        echo '<br><table cellpadding="0" cellspacing="0" class="tabla" border=0.2 style="width:98%;" align=center>
                <thead>
                  <tr style="font-size: 7px;" bgcolor=#f8f2f2 align=center>
                    <th style="width:1.5%; height:20px;">#</th>
                    <th style="width:3%;"><b>COD. OPE.</b></th>
                    <th style="width:7%;"><b>PARTIDA</b></th>
                    <th style="width:25%;">DETALLE REQUERIMIENTO</th>
                    <th style="width:10%;">UNIDAD DE MEDIDA</th>
                    <th style="width:7%;">CANTIDAD</th>
                    <th style="width:8%;">PRECIO UNITARIO</th>
                    <th style="width:8%;">PRECIO TOTAL</th>
                    <th style="width:8%;">PROG. MES</th>
                    <th style="width:15%;">OBSERVACI&Oacute;N</th>
                  </tr>
                </thead>
                <tbody>';
                $nro_req=0;$suma=0;
                foreach ($requerimientos as $row) {
                    $suma=$suma+$row['ipm_fis'];
                    $nro_req++;
                    echo '
                        <tr>
                            <td align=center style="height:20px; width:1.5%;">'.$nro_req.'</td>
                            <td align=center style="font-size: 10px; width:3%;">'.$row['prod_cod'].'</td>
                            <td align=center style="font-size: 10px; width:7%;">'.$row['par_codigo'].'</td>
                            <td style="width:25%;">'.$row['ins_detalle'].'</td>
                            <td style="width:10%;">'.$row['ins_unidad_medida'].'</td>
                            <td style="width:7%;" align=right>'.round($row['ins_cant_requerida'],2).'</td>
                            <td style="width:8%;" align=right>'.number_format($row['ins_costo_unitario'], 2, ',', '.').'</td>
                            <td style="width:8%;" align=right>'.number_format($row['ins_costo_total'], 2, ',', '.').'</td>
                            <td style="width:8%;font-size: 9px;" align=right><b>'.number_format($row['ipm_fis'], 2, ',', '.').'</b></td>
                            <td style="width:15%;" align=left>'.$row['ins_observacion'].'</td>
                        </tr>';
                }
        echo '  
                <tr>
                    <td colspan=8 style="height:15px;" align=right><b>TOTAL MONTO A CERTIFICAR </b></td>
                    <td align=right>'.number_format($suma, 2, ',', '.').'</td>
                    <td></td>
                </tr>
                </tbody>
            </table>';
        }


    ?>
    
</page>
    <?php
}

$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Notificacion_POA_'.$verif_mes[2].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
