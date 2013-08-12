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

namespace Tr8n\Rules;

class DateRule extends Base {

    public $value;

    public function key() {
        return "date";
    }

    # FORM: [past, present, future]
    # This event {date | took place, is taking place, will take place} on {date}.
    # This event {date | past: took place, present: is taking place, future: will take place} on {date}.
    public function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 3:
                $options["past"] = $params[0];
                $options["present"] = $params[1];
                $options["future"] = $params[2];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }

    public function evaluate($token) {
        $token_value = $this->tokenValue($token);
        if ($token_value === null) return false;

        $current_date = strtotime(date("Y-m-d"));
        switch ($this->value) {
            case "past":
                return ($token_value < $current_date);
            case "present":
                return ($token_value == $current_date);
            case "future":
                return ($token_value > $current_date);
        }

        return false;
    }
}
