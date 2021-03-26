php = $('[id="php"]').val();

        function fun_usuario(){ 
            a = $('[id="nombre"]').val();
            b = $('[id="ap"]').val();
            c = $('[id="am"]').val();
            
            $('[id="usuario"]').val((a[0].toUpperCase() +'.'+b.toUpperCase() ) );
        }

        $("#adm").change(function () {
            $("#adm option:selected").each(function () {
                elegido = $(this).val();
                if(elegido == 2 ){
                    $('#dependencia').slideDown();
                    $('#titulo').html('<font color=blue> (REGIONAL) </font>');
                }
                else{

                    $('#dependencia').slideUp();
                    $('#usu_sact').slideUp();
                    $('#titulo').html(' <font color=blue> (NACIONAL) </font> ');
                    $('#but_registro').slideDown();
                    
                    //document.getElementById("com_id").value=1;
                    //$('[id="com_id"]').val(0);
                  //  alert($('[id="com_id"]').val())
                }
            });
        });
    /*-------------------------------- GUARDAR REGISTRO RESPONSABLES -------------------------*/
    
        $('#resp_form input').on('change', function() {
            if($('input[name=rol_id]:checked', '#resp_form').val()==1){
                $('#but_registro').slideDown();
                $('#usu_sact').slideUp();
            }
            else{
                if($('input[name=rol_id]:checked', '#resp_form').val()==9 & $('[id="adm"]').val()!=1){
                    $('#usu_sact').slideDown();
                    $('#but_registro').slideDown();
                }
                else{
                    $('#usu_sact').slideUp();
                    $('input[name=rol_id]:checked', '#resp_form').val(1);
                }
            }
           
        });


            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post(php+"index.php/admin/proy/combo_distrital", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#dist_id").html(data);
                        $("#act_id").html('');
                        $("#com_id").html('');
                    });     
                });
            });

            $("#dist_id").change(function () {
                $("#dist_id option:selected").each(function () {
                    dist_id=$(this).val();
                    var url = php+"index.php/mantenimiento/funcionario/get_unidades";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "dist_id="+dist_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                        if (response.respuesta == 'correcto') {
                            $('#act_id').fadeIn(1000).html(response.lista_actividad);
                            $("#com_id").html('');
                        }
                        else{
                            alertify.error("ERROR AL LISTAR UNIDADES");
                        }
                    });    
                });
            });


        $("#act_id").change(function () {
            $("#act_id option:selected").each(function () {
                elegido=$(this).val();

                $.post(php+"index.php/rep/get_uadministrativas", { elegido: elegido,accion:'subactividades' }, function(data){
                    $("#com_id").html(data);
                });
            });
        });


        $("#com_id").change(function () {
            $("#com_id option:selected").each(function () {
                elegido=$(this).val();

               $('[id="componente"]').val(elegido);
            });
        });


        $(function () {
            $('#modal_nuevo_fun').on('hidden.bs.modal', function () {
                document.forms['form_fun'].reset();
            });
            $("#enviar_fun").on("click", function (e) {
                //========================VALIDANDO FORMULARIO===================
                var $validator = $("#form_fun").validate({
                    //////////////// DATOS GENERALES
                    rules: {
                        fun_nombre: { //// Programa
                            required: true,
                        },
                        fun_paterno: { //// Programa
                            required: true,
                        },
                        fun_materno: { //// Programa
                            required: true,
                        },
                        fun_ci: { //// Programa
                            required: true,
                        },
                        fun_cargo: {
                            required: true,
                            minlength: 2,
                        },
                        fun_telefono: { //// Programa
                            number: true,
                            minlength: 5,
                        },
                        fun_usuario: {
                            required: true,
                            minlength: 2,
                        },
                        fun_password: {
                            required: true,
                            minlength: 4,
                        },
                        uni_id: {
                            required: true,
                        },
                        car_id: {
                            required: true,
                        },
                        rol: {
                            required: true,
                        }

                    },
                    messages: {
                        fun_nombre: {required: "Ingrese Nombre"},
                        fun_paterno: {required: "Ingrese Apellido Paterno"},
                        fun_materno: {required: "Ingrese Apellido Materno"},
                        fun_ci: {required: "Ingrese cedula de identidad"},
                        fun_cargo: {required: "Ingrese El Cargo", minlength: "Dato Inválido"},
                        fun_telefono: {number: "Dato Inválido", minlength: "Número de Teléfono Corto"},
                        fun_usuario: {required: "Ingrese el Usuario", minlength: "Dato Inválido"},
                        fun_password: {required: "Ingrese su Contraseña", minlength: "Contraseña Corta"},
                        uni_id: {required: "Seleccione la Unidad Organizacional"},
                        car_id: {required: "Seleccione el Cargo"},
                        rol: {required: "Seleccione un Rol"},
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
                var $valid = $("#form_fun").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                    //return false;
                } else {
                    document.form_fun.submit();
                }
            });
        });
////////////////////////////////////////////////////////////////////MODIFICAR////////////////////////////////////
 
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
            $(".mod_fun").on("click", function (e) { 
                reset();
                var name = $(this).attr('name');
               /*alert(name); */

                var url = base_url+"index.php/admin/mantenimiento/get_fun";
                var request;
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: "fun_id=" + name
                });
                 request.done(function (response, textStatus, jqXHR) {

                    document.getElementById("modfun_id").value = response.fun_id;
                    document.getElementById("modfun_nombre").value = response.fun_nombre; 
                    document.getElementById("modfun_paterno").value = response.fun_paterno;
                    document.getElementById("modfun_materno").value = response.fun_materno;
                    document.getElementById("modfun_cargo").value = response.fun_cargo;
                    document.getElementById("modfun_dni").value = response.fun_dni;
                    document.getElementById("modfun_telefono").value = response.fun_telefono;
                    document.getElementById("modfun_domicilio").value = response.fun_domicilio;
                    document.getElementById("modfun_usuario").value = response.fun_usuario;
                    document.getElementById("modfun_password").value = response.fun_password;
                    //document.getElementById("modfun_password").value = '';
                    document.getElementById("moduni_id").value = response.uni_id;
                    document.getElementById("modcar_id").value = response.car_id;
                    for (var i = 0; i < response.fun_roles.length; i++) {
                        id_rol = 'rol' + response.fun_roles[i];
                        document.getElementById(id_rol).checked  = true;
                    }
                });
            });
        });
/////////////////////////////////////////validar modificacion//////////////////////////////

        $(function () {
            $('#modal_mod_fun').on('hidden.bs.modal', function () {
                document.forms['mod_formfun'].reset();
            });
            $("#modenviar_fun").on("click", function (e) {
                //========================VALIDANDO FORMULARIO===================
                var $validator = $("#mod_formfun").validate({
                    //////////////// DATOS GENERALES
                    rules: {
                        modfun_nombre: { //// Programa
                            required: true,
                        },
                        modfun_paterno: { //// Programa
                            required: true,
                        },
                        modfun_materno: { //// Programa
                            required: true,
                        },
                        modfun_dni: { //// Programa
                            required: true,
                        },
                        modfun_cargo: {
                            required: true,
                            minlength: 2,
                        },
                        modfun_telefono: { //// Programa
                            number: true,
                            minlength: 5,
                        },
                        modfun_usuario: {
                            required: true,
                            minlength: 2,
                        },
                        moduni_id: {
                            required: true,
                        },
                        modcar_id: {
                            required: true,
                        }

                    },
                    messages: {
                        modfun_nombre: {required: "Ingrese Nombre"},
                        modfun_paterno: {required: "Ingrese Apellido Paterno"},
                        modfun_materno: {required: "Ingrese Apellido Materno"},
                        modfun_dni: {required: "Ingrese cedula de identidad"},
                        modfun_cargo: {required: "Ingrese El Cargo", minlength: "Dato Inválido"},
                        modfun_telefono: {number: "Dato Inválido", minlength: "Número de Teléfono Corto"},
                        modfun_usuario: {required: "Ingrese el Usuario", minlength: "Dato Inválido"},
                        fun_password: {required: "Ingrese su Contraseña", minlength: "Contraseña Corta"},
                        moduni_id: {required: "Seleccione la Unidad Organizacional"},
                        modcar_id: {required: "Seleccione el Cargo"},
                        
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
                var $valid = $("#mod_formfun").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                    //return false;
                } else {
                    document.mod_formfun.submit();
                }
            });
        });
//////////////////////////////////////////////// ELIMINAR//////////////////////////////////
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
        $(".del_fun").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    var url =  base_url+"index.php/admin/mantenimiento/del_fun";
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        data: "del_fun=" + name
                    });
                    request.done(function (response, textStatus, jqXHR) {
                        //console.log("response: "+response);
                        $('#tr'+name).html("");
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
 