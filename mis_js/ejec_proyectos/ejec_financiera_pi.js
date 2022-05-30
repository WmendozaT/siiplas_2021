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
  function verif_valor(ejecutado,sp_id,mes_id,tp){
   /// tp 0 : Registro
   /// tp 1 : modifcacion  

    if(ejecutado!= ''){
      var url = base+"index.php/ejecucion/cejecucion_pi/verif_valor_ejecutado_x_partida";
      $.ajax({
        type:"post",
        url:url,
        data:{ejec:ejecutado,sp_id:sp_id,tp:tp,mes_id:mes_id},
        success:function(datos){
         if(datos.trim() =='true'){

          $('#but'+sp_id).slideDown();
          document.getElementById("ejec"+sp_id).style.backgroundColor = "#ffffff";
        //  document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
         }
         else{
          alertify.error("ERROR EN EL DATO REGISTRADO !");
          document.getElementById("ejec"+sp_id).style.backgroundColor = "#fdeaeb";
          $('#but'+sp_id).slideUp();
         }

      }});
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
                     // window.location.reload(true);
                      //document.getElementById("loading").style.display = 'block';
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

