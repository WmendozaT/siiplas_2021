base = $('[name="base"]').val();

function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE CONSOLIDADO EVALUACION POA" , "width=800,height=700,scrollbars=NO") ; 
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



 /*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL POR UNIDAD------*/
        $(function () {
          $(".update_eval_unidad").on("click", function (e) {
              proy_id = $(this).attr('name');
              document.getElementById("proy_id").value=proy_id;
            
              $('#tit').html('<font size=3><b>'+$(this).attr('id')+'</b></font>');
              $('#butt').slideUp();

              var url = base+"index.php/reporte_evaluacion/crep_evalunidad/update_evaluacion_trimestral";
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
        });









      //// Seguimiento POA
      function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {

      var ventana = window.open('Seguimiento Evaluacion POA ', 'PRINT', 'height=800,width=1000');
      ventana.document.write('<html><head><title>SEGUIMIENTO POA</title>');
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


    document.querySelector("#btnImprimir_evaluacion_trimestre").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_trimestre");
      
      document.getElementById("cabecera2").style.display = 'block';
      var cabecera = document.querySelector("#cabecera2");

      var eficacia = document.querySelector("#eficacia");
      
      document.getElementById("tabla_regresion_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera2").style.display = 'none';

      document.getElementById("tabla_regresion_vista").style.display = 'block';
      document.getElementById("tabla_regresion_impresion").style.display = 'none';
    });


    document.querySelector("#btnImprimir_evaluacion_pastel").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_pastel");
      
      document.getElementById("cabecera2").style.display = 'block';
      var cabecera = document.querySelector("#cabecera2");
      
      var eficacia = document.querySelector("#eficacia");

      document.getElementById("tabla_pastel_impresion").style.display = 'block';
      document.getElementById("tabla_pastel_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_pastel_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera2").style.display = 'none';

      document.getElementById("tabla_pastel_vista").style.display = 'block';
      document.getElementById("tabla_pastel_impresion").style.display = 'none';
    });




    document.querySelector("#btnImprimir_evaluacion_gestion").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_gestion");
      
      document.getElementById("cabecera3").style.display = 'block';
      var cabecera = document.querySelector("#cabecera3");
      
      var eficacia = document.querySelector("#efi");

      document.getElementById("tabla_regresion_total_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_total_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_total_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera3").style.display = 'none';

      document.getElementById("tabla_regresion_total_vista").style.display = 'block';
      document.getElementById("tabla_regresion_total_impresion").style.display = 'none';
    });

