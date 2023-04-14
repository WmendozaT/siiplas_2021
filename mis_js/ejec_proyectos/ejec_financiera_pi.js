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

//// ==== FORMULARIO DE EJECUCION DE PROYECTOS DE INVERSION (MODULO DE SEGUIMIENTO POA)

  function verif_datos(tipo,ejecutado,id,mes_id){
   /// tp 0 : nuevo
   /// tp 1 : modifcacion  
   // alert(ejecutado+'-'+valor2+'-'+valor3)
    if(ejecutado!= ''){
      var url = base+"index.php/ejecucion/cejecucion_pi/verif_valor_ejecutado_x_partida_form";
        var request;
        if (request) {
          request.abort();
        }
        request = $.ajax({
          url: url,
          type: "POST",
          dataType: 'json',
          data: "tipo="+tipo+"&ejec="+ejecutado+"&sp_id="+id+"&mes_id="+mes_id
        });

        request.done(function (response, textStatus, jqXHR) {
          //alert(response.respuesta)
        if (response.respuesta == 'correcto') {
            
            document.getElementById("ppto"+id).innerHTML = response.ejecucion_total_partida;
            document.getElementById("porcentaje"+id).innerHTML = response.porcentaje_ejecucion_total_partida+' %';
            $('#but'+id).slideDown();
        }
        else{
            alertify.error("ERROR EN EL DATO REGISTRADO !");
            document.getElementById("ppto"+id).innerHTML = '';
            document.getElementById("porcentaje"+id).innerHTML = '';
            $('#but'+id).slideUp();
        }

      });

    }
    else{
      $('#but'+id).slideUp();
      document.getElementById("ppto"+id).innerHTML = '';
      document.getElementById("porcentaje"+id).innerHTML = '';
    }
  }


  function verif_valor_pi(tipo,ejecutado,id,mes_id){
    verif_datos(tipo,ejecutado,id,mes_id)
  }

  function verif_valor_pi_obs(observacion,id){
    $('#but'+id).slideDown();
  }


/// Funcion para guardar datos de Ejecucion Proy Inversion
function guardar_pi(proy_id,tp,id_partida,mes_id,ejec_ppto_id,partida){
  ejec=parseFloat($('[id="ejec'+id_partida+'"]').val());
  obs=$('[id="obs_pi'+id_partida+'"]').val();

  alertify.confirm("GUARDAR EJECUCION EN LA PARTIDA "+partida+" ?", function (a) {
    if (a) {
      var url = base+"index.php/ejecucion/cejecucion_pi/guardar_datos_ejecucion_pinversion";
      var request;
      if (request) {
          request.abort();
      }
      request = $.ajax({
        url: url,
        type: "POST",
        dataType: 'json',
        data: "proy_id="+proy_id+"&sp_id="+id_partida+"&ejec="+ejec+"&obs="+obs+"&tp="+tp+"&mes_id="+mes_id+"&ejec_ppto_id="+ejec_ppto_id
      });

      request.done(function (response, textStatus, jqXHR) {

      if (response.respuesta == 'correcto') {
            document.getElementById('btn_generar').innerHTML = '';
            document.getElementById("botton").style.display = 'block';

            alertify.success("REGISTRO EXITOSO ...");
            document.getElementById("ppto"+id_partida).innerHTML = response.ejecucion_total_partida;
            document.getElementById("porcentaje"+id_partida).innerHTML = response.porcentaje_ejecucion_total_partida+' %';
            document.getElementById("ejec"+id_partida).value = response.dato_ejec; /// dato ejec
            document.getElementById("obs_pi"+id_partida).value = response.dato_obs; /// dato obs
            document.getElementById("efi").innerHTML = response.eficacia+'';
            $('#but'+id_partida).slideUp();

            ///-------
            
            document.getElementById('cuadro_consolidado_vista').innerHTML = response.cuadro_consolidado;
            document.getElementById('cuadro_consolidado_impresion').innerHTML = response.cuadro_consolidado_impresion;

            //// Grafico Regresion
            graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial',response.matriz,'EJECUCIÓN FINANCIERA - '+response.mes,response.datos_proyecto,'(Bs)'); /// vista
            graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial_impresion',response.matriz,'','EJECUCIÓN FINANCIERA - '+response.mes,'(Bs)'); /// impresion


            //// Graficos Barras Verticales
            let detalle_ejecucion=[];
            for (var i = 0; i < 12; i++) {
                detalle_ejecucion[i]= { name: response.matriz[0][i+1],y: response.matriz[7][i+1]};
            }

            cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado',detalle_ejecucion,'% EJECUCION FINANCIERA MENSUAL',response.datos_proyecto,'CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// vista
            cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado_impresion',detalle_ejecucion,'','% EJECUCION FINANCIERA MENSUAL','CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// impresion
      }
      else{
          alertify.error("ERROR AL GUARDAR EJECUCION POA");
      }

      });
    } else {
        alertify.error("OPCI\u00D3N CANCELADA");
    }
  });

}

  //// GUARDA DATOS 
  $("#subir_form1").on("click", function () {
      var $validator = $("#form1").validate({
          rules: {
              
              est_proy: { //// estado del proyecto
                required: true,
              },
              municipio: { //// municipio
                required: true,
              },
              fase_id: { //// fase
                required: true,
              },
              fiscal: { //// fiscal de obra
                required: true,
              },
              a_fisico: { //// avance fisico
                required: true,
              },
              a_financiero: { //// avance financiero
                required: true,
              },
              observacion: { //// observacion
                required: true,
              },
              f_plazo: { //// fecha plazo
                required: true,
              },
              problema: { //// problema presentados
                required: true,
              }
          },
          messages: {
              //costo: "<font color=red>REGISTRE COSTO TOTAL DEL PROYECTO</font>", 
              est_proy: "<font color=red>SELECCIONE ESTADO DEL PROYECTO</font>", 
              municipio: "<font color=red>REGISTRE MUNICIPIO</font>", 
              fase_id: "<font color=red>SELECCIONE FASE</font>",
              fiscal: "<font color=red>SELECCIONE FISCAL</font>",
              a_fisico: "<font color=red>REGISTRE AVANCE FISICO</font>",  
              a_financiero: "<font color=red>REGISTRE AVANCE FINANCIERO</font>",  
              observacion: "<font color=red>REGISTRE OBSERVACION / COMPROMISOS</font>",
              f_plazo: "<font color=red>SELECCIONE FECHA PLAZO</font>",
              problema: "<font color=red>REGISTRE PROBLEMA IDENTIFICADO</font>",                    
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

      var $valid = $("#form1").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        

        alertify.confirm("GUARDAR INFORMACION DE EJECUCIÓN ?", function (a) {
            if (a) {
                document.getElementById('subir_form1').disabled = true;
                document.forms['form1'].submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
      }
  });


  //// funcion para generar el Consolidado de Ejecucion Proy Inversion (cuadro, grafico)
  function generar_cuadro_consolidado_ejecucion_pi(proy_id){
    $('#loading_sepoa').html('<center><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Un momento por favor, Cargando Información </center>');

    var url = base+"index.php/ejecucion/cejecucion_pi/get_cuadro_ejecucion_pi";
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

        document.getElementById('btn_generar').innerHTML = '';
        document.getElementById('loading_sepoa').innerHTML = '';
        document.getElementById("botton").style.display = 'block';

        //// Tabla Distribucion Ppto
        document.getElementById('cuadro_consolidado_vista').innerHTML = response.cuadro_consolidado;
        document.getElementById('cuadro_consolidado_impresion').innerHTML = response.cuadro_consolidado_impresion;

        //// Grafico Regresion
        graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial',response.matriz,'EJECUCIÓN FINANCIERA - '+response.mes,response.datos_proyecto,'(Bs)'); /// vista
        graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial_impresion',response.matriz,'','EJECUCIÓN FINANCIERA - '+response.mes,'(Bs)'); /// impresion

        //// Graficos Barras Verticales
        let detalle_ejecucion=[];
        for (var i = 0; i < 12; i++) {
            detalle_ejecucion[i]= { name: response.matriz[0][i+1],y: response.matriz[7][i+1]};
        }

        cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado',detalle_ejecucion,'% EJECUCION FINANCIERA MENSUAL',response.datos_proyecto,'CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// vista
        cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado_impresion',detalle_ejecucion,'','% EJECUCION FINANCIERA MENSUAL','CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// impresion

      }
      else{
        alertify.error("ERROR !!!");
      }
    }); 
  }




  /// Grafico EJECUCION DE PROYECTOS DE INVERSION
  function graf_regresion_consolidado_pi(grafico,matriz,titulo,subtitulo,tit_laterales) {
    let programado_inicial=[];
    for (var i = 0; i <12; i++) {
      programado_inicial[i]= matriz[2][i+1];
    }

    let programado=[];
    for (var i = 0; i <12; i++) {
      programado[i]= matriz[4][i+1];
    }

    let ejecutado=[];
    for (var i = 0; i <12; i++) {
      ejecutado[i]= matriz[6][i+1];
    }

    ///----
    chart = new Highcharts.Chart({
    chart: {
      renderTo: grafico,  // Le doy el nombre a la gráfica
      defaultSeriesType: 'line' // Pongo que tipo de gráfica es
    },
    title: {
      text: '<b>'+titulo+'</b>' // Titulo (Opcional)
    },
    subtitle: {
      text: subtitulo   // Subtitulo (Opcional)
    },
    // Pongo los datos en el eje de las 'X'
    xAxis: {
      categories: ['ENE.','FEB.','MAR.','ABR.','MAY.','JUN.','JUL.','AGO.','SEPT.','OCT.','NOV.','DIC.'],
      // Pongo el título para el eje de las 'X'
      title: {
        text: ''
      }
    },
    yAxis: {
      // Pongo el título para el eje de las 'Y'
      title: {
        text: tit_laterales
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
          name: 'PPTO. PROGRAMADO INICIAL',
          data: programado_inicial,
          marker: {
            lineWidth: 5,
            lineColor: Highcharts.getOptions().colors[0],
            fillColor: 'blue',
          },
          dataLabels: {
            color: 'blue'
          }
        },
        {
          name: 'PPTO. PROGRAMADO AJUSTADO',
          data: programado,
          marker: {
            lineWidth: 5,
            lineColor: Highcharts.getOptions().colors[1],
            fillColor: 'black',
          },
          dataLabels: {
            color: 'black'
          }
        },
        {
          name: 'PPTO. EJECUTADO',
          data: ejecutado,
          marker: {
            lineWidth: 5,
            lineColor: Highcharts.getOptions().colors[7],
            fillColor: 'green',
          },
          dataLabels: {
            color: 'green'
          }
        }
      ],
      
    });
  }


  //// Cuadro consolidado de Proyectos ppto Inicial, ejecutado
  function imprimir_ejecucion_proyectos() {
    var cabecera = document.querySelector("#cabecera");
    var cumplimiento = document.querySelector("#efi");
    var grafico1 = document.querySelector("#distribucion_ppto_ejecutado_inicial_impresion"); /// grafico regresion
    var grafico2 = document.querySelector("#cumplimiento_mensual_ppto_inicial_ejecutado_impresion"); /// grafico barras
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("cuadro_consolidado_impresion").style.display = 'block';
    var tabla = document.querySelector("#cuadro_consolidado_impresion");
    imprimir_cuadro_ejecucion_pi(grafico1,grafico2,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("cuadro_consolidado_impresion").style.display = 'none';
  }

  function imprimir_cuadro_ejecucion_pi(grafico1,grafico2,cabecera,cumplimiento,tabla) {
    var ventana = window.open('Ejecucion Financiera ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN FINANCIERA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write(cumplimiento.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico1.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico2.innerHTML);
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












///================================================================================================================

//// ================================================= MODULO DE CONSULTAS POA
/// ----- REPORTE CONSULTA POA EJECUCION DE PROYECTOS DE INVERSION
  /// grado de cumplimiento PI x REGIONAL
  function nivel_cumplimiento_pi_regional(dep_id) {
    var url = base+"index.php/consultas_cns/c_consultaspi/get_detalle_ejecucion_ppto_pi_regional";
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

        $('#tabla').html(response.tabla);

        //// Avance de Proyectos
            let detalle_ejecucion=[];
            for (var i = 0; i < response.nro_proy; i++) {
              detalle_ejecucion[i]= { name: response.matriz_proy[i][10],y: response.matriz_proy[i][15]};
            }

            cuadro_grafico_en_barras_verticales('proyectos',detalle_ejecucion,'EJECUCIÓN FINANCIERA DE INVERSIÓN - '+response.regional,' (%) Cumplimiento al mes de '+descripcion_mes+' / '+gestion,'(%) CUMPLIMIENTO FINANCIERA');
      }
      else{
          alertify.error("ERROR AL RECUPERAR INFORMACION");
      }

    });
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

                /// cuadro distribucion ppto
                document.getElementById('cuadro_consolidado_vista').innerHTML = response.cuadro_consolidado;

                /// grafico regresion ppto inicial, ejecutado
                graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial',response.matriz1,'EJECUCIÓN FINANCIERA RESPECTO AL PPTO. INICIAL','INSTITUCIONAL - '+response.mes,response.regional,'(Bs)'); /// vista
                graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial_impresion',response.matriz1,'','EJECUCIÓN FINANCIERA RESPECTO AL PPTO. INICIAL ','(Bs)'); /// impresion
                

                /// Graficos Barras Verticales
                let detalle_ejecucion_mensual=[];
                for (var i = 0; i < 12; i++) {
                    detalle_ejecucion_mensual[i]= { name: response.matriz1[0][i+1],y: response.matriz1[7][i+1]};
                }

                cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado',detalle_ejecucion_mensual,'% EJECUCION FINANCIERA MENSUAL RESPECTO AL PPTO. INICIAL','INSTITUCIONAL',response.regional,'CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// vista
                cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado_impresion',detalle_ejecucion_mensual,'','% EJECUCION FINANCIERA MENSUAL RESPECTO AL PPTO. INICIAL','CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// impresion



                ///////
                cuadro_grafico_distribucion_proyectos('detalle_proyectos1',response.matriz_reg,response.nro_reg); /// grafico 1 detalle por proyecto
                cuadro_grafico_distribucion_proyectos('detalle_proyectos1_impresion',response.matriz_reg,response.nro_reg); /// grafico 1 detalle por proyecto impresion
                cuadro_grafico_distribucion_presupuesto_asignado('detalle_proyectos2',response.matriz_reg,response.nro_reg)  /// grafico 2 detalle por presupuesto
                cuadro_grafico_distribucion_presupuesto_asignado('detalle_proyectos2_impresion',response.matriz_reg,response.nro_reg)  /// grafico 2 detalle por presupuesto impresion

                //// Avance de Proyectos
                let detalle_ejecucion=[];
                for (var i = 0; i < response.nro_reg; i++) {
                  detalle_ejecucion[i]= { name: response.matriz_reg[i][1],y: response.matriz_reg[i][9]};
                }

                cuadro_grafico_en_barras_verticales('proyectos',detalle_ejecucion,'EJECUCIÓN FINANCIERA DE INVERSIÓN POR REGIONAL AL MES DE '+descripcion_mes+' / '+gestion+'','','Cumplimiento','PROYECTO DE INVERSION');
                ////


                //// Detalle Partidas
                let partida=[];
                for (var i = 0; i < response.nro_part; i++) {
                    partida[i]= 'PARTIDA '+response.matriz_part[i][2];
                }

                let partida_ejecucion=[];
                for (var i = 0; i < response.nro_part; i++) {
                    partida_ejecucion[i]= response.matriz_part[i][18];
                }

                cuadro_grafico_en_barras_horizontales('partidas',partida,partida_ejecucion,'CONSOLIDADO POR PARTIDAS - INSTITUCIONAL');/// detalle partidas
                cuadro_grafico_en_barras_horizontales('partidas_impresion',partida,partida_ejecucion,'CONSOLIDADO POR PARTIDAS - INSTITUCIONAL');/// detalle partidas impresion
                //// --- end

                cuadro_grafico_ppto_ejec_meses(response.vector_meses,'INSTITUCIONAL');  //// mensual
                //cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'INSTITUCIONAL') /// meses Acumulado

              }
              else{ /// Regional
                $('#lista_consolidado').fadeIn(1000).html(response.lista_reporte);

                document.getElementById('cuadro_consolidado_vista').innerHTML = response.cuadro_consolidado;
                
                /// grafico regresion ppto inicial, ejecutado
                graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial',response.matriz1,'EJECUCIÓN FINANCIERA RESPECTO AL PPTO. INICIAL',response.regional,'(Bs)'); /// vista
                graf_regresion_consolidado_pi('distribucion_ppto_ejecutado_inicial_impresion',response.matriz1,'','EJECUCIÓN FINANCIERA RESPECTO AL PPTO. INICIAL ','(Bs)'); /// impresion
                
                
                /// Graficos Barras Verticales
                let detalle_ejecucion_mensual=[];
                for (var i = 0; i < 12; i++) {
                    detalle_ejecucion_mensual[i]= { name: response.matriz1[0][i+1],y: response.matriz1[7][i+1]};
                }

                cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado',detalle_ejecucion_mensual,'% EJECUCION FINANCIERA MENSUAL RESPECTO AL PPTO. INICIAL',response.regional,'CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// vista
                cuadro_grafico_en_barras_verticales('cumplimiento_mensual_ppto_inicial_ejecutado_impresion',detalle_ejecucion_mensual,'','% EJECUCION FINANCIERA MENSUAL RESPECTO AL PPTO. INICIAL','CUMPLIMIENTO MENSUAL','% CUMPLIMIENTO'); /// impresion
                


              //// Avance de Proyectos
                let detalle_ejecucion=[];
                for (var i = 0; i < response.nro_proy; i++) {
                  detalle_ejecucion[i]= { name: response.matriz_proy[i][10],y: response.matriz_proy[i][15]};
                }

                cuadro_grafico_en_barras_verticales('proyectos',detalle_ejecucion,'EJECUCIÓN FINANCIERA POR PROYECTOS DE INVERSIÓN al mes de '+descripcion_mes+' / '+gestion,'','Cumplimiento','PROYECTO DE INVERSION');
              ////

              /// Partidas
                let partida=[];
                for (var i = 0; i < response.nro; i++) {
                    partida[i]= 'PARTIDA '+response.matriz[i][2];
                }

                let partida_ejecucion=[];
                for (var i = 0; i < response.nro; i++) {
                    partida_ejecucion[i]= response.matriz[i][18];
                }

                cuadro_grafico_en_barras_horizontales('partidas',partida,partida_ejecucion,'CONSOLIDADO POR PARTIDAS - REGIONAL : '+response.regional);/// detalle partidas
                cuadro_grafico_en_barras_horizontales('partidas_impresion',partida,partida_ejecucion,'CONSOLIDADO POR PARTIDAS - REGIONAL : '+response.regional);/// detalle partidas
              ///

                cuadro_grafico_ppto_ejec_meses(response.vector_meses,'REGIONAL');  //// mensual
                //cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'REGIONAL') /// meses Acumulado
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
function cuadro_grafico_distribucion_proyectos(grafico,matriz,nro){
  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= { name: matriz[i][1],y: matriz[i][4],drilldown: matriz[i][1]};
  }
  // Build the chart
  Highcharts.chart(grafico, {
    chart: {
      type: 'pie'
    },
    title: {
      text: 'PORCENTAJE DE DISTRIBUCIÓN DE PROYECTOS POR REGIONAL - GESTIÓN '+gestion
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
        name: "REGIONAL",
        colorByPoint: true,
        data: detalle
      }
    ]
    
  });
}

//// grafico Pastel Porcenjate distribucion por Presupuesto
function cuadro_grafico_distribucion_presupuesto_asignado(grafico,matriz,nro){
  let detalle=[];
  for (var i = 0; i < nro; i++) {
      detalle[i]= { name: matriz[i][1],y: matriz[i][10],drilldown: matriz[i][1]};
  }
  // Build the chart
  Highcharts.chart(grafico, {
    chart: {
      type: 'pie'
    },
    title: {
      text: 'PORCENTAJE DE DISTRIBUCIÓN DE PRESUPUESTO POR REGIONAL - GESTIÓN '+gestion
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
        //alert(response.respuesta)
        document.getElementById("proy_nombre").value = response.proyecto[0]['proy']+' - '+response.proyecto[0]['proyecto']; /// nombre proyecto
        document.getElementById("ppto_total").value = response.proyecto[0]['proy_ppto_total']; /// ppto total
        document.getElementById("estado").innerHTML = response.estado; /// estado
        document.getElementById("fase").innerHTML = response.lista_fase; /// lista fase
        document.getElementById("ejec_fis").value = response.proyecto[0]['avance_fisico']; /// Ejecucion Fisica Total
        document.getElementById("ejec_fin").value = response.proyecto[0]['avance_financiero']; /// Ejecucion Financiero Total
        document.getElementById("f_obras").value = response.proyecto[0]['fiscal_obra']; /// Fiscal de Obras
        document.getElementById("mydate").value = response.fecha_plazo; /// Fecha Plazo
        document.getElementById("calificacion").innerHTML = response.calificacion; /// Calificacion
        document.getElementById("observacion").value = response.proyecto[0]['proy_observacion']; /// Observacion
        document.getElementById("problema").value = response.proyecto[0]['proy_desc_problema']; /// problema
        document.getElementById("solucion").value = response.proyecto[0]['proy_desc_solucion']; /// solucion
        
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


$(document).ready(function() {
  pageSetUp();
  $("#mes_id").change(function () {
    $("#mes_id option:selected").each(function () {
      mes_id=$(this).val();
      $('#load').html('<div class="loading" align="center"><img src="'+base+'/assets/img/cargando-loading-039.gif" alt="loading" /></div>');
      var url = base+"index.php/ejecucion/cejecucion_pi/get_cambiar_mes";
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
              $('#load').html('');
              window.location.reload(true);
              
          }
          else{
              alertify.error("ERROR AL LISTAR");
          }
        }); 

    });
  });

})

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
              //// Avance de Proyectos
                let detalle_ejecucion=[];
                for (var i = 0; i < response.nro_proy; i++) {
                    detalle_ejecucion[i]= { name: response.matriz_proy[i][10],y: response.matriz_proy[i][15]};
                }

                cuadro_grafico_en_barras_verticales('proyectos',detalle_ejecucion,'EJECUCIÓN DE PROYECTOS, mes de '+descripcion_mes+' / '+gestion+' - REGIONAL : '+response.regional,'','Cumplimiento','PROYECTO DE INVERSION');
              ////
              //// Consolidado por partidas
                let partida=[];
                for (var i = 0; i < response.nro; i++) {
                    partida[i]= 'PARTIDA '+response.matriz[i][2];
                }

                let partida_ejecucion=[];
                for (var i = 0; i < response.nro; i++) {
                    partida_ejecucion[i]= response.matriz[i][18];
                }
                cuadro_grafico_en_barras_horizontales('partidas',partida,partida_ejecucion,'CONSOLIDADO POR PARTIDAS - REGIONAL : '+response.regional);/// detalle partidas
              ////
              cuadro_grafico_ppto_ejec_meses(response.vector_meses,'REGIONAL');  //// mensual
              //cuadro_grafico_ppto_ejec_meses_acumulado(response.vector_meses_acumulado,'REGIONAL') /// meses Acumulado
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


//// grafico Barras Verticales 
function cuadro_grafico_en_barras_verticales(grafico,detalle_ejecucion,titulo,subtitulo,cumplimiento,detalle){
    Highcharts.chart(grafico, {
      chart: {
        type: 'column'
      },
      title: {
        align: 'center',
        text: '<b>'+titulo+'</b>'
      },
      subtitle: {
        align: 'center',
        text: subtitulo
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
          text: '(%) '+cumplimiento
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
          name: detalle,
          colorByPoint: true,
          data: detalle_ejecucion
        }
      ]

    });
}

  //// grafico barras Horizontales consolidado por partidas
  function cuadro_grafico_en_barras_horizontales(grafico,categoria,ejecucion,titulo){

    Highcharts.chart(grafico, {
      chart: {
          type: 'bar'
      },
      title: {
          text: 'CUADRO DE EJECUCIÓN PRESUPUESTARIA AL MES DE '+descripcion_mes+' / '+gestion
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
    var cumplimiento = document.querySelector("#efi");
    var grafico = document.querySelector("#partidas_impresion");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_partida").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_partida");
    imprimirPartida(grafico,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_partida").style.display = 'none';
  }

  //// Barras Proyectos
  function imprimir_proyectos() {
    var cabecera = document.querySelector("#cabecera");
    var cumplimiento = document.querySelector("#efi");
    var grafico = document.querySelector("#graf_proyectos");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_ejecucion").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_ejecucion");
    imprimirPartida(grafico,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_ejecucion").style.display = 'none';
  }

  //// Barras Proyectos consultas PI
  function imprimir_proyectos_consultas() {
    var cabecera = document.querySelector("#cabecera_consulta");
    var grafico = document.querySelector("#graf_proyectos_consulta");
    var cumplimiento = document.querySelector("#efi_");
    document.getElementById("cabecera_consulta").style.display = 'block';
    var cabecera = document.querySelector("#cabecera_consulta");
    document.getElementById("tabla_impresion_ejecucion_consulta").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_ejecucion_consulta");
    imprimirPartida(grafico,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera_consulta").style.display = 'none';
    document.getElementById("tabla_impresion_ejecucion_consulta").style.display = 'none';
  }

  function imprimirPartida(grafico,cabecera,cumplimiento,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN PRESUPUESTARIA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(cumplimiento.innerHTML);
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
    var cumplimiento = document.querySelector("#efi");
    var grafico1 = document.querySelector("#graf_ppto_mensual");
    //var grafico2 = document.querySelector("#graf_ppto_mensual_acumulado");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_ejecucion_mensual").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_ejecucion_mensual");
    imprimirEjecucionMensual(grafico1,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_ejecucion_mensual").style.display = 'none';
  }

  function imprimirEjecucionMensual(grafico1,cabecera,cumplimiento,tabla) {
    var ventana = window.open('Ejecucion Financiera ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN FINANCIERA MENSUAL - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write(cumplimiento.innerHTML);
    ventana.document.write('<hr>');
    ventana.document.write(grafico1.innerHTML);
  //  ventana.document.write('<hr>');
  //  ventana.document.write(grafico2.innerHTML);
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
    var cumplimiento = document.querySelector("#efi");
    var grafico = document.querySelector("#graf_detalle_nro_proyectos");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_detalle1").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle1");
    imprimirPorcentajeDistribucion(grafico,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle1").style.display = 'none';
  }

    //// Distribucion Nro de Proyectos y presupuesto
  function imprimir_distribucion_ppto() {
    var cabecera = document.querySelector("#cabecera");
    var cumplimiento = document.querySelector("#efi");
    var grafico = document.querySelector("#graf_detalle_nro_ppto");
    document.getElementById("cabecera").style.display = 'block';
    var cabecera = document.querySelector("#cabecera");
    document.getElementById("tabla_impresion_detalle2").style.display = 'block';
    var tabla = document.querySelector("#tabla_impresion_detalle2");
    imprimirPorcentajeDistribucion(grafico,cabecera,cumplimiento,tabla);
    document.getElementById("cabecera").style.display = 'none';
    document.getElementById("tabla_impresion_detalle2").style.display = 'none';
  }

  function imprimirPorcentajeDistribucion(grafico,cabecera,cumplimiento,tabla) {
    var ventana = window.open('Ejecucion Presupuestaria ', 'PRINT', 'height=800,width=1000');
    ventana.document.write('<html><head><title>EJECUCIÓN FINANCIERA - PROYECTOS DE INVERSIÓN</title>');
    ventana.document.write('</head><body>');
    ventana.document.write('<style type="text/css">table.change_order_items { font-size: 6.5pt;width: 100%;border-collapse: collapse;margin-top: 2.5em;margin-bottom: 2.5em;}table.change_order_items>tbody { border: 0.5px solid black;} table.change_order_items>tbody>tr>th { border-bottom: 1px solid black;}</style>');
    ventana.document.write(cabecera.innerHTML);
    ventana.document.write('<hr>');
    //ventana.document.write(cumplimiento.innerHTML);

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