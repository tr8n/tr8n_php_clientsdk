<?php

namespace Tr8n\Decorators;

class HtmlDecorator extends Base {

    public function decorate($translation_key, $language, $label, $options) {
        if (array_key_exists("skip_decorations", $options)) return $label;
        if ($translation_key->language()->locale == $language->locale) return $label;

        $config = \Tr8n\Config::instance();
        if (!$config->current_translator) return $label;
        if (!$config->current_translator->isInlineMode()) return $label;
        if ($translation_key->isLocked() && !$config->current_translator->isManager()) return $label;

        if ($translation_key->id() === null) {
            $html = "";
            return $html;
        }

        $classes = array('tr8n_translatable');

        if ($translation_key->isLocked()) {
            array_push($classes, 'tr8n_locked');
        } else if ($language->isDefault()) {
            array_push($classes, 'tr8n_not_translated');
        } else if (array_key_exists("fallback", $options)) {
            array_push($classes, 'tr8n_fallback');
        } else if (array_key_exists("translated", $options) && $options['translated']) {
            array_push($classes, 'tr8n_translated');
        } else {
            array_push($classes, 'tr8n_not_translated');
        }

        $html = "<tr8n class='" . implode(' ', $classes) . "' translation_key_id='" . $translation_key->id() . "'>";
        $html = $html . $label;
        $html = $html . "</tr8n>";

        return $html;
    }

}
