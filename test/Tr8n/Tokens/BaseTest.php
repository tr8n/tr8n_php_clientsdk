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

class BaseTest extends \BaseTest {

    public function testRegisterTokens() {
        $tokens = Base::registerTokens("You have {count} messages", "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals('{count}', $tokens[0]->fullName());
        $this->assertEquals('count', $tokens[0]->declaredName());
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals('{count}', $tokens[0]->sanitizedName());

        $tokens = Base::registerTokens("{user} has {count} messages", "data");
        $this->assertEquals(2, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals('{user}', $tokens[0]->fullName());
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[1]));
        $this->assertEquals('{count}', $tokens[1]->fullName());

        $tokens = Base::registerTokens("{user.name} has messages", "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\MethodToken', get_class($tokens[0]));
        $this->assertEquals('{user.name}', $tokens[0]->fullName());

        $tokens = Base::registerTokens("This is {_his_her} message", "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\HiddenToken', get_class($tokens[0]));
        $this->assertEquals('{_his_her}', $tokens[0]->fullName());

        $tokens = Base::registerTokens("This is {user| his, her} message", "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\TransformToken', get_class($tokens[0]));
        $this->assertEquals('{user| his, her}', $tokens[0]->fullName());

        $tokens = Base::registerTokens("This is [link: a message]", "data");
        $this->assertEquals(0, count($tokens));

        $tokens = Base::registerTokens("This is [link: a message]", "decoration");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DecorationToken', get_class($tokens[0]));
        $this->assertEquals('[link: a message]', $tokens[0]->fullName());

        $tokens = Base::registerTokens("This is [link: {user::pos} messages]", "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals('{user::pos}', $tokens[0]->fullName());

        $tokens = Base::registerTokens("This is [link: {user} messages]", "decoration");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DecorationToken', get_class($tokens[0]));
        $this->assertEquals('[link: {user} messages]', $tokens[0]->fullName());
        $this->assertEquals('link', $tokens[0]->name());
        $this->assertEquals('[link: ]', $tokens[0]->sanitizedName());
    }

    public function testSubstitution() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));

        $label = "You have {count} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals("You have 1 message", $tokens[0]->substitute($label, 1, $russian));

        $label = "Hello {user}";
        $user = new \User("Michael");
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, $user, $russian));
        $this->assertEquals("Hello Peter", $tokens[0]->substitute($label, array($user, "Peter"), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array($user, "@name"), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array($user, "@@fullName"), $russian));

        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("object" => $user, "attribute"=>"name"), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("object" => $user, "method"=>"fullName"), $russian));

        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("object" => array("name" => "Michael", "gender" => "male"), "attribute"=>"name"), $russian));
    }
}