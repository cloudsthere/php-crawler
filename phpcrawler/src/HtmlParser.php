<?php
namespace phpcrawler;

class HtmlParser
{
    public static $statics = [];

    public function parse($html){
        $dom = \phpQuery::newDocument($html);

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

        $urls = [];
        $patern = '/(?<=url\()[\s\S]+?(?=\))/';
        preg_match_all($patern, $html, $match);
        if(!empty($match[0])){
            array_walk($match[0], function(&$val, $key){
                $val = trim(preg_replace('/[\'"]/', '', $val));
            });
            $urls = $match[0];
        }

        $return  = array_merge($a, $srcs, $urls);
        \phpQuery::$documents = [];

        return $return;
    }
}

function parser_test(){
    include '../../vendor/autoload.php';
    $html = <<<HTML
HTML;
    // $html = 'fdsa';
    $parser = new HtmlParser;
    $urls = $parser->parse($html);
    var_export($urls);exit;
}

// parser_test();