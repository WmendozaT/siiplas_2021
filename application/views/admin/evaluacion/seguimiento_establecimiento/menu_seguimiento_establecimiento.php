<!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $this->session->userdata('name')?></title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/dashboard_seguimiento/bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/dashboard_seguimiento/jquery-ui.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/dashboard/navbar-fixed-top.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
    <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
    <link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
      <style type="text/css">
        .table{font-size: 8px;
          width: 100%;
          max-width:1550px;;
          overflow-x: scroll;
          }
          th{
            padding: 1.4px;
            text-align: center;
            font-size: 8px;
          }
      </style>
  </head>

  <body>
    <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><font color="#1c7368"><b><?php echo $this->session->userdata('name')?></b></font></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <!-- <li><a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" title="CAMBIAR GESTI&Oacute;N">Gesti&oacute;n</a></li> -->
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Descarga de Archivos / Documentos">Descargas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo base_url(); ?>assets/video/configurar_csv.mp4" style="cursor: pointer;" download>Configurar equipo a .CSV</a></li>
                <li class="divider"></li>
                <li class="dropdown-header">Archivos/Tutoriales</li>
                                <li><a href="<?php echo base_url(); ?>assets/video/SEGUIMIENTO_POA_2021_ES.pdf" style="cursor: pointer;" download>Seguimiento POA</a></li></li>
               </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Default</a></li>
            <li class="active"><a href="<?php echo base_url(); ?>index.php/admin/logout" title="CERRAR SESI&Oacute;N"><b>SALIR</b></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
        <div class="jumbotron">
            <div id="grupoTablas">
              <ul>
                <li><a href="#tab-3">Datos Generales</a></li>
                <li><a href="#tab-1">Formulario Seguimiento POA</a></li>
                <li><a href="#tab-2">C. Seguimiento POA</a></li>
                <li><a href="#tab-4">C. de Evaluación POA (Trimestre)</a></li>
                <li><a href="#tab-5">C. de Evaluación POA (Gestión)</a></li>
              </ul>
              <div id="tab-3">
                <hr>
                <div class="row box-green1">
                    <div class="col-md-8">
                        <h2>BIENVENIDO : <?php echo $this->session->userdata("usuario");?></h2>
                        <h4><b>GESTI&Oacute;N ACTUAL : </b><?php echo $this->session->userdata("gestion");?></h4>
                        <h4><b>TRIMESTRE VIGENTE : </b><?php echo $tmes[0]['trm_descripcion'];?></h4>
                        <h4><b>MES ACTUAL : </b><?php echo $this->session->userData('mes_actual')[2];?></h4>
                    </div>
                    <div class="col-md-4" align="center">
                      <img src="<?php echo base_url('fotos/'.$this->session->userdata("img").'');?>" style="width:100%;">
                    </div>
                </div>
                <hr>
                <div class="row box-green1">
                  <?php echo $nota;?>
                </div>
              </div>
              <div id="tab-1">
                <?php echo $operaciones_programados; ?>
              </div>
              <div id="tab-2">
                <div id="cabecera" style="display: none"><?php echo $cabecera1;?></div>
                  <div class="row">
                      <table>
                        <tr>
                          <td style="font-size: 10pt;font-family:Verdana;"><b>CUADRO DE SEGUIMIENTO POA AL MES DE <?php echo $this->session->userData('mes_actual')[2].' DE '.$this->session->userData('gestion');?></b></td>
                        </tr>
                      </table>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" align="center">
                      <div id="Seguimiento">
                        <table class="change_order_items" border="0.5">
                        <tr>
                          <td>
                              <div id="container" style="width: 680px; height: 380px; margin: 0 auto"></div>
                          </td>
                        </tr>
                        <tr>
                          <td>
                          <div class="table-responsive">
                              <?php echo $tabla_temporalidad_componente;?>
                          </div>
                          </td>
                        </tr>
                        </table>
                      </div>
                    </div>
                  </div>
                <div align="right">
                  <button id="btnImprimir_seguimiento" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;Imprimir Cuadro de Seguimiento Mensual POA</b></button>
                </div>
              </div>

              <div id="tab-4">
                  <div id="cabecera2" style="display: none"><?php echo $cabecera2;?></div>
                  <div class="row">
                      <table>
                        <tr>
                          <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE AVANCE EVALUACI&Oacute;N POA AL <?php echo $tmes[0]['trm_descripcion'].' DE '.$this->session->userData('gestion');?></b></td>
                        </tr>
                      </table>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" align="center">
                        <div id="evaluacion_trimestre">
                          <table class="change_order_items" border="0.7">
                            <tr>
                              <td>
                                <div id="regresion" style="width: 580px; height: 380px; margin: 0 auto"></div>
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
                      </div>
                  </div>
                <div align="right">
                  <button id="btnImprimir_evaluacion_trimestre" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (TRIMESTRAL)</b></button>
                </div>
              </div>

              <div id="tab-5">
                  <div id="cabecera3" style="display: none"><?php echo $cabecera3;?></div>
                  <div class="row">
                      <table>
                        <tr>
                          <td style="font-size: 13pt;font-family:Verdana;"><b>CUADRO DE EVALUACI&Oacute;N POA <?php echo $this->session->userData('gestion');?></b></td>
                        </tr>
                      </table>
                      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" align="center">
                        <div id="evaluacion_gestion">
                          <table class="change_order_items" border="0.5">
                          <tr>
                            <td>
                                <div id="regresion_gestion" style="width: 680px; height: 380px; margin: 0 auto"></div>
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
                <div align="right">
                  <button id="btnImprimir_evaluacion_gestion" class="btn btn-default"><img src="<?php echo base_url() ?>assets/Iconos/printer.png" WIDTH="17" HEIGHT="17"/><b>&nbsp;&nbsp;IMPRIMIR CUADRO DE EVALUACI&Oacute;N POA (GESTIÓN)</b></button>
                </div>
              </div>
            </div>
        </div>
    </div> <!-- /container -->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/dashboard/jquery-1.js"></script>
    <script src="<?php echo base_url(); ?>assets/dashboard/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/jquery-1.12.4.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>
    <script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script> 
    <!-- <script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/stacktable.js"></script> -->
    <script>
      if (!window.jQuery) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"><\/script>');
      }
    </script>
    <script>
      if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
      }
    </script>
    <script src="<?php echo base_url(); ?>assets/dashboard_seguimiento/seguimiento.js"></script> 
    <script type="text/javascript">
      Highcharts.chart('container', {
            chart: {
                type: 'column',
                options3d: {
                    enabled: true,
                    alpha: 0,
                    beta: 0,
                    depth: 100
                }
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ''
            },
            
            plotOptions: {
                column: {
                    depth: 25
                }
            },
            xAxis: {
                categories: Highcharts.getOptions().lang.shortMonths,
                labels: {
                    skew3d: true,
                    style: {
                        fontSize: '16px'
                    }
                }
            },
            yAxis: {
                title: {
                  text: 'cumplimiento (%)'
                }
              },
            xAxis: {
                categories: [
                    'ENE.', 
                    'FEB.', 
                    'MAR.', 
                    'ABR.', 
                    'MAY.', 
                    'JUN.', 
                    'JUL.', 
                    'AGO.', 
                    'SEPT.', 
                    'OCT.', 
                    'NOV.', 
                    'DIC.'
                ]
            },
            series: [{
                name: 'Mes',
                data: [
                    <?php  
                        for ($i=1; $i <=12 ; $i++) { 
                            if($i==12){
                                echo $matriz_temporalidad_subactividad[4][$i];
                            }
                            else{
                               echo $matriz_temporalidad_subactividad[4][$i].','; 
                            }
                            
                        }
                    ?>
                ]
            }]
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
                                name: 'NRO ACT. PROGRAMADO AL TRIMESTRE',
                                data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>]
                            },
                            {
                                name: 'NRO ACT. CUMPLIDO AL TRIMESTRE',
                                data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>]
                            }
                        ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==2) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO AL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO AL TRIMESTRE',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==3) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO AL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO AL TRIMESTRE',
                                    data: [ <?php echo $tabla[3][0];?>, <?php echo $tabla[3][1];?>, <?php echo $tabla[3][2];?>, <?php echo $tabla[3][3];?>]
                                }
                            ]
                        <?php
                    }
                    elseif ($this->session->userdata('trimestre')==4) { ?>
                            series: [
                                {
                                    name: 'NRO ACT. PROGRAMADO AL TRIMESTRE',
                                    data: [ <?php echo $tabla[2][0];?>, <?php echo $tabla[2][1];?>, <?php echo $tabla[2][2];?>, <?php echo $tabla[2][3];?>, <?php echo $tabla[2][4];?>]
                                },
                                {
                                    name: 'NRO ACT. CUMPLIDO AL TRIMESTRE',
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
                        name: '% META PROGRAMADAS',
                        data: [ <?php echo $tabla_gestion[4][0];?> , <?php echo $tabla_gestion[4][1];?>, <?php echo $tabla_gestion[4][2];?>, <?php echo $tabla_gestion[4][3];?>, <?php echo $tabla_gestion[4][4];?>]
                    },
                    {
                        name: '% META CUMPLIDAS',
                        data: [ <?php echo $tabla_gestion[5][0];?>, <?php echo $tabla_gestion[5][1];?>, <?php echo $tabla_gestion[5][2];?>, <?php echo $tabla_gestion[5][3];?>, <?php echo $tabla_gestion[5][4];?>]
                    }
                ]
            });
          });
        </script>
     <script type="text/javascript">
        function guardar(prod_id,nro){
            ejec=parseFloat($('[id="ejec'+nro+'"]').val());
            mverificacion=($('[id="mv'+nro+'"]').val());
            observacion=($('[id="obs'+nro+'"]').val());
            accion=($('[id="acc'+nro+'"]').val());

            if(($('[id="mv'+nro+'"]').val())==0){
                document.getElementById("mv"+nro).style.backgroundColor = "#fdeaeb";
                alertify.error("REGISTRE MEDIO DE VERIFICACIÓN, Operación "+nro);
                return 0; 
            }
            else{
                document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
                alertify.confirm("GUARDAR SEGUIMIENTO POA?", function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/ejecucion/cseguimiento_establecimiento/guardar_seguimiento'?>";
                        var request;
                        if (request) {
                            request.abort();
                        }
                        request = $.ajax({
                            url: url,
                            type: "POST",
                            dataType: 'json',
                            data: "prod_id="+prod_id+"&ejec="+ejec+"&mv="+mverificacion+"&obs="+observacion+"&acc="+accion
                        });

                        request.done(function (response, textStatus, jqXHR) {

                        if (response.respuesta == 'correcto') {
                            alertify.alert("SE REGISTRO CORRECTAMENTE ", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                }
                            });
                        }
                        else{
                            alertify.error("ERROR AL GUARDAR SEGUIMIENTO POA");
                        }

                        });
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }

        function verif_valor(programado,ejecutado,nro){
          if(ejecutado!== '' & ejecutado!== 0){
            if(ejecutado<=programado){
                $('#but'+nro).slideDown();
                document.getElementById("ejec"+nro).style.backgroundColor = "#ffffff";
                document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
            }
            else{
                alertify.error("ERROR EN EL DATO REGISTRADO !");
                 document.getElementById("ejec"+nro).style.backgroundColor = "#fdeaeb";
                $('#but'+nro).slideUp();
            }
          }
          else{
            $('#but'+nro).slideUp();
          }
        }

        $("#mes_id").change(function () {
            $("#mes_id option:selected").each(function () {
                mes_id=$(this).val();
                mes_activo=$('[name="mes_activo"]').val();
                if(mes_id!=mes_activo){
                    var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_update_mes";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "mes_id="+mes_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                        if (response.respuesta == 'correcto') {
                            alertify.alert("SE CAMBIO AL MES CORRECTAMENTE ", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                    //alertify.success("CAMBIO DE MES");
                                }
                            })
                        }
                        else{
                            alertify.error("ERROR !!!");
                        }
                    }); 
                }
            });
        });
      </script>
    <script type="text/javascript">
        /*------ Evaluacion de Operaciones ------*/
        $(function () {
            var prod_id = ''; var proy_id = '';
            $(".ope_mes").on("click", function (e) {
                dist_id = $(this).attr('id');
                $('#operaciones').html('<div class="loading" align="center"><img src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando lista de Operaciones a ejecutar este mes ...</div>');
                var url = "<?php echo site_url("")?>/ejecucion/cseguimiento/get_operaciones_mes";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "dist_id="+dist_id
                });

                request.done(function (response, textStatus, jqXHR) { 
                    if (response.respuesta == 'correcto') {
                        $('#operaciones').html(response.tabla);
                    } else {
                        alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
                    }
                });


            });
        });
      </script>
</body>
</html>