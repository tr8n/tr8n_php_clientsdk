<?php

namespace Tr8n\Decorators;

abstract class Base {

    public static function decorator() {
        $class = \Tr8n\Config::instance()->decoratorClass();
        return new $class();
    }

    public abstract function decorate($translation_key, $language, $label, $options);
}
