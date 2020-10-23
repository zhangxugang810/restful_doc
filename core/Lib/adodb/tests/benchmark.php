<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
    <head>
        <title>ADODB Benchmarks</title>
    </head> 
    <body>
        <?php
        $testmssql = true;
//$testvfp = true;
        $testoracle = true;
        $testado = true;
        $testibase = true;
        $testaccess = true;
        $testmysql = true;
        $testsqlite = true;
        set_time_limit(240);
        include("../tohtml.inc.php");
        include("../adodb.inc.php");

        function testdb(&$db, $createtab = "create table ADOXYZ (id int, firstname char(24), lastname char(24), created date)") {
            GLOBAL $ADODB_version, $ADODB_FETCH_MODE;

            adodb_backtrace();

            $max = 100;
            $sql = 'select * from ADOXYZ';
            $ADODB_FETCH_MODE = ADODB_FETCH_NUM;
            $rs = $db->Execute($sql);
            if (!$rs) {
                print "Error in recordset<p>";
                return;
            }
            $arr = $rs->GetArray();
            global $ADODB_COUNTRECS;
            $ADODB_COUNTRECS = false;
            $start = microtime();
            for ($i = 0; $i < $max; $i++) {
                $rs = $db->Execute($sql);
                $arr = $rs->GetArray();
            }
            $end = microtime();
            $start = explode(' ', $start);
            $end = explode(' ', $end);
            ;
            $total = $end[0] + trim($end[1]) - $start[0] - trim($start[1]);
            printf("<p>seconds = %8.2f for %d iterations each with %d records</p>", $total, $max, sizeof($arr));
            flush();
        }

        include("testdatabases.inc.php");
        ?>


    </body>
</html>
