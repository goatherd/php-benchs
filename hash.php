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

$loops = 10000;
require_once './includes/big_string.php';

$algos = hash_algos($algos);
$count = count($algos);

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 0; $i < $count; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL; 


} else {
    
    switch ($_SERVER['argv'][1]) {
        
        default:
            $alg = $algos[$_SERVER['argv'][1]];
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = hash($alg , $str);
            }
            $return = array( 
                strlen($v) . ' bytes (' . $alg  . ')',  
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
$len = strlen($str);
echo  <<<head

hash algos benchmark on string ($len bytes) in PHP v$php

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