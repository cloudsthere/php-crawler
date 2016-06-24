<?php

namespace phpcrawler;

/**
 * 先前先出
 */
class TaskQueue
{
    public static $queue = [];
    public static $instance;
    public static $garbage = [];


    public static function getInstance(){
        if(!self::$instance)
            self::$instance =  new self;
        return self::$instance;
    }

    public function push(Task $task){
        if(!array_key_exists($task['path'], self::$queue) && !array_key_exists($task['path'], self::$garbage))
            self::$queue[$task['path']] = $task;
    }

    public function shift(){
        $task = array_shift(self::$queue);
        if(!empty($task))
            self::$garbage[$task['path']] = $task;
        return $task;
    }
}

