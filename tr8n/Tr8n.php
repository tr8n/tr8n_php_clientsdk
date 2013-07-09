<?php

require_once "Config.php";
require_once "Logger.php";
require_once "Application.php";

class Tr8n {
    private static $locale = 'en-US';
    private static $source = '/';

	public static function config() {
	    static $config = null;
        if ($config === null) {
            $config = new tr8n\Config();
        }
        return $config;
	}

    public static function logger() {
        static $logger = null;
        if ($logger === null) {
            $logger = new tr8n\Logger("/Users/michael/tr8n/tr8n.log", tr8n\Logger::INFO);
        }
        return $logger;
    }

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