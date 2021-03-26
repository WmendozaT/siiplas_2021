//=============================== LISTA DE SERVICIOS===========
$("#lista_ser").click(function(){
    //limpiar tablas
    $("#tabla_rhp").html("");
    //$("#tabla_ser").html("");
    $("#tabla_pas").html("");
    $("#tabla_via").html("");
    $("#tabla_cpp").html("");
    $("#tabla_cl").html("");
    $("#tabla_mat").html("");
    $("#tabla_af").html("");
    $("#tabla_oi").html("");
    //------
    var url_ser = site_url + '/insumos/programacion_insumos/tabla_ser';
    $.ajax({
        type: "post",
        url: url_ser,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_ser").html(data);
        }
    });
});
