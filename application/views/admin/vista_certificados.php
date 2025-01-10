<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="htmlcss bootstrap aside menu, vertical, sidebar nav menu CSS examples" />
<meta name="description" content="Bootstrap 5 sidebar navigation menu example" />  

<title>SIIPLAS </title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-skins.min.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>   
<link href="<?php echo base_url(); ?>assets/dashboard_pi/Content/css0d55.css" rel="stylesheet"/>
<style type="text/css">
.sidebar li .submenu{ 
    list-style: none; 
    margin: 0; 
    padding: 0; 
    padding-left: 1rem; 
    padding-right: 1rem;
}
.sidebar .nav-link {
    font-weight: 500;
    color: var(--bs-dark);
}
.sidebar .nav-link:hover {
    color: var(--bs-primary);
}
 .barra-color {
            width: 100%;
            height: 50px; /* Altura de la barra */
            background-color: #06601a; /* Color de la barra */
            text-align: center;
            line-height: 50px; /* Centra el texto verticalmente */
            color: white; /* Color del texto */
            font-size: 20px; /* Tamaño del texto */
        }
</style>

</head>
<body class="bg-light" style="background-color: #000000; padding-top: 15px">

<header class="section-header py-3">
<div class="container">
    <div class="row app-letrero">
        <div class="col-xs-12 col-md-3">
            <div class="app-row-center">
                <?php  echo $img1;?>
                
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="app-row-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 app-Title">
                    <b>Caja Nacional de Salud</b><br>Dpto. Nal. de Planificación
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <div class="app-row-center">
                <?php echo $img2;?>
            </div>
        </div>
    </div>
</div>

</header> <!-- section-header.// -->

<?php echo $cuerpo ?>

<script>
    if (!window.jQuery) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"><\/script>');
    }
</script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    }
</script>
<script src="<?php echo base_url();?>/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
<script src="<?php echo base_url(); ?>mis_js/ejec_proyectos/cns_proyectos.js"></script> 
</body>
</html>