<?php

namespace phpcrawler;

class Crawler
{
    const LOG_ON = false;
    private $cookie = '';
    private $base_url = '';
    // 下载目录
    private $dir = './web/';
    private $clear_dir = true;
    private $loop = null;

    function __construct($base_url){
        $base_info = parse_url($base_url);

        if(empty($base_info['path']))
            $base_url .= '/';

        $this->base_url = $base_url;
    }

    public function crawl(){
        $urler = new urler($this->base_url);
        $html_parser = new HtmlParser;
        $downloader = new Downloader($this->dir, $this->base_url, $this->clear_dir);
        if(!empty($this->cookie))
            $downloader->setCookie($this->cookie);

        $loop = 0;
        while($current_url = $urler->shift()){
                $loop++;
                dump($current_url);

                $html = $downloader->download($current_url);
                // echo $html;
                // exit;
                // if($loop > 1)
                //     exit;
                $ignore_ext = ['js'];
                if(in_array(pathinfo($current_url)['extension'], $ignore_ext))
                    continue;
                $new_urls = $html_parser->parse($html);
                // dump($new_urls);exit;
                // 输入原始url
                $urler->collect($new_urls, $current_url);

        }

        // $base_info = parse_url($this->base_url);
        // $statics_base_url = $base_info['scheme'].'://'.$base_info['host'].'/';
        // $downloader->setBaseUrl($statics_base_url);

        // $statics = HtmlParser::$statics;
        // $stack = [];

        // $urler->format($statics);
        // // dump($statics);
        // foreach($statics as $url){
        //     $url_info = parse_url($url);
        //     if(!in_array($url, $stack) && $url_info['host'] == $base_info['host']){
        //         $downloader->download($url);
        //         array_push($stack, $url);
        //     }
        // }
    }

    function setCookie($cookie = ''){
        $this->cookie = $cookie;
    }

    function setDir($dir = ''){
        $this->dir = $dir;
    }

    public function clearDir($clear_dir){
        $this->clear_dir = $clear_dir;
    }

    public static function log($msg){
        if(self::LOG_ON){
            echo "<pre>";
            echo $msg."\r\n";
        }
    }
}


function crawler_test(){
    $url = 'http://abc.com/';
    $crawler = new Crawler($url);
    $crawler->crawl();
}

// crawler_test();