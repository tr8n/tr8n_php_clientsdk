<?php

namespace tr8n;

require_once "Logger.php";
require_once "Application.php";

class Config {

    public $application, $default_locale;
    public $current_user, $current_language, $current_translator, $current_source, $current_component;
    public $current_translation_keys;

    private $rules_engine, $token_classes;

    public function isEnabled() {
        return true;
    }

    public function isDisabled() {
        return !self::isEnabled();
    }

    public function isLoggerEnabled() {
        return true;
    }

    public function logPath() {
        return "/Users/michael/tr8n/tr8n.log";
    }

    public function isCachingEnabled() {
        return true;
    }

    public function cacheStore() {
        return "memcache";
    }

    public function decoratorClass() {
        return "\tr8n\decorators\Default";
    }

    public function rulesEngine() {
        if ($this->rules_engine == null) {
            $this->rules_engine = array(
                "number" => array(
                    "class"             => '\tr8n\rules\NumericRule',
                    "tokens"            => array("count", "num", "age", "hours", "minutes", "years", "seconds"),
                    "object_method"     => "to_i"
                ),
                "gender" => array(
                    "class"            => '\tr8n\rules\GenderRule',
                    "tokens"           => array("user", "profile", "actor", "target"),
                    "object_method"    => "gender",
                    "method_values"    =>  array(
                        "female"         => "female",
                        "male"           => "male",
                        "neutral"        => "neutral",
                        "unknown"        => "unknown"
                    )
                ),
                "gender_list" => array(   // requires gender rule to be present
                    "class"            => '\tr8n\rules\GenderListRule',
                    "tokens"           => array("users", "profiles", "actors", "targets"),
                    "object_method"    => "size"
                ),
                "list" => array(
                    "class"            => '\tr8n\rules\ListRule',
                    "tokens"           => array("list", "items", "objects", "elements"),
                    "object_method"    => "size"
                ),
                "date" => array(
                    "class"            => '\tr8n\rules\DateRule',
                    "tokens"           => array("date"),
                    "object_method"    => "to_date"
                ),
                "value" => array(
                    "class"            => '\tr8n\rules\ValueRule',
                    "tokens"           => "*",
                    "object_method"    => "to_s"
                )
            );
        }
        return $this->rules_engine;
    }

    public function ruleClassByType($type) {
        if (!$this->rulesEngine()[$type]) return null;
        return $this->rulesEngine()[$type]["class"];
    }

    public function ruleTypesByTokenName($token_name) {
        $types = array();
        $sanitized_token_name = preg_replace("/[^A-Za-z]/", '', end(array_values(explode("_", $token_name))));

        foreach($this->rulesEngine() as $type => $config) {
            if ($config["tokens"] == "*" || in_array($sanitized_token_name, $config["tokens"])) {
                array_push($types, $type);
            }
        }
        return $types;
    }

    public function tokenClasses() {
        if ($this->token_classes == null) {
            $this->token_classes = array(
                "data" => array('\tr8n\tokens\Data', '\tr8n\tokens\Hidden', '\tr8n\tokens\Method', '\tr8n\tokens\Transform'),
                "decoration" => array('\tr8n\tokens\Decoration')
            );
        }
        return $this->token_classes;
    }

    public function dataTokenClasses() {
        return $this->tokenClasses()["data"];
    }

    public function decorationTokenClasses() {
        return $this->tokenClasses()["decoration"];
    }
}

?>