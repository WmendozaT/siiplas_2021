base = $('[name="base"]').val();



function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE FORMULARIO N° 4" , "width=800,height=700,scrollbars=NO") ; 
}



  $(document).ready(function() {
      pageSetUp();
      $("#obj_id").change(function () {
          $("#obj_id option:selected").each(function () {
          elegido=$(this).val();
          $.post(base+"/index.php/prog/combo_acciones", { elegido: elegido }, function(data){ 
            $("#acc_id").html(data);
            });     
        });
      });  
    })


  /// VERIFICANDO CODIGO DE ACCION
  function verif_codigo(){ 
  codigo = document.getElementById("cod").value;
  meta = document.getElementById("meta").value;

  if(!isNaN(codigo) & codigo!=0){
    var url = base+"index.php/mestrategico/cobjetivo_gestion/verif_codigo";
      $.ajax({
      type:"post",
      url:url,
      data:{codigo:codigo},
      success:function(datos){
        if(datos.trim() =='true'){
          $('#atit').html('<center><div class="alert alert-danger alert-block">C&Oacute;DIGO DE LA ACCIÓN '+codigo+' YA EXISTE</div></center>');
          $('#but').slideUp();
        }else{

          $('#atit').html('');
          $('#but').slideDown();
        }
    }});
  }
  else{
    alertify.error("REGISTRE CÓDIGO DE ACCIÓN");
    $('#but').slideUp();
  }
}


//// GUARDAR NUEVO REGISTRO DE ACCION DE CORTO PLAZO
  $(function () {
      $("#subir_act").on("click", function () {
          var $validator = $("#form_nuevo").validate({
              rules: {
                  obj_id: { //// Objetivo Estrategico
                    required: true,
                  },
                  acc_id: { //// Accion Estrategico
                     required: true,
                  },
                  ogestion: { //// Objetivo de Gestion
                    required: true,
                  },
                  producto: { //// producto
                     required: true,
                  },
                  resultado: { //// resultado
                      required: true,
                  },
                  tp_indi: { //// tipo de indicador
                      required: true,
                  },
                  indicador: { //// Indicador
                      required: true,
                  },
                  verificacion: { //// verificacion
                      required: true,
                  },
                  lbase: { //// linea base
                      required: true,
                  },
                  meta: { //// meta
                      required: true,
                  }
              },
              messages: {
                obj_id: "<font color=red>SELECCIONE OBJETIVO ESTRATEGICO</font>", 
                acc_id: "<font color=red>SELECCIONE ACCIÓN ESTRATEGICA</font>", 
                ogestion: "<font color=red>REGISTRE ACCION DE CORTO PLAZO</font>", 
                producto: "<font color=red>REGISTRE PRODUCTO</font>", 
                resultado: "<font color=red>REGISTRE RESULTADO</font>", 
                tp_indi: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>",
                indicador: "<font color=red>REGISTRE DETALLE DEL INDICADOR</font>",
                verificacion: "<font color=red>REGISTRE MEDIO DE VERIFICACI&Oacute;N</font>",
                lbase: "<font color=red>REGISTRE LINEA BASE</font>",
                meta: "<font color=red>REGISTRE META</font>",                     
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
              alertify.confirm("GUARDAR ACCIÓN DE CORTO PLAZO ?", function (a) {
                if (a) {
                //    document.getElementById("load").style.display = 'block';
                    document.getElementById('subir_act').disabled = true;
                    document.forms['form_nuevo'].submit();
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
          }
      });
  });


  //// Suma programado regional
  function suma_programado(){ 
    programado_mes = document.getElementById("total_temp").value;
    sum=0;
    linea = parseFloat($('[name="lbase"]').val()); //// linea base
    for (var i = 1; i<=10; i++) {
      sum=parseFloat(sum)+parseFloat($('[name="m'+i+'"]').val());
    }

    $('[name="tot"]').val((sum+linea).toFixed(2));
    programado = parseFloat($('[name="tot"]').val()); //// programado total
    meta = parseFloat($('[name="meta"]').val()); //// Meta

    if(programado!=0){
      if((programado==meta) && (programado_mes==programado)){
          $('#atit').html('');
          $('#but').slideDown();
    }
    else{
        $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
        $('#but').slideUp();
    }
    }
    else{
      $('#but').slideUp();
    }
  }


  /// Suma Temporalidad Mensual
  function suma_programado_temporalidad(){ 
    programado_reg = parseFloat($('[name="tot"]').val()); //// programado total
    sum=0;
    for (var i = 1; i<=12; i++) {
      sum=parseFloat(sum)+parseFloat($('[name="mes'+i+'"]').val());
    }

    $('[name="total_temp"]').val((sum).toFixed(2));
    programado = document.getElementById("total_temp").value;
    meta = parseFloat($('[name="meta"]').val()); //// Meta

    if(!isNaN(programado) & programado!=0){
      if(programado==meta){
        $('#atit').html('');
        $('#but').slideDown();
      }
      else{
        $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
        $('#but').slideUp();
      }
    }
    else{
      $('#but').slideUp();
    }

  }

  //// suma meta
  function fmeta(){ 
    meta = document.getElementById("meta").value;
    programado = document.getElementById("tot").value;
    programado_mes = document.getElementById("total_temp").value;

    if(!isNaN(meta) & meta!=0){
        if((programado_mes==meta) && (programado==meta)){
        $('#atit').html('');
        $('#but').slideDown();
      }
      else{
        $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
        $('#but').slideUp();
      }
    }
    else{
      $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
      $('#but').slideUp();
    }
  }

////========== FUNCIONES DE MODIFICACION POA
 /*------------ MODIFICAR OBJETIVO ----------------*/
$(function () {
    $(".mod_ff").on("click", function (e) {
      og_id = $(this).attr('name');
      document.getElementById("mog_id").value=og_id;

      var url = base+"index.php/mestrategico/cobjetivo_gestion/get_ogestion";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "og_id="+og_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
          $('#macc_id').html('<option value="'+response.ogestion[0]['acc_id']+'">'+response.ogestion[0]['acc_codigo']+'.- '+response.ogestion[0]['acc_descripcion']+'</option>');
          document.getElementById("mobj_id").value = response.ogestion[0]['obj_id'];
          document.getElementById("mogestion").value = response.ogestion[0]['og_objetivo'];
            document.getElementById("mproducto").value = response.ogestion[0]['og_producto'];
            document.getElementById("mresultado").value = response.ogestion[0]['og_resultado'];
            document.getElementById("mtp_indi").value = response.ogestion[0]['indi_id'];
            document.getElementById("mindicador").value = response.ogestion[0]['og_indicador'];
            document.getElementById("mcod").value = response.ogestion[0]['og_codigo'];

            document.getElementById("mlbase").value = response.ogestion[0]['og_linea_base'];
            document.getElementById("mmeta").value = response.ogestion[0]['og_meta'];
            document.getElementById("munidad").value = response.ogestion[0]['og_unidad'];
            document.getElementById("mobservacion").value = response.ogestion[0]['og_observacion'];
            document.getElementById("mverif").value = response.ogestion[0]['og_verificacion'];

            document.getElementById("mtot").value = response.suma;
            document.getElementById("total_temp_mod").value = response.suma_mes;

          for (var i = 1; i <= 10; i++) {
            document.getElementById("mm"+i+"").value = response.oprogramado['reg'+i+""];
            document.getElementById("mm"+i+"").title = response.titulo['tit'+i+""];
          }

          for (var i = 1; i <=12; i++) {
            document.getElementById("mmes"+i).value = parseInt(response.temporalidad[i]);
          }

          
        if(response.suma!=Math.round(response.ogestion[0]['og_meta']) || response.suma==0 || response.suma_mes!=Math.round(response.ogestion[0]['og_meta'])){
            $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACCIÓN</div></center>');
            $('#mbut').slideUp();
          }
          else{
            $('#amtit').html('');
            $('#mbut').slideDown();
          }

        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS DE LA ACCIÓN");
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
        $("#subir_mact").on("click", function (e) {
            var $validator = $("#form_mod").validate({
                   rules: {
                    og_id: { //// og
                        required: true,
                    },
                    mobj_id: { //// Objetivo Estrategico
                      required: true,
                  },
                  macc_id: { //// Accion Estrategico
                      required: true,
                  },
                    mogestion: { //// Objetivo de Gestion
                      required: true,
                    },
                    mproducto: { //// producto
                      required: true,
                    },
                    mresultado: { //// resultado
                        required: true,
                    },
                    mtp_indi: { //// tipo de indicador
                        required: true,
                    },
                    mindicador: { //// Indicador
                        required: true,
                    },
                    mverif: { //// verificacion
                        required: true,
                    },
                    mlbase: { //// linea base
                        required: true,
                    },
                    mmeta: { //// meta
                        required: true,
                  }
                },
                messages: {
                  mobj_id: "<font color=red>SELECCIONE OBJETIVO ESTRATEGICO</font>", 
                  macc_id: "<font color=red>SELECCIONE ACCIÓN ESTRATEGICA</font>", 
                    mogestion: "<font color=red>REGISTRE OBJETIVO DE GESTI&Oacute;N</font>", 
                  mproducto: "<font color=red>REGISTRE PRODUCTO</font>", 
                  mresultado: "<font color=red>REGISTRE RESULTADO</font>", 
                  mtp_indi: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>",
                  mindicador: "<font color=red>REGISTRE DETALLE DEL INDICADOR</font>",
                  mverif: "<font color=red>REGISTRE MEDIO DE VERIFICACI&Oacute;N</font>",
                  mlbase: "<font color=red>REGISTRE LINEA BASE</font>",
                  mmeta: "<font color=red>REGISTRE META</font>",                     
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
                alertify.confirm("MODIFICAR DATOS DE LA GESTIÓN ?", function (a) {
                    if (a) {
                        document.getElementById('subir_mact').disabled = true;
                        document.forms['form_mod'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });

            }
        });
    });
  });

  /// funcion Meta Modificado
  function fmmeta(){ 
    meta = document.getElementById("mmeta").value;
    programado = document.getElementById("mtot").value;
    programado_mes = document.getElementById("total_temp_mod").value;

    if(!isNaN(meta) & meta!=0){
      if((programado_mes==meta) && (programado==meta)){
        $('#amtit').html('');
        $('#mbut').slideDown();
      }
      else{
        $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
        $('#mbut').slideUp();
      }
    }
    else{
      $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
      $('#mbut').slideUp();
    }
  }


  //// --- suma temporalidad modifcado
  function suma_programado_modificado(){ 
    programado_mes = document.getElementById("total_temp_mod").value;
    sum=0;
    linea = parseFloat($('[name="mlbase"]').val()); //// linea base
    for (var i = 1; i <=10; i++) {
      sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
    }

    $('[name="mtot"]').val((sum+linea).toFixed(2));
    programado = parseFloat($('[name="mtot"]').val()); //// programado total
    meta = parseFloat($('[name="mmeta"]').val()); //// Meta

    if(programado!=0){
      if((programado==meta) && (programado_mes==programado)){
          $('#amtit').html('');
          $('#mbut').slideDown();
      }
      else{
          $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
          $('#mbut').slideUp();
      }
    }
    else{
      $('#mbut').slideUp();
    }

  }

  /// Suma temporalidad mensual modificado
  function suma_programado_temporalidad_modificado(){ 
    programado_reg = parseFloat($('[name="mtot"]').val()); //// programado total
    sum=0;
    for (var i = 1; i<=12; i++) {
      sum=parseFloat(sum)+parseFloat($('[name="mmes'+i+'"]').val());
    }

    $('[name="total_temp_mod"]').val((sum).toFixed(2));
    programado = document.getElementById("total_temp_mod").value;
    meta = parseFloat($('[name="mmeta"]').val()); //// Meta

    if(!isNaN(programado) & programado!=0){
      if(programado==meta){
        $('#amtit').html('');
        $('#mbut').slideDown();
      }
      else{
        $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA DE DISTRIBUCIÓN NO COINCIDE CON LA META PROGRAMADA !!!</div></center>');
        $('#mbut').slideUp();
      }
    }
    else{
      $('#mbut').slideUp();
    }
  }

  ///// Eliminar Accion de corto plazo
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
          var name = $(this).attr('name');
          var request;
          // confirm dialog
          alertify.confirm("ELIMINAR ACCIÓN DE CORTO PLAZO ?", function (a) {
              if (a) {
                var url = base+"index.php/mestrategico/cobjetivo_gestion/delete_ogestion";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: "og_id="+name

                });

                request.done(function (response, textStatus, jqXHR) { 
                    reset();
                    if (response.respuesta == 'correcto') {
                        alertify.alert("LA ACCIÓN SE ELIMINO CORRECTAMENTE ", function (e) {
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