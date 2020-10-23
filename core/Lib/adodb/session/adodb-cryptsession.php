<?php

if (!defined('ADODB_SESSION')) {
    require_once dirname(__FILE__) . '/adodb-session.php';
}

require_once ADODB_SESSION . '/adodb-encrypt-md5.php';

ADODB_Session::filter(new ADODB_Encrypt_MD5());
?>