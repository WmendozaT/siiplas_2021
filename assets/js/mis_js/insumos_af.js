//=============================== LISTA DE SERVICIOS===========
$("#lista_af").click(function () {
    //limpiar tablas
    $("#tabla_rhp").html("");
    $("#tabla_ser").html("");
    $("#tabla_pas").html("");
    $("#tabla_via").html("");
    $("#tabla_cpp").html("");
    $("#tabla_cl").html("");
    $("#tabla_mat").html("");
    $("#tabla_oi").html("");
    //------
    var url_af = site_url + '/insumos/programacion_insumos/tabla_af';
    $.ajax({
        type: "post",
        url: url_af,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_af").html(data);
        }
    });
});


