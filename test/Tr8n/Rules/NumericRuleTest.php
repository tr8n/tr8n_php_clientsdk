<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Rules;

require_once(__DIR__."/../../BaseTest.php");

class NumericObject {
    private $value;
    function __construct($value) {$this->value = $value;}
    function number() {return 10;}
}

class NumericRuleTest extends \BaseTest {

    public function testRuleKey() {
        $rule = new NumericRule();
        $this->assertEquals("number", $rule->key());
    }

    public function testTransformOptions() {
        $rule = new NumericRule();

        $this->assertEquals(array("one"=>"message", "many"=>"messages"),
                            $rule->defaultTransformOptions(array("message"), "{token}"));

        $this->assertEquals(array("one"=>"person", "many"=>"people"),
                            $rule->defaultTransformOptions(array("person"), "{token}"));

        $this->assertEquals(array("one"=>"person", "many"=>"people"),
                            $rule->defaultTransformOptions(array("person", "people"), "{token}"));
    }

    public function testMethodName() {
        $rule = new NumericRule();

        $this->assertEquals("number", $rule->methodName());
    }

    public function testTokenValue() {
        $rule = new NumericRule();

        $this->assertEquals(1, $rule->tokenValue(1));
        $this->assertEquals(2, $rule->tokenValue(array("number" => 2)));
        $this->assertEquals(5, $rule->tokenValue(array("object" => array("number" => 5))));
        $this->assertEquals(10, $rule->tokenValue(array("object" => new NumericObject(10))));
    }

    public function testRuleEvaluation() {
        $rules = self::loadJSON("rules/ru/numeric.json");
        $rule = new NumericRule($rules["one"]);
        $this->assertEquals(false, $rule->evaluate(0));
        $this->assertEquals(true, $rule->evaluate(1));
        $this->assertEquals(false, $rule->evaluate(2));

        $rule = new NumericRule($rules["few"]);
        $this->assertEquals(false, $rule->evaluate(0));
        $this->assertEquals(false, $rule->evaluate(1));
        $this->assertEquals(true, $rule->evaluate(2));
        $this->assertEquals(false, $rule->evaluate(5));

        $rule = new NumericRule($rules["many"]);
        $this->assertEquals(true, $rule->evaluate(0));
        $this->assertEquals(false, $rule->evaluate(1));
        $this->assertEquals(false, $rule->evaluate(2));
        $this->assertEquals(true, $rule->evaluate(5));
    }



}