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

class ParserTest extends \BaseTest {

    public function testParsingTokens() {
        $parser = new \Tr8n\RulesEngine\Parser("(= 1 (mod n 10))");
        $this->assertEquals(array("(", "=", "1", "(", "mod", "n", "10", ")", ")"), $parser->tokens);

        $parser = new \Tr8n\RulesEngine\Parser("(&& (= 1 (mod @n 10)) (!= 11 (mod @n 100)))");
        $this->assertEquals(array("(", "&&", "(", "=", "1", "(", "mod", "@n", "10", ")", ")", "(", "!=", "11", "(", "mod", "@n", "100", ")", ")", ")"), $parser->tokens);
    }

    public function testParsingExpressions() {
        foreach(array(
                        "@value"                    => "@value",
                        "(= 1 1)"                   => array("=", 1, 1),
                        "(+ 1 1)"                   => array("+", 1, 1),
                        "(= 1 (mod n 10))"          => array("=", 1, array("mod", "n", 10)),
                        "(&& 1 1)"                  => array("&&", 1, 1),
                        "(mod @n 10)"               => array("mod", "@n", 10),
                        "(&& (= 1 (mod @n 10)) (!= 11 (mod @n 100)))"
                                                 => array("&&", array("=", 1, array("mod", "@n", 10)), array("!=", 11, array("mod", "@n", 100))),
                        "(&& (in '2..4' (mod @n 10)) (not (in '12..14' (mod @n 100))))"
                                                 => array("&&", array("in", "2..4", array("mod", "@n", 10)), array("not", array("in", "12..14", array("mod", "@n", 100)))),
                        "(|| (= 0 (mod @n 10)) (in '5..9' (mod @n 10)) (in '11..14' (mod @n 100)))"
                                                 => array("||", array("=", 0, array("mod", "@n", 10)), array("in", "5..9", array("mod", "@n", 10)), array("in", "11..14", array("mod", "@n", 100)))
                ) as $source => $target) {
            $parser = new \Tr8n\RulesEngine\Parser($source);
            $this->assertEquals($target, $parser->parse());
        }
    }
}