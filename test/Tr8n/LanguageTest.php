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

class LanguageTest extends \BaseTest {

    public function testLoadingLanguage() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));
//        print_r($russian);

        $this->assertEquals('ru', $russian->locale);
        $this->assertEquals('Russian - Русский', $russian->name);
        $this->assertEquals('Russian', $russian->english_name);
        $this->assertEquals('Русский', $russian->native_name);
        $this->assertEquals(array("date", "gender_list", "gender", "number", "value"), array_keys($russian->context_rules));
        $this->assertEquals(array("nom", "gen", "dat", "acc", "ins", "pre", "pos"), array_keys($russian->language_cases));
    }

}
