$(function () {
// =============================    ASIGNAR OBJETIVO ESTRATEGICO    ========================================
    $(".asignar").on("click",function(e){
        reset();
        var name = $(this).attr('name');
        var poa = $(this).attr('id');
        var request;
        alertify.confirm("DESEA ASIGNAR EL OBJETIVO ESTRATÉGICO?", function (a) {
            if (a) {
                url = site_url+"/mantenimiento/cpoa/alta_baja_objetivo";
                if(request){
                    request.abort();
                }
                request = $.ajax({
                    url:url,
                    type:"POST",
                    data:"obj_id="+name+"&poa_id="+poa+"&accion=1"
                });
                request.done(function(response,textStatus,jqXHR){
                    //console.log("response: "+response);
                    window.location.reload(true);
                });
                request.fail(function(jqXHR,textStatus,thrown){
                    console.log("ERROR: "+ textStatus);
                });
                request.always(function(){
                    //console.log("termino la ejecuicion de ajax");
                });
                e.preventDefault();
            } else {
                // user clicked "cancel"
                alertify.error("OPCION CANCELADA");
            }
        });
        return false;
    });
// =============================    QUITAR OBJETIVO     ========================================
    $(".quitar").on("click",function(e){
        reset();
        var name = $(this).attr('name');
        var poa = $(this).attr('id');
        var request;
        alertify.confirm("DESEA QUITAR EL OBJETIVO ESTRATÉGICO?", function (a) {
            if (a) {
                url = site_url+"/mantenimiento/cpoa/alta_baja_objetivo";
                if(request){
                    request.abort();
                }
                request = $.ajax({
                    url:url,
                    type:"POST",
                    dataType: 'json',
                    data:"obj_id="+name+"&poa_id="+poa+"&accion=0"
                });
                request.done(function(response,textStatus,jqXHR){
                    //console.log("response: "+response);
                    if(response.dato == 1){
                        alertify.error("NO PUEDE QUITAR EL REGISTRO, ES DEPENDIENTE DE OTRAS VISTAS");
                    }else{
                        reset();
                        alertify.alert("EL REGISTRO SE ELIMIN\u00D3 CORRECTAMENTE", function (e) {
                            if (e) {
                                window.location.reload(true);
                            }
                        });
                    }
                });
                request.fail(function(jqXHR,textStatus,thrown){
                    console.log("ERROR: "+ textStatus);
                });
                request.always(function(){
                    //console.log("termino la ejecuicion de ajax");
                });
                e.preventDefault();
            } else {
                // user clicked "cancel"
                alertify.error("OPCION CANCELADA");
            }
        });
        return false;
    });


});
