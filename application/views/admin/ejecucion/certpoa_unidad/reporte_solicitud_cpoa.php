<?php
ob_start();
?>
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

<page backtop="145mm" backbottom="49mm" backleft="5mm" backright="5mm" pagegroup="new">
    <page_header>
    <br><div class="verde"></div>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:70%;height: 2%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="font-size: 15px;font-family: Arial;">
                            <td style="width:50%;height: 30%;">&nbsp;&nbsp;<b>CAJA NACIONAL DE SALUD</b></td>
                        </tr>
                        <tr>
                            <td style="width:50%;height: 30%;font-size: 8px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;DEPARTAMENTO NACIONAL DE PLANIFICACIÓN</td>
                        </tr>
                    </table>
                </td>
                <td style="width:30%; height: 2%; font-size: 8px;text-align:center;">
                    SISTEMA DE PROGRAMACIÓN DE OPERACIONES
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px black; text-align: center;">
                <td style="width:20%; text-align:center;">
                </td>
                <td style="width:60%; height: 5%">
                    <table align="center" border="0" style="width:100%;">
                        <tr style="font-size: 23px;font-family: Arial;">
                            <td style="height: 40%;"><b>SOLICITUD DE CERTIFICACI&Oacute;N POA - <?php echo $this->session->userdata('gestion')?></b></td>
                        </tr>
                    </table>
                </td>
                <td style="width:20%; text-align:center;">
                </td>
            </tr>
        </table>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:50%;">
                </td>
                <td style="width:50%; height: 3%">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr style="font-size: 13px;font-family: Arial;">
                            <td colspan="2" style="width:100%;height: 30%;text-align:right;"><b>FORMULARIO CERT. N° 10&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                        </tr>
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="width:50%;height: 30%;"><b>CITE : </b></td>
                            <td style="width:50%;height: 30%"><b>FECHA : </b></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
             <tr>
                <td style="width:1%;"></td>
                <td style="height: 3%;">
                    <div style="width:98%;font-size: 12px; font-family: Arial;">
                    Se solicita al Departamento Nacional de Planificación o Encargados del POA Regional/Distrital, la emisión de la <b>CERTIFICACIÓN POA GESTIÓN <?php echo $this->session->userdata('gestion')?></b>, 
                    de los requerimientos programados a favor de la Unidad, mismos se encuentran articulados a los Objetivos de Gestión y Acción Estrategica detallada a continuación.
                    </div>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>
        
        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>I. UNIDAD ORGANIZACIONAL SOLICITANTE</b></td>
                        </tr>
                    </table><br>
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>REGIONAL / DEPARTAMENTO</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;<?php echo strtoupper ($proyecto[0]['dep_departamento']); ?></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>UNIDAD EJECUTORA</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;<?php echo strtoupper ($proyecto[0]['dist_distrital']); ?></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>ACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr><td style="width:100%;height: 1.5%;">&nbsp;<?php echo $proyecto[0]['aper_actividad'].''.strtoupper ($proyecto[0]['aper_programa']); ?></td></tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:20%;">
                                <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 8px;">
                                    <tr><td style="width:95%;height: 1.5%;" bgcolor="#f0f1f0"><b>SUBACTIVIDAD</b></td><td style="width:5%;"></td></tr>
                                </table>
                            </td>
                            <td style="width:80%;">
                                <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;font-size: 7.5px;">
                                    <tr>
                                        <td style="width:100%;height: 1.5%;">&nbsp;Hola</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>

        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;" bgcolor="#a4cdf1"><b>II. ARTICULACI&Oacute;N POA 2021 Y PEI 2016-2020</b></td>
                        </tr>
                    </table><br>
                    <table border="0.4" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <thead>
                            <tr style="font-size: 8px; font-family: Arial;" align="center" >
                                <th style="width:5%;height: 1.5%;">COD. OPE.</th>
                                <th style="width:30%;">OPERACI&Oacute;N</th>
                                <th style="width:5%;">COD. OR.</th>
                                <th style="width:30%;">OBJETIVO REGIONAL</th>
                                <th style="width:30%;">ACCIÓN ESTRATEGICA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:5%;height: 5%;"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>

        <br>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
            <tr style="border: solid 0px;">              
                <td style="width:1%;"></td>
                <td style="width:98%;">
                    <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;">
                        <tr>
                            <td style="width:100%;height: 2%;font-size: 12px; font-family: Arial;"bgcolor="#a4cdf1"><b>III. DETALLE DE ITEM PARA CERTIFICACIÓN POA DEL FORMULARIO POA N°5</b></td>
                        </tr>
                    </table><br>
                </td>
                <td style="width:1%;"></td>
            </tr>
        </table>

    </page_header>sfsafsdfsdf
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    <page_footer>

    </page_footer>

</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Solicitud_cpoa.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
