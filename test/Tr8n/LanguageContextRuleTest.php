<?php
/**
 * Copyright (c) 2013 Michael Berkovich, tr8nhub.com
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

namespace Tr8n;

require_once(__DIR__."/../BaseTest.php");

class LanguageContextRuleTest extends \BaseTest {

    public function testFallbackRule() {
        $rule = new \Tr8n\LanguageContextRule(array("keyword" => "one", "conditions" => "(= 1 @n)", "examples" => "1"));
        $this->assertFalse($rule->isFallback());

        $rule = new \Tr8n\LanguageContextRule(array("keyword" => "other", "examples" => "0, 2-999; 1.2, 2.07..."));
        $this->assertTrue($rule->isFallback());
    }


    public function testEvaluatingRule() {
        $rule = new \Tr8n\LanguageContextRule(array("keyword" => "one", "conditions" => "(= 1 @n)", "examples" => "1"));
        $this->assertFalse($rule->evaluate());
        $this->assertTrue($rule->evaluate(array("@n" => 1)));
        $this->assertFalse($rule->evaluate(array("@n" => 2)));
        $this->assertFalse($rule->evaluate(array("@n" => 0)));

        $one = new \Tr8n\LanguageContextRule(array("keyword" => "one", "conditions" => "(&& (= 1 (mod @n 10)) (!= 11 (mod @n 100)))", "description" => "array(n) mod 10 is 1 and array(n) mod 100 is not 11", "examples" => "1, 21, 31, 41, 51, 61..."));
        $few = new \Tr8n\LanguageContextRule(array("keyword" => "few", "conditions" => "(&& (in '2..4' (mod @n 10)) (not (in '12..14' (mod @n 100))))", "description" => "array(n) mod 10 in 2..4 and array(n) mod 100 not in 12..14", "examples" => "2-4, 22-24, 32-34..."));
        $many = new \Tr8n\LanguageContextRule(array("keyword" => "many", "conditions" => "(|| (= 0 (mod @n 10)) (in '5..9' (mod @n 10)) (in '11..14' (mod @n 100)))", "description" => "array(n) mod 10 is 0 or array(n) mod 10 in 5..9 or array(n) mod 100 in 11..14", "examples" => "0, 5-20, 25-30, 35-40..."));

        foreach( array(
                    array($one, array(1, 21, 31, 101, 121)),
                    array($few, array(2, 3, 4, 22, 23, 24, 102, 103, 104)),
                    array($many, array(0, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 17, 20, 25, 26, 28, 30, 35, 36, 38, 39, 40))
                 ) as $vals) {
            $rule = $vals[0];
            foreach ($vals[1] as $val) {
                $vars = array("@n" => $val);
                $this->assertTrue($rule->evaluate($vars));
            }
        }

        foreach( array(
                     array($one, array(2, 3, 4, 9)),
                     array($few, array(5, 6, 7, 8, 9)),
                     array($many, array(1, 2, 3, 4))
                 ) as $vals) {
            $rule = $vals[0];
            foreach ($vals[1] as $val) {
                $vars = array("@n" => $val);
                $this->assertFalse($rule->evaluate($vars));
            }
        }

    }
}

