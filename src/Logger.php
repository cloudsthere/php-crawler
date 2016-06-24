<?php

namespace phpcrawler;

class Logger
{

    private static $level = 1;
    private static $log_level = [
        'none' => 0, 
        'debug' => 1, 
        'info' => 2,
    ];

    public static function __callStatic($name, $argv){
        // var_export($name);
        // var_export($argv);
        $log_level = self::$log_level[$name];
        if($log_level <= self::$level)
            self::present($argv[0]);
    }

    public static function present($msg){
        echo $msg."<br>";
    }

    public static function level($level){
        self::$level = $level;
    }
}
