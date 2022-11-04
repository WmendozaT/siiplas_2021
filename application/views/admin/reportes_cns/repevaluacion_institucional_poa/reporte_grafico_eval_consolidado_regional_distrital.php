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
    <style type="text/css">

      table{font-size: 11px;
        width: 100%;
        max-width:1550px;
        overflow-x: scroll;
      }
      th{
        padding: 1.4px;
        text-align: center;
        font-size: 11px;
      }
      td{
        font-size: 10px;
      }
    </style>
<body>

<div id="content">
<!-- widget grid -->
    <section id="widget-grid" class="">
        <div class="">
            
                <div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                    <header>
                        <span class="widget-icon"> <i class="fa fa-comments"></i> </span>
                        <h2>Evaluacion POA</h2>
                    </header>
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->
                        <!-- widget content -->
                        <div class="widget-body">
                            <p>
                              <?php echo $base; ?>
                              <?php echo $titulo;?>
                            </p>
                            <hr class="simple">
                            <ul id="myTab1" class="nav nav-tabs bordered">
                                <li class="active">
                                    <a href="#s1" data-toggle="tab"> Avance de Cumplimiento</a>
                                </li>
                                <li>
                                    <a href="#s2" data-toggle="tab"> Detalle Evaluación POA</a>
                                </li>
                                <li>
                                    <a href="#s3" data-toggle="tab"> Parametros de cumplimiento</a>
                                </li>
                                <li>
                                    <a href="#s4" data-toggle="tab"> Detalle por Programas</a>
                                </li>
                                <li>
                                    <a href="#s5" data-toggle="tab"> Detalle Ejecucion Cert. POA.</a>
                                </li>
                                <li>
                                    <a href="#s6" data-toggle="tab"> Detalle Ejecucion Partidas.</a>
                                </li>
                            </ul>
    
                            <div id="myTabContent1" class="tab-content padding-10">
                                
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                  <div id="eficacia"><?php echo $calificacion;?></div><div id="efi"></div>
                                
                                    <div align="right" title="CAPTURAR PANTALLA">
                                        <button id="btnregresion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/camera.png" WIDTH="25" HEIGHT="25" title="CAPTURA DE PANTALLA"/></button>
                                    </div>
                                  <hr>
                                </div>
                                <div class="tab-pane fade in active" id="s1">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div id="cabecera" style="display: none"><?php echo $cabecera_regresion;?></div>
                                            <div id="regresion_canvas">
                                                <div id="regresion_canvasjs" style="width: 750px; height: 390px; margin: 0 auto"></div>
                                            </div>
                                            <hr>
                                            <div id="evaluacion_trimestre" style="display: none">
                                                <div id="regresion" style="width: 600px; height: 390px; margin: 0 auto"></div>
                                            </div>
                                            <hr>
                                            <div class="table-responsive" id="tabla_regresion_vista">
                                                <?php echo $tabla_regresion;?>
                                            </div>
                                            <div id="tabla_regresion_impresion" style="display: none">
                                                <?php echo $tabla_regresion_impresion;?>
                                            </div>
                                            <hr>
                                            <div align="right">
                                                <button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="25" HEIGHT="25" title="IMPRIMIR GRAFICO"/></button>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div id="cabecera2" style="display: none"><?php echo $cabecera_regresion_total;?></div>
                                            <div id="regresion_gestion_canvas">
                                                <center><div id="regresion_gestion_canvasjs" style="width: 750px; height: 390px; margin: 0 auto"></div></center>
                                            </div>
                                            <hr>
                                            <div id="evaluacion_gestion" style="display: none">
                                              <div id="regresion_gestion" style="width: 700px; height: 400px; margin: 0 auto"></div>
                                            </div>
                                            <hr>
                                            <div class="table-responsive" id="tabla_regresion_total_vista">
                                                <?php echo $tabla_regresion_total;?>
                                            </div>
                                            <div id="tabla_regresion_total_impresion" style="display: none">
                                                <?php echo $tabla_regresion_total_impresion;?>
                                            </div>
                                          <hr>
                                            <div align="right">
                                                <button id="btnImprimir_evaluacion_gestion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="tab-pane fade" id="s2">
                                    <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                      <div id="cabecera1" style="display: none"><?php echo $cabecera_pastel;?></div>

                                        <div id="pastel_canvas" align="center">
                                            <center><div id="pastel_canvasjs" style="width: 500px; height: 420px; margin: 0 auto" ></div></center>
                                        </div>
                                        
                                        <div id="evaluacion_pastel" style="display: none">
                                          <div id="pastel_todosprint" style="width: 600px; height: 420px; margin: 0 auto"></div>
                                        </div>
                                        <hr>
                                        <div class="table-responsive" id="tabla_pastel_vista">
                                            <?php echo $tabla_pastel_todo;?>
                                        </div>
                                        <div id="tabla_pastel_impresion" style="display: none">
                                            <?php echo $tabla_pastel_todo_impresion;?>
                                        </div>
                                        <hr>
                                        <div align="right">
                                            <button id="btnImprimir_evaluacion_pastel" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="25" HEIGHT="25"/></button>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                               <div class="tab-pane fade" id="s3">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <?php echo $parametro_eficacia;?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div align="left" id="boton_eficacia">
                                                <a href="#" class="btn btn-default eficacia_unidad" title="CUADRO DE CUMPLIMIENTO" style="width:40%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;<?php echo $boton1;?></a>
                                             </div>
                            
                                            <div class="row">
                                              <div id="lista"></div>
                                            </div>

                                            <div align="right" id="print_eficacia" style="display: none">
                                              <?php echo $boton_parametros_unidad;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s4">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div align="left" id="boton_eficacia_prog">
                                              <a href="#" class="btn btn-default eficacia_prog" title="CUADRO DE EFICIENCIA Y EFICACIA" style="width:40%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;<?php echo $boton2;?></a>
                                            </div>
                                            <div id="lista_prog"></div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                                            <div id="parametros_prog"></div>
                                            <div align="right" id="print_eficacia_prog" style="display: none">
                                              <?php echo $boton_parametros_prog;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="s5">
                                    
                                    <div align="left" id="boton_ejec_certpoa">
                                        <a href="#" class="btn btn-default ejecucion_certpoa" title="CUADRO DE EJECUCION DE CERTIFICACION POA" style="width:25%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;EJECUCIÓN DE CERTIFICACIÓN POA</a>
                                    </div>
                                    <div id="lista_certpoa"></div>
                                   
                                </div>

                                <div class="tab-pane fade" id="s6">
                                    
                                    <div align="left" id="boton_ejec_partidas">
                                        <a href="#" class="btn btn-default ejecucion_partidas" title="CUADRO DE EJECUCION POR PARTIDAS" style="width:25%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;EJECUCIÓN POR PARTIDAS</a>
                                    </div>
                                    <div id="lista_partidas"></div>
                                   
                                </div>
                            </div>
    
                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
           

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

<script src="<?php echo base_url(); ?>assets/captura/html2canvas.min.js"></script>
<script src="<?php echo base_url(); ?>assets/captura/canvasjs.min.js"></script>
<script src="<?php echo base_url(); ?>assets/captura/jsPdf.debug.js"></script>
<script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/reporte_evaluacionpoa.js"></script> 

     
 <!-- REGRESION LINEAL A LA GESTIÓN -->
  <script type="text/javascript">
    var chart1;
    $(document).ready(function() {
      chart1 = new Highcharts.Chart({
        chart: {
          renderTo: 'regresion_gestion',
          defaultSeriesType: 'line'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        xAxis: {
                  categories: ['<?php echo $tabla_gestion[1][0];?>', '<?php echo $tabla_gestion[1][1];?>', '<?php echo $tabla_gestion[1][2];?>', '<?php echo $tabla_gestion[1][3];?>', '<?php echo $tabla_gestion[1][4];?>']
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
                  name: '% ACT. PROGRAMADAS',
                  data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
              },
              {
                  name: '% ACT. CUMPLIDAS',
                  data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
              }
          ]
      });
    });
  </script>
<script>
window.onload = function () {
/// Grafico Pastel 
    var chart1 = new CanvasJS.Chart("pastel_canvasjs", {
      exportEnabled: true,
      animationEnabled: true,
      title:{
        text: "EVALUACION POA AL <?php echo $trimestre[0]['trm_descripcion'] ?>/<?php echo $this->session->userData('gestion')?>" 
      },
      legend:{
        cursor: "pointer",
        itemclick: explodePastel
      },
      data: [{
        type: "pie",
        showInLegend: true,
        toolTipContent: "{name}: <strong>{y} %</strong>",
        indexLabel: "{name} - {y} %",
        dataPoints: [
          { y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>, name: "ACT. CUMPLIDAS", color: '#57889c', exploded: true },
          { y: <?php echo $tabla[8][$this->session->userData('trimestre')];?>, name: "ACT. EN PROCESO",color: '#f5e218' },
          { y: <?php echo ($tabla[6][$this->session->userData('trimestre')]-$tabla[8][$this->session->userData('trimestre')]);?>, name: "ACT. NO CUMPLIDAS", color: '#a90329'}
        ]
      }]
    });
    chart1.render();

    /// Grafico Pastel Parametros unidad
    var chart_parametros = new CanvasJS.Chart("pastel_canvasjs_parametros_unidad", {
      exportEnabled: true,
      animationEnabled: true,
      title:{
        text: "PARAMETROS DE CUMPLIMIENTO POA POR UNIDAD" 
      },
      legend:{
        cursor: "pointer",
        itemclick: explodePastel
      },
      data: [{
        type: "pie",
        showInLegend: true,
        toolTipContent: "{name}: <strong>{y} %</strong>",
        indexLabel: "{name} - {y} %",
        dataPoints: [
          { y: <?php echo round((($matriz_parametro_unidad[4][2]/($matriz_parametro_unidad[4][2]+$matriz_parametro_unidad[3][2]+$matriz_parametro_unidad[2][2]+$matriz_parametro_unidad[1][2]))*100),2);?>, name: "OPTIMO", color: '#73a773', exploded: true },
          { y: <?php echo round((($matriz_parametro_unidad[3][2]/($matriz_parametro_unidad[4][2]+$matriz_parametro_unidad[3][2]+$matriz_parametro_unidad[2][2]+$matriz_parametro_unidad[1][2]))*100),2);?>, name: "BUENO",color: '#57889c' },
          { y: <?php echo round((($matriz_parametro_unidad[2][2]/($matriz_parametro_unidad[4][2]+$matriz_parametro_unidad[3][2]+$matriz_parametro_unidad[2][2]+$matriz_parametro_unidad[1][2]))*100),2);?>, name: "REGULAR",color: '#f5e218' },
          { y: <?php echo round((($matriz_parametro_unidad[1][2]/($matriz_parametro_unidad[4][2]+$matriz_parametro_unidad[3][2]+$matriz_parametro_unidad[2][2]+$matriz_parametro_unidad[1][2]))*100),2);?>, name: "INSATISFACTORIO",color: '#a90329' },
        ]
      }]
    });
    chart_parametros.render();

/// Regresion al Trimestre Vigente
var chart = new CanvasJS.Chart("regresion_canvasjs", {
    animationEnabled: true,
    exportEnabled: true,
    title:{
        text: "EVALUACION POA ACUMULADO AL <?php echo $trimestre[0]['trm_descripcion'] ?>/<?php echo $this->session->userData('gestion')?>"             
    }, 
    axisY:{
        title: "Nro. de Act. Programadas y Cumplidas"
    },
    toolTip: {
        shared: true
    },
    legend:{
        cursor:"pointer",
        itemclick: toggleDataSeries
    },
    data: [{        
        type: "area",  
        name: "N° ACT. PROGRAMADAS",        
        showInLegend: true,
        dataPoints: [
        <?php 
          if($this->session->userdata('trimestre')==1){ ?>
            { label: "-", y: 0},     
            { label: "1er. Trimestre", y: <?php echo $tabla[2][1];?>,indexLabel: "<?php echo $tabla[2][1];?> Act.", markerType: "square",  markerColor: "blue"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==2) { ?>
            { label: "-", y: 0},     
            { label: "1er. Trimestre", y: <?php echo $tabla[2][1];?>,indexLabel: "<?php echo $tabla[2][1];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[2][2];?>,indexLabel: "<?php echo $tabla[2][2];?> Act.", markerType: "square",  markerColor: "blue"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==3) { ?>
            { label: "-", y: 0},     
            { label: "1er. Trimestre", y: <?php echo $tabla[2][1];?>,indexLabel: "<?php echo $tabla[2][1];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[2][2];?>,indexLabel: "<?php echo $tabla[2][2];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "3er. Trimestre", y: <?php echo $tabla[2][3];?>,indexLabel: "<?php echo $tabla[2][3];?> Act.", markerType: "square",  markerColor: "blue"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==4) { ?>
            { label: "-", y: 0},   
            { label: "1er. Trimestre", y: <?php echo $tabla[2][1];?>,indexLabel: "<?php echo $tabla[2][1];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[2][2];?>,indexLabel: "<?php echo $tabla[2][2];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "3er. Trimestre", y: <?php echo $tabla[2][3];?>,indexLabel: "<?php echo $tabla[2][3];?> Act.", markerType: "square",  markerColor: "blue"},     
            { label: "4to. Trimestre", y: <?php echo $tabla[2][4];?>,indexLabel: "<?php echo $tabla[2][4];?> Act.", markerType: "square",  markerColor: "blue"}
              <?php
          }
        ?>
        ]
    }, 
    {        
        type: "area",
        color: "green",
        name: "N° ACT. CUMPLIDAS",        
        showInLegend: true,
        dataPoints: [
        <?php 
          if($this->session->userdata('trimestre')==1){ ?>
            { label: "-", y: 0},  
            { label: "1er. Trimestre", y: <?php echo $tabla[3][1];?>,indexLabel: "<?php echo $tabla[3][1];?> Act.", markerType: "square",  markerColor: "green"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==2) { ?>
            { label: "-", y: 0},  
            { label: "1er. Trimestre", y: <?php echo $tabla[3][1];?>,indexLabel: "<?php echo $tabla[3][1];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[3][2];?>,indexLabel: "<?php echo $tabla[3][2];?> Act.", markerType: "square",  markerColor: "green"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==3) { ?>
            { label: "-", y: 0},  
            { label: "1er. Trimestre", y: <?php echo $tabla[3][1];?>,indexLabel: "<?php echo $tabla[3][1];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[3][2];?>,indexLabel: "<?php echo $tabla[3][2];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "3er. Trimestre", y: <?php echo $tabla[3][3];?>,indexLabel: "<?php echo $tabla[3][3];?> Act.", markerType: "square",  markerColor: "green"}
              <?php
          }
          elseif ($this->session->userdata('trimestre')==4) { ?>
            { label: "-", y: 0},  
            { label: "1er. Trimestre", y: <?php echo $tabla[3][1];?>,indexLabel: "<?php echo $tabla[3][1];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "2do. Trimestre", y: <?php echo $tabla[3][2];?>,indexLabel: "<?php echo $tabla[3][2];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "3er. Trimestre", y: <?php echo $tabla[3][3];?>,indexLabel: "<?php echo $tabla[3][3];?> Act.", markerType: "square",  markerColor: "green"},     
            { label: "4to. Trimestre", y: <?php echo $tabla[3][4];?>,indexLabel: "<?php echo $tabla[3][4];?> Act.", markerType: "square",  markerColor: "green"}
              <?php
          }
        ?>
        ]
    }]
});

chart.render();

/// Regresion a la Gestion 
var chart_gestion = new CanvasJS.Chart("regresion_gestion_canvasjs", {
    animationEnabled: true,
    exportEnabled: true,
    title:{
        text: "EVALUACION POA - GESTION <?php echo $this->session->userData('gestion')?>"             
    }, 
    axisY:{
        title: "% de Act. Programadas y Cumplidas"
    },
    toolTip: {
        shared: true
    },
    legend:{
        cursor:"pointer",
        itemclick: toggleDataSeries_gestion
    },
    data: [{        
        type: "area",  
        name: "% ACT. PROGRAMADAS",        
        showInLegend: true,
        dataPoints: [
        {   label: "-", y: 0},   
            { label: "1er. Trimestre", y: <?php echo $tabla_gestion[4][1];?>,indexLabel: "<?php echo $tabla_gestion[4][1];?> %", markerType: "square",  markerColor: "blue"},     
            { label: "2do. Trimestre", y: <?php echo $tabla_gestion[4][2];?>,indexLabel: "<?php echo $tabla_gestion[4][2];?> %", markerType: "square",  markerColor: "blue"},     
            { label: "3er. Trimestre", y: <?php echo $tabla_gestion[4][3];?>,indexLabel: "<?php echo $tabla_gestion[4][3];?> %", markerType: "square",  markerColor: "blue"},     
            { label: "4to. Trimestre", y: <?php echo $tabla_gestion[4][4];?>,indexLabel: "<?php echo $tabla_gestion[4][4];?> %", markerType: "square",  markerColor: "blue"}
        ]
    }, 
    {        
        type: "area",
        color: "green",
        name: "% ACT. CUMPLIDAS",        
        showInLegend: true,
        dataPoints: [
            { label: "-", y: 0},  
            { label: "1er. Trimestre", y: <?php echo $tabla_gestion[5][1];?>,indexLabel: "<?php echo $tabla_gestion[5][1];?> %", markerType: "square",  markerColor: "green"},     
            { label: "2do. Trimestre", y: <?php echo $tabla_gestion[5][2];?>,indexLabel: "<?php echo $tabla_gestion[5][2];?> %", markerType: "square",  markerColor: "green"},     
            { label: "3er. Trimestre", y: <?php echo $tabla_gestion[5][3];?>,indexLabel: "<?php echo $tabla_gestion[5][3];?> %", markerType: "square",  markerColor: "green"},     
            { label: "4to. Trimestre", y: <?php echo $tabla_gestion[5][4];?>,indexLabel: "<?php echo $tabla_gestion[5][4];?> %", markerType: "square",  markerColor: "green"}
        ]
    }]
});

chart_gestion.render();
}



  function explodePastel (e) {
    if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
    } else {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
    }
    e.chart1.render();
  }

  function toggleDataSeries(e) {
    if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) { 
        e.dataSeries.visible = false;
    }
    else {
        e.dataSeries.visible = true;            
    }
    chart.render();
  }

  function toggleDataSeries_gestion(e) {
    if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) { 
        e.dataSeries.visible = false;
    }
    else {
        e.dataSeries.visible = true;            
    }
    chart_gestion.render();
  }
</script>

  <!-- REGRESION LINEAL AL TRIMESTRE -->
  <script type="text/javascript">
    var chart1;
    $(document).ready(function() {
      chart1 = new Highcharts.Chart({
        chart: {
          renderTo: 'regresion',
          defaultSeriesType: 'line'
        },
        title: {
          text: ''
        },
        subtitle: {
          text: ''
        },
        <?php 
          if($this->session->userdata('trimestre')==1){ ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==2) { ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][2];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==3) { ?>
              xAxis: {
                  categories: ['p :<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>']
              },
              <?php
          }
          elseif ($this->session->userdata('trimestre')==4) { ?>
              xAxis: {
                  categories: ['<?php echo $tabla[1][0];?>', '<?php echo $tabla[1][1];?>', '<?php echo $tabla[1][2];?>', '<?php echo $tabla[1][3];?>', '<?php echo $tabla[1][4];?>']
              },
              <?php
          }
        ?>
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

          <?php 
              if($this->session->userdata('trimestre')==1){ ?>
                  series: [
                      {
                        name: 'N° ACT. PROGRAMADAS',
                        data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                      },
                      {
                        name: 'N° ACT. CUMPLIDAS',
                        data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                      }
                  ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==2) { ?>
                      series: [
                          {
                            name: 'N° ACT. PROGRAMADAS',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                          },
                          {
                            name: 'N° ACT. CUMPLIDAS',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                          }
                      ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==3) { ?>
                      series: [
                          {
                            name: 'N° ACT. PROGRAMADAS',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                          },
                          {
                            name: 'N° ACT. CUMPLIDAS',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                          }
                      ]
                  <?php
              }
              elseif ($this->session->userdata('trimestre')==4) { ?>
                      series: [
                          {
                            name: 'N° ACT. PROGRAMADAS',
                            data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                          },
                          {
                            name: 'N° ACT. CUMPLIDAS',
                            data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                          }
                      ]
                  <?php
              }
          ?>
      });
    });
  </script>
</html>