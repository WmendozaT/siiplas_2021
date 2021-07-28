base = $('[name="base"]').val();
com_id = $('[name="com_id"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "REPORTE FORMULARIO N° 4" , "width=800,height=700,scrollbars=NO") ; 
}


  $(function () {
      $(".importar_ff").on("click", function (e) {
        tipo = $(this).attr('name');
        document.getElementById("tp").value=tipo;
        if(tipo==1){
            $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <b>IMPORTAR ARCHIVO DE OPERACIONES.CSV</b></h2>');
            $('#datos').html('<ul><li type="circle"><b>Copiar el contenido de datos del primer Archivo Excel a la plantilla de migración </b></li> <li type="circle"><b>Numero de columnas 21</b></li> <li type="circle"><b>Columna (A) COD. OR. : Codigo de Objetivo Regional</b></li> <li type="circle"><b>Columna (B) COD. ACT. : Codigo Actividad</b></li> <li type="circle"><b>Columnas (G-T), Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>Convertir el Archivo .Xls a .Csv</b></li></ul>');
            $('#img').html('<img src="'+base+'/assets/img/actividades.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
            $('#buton').html('SUBIR ARCHIVO ACTIVIDADES.SCV');
          }
          else{
            $('#titulo').html('<h2 class="row-seperator-header"><i class="glyphicon glyphicon-import"></i> <font color=blue><b> IMPORTAR ARCHIVO DE REQUERIMIENTOS.SCV (GLOBAL)</b></font></h2>');
            $('#datos').html('<ul><li type="circle"><b>Copiar el contenido de datos del primer Archivo Excel a la plantilla de migración </b></li> <li type="circle"><b>Numero de columnas 22</b></li> <li type="circle"><b>Columna (A) COD. ACT. : Codigo de Actividad</b></li> <li type="circle"><b>Columnas (E-S), por tratarse de presupuesto el Tipo de Dato debe ser GENERAL</b></li> <li type="circle"><b>Convertir el Archivo .Xls a .Csv</b></li></ul>');
            $('#img').html('<img src="'+base+'/assets/img/requerimientos_global.JPG" style="border-style:solid;border-width:5px;" style="width:10px;">');
            $('#buton').html('SUBIR ARCHIVO DE REQUERIMIENTOS.SCV');
          }
      });
  });

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



              /* document.getElementById("detalle").value = response.insumo[0]['ins_detalle'];
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
               }*/
               
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
/*      function suma_programado_modificado(){ 
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
      }*/






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

/*  function confirmar(){
    if(confirm('¿Estas seguro de Eliminar ?'))
      return true;
    else
    return false;
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
*/
  //// VER POA
/*  $(function () {
    $(".enlace").on("click", function (e) {
        proy_id = $(this).attr('name');
        establecimiento = $(this).attr('id');
        
        $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
        $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones - <br>'+establecimiento+'</div>');
        
        var url = base+"index.php/programacion/proyecto/get_poa";
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
            $('#content1').fadeIn(1000).html(response.tabla);
            $('#caratula').fadeIn(1000).html(response.caratula);
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
        }

        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
        });
        e.preventDefault();
        
      });
  });*/

  /*------ AJUSTE POA ------*/
/*  $(function () {
    $(".enlace2").on("click", function (e) {
        proy_id = $(this).attr('name');
        establecimiento = $(this).attr('id');
       
        $('#titulo2').html('<font size=3><b>'+establecimiento+'</b></font>');
        $('#content2').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Poa - <br>'+establecimiento+'</div>');
        
        var url = base+"index.php/programacion/proyecto/get_poa_ajuste";
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
            $('#content2').fadeIn(1000).html(response.tabla);
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS DE LOS SERVICIOS");
        }

        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
        });
        e.preventDefault();
      });
  });*/

  /*------------ VERIFICANDO POA ----------------*/
/*  $(function () {
      $(".verif_poa").on("click", function (e) {
        proy_id = $(this).attr('name');
          document.getElementById("proy_id").value=proy_id;
          
          establecimiento = $(this).attr('id');
          $('#titulo').html('<font size=3><b>'+establecimiento+'</b></font>');
          $('#content_valida').html('<div class="loading" align="center"><h2>Verificando Presupuesto POA  <br>'+establecimiento+'</h2><br><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /></div>');
          $('#but').slideUp();

          var url = base+"index.php/programacion/proyecto/verif_poa";
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
            $('#content_valida').fadeIn(1000).html(response.tabla);
                  if(response.valor==0){
                      $('#but').slideDown();
                }
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
          // =============================VALIDAR EL FORMULARIO DE MODIFICACION
          $("#enviar_ff").on("click", function (e) {
              var $valid = $("#form_vpoa").valid();
              if (!$valid) {
                  $validator.focusInvalid();
              } else {

                  alertify.confirm("ESTA SEGURO EN VALIDAR EL POA, PARA SU APROBACIÓN ?", function (a) {
                      if (a) {
                      var url = base+"index.php/programacion/proyecto/validar_poa";
                      $.ajax({
                          type: "post",
                          url: url,
                          data: {
                              proy_id: proy_id
                          },
                          success: function (date) {
                              window.location.reload(true);
                              alertify.success("VALIDACION EXITOSA ...");
                          }
                      });

                      } else {
                          alertify.error("OPCI\u00D3N CANCELADA");
                      }
                  });

              }
          });
      });
  });*/

/*  $(function () {
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

      $(".aprob_pi").on("click", function (e) {
          reset();
          var proy_id = $(this).attr('name');
          var request;
          alertify.confirm("ESTA SEGURO DE APROBAR POA ?", function (a) {
            if (a) { 
                var url = base+"index.php/programacion/proyecto/aprobar_poa";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                  data: "proy_id="+proy_id

                });

                request.done(function (response, textStatus, jqXHR) { 
                  reset();
                  if (response.respuesta == 'correcto') {
                      alertify.alert("EL POA SE APROBO CORRECTAMENTE ", function (e) {
                          if (e) {
                              window.location.reload(true);
                          }
                      });
                  } else {
                      alertify.alert("ERROR !!!", function (e) {
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
                alertify.error("OPCIÓN CANCELADA");
            }
          });
          return false;
      });
    });*/

    ///// Rechazar POA

 /* $(function () {
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

      $(".neg_ff").on("click", function (e) {
       // alert(base+'/assets/themes_alerta/alertify.default.css')
          reset();
          var proy_id = $(this).attr('name');
          var request;
          alertify.confirm("ESTA SEGURO DE RECHAZAR EL POA Y DEVOLVER AL RESPONSABLE POA ?", function (a) {
              if (a) { 
                  var url = base+"index.php/programacion/proyecto/observar_poa";
                  if (request) {
                      request.abort();
                  }
                  request = $.ajax({
                      url: url,
                      type: "POST",
                      dataType: "json",
                    data: "proy_id="+proy_id

                  });

                  request.done(function (response, textStatus, jqXHR) { 
                    reset();
                    if (response.respuesta == 'correcto') {
                        alertify.alert("SE RECHAZO REPORTE POA", function (e) {
                            if (e) {
                                window.location.reload(true);
                            }
                        });
                    } else {
                        alertify.alert("ERROR AL OBSERVAR !!!", function (e) {
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
                  alertify.error("OPCIÓN CANCELADA");
              }
          });
          return false;
      });
  });*/

