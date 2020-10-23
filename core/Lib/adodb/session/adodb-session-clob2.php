<?php
if (!defined('ADODB_SESSION')) {
	require_once dirname(__FILE__) . '/adodb-session2.php';
}
ADODB_Session::clob('CLOB');
?>