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

namespace Tr8n\Tokens;

require_once(__DIR__."/../../BaseTest.php");

class MethodTokenTest extends \BaseTest {

    public function testParsing() {
        $dt = new \Tr8n\Tokens\DataTokenizer("Hello {user.name}");
        $this->assertEquals(1, count($dt->tokens));
        $this->assertEquals('Tr8n\Tokens\MethodToken', get_class($dt->tokens[0]));
    }

    public function testObjectName() {
        $dt = new \Tr8n\Tokens\DataTokenizer("Hello {user.name}");
        $this->assertEquals("user", $dt->tokens[0]->objectName());
    }

    public function testObjectMethod() {
        $dt = new \Tr8n\Tokens\DataTokenizer("Hello {user.name}");
        $this->assertEquals("name", $dt->tokens[0]->objectMethod());
    }

    public function testSubstitution() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));

        $label = "Hello {user.name}";
        $dt = new \Tr8n\Tokens\DataTokenizer("Hello {user.name}");
        $user = new \User("Michael");

        $this->assertEquals("Hello Michael", $dt->tokens[0]->substitute($label, array("user" => $user), $russian));
    }

}