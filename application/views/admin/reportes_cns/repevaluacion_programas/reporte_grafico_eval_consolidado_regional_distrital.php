<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title><?php echo $this->session->userData('sistema');?></title>
    </head>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-production.min.css"> 
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-skins.min.css">
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/demo.min.css">
    <!--estiloh-->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
    <script type="text/javascript">
      function abreVentana_eficiencia(PDF){             
          var direccion;
          direccion = '' + PDF;
          window.open(direccion, "Cuadro Eficiencia-Eficacia por Unidad" , "width=700,height=600,scrollbars=NO") ; 
      }
    </script>
    <style type="text/css">
      #areaImprimir_programas{display:none}
      @media print {
        #areaImprimir_programas {display:block}
      }

      table{font-size: 9.5px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
      }
      th{
        padding: 1.4px;
        text-align: center;
        font-size: 9.5px;
      }
    </style>
    <script type="text/javascript">
        function printDiv(nombreDiv) {
            var contenido= document.getElementById(nombreDiv).innerHTML;
            var contenidoOriginal= document.body.innerHTML;
            document.body.innerHTML = contenido;
            window.print();
            document.body.innerHTML = contenidoOriginal;
        }
    </script>
<body>

<div id="content">
<!-- widget grid -->
    <section id="widget-grid" class="">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <section id="widget-grid" class="well">
            <div class="">
              <?php echo $titulo;?>
            </div>
          </section>
        </article>
        <div class="row">
            <article class="col-sm-12">
                <!-- new widget -->
                <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                    <header>
                        <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                        <h2>CUADRO DE EVALUACI&Oacute;N POA POR CATEGORIA PROGRAMÁTICA</h2>
                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N POA</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE EFICACIA</span></a>
                            </li>
                        </ul>
                    </header>

                    <!-- widget div-->
                    <div class="no-padding">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            test
                        </div>
                        <!-- end widget edit box -->
                        <div class="widget-body">
                            <!-- content -->
                            <div id="myTabContent" class="tab-content">
                                <br>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                 <?php echo $calificacion;?>
                                </div>
                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="CUADRO DE EVALUACI&Oacute;N POA">
                                    <!-- <div align="right" id="eval_poa" style="display:none;"> -->
                                    <div align="right" id="eval_poa">
                                      <a href="#" onclick="printDiv('areaImprimir_programas')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA POR CATEGORIA PROGRAMATICA" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div><hr>
                                    <div class="row">
                                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        &nbsp;&nbsp; <b>CUADRO DE EVALUACI&Oacute;N POR CATEGORIA PROGRAM&Aacute;TICA</b><br><br>
                                        <?php echo $tabla_programa;?>
                                      </div>
                                    </div>
                                </div>
                                <!-- end s1 tab pane -->
                                
                                <div class="tab-pane fade" id="s2" title="CUADRO MIS SERVICIOS">
                                  <div class="row">
                                    <br>
                                        <div class="row">
                                          <?php echo $tabla_parametros;?>
                                        </div>
                                        </div>
                                    </div>
                              </div>

                            </div>
                            <!-- end content -->
                        </div>
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
        </div>
    </section>
</div>

<div id="areaImprimir_programas">
    <?php echo $print_evaluacion_programas;?>
</div>

<script>
if (!window.jQuery) {
    document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');
}
</script>
<script>
if (!window.jQuery.ui) {
    document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
}
</script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
 <script type="text/javascript">
    $(document).ready(function() {  
       Highcharts.chart('parametro_efi', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'PARAMETRO DE EFICACIA AL <?php echo $trimestre[0]['trm_descripcion'];?> POR APERTURA PROGRAMATICA <?php echo $this->session->userData('gestion');?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Unidades',
            data: [
                {
                  name: 'INSATISFACTORIO : <?php echo $matriz[1][3];?> %',
                  y: <?php echo $matriz[1][3];?>,
                  color: '#f95b4f',
                },

                {
                  name: 'REGULAR : <?php echo $matriz[2][3];?> %',
                  y: <?php echo $matriz[2][3];?>,
                  color: '#edd094',
                },

                {
                 name: 'BUENO : <?php echo $matriz[3][3];?> %',
                  y: <?php echo $matriz[3][3];?>,
                  color: '#afd5e5',
                },

                {
                  name: 'OPTIMO : <?php echo $matriz[4][3];?> %',
                  y: <?php echo $matriz[4][3];?>,
                  color: '#4caf50',
                  sliced: true,
                  selected: true
                }
            ]
        }]
      });
    });

    $(document).ready(function() {  
       Highcharts.chart('parametro_efi_print', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: 'PARAMETRO DE EFICACIA AL <?php echo $trimestre[0]['trm_descripcion'];?>'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                depth: 35,
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Unidades',
            data: [
                {
                  name: 'INSATISFACTORIO : <?php echo $matriz[1][3];?> %',
                  y: <?php echo $matriz[1][3];?>,
                  color: '#f95b4f',
                },

                {
                  name: 'REGULAR : <?php echo $matriz[2][3];?> %',
                  y: <?php echo $matriz[2][3];?>,
                  color: '#edd094',
                },

                {
                 name: 'BUENO : <?php echo $matriz[3][3];?> %',
                  y: <?php echo $matriz[3][3];?>,
                  color: '#afd5e5',
                },

                {
                  name: 'OPTIMO : <?php echo $matriz[4][3];?> %',
                  y: <?php echo $matriz[4][3];?>,
                  color: '#4caf50',
                  sliced: true,
                  selected: true
                }
            ]
        }]
      });
    });
</script>
</body>
</html>