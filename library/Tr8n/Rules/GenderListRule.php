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

class GenderListRule extends Base {

    public static $GENDERS = array("male", "female", "neutral", "unknown");

    public $value1, $multipart, $part2, $value2;

    public function key() {
        return "gender_list";
    }

    public function genders($list) {
        $results = array();
        foreach(self::$GENDERS as $gender) {
            $results[$gender] = false;
        }

        foreach($list as $object) {
            $object_gender = \tr8n\rules\GenderRule::tokenValue($object);
            if (!$object_gender) continue;
            foreach(self::$GENDERS as $gender) {
                if ($object_gender == \Tr8n\Rules\GenderRule::genderObjectValue($gender)) {
                    $results[$gender] = true;
                }
            }
        }

        return $results;
    }

    # FORM: [one element male, one element female, at least two elements]
    # or: [one element, at least two elements]
    # {actors:gender_list|| likes, like} this story
    public function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 2:
                $options["one"] = $params[0];
                $options["other"] = $params[1];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }

    private function isMultipart() {
        return ($this->multipart == 'true');
    }

    private function isOneElement() {
        return ($this->value1 == 'one_element');
    }

    private function isAtLeastTwoElements() {
        return ($this->value1 == 'at_least_two_elements');
    }

    public function evaluate($token) {
        $list_size = $this->tokenValue($token);
        if ($list_size === null) return false;

        $genders = self::genders($token);

        if ($this->isOneElement()) {
            if ($list_size != 1) return false;
            if (!$this->isMultipart()) return true;

            if ($this->part2 == "is") {
                foreach(self::$GENDERS as $gender) {
                    if ($this->value2 == $gender && $genders[$gender])
                        return true;
                }
                return false;
            }

            if ($this->part2 == "is_not") {
                foreach(self::$GENDERS as $gender) {
                    if ($this->value2 == $gender && !$genders[$gender])
                        return true;
                }
                return false;
            }

            return false;
        }

        if ($this->isAtLeastTwoElements()) {
            if ($list_size < 2) return false;
            if (!$this->isMultipart()) return true;

            if ($this->part2 == "are") {
                if ($this->value2 == "all_male" && ($genders["male"] && !($genders["female"] || $genders["unknown"] or $genders["neutral"]))) {
                    return true;
                }
                if ($this->value2 == "all_female" && ($genders["female"] && !($genders["male"] || $genders["unknown"] or $genders["neutral"]))) {
                    return true;
                }
                if ($this->value2 == "mixed" && (  ($genders["male"] && ($genders["female"] || $genders["unknown"] or $genders["neutral"]))
                                                || ($genders["female"] && ($genders["male"] || $genders["unknown"] or $genders["neutral"])))) {
                    return true;
                }
                return false;
            }

            if ($this->part2 == "are_not") {
                if ($this->value2 == "all_male" && ($genders["male"] && ($genders["female"] || $genders["unknown"] or $genders["neutral"]))) {
                    return true;
                }
                if ($this->value2 == "all_female" && ($genders["female"] && ($genders["male"] || $genders["unknown"] or $genders["neutral"]))) {
                    return true;
                }
                if ($this->value2 == "mixed" && (  ($genders["male"] && !($genders["female"] || $genders["unknown"] or $genders["neutral"]))
                        || ($genders["female"] && !($genders["male"] || $genders["unknown"] or $genders["neutral"])))) {
                    return true;
                }
                return false;
            }

            return false;
        }


        return false;
    }
}
