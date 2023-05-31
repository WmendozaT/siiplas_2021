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


    $(document).ready(function() {
        pageSetUp();
        $("#dep_id").change(function () {
            $("#dep_id option:selected").each(function () {
                elegido=$(this).val();
                $.post(base+"index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
                    $("#ue_id").html(data);
                });     
            });
        });
    })

    $(document).ready(function() {
        pageSetUp();
        $("#reg_id").change(function () {
            $("#reg_id option:selected").each(function () {
                elegido=$(this).val();
                $.post(base+"index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
                    $("#uejec_id").html(data);
                    document.getElementById("but").style.display = 'block';
                });     
            });
        });
    })



    /// ---- Generar Reporte Detallado por Regional sobre las modificaciones presupuestarias
    function generar_modppto_regional(mp_id) {
      document.getElementById("mp_id").value = mp_id;
      var url = base+"index.php/modificaciones/cmod_presupuestario/get_datos_modificacion_presupuestaria";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "mp_id="+mp_id
      });

      request.done(function (response, textStatus, jqXHR) { 
        if (response.respuesta == 'correcto') {
            $('#titulo_sol').html('<h2 class="alert alert-success"><center> RESOLUCIÓN : '+response.modificacion[0]['resolucion']+'</center></h2>');
            document.getElementById("reg_id").value = response.modificacion[0]['dep_id'];
            $('#dist').html(response.distritales);
         //  header ("Location: http://www.cristalab.com");
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

        // ===VALIDAR REPORTE CLASIFICADO POR REGIONAL
        $("#generar_rep").on("click", function (e) {
            var error='false';
            var regional=document.getElementById('reg_id').value;
            var ue=document.getElementById('uejec_id').value;
            
          
            if(regional==''){
                $('#reg').html('<font color="red" size="1">SELECCIONE REGIONAL (*)</font>');
                document.form_rep.regional.focus() 
                return 0;
            }

            if(ue!=''){
              window.open(base+"index.php/mod_ppto/rep_mod_ppto_distrital/"+mp_id+"/"+ue, "Modificación Presupuestaria", "width=800, height=800");
              $("#modal_regional").modal("hide");
             // document.getElementById("but").style.display = 'none';
            }
            else{
              $('#ue').html('<font color="red" size="1">SELECCIONE UNIDAD EJECUTORA (*)</font>');
                document.form_rep.ue.focus() 
                return 0;
            }
        });
    }

