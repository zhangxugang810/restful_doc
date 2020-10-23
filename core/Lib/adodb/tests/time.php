<?php
include_once('../adodb-time.inc.php');
adodb_date_test();
$datestring = "2063-12-24";
$stringArray = explode("-", $datestring);
$date = adodb_mktime(0, 0, 0, $stringArray[1], $stringArray[2], $stringArray[0]);
$convertedDate = adodb_date("d-M-Y", $date);
echo( "Original: $datestring<br>" );
echo( "Converted: $convertedDate" );
?>