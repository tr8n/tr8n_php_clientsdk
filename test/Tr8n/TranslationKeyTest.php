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

class TranslationKeyTest extends \BaseTest {

    public function testSubstitution() {
        $english = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));
        $label = "Hello World";
        $tkey = new TranslationKey(array("label" => $label));
        $this->assertEquals("Hello World", $tkey->substituteTokens($label, array(), $english));

        $user1 = new \User("Michael");
        $user2 = new \User("Alex");

        $label = "Hello {user}";
        $tkey = new TranslationKey(array("label" => $label));
        $this->assertEquals("Hello Michael", $tkey->substituteTokens($label, array("user" => $user1), $english));

        $label = "Hello {user1} and {user2}";
        $tkey = new TranslationKey(array("label" => $label));
        $this->assertEquals("Hello Michael and Alex", $tkey->substituteTokens($label, array("user1" => $user1, "user2" => $user2), $english));

        $label = "Hello {user1} [bold: and] {user2}";
        $tkey = new TranslationKey(array("label" => $label));
        $this->assertEquals("Hello Michael <bold>and</bold> Alex", $tkey->substituteTokens($label,
            array("user1" => $user1, "user2" => $user2, "bold" => '<bold>{$0}</bold>'),
            $english));

        $label = "You have [link: [bold: {count}] messages]";
        $tkey = new TranslationKey(array("label" => $label));
        $this->assertEquals("You have <a><bold>1</bold> messages</a>", $tkey->substituteTokens($label,
            array("count" => 1, "bold" => '<bold>{$0}</bold>', "link" => '<a>{$0}</a>'),
            $english));
    }

    public function testTranslation() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));
        $tkey = new TranslationKey(array("label" => "Hello World"));
//        $tkey->translate($russian);
    }


}
