
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


</head>
<body>
<?php 
$user = 'postgres';
$passwd = '$Cocae123';
$db = 'siipp';// siipp3
$port = 5432;
$host = 'localhost';
$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
$link = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
/**/

$sSQL="";
  $sSQL=$sSQL." SELECT * ";
  $sSQL=$sSQL." FROM funcionario ";
  //$sSQL=$sSQL." WHERE proy_id = ".$vProyecto." ";

  
  $RecordBD=pg_fetch_array(pg_query($sSQL));
   $varX=($RecordBD["fun_id"]!='') ? $RecordBD["fun_id"] : 'ooo' ;
   $lat=($RecordBD["fun_nombre"]!='') ? $RecordBD["fun_nombre"] : 'nombre' ;
   $lng=($RecordBD["fun_usuario"]!='') ? $RecordBD["fun_usuario"] : 'jhsjd' ;
print($varX.' '.$lat.' '.$lng.' ');
?>

</body>
</html>
