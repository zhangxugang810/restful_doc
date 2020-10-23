<?php

$ACCEPTIP = '127.0.0.1';
$driver = 'mysql';
$host = 'localhost';
$uid = 'root';
$pwd = 'garbase-it-is';
$database = 'test';
$sep = ' :::: ';

include('./adodb.inc.php');
include_once(ADODB_DIR . '/adodb-csvlib.inc.php');

function err($s) {
    die('**** ' . $s . ' ');
}

function undomq(&$m) {
    if (get_magic_quotes_gpc()) {
        $m = str_replace('\\\\', '\\', $m);
        $m = str_replace('\"', '"', $m);
        $m = str_replace('\\\'', '\'', $m);
    }
    return $m;
}

$remote = $_SERVER["REMOTE_ADDR"];


if (!empty($ACCEPTIP))
    if ($remote != '127.0.0.1' && $remote != $ACCEPTIP)
        err("Unauthorised client: '$remote'");


if (empty($_REQUEST['sql']))
    err('No SQL');


$conn = ADONewConnection($driver);

if (!$conn->Connect($host, $uid, $pwd, $database))
    err($conn->ErrorNo() . $sep . $conn->ErrorMsg());
$sql = undomq($_REQUEST['sql']);

if (isset($_REQUEST['fetch']))
    $ADODB_FETCH_MODE = $_REQUEST['fetch'];

if (isset($_REQUEST['nrows'])) {
    $nrows = $_REQUEST['nrows'];
    $offset = isset($_REQUEST['offset']) ? $_REQUEST['offset'] : -1;
    $rs = $conn->SelectLimit($sql, $nrows, $offset);
} else
    $rs = $conn->Execute($sql);
if ($rs) {
    echo _rs2serialize($rs, $conn, $sql);
    $rs->Close();
} else
    err($conn->ErrorNo() . $sep . $conn->ErrorMsg());
?>