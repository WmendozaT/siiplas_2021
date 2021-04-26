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

<page backtop="142mm" backbottom="60mm" backleft="2.6mm" backright="2.6mm" pagegroup="new">
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
                        <tr style="font-size: 10px;font-family: Arial;">
                            <td style="height: 10%;"><b>(DOCUMENTO NO VALIDO PARA PROCESOS DE EJECUCIÓN POA)</b></td>
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
                    <?php echo $datos_cite;?>
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
        <?php echo $datos_unidad_articulacion;?>
    </page_header>

        
    <page_footer>
        <?php echo $conformidad;?>
        <hr>
        <table border="0" cellpadding="0" cellspacing="0" class="tabla" style="width:100%;" align="center">
            <tr style="font-size: 7px;font-family: Arial;">
                <td style="width: 50%; text-align: left;" >
                    &nbsp;&nbsp;<b><?php echo $this->session->userdata('sistema')?></b>
                </td>
                <td style="width: 50%; text-align: right">
                   pag. [[page_cu]]/[[page_nb]]
                </td>
            </tr>
        </table>
        <br>
    </page_footer>
        <?php echo $items;?>
</page>
<?php
$content = ob_get_clean();
//require_once(dirname(__FILE__).'/../html2pdf.class.php');
require_once('assets/html2pdf-4.4.0/html2pdf.class.php');
try{
    
    $html2pdf = new HTML2PDF('P', 'Letter', 'fr', true, 'UTF-8', 0);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
    $html2pdf->Output('Solicitud_Certificacion_Poa-'.$solicitud[0]['tipo_subactividad'].' '.$solicitud[0]['serv_descripcion'].' '.$solicitud[0]['abrev'].'.pdf');
}
catch(HTML2PDF_exception $e) {
    echo $e;
    exit;
}
