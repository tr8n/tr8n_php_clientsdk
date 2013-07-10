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

class GenderRule extends Base {
    public $operator, $value;

    public function key() {
        return "gender";
    }

    public static function genderObjectValueFor($type) {
        $config = \Tr8n\Config::instance();
        return $config["gender"]["method_values"][$type];
    }

    # FORM: [male, female(, unknown)]
    # {user | registered on}
    # {user | he, she}
    # {user | he, she, he/she}
    # {user | male: he, female: she, unknown: he/she}
    # {user | female: she, other: he}
    public function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 1:
                $options["other"] = $params[0];
                break;
            case 2:
                $options["male"] = $params[0];
                $options["female"] = $params[1];
                $options["other"] = "$params[0]/$params[1]";
                break;
            case 3:
                $options["male"] = $params[0];
                $options["female"] = $params[1];
                $options["other"] = $params[2];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }


    public function evaluate($token) {
        $token_value = $this->tokenValue($token);
        if (!$token_value) return false;

        if ($this->operator == "is") {
            return ($token_value == $this->genderObjectValueFor($this->value));
        }

        if ($this->operator == "is_not") {
            return ($token_value != $this->genderObjectValueFor($this->value));
        }

        return false;
    }

}

?>