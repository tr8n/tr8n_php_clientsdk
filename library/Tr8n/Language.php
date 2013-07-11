<?php
namespace tr8n;

require_once 'Base.php';
require_once 'TranslationKey.php';
require_once 'Rules/Base.php';

class Language extends Base {

    public $application;
	public $locale, $name, $english_name, $native_name, $right_to_left, $enabled;
    public $google_key, $facebook_key, $myheritage_key, $context_rules, $language_cases;

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->context_rules = array();
        if (array_key_exists('context_rules', $attributes)) {
            foreach($attributes['context_rules'] as $rule_class => $hash) {
                if (!array_key_exists($rule_class, $this->context_rules))
                    $this->context_rules[$rule_class] = array();

                foreach($hash as $keyword => $rule) {
                    $class_name = Config::instance()->ruleClassByType($rule_class);
                    $this->context_rules[$rule_class][$keyword] = new $class_name(array_merge($rule, array("language" => $this)));
                }
            }

        }
    }

    public function contextRule($type, $key = null) {
        if ($key === null)
            return $this->context_rules[$type];

        return $this->context_rules[$type][$key];
    }

    public function languageCase($key) {
        return $this->language_cases[$key];
    }

    public function isDefault() {
        if ($this->application == null) return false;
        return ($this->application->defaultLocale() === $this->locale);
    }

    public function direction() {
        return $this->right_to_left ? "rtl" : "ltr";
    }

    public function alignment($default) {
        if ($this->right_to_left) return $default;
        return $this->right_to_left ? "right" : "left";
    }

	public function translate($label, $description = "", $tokens = array(), $options = array()) {

        if (Config::instance()->isDisabled()) {
            return TranslationKey::substitute_tokens($this, $label, $tokens, $options);
        }

        # create a temporary key
        $temp_key = new TranslationKey(array(
            "application"   => $this->application,
            "label"         => $label,
            "description"   => $description,
            "locale"        => array_key_exists("locale", $options) ? $options["locale"] : Config::instance()->default_locale,
            "level"         => array_key_exists("level", $options) ? $options["level"] : 0,
            "translations"  => array()
         ));

        $source_key = $options["source"] || Config::instance()->current_source;
        $cached_key = null;
        if ($source_key) {
            $source = $this->application->sourceByKey($source_key);
            $source_translation_keys = $source->fetchTranslationsForLanguage($this, $options);
            $cached_key = $source_translation_keys[$temp_key->key()];
            if ($cached_key === null) {
                $this->application->registerMissingKey($temp_key, $source);
                $cached_key = $temp_key;
            }
        } else {
            $cached_key = $this->application->traslationKeyByKey($temp_key->key());
            if ($cached_key === null) {
                $cached_key = $temp_key->fetchTranslationsForLanguage($this, $options);
            }
        }

        return $cached_key->translate($this, array_merge($tokens, array("viewing_user" => Config::instance()->current_user)), $options);
	}

}

?>