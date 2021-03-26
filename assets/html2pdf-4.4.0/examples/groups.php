<?php
/**
 * HTML2PDF Library - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @package   Html2pdf
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @copyright 2016 Laurent MINGUET
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

ob_start();
?>
<style type="text/css">
<!--
    table.page_header {width: 100%; border: none; background-color: #DDDDFF; border-bottom: solid 1mm #AAAADD; padding: 2mm }
    table.page_footer {width: 100%; border: none; background-color: #DDDDFF; border-top: solid 1mm #AAAADD; padding: 2mm}
-->
</style>

<page backtop="44mm" backbottom="20mm" backleft="10mm" backright="10mm" pagegroup="new">
    <page_header>
        <table class="page_header">
            <tr>
                <td style="width: 100%; text-align: left">
                    <table width="100%">
                    <tr>
                      <td width=20%; text-align:center;"">
                        
                      </td>
                      <td width=60%; class="titulo_pdf" align=left>
                          <FONT FACE="courier new" size="1">
                          <b>ENTIDAD : </b>CAJA NACIONAL DE SALUD<br>
                          <b>PLAN OPERATIVO ANUAL POA : </b>2018<br>
                          <b>REPORTE : </b> REQUERIMIENTOS POR UNIDAD<br>
                          <b>OPERACI&Oacute;N : </b>NOMBRE DEL PROYECTO<br>
                          <b>APERTURA PROGRAMATICA : </b>710000000 - PROYECTO DE INVERSIÓN<br>
                          <b>REPORTE : </b>CUADRO COMPARATIVO POR PARTIDAS
                          </FONT>
                      </td>
                      <td width=20%; text-align:center;"">
                      </td>
                    </tr>
                  </table>
                </td>
            </tr>
        </table>
    </page_header>
    <page_footer>
        <table class="page_footer">
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: right">
                    page [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
    </page_footer>
    <table border=1>
        <thead>
                  <tr class="modo1">
                    <th style="width:1%;"><b>COD.</b></th>
                    <th style="width:10%;"><b>OPERACI&Oacute;N</b></th>
                    <th style="width:10%;"><b>RESULTADO</b></th>
                    <th style="width:1%;"><b>TIP. IND.</b></th>
                    <th style="width:5%;"><b>INDICADOR</b></th>
                    <th style="width:1%;"><b>LINEA BASE</b></th>
                    <th style="width:1%;"><b>META</b></th>
                    <th style="width:5%;"><b>PONDERACI&Oacute;N</b></th>
                    <th style="width:4%;"><b>ENE.</b></th>
                    <th style="width:4%;"><b>FEB.</b></th>
                    <th style="width:4%;"><b>MAR.</b></th>
                    <th style="width:4%;"><b>ABR.</b></th>
                    <th style="width:4%;"><b>MAY.</b></th>
                    <th style="width:4%;"><b>JUN.</b></th>
                    <th style="width:4%;"><b>JUL.</b></th>
                    <th style="width:4%;"><b>AGO.</b></th>
                    <th style="width:4%;"><b>SEP.</b></th>
                    <th style="width:4%;"><b>OCT.</b></th>
                    <th style="width:4%;"><b>NOV.</b></th>
                    <th style="width:4%;"><b>DIC.</b></th>
                    <th style="width:7%;"><b>MEDIO DE VERIFICACI&Oacute;N</b></th>
                    <th style="width:7%;"><b>DELETE</b></th>
                    <th style="width:7%;"><b>NRO. REQ.</b></th>
                  </tr>
                </thead>
                <tbody>
    <?php
        for ($i=1; $i <=2000 ; $i++) { 
            echo "<tr>";
                echo "<td>0001</td>";
                echo "<td>NOMBRE DE LA OPERACI&Oacute;N</td>";
                echo "<td>ABS</td>";
                echo "<td>INDICADOR</td>";
                echo "<td>10</td>";
                echo "<td>12</td>";
                echo "<td>12</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
                echo "<td>1234</td>";
            echo "</tr>";
        }
    ?>
</tbody>
</table>
</page>
<?php
$content = ob_get_clean();

require_once(dirname(__FILE__).'/../html2pdf.class.php');
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
