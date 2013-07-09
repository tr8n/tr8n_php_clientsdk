<?php
namespace tr8n;

require_once "TranslationKey.php";
require_once "rules\Base.php";

class Language extends Base {

    public $application;
	public $locale, $name, $english_name, $native_name, $right_to_left, $enabled;
    public $google_key, $facebook_key, $myheritage_key, $context_rules, $language_cases;

    function __construct($attributes) {
        parent::__construct($attributes);

        if ($attributes['context_rules']) {
            $this->context_rules = array();
            foreach($attributes['context_rules'] as $rule_class => $hash) {
                if ($this->context_rules[$rule_class]) $this->context_rules[$rule_class] = array();
                foreach($hash as $keyword => $rule) {
                    $class_name = rules\Base::rule_class(rule_class);
                    $this->context_rules[$rule_class][$keyword] = new $class_name(array_merge($rule, array("language" => $this)));
                }
            }

        }
    }

	public function translate($label, $description = "", $tokens = array(), $options = array()) {
        $tkey =  new TranslationKey($label, $description);
		return $tkey->translate($tokens, $options);
	}
}

?>