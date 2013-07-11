<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Tokens;

require_once(__DIR__."/../../BaseTest.php");

class DecorationTokenTest extends \BaseTest {

    public function testParsing() {
        $tokens = Base::registerTokens("Hello [bold: world]", "decoration");
        $this->assertEquals(1, count($tokens));

        $tokens = Base::registerTokens("You have [link: [bold: {count}] messages].", "decoration");
        $this->assertEquals(2, count($tokens));
        $this->assertEquals("[bold: {count}]", $tokens[0]->fullName());
        $this->assertEquals("[link: [bold: {count}] messages]", $tokens[1]->fullName());

        $tokens = Base::registerTokens("Follow [link1: this message] or [link2: that message].", "decoration");
        $this->assertEquals(2, count($tokens));
        $this->assertEquals("[link1: this message]", $tokens[0]->fullName());
        $this->assertEquals("[link2: that message]", $tokens[1]->fullName());

        $tokens = Base::registerTokens("Follow [link1: [bold: this] message] or [link2: [bold: that] message].", "decoration");
        $this->assertEquals(4, count($tokens));
        $this->assertEquals("[bold: this]",                     $tokens[0]->fullName());
        $this->assertEquals("[link1: [bold: this] message]",    $tokens[1]->fullName());
        $this->assertEquals("[bold: that]",                     $tokens[2]->fullName());
        $this->assertEquals("[link2: [bold: that] message]",    $tokens[3]->fullName());

        $tokens = Base::registerTokens("Follow [link1: [bold: this] message] or [link2: [bold: that] message].", "decoration", array("exclude_nested" => true));
        $this->assertEquals(2, count($tokens));
        $this->assertEquals("[bold: this]",                     $tokens[0]->fullName());
        $this->assertEquals("[bold: that]",                     $tokens[1]->fullName());

    }

    public function testSanitizedName() {
        $label = "Hello [bold: world]";
        $tokens = Base::registerTokens($label, "decoration");
        $this->assertEquals("[bold: ]", $tokens[0]->sanitizedName());
    }

    public function testDecoratedValue() {
        $label = "Hello [bold: world]";
        $tokens = Base::registerTokens($label, "decoration");
        $this->assertEquals("world", $tokens[0]->decoratedValue());

        $label = "Hello [bold: number: 5]";
        $tokens = Base::registerTokens($label, "decoration");
        $this->assertEquals("number: 5", $tokens[0]->decoratedValue());
    }

    public function testSubstitution() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));

        $label = "Hello [bold: world]";
        $tokens = Base::registerTokens($label, "decoration");
        $this->assertEquals("Hello <strong>world</strong>", $tokens[0]->substitute($label, array("bold" => function($text) {
            return "<strong>$text</strong>";
        }), $russian));


        $this->assertEquals("Hello <strong>world</strong>", $tokens[0]->substitute($label, array("bold" => '<strong>{$0}</strong>'), $russian));
    }

}