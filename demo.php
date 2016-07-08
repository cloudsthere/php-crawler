<?php

include './vendor/autoload.php';

set_time_limit(0);
$base_url = 'http://target/url';

$cookies = true; // 默认
// $cookies = 'key=abc; key1=123';
// $cookies = [
//     'key' => 'abc',
//     'key1' => '123',
// ];

$config = [
    // 保存文件目录
    'dir' => './web/',
    'log_level' => 2,
    // 若值为false, 表示不使用cookie
    // 若值为true, 表示使用cookie，并且cookie会随服务器操作而改变
    // 若指定cookie值(数组或字符串), 则cookie始终为设定值，不受服务器影响
    'cookies' => $cookies,
    // http请求头，一般不需要设置
    'headers' => [
        'foo' => 'bar',
        'User-Agent' => $_SERVER['HTTP_USER_AGENT'],    
    ],
    // 注意：参数method, form_params和multipart，只对种子页生效，其派生的页面都将使用默认设置。
    // 默认方法为get。
    'method' => 'post',
    // 使用 x-www-form-urlencoded 编码    
    'form_params' => [
        'hello' => 'beauty',
    ],
    // 注意 multipart与form_params不能同时使用
    // 使用 multipart/form-data 编码
    // 'multipart' => [
    //     [
    //         'name'     => 'field_name',
    //         'contents' => 'abc'
    //     ],
    //     [
    //         'name'     => 'file_name',
    //         'contents' => fopen('LICENSE', 'r')
    //     ],
    //     [
    //         'name'     => 'other_file',
    //         'contents' => 'hello',
    //         'filename' => 'filename.txt',
    //     ]
    // ],
];


$crawler = new phpcrawler\Crawler($base_url, $config);

// 循环解析网页的次数，如果为1(数字)，表示只解析种子页面的url。如果为true(默认), 表示无限循环，直到没有发现新的url。
$loop = 1;
$crawler->crawl($loop);


