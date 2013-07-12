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

#######################################################################
#
# Method Token Forms
#
# {user.name}
# {user.name:gender}
#
#######################################################################

namespace Tr8n\Tokens;

use tr8n\Tr8nException;

class MethodToken extends Base {

    protected $object_name, $object_method;

    public function expression() {
        return '/(\{[^_:.][\w]*(\.[\w]+)(:[\w]+)*(::[\w]+)*\})/';
    }

    public function objectName() {
        if ($this->object_name == null) {
            $parts = explode('.', $this->name());
            $this->object_name = $parts[0];
        }
        return $this->object_name;
    }

    public function objectMethod() {
        if ($this->object_method == null) {
            $parts = explode('.', $this->name());
            $this->object_method = $parts[1];
        }
        return $this->object_method;
    }

    public function substitute($label, $token_values, $language, $options = array()) {
        $name = $this->objectName();
        $object = self::tokenObject($token_values, $name);
        if ($object == null) {
            throw new Tr8nException("Missing value for token: " . $this->fullName());
        }
        $method = $this->objectMethod();

        if (method_exists($object, $method))
            $token_value = $object->$method();
        else
            $token_value = $object->$method;

        $token_value = $this->sanitize($token_value, $language, array_merge($options, array("sanitize" => true)));
        return str_replace($this->fullName(), $token_value, $label);
    }

}
