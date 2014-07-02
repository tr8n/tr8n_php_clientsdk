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

class LanguageContextTest extends \BaseTest {

    public function testMatchingTokens() {
        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/ru/number.json'));

        $this->assertEquals(
            "/.*(count|num|minutes|seconds|hours|sum|total)(\d)*$/",
            $context->token_expression
        );

        foreach(array("num", "num1", "profile_num1", "profile_num21", "total", "total1", "num_hours", "seconds","count1", "count2") as $token) {
            $this->assertTrue(
                $context->isAppliedToToken($token)
            );
        }

        foreach(array("profile", "user", "application", "name") as $token) {
            $this->assertFalse(
                $context->isAppliedToToken($token)
            );
        }

    }

    public function testNumericRules() {
        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/ru/number.json'));

        $this->assertEquals(
            array("@n" => 5),
            $context->vars(5)
        );

        $this->assertEquals(
            "other",
            $context->fallbackRule()->keyword
        );

        foreach(array(1, 101, 1001) as $num) {
            $this->assertEquals(
                "one",
                $context->findMatchingRule($num)->keyword
            );
        }

        foreach(array(2, 22, 32, 102, 132) as $num) {
            $this->assertEquals(
                "few",
                $context->findMatchingRule($num)->keyword
            );
        }

        foreach(array(11, 12, 13, 15, 105, 135) as $num) {
            $this->assertEquals(
                "many",
                $context->findMatchingRule($num)->keyword
            );
        }

    }

    public function testGenderRules() {
        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/ru/gender.json'));

        $this->assertEquals(
            array("@gender" => "male"),
            $context->vars(new \User("Michael", "male"))
        );

        $this->assertEquals(
            array("@gender" => "male"),
            $context->vars(array("object" => new \User("Michael", "male")))
        );

        $this->assertEquals(
            array("@gender" => "male"),
            $context->vars(array("object" => array("name" => "Michael", "gender" => "male"), "attribute" => "name"))
        );

        $this->assertEquals(
            "other",
            $context->fallbackRule()->keyword
        );

        foreach(array(  new \User("Michael", "male"),
                        array("object" => new \User("Michael", "male")),
                        array("object" => array("name" => "Michael", "gender" => "male"), "attribute" => "name")) as $token) {
            $this->assertEquals(
                "male",
                $context->findMatchingRule($token)->keyword
            );
        }

        foreach(array(  new \User("Michael", "female"),
                    array("object" => new \User("Michael", "female")),
                    array("object" => array("name" => "Anna", "gender" => "female"), "attribute" => "name")) as $token) {
            $this->assertEquals(
                "female",
                $context->findMatchingRule($token)->keyword
            );
        }

        foreach(array(  new \User("Michael", "unknown"),
                    array("object" => new \User("Michael", "unknown")),
                    array("object" => array("name" => "Anna", "gender" => "unknown"), "attribute" => "name")) as $token) {
            $this->assertEquals(
                "other",
                $context->findMatchingRule($token)->keyword
            );
        }
    }

}
