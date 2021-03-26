/////////////////////////////////////////adicionar /////////////////////
 $(function () {
        $('#modal_nuevo_et').on('hidden.bs.modal', function () {
            document.forms['form_et'].reset();
        });
        $("#enviar_et").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_et").validate({
                //////////////// DATOS GENERALES
                rules: {
                    etdescripcion: { //// Programa
                        required: true,
                    },
                    etsigla: { //// Programa
                        required: true,
                    },
                    etcodigo: { //// Programa
                        required: true,
                        number: true,
                        min: 1,
                    },
                    etgestion: { //// Programa
                        required: true,
                        number: true,
                        max: 2030,
                        min: 2000,
                    }
                },
                messages: {
                    etdescripcion: "Ingrese la descripcion",
                    etsigla: "Ingrese la sigla",
                    etcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                    etgestion: {
                        required: "Ingrese la gestión",
                        number: "Dato Inválido",
                        max: "El dato debe ser menor o igual al año 2030",
                        min: "El dato debe ser mayor o igual al año 2000"
                    },
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
            var $valid = $("#form_et").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var etdescripcion = document.getElementById("etdescripcion").value;
                var etsigla = document.getElementById("etsigla").value;
                var etcodigo = document.getElementById("etcodigo").value;
                var etgestion = document.getElementById("etgestion").value;
                //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
                var url =  base_url+"index.php/admin/entidad_transferencia_ver";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {et_codigo: etcodigo, et_gestion: etgestion},
                    success: function (datos) {
                        
                        if (datos == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            
                            var url =  base_url+"index.php/admin/entidad_transferencia_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {
                                    etdescripcion: etdescripcion,
                                    etsigla: etsigla,
                                    etcodigo: etcodigo,
                                    etgestion: etgestion
                                },
                                success: function (data) {
                                    if (data == 'true') {
                                        window.location.reload(true);
                                    } else {
                                        /*alert(data);*/
                                        window.location.reload(true);
                                    }
                                }
                            });
                        } else {
                            $("#etcodigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE ENTIDAD TRANSFERENCIA YA EXISTE");
                        }
                    }
                });
            }
        });
    });
////////////////////////////////////////////////modificar///////////////////////////////////////////////
$(function () {
        //limpiar variable
        var id_et = '';
        $(".mod_et").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA
            id_et = $(this).attr('name');
            var url =  base_url+"index.php/admin/entidad_transferencia_mod";
            var codigo = '';
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_et=" + id_et
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("mod_etdescripcion").value = response.et_descripcion;
                document.getElementById("mod_etsigla").value = response.et_sigla;
                document.getElementById("mod_etcodigo").value = response.et_codigo;
                document.getElementById("mod_etgestion").value = response.et_gestion;
                codigo = response.et_codigo;
            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
            $("#mod_etenviar").on("click", function (e) {
                var $validator = $("#mod_formet").validate({
                    rules: {
                        mod_etdescripcion: {
                            required: true,
                        },
                        mod_etsigla: {
                            required: true,
                        },
                        mod_etcodigo: {
                            required: true,
                            number: true,
                            min: 1,
                        },
                        mod_etgestion: {
                            required: true,
                            number: true,
                            max: 2030,
                            min: 2000,
                        }
                    },
                    messages: {
                        mod_etdescripcion: "Ingrese la descripcion",
                        mod_etsigla: "Ingrese la sigla",
                        mod_etcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                        mod_etgestion: {
                            required: "Ingrese la gestión",
                            number: "Dato Inválido",
                            max: "El dato debe ser menor o igual al año 2030",
                            min: "El dato debe ser mayor o igual al año 2000"
                        },
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
                var $valid = $("#mod_formet").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    //==========================================================
                    var etdescripcion = document.getElementById("mod_etdescripcion").value;
                    var etsigla = document.getElementById("mod_etsigla").value;
                    var etcodigo = document.getElementById("mod_etcodigo").value;
                    var etgestion = document.getElementById("mod_etgestion").value;

                    var url =  base_url+"index.php/admin/entidad_transferencia_add";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            etdescripcion: etdescripcion,
                            etsigla: etsigla,
                            etcodigo: etcodigo,
                            etgestion: etgestion,
                            modificar: id_et
                        },
                        success: function (data) {
                            window.location.reload(true);
                        }
                    });
                }
            });
        });
    });
//////////////////////////////////////////////eleiminar////////////////////////////////////////////
$(function () {
        function reset() {
            ruta_alerta = base_url + 'assets/themes_alerta/alertify.default.css';
                                $("#toggleCSS").attr("href", ruta_alerta);
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

        // =====================================================================
        $(".del_et").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    var url =  base_url+"index.php/admin/entidad_transferencia_del";
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        data: "postid=" + name
                    });
                    request.done(function (response, textStatus, jqXHR) {
                        //console.log("response: "+response);
                        $('#tr' + name).html("");
                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                    alertify.success("Se eliminó el registro correctamente");
                } else {
                    // user clicked "cancel"
                    alertify.error("Opcion cancelada");
                }
            });
            return false;
        });
    });
