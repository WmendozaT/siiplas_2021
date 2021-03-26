//////////////////////////////////////add partidas////////////////////////////////////
 $(function () {
        $('#modal_nuevo_par').on('hidden.bs.modal', function(){
            document.forms['form_par'].reset();
        });
        $("#enviar_par").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_par").validate({
                rules: {
                    par_nombre: { //// Programa
                        required: true,
                    },
                    dependiente: { //// Programa
                        required: true,
                    },
                    padre: { //// Programa
                        required: true,
                    },
                    par_codigo: { //// Programa
                        required: true,
                        number: true,
                    },
                    par_gestion: { //// Programa
                        required: true,
                        number: true,
                        max:2018,
                        min: 2000,
                    }
                },
                messages: {
                    par_nombre: "Ingrese el nombre de partida",
                    dependiente: "Elija una opcion",
                    padre: "Seleccione una opcion",
                    par_codigo: {required: "Ingrese el código", number: "Dato Inválido"},
                    par_gestion: {
                        required: "Ingrese la gestión",
                        number: "Dato Inválido",
                        max: "El dato debe ser menor o igual al año 2018",
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
            var $valid = $("#form_par").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var par_nombre = document.getElementById("par_nombre").value;
                var par_codigo = document.getElementById("par_codigo").value;
                var par_gestion = document.getElementById("par_gestion").value;
                var padre = document.getElementById("padre").value;
               
                //var dependiente = document.getElementById("dependiente").value;
                //alert(dependiente)
                //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
                var url =  base_url+"index.php/admin/verificar_par";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {par_codigo: par_codigo, par_gestion: par_gestion},
                    success: function (datos) {
                        
                        if (datos == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            // validacion de confirmacion
                            alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                            if (a) {
                            //end validacion de confirmacion
                            var url =  base_url+"index.php/admin/partidas_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {
                                    par_nombre: par_nombre,
                                    par_codigo: par_codigo,
                                    par_gestion: par_gestion,
                                    padre: padre
                                    
                                },
                                success: function (data) {
                                    if (data == 'true') {
                                        window.location.reload(true);
                                        
                                    } else {
                                        /*alert(data);*/
                                        alertify.alert("EL REGISTRO SE GUARDÓ CORRECTAMENTE", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });
                                       // window.location.reload(true);
                                    }
                                }
                            });
                            //validar de confirmacion
                              } else {
                                // user clicked "cancel"
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                            });
                            //end validar de confirmacion
                        } else {
                            $("#par_codigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE PARTIDA YA EXISTE");
                        }
                    }
                });
            }
        });
    });
 ///////////////////////////////////////////////////
 $(document).ready(function () {
        $(".par_si").click(function () {
            $('#content_parent').slideDown();
        })
        $(".par_no").click(function () {
            $('#content_parent').slideUp();
        })

    });
 //////////////////////////////////////////////////////
 ////////////////////////////////modificar////////////////////////////////////////////////
  $(function () {
        //limpiar variable
        var id_par = '';
        $(".mod_par").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA
            id_par = $(this).attr('name');
            
            var url =  base_url+"index.php/admin/partidas_mod";
            var codigo = '';
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_par=" + id_par
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("modpar_nombre").value = response.par_nombre;
                document.getElementById("modpar_padre").value = response.padre;
                document.getElementById("modpar_codigo").value = response.par_codigo;
                document.getElementById("modpar_gestion").value = response.par_gestion;

                codigo = response.par_codigo;
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
                var $validator = $("#mod_formpar").validate({
                    rules: {
                        modpar_nombre: { //// Programa
                            required: true,
                        },
                        modpar_codigo: { //// Programa
                            required: true,
                            number: true,
                        },
                        modpar_gestion: { //// Programa
                            required: true,
                            number: true,
                            max:2017,
                            min: 2000,
                        }
                    },
                    messages: {
                        modpar_nombre: "Ingrese el nombre de partida",
                        modpar_codigo: {required: "Ingrese el código", number: "Dato Inválido"},
                        modpar_gestion: {
                            required: "Ingrese la gestión",
                            number: "Dato Inválido",
                            max: "El dato debe ser menor o igual al año 2017",
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
                var $valid = $("#mod_formpar").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    //==========================================================
                    var par_nombre = document.getElementById("modpar_nombre").value;
                    var par_gestion = document.getElementById("modpar_gestion").value;
                    var par_codigo = document.getElementById("modpar_codigo").value;
                            // validacion de confirmacion
                            alertify.confirm("REALMENTE DESEA MODIFICAR ESTE REGISTRO?", function (a) {
                            if (a) {
                            //end validacion de confirmacion
                    var url =  base_url+"index.php/admin/partidas_add";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            par_nombre: par_nombre,
                            par_gestion: par_gestion,
                            par_codigo: par_codigo,
                            modificar: id_par
                        },
                        success: function (data) {
                                        alertify.alert("EL REGISTRO SE GUARDÓ CORRECTAMENTE", function (e) {
                                            if (e) {
                                                window.location.reload(true);
                                            }
                                        });
                            //window.location.reload(true);
                        }
                    });
                            //validar de confirmacion
                              } else {
                                // user clicked "cancel"
                                alertify.error("OPCI\u00D3N CANCELADA");
                            }
                            });
                            //end validar de confirmacion
                }
            });
        });
    });
////////////////////////////////////////////////eliminar/////////////////////////////////
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
        $(".del_par").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    var url =  base_url+"index.php/admin/partidas_del";
                    
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
