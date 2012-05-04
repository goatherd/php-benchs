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

$loops = 2000000;

$str = dirname(__FILE__);
$str .= "/../../../../../etc/passwd\0";
$str .= '/expr.php';

//echo realpath($str) . "\n\n";
//echo substr($str, (int)strpos($str, "\0")) . "\n\n";
//exit;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 0; $i <= 1; $i++) {
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
                $v = str_replace("\0", '', $str);
            }
            $return = array(
                'str_replace(..., "", str)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = substr($str, (int)strpos($str, "\0"));
            }
            $return = array(
                'substr(str, strpos(str ...))',
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


benchmark for replacement of \\x0 (null byte poisen) in PHP v$php

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