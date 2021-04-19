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


    function verif_valor(programado,ejecutado,nro){
      if(ejecutado!== '' & ejecutado!== 0){
        if(ejecutado<=programado){
            $('#but'+nro).slideDown();
            document.getElementById("ejec"+nro).style.backgroundColor = "#ffffff";
            document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
        }
        else{
            alertify.error("ERROR EN EL DATO REGISTRADO !");
             document.getElementById("ejec"+nro).style.backgroundColor = "#fdeaeb";
            $('#but'+nro).slideUp();
        }
      }
      else{
        $('#but'+nro).slideUp();
      }
    }




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
      ventana.document.write("<p>");
      ventana.document.write("<div style='font-size: 10px;'>[Copyright]:Departamento Nacional de Planificación - Sistema de Planificación de Salud SIIPLAS V.2</div>");
      ventana.document.write("<\/p>");
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


    document.querySelector("#btnImprimir_seguimiento").addEventListener("click", function() {
      var grafico = document.querySelector("#Seguimiento");

      document.getElementById("cabecera").style.display = 'block';
      var cabecera = document.querySelector("#cabecera");

      var eficacia = document.querySelector("#efi");

      document.getElementById("tabla_componente_impresion").style.display = 'block';
      document.getElementById("tabla_componente_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_componente_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera").style.display = 'none';
      
      document.getElementById("tabla_componente_vista").style.display = 'block';
      document.getElementById("tabla_componente_impresion").style.display = 'none';
    });


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

    /// Cambio de mes para el seguimiento 
    $("#mes_id").change(function () {
        $("#mes_id option:selected").each(function () {
            mes_id=$(this).val();
            mes_activo=$('[name="mes_activo"]').val();
            if(mes_id!=mes_activo){
              var url = base+"index.php/ejecucion/cseguimiento/get_update_mes";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "mes_id="+mes_id
              });

              request.done(function (response, textStatus, jqXHR) {
                  if (response.respuesta == 'correcto') {
                      alertify.alert("SE CAMBIO AL MES CORRECTAMENTE ", function (e) {
                          if (e) {
                              window.location.reload(true);
                          }
                      })
                  }
                  else{
                      alertify.error("ERROR !!!");
                  }
              }); 
            }
        });
      })



    $(function () {
        $(".enlace").on("click", function (e) {
          prod_id = $(this).attr('name');
           //$('#temporalidad').html('<div class="loading" align="center"><img src='+base+'"/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando Información</div>');
            var url = base+"index.php/ejecucion/cseguimiento/get_temporalidad";
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
              $('#temporalidad').fadeIn(1000).html(response.tabla);
              $('#calificacion').fadeIn(1000).html(response.calificacion);
            }
            else{
                alertify.error("ERROR AL RECUPERAR TEMPORALIDAD");
            }

            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
            });
            e.preventDefault();
          
        });
    });


        /*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL ------*/
        $(function () {
          $(".update_eval").on("click", function (e) {
              com_id = $(this).attr('name');
              document.getElementById("com_id").value=com_id;
              $('#tit').html('<font size=3><b>'+$(this).attr('id')+'</b></font>');
              $('#but').slideUp();

              var url = base+"index.php/ejecucion/cseguimiento/update_evaluacion_trimestral";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "com_id="+com_id
              });

              request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                  $('#content_valida').fadeIn(1000).html(response.tabla);
                  $('#but').slideDown();
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
                    document.getElementById("but").style.display = 'none';
                    document.getElementById("load").style.display = 'block';
                    alertify.success("ACTUALIZACIÓN EXITOSA ...");
                }
              });
          });
        });


        /// Eliminar Seguimiento Mensual
        $(function () {
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

          $(".del_ope").on("click", function (e) {
            reset();
            var prod_id = $(this).attr('name'); // prod id
            var mes_id = $(this).attr('id'); // mes id
          
            var request;
            alertify.confirm("ESTA SEGURO DE ELIMINAR EL SEGUIMIENTO POA ?", function (a) {
              if (a) {
                  url = base+"index.php/ejecucion/cseguimiento/delete_seguimiento_operacion";
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: "json",
                      data: "prod_id="+prod_id+"&mes_id="+mes_id
                  });

                  request.done(function (response, textStatus, jqXHR) { 
                    reset();
                    if (response.respuesta == 'correcto') {
                        alertify.alert("EL SEGUIMIENTO SE ELIMINO CORRECTAMENTE ", function (e) {
                          if (e) {
                            document.getElementById("loading").style.display = 'block';
                            window.location.reload(true);
                            alertify.success("Función Ejecutada Exitosamente ...");
                          }
                        });
                    } else {
                        alertify.alert("ERROR AL ELIMINAR SEGUIMIENTO POA !!!", function (e) {
                          if (e) {
                              window.location.reload(true);
                          }
                        });
                    }
                  });
                  request.fail(function (jqXHR, textStatus, thrown) {
                      console.log("ERROR: " + textStatus);
                  });
                  request.always(function () {
                      //console.log("termino la ejecuicion de ajax");
                  });

                  e.preventDefault();

              } else {
                  alertify.error("Opcion cancelada");
              }
            });
            return false;
          });

        });