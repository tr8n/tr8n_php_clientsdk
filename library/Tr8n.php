<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$files = array(
    "Tr8n/Utils",
    "Tr8n/Base.php",
    "Tr8n",
    "Tr8n/Tokens",
    "Tr8n/RulesEngine",
    "Tr8n/Decorators/Base.php",
    "Tr8n/Decorators",
    "Tr8n/Cache/Base.php",
    "Tr8n/Cache",
    "Tr8n/Cache/Generators/Base.php",
    "Tr8n/Cache/Generators",
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

function tr8n_init_client_sdk($host = null, $key = null, $secret = null) {
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

    return true;
}

function tr8n_complete_request($options = array()) {
    \Tr8n\Config::instance()->completeRequest($options);
}

function tr8n_application() {
    return \Tr8n\Config::instance()->application;
}

/**
 * @return \Tr8n\Language
 */
function tr8n_current_language() {
    return \Tr8n\Config::instance()->current_language;
}

function tr8n_current_translator() {
    return \Tr8n\Config::instance()->current_translator;
}

function tr8n_begin_block_with_options($options = array()) {
    \Tr8n\Config::instance()->beginBlockWithOptions($options);
}

function tr8n_finish_block_with_options() {
    return \Tr8n\Config::instance()->finishBlockWithOptions();
}

/**
 * There are three ways to call this method:
 *
 * 1. tr($label, $description = "", $tokens = array(), options = array())
 * 2. tr($label, $tokens = array(), $options = array())
 * 3. tr($params = array("label" => label, "description" => "", "tokens" => array(), "options" => array()))
 *
 * @param string $label
 * @param string $description
 * @param array $tokens
 * @param array $options
 * @return mixed
 */
function tr($label, $description = "", $tokens = array(), $options = array()) {
    $params = \Tr8n\Utils\ArrayUtils::normalizeTr8nParameters($label, $description, $tokens, $options);

    try {
        // Translate individual sentences
        if (isset($params["options"]['split'])) {
            $sentences = \Tr8n\Utils\StringUtils::splitSentences($params["label"]);
            foreach($sentences as $sentence) {
                $params["label"] = str_replace($sentence, tr8n_current_language()->translate($sentence, $params["description"], $params["tokens"], $params["options"]), $params["label"]);
            }
            return $label;
        }

        // Remove html and translate the content
        if (isset($params["options"]["strip"])) {
            $stripped_label = str_replace(array("\r\n", "\n"), '', strip_tags(trim($params["label"])));
            $translation = tr8n_current_language()->translate($stripped_label, $params["description"], $params["tokens"], $params["options"]);
            $label = str_replace($stripped_label, $translation, $params["label"]);
            return $label;
        }

        return tr8n_current_language()->translate($params["label"], $params["description"], $params["tokens"], $params["options"]);
    } catch(\Tr8n\Tr8nException $ex) {
        \Tr8n\Logger::instance()->error("Failed to translate " . $params["label"] . ": " . $ex);
        return $label;
    } catch(\Exception $ex) {
        \Tr8n\Logger::instance()->error("ERROR: Failed to translate " . $params["label"] . ": " . $ex);
        throw $ex;
    }
}

/**
 * Translates a label and prints it to the page
 *
 * @param string $label
 * @param string $description
 * @param array $tokens
 * @param array $options
 */
function tre($label, $description = "", $tokens = array(), $options = array()) {
    echo tr($label, $description, $tokens, $options);
}

/**
 * Translates a label while suppressing its decorations
 * The method is useful for translating alt tags, list options, etc...
 *
 * @param string $label
 * @param string $description
 * @param array $tokens
 * @param array $options
 * @return mixed
 */
function trl($label, $description = "", $tokens = array(), $options = array()) {
    $params = \Tr8n\Utils\ArrayUtils::normalizeTr8nParameters($label, $description, $tokens, $options);
    $params["options"]["skip_decorations"] = true;
	return tr($params);
}

/**
 * Same as trl, but with printing it to the page
 *
 * @param string $label
 * @param string $description
 * @param array $tokens
 * @param array $options
 */
function trle($label, $description = "", $tokens = array(), $options = array()) {
    echo trl($label, $description, $tokens, $options);
}

/**
 * Translates a block of html, converts it to TML, then prints it back to HTML
 *
 * @param string $html
 * @param string $description
 * @param array $tokens
 * @param array $options
 */
function trh($html, $description = "", $tokens = array(), $options = array()) {

    $html = trim($html);
    $ht = new \Tr8n\Tokens\HtmlTokenizer($html);
    $tokens = $ht->context; //array_merge($ht->context, $tokens);
//    \Tr8n\Logger::instance()->error("Translating HTML: ". $ht->tml, $ht->context);
    $options["use_div"] = true;
    tre($ht->tml, $description, $tokens, $options);
}
