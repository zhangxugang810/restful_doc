<?php

if (!defined('ADODB_DIR'))
    die();

include_once(ADODB_DIR . '/drivers/adodb-sqlite.inc.php');

class ADODB_sqlitepo extends ADODB_sqlite {

    var $databaseType = 'sqlitepo';

    function ADODB_sqlitepo() {
        $this->ADODB_sqlite();
    }

}

class ADORecordset_sqlitepo extends ADORecordset_sqlite {

    var $databaseType = 'sqlitepo';

    function ADORecordset_sqlitepo($queryID, $mode = false) {
        $this->ADORecordset_sqlite($queryID, $mode);
    }

    function _fetch($ignore_fields = false) {
        $this->fields = array();
        $fields = @sqlite_fetch_array($this->_queryID, $this->fetchMode);
        if (is_array($fields))
            foreach ($fields as $n => $v) {
                if (($p = strpos($n, ".")) !== false)
                    $n = substr($n, $p + 1);
                $this->fields[$n] = $v;
            }

        return !empty($this->fields);
    }

}

?>