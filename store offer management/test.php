<?php


//$dbhost  = "ctuzu3.coosbay.cardinal-services.com";
$dbhost  = "192.168.11.25";
$dbport  = 10060;
$dbname  = "Tempworks";
$dbuser  = "webapp";
$dbpass  = "gh7Zp5s";
try {
    $dbh = new PDO ("dlib:host=$dbhost:$dbport;dbname=$dbname","$dbuser","$dbpass");
    // $dbh = new PDO("sqlsrv:Server=$dbhost;Database=$dbname", '$dbuser', '$dbpass');
} catch (PDOException $e) {
    echo  $e->getMessage() . "\n";
    exit;
}


    // $link = mssql_connect($dbhost . ':' . $dbport, $dbuser, $dbpass) or exit('Could not connect to server (' . $dbhost . ')');

    // mssql_select_db($dbname, $link) or exit('Could not select the database');

    // mssql_query('SET ANSI_WARNINGS ON');
    // mssql_query('SET ANSI_NULLS ON');

//How To Enable PHP MSSQL Extension on cpanel
?>