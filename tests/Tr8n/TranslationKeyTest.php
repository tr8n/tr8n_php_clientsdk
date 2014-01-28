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

class TranslationKeyTest extends \BaseTest {

    public function testSubstitution() {
        $english = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));
        $label = "Hello World";
        $tkey = new TranslationKey(array("label" => $label, "language" => $english));
        $this->assertEquals("Hello World", $tkey->substituteTokens($label, array(), $english));

        $user1 = new \User("Michael");
        $user2 = new \User("Alex");

        $label = "Hello {user}";
        $tkey = new TranslationKey(array("label" => $label, "language" => $english));
        $this->assertEquals("Hello Michael", $tkey->substituteTokens($label, array("user" => $user1), $english));

        $label = "Hello {user1} and {user2}";
        $tkey = new TranslationKey(array("label" => $label, "language" => $english));
        $this->assertEquals("Hello Michael and Alex", $tkey->substituteTokens($label, array("user1" => $user1, "user2" => $user2), $english));

        $label = "Hello {user1} [bold: and] {user2}";
        $tkey = new TranslationKey(array("label" => $label, "language" => $english));
        $this->assertEquals("Hello Michael <bold>and</bold> Alex", $tkey->substituteTokens($label,
            array("user1" => $user1, "user2" => $user2, "bold" => '<bold>{$0}</bold>'),
            $english));

        $label = "You have [link: [bold: {count}] messages]";
        $tkey = new TranslationKey(array("label" => $label, "language" => $english));
        $this->assertEquals("You have <a><bold>1</bold> messages</a>", $tkey->substituteTokens($label,
            array("count" => 1, "bold" => '<bold>{$0}</bold>', "link" => '<a>{$0}</a>'),
            $english));
    }

    public function testTranslation() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));
        $tkey = new TranslationKey(array("label" => "Hello World", "language" => $russian));
    }


}
