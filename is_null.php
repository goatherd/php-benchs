<?php
/**
 * Benchmark tests for reading of file
 * Usage: php -q <FILE>
 */

if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

$loops = 5000000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:'; 
    for($i = 1; $i <= 6; $i++) {
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
            $var=null;
            for($i = 0; $i < $loops; $i++) {
                $v=is_null($var);
            }
            $return = array(
                '$var=null; $v=is_null($var);',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            $var=null;
            for($i = 0; $i < $loops; $i++) {
                $v=($a==null);
            }
            $return = array(
                '$var=null; $v=($a==null);',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            $var=null;
            for($i = 0; $i < $loops; $i++) {
                $v=($a===null);
            }
            $return = array(
                '$var=null; $v=($a===null);',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            $var='string';
            for($i = 0; $i < $loops; $i++) {
                $v=is_null($var);
            }
            $return = array(
                '$var="string"; $v=is_null($var);',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            $var='string';
            for($i = 0; $i < $loops; $i++) {
                $v=($a==null);
            }
            $return = array(
                '$var="string"; $v=($a==null);',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            $var='string';
            for($i = 0; $i < $loops; $i++) {
                $v=($a===null);
            }
            $return = array(
                '$var="string"; $v=($a===null);',
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


compare is_null(\$var) vs \$var === null  in PHP v$php

+-----------------------------------+-------+----------+---------+
|            description            |  sec  |  memory  |    %    |
+-----------------------------------+-------+----------+---------+

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
        "| %-33s | %-5s | %-8s | %-7s |\n+%'-35s+%'-7s+%'-10s+%'-9s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";
