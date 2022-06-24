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

///// ===================================================
/// ------- MENU REPORTE EJECUCION PRESUPUESTARIA pi
$(document).ready(function() {
  pageSetUp();
  $("#dep_id").change(function () {
    $("#dep_id option:selected").each(function () {
      dep_id=$(this).val();

      $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Información ...</div>');
      var url = base+"index.php/reporte_ejecucion_proyectos/creportejecucion_pi/get_detalle_ejecucion_ppto_pi_regional_institucional";
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
              if(dep_id==0){ /// Institucional
                $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                cuadro_grafico_distribucion_proyectos(response.matriz_reg,response.nro_reg); /// grafico 1 detalle por proyecto
                cuadro_grafico_distribucion_presupuesto_asignado(response.matriz_reg,response.nro_reg)  /// grafico 1 detalle por presupuesto

                cuadro_grafico_partidas(response.matriz_part,response.nro_part,'INSTITUCIONAL');/// detalle partidas

                cuadro_grafico_ppto_ejec_meses(response.vector_meses,'INSTITUCIONAL');  //// mensual
                cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'INSTITUCIONAL') /// meses Acumulado

              }
              else{ /// Regional
                $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                cuadro_grafico_partidas(response.matriz,response.nro,'REGIONAL');/// detalle partidas
                cuadro_grafico_ppto_ejec_meses(response.vector_meses,'REGIONAL');  //// mensual
                cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'REGIONAL') /// meses Acumulado
              }
          }
          else{
              alertify.error("ERROR AL LISTAR");
          }
        }); 

    });
  });

})


//// grafico Pastel Porcenjate distribucion por proyectos
function cuadro_grafico_distribucion_proyectos(matriz,nro){
  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= { name: matriz[i][1],y: matriz[i][4],drilldown: matriz[i][1]};
  }
  // Build the chart
  Highcharts.chart('detalle_proyectos1', {
    chart: {
      type: 'pie'
    },
    title: {
      text: ''
    },
    subtitle: {
      text: 'PORCENTAJE DE DISTRIBUCIÓN DE PROYECTOS, GESTIÓN '+gestion
    },

    accessibility: {
      announceNewData: {
        enabled: true
      },
      point: {
        valueSuffix: '%'
      }
    },

    plotOptions: {
      series: {
        dataLabels: {
          enabled: true,
          format: '{point.name}: {point.y:.1f}%'
        }
      }
    },

    tooltip: {
      headerFormat: '<span style="font-size:16px">{series.name}</span><br>',
      pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b><br/>'
    },

    series: [
      {
        name: "DEPARTAMENTO",
        colorByPoint: true,
        data: detalle
      }
    ]
    
  });
}

//// grafico Pastel Porcenjate distribucion por Presupuesto
function cuadro_grafico_distribucion_presupuesto_asignado(matriz,nro){
  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= { name: matriz[i][1],y: matriz[i][10],drilldown: matriz[i][1]};
  }
  // Build the chart
  Highcharts.chart('detalle_proyectos2', {
    chart: {
      type: 'pie'
    },
    title: {
      text: ''
    },
    subtitle: {
      text: 'PORCENTAJE DE DISTRIBUCIÓN DE PRESUPUESTO, GESTIÓN '+gestion
    },

    accessibility: {
      announceNewData: {
        enabled: true
      },
      point: {
        valueSuffix: '%'
      }
    },

    plotOptions: {
      series: {
        dataLabels: {
          enabled: true,
          format: '{point.name}: {point.y:.1f}%'
        }
      }
    },

    tooltip: {
      headerFormat: '<span style="font-size:16px">{series.name}</span><br>',
      pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> asignado<br/>'
    },

    series: [
      {
        name: "DEPARTAMENTO",
        colorByPoint: true,
        data: detalle
      }
    ]
    
  });
}







/////====================================================
 /// ------ Ejecutar Presupuesto (FORMULARIO DE EJECUCION) POR REGIONAL
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

        document.getElementById("proy_nombre").value = response.proyecto[0]['proy_sisin']+' - '+response.proyecto[0]['proy_nombre']; /// nombre proyecto
        document.getElementById("ppto_total").value = response.proyecto[0]['proy_ppto_total']; /// ppto total
        document.getElementById("fase").value = response.fase[0]['fase']+' - '+response.fase[0]['descripcion']; /// fase
        document.getElementById("estado").innerHTML = response.estado; /// estado
        document.getElementById("ejec_fis").value = response.proyecto[0]['avance_fisico']; /// Ejecucion Fisica Total
        document.getElementById("ejec_fin").value = response.proyecto[0]['avance_financiero']; /// Ejecucion Financiero Total
        //document.getElementById("ejec_fin_gestion").value = response.avance_financiero; /// Ejecucion Financiero Gestion
        document.getElementById("observacion").value = response.proyecto[0]['proy_observacion']; /// Observacion
        document.getElementById("problema").value = response.proyecto[0]['desc_prob']; /// problema
        document.getElementById("solucion").value = response.proyecto[0]['desc_sol']; /// solucion
        
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
              },
              problema: { //// problema
                  required: true,
                  minlength : 10,
              },
              solucion: { //// solucion
                  required: true,
                  minlength : 10,
              }
            },
            messages: {
              proy_id: "<font color=red>PROYECTO/font>",
              est_proy: "<font color=red>ESTADO DEL PROYECTO</font>", 
              ejec_fis: "<font color=red>AVANCE FISICO DEL PROYECTO</font>",
              problema: "<font color=red>DESCRIPCION DEL PROBLEMA</font>",  
              solucion: "<font color=red>DESCRIPCION DE LA SOLUCION</font>",                
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
            cuadro_grafico_ppto_ejec_meses(response.ppto,'REGIONAL')
        }
        else{
            alertify.error("ERROR !!!");
        }

      });

    });
  });


//// grafico REGRESION ppto ejecjutado por meses PROYECTO DE INVERSION
function cuadro_grafico_ppto_ejec_meses(matriz,titulo){
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
    text: 'Detalle de Ejecución Mensual a nivel '+titulo  // Titulo (Opcional)
  },
  subtitle: {
    text: ''   // Subtitulo (Opcional)
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
function cuadro_grafico_ppto_ejec_meses_acumulado(matriz,titulo){
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
    text: 'Detalle de Ejecución Mensual Acumulado a nivel '+titulo  // Titulo (Opcional)
  },
  subtitle: {
    text: ''   // Subtitulo (Opcional)
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
              cuadro_grafico_partidas(response.matriz,response.nro,'REGIONAL');/// detalle partidas
              cuadro_grafico_ppto_ejec_meses(response.vector_meses,'REGIONAL');  //// mensual
              cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'REGIONAL') /// meses Acumulado
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
  function cuadro_grafico_partidas(matriz,nro,titulo){
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
          text: 'CONSOLIDADO POR PARTIDAS A NIVEL '+titulo
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


//////// IMPRIMIR GRAFICOS
  //// Partidas
  function imprimir_partida() {
    var cabecera = document.querySelector("#cabecera");
    var grafico = document.querySelector("#graf_partida");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_partida").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_partida");
    imprimirPartida(grafico,cabecera,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_partida").style.display = 'none';
  }

  function imprimirPartida(grafico,cabecera,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(tabla.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.onload = function() {
      ventana.print();
      ventana.close();
    };
    return true;
  }

  //// Ejecucion Mensual
  function imprimir_ejecucion_mensual() {
    var cabecera = document.querySelector("#cabecera");
    var grafico1 = document.querySelector("#graf_ppto_mensual");
    var grafico2 = document.querySelector("#graf_ppto_mensual_acumulado");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_ejecucion_mensual").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_ejecucion_mensual");
    imprimirEjecucionMensual(grafico1,grafico2,cabecera,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_ejecucion_mensual").style.display = 'none';
  }

  function imprimirEjecucionMensual(grafico1,grafico2,cabecera,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico1.innerHTML);
  //  ventana.document.write('<hr>');
    ventana.document.write(grafico2.innerHTML);
   // ventana.document.write('<hr>');
   // ventana.document.write(tabla.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.onload = function() {
      ventana.print();
      ventana.close();
    };
    return true;
  }


  //// Distribucion Nro de Proyectos y presupuesto
  function imprimir_distribucion_proyectos() {
    var cabecera = document.querySelector("#cabecera");
    var grafico = document.querySelector("#graf_detalle_nro_proyectos");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_detalle1").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle1");
    imprimirPorcentajeDistribucion(grafico,cabecera,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle1").style.display = 'none';
  }

    //// Distribucion Nro de Proyectos y presupuesto
  function imprimir_distribucion_ppto() {
    var cabecera = document.querySelector("#cabecera");
    var grafico = document.querySelector("#graf_detalle_nro_ppto");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_detalle2").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle2");
    imprimirPorcentajeDistribucion(grafico,cabecera,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle2").style.display = 'none';
  }

  function imprimirPorcentajeDistribucion(grafico,cabecera,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(tabla.innerHTML);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.onload = function() {
      ventana.print();
      ventana.close();
    };
    return true;
  }