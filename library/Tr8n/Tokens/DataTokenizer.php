<?php
/**
 * Copyright (c) 2013 Michael Berkovich, tr8nhub.com
 *
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

#######################################################################
#
# Decoration Token Forms:
#
# [link: click here]
#
# Decoration Tokens Allow Nesting:
#
# [link: {count} {_messages}]
# [link: {count||message}]
# [link: {count||person, people}]
# [link: {user.name}]
#
#######################################################################

namespace Tr8n\Tokens;

class DataTokenizer {

    /**
     * @var object[]
     */
    public $tokens;

    /**
     * @return array
     */
    public static function supportedTokens() {
        return array(
            '\Tr8n\Tokens\DataToken',
            '\Tr8n\Tokens\MethodToken',
            '\Tr8n\Tokens\TransformToken');
    }

    /**
     * @param string $text
     * @param array $context
     * @param array $opts
     */
    function __construct($text, $context = array(), $opts = array()) {
        $this->text = $text;
        $this->context = $context;
        $this->opts = $opts;
        $this->tokenize();
    }

    /**
     *
     */
    public function tokenize() {
        $this->tokens = array();
        foreach(self::supportedTokens() as $class) {
            preg_match_all($class::expression(), $this->text, $matches);
            $matches = array_unique($matches[0]);
            foreach($matches as $token) {
                array_push($this->tokens, new $class($this->text, $token));
            }
        }
    }

    /**
     * @param string $token
     * @return bool
     */
    function isTokenAllowed($token) {
        if (!isset($this->opts["allowed_tokens"]))
            return true;
        return in_array($token, $this->opts["allowed_tokens"]);
    }

    /**
     * @param \Tr8n\Language $language
     * @param array $options
     * @return string
     */
    public function substitute($language, $options = array()) {
        $label = $this->text;
        foreach($this->tokens as $token) {
            if (!$this->isTokenAllowed($token)) continue;
            $label = $token->substitute($label, $this->context, $language, $options);
        }
        return $label;
    }

}
