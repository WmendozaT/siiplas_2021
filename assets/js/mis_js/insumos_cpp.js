//=============================== LISTA DE consultoria por producto===========
$("#lista_cpp").click(function(){
    //limpiar tablas
    $("#tabla_rhp").html("");
    $("#tabla_ser").html("");
    $("#tabla_pas").html("");
    $("#tabla_via").html("");
    $("#tabla_cl").html("");
    $("#tabla_mat").html("");
    $("#tabla_af").html("");
    $("#tabla_oi").html("");
    //------
    var url_cpp = site_url + '/insumos/programacion_insumos/tabla_cpp';
    $.ajax({
        type: "post",
        url: url_cpp,
        data: {
            act_id: act_id,
        },
        success: function (data) {
            $("#tabla_cpp").html(data);
        }
    });
});




