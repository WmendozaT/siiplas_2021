base = $('[name="base"]').val();
com_id = $('[name="com_id"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE FORMULARIO N° 4" , "width=800,height=700,scrollbars=NO") ; 
}

  //// Subir Archivo de Migracionform 4 y form5
  $(function () {
    $(".importar_ff").on("click", function (e) {
      tipo = $(this).attr('name');
      document.getElementById("tp").value=tipo;
      if(tipo==1){
          $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <b>IMPORTAR ARCHIVO FORM 4.CSV</b></h2>');
          $('#img').html('<img src="'+base+'/assets/img/img_migracion/migracion_f4.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
          $('#buton').html('SUBIR ARCHIVO ACTIVIDADES.SCV');
        }
        else{
          $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <font color=blue><b> IMPORTAR ARCHIVO DE FORM 5.SCV (GLOBAL)</b></font></h2>');
          $('#img').html('<img src="'+base+'/assets/img/img_migracion/migracion_form5.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
          $('#buton').html('SUBIR ARCHIVO DE REQUERIMIENTOS.SCV');
        }
    });
  });

    /// ---- TIPO DE INDICADOR
    $(document).ready(function () {
        $("#tipo_i").change(function () {            
          var tp_id = $(this).val();
            if(tp_id==2){
              $('#trep').slideDown();
            }
            else{
              $('#trep').slideUp();
              for (var i = 1; i <= 12; i++) {
                  $('[name="m'+i+'"]').val((0).toFixed(0));
                  $("#m"+i).html('');
                  $('[name="m'+i+'"]').prop('disabled', false);
              }
              $('[name="total"]').val((0).toFixed(0));
              $('[name="tp_met"]').val((3).toFixed(0));
            }
          });
      });

    /// TIPO DE META
      $(document).ready(function () {
        $("#tp_met").change(function () {            
          var tp_met = $(this).val();
            if(tp_met==1){
              meta = parseFloat($('[name="meta"]').val());
              for (var i = 1; i <= 12; i++) {
                $('[name="m'+i+'"]').val((meta).toFixed(0));
                $("#m"+i).html('%');
                $('[name="m'+i+'"]').prop('disabled', true);
              }
              $('[name="total"]').val((meta).toFixed(0));
            }
            else{
              for (var i = 1; i <= 12; i++) {
                $('[name="m'+i+'"]').val((0).toFixed(0));
                $("#m"+i).html('');
                $('[name="m'+i+'"]').prop('disabled', false);
              }
              $('[name="total"]').val((0).toFixed(0));
            }
          });
      });


  //// Subir archivo de migracion form4 y 5
  $(function () {
    //SUBIR ARCHIVO
    $("#subir_archivo").on("click", function () {
      var $valid = $("#form_subir_sigep").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        if(document.getElementById('archivo').value==''){
          alertify.alert('PORFAVOR SELECCIONE ARCHIVO .CSV');
          return false;
        }

        alertify.confirm("REALMENTE DESEA SUBIR ESTE ARCHIVO?", function (a) {
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


      $(document).ready(function() {
        pageSetUp();
        $("#obj_id").change(function () {
            $("#obj_id option:selected").each(function () {
            elegido=$(this).val();
            $.post(+base+"/index.php/prog/combo_acciones", { elegido: elegido }, function(data){ 
              $("#acc_id").html(data);
              });     
          });
        });  
      });
      $("#acc_id").change(function () {
        $("#acc_id option:selected").each(function () {
          elegido=$(this).val();
            $.post(+base+"/index.php/prog/combo_indicadores", { elegido: elegido}, function(data){
              $("#indi_pei").html(data);
            });     
          });
      });



      function verif_codigo(){ 
        codigo = parseFloat($('[name="cod"]').val()); //// codigo
        com_id=com_id;
        if(!isNaN(codigo) & codigo!=0){

          var url = base+"index.php/programacion/producto/verif_codigo";
          $.ajax({
            type:"post",
            url:url,
            data:{codigo:codigo,com_id:com_id},
            success:function(datos){
              if(datos.trim() =='true'){
                $('#atit').html('<center><div class="alert alert-danger alert-block">C&Oacute;DIGO DE ACTIVIDAD '+codigo+' YA SE ENCUENTRA REGISTRADO</div></center>');
                $('[name="cod"]').val((0).toFixed(0));
                $('#but').slideUp();
              }else{
                $('#atit').html('');
                $('#but').slideDown();
              }
          }});
        }
        else{
          alertify.error("REGISTRE CÓDIGO DE ACTIVIDAD");
          $('#but').slideUp();
        }
      }

      //// VERIF META PROGRAMADO
      function verif_suma_programado(){ /// meta
        meta = parseFloat($('[name="meta"]').val()); //// linea base
        if(meta!=0){
          total = parseFloat($('[name="total"]').val()); //// linea base
          if(meta==total){
            $('#atit').html('');
            $ ('#but').slideDown();
          }
          else{
            $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
            $('#but').slideUp();
          }
        }
        else{
          $('#but').slideUp();
        }
      }

      /// -- SUMA PROGRAMADO
      function suma_programado(){ 
        sum=0;
        linea = parseFloat($('[name="lbase"]').val()); //// linea base
        codigo = parseFloat($('[name="cod"]').val()); //// codigo
        for (var i = 1; i<=12; i++) {
          sum=parseFloat(sum)+parseFloat($('[name="m'+i+'"]').val());
        }

        $('[name="total"]').val((sum+linea).toFixed(2));
        programado = parseFloat($('[name="total"]').val()); //// programado total
        meta = parseFloat($('[name="meta"]').val()); //// Meta

        if(programado!='' || programado!=0){
          if(programado!=meta){
            $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
            $('#but').slideUp();
          }
          else{

            if(codigo==0){
              $('#but').slideUp();
            }
            else{
              $('#atit').html('');
              $ ('#but').slideDown();
            }
          }
        }
        else{
          $('#but').slideUp();
        }
      }

      /*------- ELIMINAR REQUERIMIENTOS DEL SERVICIO --------*/
      function eliminar_requerimientos_servicio(){
        alertify.confirm("DESEA ELIMINAR TODOS LOS REQUERIMEINTOS DE LA UNIDAD ?", function (a) {
          if (a) {
            window.location=base+"index.php/prog/delete_insumos_servicio/"+com_id;
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }

      /*------- UPDATE CÓDIGO --------*/
      function update_codigo(){
        alertify.confirm("DESEA ACTUALIZAR LOS CÓDIGOS DE ACTIVIDAD ?", function (a) {
          if (a) {
            window.location=base+"index.php/prog/update_codigo/"+com_id;
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }

    /// GUARDAR NUEVO FORMULARIO N4 
    $(function () {
        $("#subir_ope").on("click", function () {
          var $validator = $("#form_nuevo").validate({
              rules: {
                com_id: {
                  required: true,
                },
                prod: {
                    required: true,
                },
                resultado: {
                    required: true,
                },
                tipo_i: {
                    required: true,
                },
                indicador: {
                    required: true,
                },
                lbase: {
                    required: true,
                },
                meta: {
                    required: true,
                }
              },
              messages: {
                prod: {required: "<font color=red size=1>REGISTRE DESCRIPCIÓN DE ACTIVIDAD</font>"},
                resultado: {required: "<font color=red size=1>REGISTRE RESULTADO</font>"},
                tipo_i: {required: "<font color=red size=1>SELECCIONE UNIDAD EJECUTORA</font>"},
                indicador: {required: "<font color=red size=1>REGISTRE INDICADOR</font>"},
                lbase: {required: "<font color=red size=1>REGISTRE LINEA BASE</font>"},
                meta: {required: "<font color=red size=1>REGISTRE META DE LA ACTIVIDAD</font>"}                    
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
            if(document.form_nuevo.tipo_i.value==1){
              meta = parseFloat($('[name="meta"]').val());
              total = parseFloat($('[name="total"]').val());
              if(parseFloat(meta)!=parseFloat(total)){
                alertify.error("LA SUMA DE MESES PROGRAMADOS NO ES IGUAL A LA META DE LA ACTIVIDAD") 
                  document.form_nuevo.meta.focus() 
                  return 0; 
              }
            } 
            else{
              if(document.form_nuevo.tp_met.value==0){
                alertify.error("SELECCIONE TIPO DE META") 
                  document.form_nuevo.resultado.focus() 
                  return 0; 
              }
              if(document.form_nuevo.tipo_i.value==2){
                if(document.form_nuevo.tp_met.value==3){
                  meta = parseFloat($('[name="meta"]').val());
                  total = parseFloat($('[name="total"]').val());
                  if(parseFloat(meta)!=parseFloat(total)){
                    alertify.error("LA SUMA DE MESES PROGRAMADOS NO ES IGUAL A LA META DE LA ACTIVIDAD") 
                      document.form_nuevo.meta.focus() 
                      return 0; 
                  }
                }
              }
            }

            if(document.form_nuevo.cod.value==0 || document.form_nuevo.cod.value==''){
              alertify.error("REGISTRE CÓDIGO DE ACTIVIDAD") 
                document.form_nuevo.cod.focus() 
                return 0;
            }

            alertify.confirm("GUARDAR DATOS DE LA ACTIVIDAD ?", function (a) {
              if (a) {
                  document.getElementById("loadp").style.display = 'block';
                  document.forms['form_nuevo'].submit();
                  document.getElementById("subir_ope").style.display = 'none';
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
            });
          }
      });
    });


  /*---- MODIFICAR FORMULARIO N 4 ---*/
    $(function () {
        $(".mod_ff").on("click", function (e) {
            prod_id = $(this).attr('name');
            document.getElementById("prod_id").value=prod_id;
            
            var url = base+"index.php/programacion/producto/get_producto";
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

               document.getElementById("mcod").value = response.producto[0]['prod_cod']; 
               document.getElementById("mprod").value = response.producto[0]['prod_producto']; 
               document.getElementById("mresultado").value = response.producto[0]['prod_resultado'];
               document.getElementById("mtipo_i").value = response.producto[0]['indi_id'];

               document.getElementById("mindicador").value = response.producto[0]['prod_indicador'];
               document.getElementById("mverificacion").value = response.producto[0]['prod_fuente_verificacion'];
               document.getElementById("munidad").value = response.producto[0]['prod_unidades'];

               document.getElementById("mlbase").value = parseInt(response.producto[0]['prod_linea_base']);
               document.getElementById("mmeta").value = parseInt(response.producto[0]['prod_meta']);
               document.getElementById("munidad").value = response.producto[0]['prod_unidades'];

               document.getElementById("mor_id").value = response.producto[0]['or_id'];
               document.getElementById("mtp_met").value = response.producto[0]['mt_id'];

               for (var i = 1; i <=12; i++) {
                document.getElementById("mm"+i).value = parseInt(response.temp[i]);
                if(response.producto[0]['indi_id']==2 && response.producto[0]['mt_id']==1){
                  document.getElementById("mm"+i).disabled = true;
                }
                else{
                document.getElementById("mm"+i).disabled = false;
                }
               }
                /// Tipo de Meta
/*               if(response.producto[0]['indi_id']==2 && response.producto[0]['mt_id']==1){ /// Recurrente
                  document.getElementById("mm"+i).disabled = true;
                }
                else{
                document.getElementById("mm"+i).disabled = false;
                }*/



               $('[name="mtotal"]').val((parseInt(response.sum_temp)).toFixed(0));
               if(response.producto[0]['indi_id']==2 && response.producto[0]['mt_id']==1){
                document.getElementById("mtrep").style.display = 'block';
               }
               else{
                document.getElementById("mtrep").style.display = 'none';
               }

            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS DE LA ACTIVIDAD");
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
            $("#subir_mform4").on("click", function (e) {
                var $validator = $("#form_mod").validate({
                       rules: {
                        prod_id: { //// prod id
                          required: true,
                        },
                        mprod: { //// prod
                            required: true,
                        },
                        mresultado: { //// resultado
                            required: true,
                        },
                        mtipo_i: { //// tipo de indi
                            required: true,
                        },
                        mindicador: { //// indicador
                            required: true,
                        },
                        munidad: { //// unidad
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
                        prod_id: "<font color=red>ACTIVIDAD/font>",
                        mprod: "<font color=red>REGISTRE DETALLE DE LA ACTIVIDAD</font>", 
                        mresultado: "<font color=red>REGISTRE RESULTADO</font>",
                        mtipo_i: "<font color=red>TIPO DE INDICADOR</font>",
                        mindicador: "<font color=red>RESGISTRE INDICADOR</font>",
                        munidad: "<font color=red>REGISTRE UNIDAD RESPONSABLE</font>",
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

                  $('#matit').html('');
                    alertify.confirm("MODIFICAR DATOS DE LA ACTIVIDAD ?", function (a) {
                      if (a) {
                        document.getElementById("loadm").style.display = 'block';
                          document.getElementById('subir_mform4').disabled = true;
                          document.getElementById("subir_mform4").value = "MODIFICANDO DATOS ACTIVIDAD...";
                          document.forms['form_mod'].submit();
                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                      }
                  });
                }
            });
        });
    });

    /// Tipo de indicador (Modificacion POA)
    $(document).ready(function () {
      $("#mtipo_i").change(function () {            
        var tp_id = $(this).val();
    
          if(tp_id==2){
            $('#mtrep').slideDown();
          }
          else{
            $('#mtrep').slideUp();
            for (var i = 1; i <= 12; i++) {
                $('[name="mm'+i+'"]').val((0).toFixed(0));
                $("#mm"+i).html('');
                $('[name="mm'+i+'"]').prop('disabled', false);
            }
            $('[name="mtotal"]').val((0).toFixed(0));
            $('[name="mtp_met"]').val((3).toFixed(0));
          }
        });
    });

    /// Tipo de Meta (modificacion poa)
    $(document).ready(function () {
      $("#mtp_met").change(function () {            
        var tp_met = $(this).val();
        
          if(tp_met==0){
            $('#mbut').slideUp();
          }
          else{
            if(tp_met==1){
              meta = parseFloat($('[name="mmeta"]').val());
              for (var i = 1; i <= 12; i++) {
                $('[name="mm'+i+'"]').val((meta).toFixed(0));
                $("#m"+i).html('%');
                $('[name="mm'+i+'"]').prop('disabled', true);
              }
              $('[name="mtotal"]').val((meta).toFixed(0));

              $('#matit').html('');
              $('#mbut').slideDown();
            }
            else{
              for (var i = 1; i <= 12; i++) {
                $('[name="mm'+i+'"]').val((0).toFixed(0));
                $("#mm"+i).html('');
                $('[name="mm'+i+'"]').prop('disabled', false);
              }
              $('[name="mtotal"]').val((0).toFixed(0));
              
              programado = parseFloat($('[id="mtotal"]').val()); //// programado total
              meta = parseFloat($('[id="mmeta"]').val()); //// Meta
              if(meta==programado){
                $('#matit').html('');
                $('#mbut').slideDown();
              }
              else{
                $('#matit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
                $('#mbut').slideUp();
              }
            }
          }
        });
    });

  /// ---- Suma Programado Modificado
    function suma_programado_modificado(){ 
      sum=0;
      linea = parseFloat($('[name="mlbase"]').val()); //// linea base
      codigo = parseFloat($('[name="mcod"]').val()); //// codigo
      for (var i = 1; i<=12; i++) {
        sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
      }

      $('[name="mtotal"]').val((sum+linea).toFixed(2));
      programado = parseFloat($('[name="mtotal"]').val()); //// programado total
      meta = parseFloat($('[name="mmeta"]').val()); //// Meta

      if(programado!='' || programado!=0){
        if(programado!=meta){
          $('#matit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
          $('#mbut').slideUp();
        }
        else{

          if(codigo==0){
            $('#mbut').slideUp();
          }
          else{
            $('#matit').html('');
            $ ('#mbut').slideDown();
          }
        }
      }
      else{
        $('#mbut').slideUp();
      }
    }


    //// VERIF META (Modificado)
    function verif_meta_mod(){ /// meta
      meta = document.getElementById("mmeta").value;

      if(meta!='' & meta!=0){
          total = parseFloat($('[name="mtotal"]').val()); //// linea base
          indicador = parseFloat($('[name="mtipo_i"]').val()); //// Indicador
          tipo_meta = parseFloat($('[name="mtp_met"]').val()); //// tipo Meta

          if(indicador==2 & tipo_meta==1){
            for (var i = 1; i <=12; i++) {
                document.getElementById("mm"+i).value = parseInt(meta);
              }
              document.getElementById("mmeta").value = parseInt(meta);
              $('#mbut').slideDown();
            }
            else{
              if(meta==total){
                $('#matit').html('');
                $('#mbut').slideDown();
              }
              else{
                $('#matit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
                $('#mbut').slideUp();
              }
            }
      }
      else{
        $('#mbut').slideUp();
      }
    }


    //// Elimina Actividades Seleccionados
    function valida_eliminar(){
      if (document.del_req.tot.value=="" || document.del_req.tot.value==0){
        alertify.error("SELECCIONE ACTIVIDADES A ELIMINAR");
      }
      else{
        alertify.confirm("DESEA ELIMINAR "+document.del_req.tot.value+" ACTIVIDAD(es) ?", function (a) {
          if (a) {
              document.getElementById("btsubmit").value = "ELIMINANDO ACTIVIDAD(es)...";
              document.getElementById("btsubmit").disabled = true;
              document.del_req.submit();
              return true;
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }
    }


    //// ELiminar Actividad
  $(function () {
/*      function reset() {
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
      }*/

      // =====================================================================
    $(".del_ff").on("click", function (e) {
        reset();
        var prod_id = $(this).attr('name');
        var request;
        // confirm dialog
        alertify.confirm("DESEA ELIMINAR ACTIVIDAD ?", function (a) {
          if (a) { 
            var url = base+"index.php/programacion/producto/desactiva_producto";
            if (request) {
              request.abort();
            }
            request = $.ajax({
              url: url,
              type: "POST",
              dataType: "json",
              data: "prod_id="+prod_id
            });

            request.done(function (response, textStatus, jqXHR) { 
              reset();
            //  alert(response.verif)
              if (response.respuesta == 'correcto') {
                alertify.alert("LA ACTIVIDAD SE ELIMINO CORRECTAMENTE ", function (e) {
                  if (e) {
                    window.location.reload(true);
                  }
                });
              } else {
                alertify.alert("ERROR AL ELIMINAR LA ACTIVIDAD !!!", function (e) {
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
              // user clicked "cancel"
              alertify.error("OPERACION CANCELADA");
          }
        });
      return false;
    });
  });


