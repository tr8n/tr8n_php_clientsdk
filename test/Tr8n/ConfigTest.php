<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n;

require_once(__DIR__."/../BaseTest.php");

class ConfigTest extends \BaseTest {

    public function testConfigInstance() {
        $config = \Tr8n\Config::instance();

        $this->assertEquals(false, $config->isEnabled());
        $this->assertEquals(true, $config->isLoggerEnabled());
        $this->assertEquals('\Tr8n\Rules\NumericRule', $config->ruleClassByType("number"));
        $this->assertEquals('\Tr8n\Rules\GenderRule', $config->ruleClassByType("gender"));

   }

}