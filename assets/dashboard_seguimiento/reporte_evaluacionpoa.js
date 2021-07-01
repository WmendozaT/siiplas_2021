base = $('[name="base"]').val();
tab2 = $('[name="tabla2"]').val();
tab3 = $('[name="tabla3"]').val();
tab4 = $('[name="tabla4"]').val();
tab5 = $('[name="tabla5"]').val();
tab6 = $('[name="tabla6"]').val();
tab7 = $('[name="tabla7"]').val();
tab8 = $('[name="tabla8"]').val();
titulo_evaluacion = $('[name="tit"]').val();

function abreVentana_eficiencia(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "EVALUACION POA" , "width=800,height=700,scrollbars=NO") ; 
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


  $( function() {
    $( "#grupoTablas" ).tabs();
  } );

  function justNumbers(e){
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;           
    return /\d/.test(String.fromCharCode(keynum));
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


    ///// Muestra Datos para la Regionales y distritales
      $(function () {
        $(".enlace").on("click", function (e) {
          id = $(this).attr('name');
          tp = $(this).attr('id');
          titulo='Consolidado Regional';
          if(tp==1){
              titulo='Consolidado Distrital';
          }
          else{
              titulo='Consolidado Nacional';
          }

          $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Evaluaci&oacute;n '+titulo+'</div>');
          var url = base+"index.php/reporte_evaluacion/crep_evalinstitucional/get_cuadro_evaluacion_institucional";
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
          }
          else{
              alertify.error("ERROR AL RECUPERAR DATOS");
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


      //// Muestra datos para las gerencias de la Oficina Nacional
       $(function () {
          $(".enlaceg").on("click", function (e) {
            id = $(this).attr('name');
            tp = $(this).attr('id');
            titulo='Consolidado Gerencia de Servicios de Salud';
            if(tp==1){
                titulo='Consolidado Gerencia General';
            }
            else{
                titulo='Consolidado Gerencia Administrativa Financiera';
            }

            $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Evaluaci&oacute;n '+titulo+'</div>');
            
            var url = base+"index.php/reporte_evaluacion/crep_evalofinacional/get_cuadro_evaluacion_onacional";
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
            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS");
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



    /*---- CUADRO EFICACIA POR UNIDAD-REGIONAL ----*/
    $(function () {
        $(".eficacia").on("click", function (e) {
            tp = $(this).attr('id');
            id = $(this).attr('name');

            $('#lista').html('<div class="loadin" align="center"><br><img src="'+base+'/assets/img_v1.1/load.gif" alt="loading" style="width:30%;"/><br/><b>CARGANDO DATOS DE LAS UNIDADES ORGANIZACIONALES ...</b></div>');
            var url = base+"index.php/reporte_evaluacion/crep_evalinstitucional/get_unidades_eficiencia";
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
                $('#lista').fadeIn(1000).html(response.tabla);
                $('#boton_eficacia').slideUp();
                $('#print_eficacia').slideDown();
              //  $('#eval_poa').slideDown();
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



    ////// AREA DE IMPRESION 
      //// Evaluacion POA
      function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {
      var ventana = window.open('Evaluacion POA ', 'PRINT', 'height=800,width=1000');
      ventana.document.write('<html><head><title>EVALUACIÓN POA</title>');
      //ventana.document.write('<link rel="stylesheet" href="assets/print_static.css">');
      ventana.document.write('</head><body>');
     // ventana.document.write('<style type="text/css" media="print">div.page { writing-mode: tb-rl;height: 100%;margin: 100% 100%;}</style>');
      //ventana.document.write('<style type="text/css">@media print{body{writing-mode: rl;}}.verde{ width:100%; height:5px; background-color:#1c7368;}.blanco{ width:100%; height:5px; background-color:#F1F2F1;}</style>');
      ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
     // ventana.document.write('<div class="page">');
      ventana.document.write('<hr>');
      ventana.document.write(cabecera.innerHTML);
      ventana.document.write('<hr>');
      ventana.document.write(eficacia.innerHTML);
      ventana.document.write(grafico.innerHTML);
      ventana.document.write('<hr>');
      ventana.document.write(tabla.innerHTML);
/*      ventana.document.write("<p>");
      ventana.document.write("<div style='font-size: 10px;'>[Copyright]:Departamento Nacional de Planificación - Sistema de Planificación de Salud SIIPLAS V.2</div>");
      ventana.document.write("<\/p>");*/
     // ventana.document.write('</div>');
      ventana.document.write('</body></html>');
      ventana.document.close();
      ventana.focus();
      ventana.onload = function() {
        ventana.print();
        ventana.close();
      };
      return true;
    }

    /// Impresion grafico 1 (Regresion al trimestre)
/*    document.querySelector("#btnImprimir_evaluacion_trimestre").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_trimestre");
      
      document.getElementById("cabecera").style.display = 'block';
      var cabecera = document.querySelector("#cabecera");

      var eficacia = document.querySelector("#eficacia");
      
      document.getElementById("tabla_regresion_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera").style.display = 'none';

      document.getElementById("tabla_regresion_vista").style.display = 'block';
      document.getElementById("tabla_regresion_impresion").style.display = 'none';
    });*/


    /// Impresion grafico 2 (Pastel al trimestre)
    document.querySelector("#btnImprimir_evaluacion_pastel").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_pastel");
      
      document.getElementById("cabecera1").style.display = 'block';
      var cabecera = document.querySelector("#cabecera1");
      
      var eficacia = document.querySelector("#eficacia");

      document.getElementById("tabla_pastel_impresion").style.display = 'block';
      document.getElementById("tabla_pastel_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_pastel_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera1").style.display = 'none';

      document.getElementById("tabla_pastel_vista").style.display = 'block';
      document.getElementById("tabla_pastel_impresion").style.display = 'none';
      document.getElementById("pastel_canvas").style.display = 'block';
    });




    /// Impresion grafico 3 (Regresion Total)
    document.querySelector("#btnImprimir_evaluacion_gestion").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_gestion");
      
      document.getElementById("cabecera2").style.display = 'block';
      var cabecera = document.querySelector("#cabecera2");
      
      var eficacia = document.querySelector("#efi");

      document.getElementById("tabla_regresion_total_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_total_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_total_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera2").style.display = 'none';

      document.getElementById("tabla_regresion_total_vista").style.display = 'block';
      document.getElementById("tabla_regresion_total_impresion").style.display = 'none';
    });


      //// ----CAPTURA GRAFICOS CANVAS
      var downPdf = document.getElementById("btnregresion");
      var downPdf2 = document.getElementById("btnpastel");

      downPdf.onclick = function() {
        html2canvas(document.body, {
          onrendered:function(canvas) {

            var contentWidth = canvas.width;
            var contentHeight = canvas.height;

            var pageHeight = contentWidth / 595.28 * 841.89;
        
            var leftHeight = contentHeight;
         
            var position = 0;
      
            var imgWidth = 555.28;
            var imgHeight = 555.28/contentWidth * contentHeight;

            var pageData = canvas.toDataURL('image/jpeg', 1.0);

            var pdf = new jsPDF('', 'pt', 'a4');
        
           if (leftHeight < pageHeight) {
                pdf.addImage(pageData, 'JPEG', 20, 0, imgWidth, imgHeight );
            } else {
                while(leftHeight > 0) {
                    pdf.addImage(pageData, 'JPEG', 20, position, imgWidth, imgHeight)
                    leftHeight -= pageHeight;
                    position -= 841.89;
                 
                    if(leftHeight > 0) {
                        pdf.addPage();
                    }
                }
            }

            pdf.save(titulo_evaluacion+'_AVANCE_POA_EVALUACION_TRIMESTRE.pdf');
          }
        })
      }

    downPdf2.onclick = function() {
        html2canvas(document.body, {
          onrendered:function(canvas) {

            var contentWidth = canvas.width;
            var contentHeight = canvas.height;

            var pageHeight = contentWidth / 595.28 * 841.89;
        
            var leftHeight = contentHeight;
         
            var position = 0;
      
            var imgWidth = 555.28;
            var imgHeight = 555.28/contentWidth * contentHeight;

            var pageData = canvas.toDataURL('image/jpeg', 1.0);

            var pdf = new jsPDF('', 'pt', 'a4');
        
           if (leftHeight < pageHeight) {
                pdf.addImage(pageData, 'JPEG', 20, 0, imgWidth, imgHeight );
            } else {
                while(leftHeight > 0) {
                    pdf.addImage(pageData, 'JPEG', 20, position, imgWidth, imgHeight)
                    leftHeight -= pageHeight;
                    position -= 841.89;
                 
                    if(leftHeight > 0) {
                        pdf.addPage();
                    }
                }
            }

            pdf.save(titulo_evaluacion+'_CUADRO DETALLE_EVALUACION_POA.pdf');
          }
        })
      }

    ///// =============== GRAFICO PASTEL HIGTHCARS Y CANVAS
    window.onload = function () {
    var chart = new CanvasJS.Chart("pastel_todos", {
      exportEnabled: true,
      animationEnabled: true,
      title:{
        text: ""
      },
      legend:{
        cursor: "pointer",
        itemclick: explodePie
      },
      data: [{
        type: "pie",
        showInLegend: true,
        toolTipContent: "{name}: <strong>{y} %</strong>",
        indexLabel: "{name} - {y} %",
        dataPoints: [
          { y: tab5, name: "METAS CUMPLIDAS", color: '#57889c', exploded: true },
          { y: tab8, name: "METAS EN PROCESO",color: '#f5e218' },
          { y: (tab6-tab8), name: "METAS NO CUMPLIDAS", color: '#a90329'}
        ]
      }]
    });
    chart.render();
    }

    function explodePie (e) {
      if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
      } else {
        e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
      }
      e.chart.render();
    }


    $(document).ready(function() {  
     Highcharts.chart('pastel_todosprint', {
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
          name: 'Actividades',
          data: [
              {
                name: 'NO CUMPLIDO : '+(tab6-tab8)+'%',
                y: +(tab6-tab8),
                color: '#f98178',
              },

              {
                name: 'EN PROCESO : '+tab8+'%',
                y: +tab8,
                color: '#f5eea3',
              },

              {
                name: 'CUMPLIDO : '+tab5+'%',
                y: +tab5,
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
          ]
      }]
    });
  });
////============= ENS PASTEL


var chart = new CanvasJS.Chart("chartContainer", {
  animationEnabled: true,
  exportEnabled: true,
  title:{
    text: "Gold Medals Won in Olympics"             
  }, 
  axisY:{
    title: "Number of Medals"
  },
  toolTip: {
    shared: true
  },
  legend:{
    cursor:"pointer",
    itemclick: toggleDataSeries
  },
  data: [{        
    type: "line",  
    name: "US",        
    showInLegend: true,
    dataPoints: [
      { label: "", y: 0 },     
      { label:"Sydney 2000", y: 37,indexLabel: "\u2191 highest",markerColor: "red", markerType: "triangle" },     
      { label: "Athens 2004", y: 36 },     
      { label: "Beijing 2008", y: 36 },     
      { label: "London 2012", y: 46 },
      { label: "Rio 2016", y: 46 }
    ]
  }, 
  {        
    type: "line",
    name: "China",        
    showInLegend: true,
    dataPoints: [
      { label: "Atlanta 1996" , y: 16 },     
      { label:"Sydney 2000", y: 28 },     
      { label: "Athens 2004", y: 32 },     
      { label: "Beijing 2008", y: 48 },     
      { label: "London 2012", y: 38 },
      { label: "Rio 2016", y: 26 }
    ]
  }]
});

chart.render();


function toggleDataSeries(e) {
  if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
    e.dataSeries.visible = false;
  } else {
    e.dataSeries.visible = true;
  }
  e.chart.render();
}
