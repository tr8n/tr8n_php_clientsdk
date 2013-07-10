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
                foreach($values as $value) {
                    if (preg_match('/^'.$value.'/', token_value))
                        return true;
                }
                return false;
            case "does_not_start_with":
                foreach($values as $value) {
                    if (preg_match('/^'.$value.'/', token_value))
                        return false;
                }
                return true;
            case "ends_in":
                foreach($values as $value) {
                    if (preg_match('/'.$value.'$/', token_value))
                        return true;
                }
                return false;
            case "does_not_end_in":
                foreach($values as $value) {
                    if (preg_match('/'.$value.'$/', token_value))
                        return false;
                }
                return true;
            case "is":
                return array_key_exists($token_value, $values);
            case "is_not":
                return !array_key_exists($token_value, $values);
        }

        return false;
    }
}

?>