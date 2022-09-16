<!DOCTYPE html>
<head>
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>

    <link rel="stylesheet"  href="vendor/DataTables/jquery.datatables.min.css">	
    <script src="vendor/DataTables/jquery.dataTables.min.js" type="text/javascript"></script> 

    <link href="style.css" rel="stylesheet" type="text/css" />

    <title>Buscar en columnas DataTables (Completo)</title>
    <script>
        $(document).ready(function ()
        {
            $('#tbl-contact thead th').each(function () {
                var title = $(this).text();
                $(this).html(title+' <input type="text" class="col-search-input" placeholder="Buscar ' + title + '" />');
            });
            
            var table = $('#tbl-contact').DataTable({
                	"scrollX": true,
            		"pagingType": "numbers",
                "processing": true,
                "serverSide": true,
                "ajax": "server.php",
				"language": {
           		 "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
       			 },
                order: [[2, 'asc']],
                columnDefs: [{
                    targets: "_all",
                    orderable: false
                 }]
            });

            table.columns().every(function () {
                var table = this;
                $('input', this.header()).on('keyup change', function () {
                    if (table.search() !== this.value) {
                    	   table.search(this.value).draw();
                    }
                });
            });
        });

    </script>
</head>

<body>
    <div class="datatable-container">
        <h2>Buscar en columnas DataTables ServerSide(Completo)</h2>
        <hr>
        <table name="tbl-contact" id="tbl-contact" class="display" cellspacing="0" width="100%">   

            <thead>
                <tr>
                    
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Fecha Nacimiento</th>

                </tr>
            </thead>
            
        </table>
    </div>
</body>
</html>