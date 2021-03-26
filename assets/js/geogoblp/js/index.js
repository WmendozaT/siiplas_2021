function guardarCoordenada(){


    var ubicacion=[];
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
    
    var lat = $('#lat').val();
    var lng = $('#lng').val();
    var zoom = $('#desplazamiento').val();
    var cod_territorio = $('#cod_terr').val();

            if(pts+lin+cir+pol <= 1){

                $.ajax({
                    url: 'ubicacionGeoreferencial_graba.php?lat='+lat+'&lng='+lng+'&ubicacionExacta='+coordenada,
                    type: "GET",
                    success: function( resp ) {
                        if(resp==1)
                        {

                            alert('Se grabo la ubicación georeferencial del proyecto');

                            location.reload()
                        }else{
                            alert('no se guardo');
                        }

                    },
                    error: function( req, status, err ) {
                        console.log( 'error', status, err );
                    }
                });/**/
            }else{
                alert('Error. Solo puede guardar un solo tipo de objeto');
            }
        
}
//////////////////////////////////////////////////

 
























trails = {
	"type": "FeatureCollection",
	"features": [
	{
		"type": "Feature",
		"properties": {
			"name": "Tanyard Creek Park and Urban Forest, Ardmore Park, and Northside BeltLine Park/Peachtree Battle Trail and Northside BeltLine Trail",
			"distance": "~2.2 miles"
		},
		"geometry": {
			"type": "LineString",
			"coordinates": [
				[-68.288844930780911,-16.470163810726451],
				[-68.289133779064983,-16.471474067331759],
				[-68.289213848288568,-16.471837269729882],
				[-68.29152757739277,-16.474601711158726],
				[-68.287724180758445,-16.477268193323091 ],
				[-68.287726038623177,-16.477272781724082 ],
				[-68.288820249632465,-16.479974482835587 ],
				[-68.288818436078884,-16.479975965465549 ],
				[-68.288500814912439,-16.480235589117616 ],
				[-68.288465810781886,-16.482574999889856 ],
				[-68.288464296963085,-16.482676169007799 ],
				[-68.290778492168528,-16.485963061841137 ],
				[-68.291204904108525,-16.486564060671643 ],
				[-68.291773867735245,-16.487365969543863 ],
				[-68.290964748659761,-16.487981990672989 ],
				[-68.288201607989848,-16.490085647627545 ],
				[-68.287952920987735,-16.490274975926138 ],
				[-68.286673031790912,-16.491257579295528 ],
				[-68.286375086010707,-16.490723295339428 ],
				[-68.285273188824178,-16.489680201350744 ],
				[-68.284328741080174,-16.490383457527219 ],
				[-68.28139105375098,-16.4925708615215 ],
				[-68.281225050164736,-16.492694465131162 ],
				[-68.279964345591807,-16.49363315586421 ],
				[-68.279921165950938,-16.493665306097814 ],
				[-68.277274984424821,-16.495603990860005 ],
				[-68.287663783756429,-16.510372178803198 ],
				[-68.295737055218112,-16.510308013821373 ],
				[-68.295756751099219,-16.510313206093279 ],
				[-68.297309617112873,-16.510722533825973 ],
				[-68.301599814297319,-16.511106825774387 ],
				[-68.302620284815276,-16.51136316921723 ],
				[-68.305062776015859,-16.512203503733822 ],
				[-68.305325097431478,-16.51229375307565 ],
				[-68.306919511942809,-16.512790332841462 ],
				[-68.307364956368687,-16.512931114583441 ],
				[-68.315467456183811,-16.505830938803133 ],
				[-68.320130037244709,-16.501760226954293 ],
				[-68.314622512614491,-16.489926722935216 ],
				[-68.308413222084084,-16.477447498325205 ],
				[-68.301111028802254,-16.468234979098501 ],
				[-68.297591944934737,-16.470738207740265 ],
				[-68.295349814486443,-16.466886235240207 ],
				[-68.291243938626025,-16.468955074584049 ],
				[-68.288844930780911,-16.470163810726451 ],
				[-68.288844930780911,-16.470163810726451 ],
				[-68.288844930780911,-16.470163810726451],


				
			]
		}
	}
	]
}



  // map:
  var map = new L.map('map', {
    center: [-16.508470527666443, -68.16444725652569],
    zoom: 15
  });

  var hikeLayer = L.geoJson(trails, {
    style: {color: '#3498db',
      opacity: 0.9},
  }).addTo(map);

  // editable layers:
  var drawnItems = new L.FeatureGroup();
  map.addLayer(drawnItems);

  var drawControl = new L.Control.Draw({
    draw: {
      polygon: true,
      marker: true,
      rectangle: false,
      circle: false,
      polyline: {
        metric: false,
        shapeOptions: {
          opacity: 1,
          color: '#f07300',
          fillColor: '#f07300'
        }
      }
    },
    edit: {
      featureGroup: drawnItems
    }
  });

  //map.addControl(drawControl);


var puntos = [];
var lineas = [];
var circulos = [];
var rectangulos=[];
var poligonos=[];

 var drawControl = new L.Control.Draw({
        draw: {
            position: 'topright',
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
        //$('#lat').val(latlng.lat);  
        //$('#lng').val(latlng.lng);
        //$('#desplazamiento').val(zoom);
        //alert(latlng.lat);
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
    L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
    }).addTo(map);


    