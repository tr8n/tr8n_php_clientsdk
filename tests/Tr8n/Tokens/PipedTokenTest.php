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

class PipedTokenTest extends \BaseTest {

    public function testParsing() {
        $token = PipedToken::tokenWithName("{count | message}");
        $this->assertEquals("{count | message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count| message}");
        $this->assertEquals("{count| message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count|message}");
        $this->assertEquals("{count|message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count || message}");
        $this->assertEquals("{count || message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count|| message}");
        $this->assertEquals("{count|| message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count:number||message}");
        $this->assertEquals("{count:number||message}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array("number"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count|| message, messages}");
        $this->assertEquals("{count|| message, messages}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("message", "messages"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count|| one: message, other: messages}");
        $this->assertEquals("{count|| one: message, other: messages}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("one: message", "other: messages"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{count : number || one : message, other : messages}");
        $this->assertEquals("{count : number || one : message, other : messages}", $token->full_name);
        $this->assertEquals("count", $token->short_name);
        $this->assertEquals(array("one : message", "other : messages"), $token->parameters);
        $this->assertEquals("||", $token->separator);
        $this->assertEquals(array("number"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{user| Born on}");
        $this->assertEquals("{user| Born on}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("Born on"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array(), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{user:gender| Born on}");
        $this->assertEquals("{user:gender| Born on}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("Born on"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{user:gender | Born on}");
        $this->assertEquals("{user:gender | Born on}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("Born on"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);

        $token = PipedToken::tokenWithName("{user : gender | Born on}");
        $this->assertEquals("{user : gender | Born on}", $token->full_name);
        $this->assertEquals("user", $token->short_name);
        $this->assertEquals(array("Born on"), $token->parameters);
        $this->assertEquals("|", $token->separator);
        $this->assertEquals(array("gender"), $token->context_keys);
        $this->assertEquals(array(), $token->case_keys);
    }

    public function testValueMapForContext() {
        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/en-US/gender.json'));

        $token = PipedToken::tokenWithName("{user:gender| other: Born on}");
        $this->assertEquals(array("other" => "Born on"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user| male: He, female: She}");
        $this->assertEquals(array("male" => "He", "female" => "She"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user| male: He, female: She, other: He/She}");
        $this->assertEquals(array("male" => "He", "female" => "She", "other" => "He/She"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user:gender| Born on}");
        $this->assertEquals(array("other" => "Born on"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user| He, She}");
        $this->assertEquals(array("male" => "He", "female" => "She", "other" => "He/She"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user| He, She, She/He}");
        $this->assertEquals(array("male" => "He", "female" => "She", "other" => "She/He"), $token->generateValueMapForContext($context));

        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/en-US/number.json'));

        $token = PipedToken::tokenWithName("{count|| one: message, many: messages}");
        $this->assertEquals(array("one" => "message", "many" => "messages"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{count|| message, messages}");
        $this->assertEquals(array("one" => "message", "other" => "messages"), $token->generateValueMapForContext($context));

        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/ru/gender.json'));
        $token = PipedToken::tokenWithName("{user| female: родилась, other: родился}");
        $this->assertEquals(array("female" => "родилась", "other" => @"родился"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{user| родился, родилась}");
        $this->assertEquals(array("female" => "родилась", "male" => @"родился", "other" => "родился/родилась"), $token->generateValueMapForContext($context));

        $context = new \Tr8n\LanguageContext(self::loadJSON('contexts/ru/number.json'));

        $token = PipedToken::tokenWithName("{count|| one: сообщение, few: сообщения, other: сообщений}");
        $this->assertEquals(array("one" => "сообщение", "few" => "сообщения", "other" => "сообщений"), $token->generateValueMapForContext($context));

        $token = PipedToken::tokenWithName("{count|| сообщение, сообщения, сообщений}");
        $this->assertEquals(array("one" => "сообщение", "few" => "сообщения", "many" => "сообщений", "other" => "сообщений"), $token->generateValueMapForContext($context));
   }

    public function testSubstitute() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $language = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        $token = PipedToken::tokenWithName("{count|| one: message, other: messages}");
        $this->assertEquals("You have 1 message", $token->substitute("You have {count|| one: message, other: messages}", array("count" => 1), $language));
        $this->assertEquals("You have 2 messages", $token->substitute("You have {count|| one: message, other: messages}", array("count" => 2), $language));

        $token = PipedToken::tokenWithName("{count|| message}");
        $this->assertEquals("You have 1 message", $token->substitute("You have {count|| message}", array("count" => 1), $language));
        $this->assertEquals("You have 2 messages", $token->substitute("You have {count|| message}", array("count" => 2), $language));
        $this->assertEquals("You have 20 messages", $token->substitute("You have {count|| message}", array("count" => 20), $language));

        $token = PipedToken::tokenWithName("{count| a message, #count# messages}");
        $this->assertEquals("You have a message", $token->substitute("You have {count| a message, #count# messages}", array("count" => 1), $language));
        $this->assertEquals("You have 5 messages", $token->substitute("You have {count| a message, #count# messages}", array("count" => 5), $language));

        $michael = new \User("Michael", "male");
        $tom = new \User("Tom", "male");
        $alex = new \User("Alex", "male");
        $peter = new \User("Peter", "male");
        $anna = new \User("Anna", "female");
        $kate = new \User("Kate", "female");
        $jenny = new \User("Jenny", "female");
        $all = array($michael, $tom, $alex, $peter, $anna, $kate, $jenny);

        $token = PipedToken::tokenWithName("{users || likes, like}");
        $this->assertEquals("Michael and Tom like", $token->substitute("{users || likes, like}", array("users" => array(array($michael, $tom), "@name")), $language));
        $this->assertEquals("Michael likes", $token->substitute("{users || likes, like}", array("users" => array(array($michael), "@name")), $language));
    }

}