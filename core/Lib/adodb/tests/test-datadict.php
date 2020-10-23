<?php

error_reporting(E_ALL);
include_once('../adodb.inc.php');

foreach (array('sapdb', 'sybase', 'mysql', 'access', 'oci8po', 'odbc_mssql', 'odbc', 'db2', 'firebird', 'postgres', 'informix') as $dbType) {
    echo "<h3>$dbType</h3><p>";
    $db = NewADOConnection($dbType);
    $dict = NewDataDictionary($db);

    if (!$dict)
        continue;
    $dict->debug = 1;

    $opts = array('REPLACE', 'mysql' => 'ENGINE=INNODB', 'oci8' => 'TABLESPACE USERS');
    $flds = "
ID            I           AUTO KEY,
FIRSTNAME     VARCHAR(30) DEFAULT 'Joan' INDEX idx_name,
LASTNAME      VARCHAR(28) DEFAULT 'Chen' key INDEX idx_name INDEX idx_lastname,
averylonglongfieldname X(1024) DEFAULT 'test',
price         N(7.2)  DEFAULT '0.00',
MYDATE        D      DEFDATE INDEX idx_date,
BIGFELLOW     X      NOTNULL,
TS_SECS            T      DEFTIMESTAMP,
TS_SUBSEC   TS DEFTIMESTAMP
";


    $sqla = $dict->CreateDatabase('KUTU', array('postgres' => "LOCATION='/u01/postdata'"));
    $dict->SetSchema('KUTU');

    $sqli = ($dict->CreateTableSQL('testtable', $flds, $opts));
    $sqla = array_merge($sqla, $sqli);

    $sqli = $dict->CreateIndexSQL('idx', 'testtable', 'price,firstname,lastname', array('BITMAP', 'FULLTEXT', 'CLUSTERED', 'HASH'));
    $sqla = array_merge($sqla, $sqli);
    $sqli = $dict->CreateIndexSQL('idx2', 'testtable', 'price,lastname');
    $sqla = array_merge($sqla, $sqli);

    $addflds = array(array('height', 'F'), array('weight', 'F'));
    $sqli = $dict->AddColumnSQL('testtable', $addflds);
    $sqla = array_merge($sqla, $sqli);
    $addflds = array(array('height', 'F', 'NOTNULL'), array('weight', 'F', 'NOTNULL'));
    $sqli = $dict->AlterColumnSQL('testtable', $addflds);
    $sqla = array_merge($sqla, $sqli);


    printsqla($dbType, $sqla);

    if (file_exists('d:\inetpub\wwwroot\php\phplens\adodb\adodb.inc.php'))
        if ($dbType == 'mysqlt') {
            $db->Connect('localhost', "root", "", "test");
            $dict->SetSchema('');
            $sqla2 = $dict->ChangeTableSQL('adoxyz', $flds);
            if ($sqla2)
                printsqla($dbType, $sqla2);
        }
    if ($dbType == 'postgres') {
        if (@$db->Connect('localhost', "tester", "test", "test"))
            ;
        $dict->SetSchema('');
        $sqla2 = $dict->ChangeTableSQL('adoxyz', $flds);
        if ($sqla2)
            printsqla($dbType, $sqla2);
    }

    if ($dbType == 'odbc_mssql') {
        $dsn = $dsn = "PROVIDER=MSDASQL;Driver={SQL Server};Server=localhost;Database=northwind;";
        if (@$db->Connect($dsn, "sa", "natsoft", "test"))
            ;
        $dict->SetSchema('');
        $sqla2 = $dict->ChangeTableSQL('adoxyz', $flds);
        if ($sqla2)
            printsqla($dbType, $sqla2);
    }



    adodb_pr($dict->databaseType);
    printsqla($dbType, $dict->DropColumnSQL('table', array('my col', '`col2_with_Quotes`', 'A_col3', 'col3(10)')));
    printsqla($dbType, $dict->ChangeTableSQL('adoxyz', 'LASTNAME varchar(32)'));
}

function printsqla($dbType, $sqla) {
    print "<pre>";
    foreach ($sqla as $s) {
        $s = htmlspecialchars($s);
        print "$s;\n";
        if ($dbType == 'oci8')
            print "/\n";
    }
    print "</pre><hr />";
}

echo "<h1>Test XML Schema</h1>";
$ff = file('xmlschema.xml');
echo "<pre>";
foreach ($ff as $xml)
    echo htmlspecialchars($xml);
echo "</pre>";
include_once('test-xmlschema.php');
?>