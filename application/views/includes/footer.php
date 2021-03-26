<!------------------------------------------- BASE URL ---------------------------------------->
<input type="hidden" name="base_url" id="base_url" value="<?php echo base_url()?>">
<!------------------------------------------- SITE URL ---------------------------------------->
<input type="hidden" name="site_url" id="site_url" value="<?php echo site_url("")?>">
<!------------------------------------------- GESTION ---------------------------------------->
<input type="hidden" name="gestion" id="gestion" value="<?php echo $this->session->userData('gestion')?>">
<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white">SIPLAS ©<?php echo date('Y')?></span>
        </div>
    </div>
</div>
<!-- END PAGE FOOTER -->

<!--================================================== -->


<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script data-pace-options='{ "restartOnRequestAfter": true }'
        src="<?php echo base_url(); ?>assets/js/plugin/pace/pace.min.js"></script>
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

<!-- BOOTSTRAP JS -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>

<!-- CUSTOM NOTIFICATION -->
<script src="<?php echo base_url(); ?>assets/js/notification/SmartNotification.min.js"></script>

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

<!-- Demo purpose only -->
<script src="<?php echo base_url(); ?>assets/js/demo.min.js"></script>

<!-- MAIN APP JS FILE -->
<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>

<!-- CONTROL DE SESION -->
<script src="<?php echo base_url(); ?>assets/js/session_time/jquery-idletimer.js"></script>

<!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->
<!-- Voice command : plugin -->
<script src="<?php echo base_url(); ?>assets/js/speech/voicecommand.min.js"></script>
<!--alertas -->
<script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
<!-- ------------  mis validaciones js --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/validacion_form.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM DE LA APERTURA PROGRAMATICA   --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/mantenimiento/abm_apertura_programatica.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM DE CARPETA POA   --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/mantenimiento/abm_poa.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM OBJETIVOS ESTRATEGICOS   --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/me/abm_objetivos.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ASIGNAR  OBJETIVOS ESTRATEGICOS  A CARPETA POA  --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/mantenimiento/asignar_obje_poa.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM OBJETIVO DE GESTION  --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/prog_poa/abm_ogestion.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM PRODUCTO TERMINAL  --------------------- -->
<SCRIPT src="<?php echo base_url(); ?>mis_js/programacion/prog_poa/abm_pterminal.js" type="text/javascript"></SCRIPT>
<!-- -----------------------------  ABM INSUMOS DIRECTO --------------------- -->
<?php if(isset($insumos_js)){echo $insumos_js;}?>
<!-- -----------------------------  ABM INSUMOS DIRECTO --------------------- -->
<?php if(isset($insumos_js_delegado)){echo $insumos_js_delegado;}?>
<!-- -----------------------------  ABM PONDERACION --------------------- -->
<?php if(isset($ponderacion_js)){echo $ponderacion_js;}?>
<!-- -----------------------------  registro de ejecucion del programa --------------------- -->
<?php if(isset($reg_ejec_programa_js)){echo $reg_ejec_programa_js;}?>
<!-- -----------------------------  mis js --------------------- -->
<?php if(isset($mis_js)){echo $mis_js;}?>
<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
<!-- HIGH CHART  -->
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-more.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/modules/exporting.js"></script>
<script src="<?php echo base_url(); ?>assets/highcharts/js/highcharts-3d.js"></script>

<!-- END MAIN PANEL -->
<!--funcionario-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/funcionario.js"></script>
<!--escala_salarial-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/escala_salarial.js"></script>
<!--estructura organizacional-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/estructura_org.js"></script>
<!--estructura partidas-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/partidas.js"></script>
<!--estructura organismo financiador-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/organismo_financiador.js"></script>
<!--estructura fuente financiamiento-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/fuente_financiamiento.js"></script>
<!--estructura endidad de transferencia-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/entidad_transferencia.js"></script>
<!--analisis de situacion-->
<script src="<?php echo base_url(); ?>mis_js/programacion/analisis_situacion/abm_analisis_situacion.js"></script>
<!--Activar/descativar Gestiones-->
<script src="<?php echo base_url(); ?>mis_js/mantenimiento/configuracion_gestion.js"></script>
<!--clasificacion sectorial-->
<script src = "<?php echo base_url(); ?>mis_js/mantenimiento/clasificacion_sectorial.js"></script>
<!--auditoria-->
<script src = "<?php echo base_url(); ?>mis_js/mantenimiento/auditoria_proyecto.js"></script>
<!--CONTRORL DE LA INACTIVIDAD DE LA SESION-->
<script src = "<?php echo base_url(); ?>mis_js/control_session.js"></script>
<script type="text/javascript">
    // TABLA
    $(document).ready(function() {
        pageSetUp();
        /* BASIC ;*/
        var responsiveHelper_dt_basic = undefined;

        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        $('#dt_basic').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth" : true,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            }
        });

        /* END BASIC */



    })
</script>
<script type="text/javascript">

    // DO NOT REMOVE : GLOBAL FUNCTIONS!

    $(document).ready(function () {
        pageSetUp();
        var responsiveHelper_datatable_fixed_column = undefined;
        var breakpointDefinition = {
            tablet: 1024,
            phone: 480
        };
        var otable = $('#datatable_fixed_column').DataTable({
            "bInfo": false,
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>" +
            "t" +
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth": true,
            "preDrawCallback": function () {
                if (!responsiveHelper_datatable_fixed_column) {
                    responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
                }
            },
            "rowCallback": function (nRow) {
                responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
            },
            "drawCallback": function (oSettings) {
                responsiveHelper_datatable_fixed_column.respond();
            }

        });
        $("#datatable_fixed_column thead th input[type=text]").on('keyup change', function () {

            otable
                .column($(this).parent().index() + ':visible')
                .search(this.value)
                .draw();

        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var pathname = window.location.pathname;
        var v = pathname.split( '/' );
        var direccion_fisica = '';
        for (var i = 2; i < v.length; i++) {
            if (v.length - 1 == i) {
                direccion_fisica = direccion_fisica + v[i];
            } else {
                direccion_fisica = direccion_fisica + v[i] + '/';
            }
        }
        var url = base_url + "index.php/user/verificar_menu";
        var request;
        if (request) {
            request.abort();
        }
        request = $.ajax({
            url: url,
            type: "POST",
            dataType: 'json',
            data: "url=" + direccion_fisica
        });
        request.done(function (response, textStatus, jqXHR) {
            // console.log(response);
            if (!response.boolean) {
                $(location).attr('href', site_url+'/admin/dashboard');
            }
        });
    });
</script>

</body>
</html>