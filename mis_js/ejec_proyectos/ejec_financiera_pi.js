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


  //// Verificando valor ejecutado por partida
  function verif_valor(valor_original,ejecutado,sp_id,mes_id,aper_id){
   /// tp 0 : Registro
   /// tp 1 : modifcacion  
    if(ejecutado!= ''){
      var url = base+"index.php/ejecucion/cejecucion_pi/verif_valor_ejecutado_x_partida";
        var request;
        if (request) {
          request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "ejec="+ejecutado+"&sp_id="+sp_id+"&aper_id="+aper_id+"&mes_id="+mes_id+"&valor_inicial="+valor_original
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
            $('#but'+sp_id).slideDown();
            document.getElementById("ejec"+sp_id).style.backgroundColor = "#ffffff";
            document.getElementById('total_fin'+sp_id).innerHTML = 'Bs. '+ new Intl.NumberFormat().format(response.ejecucion_total_partida);
            document.getElementById('avance_fin'+sp_id).innerHTML = response.avance_fin_partida+' %';
            document.getElementById('ppto_total_ejec_proy'+aper_id).innerHTML = new Intl.NumberFormat().format(response.ejecucion_total_pi);
            document.getElementById('ejec_pi'+aper_id).innerHTML = response.avance_fin_pi+' %';
        }
        else{
            alertify.error("ERROR EN EL DATO REGISTRADO !");
            document.getElementById("ejec"+sp_id).style.backgroundColor = "#fdeaeb";
            document.getElementById('total_fin'+sp_id).innerHTML = 'null';
            document.getElementById('avance_fin'+sp_id).innerHTML = 'null';
            document.getElementById('ejec_pi'+aper_id).innerHTML = 'null %';
            $('#but'+sp_id).slideUp();
        }

      });
    }
    else{
      $('#but'+sp_id).slideUp();
    }
  }


  //// Verificando valor ejecutado por partida
  function verif_observacion(registro,sp_id){ 
    ejec=parseFloat($('[id="ejec'+sp_id+'"]').val());

    if(registro.length>30){
      $('#but'+sp_id).slideDown();
    }
    else{
      if(ejec!=0){
        $('#but'+sp_id).slideUp();
      }
      else{
        $('#but'+sp_id).slideDown();  
      }
      
    }
  }


  /// Funcion para guardar datos de la ejecucion presupuestaria
  function guardar(sp_id){
    ejec=parseFloat($('[id="ejec'+sp_id+'"]').val());
    observacion=($('[id="obs'+sp_id+'"]').val());

    if(observacion.length==0 & observacion.length<30){
        document.getElementById("obs"+sp_id).style.backgroundColor = "#fdeaeb";
        alertify.error("REGISTRE OBSERVACION > 30 CARACTERES");
        return 0; 
    }
    else{
        document.getElementById("obs"+sp_id).style.backgroundColor = "#ffffff";
      //  alert("prod_id="+prod_id+" &ejec="+ejec+" &mv="+mverificacion+" &obs="+problemas+" &acc="+accion)
        alertify.confirm("GUARDAR EJECUCION PRESUPUESTARIA ?", function (a) {
        if (a) {
            var url = base+"index.php/ejecucion/cejecucion_pi/guardar_ppto_ejecutado";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "sp_id="+sp_id+"&ejec="+ejec+"&obs="+observacion
            });

            request.done(function (response, textStatus, jqXHR) {

            if (response.respuesta == 'correcto') {
                alertify.alert("LA EJECUCION SE REGISTRO CORRECTAMENTE ", function (e) {
                  if (e) {
                    document.getElementById('ejec'+sp_id).innerHTML = response.ppto_mes;
                    document.getElementById('obs'+sp_id).innerHTML = response.obs_mes;
                    document.getElementById("tr_color_partida"+sp_id).style.backgroundColor = "#edf7ec";
                    document.getElementById('success_partida'+sp_id).innerHTML = '<img src="'+base+'/assets/ifinal/ok1.png"/><br><font color=green><b>ACTUALIZADO !!</b></font>';
                    alertify.success("REGISTRO EXITOSO ...");
                  }
                });
            }
            else{
                alertify.error("ERROR AL GUARDAR EJECUCIÓN POA");
            }

            });
        } else {
            alertify.error("OPCI\u00D3N CANCELADA");
        }
      });
    }
  }

  ////// FORMULARIO DE PROYECTOS DE INVERSION
  /// Funcion para guardar datos del Proyecto de Inversion
  function guardar_pi(proy_id){
    estado=parseFloat($('[id="est_proy'+proy_id+'"]').val());
    avance_fisico=($('[id="efis_pi'+proy_id+'"]').val());

    alertify.confirm("GUARDAR DATOS DEL PROYECTO ?", function (a) {
      if (a) {
        var url = base+"index.php/ejecucion/cejecucion_pi/guardar_datos_proyecto";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id+"&estado="+estado+"&fis="+avance_fisico
        });

        request.done(function (response, textStatus, jqXHR) {

        if (response.respuesta == 'correcto') {
            alertify.alert("SE REGISTRO CORRECTAMENTE ", function (e) {
              if (e) {
                document.getElementById('efis_pi'+proy_id).innerHTML = response.proyecto[0]['avance_fisico'];
                //document.getElementById('est_proy'+proy_id).innerHTML = response.proyecto[0]['proy_estado'];
                document.getElementById("tr_color"+proy_id).style.backgroundColor = "#edf7ec";
                document.getElementById('success'+proy_id).innerHTML = '<img src="'+base+'/assets/ifinal/ok1.png"/><br><font color=green><b>ACTUALIZADO !!</b></font>';
                alertify.success("REGISTRO EXITOSO ...");
              }
            });
        }
        else{
            alertify.error("ERROR AL GUARDAR INFORMACION POA");
        }

        });
      } else {
          alertify.error("OPCI\u00D3N CANCELADA");
      }
    });
  }


  //// verificando ejecucion fisica
  function verif_pi_ejecfis(proy_id,valor_antiguo, valor_nuevo){
    if(valor_antiguo!=valor_nuevo){
      document.getElementById("tr_color"+proy_id).style.backgroundColor = "#ffffff";
      document.getElementById('success'+proy_id).innerHTML = '<img src="'+base+'/assets/ifinal/interogacion.png" width:50px; height=50px;/><br><font color=green><b>ACTUALIZAR DATOS !!</b></font>';
    }
  }
