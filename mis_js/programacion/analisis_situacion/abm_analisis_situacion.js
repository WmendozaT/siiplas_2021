$(function () {
    //ANALISIS DE SITUACION
    var id_aper = '';
    $(".editar_anal").on("click", function (e) {
        //==========================LLENAR MIS DATOS DE FORMULARIO DE MODIFICACION CON LA CLAVE RECIBIDA
        foda_id = $(this).attr('name');
        var url = site_url + "/programacion/analisis_situacion/get_foda_editar";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "foda_id=" + foda_id
        });

        request.done(function (response, textStatus, jqXHR) {
            document.getElementById("e_descripcion").value = response.foda_variables;
            document.getElementById("e_incidencia").value = response.foda_incidencia;
            document.getElementById("e_tipo_foda").value = response.tfoda_id;
            document.getElementById("e_foda_id").value = response.foda_id;
            //document.form_anal.e_incidencia.selectedIndex=response.uni_id;
            console.log(response);
        });
        request.fail(function (error) {
            console.log("ERROR: " + error);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
        e.preventDefault();
        //VALIDAR MI FORMULARIO ANTES DE GUARDAR
        // =============================VALIDAR EL FORMULARIO DE MODIFICACION
        $("#enviar_anal").on("click", function (e) {
            var $validator = $("#form_anal").validate({
                rules: {
                    e_descripcion: {
                        required: true
                    },
                    e_incidencia: {
                        required: true
                    },
                    e_tipo_foda: {
                        required: true
                    }
                },
                messages: {
                    e_descripcion: {
                        required: "<h5>Dato Necesario</h5>"
                    },
                    e_incidencia: {
                        required: "<h5>Dato Necesario</h5>"
                    },
                    e_tipo_foda: {
                        required: "<h5>Seleccione Una Opcion</h5>"
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
            var $valid = $("#form_anal").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } else {
                //==========================================================
                var n_foda_variables = document.getElementById("e_descripcion").value;
                var n_foda_incidencia = document.getElementById("e_incidencia").value;
                var n_foda_id = document.getElementById("e_foda_id").value;
                var n_tfoda_id = document.getElementById("e_tipo_foda").value;
                reset();
                alertify.confirm("REALMENTE DESEA EDITAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        //============= MODIFICAR DESPUES DE LA VALIDACION ===============
                        var url = site_url + "/programacion/analisis_situacion/editar_foda";
                        $.ajax({
                            type: "post",
                            url: url,
                            dataType: 'json',
                            data: {
                                foda_id: n_foda_id,
                                foda_variables: n_foda_variables,
                                foda_incidencia: n_foda_incidencia,
                                tfoda_id: n_tfoda_id
                            },
                            success: function (data) {
                                reset();
                                //verificar si se MODIFICO correctamente
                                if (data.respuesta == 'correcto') {
                                    alertify.alert("EL REGISTRO SE MODIFICIÓN CORRECTAMENTE", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    alertify.alert("ERROR AL MODIFICAR !!!", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    });
                                }
                            }
                        });
                    } else {
                        // user clicked "cancel"
                        alertify.error("OPCIÓN CANCELADA");
                    }
                });
            }
        });

    });

    $(".del_anal").on("click", function (e) {
        reset();
        var foda_id = $(this).attr('name');
        var request;
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/programacion/analisis_situacion/eliminar_foda";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: "foda_id=" + foda_id
                });
                request.done(function (response, textStatus, jqXHR) {
                    if(response.respuesta=='correcto')
                    {
                        alertify.success("Se eliminó el registro correctamente");
                        $("#tr" + response.foda_id).html("");
                    }else{
                        alertify.error("Intente de nuevo, por favor");
                        return false;
                    }
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    Console.log(error);
                });
            } else {
                alertify.error("Opcion cancelada");
            }
        });
        return false;
    });

    $("#validar_form_analisis").on("click", function (e) {
        //========================VALIDANDO FORMULARIO===================
        console.log('fomr validator');
        var $validator = $("#form_analisis").validate({
            rules: {
                descripcion: {
                    required: true
                },
                incidencia: {
                    required: true
                },
                tipo_foda: {
                    required: true
                }
            },
            messages: {
                descripcion: {
                    required: "<h5>Dato Necesario</h5>"
                },
                incidencia: {
                    required: "<h5>Dato Necesario</h5>"
                },
                tipo_foda: {
                    required: "<h5>Seleccione Una Opcion</h5>"
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
        var $valid = $("#form_analisis").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            console.log('Form Bien');
            alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                if (a) {
                    //============= GUARDAR DESPUES DE LA VALIDACION ===============
                    document.form_analisis.submit();
                } else {
                    alertify.error("OPCIÓN CANCELADA");
                }
            });
        }
    });

});