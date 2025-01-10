base = $('[name="base"]').val();

function abreVentana(PDF){             
  var direccion;
  direccion = '' + PDF;
  window.open(direccion, "FICHA TECNICA" , "width=900,height=900,scrollbars=NO") ; 
}


  function doSearch(){
    var tableReg = document.getElementById('datos');
    var searchText = document.getElementById('searchTerm').value.toLowerCase();
    var cellsOfRow="";
    var found=false;
    var compareWith="";

    // Recorremos todas las filas con contenido de la tabla
    for (var i = 1; i < tableReg.rows.length; i++){
      cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
      found = false;
      // Recorremos todas las celdas
      for (var j = 0; j < cellsOfRow.length && !found; j++){
        compareWith = cellsOfRow[j].innerHTML.toLowerCase();
        // Buscamos el texto en el contenido de la celda
        if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)){
          found = true;
        }
      }
      if(found) {
        tableReg.rows[i].style.display = '';
      } else {
        // si no ha encontrado ninguna coincidencia, esconde la
        // fila de la tabla
        tableReg.rows[i].style.display = 'none';
      }
    }
  }


  $( function() {
    $( "#grupoTablas" ).tabs();
  } );

  function justNumbers(e){
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;           
    return /\d/.test(String.fromCharCode(keynum));
  }

    //// funcion para generar el cuadro de seguimiento POa Mensual (cuadro, grafico)
  function generar_reporte(proy_id){

    $('#reporte').html('<div class="loading" align="center"><img src="'+base+'/assets/img_v1.1/preloader.gif" alt="loading" /><br/>Cargando Informaciónn...</div>');
    var url = base+"index.php/reporte_ejecucion_proyectos/consulta_pi/get_reporte_proyecto";
    var request;
    if (request) {
        request.abort();
    }
    request = $.ajax({
      url: url,
      type: "POST",
      dataType: 'json',
      data: "proy_id="+proy_id
    });

    request.done(function (response, textStatus, jqXHR) {
        
      if (response.respuesta == 'correcto') {
          document.getElementById('reporte').innerHTML = response.iframe;

/*var Icono = L.icon({
        iconUrl: base+"assets/ifinal/cns3.JPG",
        iconSize: [30, 40],
        iconAnchor: [15, 40],
        //shadowUrl: "https://vivaelsoftwarelibre.com/wp-content/uploads/2020/05/icono_sombra.png",
        shadowSize: [35, 50],
        shadowAnchor: [0, 55],
        popupAnchor: [0, -40]});
*/

 var cloudmadeUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    var satelitalUrl = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}';

    var basemap = new L.TileLayer(cloudmadeUrl, { maxZoom: 19 });
    var satmap = new L.TileLayer(satelitalUrl, { maxZoom: 19 });

    

    var baseLayers = {
        "Ver mapa": basemap,
        "Satelital": satmap
    };
          var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
          osmAttrib = '&copy; Caja Nacional de Salud / 2023',
          osm = L.tileLayer(osmUrl, {maxZoom: 19, attribution: osmAttrib});
          
          var map = L.map('map').setView([response.proyecto[0]['lat'], response.proyecto[0]['lng']], 14).addLayer(osm);
          L.control.layers(baseLayers).addTo(map);
          L.marker([response.proyecto[0]['lat'], response.proyecto[0]['lng']])
          .addTo(map)
          .bindPopup(response.foto+'<br>'+response.proyecto[0]['proy']+' - '+response.proyecto[0]['proyecto'])
          .openPopup();
         /* L.marker([response.proyecto[0]['lat'], response.proyecto[0]['lng']], {
          title: response.proyecto[0]['proy']+' - '+response.proyecto[0]['proyecto'], 
          draggable:false, 
          opacity: 1,
          icon: Icono
            })
          .addTo(map)
          .bindPopup(response.foto+'<br>'+response.proyecto[0]['proy']+' - '+response.proyecto[0]['proyecto'])
            .openPopup();;*/

      }
      else{
          alertify.error("ERROR !!!");
      }
    }); 
  }


/////---------verif ci--------

    $("#verif_ci").on("click", function () {
    var $validator = $("#form_ci").validate({
        rules: {
          ci: { //// Cite
              required: true,
          }
        },
        messages: {
          ci: "<font color=red>REGISTRE CI</font>",                       
        },
        highlight: function (element) {
          $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        unhighlight: function (element) {
          $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
          if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
          } else {
              error.insertAfter(element);
          }
        }
      });

      var $valid = $("#form_ci").valid();
      if (!$valid) {
          $validator.focusInvalid();
      } else {
        even_id=$('[name="even_id"]').val();
        ci=$('[name="ci"]').val();
        $('#loading').html('<div class="loading" align="center"><img src="'+base+'/assets/img/loading.gif" alt="loading" /><br/>Buscando Certificado ...</div>');
        var url = base+"index.php/mantenimiento/ceventos_dnp/get_ci";
        var request;
        if (request) {
            request.abort();
        }
          request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "ci="+ci+"&even_id="+even_id
          });

          request.done(function (response, textStatus, jqXHR) {

          if (response.respuesta == 'correcto') {
            $('#loading').fadeIn(1000).html(response.tabla);
          }
          else{
            alertify.error("ERROR AL RECUPERAR INFORMACION");
          }

        });
      }
  });



       



