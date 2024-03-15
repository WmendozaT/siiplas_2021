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
         <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <meta name="viewport" content="width=device-width">
        <!--fin de stiloh-->
            <script>
            function abreVentana(PDF){
                var direccion;
                direccion = '' + PDF;
                window.open(direccion, "Reporte Unidad Organizacional" , "width=800,height=650,scrollbars=SI") ;
            }                                                 
            </script>
            <style>
            .table1{
              display: inline-block;
              width:100%;
              max-width:1550px;
              overflow-x: scroll;
              }
            table{font-size: 10px;
            width: 100%;
            max-width:1550px;;
            overflow-x: scroll;
            }
            th{
              padding: 1.4px;
              text-align: center;
              font-size: 10px;
            }
            #mdialTamanio{
              width: 95% !important;
            }
            tr:hover td { background: #dce7f9; color: #339; }
            input[type="checkbox"] {
                display:inline-block;
                width:25px;
                height:25px;
                margin:-1px 4px 0 0;
                vertical-align:middle;
                cursor:pointer;
            }
            </style>
    </head>
    <body class="">
        <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
        <!-- HEADER -->
        <header id="header">
            <div id="logo-group">
                <!-- <span id="logo"> <img src="<?php echo base_url(); ?>assets/img/logo.png" alt="SmartAdmin"> </span> -->
            </div>
            <div class="col-md-4 " style="font-size:18px;margin-top:10px;margin-bottom:-10px;">
                <span>
                    &nbsp;&nbsp;&nbsp; 
                    <div class="badge bg-color-blue">
                        <span style="font-size:15px;"><b>Fecha Sesi&oacute;n: <?php echo $this->session->userdata('desc_mes').' / '.$this->session->userdata('gestion');?></b></span>
                    </div>
                </span>
                <div class="project-context hidden-xs">
                    <span class="project-selector dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:19px;">
                        <i class="fa fa-lg fa-fw fa-calendar txt-color-blue"></i>
                    </span>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo base_url();?>index.php/cambiar_gestion">Cambiar Gestión</a>
                        </li>
                    </ul>
                </div>
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
                        <a href="#" title="MANTENIMIENTO"> <span class="menu-item-parent">MANTENIMIENTO</span></a>
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
                    <li>Mantenimiento</li><li>Estructura Organizacional</li>
                </ol>
            </div>
            <!-- MAIN CONTENT -->
            <div id="content">
                <!-- widget grid -->
                <section id="widget-grid" class="">
                    <div class="row">
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                            <section id="widget-grid" class="well">
                                <div class="">
                                  <h1>RESPONSABLE : <?php echo $resp; ?> -> <small><?php echo $res_dep;?></small>
                                </div>
                            </section>
                        </article>
                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
                            <section id="widget-grid" class="well">
                              <center>
                                <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                                  OPCIONES
                                  <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/1';?>');">IMPRIMIR ACTIVIDADES</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/2';?>');">IMPRIMIR SUB ACTIVIDADES</a></li>
                                  <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:abreVentana('<?php echo site_url("").'/mnt/rep_estructura/3';?>');">VER UNIDAD - SUBACTIVIDAD</a></li>
                                </ul>
                              </div>
                              </center>
                            </section>
                        </article>

                        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <?php 
                              if($this->session->flashdata('success')){ ?>
                                <div class="alert alert-success">
                                  <?php echo $this->session->flashdata('success'); ?>
                                </div>
                            <?php }
                                elseif($this->session->flashdata('danger')){ ?>
                                <div class="alert alert-danger">
                                  <?php echo $this->session->flashdata('danger'); ?>
                                </div><?php }
                            ?>
                            <div class="well well-sm well-light">
                                <div id="tabs">
                                    <ul>
                                        <li>
                                            <a href="#tabs-a">LISTA - UNIDADES / ESTABLECIMIENTOS</a>
                                        </li>
                                        <li>
                                            <a href="#tabs-b">LISTA - SERVICIOS</a>
                                        </li>
                                    </ul>
                                    <div id="tabs-a">
                                        <div class="row">
                                            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="jarviswidget jarviswidget-color-darken">
                                              <header>
                                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                                  <h2 class="font-md"><strong>ESTRUCTURA ORGANICA - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                                              </header>
                                                <div>
                                                    <?php
                                                        if($this->session->userdata('rol_id')==1){?>
                                                        <a href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success nuevo_ff" title="NUEVO REGISTRO - ACTIVIDAD" class="btn btn-success" style="width:15.5%;">NUEVO REGISTRO</a><br><br>
                                                    <?php } ?>
                                                    <div class="widget-body no-padding">
                                                        <table  class="table table-bordered" width="100%">
                                                            <thead>
                                                                <tr style="height:45px;">
                                                                    <th style="width:5%;">#</th>
                                                                    <th style="width:10%;">REGIONAL</th>
                                                                    <th style="width:15%;">UNIDAD ADMINISTRATIVA</th>
                                                                    <th style="width:15%;">UNIDAD EJECUTORA</th>
                                                                    <th style="width:15%;">UNIDAD / ESTABLECIMIENTO</th>
                                                                    <th style="width:10%;">TIPO</th>
                                                                    <th style="width:5%;">MODIFICAR</th>
                                                                    <th style="width:5%;">GESTI&Oacute;N - <?php echo $this->session->userdata('gestion')?></th>
                                                                    <th style="width:5%;">ELIMINAR</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php echo $actividades;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end widget content -->
                                                </div>
                                                <!-- end widget div -->
                                            </div>
                                            <!-- end widget -->
                                            </article>
                                        </div>
                                    </div>

                                    <div id="tabs-b">
                                        <div class="row">
                                            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-2">
                                            </article>
                                            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-8">
                                            <div class="jarviswidget jarviswidget-color-darken" >
                                              <header>
                                                  <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                                  <h2 class="font-md"><strong>SUBACTIVIDAD / SERVICIOS</strong></h2>  
                                              </header>
                                                <div>
                                                    <a href="#" data-toggle="modal" data-target="#modal_nuevo_fsa" class="btn btn-success nuevo_fsa"  title="NUEVO REGISTRO - SUB ACTIVIDAD - SERVICIO" class="btn btn-success" style="width:24%;">NUEVO REGISTRO</a><br><br>
                                                    <div class="widget-body no-padding">
                                                        <table id="dt_basic4" class="table table table-bordered" width="100%">
                                                            <thead>
                                                                <tr style="height:45px;">
                                                                    <th style="width:5%;">#</th>
                                                                    <th style="width:10%;">COD. SERVICIO</th>
                                                                    <th style="width:25%;">SERVICIO</th>
                                                                    <th style="width:5%;"></th>
                                                                    <th style="width:5%;"></th>
                                                                    <th style="width:5%;"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php echo $servicios;?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- end widget content -->
                                                </div>
                                                <!-- end widget div -->
                                            </div>
                                            <!-- end widget -->
                                            </article>
                                        </div>
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
        <!-- MODAL NUEVO REGISTRO DE ACTIVIDADES   -->
        <div class="modal fade" id="modal_nuevo_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url().'/mnt/valida_actividad'?>" id="form_nuevo" name="form_nuevo" class="form-horizontal" method="post">
                        <h2 class="alert alert-info"><center>UNIDAD / ESTABLECIMIENTO (Nuevo)</center></h2>                           
                        <fieldset>
                            <div id="tit"></div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">TIPO DE ESTABLECIMIENTO</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="te_id" name="te_id" title="SELECCIONE TIPO DE ESTABLECIMIENTO">
                                          <option value="">Seleccione Tipo</option>
                                            <?php 
                                              foreach($lista_establecimiento as $row){ ?>
                                                <option value="<?php echo $row['te_id']; ?>"><?php echo $row['escalon'].' - '.$row['tipo'].' '.$row['establecimiento'].''; ?></option>
                                                <?php   
                                              }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">REGIONAL</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="dep_id" name="dep_id" title="Seleccione Regional">
                                            <option value="">Seleccione Regional</option>
                                            <?php 
                                                foreach($list_dep as $row){ ?>
                                                    <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">UNIDAD EJECUTORA</label>
                                    <div class="col-md-10">
                                        <select class="form-control" id="ue_id" name="ue_id" title="Seleccione Unidad Ejecutora">
                                            <option value="">No seleccionado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="cod">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">C&Oacute;DIGO </label>
                                        <div class="col-md-10">
                                            <input class="form-control" type="text" name="codigo" id="codigo" maxlength="5" placeholder="00" onkeyup="verif_actividad_cod();" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">UNIDAD / ESTABLECIMIENTO / SERVICIO</label>
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="descripcion" id="descripcion" maxlength="200" rows="2" ></textarea>
                                    </div>
                                </div>
                            </div>

                        </fieldset>                    
                        <div class="form-actions">
                            <div class="row">
                                <div id="but" style="display:none;">
                                    <div class="col-md-12">
                                       <button class="btn btn-default" data-dismiss="modal" id="cl" title="CANCELAR">CANCELAR</button>
                                       <button type="button" name="subir_form" id="subir_form" class="btn btn-info" >GUARDAR INFORMACI&Oacute;N</button>
                                        <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
        <!-- ============ Modal Modificar Actividad Institucional ========= -->
        <div class="modal fade" id="modal_mod_ff" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/mnt/valida_update_actividad'?>" id="form_mod" name="form_mod" class="form-horizontal" method="post">
                <input type="hidden" name="act_id" id="act_id">
                <input type="hidden" name="cod1" id="cod1">
                <input type="hidden" name="ue1" id="ue1">

                    <h2 class="alert alert-info"><center>UNIDAD / ESTABLECIMIENTO (Modificar)</center></h2>                           
                    <fieldset>
                        <div id="mtit"></div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">TIPO DE ESTABLECIMIENTO</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="mte_id" name="mte_id" title="SELECCIONE TIPO DE ESTABLECIMIENTO">
                                      <option value="">Seleccione Tipo</option>
                                        <?php 
                                          foreach($lista_establecimiento as $row){ ?>
                                            <option value="<?php echo $row['te_id']; ?>"><?php echo $row['escalon'].' - '.$row['tipo'].' '.$row['establecimiento'].''; ?></option>
                                            <?php   
                                          }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">REGIONAL</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="dep" name="dep" title="Seleccione Regional">
                                        <option value="">Seleccione Regional</option>
                                        <?php 
                                            foreach($list_dep as $row){ ?>
                                                <option value="<?php echo $row['dep_id']; ?>" <?php if(@$_POST['pais']==$row['dep_id']){ echo "selected";} ?>><?php echo $row['dep_departamento']; ?></option>
                                        <?php } ?>        
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">UNIDAD EJECUTORA</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="ue" name="ue" title="Seleccione Unidad Ejecutora">
                                        <option value="">Seleccione</option>
                                        <?php 
                                            foreach($unidad_ejec as $row){ ?>
                                            <option value="<?php echo $row['dist_id']; ?>"><?php echo $row['dist_cod'].' - '.strtoupper($row['dist_distrital']); ?></option>
                                        <?php } ?>   
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div id="mcod">
                            <div class="form-group">
                                <div class="form-group">
                                    <label class="col-md-2 control-label">C&Oacute;DIGO</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="cod" id="codi" maxlength="5" placeholder="00" onkeyup="verif_actividad_mod();" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                    </div>
                                </div>
                            </div>    
                        </div>
                        
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">UNIDAD / ESTABLECIMIENTO, COMPRA DE SERVICIO</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="desc" id="desc" maxlength="200" rows="2" onkeyup="verif_actividad_mod();"></textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>                    
                    <div class="form-actions">
                        <div class="row">
                            <div id="mbut">
                                <div class="col-md-12">
                                   <button class="btn btn-default" data-dismiss="modal" id="mcl" title="CANCELAR">CANCELAR</button>
                                   <button type="button" name="mod_ffenviar" id="mod_ffenviar" class="btn btn-info" >MODIFICAR INFORMACI&Oacute;N</button>
                                    <center><img id="loadd" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

        <!-- MODAL NUEVO REGISTRO DE SUBACTIVIDADES   -->
        <div class="modal fade" id="modal_nuevo_fsa" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <form action="<?php echo site_url().'/mantenimiento/cestructura_organizacional/valida_updateactividad'?>" id="form_nuevosa" name="form_nuevosa" class="form-horizontal" method="post">
                        <h2 class="alert alert-info"><center>SUBACTIVIDAD / SERVICIO (Nuevo)</center></h2>                           
                    <fieldset>
                        <div id="tits"></div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">C&Oacute;DIGO SERVICIO</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="scod" id="scod" maxlength="5" placeholder="00" onkeyup="verif_cod_subactividad();" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">DESCRIPCI&Oacute;N SERVICIO</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="sdesc" id="sdesc" maxlength="200" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">TIPO</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="stp" name="stp" title="Seleccione Regional">
                                        <option value="">Seleccione</option>
                                        <option value="0">ADMINISTRACI&Oacute;N REGIONAL</option>
                                        <option value="1">ADMINISTRACI&Oacute;N OFICINA NACIONAL</option> 
                                    </select>
                                </div>
                            </div>
                        </div>                        
                    </fieldset>                     
                        <div class="form-actions">
                            <div class="row">
                                <div id="buts">
                                    <div class="col-md-12">
                                       <button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
                                       <button type="button" name="subir_sa" id="subir_sa" class="btn btn-info" >SUBACTIVIDAD SUB-ACTIVIDAD</button>
                                        <center><img id="loadsa1" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!--  =====================================================  -->
        <!-- ============ Modal Modificar Sub Actividad ========= -->
        <div class="modal fade" id="modal_mod_ffsa" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

              <div class="modal-body">
                <form action="<?php echo site_url().'/mnt/valida_update_sub_actividad'?>" id="form_modsa" name="form_modsa" class="form-horizontal" method="post">
                    <input type="hidden" name="serv_id" id="serv_id">
                    <input type="hidden" name="sub_cod1" id="sub_cod1">
                    <h2 class="alert alert-info"><center>SERVICIO (Modificar)</center></h2>                           
                    <fieldset>
                        <div id="stit"></div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">C&Oacute;DIGO SERVICIO</label>
                                <div class="col-md-10">
                                    <input class="form-control" type="text" name="sub_cod" id="sub_cod" maxlength="5" placeholder="00" onkeyup="verif_cod_sact();" onkeypress="if (this.value.length < 20) { return soloNumeros(event);}else{return false; }" onpaste="return false">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">DESCRIPCI&Oacute;N SERVICIO</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="sub_desc" id="sub_desc" maxlength="200" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <label class="col-md-2 control-label">TIPO</label>
                                <div class="col-md-10">
                                    <select class="form-control" id="tp" name="tp" title="Seleccione Regional">
                                        <option value="">Seleccione</option>
                                        <option value="0">ADMINISTRACI&Oacute;N REGIONAL</option>
                                        <option value="1">ADMINISTRACI&Oacute;N OFICINA NACIONAL</option> 
                                    </select>
                                </div>
                            </div>
                        </div>                        
                    </fieldset>                  
                    <div class="form-actions">
                        <div class="row">
                            <div id="sbut">
                                <div class="col-md-12">
                                   <button class="btn btn-default" data-dismiss="modal" title="CANCELAR">CANCELAR</button>
                                   <button type="button" name="mod_ffsaenviar" id="mod_ffsaenviar" class="btn btn-info" >MODIFICAR SUB-ACTIVIDAD</button>
                                    <center><img id="loadsa" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
    </div>

        <!--  =====================================================  -->
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
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
        <script>
            document.getElementById("cl").addEventListener("click", function(){
            window.location.reload(true);
          });
            document.getElementById("mcl").addEventListener("click", function(){
            window.location.reload(true);
          });
        </script>
        <script type="text/javascript">
        $(document).ready(function() {
            pageSetUp();
            $("#dep_id").change(function () {
                $("#dep_id option:selected").each(function () {
                    elegido=$(this).val();
                    $.post("<?php echo base_url(); ?>index.php/admin/proy/combo_uejecutoras", { elegido: elegido,accion:'distrital' }, function(data){
                        $("#ue_id").html(data);
                    });     
                });
            });
        })
        </script>
        <script type="text/javascript">
            function verif_actividad_cod(){ 
            cod = $('[name="codigo"]').val(); /// Codigo
            ue = parseFloat($('[name="ue_id"]').val()); /// Unidad Ejecutora
                var url = "<?php echo site_url("")?>/mnt/verif_cod";
                $.ajax({
                    type:"post",
                    url:url,
                    data:{cod:cod,ue:ue},
                    success:function(datos){
                        
                        if(datos.trim() =='true'){
                           $('#tit').html('<center><div class="alert alert-danger alert-block">ACTIVIDAD REGISTRADO</div></center>');
                           $('#but').slideUp();
                            
                        }else{
                            $('#tit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD DISPONIBLE REGISTRAR</div></center>');
                            $('#but').slideDown();
                        }
                }});
            }

            function verif_actividad_mod(){
                cod1 = parseFloat($('[name="cod1"]').val()); /// Codigo Inicial
                ue1 = parseFloat($('[name="ue1"]').val()); /// Unidad ejecutora

                cod = parseFloat($('[name="cod"]').val()); /// Codigo
                ue = parseFloat($('[name="ue"]').val()); /// Unidad Ejecutora
                
                if((cod==cod1) & (ue==ue1)){
                    $('#mtit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD ACTUAL</div></center>');
                    $('#mbut').slideDown();
                }
                else{
                    var url = "<?php echo site_url("")?>/mnt/verif_cod";
                    $.ajax({
                        type:"post",
                        url:url,
                        data:{cod:cod,ue:ue},
                        success:function(datos){
                            
                            if(datos.trim() =='true'){
                               $('#mtit').html('<center><div class="alert alert-danger alert-block">ACTIVIDAD REGISTRADO</div></center>');
                               $('#mbut').slideUp();
                                
                            }else{
                                $('#mtit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD DISPONIBLE REGISTRAR</div></center>');
                                $('#mbut').slideDown();
                            }
                    }});
                }
            }
            
            $(document).ready(function () {
                $("#ue_id").change(function () {            
                var ue = $(this).val();
                    cod = $('[name="codigo"]').val(); /// Codigo
                    var url = "<?php echo site_url("")?>/mnt/verif_cod";
                    $.ajax({
                        type:"post",
                        url:url,
                        data:{cod:cod,ue:ue},
                        success:function(datos){
                            if(datos.trim() =='true'){
                                $('#tit').html('<center><div class="alert alert-danger alert-block">ACTIVIDAD REGISTRADO</div></center>');
                                $('#but').slideUp();
                                
                            }else{
                                $('#tit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD DISPONIBLE REGISTRAR</div></center>');
                                $('#but').slideDown();
                            }
                    }});   
                });
            });

            /*-------- Verificar el tipo de establecimiento ------*/
            $(document).ready(function () {
                $("#te_id").change(function () {            
                var te_id = $(this).val();
                    if(te_id==21){
                        $('#cod').slideUp();
                        document.getElementById("codigo").value = 101;
                    } 
                    else{
                        $('#cod').slideDown();
                    }
                });
            });


            $(document).ready(function () {
                $("#ue").change(function () {            
                    var ue = $(this).val();
                        cod1 = parseFloat($('[name="cod1"]').val()); /// Codigo Inicial
                        ue1 = parseFloat($('[name="ue1"]').val()); /// apertura Inicial
                        cod = parseFloat($('[name="cod"]').val()); /// Codigo

                        if((cod==cod1) & (ue==ue1)){
                            $('#mtit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD ACTUAL</div></center>');
                            $('#mbut').slideDown();
                        }
                        else{
                            var url = "<?php echo site_url("")?>/mnt/verif_cod";
                            $.ajax({
                                type:"post",
                                url:url,
                                data:{cod:cod,ue:ue},
                                success:function(datos){
                                    if(datos.trim() =='true'){
                                        $('#mtit').html('<center><div class="alert alert-danger alert-block">ACTIVIDAD REGISTRADO</div></center>');
                                        $('#mbut').slideUp();
                                        
                                    }else{
                                        $('#mtit').html('<center><div class="alert alert-success alert-block">ACTIVIDAD DISPONIBLE REGISTRAR</div></center>');
                                        $('#mbut').slideDown();
                                    }
                            }});
                        }
                    });
                });

            /*----- insert subactividad ----*/
            function verif_cod_subactividad(){ 
                cod = $('[name="scod"]').val(); /// Codigo subactividad
                if(cod){
                    var url = "<?php echo site_url("")?>/mnt/verif_cod_sact";
                    $.ajax({
                        type:"post",
                        url:url,
                        data:{cod:cod},
                        success:function(datos){
                            if(datos.trim() =='true'){
                               $('#tits').html('<center><div class="alert alert-danger alert-block">CÓDIGO DE SUB ACTIVIDAD REGISTRADO</div></center>');
                               $('#buts').slideUp();
                                
                            }else{
                                $('#tits').html('<center><div class="alert alert-success alert-block">CÓDIGO DE SUB ACTIVIDAD DISPONIBLE</div></center>');
                                $('#buts').slideDown();
                            }
                    }});  
                }
            }

            /*----- mod subactividad ----*/
            function verif_cod_sact(){ 
            cod1 = $('[name="sub_cod1"]').val(); /// Codigo sub act1
            cod = $('[name="sub_cod"]').val(); /// Codigo sub act2

                if(cod1!=cod){
                    var url = "<?php echo site_url("")?>/mnt/verif_cod_sact";
                    $.ajax({
                        type:"post",
                        url:url,
                        data:{cod:cod},
                        success:function(datos){
                            if(datos.trim() =='true'){
                               $('#stit').html('<center><div class="alert alert-danger alert-block">CÓDIGO DE SUB ACTIVIDAD REGISTRADO</div></center>');
                               $('#sbut').slideUp();
                                
                            }else{
                                $('#stit').html('<center><div class="alert alert-success alert-block">CÓDIGO DE SUB ACTIVIDAD DISPONIBLE</div></center>');
                                $('#sbut').slideDown();
                            }
                    }});  
                }
            }
        </script>
        
        <!-- MODIFICAR ACTIVIDAD -->
        <script type="text/javascript">
            $(function () {
                $(".mod_ff").on("click", function (e) {
                    act_id = $(this).attr('name'); 
                    te_id = $(this).attr('id');
                    document.getElementById("act_id").value=act_id;
                    var url = "<?php echo site_url("")?>/mnt/get_actividad";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "act_id="+act_id+"&te_id="+te_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        document.getElementById("dep").value = response.actividad[0]['dep_id'];
                        document.getElementById("ue").value = response.actividad[0]['dist_id'];
                        document.getElementById("ue1").value = response.actividad[0]['dist_id'];
                        document.getElementById("codi").value = response.actividad[0]['act_cod'];
                        document.getElementById("cod1").value = response.actividad[0]['act_cod'];
                        document.getElementById("desc").value = response.actividad[0]['act_descripcion'];
                        document.getElementById("mte_id").value = response.actividad[0]['te_id'];

                        if(response.actividad[0]['te_id']==21){
                            $('#mcod').slideUp();
                        }
                        else{
                            $('#mcod').slideDown();   
                        }

                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE LA ACTIVIDAD");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                    // =============================VALIDAR EL FORMULARIO DE MODIFICACION
                    $("#mod_ffenviar").on("click", function (e) {
                        var $validator = $("#form_mod").validate({
                               rules: {
                                dep_id: { //// Departamento
                                required: true,
                                },
                                ue_id: { //// Unidad Ejecutora
                                    required: true,
                                },
                                cod: { //// Codigo
                                    required: true,
                                },
                                descripcion: { //// Actividad
                                    required: true,
                                },
                                mte_id: { //// tp Establecimiento
                                    required: true,
                                }
                            },
                            messages: {
                                dep_id: "<font color=red>SELECCIONE REGIONAL</font>",
                                ue_id: "<font color=red>SELECCIONE UNIDAD EJECUTORA</font>", 
                                cod: "<font color=red>CÓDIGO ACTIVIDAD</font>",
                                descripcion: "<font color=red>REGISTRE DESCRIPCIÓN</font>",
                                mte_id: "<font color=red>SELECCIONE TIPO DE ESTABLECIMIENTO</font>",                     
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
                        var $valid = $("#form_mod").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {

                            alertify.confirm("MODIFICAR DATOS UNIDAD,ESTABLECIMIENTO, COMPRA DE SERVICIO ?", function (a) {
                                if (a) {
                                    document.getElementById("loadd").style.display = 'block';
                                    document.getElementById('mod_ffenviar').disabled = true;
                                    document.forms['form_mod'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>

        <!-- AGREGAR NUEVA ACTIVIDAD -->
        <script type="text/javascript">
        $(function () {
            $("#subir_sa").on("click", function () {
                var $validator = $("#form_nuevosa").validate({
                        rules: {
                            scod: { //// codigo
                                required: true,
                            },
                            sdesc: { //// descripcion subActividad
                                required: true,
                            },
                            stp: { //// tp
                                required: true,
                            }
                        },
                        messages: {
                            scod: "<font color=red>REGISTRE CODIGO</font>",
                            sdesc: "<font color=red>REGISTRE DESCRIPCIÓN SUBACTIVIDAD</font>", 
                            stp: "<font color=red>SELECCIONE TIPO</font>",                    
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

                var $valid = $("#form_nuevosa").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("GUARDAR DATOS DE SUBACTIVIDAD / SERVICIO ?", function (a) {
                        if (a) {
                            document.getElementById("loadsa1").style.display = 'block';
                            document.getElementById('subir_sa').disabled = true;
                            document.forms['form_nuevosa'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>

        <!-- AGREGAR NUEVA SUBACTIVIDAD -->
        <script type="text/javascript">
        $(function () {
            $("#subir_formsa").on("click", function () {
                var $validator = $("#form_nuevo").validate({
                        rules: {
                            dep_id: { //// codigo
                            required: true,
                            },
                            ue_id: { //// descripcion Actividad
                                required: true,
                            },
                            codigo: { //// Codigo
                                required: true,
                            },
                            descripcion: { //// Programa
                                required: true,
                            },
                            te: { //// Establecimiento
                                required: true,
                            }
                        },
                        messages: {
                            dep_id: "<font color=red>SELECCIONE REGIONAL</font>",
                            ue_id: "<font color=red>SELECCIONE UNIDAD EJECUTORA</font>", 
                            codigo: "<font color=red>REGISTRE CÓDIGO</font>", 
                            descripcion: "<font color=red>REGISTRE DESCRIPCIÓN</font>", 
                            te: "<font color=red>SELECCIONE TIPO DE ESTABLECIMIENTO</font>",                     
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

                var $valid = $("#form_nuevo").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {

                    alertify.confirm("GUARDAR DATOS DE UNIDAD, ESTABLECIMIENTO, COMPRA DE SERVICIO ?", function (a) {
                        if (a) {
                            document.getElementById("load").style.display = 'block';
                            document.getElementById('subir_form').disabled = true;
                            document.forms['form_nuevo'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>
        <!-- MODIFICAR SUB ACTIVIDAD -->
        <script type="text/javascript">
            $(function () {
                $(".mod_ffsa").on("click", function (e) {
                    serv_id = $(this).attr('name');
                    document.getElementById("serv_id").value=serv_id;
                    var url = "<?php echo site_url("")?>/mnt/get_sub_actividad";
                    var request;
                    if (request) {
                        request.abort();
                    }
                    request = $.ajax({
                        url: url,
                        type: "POST",
                        dataType: 'json',
                        data: "serv_id=" + serv_id
                    });

                    request.done(function (response, textStatus, jqXHR) {
                    if (response.respuesta == 'correcto') {
                        document.getElementById("sub_cod").value = response.sub_actividad[0]['serv_cod'];
                        document.getElementById("sub_cod1").value = response.sub_actividad[0]['serv_cod'];
                        document.getElementById("sub_desc").value = response.sub_actividad[0]['serv_descripcion'];
                        document.getElementById("tp").value = response.sub_actividad[0]['serv_tp'];
                    }
                    else{
                        alertify.error("ERROR AL RECUPERAR DATOS DE LA ACTIVIDAD");
                    }

                    });
                    request.fail(function (jqXHR, textStatus, thrown) {
                        console.log("ERROR: " + textStatus);
                    });
                    request.always(function () {
                        //console.log("termino la ejecuicion de ajax");
                    });
                    e.preventDefault();
                    // =============================VALIDAR EL FORMULARIO DE MODIFICACION
                    $("#mod_ffsaenviar").on("click", function (e) {
                        var $validator = $("#form_modsa").validate({
                               rules: {
                                serv_id: { //// Servicio
                                required: true,
                                },
                                sub_cod: { //// Codigo
                                    required: true,
                                },
                                sub_desc: { //// Descripcion
                                    required: true,
                                },
                                tp: { //// Tipo
                                    required: true,
                                }
                            },
                            messages: {
                                serv_id: "<font color=red>SERVICIOL</font>",
                                sub_cod: "<font color=red>REGISTRE CÓDIGO SUB ACTIVIDAD</font>", 
                                sub_desc: "<font color=red>DESCRIPCIÓN SUB ACTIVIDAD</font>",
                                tp: "<font color=red>SELECCIONE TIPO</font>",                     
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
                        var $valid = $("#form_modsa").valid();
                        if (!$valid) {
                            $validator.focusInvalid();
                        } else {
                            alertify.confirm("MODIFICAR DATOS SUB - ACTIVIDAD ?", function (a) {
                                if (a) {
                                    document.getElementById("loadsa").style.display = 'block';
                                    document.getElementById('mod_ffsaenviar').disabled = true;
                                    document.forms['form_modsa'].submit();
                                } else {
                                    alertify.error("OPCI\u00D3N CANCELADA");
                                }
                            });

                        }
                    });
                });
            });
        </script>

        <!-- ELIMINAR ACTIVIDAD INSTITUCIONAL -->
        <script type="text/javascript">
            $(function () {
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

                $(".del_ff").on("click", function (e) {
                    reset();
                    var name = $(this).attr('name');
                    var request;
                    // confirm dialog
                    alertify.confirm("DESEA ELIMINAR ACTIVIDAD INSTITUCIONAL?", function (a) {
                        if (a) { 
                            url = "<?php echo site_url("")?>/mnt/delete_actividad";
                            if (request) {
                                request.abort();
                            }
                            request = $.ajax({
                                url: url,
                                type: "POST",
                                dataType: "json",
                                data: "act_id="+name

                            });

                            request.done(function (response, textStatus, jqXHR) { 
                                reset();
                                if (response.respuesta == 'correcto') {
                                    alertify.alert("EL OBJETIVO ESTRATEGICO SE ELIMINO CORRECTAMENTE ", function (e) {
                                        if (e) {
                                            window.location.reload(true);
                                        }
                                    })
                                } else {
                                    alertify.error("Error al Eliminar");
                                }
                            });
                            request.fail(function (jqXHR, textStatus, thrown) {
                                console.log("ERROR: " + textStatus);
                            });
                            request.always(function () {
                                //console.log("termino la ejecuicion de ajax");
                            });

                            e.preventDefault();

                        } else {
                            // user clicked "cancel"
                            alertify.error("Opcion cancelada");
                        }
                    });
                    return false;
                });

            });
        </script>
        <script type="text/javascript">
        $(function () {
            $("#subir_archivo").on("click", function () {
                var $valid = $("#form_subir_serv").valid();
                if (!$valid) {
                    $validator.focusInvalid();
                } else {
                    if(document.getElementById('archivo').value==''){
                        alertify.alert('POR FAVOR SELECCIONE ARCHIVO .CSV');
                        return false;
                    }
                   
                    archivo = document.getElementById('archivo').value;
                    alertify.confirm("SUBIR ARCHIVO ?", function (a) {
                        if (a) {
                            document.getElementById("load").style.display = 'block';
                            document.getElementById('cerrar').disabled = true;
                            document.getElementById('subir_archivo').disabled = true;
                            document.getElementById("subir_archivo").value = "Subiendo Archivo...";
                            document.forms['form_subir_serv'].submit();
                        } else {
                            alertify.error("OPCI\u00D3N CANCELADA");
                        }
                    });
                }
            });
        });
        </script>
        <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            $(document).ready(function() {
                pageSetUp();
                $("#menu").menu();
                $('.ui-dialog :button').blur();
                $('#tabs').tabs();
            })
        </script>
    </body>
</html>

