<?php
/**
 * Copyright (c) 2014 Michael Berkovich, http://tr8nhub.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
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