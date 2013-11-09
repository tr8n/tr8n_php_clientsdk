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

use Tr8n\Utils\HtmlTranslator2;
use Tr8n\Utils\StringUtils;

require_once(__DIR__ . "/../../BaseTest.php");

class HtmlTranslator2Test extends \BaseTest {

  function testTranslator() {
    $ht = new HtmlTranslator("", array(), array("debug" => true, "debug_format" => '{{ {$0} }}', "data_tokens.special" => true));
//    $ht->debug("<div>Hello </div><div>World</div>Some text<br>More text");
//
//    echo $ht->html . "\n\n";
//    echo $ht->translate("<div>Hello </div><div>World</div>Some text<br>More text");

      foreach(
          array(
              array(
                  "html"      => "Hello World",
                  "tml"       => "<p>{{ Hello World }}</p>",
                  "tokens"    => array()
              ),

              array(
                  "html"      => "<p>Hello World</p>",
                  "tml"       => "<p>{{ Hello World }}</p>",
                  "tokens"    => array()
              ),

              array(
                  "html"      => "Hello <b>World</b>",
                  "tml"       => "<p>{{ Hello [bold: World] }}</p>",
                  "tokens"    => array('bold' => '<b>{$0}</b>')
              ),

              array("html"      => "<div><b>Hello</b> <a href='abc'>World</a></div>",
                    "tml"       => "<div>{{ [bold: Hello] [link: World] }}</div>",
                    "tokens"    => array("bold" => '<b>{$0}</b>',
                                         "link" => '<a href=\'abc\'>{$0}</a>')
              ),

              array("html"      => "<b>Hello</b>",
                    "tml"       => "<b>{{ Hello }}</b>",
                    "tokens"    => array()
              ),

              array("html"      => "<i>Hello</i>",
                    "tml"       => "<i>{{ Hello }}</i>",
                    "tokens"    => array()
              ),

              array("html"      => "<b>Hello <i>World</i></b>",
                    "tml"       => "<b>{{ Hello [italic: World] }}</b>",
                    "tokens"    => array('italic' => '<i>{$0}</i>')
              ),

              array("html"      => "<ul><li>Item 1</li><li>Item 2</li></ul>",
                    "tml"       => "<ul><li>{{ Item 1 }}</li><li>{{ Item 2 }}</li></ul>",
                    "tokens"    => array()
              ),

              array("html"      => "<ul><li><b>Item 1</b></li><li>Item 2</li></ul>",
                    "tml"       => "<ul><li><b>{{ Item 1 }}</b></li><li>{{ Item 2 }}</li></ul>",
                    "tokens"    => array()
              ),

              array("html"      => "<ul><li>Another <b>Item</b></li><li>Item 2</li></ul>",
                    "tml"       => "<ul><li>{{ Another [bold: Item] }}</li><li>{{ Item 2 }}</li></ul>",
                    "tokens"    => array()
              ),

              array("html"      => "<div>Hello <a href='abc'>World</a></div>",
                    "tml"       => "<div>{{ Hello [link: World] }}</div>",
                    "tokens"    => array('link' => '<a href=\'abc\'>{$0}</a>')
              ),

              array("html"      => "<p style='font-size:10px'>Hello <b>World</b></p>",
                    "tml"       => "<p style='font-size:10px'>{{ Hello [bold: World] }}</p>",
                    "tokens"    => array('bold' => '<b>{$0}</b>')
              ),

              array("html"      => "<div>Hello World</div>",
                    "tml"       => "<div>{{ Hello World }}</div>",
                    "tokens"    => array()
              ),

              array("html"      => "<div>Hello <div>World</div></div>",
                    "tml"       => "<div>{{ Hello  }}<div>{{ World }}</div></div>",
                    "tokens"    => array()
              ),

              array("html"      => "<div>Level 1 <div>Level 2 <div>Level 3</div></div></div>",
                    "tml"       => "<div>{{ Level 1  }}<div>{{ Level 2  }}<div>{{ Level 3 }}</div></div></div>",
                    "tokens"    => array()
              ),

              array("html"      => "<i>Hello <b>World</b></i>",
                    "tml"       => "<i>{{ Hello [bold: World] }}</i>",
                    "tokens"    => array('bold' => '<b>{$0}</b>')
              ),

              array("html"      => "<p>Some sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>",
                    "tml"       => "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>",
                    "tokens"    => array('bold' => '<b>{$0}</b>')
              ),

              array("html"      => "I give you <img src='thumbs_up.gif'> for this idea",
                    "tml"       => "<p>{{ I give you {picture} for this idea }}</p>",
                    "tokens"    => array('picture' => '<img src=\'thumbs_up.gif\'/>')
              ),

              array("html"      => "<ul><li><a class=\"active-trail dropdown-toggle\" href=\"/about-myheritage\">Company</a><ul><li><a href=\"/about-myheritage\">Overview</a></li></ul></li></ul>",
                    "tml"       => "<ul><li><a href='/about-myheritage' class='active-trail dropdown-toggle'>{{ Company }}</a><ul><li><a href='/about-myheritage'>{{ Overview }}</a></li></ul></li></ul>",
                    "tokens"    => array()
              ),

              array("html"      => "<span>Hello</span><span>World</span>",
                    "tml"       => "{{ [span: Hello][span: World] }}",
                    "tokens"    => array('span' => '<span>{$0}</span>')
              ),

              array("html"    => "<span>Hello</span><br><span>World</span>",
                    "tml"       => "<span>{{ Hello }}</span><br/><span>{{ World }}</span>",
                    "tokens"    => array()
              ),

              array("html"    => "Hello<br><span>World</span>",
                  "tml"       => "<p>{{ Hello }}<br/><span>{{ World }}</span></p>",
                  "tokens"    => array()
              ),

              array("html"    => "Hello<br>Awesome <span>World</span>",
                  "tml"       => "<p>{{ Hello }}<br/>{{ Awesome [span: World] }}</p>",
                  "tokens"    => array('span' => '<span>{$0}</span>')
              ),

              array("html"    => "<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div>",
                  "tml"       => "<div class='1'>{{ Level 1  }}<div class='2'>{{ Level 2  }}<div class='3'>{{ Level 3 }}</div></div></div>",
                  "tokens"    => array()
              ),

              array("html"    => "<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div><div>Another Level 1 div</div>",
                    "tml"     => "<div class='1'>{{ Level 1  }}<div class='2'>{{ Level 2  }}<div class='3'>{{ Level 3 }}</div></div></div><div>{{ Another Level 1 div }}</div>",
                    "tokens"  => array()
              ),

              array("html"    => "Hello <p>World</p>",
                    "tml"     => "<p>{{ Hello  }}</p><p>{{ World }}</p>",
                    "tokens"  => array()
              ),

              array("html"    => "<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>\n\n<p>Another test</p>",
                    "tml"     => "<p><span style='font-family:Arial'>{{ Message = [span1: Hello [span: World]] }}</span></p> <p>{{ Another test }}</p>",
                    "tokens"  => array()
              ),

              array("html"    => "<p>Some sentence<br><br>Another sentence<br><br>Third sentence</p>",
                    "tml"     => "<p>{{ Some sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third sentence }}</p>",
                    "tokens"  => array()
              ),

              array("html"    => "<p><i>Some</i> sentence<br><br>Another sentence<br><br>Third <b>sentence</b></p>",
                    "tml"     => "<p>{{ [italic: Some] sentence }}<br/><br/>{{ Another sentence }}<br/><br/>{{ Third [bold: sentence] }}</p>",
                    "tokens"  => array('bold' => '<b>{$0}</b>')
              ),

              array("html"    => "<p><span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span></p>",
                    "tml"     => "<p><span style='font-family:Arial'>{{ Message = [span1: Hello [span: World]] }}</span></p>",
                    "tokens"  => array('span' => '<span>{$0}</span>', 'span1' => '<span style=\'font-weight:bold;\'>{$0}</span>')
              ),

              array("html"    => "<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>",
                    "tml"     => "<span style='font-family:Arial'>{{ Message = [span1: Hello [span: World]] }}</span>",
                    "tokens"  => array('span' => '<span>{$0}</span>', 'span1' => '<span style=\'font-weight:bold;\'>{$0}</span>')
              ),

              array("html"    => "<div><p>Hello <span>World</span></p></div><p>This is very cool</p>",
                    "tml"     => "<div><p>{{ Hello [span: World] }}</p></div><p>{{ This is very cool }}</p>",
                    "tokens"  => array()
              ),


              array("html"    => "<span>Some text</span><br><strong>Please note</strong> that bla bla.<br>",
                    "tml"     => "<span>{{ Some text }}</span><br/>{{ [strong: Please note] that bla bla. }}<br/>",
                    "tokens"  => array('strong' => '<strong>{$0}</strong>')
              ),

//              "Special characters: &nbsp; &frac34;"
//              => "<p>{{ Special characters: {nbsp} {frac34} }}</p>",
//
//              "<div class='1'>Level 1 <div class='2'>Level 2 <div class='3'>Level 3</div></div></div> <div>Another Level 1 div</div>"
//              => "<div class='1'>{{ Level 1  }}<div class='2'>{{ Level 2  }}<div class='3'>{{ Level 3 }}</div></div></div> <div>{{ Another Level 1 div }}</div>",
//
//              "<div>Hello <b>My</b> <div class=''>World!</div> This is awesome!</div>"
//              => "<div>{{ Hello [bold: My]  }}<div class=''>{{ World! }}</div>{{  This is awesome! }}</div>",
//
//              "<div>Hello <b>My</b> <div>World!</div> This is awesome!</div>"
//              => "<div>{{ Hello [bold: My]  }}<div>{{ World! }}</div>{{  This is awesome! }}</div>",
//
//              "<div>Hello <b>My</b> <span>World!</span> I love you!</div>"
//              => "<div>{{ Hello [bold: My] [span: World!] I love you! }}</div>",
//
//              "<div><div>Hello</div><div>World</div></div>"
//              => "<div><div>{{ Hello }}</div><div>{{ World }}</div></div>",
//
//              "<div> <div>Hello</div> <div>World</div> </div>"
//              => "<div> <div>{{ Hello }}</div> <div>{{ World }}</div> </div>",
//
//              "<div> <div> Hello </div> <div> World </div> </div>"
//              => "<div> <div>{{  Hello  }}</div> <div>{{  World  }}</div> </div>",
//
//              "<table><tr><td>Name</td><td>Value</td></tr></table>"
//              => "<table><tr><td>{{ Name }}</td><td>{{ Value }}</td></tr></table>",
//
//              "<div>Hello <br> World</div>"
//              => "<div>{{ Hello }}<br/>{{ World }}</div>",
//
//              "<p>Hello <span>World</span></p>\n\n<p>This is very cool</p>"
//              => "<p>{{ Hello [span: World] }}</p><p>{{ This is very cool }}</p>",

          ) as $set) {

          $ht->debug($set["html"]);
          $translation = $ht->translate($set["html"]);

          echo "Original:\n";
          echo $ht->html . "\n";
          echo "\nExpected:\n";
          echo $set["tml"] . "\n";
          echo "\nTranslated:\n";
          echo $translation;
          echo "\n\nTokens:\n";
          print_r ($ht->debug_tokens);
          echo "\n---------------------------------------------------------------------------------------------------------------------\n\n";

          $this->assertEquals($set["tml"], $translation);
          $this->assertEquals($set["tokens"], $ht->debug_tokens);

      };
  }

}