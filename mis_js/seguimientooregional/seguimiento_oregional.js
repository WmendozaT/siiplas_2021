  base = $('[name="base"]').val();

  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "EVALUACION FORM. N° 2" , "width=800,height=700,scrollbars=NO") ; 
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

///// =================== MODULO DE REPORTES POA - EVALUACION DE FORMULARIO 2
////------- menu select regionales
  $("#d_id").change(function () {
    $("#d_id option:selected").each(function () {
      dep_id=$(this).val();

      if(dep_id!=''){
        if(dep_id==0){ /// Institucional
            $('#lista_consolidado').fadeIn(1000).html('Cargando Informacion ....');
            var url = base+"index.php/reporte_evalform2/crep_evalform2/get_cuadro_evaluacion_formulario2_institucional";
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
                cuadro_grafico_cumplimiento_operaciones_institucional(response.matriz,response.nro,response.titulo);
                cuadro_grafico_cumplimiento_operaciones_regresion_intitucional(response.matriz_regresion,response.titulo); 

                cuadro_grafico_cumplimiento_form2_detalle_institucional('grafico_trimestre','CUMPLIMIENTO DE OPERACIONES AL '+response.trimestre[0]['trm_descripcion']+'<br>INSTITUCIONAL','#66efdc',response.matriz_form2_trimestre,response.nro_form2_trimestre,response.gestion) /// al trimestre
                cuadro_grafico_cumplimiento_form2_detalle_institucional('grafico3','CUMPLIMIENTO DE OPERACIONES - GESTIÓN '+response.gestion+'<br>INSTITUCIONAL','#296860',response.matriz_form2,response.nro_form2,response.gestion) /// Acumulado a la Gestion
              }
              else{
                alertify.error("ERROR AL LISTAR");
              }
            }); 
        }
        else{ /// Regional

          var url = base+"index.php/reporte_evalform2/crep_evalform2/get_cuadro_evaluacion_formulario2_regional";
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
                cuadro_grafico_cumplimiento_operaciones_regional('grafico_trimestral',3,'CUMPLIMIENTO DE OPERACIONES AL '+response.trimestre[0]['trm_descripcion'],'#66efdc',response.matriz,response.nro,response.regional);                                                                                             
                cuadro_grafico_cumplimiento_operaciones_regional('grafico1',4,'CUMPLIMIENTO ACUMULADO DE OPERACIONES - GESTIÓN '+response.gestion,'#296860',response.matriz,response.nro,response.regional);
              }
              else{
                alertify.error("ERROR AL LISTAR");
              }
            }); 

        }

      }
      else{
        $('#lista_consolidado').fadeIn(1000).html('<div class="well"><div class="jumbotron"><h1>Evaluaci&oacute;n OPERACIONES '+gestion+'</h1></div></div>');
      }
    });
  });








//// grafico nivel de cumplimiento de operaciones Institucional
function cuadro_grafico_cumplimiento_operaciones_institucional(matriz,nro,titulo){
  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= { name: matriz[i][2],y: matriz[i][6]};
  }

   Highcharts.chart('grafico1', {
    chart: {
      type: 'column'
    },
    title: {
      text: ''
    },
    subtitle: {
      text: '(%) de Cumplimiento de Operaciones (Formulario N° 2)<br><b>'+titulo+'</b>'
    },
    accessibility: {
      announceNewData: {
        enabled: true
      }
    },
    xAxis: {
      type: 'category'
    },
    yAxis: {
      title: {
        text: '(%) de Cumplimiento de Operaciones a Nivel de Regionales'
      }

    },
    legend: {
      enabled: false
    },
    plotOptions: {
      series: {
        borderWidth: 0,
        dataLabels: {
          enabled: true,
          format: '{point.y:.1f}%'
        }
      }
    },

    tooltip: {
      headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
      pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> Cumplimiento<br/>'
    },

    series: [
      {
        name: "Operaciones",
        colorByPoint: true,
        data: detalle
      }
    ]
  });

}

//// grafico nivel de cumplimiento de operaciones Institucional
function cuadro_grafico_cumplimiento_operaciones_regresion_intitucional(matriz,titulo){
    let detalle1=[];
    for (var i = 0; i <= 4; i++) {
        detalle1[i]= matriz[5][i];
    }

    let detalle2=[];
    for (var i = 0; i <= 4; i++) {
        detalle2[i]=  matriz[6][i];
    }
  /// REGRESION LINEAL ACUMULADO
  chart = new Highcharts.Chart({
    chart: {
      renderTo: 'grafico2',  // Le doy el nombre a la gráfica
      defaultSeriesType: 'line' // Pongo que tipo de gráfica es
    },
    title: {
      text: '' // Titulo (Opcional)
    },
    subtitle: {
      text: '% CUMPLIMIENTO DE OPERACIONES PRIORIZADOS<br><b>'+titulo+'</b>'    // Subtitulo (Opcional)
    },
    // Pongo los datos en el eje de las 'X'
    xAxis: {
      categories: ['','I Trimestre','II Trimestre','III Trimestre','IV Trimestre'],
      // Pongo el título para el eje de las 'X'
      title: {
        text: '% Operaciones Acumulados por Trimestre'
      }
    },
    yAxis: {
      // Pongo el título para el eje de las 'Y'
      title: {
        text: '% Cumplimiento'
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
    series: [{
        name: '(%) Programado',
        data: detalle1
      },
      {
        name: '(%) Cumplimiento',
        data: detalle2
      }],
    });
  }


//// grafico nivel de cumplimiento de operaciones Regional
function cuadro_grafico_cumplimiento_operaciones_regional(titulo_grafico,j,titulo_texto,graf_color,matriz,nro,regional){
  let categoria=[];
  for (var i = 0; i < nro; i++) {
      categoria[i]= 'OPE. '+matriz[i][0]+'.'+matriz[i][1];
  }

  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= matriz[i][j];
  }

  Highcharts.chart(titulo_grafico, {
    chart: {
        type: 'bar'
    },
    title: {
        text: titulo_texto
    },
    subtitle: {
        text: '<b>'+regional+'</b>'
    },
    xAxis: {
        categories: categoria,
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
            overflow: 'Operaciones'
        }
    },
    tooltip: {
        valueSuffix: ' %'
    },

    plotOptions: {
      series: {
        borderWidth: 0,
        dataLabels: {
          enabled: true,
          format: '{point.y:.1f}%'
        }
      },
      column: {
          borderRadius: '55%'
      }
    },

    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -40,
      y: 80,
      floating: true,
      borderWidth: 1,
      shadow: true
    },

    credits: {
      enabled: false
    },

    series: [{
      color: graf_color,
      name: '(%) CUMPLIMIENTO',
      data: detalle
    }]
  });
}


//// grafico nivel de cumplimiento de operaciones form2 detalle (Institucional)
function cuadro_grafico_cumplimiento_form2_detalle_institucional(grafico,titulo,graf_color,matriz,nro,gestion){
  let categoria=[];
  for (var i = 0; i < nro; i++) {
      categoria[i]= 'OPE. '+matriz[i][0]+'.'+matriz[i][1];
  }

  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= matriz[i][4];
  }

 Highcharts.chart(grafico, {
    chart: {
        type: 'bar'
    },
    title: {
        text: ''
    },
    subtitle: {
        text: titulo
    },
    xAxis: {
        categories: categoria,
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
            overflow: 'Operaciones'
        }
    },
    tooltip: {
        valueSuffix: ' %'
    },
    plotOptions: {
      series: {
        borderWidth: 0,
        dataLabels: {
          enabled: true,
          format: '{point.y:.1f}%'
        }
      },
      column: {
          borderRadius: '55%'
      }
    },

    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -40,
      y: 80,
      floating: true,
      borderWidth: 1,
      shadow: true
    },

    credits: {
        enabled: false
    },

    series: [{
        color: graf_color,
        name: '(%) CUMPLIMIENTO',
        data: detalle
    }]
  });
}

  /// ========== FUNCION PARA IMPRIMIR
  //// imprimir grafico1 (Institucional)
  function imprimir_grafico1() {
    var grafico = document.querySelector("#grafico1");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    var eficacia = document.querySelector("#calificacion");
    document.getElementById("tabla_impresion_detalle1").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle1");
    imprimirevaluacionform2(grafico,cabecera,eficacia,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle1").style.display = 'none';
  }

  //// imprimir grafico2 (Institucional)
  function imprimir_grafico2() {
    var grafico = document.querySelector("#grafico2");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    var eficacia = document.querySelector("#calificacion");
    document.getElementById("tabla_impresion_detalle2").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle2");
    imprimirevaluacionform2(grafico,cabecera,eficacia,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle2").style.display = 'none';
  }

  //// imprimir grafico3 Detalle form 2 (Institucional)
  function imprimir_grafico3() {
    var grafico = document.querySelector("#grafico3");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
 
    document.getElementById("tabla_impresion_detalle3").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle3");
    imprimirevaluacionform3(grafico,cabecera,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle3").style.display = 'none';
  }

  function imprimirevaluacionform2(grafico,cabecera,eficacia,tabla) {

    var ventana = window.open('Evaluacion FORMULARIO N° 2 ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EVALUACION OPERACIONES - FORM. N° 2</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(eficacia.innerHTML);
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

    function imprimirevaluacionform3(grafico,cabecera,tabla) {

    var ventana = window.open('Evaluacion FORMULARIO N° 2 ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EVALUACION OPERACIONES - FORM. N° 2</title>');
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


  /// grado de cumplimiento por Objetivo Regional x ACP REGIONAL
  function nivel_cumplimiento_acp_regional(og_id,dep_id) {
  //  $('#titulo_grafico').html('<font size=3><b>Cargando ..</b></font>');
   // $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
  //alert(og_id+'--'+dep_id)
    var url = base+"index.php/reporte_evalform2/crep_evalform2/ver_datos_avance_oregional_acp";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "og_id="+og_id+"&dep_id="+dep_id
    });

    request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {

        $('#tabla').html(response.tabla);

        //// Trimestre
        let detalle_trimestre=[];
        for (var i = 0; i < response.nro; i++) {
            detalle_trimestre[i]= { name: response.matriz[i][0]+'.'+response.matriz[i][1]+' '+response.matriz[i][2],y: response.matriz[i][4]};
        }
        grafico='container1';
        titulo='(%) CUMPLIMIENTO DE OPERACIONES <b>'+response.regional+ '</b> - '+response.trimestre[0]['trm_descripcion']+' /'+response.gestion;
        cuadro_grafico_cumplimiento_operaciones_x_acp_regional(grafico,detalle_trimestre,titulo,response.acp_regional)


        //// Gestion
        let detalle_gestion=[];
        for (var i = 0; i < response.nro; i++) {
            detalle_gestion[i]= { name: response.matriz[i][0]+'.'+response.matriz[i][1]+' '+response.matriz[i][2],y: response.matriz[i][5]};
        }
        grafico='container2';
        titulo='(%) CUMPLIMIENTO DE OPERACIONES <b>'+response.regional+ '</b> GESTIÓN - '+response.gestion;
        cuadro_grafico_cumplimiento_operaciones_x_acp_regional(grafico,detalle_gestion,titulo,response.acp_regional)

      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }


//// grafico nivel de cumplimiento de operaciones por ACP
function cuadro_grafico_cumplimiento_operaciones_x_acp_regional(grafico,detalle_trimestre,titulo,acp_regional){
      /*let detalle=[];
      for (var i = 0; i < nro; i++) {
          detalle[i]= { name: matriz[i][0]+'.'+matriz[i][1]+' '+matriz[i][2],y: matriz[i][4]};
      }*/

    Highcharts.chart(grafico, {
      chart: {
        type: 'column'
      },
      title: {
        align: 'center',
        text: titulo
      },
      subtitle: {
        align: 'center',
        text: '<b>ACP. '+acp_regional[0]['og_codigo']+'.'+acp_regional[0]['og_objetivo']+'</b>'
      },
      accessibility: {
        announceNewData: {
          enabled: true
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        title: {
          text: '(%) Cumplimiento de Operaciones al Trimestre'
        }

      },
      legend: {
        enabled: false
      },
      plotOptions: {
        series: {
          borderWidth: 0,
          dataLabels: {
            enabled: true,
            format: '{point.y:.1f}%'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
      },

      series: [
        {
          name: "OPERACIÓN",
          colorByPoint: true,
          data: detalle_trimestre
        }
      ]

    });
}


///// ========================================================================









/*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL (EJECUCION)------*/
$(function() {
  $(".update_evaluacion").on("click", function(e) {
    document.getElementById("load_update_temp_general").style.display = 'block';
    e.preventDefault();
    var url = base+"index.php/ejecucion/cevaluacion_form2/update_evaluacion_oregional";
    $.ajax({
      type: "POST",
      url: url,
    //  data: $("#form").serialize(),
      success: function(data) {
       if(data.trim()){
          window.location.reload(true);
          alertify.success("ACTUALIZACIÓN EXITOSA ...");
       }
       else{
        alertify.error("ERROR AL ACTUALIZAR DATOS DE EVALUACIÓN POA !!!");
       }

      }
    });
    return false;

  });


  //// LISTA DE OPERACIONES POR REGIONAL 2022
  $("#dep_id").change(function () {
    $("#dep_id option:selected").each(function () {
        dep_id=$(this).val();
        $('#load_update').html('');
        if(dep_id!=0){
          $('#titulo_lista').html('<font size=3><b>Cargando Informacion ..... </b></font>');
            var url = base+"index.php/ejecucion/cevaluacion_form2/get_lista_operaciones_x_regionales";
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
                $('#detalles').slideDown();
                $('#update').slideDown();
                $('#btn_update').html(response.btn_update);
            }
            else{
                alertify.error("ERROR AL RECUPERAR INFORMACION");
            }

          });
        }
        else{
          $('#titulo_lista').html('');
          $('#detalles').slideUp();
        }
    });
  });

});


 


  /// Parametros de cumplimiento
  function parametros_cumplimiento(valor) {
    resp='';
    if(valor>0 & valor<=50){
      resp='<b>INSATISFACTORIO</b>';
    }
    if(valor>50 & valor<=75){
     resp='<b>REGULAR</b>'; 
    }
    if(valor>75 & valor<=99){
     resp='<b>BUENO</b>'; 
    }
    if(valor==100){
     resp='<b>OPTIMO</b>'; 
    }

    return resp;
  }


  /// Lista de Actividades Priorizados por cada Objetivo Regional FORM 2
  function update_temp(dep_id) {
      $('#load_update').fadeIn(1000).html('<font size=4><b>Actualizando Temporalidad de Operaciones .....</b></font>');
      var url = base+"index.php/ejecucion/cevaluacion_form2/update_temporalidad_oregional";
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
          $('#load_update').fadeIn(1000).html('<font color=green size=5><b>Temporalidad Actualizado !!! .....</b></font>');
          $('#titulo_lista').html(response.tabla);
      }
      else{
          alertify.error("ERROR AL RECUPERAR DATOS");
      }

      });
      request.fail(function (jqXHR, textStatus, thrown) {
          console.log("ERROR: " + textStatus);
      });
      request.always(function () {
      });
  }

  /// Lista de Actividades Priorizados por cada Objetivo Regional FORM 2
  function ver_actividades_priorizados(or_id,dep_id) {
    $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
    $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
    $('#imprimir_act_priori').html('');
  //  alert(dep_id)
    var url = base+"index.php/ejecucion/cevaluacion_form2/ver_actividades_priorizados";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "or_id="+or_id+"&dep_id="+dep_id
    });

    request.done(function (response, textStatus, jqXHR) {

    if (response.respuesta == 'correcto') {
        $('#titulo').html(response.titulo);
        $('#content1').fadeIn(1000).html(response.tabla);
        $('#imprimir_act_priori').fadeIn(1000).html(response.imprimir_act_priori);
    }
    else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
    }

    });
  }

  /// grado de cumplimiento por Objetivo Regional FORM 2
  function nivel_cumplimiento(or_id,dep_id) {
    $('#titulo_grafico').html('<font size=3><b>Cargando ..</b></font>');
    $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Ediciones </div>');
  //  alert(dep_id)
    var url = base+"index.php/ejecucion/cevaluacion_form2/ver_datos_avance_oregional";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "or_id="+or_id+"&dep_id="+dep_id
    });

    request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {

        $('#titulo_grafico').html(response.titulo);
        $('#tab').html(response.tab);
        $('#tab_acumulado').html(response.tab_acu);

        ////////// TRIMESTRAL
        chart = new Highcharts.Chart({
        chart: {
          renderTo: 'parametro_efi',  // Le doy el nombre a la gráfica
          defaultSeriesType: 'line' // Pongo que tipo de gráfica es
        },
        title: {
          text: 'EJECUCIÓN DE OPERACIONES POR TRIMESTRE'  // Titulo (Opcional)
        },
        subtitle: {
          text: ''   // Subtitulo (Opcional)
        },
        // Pongo los datos en el eje de las 'X'
        xAxis: {
          categories: ['','I Trimestre','II Trimestre','III Trimestre','IV Trimestre'],
          // Pongo el título para el eje de las 'X'
          title: {
            text: 'N° Operaciones por Trimestre'
          }
        },
        yAxis: {
          // Pongo el título para el eje de las 'Y'
          title: {
            text: 'N° operaciones'
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
              name: 'Meta Programado',
              data: [0,response.matriz_acumulado[1][1],response.matriz_acumulado[1][2],response.matriz_acumulado[1][3],response.matriz_acumulado[1][4]]
            },
            {
              name: 'Meta Ejecutado',
              data: [0,response.matriz_acumulado[2][1],response.matriz_acumulado[2][2],response.matriz_acumulado[2][3],response.matriz_acumulado[2][4]]
            },
            {
              name: '% Cumplimiento',
              data: [0,response.matriz_acumulado[3][1],response.matriz_acumulado[3][2],response.matriz_acumulado[3][3],response.matriz_acumulado[3][4]]
            }
          ],
          
        });

        /////////// ACUMULADO


        chart = new Highcharts.Chart({
        chart: {
          renderTo: 'parametro_efi2',  // Le doy el nombre a la gráfica
          defaultSeriesType: 'line' // Pongo que tipo de gráfica es
        },
        title: {
          text: '% EJECUCIÓN ACUMULADO TRIMESTRAL'  // Titulo (Opcional)
        },
        subtitle: {
          text: ''   // Subtitulo (Opcional)
        },
        // Pongo los datos en el eje de las 'X'
        xAxis: {
          categories: ['','I Trimestre','II Trimestre','III Trimestre','IV Trimestre'],
          // Pongo el título para el eje de las 'X'
          title: {
            text: '% Operaciones Acumulados por Trimestre'
          }
        },
        yAxis: {
          // Pongo el título para el eje de las 'Y'
          title: {
            text: '% Ejecucion'
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
        series: [{
            name: '(%) Programado',
            data: [0,response.matriz_acumulado[5][1],response.matriz_acumulado[5][2],response.matriz_acumulado[5][3],response.matriz_acumulado[5][4]]
          },
          {
            name: '(%) Ejecutado',
            data: [0,response.matriz_acumulado[6][1],response.matriz_acumulado[6][2],response.matriz_acumulado[6][3],response.matriz_acumulado[6][4]]
          }],
        });

      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }


  /// GRADO DE CUMPLIMIENTO DE OPERACIONES CONSOLIDADO POR REGIONAL (GRAFICO) FORM 2
  function nivel_cumplimiento_operaciones_grafico(dep_id,trm_id) {
    var url = base+"index.php/ejecucion/cevaluacion_form2/get_cumplimiento_operaciones_grafico";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "dep_id="+dep_id+"&trm_id="+trm_id
    });

    request.done(function (response, textStatus, jqXHR) {
      if (response.respuesta == 'correcto') {
        $('#titulo_graf').html(response.titulo_graf);
        $('#tabla').html(response.tabla);
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }


  /////// ================== REPORTE DE FORMULARIO 2 





  