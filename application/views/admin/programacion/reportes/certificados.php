<?php
ob_start();
?>
<page backcolor="#036153" backleft="5mm" backright="5mm" backtop="5mm" backbottom="5mm" >
    <table style="width: 99%;" border="0">
        <tr>
            <td style="width: 20%; height: 98%; background: #036153;">
                <div style="width: 100%; height:35%;border:0px;">
                    <img src="<?php echo getcwd(); ?>/assets/img/certificados/cns2.PNG" alt="Logo" title="Logo" style="width:85%; height:97%;">
                </div>
                <div style="width: 100%; height:40%;border:0px;">
                </div>
                <div style="width: 100%; height:20%;border:0px;">
                </div>
            </td>
            <td style="width: 80%; height: 98%; background: #FFFFFF; border: 1px;">
                <div style="width: 100%; height:15%;border:1px;">
                    <div style="font-size: 38px;font-family: Arial; color: #035b4e;text-align:center;"><b>CAJA NACIONAL DE SALUD</b></div>
                    <div style="font-size: 30px;font-family: Arial; text-align:center;"><b>OFICINA NACIONAL</b></div>
                    <div style="font-size: 18px;font-family: Arial; text-align:center;">DEPARTAMENTO NACIONAL DE PLANIFICACION</div>
                </div>
                <div style="width: 100%; height:20%;border:1px;">
                    <div style="font-size: 15px;font-family: Arial;"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Confiere el Presente: </b></div><br>
                    <div style="font-size: 65px;font-family: Arial;text-align:center;"><b>CERTIFICADO</b></div>
                </div>
                <div style="width: 100%; height:50%;border:1px;">
                  
                </div>
            </td>
            <!-- <td class="div"><div style="rotate: 0;">Hello ! ceci <b>est</b> un test !<br></div></td> -->
        </tr>
    </table>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    $html2pdf = new HTML2PDF('L', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
   // $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('FORM SPO N 4.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}



