//-------------NUEVO SECTOR--------------------//
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
    $('#clas_cod').keyup(function () {
        $("#display").css("color", "#3A3633");
        var codigo = $(this).val();
        codigo = parseInt(codigo);
        var codsectorial = null;
        if(codigo > 0){
            if(codigo >= 1 && codigo <= 9){
                codsectorial = '0'+$(this).val() + '-0-00';
                $('#display').text(codsectorial);
            } else{
                if($(this).val() > 9) {
                    codsectorial = $(this).val() + '-0-00';
                    $('#display').text(codsectorial);
                } else {
                    $('#display').text('');
                }
            }
        } else{
            $('#display').text('');
        }
        var url = site_url+"/mantenimiento/clasificacion_sectorial/valida_codigosector";
        $.ajax({
            data: {'codsectorial': codsectorial},
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function (data, textStatus, jqXHR) {
            if (data.respuesta) {
                console.log(data.mensaje);
                $("#display").css("color", "red");
                $('#display').text(data.mensaje + ' ' + codsectorial);
                document.getElementById('clas_nuevo_sector').disabled = true;
                // alertify.error("EL CODIGO DEL PROGRAMA YA EXISTE");
            } else {
                document.getElementById('clas_nuevo_sector').disabled = false;
                console.log(data.mensaje);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        });
    });
    
    $('#modal_nuevo_sector').on('hidden.bs.modal', function () {
        document.forms['form_nuevo_sector'].reset();
    });
    $("#clas_nuevo_sector").on("click", function (e) {
        //========================Validar Formulario Nuevo Sector===================
        var $validator = $("#form_nuevo_sector").validate({
            //////////////// DATOS GENERALES
            rules: {
                clas_cod: {
                    required: true,
                    min: 1
                },
                clas_abre: {
                    minlength: 2,
                    maxlength: 3
                },
                clas_nombre: {
                    required: true
                }
            },
            messages: {
                clas_cod: {required: "Ingrese Código", min:"El numero Debe ser mayor a 0"},
                clas_nombre: {required: "Ingrese Nombre del Sector"},
                clas_abre: {maxlength: "Abreviación Muy Larga", minlength: "Abreviación Muy Corto"}
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
        var $valid = $("#form_nuevo_sector").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            reset();
            alertify.confirm("Confirmar Registro", function (a) {
                if (a) {
                    document.form_nuevo_sector.submit();
                    // e.preventDefault();
                    // window.location.reload();
                    // alertify.success("Se registro correctamente");
                } else {
                    e.preventDefault();
                    alertify.error("Opcion cancelada");
                }
            });
            return false;
        }
    });
});
//-------------MODIFICAR SECTOR----------------// 
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
    //-------------------------------------
    $(".mod_sector").on("click", function (e) {
        reset();
        var name = $(this).attr('name');
        console.log(name);
        var url = site_url+"/mantenimiento/clasificacion_sectorial/modificar_sector";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "codsectorial=" + name
        });
        request.done(function (response, textStatus, jqXHR) {
            document.getElementById("clas_mod_cod").value = response.codsectorial;
            document.getElementById("clas_mod_gestion").value = response.gestion;
            document.getElementById("clas_mod_nombre").value = response.descripcion;
            document.getElementById("clas_mod_abre").value = response.abreviacion;
            document.getElementById("codsectorial_mod").value = response.codsectorial;
            document.getElementById("mod_codsec").value = response.codsec;
            document.getElementById("mod_gestion_sector").value = response.gestion;
        });
    });
    //-------------------------------------
    $("#clas_mod_sector").on("click", function (e) {
        //========================Validar Formulario Modificar Sector===================
        var $validator = $("#form_mod_sector").validate({
            rules: {
                clas_mod_abre: {
                    minlength: 2,
                    maxlength: 3
                },
                clas_mod_nombre: {
                    required: true
                }
            },
            messages: {
                clas_mod_nombre: {required: "Ingrese Nombre del Sector"},
                clas_mod_abre: {maxlength: "Abreviación Muy Larga", minlength: "Abreviación Muy Corto"}
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
        var $valid = $("#form_mod_sector").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            reset();
            alertify.confirm("Confirmar Registro", function (a) {
                if (a) {
                    document.form_mod_sector.submit();
                    // e.preventDefault();
                    // window.location.reload();
                } else {
                    e.preventDefault();
                    alertify.error("Opcion cancelada");
                }
            });
            return false;
        }
    });
});
//-------------ELIMINAR SECTOR-----------------//
$(function() {
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
    //------------------------------------------
    $(".eliminar_sector").on("click", function (e) {
        reset();
        var name = $(this).attr('name');
        var gest = $(this).attr('id');
        console.log(name+' '+gest);
        var request;
        alertify.confirm("<h2 style='color:#A0B5CB;'>CONFIRMAR ELIMINAR EL REGISTRO?</h2><h2>Se Eliminaran todos los subsectores y actividades economicas </h2>", function (a) {
            if (a) {
                var url = site_url + "/mantenimiento/clasificacion_sectorial/eliminar_sector";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: {'codsec': name,
                            'gest':gest}
                });
                request.done(function (response, textStatus, jqXHR) {
                    if(response.bool){
                        e.preventDefault();
                        alertify.success("Se eliminó el registro correctamente");
                        window.setTimeout(function(){location.reload()},500);
                    } else{
                        alertify.error("Error");
                        window.setTimeout(function(){location.reload()},500);
                    }
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                });
            } else {
                alertify.error("Opcion cancelada");
            }
        });
        return false;
    });
});
//**************************************SUBSECTORES******************************//
//-------------NUEVO SUBSECTOR--------------------//
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
    $('#clas_subcod').keyup(function () {
        $("#display").css("color", "#3A3633");
        var codigo = $(this).val();
        codigo = parseInt(codigo);
        var codsectorial = null;
        if(codigo > 0){
            if(codigo >= 1 && codigo <= 9){
                codsectorial = '0'+$(this).val() + '-0-00';
                $('#display').text(codsectorial);
            } else{
                if($(this).val() > 9) {
                    codsectorial = $(this).val() + '-0-00';
                    $('#display').text(codsectorial);
                } else {
                    $('#display').text('');
                }
            }
        } else{
            $('#display').text('');
        }
        var url = site_url+"/mantenimiento/clasificacion_sectorial/valida_codigosector";
        $.ajax({
            data: {'codsectorial': codsectorial},
            type: "POST",
            dataType: "json",
            url: url,
        })
        .done(function (data, textStatus, jqXHR) {
            if (data.respuesta) {
                console.log(data.mensaje);
                $("#display").css("color", "red");
                $('#display').text(data.mensaje + ' ' + codsectorial);
                document.getElementById('clas_nuevo_sector').disabled = true;
                // alertify.error("EL CODIGO DEL PROGRAMA YA EXISTE");
            } else {
                document.getElementById('clas_nuevo_sector').disabled = false;
                console.log(data.mensaje);
            }
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (console && console.log) {
                console.log("La solicitud a fallado: " + textStatus);
            }
        });
    });
    
    $('#modal_nuevo_sector').on('hidden.bs.modal', function () {
        document.forms['form_nuevo_sector'].reset();
    });
    $("#clas_nuevo_sector").on("click", function (e) {
        //========================Validar Formulario Nuevo Sector===================
        var $validator = $("#form_nuevo_sector").validate({
            //////////////// DATOS GENERALES
            rules: {
                clas_cod: {
                    required: true,
                    min: 1
                },
                clas_abre: {
                    minlength: 2,
                    maxlength: 3
                },
                clas_nombre: {
                    required: true
                }
            },
            messages: {
                clas_cod: {required: "Ingrese Código", min:"El numero Debe ser mayor a 0"},
                clas_nombre: {required: "Ingrese Nombre del Sector"},
                clas_abre: {maxlength: "Abreviación Muy Larga", minlength: "Abreviación Muy Corto"}
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
        var $valid = $("#form_nuevo_sector").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            reset();
            alertify.confirm("Confirmar Registro", function (a) {
                if (a) {
                    document.form_nuevo_sector.submit();
                    // e.preventDefault();
                    // window.location.reload();
                    // alertify.success("Se registro correctamente");
                } else {
                    e.preventDefault();
                    alertify.error("Opcion cancelada");
                }
            });
            return false;
        }
    });
});