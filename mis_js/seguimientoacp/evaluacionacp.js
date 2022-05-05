base = $('[name="base"]').val();
gestion = $('[name="gestion"]').val();

function abreVentana_eficiencia(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "EVALUACION ACP" , "width=800,height=700,scrollbars=NO") ; 
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


  //// LISTA DE REGIONALES PARA GENERAR LOS FORM 1
  $("#d_id").change(function () {
    $("#d_id option:selected").each(function () {
      dep_id=$(this).val();
      if(dep_id!=0){
        $('#titulo_lista').html('<font size=3><b>Cargando Informacion ..... </b></font>');
          var url = base+"index.php/ejecucion/cevaluacion_form1/get_lista_form1_x_regionales";
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
              $('#titulo_lista').html(response.tabla);
          }
          else{
              alertify.error("ERROR AL RECUPERAR INFORMACION");
          }

        });
      }
      else{
        $('#titulo_lista').html('');
      }
    });
  });




 /// ACP FORMULARIO N 1
  /// Funcion para guardar datos de Evaluacion POA ACP Regional 2022
  function guardar_acp_regional(pog_id){
    tp=($('[id="tp'+pog_id+'"]').val());
    ejec=($('[id="ejec'+pog_id+'"]').val());
    mverificacion=($('[id="mverificacion'+pog_id+'"]').val());

    var $validator = $("#form_eval"+pog_id).validate({
      rules: {
        ejec: { //// ejecucion
          required: true,
        },
        mverificacion: { //// medio de verificacion
          required: true,
          minlength : 50,
        }
      },
      messages: {
        ejec: "<font color=red>REGISTRE VALOR DE EJECUCION</font>",
        mverificacion: "<font color=red>REGISTRE MEDIO DE VERIFICACION > 50 (Caracteres)</font>",
      }
    });

    var $valid = $("#form_eval"+pog_id).valid();
    if (!$valid) {
        $validator.focusInvalid();
    } else {

      alertify.confirm("GUARDAR EVALUACIÓN ACP ?", function (a) {
      if (a) {
          var url = base+"index.php/ejecucion/cevaluacion_form1/valida_update_evaluacion_acp";
          var request;
          if (request) {
              request.abort();
          }
          request = $.ajax({
              url: url,
              type: "POST",
              dataType: 'json',
              data: "pog_id="+pog_id+"&ejec="+ejec+"&mv="+mverificacion+"&tp="+tp
          });

          request.done(function (response, textStatus, jqXHR) {
              document.getElementById('log'+pog_id).innerHTML = '<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/><b>GUARDANDO REGISTRO ....</b></div>';
              $('#btn_eval'+pog_id).slideUp();
              
              if (response.respuesta == 'correcto') {
                  document.getElementById('log'+pog_id).innerHTML = '';
                  $('#btn_eval'+pog_id).slideDown();

                  document.getElementById('porcentaje'+pog_id).innerHTML = response.calificacion;
                  
                  document.getElementById("ejec"+pog_id).value = response.info_evaluado[0]['ejec_fis'];
                  document.getElementById("mverificacion"+pog_id).value = response.info_evaluado[0]['tmed_verif'];
                  alertify.success("REGISTRO CORRECTAMENTE !!");
              }
              else{
                  alertify.error("ERROR AL GUARDAR INFORMACION");
              }

          });
      } else {
          alertify.error("OPCI\u00D3N CANCELADA");
      }
    });

    }
  }


  /// ACP FORM 1
  //// Verificando valor ejecutado registrado 2022
  function verif_valor_ejecucion(pog_id,valor_registrado){
    if(valor_registrado!=''){
        var url = base+"index.php/ejecucion/cevaluacion_form1/get_datos_acp_regional";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "pog_id="+pog_id+"&ejec="+valor_registrado
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
          //  alert(response.acp_regional[0]['tp_indi_og'])
           if(response.acp_regional[0]['tp_indi_og']==0){
              if((parseFloat(valor_registrado) + parseFloat(response.evaluado))<=response.meta_regional[0]['prog_fis']){
                document.getElementById('porcentaje'+pog_id).innerHTML = response.calificacion;
                document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#ffffff";
                document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#ffffff";
                $('#btn_eval'+pog_id).slideDown();
              }
              else{
                document.getElementById('porcentaje'+pog_id).innerHTML = '<center>---</center>';
                document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#fff0f0";
                document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#fff0f0";
                $('#btn_eval'+pog_id).slideUp();
              }
           }

           if(response.acp_regional[0]['tp_indi_og']==1){
              document.getElementById('porcentaje'+pog_id).innerHTML = response.calificacion;
              document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#ffffff";
              document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#ffffff";
              $('#btn_eval'+pog_id).slideDown();
           }

           if(response.acp_regional[0]['tp_indi_og']==2){
              if((parseFloat(valor_registrado))<=100){
                document.getElementById('porcentaje'+pog_id).innerHTML = response.calificacion;
                document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#ffffff";
                document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#ffffff";
                $('#btn_eval'+pog_id).slideDown();
              }
              else{
                document.getElementById('porcentaje'+pog_id).innerHTML = '<center>---</center>';
                document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#fff0f0";
                document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#fff0f0";
                $('#btn_eval'+pog_id).slideUp();
              }
           }
          
        }
        else{
            alertify.error("ERROR AL RECUPERAR DATOS");
        }

        });
    }
    else{

      document.getElementById('porcentaje'+pog_id).innerHTML = '<center>---</center>';
      document.getElementById("ejec"+pog_id+'').style.backgroundColor = "#fff0f0";
      document.getElementById("mverificacion"+pog_id+'').style.backgroundColor = "#fff0f0";
      $('#btn_eval'+pog_id).slideUp();
    }

  }


















///// MODULO DE REPORTES ACP FORM 1
////------- menu select regionales
  $("#dep_id").change(function () {
    $("#dep_id option:selected").each(function () {
      dep_id=$(this).val();
      if(dep_id!=''){
        var url = base+"index.php/reporte_evalform1/crep_evalform1/get_cuadro_evaluacion_objetivos";
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
            $('#lista_consolidado').fadeIn(1000).html(response.tabla);
          }
          else{
            alertify.error("ERROR AL LISTAR");
          }
        }); 
      }
      else{
        $('#lista_consolidado').fadeIn(1000).html('<div class="well"><div class="jumbotron"><h1>Evaluaci&oacute;n A.C.P. '+gestion+'</h1></div></div>');
      }
    });
  });

