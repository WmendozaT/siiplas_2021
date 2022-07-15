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
        var url = base+"index.php/reporte_evalform2/crep_evalform2/get_cuadro_evaluacion_formulario2";
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
            cuadro_grafico_cumplimiento_operaciones(response.matriz,response.nro,dep_id);
            cuadro_grafico_cumplimiento_operaciones_regresion(response.matriz_regresion); 
          }
          else{
            alertify.error("ERROR AL LISTAR");
          }
        }); 
      }
      else{
        $('#lista_consolidado').fadeIn(1000).html('<div class="well"><div class="jumbotron"><h1>Evaluaci&oacute;n OPERACIONES '+gestion+'</h1></div></div>');
      }
    });
  });








//// grafico nivel de cumplimiento de operaciones Nacional / Regional
function cuadro_grafico_cumplimiento_operaciones(matriz,nro,dep_id){
  if(dep_id==0){
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
          text: '(%) de cumplimiento de Operaciones (Formulario N° 2)<br><b></b>'
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
  else{
     
  }

}

//// grafico nivel de cumplimiento de operaciones Nacional / Regional
function cuadro_grafico_cumplimiento_operaciones_regresion(matriz){
      let detalle1=[];
      for (var i = 0; i < 4; i++) {
          detalle1[i]= matriz[5][i];
      }

      let detalle2=[];
      for (var i = 0; i < 4; i++) {
          detalle2[i]=  matriz[6][i];
      }
    /// REGRESION LINEAL ACUMULADO
  chart = new Highcharts.Chart({
      chart: {
        renderTo: 'regresion',  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: '% CUMPLIMIENTO DE OPERACIONES PRIORIZADOS</b>'  // Titulo (Opcional)
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
          //data: [0,<?php echo $matriz_form2_regresion[5][1];?>,<?php echo $matriz_form2_regresion[5][2];?>,<?php echo $matriz_form2_regresion[5][3];?>,<?php echo $matriz_form2_regresion[5][4];?>]
        },
        {
          name: '(%) Cumplimiento',
          data: detalle2
          //data: [0,<?php echo $matriz_form2_regresion[6][1];?>,<?php echo $matriz_form2_regresion[6][2];?>,<?php echo $matriz_form2_regresion[6][3];?>,<?php echo $matriz_form2_regresion[6][4];?>]
        }],
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





  