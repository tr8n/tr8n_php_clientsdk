<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Rules;

use Tr8n\Utils\StringUtils;

require_once(__DIR__."/../../BaseTest.php");

class TmlUtilsTest extends \BaseTest {

    public function testConvertToTml() {
        $text = '<span>Hello <strong>World</strong></span>';

        $p = new \Tr8n\Utils\HtmlParser($text);

        $this->assertEquals(
            "[span: Hello World]",
            $p->evaluate(array("span", array(), "Hello World"))
        );

        $this->assertEquals(
            array("<tr8n>", "<span>", "Hello ", "<strong>", "World", "</strong>", "</span>", "</tr8n>", ""),
            $p->tokenize()
        );

        $this->assertEquals(
            array("tr8n", array(), array("span", array(), "Hello ", array("strong", array(), "World"))),
            $p->parse()
        );

        $this->assertEquals(
            "[span: Hello [strong: World]]",
            $p->evaluate(array("tr8n", array(), array("span", array(), "Hello ", array("strong", array(), "World"))))
        );

        $this->assertEquals(
            "[span: Hello [strong: World]]",
            $p->process()
        );

        $this->assertEquals(
            "[span: Hello [strong: World]]",
            \Tr8n\Utils\HtmlParser::translate('<span>Hello <strong>World</strong></span>')
        );

        $p = new \Tr8n\Utils\HtmlParser('The message is: <span>Hello <strong>World</strong></span>');
        $this->assertEquals(
            "The message is: [span: Hello [strong: World]]",
            $p->process()
        );

        $p = new \Tr8n\Utils\HtmlParser('The message is: <span>Hello <strong>World</strong></span>');
        $this->assertEquals(
            "The message is: [span: Hello [strong: World]]",
            $p->process()
        );

        $this->assertEquals(
            "The message is: [span: Hello [strong: World]]",
            \Tr8n\Utils\HtmlParser::translate('The message is: <span>Hello <strong>World</strong></span>')
        );

        $this->assertEquals(
            "[link: Click here] to visit Google",
            \Tr8n\Utils\HtmlParser::translate("<a href='http://www.google.com'>Click here</a> to visit Google")
        );

        $this->assertEquals(
            "[link: Click [strong: here]] to visit Google",
            \Tr8n\Utils\HtmlParser::translate("<a href='http://www.google.com'>Click <strong>here</strong></a> to visit Google")
        );

        $this->assertEquals(
            "[span: Hello World]",
            \Tr8n\Utils\HtmlParser::translate("<span>Hello World</span>")
        );

        $this->assertEquals(
            "[span: Hello [span: World]]",
            \Tr8n\Utils\HtmlParser::translate("<span>Hello <span>World</span></span>")
        );

        $this->assertEquals(
            "[span1: Hello [span: World]]",
            \Tr8n\Utils\HtmlParser::translate("<span style='font-weight:bold;'>Hello <span>World</span></span>")
        );

        $this->assertEquals(
            "[span2: Message = [span1: Hello [span: World]]]",
            \Tr8n\Utils\HtmlParser::translate("<span style='font-family:Arial'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>")
        );

        $this->assertEquals(
            "[span1: Message = [span1: Hello [span: World]]]",
            \Tr8n\Utils\HtmlParser::translate("<span style='font-weight:bold;'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></span>")
        );

        $this->assertEquals(
            "[p: [div: Message = [span1: Hello [span: World]]]]",
            \Tr8n\Utils\HtmlParser::translate("<p><div style='font-weight:bold;'>Message = <span style='font-weight:bold;'>Hello <span>World</span></span></div></p>")
        );


        $this->assertEquals(
            "Hello {br} World",
            \Tr8n\Utils\HtmlParser::translate("Hello <br> World")
        );

        $this->assertEquals(
            "[span: Hello {br} World]",
            \Tr8n\Utils\HtmlParser::translate("<span>Hello <br/> World</span>")
        );

        $this->assertEquals(
            "[span: Hello {hr} World]",
            \Tr8n\Utils\HtmlParser::translate("<span>Hello <hr/> World</span>")
        );

    }

    public function testHTMLParsing() {
//        print_r(\Tr8n\Utils\HtmlParser::translate(self::loadFile("html/span.html")));
//        print_r(\Tr8n\Utils\HtmlParser::translate(self::loadFile("html/bold.html")));
//        print_r(\Tr8n\Utils\HtmlParser::translate(self::loadFile("html/source/nested.html")));
    }

}