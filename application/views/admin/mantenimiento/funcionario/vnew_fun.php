<!DOCTYPE html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
        <title><?php echo $this->session->userdata('name')?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-production.min.css"> 
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/smartadmin-skins.min.css">
        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/demo.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script> 
        <meta name="viewport" content="width=device-width">
    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">
            </div>
            <div class="pull-right">
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
            </div>
        </header>
        <!-- END HEADER -->
        <!-- Left panel : Navigation area -->
        <aside id="left-panel">
            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                            <span>
                                <i class="fa fa-user" aria-hidden="true"></i><?php echo $this->session->userdata("user_name");?>
                            </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                </span>
            </div>
            <nav>
                <ul>
                    <li class="">
                    <a href="<?php echo site_url("admin") . '/dashboard'; ?>" title="MENÚ PRINCIPAL"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">MEN&Uacute; PRINCIPAL</span></a>
                    </li>
                    <li class="text-center">
                        <a href="#" title="REPORTE GERENCIAL"> <span class="menu-item-parent">MANTENIMIENTO</span></a>
                    </li>
                    <?php echo $menu;?>
                </ul>
            </nav>
            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
        </aside>

        <!-- MAIN PANEL -->
        <div id="main" role="main">
            <!-- RIBBON -->
            <div id="ribbon">
                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
                        <i class="fa fa-refresh"></i>
                    </span> 
                </span>
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <li>Mantenimiento</li><li>Responsables POA</li><li>Nuevo Registro</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        </article>
                        <article class="col-sm-12 col-md-8 col-lg-8">
                            <?php 
                              if($this->session->flashdata('danger'))
                                { ?>
                                <div class="alert alert-danger">
                                  <?php echo $this->session->flashdata('danger'); ?>
                                </div>
                            <?php 
                                }
                            ?>
                            <!-- Widget ID (each widget will need unique ID)-->
                            <div class="jarviswidget" id="wid-id-8" data-widget-editbutton="false" data-widget-custombutton="false">
                                <header>
                                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                                    <h2></h2> 
                                </header>
                                <!-- widget div-->
                                <div>
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                        <!-- This area used as dropdown edit box -->
                                    </div>
                                    <div class="widget-body no-padding">
                                        <form action="<?php echo site_url("admin").'/funcionario/add_fun' ?>" method="post" id="resp_form" name="resp_form" class="smart-form">
                                            <input type="hidden" name="php" id="php" value="<?php echo base_url(); ?>">
                                            <input type="hidden" name="componente" id="componente" value="0">
                                            <header>REGISTRO DE RESPONSABLES NACIONALES / REGIONALES</header>
                                            <fieldset>
                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">NOMBRE COMPLETO</label>
                                                            <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" onkeyup="javascript:fun_usuario();">
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">APELLIDO PATERNO</label>
                                                            <input class="form-control" type="text" name="ap" id="ap" maxlength="50" onkeyup="javascript:fun_usuario();">
                                                        </section> 
                                                        <section class="col col-4">
                                                            <label class="label">APELLIDO MATERNO</label>
                                                            <input class="form-control" type="text" name="am" id="am" maxlength="50" onkeyup="javascript:fun_usuario();">
                                                        </section>
                                                    </div>   
                                                </div><br>

                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">CARNET</label>
                                                            <input class="form-control" type="text" name="ci" id="ci"  onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }"  onpaste="return false">
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">TELEFONO / CELULAR</label>
                                                            <input class="form-control" type="text" name="fono" id="fono" onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }"  onpaste="return false">
                                                        </section> 
                                                        <section class="col col-4">
                                                            <label class="label">CARGO ADMINISTRATIVO</label>
                                                            <input class="form-control" type="text" name="crgo" id="crgo" maxlength="50">
                                                        </section>
                                                    </div>   
                                                </div><br>

                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-6">
                                                            <label class="label">DOMICILIO</label>
                                                            <input class="form-control" type="text" name="domicilio" id="domicilio">
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label"><font color="blue">USUARIO</font></label>
                                                            <input class="form-control" type="text" name="usuario" id="usuario" onblur="javascript:fun_ci();" placeholder="Usuario" style="width:100%;" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="20">
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label"><font color="blue">PASSWORD</font></label>
                                                            <input class="form-control" type="Password" name="password" id="password" placeholder="Password" maxlength="20">
                                                        </section>
                                                    </div>    
                                                </div><br>

                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                        <label class="label">ADMINISTRACI&Oacute;N  ?</label>
                                                            <label class="input">
                                                                <select class="form-control" id="adm" name="adm">
                                                                    <option value="0">Seleccione</option>
                                                                    <option value="1">NACIONAL</option>
                                                                    <option value="2">REGIONAL</option>
                                                                </select> 
                                                            </label>
                                                        </section>
                                                        <div id="dependencia" style="display:none;">
                                                            <section class="col col-4">
                                                                <label class="label">REGIONALES</label>
                                                                <label class="input">
                                                                    <select class="form-control" id="dep_id" name="dep_id" title="Seleccione Departamento">
                                                                        <option value="">Seleccione</option>
                                                                        <?php 
                                                                            foreach($list_dep as $row){ ?>
                                                                                <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
                                                                                <?php
                                                                            }
                                                                        ?>        
                                                                    </select> 
                                                                </label>
                                                            </section>
                                                            <section class="col col-4">
                                                                <label class="label">DISTRITALES</label>
                                                                <label class="input">  
                                                                    <select class="form-control" id="dist_id" name="dist_id" title="Seleccione Distrital Regional">
                                                                    </select>
                                                                </label>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </div><br>
                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-3">
                                                        <label class="label">UNIDAD ORGANIZACIONAL</label>
                                                            <label class="input">
                                                                <select class="form-control" id="uni_id" name="uni_id" title="Seleccione Unidad Organizacional">
                                                                    <option value="">Seleccione</option>
                                                                    <?php 
                                                                        foreach($uni_org as $row){ ?>
                                                                            <option value="<?php echo $row['uni_id']; ?>" ><?php echo $row['uni_unidad']; ?></option>
                                                                            <?php   
                                                                        }
                                                                    ?>        
                                                                </select> 
                                                            </label>
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label">SELECCIONE ROL : <b id="titulo"></b></label>
                                                                <div class="row">
                                                                    <div class="col col-2"></div>
                                                                    <div class="col col-10">
                                                                        <?php
                                                                            foreach($listas_rol as $row){
                                                                                if($row['r_id']==1){
                                                                                    echo '
                                                                                    <input type="radio" id="rol_id" name="rol_id" value="'.$row['r_id'].'" checked>
                                                                                    <label for="male"><b>'.$row['r_nombre'].'</b></label><br>';
                                                                                }
                                                                                else{
                                                                                    echo '
                                                                                    <input type="radio" id="rol_id" name="rol_id" value="'.$row['r_id'].'">
                                                                                    <label for="male"><b>'.$row['r_nombre'].'</b></label><br>';
                                                                                }
                                                                                
                                                                            }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                        </section>
                                                        <div id="usu_sact" style="display:none;">
                                                            <section class="col col-3">
                                                            <label class="label">ACTIVIDAD</label>
                                                                <label class="input">
                                                                    <select class="form-control" id="act_id" name="act_id" title="Seleccione Actividad">       
                                                                    </select> 
                                                                </label>
                                                            </section>
                                                            <section class="col col-3">
                                                            <label class="label">SUBACTIVIDAD</label>
                                                                <label class="input">
                                                                    <select class="form-control" id="com_id" name="com_id" title="Seleccione Sub Actividad">       
                                                                    </select> 
                                                                </label>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <footer>
                                                <div id="but_registro" style="display:none;">
                                                <input type="button" value="GUARDAR INFORMACIÓN" id="btsubmit" class="btn btn-primary" onclick="valida_envia()" title="GUARDAR RESPONSABLE">
                                                <a href="<?php echo base_url().'index.php/admin/mnt/list_usu'; ?>" class="btn btn-default" title="REQUERIMIENTOS DE LA OPERACION"> CANCELAR </a>
                                                </div>
                                            </footer>
                                        </form>   
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->
    </div>
    <!-- ========================================================================================================= -->
        <script type="text/javascript">
        function fun_ci(){ 
            ci = $('[id="ci"]').val();
            usuario = $('[id="usuario"]').val();
            
            var url = 
            "<?php echo site_url("admin")?>/funcionario/verif_ci";
            $.ajax({
                type:"post",
                url:url,
                data:{ci:ci},
                success:function(datos){

                    if(datos.trim() =='false'){
                        alertify.error('LA CEDULA DE IDENTIDAD YA SE ENCUENTRA REGISTRADO !!!')
                        document.resp_form.ci.focus() 
                        return 0; 
                    }
                    else{
                        var url = 
                        "<?php echo site_url("admin")?>/funcionario/verif_usuario";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{user:usuario},
                            success:function(datos){

                                if(datos.trim() =='false'){
                                    alertify.error('EL USUARIO YA SE ENCUENTRA REGISTRADO !!!')
                                    document.resp_form.usuario.focus() 
                                    return 0; 
                                }
                                else{
                                    
                                }

                        }});
                    }

            }});
        }
        </script>
        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
                </div>
            </div>
        </div>
        <!-- END PAGE FOOTER -->
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="<?php echo base_url(); ?>assets/js/plugin/pace/pace.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>

        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>

        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
        <script src="<?php echo base_url(); ?>mis_js/mantenimiento/funcionario.js"></script>
        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
        <!-- BOOTSTRAP JS -->
        <script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
        <!-- CUSTOM NOTIFICATION -->
        <script src="<?php echo base_url(); ?>assets/js/notification/SmartNotification.min.js"></script>
        <!-- JARVIS WIDGETS -->
        <script src="<?php echo base_url(); ?>assets/js/smartwidgets/jarvis.widget.min.js"></script>
        <!-- EASY PIE CHARTS -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
        <!-- SPARKLINES -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY SELECT2 INPUT -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/select2/select2.min.js"></script>
        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
        <!-- browser msie issue fix -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
        <!-- FastClick: For mobile devices -->
        <script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <!-- Demo purpose only -->
        <script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <script type="text/javascript">
        function valida_envia(){ 

            if (document.resp_form.nombre.value==""){ 
                alert("REGISTRE NOMBRE DEL RESPONSABLE") 
                document.resp_form.nombre.focus() 
                return 0; 
            }

            if (document.resp_form.ap.value==""){ 
                alert("REGISTRE EL APELLIDO PATERNO") 
                document.resp_form.prog.focus() 
                return 0; 
            }

/*            if (document.resp_form.ci.value==""){ 
                alert("REGISTRE CI.") 
                document.resp_form.ci.focus() 
                return 0; 
            }*/

            if (document.resp_form.crgo.value==""){ 
                alert("REGISTRE CARGO") 
                document.resp_form.crgo.focus() 
                return 0; 
            }

            if (document.resp_form.usuario.value==""){ 
                alert("REGISTRE USUARIO") 
                document.resp_form.usuario.focus() 
                return 0; 
            }

            if (document.resp_form.password.value==""){ 
                alert("REGISTRE PASSWORD") 
                document.resp_form.password.focus() 
                return 0; 
            }

            if (document.resp_form.adm.value=="0"){
                alert("SELECCIONE EL TIPO DE ADMINISTRACION") 
                document.resp_form.adm.focus() 
                return 0;
            }
            
            if (document.resp_form.adm.value=="2") /////// REGIONAL 
            { 
                if (document.resp_form.dep_id.value=="") /////// DEPARTAMENTOS
                {
                    alert("SELECCIONE REGIONAL") 
                    document.resp_form.dep_id.focus() 
                    return 0; 
                }
            }                


            if ($('input[name=rol_id]:checked', '#resp_form').val()==9){
                if ($('[id="com_id"]').val()==0 || $('[id="com_id"]').val()==null){
                    alert("SELECCIONE SUBACTIVIDAD") 
                    document.resp_form.com_id.focus() 
                    return 0;
                }
                else{
                    $('[id="componente"]').val($('[id="com_id"]').val());
                }
            }
            else{
                $('[id="componente"]').val(0);
            }


      

            usuario=document.resp_form.usuario.value.trim();
            var url = "<?php echo site_url("")?>/funcionario/verif_usuario";
            $.ajax({
                type:"post",
                url:url,
                data:{user:usuario},
                success:function(datos){
                    
                    if(datos.trim() =='true'){
                        var OK = confirm("GUARDAR DATOS DEL RESPONSABLE ?");
                        if (OK) {
                                document.resp_form.submit(); 
                                document.getElementById("btsubmit").value = "GUARDANDO...";
                                document.getElementById("btsubmit").disabled = true;
                                return true;
                        }
                    }else{
                        alert("EL USUARIO YA ESE ENCUENTRA REGISTRADO")
                        document.resp_form.usuario.focus() 
                        return 0;  
                    }
            }});
        }

        </script>
    </body>
</html>
