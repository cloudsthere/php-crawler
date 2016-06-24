<?php

include './vendor/autoload.php';

set_time_limit(0);
// $base_url = 'http://www.bootcdn.cn/';
// $base_url = 'http://www.51jingying.com/';
// $base_url = 'http://www.91elian.com/index.html';
// $base_url = 'https://www.nanit.com/';
// $base_url = 'https://draftin.com/';
// $base_url = 'http://fishry.com/';
// $base_url = 'http://www.golaravel.com/';
// $base_url = 'http://www.ghostchina.com/';
// $base_url = 'https://haobtc.com/';
$base_url = 'http://localhost/phpcrawler/test/web/one.php';
// $base_url = 'http://www.baidu.com';
// $base_url = 'http://weixiao.qq.com/home/index/view/';
// $base_url = 'http://my.csdn.net/my/mycsdn';
// $base_url = 'http://www.bootcss.com/p/flat-ui/';
// $base_url = 'http://www.topys.cn/';
// $base_url = 'http://www.jianshu.com';
// $base_url = 'http://woods.niowoo.com';
// $base_url = 'http://localhost/phpCrawler/phpcrawler/test/web/index.html';
// $base_url = 'http://localhost/phpcrawler/phpcrawler/test/one.html';

// $cookies = false;
// $cookies = 'uuid_tt_dd=-6189335456510426046_20160617; Hm_lvt_6bcd52f51e9b3dce32bec4a3997715ac=1466752263; Hm_lpvt_6bcd52f51e9b3dce32bec4a3997715ac=1466752263; __message_sys_msg_id=0; __message_gu_msg_id=0; __message_cnel_msg_id=0; __message_district_code=440000; __message_in_school=0; _ga=GA1.2.1863213334.1466752268; _gat=1; UserName=Allen_Tsang; UserInfo=kCp5CGizJCSfvsiZzVvysKe6pVsY18skF3fD%2FToP5hiPjLYwzHjpyYon16tcrvgd5j6KFqV8QEYTp22DT7f9aHrjesgTN%2FHaJ53VTChjJVVV48tnhQ0Wg80iX1pa1UtyUTEy6323EBGPZuUDF9KP2Q%3D%3D; UserNick=%E5%A4%A9%E5%8F%B0%E7%9A%84%E4%BA%91; AU=3D3; UN=Allen_Tsang; UE="hlzeng2009@163.com"; BT=1466752269772; access-token=1eacae05-af1e-443e-ad48-c53b5e1bd273; dc_tos=o99lba; bdshare_firstime=1466752294785; dc_session_id=1466752277738';
// $cookies = 'daily_cookie_name_45750=1; show_update_school=0; tencentSig=9107857408; pgv_pvid=6308025744; PHPSESSID=hr5o69d0devtkhfhmjudjgght1; pt_local_token=2-; pt2gguin=o0309954980; uin=o0309954980; skey=@JUgMbXBSx; ptisp=ctc; RK=7aVG3W7HZo; ptcz=b159d6577d32aaffb021965bf0a81042169b94dedc2d9eefd00731de55332e7b; hwJR_2132_saltkey=be4d87; hwJR_2132_auth=P3DoPz2v9Ua0gsMCvtJ%2BY8biw2S4MUEnbGsxPkCv705Z3VgOVHkb5gTa70kwH%2FcvDpsNtAz1EJEoJHR9yx9VpOG4Uaen7L2lp88YzvyjFUw%2F5w6prAus0Moju%2BCO7Leb; pgv_pvi=2837614592; pgv_si=s1021460480; hwJR_2132_309954980_version_code=2016-06-13+00%3A00%3A00; hwJR_2132_current_weixin_media=30061; _qddab=3-tks7q.iptg2r5y';
// $cookies = 'favor=salty; pgv_pvid=2667591072; CNZZDATA2317851=cnzz_eid%3D1470295619-1466321421-%26ntime%3D1466427547; Hm_lvt_7d03826329b9cbb4dbc58d74f9b0a417=1466321447; _ga=GA1.1.1907843289.1466425874; SQLiteManager_currentLangue=2; PHPSESSID=71bcb5a05fdf66cab3eb3e0b5fef78cc';
// $cookies = 'pgv_pvid=2667591072; CNZZDATA2317851=cnzz_eid%3D1470295619-1466321421-%26ntime%3D1466427547; Hm_lvt_7d03826329b9cbb4dbc58d74f9b0a417=1466321447; _ga=GA1.1.1907843289.1466425874; SQLiteManager_currentLangue=2; PHPSESSID=71bcb5a05fdf66cab3eb3e0b5fef78cc';
// $cookies = [
//     'favor' => 'sweet',
// ];
// $cookies = true;
$config = [
    'dir' => '/Applications/MAMP/htdocs/web/',
    'log_level' => 2,
    'cookies' => $cookies,
];


$crawler = new phpcrawler\Crawler($base_url, $config);

// $crawler->setDir('');
$crawler->crawl();

// echo 'second';

// $base_url = 'http://weixiao.qq.com/home/index/view/';
// $config['dir'] = '/Applications/MAMP/htdocs/web1/';
// $crawler = new phpcrawler\Crawler($base_url, $config);
// $crawler->crawl(1);





function dump($x){
    echo "<pre>";
    var_dump($x);

}