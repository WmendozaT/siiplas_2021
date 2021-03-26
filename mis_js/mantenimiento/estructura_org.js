/*--------------------- Dependencia --------------------*/
$("#dep").change(function () {
        $("#dep option:selected").each(function () {
            elegido = $(this).val();
            if(elegido == 1 ){
                $('#dependencia').slideDown();
            }
            else
            {
                $('#dependencia').slideUp();
            }
        });
    });

function valida_envia()
{ 

    if (document.uni_form.cod_uni.value==0 || document.uni_form.cod_uni.value=='') /////// Codigo
    { 
      alert("REGISTRE CAMPO CODIGO") 
      document.uni_form.cod_uni.focus() 
      return 0; 
    }

    if (document.uni_form.unidad.value.length==0) /////// Unidad Organizacional
    { 
      alert("REGISTRE UNIDAD ORGANIZACIONAL") 
      document.uni_form.unidad.focus() 
      return 0; 
    }

    if (document.uni_form.dep.value=='') /////// Dependencia
    { 
      alert("SELECCIONE SI TIENE O NO DEPENDECIA DE LA ORGANIZACION") 
      document.uni_form.dep.focus() 
      return 0; 
    }

    if (document.uni_form.tipo_u.value=='') /////// Tipo de Unidad
    { 
      alert("SELECCIONE TIPO DE UNIDAD ORGANIZACIONAL") 
      document.uni_form.tipo_u.focus() 
      return 0; 
    }

    if (document.uni_form.dep.value=='1') /////// Dependencia
    { 
        if (document.uni_form.unidad_dep.value=='') /////// Unidad Dependencia
        { 
          alert("SELECCIONE UNIDAD DEPENDIENTE") 
          document.uni_form.unidad_dep.focus() 
          return 0; 
        }
    }

    var uni_codigo = document.getElementById("cod_uni").value;
    //=================== VERIFICAR SI EXISTE EL CODIGO ==============
    var url = "estructura_org_verificar";

    $.ajax({
        type: "post",
        url: url,
        data: {uni_codigo: uni_codigo},
        success: function (datos) {
            if (parseInt(datos) == 1) {
                //============= GUARDAR DESPUES DE LA VALIDACION ===============
               var OK = confirm(" GUARDAR UNIDAD ?");
                if (OK) {
                        document.uni_form.submit();
                        document.getElementById("btsubmit").value = "GUARDANDO...";
                        document.getElementById("btsubmit").disabled = true;
                        return true;
                } 

            } else {
                alert('Error!! El codigo ya existe, Verifique el dato')
                document.uni_form.cod_uni.focus() 
                return 0; 
            }
        }
    });
}

function valida_envia_update()
{ 

    if (document.uni_form.unidad.value.length==0) /////// Unidad Organizacional
    { 
      alert("REGISTRE UNIDAD ORGANIZACIONAL") 
      document.uni_form.unidad.focus() 
      return 0; 
    }

    if (document.uni_form.dep.value=='') /////// Dependencia
    { 
      alert("SELECCIONE SI TIENE O NO DEPENDECIA DE LA ORGANIZACION") 
      document.uni_form.dep.focus() 
      return 0; 
    }

    if (document.uni_form.tipo_u.value=='') /////// Tipo de Unidad
    { 
      alert("SELECCIONE TIPO DE UNIDAD ORGANIZACIONAL") 
      document.uni_form.tipo_u.focus() 
      return 0; 
    }

    if (document.uni_form.dep.value=='1') /////// Dependencia
    { 
        if (document.uni_form.unidad_dep.value=='') /////// Unidad Dependencia
        { 
          alert("SELECCIONE UNIDAD DEPENDIENTE") 
          document.uni_form.unidad_dep.focus() 
          return 0; 
        }
    }

    var OK = confirm(" MODIFICAR UNIDAD ?");
    if (OK) {
            document.uni_form.submit();
            document.getElementById("btsubmit").value = "MODIFICANDO...";
            document.getElementById("btsubmit").disabled = true;
            return true;
    } 
}





////////////////////////////////////////////adicionar cargo////////////////////////////////////////////

    $(function () {
        $('#modal_nuevo_uni').on('hidden.bs.modal', function () {
            document.forms['form_uni'].reset();
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

        $("#enviar_uni").on("click", function (e) {
            //========================VALIDANDO FORMULARIO===================
            var $validator = $("#form_uni").validate({
                rules: {
                    uni_unidad: { //// Programa
                        required: true,
                    },
                    dependiente: { //// Programa
                        required: true,
                    },
                    padre: { //// Programa
                        required: true,
                    },
                    uni_codigo: {
                        required: true,
                        number: true,
                    }
                },
                messages: {
                    uni_unidad: "Ingrese el Nombre de la Unidad Organizacional",
                    dependiente: "Elija una Opcion",
                    padre: "Seleccione una Opcion",
                    uni_codigo: {required: "Ingrese el Código", number: "Dato Inválido"},
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
            var $valid = $("#form_uni").valid();
            if (!$valid) {
                $validator.focusInvalid();
                //return false;
            } else {
                //==========================================================
                var uni_unidad = document.getElementById("uni_unidad").value;
                var padre = document.getElementById("padre").value;
                var uni_codigo = document.getElementById("uni_codigo").value;
                //=================== VERIFICAR SI EXISTE EL CODIGO ==============


                var url =  base_url+"index.php/admin/estructura_org_verificar";
                $.ajax({
                    type: "post",
                    url: url,
                    data: {uni_codigo: uni_codigo},
                    success: function (datos) {
                        if (parseInt(datos) == 1) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            
                            var url =  base_url+"index.php/admin/estructura_org_add";
                            $.ajax({
                                type: "post",
                                url: url,
                                data: {uni_unidad: uni_unidad, padre: padre, uni_codigo: uni_codigo},
                                success: function (data) {
                                    //alert(data)
                                    if (data = 'true') {
                                        $("#modal_nuevo_uni").css("display", "none");
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
                            $("#uni_codigo").closest('.form-group').removeClass('has-success').addClass('has-error');
                            alertify.error("EL CODIGO DE LA UNIDAD ORGANIZACIONAL YA EXISTE");
                        }
                    }
                });


            }
        });
    });
/////////////////////////////
$(document).ready(function () {
        $(".uni_si").click(function () {
            $('#content_parent').slideDown();
        })
        $(".uni_no").click(function () {
            $('#content_parent').slideUp();
        })
    });
////////////////////////////
//////////////////////////////////modificar cargo//////////////////////////////////////////////
 $(function () {
        var id_uni = '';
        $('#modal_mod_uni').on('hidden.bs.modal', function () {
            id_uni = '';
            document.forms['mod_formuni'].reset();
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

        $(".mod_uni").on("click", function (e) {
            //==========================LLENAR MIS DATOS DE FORMULARIO CON LA CLAVE RECIBIDA

            id_uni = $(this).attr('name');

            var url =  base_url+"index.php/admin/estructura_org_mod";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "id_uni=" + id_uni
            });
            request.done(function (response, textStatus, jqXHR) {
                document.getElementById("moduni_unidad").value = response.uni_unidad;
                document.getElementById("moduni_codigo").value = response.uni_id;
                document.getElementById("moduni_codigo").disabled = true;
                document.getElementById('moduni_si').disabled = false;
                document.getElementById('moduni_no').disabled = false;
                if (response.padre == 'NINGUNO') {
                    document.getElementById('moduni_no').checked = true;
                    document.getElementById('moduni_si').disabled = true;
                    $('#modcontent_parent').slideUp();
                } else {
                    document.getElementById('moduni_si').checked = true;
                    document.getElementById('moduni_no').disabled = true;
                    $('#modcontent_parent').slideDown();
                    document.getElementById("moduni_padre").value = response.uni_depende;
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
            $("#mod_unienviar").on("click", function (e) {
                var $validator = $("#mod_formuni").validate({
                    rules: {
                        moduni_unidad: { //// Programa
                            required: true,
                        },
                        moduni_padre: { //// Programa
                            required: true,
                        },
                    },
                    messages: {
                        moduni_unidad: "Ingrese el Nombre de la Unidad Organizacional",
                        moduni_padre: "Seleccione una Opcion",

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
                var $valid = $("#mod_formuni").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    //==========================================================
                    var moduni_unidad = document.getElementById("moduni_unidad").value;
                    var moduni_padre = document.getElementById("moduni_padre").value;
                    var url =  base_url+"index.php/admin/estructura_org_add";
                    $.ajax({
                        type: "post",
                        url: url,
                        data: {uni_unidad: moduni_unidad, uni_codigo: 0, padre: moduni_padre, modificar: id_uni},
                        success: function (data) {
                            if (data = 'true') {
                                $("#modal_mod_uni").css("display", "none");
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
        });
    });
///////////////////////////////////////////////////////eliminar cargo////////////////////////////////
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
        $(".del_uni").on("click", function (e) {
            reset();
            var name = $(this).attr('name');
            var request;
            alertify.confirm("REALMENTE DESEA ELIMINAR ESTE REGISTRO?", function (a) {
                if (a) {
                    var url =  base_url+"index.php/admin/estructura_org_del";
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