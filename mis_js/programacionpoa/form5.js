base = $('[name="base"]').val();
prod_id = $('[name="prod_id"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE FORMULARIO N° 5" , "width=800,height=700,scrollbars=NO") ; 
}

function doSelectAlert(event,prod_id,ins_id) {
  var option = event.srcElement.children[event.srcElement.selectedIndex];
  if (option.dataset.noAlert !== undefined) {
      return;
  }

  alertify.confirm("DESEA CAMBIAR DE ALINEACION ACTIVIDAD ?", function (a) {
      if (a) {
      var url = base+"index.php/programacion/crequerimiento/cambia_actividad";
      $.ajax({
          type: "post",
          url: url,
          data:{prod_id:prod_id,ins_id:ins_id},
              success: function (data) {
              window.location.reload(true);
          }
      });
      } else {
          alertify.error("OPCI\u00D3N CANCELADA");
      }
    });
}


  function justNumbers(e){
      var keynum = window.event ? window.event.keyCode : e.which;
      if ((keynum == 8) || (keynum == 46))
      return true;
       
      return /\d/.test(String.fromCharCode(keynum));
  }

  //// ELIMINA REQUERIMIENTOS SELECCIONADOS
    function valida_eliminar(){
      alertify.confirm("DESEA ELIMINAR "+document.del_req.tot.value+" REQUERIMIENTOS ?", function (a) {
          if (a) {
              document.getElementById("btsubmit").value = "ELIMINANDO REQUERIMIENTOS...";
              document.getElementById("btsubmit").disabled = true;
              document.del_req.submit();
              return true;
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
     }

  ///  ELIMINAR TODOS LOS REQUERIMIENTOS DE LA ACTIVIDAD
  function eliminar_requerimientos(){
    alertify.confirm("DESEA ELIMINAR TODOS LOS REQUERIMIENTOS ?", function (a) {
          if (a) {
            window.location=base+"index.php/prog/eliminar_insumos_todos/"+prod_id;
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
      });
  }


  $(document).ready(function() {
      pageSetUp();
      $("#padre").change(function () {
          $("#padre option:selected").each(function () {
            elegido=$(this).val();
            $("#um_id").html('');
            $.post(base+"index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
            $("#partida_id").html(data);
            });     
        });
      });

      $("#partida_id").change(function () {
          $("#partida_id option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"index.php/prog/combo_umedida", { elegido: elegido }, function(data){ 
            $("#um_id").html(data);
            });     
        });
      }); 
    })

    $(document).ready(function() {
      pageSetUp();
      $("#par_padre").change(function () {
          $("#par_padre option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
            $("#par_hijo").html(data);
            });     
        });
      });

      $("#par_hijo").change(function () {
          $("#par_hijo option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"index.php/prog/combo_umedida", { elegido: elegido }, function(data){  
            $("#mum_id").html(data);
            });     
        });
      }); 
    })


    function suma_programado(){ 
      sum=0;
      for (var i = 1; i<=12; i++) {
        sum=parseFloat(sum)+parseFloat($('[name="m'+i+'"]').val());
      }

      $('[name="tot"]').val((sum).toFixed(2));
      programado = parseFloat($('[name="tot"]').val()); //// programado total
      ctotal = parseFloat($('[name="costo"]').val()); //// Costo Total

      if(programado!=ctotal){
        $('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
            $('#but').slideUp();
      }
      else{
        $('#atit').html('');
            $('#but').slideDown();
      }
    }

      function suma_programado_modificado(){ 
        sum=0;
        for (var i = 1; i <=12; i++) {
          sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
        }

        $('[name="mtot"]').val((sum).toFixed(2));
        saldo = parseFloat($('[name="saldo"]').val()); //// Monto Saldo
        programado = parseFloat($('[name="mtot"]').val()); //// programado total
        ctotal = parseFloat($('[name="costot"]').val()); //// Costo Total

        if(programado!=ctotal){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
              $('#mbut').slideUp();
        }
        else{
          $('#amtit').html('');
              $('#mbut').slideDown();
        }
      }

      function costo_totalm(){ 
        a = parseFloat($('[name="cantidad"]').val()); //// Meta
        b = parseFloat($('[name="costou"]').val()); //// Costo
        if (a!=0 && a>0 ){
            $('[name="costot"]').val((b*a).toFixed(2) );
            $('[name="costot2"]').val((b*a).toFixed(2) );
        }

        ct = parseFloat($('[name="costot"]').val()); //// total
        mt = parseFloat($('[name="mtot"]').val()); //// prog
        if(ct!=mt ||  isNaN(a)){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
              $('#mbut').slideUp();
        }
        else{
          $('#amtit').html('');
              $('#mbut').slideDown();
        }
      }

      function costo_total(){ 
        a = parseFloat($('[name="ins_cantidad"]').val()); //// cantidad
        b = parseFloat($('[name="ins_costo_u"]').val()); //// Costo unitario
        if (a!=0 && a>0 ){
            $('[name="costo"]').val((b*a).toFixed(2) );
            $('[name="costo2"]').val((b*a).toFixed(2) );
        }

        ct = parseFloat($('[name="costo"]').val()); //// total
        mt = parseFloat($('[name="tot"]').val()); //// prog
        if(ct!=mt ||  isNaN(a) || ct==0){
          $('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
              $('#but').slideUp();
        }
        else{
          $('#atit').html('');
              $('#but').slideDown();
        }
      }

      function verif(){ 
        a = parseFloat($('[name="costot"]').val()); //// total
        b = parseFloat($('[name="mtot"]').val()); //// prog
        if(a!=b){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
              $('#mbut').slideUp();
        }
        else{
          $('#amtit').html('');
              $('#mbut').slideDown();
        }
      }


  //// INSERTAR NUEVO REQUERIMIENTO
  $(function () {
      $("#subir_ins").on("click", function () {
          var $validator = $("#form_nuevo").validate({
              rules: {
                  prod_id: { //// producto
                  required: true,
                  },
                  proy_id: { //// proyecto
                      required: true,
                  },
                  ins_detalle: { //// Detalle
                      required: true,
                  },
                  ins_cantidad: { //// Cantidad
                      required: true,
                  },
                  ins_costo_u: { //// Costo U
                      required: true,
                  },
                  costo: { //// costo tot
                      required: true,
                  },
                  um_id: { //// unidad medida
                      required: true,
                  },
                  padre: { //// par padre
                      required: true,
                  },
                  partida_id: { //// par hijo
                      required: true,
                  }
              },
              messages: {
                  ins_detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                  ins_cantidad: "<font color=red>CANTIDAD</font>",
                  ins_costo_u: "<font color=red>COSTO UNITARIO</font>",
                  costo: "<font color=red>COSTO TOTAL</font>",
                  um_id: "<font color=red>SELECCIONE UNIDAD DE MEDIDA</font>",
                  padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                  partida_id: "<font color=red>SELECCIONE PARTIDA</font>",                     
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
            saldo=document.getElementById("saldo").value;
              programado=document.getElementById("tot").value;
              dif=saldo-programado;
              $('#atit').html('');
                alertify.confirm("GUARDAR DATOS REQUERIMIENTO ?", function (a) {
                    if (a) {
                      document.getElementById("loadi").style.display = 'block';
                          document.getElementById('subir_ins').disabled = true;
                          document.getElementById("subir_ins").value = "GUARDANDO DATOS REQUERIMIENTO...";
                          document.forms['form_nuevo'].submit();
                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                    }
                }); 
          }
      });
  });

  ////// ===== MODIFICAR REUQERIMIENTO
    $(function () {
        $(".mod_ff").on("click", function (e) {
            ins_id = $(this).attr('name');
            document.getElementById("ins_id").value=ins_id;
            var url = base+"index.php/programacion/crequerimiento/get_requerimiento";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "ins_id="+ins_id
            });

            request.done(function (response, textStatus, jqXHR) {
            if (response.respuesta == 'correcto') {
               document.getElementById("saldo").value = parseFloat(response.monto_saldo).toFixed(2);
               document.getElementById("sal").value = parseFloat(response.monto_saldo).toFixed(2);
               document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
               document.getElementById("cantidad").value = response.insumo[0]['ins_cant_requerida'];
               document.getElementById("costou").value = parseFloat(response.insumo[0]['ins_costo_unitario']).toFixed(2);
               document.getElementById("costot").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
               document.getElementById("costot2").value = parseFloat(response.insumo[0]['ins_costo_total']).toFixed(2);
               document.getElementById("par_padre").value = response.ppdre[0]['par_codigo'];
               $("#par_hijo").html(response.lista_partidas);
               document.getElementById("iumedida").value = response.insumo[0]['ins_unidad_medida'];
               //$("#mum_id").html(response.lista_umedida);
               document.getElementById("mtot").value = response.prog[0];
               document.getElementById("observacion").value = response.insumo[0]['ins_observacion'];
               //$('#ff').html('FUENTE DE FINANCIAMIENTO : '+response.prog[0]['ff_codigo']+' || ORGANISMO FINANCIADOR : '+response.prog[0]['of_codigo']);
               if(response.prog[0]!=response.insumo[0]['ins_costo_total']){
                $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
                $('#mbut').slideUp();
               }

               for (var i = 1; i <=12; i++) {
                document.getElementById("mm"+i).value = response.prog[i];
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
            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
            $("#subir_mins").on("click", function (e) {
                var $validator = $("#form_mod").validate({
                       rules: {
                        ins_id: { //// Insumo
                        required: true,
                        },
                        proy_id: { //// Proyecto
                            required: true,
                        },
                        detalle: { //// Detalle
                            required: true,
                        },
                        cantidad: { //// Cantidad
                            required: true,
                        },
                        costou: { //// Costo U
                            required: true,
                        },
                        costot: { //// costo tot
                            required: true,
                        },
                        mum_id: { //// unidad medida
                            required: true,
                        },
                        par_padre: { //// par padre
                            required: true,
                        },
                        par_hijo: { //// par hijo
                            required: true,
                        }
                    },
                    messages: {
                        ins_id: "<font color=red>INSUMO/font>",
                        detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                        cantidad: "<font color=red>CANTIDAD</font>",
                        costou: "<font color=red>COSTO UNITARIO</font>",
                        costot: "<font color=red>COSTO TOTAL</font>",
                        mum_id: "<font color=red>SELECCIONE UNIDAD DE MEDIDA</font>",
                        par_padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                        par_hijo: "<font color=red>SELECCIONE PARTIDA</font>",                     
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
            
                $('#amtit').html('');
                    alertify.confirm("MODIFICAR REQUERIMIENTO ?", function (a) {
                        if (a) {
                          document.getElementById("loadm").style.display = 'block';
                            document.getElementById('subir_mins').disabled = true;
                            document.getElementById("subir_mins").value = "MODIFICANDO DATOS REQUERIMIENTO...";
                            document.forms['form_mod'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
    });


  //// ==== ELIMINAR REQUERIMIENTO
    $(function () {
        function reset() {
          $("#toggleCSS").attr("href", base+"/assets/themes_alerta/alertify.default.css");
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

        $(".del_ff").on("click", function (e) {
            reset();
            var ins_id = $(this).attr('name');
            var request;

            // confirm dialog
            alertify.confirm("DESEA ELIMINAR REQUERIMIENTO ?", function (a) {
              if (a) { 
                  var url = base+"index.php/programacion/crequerimiento/delete_get_requerimiento";
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: "json",
                      data: "ins_id="+ins_id
                  });

                  request.done(function (response, textStatus, jqXHR) { 
                    reset();
                    if (response.respuesta == 'correcto') {
                        alertify.alert("EL REQUERIMIENTO SE ELIMINO CORRECTAMENTE ", function (e) {
                            if (e) {
                                window.location.reload(true);
                            }
                        })
                    } else {
                        alertify.error("Error al Eliminar");
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
                  alertify.error("Opcion cancelada");
              }
          });
          return false;
        });

    });


    ///// SUBIR ARCHIVO DE MIRGRACION DE REQUERIMIENTOS
    $(function () {
      $("#subir_archivo").on("click", function () {
          var $valid = $("#form_subir_sigep").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } else {
            if(document.getElementById('archivo_csv').value==''){
              alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
              return false;
            }

              alertify.confirm("SUBIR ARCHIVO AL SISTEMA ?", function (a) {
                  if (a) {
                      document.getElementById("load").style.display = 'block';
                      document.getElementById('subir_archivo').disabled = true;
                      document.forms['form_subir_sigep'].submit();
                  } else {
                      alertify.error("OPCI\u00D3N CANCELADA");
                  }
              });
          }
      });
    });