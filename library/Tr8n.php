<?php

$files = array(
    "Tr8n/Utils",
    "Tr8n/Base.php",
    "Tr8n",
    "Tr8n/Tokens/Base.php",
    "Tr8n/Tokens",
    "Tr8n/Rules/Base.php",
    "Tr8n/Rules",
    "Tr8n/Decorators/Base.php",
    "Tr8n/Decorators",
    "Tr8n/Cache"
);

foreach($files as $dir) {
    $path = dirname(__FILE__)."/".$dir;
    if (is_dir($path)) {
        foreach (scandir($path) as $filename) {
            $file = $path . "/" . $filename;
            if (is_file($file)) {
    //            print($path."\n");
                require_once $file;
            }
        }
    } else {
        require_once $path;
    }
}

class Tr8n {
    private static $locale = 'en-US';
    private static $source = '/';

    public static function application() {
        static $application = null;
//        $application = apc_fetch("tr8n_application");
        if ($application === null) {
            $application = tr8n\Application::init("http://localhost:3000", "29adc3257b6960703", "a5af33d9d691ce0a6");
//            apc_store('tr8n_application', $application);
        }
        return $application;
    }

    public static function init_request() {

    }

    public static function reset_request() {

    }

    public static function translate($label, $description = "", $tokens = array(), $options = array()) {
        $language = Tr8n::application()->language(Tr8n::$locale);
		return $language->translate($label, $description, $tokens, $options);
	}
}

function tr($label, $description = "", $tokens = array(), $options = array()) {
	return Tr8n::translate($label, $description, $tokens, $options);
}

function trl($label, $description = "", $tokens = array(), $options = array()) {
	$options["skip_decorations"] = true;
	return tr($label, $description, $tokens, $options);
}

?>