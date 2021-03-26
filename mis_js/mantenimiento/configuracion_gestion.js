//////////////////////////////////////////////// ACTIVAR/DESACTIVAR GESTION//////////////////////////////////
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
    // ============================ACTIVAR GESTION=========================================
    $(".act_gestion").on("click", function (e) {
        reset();
        var name = $(this).attr('name');
        console.log(name);
        var request;
        alertify.confirm("CONFIRMAR ACTIVAR GESTION?", function (a) {
            if (a) {
                var url =  site_url+"/mantenimiento/configuracion/activar_gestion";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: {'ide': name}
                });
                request.done(function (response, textStatus, jqXHR) {
                    console.log(response.resultado);
                    e.preventDefault();
                    alertify.success("La Operación se Realizó Correctamente");
                    // window.location.reload(true);
                    window.setTimeout(function(){location.reload()},500)
                    // $('#tr'+name).html("");
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });
            } else {
                alertify.error("Operación cancelada");
            }
        });
        return false;
    });
    // ============================DESACTIVAR GESTION=========================================
    $(".desact_gestion").on("click", function (e) {
        reset();
        var name = $(this).attr('name');
        console.log(name);
        var request;
        alertify.confirm("CONFIRMAR DESACTIVAR GESTION?", function (a) {
            if (a) {
                var url =  site_url+"/mantenimiento/configuracion/desactivar_gestion";
                if (request) {
                    request.abort();
                }
                request = $.ajax({
                    url: url,
                    type: "POST",
                    dataType: 'json',
                    data: {'ide': name}
                });
                request.done(function (response, textStatus, jqXHR) {
                    console.log(response.resultado);
                    e.preventDefault();
                    alertify.success("La Operación se Realizó Correctamente");
                    // window.location.reload(true);
                    window.setTimeout(function(){location.reload()},500);
                    // $('#tr'+name).html("");
                });
                request.fail(function (jqXHR, textStatus, thrown) {
                    console.log("ERROR: " + textStatus);
                });
                request.always(function () {
                    //console.log("termino la ejecuicion de ajax");
                });
            } else {
                alertify.error("Operación cancelada");
            }
        });
        return false;
    });

    
});