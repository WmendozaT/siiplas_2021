<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
      <title><?php echo $this->session->userData('sistema');?></title>
    </head>
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <style type="text/css">
        #areaImprimir{display:none}
        @media print {
            #areaImprimir {display:block}
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

<table border="0" style="width:100%;">
    <tr>
        <td>
            <br>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="printDiv('areaImprimir')" title="IMPRIMIR CUADRO EVALUACIÓN" class="btn btn-default xs"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="18"/>&nbsp;&nbsp;<b>IMPRIMIR CUADRO DE EVALUACI&Oacute;N</b></a><br>
        </td>
    </tr>
    <tr>
        <td><div id="container" style="width: 1000px; height: 600px; margin: 0 auto"></div></td>
    </tr>
</table>


<div id="areaImprimir">
    <script>alert('GENERAR CUADRO DE EVALUACIÓN ..')</script>
    <?php echo $print_evaluacion;?>
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
              for ($i=1; $i <=$nro-1 ; $i++){ 
                if($i==$nro-1){
                  ?>
                  '<?php echo $eval[$i][3];?>'
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
              for ($i=1; $i <=$nro-1 ; $i++){ 
                if($i==$nro-1){
                  ?>
                  <?php echo $eval[$i][10];?>
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
        text: '<?php echo 'REGIONAL : '.strtoupper($regional[0]['dep_departamento']);?>'
    },
    subtitle: {
        text: 'OBJETIVOS <?php echo $trimestre[0]['trm_descripcion']; ?>'
    },
    xAxis: {
        categories: [
            <?php 
              for ($i=1; $i <=$nro-1 ; $i++){ 
                if($i==$nro-1){
                  ?>
                  '<?php echo $eval[$i][3];?>'
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
              for ($i=1; $i <=$nro-1 ; $i++){ 
                if($i==$nro-1){
                  ?>
                  <?php echo $eval[$i][10];?>
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