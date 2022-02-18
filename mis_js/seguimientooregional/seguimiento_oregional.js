  base = $('[name="base"]').val();

  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "EVALUACION OPERACION" , "width=800,height=700,scrollbars=NO") ; 
  }


  function doSearch(){
    var tableReg = document.getElementById('datos');
    var searchText = document.getElementById('searchTerm').value.toLowerCase();
    var cellsOfRow="";
    var found=false;
    var compareWith="";

    // Recorremos todas las filas con contenido de la tabla
    for (var i = 1; i < tableReg.rows.length; i++){
      cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
      found = false;
      // Recorremos todas las celdas
      for (var j = 0; j < cellsOfRow.length && !found; j++){
        compareWith = cellsOfRow[j].innerHTML.toLowerCase();
        // Buscamos el texto en el contenido de la celda
        if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)){
          found = true;
        }
      }
      if(found) {
        tableReg.rows[i].style.display = '';
      } else {
        // si no ha encontrado ninguna coincidencia, esconde la
        // fila de la tabla
        tableReg.rows[i].style.display = 'none';
      }
    }
  }



  /*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL ------*/
  $(function () {
    $(".update_temporalidad").on("click", function (e) {
        dep_id = $(this).attr('name');
        //document.getElementById("com_id").value=dep_id;
        $('#tit').html('<font size=3><b>'+$(this).attr('id')+'</b></font>');
        $('#but_update_temp').slideUp();

        var url = base+"index.php/ejecucion/cevaluacion_oregional/update_temporalidad_oregional";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "dep_id="+dep_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
            $('#content_valida').fadeIn(1000).html(response.tabla);
            $('#but_update_temp').slideDown();
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS");
        }

        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
        });
        e.preventDefault();

        $("#but_update").on("click", function (e) {
          var $valid = $("#form_update").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } else {
              window.location.reload(true);
              document.getElementById("but_update_temp").style.display = 'none';
              document.getElementById("load_update_temp").style.display = 'block';
              alertify.success("ACTUALIZACIÓN EXITOSA ...");
          }
        });
    });
  });


  /// Lista de Actividades Priorizados por cada Objetivo Regional
  function ver_actividades_priorizados(or_id,dep_id) {
    $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
    $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
  //  alert(dep_id)
    var url = base+"index.php/ejecucion/cevaluacion_oregional/ver_actividades_priorizados";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "or_id="+or_id+"&dep_id="+dep_id
    });

    request.done(function (response, textStatus, jqXHR) {

    if (response.respuesta == 'correcto') {
        $('#titulo').html(response.titulo);
        $('#content1').fadeIn(1000).html(response.tabla);
    }
    else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
    }

    });
  }

  /// grado de cumplimiento por Objetivo Regional
  function nivel_cumplimiento(or_id,dep_id) {
    $('#titulo_grafico').html('<font size=3><b>Cargando ..</b></font>');
    $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
  //  alert(dep_id)
    var url = base+"index.php/ejecucion/cevaluacion_oregional/ver_datos_avance_oregional";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "or_id="+or_id+"&dep_id="+dep_id
    });

    request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {

          $('#titulo_grafico').html(response.titulo);

          var chart = new CanvasJS.Chart("parametro_efi", {
              animationEnabled: true,
              exportEnabled: true,
              title:{
                  text: "EVALUACION POA ACUMULADO AL"             
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
                  name: "ACT. PROGRAMADAS",        
                  showInLegend: true,
                  dataPoints: [
                    { label: "-", y: 0},   
                    { label: "1er. Trimestre", y: 2,indexLabel: "a Act.", markerType: "square",  markerColor: "blue"},     
                    { label: "2do. Trimestre", y: 3,indexLabel: "3 Act.", markerType: "square",  markerColor: "blue"},     
                    { label: "3er. Trimestre", y: 2,indexLabel: "2 Act.", markerType: "square",  markerColor: "blue"},     
                    { label: "4to. Trimestre", y: 5,indexLabel: "5 Act.", markerType: "square",  markerColor: "blue"}
                  ]
              }, 
              {        
                  type: "area",
                  color: "green",
                  name: "ACT. CUMPLIDAS",        
                  showInLegend: true,
                  dataPoints: [
                    { label: "-", y: 0},  
                    { label: "1er. Trimestre", y: 2,indexLabel: "2 Act.", markerType: "square",  markerColor: "green"},     
                    { label: "2do. Trimestre", y: 3,indexLabel: "3 Act.", markerType: "square",  markerColor: "green"},     
                    { label: "3er. Trimestre", y: 1,indexLabel: "1 Act.", markerType: "square",  markerColor: "green"},     
                    { label: "4to. Trimestre", y: 3,indexLabel: "3 Act.", markerType: "square",  markerColor: "green"}
                  ]
              }]
          });

          chart.render();

      





      chart = new Highcharts.Chart({
      chart: {
        renderTo: 'parametro_efi2',  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: 'Datos de las Visitas'  // Titulo (Opcional)
      },
      subtitle: {
        text: 'Jarroba.com'   // Subtitulo (Opcional)
      },
      // Pongo los datos en el eje de las 'X'
      xAxis: {
        categories: ['','Mar12','Abr12','May12','Jun12'],
        // Pongo el título para el eje de las 'X'
        title: {
          text: 'Trimestres'
        }
      },
      yAxis: {
        // Pongo el título para el eje de las 'Y'
        title: {
          text: 'Nº Visitas'
        }
      },
      // Doy formato al la "cajita" que sale al pasar el ratón por encima de la gráfica
      tooltip: {
        enabled: true,
        formatter: function() {
          return '<b>'+ this.series.name +'</b><br/>'+
            this.x +': '+ this.y +' '+this.series.name;
        }
      },
      // Doy opciones a la gráfica
      plotOptions: {
        line: {
          dataLabels: {
            enabled: true
          },
          enableMouseTracking: true
        }
      },
      // Doy los datos de la gráfica para dibujarlas
      series: [{
                    name: 'Visitas',
                    data: [0,474,402,536,1041]
                },
                {
                    name: 'Visitantes Únicos',
                    data: [0,278,203,370,810]
                },
                {
                    name: 'Páginas Vistas',
                    data: [0,1648,1040,1076,2012]
                }],
    });
/*var chart1 = new CanvasJS.Chart("parametro_efi", {
      exportEnabled: true,
      animationEnabled: true,
      title:{
        text: "EVALUACION POA AL " 
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
          { y: response.datos[1], name: "CUMPLIDAS", color: '#57889c', exploded: true },
          { y: response.datos[2], name: "EN PROCESO",color: '#f5e218' },
          { y: response.datos[3], name: "NO CUMPLIDAS", color: '#a90329'}
        ]
      }]
    });
    chart1.render();*/

/*          Highcharts.chart('parametro_efi', {
            chart: {
                type: 'pie',
                options3d: {
                    enabled: true,
                    alpha: 45,
                    beta: 0
                }
            },
            title: {
                text: ''
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
                      name: 'INSATISFACTORIO : '+response.datos[1]+' %',
                      y: response.datos[1],
                      color: '#f95b4f',
                    },

                    {
                      name: 'REGULAR : 3 %',
                      y: 3,
                      color: '#edd094',
                    },

                    {
                     name: 'BUENO : 5 %',
                      y: 5,
                      color: '#afd5e5',
                    },

                    {
                      name: 'OPTIMO : 6%',
                      y: 6,
                      color: '#4caf50',
                      sliced: true,
                      selected: true
                    }
                ]
            }]
          });*/
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
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
/*    function explodePastel (e) {
        if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
        } else {
            e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
        }
        e.chart1.render();
    }*/
/* $(document).ready(function() {  
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
          text: 'PARAMETRO DE EFICACIA AL '
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
                name: 'INSATISFACTORIO : 2 %',
                y: 2,
                color: '#f95b4f',
              },

              {
                name: 'REGULAR : 3 %',
                y: 3,
                color: '#edd094',
              },

              {
               name: 'BUENO : 5 %',
                y: 5,
                color: '#afd5e5',
              },

              {
                name: 'OPTIMO : 6%',
                y: 6,
                color: '#4caf50',
                sliced: true,
                selected: true
              }
          ]
      }]
    });
  });*/


  /// srcip para graficos
  $(document).ready(function () {
    $("#btnGrafico").click(function () {
      alert('hola mundo')
      /*var asesor = $("asesor").val;
      var v_neta_tot = $("v_neta_tot").val;
      if (asesor && v_neta_tot != null)
      {
         var data = [];
          var serie = new Array(asesor, v_neta_tot);
          data.push(serie);
          alert(serie);
         // DibujarGrafico(data);

      }else {
              toastr["error"]('Atencion!No se  Cargaron los Datos ' + ' Estado ' + result.status + '  ' + result.statusText);
     }*/
    });
  });