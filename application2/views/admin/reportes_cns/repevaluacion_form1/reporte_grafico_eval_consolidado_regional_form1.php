<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
 <!-- REPORTE PARA LA GESTION 2021 -->
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
    <style type="text/css">
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
<body>

<div id="content">
<!-- widget grid -->
  <section id="widget-grid" class="">
      <div class="row">
          <article class="col-sm-12">
              <!-- new widget -->
              <div class="jarviswidget" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
                  <header>
                      <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                      <h2>CUADRO DE EVALUACI&Oacute;N A.C.P. </h2>
                      <ul class="nav nav-tabs pull-right in" id="myTab">
                        <li class="active">
                          <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">(%) CUMPLIMIENTO ACP.</span></a>
                        </li>
                        <li>
                          <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">CUADRO DE CUMPLIMIENTO</span></a>
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
                              <div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1" title="CUADRO DE EVALUACI&Oacute;N ">
                                <div id="cabecera" style="display: none"><?php echo $cabecera;?></div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                  <div id="Evaluacion">
                                    <div id="container" style="width: 1000px; height: 750px; margin: 0 auto"></div>
                                  </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                  <?php echo $detalle_eval;?>
                                </div>

                                <?php echo $tabla;?>

                                <div id="detalle_impresion" style="display: none">
                                  <?php echo $detalle_acp;?>
                                </div>

                                <div align="right">
                                  <button id="btnImprimir_evaluacionacp" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="40" HEIGHT="40"/></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                              </div>
                              <!-- end s1 tab pane -->
                              
                              <div class="tab-pane fade" id="s2" title="CUADRO EVALUACIÓN A.C.P.">
                               <hr>
                                  <div class="row">
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
  //// Evaluacion ACP
  function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {

    var ventana = window.open('Evaluacion A.C.P. ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EVALUACION A.C.P.</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(tabla.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.onload = function() {
      ventana.print();
      ventana.close();
    };
    return true;
  }


  document.querySelector("#btnImprimir_evaluacionacp").addEventListener("click", function() {
    var grafico = document.querySelector("#Evaluacion");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    var eficacia = '';
    document.getElementById("detalle_impresion").style.display = 'block';
    var tabla = document.querySelector("#detalle_impresion");
    imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("detalle_impresion").style.display = 'none';
  });
</script>
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
            name: 'A.C.P.',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo ($matriz_pastel[6]+$matriz_pastel[7]);?>%',
                y: <?php echo ($matriz_pastel[6]+$matriz_pastel[7]);?>,
                color: '#f44336',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz_pastel[5];?>%',
                y: <?php echo $matriz_pastel[5];?>,
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
            name: 'A.C.P.',
            data: [
              {
                name: 'NO CUMPLIDO : <?php echo $matriz_pastel[7];?> %',
                y: <?php echo $matriz_pastel[7];?>,
                color: '#f98178',
              },

              {
                name: 'EN PROCESO : <?php echo $matriz_pastel[6];?> %',
                y: <?php echo $matriz_pastel[6];?>,
                color: '#f5eea3',
              },

              {
                name: 'CUMPLIDO : <?php echo $matriz_pastel[5];?> %',
                y: <?php echo $matriz_pastel[5];?>,
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
        text: 'EVALUACIÓN A.C.P. - <?php echo $titulo_graf;?>'
    },
    subtitle: {
        text: 'CUMPLIMIENTO DE ACCIONES DE CORTO PLAZO AL <?php echo $trimestre[0]['trm_descripcion'].' / '.$this->session->userData('gestion');?>'
    },
    xAxis: {
      categories: [
        <?php 
          for ($i=1; $i <=$nro ; $i++){ 
            if($i==$nro-1){
              ?>
              '<?php echo $eval[$i][1];?>',
              <?php
            }
            else{
              ?>
              '<?php echo $eval[$i][1];?>',
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
          text: 'CUMPLIMIENTO (%)',
          align: 'high'
      },
      labels: {
          overflow: 'A.C.P.'
      }
    },
    tooltip: {
        valueSuffix: '%'
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
      name: 'CUMPLIMIENTO %',
      data: [
        <?php 
          for ($i=1; $i <=$nro ; $i++){ 
            if($i==$nro-1){
              ?>
              <?php echo $eval[$i][6];?>,
              <?php
            }
            else{
              ?>
              <?php echo $eval[$i][6];?>,
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