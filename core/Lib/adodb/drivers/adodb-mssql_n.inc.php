<?php

if (!defined('ADODB_DIR'))
    die();
if (!defined('SINGLEQUOTE'))
    define('SINGLEQUOTE', "'");

include_once(ADODB_DIR . '/drivers/adodb-mssql.inc.php');

class ADODB_mssql_n extends ADODB_mssql {

    var $databaseType = "mssql_n";

    function ADODB_mssqlpo() {
        ADODB_mssql::ADODB_mssql();
    }

    function _query($sql, $inputarr = false) {
        $sql = $this->_appendN($sql);
        return ADODB_mssql::_query($sql, $inputarr);
    }

    function _appendN($sql) {

        $result = $sql;
        if (strpos($sql, SINGLEQUOTE) === false) {
            return $sql;
        }
        if ((substr_count($sql, SINGLEQUOTE) & 1)) {
            if ($this->debug) {
                ADOConnection::outp("{$this->databaseType} internal transformation: not converted. Wrong number of quotes (odd)");
            }
            return $sql;
        }
        $regexp = '/(\\\\' . SINGLEQUOTE . '[^' . SINGLEQUOTE . '])/';
        if (preg_match($regexp, $sql)) {
            if ($this->debug) {
                ADOConnection::outp("{$this->databaseType} internal transformation: not converted. Found bad use of backslash + single quote");
            }
            return $sql;
        }
        $pairs = array();
        $regexp = '/(' . SINGLEQUOTE . SINGLEQUOTE . ')/';
        preg_match_all($regexp, $result, $list_of_pairs);
        if ($list_of_pairs) {
            foreach (array_unique($list_of_pairs[0]) as $key => $value) {
                $pairs['<@#@#@PAIR-' . $key . '@#@#@>'] = $value;
            }
            if (!empty($pairs)) {
                $result = str_replace($pairs, array_keys($pairs), $result);
            }
        }
        $literals = array();
        $regexp = '/(N?' . SINGLEQUOTE . '.*?' . SINGLEQUOTE . ')/is';
        preg_match_all($regexp, $result, $list_of_literals);
        if ($list_of_literals) {
            foreach (array_unique($list_of_literals[0]) as $key => $value) {
                $literals['<#@#@#LITERAL-' . $key . '#@#@#>'] = $value;
            }
            if (!empty($literals)) {
                $result = str_replace($literals, array_keys($literals), $result);
            }
        }
        if (!empty($literals)) {
            foreach ($literals as $key => $value) {
                if (!is_numeric(trim($value, SINGLEQUOTE))) {
                    $literals[$key] = 'N' . trim($value, 'N');
                }
            }
        }
        if (!empty($literals)) {
            $result = str_replace(array_keys($literals), $literals, $result);
        }
        $result = preg_replace("/((<@#@#@PAIR-(\d+)@#@#@>)+)N'/", "N'$1", $result);
        if (!empty($pairs)) {
            $result = str_replace(array_keys($pairs), $pairs, $result);
        }
        if ($result != $sql && $this->debug) {
            ADOConnection::outp("{$this->databaseType} internal transformation:<br>{$sql}<br>to<br>{$result}");
        }

        return $result;
    }

}

class ADORecordset_mssql_n extends ADORecordset_mssql {

    var $databaseType = "mssql_n";

    function ADORecordset_mssql_n($id, $mode = false) {
        $this->ADORecordset_mssql($id, $mode);
    }

}

?>