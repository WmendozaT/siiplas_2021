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
