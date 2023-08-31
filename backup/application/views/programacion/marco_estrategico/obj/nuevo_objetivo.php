<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Marco Estrategico</li>
            <li><a href="<?php
                $atras = site_url("") . '/prog/me/objetivo';
                echo $atras; ?>" title="MIS OBJETIVOS ESTRATEGICOS">Resultados de Mediano Plazo
            </a></li>
            <li>(Nuevo)</li>
        </ol>
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 animated fadeInDown">
                <h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw "></i>
                    VINCULACI&Oacute;N DE RESULTADOS DE MEDIANO PLAZO (NUEVO)
                </h1>
            </div>
        </div>
        <section id="widget-grid" class="">
            <form name="form_nuevo_obj" id="form_nuevo_obj" method="post"
                  action="<?php echo site_url("") . '/prog/me/add_obj' ?>">
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                <!-- CONTENIDO DE FORMULARIO CABECERA    -->
                <div class="row">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget jarviswidget-color-darken">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>VINCULACI&Oacute;N DE RESULTADOS DE MEDIANO PLAZO (Nuevo)</strong></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><font size="1"><b>RESPONSABLE DEL OBJETIVO</b></font></label>
                                                <select class="select2" id="fun_id" name="fun_id">
                                                    <option value=""> Seleccione el Responsable</option>
                                                    <?php echo $combo_resp; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><font size="1"><b>UNIDAD ORGANIZACIONAL</b></font></label>
                                                <input type="text" class="form-control" name="unidad" id="unidad" disabled="disabled">
                                            </div>
                                        </div>

                                    </div><!-- row -->
                                    <label><b>PEDES</b></label>

                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><font size="1"><b>PILAR</b></font></label>
                                                    <select class="select2" id="pedes1" name="pedes1">
                                                        <option value="">Seleccione una opci&oacute;n</option>
                                                        <?php
                                                        echo $combo_pilar_pdes;
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><font size="1"><b>META</b></font></label>
                                                    <select class="select2" id="pedes2" name="pedes2">
                                                        <option value="">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><font size="1"><b>RESULTADO</b></font></label>
                                                    <select class="select2" id="pedes3" name="pedes3">
                                                        <option value="">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><font size="1"><b>ACCI&Oacute;N</b></font></label>
                                                    <select class="select2" id="pedes4" name="pedes4">
                                                        <option value="">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <label><b>PLAN ESTRAT&Eacute;GICO <font color="red">(En Proceso)</font></b></label>
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>EJE ESTRAT&Eacute;GICO <font color="red">(En Proceso)</font></b></font></label>
                                                    <input type="hidden" name="ptdi1" id="ptdi1" value="0">
                                                    <select class="select2" id="ptdi1" name="ptdi1" disabled="true">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>RESULTADO DE MEDIANO PLAZO <font color="red">(En Proceso)</font></b></font></label>
                                                    <input type="hidden" name="ptdi2" id="ptdi2" value="0">
                                                    <select class="select2" id="ptdi2" name="ptdi2" disabled="true">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>PROGRAMA <font color="red">(En Proceso)</font></b></font></label>
                                                    <input type="hidden" name="ptdi3" id="ptdi3" value="0">
                                                    <select class="select2" id="ptdi3" name="ptdi3" disabled="true">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>EJE ESTRAT&Eacute;GICO</b></font></label>
                                                    <select class="select2" id="ptdi1" name="ptdi1">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                        <?php
                                                        echo $combo_pilar_ptdi;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>RESULTADO DE MEDIANO PLAZO</b></font></label>
                                                    <select class="select2" id="ptdi2" name="ptdi2">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><font size="1"><b>PROGRAMA</b></font></label>
                                                    <select class="select2" id="ptdi3" name="ptdi3">
                                                        <option value="0">Seleccione una opci&oacute;n</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->

                                </div>
                            </div>
                        </div>

                    </article>
                </div>
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                <div class="row">
                    <!-- CONTENIDO DE FORMULARIO LATERAL -->
                    <article class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
                        <div class="jarviswidget jarviswidget-color-darken">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>
                                <h2 class="font-md"><strong>REGISTRO DEL RESULTADO </strong></h2>
                            </header>
                            <div>
                                <!-- widget content -->
                                <div class="widget-body">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><b><font size="1">C&Oacute;DIGO</font></b></label>
                                                    <input class="form-control" type="text" value="AUTOMÁTICO"
                                                           disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label><b><font size="1">RESULTADO</font></b></label>
                                                    <textarea rows="4" class="form-control" style="width:100%;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="obj" id="obj"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><b><font size="1">TIPO DE INDICADOR</font></b></label>
                                                        <select class="form-control" id="tipo_i" name="tipo_i">
                                                            <option value="">Seleccione Indicador</option>
                                                            <?php echo $combo_indicador; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div id="caja_denominador" name="caja_denominador" style="display: none;">
                                                    <div class="col-sm-6">
                                                        <label for=""><b>Denominador</b></label>
                                                        <select class="form-control input-sm" id="o_denominador"
                                                                name="denominador" onChange="denominador(this)">
                                                            <option value="0">Variable</option>
                                                            <option value="1">Fijo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <!-- --------------   CAJA RELATIVO ------------ -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><b><font size="1">INDICADOR</font></b></label>
                                                    <textarea rows="3" class="form-control" style="width:100%;"
                                                              style="text-transform:uppercase;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="indicador" id="indicador"></textarea>
                                                </div>
                                            </div>
                                            <!-- --------------RELATIVO FORMULA------------ -->
                                            <div id="formula" style="display:none;">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label><b><font size="1">FORMULA</font></b></label>
                                                            <textarea rows="3" class="form-control" style="width:100%;"
                                                                      style="text-transform:uppercase;"
                                                                      onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                      name="formula" id="formula"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><b><font size="1">LINEA BASE</font></b></label>
                                                    <input class="form-control" type="number" name="lb" id="lb"
                                                           value="0"
                                                           onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                           onpaste="return false">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div id="met_a">
                                                        <label><b><font size="1">META </b><b id="por_meta"></b></font></label></div>
                                                    <input class="form-control" type="number" name="meta" id="meta"
                                                           placeholder="0 %"
                                                           onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                           onpaste="return false">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><b><font size="1">PONDERACI&Oacute;N</font></b></label>
                                                    <input class="form-control" type="number" name="pn_cion"
                                                           id="pn_cion" value="0" placeholder="0 %"
                                                           onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                           onpaste="return false">
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><b><font size="1">FUENTE DE
                                                                VERIFICACI&Oacute;N</font></b></label>
                                                    <textarea rows="2" class="form-control" style="width:100%;"
                                                              style="text-transform:uppercase;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="verificacion" id="verificacion"></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><b><font size="1">SUPUESTOS</font></b></label>
                                                    <textarea rows="2" class="form-control" style="width:100%;"
                                                              style="text-transform:uppercase;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="supuestos" id="supuestos"></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- --------------CASO RELATIVO CARACTERISTICAS------------ -->
                                        <div id="rel" style="display:none;">
                                            <div class="row">
                                                <label><font size="1"><b>CARACTERISTICAS</b></font></label>

                                                <div class="form-group">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label><font size="1"><b>Total de Casos</b></font></label>
                                                            <input class="form-control" type="text" name="c_a" id="c_b">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label><font size="1"><b>Casos Favorables</b></font></label>
                                                            <input class="form-control" type="text" name="c_b" id="c_b">
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label><font size="1"><b>Casos
                                                                        Desfavorables</b></font></label>
                                                            <input class="form-control" type="text" name="c_c" id="c_c">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div><!-- well -->
                                </div>
                            </div>
                        </div>
                    </article>
                    <!-- --------------ARTICLE DE INDICADORES------------ -->
                    <article class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="jarviswidget jarviswidget-color-darken">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>

                                <h2 class="font-md"><strong>CRONOGRAMA DE EJECUCI&Oacute;N</strong></h2>
                            </header>
                            <div>
                                <!-- INDICADORES -->
                                <div class="widget-body">
                                    <div id="caja_indicador" style="display:none;">
                                        <div class="well">
                                            <div class="alert alert-block alert-success">
                                                <center><label><font size="1">
                                                            <b id="titulo_indicador"></b>
                                                            <b>
                                                                <?php
                                                                //$gestion_inicial = $this->session->userData('gestion');
                                                                $gestion_inicial = 2016;
                                                                $gestion_final = $gestion_inicial + 4;
                                                                echo $gestion_inicial . ' - ' . $gestion_final;
                                                                ?>
                                                            </b></font></label>
                                                </center>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo $gestion_inicial ?></b>
                                                                <b id="porc1"> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g1" id="g1"
                                                               placeholder="0" value="0"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 1); ?></b>
                                                                <b id="porc2"> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g2" id="g2"
                                                               placeholder="0" value="0"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 2); ?></b>
                                                                <b id="porc3"> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g3" id="g3"
                                                               placeholder="0" value="0"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 3); ?></b>
                                                                <b id="porc4"> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g4" id="g4"
                                                               placeholder="0" value="0"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 4); ?></b>
                                                                <b id="porc5"> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g5" id="g5"
                                                               placeholder="0" value="0"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                <!-- ++++++++++++++++++++++++  ENVIAR FORMULARIO    ++++++++++++++++++++++++++++++++++++++++++ -->
                <div class="form-actions">
                    <a href="<?php echo $atras; ?>" class="btn btn-lg btn-default"> CANCELAR </a>
                    <input type="button" id="enviar_obj" name="enviar_obj" value="GUARDAR" class="btn btn-primary btn-lg">
                </div>
        </section>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->






