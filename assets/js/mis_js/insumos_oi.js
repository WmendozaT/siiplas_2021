//=============================== LISTA DE otros insumos===========
$("#lista_oi").click(function(){
    //limpiar tablas
    $("#tabla_rhp").html("");
    $("#tabla_ser").html("");
    $("#tabla_pas").html("");
    $("#tabla_via").html("");
    $("#tabla_cpp").html("");
    $("#tabla_cl").html("");
    $("#tabla_mat").html("");
    $("#tabla_af").html("");
    //------
    var url_oi = site_url + '/insumos/programacion_insumos/tabla_oi';
    $.ajax({
        type: "post",
        url: url_oi,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_oi").html(data);
        }
    });
});
