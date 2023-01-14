base = $('[name="base"]').val();

function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "SEGUIMIENTO POA" , "width=800,height=700,scrollbars=NO") ; 
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

    //// funcion para generar el cuadro de seguimiento POa Mensual (cuadro, grafico)
  function generar_reporte(proy_id){

    $('#reporte').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando Informaciónn...</div>');
    var url = base+"index.php/reporte_ejecucion_proyectos/consulta_pi/get_reporte_proyecto";
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
          document.getElementById('reporte').innerHTML = response.iframe;
      }
      else{
          alertify.error("ERROR !!!");
      }
    }); 
  }




       



