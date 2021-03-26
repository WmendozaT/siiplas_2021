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
          window.open(direccion, "Cuadro " , "width=700,height=600,scrollbars=NO") ; 
      }
    </script>
    <style type="text/css">
      #areaImprimir_objetivos{display:none}
      @media print {
        #areaImprimir_objetivos {display:block}
      }

      #areaImprimir_gcumplimiento {display:none}
      @media print {
        #areaImprimir_gcumplimiento {display:block}
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
                        <h2>CUADRO DE EVALUACI&Oacute;N OBJETIVOS </h2>
                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N POR OBJETIVOS</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE CUMPLIMIENTO</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO 2</span></a>
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
                                  TITULO
                                </div>
                                <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="CUADRO DE EVALUACI&Oacute;N ">
                                    <div align="right">
                                      <a href="#" onclick="printDiv('areaImprimir_objetivos')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N " class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N OBJETIVOS</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    <hr>
                                    <table border="0" style="width:100%;">
                                        <tr>
                                            <td><div id="container" style="width: 1000px; height: 600px; margin: 0 auto"></div></td>
                                        </tr>
                                    </table>
                                    <?php echo $detalle;?>
                                </div>
                                <!-- end s1 tab pane -->
                                
                                <div class="tab-pane fade" id="s2" title="CUADRO EVALUACIÓN DE OPERACIONES - DISTRITAL">
                                    <hr>
                                    <div class="row">
                                      <div align="right">
                                        <a href="#" onclick="printDiv('areaImprimir_gcumplimiento')" title="IMPRIMIR CUADRO GRADO DE CUMPLIMIENTO" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE CUMPLIMIENTO</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                      </div>
                                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="change_order_items" border=1>
                                              <tr>
                                                <td>
                                                <div id="pastel" style="width: 600px; height: 350px; margin: 0 auto"></div>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                  <?php echo $tabla_pastel;?>
                                                </td>
                                              </tr>
                                            </table>
                                          </div>
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="change_order_items" border=1>
                                            <tr>
                                              <td>
                                                  <div id="pastel_todos" style="width: 600px; height: 350px; margin: 0 auto"></div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td>
                                              <div class="table-responsive">
                                                <?php echo $tabla_pastel_todo;?>
                                              </div>
                                              </td>
                                            </tr>
                                            </table>
                                          </div>
                                      </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s3" title="CUADRO PARAMETRO DE EFICACIA - DISTRITAL">
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    CUADRO 3
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

<div id="areaImprimir_objetivos">
    <?php echo $print_objetivos;?>
</div>

<div id="areaImprimir_gcumplimiento">
    <?php echo $print_gcumplimiento;?>
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
       Highcharts.chart('pastel', {
        chart: {
            type: 'pie',
            options3d: {
              enabled: true,
              alpha: 45,
              beta: 0
            }
        },
        title: {
            text: '<?php echo 'EVALUACIÓN '.$trimestre[0]['trm_descripcion'];?>'
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
            name: 'Objetivos',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo ($matriz[6]+$matriz[7]);?>%',
                y: <?php echo ($matriz[6]+$matriz[7]);?>,
                color: '#f44336',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz[5];?>%',
                y: <?php echo $matriz[5];?>,
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
            ]
        }]
      });
    });

    $(document).ready(function() {  
       Highcharts.chart('pastel_todos', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '<?php echo 'EVALUACIÓN '.$trimestre[0]['trm_descripcion'];?>'
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
            name: 'Objetivos',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo $matriz[7];?> %',
                y: <?php echo $matriz[7];?>,
                color: '#f98178',
              },

              {
                name: 'EN PROCESO : <?php echo $matriz[6];?> %',
                y: <?php echo $matriz[6];?>,
                color: '#f5eea3',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz[5];?> %',
                y: <?php echo $matriz[5];?>,
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
            ]
        }]
      });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {  
       Highcharts.chart('pastel_print', {
        chart: {
            type: 'pie',
            options3d: {
              enabled: true,
              alpha: 45,
              beta: 0
            }
        },
        title: {
            text: '<?php echo 'EVALUACIÓN '.$trimestre[0]['trm_descripcion'];?>'
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
            name: 'Objetivos',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo ($matriz[6]+$matriz[7]);?>%',
                y: <?php echo ($matriz[6]+$matriz[7]);?>,
                color: '#f44336',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz[5];?>%',
                y: <?php echo $matriz[5];?>,
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
            ]
        }]
      });
    });

    $(document).ready(function() {  
       Highcharts.chart('pastel_todos_print', {
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45,
                beta: 0
            }
        },
        title: {
            text: '<?php echo 'EVALUACIÓN '.$trimestre[0]['trm_descripcion'];?>'
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
            name: 'Objetivos',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo $matriz[7];?> %',
                y: <?php echo $matriz[7];?>,
                color: '#f98178',
              },

              {
                name: 'EN PROCESO : <?php echo $matriz[6];?> %',
                y: <?php echo $matriz[6];?>,
                color: '#f5eea3',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz[5];?> %',
                y: <?php echo $matriz[5];?>,
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
            ]
        }]
      });
    });
</script>

<script type="text/javascript">
Highcharts.chart('container', {
    chart: {
        type: 'bar'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: ''
    },
    xAxis: {
      categories: [
        <?php 
          for ($i=1; $i <=$nro ; $i++){ 
            if($i==$nro-1){
              ?>
              '<?php echo $eval[$i][3];?>',
              <?php
            }
            else{
              ?>
              '<?php echo $eval[$i][3];?>',
              <?php
            }
          } 
        ?>
      ],
      title: {
          text: null
      }
    },
    yAxis: {
      min: 0,
      title: {
          text: 'Eficacia (%)',
          align: 'high'
      },
      labels: {
          overflow: 'Objetivos'
      }
    },
    tooltip: {
        valueSuffix: ' %'
    },
    plotOptions: {
      bar: {
          dataLabels: {
              enabled: true
          }
      }
    },

    credits: {
        enabled: false
    },

    series: [{
      name: 'EFICACIA %',
      data: [
        <?php 
          for ($i=1; $i <=$nro ; $i++){ 
            if($i==$nro-1){
              ?>
              <?php echo $eval[$i][10];?>,
              <?php
            }
            else{
              ?>
              <?php echo $eval[$i][10];?>,
              <?php
            }
          } 
        ?>
      ]
    }]
});

Highcharts.chart('container_print', {
    chart: {
        type: 'bar'
    },
    title: {
        text: '<?php echo $tipo_regional;?>'
    },
    subtitle: {
        text: 'OBJETIVOS <?php echo $trimestre[0]['trm_descripcion']; ?>'
    },
    xAxis: {
      categories: [
        <?php 
          for ($i=1; $i <=$nro ; $i++){ 
            if($i==$nro-1){
              ?>
              '<?php echo $eval[$i][3];?>',
              <?php
            }
            else{
              ?>
              '<?php echo $eval[$i][3];?>',
              <?php
            }
          } 
        ?>
      ],
      title: {
          text: null
      }
    },
    yAxis: {
      min: 0,
      title: {
          text: 'Eficacia (%)',
          align: 'high'
      },
      labels: {
          overflow: 'Operaciones'
      }
    },
    tooltip: {
        valueSuffix: ' %'
    },
    plotOptions: {
      bar: {
          dataLabels: {
              enabled: true
          }
      }
    },

    credits: {
        enabled: false
    },

    series: [{
      name: 'EFICACIA %',
      data: [
          <?php 
            for ($i=1; $i <=$nro ; $i++){ 
              if($i==$nro-1){
                ?>
                <?php echo $eval[$i][10];?>,
                <?php
              }
              else{
                ?>
                <?php echo $eval[$i][10];?>,
                <?php
              }
            } 
          ?>
      ]
    }]
});
</script>
</body>
</html>