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
#######################################################################
#
# Method Token Forms
#
# {user.name}
# {user.name:gender}
#
#######################################################################

namespace Tr8n\Tokens;

use Tr8n\Tr8nException;

class MethodToken extends DataToken {

    /**
     * @var string
     */
    protected $object_name;

    /**
     * @var string
     */
    protected $object_method;

    /**
     * @return string
     */
    public static function expression() {
        return '/(\{[^_:.][\w]*(\.[\w]+)(:[\w]+)*(::[\w]+)*\})/';
    }

//    public function parse() {
//
//    }

    /**
     * @return string
     */
    public function objectName() {
        if ($this->object_name == null) {
            $parts = explode('.', $this->name());
            $this->object_name = $parts[0];
        }
        return $this->object_name;
    }

    /**
     * @return string
     */
    public function objectMethod() {
        if ($this->object_method == null) {
            $parts = explode('.', $this->name());
            $this->object_method = $parts[1];
        }
        return $this->object_method;
    }

    /**
     * @param string $label
     * @param \mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param array $options
     * @return mixed
     * @throws Tr8nException
     */
    public function substitute($label, $token_values, $language, $options = array()) {
        $name = $this->objectName();
        $object = self::tokenObject($token_values, $name);
        if ($object == null) {
            throw new Tr8nException("Missing value for token: " . $this->full_name);
        }
        $method = $this->objectMethod();

        if (method_exists($object, $method))
            $token_value = $object->$method();
        else
            $token_value = $object->$method;

        $token_value = $this->sanitize($token_value, $token_values, $language, array_merge($options, array("sanitize" => true)));
        return str_replace($this->full_name, $token_value, $label);
    }

}
