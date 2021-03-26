
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/">
<meta name="DC.title" content="siipp">
<meta name="DC.identifier" content="">
<meta name="DC.description" content="SIIPP ">
<meta name="DC.subject" content="inversion, publica, sistema, informacion, software">
<meta name="DC.language" scheme="ISO639-1" content="es">
<meta name="DC.publisher" content="">
<meta name="DC.license" content="vipfe">
<meta name="DC.type" scheme="DCMITYPE" content="http://purl.org/dc/dcmitype/Software">
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/"/>
<meta name="DCTERMS.created" scheme="ISO8601" content="2016-08-09">
<title>LOCALIZACION GOBERNACION</title>
	
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/principal.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/menu.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/kgrid.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/tooltip.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/forms.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/jquery-ui.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/jqmodal.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/subModal.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/aux_ktr/ext-all-ktr.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/leaflet/leaflet.css">
<link rel="stylesheet" type="text/css" media="all" href="skins/ktr1/styles/leaflet/leaflet.draw.css">
<script type="text/javascript" src="scripts/jquery/jquery-1.7.2.min.js"></script>
<script type="text/javascript" 
src="scripts/sbs_proyectoInversion/mod_proyecto/inversionOpciones.js"></script>

<!--
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAdW5-R5IjRBX8ZOb-Rbp_ck_jxsglqHH4"></script>
-->
<script type="text/javascript" src="scripts/leaflet/leaflet.js"></script>
<script type="text/javascript" src="scripts/leaflet/leaflet.draw.js"></script>
<!--<script type="text/javascript" src="scripts/leaflet/Google.js"></script>
-->

<script type="text/javascript" src="scripts/leaflet/mapaGeoreferencial.js"></script>
<script type="text/javascript" src="scripts/sbs_proyectoInversion/mod_proyecto/localizacionGeografica.js"></script>

<!--<link rel="stylesheet" href="leaflet-search/src/leaflet-search.css" />
<script src="leaflet-search/src/leaflet-search.js"></script>-->
<script type="text/javascript" >

</script>

</head>
<body>
<?php 
$user = 'postgres';
$passwd = 'cns51stemas';
$db = 'cns';// siipp3
$port = 5432;
$host = 'localhost';
$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
$link = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
/**/
$vProyecto=$_GET['id'];
$sSQL="";
  $sSQL=$sSQL." SELECT * ";
  $sSQL=$sSQL." FROM _proyectos ";
  $sSQL=$sSQL." WHERE proy_id = ".$vProyecto." ";

  
  $RecordBD=pg_fetch_array(pg_query($sSQL));
   $varX=($RecordBD["proy_geo"]!='') ? $RecordBD["proy_geo"] : '[[],[],[],[],[]]' ;
   $lat=($RecordBD["lat"]!='') ? $RecordBD["lat"] : '-21.5354900' ;
   $lng=($RecordBD["lng"]!='') ? $RecordBD["lng"] : '-64.7295600' ;
   $desplazamiento=($RecordBD["desplazamiento"]!='') ? $RecordBD["desplazamiento"]: '11' ;
   $cod_territorio=($RecordBD["cod_territorio"]!='') ? $RecordBD["cod_territorio"] : '117' ;
?>
<div class="panel_button_bar_2">
<div class="button_bar_2">
<ul id="button_bar_ul">
<li id="graba" class="activ"><a href="javascript:guardarCoordenada();"><img class="img_main_menu" src="images/floppy.png" pagespeed_url_hash="3810232762">Grabar Ubicación</a></li>
<li id="Cerrar" class=""><a href="javascript:window.parent.jQuery('#myDiv').dialog('close');map.remove();"><img class="img_main_menu" src="images/cancelar.png" pagespeed_url_hash="3074728853">Cerrar</a></li>
</ul>
</div>
</div>
<div style="padding: 5px;">
<div id="map" style="height:540px;border:1px solid #54a1d1;"> </div><br/>
<form>
<label for="lat">Latitud:</label><input name="lat" id="lat" type="text" value="<?php echo $lat?>" readonly="readonly"/>&nbsp;
<label for="lng">Longitud:</label><input name="lng" id="lng" type="text" value="<?php echo $lng?>" readonly="readonly"/>
<input type="checkbox" name="editar" id="editar"/> Editar
<a href='#' id="refrescar" style="visibility: hidden">Refrescar Mapa</a>
<input name="enfoque" id="enfoque" type="hidden" value=""/>
<input type="hidden" id='cod_territorio' name="cod_territorio" value="<?php echo $cod_territorio?>"/>
<input type="hidden" id='cod_terr' name="cod_terr" value="<?php echo $cod_territorio?>"/>
<input type="hidden" id='desplazamiento' name="desplazamiento" value="<?php echo $desplazamiento?>"/>
<input type="hidden" id='id_p' name="id_p" value="<?php echo $vProyecto?>"/>
</form>
</div><script type="text/javascript">
ver_mapa('<?php echo $lat?>|<?php echo $lng?>|<?php echo $varX?>|<?php echo $desplazamiento?>');
</script>

</body>
</html>
