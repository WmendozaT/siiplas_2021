base = $('[name="base"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE SEGUIMIENTO POA" , "width=800,height=700,scrollbars=NO") ; 
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

//////======================= DISTRIBUCION MENSUAL DE CERTIFICACION POA
  //// Get distribucion Certificacion POA
  $(function () {
    $(".distribucion").on("click", function (e) {
      proy_id = $(this).attr('name');
      establecimiento = $(this).attr('id');
     
      $('#titulo_dist').html('');
      $('#load').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando distribucion - <br>'+establecimiento+'</div>');
      
      var url = base+"index.php/ejecucion/cseguimiento/get_distribucion_mensual_certpoa";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {

        $('#load').fadeIn(1000).html(response.tabla);
        $('#titulo_dist').html('<h2 class="alert alert-info"><center>'+establecimiento+'</center></h2>');
        graf_regresion_trimestral_temporalidad_prog_ejec('graf_form5',response.matriz_form5,'CUADRO DE EJECUCION FORMULARIO N° (REQUERIMIENTOS)',establecimiento,'% EJECUCION CERT. POA') /// formulario 5
        graf_regresion_trimestral_temporalidad_prog_ejec('graf_form4',response.matriz_form4,'CUADRO CUMPLIMIENTO DE METAS FORMULARIO N° 4 (ACTIVIDADES)',establecimiento,'% EJECUCION METAS - ACTIVIDADES') /// formulario 4
      }
      else{
        alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
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



//////======================= DISTRIBUCION MENSUAL PROGRAMACION INICIAL PROYECTOS DE INVERSION 2023
  //// Distribucion Financiera Inicial Proyectos de Inversion
  $(function () {
    $(".distribucion_inicial").on("click", function (e) {
      proy_id = $(this).attr('name');
      proyecto = $(this).attr('id');
     
      $('#titulo_dist_inicial').html('');
      $('#load_inicial').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando distribucion - <br>'+proyecto+'</div>');
      
      var url = base+"index.php/ejecucion/cejecucion_pi/get_cuadro_ejecucion_pi";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id
      });

      request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {
        document.getElementById('titulo_dist_inicial').innerHTML = '<h1>'+response.datos_proyecto+'</h1><hr>';
        document.getElementById('load_inicial').innerHTML = response.cuadro_consolidado;
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
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





















    /// Grafico EJECUCION DE FORMULARIO 4 Y 5
    function graf_regresion_trimestral_temporalidad_prog_ejec(grafico,matriz,titulo,subtitulo,tit_laterales) {
      let programado=[];
      for (var i = 0; i <=12; i++) {
        programado[i]= matriz[5][i];
      }

      let ejecutado=[];
      for (var i = 0; i <=12; i++) {
        ejecutado[i]= matriz[6][i];
      }

      ///----
      chart = new Highcharts.Chart({
      chart: {
        renderTo: grafico,  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: titulo  // Titulo (Opcional)
      },
      subtitle: {
        text: '<b>'+subtitulo+'</b>'   // Subtitulo (Opcional)
      },
      // Pongo los datos en el eje de las 'X'
      xAxis: {
        categories: ['','ENE.','FEB.','MAR.','ABR.','MAY.','JUN.','JUL.','AGO.','SEPT.','OCT.','NOV.','DIC.'],
        // Pongo el título para el eje de las 'X'
        title: {
          text: ''
        }
      },
      yAxis: {
        // Pongo el título para el eje de las 'Y'
        title: {
          text: tit_laterales
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
      series: [
          {
            name: '% PROG. ACUMULADO',
            data: programado
          },
          {
            name: '% EJEC. ACUMULADO',
            data: ejecutado
          }
        ],
        
      });
    }

  /// IMPRESION DE GRAFICOS CONSOLIDADO DE FORM 4 Y 5
  // function imprimir_form5() {
  //   var cabecera = document.querySelector("#cabecera_ejec");
  //   var grafico = document.querySelector("#grafico_form5");
  //   document.getElementById("cabecera_ejec").style.display = 'block';
  //   var cabecera = document.querySelector("#cabecera_ejec");
  //   document.getElementById("tabla_impresion_form5").style.display = 'block';
  //   var tabla = document.querySelector("#tabla_impresion_form5");
  //   imprimirGrafico(grafico,cabecera,tabla);
  //   document.getElementById("cabecera_ejec").style.display = 'none';
  //   document.getElementById("tabla_impresion_form5").style.display = 'none';
  // }

  // function imprimir_form4() {
  //   var cabecera = document.querySelector("#cabecera_ejec");
  //   var grafico = document.querySelector("#grafico_form4");
  //   document.getElementById("cabecera_ejec").style.display = 'block';
  //   var cabecera = document.querySelector("#cabecera_ejec");
  //   document.getElementById("tabla_impresion_form4").style.display = 'block';
  //   var tabla = document.querySelector("#tabla_impresion_form4");
  //   imprimirGrafico(grafico,cabecera,tabla);
  //   document.getElementById("cabecera_ejec").style.display = 'none';
  //   document.getElementById("tabla_impresion_form4").style.display = 'none';
  // }

  // function imprimirGrafico(grafico,cabecera,tabla) {
  //   var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
  //   ventana.document.write('<html><head><title>EJECUCIÓN POA FORM. N° 4 y FORM. N° 5</title>');
  //   ventana.document.write('</head><body>');
  //   ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
  //   ventana.document.write(cabecera.innerHTML);
  //   ventana.document.write('<hr>');
  //   ventana.document.write(grafico.innerHTML);
  //   ventana.document.write('<hr>');
  //   ventana.document.write(tabla.innerHTML);
  //   ventana.document.write('</body></html>');
  //   ventana.document.close();
  //   ventana.focus();
  //   ventana.onload = function() {
  //     ventana.print();
  //     ventana.close();
  //   };
  //   return true;
  // }

///// =================================================================

  //// Lista de Unidades -- enlace para listar las subactividades
  $(function () {
    $(".enlace").on("click", function (e) {
      proy_id = $(this).attr('name');
      establecimiento = $(this).attr('id');
      
      $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
      $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones - <br>'+establecimiento+'</div>');
      
      var url = base+"index.php/ejecucion/cseguimiento/get_subactividades";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
          $('#content1').fadeIn(1000).html(response.tabla);
          $('#evaluacion').fadeIn(1000).html(response.evaluacion);
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
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



 /*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL POR UNIDAD------*/
/*  $(function () {
    $(".update_eval_unidad").on("click", function (e) {
        proy_id = $(this).attr('name');
        document.getElementById("proy_id").value=proy_id;
      
        $('#tit').html('<font size=3><b>'+$(this).attr('id')+'</b></font>');
        $('#butt').slideUp();

        var url = base+"index.php/reporte_evalform4/crep_evalunidad/update_evaluacion_trimestral";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "proy_id="+proy_id
        });

        request.done(function (response, textStatus, jqXHR) {
    
        if (response.respuesta == 'correcto') {
            $('#content_valida').fadeIn(1000).html(response.tabla);
            $('#butt').slideDown();
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
            document.getElementById("butt").style.display = 'none';
            $('#content_valida').fadeIn(1000).html('<center><div class="loading" align="center"><h2>Actualizando Evaluaci&oacute;n  POA <br><div id="tit"></div><br><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /></div></center>');
            window.open(base+"index.php/rep_eficacia_unidad/"+proy_id, "CUADRO DE AVANCE - EVALUACION POA TRIMESTRAL", "width=800, height=800");
            $("#modal_update_eval_unidad").modal("hide");
          }
        });
    });
  });*/


///////============ REPORTE GERENCIAL DE SEGUIMIENTO POA


  function ver_operaciones(proy_id) {
      $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
      $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando informacion </div>');
  
      var url = base+"index.php/reporte_seguimiento_poa/crep_seguimientopoa/get_operaciones_subactividad";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
          $('#titulo').fadeIn(1000).html('<font size=3><b>'+response.titulo+'</b></font>');
          $('#content1').fadeIn(1000).html(response.tabla);
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

      });
  }


  $(document).ready(function() {
      pageSetUp();
      $("#dep_id").change(function () {
          $("#dep_id option:selected").each(function () {
             // dist_id=$('[name="dist_id"]').val();
              elegido=$(this).val();
              if(elegido!=0){
                  $('#ue').slideDown();
                  $('#tp').slideDown();
                  $.post(base+"index.php/rep/get_seguimiento_da", { elegido: elegido,accion:'distrital' }, function(data){
                      $("#dist_id").html(data);
                      $("#tp_id").html('');
                      $("#lista_consolidado").html('');
                  });
              }
              else{
                  dep_id=0;
                  dist_id=0;
                  tp_id=4;
                  $('#ue').slideUp();
                  $('#tp').slideUp();

                  $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
                  var url = base+"index.php/reporte_seguimiento_poa/crep_seguimientopoa/get_lista_gcorriente_pinversion";
                  var request;
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: 'json',
                      data: "dep_id="+dep_id+"&dist_id="+dist_id+"&tp_id="+tp_id
                  });

                  request.done(function (response, textStatus, jqXHR) {
                      if (response.respuesta == 'correcto') {
                          $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                      }
                      else{
                          alertify.error("ERROR AL LISTAR");
                      }
                  }); 
              }
              
          });
      });

      $("#dist_id").change(function () {
          $("#dist_id option:selected").each(function () {
              elegido=$(this).val();
              $.post(base+"index.php/rep/get_seguimiento_da", { elegido: elegido,accion:'tipo' }, function(data){
                  $("#tp_id").html(data);
                  $("#lista_consolidado").html('');
              });
          });
      });


      $("#tp_id").change(function () {
          $("#tp_id option:selected").each(function () {
              dep_id=$('[name="dep_id"]').val();
              dist_id=$('[name="dist_id"]').val();
              tp_id=$(this).val();
              if(tp_id!=0){
                  $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
                  var url = base+"index.php/reporte_seguimiento_poa/crep_seguimientopoa/get_lista_gcorriente_pinversion";
                  var request;
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: 'json',
                      data: "dep_id="+dep_id+"&dist_id="+dist_id+"&tp_id="+tp_id
                  });

                  request.done(function (response, textStatus, jqXHR) {
                      if (response.respuesta == 'correcto') {
                          $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                      }
                      else{
                          alertify.error("ERROR AL LISTAR");
                      }
                  }); 
              }
              else{
                  $("#lista_consolidado").html('');
              }
              
          });
      });
  })


////============= END PASTEL

//// EVALUACION POA
/// Boton de Impresion Cuadros de Evaluacion 2025
document.getElementById('btnImprimir_evaluacion_trimestre').addEventListener('click', function() {
  //alert('hola mundo')
  const plantillaPagina = (contenido, numeroPagina, calificacion) => `
    <div class="pagina">
      <header class="cabecera-pagina">
      <div class="membrete">
        
        <div class="datos-institucion">
          <h2>${document.getElementById('cabecera').outerHTML}</h2>
        </div>

      </div>
      <hr class="linea-separadora">
    ${calificacion}
    </header>

      ${contenido}

      <footer class="pie-pagina">
        <div class="marcas-agua">
          <span class="pagina-numero">Página ${numeroPagina} de 3</span>
          <span class="fecha-generacion">${new Date().toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          })}</span>
        </div>
      </footer>
    </div>
  `;

  const ventanaImpresa = window.open('', '_blank');
  ventanaImpresa.document.write(`
    <html>
      <head>
        <title>DEPARTAMENTO NACIONAL DE PLANIFICACION / SIIPLAS</title>
        <style>
          @page {
            size: A4 portrait;
            margin: 1cm 0.8cm;
            @top { content: element(cabecera-pagina); }
            @bottom { content: element(pie-pagina); }
          }
          #cabecera {
            display: block !important;
            margin: 0 0 12px 0;
            font-size: 10pt !important;
            color: #000000 !important;
            border: none !important;
          }

          /* Optimización tipográfica */
          .datos-institucion h2 {
            font-size: 15pt !important;
            margin: 3px 0 3px 0;
            color: #000000 !important;
          }
          .pagina {
            page-break-after: always;
            position: relative;
            height: calc(297mm - 5cm);
          }

          .cabecera-pagina {
            position: running(cabecera-pagina);
            margin-bottom: 1cm;
          }

          .pie-pagina {
            position: running(pie-pagina);
            height: 2cm;
          }

          .membrete {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
            align-items: center;
            margin-bottom: 5px;
          }

          .linea-separadora {
            border: 1px solid #11574e;
            margin: 8px 0;
          }

          .marcas-agua {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 3px;
          }

          .grafico-impresion {
            width: 100%!important;
            height: 380px!important;
            margin: 15px 0;
            page-break-inside: avoid;
          }
          }
        </style>
      </head>
      <body>
        ${plantillaPagina(`
          ${document.getElementById('regresion').outerHTML}
          ${document.getElementById('tabla_regresion_impresion').outerHTML}
        `, 1, `${document.getElementById('calificacion').outerHTML}`)}

        ${plantillaPagina(`
          ${document.getElementById('pastel_todos').outerHTML}
          ${document.getElementById('tabla_pastel_vista').outerHTML}
        `, 2, `${document.getElementById('calificacion').outerHTML}`)}

        ${plantillaPagina(`
          ${document.getElementById('regresion_gestion').outerHTML}
          ${document.getElementById('tabla_regresion_total_impresion').outerHTML}
        `, 3, '')}
      </body>
    </html>
  `);

  ventanaImpresa.document.close();
  setTimeout(() => {
    ventanaImpresa.print();
    ventanaImpresa.close();
  }, 1000);
});