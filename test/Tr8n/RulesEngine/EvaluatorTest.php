<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\RulesEngine;

require_once(__DIR__."/../../BaseTest.php");

class EvaluatorTest extends \BaseTest {

    public function testEvaluatingStandardExpressions() {
        $e = new \Tr8n\RulesEngine\Evaluator();

        $e->evaluate(array("label", "@greeting", "hello world"));
        $this->assertEquals($e->vars, array("@greeting" => "hello world"));

        $this->assertEquals(
            array(1,2,3),
            $e->evaluate(array("quote", array(1,2,3)))
        );

        $this->assertEquals(
            array("a","b","c"),
            $e->evaluate(array("quote", array("a","b","c")))
        );

        $this->assertEquals(
            1,
            $e->evaluate(array("car", array("+",1,2)))
        );

        $this->assertEquals(
            array(1,2),
            $e->evaluate(array("cdr", array("+",1,2)))
        );

        $this->assertEquals(
            array(1,2,3),
            $e->evaluate(array("cons", 1, array("quote", array(2, 3))))
        );

        $this->assertTrue(
            $e->evaluate(array("eq", 1, 1))
        );

        foreach (array("hello", 1, 1.3) as $atom) {
            $this->assertTrue(
                $e->evaluate(array("atom", $atom))
            );
        }

        $this->assertEquals(
            1,
            $e->evaluate(array("cond", array("eq", 1, 1), 1, 0))
        );

        $this->assertEquals(
            0,
            $e->evaluate(array("cond", array("eq", 1, 2), 1, 0))
        );
    }

    public function testEvaluatingExtensions() {
        $e = new \Tr8n\RulesEngine\Evaluator();

        foreach(array(
                    array(true, array("=", 1, 1)),
                    array(false, array("=", 2, 1)),

                    array(true, array("!=", 2, 1)),
                    array(false, array("!=", 2, 2)),

                    array(true, array(">", 3, 2)),
                    array(false, array(">", 3, 5)),

                    array(false, array("<", 3, 2)),
                    array(true, array("<", 3, 5)),

                    array(3, array("+", 1, 2)),
                    array(1, array("+", -1, 2)),
                    array(20, array("*", 2, 10)),

                    array(true, array("true")),
                    array(false, array("false")),

                    array(true, array("!", array("=", 1, 2))),

                    array(true, array("&&", array("=", 1, 1), array("=", 10, array("/", 20, 2)))),
                    array(true, array("||", array("=", 1, 2), array("=", 10, array("/", 20, 2)))),
                    array(true, array("and", array("=", 1, 1), array("=", 10, array("/", 20, 2)))),
                    array(true, array("or", array("=", 1, 2), array("=", 10, array("/", 20, 2)))),

                    array(0, array("if", array("=", 1, 2), 1, 0)),

                    array(3, array("%", 23, 10)),
                    array(3, array("mod", 23, 10)),
                    array(2, array("mod", 2.3, 10)),

                    array(true, array("match", "hello", "hello world")),
                    array(true, array("match", "/hello/", "hello world")),
                    array(true, array("match", "/^h/", "hello world")),
                    array(false, array("match", "/^e/", "hello world")),
                    array(true, array("match", "/^h.*d$/", "hello world")),

                    array(true, array("in", "1,2", "1")),
                    array(true, array("in", "1,2", 1)),
                    array(true, array("in", "1..10", 5)),
                    array(false, array("in", "1..10", 15)),
                    array(true, array("in", "a,b,c", 'a')),
                    array(true, array("in", "a..c, d..z", 'h')),
                    array(false, array("in", "a..c, e..g", 'd')),

                    array(true, array("within", "0..3", 1.5)),
                    array(true, array("within", "0..3", "1.5")),
                    array(false, array("within", "0..1", "1.5")),

                    array("hi world", array("replace", "/^hello/", "hi", "hello world")),
                    array("hella warld", array("replace", "o", "a", "hello world")),
                    array("hello moon", array("replace", "/world$/", "moon", "hello world")),
                    array("vertex", array("replace", "/(vert|ind)ices$/i", "$1ex", "vertices")),

                    array("hello world", "hello world"),

                    array("hello world", array("append", "world", "hello ")),
                    array("hello world", array("prepend", "hello ", "world")),

                    array(5, array("count", array(1,2,3,4,5))),
                    array(false, array("all", array(1,2,3,4,5), 1)),
                    array(true, array("all", array(1,1,1), 1)),

                    array(true, array("any", array(1,2,3,4,5), 1)),
                    array(false, array("any", array(2,3,4,5), 1)),

                ) as $test) {
            $this->assertEquals($test[0], $e->evaluate($test[1]));
        }

        $e->evaluate(array("let", "@n", 1));
        $this->assertEquals(
            array("@n" => 1),
            $e->vars
        );
        $this->assertTrue($e->evaluate(array("=", "@n", 1)));

        $e->evaluate(array("let", "@n", 11));
        $this->assertEquals(
            array("@n" => 11),
            $e->vars
        );

        $this->assertTrue($e->evaluate(array("=", "@n", 11)));
        $this->assertTrue($e->evaluate(array("and", array("=", "@n", 11), array("=", 22, array("*", "@n", 2)))));

        $e->reset();
        $e->evaluate(array("let", "@arr", array(1,2,3,4,5)));
        $this->assertEquals(
            array("@arr" => array(1,2,3,4,5)),
            $e->vars
        );

        $this->assertEquals(
            array(1,2,3,4,5),
            $e->evaluate("@arr")
        );

        $this->assertEquals(
            5,
            $e->evaluate(array("count", "@arr"))
        );


        $e->reset();

        $e->evaluate(array("let", "@genders", array("male", "female", "male")));
        $this->assertTrue(
            $e->evaluate(array("any", "@genders", "male"))
        );
        $this->assertTrue(
            $e->evaluate(array("any", "@genders", "female"))
        );
        $this->assertFalse(
            $e->evaluate(array("all", "@genders", "male"))
        );
        $this->assertFalse(
            $e->evaluate(array("all", "@genders", "female"))
        );
        $this->assertTrue(
            $e->evaluate(array("&&", array("any", "@genders", "female"), array("any", "@genders", "male")))
        );
    }

    public function testEvaluatingExpressions() {
        $e = new \Tr8n\RulesEngine\Evaluator();

        $rules = array(
            "one"   => array("&&", array("=", 1, array("mod", "@n", 10)), array("!=", 11, array("mod", "@n", 100))),
            "few"   => array("&&", array("in", "2..4", array("mod", "@n", 10)), array("not", array("in", "12..14", array("mod", "@n", 100)))),
            "many"  => array("||", array("=", 0, array("mod", "@n", 10)), array("in", "5..9", array("mod", "@n", 10)), array("in", "11..14", array("mod", "@n", 100)))
        );

        $results = array(
          "one" => array(1, 21, 31, 41, 51, 61),
          "few" => array(2,3,4, 22,23,24, 32,33,34),
          "many" => array(0, 5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20, 25,26,27,28,29,30, 35,36,37,38,39,40)
        );
        
        foreach($results as $key => $values) {
            foreach($values as $value) {
                $e->evaluate(array("let", "@n", $value));
                $this->assertTrue(
                    $e->evaluate($rules[$key])
                );
            }
        }
    }

}