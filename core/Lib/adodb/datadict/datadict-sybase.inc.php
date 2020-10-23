<?php

if (!defined('ADODB_DIR'))
    die();

class ADODB2_sybase extends ADODB_DataDict {

    var $databaseType = 'sybase';
    var $dropIndex = 'DROP INDEX %2$s.%1$s';

    function MetaType($t, $len = -1, $fieldobj = false) {
        if (is_object($t)) {
            $fieldobj = $t;
            $t = $fieldobj->type;
            $len = $fieldobj->max_length;
        }

        $len = -1;
        switch (strtoupper($t)) {

            case 'INT':
            case 'INTEGER': return 'I';
            case 'BIT':
            case 'TINYINT': return 'I1';
            case 'SMALLINT': return 'I2';
            case 'BIGINT': return 'I8';

            case 'REAL':
            case 'FLOAT': return 'F';
            default: return parent::MetaType($t, $len, $fieldobj);
        }
    }

    function ActualType($meta) {
        switch (strtoupper($meta)) {
            case 'C': return 'VARCHAR';
            case 'XL':
            case 'X': return 'TEXT';

            case 'C2': return 'NVARCHAR';
            case 'X2': return 'NTEXT';

            case 'B': return 'IMAGE';

            case 'D': return 'DATETIME';
            case 'TS':
            case 'T': return 'DATETIME';
            case 'L': return 'BIT';

            case 'I': return 'INT';
            case 'I1': return 'TINYINT';
            case 'I2': return 'SMALLINT';
            case 'I4': return 'INT';
            case 'I8': return 'BIGINT';

            case 'F': return 'REAL';
            case 'N': return 'NUMERIC';
            default:
                return $meta;
        }
    }

    function AddColumnSQL($tabname, $flds) {
        $tabname = $this->TableName($tabname);
        $f = array();
        list($lines, $pkey) = $this->_GenFields($flds);
        $s = "ALTER TABLE $tabname $this->addCol";
        foreach ($lines as $v) {
            $f[] = "\n $v";
        }
        $s .= implode(', ', $f);
        $sql[] = $s;
        return $sql;
    }

    function AlterColumnSQL($tabname, $flds) {
        $tabname = $this->TableName($tabname);
        $sql = array();
        list($lines, $pkey) = $this->_GenFields($flds);
        foreach ($lines as $v) {
            $sql[] = "ALTER TABLE $tabname $this->alterCol $v";
        }

        return $sql;
    }

    function DropColumnSQL($tabname, $flds) {
        $tabname = $this->TableName($tabname);
        if (!is_array($flds))
            $flds = explode(',', $flds);
        $f = array();
        $s = "ALTER TABLE $tabname";
        foreach ($flds as $v) {
            $f[] = "\n$this->dropCol " . $this->NameQuote($v);
        }
        $s .= implode(', ', $f);
        $sql[] = $s;
        return $sql;
    }

    function _CreateSuffix($fname, &$ftype, $fnotnull, $fdefault, $fautoinc, $fconstraint, $funsigned) {
        $suffix = '';
        if (strlen($fdefault))
            $suffix .= " DEFAULT $fdefault";
        if ($fautoinc)
            $suffix .= ' DEFAULT AUTOINCREMENT';
        if ($fnotnull)
            $suffix .= ' NOT NULL';
        else if ($suffix == '')
            $suffix .= ' NULL';
        if ($fconstraint)
            $suffix .= ' ' . $fconstraint;
        return $suffix;
    }

    function _IndexSQL($idxname, $tabname, $flds, $idxoptions) {
        $sql = array();

        if (isset($idxoptions['REPLACE']) || isset($idxoptions['DROP'])) {
            $sql[] = sprintf($this->dropIndex, $idxname, $tabname);
            if (isset($idxoptions['DROP']))
                return $sql;
        }

        if (empty($flds)) {
            return $sql;
        }

        $unique = isset($idxoptions['UNIQUE']) ? ' UNIQUE' : '';
        $clustered = isset($idxoptions['CLUSTERED']) ? ' CLUSTERED' : '';

        if (is_array($flds))
            $flds = implode(', ', $flds);
        $s = 'CREATE' . $unique . $clustered . ' INDEX ' . $idxname . ' ON ' . $tabname . ' (' . $flds . ')';

        if (isset($idxoptions[$this->upperName]))
            $s .= $idxoptions[$this->upperName];

        $sql[] = $s;

        return $sql;
    }

}

?>