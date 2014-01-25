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

use Tr8n\Tr8nException;

class TransformToken extends DataToken {

    /**
     * @var string
     */
    protected $pipe_separator;

    /**
     * @var string[]
     */
    protected $piped_parameters;

    /**
     * @return string
     */
    public static function  expression() {
        return '/(\{[^_:|][\w]*(:[\w]+)*(::[\w]+)*\s*\|\|?[^{^}]+\})/';
    }

    /**
     * Parses token elements
     */
    public function parse() {
        $name_without_parens = preg_replace('/[{}\[\]]/', '', $this->full_name);

        $parts = explode('|', $name_without_parens);
        $name_without_pipes = trim($parts[0]);

        $parts = explode('::', $name_without_pipes);
        $name_without_case_keys = trim($parts[0]);

        $parts = explode(':', $name_without_pipes);
        $this->short_name = trim($parts[0]);

        $keys = array();
        preg_match_all('/(::[\w]+)/', $name_without_pipes, $keys);
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

        $this->pipe_separator = (strpos($this->full_name,'||') !== false) ? '||' : '|';

        $this->piped_parameters = array();

        $parts = explode($this->pipe_separator, $name_without_parens);
        if (count($parts) > 1) {
            $parts = explode(',', $parts[1]);
            foreach($parts as $part) {
                array_push($this->piped_parameters, trim($part));
            }
        }
    }

    /**
     * @return bool
     */
    public function isAllowedInTranslation() {
        return ($this->pipe_separator == '||');
    }

    /**
     * token:      {count|| one: message, many: messages}
     * results in: {"one": "message", "many": "messages"}
     *
     * token:      {count|| message}
     * transform:  [{"one": "{$0}", "other": "{$0::plural}"}, {"one": "{$0}", "other": "{$1}"}]
     * results in: {"one": "message", "other": "messages"}
     *
     * token:      {count|| message, messages}
     * transform:  [{"one": "{$0}", "other": "{$0::plural}"}, {"one": "{$0}", "other": "{$1}"}]
     * results in: {"one": "message", "other": "messages"}
     *
     * token:      {user| Dorogoi, Dorogaya}
     * transform:  ["unsupported", {"male": "{$0}", "female": "{$1}", "other": "{$0}/{$1}"}]
     * results in: {"male": "Dorogoi", "female": "Dorogaya", "other": "Dorogoi/Dorogaya"}
     *
     * token:      {actors:|| likes, like}
     * transform:  ["unsupported", {"one": "{$0}", "other": "{$1}"}]
     * results in: {"one": "likes", "other": "like"}
     *
     *
     * @param string[] $params
     * @param \Tr8n\LanguageContext $context
     * @return array
     * @throws Tr8nException
     */
    function generateValueMap($params, $context) {
        $values = array();

        if (strstr($params[0], ':')) {
           foreach($params as $param) {
               $name_value = explode(':', $param);
               $values[$name_value[0]] = $name_value[1];
           }
            return $values;
        }

        $token_mapping = $context->token_mapping;

        if ($token_mapping == null) {
            throw new Tr8nException("The token context ". $context->keyword . " does not support transformation for unnamed params: " . $this->full_name);
        }

        // "unsupported"
        if (is_string($token_mapping)) {
            throw new Tr8nException("The token mapping $token_mapping does not support " . count($params) . " params: " . $this->full_name);
        }

        // ["unsupported", {}]
        if (is_array($token_mapping) && !\Tr8n\Utils\ArrayUtils::isHash($token_mapping)) {
            if (count($params) > count($token_mapping)) {
                throw new Tr8nException("The token mapping $token_mapping does not support " . count($params) . " params: " . $this->full_name);
            }

            $token_mapping = $token_mapping[count($params)-1];
            if (is_string($token_mapping)) {
                throw new Tr8nException("The token mapping $token_mapping does not support " . count($params) . " params: " . $this->full_name);
            }
        }

        // {}
        foreach($token_mapping as $key => $value) {
            $values[$key] = $value;

            // token form {$0::plural} - number followed by language cases
            $keys = array();
            preg_match_all('/\{\$\d(::[\w]+)*\}/', $value, $keys);
            $keys = $keys[0];

            foreach($keys as $tkey) {
                $token = $tkey;
                $token_without_parens = preg_replace('/[{}]/', '', $token);
                $parts = explode('::', $token_without_parens);
                $index = preg_replace('/[$]/', '', $parts[0]);

                if (count($params) < $index) {
                    throw new Tr8nException("The index inside " . $token_mapping . " is out of bound: " . $this->full_name);
                }

                $val = $params[$index];

                // TODO: check if language cases are enabled
                foreach(array_slice($parts, 1) as $case_key) {
                    $lcase = $context->language->languageCase($case_key);
                    if ($lcase == null) {
                        throw new Tr8nException("Language case " . $case_key . " for context " . $context->keyword . "  mapping " . $key . " is not defined: " . $this->full_name);
                    }

                    $val = $lcase->apply($val);
                }

                $values[$key] = str_replace($tkey, $val, $values[$key]);
            }
        }

        return $values;
    }

    /**
     * @param string $label
     * @param \mixed[] $token_values
     * @param \Tr8n\Language $language
     * @param array $options
     * @return mixed|string
     * @throws Tr8nException
     */
    public function substitute($label, $token_values, $language, $options = array()) {
        if (!array_key_exists($this->name(), $token_values)) {
            throw new Tr8nException("Missing value for token: " . $this->full_name);
        }

        $object = $token_values[$this->name()];

        if (count($this->piped_parameters) == 0) {
            throw new Tr8nException("Piped params may not be empty for token: " . $this->full_name);
        }

        $context = $this->contextForLanguage($language);

        $piped_values = $this->generateValueMap($this->piped_parameters, $context);

        $rule = $context->findMatchingRule($object);
        if ($rule == null) return $label;

        if (isset($piped_values[$rule->keyword])) {
            $value = $piped_values[$rule->keyword];
        } else {
            $fallback_rule = $context->fallbackRule();
            if ($fallback_rule && isset($piped_values[$fallback_rule->keyword])) {
                $value = $piped_values[$fallback_rule->keyword];
            } else {
                return $label;
            }
        }

        $token_value = array();
        if ($this->isAllowedInTranslation()) {
            array_push($token_value, $this->tokenValue($token_values, $language, $options));
            array_push($token_value, " ");
        }

        array_push($token_value, $value);

        return str_replace($this->full_name, implode("", $token_value), $label);
    }

}
