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

class LoggerTest extends \BaseTest {

    public function testLogger() {
        $logger = Logger::instance();
        $logger->debug("Debug message");
        $logger->info("Info message");
        $logger->warn("Warning message");
        $logger->notice("Notice message");
        $logger->error("Error message");

        $this->assertEquals(true, file_exists(Config::instance()->loggerFilePath()));
    }

}