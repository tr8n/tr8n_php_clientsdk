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

class StringUtilsTest extends \BaseTest {

    public function testSplitToSentences() {
        $text = "Hello World";
        $matches = StringUtils::splitSentences($text);
        $this->assertEquals("Hello World", $matches[0]);

        $text = "This is the first sentence. Followed by the second one.";
        $matches = StringUtils::splitSentences($text);
        $this->assertEquals(2, count($matches));

        $text = "Genealogical societies are essential to family history researchers. They provide resources, programs, conferences, and other important assistance. MyHeritage is spotlighting these societies in a new series over the year.";
        $matches = StringUtils::splitSentences($text);
        $this->assertEquals(3, count($matches));

        $text = "<br />
Genealogical societies are essential to family history researchers. </p>
<p>They provide resources, programs, conferences, and other important assistance. MyHeritage is spotlighting these societies in a new series over the year.<br />";
        $matches = StringUtils::splitSentences($text);
        $this->assertEquals(3, count($matches));
        print_r($matches);

    }

}