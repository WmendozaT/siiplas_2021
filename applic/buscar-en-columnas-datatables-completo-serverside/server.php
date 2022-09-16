<?php
 
// Tabla de base de datos a usar
$table = 'tbl_contacto';
 
// Llave primaria a usar
$primaryKey = 'id';
 

$columns = array(
    array( 'db' => 'nombres', 'dt' => 0 ),
    array( 'db' => 'apellidos',  'dt' => 1 ),
    array( 'db' => 'direccion',   'dt' => 2 ),
    array( 'db' => 'telefono', 'dt' => 3,),
    array( 'db' => 'fecha_de_naci','dt' => 4,
        'formatter' => function( $d, $row ) {
            return date( 'd-m-Y', strtotime($d));
        }
    )
   
);
 
// Datos de conexión MySQL
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'b_search',
    'host' => 'localhost'
);
 
 

 
require( 'vendor/DataTables/server-side/scripts/ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);