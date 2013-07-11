<?php

namespace Tr8n\Decorators;

class PlainDecorator extends Base {

    public function decorate($translation_key, $language, $label, $options) {
        return $label;
    }

}

?>