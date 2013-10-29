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

class HtmlTokenizerTest extends \BaseTest
{
    /**
     * @var HtmlTranslator
     */
    protected $htmlTranslator;

    /**
     * @var HtmlTranslator
     */
    protected $htmlTranslatorNumeric;

    public function setUp()
    {
        $this->htmlTranslator = new HtmlTranslator();
        $this->htmlTranslator->options = array("debug" => true, "debug_format" => '{{ {$0} }}', "data_tokens.numeric" => false, "data_tokens.special" => true);

        $this->htmlTranslatorNumeric = new HtmlTranslator();
        $this->htmlTranslatorNumeric->options = array("debug" => true, "debug_format" => '{{ {$0} }}', "data_tokens.numeric" => true, "data_tokens.numeric_name" => "count");

    }

    /**
     * HTML translator
     */
    public function untestHTMLParsingWithWhitelist() {
        $ht = new HtmlTranslator("<p style='font-size:10px'>Hello <b>World</b></p>");
        $ht->options = array("debug" => true);
        $ht->debug();
        print_r($ht->translate());

    }

    public function translationProvider()
    {
        return array(
            array("Hello World", // DOM will self correct text to a paragraph.
                "<p>{{ Hello World }}</p>"),

            array("Special characters: &nbsp; &frac34;",
                "<p>{{ Special characters: {nbsp} {frac34} }}</p>"),

            array("<p>Hello World</p>",
                "<p>{{ Hello World }}</p>"),

            array("<p>Hello <b>World</b></p>",
                "<p>{{ Hello [bold: World] }}</p>"),

            array("<p style='font-size:10px'>Hello <b>World</b></p>",
                "<p style='font-size:10px'>{{ Hello [bold: World] }}</p>"),

            array("<p style=\"font-size:10px\">Hello <b>World</b></p>",
                "<p style='font-size:10px'>{{ Hello [bold: World] }}</p>"),

            array("<div>Hello World</div>",
                "<div>{{ Hello World }}</div>"),

            array("<div>Hello <div>World</div></div>",
                "<div>{{ Hello }}<div>{{ World }}</div></div>"),

            array("<div>Level 1 <div>Level 2 <div>Level 3</div></div></div>",
                "<div>{{ Level 1 }}<div>{{ Level 2 }}<div>{{ Level 3 }}</div></div></div>"),

            array("<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div>",
                "<div class='1'>{{ Level 1 }}<div class='2'>{{ Level 2 }}<div class='3'>{{ Level 3 }}</div></div></div>"),

            array("<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div><div>Another Level 1 div</div>",
                "<div class='1'>{{ Level 1 }}<div class='2'>{{ Level 2 }}<div class='3'>{{ Level 3 }}</div></div></div><div>{{ Another Level 1 div }}</div>"),

            array("<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div> \n<div>Another Level 1 div</div>",
                "<div class='1'>{{ Level 1 }}<div class='2'>{{ Level 2 }}<div class='3'>{{ Level 3 }}</div></div></div><div>{{ Another Level 1 div }}</div>"),

            array("<div>Hello <b>My</b> <div class=''>World!</div> This is awesome!</div>",
                "<div>{{ Hello [bold: My] }}<div class=''>{{ World! }}</div>{{ This is awesome! }}</div>"),

            array("<div>Hello <b>My</b> <div>World!</div> This is awesome!</div>",
                "<div>{{ Hello [bold: My] }}<div>{{ World! }}</div>{{ This is awesome! }}</div>"),

            array("<div>Hello <b>My</b> <span>World!</span> I love you!</div>",
                "<div>{{ Hello [bold: My] [span: World!] I love you! }}</div>"),

            array("<div><div>Hello</div><div>World</div></div>",
                "<div><div>{{ Hello }}</div><div>{{ World }}</div></div>"),

            array("<div> <div>Hello</div> <div>World</div> </div>",
                "<div><div>{{ Hello }}</div><div>{{ World }}</div></div>"),

            array("<div> <div> Hello </div> <div> World </div> </div>",
                "<div><div>{{ Hello }}</div><div>{{ World }}</div></div>"),

            array("<table><tr><td>Name</td><td>Value</td></tr></table>",
                "<table><tr><td>{{ Name }}</td><td>{{ Value }}</td></tr></table>"),

            array("Hello <p>World</p>",
                "<p>{{ Hello }}</p><p>{{ World }}</p>"),

            array("Hello <b>World</b>",
                "<p>{{ Hello [bold: World] }}</p>"),

            array("<i>Hello <b>World</b></i>",
                "{{ [italic]Hello [bold: World][/italic] }}"),

            array("<div>Hello <br> World</div>",
                "<div>{{ Hello }}<br/>{{ World }}</div>"),

            array("I give you <img src='thumbs_up.gif'> for this idea",
                "<p>{{ I give you {picture} for this idea }}</p>"),

            array("<p>Hello <span>World</span></p>\n\n<p>This is very cool</p>",
                "<p>{{ Hello [span: World] }}</p><p>{{ This is very cool }}</p>"),

            array("<div><p>Hello <span>World</span></p></div><p>This is very cool</p>",
                "<div><p>{{ Hello [span: World] }}</p></div><p>{{ This is very cool }}</p>"),

            array("<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>",
                "{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}"),

            array("<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>",
                "<p>{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}</p>"),

            array("<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>\n\n<p>Another test</p>",
                "<p>{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}</p><p>{{ Another test }}</p>"),

            array("<p>Some sentence<br><br>Another sentence<br><br>Third sentence</p>",
                "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third sentence }}</p>"),

            array("<p>Some sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>",
                "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>"),

            array("<p><i>Some</i> sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>",
                "<p>{{ [italic: Some] sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>"),

        );
    }


    /**
     * @dataProvider translationProvider
     */
    public function testTranslate($source, $target)
    {
        $this->assertEquals($target, $this->htmlTranslator->translate($source));
    }

    public function numericTranslationProvider()
    {
        return array(
            array("You have 5 messages.",
                "<p>{{ You have {count} messages. }}</p>"),

            array("3 messages were sent.",
                "<p>{{ {count} messages were sent. }}</p>"),
        );
    }

    /**
     * @dataProvider numericTranslationProvider
     */
    public function testTranslateNumeric($pair)
    {
        list($source, $target) = $pair;
        $this->htmlTranslatorNumeric->translate($source, $target);
    }
}