<?php
/** 
 * Benchmark tests for reading of file
 * Usage: php -q array_lookup.php
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
    for($i = 1; $i <= 2; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL;

} else {
    
    $array = array(array(array(array())));
    for($i = 0; $i < 10; $i++) {
        $array[0][0][0][$i] = $i;
    }

    
    switch ($_SERVER['argv'][1]) {
        
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = $array[0][0][0][0];
            }
            $return = array(
                '$v = array[a][b][c][d];',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            $arr =& $array[0][0][0];
            for($i = 0; $i < $loops; $i++) {
                $v = $arr[0];
            }
            $return = array(
                '$ref=&array[a][b][c]; $v=$ref[d];',
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

access multidimensional array element in PHP v$php

+-------------------------------------+-------+----------+---------+
|            description              |  sec  |  memory  |    %    |
+-------------------------------------+-------+----------+---------+

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
        "| %-35s | %-5s | %-8s | %-7s |\n+%'-37s+%'-7s+%'-10s+%'-9s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";
