//-------------VER CAMBIOS DE CAMPO SELECCIONADO----------------// 
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
    //-------------------------------------
    $(".ver_auditoria").on("click", function (e) {
        reset();
        var name = $(this).attr('name');
        var id = $(this).attr('id');
        console.log(name);
        console.log(id);
        var request;
        var url = site_url+"/auditoria_proyecto/ver_auditorias";
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            data: {'id': id,
                   'tabla':name}
        });
        request.done(function (response, textStatus, jqXHR) {
            pasado = JSON.parse(response.old);
            nuevo = JSON.parse(response.new);
            console.log(response);
            $("#tbl_audi").empty();
            $("#nom_tabla").empty();
            columna = response.columna;
            $('<label>'+response.tabla+'</label>').appendTo('#nom_tabla');
            $('<tr><td>Nombre Campo</td><td>Dato Antiguo</td><td>Dato Nuevo</td></tr>').appendTo('#tbl_audi');
            for (var i = 0; i < response.n; i++) {
                col = columna[i];
                ol = pasado[col];
                ne = nuevo[col];
                if(ol != ne){
                    console.log(col);
                    console.log('Modificado: ');
                    console.log(ol);
                    console.log(ne);
                    $('<tr><td>'+col+'</td><td>'+ol+'</td><td>'+ne+'</td></tr>').appendTo($('#tbl_audi'));
                    // $('<table>').appendTo($('auditoria'));
                    // $('<p></p>').text('JSONGENERADO1').appendTo($('auditoria'));
                    // $('<br>').appendTo($('auditoria'));
                    // $('<tr>').text('JSONGENERADO2').appendTo($('auditoria'));
                    // $('<input>').attr({type: 'text', id:'employee_name'}).appendTo($('body'));
                }
            }
        });

    });
});

function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     location.reload();
     document.body.innerHTML = originalContents;
    //  $("#modal_ver_auditoria").on('hidden.bs.modal', function (e) {
    //      console.log('qweqweq');
    //     });
    // $("#modal_ver_auditoria").modal("hide");
}

function name(params) {
    
}