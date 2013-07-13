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

class LanguageTest extends \BaseTest {

    public function testLoadingLanguage() {
        $russian = new \Tr8n\Language(self::loadJSON('languages/ru.json'));
//        print_r($russian);

        $this->assertEquals('ru', $russian->locale);
        $this->assertEquals('Russian - Русский', $russian->name);
        $this->assertEquals('Russian', $russian->english_name);
        $this->assertEquals('Русский', $russian->native_name);
        $this->assertEquals(array("date", "gender_list", "gender", "number", "value"), array_keys($russian->context_rules));
        $this->assertEquals(array("nom", "gen", "dat", "acc", "ins", "pre", "pos"), array_keys($russian->language_cases));
    }

    public function testDefaultTranslationsWithNoTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        // No Tokens
        $this->assertEquals('Hello World',
            $english->translate("Hello World")
        );

        $this->assertEquals('Hello World',
            $english->translate("Hello World", 'Greeting')
        );
    }

    public function testDefaultTranslationsWithBasicTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        // Basic Tokens
        $this->assertEquals('You have 0 messages',
            $english->translate('You have {count} messages', '', array('count' => 0))
        );

        $this->assertEquals('Hello World',
            $english->translate('Hello {world}', '', array('world' => 'World'))
        );

        $user = new \User("Michael");

        $this->assertEquals('Hello Michael',
            $english->translate('Hello {user}', '', array('user' => $user))
        );
    }

    public function testDefaultTranslationsWithDecorationTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        // Decoration Tokens
        $this->assertEquals('Hello <strong>World</strong>',
            $english->translate('Hello [strong: World]', '', array('strong' => function($txt){
                return "<strong>$txt</strong>";
            }))
        );

        $this->assertEquals('Hello <strong>World</strong>',
            $english->translate('Hello [strong: World]', '', array('strong' => '<strong>{$0}</strong>'))
        );

        $this->assertEquals('Hello <strong>World</strong>',
            $english->translate('Hello [strong: {world}]', '', array('world' => 'World', 'strong' => '<strong>{$0}</strong>'))
        );

        $this->assertEquals('This is <strong>Pretty <italic>Cool</italic>!</strong>',
            $english->translate('This is [strong: {pretty} [italic: {cool}]!]', '', array(
                'pretty' => 'Pretty',
                'cool' => 'Cool',
                'strong' => '<strong>{$0}</strong>',
                'italic' => '<italic>{$0}</italic>'
            ))
        );
    }

    public function testDefaultTranslationsWithTransformTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        $user = new \User("Michael");

        // Numeric transform Tokens
        $this->assertEquals('Michael, you have 10 messages',
            $english->translate('{user}, you have {count} messages', '', array('user'=>$user, 'count'=>10))
        );

        $this->assertEquals('Michael, you have 1 message',
            $english->translate('{user}, you have {count||message}', '', array('user'=>$user, 'count'=>1))
        );

        $this->assertEquals('Michael, you have 5 messages',
            $english->translate('{user}, you have {count||message}', '', array('user'=>$user, 'count'=>5))
        );

        $this->assertEquals('Michael, you have 5 messages',
            $english->translate('{user}, you have {count||message,messages}', '', array('user'=>$user, 'count'=>5))
        );

        $this->assertEquals('Michael, you have 5 messages',
            $english->translate('{user}, you have {count|| one: message, many: messages}', '', array('user'=>$user, 'count'=>5))
        );

        $this->assertEquals('Michael, you have 5 messages',
            $english->translate('{user}, you have {count|| one: message, other: messages}', '', array('user'=>$user, 'count'=>5))
        );

        $this->assertEquals('1 person',
            $english->translate('{count||person}', '', array('count'=>1))
        );

        $this->assertEquals('2 people',
            $english->translate('{count||person}', '', array('count'=>2))
        );

        $this->assertEquals('people',
            $english->translate('{count|person}', '', array('count'=>2))
        );

        $this->assertEquals('people',
            $english->translate('{count|one:person, many:people}', '', array('count'=>2))
        );

        $this->assertEquals('people',
            $english->translate('{count | one: person, many: people}', '', array('count'=>2))
        );

        $this->assertEquals('Michael, you have <strong>5 messages</strong>',
            $english->translate('{user}, you have [strong: {count||message}]', '', array(
                'user'=>$user,
                'strong' => '<strong>{$0}</strong>',
                'count'=>5
            ))
        );

        $this->assertEquals('Michael, you have <strong>5 <italic>messages</italic></strong>',
            $english->translate('{user}, you have [strong: {count} [italic: {count|message}]]', '', array(
                'user'=>$user,
                'count'=>5,
                'strong' => '<strong>{$0}</strong>',
                'italic' => '<italic>{$0}</italic>'
            ))
        );

        // Gender transform tokens
        $michael = new \User("Michael", "male");
        $anna = new \User("Anna", "female");

        $this->assertEquals('He likes this',
            $english->translate('{user|He,She} likes this', '', array(
                'user' => $michael
            ))
        );

        $this->assertEquals('She likes this',
            $english->translate('{user|He,She} likes this', '', array(
                'user' => $anna
            ))
        );

        $this->assertEquals('Born on:',
            $english->translate('{user|Born on}:', '', array(
                'user' => $michael
            ))
        );

        $this->assertEquals('Born on:',
            $english->translate('{user|Born on}:', '', array(
                'user' => $anna
            ))
        );
    }

    public function testForeignTranslationsWithNoTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        self::cacheTranslations($app, 'Hello World', '', array("ru" => array(
            array("label" => "Привет Мир")
        )));

        Config::instance()->beginBlockWithOptions(array("dry" => true));

        // No Tokens
        $this->assertEquals('Привет Мир',
            $russian->translate("Hello World")
        );

        self::cacheTranslations($app, 'Invite', 'Action to invite', array("ru" => array(
            array("label" => "Пригласить")
        )));
        self::cacheTranslations($app, 'Invite', 'An invitation', array("ru" => array(
            array("label" => "Приглашение")
        )));

        $this->assertEquals('Пригласить',
            $russian->translate('Invite', 'Action to invite')
        );

        $this->assertEquals('Приглашение',
            $russian->translate('Invite', 'An invitation')
        );

        $this->assertEquals('Invite',
            $russian->translate('Invite', 'Something non-existent')
        );

        Config::instance()->finishBlockWithOptions();
    }

    public function testForeignTranslationsWithBasicTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        Config::instance()->beginBlockWithOptions(array("dry" => true));

        self::cacheTranslations($app, 'Hello {world}', '', array("ru" => array(
            array("label" => "Привет {world}")
        )));

        $this->assertEquals('Привет Мир',
            $russian->translate('Hello {world}', '', array('world' => 'Мир'))
        );

        $user = new \User("Михаил");

        self::cacheTranslations($app, 'Hello {user}', '', array("ru" => array(
            array("label" => "Привет {user}")
        )));

        $this->assertEquals('Привет Михаил',
            $russian->translate('Hello {user}', '', array('user' => $user))
        );

        Config::instance()->finishBlockWithOptions();
    }


    public function testForeignTranslationsWithDecorationTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        Config::instance()->beginBlockWithOptions(array("dry" => true));

        self::cacheTranslations($app, 'Hello [strong: World]', '', array("ru" => array(
            array("label" => "Привет [strong: Мир]")
        )));

        // Decoration Tokens
        $this->assertEquals('Привет <strong>Мир</strong>',
            $russian->translate('Hello [strong: World]', '', array('strong' => function($txt){
                return "<strong>$txt</strong>";
            }))
        );

        $this->assertEquals('Привет <strong>Мир</strong>',
            $russian->translate('Hello [strong: World]', '', array('strong' => '<strong>{$0}</strong>'))
        );

        self::cacheTranslations($app, 'Hello [strong: {world}]', '', array("ru" => array(
            array("label" => "Привет [strong: {world}]")
        )));

        $this->assertEquals('Привет <strong>Мир</strong>',
            $russian->translate('Hello [strong: {world}]', '', array('world' => 'Мир', 'strong' => '<strong>{$0}</strong>'))
        );

        self::cacheTranslations($app, 'This is [strong: pretty [italic: cool]!]', '', array("ru" => array(
            array("label" => "Это [strong: довольно [italic: круто]!]")
        )));

        $this->assertEquals('Это <strong>довольно <italic>круто</italic>!</strong>',
            $russian->translate('This is [strong: pretty [italic: cool]!]', '', array(
                'strong' => '<strong>{$0}</strong>',
                'italic' => '<italic>{$0}</italic>'
            ))
        );

        Config::instance()->finishBlockWithOptions();
    }


    public function testForeignLanguageAsDefaultLanguage() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        Config::instance()->beginBlockWithOptions(array("dry" => true, "locale" => 'ru'));

        $user = new \User("Михаил");

        $this->assertEquals('Михаил, у вас есть 5 сообщений',
            $russian->translate('{user}, у вас есть {count|| one: сообщение, few: сообщения, many: сообщений}', '', array('user'=>$user, 'count'=>5))
        );

        $this->assertEquals('Михаил, у вас есть 1 сообщение',
            $russian->translate('{user}, у вас есть {count|| one: сообщение, few: сообщения, many: сообщений}', '', array('user'=>$user, 'count'=>1))
        );

        $this->assertEquals('Михаил, у вас есть 2 сообщения',
            $russian->translate('{user}, у вас есть {count|| one: сообщение, few: сообщения, many: сообщений}', '', array('user'=>$user, 'count'=>2))
        );

        $michael = new \User("Михаил", "male");
        $anna = new \User("Анна", "female");

        $this->assertEquals('Ему это нравится',
            $russian->translate('{user|Ему, Ей} это нравится', '', array(
                'user' => $michael
            ))
        );

        $this->assertEquals('Ей это нравится',
            $russian->translate('{user|Ему, Ей} это нравится', '', array(
                'user' => $anna
            ))
        );

        $this->assertEquals('Родился:',
            $russian->translate('{user| male: Родился, female: Родилась}:', '', array(
                'user' => $michael
            ))
        );

        $this->assertEquals('Родилась:',
            $russian->translate('{user| male: Родился, female: Родилась}:', '', array(
                'user' => $anna
            ))
        );

        $this->assertEquals('Родилась:',
            $russian->translate('{user| Родился, Родилась}:', '', array(
                'user' => $anna
            ))
        );

        Config::instance()->finishBlockWithOptions();
    }


    public function testForeignTranslationsWithTransformTokens() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        Config::instance()->beginBlockWithOptions(array("dry" => true));

        $user = new \User("Михаил");

        self::cacheTranslations($app, '{user}, you have {count||message}', '', array("ru" => array(
            array("label" => "{user}, у вас есть {count} сообщение", "context" => array("count" => array(
                array("type" => "number", "key" => "one")
            ))),
            array("label" => "{user}, у вас есть {count} сообщения", "context" => array("count" => array(
                array("type" => "number", "key" => "few")
            ))),
            array("label" => "{user}, у вас есть {count} сообщений", "context" => array("count" => array(
                array("type" => "number", "key" => "many")
            )))
        )));

        // Numeric transform Tokens
        $this->assertEquals('Михаил, у вас есть 1 сообщение',
            $russian->translate('{user}, you have {count||message}', '', array('user'=>$user, 'count'=>1))
        );
        $this->assertEquals('Михаил, у вас есть 2 сообщения',
            $russian->translate('{user}, you have {count||message}', '', array('user'=>$user, 'count'=>2))
        );
        $this->assertEquals('Михаил, у вас есть 5 сообщений',
            $russian->translate('{user}, you have {count||message}', '', array('user'=>$user, 'count'=>5))
        );

        self::cacheTranslations($app, '{count||person}', '', array("ru" => array(
            array("label" => "{count} человек", "context" => array("count" => array(
                array("type" => "number", "key" => "one")
            ))),
            array("label" => "{count} человека", "context" => array("count" => array(
                array("type" => "number", "key" => "few")
            ))),
            array("label" => "{count} человек", "context" => array("count" => array(
                array("type" => "number", "key" => "many")
            )))
        )));

        $this->assertEquals('1 человек',
            $russian->translate('{count||person}', '', array('count'=>1))
        );

        $this->assertEquals('2 человека',
            $russian->translate('{count||person}', '', array('count'=>2))
        );

        $this->assertEquals('3 человека',
            $russian->translate('{count||person}', '', array('count'=>3))
        );

        $this->assertEquals('10 человек',
            $russian->translate('{count||person}', '', array('count'=>10))
        );

        self::cacheTranslations($app, '{count|person}', '', array("ru" => array(
            array("label" => "человек", "context" => array("count" => array(
                array("type" => "number", "key" => "one")
            ))),
            array("label" => "люди", "context" => array("count" => array(
                array("type" => "number", "key" => "few")
            ))),
            array("label" => "люди", "context" => array("count" => array(
                array("type" => "number", "key" => "many")
            )))
        )));

        $this->assertEquals('люди',
            $russian->translate('{count|person}', '', array('count'=>2))
        );

        self::cacheTranslations($app, '{count||message}', '', array("ru" => array(
            array("label" => "{count||one: сообщение, few: сообщения, many: сообщений}"),
        )));

        $this->assertEquals('2 сообщения',
            $russian->translate('{count||message}', '', array('count'=>2))
        );

        self::cacheTranslations($app, '{user}, you have [strong: {count||message}]', '', array("ru" => array(
            array("label" => "{user}, у вас есть [strong: {count||one: сообщение, few: сообщения, many: сообщений}]"),
        )));

        $this->assertEquals('Михаил, у вас есть <strong>1 сообщение</strong>',
            $russian->translate('{user}, you have [strong: {count||message}]', '', array(
                'user'      =>  $user,
                'strong'    => '<strong>{$0}</strong>',
                'count'     => 1
            ))
        );

        $this->assertEquals('Михаил, у вас есть <strong>5 сообщений</strong>',
            $russian->translate('{user}, you have [strong: {count||message}]', '', array(
                'user'=>$user,
                'strong' => '<strong>{$0}</strong>',
                'count'=>5
            ))
        );

        self::cacheTranslations($app, '{user}, you have [strong: {count} [italic: {count|message}]]', '', array("ru" => array(
            array("label" => "{user}, у вас есть [strong: {count} [italic: {count|one: сообщение, few: сообщения, many: сообщений}]]"),
        )));

        $this->assertEquals('Михаил, у вас есть <strong>1 <italic>сообщение</italic></strong>',
            $russian->translate('{user}, you have [strong: {count} [italic: {count|message}]]', '', array(
                'user'=>$user,
                'count'=>1,
                'strong' => '<strong>{$0}</strong>',
                'italic' => '<italic>{$0}</italic>'
            ))
        );

        $this->assertEquals('Михаил, у вас есть <strong>5 <italic>сообщений</italic></strong>',
            $russian->translate('{user}, you have [strong: {count} [italic: {count|message}]]', '', array(
                'user'=>$user,
                'count'=>5,
                'strong' => '<strong>{$0}</strong>',
                'italic' => '<italic>{$0}</italic>'
            ))
        );

        // Gender transform tokens
        $michael = new \User("Michael", "male");
        $anna = new \User("Anna", "female");

        self::cacheTranslations($app, '{user|He,She} likes this', '', array("ru" => array(
            array("label" => "{user|Ему, Ей} это нравится"),
        )));

        $this->assertEquals('Ему это нравится',
            $russian->translate('{user|He,She} likes this', '', array(
                'user' => $michael
            ))
        );

        $this->assertEquals('Ей это нравится',
            $russian->translate('{user|He,She} likes this', '', array(
                'user' => $anna
            ))
        );

        Config::instance()->finishBlockWithOptions();
    }

    public function testDefaultLanguageCases() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));

        $this->assertEquals('This is your 1st warning',
            $english->translate('This is your {count::ord} warning', '', array('count' => 1))
        );

        $this->assertEquals('This is your 2nd warning',
            $english->translate('This is your {count::ord} warning', '', array('count' => 2))
        );

        $this->assertEquals('This is your 3rd warning',
            $english->translate('This is your {count::ord} warning', '', array('count' => 3))
        );

        $this->assertEquals('This is your 4th warning',
            $english->translate('This is your {count::ord} warning', '', array('count' => 4))
        );

        $this->assertEquals('This is your first warning',
            $english->translate('This is your {count::ordinal} warning', '', array('count' => 1))
        );

        $michael = new \User("Michael", "male");
        $this->assertEquals("This is Michael's message",
            $english->translate('This is {user::pos} message', '', array('user' => $michael))
        );
    }

    public function testTranslatedLanguageCases() {
        $app = new \Tr8n\Application(self::loadJSON('application.json'));
        $english = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/en-US.json')));
        $russian = $app->addLanguage(new \Tr8n\Language(self::loadJSON('languages/ru.json')));

        $michael = new \User("Михаил", "male");
        $anna = new \User("Анна", "female");

        self::cacheTranslations($app, '{actor} sent {target} a present.', '', array("ru" => array(
            array("label" => "{actor||прислал,прислала} подарок {target::dat}."),
        )));

        $this->assertEquals('Анна прислала подарок Михаилу.',
            $russian->translate('{actor} sent {target} a present.', '', array('actor' => $anna, 'target' => $michael))
        );

        $this->assertEquals('Михаил прислал подарок Анне.',
            $russian->translate('{actor} sent {target} a present.', '', array('target' => $anna, 'actor' => $michael))
        );

    }
}