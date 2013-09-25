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

class TransformTokenTest extends \BaseTest {

    public function testParsing() {
        $tokens = Base::registerTokens("Hello {user.name}");
        $this->assertEquals(1, count($tokens));
        $this->assertEquals('Tr8n\Tokens\TransformToken', get_class($tokens[0]));
    }





}