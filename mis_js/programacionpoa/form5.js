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
            $.post(base+"/index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
            $("#partida_id").html(data);
            });     
        });
      });

      $("#partida_id").change(function () {
          $("#partida_id option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"/index.php/prog/combo_umedida", { elegido: elegido }, function(data){ 
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
            $.post(base+"/index.php/prog/combo_partidas", { elegido: elegido }, function(data){ 
            $("#par_hijo").html(data);
            });     
        });
      });

      $("#par_hijo").change(function () {
          $("#par_hijo option:selected").each(function () {
            elegido=$(this).val();
            $.post(base+"/index.php/prog/combo_umedida", { elegido: elegido }, function(data){  
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








