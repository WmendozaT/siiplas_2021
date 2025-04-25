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


/// Boton de Impresion Cuadros de Evaluacion Institucional 2025
document.getElementById('btnImprimir_grafico_acp').addEventListener('click', function() {
  const plantillaPagina = (contenido, numeroPagina) => `
    <div class="pagina">
      ${contenido}
      <footer class="pie-pagina">
        <div class="marcas-agua">
          <span class="pagina-numero">Página ${numeroPagina} de 1</span>
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

          .pagina {
            page-break-after: always;
            position: relative;
            height: calc(297mm - 5cm);
          }

          .pie-pagina {
            position: running(pie-pagina);
            height: 2cm;
          }

          .membrete {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 15px;
            align-items: center;
            margin-bottom: 10px;
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
          ${document.getElementById('calificacion_trimestre').outerHTML}
          ${document.getElementById('cumplimiento_trimestral').outerHTML}
          ${document.getElementById('calificacion_gestion').outerHTML}
          ${document.getElementById('cumplimiento_gestion').outerHTML}
        `, 1)}
      </body>
    </html>
  `);

  ventanaImpresa.document.close();
  setTimeout(() => {
    ventanaImpresa.print();
    ventanaImpresa.close();
  }, 1000);
});

