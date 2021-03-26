<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<link rel="STYLESHEET" href="<?php echo base_url(); ?>assets/print_static.css" type="text/css" />
<title><?php echo $this->session->userData('sistema');?></title>
</head>
<style type="text/css">
        @page {size: landscape;}
      </style>
<style>
#areaImprimir{background:#fff;}
.button-container {
    margin: 0 auto;
    padding: 5px; 0;
    width: 50%;
    border: thin solid gray;
    display: flex;
    justify-content: space-around;
}
body {
    background-color: #4C4F53;
}
</style>
  <script type="text/javascript">
    function printDiv(nombreDiv) {
     var contenido= document.getElementById(nombreDiv).innerHTML;
     var contenidoOriginal= document.body.innerHTML;
     document.body.innerHTML = contenido;
     window.print();
     document.body.innerHTML = contenidoOriginal;
}
  </script>

<body >
<table style="width:100%;">
	<tr>
		<td style="width:80%;"></td>
		<td style="width:20%;" align="center"><input type="button" name="name" onclick="printDiv('areaImprimir')" value="IMPRIMIR REPORTE"/></td>
	</tr>
</table>
<hr>
<div id="areaImprimir">
	<?php echo $vinculacion;?>
</div>

</body>
</html>
