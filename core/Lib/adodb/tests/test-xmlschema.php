<?PHP
error_reporting(E_ALL);
include_once( "../adodb.inc.php" );
include_once( "../adodb-xmlschema03.inc.php" );
$db = ADONewConnection('mysql');
$db->Connect('localhost', 'root', '', 'test') || die('fail connect1');
$schema = new adoSchema($db);
print "<b>SQL to build xmlschema.xml</b>:\n<pre>";
$sql = $schema->ParseSchema("xmlschema.xml");
var_dump($sql);
print "</pre>\n";
print "<b>SQL to build xmlschema-mssql.xml</b>:\n<pre>";
$db2 = ADONewConnection('mssql');
$db2->Connect('', 'adodb', 'natsoft', 'northwind') || die("Fail 2");
$db2->Execute("drop table simple_table");
$schema = new adoSchema($db2);
$sql = $schema->ParseSchema("xmlschema-mssql.xml");
print_r($sql);
print "</pre>\n";
$db2->debug = 1;
foreach ($sql as $s)
    $db2->Execute($s);
?>