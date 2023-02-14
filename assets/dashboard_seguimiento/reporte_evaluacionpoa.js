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

    function doSearchuni(){
    var tableReg = document.getElementById('tab_uni');
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


////------- menu select regionales

  $("#dep_id").change(function () {
      $("#dep_id option:selected").each(function () {
          dist_id=$('[name="dist_id"]').val();
          elegido=$(this).val();
          
          if(elegido!=0){ /// REGIONAL
              $('#ue').slideDown();
              $('#tp').slideDown();
              $.post(base+"index.php/rep/get_seguimiento_da", { elegido: elegido,accion:'distrital' }, function(data){
                  $("#dist_id").html(data);
                  $("#tp_id").html('');
                  $("#lista_consolidado").html('');
                  document.getElementById("update_eval").style.display = 'block';
              });
          }
          else{ //// INSTITUCIONAL NACIONAL
           
              dep_id=0;
              dist_id=0;
              tp_id=4;
              $('#ue').slideUp();
              $('#tp').slideUp();
              document.getElementById("update_eval").style.display = 'none';
       
              $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
                var url = base+"index.php/reporte_evalform4/crep_evalinstitucional/get_cuadro_evaluacion_institucional";
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
                      $('#lista_consolidado').fadeIn(1000).html(response.tabla);
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

    //// 2021
    $("#tp_id").change(function () {
        $("#tp_id option:selected").each(function () {
          dep_id=$('[name="dep_id"]').val();
          dist_id=$('[name="dist_id"]').val();
          tp_id=$(this).val();
          document.getElementById("update_eval").style.display = 'none';
          if(tp_id!=0){
              $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
              var url = base+"index.php/reporte_evalform4/crep_evalinstitucional/get_cuadro_evaluacion_institucional";
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
                      $('#lista_consolidado').fadeIn(1000).html(response.tabla);
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
////----- End menu select


  //// Actualizar Evaluacion Trimestral por Regional 2021
    $("#da").change(function () {
        $("#da option:selected").each(function () {
          dep_id=$(this).val();
          tp_id=4;
          document.getElementById("da").disabled=true;
          document.getElementById("loadd").style.display = 'block';
          var url = base+"index.php/reporte_evalform4/crep_evalunidad/update_evaluacion_trimestral_institucional";
          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "dep_id="+dep_id+"&tp_id="+tp_id
          });

          request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              document.getElementById("loadd").style.display = 'none';
              $('#loadd').fadeIn(1000).html(response.tabla);
          }
          else{
              alertify.error("ERROR AL RECUPERAR DATOS");
          }

          });
          request.fail(function (jqXHR, textStatus, thrown) {
              console.log("ERROR: " + textStatus);
          });
        });
      });





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

    /// 2021
    /*---- CUADRO DE CUMPLIMIENTO POR UNIDAD-REGIONAL ----*/
    $(function () {
        $(".eficacia_unidad").on("click", function (e) {
            dep_id=$('[name="dep_id"]').val();
            dist_id=$('[name="dist_id"]').val();
            tp_id=$('[name="tp_id"]').val();
          //  alert(dep_id+'--'+dist_id+'--'+tp_id)

            $('#lista').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:100%;"/></div>');
            $('#parametro_eficacia').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:100%;"/></div>');
           
            document.getElementById("boton_eficacia").style.display = 'none';
            var url = base+"index.php/reporte_evalform4/crep_evalinstitucional/get_unidades_eficiencia";
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
                $('#lista').fadeIn(1000).html(response.tabla);
              //  $('#parametro_eficacia').fadeIn(1000).html(response.parametro_eficacia);
                $('#print_eficacia').slideDown();
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

        //// 2021
        $(".eficacia_prog").on("click", function (e) {
            dep_id=$('[name="dep_id"]').val();
            dist_id=$('[name="dist_id"]').val();
            tp_id=$('[name="tp_id"]').val();

            $('#lista_prog').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:100%;"/></div>');
            $('#parametros_prog').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:100%;"/></div>');
           
            document.getElementById("boton_eficacia_prog").style.display = 'none';
            var url = base+"index.php/reporte_evalform4/creportes_evaluacionpoa/get_programas_parametros";
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
                $('#lista_prog').fadeIn(1000).html(response.tabla_prog);
                $('#parametros_prog').fadeIn(1000).html(response.parametro_eficacia_prog);
                $('#print_eficacia_prog').slideDown();
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


    /// 2022
    /*---- CUADRO DE EJECUCION DE CERTIFICACION POA ----*/
    $(function () {
        //// 2022
        $(".ejecucion_certpoa").on("click", function (e) {
            dep_id=$('[name="dep_id"]').val();
            dist_id=$('[name="dist_id"]').val();
            tp_id=$('[name="tp_id"]').val();
            //alert(dep_id+'-'+dist_id+'-'+tp_id)
            $('#lista_certpoa').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:50%;"/></div>');
            //$('#ejecucion_certpoa').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:100%;"/></div>');
           
            document.getElementById("boton_ejec_certpoa").style.display = 'none';
            var url = base+"index.php/reporte_evalform4/creportes_evaluacionpoa/get_ejecucion_certpoa";
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
              $('#lista_certpoa').fadeIn(1000).html(response.tabla);
              graf_regresion_trimestral_temporalidad_prog_ejec('graf_form5',response.matriz_form5,'CUADRO DE EJECUCION CERT. POA - FORMULARIO N° 5 (REQUERIMIENTOS)',response.titulo_rep,'% EJECUCION CERT. POA') /// formulario 5
              graf_regresion_trimestral_temporalidad_prog_ejec('graf_form4',response.matriz_form4,'CUADRO CUMPLIMIENTO DE <b>METAS</b> FORMULARIO N° 4 (ACTIVIDADES)',response.titulo_rep,'% CUMPLIMIENTO DE METAS') /// formulario 4
            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS DE EJECUCION CERT. POA ");
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


    /*---- CUADRO DE EJECUCION POR PARTIDAS ----*/
    $(function () {
      //// 2022
      $(".ejecucion_partidas").on("click", function (e) {
          dep_id=$('[name="dep_id"]').val();
          dist_id=$('[name="dist_id"]').val();
          tp_id=$('[name="tp_id"]').val();
         // alert(dep_id+'-'+dist_id+'-'+tp_id)
          $('#lista_partidas').html('<div class="loadin" align="center"><br><br><br><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" style="width:50%;"/></div>');
          
          document.getElementById("boton_ejec_partidas").style.display = 'none';
          var url = base+"index.php/reporte_evalform4/creportes_evaluacionpoa/get_ejecucion_x_partida";
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
            $('#lista_partidas').fadeIn(1000).html(response.tabla);
          }
          else{
              alertify.error("ERROR AL RECUPERAR DATOS DE EJECUCION POR PARTIDAS ");
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


    /// Grafico regresion por trimestre
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
          text: tit_laterales
        }
      },
      yAxis: {
        // Pongo el título para el eje de las 'Y'
        title: {
          text: ''
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
  function imprimir_form5() {
    var cabecera = document.querySelector("#cabecera_ejec");
    var grafico = document.querySelector("#grafico_form5");
    document.getElementById("cabecera_ejec").style.display = 'block';
    var cabecera = document.querySelector("#cabecera_ejec");
    document.getElementById("tabla_impresion_form5").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_form5");
    imprimirGrafico(grafico,cabecera,tabla);
    document.getElementById("cabecera_ejec").style.display = 'none';
    document.getElementById("tabla_impresion_form5").style.display = 'none';
  }

  function imprimir_partidas() {
    var cabecera = document.querySelector("#cabecera_partidas");
   // var grafico = document.querySelector("#grafico_form5");
    document.getElementById("cabecera_partidas").style.display = 'block';
    var cabecera = document.querySelector("#cabecera_partidas");
    document.getElementById("tabla_impresion_partidas").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_partidas");
    imprimirGrafico('detalle',cabecera,tabla);
    document.getElementById("cabecera_partidas").style.display = 'none';
    document.getElementById("tabla_impresion_partidas").style.display = 'none';
  }

  function imprimir_form4() {
    var cabecera = document.querySelector("#cabecera_ejec");
    var grafico = document.querySelector("#grafico_form4");
    document.getElementById("cabecera_ejec").style.display = 'block';
    var cabecera = document.querySelector("#cabecera_ejec");
    document.getElementById("tabla_impresion_form4").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_form4");
    imprimirGrafico(grafico,cabecera,tabla);
    document.getElementById("cabecera_ejec").style.display = 'none';
    document.getElementById("tabla_impresion_form4").style.display = 'none';
  }

  function imprimirGrafico(grafico,cabecera,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN POA FORM. N° 4 y FORM. N° 5</title>');
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
    document.querySelector("#btnImprimir_evaluacion_trimestre").addEventListener("click", function() {
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
    });


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

    ///// =============== GRAFICO PASTEL HIGTHCARS Y CANVAS

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
              name: 'N°. ACT. NO CUMPLIDAS : '+Math.round((tab6-tab8),2)+'%',
              y: +Math.round((tab6-tab8),2),
              color: '#f98178',
            },

            {
              name: 'N°. ACT. CUMPLIDAS PARCIALMENTE : '+tab8+'%',
              y: +tab8,
              color: '#f5eea3',
            },

            {
              name: 'N°. ACT. CUMPLIDAS : '+tab5+'%',
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

