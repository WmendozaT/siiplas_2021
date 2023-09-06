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

  //// SUBIR ARCHIVO DE AJUSTE MEDIANTE EXCEL
  $(function () {
    $("#subir_archivo").on("click", function () {
      var $valid = $("#form_subir_ajuste").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        if(document.getElementById('archivo').value==''){
          alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
          return false;
        }

          alertify.confirm("SUBIR ARCHIVO AL SISTEMA ?", function (a) {
              if (a) {
                  document.getElementById("load").style.display = 'block';
                  document.getElementById('subir_archivo').disabled = true;
                  document.forms['form_subir_ajuste'].submit();
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
          });
      }
    });
  });

  /// ============ GENERACION DE CERTIFICACION POA

    $(function () {
      $(".update_eval").on("click", function (e) {
          prod_id = $(this).attr('name');
          cpoa_id = $(this).attr('id');
          document.getElementById("load_insumo").style.display = 'block';
          document.getElementById("btn_insumos").style.display = 'none';
          var url = base+"index.php/ejecucion/ccertificacion_poa/get_insumos";
          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "prod_id="+prod_id+"&cpoa_id="+cpoa_id
          });

          request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
            document.getElementById("load_insumo").style.display = 'none';
              $('#lista').fadeIn(1000).html(response.lista);
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
      });
    });

    //// adiciona el item (temporalidad unica) a la Certificacion POA
    function add_item_cpoa(ins_id,cpoa_id,nro,estaChequeado) {
      if (estaChequeado == true) { //// adicionar
          document.getElementById("tr"+nro).style.backgroundColor = "#c6f1d7";
         check=1;
      }
      else{
          document.getElementById("tr"+nro).style.backgroundColor = "";
          check=0;
      }

      $('#vista_previa').fadeIn(1000).html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif"  alt="loading" /><br/><font color=blue>Actualizando Vista Previa ......</font></center>');
        var url = base+"index.php/ejecucion/ccertificacion_poa/adiciona_cancela_items";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "ins_id="+ins_id+"&check="+check+"&cpoa_id="+cpoa_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
            $('#vista_previa').fadeIn(1000).html(response.vista_previa);
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS");
        }

        });
    }

    //// Adicionar el MES seleccionado de un Items con Temporalidad Distribuida
    function seleccionar_temporalidad(tins_id,cpoa_id,estaChequeado) {
      if (estaChequeado == true) { 
        check=1;
      }
      else{
        check=0;
      }

      $('#vista_previa').fadeIn(1000).html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/><font color=blue>Actualizando Vista Previa ......</font></center>');
        var url = base+"index.php/ejecucion/ccertificacion_poa/adiciona_cancela_meses_items";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "tins_id="+tins_id+"&check="+check+"&cpoa_id="+cpoa_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
            $('#vista_previa').fadeIn(1000).html(response.vista_previa);
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS");
        }

      });

    }

    //// Validar Datos de la Nota CITE
    $(function () {
        $("#subir_form1").on("click", function () {
            var $validator = $("#cert_form").validate({
              rules: {
                  cite_cpoa: { //// Nota cite
                      required: true,
                  },
                  cite_fecha: { //// Fecha cite
                      required: true,
                  }
              },
              messages: {
                  cite_cpoa: "<font color=red>Registre Cite</font>", 
                  cite_fecha: "<font color=red>Seleccione Fecha</font>",                    
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

            var $valid = $("#cert_form").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } else {

              if(validarCaracteres(document.getElementById('cite_cpoa').value)==true){
                  alertify.confirm("VALIDAR DATOS CITE ?", function (a) {
                      if (a) {
                          document.getElementById('subir_form1').disabled = true;
                          document.forms['cert_form'].submit();
                          document.getElementById("load").style.display = 'block';
                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                      }
                  });
              }
              else{
                  alertify.error("CORRIGA CARACTERES ESPECIALES") 
                  document.cert_form.cite_cpoa.focus() 
                  return 0; 
              }

            }
        });
    });

    //// Valida Caracteres Especiales
    function validarCaracteres(cadena) {
      let caracteresNoPermitidos = ['(', ')', '/'];
      let contieneCaracteresNoPermitidos = false;
      
      $.each(caracteresNoPermitidos, function(index, caracter) {
        if (cadena.indexOf(caracter) !== -1) {
          contieneCaracteresNoPermitidos = true;
          return false; // Para salir del bucle each() si se encuentra un caracter no permitido
        }
      });
      
      if (contieneCaracteresNoPermitidos) {
        return false;
      } else {
        return true;
      }
    }

    //// Valida Para Reporte de Certificacion POA
    function guardar(){
      if(document.form_cpoa.recomendacion.value==''){
        alertify.error("REGISTRE RECOMENDACION") 
        document.form_cpoa.recomendacion.focus() 
        return 0; 
      }

      if(validarCaracteres(document.getElementById('recomendacion').value)==true){
        alertify.confirm("GENERAR REPORTE DE CERTIFICACION POA ?", function (a) {
          if (a) {
              document.form_cpoa.submit();
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }
      else{
          alertify.error("CORRIGA CARACTERES ESPECIALES") 
          document.form_cpoa.recomendacion.focus() 
          return 0; 
      }
    }


  /// ============================================

  //// EDITAR CERTIFICACION POA (NOTA CITE)
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
              $('#titulo_edit').html('<h2 class="alert alert-warning"><center>MODIFICACIÓN DE CERTIFICACI&Oacute;N POA N° : '+response.certificado[0]['cpoa_codigo']+'</center></h2>');
              $('#titulo2').html('<font color="blue" size="3">U.E. '+response.certificado[0]['proy_nombre']+'</font>');
              document.getElementById("cite_edit").value = '';
              document.getElementById("justificacion_edit").value = '';
              $('#error1').html('NRO CITE');
              $('#error2').html('JUSTIFICACIÓN');
          } else {
              alertify.error("ERROR AL RECUPERAR DATOS"); 
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

                if(validarCaracteres(document.getElementById('cite_edit').value)==true){
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
                  alertify.error("CORRIGA CARACTERES ESPECIALES") 
                  document.form_anular.cite_edit.focus() 
                  return 0;
                }
            }
            else{
                alertify.error("REGISTRE DATOS");
            }
        });
    }

    //// Anula Certificacion POA 
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
              if(validarCaracteres(document.getElementById('cite').value)==true){
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
                  alertify.error("CORRIGA CARACTERES ESPECIALES") 
                  document.form_delete.cite.focus() 
                  return 0; 
              }
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
        tp = $('[name="tp"]').val();
        com_id = $('[name="com_id"]').val();

      //  alert(prod_id+'--'+tp+'--'+com_id)
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
              data: "prod_id="+prod_id+"&tp="+tp+"&com_id="+com_id
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

    /// === funcion para seleccionar el itema (solo para un mes)
/*    function seleccionarFilacompleta(ins_id,nro,estaChequeado) {
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
          $('#paso3').slideUp();
          $('#but').slideUp();
          $(".check2").attr('checked', 'checked');  
        }
        else{
          $('#paso3').slideDown();
        }
    }*/
      /// =====------------------------------

      //// ============= funcion : para items con varios meses programados
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
          $('#paso3').slideUp();
          $('#but').slideUp();
          $("#check2").attr('checked', 'checked');  
        }
        else{
          $('#paso3').slideDown();
        }
      }

      /// --- seleccionar el mes programado
/*      function seleccionar_temporalidad(tins_id, estaChequeado) {
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
                  $('#paso3').slideUp();
                  $('#but').slideUp();
                  $("#check2").attr('checked', 'checked');  
                }
                else{
                  $('#paso3').slideDown();
                }

              }else{ /// inhabilitado (ya se certifico anteriormente)
                 alertify.error("EL MES SELECCIONADO YA FUE CERTIFICADO ANTERIORMENTE !!!");
                val = val - 1;
                $('[name="tot_temp"]').val((val).toFixed(0));
                total = parseFloat($('[name="tot_temp"]').val());
                totalf = parseFloat($('[name="tot"]').val());
                if(total==0 || totalf==0){
                  $('#paso3').slideUp();
                  $('#but').slideUp();
                  $("#check2").attr('checked', 'checked');  
                }
                else{
                  $('#paso3').slideDown();
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
            $('#paso3').slideUp();
            $('#but').slideUp();
            $("#check2").attr('checked', 'checked');  
          }
          else{
            $('#paso3').slideDown();
          }
        }
      }*/

      /// Seleccion de opcion para guardar solicitud
      $(document).ready(function(){
          $(".paso3").click(function(evento){
            var valor = $(this).val();
            if(valor == 'si'){
                $("#but").css("display", "block");
            }
            else{
                $("#but").css("display", "none");
            }
        });
      });


    /// ==========================================================================


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


    /// ---- Aprobar Solcitud Certificacion POA
    function aprobar_solicitud(sol_id) {
      document.getElementById("sol_id").value = sol_id;
      var url = base+"index.php/ejecucion/ccertificacion_poa/get_datos_solicitud";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "sol_id="+sol_id
      });

      request.done(function (response, textStatus, jqXHR) { 
        if (response.respuesta == 'correcto') {
            $('#titulo_sol').html('<h2 class="alert alert-success"><center>APROBAR SOLICITUD : '+response.solicitud[0]['cite']+'</center></h2>');
            $('#error2').html('RECOMENDACIÓN');
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

        // ===VALIDAR LA SOLICITUD  DE CERTIFICACION POA
        $("#generar_cert").on("click", function (e) {
            var error='false';
            var recomendacion=document.getElementById('recomendacion').value;
            var sello=$('[name="sello"]').val();
          
            if(!recomendacion){
                $('#error2').html('<font color="red" size="1">REGISTRE RECOMENDACIÓN (*)</font>');
                document.form_solicitud.recomendacion.focus() 
                return 0;
            }
         
            if(recomendacion.length!=0){
                $('#error2').html('RECOMENDACIÓN');
                reset();
                var request;
                 alertify.confirm("DESEA APROBAR LA SOLICITUD DE CERTIFICACIÓN POA ?", function (a) {
                    if (a) {

                      url = base+"index.php/ejecucion/ccertificacion_poa/aprobar_solicitud_cpoa";
                      if (request) {
                          request.abort();
                      }
                      request = $.ajax({
                          url: url,
                          type: "POST",
                          dataType: "json",
                          data: "sol_id="+sol_id+"&recomendacion="+recomendacion+"&sello="+sello
                      });

                      request.done(function (response, textStatus, jqXHR) { 
                        reset();
                        if (response.respuesta == 'correcto') {
                          $("#modal_aprobar_solcert").modal("hide");

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
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
            else{
                alertify.error("REGISTRE DATOS");
            }
        });
    }


    /// --- seleccion de aprobacion en linea de la certificacion
    $("#sello").change(function () {
      $("#sello option:selected").each(function () {
          elegido=$(this).val();
          responsable=document.getElementById("resp").value;
          if(elegido!=''){
            if(elegido==0){
              $('#tip_autorizacion').html('<span class="badge bg-color-orange" style="font-size: 15px;"> Para la validez del documento, el mismo debera ser firmado por el Responsable de Planificación.</span><hr>');
            }
            else{
              $('#tip_autorizacion').html('<center><span class="badge bg-color-greenLight" style="font-size: 15px;">Yo '+responsable+' Responsable POA<br> Doy el visto bueno y validez al presente documento para procesos administrativos internos de la institución.</span></center>');
            }

            document.getElementById("but").style.display = 'block';

          }
          else{
            document.getElementById("but").style.display = 'none';
          }
      });
    });



    /// Anular Solicitud de Certificacion POa
    function anular_solicitud(sol_id) {
      document.getElementById("s_id").value = sol_id;
      var url = base+"index.php/ejecucion/ccertificacion_poa/get_datos_solicitud";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "sol_id="+sol_id
      });

      request.done(function (response, textStatus, jqXHR) { 
        if (response.respuesta == 'correcto') {
            $('#titulo_solicitud').html('<h2 class="alert alert-danger"><center>RECHAZAR SOLICITUD : '+response.solicitud[0]['cite']+'</center></h2>');
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

        // ===VALIDAR LA ANULACION DE SOLICITUD CERTIFICACION POA
        $("#rechazar_sol").on("click", function (e) {
            var error='false';
            var observacion=document.getElementById('observacion').value;
          
            if(!observacion){
                $('#error_obs').html('<font color="red" size="1">REGISTRE OBSERVACIÓN (*)</font>');
                document.form_observacion.observacion.focus() 
                return 0;
            }
         
            if(observacion.length!=0){
                $('#error_obs').html('OBSERVACIÓN');
              //  alert(sol_id+'---'+observacion)
                reset();
                var request;
                alertify.confirm("RECHAZAR SOLICITUD DE CERTIFICACIÓN POA ?", function (a) {
                  if (a) {

                    url = base+"index.php/ejecucion/ccertificacion_poa/rechazar_solicitud_cpoa";
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        data: "sol_id="+sol_id+"&observacion="+observacion
                    });

                    request.done(function (response, textStatus, jqXHR) { 
                      reset();
                      if (response.respuesta == 'correcto') {
                        $("#modal_anular_solcert").modal("hide");
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
                      alertify.error("OPCI\u00D3N CANCELADA");
                  }
                });
            }
            else{
                alertify.error("REGISTRE DATOS");
            }
        });








/*        reset();
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
      });*/

    }


    //// Valida formulario de Modificacion POA
      $(function () {

          $(".mod_ins").on("click", function (e) {
            ins_id = $(this).attr('name');
            document.getElementById("ins_id").value=ins_id;
            cpoaa_id=document.getElementById("cpoaa_id").value;
            //alert('hola mundo')
            var url = base+"index.php/ejecucion/cert_poa/get_requerimiento_cert";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "ins_id="+ins_id+"&cpoaa_id="+cpoaa_id
            });

            request.done(function (response, textStatus, jqXHR) {
            if (response.respuesta == 'correcto') {
              if(response.verif_cert==1){
               
                $( "#detalle" ).prop( "disabled", true );
                $( "#umedida" ).prop( "disabled", true );
              }
              else{
            
                $( "#detalle" ).prop( "disabled", false );
                $( "#umedida" ).prop( "disabled", false );
              }

               document.getElementById("saldo").value = parseFloat(response.monto_saldo).toFixed(2);
               document.getElementById("sal").value = parseFloat(response.monto_saldo).toFixed(2);
               document.getElementById("monto_dif").value = parseFloat(response.saldo_dif).toFixed(2);
               document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
               document.getElementById("cantidad").value = response.insumo[0]['ins_cant_requerida'];
               document.getElementById("costou").value = parseFloat(response.insumo[0]['ins_costo_unitario']).toFixed(2);
               document.getElementById("costot").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
               document.getElementById("costot2").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
               document.getElementById("umedida").value = response.insumo[0]['ins_unidad_medida'];
               document.getElementById("mtot").value = response.prog[0]['programado_total'];
               document.getElementById("monto_cert").value = response.monto_certificado;
               $('#monto').html('<span class="label bg-color-blueDark pull-right" style="color: #fff;">MONTO YA CERTIFICADO : '+response.monto_certificado+' Bs. &nbsp;&nbsp;| &nbsp;&nbsp; MONTO SELECCIONADO : '+response.monto_certificado_item+' Bs.</span>');
            
               if(response.prog[0]['programado_total']!=response.insumo[0]['ins_costo_total']){
                $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#mbut').slideUp();
               }

               for (var i = 1; i <=12; i++) {
                mes=mes_texto(i);
               
                document.getElementById("mm"+i).value = response.prog[0]['mes'+i];
             
                if(response.verif_mes['verf_mes'+i]==1){
                  document.getElementById("mm"+i).disabled = true;
                  $('#mess'+i).html('<font color=red><b>'+mes+'</b> (Ya Certificado)</font>');
                }
                else{
                  if(response.verif_mes['verf_mes'+i]==2){
                    $('#mess'+i).html('<font color=blue><b>'+mes+'</b> (Editable)</font>');
                  }
                  else{
                    $('#mess'+i).html('<b>'+mes+'</b> (Editable)');
                  }
                  document.getElementById("mm"+i).disabled = false;
                }
               }

               if(response.monto_certificado==response.prog[0]['programado_total']){
                $('#titulo_req').html('<center><h2 class="alert alert-danger">REQUERIMIENTO CERTIFICADO</h2></center>');
                $('#mbut').slideUp();
               }
               else{
                $('#titulo_req').html('<center><h2 class="alert alert-info">MODIFICAR REQUERIMIENTO</h2></center>');
                $('#mbut').slideDown();
               }

              if(response.prog[0]['programado_total']>response.monto_saldo){
                $('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL ES MAYOR AL SALDO, VERIFIQUE MONTOS</div></center>');
                $('#mbut').slideUp();
              }
              else{
                  if(response.monto_certificado==response.prog[0]['programado_total']){
                      $('#titulo_req').html('<center><h2 class="alert alert-danger">REQUERIMIENTO CERTIFICADO</h2></center>');
                      $('#mbut').slideUp();
                  }
                  else{
                    $('#amtit').html('');
                    $('#mbut').slideDown();
                  }
              }
            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS DEL REQUERIMIENTO");
            }

            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
            // =======VALIDAR EL FORMULARIO DE MODIFICACION
            $("#subir_mins").on("click", function (e) {
                var $validator = $("#form_mod").validate({
                    rules: {
                      ins_id: { //// Insumo
                        required: true,
                      },
                      detalle: { //// Detalle
                        required: true,
                      },
                      umedida: { //// unidad medida
                        required: true,
                      }
                    },
                    messages: {
                      detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                      umedida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",                    
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
                  var $valid = $("#form_mod").valid();
                  if (!$valid) {
                      $validator.focusInvalid();
                  } else {
                    saldo=document.getElementById("sal").value;
                    programado=document.getElementById("mtot").value;
                    dif=saldo-programado;
                

                    //alert('hola mundo')

                    if(dif>=0){
                      alertify.confirm("MODIFICAR DATO DEL REQUERIMIENTO ?", function (a) {
                          if (a) {
                              document.getElementById("loadm").style.display = 'block';
                              document.forms['form_mod'].submit();
                              document.getElementById("mbut").style.display = 'none';

                          } else {
                              alertify.error("OPCI\u00D3N CANCELADA");
                          }
                      });
                    }
                    else{
                      $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                      alertify.error("EL MONTO PROGRAMADO NO PUEDE SER MAYO AL MONTO SALDO DE LA OPERACIÓN, VERIFIQUE MONTOS");
                    }
                  }
              });
          });
      });
  
      function suma_programado_modificado(){ 
        sum=0;
        for (var i = 1; i <=12; i++) {
          sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
        }

        $('[name="mtot"]').val((sum).toFixed(2));
        programado = parseFloat($('[name="mtot"]').val()); //// programado total
        ctotal = parseFloat($('[name="costot"]').val()); //// Costo Total
        saldo = parseFloat($('[name="sal"]').val()); //// saldo

        if(programado!=ctotal){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
              $('#mbut').slideUp();
        }
        else{
          if(ctotal>saldo){
            $('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
                $('#mbut').slideUp();
          }
          else{
            $('#amtit').html('');
            $('#mbut').slideDown();
          }
        }
      }

       function seleccionar_temporalidad_edit(tins_id,cpoa_id,ins_id,nro,estaChequeado){
          if (estaChequeado == true) {
          //  val = val + 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f2fded";
          //  mes = parseFloat($('[name="tot_temp"]').val());
            total = parseFloat($('[name="tot_temp"]').val());
            $('[name="tot_temp"]').val((total+1).toFixed(0));
            total = parseFloat($('[name="tot_temp"]').val());
            if(total==0){
              $('#but').slideUp();
            }
            else{
              $('#but').slideDown();
            }
          }

          else {
            $('[name="tot_temp"]').val(($('[name="tot_temp"]').val()-1).toFixed(0));
            var url = base+"index.php/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                var nro_check=0;
                  for (var i = 1; i <=12; i++) {
                    if(response.temporalidad['verf_mes'+i]!=3 & response.temporalidad['verf_mes'+i]!=2){
                      if((document.getElementById("ipmm"+i+""+ins_id).checked) == true){
                        nro_check=nro_check+1;
                      }
                    }
                  }

                  if(nro_check==0){
                    document.getElementById("tr"+nro).style.backgroundColor = "#f59787";
                  }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }
            }); 
          }

          fila = parseFloat($('[name="tot"]').val());
          mes = parseFloat($('[name="tot_temp"]').val());
          if(fila!=0 && mes!=0){
            $('#but').slideDown();
          }
          else{
            $('#but').slideUp();
          }
          
        }

        /// nuevo selecciona toda la fila (Modificacion de Certificacion POA)
        function seleccionarFila_cpoa(ins_id,nro,cpoa_id,estaChequeado) {
          val = parseInt($('[name="tot"]').val());
          if (estaChequeado == true) {
            val=val+1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f2fded";
          }
          else{
            val=val-1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f59787";
          }
          
          $('[name="tot_temp"]').val((val).toFixed(0));
          $('[name="tot"]').val((val).toFixed(0));
          fila = parseFloat($('[name="tot"]').val());
          mes = parseFloat($('[name="tot_temp"]').val());

          if(fila!=0 && mes!=0){
            $('#but').slideDown();
          }
          else{
            $('#but').slideUp();
          }
        }

        //// Seleccionar item a certificar (edicion poa)
        function seleccionarFila_edit(ins_id,nro,cpoa_id,estaChequeado) {
          if (estaChequeado == true) { 
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='block';
            }
            var url = base+"index.php/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                  if(response.verif_cert==1){
                    document.getElementById("tr"+nro).style.backgroundColor = "#f2fded";
                  }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }
            }); 
          } 
          else {
        
            for (var i = 1; i <=12; i++) {
              document.getElementById("m"+i+""+ins_id).style.display='none';
             // document.getElementById("ipmm"+i+""+ins_id).style.checked='false';
             // $('input.checkbox').prop('checked',false);
            }

            var url = base+"index.php/ejecucion/ccertificacion_poa/get_programado_temporalidad";
            var request;
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cpoa_id="+cpoa_id
            });

            request.done(function (response, textStatus, jqXHR) {
              if (response.respuesta == 'correcto') {
                if(response.verif_cert==0){
                  val = parseInt($('[name="tot_temp"]').val());
                  for (var i = 1; i <=12; i++) {
                    if(response.temporalidad['verf_mes'+i]==0 & response.temporalidad['verf_mes'+i]!=3 & response.temporalidad['verf_mes'+i]!=2){
                      if((document.getElementById("ipmm"+i+""+ins_id).checked) == true){
                    
                        document.getElementById("ipmm"+i+""+ins_id).checked = false;
                        val = val - 1;
                  
                        $('[name="tot_temp"]').val((val).toFixed(0));
                        total = parseFloat($('[name="tot_temp"]').val());
                       // total = parseFloat($('[name="tot_temp"]').val());
                      
                        if(total==0){
                          $('#but').slideUp();
                        }
                        else{
                          $('#but').slideDown();
                        }
                      }
                    }
                  }
                }
              }
              else{
                alertify.error("ERROR AL RECUPERAR DATOS DE TEMPORALIDAD");
              }
            }); 

          }

          val = parseInt($('[name="tot"]').val());
          if (estaChequeado == true) {
            val = val + 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f5c9c2";
          } else {
            val = val - 1;
            document.getElementById("tr"+nro).style.backgroundColor = "#f59787";
          }
          $('[name="tot"]').val((val).toFixed(0));

          fila = parseFloat($('[name="tot"]').val());
          mes = parseFloat($('[name="tot_temp"]').val());
          if(fila!=0 && mes!=0){
            $('#but').slideDown();
          }
          else{
            $('#but').slideUp();
          }
        }

      /// GENERAR EDICION DE CERTIFICACION POA
      $(function () {
        $("#btsubmit_edit").on("click", function (e) {
          var $validator = $("#cert_form").validate({
              rules: {
                cite_cpoa: {
                    required: true,
                },
                rec: {
                    required: true,
                },
                cite_fecha: {
                    required: true,
                }
              },
              messages: {
                cite_cpoa: {required: "<font color=red size=1>REGISTRE NRO. DE CITE</font>"},
                rec: {required: "<font color=red size=1>REGISTRE RECOMENDACI&Oacute;N</font>"},
                cite_fecha: {required: "<font color=red size=1>REGISTRE FECHA CITE</font>"}
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
          var $valid = $("#cert_form").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } 
          else {
            reset();
              alertify.confirm("GENERAR EDICIÓN DE CERTIFICACI&Oacute;N POA?", function (a) {
                  if (a) {
                      //document.getElementById('btsubmit').disabled = true;
                      document.cert_form.submit();
                      document.getElementById("load").style.display = 'block';
                     document.getElementById("but").style.display = 'none';
                  } else {
                      alertify.error("OPCI\u00D3N CANCELADA");
                  }
              });
          }
      });
    });

    function mes_texto(mes){
      switch (mes) {
          case 1:
              texto = 'ENERO';
              break;
          case 2:
              texto = 'FEBRERO';
              break;
          case 3:
              texto = 'MARZO';
              break;
          case 4:
              texto = 'ABRIL';
              break;
          case 5:
              texto = 'MAYO';
              break;
          case 6:
              texto = 'JUNIO';
              break;
          case 7:
              texto = 'JULIO';
              break;
          case 8:
              texto = 'AGOSTO';
              break;
          case 9:
              texto = 'SEPTIEMBRE';
              break;
          case 10:
              texto = 'OCTUBRE';
              break;
          case 11:
              texto = 'NOVIEMBRE';
              break;
          case 12:
              texto = 'DICIEMBRE';
              break;
          default:
              texto = 'SIN REGISTRO';
              break;
      }
      return texto;
    }