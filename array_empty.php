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


$loops = 2000000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 1; $i <= 8; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL;

} else {

    switch ($_SERVER['argv'][1]) {

        /**
         * Array $a in expression $a? will be converted to boolean
         */
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array();
            for($i = 0; $i < $loops; $i++) {
                $v=$a?true:false;
            }
            $return = array(
                '$a=array(); $v=$a?true:false; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * The return value of count($a) will be converted to boolean
         */
        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array();
            for($i = 0; $i < $loops; $i++) {
                $v=count($a)?true:false;
            }
            $return = array(
                '$a=array(); $v=count($a)?true:false; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * no conversion
         */
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array();
            for($i = 0; $i < $loops; $i++) {
                $v=count($a)>0;
            }
            $return = array(
                '$a=array(); $v=count($a)>0; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * Array $a in expression $a? will be converted to boolean
         */
        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array(1);
            for($i = 0; $i < $loops; $i++) {
                $v=$a?true:false;
            }
            $return = array(
                '$a=array(1); $v=$a?true:false; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * Return value of count($a) will be converted in boolean
         */
        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array(1);
            for($i = 0; $i < $loops; $i++) {
                $v=count($a)?true:false;
            }
            $return = array(
                '$a=array(1); $v=count($a)>0?true:false; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * no conversion
         */
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array(1);
            for($i = 0; $i < $loops; $i++) {
                $v=count($a)>0;
            }
            $return = array(
                '$a=array(1); $v=count($a)>0; (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        /**
         * 
         */
        case '7':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array(1);
            for($i = 0; $i < $loops; $i++) {
                $v=!empty($a);
            }
            $return = array(
                '$a=array(1); $v=!empty($a); (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '8':
            $m = memory_get_usage();
            $s = microtime(1);
            $a = array();
            for($i = 0; $i < $loops; $i++) {
                $v=!empty($a);
            }
            $return = array(
                '$a=array(); $v=!empty($a); (' . var_export($v, true) . ')',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
    }
    exit;
}


$php = PHP_VERSION;
echo  <<<head


compare different ways for checking if given array is empty in PHP v$php

+-------------------------------------------------+-------+----------+---------+
|            description                          |  sec  |  memory  |    %    |
+-------------------------------------------------+-------+----------+---------+

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
        "| %-47s | %-5s | %-8s | %-7s |\n+%'-49s+%'-7s+%'-10s+%'-9s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";
