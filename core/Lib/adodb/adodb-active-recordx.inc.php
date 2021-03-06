<?php

define('ADODB_JOIN_AR', 0x01);
define('ADODB_WORK_AR', 0x02);
define('ADODB_LAZY_AR', 0x03);
global $_ADODB_ACTIVE_DBS;
global $ADODB_ACTIVE_CACHESECS;
global $ACTIVE_RECORD_SAFETY;
global $ADODB_ACTIVE_DEFVALS;
$_ADODB_ACTIVE_DBS = array();
$ACTIVE_RECORD_SAFETY = true;
$ADODB_ACTIVE_DEFVALS = false;

class ADODB_Active_DB {

    var $db;
    var $tables;

}

class ADODB_Active_Table {

    var $name;
    var $flds;
    var $keys;
    var $_created;
    var $_belongsTo = array();
    var $_hasMany = array();
    var $_colsCount;

    function updateColsCount() {
        $this->_colsCount = sizeof($this->flds);
        foreach ($this->_belongsTo as $foreignTable)
            $this->_colsCount += sizeof($foreignTable->TableInfo()->flds);
        foreach ($this->_hasMany as $foreignTable)
            $this->_colsCount += sizeof($foreignTable->TableInfo()->flds);
    }

}

function ADODB_SetDatabaseAdapter(&$db) {
    global $_ADODB_ACTIVE_DBS;

    foreach ($_ADODB_ACTIVE_DBS as $k => $d) {
        if (PHP_VERSION >= 5) {
            if ($d->db === $db)
                return $k;
        } else {
            if ($d->db->_connectionID === $db->_connectionID && $db->database == $d->db->database)
                return $k;
        }
    }

    $obj = new ADODB_Active_DB();
    $obj->db = $db;
    $obj->tables = array();

    $_ADODB_ACTIVE_DBS[] = $obj;

    return sizeof($_ADODB_ACTIVE_DBS) - 1;
}

class ADODB_Active_Record {

    static $_changeNames = true;
    static $_foreignSuffix = '_id';
    var $_dbat;
    var $_table;
    var $_sTable;
    var $_pTable;
    var $_tableat;
    var $_where;
    var $_saved = false;
    var $_lasterr = false;
    var $_original = false;
    var $foreignName;

    static function UseDefaultValues($bool = null) {
        global $ADODB_ACTIVE_DEFVALS;
        if (isset($bool))
            $ADODB_ACTIVE_DEFVALS = $bool;
        return $ADODB_ACTIVE_DEFVALS;
    }

    static function SetDatabaseAdapter(&$db) {
        return ADODB_SetDatabaseAdapter($db);
    }

    public function __set($name, $value) {
        $name = str_replace(' ', '_', $name);
        $this->$name = $value;
    }

    function __construct($table = false, $pkeyarr = false, $db = false, $options = array()) {
        global $ADODB_ASSOC_CASE, $_ADODB_ACTIVE_DBS;

        if ($db == false && is_object($pkeyarr)) {
            $db = $pkeyarr;
            $pkeyarr = false;
        }

        if ($table) {
            $this->_pTable = $table;
            $this->_sTable = $this->_singularize($this->_pTable);
        } else {
            $this->_sTable = strtolower(get_class($this));
            $this->_pTable = $this->_pluralize($this->_sTable);
        }
        $this->_table = &$this->_pTable;

        $this->foreignName = $this->_sTable;

        if ($db) {
            $this->_dbat = ADODB_Active_Record::SetDatabaseAdapter($db);
        } else
            $this->_dbat = sizeof($_ADODB_ACTIVE_DBS) - 1;


        if ($this->_dbat < 0)
            $this->Error("No database connection set; use ADOdb_Active_Record::SetDatabaseAdapter(\$db)", 'ADODB_Active_Record::__constructor');

        $this->_tableat = $this->_table;
        $forceUpdate = (isset($options['refresh']) && true === $options['refresh']);
        $this->UpdateActiveTable($pkeyarr, $forceUpdate);
        if (isset($options['new']) && true === $options['new']) {
            $table = & $this->TableInfo();
            unset($table->_hasMany);
            unset($table->_belongsTo);
            $table->_hasMany = array();
            $table->_belongsTo = array();
        }
    }

    function __wakeup() {
        $class = get_class($this);
        new $class;
    }

    static $IrregularP = array(
        'PERSON' => 'people',
        'MAN' => 'men',
        'WOMAN' => 'women',
        'CHILD' => 'children',
        'COW' => 'kine',
    );
    static $IrregularS = array(
        'PEOPLE' => 'PERSON',
        'MEN' => 'man',
        'WOMEN' => 'woman',
        'CHILDREN' => 'child',
        'KINE' => 'cow',
    );
    static $WeIsI = array(
        'EQUIPMENT' => true,
        'INFORMATION' => true,
        'RICE' => true,
        'MONEY' => true,
        'SPECIES' => true,
        'SERIES' => true,
        'FISH' => true,
        'SHEEP' => true,
    );

    function _pluralize($table) {
        if (!ADODB_Active_Record::$_changeNames)
            return $table;

        $ut = strtoupper($table);
        if (isset(self::$WeIsI[$ut])) {
            return $table;
        }
        if (isset(self::$IrregularP[$ut])) {
            return self::$IrregularP[$ut];
        }
        $len = strlen($table);
        $lastc = $ut[$len - 1];
        $lastc2 = substr($ut, $len - 2);
        switch ($lastc) {
            case 'S':
                return $table . 'es';
            case 'Y':
                return substr($table, 0, $len - 1) . 'ies';
            case 'X':
                return $table . 'es';
            case 'H':
                if ($lastc2 == 'CH' || $lastc2 == 'SH')
                    return $table . 'es';
            default:
                return $table . 's';
        }
    }

    // CFR Lamest singular inflector ever - @todo Make it real!
    // Note: There is an assumption here...and it is that the argument's length >= 4
    function _singularize($table) {

        if (!ADODB_Active_Record::$_changeNames)
            return $table;

        $ut = strtoupper($table);
        if (isset(self::$WeIsI[$ut])) {
            return $table;
        }
        if (isset(self::$IrregularS[$ut])) {
            return self::$IrregularS[$ut];
        }
        $len = strlen($table);
        if ($ut[$len - 1] != 'S')
            return $table;
        if ($ut[$len - 2] != 'E')
            return substr($table, 0, $len - 1);
        switch ($ut[$len - 3]) {
            case 'S':
            case 'X':
                return substr($table, 0, $len - 2);
            case 'I':
                return substr($table, 0, $len - 3) . 'y';
            case 'H';
                if ($ut[$len - 4] == 'C' || $ut[$len - 4] == 'S')
                    return substr($table, 0, $len - 2);
            default:
                return substr($table, 0, $len - 1); // ?
        }
    }

    function hasMany($foreignRef, $foreignKey = false) {
        $ar = new ADODB_Active_Record($foreignRef);
        $ar->foreignName = $foreignRef;
        $ar->UpdateActiveTable();
        $ar->foreignKey = ($foreignKey) ? $foreignKey : strtolower(get_class($this)) . self::$_foreignSuffix;

        $table = & $this->TableInfo();
        if (!isset($table->_hasMany[$foreignRef])) {
            $table->_hasMany[$foreignRef] = $ar;
            $table->updateColsCount();
        }
        $this->$foreignRef = $table->_hasMany[$foreignRef]; // WATCHME Removed assignment by ref. to please __get()
    }

    function belongsTo($foreignRef, $foreignKey = false) {
        global $inflector;

        $ar = new ADODB_Active_Record($this->_pluralize($foreignRef));
        $ar->foreignName = $foreignRef;
        $ar->UpdateActiveTable();
        $ar->foreignKey = ($foreignKey) ? $foreignKey : $ar->foreignName . self::$_foreignSuffix;

        $table = & $this->TableInfo();
        if (!isset($table->_belongsTo[$foreignRef])) {
            $table->_belongsTo[$foreignRef] = $ar;
            $table->updateColsCount();
        }
        $this->$foreignRef = $table->_belongsTo[$foreignRef];
    }

    function __get($name) {
        return $this->LoadRelations($name, '', -1. - 1);
    }

    function LoadRelations($name, $whereOrderBy, $offset = -1, $limit = -1) {
        $extras = array();
        if ($offset >= 0)
            $extras['offset'] = $offset;
        if ($limit >= 0)
            $extras['limit'] = $limit;
        $table = & $this->TableInfo();

        if (strlen($whereOrderBy))
            if (!preg_match('/^[ \n\r]*AND/i', $whereOrderBy))
                if (!preg_match('/^[ \n\r]*ORDER[ \n\r]/i', $whereOrderBy))
                    $whereOrderBy = 'AND ' . $whereOrderBy;

        if (!empty($table->_belongsTo[$name])) {
            $obj = $table->_belongsTo[$name];
            $columnName = $obj->foreignKey;
            if (empty($this->$columnName))
                $this->$name = null;
            else {
                if (($k = reset($obj->TableInfo()->keys)))
                    $belongsToId = $k;
                else
                    $belongsToId = 'id';

                $arrayOfOne = $obj->Find(
                        $belongsToId . '=' . $this->$columnName . ' ' . $whereOrderBy, false, false, $extras);
                $this->$name = $arrayOfOne[0];
            }
            return $this->$name;
        }
        if (!empty($table->_hasMany[$name])) {
            $obj = $table->_hasMany[$name];
            if (($k = reset($table->keys)))
                $hasManyId = $k;
            else
                $hasManyId = 'id';

            $this->$name = $obj->Find(
                    $obj->foreignKey . '=' . $this->$hasManyId . ' ' . $whereOrderBy, false, false, $extras);
            return $this->$name;
        }
    }

    function UpdateActiveTable($pkeys = false, $forceUpdate = false) {
        global $ADODB_ASSOC_CASE, $_ADODB_ACTIVE_DBS, $ADODB_CACHE_DIR, $ADODB_ACTIVE_CACHESECS;
        global $ADODB_ACTIVE_DEFVALS, $ADODB_FETCH_MODE;

        $activedb = $_ADODB_ACTIVE_DBS[$this->_dbat];

        $table = $this->_table;
        $tables = $activedb->tables;
        $tableat = $this->_tableat;
        if (!$forceUpdate && !empty($tables[$tableat])) {

            $tobj = $tables[$tableat];
            foreach ($tobj->flds as $name => $fld) {
                if ($ADODB_ACTIVE_DEFVALS && isset($fld->default_value))
                    $this->$name = $fld->default_value;
                else
                    $this->$name = null;
            }
            return;
        }

        $db = $activedb->db;
        $fname = $ADODB_CACHE_DIR . '/adodb_' . $db->databaseType . '_active_' . $table . '.cache';
        if (!$forceUpdate && $ADODB_ACTIVE_CACHESECS && $ADODB_CACHE_DIR && file_exists($fname)) {
            $fp = fopen($fname, 'r');
            @flock($fp, LOCK_SH);
            $acttab = unserialize(fread($fp, 100000));
            fclose($fp);
            if ($acttab->_created + $ADODB_ACTIVE_CACHESECS - (abs(rand()) % 16) > time()) {
                $activedb->tables[$table] = $acttab;
                return;
            } else if ($db->debug) {
                ADOConnection::outp("Refreshing cached active record file: $fname");
            }
        }
        $activetab = new ADODB_Active_Table();
        $activetab->name = $table;

        $save = $ADODB_FETCH_MODE;
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
        if ($db->fetchMode !== false)
            $savem = $db->SetFetchMode(false);

        $cols = $db->MetaColumns($table);

        if (isset($savem))
            $db->SetFetchMode($savem);
        $ADODB_FETCH_MODE = $save;

        if (!$cols) {
            $this->Error("Invalid table name: $table", 'UpdateActiveTable');
            return false;
        }
        $fld = reset($cols);
        if (!$pkeys) {
            if (isset($fld->primary_key)) {
                $pkeys = array();
                foreach ($cols as $name => $fld) {
                    if (!empty($fld->primary_key))
                        $pkeys[] = $name;
                }
            } else
                $pkeys = $this->GetPrimaryKeys($db, $table);
        }
        if (empty($pkeys)) {
            $this->Error("No primary key found for table $table", 'UpdateActiveTable');
            return false;
        }

        $attr = array();
        $keys = array();

        switch ($ADODB_ASSOC_CASE) {
            case 0:
                foreach ($cols as $name => $fldobj) {
                    $name = strtolower($name);
                    if ($ADODB_ACTIVE_DEFVALS && isset($fldobj->default_value))
                        $this->$name = $fldobj->default_value;
                    else
                        $this->$name = null;
                    $attr[$name] = $fldobj;
                }
                foreach ($pkeys as $k => $name) {
                    $keys[strtolower($name)] = strtolower($name);
                }
                break;

            case 1:
                foreach ($cols as $name => $fldobj) {
                    $name = strtoupper($name);

                    if ($ADODB_ACTIVE_DEFVALS && isset($fldobj->default_value))
                        $this->$name = $fldobj->default_value;
                    else
                        $this->$name = null;
                    $attr[$name] = $fldobj;
                }

                foreach ($pkeys as $k => $name) {
                    $keys[strtoupper($name)] = strtoupper($name);
                }
                break;
            default:
                foreach ($cols as $name => $fldobj) {
                    $name = ($fldobj->name);

                    if ($ADODB_ACTIVE_DEFVALS && isset($fldobj->default_value))
                        $this->$name = $fldobj->default_value;
                    else
                        $this->$name = null;
                    $attr[$name] = $fldobj;
                }
                foreach ($pkeys as $k => $name) {
                    $keys[$name] = $cols[$name]->name;
                }
                break;
        }

        $activetab->keys = $keys;
        $activetab->flds = $attr;
        $activetab->updateColsCount();

        if ($ADODB_ACTIVE_CACHESECS && $ADODB_CACHE_DIR) {
            $activetab->_created = time();
            $s = serialize($activetab);
            if (!function_exists('adodb_write_file'))
                include(ADODB_DIR . '/adodb-csvlib.inc.php');
            adodb_write_file($fname, $s);
        }
        if (isset($activedb->tables[$table])) {
            $oldtab = $activedb->tables[$table];

            if ($oldtab)
                $activetab->_belongsTo = $oldtab->_belongsTo;
            if ($oldtab)
                $activetab->_hasMany = $oldtab->_hasMany;
        }
        $activedb->tables[$table] = $activetab;
    }

    function GetPrimaryKeys(&$db, $table) {
        return $db->MetaPrimaryKeys($table);
    }

    function Error($err, $fn) {
        global $_ADODB_ACTIVE_DBS;

        $fn = get_class($this) . '::' . $fn;
        $this->_lasterr = $fn . ': ' . $err;

        if ($this->_dbat < 0)
            $db = false;
        else {
            $activedb = $_ADODB_ACTIVE_DBS[$this->_dbat];
            $db = $activedb->db;
        }

        if (function_exists('adodb_throw')) {
            if (!$db)
                adodb_throw('ADOdb_Active_Record', $fn, -1, $err, 0, 0, false);
            else
                adodb_throw($db->databaseType, $fn, -1, $err, 0, 0, $db);
        } else
        if (!$db || $db->debug)
            ADOConnection::outp($this->_lasterr);
    }

    function ErrorMsg() {
        if (!function_exists('adodb_throw')) {
            if ($this->_dbat < 0)
                $db = false;
            else
                $db = $this->DB();
            if ($db && $db->ErrorMsg())
                return $db->ErrorMsg();
        }
        return $this->_lasterr;
    }

    function ErrorNo() {
        if ($this->_dbat < 0)
            return -9999;
        $db = $this->DB();

        return (int) $db->ErrorNo();
    }

    function DB() {
        global $_ADODB_ACTIVE_DBS;

        if ($this->_dbat < 0) {
            $false = false;
            $this->Error("No database connection set: use ADOdb_Active_Record::SetDatabaseAdaptor(\$db)", "DB");
            return $false;
        }
        $activedb = $_ADODB_ACTIVE_DBS[$this->_dbat];
        $db = $activedb->db;
        return $db;
    }

    function &TableInfo() {
        global $_ADODB_ACTIVE_DBS;

        $activedb = $_ADODB_ACTIVE_DBS[$this->_dbat];
        $table = $activedb->tables[$this->_tableat];
        return $table;
    }

    function Reload() {
        $db = & $this->DB();
        if (!$db)
            return false;
        $table = & $this->TableInfo();
        $where = $this->GenWhere($db, $table);
        return($this->Load($where));
    }

    function Set(&$row) {
        global $ACTIVE_RECORD_SAFETY;

        $db = $this->DB();

        if (!$row) {
            $this->_saved = false;
            return false;
        }

        $this->_saved = true;

        $table = $this->TableInfo();
        $sizeofFlds = sizeof($table->flds);
        $sizeofRow = sizeof($row);
        if ($ACTIVE_RECORD_SAFETY && $table->_colsCount != $sizeofRow && $sizeofFlds != $sizeofRow) {
            $bad_size = TRUE;
            if ($sizeofRow == 2 * $table->_colsCount || $sizeofRow == 2 * $sizeofFlds) {
                $keys = array_filter(array_keys($row), 'is_string');
                if (sizeof($keys) == sizeof($table->flds))
                    $bad_size = FALSE;
            }
            if ($bad_size) {
                $this->Error("Table structure of $this->_table has changed", "Load");
                return false;
            }
        } else
            $keys = array_keys($row);
        reset($keys);
        $this->_original = array();
        foreach ($table->flds as $name => $fld) {
            $value = $row[current($keys)];
            $this->$name = $value;
            $this->_original[] = $value;
            if (!next($keys))
                break;
        }
        $table = & $this->TableInfo();
        foreach ($table->_belongsTo as $foreignTable) {
            $ft = $foreignTable->TableInfo();
            $propertyName = $ft->name;
            foreach ($ft->flds as $name => $fld) {
                $value = $row[current($keys)];
                $foreignTable->$name = $value;
                $foreignTable->_original[] = $value;
                if (!next($keys))
                    break;
            }
        }
        foreach ($table->_hasMany as $foreignTable) {
            $ft = $foreignTable->TableInfo();
            foreach ($ft->flds as $name => $fld) {
                $value = $row[current($keys)];
                $foreignTable->$name = $value;
                $foreignTable->_original[] = $value;
                if (!next($keys))
                    break;
            }
        }
        return true;
    }

    function LastInsertID(&$db, $fieldname) {
        if ($db->hasInsertID)
            $val = $db->Insert_ID($this->_table, $fieldname);
        else
            $val = false;

        if (is_null($val) || $val === false) {
            return $db->GetOne("select max(" . $fieldname . ") from " . $this->_table);
        }
        return $val;
    }

    function doquote(&$db, $val, $t) {
        switch ($t) {
            case 'D':
            case 'T':
                if (empty($val))
                    return 'null';

            case 'C':
            case 'X':
                if (is_null($val))
                    return 'null';

                if (strlen($val) > 0 &&
                        (strncmp($val, "'", 1) != 0 || substr($val, strlen($val) - 1, 1) != "'")) {
                    return $db->qstr($val);
                    break;
                }
            default:
                return $val;
                break;
        }
    }

    function GenWhere(&$db, &$table) {
        $keys = $table->keys;
        $parr = array();

        foreach ($keys as $k) {
            $f = $table->flds[$k];
            if ($f) {
                $parr[] = $k . ' = ' . $this->doquote($db, $this->$k, $db->MetaType($f->type));
            }
        }
        return implode(' and ', $parr);
    }

    function Load($where = null, $bindarr = false) {
        $db = $this->DB();
        if (!$db)
            return false;
        $this->_where = $where;

        $save = $db->SetFetchMode(ADODB_FETCH_NUM);
        $qry = "select * from " . $this->_table;
        $table = & $this->TableInfo();

        if (($k = reset($table->keys)))
            $hasManyId = $k;
        else
            $hasManyId = 'id';

        foreach ($table->_belongsTo as $foreignTable) {
            if (($k = reset($foreignTable->TableInfo()->keys))) {
                $belongsToId = $k;
            } else {
                $belongsToId = 'id';
            }
            $qry .= ' LEFT JOIN ' . $foreignTable->_table . ' ON ' .
                    $this->_table . '.' . $foreignTable->foreignKey . '=' .
                    $foreignTable->_table . '.' . $belongsToId;
        }
        foreach ($table->_hasMany as $foreignTable) {
            $qry .= ' LEFT JOIN ' . $foreignTable->_table . ' ON ' .
                    $this->_table . '.' . $hasManyId . '=' .
                    $foreignTable->_table . '.' . $foreignTable->foreignKey;
        }
        if ($where)
            $qry .= ' WHERE ' . $where;
        if ((count($table->_hasMany) + count($table->_belongsTo)) < 1) {
            $row = $db->GetRow($qry, $bindarr);
            if (!$row)
                return false;
            $db->SetFetchMode($save);
            return $this->Set($row);
        }
        $rows = $db->GetAll($qry, $bindarr);
        if (!$rows)
            return false;
        $db->SetFetchMode($save);
        if (count($rows) < 1)
            return false;
        $class = get_class($this);
        $isFirstRow = true;

        if (($k = reset($this->TableInfo()->keys)))
            $myId = $k;
        else
            $myId = 'id';
        $index = 0;
        $found = false;
        foreach ($this->TableInfo()->flds as $fld) {
            if ($fld->name == $myId) {
                $found = true;
                break;
            }
            $index++;
        }
        if (!$found)
            $this->outp_throw("Unable to locate key $myId for $class in Load()", 'Load');

        foreach ($rows as $row) {
            $rowId = intval($row[$index]);
            if ($rowId > 0) {
                if ($isFirstRow) {
                    $isFirstRow = false;
                    if (!$this->Set($row))
                        return false;
                }
                $obj = new $class($table, false, $db);
                $obj->Set($row);
                if (count($table->_hasMany) > 0) {
                    foreach ($table->_hasMany as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            if (!is_array($this->$foreignName)) {
                                $foreignObj = $this->$foreignName;
                                $this->$foreignName = array(clone($foreignObj));
                            } else {
                                $foreignObj = $obj->$foreignName;
                                array_push($this->$foreignName, clone($foreignObj));
                            }
                        }
                    }
                }
                if (count($table->_belongsTo) > 0) {
                    foreach ($table->_belongsTo as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            if (!is_array($this->$foreignName)) {
                                $foreignObj = $this->$foreignName;
                                $this->$foreignName = array(clone($foreignObj));
                            } else {
                                $foreignObj = $obj->$foreignName;
                                array_push($this->$foreignName, clone($foreignObj));
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    function Save() {
        if ($this->_saved)
            $ok = $this->Update();
        else
            $ok = $this->Insert();

        return $ok;
    }

    function Dirty() {
        $this->_saved = false;
    }

    function Insert() {
        $db = $this->DB();
        if (!$db)
            return false;
        $cnt = 0;
        $table = $this->TableInfo();

        $valarr = array();
        $names = array();
        $valstr = array();

        foreach ($table->flds as $name => $fld) {
            $val = $this->$name;
            if (!is_null($val) || !array_key_exists($name, $table->keys)) {
                $valarr[] = $val;
                $names[] = $name;
                $valstr[] = $db->Param($cnt);
                $cnt += 1;
            }
        }

        if (empty($names)) {
            foreach ($table->flds as $name => $fld) {
                $valarr[] = null;
                $names[] = $name;
                $valstr[] = $db->Param($cnt);
                $cnt += 1;
            }
        }
        $sql = 'INSERT INTO ' . $this->_table . "(" . implode(',', $names) . ') VALUES (' . implode(',', $valstr) . ')';
        $ok = $db->Execute($sql, $valarr);

        if ($ok) {
            $this->_saved = true;
            $autoinc = false;
            foreach ($table->keys as $k) {
                if (is_null($this->$k)) {
                    $autoinc = true;
                    break;
                }
            }
            if ($autoinc && sizeof($table->keys) == 1) {
                $k = reset($table->keys);
                $this->$k = $this->LastInsertID($db, $k);
            }
        }

        $this->_original = $valarr;
        return !empty($ok);
    }

    function Delete() {
        $db = $this->DB();
        if (!$db)
            return false;
        $table = $this->TableInfo();

        $where = $this->GenWhere($db, $table);
        $sql = 'DELETE FROM ' . $this->_table . ' WHERE ' . $where;
        $ok = $db->Execute($sql);

        return $ok ? true : false;
    }

    function Find($whereOrderBy, $bindarr = false, $pkeysArr = false, $extra = array()) {
        $db = $this->DB();
        if (!$db || empty($this->_table))
            return false;
        $table = & $this->TableInfo();
        $arr = $db->GetActiveRecordsClass(get_class($this), $this, $whereOrderBy, $bindarr, $pkeysArr, $extra, array('foreignName' => $this->foreignName, 'belongsTo' => $table->_belongsTo, 'hasMany' => $table->_hasMany));
        return $arr;
    }

    function packageFind($whereOrderBy, $bindarr = false, $pkeysArr = false, $extra = array()) {
        $db = $this->DB();
        if (!$db || empty($this->_table))
            return false;
        $table = & $this->TableInfo();
        $arr = $db->GetActiveRecordsClass(get_class($this), $this, $whereOrderBy, $bindarr, $pkeysArr, $extra, array('foreignName' => $this->foreignName, 'belongsTo' => $table->_belongsTo, 'hasMany' => $table->_hasMany));
        return $arr;
    }

    function Replace() {
        global $ADODB_ASSOC_CASE;

        $db = $this->DB();
        if (!$db)
            return false;
        $table = $this->TableInfo();

        $pkey = $table->keys;

        foreach ($table->flds as $name => $fld) {
            $val = $this->$name;
            if (is_null($val) && !empty($fld->auto_increment)) {
                continue;
            }
            $t = $db->MetaType($fld->type);
            $arr[$name] = $this->doquote($db, $val, $t);
            $valarr[] = $val;
        }

        if (!is_array($pkey))
            $pkey = array($pkey);


        if ($ADODB_ASSOC_CASE == 0)
            foreach ($pkey as $k => $v)
                $pkey[$k] = strtolower($v);
        elseif ($ADODB_ASSOC_CASE == 1)
            foreach ($pkey as $k => $v)
                $pkey[$k] = strtoupper($v);

        $ok = $db->Replace($this->_table, $arr, $pkey);
        if ($ok) {
            $this->_saved = true;
            if ($ok == 2) {
                $autoinc = false;
                foreach ($table->keys as $k) {
                    if (is_null($this->$k)) {
                        $autoinc = true;
                        break;
                    }
                }
                if ($autoinc && sizeof($table->keys) == 1) {
                    $k = reset($table->keys);
                    $this->$k = $this->LastInsertID($db, $k);
                }
            }

            $this->_original = $valarr;
        }
        return $ok;
    }

    function Update() {
        $db = $this->DB();
        if (!$db)
            return false;
        $table = $this->TableInfo();

        $where = $this->GenWhere($db, $table);

        if (!$where) {
            $this->error("Where missing for table $table", "Update");
            return false;
        }
        $valarr = array();
        $neworig = array();
        $pairs = array();
        $i = -1;
        $cnt = 0;
        foreach ($table->flds as $name => $fld) {
            $i += 1;
            $val = $this->$name;
            $neworig[] = $val;

            if (isset($table->keys[$name])) {
                continue;
            }

            if (is_null($val)) {
                if (isset($fld->not_null) && $fld->not_null) {
                    if (isset($fld->default_value) && strlen($fld->default_value))
                        continue;
                    else {
                        $this->Error("Cannot set field $name to NULL", "Update");
                        return false;
                    }
                }
            }

            if (isset($this->_original[$i]) && $val == $this->_original[$i]) {
                continue;
            }
            $valarr[] = $val;
            $pairs[] = $name . '=' . $db->Param($cnt);
            $cnt += 1;
        }


        if (!$cnt)
            return -1;
        $sql = 'UPDATE ' . $this->_table . " SET " . implode(",", $pairs) . " WHERE " . $where;
        $ok = $db->Execute($sql, $valarr);
        if ($ok) {
            $this->_original = $neworig;
            return 1;
        }
        return 0;
    }

    function GetAttributeNames() {
        $table = $this->TableInfo();
        if (!$table)
            return false;
        return array_keys($table->flds);
    }

}

;

function adodb_GetActiveRecordsClass(&$db, $class, $tableObj, $whereOrderBy, $bindarr, $primkeyArr, $extra, $relations) {
    global $_ADODB_ACTIVE_DBS;

    if (empty($extra['loading']))
        $extra['loading'] = ADODB_LAZY_AR;

    $save = $db->SetFetchMode(ADODB_FETCH_NUM);
    $table = &$tableObj->_table;
    $tableInfo = & $tableObj->TableInfo();
    if (($k = reset($tableInfo->keys)))
        $myId = $k;
    else
        $myId = 'id';
    $index = 0;
    $found = false;
    foreach ($tableInfo->flds as $fld) {
        if ($fld->name == $myId) {
            $found = true;
            break;
        }
        $index++;
    }
    if (!$found)
        $db->outp_throw("Unable to locate key $myId for $class in GetActiveRecordsClass()", 'GetActiveRecordsClass');

    $qry = "select * from " . $table;
    if (ADODB_JOIN_AR == $extra['loading']) {
        if (!empty($relations['belongsTo'])) {
            foreach ($relations['belongsTo'] as $foreignTable) {
                if (($k = reset($foreignTable->TableInfo()->keys))) {
                    $belongsToId = $k;
                } else {
                    $belongsToId = 'id';
                }

                $qry .= ' LEFT JOIN ' . $foreignTable->_table . ' ON ' .
                        $table . '.' . $foreignTable->foreignKey . '=' .
                        $foreignTable->_table . '.' . $belongsToId;
            }
        }
        if (!empty($relations['hasMany'])) {
            if (empty($relations['foreignName']))
                $db->outp_throw("Missing foreignName is relation specification in GetActiveRecordsClass()", 'GetActiveRecordsClass');
            if (($k = reset($tableInfo->keys)))
                $hasManyId = $k;
            else
                $hasManyId = 'id';

            foreach ($relations['hasMany'] as $foreignTable) {
                $qry .= ' LEFT JOIN ' . $foreignTable->_table . ' ON ' .
                        $table . '.' . $hasManyId . '=' .
                        $foreignTable->_table . '.' . $foreignTable->foreignKey;
            }
        }
    }
    if (!empty($whereOrderBy))
        $qry .= ' WHERE ' . $whereOrderBy;
    if (isset($extra['limit'])) {
        $rows = false;
        if (isset($extra['offset'])) {
            $rs = $db->SelectLimit($qry, $extra['limit'], $extra['offset']);
        } else {
            $rs = $db->SelectLimit($qry, $extra['limit']);
        }
        if ($rs) {
            while (!$rs->EOF) {
                $rows[] = $rs->fields;
                $rs->MoveNext();
            }
        }
    } else
        $rows = $db->GetAll($qry, $bindarr);

    $db->SetFetchMode($save);

    $false = false;

    if ($rows === false) {
        return $false;
    }


    if (!isset($_ADODB_ACTIVE_DBS)) {
        include(ADODB_DIR . '/adodb-active-record.inc.php');
    }
    if (!class_exists($class)) {
        $db->outp_throw("Unknown class $class in GetActiveRecordsClass()", 'GetActiveRecordsClass');
        return $false;
    }
    $uniqArr = array();
    $arr = array();
    $arrRef = array();
    $bTos = array();
    foreach ($rows as $row) {

        $obj = new $class($table, $primkeyArr, $db);
        if ($obj->ErrorNo()) {
            $db->_errorMsg = $obj->ErrorMsg();
            return $false;
        }
        $obj->Set($row);
        $rowId = intval($row[$index]);

        if (ADODB_WORK_AR == $extra['loading']) {
            $arrRef[$rowId] = $obj;
            $arr[] = &$arrRef[$rowId];
            if (!isset($indices))
                $indices = $rowId;
            else
                $indices .= ',' . $rowId;
            if (!empty($relations['belongsTo'])) {
                foreach ($relations['belongsTo'] as $foreignTable) {
                    $foreignTableRef = $foreignTable->foreignKey;
                    // First array: list of foreign ids we are looking for
                    if (empty($bTos[$foreignTableRef]))
                        $bTos[$foreignTableRef] = array();
                    // Second array: list of ids found
                    if (empty($obj->$foreignTableRef))
                        continue;
                    if (empty($bTos[$foreignTableRef][$obj->$foreignTableRef]))
                        $bTos[$foreignTableRef][$obj->$foreignTableRef] = array();
                    $bTos[$foreignTableRef][$obj->$foreignTableRef][] = $obj;
                }
            }
            continue;
        }

        if ($rowId > 0) {
            if (ADODB_JOIN_AR == $extra['loading']) {
                $isNewObj = !isset($uniqArr['_' . $row[0]]);
                if ($isNewObj)
                    $uniqArr['_' . $row[0]] = $obj;
                if (!empty($relations['hasMany'])) {
                    foreach ($relations['hasMany'] as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            $masterObj = &$uniqArr['_' . $row[0]];
                            // Assumption: this property exists in every object since they are instances of the same class
                            if (!is_array($masterObj->$foreignName)) {
                                // Pluck!
                                $foreignObj = $masterObj->$foreignName;
                                $masterObj->$foreignName = array(clone($foreignObj));
                            } else {
                                // Pluck pluck!
                                $foreignObj = $obj->$foreignName;
                                array_push($masterObj->$foreignName, clone($foreignObj));
                            }
                        }
                    }
                }
                if (!empty($relations['belongsTo'])) {
                    foreach ($relations['belongsTo'] as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            $masterObj = &$uniqArr['_' . $row[0]];
                            if (!is_array($masterObj->$foreignName)) {

                                $foreignObj = $masterObj->$foreignName;
                                $masterObj->$foreignName = array(clone($foreignObj));
                            } else {
                                $foreignObj = $obj->$foreignName;
                                array_push($masterObj->$foreignName, clone($foreignObj));
                            }
                        }
                    }
                }
                if (!$isNewObj)
                    unset($obj);
            }
            else if (ADODB_LAZY_AR == $extra['loading']) {
                if (!empty($relations['hasMany'])) {
                    foreach ($relations['hasMany'] as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            unset($obj->$foreignName);
                        }
                    }
                }
                if (!empty($relations['belongsTo'])) {
                    foreach ($relations['belongsTo'] as $foreignTable) {
                        $foreignName = $foreignTable->foreignName;
                        if (!empty($obj->$foreignName)) {
                            unset($obj->$foreignName);
                        }
                    }
                }
            }
        }

        if (isset($obj))
            $arr[] = $obj;
    }

    if (ADODB_WORK_AR == $extra['loading']) {
        if (!empty($relations['hasMany'])) {
            foreach ($relations['hasMany'] as $foreignTable) {
                $foreignName = $foreignTable->foreignName;
                $className = ucfirst($foreignTable->_singularize($foreignName));
                $obj = new $className();
                $dbClassRef = $foreignTable->foreignKey;
                $objs = $obj->packageFind($dbClassRef . ' IN (' . $indices . ')');
                foreach ($objs as $obj) {
                    if (!is_array($arrRef[$obj->$dbClassRef]->$foreignName))
                        $arrRef[$obj->$dbClassRef]->$foreignName = array();
                    array_push($arrRef[$obj->$dbClassRef]->$foreignName, $obj);
                }
            }
        }
        if (!empty($relations['belongsTo'])) {
            foreach ($relations['belongsTo'] as $foreignTable) {
                $foreignTableRef = $foreignTable->foreignKey;
                if (empty($bTos[$foreignTableRef]))
                    continue;
                if (($k = reset($foreignTable->TableInfo()->keys))) {
                    $belongsToId = $k;
                } else {
                    $belongsToId = 'id';
                }
                $origObjsArr = $bTos[$foreignTableRef];
                $bTosString = implode(',', array_keys($bTos[$foreignTableRef]));
                $foreignName = $foreignTable->foreignName;
                $className = ucfirst($foreignTable->_singularize($foreignName));
                $obj = new $className();
                $objs = $obj->packageFind($belongsToId . ' IN (' . $bTosString . ')');
                foreach ($objs as $obj) {
                    foreach ($origObjsArr[$obj->$belongsToId] as $idx => $origObj) {
                        $origObj->$foreignName = $obj;
                    }
                }
            }
        }
    }

    return $arr;
}

?>
