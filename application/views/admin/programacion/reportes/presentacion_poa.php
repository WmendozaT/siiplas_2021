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
    <page backtop="50mm" backbottom="10mm" backleft="5mm" backright="8mm" pagegroup="new">
        <page_header>
          <br><div class="verde"></div>
          <table class="page_header" border="0">
              <tr>
                <td style="width: 100%; text-align: left">
                  <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:99.5%;">
                      <tr style="width: 100%; border: solid 0px black; text-align: center; font-size: 8pt; font-style: oblique;">
                        <td width=20%; text-align:center;"">
                          <!-- <img src="<?php echo $this->session->userdata('img') ?>" alt="" style="width:60%;"> -->
                        </td>
                        <td width=60%; align=left>
                          <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 25pt;" align="center"><b><?php echo $this->session->userdata('entidad');?></b></td>
                            </tr>
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><?php echo strtoupper($proyecto[0]['dep_departamento']);?></td>
                            </tr>
                            <tr>
                              <td style="width:100%; height: 1.2%; font-size: 15pt;" align="center"><?php echo strtoupper($proyecto[0]['dist_distrital']);?></td>
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
            
            <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
              <tr>
                <td style="width:100%; height: 50%; font-size: 18pt;" align="center">
                   <?php
                    if($proyecto[0]['img']!=''){
                        echo '<img src="'.base_url().'fotos/'.$proyecto[0]['img'].'" class="img-responsive" style="width:80%; height:90%;" align=center />';
                    }
                    else{
                        echo '<img src="'.base_url().'fotos/simagen.jpg" class="img-responsive" style="width:50%; height:60%;"/>';
                    }
                    ?>
                    <br>
                </td>
              </tr>
              <tr>
                <td style="width:100%; height: 1.2%; font-size: 12pt;" align="center">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
                      <tr>
                        <td style="width:100%; height: 1.2%; font-size: 50pt;" align="center"><b>POA <?php echo $this->session->userdata('gestion');?></b></td>
                      </tr>
                      <?php
                        if($proyecto[0]['tn_id']!=0){ ?>
                          <tr>
                            <td style="width:100%; height: 1.2%; font-size: 20pt;" align="center"><br><?php echo $proyecto[0]['tipo_adm'];?></td>
                          </tr>
                          <?php
                        }
                      ?>
                      <tr>
                        <td style="width:100%; height: 1.2%; font-size: 26pt;" align="center"><b><?php echo $proyecto[0]['tipo'].' '.$proyecto[0]['act_descripcion'].' - '.$proyecto[0]['abrev'];?></b></td>
                      </tr>
                    </table>
                </td>
              </tr>
            </table>

            <?php
              if($proyecto[0]['tn_id']==0){
                $servicios=$this->model_componente->proyecto_componente($proyecto[0]['proy_id']);
                $size='font-size: 12pt;';
                if(count($servicios)>12){
                  $size='font-size: 11pt;';
                }

                 echo "<br><table border=0 style='width:90%;' align=center>";
                    echo "<tr>
                          <td style='width:50%;'>
                            <ul>";
                            $cont=0;
                            foreach($servicios as $row){
                              if(count($this->model_producto->list_prod($row['com_id']))!=0){
                                $cont++;
                                if($cont<=8){
                                  echo "<li style='height: 12%; ".$size."'><b>".$row['com_componente']."</b></li>";
                                }
                              }
                            }
                    echo "  </ul>
                          </td>
                          <td style='width:50%;'>
                            <ul>";
                            $cont=0;
                            foreach($servicios as $row){
                              if(count($this->model_producto->list_prod($row['com_id']))!=0){
                                $cont++;
                                if($cont>8){
                                  echo "<li style='height: 12%; ".$size."'><b>".$row['com_componente']."</b></li>";
                                }
                              }
                            }

                            if($proyecto[0]['te_id']==16){
                              if($this->gestion==2020){
                                echo "<li style='height: 12%; ".$size."'><b>97 - SERVICIO DE LA DEUDA Y TRANSFERENCIAS ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 12%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                              }
                              else{
                                echo "<li style='height: 12%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 12%; ".$size."'><b>99 - PAGO DE BENEFICIOS SOCIALES".$proyecto[0]['abrev']."</b></li>";
                              }
                             
                            }
                            elseif ($proyecto[0]['te_id']==12) {
                              echo "<li style='height: 12%; ".$size."'><b>72 - BIENES Y SERVICIOS ".$proyecto[0]['abrev']."</b></li>";
                              echo "<li style='height: 12%; ".$size."'><b>96 - GESTI&Oacute;N DE RIESGOS ".$proyecto[0]['abrev']."</b></li>";
                            }
                            elseif($proyecto[0]['te_id']==10){
                              echo "<li style='height: 14%; ".$size."'><b>72 - BIENES Y SERVICIOS ".$proyecto[0]['abrev']."</b></li>";
                              echo "<li style='height: 14%; ".$size."'><b>96 - GESTI&Oacute;N DE RIESGOS ".$proyecto[0]['abrev']."</b></li>";
                              
                              if($this->gestion==2020){
                                echo "<li style='height: 14%; ".$size."'><b>97 - SERVICIO DE LA DEUDA Y TRANSFERENCIAS ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 14%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                              }
                              else{
                                echo "<li style='height: 14%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 14%; ".$size."'><b>99 - PAGO DE BENEFICIOS SOCIALES ".$proyecto[0]['abrev']."</b></li>";
                              }
                              
                            }
                            elseif ($proyecto[0]['act_id']==250) {
                              if($this->gestion==2020){
                                echo "<li style='height: 11%; ".$size."'><b>97 - SERVICIO DE LA DEUDA Y TRANSFERENCIAS ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 11%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                              }
                              else{
                                echo "<li style='height: 11%; ".$size."'><b>98 - TRANSFERENCIAS MINISTERIO DE SALUD ".$proyecto[0]['abrev']."</b></li>";
                                echo "<li style='height: 11%; ".$size."'><b>99 - PAGO DE BENEFICIOS SOCIALES ".$proyecto[0]['abrev']."</b></li>";
                              }
                              
                            }
                            elseif ($proyecto[0]['act_id']==252) {
                              echo "<li style='height: 11%; ".$size."'><b>96 - GESTIÓN DE RIESGOS ".$proyecto[0]['abrev']."</b></li>";
                              echo "<li style='height: 11%; ".$size."'><b>72 - BIENES Y SERVICIOS ".$proyecto[0]['abrev']."</b></li>";
                              echo "<li style='height: 11%; ".$size."'><b>73 - MEDICINA DEL TRABAJO ".$proyecto[0]['abrev']."</b></li>";
                            }
                    echo "  </ul>
                          </td>
                        </tr>";
                  echo "</table>";
              }
            ?>
    </page>

<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Requerimientos.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
