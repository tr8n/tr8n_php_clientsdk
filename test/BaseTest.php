<?php

require_once __DIR__."/../library/Tr8n.php";

class BaseTest extends PHPUnit_Framework_TestCase {

    protected static function fixturesPath() {
        return __DIR__."/fixtures/";
    }

    protected static function loadJSON($path) {
        $path = self::fixturesPath().$path;

        if (!file_exists($path)) {
            throw new Exception("Error: File $path not found.");
        }
        $string = file_get_contents($path);
        return json_decode($string,true);
    }

}