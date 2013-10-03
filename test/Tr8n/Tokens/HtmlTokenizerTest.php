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

use Tr8n\Utils\StringUtils;

require_once(__DIR__ . "/../../BaseTest.php");

class HtmlTokenizerTest extends \BaseTest {

//    public function testConvertToTml() {
//        $text = '<span>Hello <strong>World</strong></span>';
//
//        $p = new \Tr8n\Tokens\HtmlTokenizer($text);
//
//        $this->assertEquals(
//            "[span: Hello World]",
//            $p->evaluate(array("span", array(), "Hello World"))
//        );
//
//        $this->assertEquals(
//            array("<tr8n>", "<span>", "Hello ", "<strong>", "World", "</strong>", "</span>", "</tr8n>", ""),
//            $p->tokenize()
//        );
//
//        $this->assertEquals(
//            array("tr8n", array(), array("span", array(), "Hello ", array("strong", array(), "World"))),
//            $p->parse()
//        );
//
//        $this->assertEquals(
//            "[span: Hello [strong: World]]",
//            $p->evaluate(array("tr8n", array(), array("span", array(), "Hello ", array("strong", array(), "World"))))
//        );
//
//        $this->assertEquals(
//            "[span: Hello [strong: World]]",
//            $p->process()
//        );
//
//        $this->assertEquals(
//            "[span: Hello [strong: World]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate('<span>Hello <strong>World</strong></span>')
//        );
//
//        $p = new \Tr8n\Tokens\HtmlTokenizer('The message is: <span>Hello <strong>World</strong></span>');
//        $this->assertEquals(
//            "The message is: [span: Hello [strong: World]]",
//            $p->process()
//        );
//
//        $p = new \Tr8n\Tokens\HtmlTokenizer('The message is: <span>Hello <strong>World</strong></span>');
//        $this->assertEquals(
//            "The message is: [span: Hello [strong: World]]",
//            $p->process()
//        );
//
//        $this->assertEquals(
//            "The message is: [span: Hello [strong: World]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate('The message is: <span>Hello <strong>World</strong></span>')
//        );
//
//        $this->assertEquals(
//            "[link: Click here] to visit Google",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<a href='http://www.google.com'>Click here</a> to visit Google")
//        );
//
//        $this->assertEquals(
//            "[link: Click [strong: here]] to visit Google",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<a href='http://www.google.com'>Click <strong>here</strong></a> to visit Google")
//        );
//
//        $this->assertEquals(
//            "[span: Hello World]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span>Hello World</span>")
//        );
//
//        $this->assertEquals(
//            "[span: Hello [span: World]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span>Hello <span>World</span></span>")
//        );
//
//        $this->assertEquals(
//            "[span1: Hello [span: World]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span style='font-weight:bold;'>Hello <span>World</span></span>")
//        );
//
//        $this->assertEquals(
//            "[span2: Message = [span1: Hello [span: World]]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>")
//        );
//
//        $this->assertEquals(
//            "[span1: Message = [span1: Hello [span: World]]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span style='font-weight:bold;'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>")
//        );
//
//        $this->assertEquals(
//            "[p: [div: Message = [span1: Hello [span: World]]]]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<p><div style='font-weight:bold;'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></div></p>")
//        );
//
//        $this->assertEquals(
//            "Hello {br} World",
//            \Tr8n\Tokens\HtmlTokenizer::translate("Hello <br> World")
//        );
//
//        $this->assertEquals(
//            "[span: Hello {br} World]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span>Hello <br/> World</span>")
//        );
//
//        $this->assertEquals(
//            "[span: Hello {hr} World]",
//            \Tr8n\Tokens\HtmlTokenizer::translate("<span>Hello <hr/> World</span>")
//        );
//
//    }

    public function testHTMLParsing() {
        $ht = new \Tr8n\Tokens\HtmlTokenizer("<p>Hello <a href='http://www.google.com'>World</a></p>");
        $this->assertEquals('[p]Hello [link: World][/p]', $ht->tml);
        $this->assertEquals(array(
            'link'  => '<a href=\'http://www.google.com\'>{$0}</a>',
            'p'     => '<p>{$0}</p>'
        ), $ht->context);

        $ht->tokenize("<p> Hello <a href='http://www.google.com'>World</a></p>");
        $this->assertEquals('[p] Hello [link: World][/p]', $ht->tml);
        $this->assertEquals(array(
            'link'  => '<a href=\'http://www.google.com\'>{$0}</a>',
            'p'     => '<p>{$0}</p>'
        ), $ht->context);

        $ht->tokenize("This is pretty <b>awesome</b>!");
        $this->assertEquals('[p]This is pretty [bold: awesome]![/p]', $ht->tml);

        $ht->tokenize("<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>");
        $this->assertEquals('[span2]Message = [span1]Hello [span: World][/span1][/span2]', $ht->tml);
        $this->assertEquals(array(
            'p'     => '<p>{$0}</p>',
            'bold'  => '<b>{$0}</b>',
            'link'  => '<a href=\'http://www.google.com\'>{$0}</a>',
            'span'  => '<span>{$0}</span>',
            'span1' => '<span style=\'font-weight:bold;\'>{$0}</span>',
            'span2' => '<span style=\'font-family:Arial\'>{$0}</span>'
        ), $ht->context);
    }

}