//=============================== LISTA DE pasajes===========
$("#lista_pas").click(function(){
    //limpiar tablas
    $("#tabla_rhp").html("");
    $("#tabla_ser").html("");
    $("#tabla_via").html("");
    $("#tabla_cpp").html("");
    $("#tabla_cl").html("");
    $("#tabla_mat").html("");
    $("#tabla_af").html("");
    $("#tabla_oi").html("");
    //------
    var url_pas = site_url + '/insumos/programacion_insumos/tabla_pas';
    $.ajax({
        type: "post",
        url: url_pas,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_pas").html(data);
        }
    });
});




