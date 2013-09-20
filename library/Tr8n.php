<?php

$files = array(
    "Tr8n/Utils",
    "Tr8n/Base.php",
    "Tr8n",
    "Tr8n/Tokens/Base.php",
    "Tr8n/Tokens",
    "Tr8n/RulesEngine",
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
        \Tr8n\Logger::instance()->info("Cookie params", $cookie_params);

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


//############################################################
//# There are three ways to call the tr method
//#
//# tr($label, $description = "", $tokens = array(), options = array())
//# or
//# tr($label, $tokens = array(), $options = array())
//# or
//# tr($params = array("label" => label, "description" => "", "tokens" => array(), "options" => array()))
//############################################################

function tr($label, $description = "", $tokens = array(), $options = array()) {
    $params = \Tr8n\Utils\ArrayUtils::normalizeTr8nParameters($label, $description, $tokens, $options);

    try {
        if (isset($params["options"]['split'])) {
            $sentences = \Tr8n\Utils\StringUtils::splitSentences($params["label"]);

            foreach($sentences as $sentence) {
                $params["label"] = str_replace($sentence, tr8n_current_language()->translate($sentence, $params["description"], $params["tokens"], $params["options"]), $params["label"]);
            }

            return $label;
        }

        $stripped_label = str_replace(array("\r\n", "\n"), '', strip_tags(trim($params["label"])));
        $label = str_replace($stripped_label, tr8n_current_language()->translate($stripped_label, $params["description"],  $params["tokens"], $params["options"]), $params["label"]);
        return $label;
    } catch(\Tr8n\Tr8nException $ex) {
//        \Tr8n\Logger::instance()->error("Failed to translate " . $params["label"] . ": " . $ex);
        return $label;
    }
}

function tre($label, $description = "", $tokens = array(), $options = array()) {
    echo tr($label, $description, $tokens, $options);
}

function trl($label, $description = "", $tokens = array(), $options = array()) {
    $params = \Tr8n\Utils\ArrayUtils::normalizeTr8nParameters($label, $description, $tokens, $options);
    $params["options"]["skip_decorations"] = true;
	return tr($params);
}

function trle($label, $description = "", $tokens = array(), $options = array()) {
    echo trl($label, $description, $tokens, $options);
}
