<?php
/**
 * Benchmark tests for calling require* methods
 * Usage: php -q test_require.php
 */
error_reporting(E_ALL | E_STRICT);

if(!function_exists('memory_get_usage')) {
    function memory_get_usage() {
        return -1;
    }
}

if(PHP_SAPI != 'cli') {
    die("Please run this script from command line");
}

$tempDirName = 'temp';
$loops = 10000;

if ($_SERVER['argc'] == 1) {
    // Create temporary include directory
    $tempDir = __DIR__ . '/' . $tempDirName;
    if (!is_dir($tempDir)) {
        mkdir($tempDir);
    }

    // Create temporary include files
    echo 'Generating temp classes for loading:';
    $templateContent = file_get_contents(__DIR__ . '/includes/class_template.php');
    for ($i = 0; $i < $loops; $i++) {
        if ($i % 1000 == 0) {
            echo '.';
        }
        file_put_contents(
            $tempDir . '/' . $i . '.php',
            preg_replace(
                array('/{CLASS_NAME}/m', '/{MEMBER_NAME}/m'),
                array('Class_' . $i, 'member_' . $i),
                $templateContent
            )
        );
    }
    echo 'OK' . PHP_EOL;

    $secs = array();
    $results = array();

    echo 'Running bench:';
    for($i = 1; $i <= 12; $i++) {
        $cmd = $_SERVER['_'] . ' ' . $_SERVER['SCRIPT_FILENAME'] . ' ' . $i;
        $ret = unserialize(`$cmd`);
        $secs[] = $ret[1];
        $results[] = $ret;
        echo '.';
    }
    echo 'done' . PHP_EOL;

    // Delete temporary include file
    array_map('unlink', glob($tempDir . '/*.php'));

    // Delete temporary include directory
    rmdir($tempDir);

} else {

    error_reporting(0);

    if (defined('__DIR__') === false) {
        define('__DIR__', dirname(__FILE__));
    }

    $relPath = $tempDirName . '/';
    $absPath = __DIR__ . '/' . $tempDirName . '/';

    switch ($_SERVER['argv'][1]) {

        // require tests
        case '1':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require $relPath . $i . '.php';
            }
            $return = array(
                'require relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '2':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require $absPath . $i . '.php';
            }
            $return = array(
                'require absolute path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '3':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require_once $relPath . $i . '.php';
            }
            $return = array(
                'require_once relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '4':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require_once $absPath . $i . '.php';
            }
            $return = array(
                'require_once absolute path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '5':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require_once realpath($relPath . $i . '.php');
            }
            $return = array(
                'require_once realpath(relative path)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '6':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                require_once __DIR__ . '/' . $tempDirName . '/' . $i . '.php';
            }
            $return = array(
                'require_once __DIR__ ',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;





        // include tests
        case '7':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include $relPath . $i . '.php';
            }
            $return = array(
                'include relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '8':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include $absPath . $i . '.php';
            }
            $return = array(
                'include absolute path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '9':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include_once $relPath . $i . '.php';
            }
            $return = array(
                'include_once relative path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '10':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include_once $absPath . $i . '.php';
            }
            $return = array(
                'include_once absolute path',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '11':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include_once realpath($relPath . $i . '.php');
            }
            $return = array(
                'include_once realpath(relative path)',
                (microtime(1)-$s),
                (memory_get_usage()-$m)
            );
            echo serialize($return);
            break;

        case '12':
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                include_once __DIR__ . '/' . $tempDirName . '/' . $i . '.php';
            }
            $return = array(
                'include_once __DIR__ ',
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

require* and include* tests in PHP v$php

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
