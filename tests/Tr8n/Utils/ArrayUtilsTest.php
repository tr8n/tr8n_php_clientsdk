<?php
/**
 * Created by JetBrains PhpStorm.
 * User: michael
 * Date: 7/9/13
 * Time: 12:50 PM
 * To change this template use File | Settings | File Templates.
 */

namespace Tr8n\Rules;

use Tr8n\Utils\ArrayUtils;

require_once(__DIR__."/../../BaseTest.php");

class ArrayUtilsTest extends \BaseTest {

    public function testTrim() {
      $data = array(
        array(  "source" =>  "/posts/privacy_policy",
                "keys"  =>
                        array(
                            array(  "label"=>"Hello {user}",
                                    "description"=>null,
                                    "locale"=>"en-US",
                                    "level"=>0),
                            array(
                                    "label"=>"Documentation",
                                    "description"=>null,
                                    "locale"=>"en-US",
                                    "level"=>0),
                            array(  "label"=>"Research your family history easily and instantly:",
                                    "description"=>null,
                                    "locale"=>"en-US",
                                    "level"=>0),
                            array(  "label"=> "MyHeritage was founded by a team of people with a passion for genealogy and a strong grasp of Internet technology.",
                                    "description"=>null,
                                    "locale"=>"en-US",
                                    "level"=>0)
                            )
                )
      );

      $data = ArrayUtils::trim($data);
      $this->assertEquals(
          array(
              array(  "source" =>  "/posts/privacy_policy",
                  "keys"  =>
                  array(
                      array(  "label"=>"Hello {user}",
                          "locale"=>"en-US",
                          ),
                      array(
                          "label"=>"Documentation",
                          "locale"=>"en-US",
                          ),
                      array(  "label"=>"Research your family history easily and instantly:",
                          "locale"=>"en-US",
                          ),
                      array(  "label"=> "MyHeritage was founded by a team of people with a passion for genealogy and a strong grasp of Internet technology.",
                          "locale"=>"en-US",
                          )
                  )
              )
          ),
          $data
      );
    }
}

