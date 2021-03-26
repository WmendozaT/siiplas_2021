


$(function () {


    /*-------------------- CONTROL SUMA GESTIONES --------------------*/
function suma_presupuesto()
{
    if(document.ins_form_nuevo.gestiones.value==1)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        $('[name="suma_monto_total"]').val((a1).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==2)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        $('[name="suma_monto_total"]').val((a1+a2).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==3)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==4)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==5)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==6)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==7)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==8)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==9)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        a9 = parseFloat($('[id="gestion9"]').val()); //// Novena Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8+a9).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8+a9)).toFixed(2) );
    }

    if(document.ins_form_nuevo.gestiones.value==10)
    {   
        ctotal = parseFloat($('[name="ins_costo_total"]').val()); ///// Costo Total
        a1 = parseFloat($('[id="gestion1"]').val()); //// Primera Gestion
        a2 = parseFloat($('[id="gestion2"]').val()); //// Segunda Gestion
        a3 = parseFloat($('[id="gestion3"]').val()); //// Tercera Gestion
        a4 = parseFloat($('[id="gestion4"]').val()); //// Cuarta Gestion
        a5 = parseFloat($('[id="gestion5"]').val()); //// Quinta Gestion
        a6 = parseFloat($('[id="gestion6"]').val()); //// Sexta Gestion
        a7 = parseFloat($('[id="gestion7"]').val()); //// Septima Gestion
        a8 = parseFloat($('[id="gestion8"]').val()); //// Octava Gestion
        a9 = parseFloat($('[id="gestion9"]').val()); //// Novena Gestion
        a10 = parseFloat($('[id="gestion10"]').val()); //// DEcima Gestion
        $('[name="suma_monto_total"]').val((a1+a2+a3+a4+a5+a6+a7+a8+a9+a10).toFixed(2) );
        $('[name="saldo"]').val((ctotal-(a1+a2+a3+a4+a5+a6+a7+a8+a9+a10)).toFixed(2) );
    }
} 
    $('dl dd').not('dt.activo + dd').hide();
    $('dl dt').click(function () {
        if ($(this).hasClass('activo')) {
            $(this).removeClass('activo');
            $(this).next().slideUp();
        } else {
            $('dl dt').removeClass('activo');
            $(this).addClass('activo');
            $('dl dd').slideUp();
            $(this).next().slideDown();
        }
    });


    var responsiveHelper_dt_basic = undefined;
    var responsiveHelper_dt_basic2 = undefined;
    var responsiveHelper_dt_basic3 = undefined;
    var responsiveHelper_datatable_fixed_column = undefined;
    var responsiveHelper_datatable_col_reorder = undefined;
    var responsiveHelper_datatable_tabletools = undefined;
    
    var breakpointDefinition = {
        tablet : 1024,
        phone : 480
    };

    $('#dt_basic').dataTable({
        "ordering": false,
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

    $('#recursos').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#recursos'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#det_servicios').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#det_servicios'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#pasajes').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#pasajes'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#viaticos').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#viaticos'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#cproducto').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#cproducto'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#clinea').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#clinea'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#suministros').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#suministros'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#activos').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#activos'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });

    $('#otros').dataTable({
        "ordering": false,
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#otros'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        }
    });


});

