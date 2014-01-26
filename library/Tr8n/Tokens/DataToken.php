<?php

/**
 * Copyright (c) 2014 Michael Berkovich, http://tr8nhub.com
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

class DataToken {

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $full_name;

    /**
     * @var string
     */
    public $short_name;

    /**
     * @var string[]
     */
    public $case_keys;

    /**
     * @var string[]
     */
    public $context_keys;

    /**
     * @return string
     */
    public static function expression() {
        return '/(\{[^_:][\w]*(:[\w]+)*(::[\w]+)*\})/';
    }

    public static function tokenWithName($name) {
        $class = get_called_class();
        return new $class("", $name);
    }

    public static function tokenWithLabelAndName($label, $name) {
        $class = get_called_class();
        return new $class($label, $name);
    }

    /**
     * @param string $label
     * @param string $token
     */
    function __construct($label, $token) {
        $this->label = $label;
        $this->full_name = $token;
        $this->parse();
    }

    /**
     * Parses token name elements
     */
    function parse() {
        $name_without_parens = preg_replace('/[{}]/', '', $this->full_name);

        $parts = explode('::', $name_without_parens);
        $name_without_case_keys = trim($parts[0]);
        array_shift($parts);
        $this->case_keys = array_map('trim', $parts);

        $parts = explode(':', $name_without_case_keys);
        $this->short_name = trim($parts[0]);
        array_shift($parts);
        $this->context_keys = array_map('trim', $parts);
    }

    /**
     * @param array $opts
     * @return string
     */
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
     * @param \Tr8n\Language $language
     * @param array $opts
     * @return \Tr8n\LanguageContext|null
     * @throws \Tr8n\Tr8nException
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
     * Applies a language case. The case is identified with ::
     *
     * tr("Hello {user::nom}", "", :user => current_user)
     * tr("{actor} gave {target::dat} a present", "", :actor => user1, :target => user2)
     * tr("This is {user::pos} toy", "", :user => current_user)
     *
     * @param \Tr8n\LanguageCase $case
     * @param mixed $token_value
     * @param mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param array $options
     * @return string
     */
    public function applyCase($case, $token_value, $token_values, $language, $options) {
        $case = $language->languageCase($case);
        if ($case == null) return $token_value;
        return $case->apply($token_value, self::tokenObject($token_values, $this->name()), $options);
    }

    /**
     * Returns an object from values hash.
     *
     * @param mixed[] $token_values
     * @param string $token_name
     * @return mixed
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
            if (count($token_object) == 0)
                return null;
            return $token_object[0];
        }

        return $token_object;
    }

    /**
     * Returns a value from values hash.
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
     * @param mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param array $options
     * @return string
     * @throws \Tr8n\Tr8nException
     */
    public function tokenValue($token_values, $language, $options = array()) {
        if (array_key_exists($this->short_name, $token_values)) {
            $token_data = $token_values[$this->name()];
        } else {
            $token_data = \Tr8n\Config::instance()->defaultToken($this->short_name, 'data');
        }

        if ($token_data === null) {
            return "{".$this->short_name.": missing value}";
        }

        if (is_string($token_data) || is_numeric($token_data) || is_double($token_data)) {
            return $this->sanitize($token_data, $token_values, $language, array_merge($options, array("sanitize" => false)));
        }

        if (is_array($token_data)) {
            if (\Tr8n\Utils\ArrayUtils::isHash($token_data)) {
                if (!array_key_exists('object', $token_data))
                    return "{".$this->short_name.": object attribute is missing in the hash value}";

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
                        return "{".$this->short_name.": property ".$attribute." does not exist}";
                    }

                    if (!property_exists($token_object, $attribute)) {
                        return "{".$this->short_name.": property ".$attribute." does not exist}";
                    }

                    return $this->sanitize($token_object->$attribute, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }

                if (array_key_exists('method', $token_data)) {
                    $method = $token_data['method'];
                    if (is_array($token_object)) {
                        return "{".$this->short_name.": invalid method properties for hash value}";
                    }

                    if (!method_exists($token_object, $method)) {
                        return "{".$this->short_name.": method ".$method." does not exist}";
                    }
                    return $this->sanitize($token_object->$method(), $token_values, $language, array_merge($options, array("sanitize" => true)));
                }

                return $this->sanitize($token_object, $token_values, $language, array_merge($options, array("sanitize" => true)));
            }

            if (count($token_data) == 0)
                return "{".$this->short_name.": array value is empty}";

            $token_object = $token_data[0];

            if (count($token_data) == 1)
                return $this->sanitize($token_object, $token_values, $language, array_merge($options, array("sanitize" => true)));

            $token_method = $token_data[1];

            if (is_callable($token_method)) {
                $token_value = $token_method($token_object);
                return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => false)));
            }

            if (is_string($token_method)) {
                # method
                if (preg_match('/^@@/', $token_method)) {
                    $attribute = substr($token_method, 2);

                    if (!method_exists($token_object, $attribute)) {
                        return "{".$this->short_name.": method ".$attribute." does not exist}";
                    }

                    $token_value = $token_object->$attribute();
                    return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }
                # attribute
                if (preg_match('/^@/', $token_method)) {
                    $attribute = substr($token_method, 1);

                    if (!property_exists($token_object, $attribute)) {
                        return "{".$this->short_name.": property ".$attribute." does not exist}";
                    }

                    $token_value = $token_object->$attribute;
                    return $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => true)));
                }
                return $this->sanitize($token_method, $token_values, $language, array_merge($options, array("sanitize" => false)));
            }

            return "{".$this->short_name.": unsupported array method}";
        }

        return $this->sanitize($token_data, $token_values, $language, array_merge($options, array("sanitize" => true)));
    }

    /**
     * @param mixed $token_object
     * @param mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param mixed[] $options
     * @return string
     */
    public function sanitize($token_object, $token_values, $language, $options) {
        $token_value = "" . $token_object;

        if (isset($options["sanitize"]) && $options["sanitize"]) {
            $token_value = htmlspecialchars($token_value);
        }

        if (isset($this->case_keys) && count($this->case_keys) > 0) {
            foreach($this->case_keys as $case) {
                $token_value = $this->applyCase($case, $token_value, $token_values, $language, $options);
            }
        }

        return $token_value;
    }

    /**
     * Main substitution function
     *
     * @param string $label
     * @param mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param mixed[] $options
     * @return mixed
     */
    public function substitute($label, $token_values, $language, $options = array()) {
        $token_value = $this->tokenValue($token_values, $language, $options);
        return str_replace($this->full_name, $token_value, $label);
    }

    /**
     * @return string
     */
    function __toString() {
        return $this->full_name;
    }
}

