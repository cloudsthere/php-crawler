<?php

namespace phpcrawler;

use GuzzleHttp\Client;

class TaskFactory
{
    public static $reference;
    public static $config;
    public static $downloader = [
        'config' => [
            'debug' => false,
            'method' => 'get',
            'headers' => [], 
        ]
    ];
    public static $cookieJar;
    public static $seed;
    
    public static function init($base_url, $config = []){
        self::$reference = new Task($base_url);
        self::defaultConfig($config);

        $client = [
                    'base_uri' => self::$reference->urlWithoutQuery(),
                ];

        // cookie
        if(!empty(self::$config['cookies']) && !is_bool(self::$config['cookies'])){
            $cookies = self::$config['cookies'];
            if(is_string($cookies)){
                $slices = array_filter(array_map('trim', explode(';', $cookies)));
                $cookies = [];
                foreach($slices as $slice){
                    $slice_arr = explode('=', $slice);
                    $cookies[$slice_arr[0]] = $slice_arr[1];
                }
            }

            $domain = self::$reference['host'];
            $jar = new \GuzzleHttp\Cookie\CookieJar();

            foreach($cookies as $name => $value){
                $cookie = [
                    'Name' => $name, 
                    'Value' => $value,
                ];
                $set = new \GuzzleHttp\Cookie\SetCookie($cookie);
                $set->setDomain($domain);
                $jar->setCookie($set);
            }
            self::$cookieJar = $jar;

        }elseif(is_bool(self::$config['cookies'])){
            $client['cookies'] = self::$config['cookies'];
        }

        self::$downloader['client'] = new Client($client);

        if(!empty(self::$config['method']))
            self::$downloader['config']['method'] = strtolower(self::$config['method']);
        if(!empty(self::$config['form_params']))
            self::$downloader['config']['form_params'] = self::$config['form_params'];
        if(!empty(self::$config['multipart']))
            self::$downloader['config']['multipart'] = self::$config['multipart'];

        self::$downloader['config']['headers'] = self::$config['headers'];

        $seed = self::create($base_url);
        self::$downloader['config']['method'] = 'get';
        unset(self::$downloader['config']['multipart']);
        unset(self::$downloader['config']['form_params']);

        return $seed;
    }

    private static function defaultConfig($config){
        $default = [
            'cookies' => true,
        ];
        self::$config = array_merge($default, $config);
        if(strrev(self::$config['dir'])[0] == '/'){
            self::$config['dir'] = substr_replace(self::$config['dir'], '', -1, 1);
        }
    }

    public static function create($url, Task $reference = null){
        if(!empty($reference))
            self::$reference = $reference;
        // 过滤
        if(!$url = self::filter($url))
            return false;

        $task = new Task($url);
        $path = str_replace('\/', '/', $task['path']);
        $path = createAbsolutePath($path, self::$reference['path']);
        if(!$path)
            return false;
        

        $task['scheme'] = self::$reference['scheme'];
        $task['port'] = self::$reference['port'];
        $task['host'] = self::$reference['host'];
        $task['referer'] = self::$reference['path'];
        $task['path'] = $path;

        // dump(self::$config['cookies']);
        if(!empty(self::$cookieJar) && !is_bool(self::$cookieJar))
            self::$downloader['config']['cookies'] = clone self::$cookieJar;


        $task->downloader =  self::$downloader;
        unset(self::$config['downloader']);

        foreach(self::$config as $key => $val){
            if(property_exists($task, $key))
                $task->$key = $val;
        }
        return $task;
    }

    public static function filter($url){
            // dump($url);
            $url = trim($url);
            if(strpos(strtolower($url), 'javascript') === 0 ||
            $url[0] == '#' ||
            strpos($url, 'mailto') === 0 ||
            strpos($url, 'data') === 0 ||
            strpos($url, 'tel') === 0 
            ){
                Logger::info('过虑掉url: '.$url);
                return false;
            }
            // 不同域名
            $url_info = parse_url($url);
            // dump($url_info);
            if(!empty($url_info['host']) && $url_info['host'] != self::$reference['host']){

                return false;
            }
            return $url;

    }
}
