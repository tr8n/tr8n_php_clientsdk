<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Tokens;

require_once(__DIR__."/../../BaseTest.php");

class MethodTokenTest extends \BaseTest {

    public function testParsing() {
        $tokens = Base::registerTokens("Hello {user.name}");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\MethodToken', get_class($tokens[0]));
    }

    public function testSanitizedName() {
        $label = "Hello {user.name}";
        $tokens = Base::registerTokens($label);
        $this->assertEquals("{user.name}", $tokens[0]->sanitizedName());
    }

    public function testObjectName() {
        $label = "Hello {user.name}";
        $tokens = Base::registerTokens($label);
        $this->assertEquals("user", $tokens[0]->objectName());
    }

    public function testObjectMethod() {
        $label = "Hello {user.name}";
        $tokens = Base::registerTokens($label);
        $this->assertEquals("name", $tokens[0]->objectMethod());
    }

    public function testSubstitution() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));

        $label = "Hello {user.name}";
        $tokens = Base::registerTokens($label);
        $user = new \User("Michael");

        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => $user), $russian));
    }

}