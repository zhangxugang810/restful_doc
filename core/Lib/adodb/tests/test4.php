<?php
error_reporting(E_ALL);
function testsql() {
    include('../adodb.inc.php');
    include('../tohtml.inc.php');
    global $ADODB_FORCE_TYPE;
    $sql = "
SELECT * 
FROM ADOXYZ WHERE id = -1";
    $conn = ADONewConnection("mysql");
    $conn->PConnect("localhost", "root", "", "test");
    if (PHP_VERSION >= 5) {
        $connstr = "mysql:dbname=northwind";
        $u = 'root';
        $p = '';
        $conn = ADONewConnection('pdo');
        $conn->Connect($connstr, $u, $p);
    }
    $conn->debug = 1;
    $conn->Execute("delete from adoxyz where lastname like 'Smi%'");
    $rs = $conn->Execute($sql);
    $record = array();
    if (strpos($conn->databaseType, 'mysql') === false)
        $record['id'] = 751;
    $record["firstname"] = 'Jann';
    $record["lastname"] = "Smitts";
    $record["created"] = time();
    $insertSQL = $conn->GetInsertSQL($rs, $record);
    $conn->Execute($insertSQL);
    if (strpos($conn->databaseType, 'mysql') === false)
        $record['id'] = 752;
    $record["firstname"] = 'anull';
    $record["lastname"] = "Smith\$@//";
    $record["created"] = time();
    if (isset($_GET['f']))
        $ADODB_FORCE_TYPE = $_GET['f'];
    $insertSQL = $conn->GetInsertSQL($rs, $record);
    $conn->Execute($insertSQL);
    $insertSQL2 = $conn->GetInsertSQL($table = 'ADOXYZ', $record);
    if ($insertSQL != $insertSQL2)
        echo "<p><b>Walt's new stuff failed</b>: $insertSQL2</p>";
    $sql = "
SELECT * 
FROM ADOXYZ WHERE lastname=" . $conn->Param('var') . " ORDER BY 1";
    $varr = array('var' => $record['lastname'] . '');
    $rs = $conn->Execute($sql, $varr);
    if (!$rs || $rs->EOF)
        print "<p><b>No record found!</b></p>";
    $record = array();
    $record["firstName"] = "Caroline" . rand();
    $record["creAted"] = '2002-12-' . (rand() % 30 + 1);
    $record['num'] = '';
    $updateSQL = $conn->GetUpdateSQL($rs, $record);
    $conn->Execute($updateSQL, $varr);
    if ($conn->Affected_Rows() != 1)
        print "<p><b>Error1 </b>: Rows Affected=" . $conn->Affected_Rows() . ", should be 1</p>";
    $record["firstName"] = "Caroline" . rand();
    $record["lasTname"] = "Smithy Jones";
    $record["creAted"] = '2002-12-' . (rand() % 30 + 1);
    $record['num'] = 331;
    $updateSQL = $conn->GetUpdateSQL($rs, $record);
    $conn->Execute($updateSQL, $varr);
    if ($conn->Affected_Rows() != 1)
        print "<p><b>Error 2</b>: Rows Affected=" . $conn->Affected_Rows() . ", should be 1</p>";
    $rs = $conn->Execute("select * from ADOXYZ where lastname like 'Sm%'");
//adodb_pr($rs);
    rs2html($rs);
    $record["firstName"] = "Carol-new-" . rand();
    $record["lasTname"] = "Smithy";
    $record["creAted"] = '2002-12-' . (rand() % 30 + 1);
    $record['num'] = 331;
    $conn->AutoExecute('ADOXYZ', $record, 'UPDATE', "lastname like 'Sm%'");
    $rs = $conn->Execute("select * from ADOXYZ where lastname like 'Sm%'");
    rs2html($rs);
}
testsql();
?>