//=============================== LISTA DE VIATICOS===========
$("#lista_via").click(function(){
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
    var url_via = site_url + '/insumos/programacion_insumos/tabla_via';
    $.ajax({
        type: "post",
        url: url_via,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_via").html(data);
        }
    });
});

//-------------------LIMPIAR MI FORMULARIO
$('#modal_nuevo_via').on('hidden.bs.modal', function(){
    document.forms['form_add_via'].reset();
    document.getElementById("via_sig_add").value = 'Siguiente';
});
//ACTUALIZANDO BOTONES
$("#via_atras_add").on("click", function (e) {
    document.getElementById("via_sig_add").value = 'Siguiente';
});
//ACTUALIZAR MI CAJA DE TEXTO COTO MENSUAL
function calcular_via(){

    var via_dias = esNan(document.getElementById("via_dias").value);
    var via_diario = esNan(document.getElementById("via_diario").value);
    var via_total = esNan(parseFloat(via_dias ) * parseInt(via_diario));
    document.getElementById("via_total").value= via_total.toFixed(2);
    //para la caja que solo muestra el costo total
    document.getElementById("via_mostrar_ct1").value = via_total.toFixed(2);
    document.getElementById("via_mostrar_ct2").value = via_total.toFixed(2);
}
//ACTUALIZAR MI COMBO PARTIDAS HIJO
$("#via_partidas").change(function () {

    $("#via_partidas option:selected").each(function () {
        var par_padre = $(this).val();
        url = site_url + '/insumos/programacion_insumos/get_par_hijos';
        $.post(url, {
            par_id: par_padre
        }, function (data) {
            $("#via_par_hijos").html(data);
        });
    });
});

//---------------- CASO PARA LA TABLA FINANCIAMIENTO
$('#f2_via').click(function () {
    if (this.checked) {
        //desactivar f2
        document.getElementById("via_f2_ff").disabled = false;
        document.getElementById("via_f2_of").disabled = false;
        document.getElementById("via_f2_et").disabled = false;
        document.getElementById("via_f2_monto").value = 0;
        document.getElementById("via_f2_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#via_f2_ff").html(data);
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
                $("#via_f2_of").html(data);
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
                $("#via_f2_et").html(data);
            }
        });
    } else {
        ////activar f2
        document.getElementById("via_f2_ff").disabled = true;
        document.getElementById("via_f2_of").disabled = true;
        document.getElementById("via_f2_et").disabled = true;
        document.getElementById("via_f2_monto").value = 0;
        document.getElementById("via_f2_monto").disabled = true;
        //limpiar select
        $("#via_f2_ff").html('');
        $("#via_f2_of").html('');
        $("#via_f2_et").html('');
    }
});
$('#f3_via').click(function () {
    if (this.checked) {
        //desactivar f3
        document.getElementById("via_f3_ff").disabled = false;
        document.getElementById("via_f3_of").disabled = false;
        document.getElementById("via_f3_et").disabled = false;
        document.getElementById("via_f3_monto").value = 0;
        document.getElementById("via_f3_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#via_f3_ff").html(data);
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
                $("#via_f3_of").html(data);
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
                $("#via_f3_et").html(data);
            }
        });
    } else {
        ////activar f3
        document.getElementById("via_f3_ff").disabled = true;
        document.getElementById("via_f3_of").disabled = true;
        document.getElementById("via_f3_et").disabled = true;
        document.getElementById("via_f3_monto").value = 0;
        document.getElementById("via_f3_monto").disabled = true;
        //limpiar select
        $("#via_f3_ff").html('');
        $("#via_f3_of").html('');
        $("#via_f3_et").html('');
    }
});
$('#f4_via').click(function () {
    if (this.checked) {
        //desactivar f4
        document.getElementById("via_f4_ff").disabled = false;
        document.getElementById("via_f4_of").disabled = false;
        document.getElementById("via_f4_et").disabled = false;
        document.getElementById("via_f4_monto").value = 0;
        document.getElementById("via_f4_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#via_f4_ff").html(data);
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
                $("#via_f4_of").html(data);
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
                $("#via_f4_et").html(data);
            }
        });
    } else {
        ////activar f4
        document.getElementById("via_f4_ff").disabled = true;
        document.getElementById("via_f4_of").disabled = true;
        document.getElementById("via_f4_et").disabled = true;
        document.getElementById("via_f4_monto").value = 0;
        document.getElementById("via_f4_monto").disabled = true;
        //limpiar select
        $("#via_f4_ff").html('');
        $("#via_f4_of").html('');
        $("#via_f4_et").html('');
    }
});
$('#f5_via').click(function () {
    if (this.checked) {
        //desactivar f5
        document.getElementById("via_f5_ff").disabled = false;
        document.getElementById("via_f5_of").disabled = false;
        document.getElementById("via_f5_et").disabled = false;
        document.getElementById("via_f5_monto").value = 0;
        document.getElementById("via_f5_monto").disabled = false;
        //------CARGAR SELECT FUENTE FINANCIAMIENTO
        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
        $.ajax({
            type: "post",
            url: url1,
            data: {
                ff: 1,
            },
            success: function (data) {
                $("#via_f5_ff").html(data);
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
                $("#via_f5_of").html(data);
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
                $("#via_f5_et").html(data);
            }
        });
    } else {
        ////activar f5
        document.getElementById("via_f5_ff").disabled = true;
        document.getElementById("via_f5_of").disabled = true;
        document.getElementById("via_f5_et").disabled = true;
        document.getElementById("via_f5_monto").value = 0;
        document.getElementById("via_f5_monto").disabled = true;
        //limpiar select
        $("#via_f5_ff").html('');
        $("#via_f5_of").html('');
        $("#via_f5_et").html('');
    }
});

//validacion de formulario
$(document).ready(function () {
    pageSetUp();
    //========================VALIDANDO FORMULARIO===================
    var $validator = $("#form_add_via").validate({
        rules: {
            via_fecha: {
                required: true,
            },
            via_destino: {
                required: true,
            },
            via_dias: {
                required: true,
                number:true,
                min:1,
            },
            via_diario: {
                required: true,
                number:true,
                min:1,
            },
            via_total: {
                required: true,
                number:true,
                min:1,
            },
            via_partidas: {
                required: true,
            },
            via_par_hijos: {
                required: true,

            },
            via_mes1: {
                required: true,
                number: true,
                min:0,
            },
            via_mes2: {
                required: true,
                number: true,
                min:0,
            },
            via_mes3: {
                required: true,
                number: true,
                min:0,
            },
            via_mes4: {
                required: true,
                number: true,
                min:0,
            },
            via_mes5: {
                required: true,
                number: true,
                min:0,
            },
            via_mes6: {
                required: true,
                number: true,
            },
            via_mes7: {
                required: true,
                number: true,
                min:0,
            },
            via_mes8: {
                required: true,
                number: true,
                min:0,
            },
            via_mes9: {
                required: true,
                number: true,
                min:0,
            },
            via_mes10: {
                required: true,
                number: true,
                min:0,
            },
            via_mes11: {
                required: true,
                number: true,
                min:0,
            },
            via_mes12: {
                required: true,
                number: true,
                min:0,
            },
            via_f1_monto: {
                required: true,
                number: true,
                min: 0,
            },
            via_f2_monto: {
                required: true,
                number: true,
                min: 0,
            },
            via_f3_monto: {
                required: true,
                number: true,
                min: 0,
            },
            via_f4_monto: {
                required: true,
                number: true,
                min: 0,
            },
            via_f5_monto: {
                required: true,
                number: true,
                min: 0,
            },


        },
        messages: {
            via_fecha:{required:"Ingrese la Fecha"},
            via_destino:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 0"},
            via_dias:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 1"},
            via_diario:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 1"},
            via_total:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 1"},
            via_partidas:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 0"},
            via_par_hijos:{required:"Campo Requerido", number:"Ingrese solo Números",min:"El Campo debe ser mayor a o igual a 0"},
            via_mes1: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes2: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes3: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes4: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes5: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes6: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes7: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes8: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes9: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes10: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes11: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_mes12: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_f1_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_f2_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_f3_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_f4_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
                min: "El Campo debe ser mayor a o igual a 0"
            },
            via_f5_monto: {
                required: "Campo Requerido",
                number: "Ingrese solo Números",
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
    $('#bootstrap-wizard-4').bootstrapWizard({
        'tabClass': 'form-wizard',
        'onNext': function (tab, navigation, index) {
            var $valid = $("#form_add_via").valid();
            if (!$valid) {
                $validator.focusInvalid();
                return false;
            } else {
                //VALIDAR PROGRAMACION MENSUAL
                if (index == 3) {
                    //========== VALIDAR SI LA SUMA DE LOS MESES ES IGUAL A LA SUMA TOTAL
                    var via_mes1 = parseFloat(document.getElementById("via_mes1").value);
                    var via_mes2 = parseFloat(document.getElementById("via_mes2").value);
                    var via_mes3 = parseFloat(document.getElementById("via_mes3").value);
                    var via_mes4 = parseFloat(document.getElementById("via_mes4").value);
                    var via_mes5 = parseFloat(document.getElementById("via_mes5").value);
                    var via_mes6 = parseFloat(document.getElementById("via_mes6").value);
                    var via_mes7 = parseFloat(document.getElementById("via_mes7").value);
                    var via_mes8 = parseFloat(document.getElementById("via_mes8").value);
                    var via_mes9 = parseFloat(document.getElementById("via_mes9").value);
                    var via_mes10 = parseFloat(document.getElementById("via_mes10").value);
                    var via_mes11 = parseFloat(document.getElementById("via_mes11").value);
                    var via_mes12 = parseFloat(document.getElementById("via_mes12").value);
                    var via_total = parseFloat(document.getElementById("via_total").value);
                    var suma = via_mes1+via_mes2+via_mes3+via_mes4+via_mes5+via_mes6+via_mes7+via_mes8+via_mes9+via_mes10
                        +via_mes11+via_mes12;
                    if(suma == via_total){
                        document.getElementById("pas_sig_add").value = 'Guardar';
                        //------CARGAR SELECT FUENTE FINANCIAMIENTO----------------------------------------
                        var url1 = site_url + '/insumos/programacion_insumos/cargar_ff';
                        $.ajax({
                            type: "post",
                            url: url1,
                            data: {
                                ff: 1,
                            },
                            success: function (data) {
                                $("#via_f1_ff").html(data);
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
                                $("#via_f1_of").html(data);
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
                                $("#via_f1_et").html(data);
                            }
                        });
                        //------------------------------------------------------------------------
                        $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).addClass(
                            'complete');
                        $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).find('.step')
                            .html('<i class="fa fa-check"></i>');
                    } else {
                        reset();
                        alertify.alert("La suma de la Programación Financiera Mensual debe ser igual al Costo Total");
                        return false;
                    }
                } else {
                    $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).addClass(
                        'complete');
                    $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).find('.step')
                        .html('<i class="fa fa-check"></i>');

                }
                //VALIDAR LA SUMA DE FINANCIAMIENTO = COSTO TOTAL
                if (index == 4) {

                    var via_monto1 = parseFloat(document.getElementById("via_f1_monto").value);
                    var via_monto2 = parseFloat(document.getElementById("via_f2_monto").value);
                    var via_monto3 = parseFloat(document.getElementById("via_f3_monto").value);
                    var via_monto4 = parseFloat(document.getElementById("via_f4_monto").value);
                    var via_monto5 = parseFloat(document.getElementById("via_f5_monto").value);
                    var via_costo_total = parseFloat(document.getElementById("via_total").value);
                    var suma_montos = via_monto1 + via_monto2 + via_monto3 + via_monto4 + via_monto5;
                    if (via_costo_total == suma_montos) {
                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        var via_fecha = document.getElementById("via_fecha").value;
                        var via_destino = document.getElementById("via_destino").value;
                        var via_dias = document.getElementById("via_dias").value;
                        var via_diario = document.getElementById("via_diario").value;
                        var via_total = document.getElementById("via_total").value;
                        var via_partidas = document.getElementById("via_partidas").value;
                        var via_par_hijos = document.getElementById("via_par_hijos").value;
                        //==========================================================
                        var via_mes1 = parseFloat(document.getElementById("via_mes1").value);
                        var via_mes2 = parseFloat(document.getElementById("via_mes2").value);
                        var via_mes3 = parseFloat(document.getElementById("via_mes3").value);
                        var via_mes4 = parseFloat(document.getElementById("via_mes4").value);
                        var via_mes5 = parseFloat(document.getElementById("via_mes5").value);
                        var via_mes6 = parseFloat(document.getElementById("via_mes6").value);
                        var via_mes7 = parseFloat(document.getElementById("via_mes7").value);
                        var via_mes8 = parseFloat(document.getElementById("via_mes8").value);
                        var via_mes9 = parseFloat(document.getElementById("via_mes9").value);
                        var via_mes10 = parseFloat(document.getElementById("via_mes10").value);
                        var via_mes11 = parseFloat(document.getElementById("via_mes11").value);
                        var via_mes12 = parseFloat(document.getElementById("via_mes12").value);
                        //==================================fuente fin
                        var via_f1_ff = document.getElementById("via_f1_ff").value;
                        var via_f1_of = document.getElementById("via_f1_of").value;
                        var via_f1_et = document.getElementById("via_f1_et").value;

                        var via_f2_ff = 0;
                        var via_f2_of = 0;
                        var via_f2_et = 0;

                        var via_f3_ff = 0;
                        var via_f3_of = 0;
                        var via_f3_et = 0;

                        var via_f4_ff = 0;
                        var via_f4_of = 0;
                        var via_f4_et = 0;

                        var via_f5_ff = 0;
                        var via_f5_of = 0;
                        var via_f5_et = 0;

                        if (via_monto2 != 0) {
                            via_f2_ff = document.getElementById("via_f2_ff").value;
                            via_f2_of = document.getElementById("via_f2_of").value;
                            via_f2_et = document.getElementById("via_f2_et").value;
                        }
                        if (via_monto3 != 0) {
                            via_f3_ff = document.getElementById("via_f3_ff").value;
                            via_f3_of = document.getElementById("via_f3_of").value;
                            via_f3_et = document.getElementById("via_f3_et").value;
                        }
                        if (via_monto4 != 0) {
                            via_f4_ff = document.getElementById("via_f4_ff").value;
                            via_f4_of = document.getElementById("via_f4_of").value;
                            via_f4_et = document.getElementById("via_f4_et").value;
                        }
                        if (via_monto5 != 0) {
                            via_f5_ff = document.getElementById("via_f5_ff").value;
                            via_f5_of = document.getElementById("via_f5_of").value;
                            via_f5_et = document.getElementById("via_f5_et").value;
                        }

                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        var url = site_url + '/insumos/programacion_insumos/add_via';
                        $.ajax({
                            type: "post",
                            url: url,
                            data: {
                                act_id:act_id,
                                via_fecha:via_fecha,
                                via_destino:via_destino,
                                via_dias:via_dias,
                                via_diario:via_diario,
                                via_total:via_total,
                                via_partidas:via_partidas,
                                via_par_hijos:via_par_hijos,
                                via_mes1:via_mes1,
                                via_mes2:via_mes2,
                                via_mes3:via_mes3,
                                via_mes4:via_mes4,
                                via_mes5:via_mes5,
                                via_mes6:via_mes6,
                                via_mes7:via_mes7,
                                via_mes8:via_mes8,
                                via_mes9:via_mes9,
                                via_mes10:via_mes10,
                                via_mes11:via_mes11,
                                via_mes12:via_mes12,
                                via_f1_ff: via_f1_ff,
                                via_f1_of: via_f1_of,
                                via_f1_et: via_f1_et,
                                via_monto1: via_monto1,
                                via_f2_ff: via_f2_ff,
                                via_f2_of: via_f2_of,
                                via_f2_et: via_f2_et,
                                via_monto2: via_monto2,
                                via_f3_ff: via_f3_ff,
                                via_f3_of: via_f3_of,
                                via_f3_et: via_f3_et,
                                via_monto3: via_monto3,
                                via_f4_ff: via_f4_ff,
                                via_f4_of: via_f4_of,
                                via_f4_et: via_f4_et,
                                via_monto4: via_monto4,
                                via_f5_ff: via_f5_ff,
                                via_f5_of: via_f5_of,
                                via_f5_et: via_f5_et,
                                via_monto5: via_monto5
                            },
                            success: function (data) {
                                var verificar = data.trim() + '';
                                if (verificar = 'true') {
                                    $("#modal_nuevo_via").css("display", "none");
                                    reset();
                                    alertify.alert("EL REGISTRO SE GUARDO CORRECTAMENTE", function (e) {
                                        if (e) {

                                            document.forms['form_add_via'].reset();
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
                    $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).addClass(
                        'complete');
                    $('#bootstrap-wizard-4').find('.form-wizard').children('li').eq(index - 1).find('.step')
                        .html('<i class="fa fa-check"></i>');
                }
            }
        }
    });

})



