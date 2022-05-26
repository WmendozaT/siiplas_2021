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
        <!--estiloh-->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css"> 
        <meta name="viewport" content="width=device-width">
    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
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
                    <li>Mantenimiento</li><li>Funcionarios</li><li>Modificar Registro</li>
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
                              if($this->session->flashdata('danger')){ ?>
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
                                        <form action="<?php echo site_url("admin").'/funcionario/add_update_fun' ?>" method="post" id="resp_form" name="resp_form" class="smart-form">
                                            <input type="hidden" name="php" id="php" value="<?php echo base_url(); ?>">
                                            <input type="hidden" name="componente" id="componente" value="<?php echo $fun[0]['cm_id']?>">
                                            <input class="form-control" type="hidden" name="fun_id"  value="<?php echo $fun[0]['id']?>">
                                            <header>REGISTRO DE RESPONSABLES NACIONALES / REGIONALES </header>
                                            <fieldset>
                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">NOMBRE COMPLETO</label>
                                                            <input class="form-control" type="text" name="nombre" id="nombre" maxlength="50" value="<?php echo $fun[0]['fun_nombre']?>">
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">APELLIDO PATERNO</label>
                                                            <input class="form-control" type="text" name="ap" id="ap" maxlength="50" value="<?php echo $fun[0]['fun_paterno']?>">
                                                        </section> 
                                                        <section class="col col-4">
                                                            <label class="label">APELLIDO MATERNO</label>
                                                            <input class="form-control" type="text" name="am" id="am" maxlength="50" value="<?php echo $fun[0]['fun_materno']?>">
                                                        </section>
                                                    </div>   
                                                </div><br>

                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-4">
                                                            <label class="label">CARNET</label>
                                                            <input class="form-control" type="text" name="ci" id="ci" value="<?php echo $fun[0]['fun_ci']?>" onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }"  onpaste="return false">
                                                        </section>
                                                        <section class="col col-4">
                                                            <label class="label">TELEFONO / CELULAR</label>
                                                            <input class="form-control" type="text" name="fono" id="fono" value="<?php echo $fun[0]['fun_telefono']?>" onkeypress="if (this.value.length < 10) { return soloNumeros(event);}else{return false; }"  onpaste="return false">
                                                        </section> 
                                                        <section class="col col-4">
                                                            <label class="label">CARGO ADMINISTRATIVO</label>
                                                            <input class="form-control" type="text" name="crgo" id="crgo" value="<?php echo $fun[0]['fun_cargo']?>" maxlength="50">
                                                        </section>
                                                    </div>   
                                                </div><br>

                                                <div class="well">
                                                    <div class="row">
                                                        <section class="col col-6">
                                                            <label class="label">DOMICILIO</label>
                                                            <input class="form-control" type="text" name="domicilio" id="domicilio" value="<?php echo $fun[0]['fun_domicilio']?>" maxlength="100">
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label"><font color="blue">USUARIO</font></label>
                                                            <input type="hidden" name="usuario1" value="<?php echo $fun[0]['fun_usuario']?>">
                                                            <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario" value="<?php echo $fun[0]['fun_usuario']?>" style="width:100%;" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="20">
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label"><font color="blue">PASSWORD</font></label>
                                                            <input class="form-control" type="text" name="password" id="password" placeholder="Password" value="<?php echo $edit_pass ?>" maxlength="20">
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
                                                                    <?php 
                                                                    if ($fun[0]['fun_adm']==1) { ?>
                                                                        <option value="1" selected="true">NACIONAL</option>
                                                                        <option value="2">REGIONAL</option>
                                                                        <?php
                                                                    }
                                                                    else{ ?>
                                                                        <option value="1">NACIONAL</option>
                                                                        <option value="2" selected="true">REGIONAL</option>
                                                                        <?php 
                                                                    }
                                                                    ?>
                                                                </select> 
                                                            </label>
                                                        </section>
                                                        <?php
                                                        if($fun[0]['fun_adm']==2){ ?>
                                                            <div id="dependencia">
                                                                <section class="col col-4">
                                                                    <label class="label">REGIONALES</label>
                                                                    <label class="input">
                                                                        <select class="select2" id="dep_id" name="dep_id" title="Seleccione Departamento">
                                                                            <option value="">Seleccione</option>
                                                                            <?php 
                                                                                foreach($list_dep as $row){
                                                                                    if($row['dep_id']==$fun[0]['dep_id']){ ?>
                                                                                        <option value="<?php echo $row['dep_id']; ?>" selected="true"><?php echo $row['dep_departamento']; ?></option>
                                                                                        <?php   
                                                                                    }
                                                                                    else{ ?>
                                                                                        <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
                                                                                        <?php    
                                                                                    }
                                                                                }
                                                                            ?>        
                                                                        </select> 
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label class="label">DISTRITALES</label>
                                                                    <label class="input">  
                                                                        <select class="form-control" id="dist_id" name="dist_id" title="Seleccione Distrital Regional">
                                                                        <?php 
                                                                            foreach($list_dist as $row){
                                                                                if($row['dist_id']==$fun[0]['dist_id']){ ?>
                                                                                    <option value="<?php echo $row['dist_id']; ?>" selected="true"><?php echo $row['dist_distrital']; ?></option>
                                                                                    <?php   
                                                                                }
                                                                                else{ ?>
                                                                                    <option value="<?php echo $row['dist_id']; ?>"><?php echo $row['dist_distrital']; ?></option>
                                                                                    <?php    
                                                                                }
                                                                            }
                                                                        ?> 
                                                                        </select>
                                                                    </label>
                                                                </section>
                                                            </div>
                                                            <?php
                                                        }
                                                        else{ ?>
                                                            <div id="dependencia" style="display:none;">
                                                                <section class="col col-4">
                                                                    <label class="label">REGIONALES</label>
                                                                    <label class="input">
                                                                        <select class="select2" id="dep_id" name="dep_id" title="Seleccione Departamento">
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
                                                            <?php
                                                        }
                                                        ?>
                                                        
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
                                                                        foreach($uni_org as $row){
                                                                            if($row['uni_id']==$fun[0]['uni_id']){ ?>
                                                                                <option value="<?php echo $row['uni_id']; ?>" selected="true"><?php echo $row['uni_unidad']; ?></option>
                                                                                <?php
                                                                            }
                                                                            else{ ?>
                                                                                <option value="<?php echo $row['uni_id']; ?>"><?php echo $row['uni_unidad']; ?></option>
                                                                                <?php
                                                                            }  
                                                                        }
                                                                    ?>        
                                                                </select> 
                                                            </label>
                                                        </section>
                                                        <section class="col col-3">
                                                            <label class="label">SELECCIONE ROL : <b id="titulo"></b></label>
                                                                <select class="form-control" id="rol_id" name="rol_id">
                                                                    <option value="0">Seleccione</option>
                                                                    <?php
                                                                        foreach($listas_rol as $row){ 
                                                                            $rol=$this->model_funcionario->verif_rol($fun[0]['id'],$row['r_id']);
                                                                            if(count($rol)!=0){
                                                                                if($row['r_id']==$rol[0]['r_id']){
                                                                                    ?>
                                                                                    <option value="<?php echo $row['r_id']; ?>" selected><?php echo $row['r_nombre']; ?></option>
                                                                                    <?php 
                                                                                }
                                                                            }
                                                                            else{
                                                                                ?>
                                                                                <option value="<?php echo $row['r_id']; ?>"><?php echo $row['r_nombre']; ?></option>
                                                                                <?php 
                                                                            }
                                                                        }
                                                                    ?>
                                                                </select>
                                                        </section>
                                                        <div id="usu_sact" <?php echo $display; ?>>
                                                            <section class="col col-3">
                                                            <label class="label">ACTIVIDAD</label>
                                                                <label class="input">
                                                                <select class="form-control" id="act_id" name="act_id" title="Seleccione Actividad">
                                                                    <?php echo $actividad;?>
                                                                </select>
                                                                </label>
                                                            </section>
                                                            <section class="col col-3">
                                                            <label class="label">UNIDAD RESPONSABLE</label>
                                                                <label class="input">
                                                                    <select class="form-control" id="com_id" name="com_id" title="Seleccione Subactividad">
                                                                    <?php echo $subactividad;?>
                                                                    </select>
                                                                </label>
                                                            </section>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <footer>
                                                <input type="button" value="GUARDAR" id="btsubmit" class="btn btn-primary" onclick="valida_envia()" title="MODIFICAR RESPONSABLE">
                                                <a href="<?php echo base_url().'index.php/admin/mnt/list_usu'; ?>" class="btn btn-default" title="MODIFICAR INFORMACION"> CANCELAR </a>
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
            $(document).ready(function() {
                pageSetUp();
                $("#rol_id").change(function () {
                      $("#rol_id option:selected").each(function () {
                        rol_id=$(this).val();
                        if(rol_id==9){// seguimiento poa
                            $('#usu_sact').slideDown();
                        }
                        else{
                            $('#usu_sact').slideUp();
                        }  
                  });
                });  
              })

        function valida_envia() { 
         //   alert(document.resp_form.rol_id.value)
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
            }

            if (document.resp_form.crgo.value==""){ 
                alert("REGISTRE CARGO") 
                document.resp_form.crgo.focus() 
                return 0; 
            }*/

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
          //  alert($('input[name=rol_id]:checked', '#resp_form').val())

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

/*            if (document.resp_form.tot.value=="0"){
                alert("SELECCIONE ROL") 
                document.resp_form.tot.focus() 
                return 0;
            }*/
            
            usuario1=document.resp_form.usuario1.value.trim();
            usuario=document.resp_form.usuario.value.trim();
        //    alert(usuario)
            if(usuario1!=usuario){
                var url = "<?php echo site_url("")?>/funcionario/verif_usuario";
                $.ajax({
                    type:"post",
                    url:url,
                    data:{user:usuario},
                    success:function(datos){
                        
                        if(datos.trim() =='true'){
                            var OK = confirm("GUARDAR INFORMACION DEL RESPONSABLE ?");
                            if (OK) {
                                    document.resp_form.submit(); 
                                    document.getElementById("btsubmit").value = "MODIFICANDO...";
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
            else{
                
                var OK = confirm("GUARDAR INFORMACION DEL RESPONSABLE ?");
                if (OK) {
                        document.resp_form.submit(); 
                        document.getElementById("btsubmit").value = "MODIFCANDO...";
                        document.getElementById("btsubmit").disabled = true;
                        return true;
                }
            }
            
        }


        $(document).ready(function() {
            pageSetUp();
            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/admin/proy/combo_distrital", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#dist_id").html(data);
                    });     
                });
            });
        })
        </script>
    </body>
</html>
