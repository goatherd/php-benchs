<?php
/** 
 * Usage: php -q <file>
 */
 
if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

class  test {
    public static function meth1($args = null)
    {
        $v = $args;
        return $v;
    }
    public function meth2($args = null)
    {
        $v = $args;
        return $v;
    }   
}

function userFunction($args = null)
{
    $v = $args;
    return $v;
}

$loops = 100000;
$closure = function($args = null) {$v = $args;return $v;};
$strUserFunction = 'userFunction';
$strStaticMethod = 'test::meth1';
$arrStaticMethod = array('test','meth1');
$meth1 = 'meth1';
$meth2 = 'meth2';
$class = new test();
$arrObjectMethod = array($class,'meth2');

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();
    
    echo 'Running bench:';
    for($i = 0; $i <= 12; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL; 

} else {
    
    switch ($_SERVER['argv'][1]) {
        
        case '0':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = userFunction();
            }
            $return = array(
                'userFunction()',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
    
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = $strUserFunction();
            }
            $return = array(
                '$strUserFunction()',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func($strUserFunction);
                
            }
            $return = array(
                'call_user_func($strUserFunction)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            $reg = '/((?<!\\\)[?])/';
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func_array($strUserFunction,array());
            }
            $return = array(
                'call_user_func_array($strUserFunction,...)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func($strStaticMethod);
            }
            $return = array(
                'call_user_func($strStaticMethod)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            $reg = '/((?<!\\\)[?])/';
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func_array($strStaticMethod,array());
            }
            $return = array(
                'call_user_func_array($strStaticMethod, ...)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func($arrStaticMethod);
            }
            $return = array(
                'call_user_func($arrStaticMethod)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '7':
            $m = memory_get_usage();
            $s = microtime(1);
            $reg = '/((?<!\\\)[?])/';
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func_array($arrStaticMethod,array());
            }
            $return = array(
                'call_user_func_array($arrStaticMethod, ...)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '8':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func($arrObjectMethod);
            }
            $return = array(
                'call_user_func($arrObjectMethod)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '9':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = call_user_func_array($arrObjectMethod,array());
            }
            $return = array(
                'call_user_func_array($arrObjectMethod, ...)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '10':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = $class->$meth2();
            }
            $return = array(
                '$class->$meth2()',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '11':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = test::$meth1();
            }
            $return = array(
                'test::$meth1()',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
		
		case '12':
	            $m = memory_get_usage();
	            $s = microtime(1);
	            for($i = 0; $i < $loops; $i++) {
	                $v = $closure();
	            }
	            $return = array(
	                '$closure()',
	                (microtime(1)-$s),
	                (memory_get_usage()-$m)
	            );
	            echo serialize($return);
	            break;
    }
 
    exit;   
}




$php = PHP_VERSION;
$type = gettype($elem);
echo  <<<head

benchmark for calling of userspace functions / methods in PHP v$php

class  test {
    public static function meth1(\$args = null);
    public function meth2(\$args = null); 
}

function userFunction(\$args = null);

\$strUserFunction = 'userFunction';
\$strStaticMethod = 'test::meth1';
\$arrStaticMethod = array('test','meth1');
\$class = new test();
\$meth1 = 'meth1';
\$meth2 = 'meth2';
\$arrObjectMethod = array(\$class,'meth2');

+---------------------------------------------+-------+----------+---------+
|                 description                 |  sec  |  memory  |    %    |
+---------------------------------------------+-------+----------+---------+

head;



asort($secs);
$i = 0;
foreach ($secs as $k => $v) {
    if($i == 0) {
        $best = $results[$k][1];
        $results[$k][3] = '100%';
    } else {
        $results[$k][3] = round(($results[$k][1] * 100) / $best) . '%';
    }
    echo sprintf(
        "| %-43s | %-5s | %-8s | %-7s |\n+%'-45s+%'-7s+%'-10s+%'-9s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";