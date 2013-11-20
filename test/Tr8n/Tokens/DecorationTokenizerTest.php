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

class DecorationTokenizerTest extends \BaseTest {

    public function testParsing() {
        $dt = new \Tr8n\Tokens\DecorationTokenizer("Hello World");
        $this->assertEquals(array("[tr8n]", "Hello World", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", "Hello World"), $dt->parse());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello World]");
        $this->assertEquals(array("[tr8n]", "[bold:", " Hello World", "]", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", array("bold", "Hello World")), $dt->parse());

        // broken
        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello World");
        $this->assertEquals(array("[tr8n]", "[bold:", " Hello World", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", array("bold", "Hello World")), $dt->parse());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello [strong: World]]");
        $this->assertEquals(array("[tr8n]", "[bold:", " Hello ", "[strong:", " World", "]", "]", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", array("bold", "Hello ", array("strong", "World"))), $dt->parse());

        // broken
        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello [strong: World]");
        $this->assertEquals(array("[tr8n]", "[bold:", " Hello ", "[strong:", " World", "]", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", array("bold", "Hello ", array("strong", "World"))), $dt->parse());

        // numbers
        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold1: Hello [strong22: World]]");
        $this->assertEquals(array("[tr8n]", "[bold1:", " Hello ", "[strong22:", " World", "]", "]", "[/tr8n]"), $dt->fragments);
        $this->assertEquals(array("tr8n", array("bold1", "Hello ", array("strong22", "World"))), $dt->parse());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello, [strong: how] [weak: are] you?]");
        $this->assertEquals(
            array("[tr8n]", "[bold:", " Hello, ", "[strong:", " how", "]", " ", "[weak:", " are", "]", " you?", "]", "[/tr8n]"),
            $dt->fragments
        );
        $this->assertEquals(
            array("tr8n", array("bold", "Hello, ", array("strong", "how"), " ", array("weak", "are"), " you?")),
            $dt->parse()
        );

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello, [strong: how [weak: are] you?]");
        $this->assertEquals(
            array("[tr8n]", "[bold:", " Hello, ", "[strong:", " how ", "[weak:", " are", "]", " you?", "]", "[/tr8n]"),
            $dt->fragments
        );
        $this->assertEquals(
            array("tr8n", array("bold", "Hello, ", array("strong", "how ", array("weak", "are"), " you?"))),
            $dt->parse()
        );

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link: you have [italic: [bold: {count}] messages] [light: in your mailbox]]");
        $this->assertEquals(
            array("[tr8n]", "[link:", " you have ", "[italic:", " ", "[bold:", " {count}", "]", " messages", "]", " ", "[light:", " in your mailbox", "]", "]", "[/tr8n]"),
            $dt->fragments
        );
        $this->assertEquals(
            array("tr8n", array("link", "you have ", array("italic", "", array("bold", "{count}"), " messages"), " ", array("light", "in your mailbox"))),
            $dt->parse()
        );

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link] you have [italic: [bold: {count}] messages] [light: in your mailbox] [/link]");
        $this->assertEquals(
            array("[tr8n]", "[link]", " you have ", "[italic:", " ", "[bold:", " {count}", "]", " messages", "]", " ", "[light:", " in your mailbox", "]", " ", "[/link]", "[/tr8n]"),
            $dt->fragments
        );
        $this->assertEquals(
            array("tr8n", array("link", " you have ", array("italic", "", array("bold", "{count}"), " messages"), " ", array("light", "in your mailbox"), " ")),
            $dt->parse()
        );
    }

    public function testSubstitution() {
        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold: Hello World]");
        $this->assertEquals("<strong>Hello World</strong>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold]Hello World[/bold]");
        $this->assertEquals("<strong>Hello World</strong>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[bold] Hello World [/bold]");
        $this->assertEquals("<strong> Hello World </strong>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[p: Hello World]", array("p" => '<p>{$0}</p>'));
        $this->assertEquals("<p>Hello World</p>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[p: Hello World]", array("p" => function($v){return "<p>$v</p>";}));
        $this->assertEquals("<p>Hello World</p>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[p]Hello World[/p]", array("p" => function($v){return "<p>$v</p>";}));
        $this->assertEquals("<p>Hello World</p>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link: you have 5 messages]", array("link" => function($v){return "<a href='http://mail.google.com'>$v</a>";}));
        $this->assertEquals("<a href='http://mail.google.com'>you have 5 messages</a>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link: you have {count||message}]", array("link" => function($v){return "<a href='http://mail.google.com'>$v</a>";}));
        $this->assertEquals("<a href='http://mail.google.com'>you have {count||message}</a>", $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link]you have 5 messages[/link]", array("link" => '<a href="http://mail.google.com">{$0}</a>'));
        $this->assertEquals('<a href="http://mail.google.com">you have 5 messages</a>', $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link]you have {count||message}[/link]", array("link" => '<a href="http://mail.google.com">{$0}</a>'));
        $this->assertEquals('<a href="http://mail.google.com">you have {count||message}</a>', $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link]you have [bold: {count||message}][/link]", array("link" => '<a href="http://mail.google.com">{$0}</a>'));
        $this->assertEquals('<a href="http://mail.google.com">you have <strong>{count||message}</strong></a>', $dt->substitute());

        $dt = new \Tr8n\Tokens\DecorationTokenizer("[link]you have [bold: [italic: {count}] {count||message}][/link]", array("link" => '<a href="http://mail.google.com">{$0}</a>'));
        $this->assertEquals('<a href="http://mail.google.com">you have <strong><i>{count}</i> {count||message}</strong></a>', $dt->substitute());


        $dt = new \Tr8n\Tokens\DecorationTokenizer("[p] This document will provide you with some examples of how to use TML for internationalizing your application. The same document is present with every Tr8n Client SDK to ensure that all samples work the same. [/p]", array("p" => '<p>{$0}</p>'));
        $this->assertEquals('<p> This document will provide you with some examples of how to use TML for internationalizing your application. The same document is present with every Tr8n Client SDK to ensure that all samples work the same. </p>', $dt->substitute());

    }

}