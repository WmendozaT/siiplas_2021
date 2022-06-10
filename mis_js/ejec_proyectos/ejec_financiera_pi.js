base = $('[name="base"]').val();
mes = $('[name="mes"]').val();
descripcion_mes = $('[name="descripcion_mes"]').val();
gestion = $('[name="gestion"]').val();


function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "FICHA TECNICA" , "width=800,height=700,scrollbars=NO") ; 
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


 /// ------ Ejecutar Presupuesto
  $(".ejec_ppto_pi").on("click", function (e) {
      proy_id = $(this).attr('name');
      document.getElementById("proy_id").value=proy_id;
      
      var url = base+"index.php/ejecucion/cejecucion_pi/get_formulario_proyecto_partidas";
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
        //alert(response.respuesta)

        document.getElementById("cod_sisin").value = response.proyecto[0]['proy_sisin']; /// codigo sisin
        document.getElementById("proy_nombre").value = response.proyecto[0]['proy_nombre']; /// nombre proyecto
        document.getElementById("ppto_total").value = response.proyecto[0]['proy_ppto_total']; /// ppto total
        document.getElementById("fase").value = response.fase[0]['fase']+' - '+response.fase[0]['descripcion']; /// fase
        document.getElementById("estado").innerHTML = response.estado; /// estado
        if(response.proyecto[0]['avance_fisico']!=0){
          document.getElementById("ejec_fis").value = response.proyecto[0]['avance_fisico']; /// Ejecucion Fisica
        }
        else{
          document.getElementById("ejec_fis").value = ''; /// Ejecucion Fisica
        }
        
        document.getElementById("lista_partidas").innerHTML = response.partidas; /// partidas

       // alert(response.trimestre)
        /*  document.getElementById("mcod").value = response.producto[0]['prod_cod']; 
          document.getElementById("mprod").value = response.producto[0]['prod_producto']; 
          document.getElementById("mresultado").value = response.producto[0]['prod_resultado'];
          document.getElementById("mverificacion").value = response.producto[0]['prod_fuente_verificacion'];
         if(response.trimestre==1){
          document.getElementById("mprod").disabled = false;
          document.getElementById("mresultado").disabled = false;
          document.getElementById("mverificacion").disabled = false;
         }
         else{
          document.getElementById("mprod").disabled = true;
          document.getElementById("mresultado").disabled = true;
          document.getElementById("mverificacion").disabled = true;
          
         }
         
         document.getElementById("mtipo_i").value = response.producto[0]['indi_id'];
         document.getElementById("mlbase").value = parseInt(response.producto[0]['prod_linea_base']);
         document.getElementById("mmeta").value = parseInt(response.producto[0]['prod_meta']);
         document.getElementById("mtp_met").value = response.producto[0]['mt_id'];


         document.getElementById("mindicador").value = response.producto[0]['prod_indicador'];
         document.getElementById("munidad").value = response.producto[0]['prod_unidades'];
         document.getElementById("mor_id").value = response.producto[0]['or_id'];*/
         



      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

      });
      request.fail(function (jqXHR, textStatus, thrown) {
          console.log("ERROR: " + textStatus);
      });
      request.always(function () {
          //console.log("termino la ejecuicion de ajax");
      });
      e.preventDefault();

      // ===========VALIDAR EL FORMULARIO DE EJECUCION
      $("#subir_ejec").on("click", function (e) {
          var $validator = $("#form_ejec").validate({
              rules: {
              proy_id: { //// proy id
                required: true,
              },
              est_proy: { //// estado
                  required: true,
              },
              ejec_fis: { //// avance fisico
                  required: true,
                  min: 0,
                  max: 100,
              }
            },
            messages: {
              proy_id: "<font color=red>PROYECTO/font>",
              est_proy: "<font color=red>ESTADO DEL PROYECTO</font>", 
              ejec_fis: "<font color=red>AVANCE FISICO DEL PROYECTO</font>",                
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
          var $valid = $("#form_ejec").valid();
          if (!$valid) {
              $validator.focusInvalid();
          } else {

            proy_id=($('[id="proy_id"]').val());
            avance_fisico=($('[id="est_proy"]').val());


              alertify.confirm("MODIFICAR DATOS DE LA ACTIVIDAD ?", function (a) {
                if (a) {
                  //document.getElementById("loadm").style.display = 'block';
                   /* document.getElementById('subir_mform4').disabled = true;
                    document.getElementById("subir_mform4").value = "MODIFICANDO DATOS ACTIVIDAD...";
                    document.forms['form_ejec'].submit();*/
                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
          }
      });
  });





  //// Verificando valor ejecutado por partida
  function verif_valor(ejecutado,sp_id,mes_id,aper_id){
   /// tp 0 : Registro
   /// tp 1 : modifcacion
    document.getElementById("tr_color_partida"+sp_id).style.backgroundColor = "#ffffff"; /// color de fila
    document.getElementById('success_partida'+sp_id).innerHTML = '';
    if(ejecutado!= ''){
      var url = base+"index.php/ejecucion/cejecucion_pi/verif_valor_ejecutado_x_partida";
        var request;
        if (request) {
          request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "ejec="+ejecutado+"&sp_id="+sp_id+"&aper_id="+aper_id+"&mes_id="+mes_id
        });

        request.done(function (response, textStatus, jqXHR) {
        if (response.respuesta == 'correcto') {
            $('#but'+sp_id).slideDown();
            document.getElementById("ejec"+sp_id).style.backgroundColor = "#ffffff";
            document.getElementById('ppto_fin_partida'+sp_id).innerHTML = 'Bs. '+ new Intl.NumberFormat().format(response.ejecucion_total_partida);
        }
        else{
            alertify.error("ERROR EN EL DATO REGISTRADO !");
            document.getElementById("ejec"+sp_id).style.backgroundColor = "#fdeaeb";
            $('#but'+sp_id).slideUp();
        }

      });
    }
    else{
      $('#but'+sp_id).slideUp();
    }
  }


  //// Verificando valor ejecutado por partida
  function verif_observacion(registro,sp_id){ 
    ejec=parseFloat($('[id="ejec'+sp_id+'"]').val());

    if(registro.length>30){
      $('#but'+sp_id).slideDown();
    }
    else{
      if(ejec!=0){
        $('#but'+sp_id).slideUp();
      }
      else{
        $('#but'+sp_id).slideDown();  
      }
      
    }
  }


  /// Funcion para guardar datos de la ejecucion presupuestaria por partida
  function guardar(sp_id,aper_id){
    ejec=parseFloat($('[id="ejec'+sp_id+'"]').val());
    observacion=($('[id="obs'+sp_id+'"]').val());

    if(observacion.length==0 & observacion.length<30){
        document.getElementById("obs"+sp_id).style.backgroundColor = "#fdeaeb";
        alertify.error("REGISTRE OBSERVACION > 30 CARACTERES");
        return 0; 
    }
    else{
        document.getElementById("obs"+sp_id).style.backgroundColor = "#ffffff";
        alertify.confirm("GUARDAR EJECUCION PRESUPUESTARIA ?", function (a) {
        if (a) {
            var url = base+"index.php/ejecucion/cejecucion_pi/guardar_ppto_ejecutado";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "sp_id="+sp_id+"&ejec="+ejec+"&obs="+observacion+"&aper_id="+aper_id
            });

            request.done(function (response, textStatus, jqXHR) {

            if (response.respuesta == 'correcto') {
                alertify.alert("LA EJECUCION SE REGISTRO CORRECTAMENTE ", function (e) {
                  if (e) {
                    document.getElementById('ejec'+sp_id).innerHTML = response.ppto_mes; /// ejecucion mes
                    document.getElementById('obs'+sp_id).innerHTML = response.obs_mes; /// Observacion
                    document.getElementById('ppto_fin_partida'+sp_id).innerHTML = 'Bs. '+ new Intl.NumberFormat().format(response.ppto_total_ejec_partida); /// ppto partida 
                    document.getElementById('ppto_ejec_mes'+aper_id).innerHTML = 'Bs. '+ new Intl.NumberFormat().format(response.ppto_ejec_mes); /// ppto mes ejecutado proyetco inversion
                    document.getElementById('ppto_ejec_total'+aper_id).innerHTML = 'Bs. '+ new Intl.NumberFormat().format(response.ppto_ejec_total_pi); /// ppto Total ejecutado proyetco inversion
                    document.getElementById('avance_fin'+sp_id).innerHTML = response.porcentaje_ejec_partida+' %'; /// % avance financiero 
                    document.getElementById("tr_color_partida"+sp_id).style.backgroundColor = "#edf7ec"; /// color de fila
                    document.getElementById('success_partida'+sp_id).innerHTML = '<img src="'+base+'/assets/ifinal/ok1.png"/><br><font color=green><b>ACTUALIZADO !!</b></font>';
                    alertify.success("REGISTRO EXITOSO ...");
                  }
                });
            }
            else{
                alertify.error("ERROR AL GUARDAR EJECUCIÓN POA");
            }

            });
        } else {
            alertify.error("OPCI\u00D3N CANCELADA");
        }
      });
    }
  }


  //// VER DETALLE DE EJECUCION PRESUPUESTARIA
  $(function () {
    $(".detalle_ejec_ppto_partidas").on("click", function (e) {
      sp_id = $(this).attr('name');
      partida = $(this).attr('id');
      
      $('#titulo').html('<div style="font-size: 15px;font-family: Arial;"><b>PARTIDA '+partida+'</b></div>');
      $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando detalle</div>');
      
      var url = base+"index.php/ejecucion/cejecucion_pi/get_detalle_ejecucion_partida";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "sp_id="+sp_id
      });

      request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {
          $('#content1').fadeIn(1000).html(response.tabla);
        //  $('#caratula').fadeIn(1000).html(response.caratula);
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
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






  ////// FORMULARIO DE PROYECTOS DE INVERSION
  /// Funcion para guardar datos del Proyecto de Inversion
  function guardar_pi(proy_id){
    estado=parseFloat($('[id="est_proy'+proy_id+'"]').val());
    avance_fisico=($('[id="efis_pi'+proy_id+'"]').val());

    alertify.confirm("GUARDAR DATOS DEL PROYECTO ?", function (a) {
      if (a) {
        var url = base+"index.php/ejecucion/cejecucion_pi/guardar_datos_proyecto";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "proy_id="+proy_id+"&estado="+estado+"&fis="+avance_fisico
        });

        request.done(function (response, textStatus, jqXHR) {

        if (response.respuesta == 'correcto') {
            alertify.alert("SE REGISTRO CORRECTAMENTE ", function (e) {
              if (e) {
                document.getElementById('efis_pi'+proy_id).innerHTML = response.proyecto[0]['avance_fisico'];
                //document.getElementById('est_proy'+proy_id).innerHTML = response.proyecto[0]['proy_estado'];
                document.getElementById("tr_color"+proy_id).style.backgroundColor = "#edf7ec";
                document.getElementById('success'+proy_id).innerHTML = '<img src="'+base+'/assets/ifinal/ok1.png"/><br><font color=green><b>ACTUALIZADO !!</b></font>';
                alertify.success("REGISTRO EXITOSO ...");
              }
            });
        }
        else{
            alertify.error("ERROR AL GUARDAR INFORMACION POA");
        }

        });
      } else {
          alertify.error("OPCI\u00D3N CANCELADA");
      }
    });
  }


  //// verificando ejecucion fisica
  function verif_pi_ejecfis(proy_id,valor_antiguo, valor_nuevo){
    if(valor_antiguo!=valor_nuevo){
      document.getElementById("tr_color"+proy_id).style.backgroundColor = "#ffffff";
      document.getElementById('success'+proy_id).innerHTML = '<img src="'+base+'/assets/ifinal/interogacion.png" width:50px; height=50px;/><br><font color=green><b>ACTUALIZAR DATOS !!</b></font>';
    }
  }

///// ======REPORTE FINANCIEROS

  ////------- menu select Opciones
  $("#rep_id").change(function () {
    $("#rep_id option:selected").each(function () {
      rep_id=$(this).val();
      dep_id=($('[id="dep_id"]').val());

      if(rep_id!=0){
        $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" width:30px; height=30px; alt="loading" /><br/>Cargando Información ....</div>');
        var url = base+"index.php/ejecucion/cejecucion_pi/get_tp_reporte";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "rep_id="+rep_id+"&dep_id="+dep_id
        });

        request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              $('#lista_consolidado').fadeIn(1000).html(response.tabla);
              cuadro_grafico_partidas(response.matriz,response.nro)
          }
          else{
            alertify.error("ERROR AL LISTAR");
          }
        }); 
      }
      else{
        $('#lista_consolidado').fadeIn(1000).html('<div class="well"><div class="jumbotron"><h1>Ejecucion Proyectos de Inversión '+gestion+'</h1></div></div>');
      }
    });
  });

  //// grafico barras consolidado por partidas
  function cuadro_grafico_partidas(matriz,nro){
    let texto=[];
    for (var i = 0; i < nro; i++) {
        texto[i]= 'PARTIDA '+matriz[i][2];
    }

    let ejecucion=[];
    for (var i = 0; i < nro; i++) {
        ejecucion[i]= matriz[i][18];
    }


    Highcharts.chart('container', {
      chart: {
          type: 'bar'
      },
      title: {
          text: 'CUADRO DE EJECUCIÓN PRESUPUESTARIA AL MES DE '+descripcion_mes+' / '+gestion
      },
      subtitle: {
          text: 'CONSOLIDADO POR PARTIDAS A NIVEL REGIONAL'
      },
      xAxis: {
          categories: texto,
          title: {
              text: null
          }
      },
      yAxis: {
          min: 0,
          title: {
              text: 'CUMPLIMIENTO DE METAS',
              align: 'high'
          },
          labels: {
              overflow: 'Partidas'
          }
      },
      tooltip: {
          valueSuffix: ' %'
      },
      plotOptions: {
          bar: {
              dataLabels: {
                  enabled: true
              }
          }
      },

      credits: {
          enabled: false
      },

      series: [{
          name: '% EJEC. PRESUPUESTARIA A '+descripcion_mes,
          data: ejecucion
      }]
    });
  }