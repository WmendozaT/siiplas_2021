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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

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
	<?php echo $img; ?>
</div>
</header> <!-- section-header.// -->

<div class="container">

<section class="section-content py-3">
	<div class="row">
		<aside class="col-lg-3"> 
	<!-- ============= COMPONENT ============== -->
		<nav class="sidebar card py-2 mb-4">
			<?php echo $menu; ?>
		</nav>
	<!-- ============= COMPONENT END// ============== -->	
	</aside>
	<main class="col-lg-9">

	<!-- <h6>Proyecto de Inversi&oacute;n / <?php echo $this->session->userData('gestion') ?></h6> -->
		<div id="detalle_proyecto"></div>
		<div id="reporte"></div>

	</main>
	</div>
</section>

</div><!-- container //  -->

<script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
<script>
    if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
</script>
<script src="<?php echo base_url(); ?>mis_js/ejec_proyectos/cns_proyectos.js"></script> 
</body>
</html>