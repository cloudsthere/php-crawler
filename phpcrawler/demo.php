<?php

include '../vendor/autoload.php';

set_time_limit(0);

// $base_url = 'http://www.topys.cn/';
$base_url = 'http://www.jianshu.com/';
// $base_url = 'http://localhost/phpcrawler/phpcrawler/test/one.html';

$crawler = new phpcrawler\Crawler($base_url);
$crawler->setDir('/Applications/MAMP/htdocs/web/');
$crawler->crawl();





function dump($x){
    echo "<pre>";
    var_dump($x);

}