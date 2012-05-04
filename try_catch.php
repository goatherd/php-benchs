<?php
/**
 * Benchmark tests for calling switch vs if constructs
 * Usage: php -q try_catch.php
 */

if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

require_once './includes/exception.php';

$loops = 100000;
$str='a';
$a='a';
$b='b';

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 1; $i <= 2; $i++) {
        $cmd = $_SERVER['_'] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
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

                try {
                    throwException();
                } catch (Exception $e) {
                    if ($e instanceof Exception) {
                        $v = time();
                    } else {
                        $v = time();
                    }
                }

            }
            $return = array(
                'catch { if else }',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {

                try {
                    throwOtherException();
                } catch (OtherException $e) {
                    $v = time();
                } catch (Exception $e) {
                    $v = time();
                }

            }
            $return = array(
                'try catch catch',
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

SWITCH vs. IF tests in PHP v$php

+---------------------------------------------+-------+----------+---------+
|                description                  |  sec  |  memory  |    %    |
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
