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
     * HTML translator
     */
    public function testHTMLParsingWithWhitelist() {
        $ht = new HtmlTranslator();
        $ht->options = array("debug" => true);


        // In the debug mode, {{ }} means translated as a separate key
        // Anything outside of {{ }} is treated as normal HTML

        foreach(
            array(
                "Hello World"                       // DOM will self correct text to a paragraph.
                => "<p>{{ Hello World }}</p>",

                "<p>Hello World</p>"
                => "<p>{{ Hello World }}</p>",

                "<div>Hello World</div>"
                => "<div>{{ Hello World }}</div>",

//                "<div>Hello <div>World</div></div>"
//                    => "<div>{{ Hello }}<div>{{ World }}</div></div>",

                "<div><div>Hello</div><div>World</div></div>"
                => "<div><div>{{ Hello }}</div><div>{{ World }}</div></div>",

                "<div> <div>Hello</div> <div>World</div> </div>"
                => "<div> <div>{{ Hello }}</div> <div>{{ World }}</div> </div>",

                "<div> <div> Hello </div> <div> World </div> </div>"
                => "<div> <div>{{ Hello }}</div> <div>{{ World }}</div> </div>",

                "<table><tr><td>Name</td><td>Value</td></tr></table>"
                => "<table><tr><td>{{ Name }}</td><td>{{ Value }}</td></tr></table>",

                "Hello <p>World</p>"
                => "<p>{{ Hello }}</p><p>{{ World }}</p>",

                "Hello <b>World</b>"
                => "<p>{{ Hello [bold: World] }}</p>",

                "<i>Hello <b>World</b></i>"
                => "{{ [italic]Hello [bold: World][/italic] }}",

                "<div>Hello <br> World</div>"
                => "<div>{{ Hello }}<br/>{{ World }}</div>",

                "I give you <img src='thumbs_up.gif'> for this idea"
                => "<p>{{ I give you {picture} for this idea }}</p>",

                "<p>Hello <span>World</span></p>\n\n<p>This is very cool</p>"
                => "<p>{{ Hello [span: World] }}</p> <p>{{ This is very cool }}</p>",

                "<div><p>Hello <span>World</span></p></div><p>This is very cool</p>"
                => "<div><p>{{ Hello [span: World] }}</p></div><p>{{ This is very cool }}</p>",

                "<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>"
                => "{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}",

                "<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>"
                => "<p>{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}</p>",

                "<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>\n\n<p>Another test</p>"
                => "<p>{{ [span2]Message = [span1]Hello [span: World][/span1][/span2] }}</p> <p>{{ Another test }}</p>",

                "<p>Some sentence<br><br>Another sentence<br><br>Third sentence</p>"
                => "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third sentence }}</p>",

                "<p>Some sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>"
                => "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>",

                "<p><i>Some</i> sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>"
                => "<p>{{ [italic: Some] sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>"


            ) as $source => $target) {

            $this->assertEquals($target, $ht->translate($source));

        };

//        $ht->debug();
//        print_r($ht->translate());

    }
}