<!-- MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Marco Estrategico</li>
            <li><a href="<?php
                $atras = site_url("") . '/prog/me/objetivo';
                echo $atras; ?>" title="MIS OBJETIVOS ESTRATEGICOS">Resultados De Mediano Plazo</a></li>
            <li>Modificar</li>
        </ol>
    </div>
    <div id="content">
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 animated fadeInDown">
                <h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw "></i>
                    RESULTADOS DE MEDIANO PLAZO (MODIFICAR)
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2 pull-left">
                <a href="<?php $atras = site_url("") . '/prog/me/objetivo';echo $atras; ?>"
                   class="btn btn-labeled btn-success" title="ATRAS"> <span class="btn-label"><i class="fa fa-arrow-left" aria-hidden="true"></i></span>
                    <font size="1">ATRAS </font></a>
            </div>
        </div>
        <br>
        <section id="widget-grid" class="">
            <form name="form_mod_obj" id="form_mod_obj" method="post" action="<?php echo site_url("") . '/prog/me/mod_obj' ?>">
                <input type="hidden" name="obje_id" id="obje_id" value="<?php echo $dato_obje['obje_id'] ?>">
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                <!-- CONTENIDO DE FORMULARIO CABECERA    -->
                <div class="row">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="jarviswidget jarviswidget-color-darken">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>

                                <h2 class="font-md"><strong>VINCULACI&Oacute;N DE RESULTADOS DE MEDIANO PLAZO  (Modificar)</strong></h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><font size="1"><b>RESPONSABLE</b></font></label>
                                                <select class="form-control" id="fun_id" name="fun_id">
                                                    <option value=""> Seleccione el Responsable</option>
                                                    <?php echo $combo_resp; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label><font size="1"><b>UNIDAD ORGANIZACIONAL</b></font></label>
                                                <input type="text" class="form-control" name="unidad" id="unidad"
                                                       value="<?php echo $dato_obje['get_unidad'] ?>" disabled="disabled">
                                            </div>
                                        </div>

                                    </div><!-- row -->
                                    <label><b>PEDES</b></label>

                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><font size="1"><b>PILAR</b></font></label>
                                                <select class="form-control" id="pedes1" name="pedes1">
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
                                                <select class="form-control" id="pedes2" name="pedes2">
                                                    <?php
                                                    foreach ($lista_pdes as $row) {
                                                        if ($row['pdes_codigo'] == $dato_obje['pdes_mcod'] && $row['pdes_gestion'] == $dato_obje['obje_gestion_curso']) {
                                                            echo '<option value="' . $row['pdes_codigo'] . '" selected>' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['pdes_codigo'] . '">' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><font size="1"><b>RESULTADO</b></font></label>
                                                <select class="form-control" id="pedes3" name="pedes3">
                                                    <?php
                                                    foreach ($lista_pdes as $row) {
                                                        if ($row['pdes_codigo'] == $dato_obje['pdes_rcod'] && $row['pdes_gestion'] == $dato_obje['obje_gestion_curso']) {
                                                            echo '<option value="' . $row['pdes_codigo'] . '" selected>' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['pdes_codigo'] . '">' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><font size="1"><b>ACCI&Oacute;N</b></font></label>
                                                <select class="form-control" id="pedes4" name="pedes4">
                                                    <?php
                                                    foreach ($lista_pdes as $row) {
                                                        if ($row['pdes_codigo'] == $dato_obje['pdes_acod'] && $row['pdes_gestion'] == $dato_obje['obje_gestion_curso']) {
                                                            echo '<option value="' . $row['pdes_id'] . '" selected>' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['pdes_id'] . '">' .
                                                                $row['pdes_codigo'] . ' - ' . $row['pdes_nivel'] . ' - ' . $row['pdes_descripcion'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                    <label><b>PLAN ESTRAT&Eacute;GICO <font color="red">(En Proceso)</font></b></label>

                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><font size="1"><b>EJE ESTRAT&Eacute;GICO <font color="red">(En Proceso)</font></b></font></label>
                                                <input type="hidden" name="ptdi1" id="ptdi1" value="0">
                                                <select class="form-control" id="ptdi1" name="ptdi1" disabled="true">
                                                    <option value="">Seleccione una opci&oacute;n</option>
                                                    <?php
                                                    echo $combo_pilar_ptdi;
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><font size="1"><b>RESULTADO DE MEDIANO PLAZO <font color="red">(En Proceso)</font></b></font></label>
                                                <input type="hidden" name="ptdi2" id="ptdi2" value="0">
                                                <select class="form-control" id="ptdi2" name="ptdi2" disabled="true">
                                                    <?php
                                                    foreach ($lista_ptdi as $row) {
                                                        if ($row['ptdi_codigo'] == $dato_obje['ptdi_ocod'] && $row['ptdi_gestion'] == $dato_obje['obje_gestion_curso']) {
                                                            echo '<option value="' . $row['ptdi_codigo'] . '" selected>' .
                                                                $row['ptdi_codigo'] . ' - ' . $row['ptdi_nivel'] . ' - ' . $row['ptdi_descripcion'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['pdes_codigo'] . '">' .
                                                                $row['ptdi_codigo'] . ' - ' . $row['ptdi_nivel'] . ' - ' . $row['ptdi_descripcion'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label><font size="1"><b>PROGRAMA <font color="red">(En Proceso)</font></b></font></label>
                                                <input type="hidden" name="ptdi3" id="ptdi3" value="0">
                                                <select class="form-control" id="ptdi3" name="ptdi3" disabled="true">
                                                    <?php
                                                    foreach ($lista_ptdi as $row) {
                                                        if ($row['ptdi_codigo'] == $dato_obje['ptdi_pcod'] && $row['ptdi_gestion'] == $dato_obje['obje_gestion_curso']) {
                                                            echo '<option value="' . $row['ptdi_id'] . '" selected>' .
                                                                $row['ptdi_codigo'] . ' - ' . $row['ptdi_nivel'] . ' - ' . $row['ptdi_descripcion'] . '</option>';
                                                        } else {
                                                            echo '<option value="' . $row['ptdi_id'] . '">' .
                                                                $row['ptdi_codigo'] . ' - ' . $row['ptdi_nivel'] . ' - ' . $row['ptdi_descripcion'] . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

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

                                <h2 class="font-md"><strong>REGISTRO DE RESULTADOS DE MEDIANO PLAZO </strong></h2>
                            </header>
                            <div>
                                <!-- widget content -->
                                <div class="widget-body">
                                    <div class="well">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="form-group">
                                                    <label><b><font size="1">C&Oacute;DIGO</font></b></label>
                                                    <input class="form-control" type="text"
                                                           value="<?php echo $dato_obje['obje_codigo'] ?>"
                                                           disabled="true">
                                                </div>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label><b><font size="1">OBJETIVO</font></b></label>
                                                    <textarea rows="4" class="form-control" style="width:100%;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="obj"
                                                              id="obj"><?php echo $dato_obje['obje_objetivo'] ?></textarea>
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
                                                <?php
                                                if ($dato_obje['indi_id'] == 2) {
                                                    ?>
                                                    <div id="caja_denominador" name="caja_denominador">
                                                        <div class="col-sm-6">
                                                            <label for=""><b>Denominador</b></label>
                                                            <select class="form-control input-sm" id="denominador" name="denominador" onChange="denominador(this)">
                                                                <?php
                                                                if($dato_obje['obje_denominador'] == 0){
                                                                    ?>
                                                                    <option value="0" selected>Variable</option>
                                                                    <option value="1">Fijo</option>
                                                                <?php
                                                                }else{
                                                                    ?>
                                                                    <option value="0">Variable</option>
                                                                    <option value="1" selected>Fijo</option>
                                                                <?php
                                                                }
                                                                ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div id="caja_denominador" name="caja_denominador" style="display: none">
                                                        <div class="col-sm-6">
                                                            <label for=""><b>Denominador</b></label>
                                                            <select class="form-control input-sm" id="denominador"
                                                                    name="denominador" onChange="denominador(this)">
                                                                <option value="0">Variable</option>
                                                                <option value="1">Fijo</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                ?>

                                            </div>
                                            <br>
                                            <!-- --------------   CAJA RELATIVO ------------ -->
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><b><font size="1">INDICADOR</font></b></label>
                                                    <textarea rows="3" class="form-control" style="width:100%;"
                                                              style="text-transform:uppercase;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="indicador"
                                                              id="indicador"><?php echo $dato_obje['obje_indicador'] ?></textarea>
                                                </div>
                                            </div>
                                            <!-- --------------RELATIVO FORMULA------------ -->
                                            <?php
                                            if ($dato_obje['indi_id'] == 2) {
                                                ?>
                                                <div id="formula">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label><b><font size="1">FORMULA</font></b></label>
                                                            <textarea rows="3" class="form-control" style="width:100%;"
                                                                      style="text-transform:uppercase;"
                                                                      onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                      name="formula" id="formula"><?php echo $dato_obje['obje_formula'] ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            } else {
                                                ?>
                                                <div id="formula" style="display: none">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label><b><font size="1">FORMULA</font></b></label>
                                                            <textarea rows="3" class="form-control" style="width:100%;"
                                                                      style="text-transform:uppercase;"
                                                                      onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                                      name="formula" id="formula"><?php echo $dato_obje['obje_formula'] ?></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                            ?>


                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><b><font size="1">LINEA BASE</font></b></label>
                                                    <input class="form-control" type="number" name="lb" id="lb"
                                                           value="<?php echo $dato_obje['obje_linea_base'] ?>"
                                                           onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                           onpaste="return false">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <div id="met_a">
                                                        <label><b><font size="1">META </b><b id="por_meta"></b></font>
                                                        </label></div>
                                                    <input class="form-control" type="number" name="meta" id="meta"
                                                           placeholder="0 %" value="<?php echo $dato_obje['obje_meta'] ?>"
                                                           onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                           onpaste="return false">
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label><b><font size="1">PONDERACI&Oacute;N</font></b></label>
                                                    <input class="form-control" type="number" name="pn_cion"
                                                           id="pn_cion" value="0" placeholder="0 %"
                                                           value="<?php echo $dato_obje['obje_ponderacion'] ?>"
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
                                                              name="verificacion"
                                                              id="verificacion"><?php echo $dato_obje['obje_fuente_verificacion'] ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label><b><font size="1">SUPUESTOS</font></b></label>
                                                    <textarea rows="2" class="form-control" style="width:100%;"
                                                              style="text-transform:uppercase;"
                                                              onkeyup="javascript:this.value=this.value.toUpperCase();"
                                                              name="supuestos" id="supuestos"><?php echo $dato_obje['obje_supuestos'] ?></textarea>
                                                </div>
                                            </div>

                                        </div>
                                        <!-- --------------CASO RELATIVO CARACTERISTICAS------------ -->
                                        <?php
                                        if ($dato_obje['indi_id'] == 2) {
                                            ?>
                                            <div id="rel">
                                                <div class="row">
                                                    <label><font size="1"><b>CARACTERISTICAS</b></font></label>

                                                    <div class="form-group">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Total de Casos</b></font></label>
                                                                <input class="form-control" type="text" name="c_a" id="c_b"
                                                                       value="<?php echo $dato_obje['obje_total_casos'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Casos Favorables</b></font></label>
                                                                <input class="form-control" type="text" name="c_b" id="c_b"
                                                                       value="<?php echo $dato_obje['obje_casos_favorables'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Casos
                                                                            Desfavorables</b></font></label>
                                                                <input class="form-control" type="text" name="c_c" id="c_c"
                                                                       value="<?php echo $dato_obje['obje_casos_desfavorables'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div id="rel" style="display: none">
                                                <div class="row">
                                                    <label><font size="1"><b>CARACTERISTICAS</b></font></label>

                                                    <div class="form-group">
                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Total de Casos</b></font></label>
                                                                <input class="form-control" type="text" name="c_a" id="c_b"
                                                                       value="<?php echo $dato_obje['obje_total_casos'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Casos Favorables</b></font></label>
                                                                <input class="form-control" type="text" name="c_b" id="c_b"
                                                                       value="<?php echo $dato_obje['obje_casos_favorables'] ?>">
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4">
                                                            <div class="form-group">
                                                                <label><font size="1"><b>Casos
                                                                            Desfavorables</b></font></label>
                                                                <input class="form-control" type="text" name="c_c" id="c_c"
                                                                       value="<?php echo $dato_obje['obje_casos_desfavorables'] ?>">
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>


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
                                    <?php
                                    $porc_mod[1] = '';
                                    $porc_mod[2] = '%';
                                    ?>
                                    <div id="caja_indicador">
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
                                                                <b id="porc1"><?php echo $porc_mod[($dato_obje['indi_id'])] ?> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g1" id="g1"
                                                               placeholder="0" value="<?php echo $prog['prog1'] ?>"
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
                                                               placeholder="0" value="<?php echo $prog['prog2'] ?>"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 2); ?></b>
                                                                <b id="porc3"><?php echo $porc_mod[($dato_obje['indi_id'])] ?> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g3" id="g3"
                                                               placeholder="0" value="<?php echo $prog['prog3'] ?>"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 3); ?></b>
                                                                <b id="porc4"><?php echo $porc_mod[($dato_obje['indi_id'])] ?> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g4" id="g4"
                                                               placeholder="0" value="<?php echo $prog['prog4'] ?>"
                                                               onkeypress="if (this.value.length < 10) { return numerosDecimales(event);}else{return false; }"
                                                               onpaste="return false">
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <label><font size="1"><b>
                                                                    Gesti&oacute;n <?php echo($gestion_inicial + 4); ?></b>
                                                                <b id="porc5"><?php echo $porc_mod[($dato_obje['indi_id'])] ?> </b>
                                                            </font></label>
                                                        <input class="form-control" type="number" name="g5" id="g5"
                                                               placeholder="0" value="<?php echo $prog['prog5'] ?>"
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
                    <input type="button" id="modenviar_obj" name="modenviar_obj" value="MODIFICAR"
                           class="btn btn-primary btn-lg">
                </div>
        </section>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->






