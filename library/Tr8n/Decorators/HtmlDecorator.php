<?php

/**
 * Copyright (c) 2014 Michael Berkovich, TranslationExchange.com
 *
 *  _______                  _       _   _             ______          _
 * |__   __|                | |     | | (_)           |  ____|        | |
 *    | |_ __ __ _ _ __  ___| | __ _| |_ _  ___  _ __ | |__  __  _____| |__   __ _ _ __   __ _  ___
 *    | | '__/ _` | '_ \/ __| |/ _` | __| |/ _ \| '_ \|  __| \ \/ / __| '_ \ / _` | '_ \ / _` |/ _ \
 *    | | | | (_| | | | \__ \ | (_| | |_| | (_) | | | | |____ >  < (__| | | | (_| | | | | (_| |  __/
 *    |_|_|  \__,_|_| |_|___/_|\__,_|\__|_|\___/|_| |_|______/_/\_\___|_| |_|\__,_|_| |_|\__, |\___|
 *                                                                                        __/ |
 *                                                                                       |___/
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Tr8n\Decorators;

use Tr8n\Config;

class HtmlDecorator extends Base {

    /**
     * @param \Tr8n\TranslationKey $translation_key
     * @param \Tr8n\Language $language
     * @param string $label
     * @param array $options
     * @return string
     */
    public function decorate($translation_key, $language, $label, $options) {
        if (array_key_exists("skip_decorations", $options)) return $label;
//        if ($translation_key->locale == $language->locale) return $label;

        $config = Config::instance();
        if ($config->current_translator == null) return $label;
        if (!$config->current_translator->isInlineModeEnabled()) return $label;
        if ($translation_key->isLocked() && !$config->current_translator->isManager()) return $label;

        $element = "tr8n:tr";
        if (isset($options["use_div"])) {
            $element = "div";
        }

        if ($translation_key->id == null) {
            $html = "<".$element." class='tr8n_pending'>" . $label . "</".$element.">";
            return $html;
        }

        $classes = array('tr8n_translatable');

        if ($translation_key->isLocked()) {
            if ($config->current_translator->isFeatureEnabled('show_locked_keys')) {
                array_push($classes, 'tr8n_locked');
            } else {
                return $label;
            }
        } else if ($language->isDefault()) {
            array_push($classes, 'tr8n_not_translated');
        } else if (array_key_exists("fallback", $options)) {
            array_push($classes, 'tr8n_fallback');
        } else if (array_key_exists("translated", $options) && $options['translated']) {
            array_push($classes, 'tr8n_translated');
        } else {
            array_push($classes, 'tr8n_not_translated');
        }

        $html = "<".$element." class='" . implode(' ', $classes) . "' data-translation_key_id='" . $translation_key->id . "'>";
        $html = $html . $label;
        $html = $html . "</".$element.">";

        return $html;
    }

}
