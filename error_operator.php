<?php
/** 
 * Benchmark tests for using error operator
 * Usage: php -q error_operator.php
 */
 
if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

$loops = 100000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();
        
    echo 'Running bench:';  
    for($i = 1; $i < 7; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL; 

} else {
    
    switch ($_SERVER['argv'][1]) {
        
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $r = @ fopen('/tmp/nothing','r');
            }
            $return = array(
                '(error) using @',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $r = @ fopen(__FILE__,'r');
                fclose($r);
            }
            $return = array(
                '(non-error) using @',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            $old = error_reporting(0);
            for($i = 0; $i < $loops; $i++) {
                $r = fopen('/tmp/nothing','r');
            }
            error_reporting($old );
            $return = array(
                '(error) E_NONE global',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            $old = error_reporting(0);
            for($i = 0; $i < $loops; $i++) {
                $r = fopen(__FILE__,'r');
                fclose($r);
            }
            error_reporting($old );
            $return = array(
                '(non-error) E_NONE global',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $old = error_reporting(0);
                $r = fopen('/tmp/nothing','r');
                error_reporting($old );
            }
            $return = array(
                '(error) E_NONE local',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $old = error_reporting(0);
                $r = fopen(__FILE__,'r');
                fclose($r);
                error_reporting($old );
            }
            $return = array(
                '(non-error) E_NONE local',
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

error operator (@) vs error_reporting(0) in PHP v$php
(error) - this tests produce an error
(non-error) - this tests produce no error

+-----------------------------------+-------+----------+-------+
|            description            |  sec  |  memory  |   %   |
+-----------------------------------+-------+----------+-------+

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
        "| %-33s | %-5s | %-8s | %-5s |\n+%'-35s+%'-7s+%'-10s+%'-7s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";

