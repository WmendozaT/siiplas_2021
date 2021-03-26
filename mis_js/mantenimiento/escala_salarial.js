/*
        $(function () {
            $('#modal_nuevo_car').on('hidden.bs.modal', function () {
                document.forms['form_car'].reset();
            });
              $("#enviar_car").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_car").validate({
                rules: {
                    car_nombre: { //// Programa
                        required: true,
                    },
                    dependiente: { //// Programa
                        required: true,
                    },
                    padre: { //// Programa
                        required: true,
                    },
                    car_sueldo: { //// Programa
                        required: true,
                        number: true,
                    },
                    car_codigo: {
                        required: true,
                        number: true,
                    }
                },
                messages: {
                    car_nombre: "Ingrese el Nombre de Cargo",
                    dependiente: "Elija una Opcion",
                    padre: "Seleccione una Opcion",
                    car_sueldo: {required: "Ingrese el Sueldo", number: "Dato Inválido"},
                    car_codigo: {required: "Ingrese el Código", number: "Dato Inválido"},

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
                var $valid = $("#form_car").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                    //return false;
                } else {
                    document.form_car.submit();
                }
            });
        });*/

///////////////////////////////////adicionar cargo////////////////////////////////
 $(function () {
        $('#modal_nuevo_car').on('hidden.bs.modal', function () {
            document.forms['form_car'].reset();
            $("#content_parent").css("display", "none");
        });
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

        $("#enviar_car").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_car").validate({
                rules: {
                    car_nombre: { //// Programa
                        required: true,
                    },
                    dependiente: { //// Programa
                        required: true,
                    },
                    padre: { //// Programa
                        required: true,
                    },
                    car_sueldo: { //// Programa
                        required: true,
                        number: true,
                    },
                    car_codigo: {
                        required: true,
                        number: true,
                    }
                },
                messages: {
                    car_nombre: "Ingrese el Nombre de Cargo",
                    dependiente: "Elija una Opcion",
                    padre: "Seleccione una Opcion",
                    car_sueldo: {required: "Ingrese el Sueldo", number: "Dato Inválido"},
                    car_codigo: {required: "Ingrese el Código", number: "Dato Inválido"},

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
            var $valid = $("#form_car").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var car_nombre = document.getElementById("car_nombre").value;
                var car_sueldo = document.getElementById("car_sueldo").value;
                var padre = document.getElementById("padre").value;
                var car_codigo = document.getElementById("car_codigo").value;
                //=================== VERIFICAR SI EXISTE EL COD  ==============

                var url =  base_url+"index.php/admin/escala_salarial_ver";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {car_codigo: car_codigo},
                    success: function (datos) {
                        if (parseInt(datos) == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            var url =  base_url+"index.php/admin/escala_salarial_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {
                                    car_nombre: car_nombre,
                                    car_sueldo: car_sueldo,
                                    padre: padre,
                                    car_codigo: car_codigo
                                },
                                success: function (data) {
                                    //alert(data)
                                    if (data = 'true') {
                                        $("#modal_nuevo_car").css("display", "none");
                                        reset();
                                        alertify.alert("EL REGISTRO SE GUARDÓ CORRECTAMENTE", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });
                                    } else {
                                        alert(data);
                                    }
                                }
                            });
                        } else {
                            $("#car_codigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE ESCALA SALARIAL YA EXISTE");
                        }
                    }
                });


            }
        });
    });
//////////////////////
$(document).ready(function () {
        $(".car_si").click(function () {
            $('#content_parent').slideDown();
        })
        $(".car_no").click(function () {
            $('#content_parent').slideUp();
        })
    });
    //////////////////////////
  ////////////////////////////////////////////////////modificar/////////////////////////////
   $(function () {
        var id_car = '';
        $('#modal_mod_car').on('hidden.bs.modal', function () {
            id_car = '';
            document.forms['mod_formcar'].reset();
        });
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

        $(".mod_car").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA

            id_car = $(this).attr('name');
            var url =  base_url+"index.php/admin/escala_salarial_mod";
            
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_car=" + id_car
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("modcar_nombre").value = response.car_cargo;
                document.getElementById("modcar_sueldo").value = response.car_sueldo;
                document.getElementById("modcar_codigo").value = response.car_codigo;
                document.getElementById("modcar_codigo").disabled = true;
                document.getElementById('modcar_si').disabled = false;
                document.getElementById('modcar_no').disabled = false;
                if (response.padre == 'NINGUNO') {
                    document.getElementById('modcar_no').checked = true;
                    document.getElementById('modcar_si').disabled = true;
                    $('#modcontent_parent').slideUp();
                } else {
                    document.getElementById('modcar_si').checked = true;
                    document.getElementById('modcar_no').disabled = true;
                    $('#modcontent_parent').slideDown();
                    document.getElementById("modcar_padre").value = response.car_depende;
                }

                //codigo = response.car_codigo;
            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
            $("#mod_parenviar").on("click", function (e) {
                var $validator = $("#mod_formcar").validate({
                    rules: {
                        modcar_nombre: { //// Programa
                            required: true,
                        },
                        modcar_padre: { //// Programa
                            required: true,
                        },
                        modcar_sueldo: { //// Programa
                            required: true,
                            number: true,
                        },
                        modcar_codigo: {
                            required: true,
                            number: true,
                        }
                    },
                    messages: {
                        modcar_nombre: "Ingrese el Nombre de Cargo",
                        modcar_padre: "Seleccione una Opcion",
                        modcar_sueldo: {required: "Ingrese el Sueldo", number: "Dato Inválido"},
                        modcar_codigo: {required: "Ingrese el Código", number: "Dato Inválido"},

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
                var $valid = $("#mod_formcar").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    //==========================================================
                    var modcar_nombre = document.getElementById("modcar_nombre").value;
                    var modcar_sueldo = document.getElementById("modcar_sueldo").value;
                    var modcar_padre = document.getElementById("modcar_padre").value;
                    var modcar_codigo = document.getElementById("modcar_codigo").value;


                    var url =  base_url+"index.php/admin/escala_salarial_add";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            car_nombre: modcar_nombre,
                            car_sueldo: modcar_sueldo,
                            padre: modcar_padre,
                            car_codigo: modcar_codigo,
                            modificar: id_car
                        },
                        success: function (data) {
                            if (data = 'true') {
                                $("#modal_mod_car").css("display", "none");
                                reset();
                                alertify.alert("EL REGISTRO SE MODIFICÓ CORRECTAMENTE", function (e) {
                                    if (e) {
                                        window.location.reload(true);
                                    }
                                });
                            } else {
                                alert(data);
                            }
                        }
                    });
                }
            });
            document.mod_formcar.reset()
        });
    });  
////////////////////////////////////////////////eliminar////////////////////////////////////////
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
        $(".del_car").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    var url =  base_url+"index.php/admin/escala_salarial_del";
                    
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
                        $('#tr' + response.trim()).html("");
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
