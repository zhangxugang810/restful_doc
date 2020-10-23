<?php

error_reporting(E_ALL);
include_once('../adodb-pear.inc.php');
$username = 'root';
$password = '';
$hostname = 'localhost';
$databasename = 'xphplens';
$driver = 'mysql';
$dsn = "$driver://$username:$password@$hostname/$databasename";
$db = DB::Connect($dsn);
$db->setFetchMode(ADODB_FETCH_ASSOC);
$rs = $db->Query('select firstname,lastname from adoxyz');
$cnt = 0;
while ($arr = $rs->FetchRow()) {
    print_r($arr);
    print "<br>";
    $cnt += 1;
}
if ($cnt != 50)
    print "<b>Error in \$cnt = $cnt</b>";
?>