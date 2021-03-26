var puntos = [];
var lineas = [];
var circulos = [];
var rectangulos=[];
var poligonos=[];
function dibujarMapa(lat,lng,ubicacion,desplazamiento){
    puntos = [];
    lineas = [];
    circulos = [];
    rectangulos=[];
    poligonos=[];
    lat_d='-16.63619187839765';
    lng_d='-65.6103515625';
    if(ubicacion.toUpperCase()!='NULL' && ubicacion!=''){
        var v_ubicacion = JSON.parse(ubicacion);
        var v_puntos = v_ubicacion[0];
        var v_lineas = v_ubicacion[1];
        var v_circulos = v_ubicacion[2];
        var v_rectangulos = v_ubicacion[3];
        var v_poligonos = v_ubicacion[4];
        
    }
    
    var pts=[];
    map = new L.Map('map');
    osmTile = "http://tile.openstreetmap.org/{z}/{x}/{y}.png";
    osmCopyright = "2016 Gobernacion";
            
    //Para map google
    //ggl = new L.Google();
    //map.addLayer(ggl);
            
    //para map openstreepmap
    osmLayer = new L.TileLayer(osmTile, {
        maxZoom: 18, 
        attribution: osmCopyright
    } );
    map.addLayer(osmLayer);
            
    map.addControl(new L.Control.Layers( {
        'OSM':osmLayer, 
		
    }, {}));

    latlng = new L.LatLng(lat, lng);
            
    map.setView(latlng , desplazamiento );

    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);
    if(lat!=lat_d && lng !=lng_d && lat!='0' && lng !='0'){
        var markerIndex = L.marker(latlng);
        var icon = L.icon({
                iconUrl: '/images/marker-flag.png'
        });
        markerIndex.setIcon(icon);
        map.addLayer(markerIndex);
    }

    
    if(ubicacion.toUpperCase()!='NULL' && ubicacion!=''){

        for (var i = 0, len = v_puntos.length; i < len; i++) {
            marker = L.marker(new L.LatLng(v_puntos[i].lat,v_puntos[i].lng)).addTo(drawnItems);
            marker._leaflet_id = new L.LatLng(v_puntos[i].lat,v_puntos[i].lng);
            puntos.push(new L.LatLng(v_puntos[i].lat,v_puntos[i].lng));
        };

        //lineas
        for (var i = 0, len = v_lineas.length; i < len; i++) {
            pts=[];
            var linea = v_lineas[i];
            for (var j = 0, leng = linea.length; j < leng; j++) {
                pts.push(new L.LatLng(linea[j].lat,linea[j].lng));
            }
            polyline = L.polyline(pts, {color: '#FF6633',weight: 6}).addTo(drawnItems);
            polyline._leaflet_id = polyline.getLatLngs();
            lineas.push(polyline.getLatLngs());
        };
        
        //circulos
        for (var i = 0, len = v_circulos.length; i < len; i++) {
            var linea = v_circulos[i];
            var circulo2=[]; 
            circle =L.circle([v_circulos[i][0].lat,v_circulos[i][0].lng], v_circulos[i][1],{color: '#662d91'}).addTo(drawnItems);
            ra = circle.getRadius();
            pt = circle.getLatLng();
            circulo2.push(pt);
            circulo2.push(ra);
            circle._leaflet_id = circulo2;
            circulos.push(circulo2);
        };
        
        //rectangulos
        for (var i = 0, len = v_rectangulos.length; i < len; i++) {
            var linea = v_rectangulos[i];
            var pts=[];
            for (var i = 0, len = linea.length; i < len; i++) {
                pts.push(new L.LatLng(linea[i].lat,linea[i].lng));
            }
            rectangle = L.rectangle(pts, {color: "#FF6633", weight: 1}).addTo(drawnItems);
            
        };
        
        //poligon
        for (var i = 0, len = v_poligonos.length; i < len; i++) {
            pts=[];
            var linea = v_poligonos[i];
            for (var j = 0, leng = linea.length; j < leng; j++) {
                pts.push(new L.LatLng(linea[j].lat,linea[j].lng));
            }
            polygon = L.polygon(pts,{color: '#F5B800'}).addTo(drawnItems);
            polygon._leaflet_id = polygon.getLatLngs();
            poligonos.push(polygon.getLatLngs());
        };
        
    }//ubicacion coordenadas
                
    var drawControl = new L.Control.Draw({
        draw: {
            position: 'topleft',
            rectangle:false,
            
            //delete:false,
            polyline: {
                shapeOptions: {
                    color: '#f357a1',
                    weight: 6
                }
            },
            polygon: {
                title: 'Draw a sexy polygon!',
                allowIntersection: false,
                drawError: {
                    color: '#b00b00',
                    timeout: 1000
                },
                shapeOptions: {
                    color: '#F5B800',
                    weight: 6
                },
                showArea: true
            },
            circle: {
                shapeOptions: {
                    color: '#662d91'
                }
            }
        },
        edit: {
            featureGroup: drawnItems,
            edit:true,
            remove:true
        }
    });
    map.addControl(drawControl);
    map.on('dragend', function(e){
        var latlng= map.getCenter();
        var zoom = map.getZoom();
        map.setView(latlng , zoom );
        $('#lat').val(latlng.lat);  
        $('#lng').val(latlng.lng);
        $('#desplazamiento').val(zoom);
    });
    map.on('draw:created', function (e) {
        var type = e.layerType,
        layer = e.layer;
        var circulo=[];               
        //cod_punto=0;
        if (type === 'marker') {
            pt = layer.getLatLng();
            puntos.push(pt);
            layer._leaflet_id = new L.LatLng(pt.lat,pt.lng);
            console.log(layer);
        
        }
        if (type === 'polyline') {
            pt = layer.getLatLngs();
            lineas.push(pt);
            layer._leaflet_id = pt;
            console.log(layer)
        }
        if (type === 'polygon') {
            pt = layer.getLatLngs();
            poligonos.push(pt);
            layer._leaflet_id = pt;
            console.log(layer)
        }
        if (type === 'rectangle') {
            pt = layer.getLatLngs();
            rectangulos.push(pt);
        }
        if (type === 'circle') {
            ra = layer.getRadius();
            pt = layer.getLatLng();
            circulo.push(pt);
            circulo.push(ra);
            circulos.push(circulo);
            layer._leaflet_id = circulo;
            console.log(layer)
        }

        drawnItems.addLayer(layer);
    });
    Array.prototype.remove = function(x) { 
    for(i in this){
        if(this[i].toString() == x.toString()){
            this.splice(i,1)
        }
    }
}
    //accion para editar un objeto
    map.on('draw:edited', function (e) {
        var layers = e.layers;
        layers.eachLayer(function (layer) {
            
            var typeLayer = layer.toGeoJSON();
            var type = typeLayer.geometry.type;
            id=layer._leaflet_id;
            switch (type)
            {
            case 'Point':
                    if(layer._radius){
                        var circulo3=[];
                        circulos.remove(id);
                        ra = layer.getRadius();
                        pt = layer.getLatLng();
                        circulo3.push(pt);
                        circulo3.push(ra);
                        layer._leaflet_id=circulo3;
                        circulos.push(circulo3);
                    }else{
                        puntos.remove(id);
                        layer._leaflet_id=layer.getLatLng();
                        puntos.push(layer.getLatLng());
                    }
                    
              break;
            case 'LineString':
                    lineas.remove(id);
                    layer._leaflet_id=layer.getLatLngs();
                    lineas.push(layer.getLatLngs());
              break;
            case 'Polygon':
                    poligonos.remove(id);
                    layer._leaflet_id=layer.getLatLngs();
                    poligonos.push(layer.getLatLngs());
              break;
            default:
              alert('Ocurrio un error');
            }
            guardarCoordenada();
            
        });
    });
    //accion para borrar objeto 
    map.on('draw:deleted', function (e) {
        var layers = e.layers;
        layers.eachLayer(function (layer) {
            var typeLayer = layer.toGeoJSON();
            var type = typeLayer.geometry.type;
            id=layer._leaflet_id;
            
            switch (type)
            {
            case 'Point':
                if(layer._radius){
                        circulos.remove(id);
                        layer._radius=0;
                        layer.setLatLng(new L.LatLng(0,0));
                }else{
                    puntos.remove(id);
                    var icon = L.icon({
                        iconUrl: '/images/borrar.png',
                        iconSize: new L.Point(10, 16),
                        shadowSize: new L.Point(10, 16),
                        iconAnchor: new L.Point(10, 16)
                    });
                    layer.setIcon(icon);
                    drawnItems.removeLayer(layer);
                }
              break;
            case 'LineString':
                    lineas.remove(id);
                    layer.options.color='red';
                    layer.setLatLngs(new L.LatLng(0,0));
                    drawnItems.removeLayer(layer);
              break;
            case 'Polygon':
                    poligonos.remove(id);
                    layer.options.color='red';
                    layer.setLatLngs(new L.LatLng(0,0));
                    drawnItems.removeLayer(layer);
              break;
            default:
              alert('Ocurrio un error');
            }
            guardarCoordenada();
        });
    });
    
    
    return map;
}