<script xmlns="http://www.w3.org/1999/html">
    function abreVentana(PDF) {
        var direccion;
        direccion = '' + PDF;
        window.open(direccion, "Reporte de Proyectos", "width=800,height=650,scrollbars=SI");
    }
</script>
<!--fin de stiloh-->
<style>
    h4 {
        font-size: 10px;
        padding: 0px;
        font-weight: bold;
    }
</style>

</head>
<body class="">
<?php $site_url = site_url("");?>
<script type="text/javascript">
    var a = 0;
</script>
<!-- MAIN PANEL -->
<div id="main" role="main">
    <!-- RIBBON -->
    <div id="ribbon">
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Marco Estrategico</li>
            <li>Resultados de Mediano Plazo</li>
        </ol>
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="">
        <div class="row">
            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 animated fadeInDown">
                <h1 class="page-title txt-color-blueDark"><i class="fa fa-pencil-square-o fa-fw "></i>
                    RESULTADO DE MEDIANO PLAZO <?php
                    $gestion_inicio = $this->session->userdata("gestion");
                    $gestion_final = ($gestion_inicio + 4);
                    //echo $gestion_inicio . " - " . $gestion_final;
                    echo '2016 - 2020' ?>
                </h1>
            </div>
        </div>
        <style>
            table {
                font-size: 10px;
                width: 100%;
            }
        </style>
        <section id="widget-grid" class="">
            <div class="row">
                <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><br>
                    <a href='<?php echo base_url() . 'index.php/prog/me/n_obj'; ?>' class="btn btn-labeled btn-success"
                       title="NUEVO OBJETIVO ESTRATEGICO"> <span class="btn-label"><i
                                class="glyphicon glyphicon-file"></i></span><font size="1">NUEVO RESULTADO</font></a>

                    <a href="javascript:abreVentana('<?php echo site_url("admin") . '/me/ficha_tecnica'; ?>');"
                       class="btn btn-labeled btn-success" title="REPORTE"> <span class="btn-label"><i
                                class="fa fa-file-pdf-o"></i></span><font size="1">REPORTE</font></a><br><br>

                    <div class="jarviswidget jarviswidget-color-teal">
                        <header>
                            <span class="widget-icon"> <i class="fa fa-arrows-v"></i> </span>

                            <h2 class="font-md">
                                <strong>GESTIÓN: <?php //echo $gestion_inicio . ' - ' . $gestion_final;
                                    echo '2016 - 2020' ?> </strong></h2>
                        </header>

                        <div>
                            <div class="widget-body no-padding">
                                <div class="table-responsive">
                                    <table id="dt_basic" class="table table-striped table-bordered table-hover"
                                           style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th style="width:1%;"><font size="1">COD</font></th>
                                            <th style="width:1%;" title="MODIFICAR - ELIMINAR"><font size="1"> M/E </font></th>
                                            <th style="width:1%;" title="PLAN ESTRATÉGICO DE DESARROLLO"><font size="1">VINCULACI&Oacute;N AL PDES</font>
                                            </th>
                                            <th style="width:1%;" title="PLAN TERRITORIAL DE DESARROLLO INTEGRAL"><font size="1">VINCULACI&Oacute;N AL PTDI</font></th>
                                            <th style="width:20%;"><font size="1">RESULTADO DE MEDIANO PLAZO</font></th>
                                            <th style="width:5%;" title="TIPO DE INDICADOR"><font size="1">TIPO DE INDICADOR</font></th>
                                            <th style="width:5%;"><font size="1">INDICADOR</font></th>
                                            <th style="width:5%;" title="LÍNEA BASE"><font size="1">LINEA/BASE</font></th>
                                            <th style="width:5%;"><font size="1">META</font></th>
                                            <th style="width:5%;" title="PONDERACIÓN"><font size="1">%PONDERACI&Oacute;N</font></th>
                                            <th style="width:5%;"><font size="1">RESPONSABLE</font></th>
                                            <th style="width:5%;"><font size="1">UNIDAD</font></th>
                                            <th style="width:5%;"><font size="1">MEDIO DE VERIFICACI&Oacute;N</font>
                                            </th>
                                            <th style="width:30%;"><font size="1">CRONOGRAMA DE EJECUCI&Oacute;N</font></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $cont_pdes = 0;
                                        $cont_ptdi = 0;
                                        foreach ($lista_objetivos as $fila) {
                                            echo '<tr id="tr'.$fila['obje_id'].'">';
                                            echo '<td>' . $fila['obje_codigo'] . '</td>';
                                            ?>
                                            <!-- ------------------------ BOTONES ----------------------------- -->
                                            <td>
                                                <a href="<?php echo $site_url.'/prog/me/mod_obje/'.$fila['obje_id']?>" title="MODIFICAR"
                                                   class=" modificar" name="<?php echo $fila['obje_id'] ?>" id="modificar">
                                                    <img src="<?php echo base_url(); ?>assets/ifinal/modificar.png"
                                                         WIDTH="40" HEIGHT="40"/>
                                                </a>
                                                <a href="" title="ELIMINAR" class="del_obje"
                                                   name="<?php echo $fila['obje_id'] ?>" id="eliminar">
                                                    <img src="<?php echo base_url(); ?>assets/ifinal/eliminar.png"
                                                         WIDTH="40" HEIGHT="40"/>
                                                </a>
                                                <a href="<?php echo site_url("").'/prog/me/ind/' . $fila['obje_id'];?>"
                                                   title="INDICADOR DE DESEMPEÑO">
                                                    <img src="<?php echo base_url(); ?>assets/ifinal/form1.jpg"
                                                         WIDTH="40" HEIGHT="40"/>
                                                </a>
                                                <a href="" title="DOCUMENTO" class="obje_pdf"
                                                   name="<?php echo $fila['obje_id'] ?>" id="obje_pdf"
                                                   data-toggle="modal" data-target="#obje_modal_cargar_pdf">
                                                    <img src="<?php echo base_url(); ?>assets/ifinal/doc.png"
                                                         WIDTH="40" HEIGHT="40"/>
                                                </a>
                                            </td>
                                            <!-- ------------------------ GENERAR PDES ----------------------------- -->
                                            <td>
                                                <div class="buttonclick">
                                                    <div class="btnapp">
                                                        <div class="hover-btn">
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#pdes<?php echo $cont_pdes; ?>"
                                                               class="btn btn-lg btn-default">
                                                                <font
                                                                    size="1"><b><?php echo $fila['pdes_pcod'] . ' <br> ' . $fila['pdes_mcod'] .
                                                                            ' <br> ' . $fila['pdes_rcod'] . ' <br> ' . $fila['pdes_acod'] ?></b></font>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div><!-- buttonclick -->
                                                <div class="modal fade bs-example-modal-lg"
                                                     id="pdes<?php echo $cont_pdes; ?>"
                                                     tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="texto"><font size="4">
                                                                    <div class="row text-center">
                                                                        <LABEL><b>PLAN ESTRATÉGICO DE
                                                                                DESARROLLO</b></LABEL>
                                                                    </div>
                                                                    <P>
                                                                        <u><b>PILAR</b></u>
                                                                        :<?php echo $fila['pdes_pcod'] . ' - ' . $fila['pdes_pilar'] ?>
                                                                        <br>
                                                                        <u><b>META</b></u>
                                                                        : <?php echo $fila['pdes_mcod'] . ' - ' . $fila['pdes_meta'] ?>
                                                                        <br>
                                                                        <u><b>RESULTADO</b></u>
                                                                        : <?php echo $fila['pdes_rcod'] . ' - ' . $fila['pdes_resultado'] ?>
                                                                        <br>
                                                                        <u><b>ACCION</b></u>
                                                                        : <?php echo $fila['pdes_acod'] . ' - ' . $fila['pdes_accion'] ?>
                                                                    </P>
                                                            </div>
                                                            </font>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <!-- ------------------------ GENERAR PTDI ----------------------------- -->
                                            <td>
                                                <div class="buttonclick">
                                                    <div class="btnapp">
                                                        <div class="hover-btn">
                                                            <a href="#" data-toggle="modal"
                                                               data-target="#ptdi<?php echo $cont_ptdi; ?>"
                                                               class="btn btn-lg btn-default">
                                                                <font
                                                                    size="1"><b><?php echo $fila['ptdi_ecod'] . ' <br> ' . $fila['ptdi_ocod'] .
                                                                            ' <br> ' . $fila['ptdi_pcod']?></b></font>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div><!-- buttonclick -->
                                                <div class="modal fade bs-example-modal-lg"
                                                     id="ptdi<?php echo $cont_ptdi; ?>"
                                                     tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="texto"><font size="4">
                                                                    <div class="row text-center">
                                                                        <LABEL><b>PLAN TERRITORIAL DE DESARROLLO
                                                                                INTEGRAL
                                                                            </b></LABEL>
                                                                    </div>
                                                                    <P>
                                                                        <u><b>EJE PROGRAMÁTICA</b></u>
                                                                        :<?php echo $fila['ptdi_ecod'] . ' - ' . $fila['ptdi_eje'] ?>
                                                                        <br>
                                                                        <u><b>POLÍTICA</b></u>
                                                                        : <?php echo $fila['ptdi_ocod'] . ' - ' . $fila['ptdi_politica'] ?>
                                                                        <br>
                                                                        <u><b>PROGRAMA</b></u>
                                                                        : <?php echo $fila['ptdi_pcod'] . ' - ' . $fila['ptdi_programa'] ?>
                                                                    </P>
                                                            </div>
                                                            </font>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php
                                            echo '<td>' . $fila['obje_objetivo'] . '</td>';
                                            echo '<td>' . $fila['indicador'] . '</td>';
                                            echo '<td>' . $fila['obje_indicador'] . '</td>';
                                            echo '<td>' . $fila['obje_linea_base'] . '</td>';
                                            echo '<td>' . $fila['obje_meta'] . '</td>';
                                            echo '<td>' . $fila['obje_ponderacion'] . '</td>';
                                            echo '<td>' . $fila['fun_nombre'] . ' ' . $fila['fun_paterno'] . ' ' . $fila['fun_materno'] . '</td>';
                                            echo '<td>' . $fila['get_unidad'] . '</td>';
                                            echo '<td>' . $fila['obje_fuente_verificacion'] . '</td>';
                                            echo '<td>';
                                            $indi[1] = '';
                                            $indi[2] = '%';
                                            $porc = $indi[$fila['indi_id']];
                                            //$gestion_inicial = $fila['obje_gestion_curso'];
                                            $gestion_inicial = '2016';
                                            echo '<table style="width:100%;" class="table table-bordered">
                                                          <thead>
                                                              <tr>
                                                                  <td colspan="6" bgcolor="#2F4F4F"><center><b><font color="#ffffff" size="1">
                                                                  PROGRAMACI&Oacute;N 2016 - 2020' . '</font></b></center></td>
                                                              </tr>';
                                            //---------------------- CABECERA DE GESTIONES
                                            echo '<tr>';
                                            echo '<td style="width:1%;" bgcolor="#2F4F4F">
                                            <center>
                                            <button type="button" class="btn btn-primary grafico_objetivo" name="' . $fila["obje_id"] . '" id="grafico"
                                            data-toggle="modal" data-target="#modal_grafico"
                                            title="PROGRAMACION">
                                            <span class="glyphicon glyphicon-stats" aria-hidden="true">
                                            </center>
                                            </td>';
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo '<td style="width:1%;" bgcolor="#2F4F4F"><center><b><font color="#ffffff" size="1">' . ($gestion_inicial++) . '</font></b></center></td>';
                                            }
                                            echo '</tr>';
                                            //---------------------- FIN DE CABECERA
                                            $obje_id = $fila['obje_id'];
                                            //---------------------- PROGRAMACION
                                            echo '<tr>';
                                            echo '<td style="width:5%;" bgcolor="#F5F5DC"><center><b><font color="#000000" size="1">P</font></b></center></td>';
                                            for ($i = 1; $i <= 5; $i++) {
                                                $puntero = 'prog' . $i;
                                                $prog_gestion = $temporalizacion[$obje_id][$puntero];
                                                echo ' <td style="width:1%;" bgcolor="#F5F5DC"><center><font color="#000000" size="1">' . round($prog_gestion, 1) . $porc . '</font></center></td>';
                                            }
                                            echo '</tr>';
                                            //--------------------- PROGRAMACION ACUMULADA
                                            echo '<tr>';
                                            echo '<td style="width:1%;" bgcolor="#98FB98"><center><font color="#000000" size="1">P.A</font></b></center></td>';
                                            for ($i = 1; $i <= 5; $i++) {
                                                $puntero_acumulado = 'p_acumulado' . $i;
                                                $prog_acumulado = $temporalizacion[$obje_id][$puntero_acumulado];
                                                echo '<td style="width:1%;" bgcolor="#98FB98"><center><font color="#000000" size="1">' . round($prog_acumulado, 1) . $porc . '</font></center></td>';
                                            }
                                            echo '</tr>';
                                            //--------------------- PROGRAMACION ACUMULADA PORCENTUAL
                                            echo '<tr>';
                                            echo '<td style="width:1%;" bgcolor="#B0E0E6"><center><font color="#000000" size="1">%P.A</font></b></center></td>';
                                            for ($i = 1; $i <= 5; $i++) {
                                                $puntero_pa_porcentual = 'pa_porc' . $i;
                                                $pa_porcentual = $temporalizacion[$obje_id][$puntero_pa_porcentual];
                                                echo '<td style="width:1%;" bgcolor="#B0E0E6"><center><font color="#000000" size="1">' . round($pa_porcentual, 1) . $porc . '%</font></center></td>';
                                            }
                                            echo '</tr>';
                                            echo '              </thead>
                                                        </table>';
                                            echo '</td>';
                                            echo '</tr>';
                                            $cont_pdes++;
                                            $cont_ptdi++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!-- end widget content -->

                        </div>
                        <!-- end widget div -->

                    </div>
                    <br>
                    <!-- end widget -->

                </article>
                <!-- WIDGET END -->
            </div>
        </section>

    </div>
</div>

<!-- ------------------------------- MODAL DE GRAFICO OBJETIVO ESTRATEGIO-------------- -->
<div class="modal fade bs-example-modal-lg" id="modal_grafico" tabindex="-1" role="dialog"
     aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="col-md-12 des">
                <center>TEMPORALIZACI&Oacute;N</center>
            </div>
            <table class="table table-bordered" style="width:100%;">
                <tbody id="tabla_grafico">
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-6 des">
                    L&Iacute;NEA BASE:<span class="badge" id="linea_base"></span>
                </div>
                <div class="col-md-6 des">
                    META: <span class="badge" id="meta"></span>
                </div>
            </div>

            <div class="row">
                <div id="" class="col-md-12">
                    <div id="grafico_objetivo" class="graf">
                    </div>
                </div>

            </div>


        </div>
    </div>
</div>
<!-- END MAIN PANEL -->
<!-- =============================       MODAL SUBIR PDF                 ========================== -->
<div class="modal animated fadeInDown" id="obje_modal_cargar_pdf"  tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close text-danger " data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title text-center text-info">
                    <b><i class="glyphicon glyphicon-circle-arrow-up"></i> SUBIR ARCHIVO </b>
                </h4>
            </div>
            <form action="<?php echo $site_url.'/prog/me/add_pdf' ?>" enctype="multipart/form-data"
                  id="objeform_subir_pdf" name="objeform_subir_pdf"  novalidate="novalidate" method="post">
                <input type="hidden" name="id_obje_pdf" id="id_obje_pdf">
                <input type="hidden" name="mod_eli" id="mod_eli">
                <div class="modal-body no-padding">
                    <div class="row">
                        <div id="bootstrap-wizard-1" class="col-sm-12">
                            <div class="well">
                                <div class="row">
                                    <label><b style="font-size: 15px">C&oacute;digo Objetivo Estratégico</b></label>
                                    <div class="col-sm-10">
                                        <input type="text" name="codigo_mpdf" id="codigo_mpdf" disabled="disabled" class="form-control">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <LABEL><b style="font-size: 15px">Subir archivo PDF menor a 5 mb</b></label>
                                    <div class="col-sm-11">
                                        <div class="form-group">
                                            <LABEL><b STYLE="font-size: 12px">Seleccionar Archivo</b></label>
                                            <input class="form-control" type="file" name="userfile"
                                                   id="userfile" placeholder="Seleccione el Archivo" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <label for=""> </label>
                                        <img id="load" style="display: none"
                                             src="<?php echo base_url() ?>/assets/img/loading.gif" width="30"
                                             height="30">
                                    </div>
                                </div>
                            </div> <!-- end well -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row text-align-center" style="align-content: center">
                        <div class="col-md-3">
                            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                                 <span>
                                     <i class="fa fa-times" aria-hidden="true"></i>
                                 </span>
                                <font size="2">CANCELAR </font>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a class="btn btn-success btn-sm"
                               target="_blank" id="objever_pdf" name="objever_pdf">
                                 <span>
                                     <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                 </span>
                                <font size="2">VER PDF </font>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" name="objeguardar_pdf" id="objeguardar_pdf" class="btn btn-primary">
                                <!--onclick="this.disabled=true;"-->
                                 <span>
                                     <i class="fa fa-save" aria-hidden="true"></i>
                                 </span>
                                <font size="2">GUARDAR </font>
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" name="objereemplazar_pdf" id="objereemplazar_pdf" class="btn btn-warning">
                                 <span>
                                     <i class="fa fa-pencil-square-o " aria-hidden="true"></i>
                                 </span>
                                <font size="2">REEMPLAZAR </font>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script data-pace-options='{ "restartOnRequestAfter": true }'
        src="<?php echo base_url(); ?>assets/js/plugin/pace/pace.min.js"></script>


