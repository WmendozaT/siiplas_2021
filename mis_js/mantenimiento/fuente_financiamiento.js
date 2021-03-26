//////////////////////////////////adicionar fuente financiamiento/////////////////////
  $(function () {
        $('#modal_nuevo_ff').on('hidden.bs.modal', function () {
            document.forms['form_ff'].reset();
        });
        $("#enviar_ff").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_ff").validate({
                //////////////// DATOS GENERALES
                rules: {
                    ffdescripcion: { //// Programa
                        required: true,
                    },
                    ffsigla: { //// Programa
                        required: true,
                    },
                    ffcodigo: { //// Programa
                        required: true,
                        number: true,
                        min: 1,
                    },
                    ffgestion: { //// Programa
                        required: true,
                        number: true,
                        max: 2030,
                        min: 2000,
                    }
                },
                messages: {
                    ffdescripcion: "Ingrese la descripcion",
                    ffsigla: "Ingrese la sigla",
                    ffcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                    ffgestion: {
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
            var $valid = $("#form_ff").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var ffdescripcion = document.getElementById("ffdescripcion").value;
                var ffsigla = document.getElementById("ffsigla").value;
                var ffcodigo = document.getElementById("ffcodigo").value;
                var ffgestion = document.getElementById("ffgestion").value;
                //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
                var url =  base_url+"index.php/admin/fuente_financiamiento_verif";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {ff_codigo: ffcodigo, ff_gestion: ffgestion},
                    success: function (datos) {
                        
                        if (datos == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            var url =  base_url+"index.php/fuente_financiamiento_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {
                                    ffdescripcion: ffdescripcion,
                                    ffsigla: ffsigla,
                                    ffcodigo: ffcodigo,
                                    ffgestion: ffgestion
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
                            $("#ffcodigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE FUENTE FINANCIAMIENTO YA EXISTE");
                        }
                    }
                });
            }
        });
    });
/////////////////////////////////////////////////////////modificar//////////////////////////////////////////////////
 $(function () {
        var id_ff = '';
        $(".mod_ff").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA
            id_ff = $(this).attr('name');
            var url =  base_url+"index.php/fuente_financiamiento_mod";
            var codigo = '';
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_ff=" + id_ff
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("mod_ffdescripcion").value = response.ff_descripcion;
                document.getElementById("mod_ffsigla").value = response.ff_sigla;
                document.getElementById("mod_ffcodigo").value = response.ff_codigo;
                document.getElementById("mod_ffgestion").value = response.ff_gestion;
                codigo = response.ff_codigo;
            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
            $("#mod_ffenviar").on("click", function (e) {
                var $validator = $("#mod_formff").validate({
                    rules: {
                        mod_ffdescripcion: {
                            required: true,
                        },
                        mod_ffsigla: {
                            required: true,
                        },
                        mod_ffcodigo: {
                            required: true,
                            number: true,
                            min: 1,
                        },
                        mod_ffgestion: {
                            required: true,
                            number: true,
                            max:2030,
                            min: 2000,
                        }
                    },
                    messages: {
                        mod_ffdescripcion: "Ingrese la descripcion",
                        mod_ffsigla: "Ingrese la sigla",
                        mod_ffcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                        mod_ffgestion: {
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
                var $valid = $("#mod_formff").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    //==========================================================
                    var ffdescripcion = document.getElementById("mod_ffdescripcion").value;
                    var ffsigla = document.getElementById("mod_ffsigla").value;
                    var ffcodigo = document.getElementById("mod_ffcodigo").value;
                    var ffgestion = document.getElementById("mod_ffgestion").value;

                    var url =  base_url+"index.php/fuente_financiamiento_add";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            ffdescripcion: ffdescripcion,
                            ffsigla: ffsigla,
                            ffcodigo: ffcodigo,
                            ffgestion: ffgestion,
                            modificar: id_ff
                        },
                        success: function (data) {
                            window.location.reload(true);
                        }
                    });
                }
            });
        });
    });
////////////////////////////////////////////////eliminar//////////////////////////////////////////////
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
        $(".del_ff").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            // confirm dialog
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                	var url =  base_url+"index.php/fuente_financiamiento_del";
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