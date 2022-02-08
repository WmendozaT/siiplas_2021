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

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/demo.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS"/>
    <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
    <?php echo $stylo;?>
  </head>
  <body class="">
    <!-- possible classes: minified, fixed-ribbon, fixed-header, fixed-width-->
    <!-- HEADER -->
    <header id="header">
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
               <li><a href="<?php echo base_url().'index.php/admin/proy/list_proy';?>" title="VOLVER A MI POA">Programaci&oacute;n POA</a></li><li>Unidad Reponsable</li><li>Mis Actividades</li> 
          </ol>
      </div>
      <!-- END RIBBON -->
        <!-- MAIN CONTENT -->
        <div id="content">
          <div class="row">
            <article class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
              <section id="widget-grid" class="well">
                <ul class="nav nav-pills">
                  <li><a href="<?php echo base_url().'index.php/prog/list_serv/'.$fase[0]['proy_id'].'';?>">UNIDAD RESPONSABLE</a></li>
                  <li class="active"><a href="#">MIS ACTIVIDADES</a></li>
                </ul>
              </section>
            </article>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
              <section id="widget-grid" class="well">
              <center>
                <div class="dropdown">
                  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" style="width:80%;" data-toggle="dropdown" aria-expanded="true" style="width:100%;">
                    OPCIONES
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/dashboard' ?>">SALIR A MENU PRINCIPAL</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/admin/proy/list_proy'?>">SALIR A MIS POAS</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo base_url().'index.php/prog/list_serv/'.$fase[0]['proy_id'].'';?>">SALIR A MIS UNIDADES</a></li>
                    <li role="presentation"><a role="menuitem" tabindex="-1" class="btn btn-success" onclick="update_codigo()"><font color="#ffffff">ACTUALIZAR C&Oacute;DIGOS</font></a></li>
                    <?php
                      if($this->session->userdata('tp_adm')==1 ){ ?>
                        <li ><a onclick="eliminar_requerimientos_servicio()" class="btn btn-danger" style="width:100%;" title="Eliminar Requerimientos de la unidad (todos)"><font color="#ffffff">ELIMINAR REQUERIMIENTOS UNIDAD (TODOS)</font></a></li>
                        <!-- <li ><a onclick="eliminar_requerimientos()" class="btn btn-danger" style="width:100%;" title="Eliminar Requerimientos (todos)"><font color="#ffffff">ELIMINAR REQUERIMIENTOS (TODOS)</font></a></li> -->
                      <?php
                      }
                    ?>
                  </ul>
                </div>
              </center>
              </section>
            </article>
          </div>

          <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <section id="widget-grid" class="well">
                <div class="">
                  <?php echo $datos_proyecto;?>
                  <p>
                    <button class="btn btn-default" type="button" data-toggle="collapse" data-target=".multi-collapse" aria-expanded="false" aria-controls="multiCollapseExample1 multiCollapseExample2">LISTA DE OPERACIONES REGIONALES ALINEADOS</button>
                  </p>
                  <div class="collapse multi-collapse" id="multiCollapseExample1">
                    <div class="card card-body">
                      <?php echo $oregional;?>
                    </div>
                  </div>
                  <h1> UNIDAD RESPONSABLE : <small><?php echo $componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion']; ?></small></h1>
                </div>
            </section>
          </article>
          
            <?php
              $attributes = array('id' => 'del_req','name' =>'del_req','enctype' => 'multipart/form-data');
                            echo validation_errors();
                            echo form_open('prog/delete_operaciones', $attributes);
            ?>

            <section id="widget-grid" class="">
            <div class="row">
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
                    <h2 class="font-md"><strong><?php echo count($productos);?> ACTIVIDADES REGISTRADOS</strong></h2>  
                  </header>
                <div>
                  <?php echo $button;
                  ?>
                    <div class="widget-body no-padding">
                      <form id="del_req" name="del_req" novalidate="novalidate" method="post">
                        <?php echo $prod; ?>
                        <input type="hidden" name="tot" id="tot" value="0">
                        <div class="alert alert-danger" align=right><input type="button" class="btn btn-danger btn-xs" value="ELIMINAR ACTIVIDADES" id="btsubmit" onclick="valida_eliminar()" title="ELIMINAR OPERACIONES"></div>
                      </form>
                    </div>
                    <!-- end widget content -->
                  </div>
                  <!-- end widget div -->
                </div>
                <!-- end widget -->
              </article>
            </div>
          </section>      
        <!--///////////fin de tabla///////-->
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white"><?php echo $this->session->userData('name').' @ '.$this->session->userData('gestion') ?></span>
        </div>
    </div>
</div>

  <!-- ==== MODAL FORMULARIO DE REGISTRO FORM 4 ==== -->
  <div class="modal fade" id="modal_nuevo_form" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" id="mdialTamanio">
      <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>NUEVO REGISTRO FORM N° 4 - ACTIVIDAD <?php echo $this->session->userData('gestion');?></center></h2>
              <form action="<?php echo site_url().'/programacion/producto/valida_producto'?>" id="form_nuevo" name="form_nuevo" class="smart-form" method="post">
                  <input type="hidden" name="com_id" id="com_id" value="<?php echo $componente[0]['com_id'];?>"> 
                  <input type="hidden" name="proy_id" id="proy_id" value="<?php echo $proyecto[0]['proy_id'];?>">
                  <header><b>DATOS GENERALES DE LA ACTIVIDAD</b></header>
                  <fieldset>          
                    <div class="row">
                      <section class="col col-1">
                        <label class="label"><b>C&Oacute;DIGO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="hidden" name="cod" id="cod" value="<?php echo (count($productos)+1);?>">
                          <input type="text" title="C&Oacute;DIGO ACTIVIDAD POR DEFECTO" disabled="true" value="<?php echo (count($productos)+1);?>">
                        </label>
                      </section>
                      <section class="col col-5">
                        <label class="label"><b>DESCRIPCI&Oacute;N DE LA ACTIVIDAD</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="prod" id="prod" title="REGISTRAR OPERACIÓN"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label"><b>RESULTADO</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="resultado" id="resultado" title="REGISTRAR RESULTADO"></textarea>
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>TIPO DE INDICADOR</b></label>
                        <select class="form-control" id="tipo_i" name="tipo_i" title="SELECCIONE TIPO DE INDICADOR">
                            <option value="">Seleccione Tipo de Indicador</option>
                            <?php 
                              foreach($indi as $row){ ?>
                              <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                            <?php } ?>        
                        </select>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-4">
                        <label class="label"><b>INDICADOR</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="indicador" id="indicador" title="REGISTRE DESCRIPCIÓN INDICADOR"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="verificacion" id="verificacion" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                        </label>
                      </section>
                      <section class="col col-4">
                        <label class="label"><b>UNIDAD / SERVICIO RESPONSABLE</b></label>
                        <label class="textarea">
                          <i class="icon-append fa fa-tag"></i>
                          <textarea rows="2" name="unidad" id="unidad" title="REGISTRE UNIDAD RESPONSABLE"></textarea>
                        </label>
                      </section>
                    </div>

                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>LINEA BASE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="lbase" id="lbase" value="0" title="REGISTRE LINEA BASE" onkeyup="suma_programado()">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>META</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-tag"></i>
                          <input type="text" name="meta" id="meta" value="0" onkeyup="verif_suma_programado()" title="REGISTRE META">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>NECESITA PRESUPUESTO ?</b></label>
                        <select class="form-control" id="ppto" name="ppto" title="NECESITA PRESUPUESTO">
                          <option value="1">SI</option>
                          <option value="0">NO</option>       
                        </select>
                      </section>
                      <?php echo $list_oregional;?>
                      <div id="trep" style="display:none;" >
                        <section class="col col-3">
                          <label class="label"><b>TIPO DE META</b></label>
                            <select class="form-control" id="tp_met" name="tp_met" title="SELECCIONE TIPO DE META">
                              <option value="">Seleccione Tipo de Meta</option>
                                <?php 
                                  foreach($metas as $row){ 
                                    if($row['mt_id']==3){ ?>
                                      <option value="<?php echo $row['mt_id']; ?>" selected><?php echo $row['mt_tipo']; ?></option>
                                      <?php
                                    }
                                    else{ ?>
                                      <option value="<?php echo $row['mt_id']; ?>"><?php echo $row['mt_tipo']; ?></option>
                                      <?php
                                    }
                                  }
                                ?>
                          </select>
                        </section><br>  
                      </div>
                    </div>
                 
                    <div id="atit"></div>
                    <header><b>DISTRIBUCI&Oacute;N F&Iacute;SICA : <?php echo $this->session->userdata('gestion')?></b><br>
                      <label class="label"><div id="ff"></div></label>
                    </header>
                    <br>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label">PROGRAMADO TOTAL</label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="total" id="total" value="0" disabled="true">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>ENERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m1" id="m1" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ENERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>FEBRERO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m2" id="m2" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MARZO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m3" id="m3" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MARZO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>ABRIL</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m4" id="m4" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ABRIL - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>MAYO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m5" id="m5" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MAYO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>JUNIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m6" id="m6" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JUNIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                    <div class="row">
                      <section class="col col-2">
                        <label class="label"><b>JULIO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m7" id="m7" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JULIO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>AGOSTO</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m8" id="m8" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>SEPTIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m9" id="m9" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>OCTUBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m10" id="m10" value="0" onkeyup="suma_programado()"onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>NOVIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m11" id="m11" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                      <section class="col col-2">
                        <label class="label"><b>DICIEMBRE</b></label>
                        <label class="input">
                          <i class="icon-append fa fa-money"></i>
                          <input type="text" name="m12" id="m12" value="0" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                        </label>
                      </section>
                    </div>
                  </fieldset>
        
                  <div id="but" style="display:none;">
                    <footer>
                      <button type="button" name="subir_ope" id="subir_ope" class="btn btn-info" >GUARDAR DATOS ACTIVIDAD</button>
                      <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
                    </footer>
                    <div id="loadp" style="display: none" align="center">
                      <br><img  src="<?php echo base_url() ?>/assets/img_v1.1/preloader.gif" width="100"><br><b>GUARDANDO INFORMACI&Oacute;N</b>
                    </div>
                  </div>
              </form>
              </div>
          </div>
      </div>
  </div>


    <!-- ============ Modal Modificar formulario N4 ========= -->
    <div class="modal fade" id="modal_mod_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" id="mdialTamanio">
        <div class="modal-content">
          <div class="modal-header">
            <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
            <h2 class="alert alert-info"><center>MODIFICAR REGISTRO FORM N°4 - ACTIVIDAD</center></h2>
            <form action="<?php echo site_url().'/programacion/producto/valida_update_form4'?>" method="post" id="form_mod" name="form_mod" class="smart-form">
            <input type="hidden" name="prod_id" id="prod_id">
              <header><b>DATOS GENERALES DE LA ACTIVIDAD</b></header>
              <fieldset>
                <div class="row">
                  <section class="col col-1">
                    <label class="label"><b>C&Oacute;DIGO</b></label>
                    <label class="input">
                      <i class="icon-append fa fa-tag"></i>
                      <input type="text" name="mcod" id="mcod" disabled="true">
                    </label>
                  </section>
                  <section class="col col-5">
                    <label class="label"><b>DESCRIPCI&Oacute;N DE LA ACTIVIDAD</b></label>
                    <label class="textarea">
                      <i class="icon-append fa fa-tag"></i>
                      <textarea rows="3" name="mprod" id="mprod" title="REGISTRAR ACTIVIDAD"></textarea>
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label"><b>RESULTADO</b></label>
                    <label class="textarea">
                      <i class="icon-append fa fa-tag"></i>
                      <textarea rows="3" name="mresultado" id="mresultado" title="REGISTRAR RESULTADO"></textarea>
                    </label>
                  </section>
                  <section class="col col-2">
                    <label class="label"><b>TIPO DE INDICADOR</b></label>
                    <select class="form-control" id="mtipo_i" name="mtipo_i" title="SELECCIONE TIPO DE INDICADOR">
                        <option value="">Seleccione Tipo de Indicador</option>
                        <?php 
                          foreach($indi as $row){ ?>
                          <option value="<?php echo $row['indi_id'];?>"><?php echo $row['indi_descripcion'];?></option>
                        <?php } ?>        
                    </select>
                  </section>
                </div>

                <div class="row">
                  <section class="col col-4">
                    <label class="label"><b>INDICADOR</b></label>
                    <label class="textarea">
                      <i class="icon-append fa fa-tag"></i>
                      <textarea rows="2" name="mindicador" id="mindicador" title="REGISTRE DESCRIPCIÓN INDICADOR"></textarea>
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label"><b>MEDIO DE VERIFICACI&Oacute;N</b></label>
                    <label class="textarea">
                      <i class="icon-append fa fa-tag"></i>
                      <textarea rows="2" name="mverificacion" id="mverificacion" title="REGISTRE MEDIO DE VERIFICACIÓN"></textarea>
                    </label>
                  </section>
                  <section class="col col-4">
                    <label class="label"><b>UNIDAD / SERVICIO RESPONSABLE</b></label>
                    <label class="textarea">
                      <i class="icon-append fa fa-tag"></i>
                      <textarea rows="2" name="munidad" id="munidad" title="REGISTRE UNIDAD RESPONSABLE"></textarea>
                    </label>
                  </section>
                </div>

                <div class="row">
                    <section class="col col-2">
                      <label class="label"><b>LINEA BASE</b></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="mlbase" id="mlbase" value="0" title="REGISTRE LINEA BASE" onkeyup="suma_programado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">>
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label"><b>META</b></label>
                      <label class="input">
                        <i class="icon-append fa fa-tag"></i>
                        <input type="text" name="mmeta" id="mmeta" value="0" onkeyup="verif_meta_mod()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true">
                      </label>
                    </section>
                    <section class="col col-2">
                      <label class="label"><b>NECESITA PRESUPUESTO ?</b></label>
                      <select class="form-control" id="mppto" name="mppto" title="NECESITA PRESUPUESTO">
                        <option value="1">SI</option>
                        <option value="0">NO</option>       
                      </select>
                    </section>

                    <section class="col col-4">
                      <label class="label"><b>ALINEACIÓN OPERACI&Oacute;N REGIONAL</b></label>
                      <select class="form-control" id="mor_id" name="mor_id" title="SELECCIONE ALINEACIÓN REGIONAL">
                          <option value="">SELECCIONE ALINEACIÓN OPERACIÓN</option>
                          <?php 
                            foreach($objetivos as $row){ ?>
                            <option value="<?php echo $row['or_id'];?>"><?php echo $row['og_codigo'].'.'.$row['or_codigo'].'. .- '.$row['or_objetivo'];?></option>
                          <?php } ?>        
                      </select>
                    </section>

                    <div id="mtrep" style="display:none;" >
                      <section class="col col-2">
                        <label class="label"><b>TIPO DE META</b></label>
                          <select class="form-control" id="mtp_met" name="mtp_met" title="SELECCIONE TIPO DE META">
                            <option value="">Seleccione Tipo de Meta</option>
                              <?php 
                                foreach($metas as $row){ ?>
                                  <option value="<?php echo $row['mt_id']; ?>"><?php echo $row['mt_tipo']; ?></option>
                                  <?php
                                }
                              ?>
                        </select>
                      </section><br>  
                    </div>

                    <div id="priori"></div>

                </div>
              <br>
              <div id="matit"></div>
              <header><b>DISTRIBUCI&Oacute;N F&Iacute;SICA : <?php echo $this->session->userdata('gestion')?></b><br>
              <label class="label"><div id="ff"></div></label>
              </header>
              <br>
              <div class="row">
                <section class="col col-2">
                  <label class="label"><b>PROGRAMADO TOTAL</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mtotal" id="mtotal" value="0" disabled="true">
                  </label>
                </section>
              </div>
              <div class="row">
                <section class="col col-2">
                  <label class="label"><b>ENERO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm1" id="mm1" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ENERO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>FEBRERO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm2" id="mm2" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="FEBRERO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>MARZO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm3" id="mm3" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MARZO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>ABRIL</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm4" id="mm4" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="ABRIL - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>MAYO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm5" id="mm5" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="MAYO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>JUNIO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm6" id="mm6" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JUNIO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
              </div>
              <div class="row">
                <section class="col col-2">
                  <label class="label"><b>JULIO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm7" id="mm7" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="JULIO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>AGOSTO</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm8" id="mm8" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"  onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE AGOSTO - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>SEPTIEMBRE</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm9" id="mm9" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE SEPTIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>OCTUBRE</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm10" id="mm10" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE OCTUBRE - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>NOVIEMBRE</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm11" id="mm11" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE NOVIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
                <section class="col col-2">
                  <label class="label"><b>DICIEMBRE</b></label>
                  <label class="input">
                    <i class="icon-append fa fa-money"></i>
                    <input type="text" name="mm12" id="mm12" value="0" onkeyup="suma_programado_modificado()" onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }" onpaste="return false" required="true" title="PROGRAMACION FINANCIERA MES DE DICIEMBRE - <?php echo $this->session->userdata('gestion')?>">
                  </label>
                </section>
              </div>

            </fieldset>
            
            <div id="mbut">
              <footer>
                <button type="button" name="subir_mform4" id="subir_mform4" class="btn btn-info" >MODIFICAR ACTIVIDAD</button>
                <button class="btn btn-default" data-dismiss="modal" id="amcl" title="CANCELAR">CANCELAR</button>
              </footer>
              <center><img id="loadm" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="45" height="45"></center>
            </div>
          </form>
            </div>
          </div>
        </div>
    </div>
    <!-- ======================================================== -->

    <!---- MODAL SUBIR PLANTILLA ---->
    <div class="modal fade" id="modal_importar_ff" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog" id="mdialTamanio2">
        <div class="modal-content">
          <div class="modal-header">
              <button class="close" data-dismiss="modal" id="amcl" title="SALIR"><span aria-hidden="true">&times; <b>Salir Formulario</b></span></button>
          </div>
          <div class="modal-body">
              <h2><div id="titulo"></div></h2>
              <section id="widget-grid" class="">
                <div>
                  <?php echo $datos_proyecto;?>
                  <h1> UNIDAD RESPONSABLE : <small><?php echo $componente[0]['tipo_subactividad'].' '.$componente[0]['serv_descripcion']; ?></small></h1>
                </div>
              </section>
              <div class="row">
                <form action="<?php echo site_url().'/programacion/producto/importar_operaciones_requerimientos'?>" enctype="multipart/form-data" id="form_subir_sigep" name="form_subir_sigep" class="form-horizontal" method="post">
                  <input type="hidden" name="com_id" value="<?php echo $componente[0]['com_id'];?>">
                  <input type="hidden" name="tp" id="tp">
                  <fieldset>
                    <div class="form-group">
                      <center><div id="img"></div></center>
                      <hr>
                        <p class="alert alert-info">
                          <i class="fa fa-info"></i> Por favor guardar el archivo (Excel.xls) a extension (.csv) delimitado por (; "Punto y comas"). verificar el archivo .csv para su correcta importaci&oacute;n
                        </p>
                    </div>
                  </fieldset>  
                
                  <div class="form-group">
                    <b>SELECCIONAR ARCHIVO CSV</b>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <span class="btn btn-primary" onclick="$(this).parent().find('input[type=file]').click();">Browse</span>
                        <input  id="archivo" accept=".csv" name="archivo" onchange="$(this).parent().parent().find('.form-control').html($(this).val().split(/[\\|/]/).pop());" style="display: none;" type="file">
                        <input name="MAX_FILE_SIZE" type="hidden" value="20000" />
                      </span>
                      <span class="form-control"></span>
                    </div>
                </div>
                  
                  <div>
                      <button type="button" name="subir_archivo" id="subir_archivo" class="btn btn-success" style="width:100%;"><div id="buton"></div></button><br>
                      <center><img id="load" style="display: none" src="<?php echo base_url() ?>/assets/img/loading.gif" width="50" height="50"></center>
                  </div>
                </form> 
              </div>
            </div>
        </div>
      </div>
    </div>

    <!--================================================== -->
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
<script src="<?php echo base_url(); ?>assets/js/app.config.js"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
<script src="<?php echo base_url(); ?>assets/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script>
<!--tablas-->
<!--fin tablas-->
<!-- BOOTSTRAP JS -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
<!-- CUSTOM NOTIFICATION -->
<script src="<?php echo base_url(); ?>assets/js/notification/SmartNotification.min.js"></script>
<!--  JARVIS WIDGETS -->
<script src="<?php echo base_url(); ?>assets/js/smartwidgets/jarvis.widget.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="<?php echo base_url(); ?>assets/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="<?php echo base_url(); ?>assets/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY SELECT2 INPUT -->

<!-- JQUERY UI + Bootstrap Slider -->
<script src="<?php echo base_url(); ?>assets/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
<!-- browser msie issue fix -->
<script src="<?php echo base_url(); ?>assets/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
<!-- FastClick: For mobile devices -->
<script src="<?php echo base_url(); ?>assets/js/plugin/fastclick/fastclick.min.js"></script>
<!-- Demo purpose only -->
<script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>
<!-- MAIN APP JS FILE -->
<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
<!-- Voice command : plugin -->
<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
<!-- ///// mis validaciones js ///// -->
<SCRIPT src="<?php echo base_url(); ?>assets/js/mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
<!--alertas -->
<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<script src="<?php echo base_url(); ?>mis_js/programacionpoa/form4.js"></script> 

  <script type="text/javascript">
    $(document).ready(function(){
      $("#kwd_search").keyup(function(){
        if( $(this).val() != ""){
          $("#table tbody>tr").hide();
          $("#table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
        }
        else{
          $("#table tbody>tr").show();
        }
      });
    });
    $.extend($.expr[":"], 
    {
      "contains-ci": function(elem, i, match, array){
      return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
      }
    });
  </script>
  <script src = "<?php echo base_url(); ?>mis_js/programacion/programacion/tablas.js"></script>
</body>
</html>
