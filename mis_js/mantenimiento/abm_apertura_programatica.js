$(function () {
    //---------------------------------            ADICIONAR APERTURA PROGRAMATICA
    $("#enviar_aper").on("click", function (e) {
        //========================VALIDANDO FORMULARIO===================
        var $validator = $("#form_add_programa").validate({
            //////////////// DATOS GENERALES
            rules: {
                aper_programa: {
                    required: true,
                    number: true,
                    min: 0,
                },
                unidad_o: {
                    required: true,
                },
                aper_descripcion: {
                    required: true,
                },
                aper_gestion: {
                    required: true,
                    number: true,
                    max: gestion,
                    min: 2000,
                }
            },
            messages: {
                aper_programa: {
                    required: "Ingrese el Programa",
                    number: "Dato numérico",
                    min: "El campo debe ser mayor o igual a 0"
                },
                aper_descripcion: {required: "Ingrese la Descripción"},
                unidad_o: {required: "Seleccione la opción"},
                aper_gestion: {
                    required: "Ingrese la Gestión",
                    number: "Dato Inválido",
                    max: "El dato debe ser menor o igual al año <?php echo $this->session->userdata('gestion')?>",
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
        var $valid = $("#form_add_programa").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
        } else {
            //==========================================================
            var aper_programa = document.getElementById("aper_programa").value
            var aper_descripcion = document.getElementById("aper_descripcion").value
            var aper_gestion = document.getElementById("aper_gestion").value
            var unidad_o = document.getElementById("unidad_o").value

            //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
            var url = site_url + "/mantenimiento/capertura_programatica/existe_cod_apertura";
            $.ajax({
                type: "post",
                url: url,
                data: {aper_programa: aper_programa, aper_gestion: aper_gestion},
                success: function (datos) {
                    if (datos == '0') {
                        //PREGUNTAR SI ESTA SEGURO DE GUARDAR LA APERTURA
                        reset();
                        alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                            if (a) {
                                //============= GUARDAR DESPUES DE LA VALIDACION ===============
                                var url = site_url + "/mantenimiento/capertura_programatica/add_apertura";
                                $.ajax({
                                    type: "post",
                                    url: url,
                                    dataType: 'JSON',
                                    data: {
                                        aper_programa: aper_programa,
                                        aper_descripcion: aper_descripcion,
                                        unidad_o: unidad_o,
                                        aper_gestion: aper_gestion
                                    },
                                    success: function (datos) {
                                        reset();
                                        //verificar si se guardo correctamente
                                        if (datos.respuesta == 'correcto') {
                                            alertify.alert("EL REGISTRO SE GUARD\u00D3 CORRECTAMENTE", function (e) {
                                                if (e) {
                                                    window.location.reload(true);
                                                }
                                            });
                                        } else {
                                            alertify.alert("ERROR AL GUARDAR !!!", function (e) {
                                                if (e) {
                                                    window.location.reload(true);
                                                }
                                            });
                                        }
                                    }
                                });
                            } else {
                                // user clicked "cancel"
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                        });

                    } else {
                        $("#aper_programa").closest('.form-group').removeClass('has-success').addClass('has-error');
                        alertify.error("EL CODIGO DEL PROGRAMA YA EXISTE");
                    }


                }
            });
        }
    });
    //---------------------------------            MODIFICAR APERTURA PROGRAMATICA
    //limpiar variable
    var id_aper = '';
    $(".mod_aper").on("click", function (e) {
        //==========================LLENAR MIS DATOS DE FORMULARIO DE MODIFICACION CON LA CLAVE RECIBIDA
        id_aper = $(this).attr('name');
        var url = site_url + "/mantenimiento/capertura_programatica/dato_apertura_padre";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "id_aper=" + id_aper
        });

        request.done(function (response, textStatus, jqXHR) {
            document.getElementById("modaper_descripcion").value = response.aper_descripcion;
            document.getElementById("modaper_programa").value = response.aper_programa;
            document.getElementById("modaper_gestion").value = response.aper_gestion;
            document.getElementById("modunidad_o").value = response.uni_id;
            //document.mod_formaper.modunidad_o.selectedIndex=response.uni_id;
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
        e.preventDefault();
        //VALIDAR MI FORMULARIO ANTES DE GUARDAR
        // =============================VALIDAR EL FORMULARIO DE MODIFICACION
        $("#mod_aperenviar").on("click", function (e) {
            var $validator = $("#mod_formaper").validate({
                rules: {
                    modaper_programa: {
                        required: true,
                        number: true,
                        min: 10,
                    },
                    modunidad_o: {
                        required: true,
                    },
                    modaper_descripcion: {
                        required: true,
                    },
                    modaper_gestion: {
                        required: true,
                        number: true,
                        max: gestion,
                        min: 2000,
                    }
                },
                messages: {
                    modaper_programa: {
                        required: "Ingrese el Programa",
                        number: "Dato numérico",
                        min: "Ingrese dos campos"
                    },
                    modaper_descripcion: {required: "Ingrese la Descripción"},
                    modunidad_o: {required: "Seleccione la opción"},
                    modaper_gestion: {
                        required: "Ingrese la Gestión",
                        number: "Dato Inválido",
                        max: "El dato debe ser menor o igual al año <?php echo $this->session->userdata('gestion')?>",
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
            var $valid = $("#mod_formaper").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } else {
                //==========================================================
                var aper_descripcion = document.getElementById("modaper_descripcion").value
                var unidad = document.getElementById("modunidad_o").value
                reset();
                alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        //============= MODIFICAR DESPUES DE LA VALIDACION ===============
                        var url = site_url + "/mantenimiento/capertura_programatica/modificar_apertura";
                        $.ajax({
                            type: "post",
                            url: url,
                            dataType: 'json',
                            data: {aper_descripcion: aper_descripcion, unidad_o: parseInt(unidad), aper_id: id_aper},
                            success: function (data) {
                                reset();
                                //verificar si se MODIFICO correctamente
                                if (data.respuesta == 'correcto') {
                                    alertify.alert("EL REGISTRO SE MODIFIC\u00D3 CORRECTAMENTE", function (e) {
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
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });


            }
        });
    });
    //---------------------------------            ELIMINIAR APERTURA PROGRAMATICA
    <!--================= ELIMINACION APERTURA PROGRAMATICA-->
    $(".del_aper").on("click", function (e) {
        reset();
        var aper_id = $(this).attr('name');
        var request;
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/mantenimiento/capertura_programatica/eliminar_apertura";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    data: "aper_id=" + aper_id
                });
                request.done(function (response, textStatus, jqXHR) {
                    //console.log("response: "+response);
                    $("#tr" + response).html("");
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    //console.log("ERROR: "+ textStatus);
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
