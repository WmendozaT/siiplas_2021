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
      #areaImprimir_eval{display:none}
      @media print {
        #areaImprimir_eval {display:block}
      }

      #areaImprimir_parametros {display:none}
      @media print {
        #areaImprimir_parametros {display:block}
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
                        <h2>CUADRO DE EVALUACI&Oacute;N POA </h2>
                        <ul class="nav nav-tabs pull-right in" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">EVALUACI&Oacute;N POA</span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s2"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet"><?php echo $titulo_indicador;?></span></a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#s3"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">PARAMETROS DE EFICACIA</span></a>
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
                                    <div align="right" id="eval_poa" style="display:none;">
                                      <a href="#" onclick="printDiv('areaImprimir_eval')" title="IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="change_order_items" border=1>
                                            <tr>
                                              <td>
                                                 <div id="regresion" style="width: 600px; height: 350px; margin: 0 auto"></div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td>
                                              <div class="table-responsive">
                                                  <?php echo $tabla_regresion;?>
                                              </div>
                                              </td>
                                            </tr>
                                            </table>
                                          </div>
                                          <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <table class="change_order_items" border=1>
                                            <tr>
                                              <td>
                                                 <div id="regresion_gestion" style="width: 600px; height: 350; margin: 0 auto"></div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td>
                                              <div class="table-responsive">
                                                  <?php echo $tabla_regresion_total;?>
                                              </div>
                                              </td>
                                            </tr>
                                            </table>
                                          </div>
                                        </div>
                                        </div>
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
                                <!-- end s1 tab pane -->
                                
                                <div class="tab-pane fade" id="s2" title="CUADRO EVALUACIÓN DE OPERACIONES - DISTRITAL">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      
                                        <div align="left" id="boton_eficacia">
                                          <a href="#" class="btn btn-default eficacia" name="<?php echo $id;?>" id="<?php echo $tp;?>" title="CUADRO DE EFICIENCIA Y EFICACIA" style="width:35%;"> <img src="<?php echo base_url(); ?>assets/Iconos/application.png" WIDTH="20" HEIGHT="20"/>&nbsp;<?php echo $boton;?></a>
                                        </div>

                                        <div align="right" id="print_eficacia" style="display: none">
                                          <a href="javascript:abreVentana_eficiencia('<?php echo site_url("").'/rep_eval_poa/rep_eficacia/'.$tp.'/'.$id.'' ?>');" class="btn btn-default" title="IMPRIMIR REPORTE DE MODIFICACION FINANCIERA"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR CUADRO DE INDICADOR</a>
                                        </div>
                                    <br>

                                        <div class="row">
                                          <div id="content1"></div>
                                        </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade" id="s3" title="CUADRO PARAMETRO DE EFICACIA - DISTRITAL">
                                  <div align="right" id="par" style="display:none;">
                                    <a href="#" onclick="printDiv('areaImprimir_parametros')" title="IMPRIMIR CUADRO PARAMETROS" class="btn btn-default"><img src="<?php echo base_url(); ?>assets/Iconos/printer.png" WIDTH="20" HEIGHT="20"/>&nbsp;&nbsp;IMPRIMIR PARAMETROS DE EFICACIA</a>&nbsp;&nbsp;&nbsp;&nbsp;
                                  </div>
                                  <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <?php echo $parametro_eficacia;?>
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

<div id="areaImprimir_eval">
    <?php echo $print_evaluacion;?>
</div>
<div id="areaImprimir_parametros">
    <?php echo $print_parametros;?>
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
    /*---- CUADRO EFICACIA POR UNIDAD-REGIONAL ----*/
    $(function () {
        $(".eficacia").on("click", function (e) {
            tp = $(this).attr('id');
            id = $(this).attr('name');

            $('#content1').html('<div class="loading" align="center"><br><img src="<?php echo base_url() ?>/assets/img_v1.1/load.gif" alt="loading" style="width:30%;"/><br/><b>CARGANDO DATOS DE LAS UNIDADES ORGANIZACIONALES ...</b></div>');
            var url = "<?php echo site_url("")?>/reporte_evaluacion/crep_evalinstitucional/get_unidades_eficiencia";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id="+id+"&tp="+tp
            });

            request.done(function (response, textStatus, jqXHR) {

            if (response.respuesta == 'correcto') {
                $('#content1').fadeIn(1000).html(response.tabla);
                $('#boton_eficacia').slideUp();
                $('#print_eficacia').slideDown();
                $('#eval_poa').slideDown();
                $('#par').slideDown();
            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS DE EVALUACIÓN POA ");
            }

            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
          
        });
    });
</script>
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
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
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
                        name: '% PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% CUMPLIDAS',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });

        /*--- Regresion Lineal Impresion ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_gestion_print',
                defaultSeriesType: 'line'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA GESTIÓN '.$this->session->userData('gestion') ;?>'
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
                        name: '% PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% CUMPLIDAS',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });
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
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$trimestre[0]['trm_descripcion'] ;?>'
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
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                                }
                            ]
                        <?php
                    }
                ?>
            });
          });

        /*---  REGRESION LINEAL AL TRIMESTRE IMPRESION  ---*/
        var chart1;
          $(document).ready(function() {
            chart1 = new Highcharts.Chart({
              chart: {
                renderTo: 'regresion_impresion',
                defaultSeriesType: 'line'
              },
              title: {
                text: ''
              },
              subtitle: {
                text: '<?php echo 'EVALUACIÓN POA '.$this->session->userData('gestion').' AL '.$trimestre[0]['trm_descripcion'] ;?>'
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
                                name: 'ACTIVIDADES PROGRAMADAS',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'ACTIVIDADES CUMPLIDAS',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'ACTIVIDADES PROGRAMADAS',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'ACTIVIDADES CUMPLIDAS',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>, <?php echo $tabla[3][4];?>]
                                }
                            ]
                        <?php
                    }
                ?>
            });
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
                    name: 'Operaciones',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo $tabla[6][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f44336',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?>%',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
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
                    name: 'Actividades',
                    data: [
                        {
                          name: 'NO CUMPLIDO : <?php echo (100-($tabla[5][$this->session->userData('trimestre')]+round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2)));?> %',
                          y: <?php echo $tabla[6][$this->session->userData('trimestre')];?>,
                          color: '#f98178',
                        },

                        {
                          name: 'EN PROCESO : <?php echo round((($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100),2);?> %',
                          y: <?php echo round(($tabla[7][$this->session->userData('trimestre')]/$tabla[2][$this->session->userData('trimestre')])*100,2);?>,
                          color: '#f5eea3',
                        },

                        {
                          name: 'CUMPLIDO : <?php echo $tabla[5][$this->session->userData('trimestre')];?> %',
                          y: <?php echo $tabla[5][$this->session->userData('trimestre')];?>,
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