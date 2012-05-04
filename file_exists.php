<?php
/** 
 * Benchmark tests for calling file_exists() vs is_file
 * Usage: php -q file_exists.php
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
        
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = file_exists( 'includes/big_string.php' );
            }
            $return = array(
                '1. file_exists("./existing/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '2':
            $path = dirname(__FILE__) . '/';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = file_exists( $path . 'includes/big_string.php' );
            }
            $return = array(
                '2. file_exists("/existing/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = is_file( 'includes/big_string.php' );
            }
            $return = array(
                '3. is_file("./existing/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '4':
            $path = dirname(__FILE__) . '/';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = is_file( $path . 'includes/big_string.php' );
            }
            $return = array(
                '4. is_file("/existing/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = file_exists( 'not/exists' );
            }
            $return = array(
                '5. file_exists("./nonexistant/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = file_exists( '/not/exists' );
            }
            $return = array(
                '6. file_exists("/nonexistant/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '7':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = is_file( 'not/exists' );
            }
            $return = array(
                '7. is_file("./nonexistant/file")',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '8':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = is_file( '/not/exists' );
            }
            $return = array(
                '8. is_file("/nonexistant/file")',
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

file_exists and is_file() tests in PHP v$php

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
