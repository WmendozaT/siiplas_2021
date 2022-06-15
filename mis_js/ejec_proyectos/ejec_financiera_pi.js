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
      
      $('#load').html('');
      $('#button').slideDown();

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

            alertify.confirm("MODIFICAR INFORMACION DE EJECUCIÓN ?", function (a) {
              if (a) {
                $('#button').slideUp();
                $('#load').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>GUARDANDO INFORMACIÓN ...</div>');
                document.forms['form_ejec'].submit();
              } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
              }
            });
          }
      });
  });


  //// SUBIR IMAGEN DEL PROYECTO
  $(function () {
    $(".fotos_pi").on("click", function (e) {
      proy_id = $(this).attr('name');
      proyecto = $(this).attr('id');
      
      document.getElementById("p_id").value=proy_id;
      document.getElementById("proyecto").innerHTML = proyecto; /// estado
      document.getElementById("detalle_imagen").value = ''; /// Descripcion imagen

      $("#subir_archivo").on("click", function () {

        var $validator = $("#form_subir_img").validate({
            rules: {
            p_id: { //// proy id
              required: true,
            },
            archivo: { //// archivo
              required: true,
            },
            detalle_imagen: { //// detalle
              required: true,
              minlength : 10,
            }
          },
          messages: {
            p_id: "<font color=red>PROYECTO/font>",
            archivo: "<font color=red>SELECCIONE ARCHIVO (IMAGEN)</font>", 
            detalle_imagen: "<font color=red>REGISTRE LA DESCRIPCION DE LA FOTO</font>",                
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


        var $valid = $("#form_subir_img").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {

          alertify.confirm("SUBIR ARCHIVO ?", function (a) {
            if (a) {
              //  document.getElementById("loads").style.display = 'block';
                document.getElementById('subir_archivo').disabled = true;
                document.getElementById("subir_archivo").value = "Subiendo Archivo...";
                document.forms['form_subir_img'].submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
          });
        }
      });

    });
  });

  //// VER GALERIA DE IMEGENES DEL PROYECTO
  $(function () {
    $(".lista_img_pi").on("click", function (e) {
      proy_id = $(this).attr('name');
      
      document.getElementById("id_proy").value=proy_id;

      var url = base+"index.php/ejecucion/cejecucion_pi/get_galeria_imagenes_proyecto";
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
            document.getElementById("dat_proyecto").innerHTML = '<b>PROYECTO : </b>'+response.proyecto[0]['proy_nombre']; /// partidas
            document.getElementById("lista_galeria").innerHTML = response.lista_galeria;
        }
        else{
            alertify.error("ERROR !!!");
        }

      });

    });
  });

  //// VER EJECUCION PRESUPUESTARIA A NIVEL DEL PROYECTO
  $(function () {
    $(".lista_ppto_pi").on("click", function (e) {
      proy_id = $(this).attr('name');
      
      document.getElementById("id").value=proy_id;

      var url = base+"index.php/ejecucion/cejecucion_pi/get_ejecucion_presupuestaria_pi";
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
            document.getElementById("datos_proyecto").innerHTML = '<b>PROYECTO : </b>'+response.proyecto[0]['proy_nombre']; /// partidas
            document.getElementById("detalle_ejecucion").innerHTML = response.detalle_ejecucion;
            cuadro_grafico_ppto_ejec_meses(response.ppto)
        }
        else{
            alertify.error("ERROR !!!");
        }

      });

    });
  });


//// grafico REGRESION ppto ejecjutado por meses PROYECTO DE INVERSION
function cuadro_grafico_ppto_ejec_meses(matriz){
  let ejecucion=[];
  for (var i = 0; i <=11; i++) {
      ejecucion[i]= matriz[i];
  }

  chart = new Highcharts.Chart({
  chart: {
    renderTo: 'ejec_mensual',  // Le doy el nombre a la gráfica
    defaultSeriesType: 'line' // Pongo que tipo de gráfica es
  },
  title: {
    text: 'EJECUCIÓN PRESUPUESTARIA - GESTION : '+gestion  // Titulo (Opcional)
  },
  subtitle: {
    text: 'Detalle de Ejecución Mensual a nivel Regional'   // Subtitulo (Opcional)
  },
  // Pongo los datos en el eje de las 'X'
  xAxis: {
    categories: ['ENE.','FEB.','MAR.','ABR.','MAY.','JUN.','JUL.','AGO.','SEPT.','OCT.','NOV.','DIC.'],
    // Pongo el título para el eje de las 'X'
    title: {
      text: 'Presupuesto Ejecutado Mensualmente'
    }
  },
  yAxis: {
    // Pongo el título para el eje de las 'Y'
    title: {
      text: 'Monto ejecutado'
    }
  },
  // Doy formato al la "cajita" que sale al pasar el ratón por encima de la gráfica
  tooltip: {
    enabled: true,
    formatter: function() {
      return '<b>'+ this.series.name +'</b><br/>'+
        this.x +': '+ this.y +' '+this.series.name;
    }
  },
  // Doy opciones a la gráfica
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: true
    }
  },
  // Doy los datos de la gráfica para dibujarlas
  series: [
      {
        name: '(Bs.) Ppto. Ejecutado',
        data: ejecucion
      }
    ],
    
  });
}

//// grafico REGRESION ppto ejecjutado por meses ACUMULADO PROYECTO DE INVERSION
function cuadro_grafico_ppto_ejec_meses_acumulado(matriz){
  let ejecucion=[];
  for (var i = 0; i <=11; i++) {
      ejecucion[i]= matriz[i];
  }

  chart = new Highcharts.Chart({
  chart: {
    renderTo: 'ejec_acumulado_mensual',  // Le doy el nombre a la gráfica
    defaultSeriesType: 'line' // Pongo que tipo de gráfica es
  },
  title: {
    text: 'EJECUCIÓN PRESUPUESTARIA - GESTION : '+gestion  // Titulo (Opcional)
  },
  subtitle: {
    text: 'Detalle de Ejecución Mensual Acumulado a nivel Regional'   // Subtitulo (Opcional)
  },
  // Pongo los datos en el eje de las 'X'
  xAxis: {
    categories: ['ENE.','FEB.','MAR.','ABR.','MAY.','JUN.','JUL.','AGO.','SEPT.','OCT.','NOV.','DIC.'],
    // Pongo el título para el eje de las 'X'
    title: {
      text: 'Presupuesto Ejecutado Mensualmente Acumulado'
    }
  },
  yAxis: {
    // Pongo el título para el eje de las 'Y'
    title: {
      text: 'Monto ejecutado'
    }
  },
  // Doy formato al la "cajita" que sale al pasar el ratón por encima de la gráfica
  tooltip: {
    enabled: true,
    formatter: function() {
      return '<b>'+ this.series.name +'</b><br/>'+
        this.x +': '+ this.y +' '+this.series.name;
    }
  },
  // Doy opciones a la gráfica
  plotOptions: {
    line: {
      dataLabels: {
        enabled: true
      },
      enableMouseTracking: true
    }
  },
  // Doy los datos de la gráfica para dibujarlas
  series: [
      {
        name: '(Bs.) Ppto. Ejecutado',
        data: ejecucion
      }
    ],
    
  });
}


//// Verificando valor ejecutado por partida
function verif_valor(ejecutado,sp_id,mes_id,proy_id){
 /// tp 0 : Registro
 /// tp 1 : modifcacion
 $('#button').slideDown();
  document.getElementById("tr_color_partida"+sp_id).style.backgroundColor = "#ffffff"; /// color de fila
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
        data: "ejec="+ejecutado+"&sp_id="+sp_id+"&mes_id="+mes_id
      });

      request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {
          $('#button').slideDown();
          document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#ffffff";
      }
      else{
          alertify.error("ERROR EN EL DATO REGISTRADO !");
          document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#fdeaeb";
          $('#button').slideUp();
      }

    });
  }
  else{
    $('#button').slideUp();
    document.getElementById("ejec_fin"+sp_id).style.backgroundColor = "#fdeaeb";
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
              cuadro_grafico_partidas(response.matriz,response.nro);/// detalle partidas
              cuadro_grafico_ppto_ejec_meses(response.vector_meses);  //// mensual
              cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado) /// meses Acumulado

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
              text: '(%) EJECUCIÓN',
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