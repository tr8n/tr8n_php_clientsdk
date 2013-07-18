<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Cache;

require_once(__DIR__."/../../BaseTest.php");

class ApcAdapterTest extends \BaseTest {


    public function testCache() {
        $cache = new \Tr8n\Cache\ApcAdapter();

        $value = $cache->fetch("test", "hello world");
        $this->assertEquals('hello world', $value);
        $this->assertEquals(true, $cache->exists("test"));

        $value = $cache->fetch("test2", function() {
            return "hello world";
        });
        $this->assertEquals('hello world', $value);
        $this->assertEquals(true, $cache->exists("test2"));

        $cache->delete("test2");
        $this->assertEquals(false, $cache->exists("test2"));

        $value = $cache->store("test3", "test3 value");
        $value = $cache->fetch("test3", "test4 value");
        $this->assertEquals('test3 value', $value);
        $this->assertEquals(true, $cache->exists("test3"));
        $cache->delete("test3");
        $this->assertEquals(false, $cache->exists("test3"));
        $value = $cache->fetch("test3", "test4 value");
        $this->assertEquals('test4 value', $value);
        $this->assertEquals(true, $cache->exists("test3"));
    }




}