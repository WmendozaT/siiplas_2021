 $(function () {
        $('#modal_nuevo_of').on('hidden.bs.modal', function () {
            document.forms['form_of'].reset();
        });
        $("#enviar_of").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_of").validate({
                //////////////// DATOS GENERALES
                rules: {
                    ofdescripcion: { //// Programa
                        required: true,
                    },
                    ofsigla: { //// Programa
                        required: true,
                    },
                    ofcodigo: { //// Programa
                        required: true,
                        number: true,
                        min: 1,
                    },
                    ofgestion: { //// Programa
                        required: true,
                        number: true,
                        max:2030,
                        min: 2000,
                    }
                },
                messages: {
                    ofdescripcion: "Ingrese la descripcion",
                    ofsigla: "Ingrese la sigla",
                    ofcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                    ofgestion: {
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
            var $valid = $("#form_of").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var ofdescripcion = document.getElementById("ofdescripcion").value;
                var ofsigla = document.getElementById("ofsigla").value;
                var ofcodigo = document.getElementById("ofcodigo").value;
                var ofgestion = document.getElementById("ofgestion").value;
                //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
                
                var url =  base_url+"index.php/admin/organismo_fin_verif";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {of_codigo: ofcodigo, of_gestion: ofgestion},
                    success: function (datos) {
                        
                        if (datos == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            
                            var url =  base_url+"index.php/admin/organismo_fin_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {
                                    ofdescripcion: ofdescripcion,
                                    ofsigla: ofsigla,
                                    ofcodigo: ofcodigo,
                                    ofgestion: ofgestion
                                },
                                success: function (data) {
                                    //$("#modal_nuevo_of").hide();
                                    //$("#ok").show();
                                    //colocar un alerta q de true o false
                                    if (data == 'true') {
                                        window.location.reload(true);
                                    } else {
                                        /*alert(data);*/
                                        window.location.reload(true);
                                    }

                                }
                            });
                        } else {
                            $("#ofcodigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE ORGANISMO FINANCIADOR YA EXISTE");
                        }
                    }
                });
            }
        });
    });
 ///////////////////////////////////////////modificar/////////////////////////////////////////////
 $(function () {
        //limpiar variable
        var id_of = '';
        $(".mod_of").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA
            id_of = $(this).attr('name');
            var url =  base_url+"index.php/admin/organismo_fin_mod";
            var codigo = '';
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_of=" + id_of
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("mod_ofdescripcion").value = response.of_descripcion;
                document.getElementById("mod_ofsigla").value = response.of_sigla;
                document.getElementById("mod_ofcodigo").value = response.of_codigo;
                document.getElementById("mod_ofgestion").value = response.of_gestion;
                codigo = response.of_codigo;
            });
            request.fail(function (jqXHR, textStatus, thrown) {
                console.log("ERROR: " + textStatus);
            });
            request.always(function () {
                //console.log("termino la ejecuicion de ajax");
            });
            e.preventDefault();
            // =============================VALIDAR EL FORMULARIO DE MODIFICACION
            $("#mod_ofenviar").on("click", function (e) {
                var $validator = $("#mod_formof").validate({
                    rules: {
                        mod_ofdescripcion: {
                            required: true,
                        },
                        mod_ofsigla: {
                            required: true,
                        },
                        mod_ofcodigo: {
                            required: true,
                            number: true,
                            min: 1,
                        },
                        mod_ofgestion: {
                            required: true,
                            number: true,
                            max:2030,
                            min: 2000,
                        }
                    },
                    messages: {
                        mod_ofdescripcion: "Ingrese la descripcion",
                        mod_ofsigla: "Ingrese la sigla",
                        mod_ofcodigo: {required: "Ingrese el código", number: "Dato Inválido", min: "Dato Inválido"},
                        mod_ofgestion: {
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
                var $valid = $("#mod_formof").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                    //return false;
                } else {
                    //==========================================================
                    var ofdescripcion = document.getElementById("mod_ofdescripcion").value;
                    var ofsigla = document.getElementById("mod_ofsigla").value;
                    var ofcodigo = document.getElementById("mod_ofcodigo").value;
                    var ofgestion = document.getElementById("mod_ofgestion").value;
                    //=================== VERIFICAR SI EXISTE EL COD DE PROGRAMA ==============
                    /*var url = "
                    <?php// echo site_url("admin")?>/mantenimiento/ver_cod_of";
                     var url ="";
                     $.ajax({
                     type:"post",
                     url:url,
                     data:{of_codigo:ofcodigo,codigo_antes:codigo},
                     success:function(datos){*/

                    var url =  base_url+"index.php/admin/organismo_fin_add";
                    //comparar si es el mismo codigo ya guardado en bd
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {
                            ofdescripcion: ofdescripcion,
                            ofsigla: ofsigla,
                            ofcodigo: ofcodigo,
                            ofgestion: ofgestion,
                            modificar: id_of
                        },
                        success: function (data) {
                            window.location.reload(true);
                        }
                    });
                    /*  }
                     });*/
                }
            });


        });
    });

 //////////////////////////////////////////////eliminar///////////////////////////////////
  $(function () {
        /*function lista_datos(){
         var url = "
        <?php echo site_url("admin")?>/mantenimiento/lista_of";
         $.ajax({
         type:"post",
         url:url,
         success:function(data){
         $("tbody").html(data);
         }
         });
         }
         lista_datos();*/
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
        $(".del_of").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            
            var request;
            // confirm dialog
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    //url = "<?php //echo base_url();?>/index.php/cmantenimiento/del_organismofinanciador";
                    var url =  base_url+"index.php/admin/organismo_fin_del";
                    
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
