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
        $this->assertEquals("You have 1 message", $tokens[0]->substitute($label, array("count" => 1), $russian));

        $label = "Hello {user}";
        $user = new \User("Michael");
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => $user), $russian));
        $this->assertEquals("Hello Peter", $tokens[0]->substitute($label, array("user" => array($user, "Peter")), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => array($user, "@name")), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => array($user, "@@fullName")), $russian));

        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => array("object" => $user, "attribute"=>"name")), $russian));
        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => array("object" => $user, "method"=>"fullName")), $russian));

        $this->assertEquals("Hello Michael", $tokens[0]->substitute($label, array("user" => array("object" => array("name" => "Michael", "gender" => "male"), "attribute"=>"name")), $russian));
    }

    public function testLanguageCaseKeys() {
        $label = "This is your {count::ord} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals(array('ord'), $tokens[0]->caseKeys());
        $this->assertEquals('count::ord', $tokens[0]->pipelessName());
        $this->assertEquals('count', $tokens[0]->caselessName());
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(true, $tokens[0]->hasCases());

        $label = "This is your {count::ord::pre} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals(array('ord', 'pre'), $tokens[0]->caseKeys());
        $this->assertEquals('count::ord::pre', $tokens[0]->pipelessName());
        $this->assertEquals('count', $tokens[0]->caselessName());
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(true, $tokens[0]->hasCases());
    }

    public function testTypes() {
        $label = "This is your {count:number} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(array('number'), $tokens[0]->types());

        $label = "This is your {count:number:value} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(array('number', 'value'), $tokens[0]->types());

        $label = "This is your {count:number::ord} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals(array('ord'), $tokens[0]->caseKeys());
        $this->assertEquals('count:number::ord', $tokens[0]->pipelessName());
        $this->assertEquals('count:number', $tokens[0]->caselessName());
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(true, $tokens[0]->hasCases());
        $this->assertEquals(array('number'), $tokens[0]->types());

        $label = "This is your {count:number::ord::pre} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\DataToken', get_class($tokens[0]));
        $this->assertEquals(array('ord', 'pre'), $tokens[0]->caseKeys());
        $this->assertEquals('count:number::ord::pre', $tokens[0]->pipelessName());
        $this->assertEquals('count:number', $tokens[0]->caselessName());
        $this->assertEquals('count', $tokens[0]->name());
        $this->assertEquals(true, $tokens[0]->hasCases());
        $this->assertEquals(array('number'), $tokens[0]->types());
    }

    public function testLanguageRuleClasses() {
        $label = "This is your {count:number} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\NumericRule'), $tokens[0]->languageRuleClasses());

        $label = "This is {user:gender}";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\GenderRule'), $tokens[0]->languageRuleClasses());

        $label = "This is {user:gender:value}";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\GenderRule', '\Tr8n\Rules\ValueRule'), $tokens[0]->languageRuleClasses());
    }

    public function testTransformableLanguageRuleClasses() {
        $label = "This is your {count:number} message";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\NumericRule'), $tokens[0]->transformableLanguageRuleClasses());

        $label = "This is {user:gender}";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\GenderRule'), $tokens[0]->transformableLanguageRuleClasses());

        $label = "This is {user:gender:value}";
        $tokens = Base::registerTokens($label, "data");
        $this->assertEquals(array('\Tr8n\Rules\GenderRule', '\Tr8n\Rules\ValueRule'), $tokens[0]->languageRuleClasses());
        $this->assertEquals(array('\Tr8n\Rules\GenderRule'), $tokens[0]->transformableLanguageRuleClasses());
    }
}
