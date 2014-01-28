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

require_once(__DIR__."/../../BaseTest.php");

class DataTokenTest extends \BaseTest {

    public function testParsing() {
        $token = DataToken::tokenWithName("{user}");
        $this->assertEquals("{user}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = DataToken::tokenWithName("{ user }");
        $this->assertEquals("{ user }", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = DataToken::tokenWithName("{user:gender}");
        $this->assertEquals("{user:gender}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = DataToken::tokenWithName("{user : gender}");
        $this->assertEquals("{user : gender}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = DataToken::tokenWithName("{user :: gen}");
        $this->assertEquals("{user :: gen}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array("gen"), $token->case_keys);

        $token = DataToken::tokenWithName("{user::gen}");
        $this->assertEquals("{user::gen}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array("gen"), $token->case_keys);

        $token = DataToken::tokenWithName("{user:gender::gen}");
        $this->assertEquals("{user:gender::gen}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array("gen"), $token->case_keys);

        $token = DataToken::tokenWithName("{count:number::ordinal::ord}");
        $this->assertEquals("{count:number::ordinal::ord}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("number"), $token->context_keys);
        $this->assertEquals(array("ordinal", "ord"), $token->case_keys);
    }

    public function testNameWithOptions() {
        $token = DataToken::tokenWithName("{count:number::ordinal::ord}");

        $this->assertEquals("{count:number::ordinal::ord}", $token->name(array("parens"=>true, "context_keys"=>true, "case_keys"=>true)));
        $this->assertEquals("{count:number}", $token->name(array("parens"=>true, "context_keys"=>true)));
        $this->assertEquals("{count}", $token->name(array("parens"=>true)));
        $this->assertEquals("count", $token->name(array()));
    }

    public function testContextForLanguage() {
        $language = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));

        $token = DataToken::tokenWithName("{user:gender}");
        $this->assertEquals("gender", $token->contextForLanguage($language)->keyword);

        $token = DataToken::tokenWithName("{user}");
        $this->assertEquals("gender", $token->contextForLanguage($language)->keyword);

        $token = DataToken::tokenWithName("{count}");
        $this->assertEquals("number", $token->contextForLanguage($language)->keyword);

        $token = DataToken::tokenWithName("{date}");
        $this->assertEquals("date", $token->contextForLanguage($language)->keyword);

        $token = DataToken::tokenWithName("{users}");
        $this->assertEquals("genders", $token->contextForLanguage($language)->keyword);

        $token = DataToken::tokenWithName("{items}");
        $this->assertEquals("list", $token->contextForLanguage($language)->keyword);
    }

    public function testTokenValue() {
        $language = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));
        $token = DataToken::tokenWithName("{user}");
        $user = new \User("Michael", "male");

        $this->assertEquals("{user: missing value}", $token->tokenValue(array(), $language));

        $this->assertEquals("test", $token->tokenValue(array("user" => "test"), $language));

        $this->assertEquals("1", $token->tokenValue(array("user" => 1), $language));

        $this->assertEquals("1.5", $token->tokenValue(array("user" => 1.5), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => $user), $language));

        $this->assertEquals("Mike", $token->tokenValue(array("user" => array($user, "Mike")), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array($user, "@name")), $language));

        $this->assertEquals("{user: property name1 does not exist}", $token->tokenValue(array("user" => array($user, "@name1")), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array($user, "@@fullName")), $language));

        $this->assertEquals("{user: method fullName1 does not exist}", $token->tokenValue(array("user" => array($user, "@@fullName1")), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array($user, function($obj) { return $obj->name; } )), $language));

        $this->assertEquals("{user: object attribute is missing in the hash value}", $token->tokenValue(array("user" => array()), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array("object" => $user)), $language));

        $this->assertEquals("Tom", $token->tokenValue(array("user" => array("object" => $user, "value" => "Tom")), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array("object" => $user, "attribute" => "name")), $language));

        $this->assertEquals("{user: property name1 does not exist}", $token->tokenValue(array("user" => array("object" => $user, "attribute" => "name1")), $language));

        $this->assertEquals("Michael", $token->tokenValue(array("user" => array("object" => $user, "method" => "fullName")), $language));

        $this->assertEquals("{user: method fullName1 does not exist}", $token->tokenValue(array("user" => array("object" => $user, "method" => "fullName1")), $language));

        $this->assertEquals("Peter", $token->tokenValue(array("user" => array("object" => array("name" => "Peter"), "attribute" => "name")), $language));

        $this->assertEquals("{user: property name1 does not exist}", $token->tokenValue(array("user" => array("object" => array("name" => "Peter"), "attribute" => "name1")), $language));

        $this->assertEquals("{user: invalid method properties for hash value}", $token->tokenValue(array("user" => array("object" => array("name" => "Peter"), "method" => "name")), $language));
    }

    public function testSanitize() {
        $language = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));
        $token = DataToken::tokenWithName("{user}");
        $user = new \User("<b>Michael</b>", "male");

        $this->assertEquals("<b>test</b>", $token->tokenValue(array("user" => "<b>test</b>"), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => $user), $language));

        $this->assertEquals("<b>Mike</b>", $token->tokenValue(array("user" => array($user, "<b>Mike</b>")), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array($user, "@name")), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array($user, "@@fullName")), $language));

        $this->assertEquals("<b>Michael</b>", $token->tokenValue(array("user" => array($user, function($obj) { return $obj->name; } )), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array("object" => $user)), $language));

        $this->assertEquals("<b>Tom</b>", $token->tokenValue(array("user" => array("object" => $user, "value" => "<b>Tom</b>")), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array("object" => $user, "attribute" => "name")), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array("object" => $user, "method" => "fullName")), $language));

        $this->assertEquals("&lt;b&gt;Michael&lt;/b&gt;", $token->tokenValue(array("user" => array("object" => array("name" => "<b>Michael</b>"), "attribute" => "name")), $language));
    }
    
    public function testTokenObject() {

        $user = new \User("<b>Michael</b>", "male");
        
        $this->assertEquals(null, DataToken::tokenObject(array(), "user"));

        $this->assertEquals("test", DataToken::tokenObject(array("user" => "test"), "user"));

        $this->assertEquals(1, DataToken::tokenObject(array("user" => 1), "user"));

        $this->assertEquals(1.5, DataToken::tokenObject(array("user" => 1.5), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => $user), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, "Mike")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, "@name")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, "@name1")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, "@@fullName")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, "@@fullName1")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array($user, function($obj) { return $obj->name; } )), "user"));

        $this->assertEquals(null, DataToken::tokenObject(array("user" => array()), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user)), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user, "value" => "Tom")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user, "attribute" => "name")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user, "attribute" => "name1")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user, "method" => "fullName")), "user"));

        $this->assertEquals($user, DataToken::tokenObject(array("user" => array("object" => $user, "method" => "fullName1")), "user"));

        $this->assertEquals(array("name" => "Peter"), DataToken::tokenObject(array("user" => array("object" => array("name" => "Peter"), "attribute" => "name")), "user"));

        $this->assertEquals(array("name" => "Peter"), DataToken::tokenObject(array("user" => array("object" => array("name" => "Peter"), "attribute" => "name1")), "user"));

        $this->assertEquals(array("name" => "Peter"), DataToken::tokenObject(array("user" => array("object" => array("name" => "Peter"), "method" => "name")), "user"));
    }

    public function testSubstitute() {
        $language = new \Tr8n\Language(self::loadJSON('languages/en-US.json'));
        $token = DataToken::tokenWithName("{user}");
        $user = new \User("Michael", "male");

        $this->assertEquals("Hello {user: missing value}", $token->substitute("Hello {user}", array(), $language));

        $this->assertEquals("Hello test", $token->substitute("Hello {user}", array("user" => "test"), $language));

        $this->assertEquals("Hello 1", $token->substitute("Hello {user}", array("user" => 1), $language));

        $this->assertEquals("Hello 1.5", $token->substitute("Hello {user}", array("user" => 1.5), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => $user), $language));

        $this->assertEquals("Hello Mike", $token->substitute("Hello {user}", array("user" => array($user, "Mike")), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array($user, "@name")), $language));

        $this->assertEquals("Hello {user: property name1 does not exist}", $token->substitute("Hello {user}", array("user" => array($user, "@name1")), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array($user, "@@fullName")), $language));

        $this->assertEquals("Hello {user: method fullName1 does not exist}", $token->substitute("Hello {user}", array("user" => array($user, "@@fullName1")), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array($user, function($obj) { return $obj->name; } )), $language));

        $this->assertEquals("Hello {user: object attribute is missing in the hash value}", $token->substitute("Hello {user}", array("user" => array()), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array("object" => $user)), $language));

        $this->assertEquals("Hello Tom", $token->substitute("Hello {user}", array("user" => array("object" => $user, "value" => "Tom")), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array("object" => $user, "attribute" => "name")), $language));

        $this->assertEquals("Hello {user: property name1 does not exist}", $token->substitute("Hello {user}", array("user" => array("object" => $user, "attribute" => "name1")), $language));

        $this->assertEquals("Hello Michael", $token->substitute("Hello {user}", array("user" => array("object" => $user, "method" => "fullName")), $language));

        $this->assertEquals("Hello {user: method fullName1 does not exist}", $token->substitute("Hello {user}", array("user" => array("object" => $user, "method" => "fullName1")), $language));

        $this->assertEquals("Hello Peter", $token->substitute("Hello {user}", array("user" => array("object" => array("name" => "Peter"), "attribute" => "name")), $language));

        $this->assertEquals("Hello {user: property name1 does not exist}", $token->substitute("Hello {user}", array("user" => array("object" => array("name" => "Peter"), "attribute" => "name1")), $language));

        $this->assertEquals("Hello {user: invalid method properties for hash value}", $token->substitute("Hello {user}", array("user" => array("object" => array("name" => "Peter"), "method" => "name")), $language));
    }

}