  base = $('[name="base"]').val();

  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "EVALUACION FORM. N° 2" , "width=800,height=700,scrollbars=NO") ; 
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


    /// dep id
    $("#dep_id").change(function () {
      $("#dep_id option:selected").each(function () {
          elegido=$(this).val();
          $("#tp_id").slideDown();
          $("#aper_id").slideDown();
          $("#par_id").slideDown();

          $.post(base+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'tipo' }, function(data){
          $("#tp_id").html(data);
          $("#aper_id").html('');
          $("#par_id").html('');
          $("#lista_consolidado").html('SELECCIONE TIPO DE GASTO !');
          });
          
      });
  });

    ///// Tipo de Gasto
    $("#tp_id").change(function () {
      $("#tp_id option:selected").each(function () {
          tp_id=$(this).val();
          dep_id=$('[name="dep_id"]').val();
          
          if(dep_id==0){ /// Institucional
            $("#aper_id").slideUp();
            $("#par_id").slideUp();

              $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion ...</div>');
              var url = base+"index.php/reportes_cns/crep_consultafinanciera/get_ppto_institucional";
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
                      $('#tp_id').slideUp();
                      $("#aper_id").slideUp();
                      $("#par_id").slideUp();

                      $("#tp_id").html('');
                      $("#aper_id").html('');
                      $("#par_id").html('');

                      $("#lista_consolidado").html(response.detalle);
                  }
                  else{
                      alertify.error("ERROR AL LISTAR");
                  }
              }); 
          }
          else{ // Regional
            var url = base+"index.php/reportes_cns/crep_consultafinanciera/get_unidades";
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
                    $('#aper_id').fadeIn(1000).html(response.lista_unidades);
                    $("#par_id").html('');
                    $("#lista_consolidado").html('SELECCIONE UNIDAD / ESTABLECIMIENTO / PROYECTOS DE INVERSION');
                }
                else{
                    alertify.error("ERROR AL LISTAR");
                }
            }); 
          }
      });
    });

/// proyecto, unidad
    $("#aper_id").change(function () {
        $("#aper_id option:selected").each(function () {
            aper_id=$(this).val();
            dep_id=$('[name="dep_id"]').val();
            tp_id=$('[name="tp_id"]').val();
          
            var url = base+"index.php/reportes_cns/crep_consultafinanciera/get_partidas";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "dep_id="+dep_id+"&tp_id="+tp_id+"&aper_id="+aper_id
            });

            request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                    $('#par_id').fadeIn(1000).html(response.lista_partidas);
                    $("#lista_consolidado").html('');
                }
                else{
                    alertify.error("ERROR AL LISTAR");
                }
            }); 
             
        });
    });


/// partida
    $("#par_id").change(function () {
        $("#par_id option:selected").each(function () {
            par_id=$(this).val();
            dep_id=$('[name="dep_id"]').val();
            tp_id=$('[name="tp_id"]').val();
            aper_id=$('[name="aper_id"]').val();
          
            $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion ...</div>');
            var url = base+"index.php/reportes_cns/crep_consultafinanciera/get_reporte_ppto";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "dep_id="+dep_id+"&tp_id="+tp_id+"&aper_id="+aper_id+"&par_id="+par_id
            });

            request.done(function (response, textStatus, jqXHR) {
                if (response.respuesta == 'correcto') {
                    $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                }
                else{
                    alertify.error("ERROR AL LISTAR");
                }
            }); 
             
        });
    });



  //// Ver detalle del presupuesto programado (categoria programatica)por regional 
  function ver_detalle_ppto_poa_regional(dep_id,tp_id) {

    $('#detalle_ppto').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/load.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion ...</div>');
    var url = base+"index.php/reportes_cns/crep_consultafinanciera/get_ppto_poa_categoria_programatica_regional";
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
            $('#detalle_ppto').fadeIn(1000).html(response.detalle);
        }
        else{
            alertify.error("ERROR AL LISTAR");
        }
    }); 
  }

  /// ver detalle de partidas por PROGRAMA INSTITUCIONAL/regional
  function ver_detalle_ppto_poa_partida_prog_institucional(programa,tp_id,dep_id) {
    $('#detalle_ppto_partida_institucional').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion ...</div>');
      var url = base+"index.php/reportes_cns/crep_consultafinanciera/ver_detalle_ppto_poa_partida_prog_institucional";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "programa="+programa+"&tp_id="+tp_id+"&dep_id="+dep_id
      });

      request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              $('#detalle_ppto_partida_institucional').fadeIn(1000).html(response.detalle_partidas);
          }
          else{
              alertify.error("ERROR AL LISTAR DETALLE");
          }
      }); 
  }



  /// ver detalle de partidas por Unidades por PROGRAMA Y REGIONAL
  function ver_detalle_ppto_poa_partida_unidad_regional(programa,tp_id,dep_id) {
    $('#detalle_ppto_partida_regional').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion ...</div>');
      var url = base+"index.php/reportes_cns/crep_consultafinanciera/ver_detalle_ppto_poa_partida_prog_regional";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "programa="+programa+"&tp_id="+tp_id+"&dep_id="+dep_id
      });

      request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              $('#detalle_ppto_partida_regional').fadeIn(1000).html(response.detalle_partidas_uo);
          }
          else{
              alertify.error("ERROR AL LISTAR DETALLE");
          }
      }); 
  }









  