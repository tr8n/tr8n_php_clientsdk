<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Rules;

use Tr8n\Utils\Inflector;

require_once(__DIR__."/../../BaseTest.php");

class InflectorTest extends \BaseTest {

    public function testPluralization() {
        $this->assertEquals("cars", Inflector::pluralize("car"));
        $this->assertEquals("people", Inflector::pluralize("person"));
        $this->assertEquals("times", Inflector::pluralize("time"));
        $this->assertEquals("information", Inflector::pluralize("information"));
    }

    public function testSinguralization() {
        $this->assertEquals("car", Inflector::singularize("cars"));
        $this->assertEquals("person", Inflector::singularize("people"));
        $this->assertEquals("time", Inflector::singularize("times"));
        $this->assertEquals("information", Inflector::singularize("information"));
    }

}