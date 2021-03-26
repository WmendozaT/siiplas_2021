<?php

$user = 'postgres';
$passwd = 'cns51stemas';
$db = 'cns';// siipp3
$port = 5432;
$host = 'localhost';
$strCnx = "host=$host port=$port dbname=$db user=$user password=$passwd";
$link = pg_connect($strCnx) or die ("Error de conexion. ". pg_last_error());
// Puego agregar el registro 
$qRegistro=$_GET['id_p'];
     		$sSQL="UPDATE _proyectos 
		SET proy_geo = '".$_GET['ubicacionExacta']."',
		lat = '".$_GET['lat']."',
		lng = '".$_GET['lng']."',
		cod_territorio = '".$_GET['cod_territorio']."',
		desplazamiento = '".$_GET['zoom']."'
		 WHERE proy_id = ".$qRegistro;
	$Resultado=pg_fetch_array(pg_query($sSQL));/**/

	pg_query("DELETE FROM proyectos_t_area WHERE \"PROY_ID\"=".$qRegistro.";");
	pg_query("DELETE FROM proyectos_tp_puntos WHERE \"PROY_ID\"=".$qRegistro.";");
	pg_query("DELETE FROM proyectos_tp_linea WHERE \"PROY_ID\"=".$qRegistro.";");
	////---------------------------------
	  
	  /*-------------------------- PROYECTO ------------------------------------*/
	  $sSQL="";
	  $sSQL=$sSQL." SELECT * ";
	  $sSQL=$sSQL." FROM _proyectos ";
	  $sSQL=$sSQL." WHERE proy_id = ".$qRegistro." ";

	  $RecordBD=pg_fetch_array(pg_query($sSQL));
	  /*------------------------------------------------------------------------*/
	  
	  
	   $varX=($RecordBD["proy_geo"]!='') ? $RecordBD["proy_geo"] : '[[],[],[],[],[]]' ;
	   $lat=($RecordBD["lat"]!='') ? $RecordBD["lat"] : '-21.5354900' ;
	   $lng=($RecordBD["lng"]!='') ? $RecordBD["lng"] : '-64.7295600' ;
	   $desplazamiento=($RecordBD["desplazamiento"]!='') ? $RecordBD["desplazamiento"]: '11' ;
	   $cod_territorio=($RecordBD["cod_territorio"]!='') ? $RecordBD["cod_territorio"] : '117' ;
	//---------------------------------------
	
	$tipo=$_GET['geo'];
	$filas = explode("|", $_GET['geom']);
	$sql="";

	  /*-------------------------- PTDI ---------------------------------------*/
	  $ptdi="";
	  $ptdi=$ptdi." SELECT * ";
	  $ptdi=$ptdi." FROM ptdi ";
	  $ptdi=$ptdi." WHERE ptdi_id = ".$RecordBD["ptdi_id"]." ";

	  $Tptdi=pg_fetch_array(pg_query($ptdi));

	  if($RecordBD["tp_id"]==1){$tipo_proy='PROYECTO DE INVERSION';}
	  elseif ($RecordBD["tp_id"]==2) {$tipo_proy='PROGRAMA RECURRENTE';}
	  elseif ($RecordBD["tp_id"]==3) {$tipo_proy='PROGRAMA NO RECURRENTE';}
	  elseif ($RecordBD["tp_id"]==4) {$tipo_proy='ACCION DE FUNCIONAMIENTO';}
	  /*-----------------------------------------------------------------------*/
	  /*-------------------------- CLASIFICACION --------------------------------*/
	  $cls=""; $nivel='3';
	  $cls=$cls." SELECT cd3.descclasificadorsectorial as actividad, cd2.descclasificadorsectorial as subsector,cd1.descclasificadorsectorial as sector ";
	  $cls=$cls." FROM _clasificadorsectorial cd3 ";
	  $cls=$cls." Inner Join (select codsectorial,codsectorialduf,descclasificadorsectorial,codsubsec from _clasificadorsectorial where nivel='2') as cd2 On cd2.codsubsec=cd3.codsubsec ";
	  $cls=$cls." Inner Join (select codsectorial,codsectorialduf,descclasificadorsectorial,codsec from _clasificadorsectorial where nivel='1') as cd1 On cd1.codsec=cd3.codsec";
	  $cls=$cls." WHERE cd3.codsectorial='".$RecordBD["codsectorial"]."' and cd3.nivel=".$nivel." ";

	  $Tcls=pg_fetch_array(pg_query($cls));
	  /*-----------------------------------------------------------------------*/
	  /*-------------------------- PROYECTO RESPONSABLE --------------------------------*/
	  $resp=""; $tp='1';
	  $resp=$resp." SELECT f.fun_nombre,f.fun_paterno,f.fun_materno,u1.uni_unidad as uejec,u2.uni_unidad as uresp";
	  $resp=$resp." FROM _proyectofuncionario as pf";
	  $resp=$resp." Inner Join funcionario as f On pf.fun_id=f.fun_id ";
	  $resp=$resp." Inner Join unidadorganizacional as u1 On u1.uni_id=pf.uni_ejec ";
	  $resp=$resp." Inner Join unidadorganizacional as u2 On u2.uni_id=pf.uni_resp ";
	  $resp=$resp." WHERE pf.proy_id=".$RecordBD["proy_id"]." and pf.pfun_tp=".$tp." ";

	  $Tresp=pg_fetch_array(pg_query($resp));
	  /*-----------------------------------------------------------------------*/

	  /*-------------------------- LOCALIZACION - PROVINCIA --------------------------------*/
	  $prov=""; 
	  $prov=$prov." SELECT prov.prov_provincia as provincia";
	  $prov=$prov." FROM _proyectosprovincias as pp";
	  $prov=$prov." Inner Join _provincias as prov On pp.prov_id=prov.prov_id";
	  $prov=$prov." WHERE pp.proy_id=".$RecordBD["proy_id"]." ";
	  $prov=$prov." ORDER BY pp.pp_id ASC LIMIT 1 ";

	  $Tprov=pg_fetch_array(pg_query($prov));
	  /*-------------------------------------------------------------------------------------*/
	  /*-------------------------- LOCALIZACION - MUNICIPIO --------------------------------*/
	  $mun=""; 
	  $mun=$mun." SELECT mun.muni_municipio as municipio";
	  $mun=$mun." FROM _proyectosmunicipios as pm";
	  $mun=$mun." Inner Join _municipios as mun On pm.muni_id=mun.muni_id";
	  $mun=$mun." WHERE pm.proy_id=".$RecordBD["proy_id"]." ORDER BY pm.pm_id ASC LIMIT 1 ";

	  $Tmun=pg_fetch_array(pg_query($mun));
	  /*-------------------------------------------------------------------------------------*/

	  
	  $programa= strtoupper($Tptdi["ptdi_descripcion"]);
	  if($Tcls["actividad"]=='' && $Tcls["subsector"]==''&& $Tcls["sector"]=='')
	  {
	  $actividad = 'NO SELECCIONADO';
	  $subsector = 'NO SELECCIONADO';
	  $sector = 'NO SELECCIONADO';
	  }
	  else
	  {
	  $actividad = strtoupper($Tcls["actividad"]);
	  $subsector = strtoupper($Tcls["subsector"]);
	  $sector = strtoupper($Tcls["sector"]);
	  }

	  $f_inicio=date('d-m-Y',strtotime($RecordBD["proy_gestion_inicio_ddmmaaaa"]));
	  $f_final=date('d-m-Y',strtotime($RecordBD["proy_gestion_fin_ddmmaaaa"]));
	  $provincia =strtoupper($Tprov["provincia"]);
	  $municipio =strtoupper($Tmun["municipio"]);
	
	if ($tipo==1) {//punto 
		//$sql .= "DELETE FROM appetg_puntos WHERE proy_id=".$qRegistro.";";
		foreach ($filas as $coordenada) {
			$sql .= 
			"INSERT INTO proyectos_tp_puntos (
				\"PROY_ID\",\"COD_P\",\"COD_SISIN\",\"PROGRAMA\",\"SECTOR\", \"SUB_SECTOR\",\"ACTIVIDAD\", \"NOMBRE\",  \"TIPO\",\"F_INICIO\",\"F_FIN\", \"U_EJECUTOR\",\"U_RESPNSBL\", \"PROVINCIA\",\"MUNICIPIO\",\"GESTION_IN\",\"GESTION_FN\",\"POBLA_BENF\", the_geom)
	          VALUES (".$RecordBD["proy_id"].", '".$RecordBD["proy_codigo"]."', '".$RecordBD["proy_sisin"]."', '".$programa."', '".$sector."',  '".$subsector."', '".$actividad."', '".$RecordBD["proy_nombre"]."', '".$tipo_proy."',  '".$f_inicio."', '".$f_final."', '".$Tresp["uresp"]."', '".$Tresp["uejec"]."', '".$provincia."', '".$municipio."', '".$RecordBD["proy_gestion_inicio"]."', '".$RecordBD["proy_gestion_fin"]."','".$RecordBD["proy_poblac_beneficiada"]."', ST_GeomFromText('$coordenada',32719));";
	    }
	} else if ($tipo==2) {//linea
		//$sql .= "DELETE FROM appetg_linea WHERE proy_id=".$qRegistro.";";
		foreach ($filas as $coordenada) {
			$sql .= "INSERT INTO proyectos_tp_linea (	
				\"PROY_ID\",\"COD_P\",\"COD_SISIN\",\"PROGRAMA\",\"SECTOR\", \"SUB_SECTOR\",\"ACTIVIDAD\", \"NOMBRE\",  \"TIPO\",\"F_INICIO\",\"F_FIN\", \"U_EJECUTOR\",\"U_RESPNSBL\", \"PROVINCIA\",\"MUNICIPIO\",\"GESTION_IN\",\"GESTION_FN\",\"POBLA_BENF\", the_geom)
	          VALUES (".$RecordBD["proy_id"].", '".$RecordBD["proy_codigo"]."', '".$RecordBD["proy_sisin"]."', '".$programa."', '".$sector."',  '".$subsector."', '".$actividad."', '".$RecordBD["proy_nombre"]."', '".$tipo_proy."',  '".$f_inicio."', '".$f_final."', '".$Tresp["uresp"]."', '".$Tresp["uejec"]."', '".$provincia."', '".$municipio."', '".$RecordBD["proy_gestion_inicio"]."', '".$RecordBD["proy_gestion_fin"]."','".$RecordBD["proy_poblac_beneficiada"]."', ST_GeomFromText('$coordenada',32719));";
	    }
	} else if ($tipo==3) {//poligono
		//$sql .= "DELETE FROM appetg_area WHERE proy_id=".$qRegistro.";";
		foreach ($filas as $coordenada) {
			$sql .= "INSERT INTO proyectos_t_area ( 
				\"PROY_ID\",\"COD_P\",\"COD_SISIN\",\"PROGRAMA\",\"SECTOR\", \"SUB_SECTOR\",\"ACTIVIDAD\", \"NOMBRE\",  \"TIPO\",\"F_INICIO\",\"F_FIN\", \"U_EJECUTOR\",\"U_RESPNSBL\", \"PROVINCIA\",\"MUNICIPIO\",\"GESTION_IN\",\"GESTION_FN\",\"POBLA_BENF\", the_geom)
	          VALUES (".$RecordBD["proy_id"].", '".$RecordBD["proy_codigo"]."', '".$RecordBD["proy_sisin"]."', '".$programa."', '".$sector."',  '".$subsector."', '".$actividad."', '".$RecordBD["proy_nombre"]."', '".$tipo_proy."',  '".$f_inicio."', '".$f_final."', '".$Tresp["uresp"]."', '".$Tresp["uejec"]."', '".$provincia."', '".$municipio."', '".$RecordBD["proy_gestion_inicio"]."', '".$RecordBD["proy_gestion_fin"]."','".$RecordBD["proy_poblac_beneficiada"]."', ST_GeomFromText('$coordenada',32719));";
	    }
	} 
	pg_query($sql);
//PRINT $sql;
	print '1';
?>