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

<!-- ======= Icons used for dropdown (you can use your own) ======== -->
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css"> -->

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

</style>

</head>
<body class="bg-light" style="background-color: #FFFFFF; padding-top: 15px">

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
                    Departamento Nacional de Planificación - CNS
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

<div class="container">

<section class="section-content py-3">
    <div class="row">
        <aside class="col-lg-5"> 
    <!-- ============= COMPONENT ============== -->
        <nav class="sidebar card py-2 mb-4">
            <?php echo $menu;?>
        </nav>
    <!-- ============= COMPONENT END// ============== -->   
    </aside>
    <main class="col-lg-7">
        <div class="well">
            <div id="detalle_proyecto"></div>
            <div id="reporte"></div>
        </div>
    </main>
    </div>
</section>

   
<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>

<script>
    if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
</script>
<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
<script src="<?php echo base_url(); ?>mis_js/ejec_proyectos/cns_proyectos.js"></script> 

</body>
</html>