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

use tr8n\Tr8nException;

abstract class Base extends \tr8n\Base {
    public $language, $type, $keyword;

    public static abstract function key();
    public static abstract function defaultTransformOptions($params, $token);

    public static function ruleClass($type) {
        return \Tr8n::config()->ruleClassByType($type);
    }

    public static function config() {
        return \Tr8n::config()->rulesEngine()[self::key()];
    }

    public static function methodName() {
        return self::config()["object_method"];
    }

    public static function tokenValue($token) {
        if (is_array($token)) {
            if (!$token["object"]) return null;
            if (is_array($token["object"])) return $token["object"]["method_name"];
            $method = self::methodName();
            return $token["object"]->$method();
        }

        $method = self::methodName();
        if (!$token || !method_exists($token, $method)) return null;
        return $token->$method;
    }

    public static function sanitizeValues($value) {
        if (!$value) return null;
        return array_map('trim', explode(",", $value));
    }

    public static function isTransformable() {
        return true;
    }

    public abstract function evaluate($token);

    public static function transform($token, $object, $params, $language) {
        if ($params.length == 0) {
            throw new Tr8nException("Invalid form for token $token");
        }

        $options = array();
        if (strpos($params[0], ':') !== FALSE) {
            foreach($params as $arg) {
                $parts = explode(":", $arg);
                $options[trim($parts[0])] = trim($parts[1]);
            }
        } else {
            $options = self::defaultTransformOptions($params, $token);
        }

        $matched_key = null;
        foreach(array_keys($options) as $key) {
            if ($key == "other") continue;
            $rule = $language->contextRuleByTypeAndKey(self::key(), $key);
            if (!$rule) {
                throw new Tr8nException("Invalid rule name $key for transform token $token");
            }

            if ($rule->evaluate($object)) {
                $matched_key = $key;
                break;
            }
        }

        if (!$matched_key) {
            if ($options["other"]) {
                return $options["other"];
            }
            throw new Tr8nException("No rules matched for transform token $token : $options : $object");
        }

        return $options[$matched_key];
    }
}

?>