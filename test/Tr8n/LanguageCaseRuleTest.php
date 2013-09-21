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

class LanguageCaseRuleTest extends \BaseTest {

    protected $app, $russian, $english;

    protected function setUp() {
        $this->app = new \Tr8n\Application(self::loadJSON('application.json'));
        $this->english = $this->app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $this->russian = $this->app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));
    }

    public function testEvaluation() {
        $case = $this->english->languageCase("plural");
        $rule = $case->rules[0];
        $this->assertEquals(
            "(in 'sheep,fish,series,species,money,rice,information,equipment' @value)",
            $rule->conditions()
        );

        $this->assertTrue(
            $rule->evaluate('sheep')
        );

        $this->assertEquals(
            "sheep",
            $rule->apply('sheep')
        );

        $this->assertTrue(
            $rule->evaluate('information')
        );

        $this->assertFalse(
            $rule->evaluate('horse')
        );
    }

    public function testApplication() {
        $case = $this->english->languageCase("times");
        $rule = $case->rules[0];
        $this->assertEquals(
            "(= 1 @value)",
            $rule->conditions()
        );

        $this->assertTrue(
            $rule->evaluate(1)
        );

        $this->assertFalse(
            $rule->evaluate(2)
        );

        $this->assertEquals(
            'once',
            $rule->apply(1)
        );
    }
}

