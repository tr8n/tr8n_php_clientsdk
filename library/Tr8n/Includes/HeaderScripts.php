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

if (\Tr8n\Config::instance()->isEnabled()) { ?>
    <script>
        function tr8n_add_css(doc, value, inline) {
            var css = null;
            if (inline) {
                css = doc.createElement('style'); css.type = 'text/css';
                if (css.styleSheet) css.styleSheet.cssText = value;
                else css.appendChild(document.createTextNode(value));
            } else {
                css = doc.createElement('link'); css.setAttribute('type', 'text/css');
                css.setAttribute('rel', 'stylesheet'); css.setAttribute('media', 'screen');
                if (value.indexOf('//') != -1) css.setAttribute('href', value);
                else css.setAttribute('href', '<?php echo tr8n_application()->host ?>' + value);
            }
            doc.getElementsByTagName('head')[0].appendChild(css);
            return css;
        }

        function tr8n_add_script(doc, id, src, onload) {
            var script = doc.createElement('script');
            script.setAttribute('id', id); script.setAttribute('type', 'application/javascript');
            if (src.indexOf('//') != -1)  script.setAttribute('src', src);
            else script.setAttribute('src', '<?php echo tr8n_application()->host ?>' + src);
            script.setAttribute('charset', 'UTF-8');
            if (onload) script.onload = onload;
            doc.getElementsByTagName('head')[0].appendChild(script);
            return script;
        }

        (function() {
            if (window.addEventListener) window.addEventListener('load', tr8n_init, false); // Standard
            else if (window.attachEvent) window.attachEvent('onload', tr8n_init); // Microsoft
            window.setTimeout(function() {  // just in case, hit it one more time a second later
                tr8n_init();
            }, 1000);

            function tr8n_init() {
                if (window.tr8n_already_initialized) return;
                window.tr8n_already_initialized = true;

                tr8n_add_css(window.document, '/assets/tr8n/tools.css', false);
                tr8n_add_css(window.document, "<?php echo tr8n_application()->css ?>", true);

                tr8n_add_script(window.document, 'tr8n-jssdk', '/assets/tr8n/tools.js', function() {
                    Tr8n.app_key = '<?php echo tr8n_application()->key ?>';
                    Tr8n.host = '<?php echo tr8n_application()->host ?>';
                    Tr8n.sources = <?php echo json_encode(\Tr8n\Config::instance()->requested_sources) ?>;
                    Tr8n.default_locale = '<?php echo tr8n_application()->default_locale ?>';
                    Tr8n.page_locale = '<?php echo \Tr8n\Config::instance()->current_language->locale ?>';
                    Tr8n.locale = '<?php echo \Tr8n\Config::instance()->current_language->locale ?>';

                    <?php
                        if (tr8n_application()->isFeatureEnabled("shortcuts")) {
                            foreach (tr8n_application()->shortcuts as $keys=>$script) {
                    ?>
                    shortcut.add('<?php echo $keys ?>', function() {
                        <?php echo $script ?>
                    });
                    <?php
                            }
                        }
                    ?>

                    if (typeof(tr8n_on_ready) === 'function') tr8n_on_ready();
                    if (typeof(tr8n_footer_scripts) === 'function') tr8n_footer_scripts();
                });
            }
        })();
    </script>

<?php } ?>