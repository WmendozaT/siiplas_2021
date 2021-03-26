  
  function abreVentana(PDF){             
      var direccion;
      direccion = '' + PDF;
      window.open(direccion, "Reporte de Proyectos" , "width=800,height=650,scrollbars=SI") ; 
  }
  
  function confirmar(){
    if(confirm('¿Estas seguro de Eliminar el proyecto?'))
      return true;
    else
      return false;
  }

  function abreVentanaFis(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "Reporte de Proyectos" , "width=1140,height=650,scrollbars=SI") ;                                                                             
  } 
  
  function abreVentanaFin(PDF){             
    var direccion;
    direccion = '' + PDF;
    window.open(direccion, "Reporte de Proyectos" , "width=1280,height=650,scrollbars=SI") ;                                                                
  } 

  $(function () {
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_dt_basic1 = undefined;
        var responsiveHelper_dt_basic2 = undefined;
        var responsiveHelper_dt_basic3 = undefined;
        var responsiveHelper_dt_basic4 = undefined;
        var responsiveHelper_dt_basic5 = undefined;
        var responsiveHelper_dt_basic6 = undefined;
        var responsiveHelper_dt_basic7 = undefined;
        var responsiveHelper_dt_basic8 = undefined;
        var responsiveHelper_dt_basic9 = undefined;
        var responsiveHelper_dt_basic10 = undefined;
        var responsiveHelper_dt_basic11 = undefined;
        var responsiveHelper_dt_basic12 = undefined;
        var responsiveHelper_dt_basic13 = undefined;

        var responsiveHelper_dt_basicc1 = undefined;
        var responsiveHelper_dt_basicc2 = undefined;
        var responsiveHelper_dt_basicc3 = undefined;
        var responsiveHelper_dt_basicc4 = undefined;
        var responsiveHelper_dt_basicc5 = undefined;
        var responsiveHelper_dt_basicc6 = undefined;
        var responsiveHelper_dt_basicc7 = undefined;
        var responsiveHelper_dt_basicc8 = undefined;
        var responsiveHelper_dt_basicc9 = undefined;
        var responsiveHelper_dt_basicc10 = undefined;
        var responsiveHelper_dt_basicc11 = undefined;
        var responsiveHelper_dt_basicc12 = undefined;
        var responsiveHelper_dt_basicc13 = undefined;

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

        $('#dt_basic1').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic1'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc1').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc1'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic2').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic2'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc2').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc2'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic3').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic3'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc3').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc3'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic4').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic4'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc4').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc4'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic5').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic5'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc5').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc5'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic6').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic6'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc6').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc6'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic7').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic7'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc7').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc7'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic8').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic8'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc8').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc8'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic9').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic9'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc9').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc9'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic10').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic10'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc10').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc10'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic11').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic11'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc11').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc11'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });


        $('#dt_basic12').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic12'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basicc12').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basicc12'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic13').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic13'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic14').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic14'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic15').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic15'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic16').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic16'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic17').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic17'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic18').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic18'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic19').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic19'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic20').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic20'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic21').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic21'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic22').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic22'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic23').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic23'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic24').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic24'), breakpointDefinition);
            }
          },
          "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
          },
          "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
          }
        });

        $('#dt_basic25').dataTable({
          "ordering": false,
          "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
          "autoWidth" : true,
          "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
              responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic25'), breakpointDefinition);
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