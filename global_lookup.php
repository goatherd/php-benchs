<?php
/**
 * Benchmark tests for accessing of variable within global scope
 * Usage: php -q global_lookup.php
 */

if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}


$loops = 500000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 1; $i <= 3; $i++) {
        $cmd = $_SERVER[_] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL; 

} else {

    $var = array(
        'foo' => 'bar'
    );

    function getFoo1()
    {
        global $var;
        return $var['foo'];
    }

    function getFoo2()
    {
        return $GLOBALS['var']['foo'];
    }

    function getFoo3()
    {
        $var = $GLOBALS['var'];
        return $var['foo'];
    }


    switch ($_SERVER['argv'][1]) {

        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = getFoo1();
            }
            $return = array(
                'global $var;$v=$var[\'foo\'];',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = getFoo2();
            }
            $return = array(
                '$v=$GLOBALS[\'var\'][\'foo\'];',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = getFoo3();
            }
            $return = array(
                '$var=$GLOBALS[\'var\'];$v=$var[\'foo\'];',
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


access variable from global scope in PHP v$php

+----------------------------------------+-------+----------+---------+
|            description                 |  sec  |  memory  |    %    |
+----------------------------------------+-------+----------+---------+

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
        "| %-38s | %-5s | %-8s | %-7s |\n+%'-40s+%'-7s+%'-10s+%'-9s+\n",
        $results[$k][0],
        round($results[$k][1],3),
        $results[$k][2],
        $results[$k][3],
        '','','',''
    );
    $i++;
}

echo "\n\n";
