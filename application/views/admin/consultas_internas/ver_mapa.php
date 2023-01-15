<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Proyectos de Inversión</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css">
</head>
<body>
<!-- <h1>UBICACIÓN</h1>  -->
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>	  
<div id="map" class="map map-home" style="margin:12px 0 12px 0;height:650px; width:650px"></div>
<?php 
    $lat = "-16.522502"; // recibir de un post o Get
    $lng = "-68.153588";
    //$pos = "42.822410654302125,-1.6459033203125273";
	$titulo = "prueba" ; //nombre del proyecto , quizas podamos poner mas detalle
	?>
<script>
	var osmUrl = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
		osmAttrib = '&copy; 2023',
		osm = L.tileLayer(osmUrl, {maxZoom: 18, attribution: osmAttrib});
	var map = L.map('map').setView([42.8224106543021255, -1.6459033203125273], 17).addLayer(osm);
	L.marker([<?php echo $lat; ?>, <?php echo $lng; ?>])
		.addTo(map)
		.bindPopup('<?php echo $titulo ; ?>')
		.openPopup();
</script>
  </body>
</html>