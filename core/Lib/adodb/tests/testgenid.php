<?php
include_once('../adodb.inc.php');
$testaccess = true;
include_once('testdatabases.inc.php');
function testdb(&$db, $createtab = "create table ADOXYZ (id int, firstname char(24), lastname char(24), created date)") {
    $table = 'adodbseq';
    $db->Execute("drop table $table");
    $ctr = 5000;
    $lastnum = 0;
    while (--$ctr >= 0) {
        $num = $db->GenID($table);
        if ($num === false) {
            print "GenID returned false";
            break;
        }
        if ($lastnum + 1 == $num)
            print " $num ";
        else {
            print " <font color=red>$num</font> ";
            flush();
        }
        $lastnum = $num;
    }
}

?>