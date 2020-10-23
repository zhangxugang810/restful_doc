<?php

error_reporting(E_ALL);
include_once('../adodb.inc.php');
include_once('../adodb-pager.inc.php');
$driver = 'oci8';
$sql = 'select  ID, firstname as "First Name", lastname as "Last Name" from adoxyz  order  by  id';
if ($driver == 'postgres') {
    $db = NewADOConnection('postgres');
    $db->PConnect('localhost', 'tester', 'test', 'test');
}
if ($driver == 'access') {
    $db = NewADOConnection('access');
    $db->PConnect("nwind", "", "", "");
}
if ($driver == 'ibase') {
    $db = NewADOConnection('ibase');
    $db->PConnect("localhost:e:\\firebird\\examples\\employee.gdb", "sysdba", "masterkey", "");
    $sql = 'select distinct firstname, lastname  from adoxyz  order  by  firstname';
}
if ($driver == 'mssql') {
    $db = NewADOConnection('mssql');
    $db->Connect('JAGUAR\vsdotnet', 'adodb', 'natsoft', 'northwind');
}
if ($driver == 'oci8') {
    $db = NewADOConnection('oci8');
    $db->Connect('', 'scott', 'natsoft');

    $sql = "select * from (select  ID, firstname as \"First Name\", lastname as \"Last Name\" from adoxyz 
	 order  by  1)";
}
if ($driver == 'access') {
    $db = NewADOConnection('access');
    $db->Connect('nwind');
}
if (empty($driver) or $driver == 'mysql') {
    $db = NewADOConnection('mysql');
    $db->Connect('localhost', 'root', '', 'test');
}
$db->debug = true;

if (0) {
    $rs = $db->Execute($sql);
    include_once('../toexport.inc.php');
    print "<pre>";
    print rs2csv($rs);
    print '<hr />';
    $rs->MoveFirst();
    print rs2tab($rs);
    print '<hr />';
    $rs->MoveFirst();
    rs2tabout($rs);
    print "</pre>";
}
$pager = new ADODB_Pager($db, $sql);
$pager->showPageLinks = true;
$pager->linksPerPage = 10;
$pager->cache = 60;
$pager->Render($rows = 7);
?>