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

namespace tr8n\rules;

class ValueRule extends Base {

    public $operator, $value;

    public function key() {
        return "value";
    }

    public static function isTransformable() {
        return false;
    }

    public function defaultTransformOptions($params, $token) {
        return array();
    }

    public function evaluate($token) {
        $token_value = preg_replace('/<\/?[^>]*>/', '', $this->tokenValue($token));
        $values = \Tr8n\Utils\ArrayUtils::split($this->value);

        switch ($this->operator) {
            case "starts_with":
                return \Tr8n\Utils\StringUtils::startsWith($values, $token_value);
            case "does_not_start_with":
                return !\Tr8n\Utils\StringUtils::startsWith($values, $token_value);
            case "ends_in":
                return \Tr8n\Utils\StringUtils::endsWith($values, $token_value);
            case "does_not_end_in":
                return !\Tr8n\Utils\StringUtils::endsWith($values, $token_value);
            case "is":
                return in_array($token_value, $values);
            case "is_not":
                return !in_array($token_value, $values);
        }

        return false;
    }
}
