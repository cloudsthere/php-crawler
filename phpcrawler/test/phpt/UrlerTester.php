<?php

include 'bootstrap.php';

use phpcrawler\Urler;
class UrlerTester extends PHPUnit_Framework_TestCase
{
    private $urler;
    function __construct(){
        $base_url = 'http://www.123.com';
        $this->urler = new Urler($base_url);
    }

    function testCreateAbsolutePath()
    {
        $referer = 'http://sjal.com/home/center';
        $map = [
            './a/b' => '/home/a/b',
            './a/b/../c' => '/home/c',
            '../../a/b/c' => false,
        ];
        foreach($map as $input => $expect){
            $actual = Urler::createAbsolutePath($input, $referer);
            $res = ($actual == $expect);
            $this->assertTrue($res, 'input:'.$input.' actual:'.(int)$actual.' expect:'.$expect);
        }
    }
}