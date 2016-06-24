<?php

include '../vendor/autoload.php';

$url = 'http://localhost/phpCrawler/phpcrawler/test/web/2.gif';

$client = new GuzzleHttp\Client();

// $filename = '1.jpg';

// $response = $client->request('get', $url);
// $content = $response->getBody()->getContents();
// file_put_contents('1.jpg', $content);
// // $content = base64_encode($content);

// dump($content);

// $content1 = file_get_contents($url);
// file_put_contents('11.jpg', $content1);
// $content1 = base64_encode($content1);

// dump($content1);

// dump(strcmp($content, $content1));

$path = '/Applications/MAMP/htdocs/web/index.html';
$dirname = dirname($path);
var_export($dirname);
var_export(is_dir($dirname));
// dump($dirname);

function dump($x){
    echo '<pre>';
    var_dump($x);
}