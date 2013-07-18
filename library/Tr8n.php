<?php

# TODO: comment this out once it is set in php.ini
date_default_timezone_set('America/Los_Angeles');

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
    "Tr8n/Cache"
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

function tr($label, $description = "", $tokens = array(), $options = array()) {
    $language = \Tr8n\Config::instance()->current_language;

    if (isset($options['split'])) {
        $sentences = \Tr8n\Utils\StringUtils::splitSentences($label);

        foreach($sentences as $sentence) {
            $label = str_replace($sentence, $language->translate($sentence, $description, $tokens, $options), $label);
        }

        return $label;
    }

    $stripped_label = str_replace(array("\r\n", "\n"), '', strip_tags(trim($label)));
    $label = str_replace($stripped_label, $language->translate($stripped_label, $description, $tokens, $options), $label);
	return $label;
}

function trl($label, $description = "", $tokens = array(), $options = array()) {
	$options["skip_decorations"] = true;
	return tr($label, $description, $tokens, $options);
}

?>
