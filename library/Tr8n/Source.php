<?php
namespace tr8n;

class Source extends Base {

    public $application, $source, $url, $name, $description;
    public $translation_keys;  // hashed by key

	function __construct($attributes=array()) {
        parent::__construct($attributes);
	}

}

?>