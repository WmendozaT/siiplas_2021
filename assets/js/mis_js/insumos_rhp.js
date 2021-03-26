//------------------------- OBTENER MI BASE URL - SITE URL
var base_url = document.getElementById("base_url").value;
var site_url = document.getElementById("site_url").value;
var proyecto_id = document.getElementById("proyecto_id").value;
var act_id = document.getElementById("act_id").value;
//=============================== LISTA RHP===========
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
    var url_rhp = site_url + '/insumos/programacion_insumos/tabla_rhp';
    $.ajax({
        type: "post",
        url: url_rhp,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_rhp").html(data);
        }
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
//---------------global alerta
function reset() {
    $("#toggleCSS").attr("href", base_url + "assets/themes_alerta/alertify.default.css");
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
//-------------------LIMPIAR MI FORMULARIO
$('#modal_nuevo_ins').on('hidden.bs.modal', function () {
    //formaterar mi formulario
    document.forms['form_add_ins'].reset();
    //modificar div
    document.getElementById("div_descripcion").style.display = 'none';
    document.getElementById("div_objetivo").style.display = 'none';
    document.getElementById("div_consultorias").style.display = 'none';
    document.getElementById("div_cpp").style.display = 'none';
    document.getElementById("div_cl").style.display = 'none';
    document.getElementById("div_unidad_medida").style.display = 'none';
    //limpiar cajas de textos
    document.getElementById("ins_costo_unitario").value = 0;
    document.getElementById("ins_costo_total").value = 0;
    document.getElementById("ins_cantidad").value = 0;

    //$('.primero').click();
    //$('#bootstrap-wizard-1').find('.form-wizard').children('li').addClass('active');
    /*$('#tab1').show();
     $('#tab2').hide();
     $('#tab3').hide();*/
    //$('.primero').tab('show')
    /*document.getElementById("tab1").style.display='block';
     document.getElementById("tab2").style.display='none';
     document.getElementById("tab3").style.display='none';*/
    //window.location.reload(true);
    //  window.location.reload(true);
    document.getElementById("ins_sig_add").value = 'Siguiente';

});
//ACTUALIZANDO BOTONES
$("#ins_atras_add").on("click", function (e) {
    document.getElementById("ins_sig_add").value = 'Siguiente';
});
//ACTUALIZAR MI CAJA DE TEXTO COTO MENSUAL
function calcularTotal() {
    var cantidad = esNan(document.getElementById("ins_cantidad").value);
    var costo_unitario = esNan(document.getElementById("ins_costo_unitario").value);
    var costo_total = esNan(parseFloat(cantidad) * parseFloat(costo_unitario));
    document.getElementById("ins_costo_total").value = costo_total.toFixed(2);
    //para la caja que solo muestra el costo total
    document.getElementById("ins_ct").value = costo_total.toFixed(2);
    //  document.getElementById("oi_mostrar_ct2").value = costo_total.toFixed(2);
}
//ACTUALIZAR MI COMBO PARTIDAS HIJO
$("#ins_partidas").change(function () {
    $("#ins_partidas option:selected").each(function () {
        var par_padre = $(this).val();
        url = site_url + '/insumos/programacion_insumos/get_par_hijos';
        $.post(url, {
            par_id: par_padre
        }, function (data) {
            $("#ins_par_hijos").html(data);
        });
    });
});
//SELECT PARA FINANCIAMIENTO  --  FUENTE FINANCIAMIENTO ORGANISMO FINANCIADOR
$("#ins_ff_of").on("click", function (e) {
    var financiamiento = document.getElementById("ins_ff_of").value;
    if (financiamiento.length == 0) {
        document.getElementById("ins_monto_asig").value = '';
    } else {
        //obtener monto asignado
        var url = site_url + '/insumos/insumos/get_monto';
        $.ajax({
            type: "post",
            url: url,
            dataType: 'json',
            data: {
                id: financiamiento,
                proy_id: proyecto_id,
            },
            success: function (datos) {
                document.getElementById("ins_monto_asig").value = datos.ffofet_monto;
            },
        });

    }

});
//GUARDAR INSUMO CON EL PRIMER FINANCIAMIENTO
function guardar_insumo() {
    var tipo_insumo = document.getElementById("tipo_insumo").value;
    var fecha = document.getElementById("ins_fecha").value;
    var detalle = document.getElementById("ins_detalle").value;
    var objetivo = document.getElementById("ins_objetivo").value;
    var duracion = document.getElementById("ins_duracion").value;
    var fecha_inicio = document.getElementById("ins_fecha_inicio").value;
    var fecha_conclusion = document.getElementById("ins_fecha_conclusion").value;
    var productos = document.getElementById("ins_productos").value;
    var evaluador = document.getElementById("ins_evaluador").value;
    var cargo = document.getElementById("ins_cargo").value;
    var actividades = document.getElementById("ins_actividades").value;
    var perfil = document.getElementById("ins_perfil").value;
    var car_id = document.getElementById("ins_car_id").value;
    var unidad_medida = document.getElementById("ins_unidad_medida").value;
    var cantidad = document.getElementById("ins_cantidad").value;
    var costo_unitario = document.getElementById("ins_costo_unitario").value;
    var ins_costo_total = document.getElementById("ins_costo_total").value;
    var partidas = document.getElementById("ins_par_hijos").value;
    var ins_monto_f = document.getElementById("ins_monto_f").value;
    var ins_ff_of = document.getElementById("ins_ff_of").value;// ffofet_id asignado
    var ins_et = document.getElementById("ins_et").value;
    //==========================================================
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
    //============= GUARDAR Y RETORNA EL ID DEL INSUMO ===============
    var url = site_url + '/insumos/programacion_insumos/add_ins';
    $.ajax({
        type: "post",
        url: url,
        //dataType: 'json',
        data: {
            act_id: act_id,
            tipo_insumo: tipo_insumo,
            fecha: fecha,
            detalle: detalle,
            objetivo: objetivo,
            duracion: duracion,
            fecha_inicio: fecha_inicio,
            fecha_conclusion: fecha_conclusion,
            productos: productos,
            evaluador: evaluador,
            cargo: cargo,
            actividades: actividades,
            perfil: perfil,
            car_id: car_id,
            unidad_medida: unidad_medida,
            cantidad: cantidad,
            costo_unitario: costo_unitario,
            costo_total: ins_costo_total,
            partidas: partidas,
            ins_ff_of: ins_ff_of,
            ins_et: ins_et,
            ins_monto_f: ins_monto_f,
            mes1: mes1,
            mes2: mes2,
            mes3: mes3,
            mes4: mes4,
            mes5: mes5,
            mes6: mes6,
            mes7: mes7,
            mes8: mes8,
            mes9: mes9,
            mes10: mes10,
            mes11: mes11,
            mes12: mes12
        },
        success: function (data) {
            var id = data.trim() + '';
            var str = id;
            var n1 = str.indexOf("(");
            id = (str.substr(n1+1)).trim();
            //id = (str.replace(")", " ")).trim();
            document.getElementById("insumo_id").value = id;
        }
    });

}
//GUARDAR FINANCIAMINETO
function guardar_financiamiento(){
    var ins_id = document.getElementById("insumo_id").value;
    var ins_monto_f = document.getElementById("ins_monto_f").value;
    var ins_ff_of = document.getElementById("ins_ff_of").value;// ffofet_id asignado
    var ins_et = document.getElementById("ins_et").value;
    //==========================================================
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
    //============= GUARDAR Y RETORNA EL ID DEL INSUMO ===============
    var url = site_url + '/insumos/programacion_insumos/add_fin';
    $.ajax({
        type: "post",
        url: url,
        data: {
            ins_id:ins_id,
            ins_ff_of: ins_ff_of,
            ins_et: ins_et,
            ins_monto_f: ins_monto_f,
            mes1: mes1,
            mes2: mes2,
            mes3: mes3,
            mes4: mes4,
            mes5: mes5,
            mes6: mes6,
            mes7: mes7,
            mes8: mes8,
            mes9: mes9,
            mes10: mes10,
            mes11: mes11,
            mes12: mes12
        },
        success: function (data) {

        }
    });
}
$(document).ready(function () {
    //variable global
    var primero = 1;
    pageSetUp();
    //VALIDANDO FORMULARIO DE NUEVO INSUMOS RECURSOS HUMANOS PERMANENTE
    /* $("#enviar_rhp").on("click",function(e){*/
    //========================VALIDANDO FORMULARIO===================
    var $validator = $("#form_add_ins").validate({
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
            ins_par_hijos: {
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
                required: "Ingrese la Duracón",
                number: "Ingrese solo Números",
            },
            ins_productos: {required: "Ingrese Producto", maxlength: "Cantidad Máxima de Caracteres (600)"},
            ins_evaluador: {required: "Ingrese Evaluación", maxlength: "Cantidad Máxima de Caracteres (600)"},
            ins_cargo: {required: "Ingrese el Cargo", maxlength: "El campo excedió el tamaño de Caracteres(400)"},
            ins_actividades: {
                required: "Ingrese las Actividades del Consultor",
                maxlength: "El campo excedió el tamaño de Caracteres(700)"
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
            ins_par_hijos: {required: "Campo Requerido"},
            ins_ff_of: {required: "Seleccione Fuente Financiamiento / Organismo Financiador"},
            mes1: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes2: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes3: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes4: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes5: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes6: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes7: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes8: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes9: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes10: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes11: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            mes12: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            ins_monto_f: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
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
    $('#bootstrap-wizard-1').bootstrapWizard({
        'tabClass': 'form-wizard',
        'onNext': function (tab, navigation, index) {
            var $valid = $("#form_add_ins").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            } else {
                //CASO 1 datos de insumos costo total
                if (index == 1) {
                    var total_pfec = parseFloat(document.getElementById("monto_pfec").value);
                    var costo_total = parseFloat(document.getElementById("ins_costo_total").value);
                    //VALIDAR QUE EL COSTO TOTAL SEA MENOR O IGUAL AL MONTO ASIGNADO DEL PFEC
                    if (costo_total <= total_pfec) {
                        /*  $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass(
                         'complete');
                         $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step')
                         .html('<i class="fa fa-check"></i>');*/
                    } else {
                        reset();
                        alertify.alert("EL COSTO TOTAL DEBE SER MENOR O IGUAL AL TOTAL ASIGNADO (" + total_pfec + ")");
                        $("#ins_costo_total").closest('.form-group').removeClass('has-success').addClass('has-error');
                        return false;
                    }
                } else {
                    /* $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass(
                     'complete');
                     $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step')
                     .html('<i class="fa fa-check"></i>');*/
                }
                if (index == 2) {
                    document.getElementById("ins_sig_add").value = 'Guardar';
                }
                if (index == 3) {
                    //verificando que el monto sea menor igual al MONTO ASIGNADO Y AL COSTO TOTAL
                    var monto = parseFloat(document.getElementById("ins_monto_f").value);
                    var costo_total = parseFloat(document.getElementById("ins_ct").value);//costo total del paso 3
                    var monto_asignado = parseFloat(document.getElementById("ins_monto_asig").value);
                    if (monto <= monto_asignado) {
                        //VALIDAR PARA EL ULTIMO MONTO Y  FUENTE FINANCIAMINETO --- ORGANISMO FINANCIADOR
                        var select = document.getElementById("ins_ff_of");
                        var length = select.options.length;
                        if (length == 2) {
                            if (monto == costo_total) {
                                //verificar la suma de la programacion mensual
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
                                var total = parseFloat(document.getElementById("ins_monto_f").value);
                                var suma = mes1 + mes2 + mes3 + mes4 + mes5 + mes6 + mes7 + mes8 + mes9 + mes10 + mes11 + mes12;
                                if (suma == total) {
                                    //------GUARDAR EL INSUMO CON EL PRIMER FINANCIAMINETO SI EL MONTO ES IGUAL AL COSTO TOTAL
                                    if (document.getElementById("primero").value == 1) {
                                        //GUARDAR INSUMO A LA PRIMERA ACCION
                                        guardar_insumo();
                                        //ocultar el boton cerrar hasta que termine el costo total
                                        document.getElementById("cerrar_modal").style.display='none';
                                        //
                                        document.getElementById("primero").value = 0;
                                        reset();
                                        alertify.alert("EL INSUMO SE GUARDO CORRECTAMENTE", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });

                                    } else {
                                        //GUARDAR FINANCIAMIENTO
                                        guardar_financiamiento();
                                        reset();
                                        alertify.alert("SE GUARDO EL FINANCIAMIENTO", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });
                                    }

                                } else {
                                    reset();
                                    alertify.alert("La suma de la Programación Financiera Mensual debe ser igual al Monto");
                                    return false;
                                }
                            } else {
                                //CASO CONTRARIO HACER QUE SEAN IGUALES
                                reset();
                                alertify.alert("EL MONTO DEBE SER IGUAL AL COSTO TOTAL");
                                $("#ins_ct").closest('.form-group').removeClass('has-success').addClass('has-error');
                                return false;
                            }
                        } else {
                            if (monto <= costo_total) {
                                var monto = parseFloat(document.getElementById("ins_monto_f").value);
                                //verificar la suma de la programacion mensual
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
                                var total = parseFloat(document.getElementById("ins_monto_f").value);
                                var suma = mes1 + mes2 + mes3 + mes4 + mes5 + mes6 + mes7 + mes8 + mes9 + mes10 + mes11 + mes12;
                                if (suma == total) {
                                    //------GUARDAR EL INSUMO CON EL PRIMER FINANCIAMINETO SI EL MONTO ES IGUAL AL COSTO TOTAL
                                    if (monto == costo_total) {
                                        // confirmar si desea guardar
                                        reset();
                                        alertify.confirm("ESTA SEGURO DE GUARDAR EL INSUMO", function (e) {
                                            if (e) {
                                                // user clicked "ok"
                                                if (document.getElementById("primero").value == 1) {
                                                    //GUARDAR INSUMO A LA PRIMERA ACCION
                                                    //============================================================
                                                    guardar_insumo();
                                                    document.getElementById("primero").value = 0;
                                                    //ocultar el boton cerrar hasta que termine el costo total
                                                    document.getElementById("cerrar_modal").style.display='none';
                                                    //
                                                    reset();
                                                    alertify.alert("EL INSUMO SE GUARDO CORRECTAMENTE", function (e) {
                                                        if (e) {
                                                            window.location.reload(true);
                                                        }
                                                    });


                                                } else {
                                                    //GUARDAR FINANCIAMIENTO
                                                    guardar_financiamiento()
                                                    reset();
                                                    alertify.alert("SE GUARDO EL FINANCIAMIENTO", function (e) {
                                                        if (e) {
                                                            window.location.reload(true);
                                                        }
                                                    });
                                                }

                                            } else {
                                                // user clicked "cancel"
                                                alertify.error("NO SE GUARDO EL REGISTRO");
                                            }
                                        });
                                    } else {

                                        //GUARDAR Y SEGUIR AÑADIENDO FINANCIAMIENTO SI O SI
                                        // confirmar si desea guardar
                                        reset();
                                        alertify.confirm("ESTA SEGURO DE GUARDAR EL REGISTRO", function (e) {
                                            if (e) {
                                                //EN CASO DE QUE EL MONTO SEA MENOR AL COSTO TOTAL
                                                if (document.getElementById("primero").value == 1) {
                                                    //GUARDAR INSUMO A LA PRIMERA ACCION
                                                    guardar_insumo();
                                                    //ocultar el boton cerrar hasta que termine el costo total
                                                    document.getElementById("cerrar_modal").style.display='none';
                                                    //
                                                    alertify.success("EL INSUMO SE GUARDO CORRECTAMENTE");
                                                    document.getElementById("primero").value = 0;
                                                } else {
                                                    //GUARDAR FINANCIAMIENTO
                                                    guardar_financiamiento()
                                                    alertify.success("SE GUARDO EL FINANCIAMIENTO");
                                                }

                                                //SEGUIR REGISTRANDO EL INSUMO--------------------------------------------------------------------------------
                                                //actualizar mi costo total
                                                var costo_total_actualizado = parseFloat(costo_total) - parseFloat(total);
                                                document.getElementById("ins_ct").value = costo_total_actualizado;
                                                document.getElementById("mes1").value = 0;
                                                document.getElementById("mes2").value = 0;
                                                document.getElementById("mes3").value = 0;
                                                document.getElementById("mes4").value = 0;
                                                document.getElementById("mes5").value = 0;
                                                document.getElementById("mes6").value = 0;
                                                document.getElementById("mes7").value = 0;
                                                document.getElementById("mes8").value = 0;
                                                document.getElementById("mes9").value = 0;
                                                document.getElementById("mes10").value = 0;
                                                document.getElementById("mes11").value = 0;
                                                document.getElementById("mes12").value = 0;
                                                document.getElementById("ins_monto_f").value = 0;
                                                //ACTUALIZAR MI COMBO FUENTE FINANCIAMIENTO Y ORGANISMO FINANCIDOR
                                                var ffofet_id = document.getElementById("ins_ff_of").value;
                                                var x = document.getElementById("ins_ff_of");
                                                x.remove(x.selectedIndex);
                                                //actualizar mi entidad de transferencia
                                                var mySelect = document.getElementById('ins_et');
                                                mySelect.options[0].selected = "true";

                                            } else {
                                                // user clicked "cancel"
                                                alertify.error("NO SE GUARDO EL REGISTRO");
                                            }
                                        });

                                    }

                                    // ----------------------------------------
                                    //----- SI EL MONTO ES MENOR AL

                                } else {
                                    reset();
                                    alertify.alert("La suma de la Programación Financiera Mensual debe ser igual al Monto");
                                    return false;
                                }
                            } else {
                                reset();
                                alertify.alert("EL MONTO DEBE SER MENOR O IGUAL AL COSTO TOTAL");
                                $("#ins_monto_f").closest('.form-group').removeClass('has-success').addClass('has-error');
                                return false;
                            }
                        }
                    } else {
                        reset();
                        alertify.alert("EL MONTO DEBE SER MENOR O IGUAL AL MONTO ASIGNADO");
                        $("#ins_monto_f").closest('.form-group').removeClass('has-success').addClass('has-error');
                        return false;
                    }

                }


            }
        }
    });


})
///============================                  MODIFICAR                   ================================================================================
//ACTUALIZAR MI CAJA DE TEXTO COTO MENSUAL
function modcalcularTotal() {
    var cantidad = esNan(document.getElementById("modins_cantidad").value);
    var costo_unitario = esNan(document.getElementById("modins_costo_unitario").value);
    var costo_total = esNan(parseFloat(cantidad) * parseFloat(costo_unitario));
    document.getElementById("modins_costo_total").value = costo_total.toFixed(2);
    //para la caja que solo muestra el costo total
    document.getElementById("modins_ct").value = costo_total.toFixed(2);
}
//-------------------LIMPIAR MI FORMULARIO
$('#modal_mod_ins').on('hidden.bs.modal', function () {
    //formaterar mi formulario
    document.forms['form_mod_ins'].reset();
    //modificar div
    document.getElementById("moddiv_descripcion").style.display = 'none';
    document.getElementById("moddiv_objetivo").style.display = 'none';
    document.getElementById("moddiv_consultorias").style.display = 'none';
    document.getElementById("moddiv_cpp").style.display = 'none';
    document.getElementById("moddiv_cl").style.display = 'none';
    document.getElementById("moddiv_unidad_medida").style.display = 'none';
    //limpiar cajas de textos
    document.getElementById("modins_costo_unitario").value = 0;
    document.getElementById("modins_costo_total").value = 0;
    document.getElementById("modins_cantidad").value = 0;
    document.getElementById("modins_id").value = 0;
    document.getElementById("modins_sig_add").value = 'Siguiente';
    window.location.reload(true);

});
//ACTUALIZAR MI COMBO PARTIDAS HIJO
$("#modins_partidas").change(function () {
    $("#modins_partidas option:selected").each(function () {
        var par_padre = $(this).val();
        url = site_url + '/insumos/programacion_insumos/get_par_hijos';
        $.post(url, {
            par_id: par_padre
        }, function (data) {
            $("#modins_par_hijos").html(data);
        });
    });
});
//SELECT PARA FINANCIAMIENTO  --  FUENTE FINANCIAMIENTO ORGANISMO FINANCIADOR
$("#modins_ff_of").on("click", function (e) {
    //limpiar=====================
    document.getElementById("modins_monto_f").value = 0;
    document.getElementById("modmes1").value = 0;
    document.getElementById("modmes2").value = 0;
    document.getElementById("modmes3").value = 0;
    document.getElementById("modmes4").value = 0;
    document.getElementById("modmes5").value = 0;
    document.getElementById("modmes6").value = 0;
    document.getElementById("modmes7").value = 0;
    document.getElementById("modmes8").value = 0;
    document.getElementById("modmes9").value = 0;
    document.getElementById("modmes10").value = 0;
    document.getElementById("modmes11").value = 0;
    document.getElementById("modmes12").value = 0;
    //----------------------------------------------------------------------
    var financiamiento = document.getElementById("modins_ff_of").value;
    var modins_id = document.getElementById("modins_id").value;
    if (financiamiento.length == 0) {
        document.getElementById("modins_monto_asig").value = '';
    } else {
        //obtener monto asignado
        var url = site_url + '/insumos/insumos/get_monto';
        $.ajax({
            type: "post",
            url: url,
            dataType: 'json',
            data: {
                id: financiamiento,
                proy_id: proyecto_id,
            },
            success: function (datos) {
                document.getElementById("modins_monto_asig").value = datos.ffofet_monto;
            },
        });
        //================obtener mis datos de mi programacion mensual y monto
        var url = site_url + '/insumos/programacion_insumos/get_progmensual';
        $.ajax({
            type: "post",
            url: url,
            dataType: 'json',
            data: {
                fin_id: financiamiento,
                ins_id: modins_id,
            },
            success: function (datos) {
                document.getElementById("modins_monto_f").value = datos.monto;
                document.getElementById("modmes1").value = datos.enero;
                document.getElementById("modmes2").value = datos.febrero;
                document.getElementById("modmes3").value = datos.marzo;
                document.getElementById("modmes4").value = datos.abril;
                document.getElementById("modmes5").value = datos.mayo;
                document.getElementById("modmes6").value = datos.junio;
                document.getElementById("modmes7").value = datos.julio;
                document.getElementById("modmes8").value = datos.agosto;
                document.getElementById("modmes9").value = datos.septiembre;
                document.getElementById("modmes10").value = datos.octubre;
                document.getElementById("modmes11").value = datos.noviembre;
                document.getElementById("modmes12").value = datos.diciembre;
                document.getElementById("modins_et").value = datos.et_id;

            },
        });
    }

});
$(document).ready(function () {
    //VECTOR DE MIS ID DE MIS FUENTES DE FINANCIAMIENTO
    var vec_id_fin = new Array();

    //variable global
    var modprimero = 1;
    pageSetUp();
    //VALIDANDO FORMULARIO DE NUEVO INSUMOS RECURSOS HUMANOS PERMANENTE
    //========================VALIDANDO FORMULARIO===================
    var $validator = $("#form_mod_ins").validate({
        rules: {
            modins_fecha: {
                required: true,
            },
            modins_detalle: {
                required: true,
                maxlength: 600,
            },
            modins_objetivo: {
                required: true,
                maxlength: 500,
            },
            modins_duracion: {
                required: true,
                maxlength: 9,
                number: true,
            },
            modins_fecha_inicio: {
                required: true,
            },
            modins_fecha_conclusion: {
                required: true,
            },
            modins_productos: {
                required: true,
                maxlength: 600,
            },
            modins_evaluador: {
                required: true,
                maxlength: 600,
            },
            modins_cargo: {
                required: true,
                maxlength: 400,
            },
            modins_actividades: {
                required: true,
                maxlength: 700,
            },
            modins_perfil: {
                required: true,
                maxlength: 300,
            },
            modins_car_id: {
                required: true,
            },
            modins_unidad_medida: {
                required: true,
            },
            modins_cantidad: {
                required: true,
                number: true,
                min: 1,
            },
            modins_costo_unitario: {
                required: true,
                number: true,
                min: 1,
            },
            modins_costo_total: {
                required: true,
                number: true,
                min: 1,
            },
            modins_par_hijos: {
                required: true,
            },
            modins_ff_of: {
                required: true,
            },
            modmes1: {
                required: true,
                number: true,
                min: 0,
            },
            modmes2: {
                required: true,
                number: true,
                min: 0,
            },
            modmes3: {
                required: true,
                number: true,
                min: 0,
            },
            modmes4: {
                required: true,
                number: true,
                min: 0,
            },
            modmes5: {
                required: true,
                number: true,
                min: 0,
            },
            modmes6: {
                required: true,
                number: true,
            },
            modmes7: {
                required: true,
                number: true,
                min: 0,
            },
            modmes8: {
                required: true,
                number: true,
                min: 0,
            },
            modmes9: {
                required: true,
                number: true,
                min: 0,
            },
            modmes10: {
                required: true,
                number: true,
                min: 0,
            },
            modmes11: {
                required: true,
                number: true,
                min: 0,
            },
            modmes12: {
                required: true,
                number: true,
                min: 0,
            },
            modins_monto_f: {
                required: true,
                number: true,
                min: 1,
            },
            modins_et: {
                required: true,
            }
        },
        messages: {
            modins_fecha: {required: "Ingrese la Fecha de Requerimiento"},
            modins_fecha_inicio: {required: "Ingrese la Fecha de Inicio"},
            modins_fecha_conclusion: {required: "Ingrese la Fecha de Conclusion"},
            modins_detalle: {
                required: "Campo Requerido",
                maxlength: "Cantidad Máxima de Caracteres (600)"
            },
            modins_objetivo: {
                required: "Ingrese el Objetivo",
                maxlength: "Cantidad Máxima de Caracteres (500)"
            },
            modins_duracion: {
                required: "Ingrese la Duracón",
                number: "Ingrese solo Números",
            },
            modins_productos: {required: "Ingrese Producto", maxlength: "Cantidad Máxima de Caracteres (600)"},
            modins_evaluador: {required: "Ingrese Evaluación", maxlength: "Cantidad Máxima de Caracteres (600)"},
            modins_cargo: {required: "Ingrese el Cargo", maxlength: "El campo excedió el tamaño de Caracteres(400)"},
            modins_actividades: {
                required: "Ingrese las Actividades del Consultor",
                maxlength: "El campo excedió el tamaño de Caracteres(700)"
            },
            modins_perfil: {
                required: "Ingrese Perfil / Requisitos",
                maxlength: "El campo excedió el tamaño de Caracteres(300)"
            },
            modins_car_id: {required: "Ingrese Cargo Equivalente Escala Salarial"},
            modins_cantidad: {
                required: "Ingrese el Campo",
                number: "Ingrese solo Numeros",
                min: "La Cantidad debe ser mayor a 1",
                max: "La Cantidad debe ser Menor o Igual a 12"
            },
            modins_costo_unitario: {
                required: "Ingrese el Costo",
                number: "Ingrese solo Numeros",
                min: "El Costo Mensual debe ser mayor a 1"
            },
            modins_costo_total: {
                required: "Ingrese el Costo Total",
                number: "Ingrese solo Numeros",
                min: "El Costo Total debe ser mayor a 1"
            },
            modins_unidad_medida: {
                required: "Ingrese Unidad de Medida"
            },
            modins_partidas: {required: "Seleccione la Partida"},
            modins_par_hijos: {required: "Campo Requerido"},
            modins_ff_of: {required: "Seleccione Fuente Financiamiento / Organismo Financiador"},
            modmes1: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes2: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes3: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes4: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes5: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes6: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes7: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes8: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes9: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes10: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes11: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modmes12: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            modins_monto_f: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 1"
            },
            modins_et: {
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
    $('#bootstrap-wizard-2').bootstrapWizard({
        'tabClass': 'form-wizard',
        'onNext': function (tab, navigation, index) {
            var $valid = $("#form_mod_ins").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            } else {
                //CASO 1 datos de insumos costo total
                if (index == 1) {
                    var total_pfec = parseFloat(document.getElementById("monto_pfec").value);
                    var costo_total = parseFloat(document.getElementById("modins_costo_total").value);
                    //VALIDAR QUE EL COSTO TOTAL SEA MENOR O IGUAL AL MONTO ASIGNADO DEL PFEC
                    if (costo_total <= total_pfec) {

                    } else {
                        reset();
                        alertify.alert("EL COSTO TOTAL DEBE SER MENOR O IGUAL AL TOTAL ASIGNADO (" + total_pfec + ")");
                        $("#modins_costo_total").closest('.form-group').removeClass('has-success').addClass('has-error');
                        return false;
                    }
                } else {
                }
                if (index == 2) {
                    document.getElementById("modins_sig_add").value = 'Modificar';
                }
                if (index == 3) {
                    //verificando que el monto sea menor igual al MONTO ASIGNADO Y AL COSTO TOTAL
                    var monto = parseFloat(document.getElementById("modins_monto_f").value);
                    var costo_total = parseFloat(document.getElementById("modins_ct").value);//costo total del paso 3
                    var monto_asignado = parseFloat(document.getElementById("modins_monto_asig").value);
                    if (monto <= monto_asignado) {
                        //VALIDAR PARA EL ULTIMO MONTO Y  FUENTE FINANCIAMINETO --- ORGANISMO FINANCIADOR
                        var select = document.getElementById("modins_ff_of");
                        var length = select.options.length;
                        if (length == 2) {
                            if (monto == costo_total) {
                                //verificar la suma de la programacion mensual
                                var modmes1 = parseFloat(document.getElementById("modmes1").value);
                                var modmes2 = parseFloat(document.getElementById("modmes2").value);
                                var modmes3 = parseFloat(document.getElementById("modmes3").value);
                                var modmes4 = parseFloat(document.getElementById("modmes4").value);
                                var modmes5 = parseFloat(document.getElementById("modmes5").value);
                                var modmes6 = parseFloat(document.getElementById("modmes6").value);
                                var modmes7 = parseFloat(document.getElementById("modmes7").value);
                                var modmes8 = parseFloat(document.getElementById("modmes8").value);
                                var modmes9 = parseFloat(document.getElementById("modmes9").value);
                                var modmes10 = parseFloat(document.getElementById("modmes10").value);
                                var modmes11 = parseFloat(document.getElementById("modmes11").value);
                                var modmes12 = parseFloat(document.getElementById("modmes12").value);
                                var total = parseFloat(document.getElementById("modins_monto_f").value);
                                var suma = modmes1 + modmes2 + modmes3 + modmes4 + modmes5 + modmes6 + modmes7 + modmes8 + modmes9 + modmes10 + modmes11 + modmes12;
                                if (suma == total) {
                                    //------GUARDAR EL INSUMO CON EL PRIMER FINANCIAMINETO SI EL MONTO ES IGUAL AL COSTO TOTAL
                                    if (document.getElementById("modprimero").value == 1) {
                                        //GUARDAR INSUMO A LA PRIMERA ACCION
                                        modificar_insumo();
                                        //ocultar el boton cerrar hasta que termine el costo total
                                        document.getElementById("modcerrar_modal").style.display='none';
                                        //
                                        document.getElementById("modprimero").value = 0;
                                        reset();
                                        alertify.alert("EL INSUMO SE MODIFICO CORRECTAMENTE", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });

                                    } else {
                                        //GUARDAR FINANCIAMIENTO
                                        modificar_financiamiento();
                                        reset();
                                        alertify.alert("SE MODIFICO EL FINANCIAMIENTO", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });
                                    }

                                } else {
                                    reset();
                                    alertify.alert("La suma de la Programación Financiera Mensual debe ser igual al Monto");
                                    return false;
                                }
                            } else {
                                //CASO CONTRARIO HACER QUE SEAN IGUALES
                                reset();
                                alertify.alert("EL MONTO DEBE SER IGUAL AL COSTO TOTAL");
                                $("#modins_ct").closest('.form-group').removeClass('has-success').addClass('has-error');
                                return false;
                            }
                        } else {
                            if (monto <= costo_total) {
                                var monto = parseFloat(document.getElementById("modins_monto_f").value);
                                //verificar la suma de la programacion mensual
                                var modmes1 = parseFloat(document.getElementById("modmes1").value);
                                var modmes2 = parseFloat(document.getElementById("modmes2").value);
                                var modmes3 = parseFloat(document.getElementById("modmes3").value);
                                var modmes4 = parseFloat(document.getElementById("modmes4").value);
                                var modmes5 = parseFloat(document.getElementById("modmes5").value);
                                var modmes6 = parseFloat(document.getElementById("modmes6").value);
                                var modmes7 = parseFloat(document.getElementById("modmes7").value);
                                var modmes8 = parseFloat(document.getElementById("modmes8").value);
                                var modmes9 = parseFloat(document.getElementById("modmes9").value);
                                var modmes10 = parseFloat(document.getElementById("modmes10").value);
                                var modmes11 = parseFloat(document.getElementById("modmes11").value);
                                var modmes12 = parseFloat(document.getElementById("modmes12").value);
                                var total = parseFloat(document.getElementById("modins_monto_f").value);
                                var suma = modmes1 + modmes2 + modmes3 + modmes4 + modmes5 + modmes6 + modmes7 + modmes8 + modmes9 + modmes10 + modmes11 + modmes12;
                                if (suma == total) {
                                    //------GUARDAR EL INSUMO CON EL PRIMER FINANCIAMINETO SI EL MONTO ES IGUAL AL COSTO TOTAL
                                    if (monto == costo_total) {
                                        // confirmar si desea guardar
                                        reset();
                                        alertify.confirm("ESTA SEGURO DE MODIFICAR EL INSUMO ? ", function (e) {
                                            if (e) {
                                                // user clicked "ok"
                                                if (document.getElementById("modprimero").value == 1) {
                                                    //GUARDAR INSUMO A LA PRIMERA ACCION
                                                    //============================================================
                                                    modificar_insumo();
                                                    document.getElementById("modprimero").value = 0;
                                                    //ocultar el boton cerrar hasta que termine el costo total
                                                    document.getElementById("modcerrar_modal").style.display='none';
                                                    //
                                                    reset();
                                                    alertify.alert("EL INSUMO SE MODIFICO CORRECTAMENTE", function (e) {
                                                        if (e) {
                                                            window.location.reload(true);
                                                        }
                                                    });


                                                } else {
                                                    //GUARDAR FINANCIAMIENTO
                                                    modificar_financiamiento()
                                                    reset();
                                                    alertify.alert("SE MODIFICO EL FINANCIAMIENTO", function (e) {
                                                        if (e) {
                                                            window.location.reload(true);
                                                        }
                                                    });
                                                }

                                            } else {
                                                // user clicked "cancel"
                                                alertify.error("NO SE MODIFICO EL REGISTRO");
                                            }
                                        });
                                    } else {

                                        //GUARDAR Y SEGUIR AÑADIENDO FINANCIAMIENTO SI O SI
                                        // confirmar si desea guardar
                                        reset();
                                        alertify.confirm("ESTA SEGURO DE MODIFICAR EL REGISTRO ? ", function (e) {
                                            if (e) {
                                                //EN CASO DE QUE EL MONTO SEA MENOR AL COSTO TOTAL
                                                if (document.getElementById("modprimero").value == 1) {
                                                    //GUARDAR INSUMO A LA PRIMERA ACCION
                                                    modificar_insumo();
                                                    //ocultar el boton cerrar hasta que termine el costo total
                                                    document.getElementById("modcerrar_modal").style.display='none';
                                                    //
                                                    alertify.success("EL INSUMO SE MODIFICO CORRECTAMENTE");
                                                    document.getElementById("modprimero").value = 0;
                                                } else {
                                                    //GUARDAR FINANCIAMIENTO
                                                    modificar_financiamiento()
                                                    alertify.success("SE MODIFICO EL FINANCIAMIENTO");
                                                }

                                                //SEGUIR REGISTRANDO EL INSUMO--------------------------------------------------------------------------------
                                                //actualizar mi costo total
                                                var costo_total_actualizado = parseFloat(costo_total) - parseFloat(total);
                                                document.getElementById("modins_ct").value = costo_total_actualizado;
                                                document.getElementById("modmes1").value = 0;
                                                document.getElementById("modmes2").value = 0;
                                                document.getElementById("modmes3").value = 0;
                                                document.getElementById("modmes4").value = 0;
                                                document.getElementById("modmes5").value = 0;
                                                document.getElementById("modmes6").value = 0;
                                                document.getElementById("modmes7").value = 0;
                                                document.getElementById("modmes8").value = 0;
                                                document.getElementById("modmes9").value = 0;
                                                document.getElementById("modmes10").value = 0;
                                                document.getElementById("modmes11").value = 0;
                                                document.getElementById("modmes12").value = 0;
                                                document.getElementById("modins_monto_f").value = 0;
                                                //ACTUALIZAR MI COMBO FUENTE FINANCIAMIENTO Y ORGANISMO FINANCIDOR
                                                var ffofet_id = document.getElementById("modins_ff_of").value;
                                                var x = document.getElementById("modins_ff_of");
                                                x.remove(x.selectedIndex);
                                                //actualizar mi entidad de transferencia
                                                var mySelect = document.getElementById('modins_et');
                                                mySelect.options[0].selected = "true";

                                            } else {
                                                // user clicked "cancel"
                                                alertify.error("NO SE MODIFICO EL REGISTRO");
                                            }
                                        });

                                    }

                                    // ----------------------------------------
                                    //----- SI EL MONTO ES MENOR AL

                                } else {
                                    reset();
                                    alertify.alert("La suma de la Programación Financiera Mensual debe ser igual al Monto");
                                    return false;
                                }
                            } else {
                                reset();
                                alertify.alert("EL MONTO DEBE SER MENOR O IGUAL AL COSTO TOTAL");
                                $("#modins_monto_f").closest('.form-group').removeClass('has-success').addClass('has-error');
                                return false;
                            }
                        }
                    } else {
                        reset();
                        alertify.alert("EL MONTO DEBE SER MENOR O IGUAL AL MONTO ASIGNADO");
                        $("#modins_monto_f").closest('.form-group').removeClass('has-success').addClass('has-error');
                        return false;
                    }

                }


            }
        }
    });


})
//GUARDAR INSUMO CON EL PRIMER FINANCIAMIENTO
function modificar_insumo() {
    var modins_id = document.getElementById("modins_id").value;
    //GUARDAR A MI VECTOR EL ID ISUMOFINANCIAMIENTO
    vec_id_fin.push(modins_id);
    var fecha = document.getElementById("modins_fecha").value;
    var detalle = document.getElementById("modins_detalle").value;
    var objetivo = document.getElementById("modins_objetivo").value;
    var duracion = document.getElementById("modins_duracion").value;
    var fecha_inicio = document.getElementById("modins_fecha_inicio").value;
    var fecha_conclusion = document.getElementById("modins_fecha_conclusion").value;
    var productos = document.getElementById("modins_productos").value;
    var evaluador = document.getElementById("modins_evaluador").value;
    var cargo = document.getElementById("modins_cargo").value;
    var actividades = document.getElementById("modins_actividades").value;
    var perfil = document.getElementById("modins_perfil").value;
    var car_id = document.getElementById("modins_car_id").value;
    var unidad_medida = document.getElementById("modins_unidad_medida").value;
    var cantidad = document.getElementById("modins_cantidad").value;
    var costo_unitario = document.getElementById("modins_costo_unitario").value;
    var ins_costo_total = document.getElementById("modins_costo_total").value;
    var partidas = document.getElementById("modins_par_hijos").value;
    var ins_monto_f = document.getElementById("modins_monto_f").value;
    var ins_ff_of = document.getElementById("modins_ff_of").value;// ffofet_id asignado
    var ins_et = document.getElementById("modins_et").value;
    //==========================================================
    var mes1 = parseFloat(document.getElementById("modmes1").value);
    var mes2 = parseFloat(document.getElementById("modmes2").value);
    var mes3 = parseFloat(document.getElementById("modmes3").value);
    var mes4 = parseFloat(document.getElementById("modmes4").value);
    var mes5 = parseFloat(document.getElementById("modmes5").value);
    var mes6 = parseFloat(document.getElementById("modmes6").value);
    var mes7 = parseFloat(document.getElementById("modmes7").value);
    var mes8 = parseFloat(document.getElementById("modmes8").value);
    var mes9 = parseFloat(document.getElementById("modmes9").value);
    var mes10 = parseFloat(document.getElementById("modmes10").value);
    var mes11 = parseFloat(document.getElementById("modmes11").value);
    var mes12 = parseFloat(document.getElementById("modmes12").value);
    //============= GUARDAR Y RETORNA EL ID DEL INSUMO ===============
    var url = site_url + '/insumos/programacion_insumos/mod_ins';
    $.ajax({
        type: "post",
        url: url,
        //dataType: 'json',
        data: {
            ins_id: modins_id,
            fecha: fecha,
            detalle: detalle,
            objetivo: objetivo,
            duracion: duracion,
            fecha_inicio: fecha_inicio,
            fecha_conclusion: fecha_conclusion,
            productos: productos,
            evaluador: evaluador,
            cargo: cargo,
            actividades: actividades,
            perfil: perfil,
            car_id: car_id,
            unidad_medida: unidad_medida,
            cantidad: cantidad,
            costo_unitario: costo_unitario,
            costo_total: ins_costo_total,
            partidas: partidas,
            ins_ff_of: ins_ff_of,
            ins_et: ins_et,
            ins_monto_f: ins_monto_f,
            mes1: mes1,
            mes2: mes2,
            mes3: mes3,
            mes4: mes4,
            mes5: mes5,
            mes6: mes6,
            mes7: mes7,
            mes8: mes8,
            mes9: mes9,
            mes10: mes10,
            mes11: mes11,
            mes12: mes12,
        },
        success: function (data) {
            /*            var id = data.trim() + '';
             var str = id;
             var n1 = str.indexOf("(");
             id = (str.substr(n1+1)).trim();
             document.getElementById("insumo_id").value = id;*/
        }
    });

}
//GUARDAR FINANCIAMINETO
function modificar_financiamiento(){
    var ins_id = document.getElementById("insumo_id").value;
    var ins_monto_f = document.getElementById("ins_monto_f").value;
    var ins_ff_of = document.getElementById("ins_ff_of").value;// ffofet_id asignado
    var ins_et = document.getElementById("ins_et").value;
    //==========================================================
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
    //============= GUARDAR Y RETORNA EL ID DEL INSUMO ===============
    var url = site_url + '/insumos/programacion_insumos/add_fin';
    $.ajax({
        type: "post",
        url: url,
        data: {
            ins_id:ins_id,
            ins_ff_of: ins_ff_of,
            ins_et: ins_et,
            ins_monto_f: ins_monto_f,
            mes1: mes1,
            mes2: mes2,
            mes3: mes3,
            mes4: mes4,
            mes5: mes5,
            mes6: mes6,
            mes7: mes7,
            mes8: mes8,
            mes9: mes9,
            mes10: mes10,
            mes11: mes11,
            mes12: mes12
        },
        success: function (data) {

        }
    });
}
//GUARDAR INSUMOS EN MI VECTOR ===============
$(".guardar_id_insumos").on("click",function(e){

});

