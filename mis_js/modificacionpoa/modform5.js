base = $('[name="base"]').val();
prod_id = $('[name="prod_id"]').val();
proy_id = $('[name="proy_id"]').val();
aper_id = $('[name="aper_id"]').val();
cite_id = $('[name="cite_id"]').val();


  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "REPORTE FORMULARIO N° 5" , "width=800,height=700,scrollbars=NO") ; 
  }

  function abreVentana_comparativo(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "Cuadro Comparativo" , "width=700,height=600,scrollbars=NO") ; 
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
  $("div.toolbar").html('');
  // Apply the filter
  $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
      otable
          .column( $(this).parent().index()+':visible' )
          .search( this.value )
          .draw();   
  } );
  /* END COLUMN FILTER */   
})


$(function () {
    //SUBIR ARCHIVO
    $("#subir_archivo").on("click", function () {
      var $valid = $("#form_subir_sigep").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        if(document.getElementById('archivo').value==''){
          alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
          return false;
        }
          alertify.confirm("SUBIR ARCHIVO REQUERIMIENTOS.CSV?", function (a) {
              if (a) {
                  document.getElementById("subir_archivo").value = "AGREGANDO REQUERIMIENTOS...";
                  document.getElementById("loads").style.display = 'block';
                  document.getElementById('subir_archivo').disabled = true;
                  document.forms['form_subir_sigep'].submit();
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
          });
      }
    });
  });

  function justNumbers(e){
      var keynum = window.event ? window.event.keyCode : e.which;
      if ((keynum == 8) || (keynum == 46))
      return true;
       
      return /\d/.test(String.fromCharCode(keynum));
  }

  //// ELIMINA REQUERIMIENTOS SELECCIONADOS
function valida_eliminar(){
  if(document.del_req.tot.value!=0){
    alertify.confirm("ESTA SEGURO DE ELIMINAR "+document.del_req.tot.value+" REQUERIMIENTOS ?", function (a) {
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
  else{
    alertify.error("SELECCIONE REQUERIMIENTOS A ELIMINAR !!! ");
  }
}


    /// asignar unidad responsable para Bienes y Servicios 2022
    function doSelectAlert(event,com_id,ins_id) {
     //  alert(event+'--'+com_id+'--'+ins_id)
      var url = base+"index.php/modificaciones/cmod_insumo/asignar_uresponsable";
        $.ajax({
            type: "post",
            url: url,
            data:{com_id:com_id,ins_id:ins_id},
                success: function (data) {
                alertify.success('Asignado');  
                //window.location.reload(true);
            }
        });
    }


 //// Cerrar Modificacion POA (Requerimientos)
  $(function () {
    $("#cerrar_mod").on("click", function () {
        var $validator = $("#form_cerrar").validate({
            rules: {
                cite_id: { //// cite
                  required: true,
                },
                observacion: { //// Observacion
                    required: true,
                }
            },
            messages: {
                observacion: "<font color=red>REGISTRE OBSERVACIÓN</font>",                     
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

        var $valid = $("#form_cerrar").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
          alertify.confirm("CERRAR MODIFICACIÓN FINANCIERA ?", function (a) {
                if (a) {
                    document.getElementById("mload").style.display = 'block';
                    document.forms['form_cerrar'].submit();
                    document.getElementById("mbut").style.display = 'none';
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
        }
    });
  });



$(function () {
  $(".comparativo").on("click", function (e) {
    proy_id = $(this).attr('name');
    establecimiento = $(this).attr('id');
    
    $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
    $('#cuadro_comparativo').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Cuadro Comparativo Presupuestario - <br>'+establecimiento+'</div>');
    
    var url = base+"index.php/modificaciones/cmod_insumo/get_comparativo_ptto";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "proy_id="+proy_id
    });

    request.done(function (response, textStatus, jqXHR) {
    if (response.respuesta == 'correcto') {
        $('#cuadro_comparativo').fadeIn(1000).html(response.tabla);
    }
    else{
        alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
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
});


  //// VER LISTA DE CERTIFICACIONES POA POR ITEMS
  $(function () {
    $(".certpoas").on("click", function (e) {
        ins_id = $(this).attr('name');

      var url = base+"index.php/ejecucion/ccertificacion_poa/get_lista_certificaciones_por_items";
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
        $("#cpoas").html(response.lista);
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
       
    });
  });



 //// VALIDA NUEVO REGISTRO DE REQUERIMIENTOS POA
 $(function () {
    $("#subir_ins").on("click", function () {
        var $validator = $("#form_nuevo").validate({
          rules: {
            cite_id: { //// cite
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
            ins_um: { //// unidad medida
                required: true,
            },
            padre: { //// par padre
                required: true,
            },
            partida_id: { //// par hijo
                required: true,
            },
            dato_id: { //// dato id
                required: true,
            }
          },
          messages: {
            ins_detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
            ins_cantidad: "<font color=red>CANTIDAD</font>",
            ins_costo_u: "<font color=red>COSTO UNITARIO</font>",
            costo: "<font color=red>COSTO TOTAL</font>",
            ins_um: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
            padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
            partida_id: "<font color=red>SELECCIONE PARTIDA</font>", 
            dato_id: "<font color=red>ACTIVIDAD</font>",                     
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
        if(dif>=0){
          alertify.confirm("DESEA GUARDAR REQUERIMIENTO ?", function (a) {
            if (a) {
              document.getElementById("loada").style.display = 'block';
                document.getElementById("subir_ins").value = "GUARDANDO REQUERIMIENTO...";
                document.getElementById('subir_ins').disabled = true;
                document.forms['form_nuevo'].submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
          });
        }
        else{
          $('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
            alertify.error("EL MONTO PROGRAMADO NO PUEDE SER MAYO AL MONTO SALDO DE LA OPERACIÓN, VERIFIQUE MONTOS");
        }
      }
    });
});


 /*------ MODIFICAR REQUERIMIENTO -----*/
  $(function () {
      $(".mod_ff").on("click", function (e) {
        ins_id = $(this).attr('name');
        document.getElementById("ins_id").value=ins_id;
        cite_id=document.getElementById("cite_id").value;
    
      //  var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_requerimiento'?>";
        var url = base+"index.php/modificaciones/cmod_insumo/get_requerimiento";

          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "ins_id="+ins_id+"&cite_id="+cite_id
          });

          request.done(function (response, textStatus, jqXHR) {

          if (response.respuesta == 'correcto') {

            $( "#costou" ).prop( "disabled", false );

            if(response.verif_cert==1){
              $( "#detalle" ).prop( "disabled", true );
            //  $( "#costou" ).prop( "disabled", true );
              $( "#umedida" ).prop( "disabled", true );
              $( "#par_padre" ).prop( "disabled", true );
              $( "#par_hijo" ).prop( "disabled", true );
              $( "#observacion" ).prop( "disabled", true );
              if(response.monto_certificado==response.prog[0]['programado_total']){
                $( "#cantidad" ).prop( "disabled", true );
              }
            }
            else{
              $( "#detalle" ).prop( "disabled", false );
              $( "#cantidad" ).prop( "disabled", false );
            //  $( "#costou" ).prop( "disabled", false );
              $( "#umedida" ).prop( "disabled", false );
              $( "#par_padre" ).prop( "disabled", false );
              $( "#par_hijo" ).prop( "disabled", false );
              $( "#observacion" ).prop( "disabled", false );
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
             document.getElementById("par_padre").value = response.ppdre[0]['par_codigo'];
             document.getElementById("par_hijo").value = response.insumo[0]['par_id'];
             document.getElementById("par_id").value = response.insumo[0]['par_id'];
             document.getElementById("mtot").value = response.prog[0]['programado_total'];
             document.getElementById("observacion").value = response.insumo[0]['ins_observacion'];
             document.getElementById("monto_cert").value = response.monto_certificado;
             $("#par_hijo").html(response.lista_partidas);
             $("#id").html(response.lista_prod_act);
             $('#monto').html('<font color=blue size=2><b>MONTO CERTIFICADO : '+response.monto_certificado+'</b></font>');
             $('#ff').html('FUENTE DE FINANCIAMIENTO : '+response.prog[0]['ff_codigo']+' || ORGANISMO FINANCIADOR : '+response.prog[0]['of_codigo']);
             if(response.prog[0]['programado_total']!=response.insumo[0]['ins_costo_total']){
              $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
              $('#mbut').slideUp();
             }

             for (var i = 1; i <=12; i++) {
              mes=mes_texto(i);
             
              document.getElementById("mm"+i).value = response.prog[0]['mes'+i];
           
              if(response.verif_mes['verf_mes'+i]==1){
                document.getElementById("mm"+i).disabled = true;
                $('#mess'+i).html('<font color=red><b>'+mes+' (*)</b></font>');
              }
              else{
                document.getElementById("mm"+i).disabled = false;
                $('#mess'+i).html('<b>'+mes+'</b>');
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
                      id: { //// id
                          required: true,
                      },
                      costou: { //// Costo U
                          required: true,
                      },
                      costot: { //// costo tot
                          required: true,
                      },
                      umedida: { //// unidad medida
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
                      ins_id: "<font color=red>ID</font>",
                      detalle: "<font color=red>REGISTRE DETALLE DEL REQUERIMIENTO</font>", 
                      cantidad: "<font color=red>CANTIDAD</font>",
                      costou: "<font color=red>COSTO UNITARIO</font>",
                      costot: "<font color=red>COSTO TOTAL</font>",
                      umedida: "<font color=red>REGISTRE UNIDAD DE MEDIDA</font>",
                      par_padre: "<font color=red>SELECCIONE GRUPO DE PARTIDAS</font>",
                      par_hijo: "<font color=red>SELECCIONE PARTIDA</font>", 
                      id: "<font color=red>SELECCIONE VINCULACIÓN</font>",                     
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
          
                if(dif>=0){
                    alertify.confirm("MODIFICAR REQUERIMIENTO ?", function (a) {
                        if (a) {
                            document.getElementById("loadm").style.display = 'block';
                            document.getElementById("subir_mins").value = "MODIFICANDO REQUERIMIENTO...";
                            document.getElementById('subir_mins').disabled = true;
                            document.forms['form_mod'].submit();
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


//// ELIMINAR REQUERIMIENTOS POA
$(function () {
    function reset() {
        $("#toggleCSS").attr("href", "<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css");
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
        var ins_id = $(this).attr('name'); // ins id
      //  var cite_id = "<?php echo $cite[0]['cite_id'];?>"; // cite id
        //alert(ins_id)
        var request;
        alertify.confirm("ESTA SEGURO DE ELIMINAR EL REQUERIMIENTO ?", function (a) {
          if (a) {
            var url = base+"index.php/modificaciones/cmod_insumo/delete_requerimiento";
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: "json",
                data: "ins_id="+ins_id+"&cite_id="+cite_id
              });

              request.done(function (response, textStatus, jqXHR) { 
                reset();
                if (response.respuesta == 'correcto') {
                    alertify.alert("EL REQUERIMIENTO SE ELIMINO CORRECTAMENTE ", function (e) {
                        if (e) {
                            window.location.reload(true);
                        }
                    });
                } else {
                    alertify.alert("ERROR AL ELIMINAR REQUERIMIENTO !!!", function (e) {
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




/////// FUNCIONES EXTRAS ======================
  $(document).ready(function () {
    $("#partida_id").change(function () {            
      var par_id = $(this).val(); /// Par id
      //proy=<?php echo $proyecto[0]['proy_id']; ?>; /// Proy id
      //alert(par_id)
      //  var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_monto_partida'?>";
        var url = base+"index.php/modificaciones/cmod_insumo/get_monto_partida";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "par_id="+par_id+"&proy_id="+proy_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
          $('[name="saldo"]').val((response.monto).toFixed(2));

          costo = parseFloat($('[name="costot"]').val()); //// costo Total Programado
          saldo_partida=parseFloat($('[name="saldo"]').val()); /// saldo partida
          total_programado = parseFloat($('[name="tot"]').val()); /// total programado (Temporalidad)

          if(response.monto!=0){
            if((parseFloat(costo).toFixed(2)<=parseFloat(saldo_partida).toFixed(2)) & (parseFloat(costo).toFixed(2)==parseFloat(total_programado).toFixed(2))){
              $('#atit').html('');
            }
            else{
              $('#atit').html('<center><div class="alert alert-danger alert-block">LOS MONTOS DEBEN SER CORREGIDOS</div></center>');
            $('#but').slideUp();
            }
          }
          else{
            $('#atit').html('<center><div class="alert alert-danger alert-block">NO EXISTE PRESUPUESTO DISPONIBLE EN ESA PARTIDA</div></center>');
          $('#but').slideUp();
          }

        }
        else{
            alertify.error("ERROR AL RECUPERAR MONTO ASIGNADO");
        }

        });
      });
  });



  $(document).ready(function () {
    $("#par_hijo").change(function () {            
      var par_id = $(this).val();
    //  proy=<?php echo $proyecto[0]['proy_id']; ?>;
      costo = parseFloat($('[name="costot"]').val()); //// costo
    //  alert(par_id)

      //  var url = "<?php echo site_url().'/modificaciones/cmod_insumo/get_monto_partida'?>";
        var url = base+"index.php/modificaciones/cmod_insumo/get_monto_partida";
          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "par_id="+par_id+"&proy_id="+proy_id
          });

          request.done(function (response, textStatus, jqXHR) {

          if (response.respuesta == 'correcto') {
            par_id1 = parseFloat($('[name="par_id"]').val()); //// par id 
            costo = parseFloat($('[name="costot"]').val()); //// Costo

            if(par_id1==par_id){
              document.getElementById("saldo").value = parseFloat(response.monto+costo).toFixed(2);
              document.getElementById("sal").value = parseFloat(response.monto+costo).toFixed(2);
              saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
              $('[name="monto_dif"]').val((parseFloat($('[name="sal"]').val())-costo).toFixed(2));
            }
            else{
              document.getElementById("saldo").value = parseFloat(response.monto).toFixed(2);
              document.getElementById("sal").value = parseFloat(response.monto).toFixed(2);
              saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
              $('[name="monto_dif"]').val((parseFloat($('[name="sal"]').val())-costo).toFixed(2));
            }
            
            if(costo>saldo_partida){
              $('#matit').html('<center><div class="alert alert-danger alert-block">MONTO PROGRAMADO SUPERA AL MONTO SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
              $('#mbut').slideUp();
            }
            else{
              programado = parseFloat($('[name="mtot"]').val()); //// saldo partida
              if(programado!=costo){
            $('#matit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
                $('#mbut').slideUp();
          }
          else{
            $('[name="monto_dif"]').val((saldo_partida-costo).toFixed(2));
            $('#matit').html('');
                $('#mbut').slideDown();
          }
            }
          }
          else{
              alertify.error("ERROR AL RECUPERAR MONTO ASIGNADO");
          }

          });
      });
  });



/*---------- PARTIDAS ------------*/
  $(document).ready(function() {
    pageSetUp();
      $("#padre").change(function () {
          $("#padre option:selected").each(function () {
          elegido=$(this).val();
        //  aper=<?php echo $proyecto[0]['aper_id']; ?>;
          $('[name="saldo"]').val((0).toFixed(2));
          $('#atit').html('');
          $('#but').slideUp();

          $.post(base+"index.php/prog/combo_partidas_asig", { elegido: elegido,aper:aper_id }, function(data){ 
          $("#partida_id").html(data);
          });     
        });
      });

    $("#partida_id").change(function () {
          $("#partida_id option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"index.php/prog/combo_umedida", { elegido: elegido }, function(data){ 
            $("#ins_um").html(data);
            });     
        });
      }); 
  })



$(document).ready(function() {
    pageSetUp();
    $("#par_padre").change(function () {
          $("#par_padre option:selected").each(function () {
          elegido=$(this).val();
        //  aper=<?php echo $proyecto[0]['aper_id']; ?>;
          $('[name="sal"]').val((0).toFixed(2));
          $('[name="saldo"]').val((0).toFixed(2));
          $('[name="monto_dif"]').val((0).toFixed(2));
          $('#amtit').html('');
          $('#mbut').slideUp();

          $.post(base+"index.php/prog/combo_partidas_asig", { elegido: elegido,aper:aper_id }, function(data){ 
          $("#par_hijo").html(data);
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
      saldo = parseFloat($('[name="saldo"]').val()); //// saldo

      if(programado!=ctotal){

        $('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO, VERIFIQUE DATOS</div></center>');
            $('#but').slideUp();
      }
      else{
        if(ctotal>saldo){
          $('#atit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
              $('#but').slideUp();
        }
        else{
          $('#atit').html('');
              $('#but').slideDown();
        }
        
      }
  }

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

    function costo_totalm(){ 
      s = parseFloat($('[name="sal"]').val()); //// saldo
      a = parseFloat($('[name="cantidad"]').val()); //// cantidad
      b = parseFloat($('[name="costou"]').val()); //// Costo
      
      $('[name="costot"]').val((b*a).toFixed(2) );
      $('[name="costot2"]').val((b*a).toFixed(2) );

      ct = parseFloat($('[name="costot"]').val()); //// total
      mt = parseFloat($('[name="mtot"]').val()); //// prog

      saldo_partida = parseFloat($('[name="sal"]').val()); //// saldo partida
      $('[name="monto_dif"]').val((saldo_partida-ct).toFixed(2) ); // Saldo Disponible

      if(ct!=mt ||  isNaN(a)){
        $('#amtit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
            $('#mbut').slideUp();
      }
      else{
        if(ct>saldo_partida){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
              $('#mbut').slideUp();
        }
        else{
          $('#amtit').html('');
          $('#mbut').slideDown();
        }
        
      }
    }

    function costo_total(){ 
      a = parseFloat($('[name="ins_cantidad"]').val()); //// cantidad
      b = parseFloat($('[name="ins_costo_u"]').val()); //// Costo unitario
      
      $('[name="costo"]').val((b*a).toFixed(2) );
      $('[name="costo2"]').val((b*a).toFixed(2) );

      ct = parseFloat($('[name="costo"]').val()); //// total
      mt = parseFloat($('[name="tot"]').val()); //// prog
      saldo_partida = parseFloat($('[name="saldo"]').val()); //// saldo partida
      $('[name="saldo_disp"]').val((saldo_partida-ct).toFixed(2) ); // Saldo Disponible

      if(ct!=mt ||  isNaN(a) || ct==0){
        $('#atit').html('<center><div class="alert alert-danger alert-block">EL MONTO PROGRAMADO NO COINCIDE CON EL COSTO TOTAL DEL REQUERIMIENTO</div></center>');
            $('#but').slideUp();
      }
      else{
        if(ct>saldo_partida){
          $('#atit').html('<center><div class="alert alert-danger alert-block">COSTO TOTAL SUPERA AL SALDO DE LA PARTIDA, VERIFIQUE MONTOS</div></center>');
              $('#but').slideUp();
        }
        else{
          $('#atit').html('');
              $('#but').slideDown();
        }
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
