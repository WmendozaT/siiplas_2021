  base = $('[name="base"]').val();
  gestion = $('[name="gestion"]').val();

  function abreVentana(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "REPORTE POA" , "width=800,height=700,scrollbars=NO") ; 
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


  //// Select Regional
  $("#dep_id").change(function () {
    $("#dep_id option:selected").each(function () {
      elegido=$(this).val();
      if(elegido==0){
         window.location.reload(true);
      }
      else{
          $.post(base+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'distrital' }, function(data){
            $("#dist_id").html(data);
            $("#tp_id").html('');
            $("#rep_id").html('');
            $('#unidad').slideUp();
            $("#lista_consolidado").html('<div class="jumbotron"><h1>Consolidado Programación POA '+gestion+'</h1><p>Reporte consolidado de Programación POA a nivel Regional y Distrital.</p><ol style="font-size:16px;"><li>Genera Reportes POA Formulario N° 4 y 5, Notificación POA Mensual por Unidad.</li><li>Genera Reporte Consolidado de Actividades por Regional y Distrital.</li><li>Genera Reporte Consolidado de Requerimientos por Regional y Distrital.</li><li>Genera Reporte de Ejecución Presupuestaria por Unidad Organizacional.</li><li>Genera el nro. de Actividades alineados a cada Acción Regional por Regional y Distrital.</li><li>Genera el nro. de Actividades alineados por cada Programa por Regional y Distrital.</li><li>Genera Reporte de nro. de Modificaciones POA realizados mensualmente por Regional y Distrital.</li><li>Genera Reporte de nro. de Certificaciones POA realizados mensualmente por Regional y Distrital.</li></ol></div>');
        });
      }
    });
  });

  //// Select distrital
  $("#dist_id").change(function () {
      $("#dist_id option:selected").each(function () {
          elegido=$(this).val();
          $.post(base+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'rep' }, function(data){
              $("#rep_id").html(data);
              $("#tp_id").html('');
              $('#unidad').slideUp();
              $("#lista_consolidado").html('<div class="jumbotron"><h1>Consolidado Programación POA '+gestion+'</h1><p>Reporte consolidado de Programación POA a nivel Regional y Distrital.</p><ol style="font-size:16px;"><li>Genera Reportes POA Formulario N° 4 y 5, Notificación POA Mensual por Unidad.</li><li>Genera Reporte Consolidado de Actividades por Regional y Distrital.</li><li>Genera Reporte Consolidado de Requerimientos por Regional y Distrital.</li><li>Genera Reporte de Ejecución Presupuestaria por Unidad Organizacional.</li><li>Genera el nro. de Actividades alineados a cada Acción Regional por Regional y Distrital.</li><li>Genera el nro. de Actividades alineados por cada Programa por Regional y Distrital.</li><li>Genera Reporte de nro. de Modificaciones POA realizados mensualmente por Regional y Distrital.</li><li>Genera Reporte de nro. de Certificaciones POA realizados mensualmente por Regional y Distrital.</li></ol></div>');
          });
      });
  });

  //// select tipo de reporte
  $("#rep_id").change(function () {
      $("#rep_id option:selected").each(function () {
          elegido=$(this).val();

          $.post(base+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'tipo' }, function(data){
              $("#tp_id").html(data);
              $('#unidad').slideUp();
           //   $("#lista_consolidado").html('');
          });
      });
  });


  //// Select tipo de reporte
  $("#tp_id").change(function () {
      $("#tp_id option:selected").each(function () {
          dep_id=$('[name="dep_id"]').val();
          dist_id=$('[name="dist_id"]').val();
          rep_id=$('[name="rep_id"]').val();
          tp_id=$(this).val();

          //alert(dep_id+'-'+dist_id+'-'+rep_id+'-'+tp_id)
          if(rep_id!=4){
            //  alert("dep_id="+dep_id+"dist_id="+dist_id+" &tp_id="+tp_id+" &tp_rep="+rep_id)
              $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Reporte Consolidado POA ...</div>');
              var url = base+"index.php/reportes_cns/rep_operaciones/get_lista_reportepoa";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "dep_id="+dep_id+"&dist_id="+dist_id+"&tp_id="+tp_id+"&tp_rep="+rep_id
              });

              request.done(function (response, textStatus, jqXHR) {
                  if (response.respuesta == 'correcto') {
                      $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);
                  }
                  else{
                      alertify.error("ERROR AL LISTAR");
                  }
              }); 
          }
          else{
              $('#unidad').slideDown();
              $("#lista_consolidado").html('');
              var url = base+"index.php/reportes_cns/rep_operaciones/get_unidades";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "dep_id="+dep_id+"&dist_id="+dist_id+"&rep_id="+rep_id+"&tp_id="+tp_id
              });

              request.done(function (response, textStatus, jqXHR) {
                  if (response.respuesta == 'correcto') {
                      $('#proy_id').fadeIn(1000).html(response.lista_actividad); 
                  }
                  else{
                      alertify.error("ERROR AL LISTAR");
                  }
              }); 
          }
           
      });
  });
     

  //// Seleccion de Unidad / Establecimiento / Proyecto de Inversion
  $("#proy_id").change(function () {
    $("#proy_id option:selected").each(function () {
        elegido=$(this).val();
        $.post(base+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'subactividades' }, function(data){
            $("#sub_act").html(data);
            $("#lista_consolidado").html('');
        });
    });
  });

  /// Seleccion de Unidad Responsable 
  $("#sub_act").change(function () {
    $("#sub_act option:selected").each(function () {
      com_id=$(this).val();
     // alert(com_id);
      $('#lista_consolidado').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Lista de Requerimientos Programados / Certificados ...</div>');
      var url = base+"index.php/reportes_cns/rep_operaciones/get_subactividad";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "com_id="+com_id
      });

      request.done(function (response, textStatus, jqXHR) {
          if (response.respuesta == 'correcto') {
              $('#lista_consolidado').fadeIn(1000).html(response.lista_requerimientos_certificados);
          }
          else{
              alertify.error("ERROR AL LISTAR");
          }
      }); 

    });
  });

/////// FORMULARIOS
/// ver reportes POA FORMULARIO 4 Y 5
  function ver_poa(proy_id) {
    $('#titulo').html('<font size=3><b>Cargando ..</b></font>');
    $('#content1').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion</div>');
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
        $('#titulo').html('<font size=3><b>'+response.titulo_poa+'</b></font>');
        $('#content1').fadeIn(1000).html(response.tabla);
      }
      else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }

  /// ver reportes MODIFICACION POA FORMULARIO 4 Y 5
  function ver_mpoa(proy_id) {
   // alert(proy_id)
    $('#titulo_mod').html('<font size=3><b>Cargando ..</b></font>');
    $('#content_mod').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion</div>');
    var url = base+"index.php/consultas_cns/c_consultas/get_mpoa";
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
        $('#titulo_mod').html('<font size=3><b>'+response.titulo_poa+'</b></font>');
        $('#content_mod').fadeIn(1000).html(response.tabla);
      }
      else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }

    /// ver reportes CERTIFICACION POA FORMULARIO 5
  function ver_certpoa(proy_id) {
   // alert(proy_id)
    $('#titulo_certpoa').html('<font size=3><b>Cargando ..</b></font>');
    $('#content_certpoa').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion</div>');
    var url = base+"index.php/consultas_cns/c_consultas/get_certpoa";
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
        $('#titulo_certpoa').html('<font size=3><b>'+response.titulo_poa+'</b></font>');
        $('#content_certpoa').fadeIn(1000).html(response.tabla);
      }
      else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }


  /// ver reportes EVALUACION POA
  function ver_evaluacionpoa(proy_id) {
   // alert(proy_id)
    $('#titulo_evalpoa').html('<font size=3><b>Cargando ..</b></font>');
    $('#content_evalpoa').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Informacion</div>');
    var url = base+"index.php/consultas_cns/c_consultas/get_evalpoa";
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
        $('#titulo_evalpoa').html('<font size=3><b>'+response.titulo_poa+'</b></font>');
        $('#content_evalpoa').fadeIn(1000).html(response.tabla);


        /////////// ACUMULADO

        chart = new Highcharts.Chart({
        chart: {
          renderTo: 'parametro_efi',  // Le doy el nombre a la gráfica
          defaultSeriesType: 'line' // Pongo que tipo de gráfica es
        },
        title: {
          text: 'CUADRO DE CUMPLIMIENTO POA'  // Titulo (Opcional)
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
        series: [
            {
              name: 'Nro. ACT. Programado',
              data: [0,response.evaluacion[2][1],response.evaluacion[2][2],response.evaluacion[2][3],response.evaluacion[2][4]]
            },
            {
              name: 'Nro. ACT, Cumplidas',
              data: [0,response.evaluacion[3][1],response.evaluacion[3][2],response.evaluacion[3][3],response.evaluacion[3][4]]
            }
           /* ,
            {
            name: '(%) cumplimiento',
            data: [0,response.evaluacion[5][1],response.evaluacion[5][2],response.evaluacion[5][3],response.evaluacion[5][4]]
            },
            {
            name: '(%) no cumplido',
            data: [0,response.evaluacion[6][1],response.evaluacion[6][2],response.evaluacion[6][3],response.evaluacion[6][4]]
            }*/
            ],
        });
      }
      else{
        alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
  }