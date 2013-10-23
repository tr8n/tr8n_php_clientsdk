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

use Tr8n\Utils\HtmlTranslator;
use Tr8n\Utils\StringUtils;

require_once(__DIR__ . "/../../BaseTest.php");

class HtmlTokenizerTest extends \BaseTest {

    /**
     * General Tokenizer
     */
    public function testHTMLParsingWithoutWhitelist() {
        $ht = new \Tr8n\Tokens\HtmlTokenizer("<p>Hello <a href='http://www.google.com'>World</a></p>");
        $this->assertEquals("[p]Hello [link: World][/p]\n\n", $ht->tml);
        $this->assertEquals(array(
            'link'  => '<a href=\'http://www.google.com\'>{$0}</a>',
            'p'     => '<p>{$0}</p>'
        ), $ht->context);

        $ht->tokenize("<p> Hello <a href='http://www.google.com'>World</a></p>");
        $this->assertEquals("[p]Hello [link: World][/p]\n\n", $ht->tml);
        $this->assertEquals(array(
            'link'  => '<a href=\'http://www.google.com\'>{$0}</a>',
            'p'     => '<p>{$0}</p>'
        ), $ht->context);

        $ht->tokenize("This is pretty <b>awesome</b>!");
        $this->assertEquals("[p]This is pretty [bold: awesome]![/p]\n\n", $ht->tml);

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