//--- PONDERACION DE PROGRAMAS
$(function () {
    //llenar mi formulario
    $(".form_pond_prog").on("click", function () {
        var aper_id = $(this).attr('name');
        document.getElementById("add_pond_prog").value = aper_id;
        var request;
        url = site_url + "/programacion/cponderacion_programas/get_form_programa_pond";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: {"aper_id": aper_id}
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: "+response);
            var apertura = response.programa + response.proyecto + response.actividad + " - " + response.descripcion;
            document.getElementById("codigo").value = response.codigo;
            document.getElementById("apertura").value = apertura;
            document.getElementById("pond").value = parseInt(response.ponderacion);
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });

    //guardar mi ponderacion
    $("#add_pond_prog").on("click", function () {
        var aper_id = document.getElementById("add_pond_prog").value;
        var ponderacion = document.getElementById("pond").value;
        var request;
        url = site_url + "/programacion/cponderacion_programas/add_pond_programa";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: {"aper_id": aper_id, "pond": ponderacion}
        });
        request.done(function (data, textStatus, jqXHR) {
            //console.log("response: "+response);
            if (data.peticion = 'true') {
                reset();
                alertify.alert("SE REALIZÓ LA PONDERACIÓN CORRECTAMENTE", function (e) {
                    if (e) {
                        window.location.reload(true);
                    }
                });
            } else {
                alertify.error("ERROR AL GUARDAR !!!");
            }
        });
        request.fail(function (jqXHR, textStatus, thrown) {
            console.log("ERROR: " + textStatus);
        });
        request.always(function () {
            //console.log("termino la ejecuicion de ajax");
        });
    });

    //asignar_ponderacion general
    $("#asignar_ponderacion").on("click", function () {
            reset();
            alertify.confirm("ESTA SEGURO ASIGNAR LA PONDERACIÓN A TODOS LOS PROGRAMAS ?", function (e) {
                if (e) {
                    //===========================
                    var request;
                    url = site_url + "/programacion/cponderacion_programas/ponderar_programas";
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: {"aper_id":1}
                    });
                    request.done(function (data, textStatus, jqXHR) {
                        //console.log("response: "+response);
                        if(data.peticion == 'true'){
                            reset();
                            alertify.alert("SE REALIZÓ LA PONDERACIÓN CORRECTAMENTE", function (e) {
                                if (e) {
                                    window.location.reload(true);
                                }
                            });
                        }else{
                            alertify.error("ERROR AL GUARDAR");
                        }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });

                } else {
                    alertify.error("PONDERACIÓN CANCELADA", function (e){});
                }
            });
    });
});
