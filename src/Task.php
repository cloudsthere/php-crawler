<?php
namespace phpcrawler;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class Task implements \ArrayAccess, \Iterator
{
    private $url = [];
    private $dir;
    private $content;
    private $downloader;

    function __construct($url){
        $this->url = parse_url($url);
        if(empty($this['path']))
            $this['path'] = '/';
        $this->url['ext'] = @array_pop(explode('.', $this['path']));
        $this->url['origin'] = $url;
    }

    public function urlWithoutQuery(){
        return $this->url['scheme'].'://'.$this->url['host'].(is_null($this->url['port']) ? '' : ':'.$ths->url['port']).$this->url['path'];
    }

    public function rewind(){
        reset($this->url);
    }

    public function current(){
        return current($this->url);
    }

    public function key(){
        return key($this->url);
    }

    public function next(){
        return next($this->url);
    }

    public function valid(){
        return $this->current() !== false;
    }

    public function __set($name, $value){
        $this->$name = $value;
    }

    public function offsetExists($offset){
        return isset($this->url[$offset]);
    }

    public function offsetUnset($offset){
        unset($this->url[$offset]);
    }

    public function offsetGet($offset){
        return isset($this->url[$offset]) ? $this->url[$offset] : null;
    }

    public function offsetSet($offset, $value){
        $this->url[$offset] = $value;
    }

    public function download(){


        try {
            $url = $this['path']. (empty($this['query']) ? '' : '?'.$this['query']);
            $method = $this->downloader['config']['method'];
            $response = $this->downloader['client']->$method($url, $this->downloader['config']);

            $this->content = $response->getBody()->getContents();
            $filename = $this->_makePath();

            if($filename){
                Logger::info('文件创建成功');
                file_put_contents($filename, $this->content);
            }

        } catch (RequestException $e) {
            Logger::debug('请求url: '.$this->urlWithoutQuery(). ' 失败。 origin: '.$this['origin'].'  referer: '.$this['referer']);
            return false;
        }

    }

    public function run(){
        $this->download();
        return $this->parse();
    }

    private function _makePath(){
        $path = $this['path'];
        // dump($path);
        if(empty($path))
            $path = '/index.html';
        if($path[strlen($path) - 1] == '/') 
            $path .= 'index.html';
        $dir = $this->dir.$path;
        $dirname = dirname($dir);

        if(file_exists($dir) && is_file($dir)){
            Logger::info('文件已存在: '.$dir);
            return false;
        }elseif(!is_dir($dirname)){
            $res = mkdir_recursive($dirname);
            if($res === false){
                Logger::debug('创建目录失败: '.$dirname);
                return false;
            }
        }
        return $dir;
    }

    public function parse(){
        $dom = \phpQuery::newDocument($this->content);

        $a = [];
        $nodes_count = count($dom->find('*[href]'));
        for($i = 0; $i < $nodes_count; $i++){
            $a[] = $dom->find('*[href]:eq('.$i.')')->attr('href');
        }

        $srcs = [];
        $nodes_count = count($dom->find('*[src]'));
        for($i = 0; $i < $nodes_count; $i++){
            $srcs[] = $dom->find('*[src]:eq('.$i.')')->attr('src');
        }
        
        \phpQuery::$documents = [];


        $urls = [];
        $patern = '/(?<=url\()[^)]*?(?=\))/';
        preg_match_all($patern, $this->content, $match);
        if(!empty($match[0])){
            array_walk($match[0], function(&$val, $key){
                $val = trim(preg_replace('/[\'"]/', '', $val));
            });
            $urls = $match[0];
        }

        $collection  = array_merge($a, $srcs, $urls);
        return $collection;
    }
    
}

