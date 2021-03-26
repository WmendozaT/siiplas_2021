<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

				<span class="ribbon-button-alignment">
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"
                          rel="tooltip" data-placement="bottom"
                          data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
                          data-html="true">
						<i class="fa fa-refresh"></i>
					</span>
				</span>

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Marco Estrategico</li>
            <li><a href="<?php echo site_url() .'/prog/me/objetivo' ?>" title="RESULTADO DE MEDIANO PLAZO">Resultados de Mediano Plazo</a></li>
            <li>(Indicador de Desempeño)</li>
        </ol>
    </div>
    <!-- END RIBBON -->
    <?php
    $attributes = array('class' => 'form-horizontal', 'id' => 'formulario', 'name' => 'formulario', 'enctype' => 'multipart/form-data');
    echo validation_errors();
    echo form_open('prog/me/add_id', $attributes);
    ?>
    <!-- MAIN CONTENT -->
    <div id="content">
        <section id="widget-grid" class="">
            <!-- row -->
            <form name="formulario" id="formulario" method="post">
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
                <div class="row">
                    <input class="form-control" type="hidden" name="id" id="id"
                           value="<?php echo $objetivo[0]['obje_id']; ?>">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="well">
                            <font size="2"><b>RESULTADO DE MEDIANO PLAZO: </b><?php echo $objetivo[0]['obje_objetivo'] ?>
                            </font>
                        </div>
                        <div class="jarviswidget jarviswidget-color-darken">
                            <header>
                                <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>

                                <h2 class="font-md"><strong>RESULTADO DE MEDIANO PLAZO (Indicador de Desempeño)</strong>
                                </h2>
                            </header>
                            <div>
                                <div class="widget-body">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label><b><font size="2">EFICACIA</font></b></label>
                                                <textarea rows="3" class="form-control" style="width:100%;" name="ef1"
                                                          id="ef1" style="text-transform:uppercase;"
                                                          onkeyup="javascript:this.value=this.value.toUpperCase();"><?php echo $objetivo[0]['obje_eficacia'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label></label><br>
                                                <label><b><font size="2" color="blue"> > 100% MAYOR EFICACIA</font></b></label><br>
                                                <label><b><font size="2" color="blue"> = 100%
                                                            EFICACIA</font></b></label><br>
                                                <label><b><font size="2" color="blue"> < 100 % MENOR EFICACIA</font></b></label>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label><b><font size="2">EFICIENCIA FINANCIERA</font></b></label>
                                                <textarea rows="3" class="form-control" style="width:100%;" name="ef2"
                                                          id="ef2" style="text-transform:uppercase;"
                                                          onkeyup="javascript:this.value=this.value.toUpperCase();"><?php echo $objetivo[0]['obje_eficiencia'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label></label><br>
                                                <label><b><font size="2" color="blue"> > 100% MENOS EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> = 100%
                                                            EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> < 100 % MAS EFICIENTE</font></b></label>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label><b><font size="2">EFICIENCIA EN EL PLAZO DE
                                                            EJECUCI&Oacute;N</font></b></label>
                                                <textarea rows="3" class="form-control" style="width:100%;" name="ef3"
                                                          id="ef3" style="text-transform:uppercase;"
                                                          onkeyup="javascript:this.value=this.value.toUpperCase();"><?php echo $objetivo[0]['obje_eficiencia_pe'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label></label><br>
                                                <label><b><font size="2" color="blue"> > 100% MENOS EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> = 100%
                                                            EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> < 100 % MAS EFICIENTE</font></b></label>
                                            </div>
                                        </div>
                                    </div><!-- row -->
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <div class="form-group">
                                                <label><b><font size="2">EFICIENCIA F&Iacute;SICA</font></b></label>
                                                <textarea rows="3" class="form-control" style="width:100%;" name="ef4"
                                                          id="ef4" style="text-transform:uppercase;"
                                                          onkeyup="javascript:this.value=this.value.toUpperCase();"><?php echo $objetivo[0]['obje_eficiencia_fi'] ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label></label><br>
                                                <label><b><font size="2" color="blue"> > 100% MENOS EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> = 100%
                                                            EFICIENTE</font></b></label><br>
                                                <label><b><font size="2" color="blue"> < 100 % MAS EFICIENTE</font></b></label>
                                            </div>
                                        </div>
                                    </div><!-- row -->

                                    <div class="form-actions">
                                        <a href="<?php echo site_url() .'/prog/me/objetivo' ?>"
                                           class="btn btn-lg btn-default"> CANCELAR </a>
                                        <button type="submit" value="GUARDAR" class="btn btn-primary btn-lg">GUARDAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </article>
                </div>
                <!-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
        </section>

    </div>
    <!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->
