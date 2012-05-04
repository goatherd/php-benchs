<?php
/** 
 * Benchmark tests for reading of file
 * Usage: php -q read_file.php
 */
 
if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

$filesize = filesize('includes/big_string.php');
$loops = 10000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();
    
    echo 'Running bench:';
    for($i = 1; $i <= 4; $i++) {
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
            $filename = 'includes/big_string.php';
            for($i = 0; $i < $loops; $i++) {
                $fp = fopen($filename, 'r');
                if($fp) {
                    $str = fread($fp, $filesize);
                    fclose($fp);
                }
            }
            $return = array(
                'fopen();fread();  relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $str = file_get_contents('includes/big_string.php');
            }
            $return = array(
                'file_get_contents() relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;
            
        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            $path = dirname(__FILE__) . '/';
            $filename = $path . 'includes/big_string.php';
            for($i = 0; $i < $loops; $i++) {
                $fp = fopen($filename, 'r');
                if($fp) {
                    $str = fread($fp, $filesize);
                    fclose($fp);
                }
            }
            $return = array(
                'fopen();fread(); absolute path',
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
                $str = file_get_contents($path . 'includes/big_string.php');
            }
            $return = array(
                'file_get_contents() absolute path',
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

read file of $filesize bytes (10000 loops) in PHP v$php

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
