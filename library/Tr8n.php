<?php

# TODO: comment this out once it is set in php.ini
# date_default_timezone_set('America/Los_Angeles');

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
    "Tr8n/Cache/Base.php",
    "Tr8n/Cache",
    "Tr8n/Includes/Tags.php"
);

foreach($files as $dir) {
    $path = dirname(__FILE__)."/".$dir;
    if (is_dir($path)) {
        foreach (scandir($path) as $filename) {
            $file = $path . "/" . $filename;
            if (is_file($file)) {
                require_once $file;
            }
        }
    } else {
        require_once $path;
    }
}

function tr8n_init_client_sdk($host, $key, $secret) {
    header('Content-type: text/html; charset=utf-8');
    \Tr8n\Config::instance()->initApplication($host, $key, $secret);

    if (\Tr8n\Config::instance()->isDisabled()) {
        \Tr8n\Logger::instance()->error("Tr8n application failed to initialize. Please verify if you set the host, key and secret correctly.");
        return false;
    }

    $cookie_name = "tr8n_" . \Tr8n\Config::instance()->application->key;
    \Tr8n\Logger::instance()->info("Locating cookie file $cookie_name...");

    $locale = \Tr8n\Config::instance()->default_locale;
    $translator = null;

    if (isset($_COOKIE[$cookie_name])) {
        \Tr8n\Logger::instance()->info("Cookie file $cookie_name found!");

        $cookie_params = \Tr8n\Config::instance()->decodeAndVerifyParams($_COOKIE[$cookie_name], \Tr8n\Config::instance()->application->secret);
        $locale = $cookie_params['locale'];
        if (isset($cookie_params["translator"])) {
            $translator = new \Tr8n\Translator(array_merge($cookie_params["translator"], array('application' => \Tr8n\Config::instance()->application)));
        }
    } else {
        \Tr8n\Logger::instance()->info("Cookie file $cookie_name not found!");
    }

    \Tr8n\Config::instance()->initRequest(array('locale' => $locale, 'translator' => $translator));
}

function tr8n_complete_request($options = array()) {
    \Tr8n\Config::instance()->completeRequest($options);
}

function tr8n_application() {
    return \Tr8n\Config::instance()->application;
}

function tr8n_current_language() {
    return \Tr8n\Config::instance()->current_language;
}

function tr8n_current_translator() {
    return \Tr8n\Config::instance()->current_translator;
}

function tr8n_begin_block_with_options($options = array()) {
    return \Tr8n\Config::instance()->beginBlockWithOptions($options);
}

function tr8n_finish_block_with_options() {
    return \Tr8n\Config::instance()->finishBlockWithOptions();
}

function tr($label, $description = "", $tokens = array(), $options = array()) {
    try {
        if (isset($options['split'])) {
            $sentences = \Tr8n\Utils\StringUtils::splitSentences($label);

            foreach($sentences as $sentence) {
                $label = str_replace($sentence, tr8n_current_language()->translate($sentence, $description, $tokens, $options), $label);
            }

            echo $label;
            return;
        }

        $stripped_label = str_replace(array("\r\n", "\n"), '', strip_tags(trim($label)));
        $label = str_replace($stripped_label, tr8n_current_language()->translate($stripped_label, $description, $tokens, $options), $label);
        echo $label;
    } catch(\Tr8n\Tr8nException $ex) {
        \Tr8n\Logger::instance()->error("Failed to translate $label : $ex");
        echo $label;
    }
}

function trl($label, $description = "", $tokens = array(), $options = array()) {
	$options["skip_decorations"] = true;
	echo tr($label, $description, $tokens, $options);
}

?>
