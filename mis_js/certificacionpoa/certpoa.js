  base = $('[name="base"]').val();

  function abreVentana_sol(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "SOLICITUD CERTIFICACIÓN POA" , "width=1000,height=900,scrollbars=NO") ; 
  }

  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "CERTIFICACIÓN POA" , "width=800,height=750,scrollbars=NO") ; 
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

    //// =========================LISTA DE CERTIFICACION POA (EDICION Y ANULACION) 
    function editar_certpoa(cert_id) {
        document.getElementById("cert_id").value = cert_id;
        var url = base+"index.php/ejecucion/cert_poa/get_datos_certificado";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "cert_id="+cert_id
        });

        request.done(function (response, textStatus, jqXHR) { 
            if (response.respuesta == 'correcto') {
                $('#titulo_edit').html('<h2 class="alert alert-warning"><center>EDICI&Oacute;N PARCIAL - CERTIFICACI&Oacute;N : '+response.certificado[0]['cpoa_codigo']+'</center></h2>');
                $('#titulo2').html('<font color="blue" size="3">U.E. '+response.certificado[0]['proy_nombre']+'</font>');
                document.getElementById("cite_edit").value = '';
                document.getElementById("justificacion_edit").value = '';
                $('#error1').html('NRO CITE');
                $('#error2').html('JUSTIFICACIÓN');
            } else {
                alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
            }
        });

        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
        
        // ===VALIDAR EL FORMULARIO DE MODIFICACION
        $("#anular_edit").on("click", function () {
            var error='false';
            var cite=document.getElementById('cite_edit').value;
            var justificacion=document.getElementById('justificacion_edit').value;
            if(!cite){
                $('#error1').html('<font color="red" size="1">NRO CITE (*)</font>');
                document.form_anular.cite_edit.focus() 
                return 0;
            }
            if(!justificacion){
                $('#error1').html('NRO CITE');
                $('#error2').html('<font color="red" size="1">JUSTIFICACIÓN (*)</font>');
                document.form_anular.justificacion_edit.focus() 
                return 0;
            }
         
            if(cite.length!=0 & justificacion.length!=0){
                $('#error1').html('NRO CITE');
                $('#error2').html('JUSTIFICACIÓN');
                 alertify.confirm("DESEA REALIZAR LA MODIFICACIÓN DE LA CERTIFICACI&Oacute;N ?", function (a) {
                    if (a) {
                        document.getElementById("loads").style.display = 'block';
                        document.forms['form_anular'].submit(); /// id del formulario
                        document.getElementById("but").style.display = 'none';
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
            else{
                alertify.error("REGISTRE DATOS");
            }
        });
    }

    function eliminar_certpoa(cert_id) {
      document.getElementById("cpoa_id").value = cert_id;
      var url = base+"index.php/ejecucion/cert_poa/get_datos_certificado";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "cert_id="+cert_id
      });

      request.done(function (response, textStatus, jqXHR) { 
        if (response.respuesta == 'correcto') {
            $('#titulo_del').html('<h2 class="alert alert-danger"><center>ELIMINAR CERTIFICACI&Oacute;N : '+response.certificado[0]['cpoa_codigo']+'</center></h2>');
            $('#titulo_del2').html('<font color="blue" size="3">U.E. '+response.certificado[0]['proy_nombre']+'</font>');
            document.getElementById("cite").value = '';
            document.getElementById("justificacion").value = '';
            $('#error1m').html('NRO CITE');
            $('#error2m').html('JUSTIFICACIÓN');
        } else {
            alertify.error("ERROR AL RECUPERAR DATOS, PORFAVOR CONTACTESE CON EL ADMINISTRADOR"); 
        }
      });

      request.fail(function (jqXHR, textStatus, thrown) {
          console.log("ERROR: " + textStatus);
      });
      request.always(function () {
          //console.log("termino la ejecuicion de ajax");
      });
      
      // ===VALIDAR EL FORMULARIO DE ELIMINACION
      $("#delete").on("click", function () {
        var error='false';
        var cite=document.getElementById('cite').value;
        var justificacion=document.getElementById('justificacion').value;
        if(!cite){
            $('#error1m').html('<font color="red" size="1">NRO CITE (*)</font>');
            document.form_delete.cite.focus() 
            return 0;
        }
        if(!justificacion){
            $('#error1m').html('NRO CITE');
            $('#error2m').html('<font color="red" size="1">JUSTIFICACIÓN (*)</font>');
            document.form_delete.justificacion.focus() 
            return 0;
        }
     
        if(cite.length!=0 & justificacion.length!=0){
            $('#error1').html('NRO CITE');
            $('#error2').html('JUSTIFICACIÓN');
             alertify.confirm("DESEA REALIZAR LA ANULACIÓN DE LA CERTIFICACI&Oacute;N ?", function (a) {
                if (a) {
                    document.getElementById("load_del").style.display = 'block';
                    document.forms['form_delete'].submit(); /// id del formulario
                    document.getElementById("but_del").style.display = 'none';
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
        }
        else{
            alertify.error("REGISTRE DATOS");
        }
      });
    }


    $(document).ready(function() {
        pageSetUp();
        $("#reg_id").change(function () {
            $("#reg_id option:selected").each(function () {
                dist_id=$('[name="dist_id"]').val();
                elegido=$(this).val();
                $.post(base+"index.php/ejec/get_uadministrativas", { elegido: elegido,accion:'distrital' }, function(data){
                    $("#dist_id").html(data);
                    $("#tp_id").html('');
                    $("#lista_certificaciones").html('');
                });
            });
        });
        $("#dist_id").change(function () {
            $("#dist_id option:selected").each(function () {
                elegido=$(this).val();
                $.post(base+"index.php/ejec/get_uadministrativas", { elegido: elegido,accion:'tipo' }, function(data){
                    $("#tp_id").html(data);
                    $("#lista_certificaciones").html('');
                });
            });
        });
        $("#tp_id").change(function () {
            $("#tp_id option:selected").each(function () {
                dist_id=$('[name="dist_id"]').val();
                tp_id=$(this).val();
                $('#lista_certificaciones').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Certificaciones POA ...</div>');
                var url = base+"index.php/ejecucion/cert_poa/get_lista_cpoas";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "dist_id="+dist_id+"&tp_id="+tp_id
                });

                request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        $('#lista_certificaciones').fadeIn(1000).html(response.lista_certpoa);
                    }
                    else{
                        alertify.error("ERROR AL LISTAR CERTIFICACIONES POA");
                    }
                });  
            });
        });
    })



    ////============================= FORMULARIO DE CERTIFICACION POA
    ///// Obtiene Lista de requerimientos
    $("#prod_id").change(function () {
        $("#prod_id option:selected").each(function () {
            prod_id=$(this).val();
            if(prod_id!=0){
              document.getElementById("loading").style.display = 'block';
              document.getElementById("but").style.display = 'none';
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

  //// Anular Solicitud de Certificacion POA
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

    $(".del_solicitud").on("click", function (e) {
      reset();
      var sol_id = $(this).attr('name'); // sol id
      var request;
      alertify.confirm("ESTA SEGURO DE ANULAR LA SOLICITUD DE CERTIFICACIÓN POA ?", function (a) {
        if (a) {
            url = base+"index.php/ejecucion/ccertificacion_poa/anula_solicitud_cpoa";
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: "sol_id="+sol_id
            });

            request.done(function (response, textStatus, jqXHR) { 
              reset();
              if (response.respuesta == 'correcto') {
                window.location.reload(true);
              } 
              else {
                alertify.error("Error al anular la solicitud ...");
              }
            });

            request.fail(function (jqXHR, textStatus, thrown) {
              console.log("ERROR: " + textStatus);
            });

            e.preventDefault();

        } else {
            alertify.error("Opcion cancelada");
        }
      });
      return false;
    });

  });



  //// ver Solicitud de Certificacion POA

    $(".ver_solicitud").on("click", function (e) {
      reset();
      var sol_id = $(this).attr('name'); // sol id
    //  var tp = $(this).attr('id'); // tp
      //alert(sol_id)
      var request;
        url = base+"index.php/ejecucion/ccertificacion_poa/get_ver_solicitudcpoa";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "sol_id="+sol_id
        });

        request.done(function (response, textStatus, jqXHR) { 
          reset();
          if (response.respuesta == 'correcto') {
            $('#ver').fadeIn(1000).html(response.tabla);
          } 
          else {
            alertify.error("Error al anular la solicitud ...");
          }
        });

        request.fail(function (jqXHR, textStatus, thrown) {
          console.log("ERROR: " + textStatus);
        });

        e.preventDefault();
      return false;
    });


    //////// ACEPTAR SOLICITUD DE CERTIFICACION POA

    ///// Obtiene Lista de Solicitudes
    $("#dep_id").change(function () {
      $("#dep_id option:selected").each(function () {
        dep_id=$(this).val();
       
        if(dep_id!=0){
          document.getElementById("loading").style.display = 'block';
          $('#solicitudes').fadeIn(1000).html('');
          var url = base+"index.php/ejecucion/ccertificacion_poa/get_cuadro_solicitudes_certificacionpoa";
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
                document.getElementById("loading").style.display = 'none';
                $('#solicitudes').fadeIn(1000).html(response.tabla);
              }
              else{
                  alertify.error("ERROR AL LISTAR");
              }
          }); 
        }
        else{
          $('#solicitudes').fadeIn(1000).html('');
        }
      });
    });


    /// Aprobar Solicitud
    function aprobar_solicitud(sol_id) {
        reset();
        var request;
        alertify.confirm("ESTA SEGURO DE APROBAR LA SOLICITUD DE CERTIFICACIÓN POA ?", function (a) {
        if (a) {
            url = base+"index.php/ejecucion/ccertificacion_poa/aprobar_solicitud_cpoa";
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: "sol_id="+sol_id
            });

            request.done(function (response, textStatus, jqXHR) { 
              reset();
              if (response.respuesta == 'correcto') {
                $('#solicitudes').fadeIn(1000).html(response.certpoa);
              } 
              else {
                alertify.error("Error ...");
              }
            });

            request.fail(function (jqXHR, textStatus, thrown) {
              console.log("ERROR: " + textStatus);
            });

            e.preventDefault();

        } else {
            alertify.error("Opcion cancelada");
        }
      });

    }

    /// Anular Solicitud de Certificacion POa
    function anular_solicitud(sol_id) {
        reset();
        var request;
        alertify.confirm("ESTA SEGURO DE APROBAR LA SOLICITUD DE CERTIFICACIÓN POA ?", function (a) {
        if (a) {
            url = base+"index.php/ejecucion/ccertificacion_poa/anular_solicitud_cpoa";
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: "json",
                data: "sol_id="+sol_id
            });

            request.done(function (response, textStatus, jqXHR) { 
              reset();
              if (response.respuesta == 'correcto') {
                $('#solicitudes').fadeIn(1000).html(response.solpoa);
              } 
              else {
                alertify.error("Error ...");
              }
            });

            request.fail(function (jqXHR, textStatus, thrown) {
              console.log("ERROR: " + textStatus);
            });

            e.preventDefault();

        } else {
            alertify.error("Opcion cancelada");
        }
      });

    }
