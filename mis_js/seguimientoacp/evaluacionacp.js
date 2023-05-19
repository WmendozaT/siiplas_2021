base = $('[name="base"]').val();
gestion = $('[name="gestion"]').val();

function abreVentana_eficiencia(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "EVALUACION ACP" , "width=800,height=700,scrollbars=NO") ; 
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




  //// imprimir ACP (Institucional)
  function imprimir_grafico() {
    var grafico = document.querySelector("#grafico_gestion");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    var calificacion = document.querySelector("#calificacion");
    document.getElementById("tabla_impresion_detalle").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle");
    imprimirevaluacionform1(grafico,cabecera,calificacion,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle").style.display = 'none';
  }

  function imprimirevaluacionform1(grafico,cabecera,calificacion,tabla) {
    var ventana = window.open('Evaluacion FORMULARIO N° 1 ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EVALUACION ACCIONES DE CORTO PLAZO INSTITUCIONAL - FORM. N° 1</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    //ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(calificacion.innerHTML);
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
