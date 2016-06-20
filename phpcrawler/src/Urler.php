<?php
namespace phpcrawler;

// use CzProject\PathHelper;
/**
 * 处理url
 */
class Urler
{
    private $base_url = '';
    private $collection = array();
    private $garbage = array();
    private $statics = [];

    function __construct($base_url){
        $this->base_url = $base_url;
        // $this->collection[] = $base_url;
        // dump($base_url);
        $this->collect([$base_url]);
    }

    /**
     * 收集新url
     * @param  Array  $new_urls 新url
     * @return void           
     */
    public function collect(Array $new_urls, $current_url = ''){
        // dump($new_urls);
        if(empty($new_urls))
            return;

        // 过滤
        $this->_filter($new_urls);
        // 格式化
        $this->format($new_urls, $current_url);
        // dump($new_urls);
        // 收录
        $this->_collect($new_urls);
            // dump($this->collection);
            // exit;
    }

    /**
     * 弹出新url
     * @return string 新url
     */
    public function shift(){
        // dump($this->collection);
        $current_url = array_shift($this->collection);
        $current_url_neat = explode('?', $current_url, 2)[0];
        $current_url_neat = explode('#', $current_url_neat, 2)[0];
        array_unshift($this->garbage, $current_url_neat);
        return $current_url;
    }

    /**
     * 初步过滤
     * @param  Array  &$urls 
     * @return void        
     */
    private function _filter(Array &$urls){
        $base_host = parse_url($this->base_url)['host'];

        $urls = array_filter($urls, function(&$var)use($base_host){
            $var = trim($var);
            if(empty($var) ||
            strpos(strtolower($var), 'javascript') === 0 ||
            $var == '#' )
                return false;
            // 不同域名
            $url_info = parse_url($var);
            // dump($url_info);
            if(!empty($url_info['host']) && $url_info['host'] != $base_host){
                // echo 1;
                return false;
            }
            return true;
        });
    }

    /**
     * 格式化
     * @param  Array  &$urls 
     * @return void        
     */
    public function format(Array &$urls, $current_url){
        $base_url_arr = explode('/', $this->base_url);
        // dump($base_url_arr);
        array_pop($base_url_arr);
        // dump($base_url_arr);

        $base_root = implode('/', $base_url_arr);
        // dump($base_root);
        foreach($urls as &$url){
            // if($url[strlen($url) - 1] == '/')
            //     $url .= 'index.html';
            $url_info = parse_url($url);
            // dump($url_info);
            // $url = $url_info['path'] == '/' ? '/index.html' : $url_info['path']; 
            // dump($url);
            if(!empty($url_info['scheme']))
                continue;

            // echo '<hr>';
            // dump($url);
            // dump($current_url);
            $url = self::createAbsolutePath($url, $current_url, $base_root);
            // dump($relative_path);

        }
        // dump($urls);

    }

    /**
     * 收录
     * @param  Array  $urls 
     * @return void       
     */
    private function _collect(Array $urls){
        // dump($urls);exit;
        // 排除旧url和不在相对路径下的
        $garbage = $this->garbage;
        $base_url = $this->base_url;
        $urls = array_filter($urls, function($var)use($garbage, $base_url){
            $var = explode('?', $var, 2)[0];
            $var = explode('#', $var, 2)[0];
            if(in_array($var, $garbage))
                return false;
            return true;
        });
        // dump($urls);
        // var_export($urls);exit;
        $this->collection = array_unique(array_merge($this->collection, $urls));
    }


    public function getCollection(){
        return $this->collection;
    }

    public static function createAbsolutePath($source, $referer, $prefix = ''){
        // dump($source);
        // dump($referer);
        // dump($prefix);
        if(empty($referer))
            return $source;
        $referer_info = parse_url($referer);
        // dump($referer_info);
        $source_info = parse_url($source);
        // dump($source_info);
        // var_export($referer_info);
        // $prefix = $referer_info['scheme'].'://'.$referer_info['host'];
        // 绝对路径
        if($source[0] == '/')
            return $prefix.$source;
        if($source[0] != '.')
            $source = './'.$source;

        $referer_path_stack = array_filter(explode('/', $referer_info['path']));
        $source_path_stack = array_filter(explode('/', $source_info['path']));
        // dump($referer_path_stack);
        // dump($source_path_stack);

        foreach($source_path_stack as  $node){
            $source_path_collector = [];
            if($node == '.')
                array_pop($referer_path_stack);
            elseif($node == '..'){
                array_splice($referer_path_stack, -2, 2);
                if(empty($referer_path_stack))
                    return false;
            }
            else{
                array_push($source_path_collector, $node);
            }
            $referer_path_stack = array_merge($referer_path_stack, $source_path_collector);
        }
        return $prefix.'/'.implode('/', $referer_path_stack);

    }
}


function url_test(){
    include '../../vendor/autoload.php';
    $source = '/a/b/../c';
    $source = '../../a/b/c';
    $source = './a/b';
    //  /home/c
    // $referer = 'http://123.com/home/center';
    $referer = 'http://sjal.com/home/center';

    $res = Urler::createAbsolutePath($source, $referer);
    var_export($res);
    // $url = 'http://a.cc/fdjj/dfskas.js';
    // var_export(pathinfo($url));exit;
    // $base_url = 'http://www.topys.cn/one/';
    // $new_urls = [
    //     'http://www.topys.cn/one/two',
    //     'http://www.baidu.com',
    //     '/apple/pear',
    //     'hehe/haha',
    //     '     javascript:void(0)',
    //     '    /fire/water#middle',
    //     '    /fire/water?aa=1',
    //     '#'
    // ];
    // $urler = new Urler($base_url);
    // $urler->collect($new_urls);
}


// url_test();
