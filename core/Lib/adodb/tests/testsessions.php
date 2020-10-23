<?php
function NotifyExpire($ref, $key) {
    print "<p><b>Notify Expiring=$ref, sessionkey=$key</b></p>";
}
//-------------------------------------------------------------------
error_reporting(E_ALL);
ob_start();
include('../session/adodb-cryptsession2.php');
$options['debug'] = 1;
$db = 'postgres';
switch ($db) {
    case 'oci8':
        $options['table'] = 'adodb_sessions2';
        ADOdb_Session::config('oci8', 'mobydick', 'jdev', 'natsoft', 'mobydick', $options);
        break;

    case 'postgres':
        $options['table'] = 'sessions2';
        ADOdb_Session::config('postgres', 'localhost', 'postgres', 'natsoft', 'northwind', $options);
        break;

    case 'mysql':
    default:
        $options['table'] = 'sessions2';
        ADOdb_Session::config('mysql', 'localhost', 'root', '', 'xphplens_2', $options);
        break;
}
$USER = 'JLIM' . rand();
$ADODB_SESSION_EXPIRE_NOTIFY = array('USER', 'NotifyExpire');
adodb_session_create_table();
session_start();
adodb_session_regenerate_id();
if (empty($_SESSION['MONKEY']))
    $_SESSION['MONKEY'] = array(1, 'abc', 44.41);
else
    $_SESSION['MONKEY'][0] += 1;
if (!isset($_GET['nochange']))
    @$_SESSION['AVAR'] += 1;
print "<h3>PHP " . PHP_VERSION . "</h3>";
print "<p><b>\$_SESSION['AVAR']={$_SESSION['AVAR']}</b></p>";
print "<hr /> <b>Cookies</b>: ";
print_r($_COOKIE);
var_dump($_SESSION['MONKEY']);
if (rand() % 5 == 0) {
    print "<hr /><p><b>Garbage Collection</b></p>";
    adodb_sess_gc(10);
    if (rand() % 2 == 0) {
        print "<p>Random own session destroy</p>";
        session_destroy();
    }
} else {
    $DB = ADODB_Session::_conn();
    $sessk = $DB->qstr('%AZ' . rand() . time());
    $olddate = $DB->DBTimeStamp(time() - 30 * 24 * 3600);
    $rr = $DB->qstr(rand());
    $DB->Execute("insert into {$options['table']} (sesskey,expiry,expireref,sessdata,created,modified) values ($sessk,$olddate, $rr,'',$olddate,$olddate)");
}
?>