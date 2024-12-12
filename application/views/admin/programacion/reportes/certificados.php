<?php
ob_start();
?>
<page backcolor="#004640" backleft="5mm" backright="5mm" backtop="5mm" backbottom="5mm" >
    <table style="width: 99%;" border="0">
        <tr>
            <td style="width: 20%; height: 98%; background: #004640;">
                <div style="width: 100%; height:35%;border:0px;text-align: center;">
                    <img src="<?php echo getcwd(); ?>/assets/img/certificados/cns2.PNG" alt="Logo" title="Logo" style="width:85%; height:97%;">
                </div>
                <div style="width: 100%; height:35%;border:0px;">
                </div>
                <div style="width: 100%; height:15%; text-align: center; color: white;">

                    <b>DNP - 016 - 2024</b><br>
                    <qrcode value="Hola mundo Nuevo Nuevo" ec="H" style="width: 40mm;"></qrcode><br>
                    DNP@siiplas
                </div>
            </td>
            <td style="width: 80%; height: 98%; background: #FFFFFF; border: 0px;">
                <!--  Cabecera   -->
                <div style="width: 100%; height:15%;border:0px;">
                    <div style="font-size: 38px;font-family: Arial; color: #015045;text-align:center;"><b>CAJA NACIONAL DE SALUD</b></div>
                    <div style="font-size: 30px;font-family: Arial; text-align:center;"><b>OFICINA NACIONAL</b></div>
                    <div style="font-size: 18px;font-family: Arial; text-align:center;">DEPARTAMENTO NACIONAL DE PLANIFICACION</div>
                </div>
                <!--  End Cabecera   -->
                
                <!--  Cuerpo   -->
                <div style="width: 100%; height:65%;border:0px;">
                    <table style="width: 100%; height:20%;border:0px;">
                        <tr><td style="width: 31%; text-align:right;font-size: 17px;font-family: Arial;">Confiere el Presente:</td>
                            <td style="width: 69%;"></td>
                        </tr>
                    </table>
                    <br>
                    <div style="width: 100%; height:15%;border:0px; text-align: center;">
                        <img src="<?php echo getcwd(); ?>/assets/img/certificados/cert2.PNG" alt="Logo" title="Logo" style="width:75%; height:95%;">
                    </div>
                    <br>

                    <table style="width: 100%; height:10%;border:0px;">
                        <tr><td style="width: 14%; text-align:right;font-size: 18px;font-family: Arial;">A :</td>
                            <td style="width: 8%;"></td>
                            <td style="width: 73%;font-size: 21px;font-family: Arial;"><b>__________________________________</b></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%; height:10%;border:0px;" border="0">
                        <tr><td style="width: 26%; text-align:right;font-size: 18px;font-family: Arial;">En calidad de :</td>
                            <td style="width: 15%;"></td>
                            <td style="width: 53%;font-size: 28px;font-family: Arial;"><b>ASISTENTE</b></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%; height:20%;text-align: justify;" border="0">
                        <tr><td style="width: 10%;"></td>
                            <td style="width: 80%;font-size: 20px;font-family: Arial;">En el ciclo de capacitación sobre: <b>"SISTEMA DE PLANIFICACIÓN, SISTEMA DE ORGANIZACIÓN ADMINISTRATIVA Y PROYECTOS DE INVERSIÓN."</b></td>
                            <td style="width: 10%;"></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%;" border="0">
                        <tr><td style="width: 10%;height:10%;"></td>
                            <td style="width: 80%;font-size: 19px;font-family: Arial;text-align: justify;">Organizado por el Departamento Nacional de Planificacion, realizado los dias: 04,05 y 06 de Noviembre de 2024, con una carga Horaria de: 10 hrs Academicas.</td>
                            <td style="width: 10%;"></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width: 100%;" border="0">
                        <tr><td style="width: 75%;"></td>
                            <td style="width: 25%;font-family: Arial;">La Paz, Diciembre 2024</td>
                        </tr>
                    </table>
                </div>
                <!--  End Cuerpo   -->

                <!--  Pie   -->
                <div style=";width: 100%; height:15%;">
                    <div align="center">
                      <table style="width: 100%;" border="0">
                        <tr>
                            <td style="width: 7%;"></td>
                            <td style="width: 30%;font-family: Arial;"><br><br><br><br>Lic. Luis Rivas Michel<br><b>GERENTE ADMINISTRATIVO<br>FINANCIERO</b></td>
                            <td style="width: 30%;font-family: Arial;"><br><br><br><br>Dr. Fernando R. Morales Martinez<br><b>GERENTE DE SERVICIOS <br>DE SALUD</b></td>
                            <td style="width: 30%;font-family: Arial;"><br><br><br><br>Dr. Rene Luis Delgado<br><b>GERENTE GENERAL</b></td>
                        </tr>
                    </table>  
                    </div>
                    
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



