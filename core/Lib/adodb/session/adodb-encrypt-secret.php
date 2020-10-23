<?php

@define('HORDE_BASE', dirname(dirname(dirname(__FILE__))) . '/horde');

if (!is_dir(HORDE_BASE)) {
    trigger_error(sprintf('Directory not found: \'%s\'', HORDE_BASE), E_USER_ERROR);
    return 0;
}

include_once HORDE_BASE . '/lib/Horde.php';
include_once HORDE_BASE . '/lib/Secret.php';

class ADODB_Encrypt_Secret {

    function write($data, $key) {
        return Secret::write($key, $data);
    }

    function read($data, $key) {
        return Secret::read($key, $data);
    }

}

return 1;
?>
