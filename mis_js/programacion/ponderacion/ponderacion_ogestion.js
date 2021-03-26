//--- PONDERACION
$(function () {
    //llenar mi formulario
    $(".form_pond").on("click", function () {
        var o_id = $(this).attr('name');
        document.getElementById("add_ponderacion").value = o_id;
        var request;
        url = site_url + "/programacion/cponderacion_ogestion/get_form_ogestion_pond";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "o_id=" + o_id
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
        var o_id = document.getElementById("add_ponderacion").value;
        var ponderacion = document.getElementById("pond").value;
        var request;
        url = site_url + "/programacion/cponderacion_ogestion/add_pond_ogestion";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "o_id=" + o_id + "&pond=" + ponderacion
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

});
