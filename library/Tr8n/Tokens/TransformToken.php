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
# Transform Token Form
#
# {count:number || one: message, many: messages}
# {count:number || one: сообщение, few: сообщения, many: сообщений, other: много сообщений}   in other case the number is not displayed#
#
# {count | message}   - will not include {count}, resulting in "messages" with implied {count}
# {count | message, messages}
#
# {count:number | message, messages}
#
# {user:gender | he, she, he/she}
#
# {user:gender | male: he, female: she, other: he/she}
#
# {now:date | did, does, will do}
# {users:list | all male, all female, mixed genders}
#
# {count || message, messages}  - will include count:  "5 messages"
#
#######################################################################

namespace Tr8n\Tokens;

class TransformToken extends Base {

    protected $pipe_separator, $piped_parameters;

    public function expression() {
        return '/(\{[^_:|][\w]*(:[\w]+)*(::[\w]+)*\s*\|\|?[^{^}]+\})/';
    }

    public function name() {
        if ($this->name === null) {
            $parts = explode('|', $this->declaredName());
            $parts = explode(':', $parts[0]);
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

    public function pipeSeparator() {
        if ($this->pipe_separator == null) {
            $this->pipe_separator = (strpos($this->fullName(),'||') !== false) ? '||' : '|';
        }
        return $this->pipe_separator;
    }

    public function pipedParameters() {
        if ($this->piped_parameters == null) {
            $parts = explode($this->pipeSeparator(), $this->declaredName());
            $parts = explode(',', $parts[1]);
            $this->piped_parameters = array();
            foreach($parts as $part) {
                array_push($this->piped_parameters, trim($part));
            }
        }
        return $this->piped_parameters;
    }

    public function isAllowedInTranslation() {
        return ($this->pipeSeparator() == '||');
    }

    public function substitute($label, $token_values, $language, $options = array()) {
        if (!array_key_exists($this->name(), $token_values)) {
            throw new Tr8nException("Missing value for token: " . $this->name());
        }

        $token_data = $token_values[$this->name()];

        if (count($this->transformableLanguageRuleClasses()) == 0) {
            throw new Tr8nException("The token " . $this->fullName() . " in " . $this->label . " is not associated with any rule types; no way to apply the transform method.");
        }

        if (count($this->transformableLanguageRuleClasses()) > 1) {
            throw new Tr8nException("The token " . $this->fullName() . " in " . $this->label . " is associated with multiple rule types; no way to apply the transform method.");
        }

        $language_rule = $this->transformableLanguageRuleClasses();
        $language_rule = $language_rule[0];
        $language_rule = new $language_rule();

        $token_value = array();
        if ($this->isAllowedInTranslation()) {
            array_push($token_value, $this->tokenValue($token_values, $language, $options));
            array_push($token_value, " ");
        }

        $transform_value = $language_rule->transform($this, self::tokenObject($token_values, $this->name()), $this->pipedParameters(), $language);
        array_push($token_value, $transform_value);

        return str_replace($this->fullName(), implode("", $token_value), $label);
    }

}
