$(function () {
    $('#modal_mod_poa').on('hidden.bs.modal', function(){
        document.forms['mod_formpoa'].reset();
    });
    //=================== ACTUALIZAR UNIDAD ORGANIZACIONAL MEDIANTE EL SELECT ==================
    $("#aper_programatica").on("click",function(e){
        var aper_id = document.getElementById("aper_programatica").value
        if(aper_id.length == 0){
            aper_id = 0;
        }
        var url = site_url+"/mantenimiento/cpoa/obtener_unidad";
        $.ajax({
            type:"post",
            url:url,
            dataType:'json',
            data:{aper_id:aper_id},
            success:function(datos){
                document.getElementById("unidad").value = datos.unidad;
            }
        });
    });
    //=================== GUARDAR CARPETA POA ===========================
    $("#enviar_poa").on("click",function(e){
        //========================VALIDANDO FORMULARIO===================
        var $validator = $("#form_poa").validate({
            rules: {
                aper_programatica: {
                    required: true,
                },
                poa_fecha: {
                    required: true,
                }
            },
            messages: {
                aper_programatica: "SELECCIONE UNA OPCIÇÓN",
                poa_fecha: {required:"INGRESE LA GESTIÓN"},
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
        var $valid = $("#form_poa").valid();
        if (!$valid) {
            $validator.focusInvalid();
        } else {
            //==========================================================
            var aper_programatica = document.getElementById("aper_programatica").value;
            var poa_fecha = document.getElementById("poa_fecha").value;
            //PREGUNTAR SI ESTA SEGURO EN GUARDAR
            reset();
            alertify.confirm("REALMENTE DESEA GUARDAR ESTE REGISTRO?", function (a) {
                if (a) {
                    //============= GUARDAR DESPUES DE LA VALIDACION ===============
                    var url = site_url+"/mantenimiento/cpoa/guardar_poa";
                    $.ajax({
                            type:"post",
                            url:url,
                            dataType:'json',
                            data:{aper_programatica:aper_programatica,poa_fecha:poa_fecha},
                        })
                        .done(function( data, textStatus, jqXHR ) {
                            reset();
                            //verificar si se guardo correctamente
                            if (data.respuesta == 'correcto') {
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
                        })
                        .fail(function( jqXHR, textStatus, errorThrown ) {
                            if ( console && console.log ) {
                                console.log( "La solicitud a fallado: " +  textStatus);
                            }
                        });

                   /* $.ajax({
                        type:"post",
                        url:url,
                        dataType:'json',
                        data:{aper_programatica:aper_programatica,poa_fecha:poa_fecha},
                        success:function(data){
                            reset();
                            //verificar si se guardo correctamente
                            if (data.respuesta == 'correcto') {
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

                    });*/

                } else {
                    alertify.error("OPCI\u00D3N CANCELADA");
                }
            });
        }
    });
    //=================== MODIFICAR CAPETA POA =========================
    //limpiar variable
    var id_poa ='';
    $(".mod_poa").on("click",function(e){
        //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA
        id_poa = $(this).attr('name');
        var url = site_url+"/mantenimiento/cpoa/get_poa";
        var request;
        if(request){
            request.abort();
        }
        request = $.ajax({
            url:url,
            type:"POST",
            dataType:'json',
            data:"poa_id="+id_poa
        });
        request.done(function(response,textStatus,jqXHR){
            document.getElementById("modpoa_codigo").value=response.poa_codigo;
            document.getElementById("modpoa_aper").value=response.aper_descripcion;
            document.getElementById("modpoa_fecha").value=response.fecha;
        });
        request.fail(function(jqXHR,textStatus,thrown){
            console.log("ERROR: "+ textStatus);
        });
        request.always(function(){
            //console.log("termino la ejecuicion de ajax");
        });
        e.preventDefault();
        // =============================VALIDAR EL FORMULARIO DE MODIFICACION
        $("#mod_poaenviar").on("click",function(e){
            var $validator = $("#mod_formpoa").validate({
                rules: {
                    modpoa_fecha: {
                        required: true,
                    }
                },
                messages: {
                    modpoa_fecha: {required:"INGRESE LA GESTIÓN"},
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
            var $valid = $("#mod_formpoa").valid();
            if (!$valid) {
                $validator.focusInvalid();
            } else {
                //==========================================================
                var poafecha= document.getElementById("modpoa_fecha").value;
                //PREGUNTAR SI ESTA SEGURO EN GUARDAR
                reset();
                alertify.confirm("REALMENTE DESEA MODIFICAR ESTE REGISTRO?", function (a) {
                    if (a) {
                        //============= GUARDAR DESPUES DE LA VALIDACION ===============
                        var url = site_url+"/mantenimiento/cpoa/guardar_poa";
                        $.ajax({
                            type:"post",
                            url:url,
                            dataType:"json",
                            data:{poa_fecha:poafecha,modificar:id_poa},
                            success:function(data){
                                reset();
                                //verificar si se guardo correctamente
                                if (data.respuesta == 'correcto') {
                                    alertify.alert("EL REGISTRO SE MODIFIC\u00D3 CORRECTAMENTE", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    });
                                } else {
                                    alertify.alert("ERROR AL MODIFICAR !!!"+data.respuesta, function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    });
                                }
                            }
                        });

                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        });
    });
    //=============================== ELIMINAR CARPETA POA ========================
    $(".eliminar_poa").on("click",function(e){
        reset();
        var name = $(this).attr('name');
        var request;
        // confirm dialog
        alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
            if (a) {
                url = site_url+"/mantenimiento/cpoa/eliminar_poa";
                if(request){
                    request.abort();
                }
                request = $.ajax({
                    url:url,
                    type:"POST",
                    dataType:"json",
                    data:"poa_id="+name
                });
                request.done(function(response,textStatus,jqXHR){
                    //console.log("response: "+response);
                    //$('#tr'+ response).html("");
                    reset();
                    //verificar si se guardo correctamente
                    if (response.respuesta == 'correcto') {
                        alertify.alert("EL REGISTRO SE ELIMIN\u00D3 CORRECTAMENTE", function (e) {
                            if (e) {
                                window.location.reload(true);
                            }
                        });
                    } else {
                        alertify.alert("ERROR AL ELIMINAR !!!", function (e) {
                            if (e) {
                                window.location.reload(true);
                            }
                        });
                    }
                });
                request.fail(function(jqXHR,textStatus,thrown){
                    console.log("ERROR: "+ textStatus);
                });
                request.always(function(){
                    //console.log("termino la ejecuicion de ajax");
                });
                e.preventDefault();

            } else {
                // user clicked "cancel"
                alertify.error("OPCION CANCELADA");
            }
        });
        return false;
    });




});
