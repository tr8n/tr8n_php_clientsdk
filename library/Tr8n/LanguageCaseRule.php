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

namespace Tr8n;

use \Tr8n\Utils\ArrayUtils;
use \Tr8n\Rules\GenderRule;

class LanguageCaseRule extends Base {

    public $language_case;
    public $gender, $operator, $multipart, $part1, $value1, $part2, $value2, $operation, $operation_value;

    public function evaluate($object, $value) {
        if (in_array($this->gender, Config::instance()->supportedGenders())) {
            $object_gender = GenderRule::genderObjectValue($this->gender);
            foreach(Config::instance()->supportedGenders() as $gender) {
                if ($this->gender==$gender && $object_gender!=GenderRule::genderObjectValue($gender))
                    return false;
            }
        }

        $result1 = $this->evaluatePart($value, 1);

        if (!$this->isMultipart())
            return $result1;

        $result2 = $this->evaluatePart($value, 2);

        if ($this->operator == 'and' && ($result1 && $result2))
            return true;

        if ($this->operator == 'or' && ($result1 || $result2))
            return true;

        return false;
    }

    private function isMultipart() {
        return ($this->multipart == 'true');
    }

    public function evaluatePart($token_value, $index) {
        $case_part = "part".$index;
        $case_part = $this->$case_part;
        $case_value = "value".$index;
        $case_value = $this->$case_value;

        $values = ArrayUtils::split($case_value);

        switch ($case_part) {
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

    public function apply($value) {
        $values = ArrayUtils::split($this->value1);
        $regex = implode($values, '|');

        switch ($this->operation) {
            case "replace":
                switch ($this->part1) {
                    case "starts_with":
                        return preg_replace('/^('.$regex.')/', $this->operation_value, $value);
                    case "is":
                        return $this->operation_value;
                    case "ends_in":
                        return preg_replace('/('.$regex.')$/', $this->operation_value, $value);
                }
            case "prepand":
                return "".$this->operation_value.$value;
            case "append":
                return "".$value.$this->operation_value;
        }

        return $value;
    }

}