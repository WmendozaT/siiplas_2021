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
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
    <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
    <style>
    aside{background: #05678B;}
    #mdialTamanio{
      width: 70% !important;
    }
    #mdialTamanio2{
      width: 45% !important;
    }
    table{
      font-size: 10px;
      width: 100%;
      max-width:1550px;;
      overflow-x: scroll;
    }
    th{
      padding: 1.4px;
      text-align: center;
      font-size: 10px;
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
      <!-- pulled right: nav area -->
      <div class="pull-right">
        <!-- collapse menu button -->
        <div id="hide-menu" class="btn-header pull-right">
          <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
        </div>
        <!-- end collapse menu -->
        <!-- logout button -->
        <div id="logout" class="btn-header transparent pull-right">
          <span> <a href="<?php echo base_url(); ?>index.php/admin/logout" title="Sign Out" data-action="userLogout" data-logout-msg="Estas seguro de salir del sistema"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <!-- end logout button -->
        <!-- search mobile button (this is hidden till mobile view port) -->
        <div id="search-mobile" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
        </div>
        <!-- end search mobile button -->
        <!-- fullscreen button -->
        <div id="fullscreen" class="btn-header transparent pull-right">
          <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
        <!-- end fullscreen button -->
      </div>
      <!-- end pulled right: nav area -->
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
          </a> 
        </span>
      </div>

      <?php echo $menu;?>
      <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
    </aside>

    <!-- MAIN PANEL -->
    <div id="main" role="main">
      <!-- RIBBON -->
      <div id="ribbon">
        <!-- breadcrumb -->
        <ol class="breadcrumb">                         
          <li><a href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a' ?>" title="VOLVER A MIS PROYECTOS">Programaci&oacute;n POA</a></li><li>Mis Componentes</li><li>Mis Actividades</li><li>Mis Tareas</li>  
        </ol>
      </div>
      <!-- END RIBBON --> 
      <!-- MAIN CONTENT -->
      <div id="content">
        <div class="row">
          <article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
            <section id="widget-grid" class="well">
              <ul class="nav nav-pills">
                <li><a href="<?php echo site_url("").'/prog/list_serv/'.$proyecto[0]['proy_id'].''; ?>">MIS COMPONENTES</a></li>
                  <li><a href="<?php echo site_url("").'/admin/prog/list_prod/'.$componente[0]['com_id'].''; ?>">MIS ACTIVIDADES</a></li>
                  <li class="active"><a href="#">MIS TAREAS</a></li>
              </ul>
            </section>
          </article>
          <article class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
            <section id="widget-grid" class="well">
              <center>
                <div class="dropdown">
                  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:80%;" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                    OPCIONES
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard' ?>">SALIR A MENU PRINCIPAL</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy#tabs-a' ?>">LISTA DE PROYECTOS</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo site_url("").'/admin/prog/list_prod/'.$componente[0]['com_id'].''; ?>">VOLVER ATRAS</a></li>
                  </ul>
                </div>
              </center>
            </section>
          </article>
        </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <section id="widget-grid" class="well">
                <div class="">
                  <h1> PROYECTO DE INVERSI&Oacute;N : <small><?php echo $proyecto[0]['aper_programa'].''.$proyecto[0]['aper_proyecto'].''.$proyecto[0]['aper_actividad'].' - '.$proyecto[0]['proy_nombre']?></small>
                  <h1> COMPONENTE: <small><?php echo $componente[0]['com_nro'].' - '.$componente[0]['com_componente'];?></small>
                  <h1> OPERACI&Oacute;N: <small><?php echo $producto[0]['prod_producto'];?></small>
                </div>
            </section>
          </article>

          <section id="widget-grid" class="">
            <div class="row">
              <!-- NEW WIDGET START -->
              <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php 
                  if($this->session->flashdata('success')){ ?>
                    <div class="alert alert-success">
                      <?php echo $this->session->flashdata('success'); ?>
                    </div>
                <?php 
                    }
                  elseif($this->session->flashdata('danger')){ ?>
                    <div class="alert alert-danger">
                      <?php echo $this->session->flashdata('danger'); ?>
                    </div>
                    <?php
                  }
                ?>
                <div class="jarviswidget jarviswidget-color-darken">
                  <header>
                      <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                      <h2 class="font-md"><strong>TAREAS DE LA ACTIVIDAD - <?php echo $this->session->userdata('gestion')?></strong></h2>  
                  </header>
                  <div>
                    <div class="widget-body no-padding">
                      <div class="col-sm-1">
                        <br><a role="menuitem" tabindex="-1" href="#" data-toggle="modal" data-target="#modal_nuevo_ff" class="btn btn-success"  title="NUEVO REGISTRO" style="width:130%;"><font color="#ffffff"><b>NUEVO REGISTRO</b></font></a>
                      </div>
                      <table id="dt_basic" class="table table-bordered">
                        <?php echo $act;?>
                      </table>
                    </div>
                    <!-- end widget content -->
                  </div>
                  <!-- end widget div -->
                </div>
                <!-- end widget -->
              </article>
              <!-- WIDGET END -->
            </div>
          </section>  
        <!-- end widget grid -->          
      </div>
      <!-- END MAIN CONTENT -->
    </div>
    <!-- END MAIN PANEL -->

    <!-- MODAL NUEVO REGISTRO DE REQUERIMIENTOS   -->
  <div class="modal fade" id="modal_nuevo_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio">
      <div class="modal-content">
        <div class="modal-header">
          <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
        </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>NUEVO REGISTRO - TAREA</center></h2>
              <form action="<?php echo site_url().'/prog/valida_act'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="prod_id" id="prod_id" value="<?php echo $producto[0]['prod_id'];?>">
                  <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                  <header><b>DATOS GENERALES DE LA TAREA</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-9">
                        <label class="label">TAREA</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="actividad" id="actividad" title="REGISTRAR ACTIVIDAD"></textarea>
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">TIPO DE INDICADOR</label>
                        <select class="form-control" id="tp_indi" name="tp_indi" title="SELECCIONE TIPO DE INDICADOR">
                            <option value="">Seleccione Tipo de Indicador</option>
                            <?php 
                              foreach($indi as $row){ ?>
                              <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                            <?php } ?>        
                        </select>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-6">
                        <label class="label">INDICADOR</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="indicador" id="indicador" title="REGISTRE DESCRIPCIÓN INDICADOR"></textarea>
                        </label>
                      </section>
                      <section class="col col-6">
                        <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="verificacion" id="verificacion" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-3">
                        <label class="label">LINEA BASE</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="lbase" id="lbase" value="0" title="REGISTRE LINEA BASE" onkeyup="suma_programado()">
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">META</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="meta" id="meta" value="0" title="REGISTRE META" onkeyup="fmeta()">
                        </label>
                      </section>
                      <section class="col col-3">
                        <label class="label">PONDERACI&Oacute;N</label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="pcion" id="pcion" value="0" value="0" title="REGISTRE PONDERACI&Oacute;N">
                        </label>
                      </section>
                    </div>
                    <br>
                    <div id="atit"></div>
                    <header><b>TEMPORALIDAD PROGRAMACI&Oacute;N FÍSICA : <?php echo $this->session->userdata('gestion')?></b><br>
                    <label class="label"><div id="ff"></div></label>
                    </header>
                    <br>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">PROGRAMADO TOTAL</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="tot" id="tot" value="0" disabled="true">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">ENERO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">FEBRERO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">MARZO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">ABRIL</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">MAYO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">JUNIO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">JULIO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">AGOSTO</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">SEPTIEMBRE</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">OCTUBRE</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">NOVIEMBRE</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m11" id="m11" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label">DICIEMBRE</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m12" id="m12" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>

                  </fieldset>
        
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_act" id="subir_act" class="btn btn-info" >GUARDAR DATOS TAREA</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                  </div>
              </form>
              </div>
          </div>
      </div>
  </div>
  <!--  =====================================================  -->

<!-- ============ Modal Modificar requerimiento ========= -->
      <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog" id="mdialTamanio">
            <div class="modal-content">
              <div class="modal-header">
                <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
              </div>
              <div class="modal-body">
                <h2 class="alert alert-info"><center>MODIFICAR REGISTRO - TAREA</center></h2>
                <form action="<?php echo site_url().'/prog/valida_update_act'?>" method="post" id="form_mod" name="form_mod" class="smart-form">
              <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
              <input type="hidden" name="act_id" id="act_id">
                <header><b>DATOS GENERALES DE LA TAREA</b></header>
                <fieldset>          
                  <div class="row">
                    <section class="col col-9">
                      <label class="label">TAREA</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="2" name="mactividad" id="mactividad" title="MODIFICAR ACTIVIDAD"></textarea>
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">TIPO DE INDICADOR</label>
                      <select class="form-control" id="tp_mindi" name="tp_mindi" title="SELECCIONE TIPO DE INDICADOR">
                          <option value="">Seleccione Tipo de Indicador</option>
                          <?php 
                            foreach($indi as $row){ ?>
                            <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                          <?php } ?>        
                      </select>
                    </section>
                  </div>

                  <div class="row">
                    <section class="col col-6">
                      <label class="label">INDICADOR</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="2" name="mindicador" id="mindicador" title="MODIFICAR DESCRIPCIÓN INDICADOR"></textarea>
                      </label>
                    </section>
                    <section class="col col-6">
                      <label class="label">MEDIO DE VERIFICACI&Oacute;N</label>
                      <label class="textarea">
                        <i class="icon-append fa fa-tag"></i>
                        <textarea rows="2" name="mverificacion" id="mverificacion" title="MODIFICAR MEDIO DE VERIFICACIÓN"></textarea>
                      </label>
                    </section>
                  </div>

                  <div class="row">
                    <section class="col col-3">
                      <label class="label">LINEA BASE</label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="mlbase" id="mlbase" value="0" title="MODIFICAR LINEA BASE" onkeyup="suma_programado_modificado()">
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">META</label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="mmeta" id="mmeta" value="0" title="MODIFICAR META" onkeyup="fmmeta()">
                      </label>
                    </section>
                    <section class="col col-3">
                      <label class="label">PONDERACI&Oacute;N</label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="mpcion" id="mpcion" value="0" value="0" title="MODIFICAR PONDERACI&Oacute;N">
                      </label>
                    </section>
                  </div>
                  <br>
                  <div id="amtit"></div>
                  <header><b>TEMPORALIDAD PROGRAMACIÓN FÍSICA: <?php echo $this->session->userdata('gestion')?></b><br>
                  <label class="label"><div id="ff"></div></label>
                  </header>
                  <br>
                  <div class="row">
                    <section class="col col-2">
                      <label class="label">PROGRAMADO TOTAL</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mtot" id="mtot" value="0" disabled="true">
                      </label>
                    </section>
                  </div>
                  <div class="row">
                    <section class="col col-2">
                      <label class="label">ENERO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ENERO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">FEBRERO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">MARZO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MARZO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">ABRIL</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE ABRIL - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">MAYO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE MAYO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">JUNIO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JUNIO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                  </div>
                  <div class="row">
                    <section class="col col-2">
                      <label class="label">JULIO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE JULIO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">AGOSTO</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">SEPTIEMBRE</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">OCTUBRE</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">NOVIEMBRE</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm11" id="mm11" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label">DICIEMBRE</label>
                      <label class="input">
                        <i class="icon-append fa fa-money"></i>
                        <input type="text" name="mm12" id="mm12" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                      </label>
                    </section>
                  </div>

                </fieldset>
                <div id="mbut">
                  <footer>
                    <button type="button" name="subir_mact" id="subir_mact" class="btn btn-info" >MODIFICAR DATOS TAREA</button>
                    <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                  </footer>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
    <!-- ======================================================== -->

    <!-- PAGE FOOTER -->
    <div class="page-footer">
      <div class="row">
        <div class="col-xs-12 col-sm-6">
          <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
        </div>
      </div>
    </div>
    
    <!-- END PAGE FOOTER -->    
    <script src="<?php echo base_url();?>/assets/js/libs/jquery-2.0.2.min.js"></script>
    <script>
      if (!window.jQuery.ui) {
        document.write('<script src="<?php echo base_url();?>/assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
      }
    </script>
    <!-- IMPORTANT: APP CONFIG -->
    <script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>
    <script src="<?php echo base_url();?>/assets/js/app.config.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js"></script>
    <!-- <SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/ejecucion/abm_ejecucion.js" type="text/javascript"></SCRIPT> -->
    <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
    <script src="<?php echo base_url();?>/assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
    <!-- BOOTSTRAP JS -->
    <script src="<?php echo base_url();?>/assets/js/bootstrap/bootstrap.min.js"></script>
    <!-- CUSTOM NOTIFICATION -->
    <script src="<?php echo base_url();?>/assets/js/notification/SmartNotification.min.js"></script>
    <!-- JARVIS WIDGETS -->
    <script src="<?php echo base_url();?>/assets/js/smartwidgets/jarvis.widget.min.js"></script>
    <!-- EASY PIE CHARTS -->
    <script src="<?php echo base_url();?>/assets/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
    <!-- SPARKLINES -->
    <script src="<?php echo base_url();?>/assets/js/plugin/sparkline/jquery.sparkline.min.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="<?php echo base_url();?>/assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <!-- JQUERY MASKED INPUT -->
    <script src="<?php echo base_url();?>/assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
    <!-- JQUERY SELECT2 INPUT -->
    <script src="<?php echo base_url();?>/assets/js/plugin/select2/select2.min.js"></script>
    <!-- JQUERY UI + Bootstrap Slider -->
    <script src="<?php echo base_url();?>/assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
    <!-- browser msie issue fix -->
    <script src="<?php echo base_url();?>/assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
    <!-- FastClick: For mobile devices -->
    <script src="<?php echo base_url();?>/assets/js/plugin/fastclick/fastclick.min.js"></script>
    <!-- Demo purpose only -->
    <script src="<?php echo base_url();?>/assets/js/demo.min.js"></script>
    <!-- MAIN APP JS FILE -->
    <script src="<?php echo base_url();?>/assets/js/app.min.js"></script>
    <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
    <!-- Voice command : plugin -->
    <script src="<?php echo base_url();?>/assets/js/speech/voicecommand.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
    <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
<!-- AGREGAR NUEVO REQUERIMIENTO -->
  <script type="text/javascript">
    $(function () {
        $("#subir_act").on("click", function () {
            var $validator = $("#form_nuevo").validate({
                  rules: {
                      prod_id: { //// producto
                      required: true,
                      },
                      proy_id: { //// proyecto
                          required: true,
                      },
                      actividad: { //// actividad
                          required: true,
                      },
                      tp_indi: { //// tipo de indicador
                          required: true,
                      },
                      indicador: { //// Indicador
                          required: true,
                      },
                      verificacion: { //// verificacion
                          required: true,
                      },
                      lbase: { //// linea base
                          required: true,
                      },
                      meta: { //// meta
                          required: true,
                      }
                  },
                  messages: {
                      actividad: "<font color=red>REGISTRE DETALLE DE LA ACTIVIDAD</font>", 
                      tp_indi: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>",
                      indicador: "<font color=red>REGISTRE DETALLE DEL INDICADOR</font>",
                      verificacion: "<font color=red>REGISTRE MEDIO DE VERIFICACI&Oacute;N</font>",
                      lbase: "<font color=red>REGISTRE LINEA BASE</font>",
                      meta: "<font color=red>REGISTRE META</font>",                     
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
                alertify.confirm("GUARDAR ACTIVIDAD ?", function (a) {
                    if (a) {
                    //    document.getElementById("load").style.display = 'block';
                        document.getElementById('subir_act').disabled = true;
                        document.forms['form_nuevo'].submit();
                    } else {
                        alertify.error("OPCI\u00D3N CANCELADA");
                    }
                });
            }
        });
    });
  </script>
  <script type="text/javascript">
    /*------------ MODIFICAR ACTIVIDAD ----------------*/
    $(function () {
        $(".mod_ff").on("click", function (e) {
        act_id = $(this).attr('name');
            document.getElementById("act_id").value=act_id;
        proy_id=document.getElementById("proy_id").value;

            var url = "<?php echo site_url("")?>/prog/get_actividad";
            var request;
            if (request) {
                request.abort();
            }
            request = $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                data: "act_id="+act_id+"&proy_id="+proy_id
            });

            request.done(function (response, textStatus, jqXHR) {
            if (response.respuesta == 'correcto') {
               document.getElementById("mactividad").value = response.actividad[0]['act_actividad'];
               document.getElementById("tp_mindi").value = response.actividad[0]['indi_id'];
               document.getElementById("mindicador").value = response.actividad[0]['act_indicador'];
               document.getElementById("mverificacion").value = response.actividad[0]['act_fuente_verificacion'];
               document.getElementById("mlbase").value = response.actividad[0]['act_linea_base'];
               document.getElementById("mmeta").value = response.actividad[0]['act_meta'];
               document.getElementById("mpcion").value = response.actividad[0]['act_ponderacion'];
               document.getElementById("mtot").value = response.suma;
              // document.getElementById("mtot").value = parseFloat(response.suma[0]['suma']+response.actividad[0]['act_linea_base']);
               document.getElementById("mm1").value = response.actividad[0]['enero'];
               document.getElementById("mm2").value = response.actividad[0]['febrero'];
               document.getElementById("mm3").value = response.actividad[0]['marzo'];
               document.getElementById("mm4").value = response.actividad[0]['abril'];
               document.getElementById("mm5").value = response.actividad[0]['mayo'];
               document.getElementById("mm6").value = response.actividad[0]['junio'];
               document.getElementById("mm7").value = response.actividad[0]['julio'];
               document.getElementById("mm8").value = response.actividad[0]['agosto'];
               document.getElementById("mm9").value = response.actividad[0]['septiembre'];
               document.getElementById("mm10").value = response.actividad[0]['octubre'];
               document.getElementById("mm11").value = response.actividad[0]['noviembre'];
               document.getElementById("mm12").value = response.actividad[0]['diciembre'];

               if(response.suma!=response.actividad[0]['act_meta']){
                $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
                $('#mbut').slideUp();
               }
            }
            else{
                alertify.error("ERROR AL RECUPERAR DATOS DE LA TAREA");
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
            $("#subir_mact").on("click", function (e) {
                var $validator = $("#form_mod").validate({
                       rules: {
                        act_id: { //// act
                            required: true,
                        },
                        mactividad: { //// Actividad
                            required: true,
                        },
                        proy_id: { //// Proyecto
                            required: true,
                        },
                        tp_mindi: { //// Tipo de indicador
                            required: true,
                        },
                        mindicador: { //// indicador
                            required: true,
                        },
                        mverificacion: { //// verificacion
                            required: true,
                        },
                        mlbase: { //// linea base
                            required: true,
                        },
                        mmeta: { //// meta
                            required: true,
                        }
                    },
                    messages: {
                        mactividad: "<font color=red>REGISTRE DETALLE DE ACTIVIDAD/font>",
                        tp_mindi: "<font color=red>SELECCIONE TIPO DE INDICADOR</font>", 
                        mindicador: "<font color=red>REGISTRE DETALLE INDICADOR</font>",
                        mverificacion: "<font color=red>REGISTRE MEDIO DE VERIFICACIÓN</font>",
                        mlbase: "<font color=red>REGISTRE LINEA BASE</font>",
                        mmeta: "<font color=red>REGISTRE META DE LA ACTIVIDAD</font>",                     
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

                    alertify.confirm("MODIFICAR DATOS DE TAREA ?", function (a) {
                        if (a) {
                        //    document.getElementById("loadd").style.display = 'block';
                            document.getElementById('subir_mact').disabled = true;
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
          alertify.confirm("ELIMINAR TAREA ?", function (a) {
            if (a) { 
                url = "<?php echo site_url("")?>/prog/delete_act";
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
                        alertify.alert("LA TAREA SE ELIMINO CORRECTAMENTE ", function (e) {
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
    function fmeta(){ 
      meta = parseFloat($('[name="meta"]').val()); //// Meta
      programado = parseFloat($('[name="tot"]').val()); //// Total Programado

        if(programado!=meta){
          $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
          $('#but').slideUp();
        }
        else{
          $('#atit').html('');
          $('#but').slideDown();
        }
      }

    function fmmeta(){ 
      meta = parseFloat($('[name="mmeta"]').val()); //// Meta
      programado = parseFloat($('[name="mtot"]').val()); //// Total Programado

        if(programado!=meta){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
          $('#mbut').slideUp();
        }
        else{

          $('#amtit').html('');
          $('#mbut').slideDown();
        }
      }

      function suma_programado(){ 
        sum=0;
        linea = parseFloat($('[name="lbase"]').val()); //// linea base
        for (var i = 1; i<=12; i++) {
          sum=parseFloat(sum)+parseFloat($('[name="m'+i+'"]').val());
        }

        $('[name="tot"]').val((sum+linea).toFixed(2));
        programado = parseFloat($('[name="tot"]').val()); //// programado total
        meta = parseFloat($('[name="meta"]').val()); //// Meta

        if(programado!=meta){
          $('#atit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
            $('#but').slideUp();
        }
        else{
          $('#atit').html('');
          $('#but').slideDown();
        }
      }

      function suma_programado_modificado(){ 
        sum=0;
        linea = parseFloat($('[name="mlbase"]').val()); //// linea base
        for (var i = 1; i <=12; i++) {
          sum=parseFloat(sum)+parseFloat($('[name="mm'+i+'"]').val());
        }

        $('[name="mtot"]').val((sum+linea).toFixed(2));
        programado = parseFloat($('[name="mtot"]').val()); //// programado total
        meta = parseFloat($('[name="mmeta"]').val()); //// Meta

        if(programado!=meta){
          $('#amtit').html('<center><div class="alert alert-danger alert-block">LA SUMA PROGRAMADA NO COINCIDE CON LA META DE LA ACTIVIDAD</div></center>');
              $('#mbut').slideUp();
        }
        else{
          $('#amtit').html('');
          $('#mbut').slideDown();
        }
      }
    </script>
  </body>
</html>