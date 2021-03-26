//=============================== LISTA DE SERVICIOS===========
$("#lista_mat").click(function(){
    //limpiar tablas
    $("#tabla_rhp").html("");
    $("#tabla_ser").html("");
    $("#tabla_pas").html("");
    $("#tabla_via").html("");
    $("#tabla_cpp").html("");
    $("#tabla_cl").html("");
    $("#tabla_af").html("");
    $("#tabla_oi").html("");
    //------
    var url_mat = site_url + '/insumos/programacion_insumos/tabla_mat';
    $.ajax({
        type: "post",
        url: url_mat,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_mat").html(data);
        }
    });
});

