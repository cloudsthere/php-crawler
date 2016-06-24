<?php

include 'bootstrap.php';


class UrlerTester extends PHPUnit_Framework_TestCase
{
    private $urler;
    function __construct(){
        $base_url = 'http://www.123.com';
    }

    function testCreateAbsolutePath()
    {
        $referer = 'http://sjal.com/home/center/user';
        $map = [
            './a/b' => '/home/center/a/b',
            './a/b/../c' => '/home/center/a/c',
            '../../../a/b/c' => false,
            'a' => '/home/center/a',
            '../a/b' => '/home/a/b',
            '../../a/b' => '/a/b',
            '/' => '/',
        ];
        foreach($map as $input => $expect){
            $actual = phpcrawler\createAbsolutePath($input, $referer);
            $res = ($actual == $expect);
            $this->assertTrue($res, 'input:'.$input.' actual:'.$actual.' expect:'.$expect);
        }
    }
}