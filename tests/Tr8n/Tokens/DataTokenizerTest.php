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

class DataTokenizerTest extends \BaseTest {

    public function testParsing() {
        $dt = new \Tr8n\Tokens\DataTokenizer("Hello World");
        $this->assertEquals(array(), $dt->tokens);

        $dt = new \Tr8n\Tokens\DataTokenizer("Hello {world}");
        $this->assertEquals(1, count($dt->tokens));
        $this->assertEquals('world', $dt->tokens[0]->name());
        $this->assertEquals('{world}', $dt->tokens[0]->name(array("parens"=>true)));

        $dt = new \Tr8n\Tokens\DataTokenizer("Dear {user:gender}");
        $this->assertEquals(1, count($dt->tokens));
        $this->assertEquals('user', $dt->tokens[0]->name());
        $this->assertEquals('{user}', $dt->tokens[0]->name(array("parens"=>true)));
        $this->assertEquals(array('gender'), $dt->tokens[0]->context_keys);
        $this->assertEquals('{user:gender}', $dt->tokens[0]->name(array("parens"=>true, "context_keys"=>true)));

        $dt = new \Tr8n\Tokens\DataTokenizer("Dear {user}, you have {count||message}.");
        $this->assertEquals(2, count($dt->tokens));
        $this->assertEquals('user', $dt->tokens[0]->short_name);
        $this->assertEquals('count', $dt->tokens[1]->short_name);
    }
}