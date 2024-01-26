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
        <meta name="viewport" content="width=device-width">
          <style>
            th{
                padding: 1.4px;
                text-align: center;
                font-size: 9px;
            }
            td{
                padding: 1.4px;
                font-size: 9px;
            }
            
            input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
            }
        </style>
        <script language="javascript">
            function doSearch(){
              var tableReg = document.getElementById('resp');
              var searchText = document.getElementById('searchTerm').value.toLowerCase();
              var cellsOfRow="";
              var found=false;
              var compareWith="";
         
              // Recorremos todas las filas con contenido de la tabla
              for (var i = 1; i < tableReg.rows.length; i++){
                cellsOfRow = tableReg.rows[i].getElementsByTagName('td');
                found = false;
                // Recorremos todas las celdas
                for (var j = 0; j < cellsOfRow.length && !found; j++){
                  compareWith = cellsOfRow[j].innerHTML.toLowerCase();
                  // Buscamos el texto en el contenido de la celda
                  if (searchText.length == 0 || (compareWith.indexOf(searchText) > -1)){
                    found = true;
                  }
                }
                if(found) {
                  tableReg.rows[i].style.display = '';
                } else {
                  // si no ha encontrado ninguna coincidencia, esconde la
                  // fila de la tabla
                  tableReg.rows[i].style.display = 'none';
                }
              }
            }
        </script>
    </head>
    <body class="">
        <header id="header">
            <div id="logo-group">
            </div>
            <div class="pull-right">
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="SALIR DEL SISTEMA" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
                </div>
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="EXPANDIR"><i class="fa fa-arrows-alt"></i></a> </span>
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
                                <i class="fa fa-user" aria-hidden="true"></i>  <?php echo $this->session->userdata("user_name");?>
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
                    <li>Mantenimiento</li><li>Configuraci&oacute;n</li><li>Configuraci&oacute;n Sistema - <?php echo $this->session->userdata('gestion')?></li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section id="widget-grid" class="well">
                                <div class="">
                                  <h1>CONFIGURACI&Oacute;N BASE DE DATOS - <?php echo $this->session->userdata("name");?>
                                </div>
                            </section>
                        </article>

                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php 
                          if($this->session->flashdata('success')){ ?>
                            <div class="alert alert-success">
                              <?php echo $this->session->flashdata('success'); ?>
                            </div>
                        <?php 
                            }
                          elseif($this->session->flashdata('danger')){?>
                            <div class="alert alert-danger">
                                <?php echo $this->session->flashdata('danger'); ?>
                            </div>
                            <?php
                          }
                        ?>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="jarviswidget" id="wid-id-5" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
                                <header>
                                    <h2>CONFIGURACI&Oacute;N - <?php echo $this->session->userdata("name");?></h2>
                                </header>
                                <!-- widget div-->
                                <div>
                                    <!-- widget edit box -->
                                    <div class="jarviswidget-editbox">
                                    <!-- This area used as dropdown edit box -->
                                    </div>
                                    <!-- end widget edit box -->
                                    <!-- widget content -->
                                    <div class="widget-body">
                                        <div class="alert alert-info" align="center">
                                          <strong><h4>GESTI&Oacute;N VIGENTE SISTEMA - <?php echo $this->session->userdata('gestion')?></h4></strong>
                                        </div>
                                        <div class="tabs-left">
                                            <ul class="nav nav-tabs tabs-left" id="demo-pill-nav">
                                                <li class="active">
                                                    <a href="#tab-r1" data-toggle="tab"><span class="badge bg-color-blue txt-color-white">1</span>CONF. ENTIDAD</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r2" data-toggle="tab"><span class="badge bg-color-blueDark txt-color-white">2</span>CONF. SIGLA</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r3" data-toggle="tab"><span class="badge bg-color-blue txt-color-white">3</span>CONF. PEI</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r4" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">4</span>CONF. GESTI&Oacute;N</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r5" data-toggle="tab"><span class="badge bg-color-blue txt-color-white">5</span>CONF. TRIMESTRAL</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r6" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">6</span>CONF. MES</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r7" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">7</span>CONF. MODULOS</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r8" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">8</span>CONF. EVALUACI&Oacute;N POA</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r9" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">9</span>CONF. OPCIONES POA-SIIPLAS</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r10" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">10</span>CONF. MENSAJES SISTEMA</a>
                                                </li>
                                                <li>
                                                    <a href="#tab-r11" data-toggle="tab"><span class="badge bg-color-greenLight txt-color-white">11</span>CONF. AJUSTE DE SALDOS</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab-r1">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_entidad" id="form_entidad" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="4">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR NOMBRE DE ENTIDAD</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">INSTITUCI&Oacute;N</label>
                                                                            <div class="col-md-10">
                                                                                <textarea class="form-control" name="entidad" id="entidad" placeholder="NONBRE DE LA ENTIDAD" rows="4"><?php echo $conf[0]['conf_nombre_entidad'];?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_entidad()" title="MODIFICAR NOMBRE ENTIDAD">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r2">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                               <form name="form_sigla" id="form_sigla" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="3">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR SIGLA DE ENTIDAD</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">SIGLA</label>
                                                                            <div class="col-md-10">
                                                                                <input class="form-control" name="sigla" id="sigla" placeholder="SIGLA ENTIDAD" type="text" value="<?php echo $conf[0]['conf_sigla_entidad'];?>">
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_sigla()" title="MODIFICAR NOMBRE ENTIDAD">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>
                                                
                                                <div class="tab-pane" id="tab-r3">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                               <form name="form_obj" id="form_obj" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_conf'?>"  class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="5">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR GESTI&Oacute;N PEI</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">INICIO</label>
                                                                            <div class="col-md-10">
                                                                                <input class="form-control" placeholder="GESTION INICIO" name="gi" id="gi" type="text" value="<?php echo $conf[0]['conf_gestion_desde'] ?>" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">FINAL</label>
                                                                            <div class="col-md-10">
                                                                                <input class="form-control" placeholder="GESTION FINAL" name="gf" id="gf" type="text" value="<?php echo $conf[0]['conf_gestion_hasta'] ?>" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_obj()" title="MODIFICAR GESTIONES OBJETIVOS ESTRATEGICOS">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r4">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_gest" id="form_gest" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="1">
                                                                    <input type="hidden" name="gest_ini" id="gest_ini" value="<?php echo $conf[0]['conf_gestion'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR GESTI&Oacute;N ACTIVA</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">GESTI&Oacute;N</label>
                                                                            <div class="col-md-10">
                                                                                <select class="select2" id="gest_id" name="gest_id" title="GESTIONES ">
                                                                                    <?php 
                                                                                        foreach($gestion as $row){
                                                                                            if($row['g_id']==$conf[0]['conf_gestion']){ ?>
                                                                                                 <option value="<?php echo $row['g_id']; ?>" selected><?php echo $row['g_descripcion']; ?></option>
                                                                                                <?php 
                                                                                            }
                                                                                            else{ ?>
                                                                                                 <option value="<?php echo $row['g_id']; ?>"><?php echo $row['g_descripcion']; ?></option>
                                                                                                <?php 
                                                                                            }   
                                                                                        }
                                                                                    ?>        
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_gestion()" title="MODIFICAR GESTION">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r5">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_tmes" id="form_tmes" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo$conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="6">
                                                                    <input type="hidden" name="tmes_id1" id="tmes_id1" value="<?php echo $conf[0]['conf_mes_otro'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURACI&Oacute;N TRIMESTRAL</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">TRIMESTRE</label>
                                                                            <div class="col-md-10">
                                                                                <select class="select2" id="tmes_id" name="tmes_id" title="Trimestre">
                                                                                <?php 
                                                                                    foreach($trimestre as $row){
                                                                                        if($row['trm_id']==$conf[0]['conf_mes_otro']){ ?>
                                                                                             <option value="<?php echo $row['trm_id']; ?>" selected><?php echo $row['trm_descripcion']; ?></option>
                                                                                            <?php 
                                                                                        }
                                                                                        else{ ?>
                                                                                             <option value="<?php echo $row['trm_id']; ?>"><?php echo $row['trm_descripcion']; ?></option>
                                                                                            <?php 
                                                                                        }   
                                                                                    }
                                                                                ?>        
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_tmes()" title="MODIFICAR TRIMESTRE">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r6">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_mes" id="form_mes" method="post" action="<?php echo site_url("").'/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo$conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="2">
                                                                    <input type="hidden" name="mes_id1" id="mes_id1" value="<?php echo$conf[0]['conf_mes'] ?>">
                                                                    <input type="hidden" name="mes" id="mes" value="<?php echo$conf[0]['m_descripcion'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR MES VIGENTE</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">MES</label>
                                                                            <div class="col-md-10">
                                                                                <select class="select2" id="mes_id" name="mes_id" title="Meses">
                                                                                <?php 
                                                                                    foreach($mes as $row){
                                                                                        if($row['m_id']==$conf[0]['conf_mes']){ ?>
                                                                                             <option value="<?php echo $row['m_id']; ?>" selected><?php echo $row['m_descripcion']; ?></option>
                                                                                            <?php 
                                                                                        }
                                                                                        else{ ?>
                                                                                             <option value="<?php echo $row['m_id']; ?>"><?php echo $row['m_descripcion']; ?></option>
                                                                                            <?php 
                                                                                        }   
                                                                                    }
                                                                                ?>        
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR" id="btsubmit" class="btn btn-primary" onclick="valida_mes()" title="MODIFICAR MES">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r7">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form id="form_mod" name="form_mod" novalidate="novalidate" method="post" action="<?php echo site_url("") . '/mantenimiento/cconfiguracion/update_mod'?>">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo$conf[0]['ide'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR MODULOS</legend>
                                                                        <?php echo $modulos;?>
                                                                    </fieldset>
                                                                    <div class="form-actions" id="but">
                                                                        <input type="button" value="GUARDAR CAMBIOS" id="btsubmit" class="btn btn-primary" onclick="valida_servicios()" title="ACTUALIZAR ESTADO DE MODULOS">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r8">
                                                    <p>
                                                        <article class="col-sm-12 col-md-2 col-lg-2">
                                                        </article>
                                                        <article class="col-sm-12 col-md-8 col-lg-8">
                                                            <div class="widget-body">
                                                                <form id="formulario" name="formulario" novalidate="novalidate" method="post" action="<?php echo site_url("").'/mantenimiento/cconfiguracion/update_datos_evaluacion'?>">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURACI&Oacute;N ACCESO A EVALUACI&Oacute;N POA</legend>
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="">
                                                                                    <fieldset>
                                                                                        <section>
                                                                                            <label class="input" id="col"><b>INICIO EVALUACI&Oacute;N</b></label>
                                                                                            <div class="input-group">
                                                                                                <input type="text" name="ini" id="ini" placeholder="Seleccione Fecha inicial" value="<?php if($conf[0]['eval_inicio']!=''){echo date('d/m/Y',strtotime($conf[0]['eval_inicio']));}else{echo date("d/m/Y");} ?>" class="form-control datepicker" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" title="INICIO DE EVALUACIÓN">
                                                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                                            </div>
                                                                                        </section>
                                                                                    </fieldset>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="">
                                                                                    <fieldset>
                                                                                        <section>
                                                                                            <label class="input" id="col"><b>FINAL EVALUACI&Oacute;N</b></label>
                                                                                            <div class="input-group">
                                                                                                <input type="text" name="fin" id="fin" placeholder="Seleccione Fecha final" value="<?php if($conf[0]['eval_fin']!=''){echo date('d/m/Y',strtotime($conf[0]['eval_fin']));}else{echo date("d/m/Y");} ?>" class="form-control datepicker" value="" onKeyUp="this.value=formateafecha(this.value);" data-dateformat="dd/mm/yy" title="FECHA FINAL DE EVALUACION">
                                                                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                                                            </div>
                                                                                        </section>
                                                                                    </fieldset>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <hr>
                                                                    <fieldset>
                                                                        <legend>LISTA DE RESPONSABLES HABILITADOS</legend>
                                                                        <?php echo $responsables_evaluadores;?>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="GUARDAR RESPONSABLES A EVALUAR POA" id="btsubmitt" class="btn btn-primary" title="ACTUALIZAR HABILITADOS A EVALUAR POA">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r9">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form method="post" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR OPCIONES DISPONIBLES PROG/MOD/CERT POA</legend>
                                                                        <div style="font-size: 30px">PROGRAMACIÓN POA</div><br>
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">TIPO DE PRESUPUESTO</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="ppto" name="ppto" title="SELECCIONE">
                                                                                    <?php 
                                                                                        if($this->session->userData('verif_ppto')==0){ ?>
                                                                                            <option value="0" selected>PRESUPUESTO PROGRAMADO POA </option>
                                                                                            <option value="1">PRESUPUESTO APROBADO SIGEP - <?php echo $this->session->userData('gestion')?></option>      
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="0">PRESUPUESTO PROGRAMADO POA </option>
                                                                                            <option value="1" selected>PRESUPUESTO APROBADO SIGEP - <?php echo $this->session->userData('gestion')?></option>      
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                    
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">ESTADO POA</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_poa" name="estado_poa" title="SELECCIONE PLAN OPERATIVO ANUAL">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_poa_estado')==1){ ?>
                                                                                            <option value="1" selected>PROGRAMACIÓN INICIAL </option>
                                                                                            <option value="2">AJUSTE POA</option>      
                                                                                            <option value="3">POA APROBADO</option>      
                                                                                            <?php
                                                                                        }
                                                                                        elseif($this->session->userData('conf_poa_estado')==2){ ?>
                                                                                            <option value="1">PROGRAMACIÓN INICIAL </option>
                                                                                            <option value="2" selected>AJUSTE POA</option>      
                                                                                            <option value="3">POA APROBADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">PROGRAMACIÓN INICIAL </option>
                                                                                            <option value="2">AJUSTE POA</option>      
                                                                                            <option value="3" selected>POA APROBADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">REGISTRO DEL FORM. N°4</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_poa_form4" name="estado_poa_form4" title="SELECCIONE ESTADO FORMULARIO N4">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_form4')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA REGISTRO </option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA REGISTRO </option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">REGISTRO DEL FORM. N°5</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_poa_form5" name="estado_poa_form5" title="SELECCIONE ESTADO FORMULARIO N5">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_form5')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA REGISTRO </option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA REGISTRO </option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div style="font-size: 30px">MODIFICACIÓN POA</div><br>
                                                                         <div class="form-group">
                                                                            <label class="col-md-2 control-label">MODIFICACIÓN FORM. N°4</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_modpoa_form4" name="estado_modpoa_form4" title="SELECCIONE ESTADO FORMULARIO MODIFICACIÓN N4">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_mod_ope')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA MODIFICAR FORM 4</option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA MODIFICAR FORM 4</option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">MODIFICACIÓN FORM. N°5</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_modpoa_form5" name="estado_modpoa_form5" title="SELECCIONE ESTADO FORMULARIO MODIFICACIÓN N5">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_mod_req')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA MODIFICAR FORM 5</option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA MODIFICAR FORM 5</option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <hr>
                                                                        <div style="font-size: 30px">CERTIFICACIÓN POA</div><br>
                                                                         <div class="form-group">
                                                                            <label class="col-md-2 control-label">CERTIFICACIÓN FORM. N°5</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_certpoa_form5" name="estado_certpoa_form5" title="SELECCIONE ESTADO FORMULARIO CERTIFICAICÓN N 5">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_certificacion')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA CERTIFICAR </option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA CERTIFICAR </option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <hr>
                                                                        <div style="font-size: 30px">NOTIFCACI&Oacute;N POA</div><br>
                                                                         <div class="form-group">
                                                                            <label class="col-md-2 control-label">NOTIFCACI&Oacute;N MENSUAL</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="estado_notificacion" name="estado_notificacion" title="SELECCIONE ESTADO PARA NOTIFIFCAR">
                                                                                    <?php 
                                                                                        if($this->session->userData('conf_poa')==1){ ?>
                                                                                            <option value="1" selected>HABILITADO PARA NOTIFICAR </option>
                                                                                            <option value="0">NO HABILITADO</option>     
                                                                                            <?php
                                                                                        }
                                                                                        else{ ?>
                                                                                            <option value="1">HABILITADO PARA NOTIFICAR</option>
                                                                                            <option value="0" selected>NO HABILITADO</option> 
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r10">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_msn" id="form_msn" method="post" action="<?php echo site_url("").'/mantenimiento/cconfiguracion/update_conf'?>" class="form-horizontal">
                                                                    <input type="hidden" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <input type="hidden" name="tp" id="tp" value="7">
                                                                    <fieldset>
                                                                        <legend>MENSAJES SISTEMA</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">TIPO</label>
                                                                            <div class="col-md-10">
                                                                                <select class="form-control" id="tp_msn" name="tp_msn" title="SELECCIONE">
                                                                                    <?php 
                                                                                        if($conf[0]['tp_msn']==0){ ?>
                                                                                            <option value="0" selected>NINGUN MENSAJE</option>
                                                                                            <option value="1">ALERTA ROJA</option>
                                                                                            <option value="2">ALERTA AMARILLO</option>
                                                                                            <option value="3">ALERTA VERDE</option>
                                                                                            <?php
                                                                                        }
                                                                                        elseif($conf[0]['tp_msn']==1){ ?>
                                                                                            <option value="0">NINGUN MENSAJE</option>
                                                                                            <option value="1" selected>ALERTA ROJA</option>
                                                                                            <option value="2">ALERTA AMARILLO</option>
                                                                                            <option value="3">ALERTA VERDE</option>
                                                                                            <?php
                                                                                        }
                                                                                        elseif ($conf[0]['tp_msn']==2) { ?>
                                                                                            <option value="0">NINGUN MENSAJE</option>
                                                                                            <option value="1">ALERTA ROJA</option>
                                                                                            <option value="2" selected>ALERTA AMARILLO</option>
                                                                                            <option value="3">ALERTA VERDE</option>
                                                                                            <?php
                                                                                        }
                                                                                        elseif ($conf[0]['tp_msn']==3) { ?>
                                                                                            <option value="0">NINGUN MENSAJE</option>
                                                                                            <option value="1">ALERTA ROJA</option>
                                                                                            <option value="2">ALERTA AMARILLO</option>
                                                                                            <option value="3" selected>ALERTA VERDE</option>
                                                                                            <?php
                                                                                        }
                                                                                    ?>
                                                                                    
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">MENSAJE</label>
                                                                            <div class="col-md-10">
                                                                                <textarea rows="4" name="msn" id="msn" class="form-control" title="REGISTRAR MENSAJE"><?php echo $conf[0]['conf_mensaje']; ?></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                    <div class="form-actions">
                                                                        <input type="button" value="MODIFICAR MENSAJE" id="btsubmit" class="btn btn-primary" onclick="valida_msn()" title="MODIFICAR DATOS DE MENSAJE">
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                                <div class="tab-pane" id="tab-r11">
                                                    <p>
                                                        <article class="col-sm-12 col-md-3 col-lg-3">
                                                        </article>
                                                        <article class="col-sm-12 col-md-6 col-lg-6">
                                                            <div class="widget-body">
                                                                <form name="form_gest2" id="form_gest2" method="post" class="form-horizontal">
                                                                    <input type="text" name="ide" id="ide" value="<?php echo $conf[0]['ide'] ?>">
                                                                    <fieldset>
                                                                        <legend>CONFIGURAR SALDOS POA</legend>
                                                                        
                                                                        <div class="form-group">
                                                                            <label class="col-md-2 control-label">AJUSTAR SALDOS?</label>
                                                                            <div class="col-md-10">
                                                                                <select class="select2" id="ajuste" name="ajuste" title="AJUSTE POA">
                                                                                    <?php 
                                                                                        if($conf[0]['conf_ajuste_poa']==0){?>
                                                                                            <option value="0" selected>NO</option>
                                                                                            <option value="1" >SI</option>
                                                                                            <?php 
                                                                                        }
                                                                                        else{?>
                                                                                            <option value="0">NO</option>
                                                                                            <option value="1" selected>SI</option>
                                                                                            <?php 
                                                                                        }
                                                                                    ?>      
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </fieldset>
                                                                </form>
                                                            </div>
                                                        </article>
                                                    </p>
                                                </div>

                                            </div>
                                        </div>
                
                                    </div>
                                    <!-- end widget content -->
                                </div>
                                <!-- end widget div -->
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
        <script>
            function valida_tmes(){ 
                tmesantiguo=document.form_tmes.tmes_id1.value;
                tmesnuevo=document.form_tmes.tmes_id.value;

                if(tmesantiguo!=tmesnuevo){
                   alertify.confirm('DESEA MODIFICAR EL TRIMESTRE ?', function (a) {
                        if (a) {
                            document.form_tmes.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
                }
                else{
                    alertify.success("EL TRIMESTRE SE MANTIENE EN LA CONFIGURACION");
                }   
            }

            function valida_mes(){ 
                mes_descripcion=document.form_mes.mes.value;
                mesantiguo=document.form_mes.mes_id1.value;
                mesnuevo=document.form_mes.mes_id.value;

                var m = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];

                if(mesantiguo!=mesnuevo){
                   alertify.confirm('DESEA MODIFICAR '+mes_descripcion+' POR '+m[mesnuevo]+' ?', function (a) {
                        if (a) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            document.form_mes.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
                }
                else{
                    alertify.success("MES DE "+mes_descripcion+" SE MANTIENE EN LA CONFIGURACION");
                }   
            }

            function valida_gestion(){ 
                gestantiguo=document.form_gest.gest_ini.value;
                gestnuevo=document.form_gest.gest_id.value;

                //gestantiguo= $('[name="gest_ini"]').val(); /// proyecto document.form_gest.gest_ini.value;
                //gestnuevo=$('[name="gest_id"]').val(); //document.form_gest.gest_id.value;

                if(gestantiguo!=gestnuevo){
                   alertify.confirm('DESEA MODIFICAR LA GESTION '+gestantiguo+' POR '+gestnuevo+' ?', function (a) {
                        if (a) {
                            document.form_gest.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
                }
                else{
                    alertify.success("GESTION "+gestantiguo+" SE MANTIENE EN LA CONFIGURACION");
                }
            }

            function valida_sigla(){ 
                if (document.form_sigla.sigla.value==""){ 
                      alertify.alert("REGISTRE SIGLA INSTITUCIONAL") 
                      document.form_sigla.sigla.focus() 
                      return 0; 
                  }

                alertify.confirm('MODIFICAR SIGLA INSTITUCIONAL ?', function (a) {
                        if (a) {
                            document.form_sigla.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
            }

            function valida_entidad(){ 
                if (document.form_entidad.entidad.value==""){ 
                      alertify.alert("REGISTRE NOMBRE DE LA INSTITUCION") 
                      document.form_entidad.entidad.focus() 
                      return 0; 
                  }

                alertify.confirm('MODIFICAR NOMBRE DE LA ENTIDAD ?', function (a) {
                        if (a) {
                            document.form_entidad.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
            }

            function valida_obj(){ 
                if (document.form_obj.gi.value==""){ 
                      alertify.alert("REGISTRE GESTION INICIO") 
                      document.form_obj.gi.focus() 
                      return 0; 
                  }

                  if (document.form_obj.gf.value==""){ 
                      alertify.alert("REGISTRE GESTION FINAL") 
                      document.form_obj.gf.focus() 
                      return 0; 
                  }

                 // alert(document.form_obj.gf.value+'--'+document.form_obj.gi.value)
                  if(document.form_obj.gf.value<document.form_obj.gi.value){
                        alertify.error("LA GESTION INICIAL NO PUEDE SER POSTERIOR A LA GESTION FINAL");
                        document.form_obj.gi.focus() 
                      return 0; 
                  }

                  if((document.form_obj.gf.value-document.form_obj.gi.value)==4){
                        alertify.confirm('MODIFICAR GESTIONES DEL OBJETIVO ESTRATEGICO ?', function (a) {
                        if (a) {
                            document.form_obj.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    }); 
                  }
                  else{
                     alertify.error("LA DIFERENCIA DE GESTIONES DEBE SER DE 5 AÑOS, VERIFIQUE GESTIONES");
                        document.form_obj.gf.focus() 
                      return 0;
                  }
            }
        </script>

        <script>
        function valida_servicios(){
            if (document.form_mod.tot.value=="" || document.form_mod.tot.value==0){
                alertify.error("NO PUEDE REALIZAR LA ACTUALIZACIÓN DE MODULOS");
            }
            else{
               alertify.confirm("ACTUALIZAR MODULOS ?", function (a) {
                    if (a) {
                        document.form_mod.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }

        function valida_certificaciones(){
            if (document.form_cert.totc.value=="" || document.form_cert.totc.value==0){
                alertify.error("NO PUEDE REALIZAR LA ACTUALIZACIÓN DE REGIONALES, DISTRITALES");
            }
            else{
               alertify.confirm("ACTUALIZAR DISTRITALES ?", function (a) {
                    if (a) {
                        document.form_cert.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        }

        function valida_msn(){ 
            alertify.confirm('MODIFICAR DATOS DEL MENSAJE ?', function (a) {
                    if (a) {
                        document.form_msn.submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                }); 
        }
        </script>

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
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script>
        function reset() {
            $("#toggleCSS").attr("href", "<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css");
            alertify.set({
                labels: {
                    ok: "ACEPTAR",
                    cancel: "CANCELAR"
                },
                delay: 5000,
                buttonReverse: false,
                buttonFocus: "ok"
            });
        }
        $(function () {
            $("#btsubmitt").on("click", function (e) {
                var $validator = $("#formulario").validate({
                    rules: {
                        ini: {
                            required: true,
                        },
                        fin: {
                            required: true,
                        }
                    },
                    messages: {
                        ini: {required: "<font color=red size=1>SELECCIONE FECHA INICIO DE EVALUACIÓN</font>"},
                        fin: {required: "<font color=red size=1>SELECCIONE FECHA FINAL DE EVALUACIÓN</font>"}
                    },
                    highlight: function (element) {
                        $(element).closest('.form-group').removeClass('has-success').addClass('has-error');
                    },
                    unhighlight: function (element) {
                        $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function (error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
                var $valid = $("#formulario").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } 
                else {
                    var fecha_inicial = document.formulario.ini.value.split("/")  //fecha inicial
                    var fecha_final = document.formulario.fin.value.split("/")  /*fecha final*/

                    if(parseInt(fecha_final[2])<parseInt(fecha_inicial[2])){
                        alertify.error('Error!!  en las Fechas, verifique las gestiones del proyecto')
                        document.formulario.f_final.focus() 
                        return 0;
                    }

                    reset();
                    alertify.confirm("GUARDAR DATOS DE CONFIGURACIÓN DE EVALUACIÓN ?", function (a) {
                        if (a) {
                            //============= GUARDAR DESPUES DE LA VALIDACION ===============
                            document.getElementById('btsubmitt').disabled = true;
                            document.formulario.submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                    
                }
            });
        });
        </script>
        <!-- MODIFICAR ACTIVIDAD -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#ppto").change(function () {            
                var ppto = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(ppto==0){
                    mensaje='ACTIVAR PRESUPUESTO PROGRAMADO POA - SIIPLAS ?';
                }
                else{
                    mensaje='ACTIVAR PRESUPUESTO FINAL - SIGEP ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_ppto'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{ppto:ppto,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });
            });
        </script>
        <!--   UPDATE ESTADO POA   -->
        <script type="text/javascript">
            $(document).ready(function () {
                $("#estado_poa").change(function () {            
                var est_poa = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(est_poa==1){
                    mensaje='PLAN OPERATIVO INICIAL ?';
                }
                else{
                    if(est_poa==2){
                        mensaje='PLAN OPERATIVO AJUSTADO ?';
                    }
                    else{
                        mensaje='PLAN OPERATIVO APROBADO?';
                    }
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadopoa'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:est_poa,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });

                //// ESTADO FORMULARIO 4
                $("#estado_poa_form4").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR FORMULARIO N°4 ?';
                }
                else{
                    mensaje='DESHABILITAR FORMULARIO N°4 ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadoform4'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });


                //// ESTADO FORMULARIO 5
                $("#estado_poa_form5").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR FORMULARIO N°5 ?';
                }
                else{
                    mensaje='DESHABILITAR FORMULARIO N°5 ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadoform5'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });

                //// ESTADO MODIFICACION POA FORM 4
                $("#estado_modpoa_form4").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR FORMULARIO MODIFICACIÓN N° 4 ?';
                }
                else{
                    mensaje='DESHABILITAR FORMULARIO MODIFICACIÓN N° 4 ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadomodform4'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });

                //// ESTADO MODIFICACION POA FORM 5
                $("#estado_modpoa_form5").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR FORMULARIO MODIFICACIÓN N° 5 ?';
                }
                else{
                    mensaje='DESHABILITAR FORMULARIO MODIFICACIÓN N° 5 ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadomodform5'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });


                //// ESTADO CERTIFICACION POA FORM 5
                $("#estado_certpoa_form5").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR FORMULARIO CERTIFICACION POA N° 5 ?';
                }
                else{
                    mensaje='DESHABILITAR FORMULARIO CERTIFICACION POA N° 5 ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_estadocertform5'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });

                //// ESTADO NOTIFICACION POA
                $("#estado_notificacion").change(function () {            
                var estado = $(this).val();
                var id = <?php echo $conf[0]['ide'];?>;

                var mensaje='';
                if(estado==1){
                    mensaje='HABILITAR NOTIFICACIÓN POA ?';
                }
                else{
                    mensaje='DESHABILITAR NOTIFICACIÓN POA ?';
                }

                alertify.confirm(mensaje, function (a) {
                    if (a) {
                        var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_notificacionpoa'?>";
                        $.ajax({
                            type:"post",
                            url:url,
                            data:{estado:estado,g_id:id},
                            success:function(datos){
                                if(datos.trim() =='true'){
                                    window.location.reload(true);
                                }else{
                                    alertify.error("Error al Actualizar ..");
                                }
                        }});
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                  });

                });


                //// AJUSTE POA DE SALDOS
                $("#ajuste").change(function () {    
                    var ajuste = $(this).val();
                    var id = $('[name="ide"]').val(); /// id

                    var mensaje='';
                    if(ajuste==1){
                        mensaje='HABILITAR AJUSTE POA ?';
                    }
                    else{
                        mensaje='DESHABILITAR AJUSTE POA ?';
                    }

                    alertify.confirm(mensaje, function (a) {
                        if (a) {
                            var url = "<?php echo site_url().'/mantenimiento/cconfiguracion/valida_update_saldospoa'?>";
                            $.ajax({
                                type:"post",
                                url:url,
                                data:{estado:ajuste,g_id:id},
                                success:function(datos){
                                    if(datos.trim() =='true'){
                                        window.location.reload(true);
                                    }else{
                                        alertify.error("Error al Actualizar ..");
                                    }
                            }});
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                      });

                });
            });
        </script>
        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            $(document).ready(function() {
                pageSetUp();
                // menu
                $("#menu").menu();
                $('.ui-dialog :button').blur();
                $('#tabs').tabs();
            })
        </script>
    </body>
</html>
