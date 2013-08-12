<?php
#--
# Copyright (c) 2010-2013 Michael Berkovich, tr8nhub.com
#
# Permission is hereby granted, free of charge, to any person obtaining
# a copy of this software and associated documentation files (the
# "Software"), to deal in the Software without restriction, including
# without limitation the rights to use, copy, modify, merge, publish,
# distribute, sublicense, and/or sell copies of the Software, and to
# permit persons to whom the Software is furnished to do so, subject to
# the following conditions:
#
# The above copyright notice and this permission notice shall be
# included in all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#++

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

class DecorationToken extends Base {

    private $decorated_value;

    public function expression() {
        return '/(\[\w+:[^\]]+\])/';
    }

    function name() {
        if ($this->name == null) {
            $this->name = substr($this->full_name, 1, -1);
            $parts = explode(':', $this->name);
            $this->name = trim(array_shift($parts));
        }
        return $this->name;
    }

    public function isToken() {
        return preg_match('/^\[\w+:/', $this->fullName());
    }

    public function isSimple() {
        return !preg_match($this->expression(), $this->decoratedValue());
    }

    public function isNested() {
        return !$this->isSimple();
    }

    public function appendToFullName($str) {
        $this->full_name = $this->full_name . $str;
    }

    public function parse($label, $options = array()) {
        $length = strlen($label);
        $tokens = array();
        $candidates = array();
        for ($position=0; $position < $length; $position++) {
            $ch = $label[$position];
            foreach($tokens as $token) {
                $token->appendToFullName($ch);
            }
            switch ($ch) {
                case '[':
                    array_push($tokens, new DecorationToken($label, "["));
                    break;

                case ']':
                    if (count($tokens) > 0) {
                        array_push($candidates,  array_pop($tokens));
                    }
                    break;
            }
        }

        $exclude_nested = array_key_exists('exclude_nested', $options) && $options['exclude_nested'];
        $tokens = array();
        foreach($candidates as $candidate) {
            if (!$candidate->isToken()) continue;
            if ($exclude_nested && $candidate->isNested()) continue;
            array_push($tokens, $candidate);
        }
        return $tokens;
    }

    public function sanitizedName() {
        if ($this->sanitized_name == null) {
            $this->sanitized_name = "[" . $this->name() . ": ]";
        }
        return $this->sanitized_name;
    }

    public function decoratedValue() {
        if (!$this->decorated_value) {
            $this->decorated_value = substr($this->full_name, 1, -1);
            $parts = explode(':', $this->decorated_value);
            array_shift($parts);
            $this->decorated_value = trim(implode(':', $parts));
        }
        return $this->decorated_value;
    }


    /*
     * There are a number of ways to substitute decoration tokens:
     *
     * Using anonymous function:
     *      tr("Hello [bold: world]", array("bold" => function($text) { return "<strong>$text</strong>" }))
     *
     * Using string decoration:
     *      tr("Hello [bold: world]", array("bold" => ""))
     *
     *
     * Using default decoration: (no need to define it)
     *      tr("Hello [bold: world]")
     *
     * where bold is defined as: "<strong>{$0}</strong>"
     * {$0} is always the translated value
     *
     * More complex examples:
     *      tr("Hello [link: world]", array("link" => array("href"=>"http://www.google.com")))
     *      tr("Hello [span: my [bold: world]]", array("span" => array("class"=>"some class", "style"=>"some style")))
     *
     * where link is defined as: "<a href='{$href}'>{$0}</a>"
     *
     */
    public function substitute($label, $token_values, $language, $options = array()) {
        if (array_key_exists($this->name(), $token_values)) {
            $token_data = $token_values[$this->name()];

            if (is_string($token_data)) {
                $token_value = str_replace('{$0}', $this->decoratedValue(), $token_data);
            } else if (is_callable($token_data)) {
                $token_value = $token_data($this->decoratedValue());
            } else if (is_array($token_data)) {
                $token_value = $this->defaultToken($token_data, $language, $options);
            }
        } else {
            $token_value = $this->defaultToken(null, $language, $options);
        }

        return str_replace($this->fullName(), $token_value, $label);
    }

    public function defaultToken($token_data, $language, $options = array()) {
        $token_value = $language->application->defaultToken($this->name(), 'decoration');
        if ($token_value == null)
            throw new Tr8nException("Invalid decoration token for label: $this->label");

        $token_value = "".$token_value;

        if ($token_data == null) $token_data = array();

        $token_value = str_replace('{$0}', $this->decoratedValue(), $token_value);
        if (\Tr8n\Utils\ArrayUtils::isHash($token_data)) {
            foreach($token_data as $key=>$value) {
                $token_value = str_replace('{$' . $key . '}', $value, $token_value);
            }
        } else {
            $index = 1;
            foreach($token_data as $value) {
                $token_value = str_replace('{$' . ($index++) . '}', $value, $token_value);
            }
        }

        return $token_value;
    }
}
