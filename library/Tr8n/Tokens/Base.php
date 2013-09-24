<?php
/**
 * Copyright (c) 2013 Michael Berkovich, tr8nhub.com
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Tr8n\Tokens;
use Tr8n\Config;
use Tr8n\Tr8nException;
use \Tr8n\Utils\ArrayUtils;

abstract class Base {

    protected $label, $full_name, $short_name, $case_keys, $context_keys;

    /**
     * @param $label
     * @param string $category
     * @param array $options
     * @return self[]
     */
    public static function registerTokens($label, $category = "data", $options = array()) {
        $tokens = array();
        foreach(\Tr8n\Config::instance()->tokenClasses($category) as $class) {
//            $token = new $class($label, null); // TODO: can this be made into a static function?
//            call_user_func_array($class, array($label, $options));
            $matches = $class::parse($label, $class::expression(), $options);
            array_push($tokens, $matches);
        }
        return ArrayUtils::flatten($tokens);
    }

    function __construct($label, $token) {
        $this->label = $label;
        $this->full_name = $token;
        $this->parseElements();
    }

    function parseElements() {
        $name_without_parens = preg_replace('/[{}\[\]]/', '', $this->full_name);
        $parts = explode('::', $name_without_parens);
        $name_without_case_keys = trim($parts[0]);

        $parts = explode(':', $name_without_parens);
        $this->short_name = trim($parts[0]);

        $keys = array();
        preg_match_all('/(::[\w]+)/', $name_without_parens, $keys);
        $keys = $keys[0];
        $this->case_keys = array();
        foreach($keys as $key) {
            array_push($this->case_keys, str_replace('::', '', $key));
        }

        $keys = array();
        preg_match_all('/(:[\w]+)/', $name_without_case_keys, $keys);
        $keys = $keys[0];
        $this->context_keys = array();
        foreach($keys as $key) {
            array_push($this->context_keys, str_replace(':', '', $key));
        }
    }

    public static abstract function expression();

    public static function parse($label, $expression, $options = array()) {
        $matches = array();
        preg_match_all($expression, $label, $matches);
        $matches = array_unique($matches[0]);
        $tokens = array();
        $class = get_called_class();
        foreach($matches as $token) {
           array_push($tokens, new $class($label, $token));
        }
        return $tokens;
    }

    public function name($opts = array()) {
        $val = $this->short_name;
        if (isset($opts["context_keys"]) and count($this->context_keys) > 0)
            $val = $val . ":" . implode(':', $this->context_keys);

        if (isset($opts["case_keys"]) and count($this->case_keys) > 0)
            $val = $val . "::" . implode('::', $this->case_keys);

        if (isset($opts["parens"]))
            $val = "{" . $val . "}";

        return $val;
    }


    /**
     * For transform tokens, we can only use the first context key, if it is not mapped in the context itself.
     *
     * {user:gender | male: , female: ... }
     *
     * It is not possible to apply multiple context rules on a single token at the same time:
     *
     * {user:gender:value | .... hah?}
     *
     * It is still possible to setup dependencies on multiple contexts.
     *
     * {user:gender:value}   - just not with transform tokens
     *
     * @param $language
     * @param array $opts
     */
    public function contextForLanguage($language, $opts = array()) {
        if (count($this->context_keys) > 0) {
            $ctx = $language->contextByKeyword($this->context_keys[0]);
        } else {
            $ctx = $language->contextByTokenName($this->short_name);
        }

        if ($ctx==null && !isset($opts["silent"])) {
            throw new \Tr8n\Tr8nException("Unknown context for token: " . $this->full_name . " in " . $language->locale);
        }

        return $ctx;
    }

    /**
    #
    # case is identified with ::
    #
    # examples:
    #
    # tr("Hello {user::nom}", "", :user => current_user)
    # tr("{actor} gave {target::dat} a present", "", :actor => user1, :target => user2)
    # tr("This is {user::pos} toy", "", :user => current_user)
    #
     */
    public function applyCase($case, $token_value, $token_values, $language, $options) {
        $case = $language->languageCase($case);
        if ($case == null) return $token_value;
        return $case->apply($token_value, self::tokenObject($token_values, $this->name()), $options);
    }


    /**
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

    /**
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
    public function tokenValue($token_values, $language, $options) {
        if (array_key_exists($this->name(), $token_values)) {
            $token_data = $token_values[$this->name()];
        } else {
            $token_data = $language->application->defaultTokens($this->name(), 'data');
        }

        if ($token_data === null) {
            throw new Tr8nException("Missing value for token: " . $this->name());
        }

        if (is_string($token_data) || is_numeric($token_data) || is_double($token_data)) {
            return $this->sanitize($token_data, $token_values, $language, $options);
        }

        if (is_array($token_data)) {
            if (\Tr8n\Utils\ArrayUtils::isHash($token_data)) {
                if (!array_key_exists('object', $token_data))
                    throw new Tr8nException("object attribute is missing in the hash for token: " . $this->full_name);

                $token_object = $token_data['object'];

                if (array_key_exists('value', $token_data)) {
                    return $this->sanitize($token_data['value'], $token_values, $language, array_merge($options, array("sanitize" => false)));
                }


                if (array_key_exists('attribute', $token_data)) {
                    $attribute = $token_data['attribute'];
                    if (is_array($token_object)) {
                        if (array_key_exists($attribute, $token_object)) {
                            return $this->sanitize($token_object[$attribute], $token_values, $language, array_merge($options, array("sanitize" => true)));
                        }
                        throw new Tr8nException("Invalid attribute properties for object in the hash of token: " . $this->full_name);
                    }
                    return $this->sanitize($token_object->$attribute, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }

                if (array_key_exists('method', $token_data)) {
                    $method = $token_data['method'];
                    if (is_array($token_object)) {
                        throw new Tr8nException("Invalid method properties for hash of token: " . $this->full_name);
                    }
                    return $this->sanitize($token_object->$method(), $token_values, $language, array_merge($options, array("sanitize" => true)));
                }

                throw new Tr8nException("value and attribute properties are missing in the hash for token: " . $this->full_name);
            }

            if (count($token_data) == 0)
                throw new Tr8nException("Invalid array value for token: " . $this->full_name);

            $token_object = $token_data[0];

//            if (is_array($token_object)) {
//            }

            if (count($token_data) == 1)
                return $this->sanitize($token_object, $token_values, $language, array_merge($options, array("sanitize" => true)));

            $token_method = $token_data[1];

            if (is_string($token_method)) {
                # method
                if (preg_match('/^@@/', $token_method)) {
                    $attribute = substr($token_method, 2);
                    $token_value = $token_object->$attribute();
                    return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }
                # attribute
                if (preg_match('/^@/', $token_method)) {
                    $attribute = substr($token_method, 1);
                    $token_value = $token_object->$attribute;
                    return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }
                return $this->sanitize($token_method, $token_values, $language, array_merge($options, array("sanitize" => false)));
            }

            if (is_callable($token_method)) {
                $token_value = $token_method($token_object);
                return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => false)));
            }

            throw new Tr8nException("Unsupported token array method for token: " . $this->full_name);
        }

        return $this->sanitize($token_data, $token_values, $language, $options);
    }


    public function sanitize($token_object, $token_values, $language, $options) {
        $token_value = "" . $token_object;

        // TODO: add HTML escaping

        if (isset($this->case_keys) && count($this->case_keys) > 0) {
            foreach($this->case_keys as $case) {
                $token_value = $this->applyCase($case, $token_value, $token_values, $language, $options);
            }
        }

        return $token_value;
    }

    /**
     * Main substitution function
     */
    public function substitute($label, $token_values, $language, $options = array()) {
        $token_value = $this->tokenValue($token_values, $language, $options);
        return str_replace($this->full_name, $token_value, $label);
    }

    function __toString() {
        return $this->full_name;
    }
}

