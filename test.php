<?php
// Connecting, selecting database
$dbconn = pg_connect("host=localhost dbname=cns user=cns password=cns51stemas")
    or die('Could not connect: ' . pg_last_error());

// Performing SQL query
$query = 'SELECT * FROM posts';
$result = pg_query($query) or die('Query failed: ' . pg_last_error());

// Printing results in HTML
while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
   foreach ($line as $col_value) {
        echo $col_value . "\n";
    }
}

// Free resultset
pg_free_result($result);

// Closing connection
pg_close($dbconn);
?>
