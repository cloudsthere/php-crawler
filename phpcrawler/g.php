<?php

namespace phpcrawler;

use GuzzleHttp\Client;

include '../vendor/autoload.php';

$client = new Client();

$cookies = 'pgv_pvid=2667591072; CNZZDATA2317851=cnzz_eid%3D1470295619-1466321421-%26ntime%3D1466427547; Hm_lvt_7d03826329b9cbb4dbc58d74f9b0a417=1466321447; _ga=GA1.1.1907843289.1466425874; SQLiteManager_currentLangue=2; PHPSESSID=71bcb5a05fdf66cab3eb3e0b5fef78cc';
// $cookies = [
//     'PHPSESSID' => '71bcb5a05fdf66cab3eb3e0b5fef78cc',
//     'cookie' => 'sweet',
// ];
if(is_string($cookies)){
    $slices = array_filter(array_map('trim', explode(';', $cookies)));
    $cookies = [];
    foreach($slices as $slice){
        $slice_arr = explode('=', $slice);
        $cookies[$slice_arr[0]] = $slice_arr[1];
    }
}

$domain = 'localhost';
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

// $set->setDomain($domain);

// $jar->setCookie($set);




$r = $client->request('GET', 'http://localhost/phpcrawler/phpcrawler/test/web/two.php', [
    'cookies' => $jar
]);
$content = $r->getBody()->getContents();
echo $content;
function dump($x){
    echo "<pre>";
    var_dump($x);
}
