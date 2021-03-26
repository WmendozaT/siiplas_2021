//REGISTRO DE EJECUCION DEL PROGRAMA
$(function () {
    //SUBIR ARCHIVO
    $("#subir_archivo").on("click", function () {
        var $validator = $("#form_subir_sigep").validate({
            rules: {
                nombre: {
                    required: true,
                },
                file: {
                    required: true,
                }
            },
            messages: {
                nombre: {required: "Ingrese el nombre del archivo."},
                file: {required: "Seleccione un Archivo."},
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
        var $valid = $("#form_subir_sigep").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            archivo = document.getElementById('file').value;
            valor = comprueba_extension(archivo);
            //si el valor es diferente de 0 no existe fallas
            if (parseInt(valor) != 0) {
                reset();
                alertify.confirm("REALMENTE DESEA SUBIR ESTE ARCHIVO?", function (a) {
                    if (a) {
                        document.getElementById("load").style.display = 'block';
                        document.getElementById('subir_archivo').disabled = true;
                        document.forms['form_subir_sigep'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }
    });
    //llenar modal
    $(".sincronizar_sigep").on("click", function () {
        var ruta_archivo = $(this).attr('name');
        var as_id = $(this).attr('id');
        document.getElementById("btn_sincronizar").value = ruta_archivo;
        document.getElementById("as_id").value = as_id;
    });
    //SINCRONIZAR
    $("#btn_sincronizar").on("click", function () {
        var ruta_archivo = document.getElementById("btn_sincronizar").value;
        var as_id = document.getElementById("as_id").value;
        var url = site_url + "/registro/cejec_pres_sigep/sincronizar_sigep";

        document.getElementById('modal_cerrar').disabled = true;
        document.getElementById('btn_cerrar').disabled = true;
        document.getElementById('btn_sincronizar').disabled = true;
        document.getElementById("load_modal").style.display = 'block';
        $.ajax({
                data: {'ruta': ruta_archivo, 'as_id': as_id},
                type: "POST",
                dataType: "json",
                url: url,
            })
            .done(function (data, textStatus, jqXHR) {
                if (data.peticion == 'verdadero') {
                    alertify.alert("SE HA REALIZADO LA SINCRONIZACION CORRECTAMENTE ", function (e) {
                        if (e) {
                            window.location.reload(true);
                        }
                    });
                } else {
                    alertify.error("ERROR AL SINCRONIZAR");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus.text);
                    reset();
                    alertify.alert("<h5>ERROR AL SINCRONIZAR, VERIFIQUE EL ARCHIVO.</h5>", function (e) {
                    });
                }
            });
    });
    //ELIMINAR ARCHIVO
    $(".eliminar_archivo").on("click", function () {
        var ruta_archivo = $(this).attr('name');
        var as_id = $(this).attr('id');
        var url = site_url + "/registro/cejec_pres_sigep/eliminar_archivo";
        reset();
        alertify.confirm("REALMENTE DESEA ELIMINAR EL ARCHIVO ?", function (a) {
            if (a) {
                $.ajax({
                        data: {'ruta': ruta_archivo, 'as_id': as_id},
                        type: "POST",
                        dataType: "json",
                        url: url,
                    })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.peticion == 'verdadero') {
                            alertify.alert("SE HA REALIZADO LA ELIMINACIÓN CORRECTAMENTE ", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                }
                            });
                        } else {
                            alertify.error("ERROR AL ELIMINAR");
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        if (console && console.log) {
                            console.log("La solicitud a fallado: " + textStatus.text);
                            reset();
                            alertify.alert("<h5>ERROR AL ELIMINAR EL ARCHIVO.</h5>", function (e) {
                            });
                        }
                    });
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });

    });
    //GUARDAR EJECUCION FINANCIERA
    $(".guardar_ejecucion").on("click", function () {
        var as_id = $(this).attr("name");//id de archivo segip
        var nombre_mes = $(this).attr("id");
        $("#titulo_mes").html(nombre_mes);
        $("#titulo_mes2").html(nombre_mes);
        document.getElementById("as_id_guardar").value = as_id;

    });
    //GUARDAR EJECUCION
    $("#btn_ejecucion").on("click", function () {
        var as_id = document.getElementById("as_id_guardar").value;
        var url = site_url + "/registro/cejec_pres_sigep/guardar_ejecucion";
        document.getElementById('modal_cerrar_ejecucion').disabled = true;
        document.getElementById('btn_cerrar_ejecucion').disabled = true;
        document.getElementById('btn_ejecucion').disabled = true;
        document.getElementById("load_modal_ejecucion").style.display = 'block';
        $.ajax({
                data: {'as_id': as_id},
                type: "POST",
                dataType: "json",
                url: url,
            })
            .done(function (data, textStatus, jqXHR) {
                if (data.peticion == 'verdadero') {
                    alertify.alert("SE GUARDO LA EJECUCIÓN FINANCIERA CORRECTAMENTE ", function (e) {
                        if (e) {
                            window.location.reload(true);
                        }
                    });
                } else {
                    alertify.error("ERROR AL GUARDAR LA EJECUCION FINANCIERA");
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                if (console && console.log) {
                    console.log("La solicitud a fallado: " + textStatus.text);
                    reset();
                    alertify.alert("<h5>ERROR AL GUARDAR LA EJECUCION.</h5>", function (e) {
                    });
                }
            });
    });
});

//VERIFICAR EL ARCHIVO QUE SE GUARDARA
function comprueba_extension(archivo) {
    extensiones_permitidas = new Array(".csv");
    mierror = "";
    //recupero la extensión de este nombre de archivo
    extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();
    //compruebo si la extensión está entre las permitidas
    permitida = false;
    for (var i = 0; i < extensiones_permitidas.length; i++) {
        if (extensiones_permitidas[i] == extension) {
            permitida = true;
            break;
        }
    }
    if (!permitida) {
        mierror = "<H3>SUBIR ARCHIVO CON EXTENSION: " + extensiones_permitidas.join() + "</H3>";
        //si estoy aqui es que no se ha podido submitir
        reset();
        alertify.alert(mierror, function (e) {
            if (e) {
            }
        });
        return 0;
    } else {
        var input = document.getElementById('file');
        var file = input.files[0];
        if (file.size > (1024 * 1024 * 10)) {
            mierror = 'EL ARCHIVO NO DEBE SUPERAR LAS 10 Mb';
            reset();
            alertify.alert("EL ARCHIVO NO DEBE SUPERAR LAS 10 Mb", function (e) {
                if (e) {
                }
            });
            return 0;
        }
    }
    return 1;

}

