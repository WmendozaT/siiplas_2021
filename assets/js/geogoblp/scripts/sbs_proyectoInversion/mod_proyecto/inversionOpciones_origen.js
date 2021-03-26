var cod_accion = new Array();
var vecAccion = new Array();


function localizacionGeografica_ver(){
    ajax_init("localizacionGeografica_vista", "detalle_inversion", "", localizacionGeografica_cargar_js);
}

function localizacionGeografica_cargar_js(){
    include_js_ajax("ktr/katari_dev2/scripts/common/ktree.js");
    include_js_ajax("scripts/sbs_proyectoInversion/mod_proyecto/localizacionGeografica.js");
}


function ubicacion_ver(){
    ajax_init("ubicacionGeoreferencial_vista.php", "detalle_inversion", '','');
}





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

function toWKT(layer) {
    var lng, lat, coords = [];
    if (layer instanceof L.Polygon || layer instanceof L.Polyline) {
        var latlngs = layer.getLatLngs();
        for (var i = 0; i < latlngs.length; i++) {
            latlngs[i]
            coords.push(latlngs[i].lng + " " + latlngs[i].lat);
            if (i === 0) {
                lng = latlngs[i].lng;
                lat = latlngs[i].lat;
            }
        };
        if (layer instanceof L.Polygon) {
            return "POLYGON((" + coords.join(",") + "," + lng + " " + lat + "))";
        } else if (layer instanceof L.Polyline) {
            return "LINESTRING(" + coords.join(",") + ")";
        }
    } else if (layer instanceof L.Marker) {
        return "POINT(" + layer.getLatLng().lng + " " + layer.getLatLng().lat + ")";
    }
}
//funcion convertir a UTM
function procesax(latitud,longitud) {
    
    var lat = latitud;
    var logi = longitud;
    var data = "";
    var a= 6378137.000 // semieje Mayor
    var b= 6356752.314  // semieje Menor
    var X= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/a;
    var X1= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/b;
    
    data+= "X = "+X+"<br>";
    data+= "X1 = "+X1+"<br>";
    
    var X1_2=X1*X1;
    data+= "X1_2 = "+X1_2+"<br>";
    
    var c= Math.pow(a,2)/b;
    data+= "c = "+c+"<br>";
    
// sextantes o radianes

    latr= (lat*Math.PI)/180;
    logir =(logi*Math.PI)/180;
    
    data+= "latr = "+latr+"<br>";
    data+= "longr = "+logir+"<br>";
    
//calculo HUSO  
    var h= Math.floor((logi/6)+31);
    data+= "h = "+h+"<br>";
// media HUSO
    var mh= (h*6) - 183;
    data+= "mh = "+mh+"<br>";
// delta lamda

    var DL = logir-((mh* Math.PI)/180);
    data+= "DL = "+DL+"<br>";
// constante A
    var A = Math.cos(latr)* Math.sin(DL);
    data+= "A = "+A+"<br>"; 
    
// constante Xi
    var Xi= (1/2) * Math.log((1+A)/(1-A));
    data+= "Xi = "+Xi+"<br>";
        
//  Eta
    var Eta=Math.atan(Math.tan(latr)/Math.cos(DL))-latr;
    data+= "Eta = "+Eta+"<br>";
//  Ni
    var Ni= (c/Math.sqrt(1+X1_2*(Math.pow(Math.cos(latr),2)))*0.9996);
        data+= "Ni = "+Ni+"<br>";
//  Zeta
    var Z= (X1_2/2)*Math.pow(Xi,2)* Math.pow(Math.cos(latr),2);
        data+= "Z = "+Z+"<br>";
// A1
    var A1= Math.sin(2*latr);
        data+= "A1 = "+A1+"<br>";
// A2
    var A2= A1* Math.pow(Math.cos(latr),2);
        data+= "A2 = "+A2+"<br>";
        
// J2
    var J2= latr+(A1/2);
        data+= "J2 = "+J2+"<br>";   
// J4
    var J4= (3*J2+A2)/4;
        data+= "J4 = "+J4+"<br>";
// J6
    var J6= (5*J4+(A2 * Math.pow(Math.cos(latr),2)))/3;
        data+= "J6 = "+J6+"<br>";       
// ALFA
    var AL= 3/4* X1_2; 
        data+= "AL = "+AL+"<br>";   
// BETA
    var BE= 5/3 * Math.pow(AL,2);
        data+= "BE = "+BE+"<br>";           
// GAMMA
    var GA= 35/27 * Math.pow(AL,3);
        data+= "GA = "+GA+"<br>";
// BFI
    var bfi= 0.9996*c*(latr-(AL*J2)+(BE*J4)-(GA*J6));
    data+= "<br>BFI = "+bfi+"<br>";
// calculo UTM X

    var UTMx=Xi*Ni*(1+Z/3)+500000;
    var UTMy=Eta*Ni*(1+Z)+bfi+10000000; 
            
    //document.getElementById('lat').value=UTMx;  
    //document.getElementById('lng').value=UTMy;      
     //return   (''+UTMx+','+UTMy); 
     return   (Math.floor(UTMx).toFixed(0)); 
   //document.getElementById("resultado").innerHTML=data;
}
//end funcion UTM
//funcion convertir a UTM
function procesay(latitud,longitud) {
    
    var lat = latitud;
    var logi = longitud;
    var data = "";
    var a= 6378137.000 // semieje Mayor
    var b= 6356752.314  // semieje Menor
    var X= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/a;
    var X1= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/b;
    
    data+= "X = "+X+"<br>";
    data+= "X1 = "+X1+"<br>";
    
    var X1_2=X1*X1;
    data+= "X1_2 = "+X1_2+"<br>";
    
    var c= Math.pow(a,2)/b;
    data+= "c = "+c+"<br>";
    
// sextantes o radianes

    latr= (lat*Math.PI)/180;
    logir =(logi*Math.PI)/180;
    
    data+= "latr = "+latr+"<br>";
    data+= "longr = "+logir+"<br>";
    
//calculo HUSO  
    var h= Math.floor((logi/6)+31);
    data+= "h = "+h+"<br>";
// media HUSO
    var mh= (h*6) - 183;
    data+= "mh = "+mh+"<br>";
// delta lamda

    var DL = logir-((mh* Math.PI)/180);
    data+= "DL = "+DL+"<br>";
// constante A
    var A = Math.cos(latr)* Math.sin(DL);
    data+= "A = "+A+"<br>"; 
    
// constante Xi
    var Xi= (1/2) * Math.log((1+A)/(1-A));
    data+= "Xi = "+Xi+"<br>";
        
//  Eta
    var Eta=Math.atan(Math.tan(latr)/Math.cos(DL))-latr;
    data+= "Eta = "+Eta+"<br>";
//  Ni
    var Ni= (c/Math.sqrt(1+X1_2*(Math.pow(Math.cos(latr),2)))*0.9996);
        data+= "Ni = "+Ni+"<br>";
//  Zeta
    var Z= (X1_2/2)*Math.pow(Xi,2)* Math.pow(Math.cos(latr),2);
        data+= "Z = "+Z+"<br>";
// A1
    var A1= Math.sin(2*latr);
        data+= "A1 = "+A1+"<br>";
// A2
    var A2= A1* Math.pow(Math.cos(latr),2);
        data+= "A2 = "+A2+"<br>";
        
// J2
    var J2= latr+(A1/2);
        data+= "J2 = "+J2+"<br>";   
// J4
    var J4= (3*J2+A2)/4;
        data+= "J4 = "+J4+"<br>";
// J6
    var J6= (5*J4+(A2 * Math.pow(Math.cos(latr),2)))/3;
        data+= "J6 = "+J6+"<br>";       
// ALFA
    var AL= 3/4* X1_2; 
        data+= "AL = "+AL+"<br>";   
// BETA
    var BE= 5/3 * Math.pow(AL,2);
        data+= "BE = "+BE+"<br>";           
// GAMMA
    var GA= 35/27 * Math.pow(AL,3);
        data+= "GA = "+GA+"<br>";
// BFI
    var bfi= 0.9996*c*(latr-(AL*J2)+(BE*J4)-(GA*J6));
    data+= "<br>BFI = "+bfi+"<br>";
// calculo UTM X

    var UTMx=Xi*Ni*(1+Z/3)+500000;
    var UTMy=Eta*Ni*(1+Z)+bfi+10000000; 
            
    //document.getElementById('lat').value=UTMx;  
    //document.getElementById('lng').value=UTMy;      
     //return   (''+UTMx+','+UTMy); 
     return   (Math.floor(UTMy).toFixed(0)); 
   //document.getElementById("resultado").innerHTML=data;
}
//end funcion UTM
//funcion convertir a UTM
function procesa(latitud,longitud) {
    
    var lat = latitud;
    var logi = longitud;
    var data = "";
    var a= 6378137.000 // semieje Mayor
    var b= 6356752.314  // semieje Menor
    var X= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/a;
    var X1= Math.sqrt( Math.pow(a,2) - Math.pow(b,2))/b;
    
    data+= "X = "+X+"<br>";
    data+= "X1 = "+X1+"<br>";
    
    var X1_2=X1*X1;
    data+= "X1_2 = "+X1_2+"<br>";
    
    var c= Math.pow(a,2)/b;
    data+= "c = "+c+"<br>";
    
// sextantes o radianes

    latr= (lat*Math.PI)/180;
    logir =(logi*Math.PI)/180;
    
    data+= "latr = "+latr+"<br>";
    data+= "longr = "+logir+"<br>";
    
//calculo HUSO  
    var h= Math.floor((logi/6)+31);
    data+= "h = "+h+"<br>";
// media HUSO
    var mh= (h*6) - 183;
    data+= "mh = "+mh+"<br>";
// delta lamda

    var DL = logir-((mh* Math.PI)/180);
    data+= "DL = "+DL+"<br>";
// constante A
    var A = Math.cos(latr)* Math.sin(DL);
    data+= "A = "+A+"<br>"; 
    
// constante Xi
    var Xi= (1/2) * Math.log((1+A)/(1-A));
    data+= "Xi = "+Xi+"<br>";
        
//  Eta
    var Eta=Math.atan(Math.tan(latr)/Math.cos(DL))-latr;
    data+= "Eta = "+Eta+"<br>";
//  Ni
    var Ni= (c/Math.sqrt(1+X1_2*(Math.pow(Math.cos(latr),2)))*0.9996);
        data+= "Ni = "+Ni+"<br>";
//  Zeta
    var Z= (X1_2/2)*Math.pow(Xi,2)* Math.pow(Math.cos(latr),2);
        data+= "Z = "+Z+"<br>";
// A1
    var A1= Math.sin(2*latr);
        data+= "A1 = "+A1+"<br>";
// A2
    var A2= A1* Math.pow(Math.cos(latr),2);
        data+= "A2 = "+A2+"<br>";
        
// J2
    var J2= latr+(A1/2);
        data+= "J2 = "+J2+"<br>";   
// J4
    var J4= (3*J2+A2)/4;
        data+= "J4 = "+J4+"<br>";
// J6
    var J6= (5*J4+(A2 * Math.pow(Math.cos(latr),2)))/3;
        data+= "J6 = "+J6+"<br>";       
// ALFA
    var AL= 3/4* X1_2; 
        data+= "AL = "+AL+"<br>";   
// BETA
    var BE= 5/3 * Math.pow(AL,2);
        data+= "BE = "+BE+"<br>";           
// GAMMA
    var GA= 35/27 * Math.pow(AL,3);
        data+= "GA = "+GA+"<br>";
// BFI
    var bfi= 0.9996*c*(latr-(AL*J2)+(BE*J4)-(GA*J6));
    data+= "<br>BFI = "+bfi+"<br>";
// calculo UTM X

    var UTMx=Xi*Ni*(1+Z/3)+500000;
    var UTMy=Eta*Ni*(1+Z)+bfi+10000000; 
            
    //document.getElementById('lat').value=UTMx;  
    //document.getElementById('lng').value=UTMy;      
     //return   (''+UTMx+','+UTMy); 
     return   (Math.floor(UTMx).toFixed(0)+','+Math.floor(UTMy).toFixed(0)); 
   //document.getElementById("resultado").innerHTML=data;
}
//end funcion UTM

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
    var id_p = $('#id_p').val();
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
    var geo = 0;
    if (pts==1) {geo = 1;} 
    else if(lin==1){geo = 2;}
    else if(pol==1){geo = 3;}else{geo = 0;};
    var coordenada=JSON.stringify(ubicacion);
    if($.isNumeric(lat) && $.isNumeric(lng)){
        if(cod_territorio){
            if(pts+lin+cir+pol <= 1){

                $.ajax({
                    url: 'ubicacionGeoreferencial_graba.php?lat='+lat+'&lng='+lng+'&id_p='+id_p+'&ubicacionExacta='+coordenada+'&cod_territorio='+cod_territorio+'&zoom='+zoom+'&geo='+geo,
                    type: "GET",
                    success: function( resp ) {
                        if(resp==1){
                            alert('Se grabo la ubicación georeferencial del proyectosss');
                            //location.reload()
                            //alert(JSON.stringify(coordenada));
                            //alert('punto:'+pts+' linea:'+lin)
                            //alert(toWKT(puntos));
                                var v_ubicacion =JSON.parse(coordenada);
                                var v_puntos = v_ubicacion[0];
                                var v_lineas = v_ubicacion[1];
                                var v_circulos = v_ubicacion[2];
                                var v_rectangulos = v_ubicacion[3];
                                var v_poligonos = v_ubicacion[4];
                                ////alert(JSON.stringify(v_puntos));
                                //puntos
                                var str_puntos='';
                                for (var i = 0, len = v_puntos.length; i < len; i++) {
                                   str_puntos='POINT ('+v_puntos[i].lat+' '+v_puntos[i].lng+')';
                                   alert(str_puntos)
                                };
                                
                                //lineas
                                var str_lineas='';
                                for (var i = 0, len = v_lineas.length; i < len; i++) {
                                    pts=[];
                                    var linea = v_lineas[i];
                                    str_lineas='LINESTRING(';
                                    for (var j = 0, leng = linea.length; j < leng; j++) {
                                        
                                        if ((leng-1)==j) {
                                            //alert(' menor n:'+j)
                                           str_lineas+=' '+procesax(linea[j].lat,linea[j].lng)+' '+procesay(linea[j].lat,linea[j].lng)+''; 
                                        } else{
                                            //alert(' igual n:'+j)
                                            str_lineas+=' '+procesax(linea[j].lat,linea[j].lng)+' '+procesay(linea[j].lat,linea[j].lng)+','; 
                                            //str_lineas+=' '+linea[j].lat+' '+linea[j].lng+',';
                                        };
                                    }
                                    str_lineas+=')';
                                    alert(str_lineas)
                                };
                                //alert('n lines:'+leng)
                                
                                //poligon
                                var str_poligon='';
                                for (var i = 0, len = v_poligonos.length; i < len; i++) {
                                    pts=[];
                                    var linea = v_poligonos[i];
                                    str_poligon='POLYGON((';
                                    for (var j = 0, leng = linea.length; j < leng; j++) {
                                        //pts.push(new L.LatLng(linea[j].lat,linea[j].lng));
                                         if ((leng-1)==j) {//str_poligon+=' '+linea[j].lat+' '+linea[j].lng+''; 
                                            str_poligon+=' '+procesax(linea[j].lat,linea[j].lng)+' '+procesay(linea[j].lat,linea[j].lng)+''; 
                                        } else{// str_poligon+=' '+linea[j].lat+' '+linea[j].lng+',';
                                            str_poligon+=' '+procesax(linea[j].lat,linea[j].lng)+' '+procesay(linea[j].lat,linea[j].lng)+','; 
                                        };
                                    }
                                    str_poligon+=' ) )';
                                    //polygon = L.polygon(pts,{color: '#F5B800'}).addTo(drawnItems);
                                    //polygon._leaflet_id = polygon.getLatLngs();
                                    //poligonos.push(polygon.getLatLngs());
                                    alert(str_poligon)
                                };
                                
                        }
                        else{
                            alert(resp);
                        }

                    
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


}
function cierraVentana(){
    $('#geo').jqmHide();
    map.remove();
}