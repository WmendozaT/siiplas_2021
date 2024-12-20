base = $('[name="base"]').val();
com_id = $('[name="com_id"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE FORMULARIO N° 4" , "width=800,height=700,scrollbars=NO") ; 
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
    
$(document).ready(function() {
  pageSetUp();
  /* BASIC ;*/
      var responsiveHelper_dt_basic = undefined;
      var responsiveHelper_datatable_fixed_column = undefined;
      var responsiveHelper_datatable_col_reorder = undefined;
      var responsiveHelper_datatable_tabletools = undefined;
      
      var breakpointDefinition = {
          tablet : 1024,
          phone : 480
      };

  /* END BASIC */
  
  /* COLUMN FILTER  */
  var otable = $('#datatable_fixed_column').DataTable({
      "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
              "t"+
              "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
      "autoWidth" : true,
      "preDrawCallback" : function() {
          // Initialize the responsive datatables helper once.
          if (!responsiveHelper_datatable_fixed_column) {
              responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
          }
      },
      "rowCallback" : function(nRow) {
          responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
      },
      "drawCallback" : function(oSettings) {
          responsiveHelper_datatable_fixed_column.respond();
      }       
  
  });
  
  // custom toolbar
//  $("div.toolbar").html('');
  // Apply the filter
  $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
      otable
          .column( $(this).parent().index()+':visible' )
          .search( this.value )
          .draw();   
  } );
  /* END COLUMN FILTER */   
})



  //// Subir archivo de participantes
  $(function () {
    //SUBIR ARCHIVO participantes
    $("#subir_archivo").on("click", function () {
      var $valid = $("#form_subir_sigep").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        if(document.getElementById('archivo').value==''){
          alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
          return false;
        }

        alertify.confirm("SUBIR ARCHIVO ?", function (a) {
          if (a) {
              document.getElementById("loads1").style.display = 'block';
              document.getElementById('subir_archivo').disabled = true;
              document.forms['form_subir_sigep'].submit();
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }
    });
  });


    /// GUARDAR NUEVO FORMULARIO N4 2025
    $(function () {
        $("#subir_event").on("click", function () {
          var $validator = $("#form_nuevo").validate({
              rules: {
                even_org: { /// ORGANIZADOR
                  required: true,
                },
                even_tp: { /// TIPO DE EVENTO
                    required: true,
                },
                even_fech_impresion: {
                    required: true,
                },
                evento: {
                    required: true,
                },
                fecha_even: {
                    required: true,
                },
                cod_even: {
                    required: true,
                }
              },
              messages: {
                even_org: {required: "<font color=red size=1>ORGANIZADOR</font>"},
                even_tp: {required: "<font color=red size=1>TIPO DE EVENTO</font>"},
                even_fech_impresion: {required: "<font color=red size=1>FECHA DE IMPRESION</font>"},
                evento: {required: "<font color=red size=1>TITULO DEL EVENTO</font>"},
                cod_even: {required: "<font color=red size=1>CODIGO DEL EVENTO</font>"},
                fecha_even: {required: "<font color=red size=1>DATOS DEL EVENTO</font>"}                   
              },
              highlight: function (element) {
                  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
              },
              unhighlight: function (element) {
                  $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
              },
              errorElement: 'span',
              errorClass: 'help-block',
              errorPlacement: function (error, element) {
                  if (element.parent('.input-group').length) {
                      error.insertAfter(element.parent());
                  } else {
                      error.insertAfter(element);
                  }
              }
          });

          var $valid = $("#form_nuevo").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } else {
            alertify.confirm("GUARDAR DATOS DEL EVENTO ?", function (a) {
              if (a) {
                  document.getElementById("loadp").style.display = 'block';
                  document.forms['form_nuevo'].submit();
                  document.getElementById("subir_event").style.display = 'none';
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
            });
          }
      });
    });


    //// ACTUALIZAR DATOS DEL EVENTO
    function update_evento(nro,even_id,name_input){ /// 
      // nro: 1 (cod evento)
      // nro: 2 (tipo de evento)
      // nro: 3 (evento)
      // nro: 4 (organizador)
      // nro: 5 (datos del evento)
      // nro: 6 (fecha de impresion)
      informacion = document.getElementById(name_input+even_id).value;

      var url = base+"index.php/mantenimiento/ceventos_dnp/update_datos_evento";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "even_id="+even_id+"&nro="+nro+"&detalle="+informacion+"&name_input="+name_input
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
          alertify.success("ok");
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

      });
    }


    /// GUARDAR PARTICIPANTE
    $(function () {
        $("#subir_participante").on("click", function () {
          var $validator = $("#form_nuevo2").validate({
              rules: {
                even_id: { /// Id
                  required: true,
                },
                ci: { /// Ci
                    required: true,
                },
                nombre: {
                    required: true,
                },
                tp_cert: {
                    required: true,
                }
              },
              messages: {
                ci: {required: "<font color=red size=1>REGISTRE CI</font>"},
                nombre: {required: "<font color=red size=1>REGISTRE NOMBRE</font>"},
                tp_cert: {required: "<font color=red size=1>FECHA DE IMPRESION</font>"}                  
              },
              highlight: function (element) {
                  $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
              },
              unhighlight: function (element) {
                  $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
              },
              errorElement: 'span',
              errorClass: 'help-block',
              errorPlacement: function (error, element) {
                  if (element.parent('.input-group').length) {
                      error.insertAfter(element.parent());
                  } else {
                      error.insertAfter(element);
                  }
              }
          });

          var $valid = $("#form_nuevo2").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } else {
            alertify.confirm("GUARDAR REGISTRO ?", function (a) {
              if (a) {
                  document.getElementById("loadp2").style.display = 'block';
                  document.forms['form_nuevo2'].submit();
                  document.getElementById("subir_participante").style.display = 'none';
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
            });
          }
      });
    });


    //// ACTUALIZAR DATOS DEL PARTICIPANTE
    function update_participante(nro,ci_id,name_input){ /// 
      // nro: 1 (ci)
      // nro: 2 (nombre)
      // nro: 3 (tipo de certificado)
      informacion = document.getElementById(name_input+ci_id).value;

      var url = base+"index.php/mantenimiento/ceventos_dnp/update_datos_participante";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "ci_id="+ci_id+"&nro="+nro+"&detalle="+informacion+"&name_input="+name_input
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
          $('#content1').fadeIn(1000).html('');
          alertify.success("ok");
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

      });
    }


    //// ACTUALIZAR tipo de Certificado
    function update_select_option(tp,id,ci_id){ /// 
      var url = base+"index.php/mantenimiento/ceventos_dnp/update_tp";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "ci_id="+ci_id+"&id="+id+"&tp="+tp
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
        $('#content1').fadeIn(1000).html('');
        window.location.reload(true);
          alertify.success("Seleccion procesada correctamente ...");
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

      });
    }

    //// ELIMINAR participante
  function delete_participante(ci_id){
    alertify.confirm("DESEA ELIMINAR PARTICIPANTE ?", function (a) {
        if (a) { 
        //  alert(prod_id)
          var url = base+"index.php/mantenimiento/ceventos_dnp/elimina_participante";
          
          request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "ci_id="+ci_id
          });

          request.done(function (response, textStatus, jqXHR) { 
            if (response.respuesta == 'correcto') {
                alertify.success("Eliminado correctamente ...");
                window.location.reload(true);
            } else {
              alertify.danger("Error ...");
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
            // user clicked "cancel"
            alertify.error("CANCELADA");
        }
      });
    return false;
  }


///----------- GENERA CERTIFICADO

function ver_certificado(ci_id,even_id) {

  $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Certificado</div>');
    var url = base+"index.php/mantenimiento/ceventos_dnp/get_certificado";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "ci_id="+ci_id+"&even_id="+even_id
    });

    request.done(function (response, textStatus, jqXHR) {

    if (response.respuesta == 'correcto') {
        $('#content1').fadeIn(1000).html(response.tabla);
    }
    else{
        alertify.error("ERROR AL RECUPERAR DATOS");
    }

    });
}



/*  $(function () {
    $(".enlace").on("click", function (e) {

        ci_id = $(this).attr('name');
        even_id = $(this).attr('id');
        //alert(ci_id)
        $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Certificado</div>');
        
        var url = base+"index.php/mantenimiento/ceventos_dnp/get_certificado";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "ci_id="+ci_id+"&even_id="+even_id
        });

        request.done(function (response, textStatus, jqXHR) {

        if (response.respuesta == 'correcto') {
            $('#content1').fadeIn(1000).html(response.tabla);
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS");
        }

        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
        e.preventDefault();
      
    });
});*/