//------------------------- OBTENER MI BASE URL - SITE URL
var base_url = document.getElementById("base_url").value;
var site_url = document.getElementById("site_url").value;
var act_id = document.getElementById("act_id").value;
//=============================== LISTA RHP===========
$("#lista_rhp").click(function(){
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
function esNan(valor){
    if(isNaN(valor)){
        return 0;
    }else {
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
$('#modal_nuevo_rhp').on('hidden.bs.modal', function () {
    document.forms['form_add_rhp'].reset();
    document.getElementById("rhp_sig_add").value='Siguiente';
});
//ACTUALIZANDO BOTONES
$("#rhp_atras_add").on("click",function(e){
    document.getElementById("rhp_sig_add").value='Siguiente';
});

//ACTUALIZAR MI CAJA DE TEXTO COTO MENSUAL
function calcular() {
    var cantidad_mes = document.getElementById("rhp_cantidad").value;
    var costo_mensual = document.getElementById("rhp_costo_mensual").value;
    var costo_total = parseFloat(costo_mensual) * parseInt(cantidad_mes);
    document.getElementById("rhp_costo_total").value = costo_total.toFixed(2);
    //para la caja que solo muestra el costo total
    document.getElementById("rhp_mostrar_ct1").value = costo_total.toFixed(2);
    document.getElementById("rhp_mostrar_ct2").value = costo_total.toFixed(2);
}

//ACTUALIZAR MI COMBO PARTIDAS HIJO
$("#rhp_partidas").change(function () {
    $("#rhp_partidas option:selected").each(function () {
        var par_padre = $(this).val();
        url = site_url + '/insumos/programacion_insumos/get_par_hijos';
        $.post(url, {
            par_id: par_padre
        }, function (data) {
            $("#rhp_par_hijos").html(data);
        });

        /* $.ajax({
         type:"post",
         url:url,
         data:{par_id:par_padre},
         success:function(data){
         $("#rhp_par_hijos").html(data);
         }
         });*/
    });
});

//---------------- CASO PARA LA TABLA FUENTE FINANCIAMIENT
//$('#f1_rhp').val($(this).is(':checked'));
$('#f1_rhp_ff').click(function () {
    if (this.checked) {
        //desactivar f2
        document.getElementById("rhp_f2_ff").disabled = false;
        document.getElementById("rhp_f2_of").disabled = false;
        document.getElementById("rhp_f2_et").disabled = false;
        document.getElementById("rhp_f2_monto").value = 0;
        document.getElementById("rhp_f2_monto").disabled = false;
    } else {
        ////activar f2
        document.getElementById("rhp_f2_ff").disabled = true;
        document.getElementById("rhp_f2_of").disabled = true;
        document.getElementById("rhp_f2_et").disabled = true;
        document.getElementById("rhp_f2_monto").value = 0;
        document.getElementById("rhp_f2_monto").disabled = true;
    }
});
$('#f2_rhp').click(function () {
    if (this.checked) {
        //desactivar f2
        document.getElementById("rhp_f2_ff").disabled = false;
        document.getElementById("rhp_f2_of").disabled = false;
        document.getElementById("rhp_f2_et").disabled = false;
        document.getElementById("rhp_f2_monto").value = 0;
        document.getElementById("rhp_f2_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                // document.getElementById("rhp_f1_ff").value = data;
                $("#rhp_f2_ff").html(data);
            }
        });
        //------CARGAR SELECT ORGANISMO FINANCIADOR
        var url2 = site_url + '/insumos/programacion_insumos/cargar_of';
        $.ajax({
            type: "post",
            url: url2,
            data: {
                of: 1,
            },
            success: function (data) {
                $("#rhp_f2_of").html(data);
            }
        });
        //------CARGAR SELECT ENTIDAD DE TRANFERENCIA
        var url3 = site_url + '/insumos/programacion_insumos/cargar_et';
        $.ajax({
            type: "post",
            url: url3,
            data: {
                et: 1,
            },
            success: function (data) {
                $("#rhp_f2_et").html(data);
            }
        });
    } else {
        ////desactivar disabled
        document.getElementById("rhp_f2_ff").disabled = true;
        document.getElementById("rhp_f2_of").disabled = true;
        document.getElementById("rhp_f2_et").disabled = true;
        document.getElementById("rhp_f2_monto").value = 0;
        document.getElementById("rhp_f2_monto").disabled = true;
        //limpiar select
        //$("#rhp_f2_ff").html('<option value="">Seleccione una opcion</option>');
        $("#rhp_f2_ff").html('');
        $("#rhp_f2_of").html('');
        $("#rhp_f2_et").html('');
    }

});
$('#f3_rhp').click(function () {
    if (this.checked) {
        //desactivar f3
        document.getElementById("rhp_f3_ff").disabled = false;
        document.getElementById("rhp_f3_of").disabled = false;
        document.getElementById("rhp_f3_et").disabled = false;
        document.getElementById("rhp_f3_monto").value = 0;
        document.getElementById("rhp_f3_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                // document.getElementById("rhp_f1_ff").value = data;
                $("#rhp_f3_ff").html(data);
            }
        });
        //------CARGAR SELECT ORGANISMO FINANCIADOR
        var url2 = site_url + '/insumos/programacion_insumos/cargar_of';
        $.ajax({
            type: "post",
            url: url2,
            data: {
                of: 1,
            },
            success: function (data) {
                $("#rhp_f3_of").html(data);
            }
        });
        //------CARGAR SELECT ENTIDAD DE TRANFERENCIA
        var url3 = site_url + '/insumos/programacion_insumos/cargar_et';
        $.ajax({
            type: "post",
            url: url3,
            data: {
                et: 1,
            },
            success: function (data) {
                $("#rhp_f3_et").html(data);
            }
        });

    } else {
        ////activar f3
        document.getElementById("rhp_f3_ff").disabled = true;
        document.getElementById("rhp_f3_of").disabled = true;
        document.getElementById("rhp_f3_et").disabled = true;
        document.getElementById("rhp_f3_monto").value = 0;
        document.getElementById("rhp_f3_monto").disabled = true;
        //limpiar select
        $("#rhp_f3_ff").html('');
        $("#rhp_f3_of").html('');
        $("#rhp_f3_et").html('');
    }
});
$('#f4_rhp').click(function () {
    if (this.checked) {
        //desactivar f4
        document.getElementById("rhp_f4_ff").disabled = false;
        document.getElementById("rhp_f4_of").disabled = false;
        document.getElementById("rhp_f4_et").disabled = false;
        document.getElementById("rhp_f4_monto").value = 0;
        document.getElementById("rhp_f4_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#rhp_f4_ff").html(data);
            }
        });
        //------CARGAR SELECT ORGANISMO FINANCIADOR
        var url2 = site_url + '/insumos/programacion_insumos/cargar_of';
        $.ajax({
            type: "post",
            url: url2,
            data: {
                of: 1,
            },
            success: function (data) {
                $("#rhp_f4_of").html(data);
            }
        });
        //------CARGAR SELECT ENTIDAD DE TRANFERENCIA
        var url3 = site_url + '/insumos/programacion_insumos/cargar_et';
        $.ajax({
            type: "post",
            url: url3,
            data: {
                et: 1,
            },
            success: function (data) {
                $("#rhp_f4_et").html(data);
            }
        });

    } else {
        ////activar f4
        document.getElementById("rhp_f4_ff").disabled = true;
        document.getElementById("rhp_f4_of").disabled = true;
        document.getElementById("rhp_f4_et").disabled = true;
        document.getElementById("rhp_f4_monto").value = 0;
        document.getElementById("rhp_f4_monto").disabled = true;
        //limpiar select
        $("#rhp_f4_ff").html('');
        $("#rhp_f4_of").html('');
        $("#rhp_f4_et").html('');
    }
});
$('#f5_rhp').click(function () {
    if (this.checked) {
        //desactivar f5
        document.getElementById("rhp_f5_ff").disabled = false;
        document.getElementById("rhp_f5_of").disabled = false;
        document.getElementById("rhp_f5_et").disabled = false;
        document.getElementById("rhp_f5_monto").value = 0;
        document.getElementById("rhp_f5_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#rhp_f5_ff").html(data);
            }
        });
        //------CARGAR SELECT ORGANISMO FINANCIADOR
        var url2 = site_url + '/insumos/programacion_insumos/cargar_of';
        $.ajax({
            type: "post",
            url: url2,
            data: {
                of: 1,
            },
            success: function (data) {
                $("#rhp_f5_of").html(data);
            }
        });
        //------CARGAR SELECT ENTIDAD DE TRANFERENCIA
        var url3 = site_url + '/insumos/programacion_insumos/cargar_et';
        $.ajax({
            type: "post",
            url: url3,
            data: {
                et: 1,
            },
            success: function (data) {
                $("#rhp_f5_et").html(data);
            }
        });
    } else {
        ////activar f5
        document.getElementById("rhp_f5_ff").disabled = true;
        document.getElementById("rhp_f5_of").disabled = true;
        document.getElementById("rhp_f5_et").disabled = true;
        document.getElementById("rhp_f5_monto").value = 0;
        document.getElementById("rhp_f5_monto").disabled = true;
        //limpiar select
        $("#rhp_f5_ff").html('');
        $("#rhp_f5_of").html('');
        $("#rhp_f5_et").html('');
    }
});
//-------------------------------------
$(document).ready(function () {
    pageSetUp();
    //VALIDANDO FORMULARIO DE NUEVO INSUMOS RECURSOS HUMANOS PERMANENTE
    /* $("#enviar_rhp").on("click",function(e){*/
    //========================VALIDANDO FORMULARIO===================
    var $validator = $("#form_add_rhp").validate({
        rules: {
            rhp_fecha: {
                required: true,
            },
            rhp_cantidad: {
                required: true,
                number: true,
                min: 1,
                max: 12,
            },
            rhp_costo_mensual: {
                required: true,
                number: true,
                min: 1,
            },
            rhp_costo_total: {
                required: true,
                number: true,
                min: 1,
            },
            rhp_partidas: {
                required: true,
            },
            rhp_par_hijos: {
                required: true,

            },
            rhp_mes1: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes2: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes3: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes4: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes5: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes6: {
                required: true,
                number: true,
            },
            rhp_mes7: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes8: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes9: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes10: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes11: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_mes12: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_f1_monto: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_f2_monto: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_f3_monto: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_f4_monto: {
                required: true,
                number: true,
                min: 0,
            },
            rhp_f5_monto: {
                required: true,
                number: true,
                min: 0,
            },


        },
        messages: {
            rhp_fecha: {required: "Ingrese la Fecha"},
            rhp_cantidad: {
                required: "Ingrese la Cantidad en Meses",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "La Cantidad debe ser mayor a 1",
                max: "La Cantidad debe ser Menor o Igual a 12"
            },
            rhp_costo_mensual: {
                required: "Ingrese el Costo Mensual",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Costo Mensual debe ser mayor a 1"
            },
            rhp_costo_total: {
                required: "Ingrese el Costo Total",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Costo Total debe ser mayor a 1"
            },
            rhp_partidas: {required: "Seleccione la Partida"},
            rhp_par_hijos: {required: "Campo Requerido"},
            rhp_mes1: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes2: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes3: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes4: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes5: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes6: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes7: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes8: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes9: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes10: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes11: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_mes12: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_f1_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_f2_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_f3_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_f4_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            rhp_f5_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo NÃƒÆ’Ã‚Âºmeros",
                min: "El Campo debe ser mayor a o igual a 0"
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
            var $valid = $("#form_add_rhp").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            } else {
                //VALIDAR PROGRAMACION MENSUAL
                if (index == 3) {
                    //========== VALIDAR SI LA SUMA DE LOS MESES ES IGUAL A LA SUMA TOTAL
                    var rhp_mes1 = parseFloat(document.getElementById("rhp_mes1").value);
                    var rhp_mes2 = parseFloat(document.getElementById("rhp_mes2").value);
                    var rhp_mes3 = parseFloat(document.getElementById("rhp_mes3").value);
                    var rhp_mes4 = parseFloat(document.getElementById("rhp_mes4").value);
                    var rhp_mes5 = parseFloat(document.getElementById("rhp_mes5").value);
                    var rhp_mes6 = parseFloat(document.getElementById("rhp_mes6").value);
                    var rhp_mes7 = parseFloat(document.getElementById("rhp_mes7").value);
                    var rhp_mes8 = parseFloat(document.getElementById("rhp_mes8").value);
                    var rhp_mes9 = parseFloat(document.getElementById("rhp_mes9").value);
                    var rhp_mes10 = parseFloat(document.getElementById("rhp_mes10").value);
                    var rhp_mes11 = parseFloat(document.getElementById("rhp_mes11").value);
                    var rhp_mes12 = parseFloat(document.getElementById("rhp_mes12").value);
                    var rhp_costo_total = parseFloat(document.getElementById("rhp_costo_total").value);
                    var suma = rhp_mes1 + rhp_mes2 + rhp_mes3 + rhp_mes4 + rhp_mes5 + rhp_mes6 + rhp_mes7 + rhp_mes8 + rhp_mes9 + rhp_mes10
                        + rhp_mes11 + rhp_mes12;
                    if (suma == rhp_costo_total) {
                        document.getElementById("rhp_sig_add").value='Guardar';
                        //------CARGAR SELECT FUENTE FINANCIAMIENTO----------------------------------------
                        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
                        $.ajax({
                            type: "post",
                            url: url1,
                            data: {
                                ff: 1,
                            },
                            success: function (data) {
                                $("#rhp_f1_ff").html(data);
                            }
                        });
                        //------CARGAR SELECT ORGANISMO FINANCIADOR
                        var url2 = site_url + '/insumos/programacion_insumos/cargar_of';
                        $.ajax({
                            type: "post",
                            url: url2,
                            data: {
                                of: 1,
                            },
                            success: function (data) {
                                $("#rhp_f1_of").html(data);
                            }
                        });
                        //------CARGAR SELECT ENTIDAD DE TRANFERENCIA
                        var url3 = site_url + '/insumos/programacion_insumos/cargar_et';
                        $.ajax({
                            type: "post",
                            url: url3,
                            data: {
                                et: 1,
                            },
                            success: function (data) {
                                $("#rhp_f1_et").html(data);
                            }
                        });
                        //------------------------------------------------------------------------
                        $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass(
                            'complete');
                        $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step')
                            .html('<i class="fa fa-check"></i>');
                    } else {
                        reset();
                        alertify.alert("La suma de la ProgramaciÃƒÆ’Ã‚Â³n Financiera Mensual debe ser igual al Costo Total");
                        return false;
                    }
                } else {

                    $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass(
                        'complete');
                    $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step')
                        .html('<i class="fa fa-check"></i>');
                }
                //VALIDAR LA SUMA DE FINANCIAMIENTO = COSTO TOTAL
                if (index == 4) {
                    var rhp_monto1 = parseFloat(document.getElementById("rhp_f1_monto").value);
                    var rhp_monto2 = parseFloat(document.getElementById("rhp_f2_monto").value);
                    var rhp_monto3 = parseFloat(document.getElementById("rhp_f3_monto").value);
                    var rhp_monto4 = parseFloat(document.getElementById("rhp_f4_monto").value);
                    var rhp_monto5 = parseFloat(document.getElementById("rhp_f5_monto").value);
                    var rhp_costo_total = parseFloat(document.getElementById("rhp_costo_total").value);
                    var suma_montos = rhp_monto1 + rhp_monto2 + rhp_monto3 + rhp_monto4 + rhp_monto5;
                    //alert(suma_montos + " ct "+ rhp_costo_total)
                    if (rhp_costo_total == suma_montos) {
                        var rhp_fecha = document.getElementById("rhp_fecha").value;
                        var rhp_cantidad = document.getElementById("rhp_cantidad").value;
                        var rhp_costo_mensual = document.getElementById("rhp_costo_mensual").value;
                        var rhp_costo_total = document.getElementById("rhp_costo_total").value;
                        var rhp_partidas = document.getElementById("rhp_partidas").value;
                        var rhp_par_hijos = document.getElementById("rhp_par_hijos").value;
                        //==========================================================
                        var rhp_mes1 = parseFloat(document.getElementById("rhp_mes1").value);
                        var rhp_mes2 = parseFloat(document.getElementById("rhp_mes2").value);
                        var rhp_mes3 = parseFloat(document.getElementById("rhp_mes3").value);
                        var rhp_mes4 = parseFloat(document.getElementById("rhp_mes4").value);
                        var rhp_mes5 = parseFloat(document.getElementById("rhp_mes5").value);
                        var rhp_mes6 = parseFloat(document.getElementById("rhp_mes6").value);
                        var rhp_mes7 = parseFloat(document.getElementById("rhp_mes7").value);
                        var rhp_mes8 = parseFloat(document.getElementById("rhp_mes8").value);
                        var rhp_mes9 = parseFloat(document.getElementById("rhp_mes9").value);
                        var rhp_mes10 = parseFloat(document.getElementById("rhp_mes10").value);
                        var rhp_mes11 = parseFloat(document.getElementById("rhp_mes11").value);
                        var rhp_mes12 = parseFloat(document.getElementById("rhp_mes12").value);
                        //==================================fuente fin
                        var rhp_f1_ff = document.getElementById("rhp_f1_ff").value;
                        var rhp_f1_of = document.getElementById("rhp_f1_of").value;
                        var rhp_f1_et = document.getElementById("rhp_f1_et").value;

                        var rhp_f2_ff = 0;
                        var rhp_f2_of = 0;
                        var rhp_f2_et = 0;

                        var rhp_f3_ff = 0;
                        var rhp_f3_of = 0;
                        var rhp_f3_et = 0;

                        var rhp_f4_ff = 0;
                        var rhp_f4_of = 0;
                        var rhp_f4_et = 0;

                        var rhp_f5_ff = 0;
                        var rhp_f5_of = 0;
                        var rhp_f5_et = 0;

                        if(rhp_monto2 != 0){
                            rhp_f2_ff = document.getElementById("rhp_f2_ff").value;
                            rhp_f2_of = document.getElementById("rhp_f2_of").value;
                            rhp_f2_et = document.getElementById("rhp_f2_et").value;
                        }
                        if(rhp_monto3 != 0){
                            rhp_f3_ff = document.getElementById("rhp_f3_ff").value;
                            rhp_f3_of = document.getElementById("rhp_f3_of").value;
                            rhp_f3_et = document.getElementById("rhp_f3_et").value;
                        }
                        if(rhp_monto4 != 0){
                            rhp_f4_ff = document.getElementById("rhp_f4_ff").value;
                            rhp_f4_of = document.getElementById("rhp_f4_of").value;
                            rhp_f4_et = document.getElementById("rhp_f4_et").value;
                        }
                        if(rhp_monto5 != 0){
                            rhp_f5_ff = document.getElementById("rhp_f5_ff").value;
                            rhp_f5_of = document.getElementById("rhp_f5_of").value;
                            rhp_f5_et = document.getElementById("rhp_f5_et").value;
                        }

                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        var url = site_url + '/insumos/programacion_insumos/add_rhp';
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {
                                act_id: act_id,
                                rhp_fecha: rhp_fecha,
                                rhp_cantidad: rhp_cantidad,
                                rhp_costo_mensual: rhp_costo_mensual,
                                rhp_costo_total: rhp_costo_total,
                                rhp_partidas: rhp_partidas,
                                rhp_par_hijos: rhp_par_hijos,
                                rhp_mes1: rhp_mes1,
                                rhp_mes2: rhp_mes2,
                                rhp_mes3: rhp_mes3,
                                rhp_mes4: rhp_mes4,
                                rhp_mes5: rhp_mes5,
                                rhp_mes6: rhp_mes6,
                                rhp_mes7: rhp_mes7,
                                rhp_mes8: rhp_mes8,
                                rhp_mes9: rhp_mes9,
                                rhp_mes10: rhp_mes10,
                                rhp_mes11: rhp_mes11,
                                rhp_mes12: rhp_mes12,
                                rhp_f1_ff : rhp_f1_ff,
                                rhp_f1_of : rhp_f1_of,
                                rhp_f1_et : rhp_f1_et,
                                rhp_monto1:rhp_monto1,
                                rhp_f2_ff : rhp_f2_ff,
                                rhp_f2_of : rhp_f2_of,
                                rhp_f2_et : rhp_f2_et,
                                rhp_monto2:rhp_monto2,
                                rhp_f3_ff : rhp_f3_ff,
                                rhp_f3_of : rhp_f3_of,
                                rhp_f3_et : rhp_f3_et,
                                rhp_monto3:rhp_monto3,
                                rhp_f4_ff : rhp_f4_ff,
                                rhp_f4_of : rhp_f4_of,
                                rhp_f4_et : rhp_f4_et,
                                rhp_monto4:rhp_monto4,
                                rhp_f5_ff : rhp_f5_ff,
                                rhp_f5_of : rhp_f5_of,
                                rhp_f5_et : rhp_f5_et,
                                rhp_monto5:rhp_monto5
                            },
                            success: function (data) {
                                var verificar = data.trim() + '';
                                if (verificar = 'true') {
                                    $("#modal_nuevo_rhp").css("display", "none");
                                    reset();
                                    alertify.alert("EL REGISTRO SE GUARDÃƒÆ’Ã¢â‚¬Å“ CORRECTAMENTE", function (e) {
                                        if (e) {

                                            document.forms['form_add_rhp'].reset();
                                            // document.form_add_rhp.submit();
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    alert("CADUCO LA SESION");
                                }
                            }
                        });
                    } else {
                        reset();
                        alertify.alert("La suma de los Montos debe ser igual al Costo Total");
                        return false;
                    }
                } else {
                    $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).addClass(
                        'complete');
                    $('#bootstrap-wizard-1').find('.form-wizard').children('li').eq(index - 1).find('.step')
                        .html('<i class="fa fa-check"></i>');
                }
            }
        }
    });
    // fuelux wizard
    var wizard = $('.wizard').wizard();
    wizard.on('finished', function (e, data) {
        //$("#fuelux-wizard").submit();
        //console.log("submitted!");
        $.smallBox({
            title: "Congratulations! Your form was submitted",
            content: "<i class='fa fa-clock-o'></i> <i>1 seconds ago...</i>",
            color: "#5F895F",
            iconSmall: "fa fa-check bounce animated",
            timeout: 4000
        });

    });

})


