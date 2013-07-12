<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n;

require_once(__DIR__."/../BaseTest.php");

class ApplicationTest extends \BaseTest {

    public function testInit() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $this->assertEquals('en-US', $app->language('en-US')->locale);
    }



}
