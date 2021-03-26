(function ($) {
    
    $( document ).on( "idle.idleTimer", function(event, elem, obj){
        console.log('INACTIVO');
        ruta_alerta = base_url + 'assets/themes_alerta/alertify.default.css';
        $("#toggleCSS").attr("href", ruta_alerta);
        alertify.alert('<h2 style="color:#E62117;">Por Seguridad y Inactividad <br> La Sesión Termino </h2><br><h4>Por favor vuelva a iniciar la sesión</h4>', function(){
            var logout = site_url+'/admin/logout';
            window.location.href = logout;
            alertify.success('Sesión Finalizada');
        }).setting('modal', true);
    });
    
    $( document ).on( "active.idleTimer", function(event, elem, obj, triggerevent){
        console.log('ACTIVO');
    });
    $.idleTimer(1800000);
    // console.log($.idleTimer(3000));
})(jQuery);