<?php

if (!defined('_ADODB_LAYER')) {
    require realpath(dirname(__FILE__) . '/../adodb.inc.php');
}

if (defined('ADODB_SESSION'))
    return 1;

define('ADODB_SESSION', dirname(__FILE__));
define('ADODB_SESSION2', ADODB_SESSION);

function adodb_unserialize($serialized_string) {
    $variables = array();
    $a = preg_split("/(\w+)\|/", $serialized_string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    for ($i = 0; $i < count($a); $i = $i + 2) {
        $variables[$a[$i]] = unserialize($a[$i + 1]);
    }
    return( $variables );
}

function adodb_session_regenerate_id() {
    $conn = ADODB_Session::_conn();
    if (!$conn)
        return false;

    $old_id = session_id();
    if (function_exists('session_regenerate_id')) {
        session_regenerate_id();
    } else {
        session_id(md5(uniqid(rand(), true)));
        $ck = session_get_cookie_params();
        setcookie(session_name(), session_id(), false, $ck['path'], $ck['domain'], $ck['secure']);
    }
    $new_id = session_id();
    $ok = $conn->Execute('UPDATE ' . ADODB_Session::table() . ' SET sesskey=' . $conn->qstr($new_id) . ' WHERE sesskey=' . $conn->qstr($old_id));
    if (!$ok) {
        session_id($old_id);
        if (empty($ck))
            $ck = session_get_cookie_params();
        setcookie(session_name(), session_id(), false, $ck['path'], $ck['domain'], $ck['secure']);
        return false;
    }

    return true;
}

function adodb_session_create_table($schemaFile = null, $conn = null) {
    if ($schemaFile === null)
        $schemaFile = ADODB_SESSION . '/session_schema2.xml';
    if ($conn === null)
        $conn = ADODB_Session::_conn();

    if (!$conn)
        return 0;

    $schema = new adoSchema($conn);
    $schema->ParseSchema($schemaFile);
    return $schema->ExecuteSchema();
}

class ADODB_Session {

    static function driver($driver = null) {
        static $_driver = 'mysql';
        static $set = false;

        if (!is_null($driver)) {
            $_driver = trim($driver);
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_DRIVER'])) {
                return $GLOBALS['ADODB_SESSION_DRIVER'];
            }
        }

        return $_driver;
    }

    static function host($host = null) {
        static $_host = 'localhost';
        static $set = false;

        if (!is_null($host)) {
            $_host = trim($host);
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_CONNECT'])) {
                return $GLOBALS['ADODB_SESSION_CONNECT'];
            }
        }

        return $_host;
    }

    static function user($user = null) {
        static $_user = 'root';
        static $set = false;

        if (!is_null($user)) {
            $_user = trim($user);
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_USER'])) {
                return $GLOBALS['ADODB_SESSION_USER'];
            }
        }

        return $_user;
    }

    static function password($password = null) {
        static $_password = '';
        static $set = false;

        if (!is_null($password)) {
            $_password = $password;
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_PWD'])) {
                return $GLOBALS['ADODB_SESSION_PWD'];
            }
        }

        return $_password;
    }

    static function database($database = null) {
        static $_database = '';
        static $set = false;

        if (!is_null($database)) {
            $_database = trim($database);
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_DB'])) {
                return $GLOBALS['ADODB_SESSION_DB'];
            }
        }
        return $_database;
    }

    static function persist($persist = null) {
        static $_persist = true;

        if (!is_null($persist)) {
            $_persist = trim($persist);
        }

        return $_persist;
    }

    static function lifetime($lifetime = null) {
        static $_lifetime;
        static $set = false;

        if (!is_null($lifetime)) {
            $_lifetime = (int) $lifetime;
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESS_LIFE'])) {
                return $GLOBALS['ADODB_SESS_LIFE'];
            }
        }
        if (!$_lifetime) {
            $_lifetime = ini_get('session.gc_maxlifetime');
            if ($_lifetime <= 1) {
                $_lifetime = 1440;
            }
        }

        return $_lifetime;
    }

    static function debug($debug = null) {
        static $_debug = false;
        static $set = false;

        if (!is_null($debug)) {
            $_debug = (bool) $debug;

            $conn = ADODB_Session::_conn();
            if ($conn) {
                
            }
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESS_DEBUG'])) {
                return $GLOBALS['ADODB_SESS_DEBUG'];
            }
        }

        return $_debug;
    }

    static function expireNotify($expire_notify = null) {
        static $_expire_notify;
        static $set = false;

        if (!is_null($expire_notify)) {
            $_expire_notify = $expire_notify;
            $set = true;
        } elseif (!$set) {
            if (isset($GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'])) {
                return $GLOBALS['ADODB_SESSION_EXPIRE_NOTIFY'];
            }
        }

        return $_expire_notify;
    }

    static function table($table = null) {
        static $_table = 'sessions2';
        static $set = false;

        if (!is_null($table)) {
            $_table = trim($table);
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_TBL'])) {
                return $GLOBALS['ADODB_SESSION_TBL'];
            }
        }

        return $_table;
    }

    static function optimize($optimize = null) {
        static $_optimize = false;
        static $set = false;

        if (!is_null($optimize)) {
            $_optimize = (bool) $optimize;
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (defined('ADODB_SESSION_OPTIMIZE')) {
                return true;
            }
        }

        return $_optimize;
    }

    static function syncSeconds($sync_seconds = null) {
        return 0;
    }

    static function clob($clob = null) {
        static $_clob = false;
        static $set = false;

        if (!is_null($clob)) {
            $_clob = strtolower(trim($clob));
            $set = true;
        } elseif (!$set) {
            // backwards compatibility
            if (isset($GLOBALS['ADODB_SESSION_USE_LOBS'])) {
                return $GLOBALS['ADODB_SESSION_USE_LOBS'];
            }
        }

        return $_clob;
    }

    static function dataFieldName($data_field_name = null) {
        return '';
    }

    static function filter($filter = null) {
        static $_filter = array();

        if (!is_null($filter)) {
            if (!is_array($filter)) {
                $filter = array($filter);
            }
            $_filter = $filter;
        }

        return $_filter;
    }

    static function encryptionKey($encryption_key = null) {
        static $_encryption_key = 'CRYPTED ADODB SESSIONS ROCK!';

        if (!is_null($encryption_key)) {
            $_encryption_key = $encryption_key;
        }

        return $_encryption_key;
    }

    static function _conn($conn = null) {
        return isset($GLOBALS['ADODB_SESS_CONN']) ? $GLOBALS['ADODB_SESS_CONN'] : false;
    }

    static function _crc($crc = null) {
        static $_crc = false;

        if (!is_null($crc)) {
            $_crc = $crc;
        }

        return $_crc;
    }

    static function _init() {
        session_module_name('user');
        session_set_save_handler(
                array('ADODB_Session', 'open'), array('ADODB_Session', 'close'), array('ADODB_Session', 'read'), array('ADODB_Session', 'write'), array('ADODB_Session', 'destroy'), array('ADODB_Session', 'gc')
        );
    }

    static function _sessionKey() {
        return crypt(ADODB_Session::encryptionKey(), session_id());
    }

    static function _dumprs(&$rs) {
        $conn = ADODB_Session::_conn();
        $debug = ADODB_Session::debug();

        if (!$conn) {
            return;
        }

        if (!$debug) {
            return;
        }

        if (!$rs) {
            echo "<br />\$rs is null or false<br />\n";
            return;
        }
        if (!is_object($rs)) {
            return;
        }
        $rs = $conn->_rs2rs($rs);

        require_once ADODB_SESSION . '/../tohtml.inc.php';
        rs2html($rs);
        $rs->MoveFirst();
    }

    static function config($driver, $host, $user, $password, $database = false, $options = false) {
        ADODB_Session::driver($driver);
        ADODB_Session::host($host);
        ADODB_Session::user($user);
        ADODB_Session::password($password);
        ADODB_Session::database($database);

        if ($driver == 'oci8' || $driver == 'oci8po')
            $options['lob'] = 'CLOB';

        if (isset($options['table']))
            ADODB_Session::table($options['table']);
        if (isset($options['lob']))
            ADODB_Session::clob($options['lob']);
        if (isset($options['debug']))
            ADODB_Session::debug($options['debug']);
    }

    static function open($save_path, $session_name, $persist = null) {
        $conn = ADODB_Session::_conn();

        if ($conn) {
            return true;
        }

        $database = ADODB_Session::database();
        $debug = ADODB_Session::debug();
        $driver = ADODB_Session::driver();
        $host = ADODB_Session::host();
        $password = ADODB_Session::password();
        $user = ADODB_Session::user();

        if (!is_null($persist)) {
            ADODB_Session::persist($persist);
        } else {
            $persist = ADODB_Session::persist();
        }
        $conn = ADONewConnection($driver);

        if ($debug) {
            $conn->debug = true;
            ADOConnection::outp(" driver=$driver user=$user db=$database ");
        }

        if (empty($conn->_connectionID)) {
            if ($persist) {
                switch ($persist) {
                    default:
                    case 'P': $ok = $conn->PConnect($host, $user, $password, $database);
                        break;
                    case 'C': $ok = $conn->Connect($host, $user, $password, $database);
                        break;
                    case 'N': $ok = $conn->NConnect($host, $user, $password, $database);
                        break;
                }
            } else {
                $ok = $conn->Connect($host, $user, $password, $database);
            }
        }

        if ($ok)
            $GLOBALS['ADODB_SESS_CONN'] = $conn;
        else
            ADOConnection::outp('<p>Session: connection failed</p>', false);


        return $ok;
    }

    static function close() {
        return true;
    }

    static function read($key) {
        $conn = ADODB_Session::_conn();
        $filter = ADODB_Session::filter();
        $table = ADODB_Session::table();

        if (!$conn) {
            return '';
        }
        $binary = $conn->dataProvider === 'mysql' ? '/*! BINARY */' : '';

        global $ADODB_SESSION_SELECT_FIELDS;
        if (!isset($ADODB_SESSION_SELECT_FIELDS))
            $ADODB_SESSION_SELECT_FIELDS = 'sessdata';
        $sql = "SELECT $ADODB_SESSION_SELECT_FIELDS FROM $table WHERE sesskey = $binary " . $conn->Param(0) . " AND expiry >= " . $conn->sysTimeStamp;
        $rs = $conn->Execute($sql, array($key));
        if ($rs) {
            if ($rs->EOF) {
                $v = '';
            } else {
                $v = reset($rs->fields);
                $filter = array_reverse($filter);
                foreach ($filter as $f) {
                    if (is_object($f)) {
                        $v = $f->read($v, ADODB_Session::_sessionKey());
                    }
                }
                $v = rawurldecode($v);
            }

            $rs->Close();

            ADODB_Session::_crc(strlen($v) . crc32($v));
            return $v;
        }

        return '';
    }

    static function write($key, $oval) {
        global $ADODB_SESSION_READONLY;

        if (!empty($ADODB_SESSION_READONLY))
            return;

        $clob = ADODB_Session::clob();
        $conn = ADODB_Session::_conn();
        $crc = ADODB_Session::_crc();
        $debug = ADODB_Session::debug();
        $driver = ADODB_Session::driver();
        $expire_notify = ADODB_Session::expireNotify();
        $filter = ADODB_Session::filter();
        $lifetime = ADODB_Session::lifetime();
        $table = ADODB_Session::table();

        if (!$conn) {
            return false;
        }
        if ($debug)
            $conn->debug = 1;
        $sysTimeStamp = $conn->sysTimeStamp;
        $expiry = $conn->OffsetDate($lifetime / (24 * 3600), $sysTimeStamp);

        $binary = $conn->dataProvider === 'mysql' ? '/*! BINARY */' : '';
        if ($crc !== false && $crc == (strlen($oval) . crc32($oval))) {
            if ($debug) {
                echo '<p>Session: Only updating date - crc32 not changed</p>';
            }

            $expirevar = '';
            if ($expire_notify) {
                $var = reset($expire_notify);
                global $$var;
                if (isset($$var)) {
                    $expirevar = $$var;
                }
            }


            $sql = "UPDATE $table SET expiry = $expiry ,expireref=" . $conn->Param('0') . ", modified = $sysTimeStamp WHERE $binary sesskey = " . $conn->Param('1') . " AND expiry >= $sysTimeStamp";
            $rs = $conn->Execute($sql, array($expirevar, $key));
            return true;
        }
        $val = rawurlencode($oval);
        foreach ($filter as $f) {
            if (is_object($f)) {
                $val = $f->write($val, ADODB_Session::_sessionKey());
            }
        }

        $expireref = '';
        if ($expire_notify) {
            $var = reset($expire_notify);
            global $$var;
            if (isset($$var)) {
                $expireref = $$var;
            }
        }

        if (!$clob) {
            $rs = $conn->Execute("SELECT COUNT(*) AS cnt FROM $table WHERE $binary sesskey = " . $conn->Param(0), array($key));
            if ($rs)
                $rs->Close();

            if ($rs && reset($rs->fields) > 0) {
                $sql = "UPDATE $table SET expiry=$expiry, sessdata=" . $conn->Param(0) . ", expireref= " . $conn->Param(1) . ",modified=$sysTimeStamp WHERE sesskey = " . $conn->Param(2);
            } else {
                $sql = "INSERT INTO $table (expiry, sessdata, expireref, sesskey, created, modified) 
					VALUES ($expiry," . $conn->Param('0') . ", " . $conn->Param('1') . ", " . $conn->Param('2') . ", $sysTimeStamp, $sysTimeStamp)";
            }


            $rs = $conn->Execute($sql, array($val, $expireref, $key));
        } else {
            switch ($driver) {
                case 'oracle':
                case 'oci8':
                case 'oci8po':
                case 'oci805':
                    $lob_value = sprintf('empty_%s()', strtolower($clob));
                    break;
                default:
                    $lob_value = 'null';
                    break;
            }

            $conn->StartTrans();

            $rs = $conn->Execute("SELECT COUNT(*) AS cnt FROM $table WHERE $binary sesskey = " . $conn->Param(0), array($key));

            if ($rs && reset($rs->fields) > 0) {
                $sql = "UPDATE $table SET expiry=$expiry, sessdata=$lob_value, expireref= " . $conn->Param(0) . ",modified=$sysTimeStamp WHERE sesskey = " . $conn->Param('1');
            } else {
                $sql = "INSERT INTO $table (expiry, sessdata, expireref, sesskey, created, modified) 
					VALUES ($expiry,$lob_value, " . $conn->Param('0') . ", " . $conn->Param('1') . ", $sysTimeStamp, $sysTimeStamp)";
            }

            $rs = $conn->Execute($sql, array($expireref, $key));

            $qkey = $conn->qstr($key);
            $rs2 = $conn->UpdateBlob($table, 'sessdata', $val, " sesskey=$qkey", strtoupper($clob));
            if ($debug)
                echo "<hr>", htmlspecialchars($oval), "<hr>";
            $rs = @$conn->CompleteTrans();
        }

        if (!$rs) {
            ADOConnection::outp('<p>Session Replace: ' . $conn->ErrorMsg() . '</p>', false);
            return false;
        } else {
            if ($conn->databaseType == 'access') {
                $sql = "SELECT sesskey FROM $table WHERE $binary sesskey = $qkey";
                $rs = $conn->Execute($sql);
                ADODB_Session::_dumprs($rs);
                if ($rs) {
                    $rs->Close();
                }
            }
        }
        return $rs ? true : false;
    }

    static function destroy($key) {
        $conn = ADODB_Session::_conn();
        $table = ADODB_Session::table();
        $expire_notify = ADODB_Session::expireNotify();

        if (!$conn) {
            return false;
        }
        $debug = ADODB_Session::debug();
        if ($debug)
            $conn->debug = 1;
        $qkey = $conn->quote($key);
        $binary = $conn->dataProvider === 'mysql' ? '/*! BINARY */' : '';

        if ($expire_notify) {
            reset($expire_notify);
            $fn = next($expire_notify);
            $savem = $conn->SetFetchMode(ADODB_FETCH_NUM);
            $sql = "SELECT expireref, sesskey FROM $table WHERE $binary sesskey = $qkey";
            $rs = $conn->Execute($sql);
            ADODB_Session::_dumprs($rs);
            $conn->SetFetchMode($savem);
            if (!$rs) {
                return false;
            }
            if (!$rs->EOF) {
                $ref = $rs->fields[0];
                $key = $rs->fields[1];
                $fn($ref, $key);
            }
            $rs->Close();
        }

        $sql = "DELETE FROM $table WHERE $binary sesskey = $qkey";
        $rs = $conn->Execute($sql);
        if ($rs) {
            $rs->Close();
        }

        return $rs ? true : false;
    }

    static function gc($maxlifetime) {
        $conn = ADODB_Session::_conn();
        $debug = ADODB_Session::debug();
        $expire_notify = ADODB_Session::expireNotify();
        $optimize = ADODB_Session::optimize();
        $table = ADODB_Session::table();

        if (!$conn) {
            return false;
        }


        $debug = ADODB_Session::debug();
        if ($debug) {
            $conn->debug = 1;
            $COMMITNUM = 2;
        } else {
            $COMMITNUM = 20;
        }
        $time = $conn->OffsetDate(-$maxlifetime / 24 / 3600, $conn->sysTimeStamp);
        $binary = $conn->dataProvider === 'mysql' ? '/*! BINARY */' : '';

        if ($expire_notify) {
            reset($expire_notify);
            $fn = next($expire_notify);
        } else {
            $fn = false;
        }

        $savem = $conn->SetFetchMode(ADODB_FETCH_NUM);
        $sql = "SELECT expireref, sesskey FROM $table WHERE expiry < $time ORDER BY 2"; # add order by to prevent deadlock
        $rs = $conn->SelectLimit($sql, 1000);
        if ($debug)
            ADODB_Session::_dumprs($rs);
        $conn->SetFetchMode($savem);
        if ($rs) {
            $tr = $conn->hasTransactions;
            if ($tr)
                $conn->BeginTrans();
            $keys = array();
            $ccnt = 0;
            while (!$rs->EOF) {
                $ref = $rs->fields[0];
                $key = $rs->fields[1];
                if ($fn)
                    $fn($ref, $key);
                $del = $conn->Execute("DELETE FROM $table WHERE sesskey=" . $conn->Param('0'), array($key));
                $rs->MoveNext();
                $ccnt += 1;
                if ($tr && $ccnt % $COMMITNUM == 0) {
                    if ($debug)
                        echo "Commit<br>\n";
                    $conn->CommitTrans();
                    $conn->BeginTrans();
                }
            }
            $rs->Close();

            if ($tr)
                $conn->CommitTrans();
        }
        if ($optimize) {
            $driver = ADODB_Session::driver();

            if (preg_match('/mysql/i', $driver)) {
                $sql = "OPTIMIZE TABLE $table";
            }
            if (preg_match('/postgres/i', $driver)) {
                $sql = "VACUUM $table";
            }
            if (!empty($sql)) {
                $conn->Execute($sql);
            }
        }


        return true;
    }

}

ADODB_Session::_init();
if (empty($ADODB_SESSION_READONLY))
    register_shutdown_function('session_write_close');

function adodb_sess_open($save_path, $session_name, $persist = true) {
    return ADODB_Session::open($save_path, $session_name, $persist);
}

function adodb_sess_gc($t) {
    return ADODB_Session::gc($t);
}

?>