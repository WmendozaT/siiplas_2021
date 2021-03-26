//ALTAS BAJAS MODIFICACIONES E INSUMOS
$(function () {
    //-----FUNCION ACCORDION DE INSUMOS
    $('dl dd').not('dt.activo + dd').hide();
    $('dl dt').click(function () {
        if ($(this).hasClass('activo')) {
            $(this).removeClass('activo');
            $(this).next().slideUp();
        } else {
            $('dl dt').removeClass('activo');
            $(this).addClass('activo');
            $('dl dd').slideUp();
            $(this).next().slideDown();
        }
    });
    //----FIN FUNCION ACCORDION DE INSUMOS
    var proy_id = document.getElementById('proy_id').value;
    var prod_id = document.getElementById('prod_id').value;
    var act_id = document.getElementById('act_id').value;
    //----ACCORDION LISTA DE RECURSOS HUMANOS PERMANENTES RHP
    $("#lista_rhp").click(function () {
        //limpiar tablas
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        //------
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_rhp';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_rhp").html(data);
            }
        });
    });
    //----ACCORDION LISTA DE SERVICIOS
    $("#lista_ser").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        //------
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_ser';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_ser").html(data);
            }
        });
    });
    //----ACCORDION LISTA DE PASAJES
    $("#lista_pas").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        //------
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_pas';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_pas").html(data);
            }
        });
    });
    //----ACCORDION LISTA DE VIATICOS
    $("#lista_via").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        //------
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_via';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_via").html(data);
            }
        });
    });
    //----ACCORDION LISTA CONSULTORIA POR PRODUCTO
    $("#lista_cpp").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        //------
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_cpp';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_cpp").html(data);
            }
        });
    });
    //----ACCORDION LISTA CONSULTORIA EN LINEA
    $("#lista_cl").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_cl';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_cl").html(data);
            }
        });
    });
    //----ACCORDION LISTA MATERIALES Y SUMINISTROS
    $("#lista_mat").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_af").html("");
        $("#tabla_oi").html("");
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_mat';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_mat").html(data);
            }
        });
    });
    //----ACCORDION LISTA ACTIVOS FIJOS
    $("#lista_af").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_oi").html("");
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_af';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_af").html(data);
            }
        });
    });
    //----ACCORDION LISTA OTROS INSUMOS
    $("#lista_oi").click(function () {
        //limpiar tablas
        $("#tabla_rhp").html("");
        $("#tabla_ser").html("");
        $("#tabla_pas").html("");
        $("#tabla_via").html("");
        $("#tabla_cpp").html("");
        $("#tabla_cl").html("");
        $("#tabla_mat").html("");
        $("#tabla_af").html("");
        var url_rhp = site_url + '/programacion/cprog_tipo_insumo/tabla_oi';
        $.ajax({
            type: "post",
            url: url_rhp,
            data: {
                proy_id: proy_id, prod_id: prod_id, act_id: act_id
            },
            success: function (data) {
                $("#tabla_oi").html(data);
            }
        });
    });
    //ACTUALIZAR MI COMBO PARTIDAS HIJO
    $("#ins_partidas").change(function () {
        $("#ins_partidas option:selected").each(function () {
            var par_padre = $(this).val();
            url = site_url + '/programacion/cprog_insumos/get_partidas_hijos';
            $.post(url, {
                par_id: par_padre
            }, function (data) {
                $("#ins_partidas_dependientes").html(data);
            });
        });
    });
    //VALIDAR INSUMOS
    $("#ins_enviar").on("click", function (e) {
        if (validar_formulario()) {
            var cant_fin = parseFloat(document.getElementById("cant_fin").value).toFixed(2);//OBTENER LA CANTIDAD DE FINANCIAMIENTOS QUE EXISTEN
            //var costo_total = parseFloat(document.getElementById('ins_costo_total').value).toFixed(2);//costo total del insumo
            var costo_total = esNan(parseFloat(document.getElementById("ins_cantidad").value) * parseFloat(document.getElementById("ins_costo_unitario").value));
            var saldo_total = parseFloat(document.getElementById('saldo_total').value).toFixed(2);//SALDO TOTAL DEL FINANCIAMIENTO DEL PROYECTO
            //VERIFICAR QUE EL COSTO TOTAL SEA MENOR O IGUAL A SALDO TOTAL
            if (costo_total <= saldo_total) {
                //VERIFICAR QUE LA SUMA DE LOS MONTOS ASIGNADOS SEA MENOR O IGUAL AL COSTO TOTAL
                if (costo_total == get_total_monto_asignado(cant_fin)) {
                    //VERIFICAR SI EL MONTO ASIGNADO ES MENOR AL SALDO POR PROGRAMAR
                    var cont = 0;
                    for (i = 1; i <= cant_fin; i++) {
                        monto_asignado = document.getElementById("ins_monto" + i).value;
                        saldo_prog = document.getElementById("saldo_prog" + i).value;
                        if (parseFloat(monto_asignado) <= parseFloat(saldo_prog)) {
                            cont++;
                        } else {
                            reset();
                            alertify.confirm("<h6><b style='color: red'>ERROR!!! FINANCIAMIENTO Nro. " + i + "</b>" +
                                "<BR>EL MONTO ASIGNADO DEBE SER MENOR O IGUAL AL SALDO POR PROGRAMAR" +
                                "<BR>MONTO ASIGNADO = <b style='color: red'>" + parseFloat(monto_asignado).toFixed(2) + "</b>" +
                                "<BR>SALDO POR PROGRAMAR = <b style='color: red'>" + parseFloat(saldo_prog).toFixed(2) + "</b></h6>", function (a) {
                            });
                        }
                    }
                    if (cont == cant_fin) {
                        // SI ES VERDAREO PASAMOS A LA SIGUIENTE VALIDACION DE PROGRAMACION POR MES
                        if (get_verificar_prog_mensual(cant_fin) == cant_fin) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            reset();
                            alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                                if (a) {
                                    //============= GUARDAR DESPUES DE LA VALIDACION ===============
                                      document.ins_form_nuevo.submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    }

                } else {
                    reset();
                    alertify.confirm("<h6><b style='color: red'>ERROR!!!</b>" +
                        "<BR>LA SUMA DE LOS MONTOS ASIGNADOS DEBE SER IGUAL AL COSTO TOTAL" +
                        "<BR>SUMA TOTAL MONTO ASIGNADO= <b style='color: red'>" + total_monto_asignado.toFixed(2) + "</b>" +
                        "<BR>COSTO TOTAL = <b style='color: red'>" + costo_total + "</b></h6>", function (a) {
                    });
                }
            } else {
                reset();
                alertify.confirm("<h6><b style='color: red'>ERROR!!!</b>" +
                    "<BR>EL COSTO TOTAL DEBE SER MENOR AL COSTO ACTIVIDAD" +
                    "<BR>COSTO TOTAL = <b style='color: red'>" + costo_total + "</b>" +
                    "<BR>COSTO ACTIVIDAD = <b style='color: red'>" + saldo_total + "</b></h6>", function (a) {
                });
            }
        }

    });
    //VERIFICAR LA SUMA DE LAS PROGRAMACIONES MENSUALES DEL FINANCIAMIENTO
    function get_verificar_prog_mensual(cant_fin) {
        var cont = 0; //contador para validar si casda programacion del financiamiento
        for (i = 1; i <= cant_fin; i++) {
            mes1 = parseFloat(document.getElementById("mes" + i + '1').value).toFixed(2);
            mes2 = parseFloat(document.getElementById("mes" + i + '2').value).toFixed(2);
            mes3 = parseFloat(document.getElementById("mes" + i + '3').value).toFixed(2);
            mes4 = parseFloat(document.getElementById("mes" + i + '4').value).toFixed(2);
            mes5 = parseFloat(document.getElementById("mes" + i + '5').value).toFixed(2);
            mes6 = parseFloat(document.getElementById("mes" + i + '6').value).toFixed(2);
            mes7 = parseFloat(document.getElementById("mes" + i + '7').value).toFixed(2);
            mes8 = parseFloat(document.getElementById("mes" + i + '8').value).toFixed(2);
            mes9 = parseFloat(document.getElementById("mes" + i + '9').value).toFixed(2);
            mes10 = parseFloat(document.getElementById("mes" + i + '10').value).toFixed(2);
            mes11 = parseFloat(document.getElementById("mes" + i + '11').value).toFixed(2);
            mes12 = parseFloat(document.getElementById("mes" + i + '12').value).toFixed(2);
            monto_asignado = parseFloat(document.getElementById("ins_monto" + i).value).toFixed(2);
            total_mes = (parseFloat(mes1) + parseFloat(mes2) + parseFloat(mes3) + parseFloat(mes4) + parseFloat(mes5) + parseFloat(mes6) +
            parseFloat(mes7) + parseFloat(mes8) + parseFloat(mes9) + parseFloat(mes10) + parseFloat(mes11) + parseFloat(mes12)).toFixed(2);
            if (total_mes == monto_asignado) {
                cont++;
            } else {
                reset();
                alertify.confirm("<h6><b style='color: red'>ERROR!!! EN EL FINANCIAMINETO Nro." + i + " </b>" +
                    "<BR>LA SUMA DE LA PROGRAMACION MENSUAL DEBE SER IGUAL AL MONTO ASIGNADO" +
                    "<BR>SUMA TOTAL = <b style='color: red'>" + total_mes + "</b>" +
                    "<BR>MONTO ASIGNADO = <b style='color: red'>" + monto_asignado + "</b></h6>", function (a) {
                });
            }
        }
        return cont;
    }

    //VERIFICAR LA SUMA TOTAL DEL MONTO ASIGNADO
    function get_total_monto_asignado(cant_fin) {
        total_monto_asignado = 0;
        total_monto_asignado = parseFloat(total_monto_asignado).toFixed(2);
        //VERIFICAR EL TOTAL DEL MONTO ASIGNADO
        for (i = 1; i <= cant_fin; i++) {
            monto_asignado = (parseFloat(document.getElementById("ins_monto" + i).value)).toFixed(2);
            total_monto_asignado = parseFloat(total_monto_asignado) + parseFloat(monto_asignado);
        }
        return total_monto_asignado;
    }

    //VALIDAR EL FORMULARIO DE NUEVO INSUMO
    function validar_formulario() {
        var $validator = $("#ins_form_nuevo").validate({
            rules: {
                ins_fecha: {
                    required: true,
                },
                ins_detalle: {
                    required: true,
                    maxlength: 600,
                },
                ins_objetivo: {
                    required: true,
                    maxlength: 500,
                },
                ins_duracion: {
                    required: true,
                    maxlength: 9,
                    number: true,
                },
                ins_fecha_inicio: {
                    required: true,
                },
                ins_fecha_conclusion: {
                    required: true,
                },
                ins_productos: {
                    required: true,
                    maxlength: 600,
                },
                ins_evaluador: {
                    required: true,
                    maxlength: 600,
                },
                ins_cargo: {
                    required: true,
                    maxlength: 400,
                },
                ins_actividades: {
                    required: true,
                    maxlength: 700,
                },
                ins_perfil: {
                    required: true,
                    maxlength: 300,
                },
                ins_car_id: {
                    required: true,
                },
                ins_unidad_medida: {
                    required: true,
                },
                ins_cantidad: {
                    required: true,
                    number: true,
                    min: 1,
                },
                ins_costo_unitario: {
                    required: true,
                    number: true,
                    min: 1,
                },
                ins_costo_total: {
                    required: true,
                    number: true,
                    min: 1,
                },
                ins_partidas: {
                    required: true,
                },
                ins_partidas_dependientes: {
                    required: true,
                },
                ins_ff_of: {
                    required: true,
                },
                mes1: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes2: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes3: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes4: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes5: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes6: {
                    required: true,
                    number: true,
                },
                mes7: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes8: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes9: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes10: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes11: {
                    required: true,
                    number: true,
                    min: 0,
                },
                mes12: {
                    required: true,
                    number: true,
                    min: 0,
                },
                ins_monto_f: {
                    required: true,
                    number: true,
                    min: 1,
                },
                ins_et: {
                    required: true,
                }
            },
            messages: {
                ins_fecha: {required: "Ingrese la Fecha de Requerimiento"},
                ins_fecha_inicio: {required: "Ingrese la Fecha de Inicio"},
                ins_fecha_conclusion: {required: "Ingrese la Fecha de Conclusion"},
                ins_detalle: {
                    required: "Campo Requerido",
                    maxlength: "Cantidad Máxima de Caracteres (600)"
                },
                ins_objetivo: {
                    required: "Ingrese el Objetivo",
                    maxlength: "Cantidad Máxima de Caracteres (500)"
                },
                ins_duracion: {
                    required: "Ingrese la Duración",
                    number: "Ingrese solo Números",
                },
                ins_productos: {required: "Ingrese Producto", maxlength: "Cantidad Máxima de Caracteres (600)"},
                ins_evaluador: {required: "Ingrese Evaluación", maxlength: "Cantidad Máxima de Caracteres (600)"},
                ins_cargo: {required: "Ingrese el Cargo", maxlength: "El campo excedió el tamño de Caracteres(400)"},
                ins_actividades: {
                    required: "Ingrese las Actividades del Consultor",
                    maxlength: "El campo excedio el tamaño de Caracteres(700)"
                },
                ins_perfil: {
                    required: "Ingrese Perfil / Requisitos",
                    maxlength: "El campo excedió el tamaño de Caracteres(300)"
                },
                ins_car_id: {required: "Ingrese Cargo Equivalente Escala Salarial"},
                ins_cantidad: {
                    required: "Ingrese el Campo",
                    number: "Ingrese solo Numeros",
                    min: "La Cantidad debe ser mayor a 1",
                    max: "La Cantidad debe ser Menor o Igual a 12"
                },
                ins_costo_unitario: {
                    required: "Ingrese el Costo",
                    number: "Ingrese solo Numeros",
                    min: "El Costo Mensual debe ser mayor a 1"
                },
                ins_costo_total: {
                    required: "Ingrese el Costo Total",
                    number: "Ingrese solo Numeros",
                    min: "El Costo Total debe ser mayor a 1"
                },
                ins_unidad_medida: {
                    required: "Ingrese Unidad de Medida"
                },
                ins_partidas: {required: "Seleccione la Partida"},
                ins_partidas_dependientes: {required: "Seleccione la Partida"},
                ins_ff_of: {required: "Seleccione Fuente Financiamiento / Organismo Financiador"},
                mes1: {
                    required: "Campo Requerido",
                    number: "Ingrese solo Números",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes2: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes3: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes4: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes5: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes6: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes7: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes8: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes9: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes10: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes11: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                mes12: {
                    required: "Campo Requerido",
                    number: "Ingrese solo NÃºmeros",
                    min: "El Campo debe ser mayor a o igual a 0"
                },
                ins_monto_f: {
                    required: "Campo Requerido",
                    number: "Ingrese solo Numeros",
                    min: "El Campo debe ser mayor a o igual a 1"
                },
                ins_et: {
                    required: "Seleccione la Entidad de Transferencia",
                }


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
        var $valid = $("#ins_form_nuevo").valid();
        if (!$valid) {
            $validator.focusInvalid();
            return false;
        } else {
            return true;
        }
    }

    //CERRAR INSUMO-------------------------------------------------
    $("#cerrar_ins").on("click", function (e) {
        proy_id = document.getElementById("cerrar_ins").value;
        url = site_url + '/programacion/cprog_insumos/cerrar_insumo';
        $.ajax({
            type: "post",
            url: url,
            dataType: 'json',
            data: {
                proy_id: proy_id
            },
            success: function (data) {
                if (data.respuesta == 1) {
                    reset();
                    alertify.alert("SE CERRÓ LA PROGRAMACIÓN DEL INSUMO CORRECTAMENTE ", function (e) {
                        if (e) {
                            document.getElementById("cerrar_ins").disabled = true;
                            location.href = site_url + "/admin/proy/list_proy";
                        }
                    });
                } else {
                    reset();
                    alertify.error("ERROR AL ENVIAR");
                }

            }
        });
    });


});

//VERIFICAR SI NO EXISTE VALOR global
function esNan(valor) {
    if (isNaN(valor)) {
        return 0;
    } else {
        return valor;
    }
}

//ACTUALIZAR MI CAJA DE TEXTO COTO MENSUAL
function calcularTotal() {
    var cantidad = esNan(document.getElementById("ins_cantidad").value);
    var costo_unitario = esNan(document.getElementById("ins_costo_unitario").value);
    var costo_total = esNan(parseFloat(cantidad) * parseFloat(costo_unitario));
    document.getElementById("ins_costo_total").value = costo_total.toFixed(2);
    //para la caja que solo muestra el costo total
    // document.getElementById("ins_ct").value = costo_total.toFixed(2);
    //  document.getElementById("oi_mostrar_ct2").value = costo_total.toFixed(2);
}









