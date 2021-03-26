//REGISTRO DE EJECUCION DEL PROGRAMA
$(function () {
    //GUARDAR MI EJECUCION DEL PROGRAMA
    $("#guardar_registro").on("click", function (e) {
        reset();
        alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
            if (a) {
                /* archivo = document.getElementById('userfile').value;
                 valor = comprueba_extension(archivo);
                 if (parseInt(valor) != 0) {
                 document.getElementById("load").style.display = 'block';
                 document.getElementById('oguardar_pdf').disabled = true;
                 document.form_add_ejec_op.submit();
                 }   */
               // document.getElementById("load").style.display = 'block';
                document.getElementById('guardar_registro').disabled = true;
                document.form_add_ejec_op.submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    });
    //VALIDAR LA EJECUCION DEL PROGRAMA
    $("#validar_registro").on("click", function (e) {
        reset();
        alertify.confirm("REALMENTE DESEA VALIDAR LA EJECUCION DEL PROGRAMA ? ", function (a) {
            if (a) {
                document.getElementById("validar").value = 2;
                /*archivo = document.getElementById('userfile').value;
                 valor = comprueba_extension(archivo);
                 if (parseInt(valor) != 0) {
                 document.getElementById("load").style.display = 'block';
                 document.getElementById('oguardar_pdf').disabled = true;
                 document.form_add_ejec_op.submit();
                 }   */
                document.form_add_ejec_op.submit();
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    });
    //REVERTIR REGISTRO EJECUCION DEL PROGRAMA
    $(".revertir").on("click", function () {
        var reg_id = $(this).attr('name');
        var mes_id = $(this).attr('id');
        var poa_id = document.getElementById('poa_id').value;
        reset();
        alertify.confirm("DESEA REVERTIR ESTE REGISTRO ?", function (a) {
            if (a) {
                var url = site_url + "/registro/cejec_ogestion_pterminal/revertir_ejec_programa";
                $.ajax({
                        data: {'reg_id': reg_id, 'mes_id': mes_id, 'poa_id': poa_id},
                        type: "POST",
                        dataType: "json",
                        url: url,
                    })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.peticion == 'verdadero') {
                            alertify.alert("REGISTRO REVERTIDO !!!", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                }
                            });
                        } else {
                            alertify.error("ERROR AL REVERTIR");
                        }
                    })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        if (console && console.log) {
                            console.log("La solicitud a fallado: " + textStatus);
                        }
                    });
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    });
    //SUBIR ARCHIVO
    $("#subir_archivo").on("click", function () {
        var $validator = $("#form_arc_ejec_prog").validate({
            rules: {
                file: {
                    required: true,
                },
                nombre_archivo: {
                    required: true,
                }
            },
            messages: {
                file: {required: "Seleccione un Archivo."},
                nombre_archivo: {required: "Ingrese el nombre del archivo."},
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
        var $valid = $("#form_arc_ejec_prog").valid();
        if (!$valid) {
            $validator.focusInvalid();
            //return false;
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
                        document.forms['form_arc_ejec_prog'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }
    });
    //ELIMINAR ARCHIVO
    $(".del_archivo").on("click", function () {
        var p_id = $(this).attr('name');
        reset();
        alertify.confirm("DESEA ELIMINAR ESTE ARCHIVO ?", function (a) {
            if (a) {
                var url = site_url + "/registro/cejec_ogestion_pterminal/eliminar_archivo";
                $.ajax({
                        data: {'p_id': p_id},
                        type: "POST",
                        dataType: "json",
                        url: url,
                    })
                    .done(function (data, textStatus, jqXHR) {
                        if (data.peticion == 'verdadero') {
                            alertify.alert("ARCHIVO ELIMINADO !!!", function (e) {
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
                            console.log("La solicitud a fallado: " + textStatus);
                        }
                    });
            } else {
                alertify.error("OPCI\u00D3N CANCELADA");
            }
        });
    });
});

//VERIFICAR EL ARCHIVO QUE SE GUARDARA
function comprueba_extension(archivo) {
    extensiones_permitidas = new Array(".jpg", ".doc", ".pdf", ".png", ".JPEG", ".xlsx");
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
        mierror = "COMPRUEBA LA EXTENSIÓN DE LOS ARCHIVOS. \nSÓLO SE PUEDEN SUBIR ARCHIVOS CON EXTENSIONES: " + extensiones_permitidas.join();
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

