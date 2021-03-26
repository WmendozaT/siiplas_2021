<!DOCTYPE html>
<html lang="en-us" id="lock-page">
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->session->userdata('name')?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        
        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/font-awesome.min.css">

        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/smartadmin-skins.min.css">
        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/demo.min.css">

        <!-- page related CSS -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url()?>assets/css/lockscreen.min.css">
        
        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        
        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo base_url()?>assets/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo base_url()?>assets/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo base_url()?>assets/img/splash/iphone.png" media="screen and (max-device-width: 320px)">

    </head>
    
    <body>

        <style type="text/css">
          #preloader_1{
    position:relative;
}
#preloader_1 span{
    display:block;
    bottom:0px;
    width: 9px;
    height: 5px;
    background:#9b59b6;
    position:absolute;
    animation: preloader_1 1.5s  infinite ease-in-out;
}
 
#preloader_1 span:nth-child(2){
left:11px;
animation-delay: .2s;
 
}
#preloader_1 span:nth-child(3){
left:22px;
animation-delay: .4s;
}
#preloader_1 span:nth-child(4){
left:33px;
animation-delay: .6s;
}
#preloader_1 span:nth-child(5){
left:44px;
animation-delay: .8s;
}
@keyframes preloader_1 {
    0% {height:5px;transform:translateY(0px);background:#9b59b6;}
    25% {height:30px;transform:translateY(15px);background:#3498db;}
    50% {height:5px;transform:translateY(0px);background:#9b59b6;}
    100% {height:5px;transform:translateY(0px);background:#9b59b6;}
}
        </style>
        <div id="main" role="main">
            <!-- MAIN CONTENT -->
            <form class="lockscreen animated flipInY" action="<?php echo base_url(); ?>index.php/admin/mods_contras" method="post">
                <div class="logo">
                    <h1 class="semi-bold"><img src="<?php echo base_url()?>assets/img/logo-o.png" alt=""/> SIIPLAS V1.0</h1>
                </div>
                <div><br><br>
                    <img src="<?php echo base_url()?>assets/img/avatars/seguridad.png" alt="" width="120" height="140" />
                    <div>
                        <h1>   
                            <i class="fa fa-user fa-3x text-muted air air-top-right hidden-mobile"></i>
                            <?php echo ''.$this->session->userdata("funcionario");?>
                            <small>
                                <i class="fa fa-lock text-muted"></i> 
                                <p>&nbsp;Modificar Contraseña del usuario:</p> 
                                <center>
                                    <?php echo $this->session->userdata("usuario");?>
                                </center>
                            </small>
                        </h1>
                        <div class="input-group">
                            <section>
                                <label class="label" style="color:black;">Contraseña Actual</label>
                                <input type="password" name="apassword" id="apassword" placeholder="Contraseña Actual" class="form-control" required>
                            </section>
                            <section>
                                <label class="label" style="color:black;">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" placeholder="Contraseña Actual" class="form-control" required>
                            </section>
                            <input class="form-control" name="fun_id" id="fun_id" type="hidden" placeholder="ID" value="<?php echo $this->session->userdata("fun_id");?>">
                            <button type="submit" class="btn btn-primary">Modificar </button>
                        </div>
                        <p class="no-margin margin-top-5">
                            Para regresar <a href="<?php echo base_url();?>index.php/admin/dashboard">Haga clic aqui</a>
                        </p>
                    </div>
                </div>
                <p class="font-xs margin-top-5">
                   SIIPLAS © <?php echo $this->session->userdata('gestion');?>
                    <div id="preloader_1">
                        <span style="color: #000;"></span>
                        <span style="color: #000;"></span>
                        <span style="color: #000;"></span>
                        <span style="color: #000;"></span>
                        <span style="color: #000;"></span>
                    </div>
                </p>
            </form>
        </div>
        <!--================================================== -->
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script src="<?php echo base_url()?>assets/js/plugin/pace/pace.min.js"></script>
        <!-- BOOTSTRAP JS -->       
        <script src="<?php echo base_url()?>assets/js/bootstrap/bootstrap.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="<?php echo base_url()?>assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo base_url()?>assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url()?>assets/js/app.min.js"></script>
        

    </body>
</html>
