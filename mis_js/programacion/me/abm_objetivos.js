$(function () {
    //----------------------------- CASO DEL PDES ------------------------------------------------------
    //FUNCION QUE SIRVE PARA OBTNER EL NOMBRE DE LA UNIDAD ORGANIZACIONAL DEL RESPONSABLE
    $("#fun_id").change(function () {
        $("#fun_id option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/get_unidad_org"
            $.ajax({
                type: "post",
                url: url,
                dataType:'json',
                data: {
                    fun_id: elegido,
                },
                success: function (data) {
                    document.getElementById("unidad").value = data.unidad;
                }
            });
        });
    });
    //FUNCION QUE SIRVE PARA OBTNER LAS METAS A PARTIR DEL CODIGO DE PILAR
    $("#pedes1").change(function () {
        $("#pedes1 option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/lista_combo_pdes"
            $.post(url, {
                elegido: elegido
            }, function (data) {
                $("#pedes2").html(data);
                document.getElementById("pedes3").value='';
                document.getElementById("pedes4").value='';
            });
        });
    });
    //FUNCION QUE SIRVE PARA OBTNER LOS RESULTADOS A PARTIR DEL CODIGO DE METAS
    $("#pedes2").change(function () {
        $("#pedes2 option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/lista_combo_pdes"
            $.post(url, {
                elegido: elegido
            }, function (data) {
                $("#pedes3").html(data);
                document.getElementById("pedes4").value='';
            });
        });
    });
    //FUNCION QUE SIRVE PARA OBTNER LAS ACCIONES A PARTIR DEL CODIGO DE RESULTAOD
    $("#pedes3").change(function () {
        $("#pedes3 option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/lista_combo_pdes"
            $.post(url, {
                elegido: elegido,
                ultimo:1,
            }, function (data) {
                $("#pedes4").html(data);
            });
        });
    });
    //---------------------------------- FIN DEL CASO PDES -----------------------------------------
    //---------------------------------- CASO PTDI ----------------------------------------------
    //FUNCION QUE SIRVE PARA OBTNER LAS METAS A PARTIR DEL CODIGO DE PILAR
    $("#ptdi1").change(function () {
        $("#ptdi1 option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/lista_combo_ptdi"
            $.post(url, {
                elegido: elegido
            }, function (data) {
                $("#ptdi2").html(data);
                document.getElementById("ptdi3").value='';
            });
        });
    });
    //FUNCION QUE SIRVE PARA OBTNER LOS RESULTADOS A PARTIR DEL CODIGO DE METAS
    $("#ptdi2").change(function () {
        $("#ptdi2 option:selected").each(function () {
            elegido = $(this).val();
            url = site_url+"/programacion/cme_objetivos/lista_combo_ptdi"
            $.post(url, {
                elegido: elegido,
                ultimo:1
            }, function (data) {
                $("#ptdi3").html(data);
            });
        });
    });
    //------------------------------- FIN DEL CASO PTDI ----------------------------
    //-------------------------------  TIPO DE INDICADOR -------------------------------------------
    $("#tipo_i").change(function () {
        $("#tipo_i option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                //ABSOLUTO
                $('#caja_indicador').slideDown();
                $('#rel').slideUp();
                $('#formula').slideUp();
                $('#caja_denominador').slideUp();
                $('#titulo_indicador').html(' ABSOLUTO ');
                $('#porc1').html('');
                $('#porc2').html('');
                $('#porc3').html('');
                $('#porc4').html('');
                $('#porc5').html('');
                $('#por_meta').html('');
            }else{
                //RELATIVO
                $('#caja_indicador').slideDown();
                $('#rel').slideDown();
                $('#formula').slideDown();
                $('#caja_denominador').slideDown();
                $('#titulo_indicador').html(' RELATIVO ');
                $('#porc1').html(' - %');
                $('#porc2').html(' - %');
                $('#porc3').html(' - %');
                $('#porc4').html(' - %');
                $('#porc5').html(' - %');
                $('#por_meta').html(' - %');

            }


        });
    });
    //------------------------------- GUARDAR OBJETIVO ESTRATEGICO ------------------------------
    //============================ validar y guardar
    $("#enviar_obj").on("click", function (e) {
        var $validator = $("#form_nuevo_obj").validate({
            rules: {
                fun_id: {
                    required: true,
                },
                unidad: {
                    required: true,
                },
                pedes1: {
                    required: true,
                },
                pedes2: {
                    required: true,
                },
                pedes3: {
                    required: true,
                },
                pedes4: {
                    required: true,
                },
              /*  ptdi1: {
                    required: true,
                },
                ptdi2: {
                    required: true,
                },
                ptdi3: {
                    required: true,
                },
                ptdi4: {
                    required: true,
                },*/
                obj: {
                    required: true,
                },
                tipo_i: {
                    required: true,
                },
              /*  indicador: {
                    required: true,
                },*/
                meta: {
                    required: true,
                }

            },
            messages: {
                fun_id: {required: "Campo Requerido"},
                unidad: {required: "Campo Requerido"},
                pedes1: {required: "Campo Requerido"},
                pedes2: {required: "Campo Requerido"},
                pedes3: {required: "Campo Requerido"},
                pedes4: {required: "Campo Requerido"},
                ptdi1: {required: "Campo Requerido"},
                ptdi2: {required: "Campo Requerido"},
                ptdi3: {required: "Campo Requerido"},
                ptdi4: {required: "Campo Requerido"},
                obj: {required: "Campo Requerido"},
                tipo_i: {required: "Campo Requerido"},
                indicador: {required: "Campo Requerido"},
                meta: {required: "Campo Requerido"}
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
        var $valid = $("#form_nuevo_obj").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            meta = document.getElementById("meta").value;
            linea_base = parseFloat(document.getElementById("lb").value);
            //---------------------------- gestion
            gestion1 = parseFloat(document.getElementById("g1").value);
            gestion2 = parseFloat(document.getElementById("g2").value);
            gestion3 = parseFloat(document.getElementById("g3").value);
            gestion4 = parseFloat(document.getElementById("g4").value);
            gestion5 = parseFloat(document.getElementById("g5").value);
            //----------------------------------------------------------
            suma = gestion1 + gestion2 + gestion3 + gestion4 + gestion5 + linea_base;
            comparar = parseFloat(meta);
            if (suma == comparar) {
                //PREGUNTAR SI ESTA SEGURO DE GUARDAR LA APERTURA
                reset();
                alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        document.form_nuevo_obj.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LAS CIFRAS PROGRAMADAS + LA LINEA BASE,DEBE SER IGUAL A LA META <br>SUMA TOTAL = "+suma+" <br> META = "+meta);
                return false;
            }
        }
    });
    //-------------------------------------------------------------------------------------------------
    //------------------- GRAFICO OBJETIVO ESTRATEGICO ----------------------------
    $(".grafico_objetivo").on("click",function(e){
        obje_id = $(this).attr('name');
        var url = site_url+"/programacion/cme_objetivos/get_grafico_obje";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "obje_id=" + obje_id
        });
        request.done(function (response, textStatus, jqXHR) {
            $("#tabla_grafico").html(response.tabla);
            $("#linea_base").html(response.linea_base);
            $("#meta").html(response.meta);
            gestion = parseInt(response.gestion);
            //GRAFICO ESTADISTICO
            var options = {
                chart: {
                    renderTo: 'grafico_objetivo', // div contenedor
                    type: 'line' // tipo de grafico
                },

                title: {
                    text: 'PROGRAMACIÓN OBJETIVO ESTRATÉGICO', // titulo del grafico
                    x: -20 //center
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    categories: [gestion, (gestion+1),(gestion+2) ,(gestion+3),(gestion+4)]
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

    <!--=================  SUBIR PDF=========================================-->
    //LIMPIAR MODAL
    $('#obje_modal_cargar_pdf').on('hidden.bs.modal', function () {
        document.forms['objeform_subir_pdf'].reset();
        id_pt_pdf = 0;
    });
    //LLENAR MI FORMULARIO
    $(".obje_pdf").on("click",function(e){
        var obje_id = $(this).attr('name')
        document.getElementById("id_obje_pdf").value = obje_id;
        //---------------------------------------------------
        url = site_url+"/programacion/cme_objetivos/get_form_archivo";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: "obje_id=" + obje_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: " + response);
            document.getElementById('codigo_mpdf').value = response.codigo;
            //esta vacio es true
            if((response.respuesta)== 'true'){
                //CASO GUARDAR
                document.getElementById('objeguardar_pdf').disabled= false;//activar el boton guardar
                document.getElementById('objereemplazar_pdf').disabled= true;//desactivar el boton reeemplazar
                document.getElementById('objever_pdf').style.pointerEvents = 'none';//desactivar el boton ver pdf
                document.getElementById('objever_pdf').style.background = '#A4C0A4';
                document.getElementById('mod_eli').value=0;

            }else{
                //caso reeemplazar
                url_pdf = base_url+'archivos/oestrategicos/'+response.ruta;
                document.getElementById('objeguardar_pdf').disabled= true;//descactivar el boton guardar
                document.getElementById('objereemplazar_pdf').disabled= false;//activar el boton reemplazar
                document.getElementById('objever_pdf').style.pointerEvents = 'visible';//activar el boton ver
                document.getElementById('objever_pdf').style.background = '#739E73 ';
                document.getElementById('objever_pdf').href = url_pdf;//
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
    $("#objeguardar_pdf").on("click", function (e) {
        var $validator = $("#objeform_subir_pdf").validate({
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
        var $valid = $("#objeform_subir_pdf").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            archivo = document.getElementById('userfile').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if(parseInt(valor )!= 0 ){
                document.getElementById("load").style.display = 'block';
                document.getElementById('objeguardar_pdf').disabled= true;
                document.forms['objeform_subir_pdf'].submit();
            }
        }

    });
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
    //============================ validar y modficar =======================================
    $("#modenviar_obj").on("click", function (e) {
        var $validator = $("#form_mod_obj").validate({
            rules: {
                fun_id: {
                    required: true,
                },
                unidad: {
                    required: true,
                },
                pedes1: {
                    required: true,
                },
                pedes2: {
                    required: true,
                },
                pedes3: {
                    required: true,
                },
                pedes4: {
                    required: true,
                },
               /* ptdi1: {
                    required: true,
                },
                ptdi2: {
                    required: true,
                },
                ptdi3: {
                    required: true,
                },
                ptdi4: {
                    required: true,
                },*/
                obj: {
                    required: true,
                },
                tipo_i: {
                    required: true,
                },
              /*  indicador: {
                    required: true,
                },*/
                meta: {
                    required: true,
                }

            },
            messages: {
                fun_id: {required: "Campo Requerido"},
                unidad: {required: "Campo Requerido"},
                pedes1: {required: "Campo Requerido"},
                pedes2: {required: "Campo Requerido"},
                pedes3: {required: "Campo Requerido"},
                pedes4: {required: "Campo Requerido"},
               /* ptdi1: {required: "Campo Requerido"},
                ptdi2: {required: "Campo Requerido"},
                ptdi3: {required: "Campo Requerido"},
                ptdi4: {required: "Campo Requerido"},*/
                obj: {required: "Campo Requerido"},
                tipo_i: {required: "Campo Requerido"},
                indicador: {required: "Campo Requerido"},
                meta: {required: "Campo Requerido"}
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
        var $valid = $("#form_mod_obj").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            meta = document.getElementById("meta").value;
            linea_base = parseFloat(document.getElementById("lb").value);
            //---------------------------- gestion
            gestion1 = parseFloat(document.getElementById("g1").value);
            gestion2 = parseFloat(document.getElementById("g2").value);
            gestion3 = parseFloat(document.getElementById("g3").value);
            gestion4 = parseFloat(document.getElementById("g4").value);
            gestion5 = parseFloat(document.getElementById("g5").value);
            //----------------------------------------------------------
            suma = gestion1 + gestion2 + gestion3 + gestion4 + gestion5 + linea_base;
            comparar = parseFloat(meta);
            if (suma == comparar) {
                //PREGUNTAR SI ESTA SEGURO DE GUARDAR LA APERTURA
                reset();
                alertify.confirm("REALMENTE DESEA MODIFICAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        document.form_mod_obj.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            } else {
                reset();
                alertify.alert("LA SUMA DE LAS CIFRAS PROGRAMADAS + LA LINEA BASE,DEBE SER IGUAL A LA META <br>SUMA TOTAL = "+suma+" <br> META = "+meta);
                return false;
            }
        }
    });
    //ELIMINAR OBJETIVO ESTRATEGICO
    $(".del_obje").on("click",function(e){
        reset();
        var obje_id = $(this).attr('name');
        var request;
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/programacion/cme_objetivos/del_obje";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "obje_id="+obje_id
                });
                request.done(function (data, textStatus, jqXHR) {
                    //console.log("response: "+response);
                    if(data.respuesta == -1 ){
                        alertify.error("NO SE `PUEDE ELIMINAR ESTE REGISTRO");
                    }else{
                        $("#tr" + data.respuesta).html("");
                        alertify.success("SE ELIMINÓ EL REGISTRO CORRECTAMENTE");
                    }
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: "+ textStatus);
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
