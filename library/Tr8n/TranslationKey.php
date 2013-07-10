<?php
namespace tr8n;

class TranslationKey {
    public $application, $language, $translations;
	public $id, $key, $label, $description;
	
	public function __construct($label, $description = "", $options = array()) {
		$this->key = $this->generateKey($label, $description);
		$this->label = $label;
		$this->description = $description;
	}
	
	private function generateKey($label, $description) {
		return md5($label . ";;;" . $description);
	}

    public function language() {
        $this->language = $this->language || ($this->locale ? $this->application->language(locale) : $this->application->default_language());
        return $this->language;
    }

    public function has_translations_for_language($language) {
        if ($this->translations == null) return false;
        return ($this->translations[$language->locale] && $this->translations[$language->locale].length > 0);
    }


    function fetch_translations_for_language($language, $options = array()) {
        if ($this->id && $this->has_translations_for_language($language))
            return $this;

//        if ($options['dry'] || \Tr8n::config()->block_options['dry'])
//            return $this->application->cache_translation_key($this);

//        var $tkey = $this->application.post("translation_key/translations", $this->to_api_hash.merge(:locale => language.locale), {:class => Tr8n::TranslationKey, :attributes => {:application => application, :language => language}})
//        $this->application->cache_translation_key($tkey);
    }


	public function translate($tokens = array(), $options = array()) {
		return $this->label;
	}
}

?>