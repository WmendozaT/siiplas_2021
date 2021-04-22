base = $('[name="base"]').val();

function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "CERTIFICACIÓN POA" , "width=800,height=700,scrollbars=NO") ; 
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

    ///// Obtiene Lista de requerimientos
    $("#prod_id").change(function () {
        $("#prod_id option:selected").each(function () {
            prod_id=$(this).val();
           // alert(prod_id)
            if(prod_id!=0){
              document.getElementById("loading").style.display = 'block';
              $('#lista_requerimientos').fadeIn(1000).html('');
              var url = base+"index.php/ejecucion/ccertificacion_poa/get_cuadro_certificacionpoa";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "prod_id="+prod_id
              });

              request.done(function (response, textStatus, jqXHR) {
                  if (response.respuesta == 'correcto') {
                    document.getElementById("loading").style.display = 'none';
                    $('#lista_requerimientos').fadeIn(1000).html(response.requerimientos);
                  }
                  else{
                      alertify.error("ERROR AL LISTAR");
                  }
              }); 
            }
            else{
              $('#lista_requerimientos').fadeIn(1000).html('');
            }
        });
    });


      function seleccionarFilacompleta(ins_id,nro,estaChequeado) {
        if (estaChequeado == true) { 
          document.getElementById("tr"+nro).style.backgroundColor = "#c6f1d7";
        }
        else{
          document.getElementById("tr"+nro).style.backgroundColor = "";
        }

        valf = parseInt($('[name="tot"]').val());
        valm = parseInt($('[name="tot_temp"]').val());
        if (estaChequeado == true) {
          valf = valf + 1;
          valm = valm + 1;
        } else {
          valf = valf - 1;
          valm = valm - 1;
        }

        $('[name="tot"]').val((valf).toFixed(0));
        $('[name="tot_temp"]').val((valm).toFixed(0));
        
        totalf = parseFloat($('[name="tot"]').val());
        total = parseFloat($('[name="tot_temp"]').val());
        if(total==0 || totalf==0){
            $('#but').slideUp();
          }
          else{
            $('#but').slideDown();
          }
      }

      function seleccionarFila(ins_id, estaChequeado) {
        if (estaChequeado == true) {            
          for (var i = 1; i <=12; i++) {
            document.getElementById("m"+i+""+ins_id).style.display='block';
          }
        } 
        else {
          for (var i = 1; i <=12; i++) {
            document.getElementById("m"+i+""+ins_id).style.display='none';
          }
        }

        val = parseInt($('[name="tot"]').val());
        if (estaChequeado == true) {
          val = val + 1;
        } else {
          val = val - 1;
        }
        $('[name="tot"]').val((val).toFixed(0));
        totalf = parseFloat($('[name="tot"]').val());
        total = parseFloat($('[name="tot_temp"]').val());
        if(totalf==0 || total==0){
          $('#but').slideUp();
        }
        else{
          $('#but').slideDown();
        }
      }

      function seleccionar_temporalidad(tins_id, estaChequeado) {
        if (estaChequeado == true) { 
          val = parseInt($('[name="tot_temp"]').val());
        var url = base+"index.php/ejecucion/ccertificacion_poa/verif_mes_certificado";
          $.ajax({
            type:"post",
            url:url,
            data:{tins_id:tins_id},
            success:function(datos){
              if(datos.trim() =='true'){ /// habilitado para certificar

                val = val + 1;
                $('[name="tot_temp"]').val((val).toFixed(0));
                total = parseFloat($('[name="tot_temp"]').val());
                totalf = parseFloat($('[name="tot"]').val());
                if(total==0 || totalf==0){
                  $('#but').slideUp();
                }
                else{
                  $('#but').slideDown();
                }

              }else{ /// inhabilitado (ya se certifico anteriormente)
                 alertify.error("EL MES SELECCIONADO YA FUE CERTIFICADO ANTERIORMENTE !!!");
                val = val - 1;
                $('[name="tot_temp"]').val((val).toFixed(0));
                total = parseFloat($('[name="tot_temp"]').val());
                totalf = parseFloat($('[name="tot"]').val());
                if(total==0 || totalf==0){
                  $('#but').slideUp();
                }
                else{
                  $('#but').slideDown();
                }
              }
          }});
        } 
        else {
          val = val - 1;
          $('[name="tot_temp"]').val((val).toFixed(0));
          total = parseFloat($('[name="tot_temp"]').val());
          totalf = parseFloat($('[name="tot"]').val());

          if(total==0 || totalf==0){
            $('#but').slideUp();
          }
          else{
            $('#but').slideDown();
          }
        }
      }



  function reset() {
      $("#toggleCSS").attr("href", base+"assets/themes_alerta/alertify.default.css");
      alertify.set({
          labels: {
              ok: "ACEPTAR",
              cancel: "CANCELAR"
          },
          delay: 5000,
          buttonReverse: false,
          buttonFocus: "ok"
      });
    }

    $(function () {
      $("#btsubmit").on("click", function (e) {

        alertify.confirm("GENERAR SOLICITUD DE CERTIFICACI&Oacute;N POA ?", function (a) {
          if (a) {
              document.cert_form.submit();
              document.getElementById("load").style.display = 'block';
             document.getElementById("but").style.display = 'none';
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      });
    });


