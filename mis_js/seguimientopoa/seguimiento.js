base = $('[name="base"]').val();

function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "SEGUIMIENTO POA" , "width=800,height=700,scrollbars=NO") ; 
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

  //// funcion para generar el cuadro de seguimiento POa Mensual (cuadro, grafico)
  function generar_cuadro_seguimiento_evalpoa(com_id,mes,trimestre){
   // alert('hola mundo')
    $('#btn_generarr').html('');
    $('#loading_evalpoa').html('<center><img src="'+base+'/assets/img_v1.1/loading.gif" style="width:350px; height:350px;" alt="loading" /></center>');
    
    var url = base+"index.php/ejecucion/cseguimiento/get_cuadro_seguimientopoa";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      data: "com_id="+com_id+"&mes_id="+mes+"&trm_id="+trimestre
    });

    request.done(function (response, textStatus, jqXHR) {
        
      if (response.respuesta == 'correcto') {
        document.getElementById('calificacion').innerHTML = response.calificacion;

        //------ Evaluacion POA
        document.getElementById('loading_evalpoa').innerHTML = '';
        document.getElementById("cuerpo_evalpoa").style.display = 'block';
        document.getElementById('tabla_regresion_impresion').innerHTML = response.tabla_regresion_impresion;
        graf_regresion_trimestral(response.matriz_regresion);

        document.getElementById('tabla_pastel_vista').innerHTML = response.tabla_pastel_todo;
        graf_regresion_pastel(response.matriz_regresion,trimestre);

        document.getElementById('tabla_regresion_total_impresion').innerHTML = response.tabla_regresion_total_impresion;
        graf_regresion_anual(response.matriz_gestion);
        // ---- end


        /// ---- lista de form completa
        document.getElementById('list_form4_temporalidad').innerHTML = response.form4_temporalidad;
        ///------ end
        //alert(response.calificacion)
      }
      else{
          alertify.error("ERROR !!!");
      }
    }); 
  }



  //// funcion para generar el cuadro de Evaluacion POa (Seguimiento por las unidades)
  function generar_cuadro_seguimiento_evalpoa_unidad(com_id,mes,trimestre){
   // alert(com_id+'--'+mes+'--'+trimestre)
    $('#btn_generar').html('');
    $('#loading_evalpoa').html('<center><img src="'+base+'/assets/img_v1.1/loading.gif" style="width:350px; height:350px;" alt="loading" /></center>');

    var url = base+"index.php/ejecucion/cseguimiento/get_cuadro_seguimientopoa";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      data: "com_id="+com_id+"&mes_id="+mes+"&trm_id="+trimestre
    });

    request.done(function (response, textStatus, jqXHR) {
        
      if (response.respuesta == 'correcto') {
        document.getElementById('calificacion').innerHTML = response.calificacion;
        document.getElementById('loading_evalpoa').innerHTML = '';

        //------ Evaluacion POA
        document.getElementById("cuerpo_evalpoa").style.display = 'block';
        document.getElementById('tabla_regresion_impresion').innerHTML = response.tabla_regresion_impresion;
        graf_regresion_trimestral(response.matriz_regresion);

        document.getElementById('tabla_pastel_vista').innerHTML = response.tabla_pastel_todo;
        graf_regresion_pastel(response.matriz_regresion,trimestre);

        document.getElementById('tabla_regresion_total_impresion').innerHTML = response.tabla_regresion_total_impresion;
        graf_regresion_anual(response.matriz_gestion);
        
      }
      else{
          alertify.error("ERROR !!!");
      }
    }); 
  }

/// Boton de Impresion Cuadros de Evaluacion 2025
document.getElementById('btnImprimir_evaluacion_trimestre').addEventListener('click', function() {
  const plantillaPagina = (contenido, numeroPagina, calificacion) => `
    <div class="pagina">
      <header class="cabecera-pagina">
      <div class="membrete">
        
        <div class="datos-institucion">
          <h2>${document.getElementById('cabecera').outerHTML}</h2>
        </div>

      </div>
      <hr class="linea-separadora">
    <h1>${calificacion}</h1>
    </header>

      ${contenido}

      <footer class="pie-pagina">
        <div class="marcas-agua">
          <span class="pagina-numero">Página ${numeroPagina} de 3</span>
          <span class="fecha-generacion">${new Date().toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
          })}</span>
        </div>
      </footer>
    </div>
  `;

  const ventanaImpresa = window.open('', '_blank');
  ventanaImpresa.document.write(`
    <html>
      <head>
        <title>DEPARTAMENTO NACIONAL DE PLANIFICACION / SIIPLAS</title>
        <style>
          @page {
            size: A4 portrait;
            margin: 1cm 0.8cm;
            @top { content: element(cabecera-pagina); }
            @bottom { content: element(pie-pagina); }
          }
          #cabecera {
            display: block !important;
            margin: 0 0 12px 0;
            font-size: 10pt !important;
            color: #000000 !important;
            border: none !important;
          }

          /* Optimización tipográfica */
          .datos-institucion h2 {
            font-size: 15pt !important;
            margin: 5px 0 4px 0;
            color: #000000 !important;
          }
          .pagina {
            page-break-after: always;
            position: relative;
            height: calc(297mm - 5cm);
          }

          .cabecera-pagina {
            position: running(cabecera-pagina);
            margin-bottom: 1cm;
          }

          .pie-pagina {
            position: running(pie-pagina);
            height: 2cm;
          }

          .membrete {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 15px;
            align-items: center;
            margin-bottom: 10px;
          }

          .linea-separadora {
            border: 1px solid #11574e;
            margin: 8px 0;
          }

          .marcas-agua {
            display: flex;
            justify-content: space-between;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 3px;
          }

          .grafico-impresion {
            width: 100%!important;
            height: 380px!important;
            margin: 15px 0;
            page-break-inside: avoid;
          }
          }
        </style>
      </head>
      <body>
        ${plantillaPagina(`
          ${document.getElementById('regresion').outerHTML}
          ${document.getElementById('tabla_regresion_impresion').outerHTML}
        `, 1, `${document.getElementById('calificacion').outerHTML}`)}

        ${plantillaPagina(`
          ${document.getElementById('pastel_todos').outerHTML}
          ${document.getElementById('tabla_pastel_vista').outerHTML}
        `, 2, `${document.getElementById('calificacion').outerHTML}`)}

        ${plantillaPagina(`
          ${document.getElementById('regresion_gestion').outerHTML}
          ${document.getElementById('tabla_regresion_total_impresion').outerHTML}
        `, 3, '')}
      </body>
    </html>
  `);

  ventanaImpresa.document.close();
  setTimeout(() => {
    ventanaImpresa.print();
    ventanaImpresa.close();
  }, 1000);
});

  /// Grafico regresion por trimestre
  function graf_regresion_trimestral(matriz) {
  chart = new Highcharts.Chart({
    chart: {
      renderTo: 'regresion',
      type: 'line',
      backgroundColor: '#f0f0f0',
      spacing: [40, 20, 15, 45],
      style: {
        fontFamily: "'Segoe UI', Arial, sans-serif"
      }
    },
    title: {
      text: 'CUMPLIMIENTO ACUMULADO DE ACTIVIDADES AL TRIMESTRE',
      align: 'center',
      style: {
        color: '#1e293b',
        fontSize: '22px',
        fontWeight: 600
      },
      margin: 30
    },
    xAxis: {
      categories: ['','I Trimestres','II Trimestre','III Trimestre','IV Trimestre'],
      title: {
        text: 'Periodos de Evaluación',
        style: {
          color: '#475569',
          fontSize: '14px'
        }
      },
      gridLineWidth: 1,
      gridLineColor: '#f1f5f9',
      labels: {
        style: {
          color: '#64748b',
          fontWeight: 500
        }
      }
    },
    yAxis: {
      title: {
        text: 'Nro de Actividades',
        style: {
          color: '#475569',
          fontSize: '14px'
        }
      },
      labels: {
        style: {
          color: '#64748b'
        }
      },
      gridLineColor: '#f8fafc'
    },
    tooltip: {
      useHTML: true,
      backgroundColor: '#ffffff',
      borderWidth: 0,
      shadow: {
        color: 'rgba(0,0,0,0.08)',
        width: 3,
        offsetX: 2,
        offsetY: 2
      },
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true,
          style: {
            color: '#1e293b',
            fontSize: '12px',
            textOutline: 'none'
          },
          formatter: function() {
            return this.y + (this.y > 0 ? ' act.' : '');
          }
        },
        marker: {
          symbol: 'circle',
          radius: 6,
          fillColor: '#ffffff',
          lineWidth: 2
        },
        animation: {
          duration: 800
        }
      }
    },
    series: [{
      name: 'NRO ACT. PROGRAMADO AL TRIMESTRE',
      data: [0,matriz[2][1],matriz[2][2],matriz[2][3],matriz[2][4]],
      color: '#3b82f6',
      marker: {
        lineColor: '#3b82f6'
      },
      lineWidth: 3
    },{
      name: 'NRO ACT. CUMPLIDAS AL TRIMESTRE',
      data: [0,matriz[3][1],matriz[3][2],matriz[3][3],matriz[3][4]],
      color: '#10b981',
      marker: {
        lineColor: '#10b981'
      },
      lineWidth: 3
    }],
    legend: {
      align: 'right',
      verticalAlign: 'top',
      itemStyle: {
        color: '#475569',
        fontWeight: 500
      },
      itemMarginBottom: 15
    },
    credits: {
      enabled: false
    },
    responsive: {
      rules: [{
        condition: {
          maxWidth: 768
        },
        chartOptions: {
          title: {
            style: { fontSize: '18px' }
          },
          dataLabels: {
            style: { fontSize: '10px' }
          }
        }
      }]
    }
  });
}


    /// Grafico regresion Anual
    /*function graf_regresion_anual(matriz) {
      chart = new Highcharts.Chart({
      chart: {
        renderTo: 'regresion_gestion',  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: 'TITULO'  // Titulo (Opcional)
      },
      subtitle: {
        text: 'SEGUNDO'   // Subtitulo (Opcional)
      },
      // Pongo los datos en el eje de las 'X'
      xAxis: {
        categories: ['','I TRIMESTRE','II TRIMESTRE','III TRIMESTRE','IV TRIMESTRE'],
        // Pongo el título para el eje de las 'X'
        title: {
          text: '% CUMPLIMIENTO DE ACTIVIDADES'
        }
      },
      yAxis: {
        // Pongo el título para el eje de las 'Y'
        title: {
          text: ''
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
            name: '% ACT. PROGRAMADAS POR TRIMESTRE',
            data: [0,matriz[4][1],matriz[4][2],matriz[4][3],matriz[4][4]]
          },
          {
            name: '% ACT. CUMPLIDAS POR TRIMESTRE',
            data: [0,matriz[5][1],matriz[5][2],matriz[5][3],matriz[5][4]]
          }
        ],
        
      });
    }*/

function graf_regresion_anual(matriz) {
  chart = new Highcharts.Chart({
    chart: {
      renderTo: 'regresion_gestion',
      type: 'line',
      backgroundColor: '#f0f0f0',  // Fondo claro
      spacing: [35, 20, 15, 45]    // Margen inferior aumentado
    },
    title: {
      text: 'CUMPLIMIENTO AL POA - GESTION 2025',
      align: 'center',
      style: {
        color: '#1e293b',
        fontSize: '20px',
        fontWeight: 600,
        fontFamily: 'Segoe UI'
      }
    },
    subtitle: {
      text: '',
      align: 'center',
      style: {
        color: '#64748b',
        fontSize: '14px'
      }
    },
    xAxis: {
      categories: ['','I TRIMESTRE','II TRIMESTRE','III TRIMESTRE','IV TRIMESTRE'],
      title: {
        text: '% CUMPLIMIENTO DE ACTIVIDADES TRIMESTRAL RESPECTO A LA GESTION',
        style: {
          color: '#475569',
          fontSize: '13px'
        }
      },
      gridLineWidth: 1,
      gridLineColor: '#e2e8f0',
      labels: {
        style: {
          color: '#64748b',
          fontWeight: 500
        }
      }
    },
    yAxis: {
      title: {
        text: '(%) de cumplimiento a alcanzar',  // Nuevo título eje Y
        style: {
          color: '#475569',
          fontSize: '13px'
        }
      },
      labels: {
        format: '{value}%',  // Agregar símbolo %
        style: {
          color: '#64748b'
        }
      },
      gridLineColor: '#f1f5f9'
    },
    tooltip: {
      useHTML: true,
      backgroundColor: '#ffffff',
      borderWidth: 0,
      shadow: {
        color: 'rgba(0,0,0,0.1)',
        width: 3,
        offsetX: 2,
        offsetY: 2
      },
    },
    plotOptions: {
      line: {
        dataLabels: {
          enabled: true,
          format: '{y}%',  // Agregar símbolo %
          style: {
            color: '#1e293b',
            fontSize: '12px',
            textOutline: 'none'
          },
          align: 'center',
          y: -10  // Posición vertical
        },
        marker: {
          symbol: 'circle',
          radius: 6,
          fillColor: '#ffffff',
          lineWidth: 2,
          lineColor: null  // Hereda color de serie
        },
        animation: {
          duration: 1000
        }
      }
    },
    series: [{
      name: '% PROGRAMACIÓN AL TRIMESTRE',
      data: [0,matriz[4][1],matriz[4][2],matriz[4][3],matriz[4][4]],
      color: '#3b82f6',  // Azul corporativo
      marker: {
        lineColor: '#3b82f6'
      }
    },{
      name: '% CUMPLIMIENTO AL TRIMESTRE',
      data: [0,matriz[5][1],matriz[5][2],matriz[5][3],matriz[5][4]],
      color: '#10b981',  // Verde esmeralda
      marker: {
        lineColor: '#10b981'
      }
    }],
    credits: {
      enabled: false  // Remover créditos de Highcharts
    },
    legend: {
      align: 'right',
      verticalAlign: 'top',
      itemStyle: {
        color: '#475569',
        fontWeight: 500
      }
    },
  });
}

    /// Grafico pastel
  /*function graf_regresion_pastel(matriz,trimestre) {
      Highcharts.chart('pastel_todos', {
        chart: {
          type: 'pie',
          options3d: {
              enabled: true,
              alpha: 45,
              beta: 0
          }
        },
        title: {
            text: 'HOLA MUNDO'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              depth: 35,
              dataLabels: {
                  enabled: true,
                  format: '{point.name}'
              }
          }
        },
        series: [{
          type: 'pie',
          name: 'Actividades',
          data: [
              {
                name: 'NO CUMPLIDO : '+Math.round(100-(matriz[5][trimestre]+Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100)))+' %',
                y: matriz[6][trimestre],
                color: '#f98178',
              },

              {
                name: 'CUMPLIMIENTO PARCIAL : '+Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100)+' %',
                y: Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100),
                color: '#f5eea3',
              },

              {
                name: 'CUMPLIDO : '+matriz[5][trimestre]+' %',
                y: matriz[5][trimestre],
                color: '#2CC8DC',
                sliced: true,
                selected: true
              }
          ]
        }]
      });

    }*/


function graf_regresion_pastel(matriz,trimestre) {
  Highcharts.chart('pastel_todos', {
    chart: {
      type: 'pie',
      backgroundColor: '#f0f0f0', // Fondo plomo claro
      spacing: [30, 10, 15, 10], // Espaciado superior aumentado
      options3d: { enabled: true } // Deshabilitar 3D
    },
    title: {
      text: 'DETALLE CUMPLIMIENTO POA (Trimestre)',
      align: 'center',
      verticalAlign: 'top',
      margin: 10,
      style: {
        color: '#333333',
        fontSize: '24px',
        fontWeight: '600',
        fontFamily: 'Arial, sans-serif',
        textTransform: 'uppercase'
      },
      y: 10 // Posición vertical ajustada
    },
    tooltip: {
      useHTML: true,
      backgroundColor: '#ffffff',
      borderWidth: 0,
      borderRadius: 8,
      shadow: {
        color: 'rgba(0,0,0,0.1)',
        width: 5,
        offsetX: 2,
        offsetY: 2
      },
    },
    plotOptions: {
      pie: {
        allowPointSelect: true,
        cursor: 'pointer',
        innerSize: '39%', // Efecto donut moderno
        dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b>',
          style: {
            color: '#2d3748',
            fontSize: '12px',
            fontWeight: '500',
            textOutline: 'none'
          },
          distance: 20,
          connectorWidth: 1,
          connectorColor: '#cbd5e0'
        },
        borderWidth: 2,
        borderColor: '#ffffff' // Borde blanco entre secciones
      }
    },
    series: [{
      type: 'pie',
      name: 'Actividades',
      data: [
        {
          name: 'NO CUMPLIDO : '+Math.round(100-(matriz[5][trimestre]+Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100)))+' %',
          y: matriz[6][trimestre],
          color: '#ef4444', // Rojo mejorado
          className: 'slice-emergencia'
        },
        {
          name: 'EN PROCESO : '+Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100)+' %',
          y: Math.round((matriz[7][trimestre]/matriz[2][trimestre])*100),
          color: '#f59e0b' // Ámbar más profesional
        },
        {
          name: 'CUMPLIDO : '+matriz[5][trimestre]+' %',
          y: matriz[5][trimestre],
          color: '#10b981', // Verde esmeralda
          sliced: true,
          selected: true
        }
      ]
    }],
    responsive: {
      rules: [{
        condition: {
          maxWidth: 600
        },
        chartOptions: {
          title: {
            style: { fontSize: '18px' },
            margin: 20,
            y: 10
          },
          plotOptions: {
            pie: {
              dataLabels: {
                distance: 15,
                style: { fontSize: '10px' }
              }
            }
          }
        }
      }]
    }
  });
}




    /// Grafico Seguimiento POA Mensual
    function graf_seguimiento_poa(matriz) {
      Highcharts.chart('container', {
        chart: {
          type: 'column',
          options3d: {
              enabled: true,
              alpha: 0,
              beta: 0,
              depth: 50
          }
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        
        plotOptions: {
            column: {
                depth: 20
            }
        },
        xAxis: {
            categories: Highcharts.getOptions().lang.shortMonths,
            labels: {
                skew3d: true,
                style: {
                    fontSize: '12px'
                }
            }
        },
        yAxis: {
            title: {
              text: 'cumplimiento (%)'
            }
        },
        xAxis: {
          categories: [
              'ENE.', 
              'FEB.', 
              'MAR.', 
              'ABR.', 
              'MAY.', 
              'JUN.', 
              'JUL.', 
              'AGO.', 
              'SEPT.', 
              'OCT.', 
              'NOV.', 
              'DIC.'
          ]
        },
        series: [{
          name: 'Eficiencia',
          data: [matriz[4][1],matriz[4][2],matriz[4][3],matriz[4][4],matriz[4][5],matriz[4][6],matriz[4][7],matriz[4][8],matriz[4][9],matriz[4][10],matriz[4][11],matriz[4][12]]
        }]
      });
    }


  //// Verificando valor ejecutado por form 4
  function verif_valor(programado,ejecutado,prod_id,nro,tp,mes_id){
    //alert(programado+'-'+ejecutado+'-'+prod_id+'-'+nro+'-'+tp+'-'+mes_id)
   /// tp 0 : Registro
   /// tp 1 : modifcacion  

    if(ejecutado!= ''){
    //  alert(ejecutado+'-'+prod_id+'-'+nro+'-'+tp+'-'+mes_id)
      var url = base+"index.php/ejecucion/cseguimiento/verif_valor_ejecutado_x_form4";
      $.ajax({
        type:"post",
        url:url,
        data:{ejec:ejecutado,prod_id:prod_id,tp:tp,mes_id:mes_id},
        success:function(datos){

         if(datos.trim() =='true'){

          $('#but'+nro).slideDown();
          document.getElementById("ejec"+nro).style.backgroundColor = "#ffffff";
          document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
         }
         else{
          alertify.error("ERROR EN EL DATO REGISTRADO !");
           document.getElementById("ejec"+nro).style.backgroundColor = "#fdeaeb";
          $('#but'+nro).slideUp();
         }

      }});
    }
    else{
      $('#but'+nro).slideUp();
    }
  }

    /// Funcion para guardar datos de seguimiento POA y Actualiza Cuadro de Evaluacion POA 2025
    function guardar(prod_id,nro){
      $('#btn_generarr').html('');
      $('#loading_evalpoa').html('<center><img src="'+base+'/assets/img_v1.1/loading.gif" style="width:350px; height:350px;" alt="loading" /></center>');
      ejec=parseFloat($('[id="ejec'+nro+'"]').val());
      mverificacion=($('[id="mv'+nro+'"]').val());
      problemas=($('[id="obs'+nro+'"]').val());
      accion=($('[id="acc'+nro+'"]').val());

      if(($('[id="mv'+nro+'"]').val())==0){
          document.getElementById("mv"+nro).style.backgroundColor = "#fdeaeb";
          alertify.error("REGISTRE MEDIO DE VERIFICACIÓN, Form. "+nro);
          return 0; 
      }
      else{
          document.getElementById("mv"+nro).style.backgroundColor = "#ffffff";
          alertify.confirm("GUARDAR REGISTRO POA?", function (a) {
          if (a) {
              document.getElementById("loading").style.display = 'block';
              var url = base+"index.php/ejecucion/cseguimiento/guardar_seguimiento";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "prod_id="+prod_id+"&ejec="+ejec+"&mv="+mverificacion+"&obs="+problemas+"&acc="+accion
              });

              request.done(function (response, textStatus, jqXHR) {

              if (response.respuesta == 'correcto') {
           
                    document.getElementById("loading").style.display = 'none';
                    document.getElementById('ejec'+nro).value = response.ejecucion;
                    document.getElementById('mv'+nro).value = response.m_verificacion;
                    document.getElementById('obs'+nro).value = response.observacion;
                    document.getElementById('acc'+nro).value = response.acciones;
                    document.getElementById('btn'+nro).innerHTML = '<font color=green size=1px><b>MODIFICAR</b></font>';
                    document.getElementById('calif'+nro).innerHTML = response.calif;
                    document.getElementById("ejec"+nro).style.backgroundColor = "#ffffff";
                    if(response.ejecucion==0){
                      document.getElementById('btn'+nro).innerHTML = '<font color=orange size=1px><b>MODIFICAR</b></font>';
                      document.getElementById("ejec"+nro).style.backgroundColor = "#fdeaeb";
                    }
                    

                    //// actualizando el cumplimiento (graficos)
                    document.getElementById('calificacion').innerHTML = response.calificacion;
                    document.getElementById('loading_evalpoa').innerHTML = '';
                    document.getElementById("cuerpo_evalpoa").style.display = 'block';

                    document.getElementById('tabla_regresion_impresion').innerHTML = response.tabla_regresion_impresion;
                    graf_regresion_trimestral(response.matriz_regresion);

                    document.getElementById('tabla_pastel_vista').innerHTML = response.tabla_pastel_todo;
                    graf_regresion_pastel(response.matriz_regresion,response.trm_id);

                    document.getElementById('tabla_regresion_total_impresion').innerHTML = response.tabla_regresion_total_impresion;
                    graf_regresion_anual(response.matriz_gestion);


                    document.getElementById('list_form4_temporalidad').innerHTML = response.form4_temporalidad;

                    alertify.success("EL SEGUIMIENTO SE GUARDO CORRECTAMENTE ...");
              }
              else{
                  alertify.error("ERROR AL GUARDAR SEGUIMIENTO POA");
              }

              });
          } else {
              alertify.error("OPCI\u00D3N CANCELADA");
          }
        });
      }
    }

    /// Cambio de mes para el seguimiento 
    $("#mes_id").change(function () {
        $("#mes_id option:selected").each(function () {
            mes_id=$(this).val();
            mes_activo=$('[name="mes_activo"]').val();

            if(mes_id!=mes_activo){
              var url = base+"index.php/ejecucion/cseguimiento/get_update_mes";
              var request;
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: 'json',
                  data: "mes_id="+mes_id
              });

              request.done(function (response, textStatus, jqXHR) {
                  if (response.respuesta == 'correcto') {
                      document.getElementById("loading").style.display = 'block';
                      window.location.reload(true);
                  }
                  else{
                      alertify.error("ERROR !!!");
                  }
              }); 
            }
        });
      })


    
    $(function () {
        $(".enlace").on("click", function (e) {
          prod_id = $(this).attr('name');
          //alert(prod_id)
           //$('#temporalidad').html('<div class="loading" align="center"><img src='+base+'"/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando Información</div>');
            var url = base+"index.php/ejecucion/cseguimiento/get_temporalidad";
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
              $('#temporalidad').fadeIn(1000).html(response.tabla);
              $('#calificacion_form4').fadeIn(1000).html(response.calificacion);
            }
            else{
                alertify.error("ERROR AL RECUPERAR TEMPORALIDAD");
            }

            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
            });
            e.preventDefault();
          
        });
    });


    /*------ ACTUALIZANDO DATOS DE EVALUACION POA AL TRIMESTRE ACTUAL ------*/
    $(function () {
      $(".update_eval").on("click", function (e) {
        
          com_id = $(this).attr('name');
          document.getElementById("com_id").value=com_id;
          $('#tit').html('<font size=3><b>'+$(this).attr('id')+'</b></font>');
          $('#but').slideUp();

          var url = base+"index.php/ejecucion/cseguimiento/update_evaluacion_trimestral";
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
              $('#content_valida').fadeIn(1000).html(response.tabla);
              $('#but').slideDown();
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
          e.preventDefault();

          $("#but_update").on("click", function (e) {
            var $valid = $("#form_update").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } else {
                window.location.reload(true);
                document.getElementById("but").style.display = 'none';
                document.getElementById("load").style.display = 'block';
                alertify.success("ACTUALIZACIÓN EXITOSA ...");
            }
          });
      });
    });


    /// Eliminar Registro del Seguimiento Mensual
    $(function () {
        function reset() {
          $("#toggleCSS").attr("href", base+"assets/themes_alerta/alertify.default.css");
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

      $(".del_ope").on("click", function (e) {
        reset();
        var prod_id = $(this).attr('name'); // prod id
        var mes_id = $(this).attr('id'); // mes id

        var request;
        alertify.confirm("ESTA SEGURO DE ELIMINAR REGISTRO POA ?", function (a) {
          if (a) {
              document.getElementById("loading").style.display = 'block';
              url = base+"index.php/ejecucion/cseguimiento/delete_seguimiento_operacion";
              if (request) {
                  request.abort();
              }
              request = $.ajax({
                  url: url,
                  type: "POST",
                  dataType: "json",
                  data: "prod_id="+prod_id+"&mes_id="+mes_id
              });

              request.done(function (response, textStatus, jqXHR) { 
                reset();
                if (response.respuesta == 'correcto') {

                    document.getElementById("loading").style.display = 'none';
                    window.location.reload(true);
                    alertify.success("EL REGISTRO SE ELIMINO CORRECTAMENTE ...");
                } else {
                    alertify.error("Error al Eliminar Registro ..");
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
              alertify.error("Opcion cancelada");
          }
        });
        return false;
      });

    });



      //// Seguimiento POA
//       function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {

//       var ventana = window.open('Seguimiento Evaluacion POA ', 'PRINT', 'height=800,width=1000');
//       ventana.document.write('<html><head><title>SEGUIMIENTO POA</title>');
//       //ventana.document.write('<link rel="stylesheet" href="assets/print_static.css">');
//       ventana.document.write('</head><body>');
//      // ventana.document.write('<style type="text/css" media="print">div.page { writing-mode: tb-rl;height: 100%;margin: 100% 100%;}</style>');
//       //ventana.document.write('<style type="text/css">@media print{body{writing-mode: rl;}}.verde{ width:100%; height:5px; background-color:#1c7368;}.blanco{ width:100%; height:5px; background-color:#F1F2F1;}</style>');
//       ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
//      // ventana.document.write('<div class="page">');
//       ventana.document.write('<hr>');
//       ventana.document.write(cabecera.innerHTML);
//       ventana.document.write('<hr>');
//     //  ventana.document.write(eficacia.innerHTML);
//       ventana.document.write(grafico.innerHTML);
//       ventana.document.write('<hr>');
//       ventana.document.write(tabla.innerHTML);
// /*      ventana.document.write("<p>");
//       ventana.document.write("<div style='font-size: 10px;'>[Copyright]:Departamento Nacional de Planificación - Sistema de Planificación de Salud SIIPLAS V.2</div>");
//       ventana.document.write("<\/p>");*/
//      // ventana.document.write('</div>');
//       ventana.document.write('</body></html>');
//       ventana.document.close();
//       ventana.focus();
//       ventana.onload = function() {
//         ventana.print();
//         ventana.close();
//       };
//       return true;
//     }


/*    document.querySelector("#btnImprimir_seguimiento").addEventListener("click", function() {
      var grafico = document.querySelector("#Seguimiento");

      document.getElementById("cabecera").style.display = 'block';
      var cabecera = document.querySelector("#cabecera");

      //var eficacia = document.querySelector("#efi");
      var eficacia = '';

      document.getElementById("tabla_componente_impresion").style.display = 'block';
      document.getElementById("tabla_componente_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_componente_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera").style.display = 'none';
      
      document.getElementById("tabla_componente_vista").style.display = 'block';
      document.getElementById("tabla_componente_impresion").style.display = 'none';
    });*/


/*    document.querySelector("#btnImprimir_evaluacion_trimestre").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_trimestre");
      
      document.getElementById("cabecera2").style.display = 'block';
      var cabecera = document.querySelector("#cabecera2");

      var eficacia = document.querySelector("#eficacia");
      
      document.getElementById("tabla_regresion_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera2").style.display = 'none';

      document.getElementById("tabla_regresion_vista").style.display = 'block';
      document.getElementById("tabla_regresion_impresion").style.display = 'none';
    });*/


/*    document.querySelector("#btnImprimir_evaluacion_pastel").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_pastel");
      
      document.getElementById("cabecera2").style.display = 'block';
      var cabecera = document.querySelector("#cabecera2");
      
      var eficacia = document.querySelector("#eficacia");

      document.getElementById("tabla_pastel_impresion").style.display = 'block';
      document.getElementById("tabla_pastel_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_pastel_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera2").style.display = 'none';

      document.getElementById("tabla_pastel_vista").style.display = 'block';
      document.getElementById("tabla_pastel_impresion").style.display = 'none';
    });*/




/*    document.querySelector("#btnImprimir_evaluacion_gestion").addEventListener("click", function() {
      var grafico = document.querySelector("#evaluacion_gestion");
      
      document.getElementById("cabecera3").style.display = 'block';
      var cabecera = document.querySelector("#cabecera3");
      
      var eficacia = document.querySelector("#efi");

      document.getElementById("tabla_regresion_total_impresion").style.display = 'block';
      document.getElementById("tabla_regresion_total_vista").style.display = 'none';
      var tabla = document.querySelector("#tabla_regresion_total_impresion");

      imprimirSeguimiento(grafico,cabecera,eficacia,tabla);
      document.getElementById("cabecera3").style.display = 'none';

      document.getElementById("tabla_regresion_total_vista").style.display = 'block';
      document.getElementById("tabla_regresion_total_impresion").style.display = 'none';
    });
*/
       



