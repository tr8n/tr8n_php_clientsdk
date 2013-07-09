<?php
namespace tr8n;

class Source extends Base {

    public $application, $source, $url, $name, $description;
    public $translation_keys;  // hashed by key

	function __construct($attributes) {
        parent::__construct($attributes);
	}

}

?>