<html>
    <body bgcolor=white>
        <?php
        echo PHP_VERSION, '<br>';
        var_dump(parse_url('odbc_mssql://userserver/'));
        die();
        include('../adodb.inc.php');
        include('../tohtml.inc.php');

        function send2server($url, $sql) {
            $url .= '?sql=' . urlencode($sql);
            print "<p>$url</p>";
            $rs = csv2rs($url, $err);
            if ($err)
                print $err;
            return $rs;
        }

        function print_pre($s) {
            print "<pre>";
            print_r($s);
            print "</pre>";
        }

        $serverURL = 'http://localhost/php/phplens/adodb/server.php';
        $testhttp = false;

        $sql1 = "insertz into products (productname) values ('testprod 1')";
        $sql2 = "insert into products (productname) values ('testprod 1')";
        $sql3 = "insert into products (productname) values ('testprod 2')";
        $sql4 = "delete from products where productid>80";
        $sql5 = 'select * from products';

        if ($testhttp) {
            print "<a href=#c>Client Driver Tests</a><p>";
            print "<h3>Test Error</h3>";
            $rs = send2server($serverURL, $sql1);
            print_pre($rs);
            print "<hr />";

            print "<h3>Test Insert</h3>";

            $rs = send2server($serverURL, $sql2);
            print_pre($rs);
            print "<hr />";

            print "<h3>Test Insert2</h3>";

            $rs = send2server($serverURL, $sql3);
            print_pre($rs);
            print "<hr />";

            print "<h3>Test Delete</h3>";

            $rs = send2server($serverURL, $sql4);
            print_pre($rs);
            print "<hr />";


            print "<h3>Test Select</h3>";
            $rs = send2server($serverURL, $sql5);
            if ($rs)
                rs2html($rs);

            print "<hr />";
        }


        print "<a name=c><h1>CLIENT Driver Tests</h1>";
        $conn = ADONewConnection('csv');
        $conn->Connect($serverURL);
        $conn->debug = true;

        print "<h3>Bad SQL</h3>";

        $rs = $conn->Execute($sql1);

        print "<h3>Insert SQL 1</h3>";
        $rs = $conn->Execute($sql2);

        print "<h3>Insert SQL 2</h3>";
        $rs = $conn->Execute($sql3);

        print "<h3>Select SQL</h3>";
        $rs = $conn->Execute($sql5);
        if ($rs)
            rs2html($rs);

        print "<h3>Delete SQL</h3>";
        $rs = $conn->Execute($sql4);

        print "<h3>Select SQL</h3>";
        $rs = $conn->Execute($sql5);
        if ($rs)
            rs2html($rs);
        ?>
