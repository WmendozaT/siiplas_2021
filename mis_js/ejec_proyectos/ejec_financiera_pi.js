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


  //// Verificando valor ejecutado por form 4
  // this.value,'.$partida['sp_id'].','.$this->verif_mes[1].'
  function verif_valor(monto,sp_id,mes_id){
    alert(monto+'--'+sp_id+'--'+mes_id)
   /// tp 0 : Registro
   /// tp 1 : modifcacion  
  
/*    if(ejecutado!= ''){
      var url = base+"index.php/ejecucion/cseguimiento/verif_valor_ejecutado_x_form4";
      $.ajax({
        type:"post",
        url:url,
        data:{ejec:ejecutado,prod_id:prod_id,tp:tp,mes_id:mes_id},
        success:function(datos){

         if(datos.trim() =='true'){

          $('#but'+nro).slideDown();
          document.getElementById("ejec"+nro).style.backgroundColor = "#ffffff";
          document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
         }
         else{
          alertify.error("ERROR EN EL DATO REGISTRADO !");
           document.getElementById("ejec"+nro).style.backgroundColor = "#fdeaeb";
          $('#but'+nro).slideUp();
         }

      }});
    }
    else{
      $('#but'+nro).slideUp();
    }*/


  }


    /// Funcion para guardar datos de seguimiento POA
    function guardar(prod_id,nro){
      ejec=parseFloat($('[id="ejec'+nro+'"]').val());
      mverificacion=($('[id="mv'+nro+'"]').val());
      problemas=($('[id="obs'+nro+'"]').val());
      accion=($('[id="acc'+nro+'"]').val());

      if(($('[id="mv'+nro+'"]').val())==0){
          document.getElementById("mv"+nro).style.backgroundColor = "#fdeaeb";
          alertify.error("REGISTRE MEDIO DE VERIFICACIÓN, Operación "+nro);
          return 0; 
      }
      else{
          document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
        //  alert("prod_id="+prod_id+" &ejec="+ejec+" &mv="+mverificacion+" &obs="+problemas+" &acc="+accion)
          alertify.confirm("GUARDAR SEGUIMIENTO POA?", function (a) {
          if (a) {
              var url = base+"index.php/ejecucion/cseguimiento/guardar_seguimiento";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "prod_id="+prod_id+"&ejec="+ejec+"&mv="+mverificacion+"&obs="+problemas+"&acc="+accion
              });

              request.done(function (response, textStatus, jqXHR) {

              if (response.respuesta == 'correcto') {
                  alertify.alert("SE REGISTRO CORRECTAMENTE ", function (e) {
                      if (e) {
                          window.location.reload(true);
                          document.getElementById("loading").style.display = 'block';
                          alertify.success("REGISTRO EXITOSO ...");
                      }
                  });
              }
              else{
                  alertify.error("ERROR AL GUARDAR SEGUIMIENTO POA");
              }

              });
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }
    }

