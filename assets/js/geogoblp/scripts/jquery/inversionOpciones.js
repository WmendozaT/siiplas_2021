var cod_accion = new Array();
var vecAccion = new Array();

function aspectosGenerales_ver(){
    ajax_init("aspectosGenerales_vista", "detalle_inversion", "", aspectosGenerales_cargar_js);
}

function aspectosGenerales_cargar_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/aspectosGenerales.js");
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/aspectosGeneralesOpciones.js");
}

function problematica_ver(){
    ajax_init("problematica_vista.php", "detalle_inversion", "", problematica_carga_js);
}

function problematica_carga_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/problematica.js");
}

function localizacionGeografica_ver(){
    ajax_init("localizacionGeografica_vista", "detalle_inversion", "", localizacionGeografica_cargar_js);
}

function riesgo_ver(){
    ajax_init("riesgo_vista", "detalle_inversion", "", riesgo_cargar_js);
}

function riesgo_cargar_js(){
    //include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/riesgoOpciones.js");
}
function costoRiesgo_ver(){
    ajax_init("costoRiesgo_vista", "detalle_inversion", "", costoRiesgo_cargar_js);
}
function costoRiesgo_cargar_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/riesgoOpciones.js");
}

function inversionRelacion_ver(){
    ajax_init("inversionRelacion_vista", "detalle_inversion", "", inversionRelacion_cargar_js);
}
function inversionRelacion_cargar_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/proyectosRelacionados.js");
}
function localizacionGeografica_cargar_js(){
    include_js_ajax("/ktr/katari_dev2/scripts/common/ktree.js");
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/localizacionGeografica.js");
}

function faseComponente_ver(params){
    ajax_init("etapa_vista.php", "detalle_inversion", params, faseComponente_carga_js);
}

function faseComponente_carga_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/etapa.js");
    seleccionar_componente();
}

function seleccionar_componente(){
    cod_etapa=$("#cod_etapa option:selected").val();
    params = "cod_etapa=" + cod_etapa;
    ajax_init("componente_vista.php", "detalle_componente", params,"");
}


function empleo_ver(){
    ajax_init("empleo_vista.php", "detalle_inversion", "", empleo_carga_js);
}

function empleo_carga_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/empleo.js");
}


function sector_ver(){
    ajax_init("sector_vista.php", "detalle_inversion", "", sector_carga_js);
}
function sector_carga_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/sector.js");
}


function metas_ver(){
    ajax_init("metas_vista.php", "detalle_inversion", "", metas_carga_js);
}
function metas_carga_js(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/metas.js");
}

function ubicacion_ver(){
    ajax_init("ubicacionGeoreferencial_vista.php", "detalle_inversion", '','');
}


//function ver_estructura_financiamiento(refrescar)
//{
//    if(!refrescar==true){
//        cargar_combo_etapa()
//    };
//    ajax_init("estru_finan_vista.php", "detalle_inversion", "",carga_js_estru_finan);
//}
//function ver_resumen_costo_total()
//{
//    ajax_init("estru_finan_resumen_vista.php", "detalle_inversion", "", carga_js_estru_finan);
//}

//function cargar_combo_etapa(){
//    var param = 'cargando=1';
//    $.ajax({
//        type: "POST",
//        url: "actualizaEtapa.php",
//        data: param,
//        dataType: "json",
//        async:false,
//        success: function(datos){
//            if (datos.cod != true)
//                alert(datos.msg);
//        }
//    });
//}
function carga_js_estru_finan(){
    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/costos.js");
}

//function ver_costos_operacion()
//{
//    ajax_init("costos_operacion.php", "detalle_inversion", "", carga_js_estru_costos);
//}
//
//function carga_js_estru_costos()
//{
//    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/estructura_costos.js");
//}

//function verAvales(ininv_codig, tinic_codig)
//{
//    params = "cod_inversion=" + ininv_codig+ "&cod_modalidad_inversion="+tinic_codig;
//    ajax_init("inici_inver_defin_sesio", "detalle_inversion", params, carga_avales);
//}
//
//function carga_avales()
//{
//    include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/aspec_gener_abajo.js");
////include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/grid.js");
////include_js_ajax("/scripts/sbs_proyectoInversion/mod_proyecto/aspectosGeneralesOpciones.js");
////  obtiene_clasif_accion();
//}

function llenaTipoInversion(){
    var accionSel = $("#form\\[cod_accion_inversion\\]").val();
    var opciones ='<option value="0">Seleccionar</option>';
    var objTipo=$("#form\\[cod_tipo_inversion\\]")
    var tipoSel=objTipo.val();
    var seleccionado='';
    for(var k in vecAccion[accionSel]){
        if (k==tipoSel)
            seleccionado='selected ="selected"';
        else
            seleccionado='';

        opciones += '<option '+seleccionado+'  value="'+k+'">'+vecAccion[accionSel][k]+'</option>';
    }
    $(objTipo).html(opciones);
}



//function obtiene_clasif_accion(){
//    datos = ajax_json_init_s("obtie_clasi_accio", '');
//    var auxLen = datos.length;
//    for (f=0;f<auxLen;f++)
//    {
//        cod_accion[datos[f].codig] = datos[f].valor;
//    }
//    $.getJSON('tipoInversion_cb.php', function(data) {
//        vecAccion=data;
//    })
//}
var map;
function ver_mapa(coordenada){
    var elemento=coordenada.split('|');
    var lat = elemento[0];
    var lng = elemento[1];
    var ubicacionExacta = elemento[2];
    var desplazamiento = elemento[3];
    map = dibujarMapa(lat,lng,ubicacionExacta,desplazamiento);
    $('#refrescar').click(function(){
        var lngVal = /^-?((1?[0-7]?|[0-9]?)[0-9]|180)\.[0-9]{1,6}$/;
        var latVal = /^-?([0-8]?[0-9]|90).[0-9]{1,6}$/;
        var lat = $('#lat').val();
        var lng = $('#lng').val();
        if(isNaN(lat)) {
            alert("Error:\nEste campo debe tener sólo números.");
            $('#lat').focus();
            return false;
        }
        if(isNaN(lng)) {
            alert("Error:\nEste campo debe tener sólo números.");
            $('#lng').focus();
            return false;
        }
        /*if (!lngVal.test(lng)){
            alert("Error:\nNo es una longitud");
            $('#lng').focus();
            return false;
        }
        if (!latVal.test(lat)){
            alert("Error:\nNo es una latitud");
            $('#lat').focus();
            return false;
        }*/
        var zoom = map.getZoom();
        $('#desplazamiento').val(zoom);
        var london = new L.LatLng(lat,lng);
        map.setView(london, zoom);
        var marker = L.marker(london);
        var icon = L.icon({
            iconUrl: '/images/marker-flag.png'
        });
        marker.setIcon(icon);
        map.addLayer(marker);
    });
    $("#editar").click(function(event){
     if($(this).is(":checked")) {
	 	 $("#lat").removeAttr("readonly");
                 $("#lng").removeAttr("readonly");
                 $("#refrescar").css("visibility", "visible");
	 }else{
		$("#lat").attr("readonly", "readonly");
                $("#lng").attr("readonly", "readonly");
                $("#refrescar").css("visibility", "hidden");
	 }
   });
    /*var marker;
    var map = dibujarMapa(lat,lng,ubicacionExacta,desplazamiento);//batallas
    //dibujarMapa(-16.498553,-68.135474);//la paz
    $('#polygon').click(function(){
        marker = polygon(map);
        var zoom = map.getZoom();
        $('#desplazamiento').val(zoom);
    });
    $('#polygonDelete').click(function(){
        $.each(markerArray,function(indice,valor) {
            map.removeLayer(valor);
        });
        map.removeLayer(polygonGlobal);
        map.off('click');
        $('#mymap').css( 'cursor', 'hand' );
    });

    */
}
function distancia(lat,lng,cod_territorio){
    var resultado;
    $.ajax({
            url: 'distanciaPuntos.php?lat='+lat+'&lng='+lng+'&cod_territorio='+cod_territorio,
            type: "GET",
            async: false,
            success: function( resp ) {
                resultado = resp;
            },
            error: function( req, status, err ) {
                console.log( 'error', status, err );
            }
     });
     return resultado;
}
function guardarCoordenada(){
    var lat = $('#lat').val();
    var lng = $('#lng').val();
    var zoom = $('#desplazamiento').val();
    var cod_territorio = $('#cod_terr').val();
    var respuesta_distancia = distancia(lat,lng,cod_territorio);
    var ubicacion=[];
    if(parseInt(respuesta_distancia)=='0')
        alert('Le recomendamos revisar la distancia por que excede el límite del territorio');


    ubicacion.push(puntos);
    ubicacion.push(lineas);
    ubicacion.push(circulos);
    ubicacion.push(rectangulos);
    ubicacion.push(poligonos);

    var pts = (puntos.length > 0 ? 1 : 0);
    var lin = (lineas.length > 0 ? 1 : 0);
    var cir = (circulos.length > 0 ? 1 : 0);
    var pol = (poligonos.length > 0 ? 1 : 0);

    var coordenada=JSON.stringify(ubicacion);
    if($.isNumeric(lat) && $.isNumeric(lng)){
        if(cod_territorio){
            if(pts+lin+cir+pol <= 1){

                $.ajax({
                    url: 'ubicacionGeoreferencial_graba.php?lat='+lat+'&lng='+lng+'&ubicacionExacta='+coordenada+'&cod_territorio='+cod_territorio+'&zoom='+zoom,
                    type: "GET",
                    success: function( resp ) {
                        if(resp==1)
                            alert('Se grabo la ubicación georeferencial del proyecto');
                        else
                            alert(resp);

                    //alert(JSON.stringify(resp));
                    },
                    error: function( req, status, err ) {
                        console.log( 'error', status, err );
                    }
                });
            }else{
                alert('Error. Solo puede guardar un solo tipo de objeto');
            }
        }else
         alert('Error. Complete los datos de la pestaña Localización Geográfica');
    }else{
        alert("Error:\nLatitud o Longitud no son correctos.");
    }
}
var refDialogo;
function abre_georeferenciacion(cod_territorio,cod_inversion){
    var params = "cod_inversion="+cod_inversion+'&cod_territorio='+cod_territorio;
    var vista = 'ubicacionGeoreferencial_vista.php?'+params;
    $("#iframeGeo").attr("src", vista);
    $myWindow = jQuery('#myDiv');
    refDialogo=$myWindow.dialog({ height: 500,
                        width: 850,
                        modal: true,
                        position: 'center',
                        autoOpen:false,
                        title:'Geolocalización'
                        //overlay: { opacity: 0.5, background: 'black'}
    });
    $myWindow.show();
    $myWindow.dialog("open");

    // auxHtml = '<p> Cargando ... </p>';
    // $('#geo').jqm({
    //     ajax:vista,
    //     modal: true,
    //     overlay: 5,
    //     ajaxText: auxHtml,
    //     onLoad: function(h) {
    //         $('#geo .panel_button_bar_2 #button_bar_ul').append('<li id=""><a href="javascript:cierraVentana();"><img src="/images/cancelar.png" class="img_main_menu">Cerrar</a></li>');
    //     }
    // });

    // $('#geo').jqmShow();
}
function cierraVentana(){
    $('#geo').jqmHide();
    map.remove();
}