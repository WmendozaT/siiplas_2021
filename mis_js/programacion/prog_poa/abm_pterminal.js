$(function () {
    //-------------------------------  TIPO DE INDICADOR -------------------------------------------
    $("#pttipo_indicador").change(function () {
        $("#pttipo_indicador option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                //ABSOLUTO
                //hablitar cajas
                $('#caja_prog_mensual').slideDown();
                $('#caja_absoluto').slideDown();
                $('#caja_relativo').slideUp();
                $('#caja_relativo2').slideUp();
                $('.relativo').slideUp();
                //titulo texto
                $("#titulo_indicador").html('INDICADOR ABSOLUTO');
                $("#tipo_meta").html('');
                //formula
                document.getElementById("ptformula").value = ' N/A ';
                document.getElementById("ptformula").disabled = true;
                //caracterirsticas
                document.getElementById("relativoa").value = '';
                document.getElementById("relativob").value = '';
                document.getElementById("relativoc").value = '';
                //limpiar para el caso absoluto
                document.getElementById("ptmeta").value = 0;
                for (var i = 1; i <= 12; i++) {
                    document.getElementById("mes" + i).value = 0;
                }
                document.getElementById("pt_denominador").selectedIndex = 0;
            }else{
                //RELATIVO
                //habilitar cajas
                $('#caja_prog_mensual').slideDown();
                $('#caja_relativo').slideDown();
                $('#caja_relativo2').slideDown();
                $('.relativo').slideDown();
                $('#caja_absoluto').slideUp();
                //formula
                document.getElementById("ptformula").disabled = false;
                //titulo y textos
                $("#tipo_meta").html(' - % ');
                $("#titulo_indicador").html('INDICADOR RELATIVO');
                document.getElementById("ptformula").value = '';
                //caso relativo variable
                document.getElementById("ptmeta").value = 100;
                var numero = 100 / 12;
                var prog_mes = numero.toFixed(2);
                for (var i = 1; i <= 11; i++) {
                    document.getElementById("mes" + i).value = prog_mes;
                }
                document.getElementById("mes12").value = 8.37;
            }
        });
    });
    <!--================================       CASO RELATIVO Y VARIABLE  ==> META, PROGRAMACION MENSUAL         ================== -->
    $("#pt_denominador").change(function () {
        $("#pt_denominador option:selected").each(function () {
            elegido = $(this).val();
            if (elegido == '1') {
                document.getElementById("ptmeta").value=0;
                for(var i =1 ;i<=12;i++){
                    document.getElementById("mes"+i).value= 0;
                }
            } else {
                document.getElementById("ptmeta").value=100;
                var numero = 100/12;
                var prog_mes = numero.toFixed(2);
                for(var i =1 ;i<=11;i++){
                    document.getElementById("mes"+i).value= prog_mes;
                }
                document.getElementById("mes12").value= 8.37;
            }
        });
    });
    //============================ validar y guardar
    $("#guardar_productot").on("click", function (e) {
        var $validator = $("#form_add_productot").validate({

            rules: {
                ptobjetivo: {
                    required: true,
                },
              /*  ptindicador: {
                    required: true,
                },*/
                tipo_indicador: {
                    required: true,
                },
                uni_unidad: {
                    required: true,
                },
                funcionario: {
                    required: true,
                },
                ptlineabase: {
                    required: true,
                    number: true,
                },
                ptformula: {
                    required: true,
                },
                ptmeta: {
                    required: true,
                    //min: 1,
                    number: true,
                },
                ptponderacion: {
                    number: true,
                    min:0,
                },
                mes1: {
                    required: true,
                    number: true,
                },
                mes2: {
                    required: true,
                    number: true,
                },
                mes3: {
                    required: true,
                    number: true,
                },
                mes4: {
                    required: true,
                    number: true,
                },
                mes5: {
                    required: true,
                    number: true,
                },
                mes6: {
                    required: true,
                    number: true,
                },
                mes7: {
                    required: true,
                    number: true,
                },
                mes8: {
                    required: true,
                    number: true,
                },
                mes9: {
                    required: true,
                    number: true,
                },
                mes10: {
                    required: true,
                    number: true,
                },
                mes11: {
                    required: true,
                    number: true,
                },
                mes12: {
                    required: true,
                    number: true,
                }
            },
            messages: {
                ptobjetivo: {required: "Campo Requerido"},
                ptformula: {required: "Campo Requerido"},
                tipo_indicador: {required: "Campo Requerido"},
                ptindicador: {required: "Campo Requerido"},
                ptlineabase: {required: "Campo Requerido", number: "Ingrese un número"},
                ptmeta: {required: "Campo Requerido", number: "Ingrese un número",min:"La meta debe ser mayor a 1"},
                ptponderacion: {number: "Ingrese un número",min:'El número debe ser mayor igual a 0'},
                uni_unidad: {required: "Campo Requerido"},
                funcionario: {required: "Campo Requerido"},
                mes1: {required: "Campo Requerido", number: "Ingrese un número"},
                mes2: {required: "Campo Requerido", number: "Ingrese un número"},
                mes3: {required: "Campo Requerido", number: "Ingrese un número"},
                mes4: {required: "Campo Requerido", number: "Ingrese un número"},
                mes5: {required: "Campo Requerido", number: "Ingrese un número"},
                mes6: {required: "Campo Requerido", number: "Ingrese un número"},
                mes7: {required: "Campo Requerido", number: "Ingrese un número"},
                mes8: {required: "Campo Requerido", number: "Ingrese un número"},
                mes9: {required: "Campo Requerido", number: "Ingrese un número"},
                mes10: {required: "Campo Requerido", number: "Ingrese un número"},
                mes11: {required: "Campo Requerido", number: "Ingrese un número"},
                mes12: {required: "Campo Requerido", number: "Ingrese un número"}
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
        var $valid = $("#form_add_productot").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            var meta = document.getElementById("ptmeta").value;
            //----------------------------
            var linea_base = parseFloat(document.getElementById("ptlineabase").value);
            var mes1 = parseFloat(document.getElementById("mes1").value);
            var mes2 = parseFloat(document.getElementById("mes2").value);
            var mes3 = parseFloat(document.getElementById("mes3").value);
            var mes4 = parseFloat(document.getElementById("mes4").value);
            var mes5 = parseFloat(document.getElementById("mes5").value);
            var mes6 = parseFloat(document.getElementById("mes6").value);
            var mes7 = parseFloat(document.getElementById("mes7").value);
            var mes8 = parseFloat(document.getElementById("mes8").value);
            var mes9 = parseFloat(document.getElementById("mes9").value);
            var mes10 = parseFloat(document.getElementById("mes10").value);
            var mes11 = parseFloat(document.getElementById("mes11").value);
            var mes12 = parseFloat(document.getElementById("mes12").value);
            var suma = mes1 + mes2 + mes3 + mes4 + mes5 + mes6 + mes7 + mes8 + mes9 + mes10 + mes11 + mes12 + linea_base;
            var comparar = parseFloat(meta);
            if (suma == comparar) {
                //PREGUNTAR SI ESTA SEGURO DE GUARDAR LA APERTURA
                reset();
                alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        document.form_add_productot.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LA PROGRAMACIÓN MENSUAL + LA LÍNEA BASE DEBE SER IGUAL A LA META<br>" +
                    " SUMA TOTAL = "+suma+" <br> META = "+comparar);
                return false;
            }
        }
    });
    //grafico de PRODUCTO TERMINAL para el modal
    $(".grafico_pterminal").on("click",function(e){
        pt_id = $(this).attr('name');
        var url = site_url+"/programacion/cprog_prod_terminal/get_grafico_pterminal";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "pt_id=" + pt_id
        });
        request.done(function (response, textStatus, jqXHR) {
            $("#tabla_grafico_pt").html(response.tabla);
            $("#linea_base").html(response.linea_base);
            $("#meta").html(response.meta);
            $("#m_codigo").html(response.codigo);
            //GRAFICO ESTADISTICO
            var options = {
                chart: {
                    renderTo: 'graf_pterminal', // div contenedor
                    type: 'line' // tipo de grafico
                },

                title: {
                    text: 'PROGRAMACIÓN MENSUAL PRODUCTO TERMINAL', // titulo del grafico
                    x: -20 //center
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO',
                        'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE']
                },
                yAxis: {
                    title: {
                        text: 'PORCENTAJES (%)'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series: []
            }

            var seriesTotal = {
                data: []
            };
            seriesTotal.name = 'PROGRAMACIÓN ACUMULADA EN %';
            for (x = 0; x < (response.prog_acumulada_p).length; x++) {
                var valor = response.prog_acumulada_p[x];
                seriesTotal.data.push(parseInt(valor));
            }
            options.series.push(seriesTotal);
            var chart = new Highcharts.Chart(options);
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
        e.preventDefault();
    });
    // LLENAR DATOS DE MI MODAL DE INDICADOR DE DESEMPEÑO
    $(".indicador_desem_pt").on("click", function (e) {
        var pt_id = $(this).attr('name');
        document.getElementById("pt_id_desem").value = pt_id;
        url = site_url+"/programacion/cprog_prod_terminal/get_indicador_pt";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "pt_id=" + pt_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: " + response);
            $('#objetivo_texto').html(response.objetivo);
            document.getElementById('modal_ptcodigo').value = response.codigo;
            document.getElementById('modal_pteficacia').value = response.eficacia;
            document.getElementById('modal_ptfinanciera').value = response.financiera;
            document.getElementById('modal_ptejecucion').value = response.ejecucion;
            document.getElementById('modal_ptfisica').value = response.fisica;
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });
    $("#guardar_indicador_pt").on("click", function (e) {
        reset();
        alertify.confirm("REALMENTE DESEA GUARDAR EL INDICADOR DE DESEMPEÑO ?", function (a) {
            if (a) {
                //construir mis datos
                var pt_id = document.getElementById("pt_id_desem").value;
                var eficacia = document.getElementById("modal_pteficacia").value;
                var financiera = document.getElementById("modal_ptfinanciera").value;
                var ejecucion = document.getElementById("modal_ptejecucion").value;
                var fisica = document.getElementById("modal_ptfisica").value;
                url = site_url+"/programacion/cprog_prod_terminal/add_indicador_pt";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: "pt_id=" + pt_id + "&eficacia=" + eficacia + "&financiera=" + financiera + "&ejecucion=" + ejecucion + "&fisica=" + fisica
                });
                request.done(function (response, textStatus, jqXHR) {
                    //console.log("response: " + response);
                    reset();
                    alertify.alert("EL INDICADOR SE REGISTRO CORRECTAMENTE", function (e) {
                        if (e) {
                            window.location.reload(true);
                        }
                    });
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });


            } else {
                // user clicked "cancel"
                alertify.error("OPCIÓN CANCELADA");
            }
        });
        return false;

    });
    <!--=================  SUBIR PDF=========================================-->
    //LIMPIAR MODAL
    $('#pt_modal_cargar_pdf').on('hidden.bs.modal', function () {
        document.forms['ptform_subir_pdf'].reset();
        id_pt_pdf = 0;
    });
    //LLENAR MI FORMULARIO
    $(".pt_pdf").on("click",function(e){
        var pt_id = $(this).attr('name')
        document.getElementById("id_pt_pdf").value = pt_id;
        //---------------------------------------------------
        url = site_url+"/programacion/cprog_prod_terminal/get_form_archivo";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "pt_id=" + pt_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: " + response);
            document.getElementById('codigo_mpdf').value = response.codigo;
            //esta vacio es true
            if((response.respuesta)== 'true'){
                //CASO GUARDAR
                document.getElementById('ptguardar_pdf').disabled= false;//activar el boton guardar
                document.getElementById('ptreemplazar_pdf').disabled= true;//desactivar el boton reeemplazar
                document.getElementById('ptver_pdf').style.pointerEvents = 'none';//desactivar el boton ver pdf
                document.getElementById('ptver_pdf').style.background = '#A4C0A4';
                document.getElementById('mod_eli').value=0;

            }else{
                //caso reeemplazar
                url_pdf = base_url+'archivos/productoTerminal/'+response.ruta;
                document.getElementById('ptguardar_pdf').disabled= true;//descactivar el boton guardar
                document.getElementById('ptreemplazar_pdf').disabled= false;//activar el boton reemplazar
                document.getElementById('ptver_pdf').style.pointerEvents = 'visible';//activar el boton ver
                document.getElementById('ptver_pdf').style.background = '#739E73';
                document.getElementById('ptver_pdf').href = url_pdf;//
                document.getElementById('mod_eli').value= response.ruta;
            }
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });
    //GUARDAR PDF
    $("#ptguardar_pdf").on("click", function (e) {
        var $validator = $("#ptform_subir_pdf").validate({
            rules: {
                userfile: {
                    required: true,
                },
            },
            messages: {
                userfile: {required: "Seleccione un Archivo Pdf"},
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
        var $valid = $("#ptform_subir_pdf").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            archivo = document.getElementById('userfile').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if(parseInt(valor )!= 0 ){
                document.getElementById("load").style.display = 'block';
                document.getElementById('ptguardar_pdf').disabled= true;
                document.forms['ptform_subir_pdf'].submit();
            }
        }

    });
    //MODIFICAR PDF
    $("#ptreemplazar_pdf").on("click",function(){
        var $validator = $("#ptform_subir_pdf").validate({
            rules: {
                userfile: {
                    required: true,
                },
            },
            messages: {
                userfile: {required: "Seleccione un Archivo Pdf"},
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
        var $valid = $("#ptform_subir_pdf").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            archivo = document.getElementById('userfile').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if(parseInt(valor )!= 0 ){
                reset();
                alertify.confirm("REALMENTE DESEA REEMPLAZAR ESTE ARCHIVO?", function (a) {
                    if (a) {
                        document.getElementById("load").style.display = 'block';
                        document.getElementById('ptreemplazar_pdf').disabled= true;
                        document.forms['ptform_subir_pdf'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }


    })
    //VERIFICAR EL ARCHIVO QUE SE GUARDARA
    function comprueba_extension(archivo) {
        extensiones_permitidas = new Array(".jpg", ".doc", ".pdf", ".png", ".JPEG",".xlsx");
        mierror = "";
        //recupero la extensión de este nombre de archivo
        extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
        //compruebo si la extensión está entre las permitidas
        permitida = false;
        for (var i = 0; i < extensiones_permitidas.length; i++) {
            if (extensiones_permitidas[i] == extension) {
                permitida = true;
                break;
            }
        }
        if (!permitida) {
            mierror = "COMPRUEBA LA EXTENSIÓN DE LOS ARCHIVOS A SUBIR. \nSÓLO SE PUEDEN SUBIR ARCHIVOS CON EXTENSIONES: " + extensiones_permitidas.join();
            //si estoy aqui es que no se ha podido submitir
            reset();
            alertify.alert(mierror, function (e) {
                if (e) {
                }
            });
            return 0;
        } else {
            var input = document.getElementById('userfile');
            var file = input.files[0];
            if (file.size > (1024 * 1024 * 10)) {
                mierror = 'EL ARCHIVO NO DEBE SUPERAR LAS 10 Mb';
                reset();
                alertify.alert("EL ARCHIVO NO DEBE SUPERAR LAS 10 Mb", function (e) {
                    if (e) {
                    }
                });
                return 0;
            }
        }
        return 1;

    }
    //MDIFICAR PRODUCTO TERMINAL
    $("#modificar_pterminal").on("click", function (e) {
        var $validator = $("#form_mod_pterminal").validate({
            rules: {
                ptobjetivo: {
                    required: true,
                },
                /*  ptindicador: {
                 required: true,
                 },*/
                tipo_indicador: {
                    required: true,
                },
                uni_unidad: {
                    required: true,
                },
                funcionario: {
                    required: true,
                },
                ptlineabase: {
                    required: true,
                    number: true,
                },
                ptformula: {
                    required: true,
                },
                ptmeta: {
                    required: true,
                    //min: 1,
                    number: true,
                },
                ptponderacion: {
                    number: true,
                    min:0,
                },
                mes1: {
                    required: true,
                    number: true,
                },
                mes2: {
                    required: true,
                    number: true,
                },
                mes3: {
                    required: true,
                    number: true,
                },
                mes4: {
                    required: true,
                    number: true,
                },
                mes5: {
                    required: true,
                    number: true,
                },
                mes6: {
                    required: true,
                    number: true,
                },
                mes7: {
                    required: true,
                    number: true,
                },
                mes8: {
                    required: true,
                    number: true,
                },
                mes9: {
                    required: true,
                    number: true,
                },
                mes10: {
                    required: true,
                    number: true,
                },
                mes11: {
                    required: true,
                    number: true,
                },
                mes12: {
                    required: true,
                    number: true,
                }
            },
            messages: {
                ptobjetivo: {required: "Campo Requerido"},
                ptformula: {required: "Campo Requerido"},
                tipo_indicador: {required: "Campo Requerido"},
                ptindicador: {required: "Campo Requerido"},
                ptlineabase: {required: "Campo Requerido", number: "Ingrese un número"},
                ptmeta: {required: "Campo Requerido", number: "Ingrese un número",min:"La meta debe ser mayor a 1"},
                ptponderacion: {number: "Ingrese un número",min:'El número debe ser mayor igual a 0'},
                uni_unidad: {required: "Campo Requerido"},
                funcionario: {required: "Campo Requerido"},
                mes1: {required: "Campo Requerido", number: "Ingrese un número"},
                mes2: {required: "Campo Requerido", number: "Ingrese un número"},
                mes3: {required: "Campo Requerido", number: "Ingrese un número"},
                mes4: {required: "Campo Requerido", number: "Ingrese un número"},
                mes5: {required: "Campo Requerido", number: "Ingrese un número"},
                mes6: {required: "Campo Requerido", number: "Ingrese un número"},
                mes7: {required: "Campo Requerido", number: "Ingrese un número"},
                mes8: {required: "Campo Requerido", number: "Ingrese un número"},
                mes9: {required: "Campo Requerido", number: "Ingrese un número"},
                mes10: {required: "Campo Requerido", number: "Ingrese un número"},
                mes11: {required: "Campo Requerido", number: "Ingrese un número"},
                mes12: {required: "Campo Requerido", number: "Ingrese un número"}
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
        var $valid = $("#form_mod_pterminal").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            var meta = document.getElementById("ptmeta").value;
            //----------------------------
            var linea_base = parseFloat(document.getElementById("ptlineabase").value);
            var mes1 = parseFloat(document.getElementById("mes1").value);
            var mes2 = parseFloat(document.getElementById("mes2").value);
            var mes3 = parseFloat(document.getElementById("mes3").value);
            var mes4 = parseFloat(document.getElementById("mes4").value);
            var mes5 = parseFloat(document.getElementById("mes5").value);
            var mes6 = parseFloat(document.getElementById("mes6").value);
            var mes7 = parseFloat(document.getElementById("mes7").value);
            var mes8 = parseFloat(document.getElementById("mes8").value);
            var mes9 = parseFloat(document.getElementById("mes9").value);
            var mes10 = parseFloat(document.getElementById("mes10").value);
            var mes11 = parseFloat(document.getElementById("mes11").value);
            var mes12 = parseFloat(document.getElementById("mes12").value);
            var suma = mes1 + mes2 + mes3 + mes4 + mes5 + mes6 + mes7 + mes8 + mes9 + mes10 + mes11 + mes12 + linea_base;
            var comparar = parseFloat(meta);
            if (suma == comparar) {
                //PREGUNTAR SI ESTA SEGURO DE GUARDAR LA APERTURA
                reset();
                alertify.confirm("REALMENTE DESEA MODIFICAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        document.form_mod_pterminal.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LA PROGRAMACIÓN MENSUAL + LA LÍNEA BASE DEBE SER IGUAL A LA META<br>" +
                    " SUMA TOTAL = "+suma+" <br> META = "+comparar);
                return false;
            }
        }
    });
    //ELIMINAR PRODUCTO TERMINAL
    $('.del_pterminal').on("click", function (e) {
        reset();
        var pt_id = $(this).attr('name');
        var request;
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/programacion/cprog_prod_terminal/del_pterminal";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "pt_id=" + pt_id
                });
                request.done(function (data, textStatus, jqXHR) {
                    //console.log("response: "+response);
                    if (data.respuesta == -1) {
                        alertify.error("NO SE PUEDE ELIMINAR ESTE REGISTRO");
                    } else {
                        $("#tr" + data.respuesta).html("");
                        alertify.success("SE ELIMINÓ EL REGISTRO CORRECTAMENTE");
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
                // user clicked "cancel"
                alertify.error("OPCION CANCELADA");
            }

        });
        return false;

    });

});
