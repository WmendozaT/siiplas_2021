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
        #areaImprimir{display:none}
        @media print {
            #areaImprimir {display:block}
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
                        <h2>CUADRO DE EVALUACI&Oacute;N POA - PROYECTO DE INVERSI&Oacute;N</h2>
                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N POA</span></a>
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
                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="EFICACIA INSTITUCIONAL A NIVEL UNIDAD">
                                    <hr>
                                        <div align="right">
                                            <a href="#" onclick="printDiv('areaImprimir')" title="IMPRIMIR CUADRO DE EFICACIA DE UNIDAD" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;
                                        </div>
                                    <hr>

                                    <?php echo $calificacion;?>
                                
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                        <div class="row" align="center">
                                            <div id="regresion_lineal_pi" style="width: 1400px; height: 480px; margin: 2 auto"></div>
                                        </div>
                                    </div>
                                        <br>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php echo $tabla_acumulado;?>
                                    </div>
                               
                                </div>
                                <!-- end s1 tab pane -->
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

<div id="areaImprimir">
  <?php echo $print_tabla;?>
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
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion_lineal_pi',
            defaultSeriesType: 'line'
          },
          title: {
            text: 'CUADRO DE EVALUACIÓN POA <?php echo $this->session->userdata('gestion') ?>'
          },
          subtitle: {
            text: '<?php echo $title_graf;?>'
          },
          xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
            },
          yAxis: {
            title: {
              text: 'Promedio (%)'
            }
          },
          tooltip: {
            enabled: false,
            formatter: function() {
              return '<b>'+ this.series.name +'</b><br/>'+
                this.x +': '+ this.y +'%';
            }
          },
          plotOptions: {
            line: {
              dataLabels: {
                enabled: true
              },
              enableMouseTracking: false
            }
          },
          series: [
                {
                    name: 'PROGRAMACIÓN ACUMULADA EN %',
                    data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                },
                {
                    name: 'EJECUCIÓN ACUMULADA EN %',
                    data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                }
            ]
        });
      });
    </script>
    <script type="text/javascript">
      var chart1;
      $(document).ready(function() {
        chart1 = new Highcharts.Chart({
          chart: {
            renderTo: 'regresion_lineal_pi_print',
            defaultSeriesType: 'line'
          },
          title: {
            text: 'CUADRO DE EVALUACIÓN POA <?php echo $this->session->userdata('gestion') ?>'
          },
          subtitle: {
            text: '<?php echo $title_graf;?>'
          },
          xAxis: {
                    categories: ['ENE.', 'FEB.', 'MAR.', 'ABR.', 'MAY.', 'JUN.', 'JUL.', 'AGO.', 'SEPT.', 'OCT.', 'NOV.', 'DIC.']
            },
          yAxis: {
            title: {
              text: 'Promedio (%)'
            }
          },
          tooltip: {
            enabled: false,
            formatter: function() {
              return '<b>'+ this.series.name +'</b><br/>'+
                this.x +': '+ this.y +'%';
            }
          },
          plotOptions: {
            line: {
              dataLabels: {
                enabled: true
              },
              enableMouseTracking: false
            }
          },
          series: [
                {
                    name: 'PROGRAMACIÓN ACUMULADA EN %',
                    data: [ <?php echo $tabla[1][1];?>, <?php echo $tabla[1][2];?>, <?php echo $tabla[1][3];?>, <?php echo $tabla[1][4];?>, <?php echo $tabla[1][5];?>, <?php echo $tabla[1][6];?>, <?php echo $tabla[1][7];?>, <?php echo $tabla[1][8];?>, <?php echo $tabla[1][9];?>, <?php echo $tabla[1][10];?>, <?php echo $tabla[1][11];?>, <?php echo $tabla[1][12];?>]
                },
                {
                    name: 'EJECUCIÓN ACUMULADA EN %',
                    data: [ <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>, <?php echo $tabla[2][5];?>, <?php echo $tabla[2][6];?>, <?php echo $tabla[2][7];?>, <?php echo $tabla[2][8];?>, <?php echo $tabla[2][9];?>, <?php echo $tabla[2][10];?>, <?php echo $tabla[2][11];?>, <?php echo $tabla[2][12];?>]
                }
            ]
        });
      });
    </script> 
</body>
</html>