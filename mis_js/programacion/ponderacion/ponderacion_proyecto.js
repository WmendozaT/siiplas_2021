//--- PONDERACION DE PROYECTOS
$(function () {
    //llenar mi formulario
    $(".add_pond").on("click", function () {
        var proy_id = $(this).attr('name');
        document.getElementById("add_ponderacion").value = proy_id;
        var request;
        url = site_url + "/programacion/cponderacion_proyecto/get_proy";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "proy_id=" + proy_id
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: "+response);
            document.getElementById("codigo").value = response.codigo;
            document.getElementById("objetivo").value = response.objetivo;
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
    $("#add_ponderacion").on("click", function () {
        var proy_id = document.getElementById("add_ponderacion").value;
        var ponderacion = document.getElementById("pond").value;
        //===========================
        var request;
        url = site_url + "/programacion/cponderacion_proyecto/guardar_pond";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: {"proy_id": proy_id, "pond": ponderacion}
        });
        request.done(function (response, textStatus, jqXHR) {
            //console.log("response: "+response);
            reset();
            alertify.alert("SE REALIZÓ LA PONDERACIÓN CORRECTAMENTE", function (e) {
                if (e) {
                    window.location.reload(true);
                }
            });

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
        var aper_id = document.getElementById("asignar_ponderacion").value;
        //------------------        verificar si existe proyectos
        reset();
        alertify.confirm("ESTA SEGURO ASIGNAR LA PONDERACIÓN A TODOS LOS PROYECTOS ?", function (e) {
            if (e) {
                //===========================
                var request;
                url = site_url + "/programacion/cponderacion_proyecto/ponderar_proyectos";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    data: {"aper_id": aper_id}
                });
                request.done(function (response, textStatus, jqXHR) {
                    //console.log("response: "+response);
                    reset();
                    alertify.alert("SE REALIZÓ LA PONDERACIÓN CORRECTAMENTE", function (e) {
                        if (e) {
                            window.location.reload(true);
                        }
                    });

                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });

            } else {
                alertify.error("PONDERACIÓN CANCELADA", function (e) {
                });
            }
        });


    });

});
