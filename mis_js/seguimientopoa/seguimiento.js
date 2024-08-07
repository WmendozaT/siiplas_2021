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
    $('#loading_sepoa').html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Información </center>');
    $('#loading_evalpoa').html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Información </center>');
    $('#loading_evalpoa2').html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Información </center>');
    
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
        document.getElementById('btn_generar').innerHTML = '';

        //------ Seguimiento poa
        document.getElementById('loading_sepoa').innerHTML = '';
        document.getElementById("cuerpo_segpoa").style.display = 'block';
        document.getElementById('cabecera').innerHTML = response.cabecera1;
        document.getElementById('tabla_componente_vista').innerHTML = response.tabla_vista;
        document.getElementById('tabla_componente_impresion').innerHTML = response.tabla_impresion;
        graf_seguimiento_poa(response.matriz);
        /// ---- end 

        //------ Evaluacion POA
        document.getElementById('loading_evalpoa').innerHTML = '';
        document.getElementById("cuerpo_evalpoa").style.display = 'block';
        document.getElementById('cabecera2').innerHTML = response.cabecera2;
        document.getElementById('tabla_regresion_vista').innerHTML = response.tabla_regresion;
        document.getElementById('tabla_regresion_impresion').innerHTML = response.tabla_regresion_impresion;
        graf_regresion_trimestral(response.matriz_regresion);

        document.getElementById('tabla_pastel_vista').innerHTML = response.tabla_pastel_todo;
        document.getElementById('tabla_pastel_impresion').innerHTML = response.tabla_pastel_todo_impresion;
        graf_regresion_pastel(response.matriz_regresion,trimestre);

        document.getElementById('loading_evalpoa2').innerHTML = '';
        document.getElementById("cuerpo_evalpoa2").style.display = 'block';
        document.getElementById('cabecera3').innerHTML = response.cabecera3;
        document.getElementById('tabla_regresion_total_vista').innerHTML = response.tabla_regresion_total;
        document.getElementById('tabla_regresion_total_impresion').innerHTML = response.tabla_regresion_total_impresion;
        graf_regresion_anual(response.matriz_gestion);
        // ---- end


        /// ---- lista de form completa
        document.getElementById('list_form4_temporalidad').innerHTML = response.form4_temporalidad;
        ///------ end
      }
      else{
          alertify.error("ERROR !!!");
      }
    }); 
  }



    /// Grafico regresion por trimestre
    function graf_regresion_trimestral(matriz) {
      chart = new Highcharts.Chart({
      chart: {
        renderTo: 'regresion',  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: ''  // Titulo (Opcional)
      },
      subtitle: {
        text: ''   // Subtitulo (Opcional)
      },
      // Pongo los datos en el eje de las 'X'
      xAxis: {
        categories: ['','I Trimestre','II Trimestre','III Trimestre','IV Trimestre'],
        // Pongo el título para el eje de las 'X'
        title: {
          text: 'N° Actividades por Trimestre'
        }
      },
      yAxis: {
        // Pongo el título para el eje de las 'Y'
        title: {
          text: 'N° Act'
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
            name: 'NRO ACT. PROGRAMADO EN EL TRIMESTRE',
            data: [0,matriz[2][1],matriz[2][2],matriz[2][3],matriz[2][4]]
          },
          {
            name: 'NRO ACT. CUMPLIDO EN EL TRIMESTRE',
            data: [0,matriz[3][1],matriz[3][2],matriz[3][3],matriz[3][4]]
          }
        ],
        
      });
    }


    /// Grafico regresion Anual
    function graf_regresion_anual(matriz) {
      chart = new Highcharts.Chart({
      chart: {
        renderTo: 'regresion_gestion',  // Le doy el nombre a la gráfica
        defaultSeriesType: 'line' // Pongo que tipo de gráfica es
      },
      title: {
        text: ''  // Titulo (Opcional)
      },
      subtitle: {
        text: ''   // Subtitulo (Opcional)
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
            name: '% ACT. PROGRAMADAS EN EL TRIMESTRE',
            data: [0,matriz[4][1],matriz[4][2],matriz[4][3],matriz[4][4]]
          },
          {
            name: '% ACT. CUMPLIDAS EN EL TRIMESTRE',
            data: [0,matriz[5][1],matriz[5][2],matriz[5][3],matriz[5][4]]
          }
        ],
        
      });
    }


    /// Grafico pastel
  function graf_regresion_pastel(matriz,trimestre) {
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
            text: ''
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
              depth: 100
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
                depth: 25
            }
        },
        xAxis: {
            categories: Highcharts.getOptions().lang.shortMonths,
            labels: {
                skew3d: true,
                style: {
                    fontSize: '16px'
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

    /// Funcion para guardar datos de seguimiento POA
    function guardar(prod_id,nro){
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
          alertify.confirm("GUARDAR SEGUIMIENTO POA?", function (a) {
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
        alertify.confirm("ESTA SEGURO DE ELIMINAR REGISTRO DE SEGUIMIENTO POA ?", function (a) {
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
      function imprimirSeguimiento(grafico,cabecera,eficacia,tabla) {

      var ventana = window.open('Seguimiento Evaluacion POA ', 'PRINT', 'height=800,width=1000');
      ventana.document.write('<html><head><title>SEGUIMIENTO POA</title>');
      //ventana.document.write('<link rel="stylesheet" href="assets/print_static.css">');
      ventana.document.write('</head><body>');
     // ventana.document.write('<style type="text/css" media="print">div.page { writing-mode: tb-rl;height: 100%;margin: 100% 100%;}</style>');
      //ventana.document.write('<style type="text/css">@media print{body{writing-mode: rl;}}.verde{ width:100%; height:5px; background-color:#1c7368;}.blanco{ width:100%; height:5px; background-color:#F1F2F1;}</style>');
      ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
     // ventana.document.write('<div class="page">');
      ventana.document.write('<hr>');
      ventana.document.write(cabecera.innerHTML);
      ventana.document.write('<hr>');
    //  ventana.document.write(eficacia.innerHTML);
      ventana.document.write(grafico.innerHTML);
      ventana.document.write('<hr>');
      ventana.document.write(tabla.innerHTML);
/*      ventana.document.write("<p>");
      ventana.document.write("<div style='font-size: 10px;'>[Copyright]:Departamento Nacional de Planificación - Sistema de Planificación de Salud SIIPLAS V.2</div>");
      ventana.document.write("<\/p>");*/
     // ventana.document.write('</div>');
      ventana.document.write('</body></html>');
      ventana.document.close();
      ventana.focus();
      ventana.onload = function() {
        ventana.print();
        ventana.close();
      };
      return true;
    }


    document.querySelector("#btnImprimir_seguimiento").addEventListener("click", function() {
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
    });


    document.querySelector("#btnImprimir_evaluacion_trimestre").addEventListener("click", function() {
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
    });


    document.querySelector("#btnImprimir_evaluacion_pastel").addEventListener("click", function() {
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
    });




    document.querySelector("#btnImprimir_evaluacion_gestion").addEventListener("click", function() {
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

       



