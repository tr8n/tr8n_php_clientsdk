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

namespace Tr8n\Utils;

class ArrayUtils {

    public static function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    public static function split($value, $delimiter = ',') {
        if (!$value) return null;
        return array_map('trim', explode($delimiter, $value));
    }

    public static function isHash($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public static function createAttribute(&$target, $parts, $value) {
        foreach ($parts as $sub) {
            if (! isset($target[$sub])) {
                $target[$sub] = array();
            }
            $target = & $target[$sub];
        }
        $target = $value;
    }

    public static function toHTMLAttributes($arr) {
        $attrs = array();
        foreach($arr as $key=>$value) {
             array_push($attrs, $key . '="' . $value . '"');
        }
        return implode($attrs, " ");
    }

    public static function normalizeTr8nParameters($label, $description = "", $tokens = array(), $options = array()) {
        if (is_array($label)) return $label;

        if (is_array($description)) {
            return array(
                "label" => $label,
                "description" => "",
                "tokens" => $description,
                "options" => $tokens
            );
        }

        return array(
            "label" => $label,
            "description" => $description,
            "tokens" => $tokens,
            "options" => $options
        );
    }
}