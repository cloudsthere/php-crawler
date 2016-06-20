<?php

namespace phpcrawler;
use GuzzleHttp\Client;
use CzProject\PathHelper;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;


class Downloader
{
    // 下载目录
    private $dir;
    private $base_url;
    private $cookie;
    private $actual_url;  // 真实url

    const TIMEOUT = 30;

    /**
     * @param string $dir 下载目录
     * @param string $base_url 基础url,通过此url计算下载的相对目录
     * @param boolean $clear_dir 是否清空下载目录
     */
    function __construct($dir, $base_url, $clear_dir = true){

        $this->dir = $dir;
        $this->base_url = $base_url;
        if(file_exists($this->dir) && $clear_dir) 
            $this->deldir($this->dir);
        mkdir($this->dir, 0755);
    }

    public function setCookie($cookie){
        $this->cookie = $cookie;
    }

    public function download($url){
        // dump($url);
      
        $content = $this->_http_get($url);
        // dump($content);
        if($content){
          $path = $this->_makePath($url);
          // dump($path);
          if($path){
              if(!file_exists($path)){
                Crawler::log('获取url: '.$url.' 成功');
                $re = file_put_contents($path, $content);
              }
          }
        }
        return $content;
    }

    private function _makePath($url){

        $dir = Urler::createAbsolutePath($url, $this->base_url);
        if($dir == '/')
          $dir = 'index.html';
        $dir = $this->dir.$dir;
        // dump($dir);
        // dump(realpath($dir));
        $dirname = dirname($dir);
        // dump($dirname);
        if(file_exists($dirname))
            return is_dir($dirname) ? $dir : false;
        $re = mkdir($dirname, 0755, true);
        return $re ? $dir : false;
    }



    public function _http_get($url){
        $client = new Client;
       
        try {
            $response = $client->request('GET', $url);
            return $response->getBody();
        } catch (RequestException $e) {
            // dump($e->getMessage());
            Crawler::log('请求url: '.$url. ' 失败');
            return false;
        }
  }

    public function setBaseUrl($base_url){
        $this->base_url = $base_url;
    }


    private function deldir($dir) {
          //先删除目录下的文件：
          $dh=opendir($dir);
          while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
              $fullpath=$dir."/".$file;
              if(!is_dir($fullpath)) {
                  unlink($fullpath);
              } else {
                  $this->deldir($fullpath);
              }
            }
          }
         
          closedir($dh);
          //删除当前文件夹：
          if(rmdir($dir)) {
            return true;
          } else {
            return false;
          }
        }

}

function download_test(){
    $dir = './web';
    $base_url = 'http://hehe.com';
    $downloader = new Downloader($dir, $base_url);
    $downloader->download();
}

// download_test();