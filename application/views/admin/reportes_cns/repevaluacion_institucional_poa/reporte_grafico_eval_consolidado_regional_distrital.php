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
        <?php echo $formulario; ?>
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
          renderTo: 'regresion_gestionn',
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
          { y: <?php echo $tabla[8][$this->session->userData('trimestre')];?>, name: "ACT. CUMPLIDAS PARCIALMENTE",color: '#f5e218' },
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