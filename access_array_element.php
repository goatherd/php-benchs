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

$loops = 300000;

if($_SERVER['argc'] == 1) {

    $secs = array();
    $results = array();

    
    echo 'Running bench:';
    for($i = 1; $i <= 7; $i++) {
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
            $old = error_reporting(E_ALL);
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = @ $_SERVER['nada'];
            }
            $return = array( 
                '1', 
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;

        case '2':
            $old = error_reporting(E_ALL);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = isset($_SERVER["nada"]) ? $_SERVER['nada'] : null;
            }
            $v = $buffer;
            $return = array( 
                '2',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;
            
        case '3':
            $old = error_reporting(E_ALL ^ E_NOTICE);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = $_SERVER['nada'];
            }
            $v = $buffer;
            $return = array( 
                '3',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;

        case '4':
            $old = error_reporting(0);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = $_SERVER['nada'];
            }
            $v = $buffer;
            $return = array( 
                '4',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;
            
        case '5':
            $old = error_reporting(0);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = isset($_SERVER["nada"]) ? $_SERVER['nada'] : null;
            }
            $v = $buffer;
            $return = array( 
                '5',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;
            
        case '6':
            $old = error_reporting(0);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = array_key_exists('nada',$_SERVER) ? $_SERVER['nada'] : null;
            }
            $v = $buffer;
            $return = array( 
                '6',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;
            
        case '7':
            $old = error_reporting(E_ALL);
            $buffer = '';
            $m = memory_get_usage();
            $s = microtime(1);
            for($i = 0; $i < $loops; $i++) {
                $v = array_key_exists('nada',$_SERVER) ? $_SERVER['nada'] : null;
            }
            $v = $buffer;
            $return = array( 
                '7',  
                (microtime(1)-$s), 
                (memory_get_usage()-$m) 
            );
            echo serialize($return);
            error_reporting($old);
            break;
            
    }
 
    exit;   
}




$php = PHP_VERSION;
$type = gettype($elem);
echo  <<<head

access to non-existing array element benchmark in PHP v$php

1. using error control operator with error level E_ALL:
   error_reporting(E_ALL); \$v = @ \$a['nada'];
   
2. using isset() with error level E_ALL
   error_reporting(E_ALL); \$v = isset(\$a['nada']) ? \$a['nada'] : null;
   
3. same as 1., without error control operator, error level E_ALL ^ E_NOTICE:
   error_reporting(E_ALL ^ E_NOTICE); \$v = \$a['nada'];
   
4. same as 1., with error level 0:
   error_reporting(0); \$v = \$a['nada'];
   
5. same as 2., with error level 0:
   error_reporting(0);  \$v = isset(\$a['nada']) ? \$a['nada'] : null;
   
6. using array_key_exists(), with error level 0:
   error_reporting(0);  \$v = array_key_exists('nada', \$a) ? \$a['nada'] : null;

7. using array_key_exists(), with error level E_ALL:
   error_reporting(E_ALL);  \$v = array_key_exists('nada', \$a) ? \$a['nada'] : null;


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