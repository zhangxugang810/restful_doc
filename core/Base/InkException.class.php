<?php
/**
 * 异常类型：
 * 第一级分类（1位）
 * 1：框架级错误提示
 * 2: 系统级错误提示
 * 3: 用户级错误提示
 *
 * ****************第二级分类 - 框架级异常编码（4位）********************
 * 
 * 1001：核心类异常
 * 1002：Model异常
 * 1003：Template异常
 * 1004：路由异常
 * 1005：APP异常
 * 1006：控制器异常
 * 1007：缓存处理异常
 * 1008：核心配置异常
 * 1009：APP配置异常
 * 1010：系统函数异常
 * 1011：预处理异常
 * 1012：请求异常
 * 1013：文件操作异常
 * 1014：文件操作异常
 * 1015：分页异常
 * 1016：邮件操作异常
 * 1017：COOKIE和SESSION操作异常
 * 1017：验证码异常
 * 1017：XML异常
 * 
 * *****************第二级分类 - 系统级异常编码*******************
 * 
 * 2001：
 * 2002：
 * 2003：
 * 2004：
 * 2005：
 * 
 * *****************第二级分类 - 用户级异常编码*******************
 * 
 * 3001：
 * 3002：
 * 3003：
 * 3004：
 * 
 * *****************第三级分类 - 接口机异常编码*******************
 * 
 * 4001：
 * 4002：
 * 4003：
 * 4004：
 * 
 * *****************第三级别 - 异常位置编码***********************
 * 从1001开始，到9999结束，程序位置编码不允许大于9999；
 * 
 * 编码实例：核心类中抛出一个异常，那么他的异常编码应该是：11001xxxx，建议对自己已使用的要放在异常仓库中，方便查询。
 * 另外需要注意的是，异常编码必须唯一，不允许重复使用异常编码，剩余未使用异常编码可以在用户中使用，也可以自己单独规定到某一个指定的系统或者接口类的异常中。
 */
class InkException extends Exception {
    public static function systemError($message, $show = true, $save = true, $halt = true) {
        list($showTrace, $logTrace) = self::debugBacktrace();
        if ($save) {
            $messageSave = '' . $message . ' PHP: ' . $logTrace;
            self::writeErrorLog($messageSave);
        }
        if ($show) {
            self::showError("<li>$message</li>", $showTrace, 100000000);
        }
        if ($halt) {
            exit();
        } else {
            return $message;
        }
    }

    /**
     * @see 代码执行过程回溯信息
     * @static
     * @access public
     */
    public static function debugBacktrace() {
        $skipFunc[] = 'InkException->debugBacktrace';
        $show = $log = '';
        $debugBacktrace = debug_backtrace();
        ksort($debugBacktrace);
        foreach ($debugBacktrace as $k => $error) {
            if (!isset($error['file'])) {
                try {
                    if (isset($error['class'])) {
                        $reflection = new ReflectionMethod($error['class'], $error['function']);
                    } else {
                        $reflection = new ReflectionFunction($error['function']);
                    }
                    $error['file'] = $reflection->getFileName();
                    $error['line'] = $reflection->getStartLine();
                } catch (Exception $e) {
                    continue;
                }
            }
            $file = str_replace(__SERVER_PATH__, '', $error['file']);
            $func = isset($error['class']) ? $error['class'] : '';
            $func .= isset($error['type']) ? $error['type'] : '';
            $func .= isset($error['function']) ? $error['function'] : '';
            if (in_array($func, $skipFunc)) {
                break;
            }
            $error['line'] = sprintf('%04d', $error['line']);
            $show .= '<li>[Line: ' . $error['line'] . ']' . $file . '(' . $func . ')</li>';
            $log .=!empty($log) ? ' -> ' : '';
            $log .= $file . ':' . $error['line'];
        }
        return array($show, $log);
    }

    /**
     * 异常处理
     * @static
     * @access public
     * @param mixed $exception
     */
    public static function exceptionError($exception) {
//        print_r($exception);
        $errorMsg = $exception->getMessage();
        $code = $exception->getCode();
        $trace = $exception->getTrace();
        krsort($trace);
        $trace[] = array('file' => $exception->getFile(), 'line' => $exception->getLine(), 'function' => 'break');
        $phpMsg = array();
        foreach ($trace as $error) {
            if (!empty($error['function'])) {
                $fun = '';
                if (!empty($error['class'])) {
                    $fun .= $error['class'] . $error['type'];
                }
                $fun .= $error['function'] . '(';
                if (!empty($error['args'])) {
                    $mark = '';
                    foreach ($error['args'] as $arg) {
                        $fun .= $mark;
                        if (is_array($arg)) {
                            $fun .= 'Array';
                        } elseif (is_bool($arg)) {
                            $fun .= $arg ? 'true' : 'false';
                        } elseif (is_int($arg)) {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? $arg : '%d';
                        } elseif (is_float($arg)) {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? $arg : '%f';
                        } else {
                            $fun .= (defined('SITE_DEBUG') && SITE_DEBUG) ? '\'' . htmlspecialchars(substr(self::clear($arg), 0, 10)) . (strlen($arg) > 10 ? ' ...' : '') . '\'' : '%s';
                        }
                        $mark = ', ';
                    }
                }
                $fun .= ')';
                $error['function'] = $fun;
            }
            if (!isset($error['line'])) {
                continue;
            }
            $phpMsg[] = array('file' => str_replace(array(__SERVER_PATH__, '\\'), array('', '/'), $error['file']), 'line' => $error['line'], 'function' => $error['function']);
        }
        self::showError($errorMsg, $phpMsg, $code);
        exit();
    }

    /**
     * 记录错误日志
     * @static
     * @access public
     * @param string $message
     */
    public static function writeErrorLog($message) {
        return false; // 暂时不写入
        $message = self::clear($message);
        $time = time();
        if(!file_exists(__LOG_PATH__)){
            @mkdir(__LOG_PATH__, 0777);
        }
        $file = __LOG_PATH__ . '/' . date('Y.m.d') . '_error.log';
        $hash = md5($message);
        $userId = 0;
        $ip = get_client_ip();
        $user = '<b>User:</b> userId=' . intval($userId) . '; IP=' . $ip . '; RIP:' . $_SERVER['REMOTE_ADDR'];
        $uri = 'Request: ' . htmlspecialchars(self::clear($_SERVER['REQUEST_URI']));
        $message = '['.date('Y-m-d H:i:s', $time).'] '.$message.' - '.$uri."\n";
        if (is_file($file)) {
            $fp = @fopen($file, 'rb');
            $lastlen = 50000;
            $maxtime = 60 * 10;
            $offset = filesize($file) - $lastlen;
            if ($offset > 0) {
                fseek($fp, $offset);
            }
            if ($data = fread($fp, $lastlen)) {
                $array = explode("\n", $data);
                if (is_array($array))
                    foreach ($array as $key => $val) {
                        $row = explode("\t", $val);
                        if ($row[0] != '<?php exit;?>') {
                            continue;
                        }
                        if ($row[3] == $hash && ($row[1] > $time - $maxtime)) {
                            return;
                        }
                    }
            }
        }
        error_log($message, 3, $file);
    }

    /**
     * 清除文本部分字符
     * @param string $message
     */
    public static function clear($message) {return str_replace(array("\t", "\r", "\n"), " ", $message);}

    /**
     * sql语句字符清理
     * @static
     * @access public
     * @param string $message
     * @param string $dbConfig
     */
    public static function sqlClear($message, $dbConfig) {
        $message = self::clear($message);
        if (!(defined('SITE_DEBUG') && SITE_DEBUG)) {
            $message = str_replace($dbConfig['database'], '***', $message);
            $message = str_replace(C('DB_PREFIX'), '***', $message);
        }
        $message = htmlspecialchars($message);
        return $message;
    }
    
    private static function getTitle($code){
        $c = (string)$code;
        if($c[0] === '1'){
            $title = L('inkphp_exception');//inkphp指定好的编号，2,3,4,5位用来判断框架的哪一类异常，6,7,8,9为用来标识是那个异常
        }elseif($c[0] === '2'){
            $title = L('inkcms_exception');//inkcms指定好的编号，2,3,4,5位判断cms的那一个模块异常，6,7,8,9位用来标识是那个异常
        }elseif($c[0] === '3'){
            $title = L('program_exception');//程序员自定义的异常，指定使用2,3,4,5做为模块的异常类，6,7,8,9位用来标识是那个异常
        }
        return $title;
    }

    /**
     * 显示错误
     * @static
     * @access public
     * @param string $errorMsg
     * @param string $phpMsg
     */
    public static function showError($errorMsg, $phpMsg = '', $code = 0) {
        global $_G;
        $errorMsg = str_replace(__SERVER_PATH__, '', $errorMsg);
        ob_end_clean();
        $host = $_SERVER['HTTP_HOST'];
        $title = self::getTitle($code);
        echo <<<EOT
<!DOCTYPE html>
<html>
<head>
	<title>$title</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE" />
EOT;
        S('sys_exception');
        S('sys_jquery', 'js');
        S('sys_exception', 'js');
        echo <<<EOT
</head>
<body>
<h1 class="title">$title</h1>
<div class="exceptionBody">
<div class='info'>
EOT;
        if (!empty($code)) {
            echo '<div class="codeLine">' . L('exception_no') . '：<a href="' . __OFFICIAL_WEBSITE__ . '/error/code/' . $code . '.html" target="_blank">' . $code . '</a></div>';
        }
        if (__DEBUG__) {
            echo '<div class="codeMsg">' . L('exception_info') . '：' . $errorMsg . '</div>';
        }
        echo <<<EOT
</div>
EOT;
        if (__DEBUG__) {
            if (!empty($phpMsg)) {
                echo '<div class="info">';
                echo '<p><strong>' . L('ink_debug') . '</strong></p>';
                echo '<table cellpadding="0" cellspacing="0" border="0" width="100%" class="table"><thead>';
                if (is_array($phpMsg)) {
                    echo '<tr class="bg2"><th>' . L('exception_number') . '</th><th>' . L('exception_file') . '</th><th>' . L('exception_line') . '</th><th>' . L('exception_position') . '</th></tr><thead><tbody>';
                    foreach ($phpMsg as $k => $msg) {
                        $k++;
                        echo '<tr class="bg1">';
                        echo '<td class="center">' . $k . '</td>';
                        echo '<td>' . str_replace(__SERVER_PATH__, '', $msg['file']) . '</td>';
                        echo '<td class="center">' . $msg['line'] . '</td>';
                        echo '<td>' . ($msg['function'] == 'break()' ? L('throw_exception') : $msg['function']) . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td><ul>' . $phpMsg . '</ul></td></tr>';
                }
                echo '</tbody></table></div>';
            }
        }
        echo <<<EOT
    </div>
</body>
</html>
EOT;
        exit();
    }
}