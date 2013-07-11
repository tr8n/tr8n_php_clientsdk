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

namespace Tr8n\Tokens;

use tr8n\Tr8nException;

abstract class Base {

    protected $label, $name, $full_name, $declared_name, $sanitized_name, $pipeless_name;
    protected $case_key;

    public static function registerTokens($label, $category = "data") {
        $tokens = array();
        foreach(\Tr8n\Config::instance()->tokenClasses($category) as $class) {
            $token = new $class($label, null);
            $matches = $token->parse($label);
            array_push($tokens, $matches);
        }
        return \Tr8n\Utils\ArrayUtils::flatten($tokens);
    }

    function __construct($label, $token) {
        $this->label = $label;
        $this->full_name = $token;
    }

    public abstract function expression();

    public function parse($label) {
        $matches = array();
        preg_match_all($this->expression(), $label, $matches);
        $matches = array_unique($matches[0]);
        $tokens = array();
        $class = get_called_class();
        foreach($matches as $token) {
           array_push($tokens, new $class($label, $token));
        }
        return $tokens;
    }

    public function fullName() {
        return $this->full_name;
    }

    public function declaredName() {
        if ($this->declared_name === null) {
            $this->declared_name = preg_replace('/[{}\[\]]/', '', $this->fullName());
        }
        return $this->declared_name;
    }

    public function name() {
        if ($this->name === null) {
            $parts = explode(':', $this->declaredName());
            $this->name = trim($parts[0]);
        }
        return $this->name;
    }

    public function sanitizedName() {
        if ($this->sanitized_name == null) {
            $this->sanitized_name = "{" . $this->name() . "}";
        }
        return $this->sanitized_name;
    }

    public function pipelessName() {
        if ($this->pipeless_name == null) {
            $parts = explode('|', $this->declaredName());
            $this->pipeless_name = $parts[0];
        }
        return $this->pipeless_name;
    }

    public function caseKey() {
        if (strpos($this->declaredName(), '::') === FALSE) {
            return null;
        }
    }

    /*
     * Method for getting an object from values hash.
     *
     */
    public static function tokenObject($token_values, $token_name) {
        if ($token_values == null)
            return null;

        if (!array_key_exists($token_name, $token_values))
            return null;

        $token_object = $token_values[$token_name];

        if (is_array($token_object)) {
            if (\Tr8n\Utils\ArrayUtils::isHash($token_object)) {
                if (!array_key_exists('object', $token_object)) return null;
                return $token_object['object'];
            }
            return $token_object[0];
        }

        return $token_object;
    }

    /*
     * Method for getting a value from values hash.
     *
     * Token objects can be passed as:
     *
     * - if an object is passed without a substitution value, it will use __toString() to get the value
     *
     *     tr("Hello {user}", array("user" => $current_user));
     *     tr("{count||message}", array("count" => $counter));
     *
     * - if object is an array, the second value is the substitution value
     *
     *     tr("Hello {user}", array("user" => array($current_user, $current_user->name)));
     *
     * - if the substitution value starts with @@ - it is a method of an object
     *
     *     tr("Hello {user}", array("user" => array($current_user, "@@name")));
     *
     * - Second parameter can be an anonymous function
     *
     *     tr("Hello {user}", array("user" => array(current_user, function($object) {
     *       return $object->name;
     *     })));
     *
     * - Parameter can be a hash, which must contain "object" and value/attribute properties
     *
     *     tr("Hello {user}", array("user" => array("object" => array("gender"=>"male"), "value"=>"Michael")));
     *     tr("Hello {user}", array("user" => array("object" => array("gender"=>"male", "name"=>"Michael), "attribute"=>"name")));
     *
     */
    public function tokenValue($token_data, $language, $options) {
        if (is_array($token_data)) {
            if (\Tr8n\Utils\ArrayUtils::isHash($token_data)) {
                if (!array_key_exists('object', $token_data))
                    throw new Tr8nException("object attribute is missing in the hash for token: " . $this->fullName());

                $token_object = $token_data['object'];

                if (array_key_exists('value', $token_data)) {
                    return $this->sanitize($token_data['value'], $language, array_merge($options, array("sanitize" => false)));
                }


                if (array_key_exists('attribute', $token_data)) {
                    $attribute = $token_data['attribute'];
                    if (is_array($token_object)) {
                        if (array_key_exists($attribute, $token_object)) {
                            return $this->sanitize($token_object[$attribute], $language, array_merge($options, array("sanitize" => true)));
                        }
                        throw new Tr8nException("Invalid attribute properties for object in the hash of token: " . $this->fullName());
                    }
                    return $this->sanitize($token_object->$attribute, $language, array_merge($options, array("sanitize" => true)));
                }

                throw new Tr8nException("value and attribute properties are missing in the hash for token: " . $this->fullName());
            }

            if (count($token_data) == 0)
                throw new Tr8nException("Invalid array value for token: " . $this->fullName());

            $token_object = $token_data[0];

//            if (is_array($token_object)) {
//            }

            if (count($token_data) == 1)
                return $this->sanitize($token_object, $language, array_merge($options, array("sanitize" => true)));

            $token_method = $token_data[1];

            if (is_string($token_method)) {
                if (preg_match('/^@@/', $token_method)) {
                    $attribute = substr($token_method, -2);
                    $token_value = $token_object->$attribute;
                    return $this->sanitize($token_value, $language, array_merge($options, array("sanitize" => true)));
                }
                return $this->sanitize($token_method, $language, array_merge($options, array("sanitize" => false)));
            }

            if (is_callable($token_method)) {
                $token_value = $token_method($token_object);
                return $this->sanitize($token_value, $language, array_merge($options, array("sanitize" => false)));
            }

            throw new Tr8nException("Unsupported token array method for token: " . $this->fullName());
        }

        return $this->sanitize($token_data, $language, $options);
    }

    public function sanitize($token_object, $language, $options) {
        $token_value = "".$token_object;

        // TODO: add language cases support and HTML escaping

        return $token_value;
    }

    public function substitute($translation_key, $language, $label, $token_values, $options) {
        if (array_key_exists($this->name(), $token_values)) {
            $token_data = $token_values[$this->name()];
        } else {
            $token_data = $translation_key->application->defaultTokens($this->name(), 'data');
            if ($token_data == null) {
                throw new Tr8nException("Missing value for token: " . $this->fullName());
            }
        }

        $token_value = $this->tokenValue($token_data, $language, $options);
        return str_replace($this->fullName(), $token_value, $label);
    }


}

