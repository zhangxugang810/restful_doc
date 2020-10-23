<?php
include('../adodb.inc.php');
include('../tohtml.inc.php');
error_reporting(E_ALL);
$db = ADONewConnection('oci8');
$db->PConnect('', 'scott', 'natsoft');
$db->debug = 99;
define('MYNUM', 5);
$rs = $db->ExecuteCursor("BEGIN adodb.open_tab(:RS,'A%'); END;");

if ($rs && !$rs->EOF) {
    print "Test 1 RowCount: " . $rs->RecordCount() . "<p>";
} else {
    print "<b>Error in using Cursor Variables 1</b><p>";
}
print "<h4>Testing Stored Procedures for oci8</h4>";
$stid = $db->PrepareSP('BEGIN adodb.myproc(' . MYNUM . ', :myov); END;');
$db->OutParameter($stid, $myov, 'myov');
$db->Execute($stid);
if ($myov != MYNUM)
    print "<p><b>Error with myproc</b></p>";
$stmt = $db->PrepareSP("BEGIN adodb.data_out(:a1, :a2); END;", true);
$a1 = 'Malaysia';
$db->InParameter($stmt, $a1, 'a1');
$db->OutParameter($stmt, $a2, 'a2');
$rs = $db->Execute($stmt);
if ($rs) {
    if ($a2 !== 'Cinta Hati Malaysia')
        print "<b>Stored Procedure Error: a2 = $a2</b><p>";
    else
        echo "OK: a2=$a2<p>";
} else {
    print "<b>Error in using Stored Procedure IN/Out Variables</b><p>";
}
$tname = 'A%';
$stmt = $db->PrepareSP('select * from tab where tname like :tablename');
$db->Parameter($stmt, $tname, 'tablename');
$rs = $db->Execute($stmt);
rs2html($rs);
?>