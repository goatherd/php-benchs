<?php
/** 
 * Benchmark tests for array looping
 * Usage: php -q array_loop.php [type]
 * where [type] is one of the following:
 *   s  - scalar value, test looping through 
 *        array with scalar elements 
 *   a  - array value, test looping through 
 *        array with array elements 
 *   o  - object value, test looping through 
 *        array with object elements 
 */
 
if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

$num = 200000;

if($_SERVER['argc'] == 1) {
    $_SERVER['argv'][] = 's';
    $_SERVER['argc'] = 2;
}

if($_SERVER['argc'] == 2) {
    
    
    
    $secs = array();
    $results = array();
    
    echo 'Running bench:';
    for($i = 1; $i <= 8; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i . ' ' . $_SERVER['argv'][1];
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL;


} else {

    switch($_SERVER['argv'][2]) {
        case 'a':
            $elem = array('a' => 1,'b' => 2, 'c' => 3,'d' => 4);
            break;
        case 'o':
            $elem = (object)array('a' => 1,'b' => 2, 'c' => 3,'d' => 4);
            break;
        default:
            $elem = str_repeat('a',128);
    }

    $array = array_fill(0,$num, is_object($elem) ? clone $elem : $elem);


    switch ($_SERVER['argv'][1]) {
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            for($i = 0; $i < $num; $i++) {
                $v = $array[$i];
            }
            $return = array(
                'for($i = 0; $i < $num; $i++)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
         case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            while(list(,$v) = each($array)) {
                continue;
            }
            $return = array(
                'while(list(,$v) = each($array))',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            while(list($k,$v) = each($array)) {
                continue;
            }
            $return = array(
                'while(list($k,$v) = each($array))',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            foreach($array as $v) {
                continue;
            }
            $return = array(
                'foreach($array as $v)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            foreach($array as $k => $v) {
                continue;
            }
            $return = array(
                'foreach($array as $k => $v)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            foreach($array as &$v) {
                continue;
            }
            $return = array(
                'foreach($array as &$v)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '7':
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            foreach($array as $k => &$v) {
                continue;
            }
            $return = array(
                'foreach($array as $k => &$v)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '8':
            function callback(&$item, $index) {}
            $m = memory_get_usage();
            $s = microtime(1);
            reset($array);
            array_walk($array, 'callback');
            $return = array(
                'array_walk($array, callback)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
    }
    exit;
}



$php = PHP_VERSION;
switch ($_SERVER['argv'][1]) {
    case 'a':
        $type = 'array';
        break;
    case 'o':
        $type = 'object';
        break;
    default:
        $type = 'string';
        break;
}
echo  <<<head

Looping array with $num $type elements in PHP v$php

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
