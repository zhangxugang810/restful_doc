<?php

if (!defined('ADODB_SESSION'))
    die();

include_once ADODB_SESSION . '/crypt.inc.php';

class ADODB_Encrypt_MD5 {

    function write($data, $key) {
        $md5crypt = new MD5Crypt();
        return $md5crypt->encrypt($data, $key);
    }

    function read($data, $key) {
        $md5crypt = new MD5Crypt();
        return $md5crypt->decrypt($data, $key);
    }

}

return 1;
?>