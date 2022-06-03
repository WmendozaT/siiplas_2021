<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title><?php echo $this->session->userData('sistema');?></title>
    </head>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<body>
<div id="cabecera" style="display: none">
    <?php echo $cabecera;?>
</div>
<div id="tabla_componente_impresion" style="display: none">
    <?php echo $tabla_detalle;?>
</div>
<div id="Seguimiento">
  <?php echo $calificacion;?>
  <hr>
    <div id="container" style="width: 1000px; height: 680px; margin: 0 auto"></div></div>

</div>
<div align="right">
    <button id="btnImprimir_seguimiento" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO</b></button>
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

<script type="text/javascript">
  //// Seguimiento POA
  function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {

    var ventana = window.open('Seguimiento Evaluacion POA ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EVALUACION POA</title>');
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


  document.querySelector("#btnImprimir_seguimiento").addEventListener("click", function() {
    var grafico = document.querySelector("#Seguimiento");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    var eficacia = '';
    document.getElementById("tabla_componente_impresion").style.display = 'block';
    var tabla = document.querySelector("#tabla_componente_impresion");
    imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_componente_impresion").style.display = 'none';
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
        text: 'CUMPLIMIENTO DE OPERACIONES AL <?php echo $trimestre[0]['trm_descripcion']; ?>'
    },
    xAxis: {
        categories: [
            <?php 
              for ($i=1; $i <=$nro ; $i++){ 
                if($i==$nro){
                  ?>
                  '<?php echo 'OPE. '.$eval[$i][1].'.'.$eval[$i][2];?>'
                  <?php
                }
                else{
                  ?>
                  '<?php echo 'OPE. '.$eval[$i][1].'.'.$eval[$i][2];?>',
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
            text: 'CUMPLIMIENTO DE METAS',
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
        name: 'CUMPLIMIENTO %',
        data: [
            <?php 
              for ($i=1; $i <=$nro ; $i++){ 
                if($i==$nro){
                  ?>
                  <?php echo $eval[$i][5];?>
                  <?php
                }
                else{
                  ?>
                  <?php echo $eval[$i][5];?>,
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