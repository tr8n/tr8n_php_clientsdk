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
        if ($this->sanitized_name === null) {
            $this->sanitized_name = "{" . $this->name() . "}";
        }
        return $this->sanitized_name;
    }

    public function pipelessName() {
        if ($this->pipeless_name === null) {
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

}

