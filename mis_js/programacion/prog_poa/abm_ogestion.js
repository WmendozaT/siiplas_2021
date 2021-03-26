$(function () {
    //----------------------------- CASO DEL PDES ------------------------------------------------------
    //FUNCION QUE SIRVE PARA OBTNER EL NOMBRE DE LA UNIDAD ORGANIZACIONAL DEL RESPONSABLE
    $("#funcionario").change(function () {
        $("#funcionario option:selected").each(function () {
            elegido = $(this).val();
            url = site_url + "/programacion/cme_objetivos/get_unidad_org"
            $.ajax({
                type: "post",
                url: url,
                dataType: 'json',
                data: {
                    fun_id: elegido,
                },
                success: function (data) {
                    document.getElementById("uni_unidad").value = data.unidad;
                }
            });
        });
    });
    //-------------------------------  TIPO DE INDICADOR -------------------------------------------
    $("#tipo_indicador").change(function () {
        $("#tipo_indicador option:selected").each(function () {
            elegido = $(this).val();
            if (elegido == 1) {
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
                document.getElementById("oformula").value = ' N/A ';
                document.getElementById("oformula").disabled = true;
                //caracterirsticas
                document.getElementById("relativoa").value = '';
                document.getElementById("relativob").value = '';
                document.getElementById("relativoc").value = '';
                //limpiar para el caso absoluto
                document.getElementById("ometa").value = 0;
                for (var i = 1; i <= 12; i++) {
                    document.getElementById("mes" + i).value = 0;
                }
                document.getElementById("o_denominador").selectedIndex = 0;
            } else {
                //RELATIVO
                //habilitar cajas
                $('#caja_prog_mensual').slideDown();
                $('#caja_relativo').slideDown();
                $('#caja_relativo2').slideDown();
                $('.relativo').slideDown();
                $('#caja_absoluto').slideUp();
                //formula
                document.getElementById("oformula").disabled = false;
                //titulo y textos
                $("#tipo_meta").html(' - % ');
                $("#titulo_indicador").html('INDICADOR RELATIVO');
                document.getElementById("oformula").value = '';
                //caso relativo variable
                document.getElementById("ometa").value = 100;
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
    $("#o_denominador").change(function () {
        $("#o_denominador option:selected").each(function () {
            elegido = $(this).val();
            if (elegido == '1') {
                document.getElementById("ometa").value = 0;
                for (var i = 1; i <= 12; i++) {
                    document.getElementById("mes" + i).value = 0;
                }
            } else {
                document.getElementById("ometa").value = 100;
                var numero = 100 / 12;
                var prog_mes = numero.toFixed(2);
                for (var i = 1; i <= 11; i++) {
                    document.getElementById("mes" + i).value = prog_mes;
                }
                document.getElementById("mes12").value = 8.37;
            }
        });
    });
    //============================ validar y guardar=======================================================
    $("#guardar_og").on("click", function (e) {
        var $validator = $("#form_add_objgestion").validate({
            rules: {
                uni_unidad: {
                    required: true,
                },
                oobjetivo: {
                    required: true,
                },
                funcionario: {
                    required: true,
                },
                tipo_indicador: {
                    required: true,
                },
                /* oindicador: {
                 required: true,
                 },*/
                oformula: {
                    required: true,
                },
                olineabase: {
                    required: true,
                },
                ometa: {
                    required: true,
                    //min: 1,
                },
                mes1: {
                    required: true,
                },
                mes2: {
                    required: true,
                },
                mes3: {
                    required: true,
                },
                mes4: {
                    required: true,
                },
                mes5: {
                    required: true,
                },
                mes6: {
                    required: true,
                },
                mes7: {
                    required: true,
                },
                mes8: {
                    required: true,
                },
                mes9: {
                    required: true,
                },
                mes10: {
                    required: true,
                },
                mes11: {
                    required: true,
                },
                mes12: {
                    required: true,
                }
            },
            messages: {
                uni_unidad: {required: "Campo Requerido"},
                oobjetivo: {required: "Campo Requerido"},
                funcionario: {required: "Campo Requerido"},
                tipo_indicador: {required: "Campo Requerido"},
                oformula: {required: "Campo Requerido"},
                olineabase: {required: "Campo Requerido"},
                ometa: {required: "Campo Requerido", min: "El campo debe ser mayor a 0"},
                oindicador: {required: "Campo Requerido"},
                mes1: {required: "Campo Requerido"},
                mes2: {required: "Campo Requerido"},
                mes3: {required: "Campo Requerido"},
                mes4: {required: "Campo Requerido"},
                mes5: {required: "Campo Requerido"},
                mes6: {required: "Campo Requerido"},
                mes7: {required: "Campo Requerido"},
                mes8: {required: "Campo Requerido"},
                mes9: {required: "Campo Requerido"},
                mes10: {required: "Campo Requerido"},
                mes11: {required: "Campo Requerido"},
                mes12: {required: "Campo Requerido"},

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
        var $valid = $("#form_add_objgestion").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            var meta = document.getElementById("ometa").value;
            //----------------------------
            var linea_base = parseFloat(document.getElementById("olineabase").value);
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
                        document.form_add_objgestion.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LA PROGRAMACIÓN MENSUAL + LA LÍNEA BASE DEBE SER IGUAL A LA META<br>" +
                    " SUMA TOTAL = " + suma + " <br> META = " + comparar);
                return false;
            }
        }
    });
    //grafico de objetivo de gestion para el modal
    $(".graf_ogestion").on("click", function (e) {
        o_id = $(this).attr('name');
        var url = site_url + "/programacion/cprog_objetivo_gestion/get_grafico_ogestion";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "o_id=" + o_id
        });
        request.done(function (response, textStatus, jqXHR) {
            $("#tabla_grafico").html(response.tabla);
            $("#linea_base").html(response.linea_base);
            $("#meta").html(response.meta);
            $("#codigo").html(response.codigo);
            //GRAFICO ESTADISTICO
            var options = {
                chart: {
                    renderTo: 'grafico_objetivo', // div contenedor
                    type: 'line' // tipo de grafico
                },

                title: {
                    text: 'PROGRAMACIÓN MENSUAL OBJETIVO DE GESTIÓN', // titulo del grafico
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
    //MODAL DE INDICADOR DE DESEMPEÑO
    $(".o_indicador_desempenio").click(function () {
        o_id = $(this).attr('name');
        document.getElementById("o_id_desem").value = o_id;
        url = site_url + "/programacion/cprog_objetivo_gestion/get_indicador_ogestion";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "o_id=" + o_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: " + response);
            $('#objetivo_texto').html(response.objetivo);
            document.getElementById('modal_o_codigo').value = response.codigo;
            document.getElementById('modal_o_eficacia').value = response.eficacia;
            document.getElementById('modal_o_financiera').value = response.financiera;
            document.getElementById('modal_o_ejecucion').value = response.ejecucion;
            document.getElementById('modal_o_fisica').value = response.fisica;
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });
    //GUARDAR EL INDICADOR DE DESEMPEÑO DEL OBJETIVO
    $("#guardar_indicador").on("click", function (e) {
        reset();
        alertify.confirm("REALMENTE DESEA GUARDAR EL INDICADOR DE DESEMPEÑO ?", function (a) {
            if (a) {
                //construir mis datos
                var o_id = document.getElementById("o_id_desem").value;
                var eficacia = document.getElementById("modal_o_eficacia").value;
                var financiera = document.getElementById("modal_o_financiera").value;
                var ejecucion = document.getElementById("modal_o_ejecucion").value;
                var fisica = document.getElementById("modal_o_fisica").value;
                url = site_url + "/programacion/cprog_objetivo_gestion/add_indicador_ogestion";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: "o_id=" + o_id + "&eficacia=" + eficacia + "&financiera=" + financiera + "&ejecucion=" + ejecucion + "&fisica=" + fisica
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
                alertify.error("OPCIÓN CANCELADA");
            }
        });
        return false;

    });
    //CASO CARGAR PDF
    <!--=================  SUBIR PDF=========================================-->
    //LIMPIAR MODAL
    $('#modal_cargar_pdf').on('hidden.bs.modal', function () {
        document.forms['oform_subir_pdf'].reset();
        id_o_pdf = 0;
    });
    //LLENAR MI FORMULARIO PDF
    $(".o_pdf").on("click", function (e) {
        var o_id = $(this).attr('name')
        document.getElementById("id_o_pdf").value = o_id;
        //---------------------------------------------------
        url = site_url + "/programacion/cprog_objetivo_gestion/get_form_archivo";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "o_id=" + o_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: " + response);
            document.getElementById('codigo_mpdf').value = response.codigo;
            //esta vacio es true
            if ((response.respuesta) == 'true') {
                //CASO GUARDAR
                document.getElementById('oguardar_pdf').disabled = false;//activar el boton guardar
                document.getElementById('oreemplazar_pdf').disabled = true;//desactivar el boton reeemplazar
                document.getElementById('over_pdf').style.pointerEvents = 'none';//desactivar el boton ver pdf
                document.getElementById('over_pdf').style.background = '#A4C0A4 ';
                document.getElementById('mod_eli').value = 0;

            } else {
                //caso reeemplazar
                url_pdf = base_url + 'archivos/obje_gestion/' + response.ruta;
                document.getElementById('oguardar_pdf').disabled = true;//descactivar el boton guardar
                document.getElementById('oreemplazar_pdf').disabled = false;//activar el boton reemplazar
                document.getElementById('over_pdf').style.pointerEvents = 'visible';//activar el boton ver
                document.getElementById('over_pdf').style.background = '#739E73 ';
                document.getElementById('over_pdf').href = url_pdf;//
                document.getElementById('mod_eli').value = response.ruta;
            }
            // document.getElementById('modal_o_eficacia').value = response.eficacia;
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });
    //GUARDAR PDF
    $("#oguardar_pdf").on("click", function (e) {
        var $validator = $("#oform_subir_pdf").validate({
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
        var $valid = $("#oform_subir_pdf").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            archivo = document.getElementById('userfile').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if (parseInt(valor) != 0) {
                document.getElementById("load").style.display = 'block';
                document.getElementById('oguardar_pdf').disabled = true;
                document.forms['oform_subir_pdf'].submit();
            }
        }

    });
    //MODIFICAR PDF
    $("#oreemplazar_pdf").on("click", function () {
        var $validator = $("#oform_subir_pdf").validate({
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
        var $valid = $("#oform_subir_pdf").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            archivo = document.getElementById('userfile').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if (parseInt(valor) != 0) {
                reset();
                alertify.confirm("REALMENTE DESEA REEMPLAZAR ESTE ARCHIVO?", function (a) {
                    if (a) {
                        document.getElementById("load").style.display = 'block';
                        document.getElementById('oreemplazar_pdf').disabled = true;
                        document.forms['oform_subir_pdf'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }


    })
    //-------------------------------------------------------------------------------
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
    //MODIFICAR OBJETIVO DE GESTION
    $("#og_modificar").on("click", function (e) {
        var $validator = $("#form_mod_ogestion").validate({
            rules: {
                uni_unidad: {
                    required: true,
                },
                oobjetivo: {
                    required: true,
                },
                funcionario: {
                    required: true,
                },
                tipo_indicador: {
                    required: true,
                },
                /*oindicador: {
                 required: true,
                 },*/
                oformula: {
                    required: true,
                },
                olineabase: {
                    required: true,
                },
                ometa: {
                    required: true,
                    // min: 1,
                },
                mes1: {
                    required: true,
                },
                mes2: {
                    required: true,
                },
                mes3: {
                    required: true,
                },
                mes4: {
                    required: true,
                },
                mes5: {
                    required: true,
                },
                mes6: {
                    required: true,
                },
                mes7: {
                    required: true,
                },
                mes8: {
                    required: true,
                },
                mes9: {
                    required: true,
                },
                mes10: {
                    required: true,
                },
                mes11: {
                    required: true,
                },
                mes12: {
                    required: true,
                }
            },
            messages: {
                uni_unidad: {required: "Campo Requerido"},
                oobjetivo: {required: "Campo Requerido"},
                funcionario: {required: "Campo Requerido"},
                tipo_indicador: {required: "Campo Requerido"},
                oformula: {required: "Campo Requerido"},
                olineabase: {required: "Campo Requerido"},
                ometa: {required: "Campo Requerido", min: "El campo debe ser mayor a 0"},
                oindicador: {required: "Campo Requerido"},
                mes1: {required: "Campo Requerido"},
                mes2: {required: "Campo Requerido"},
                mes3: {required: "Campo Requerido"},
                mes4: {required: "Campo Requerido"},
                mes5: {required: "Campo Requerido"},
                mes6: {required: "Campo Requerido"},
                mes7: {required: "Campo Requerido"},
                mes8: {required: "Campo Requerido"},
                mes9: {required: "Campo Requerido"},
                mes10: {required: "Campo Requerido"},
                mes11: {required: "Campo Requerido"},
                mes12: {required: "Campo Requerido"},

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
        var $valid = $("#form_mod_ogestion").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            var meta = document.getElementById("ometa").value;
            //----------------------------
            var linea_base = parseFloat(document.getElementById("olineabase").value);
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
                        document.form_mod_ogestion.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LA PROGRAMACIÓN MENSUAL + LA LÍNEA BASE DEBE SER IGUAL A LA META<br>" +
                    " SUMA TOTAL = " + suma + " <br> META = " + comparar);
                return false;
            }
        }
    });
    //ELIMINAR OBJETIVO DE GESTION
    $('.del_ogestion').on("click", function (e) {
        reset();
        var o_id = $(this).attr('name');
        var request;
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/programacion/cprog_objetivo_gestion/del_ogestion";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "o_id=" + o_id
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
