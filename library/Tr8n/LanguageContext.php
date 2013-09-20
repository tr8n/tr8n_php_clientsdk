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

namespace Tr8n;

class LanguageContext extends Base {

    public $keyword, $description, $definition;
    public $language, $rules;

    function __construct($attributes=array()) {
        parent::__construct($attributes);

        $this->rules = array();
        if (array_key_exists('rules', $attributes)) {
            foreach($attributes['rules'] as $key => $rule) {
                $this->rules[$key] = new \Tr8n\LanguageContextRule(array_merge($rule, array("language_context" => $this)));
            }
        }
    }

    function config() {
        $context_rules = \Tr8n\Config::instance()->contextRules();
        if ($context_rules && isset($context_rules[$this->keyword]))
            return $context_rules[$this->keyword];
        return array();
    }

    function tokenMapping() {
        return $this->definition["token_mapping"];
    }

    function defaultRule() {
        return $this->definition["default_rule"];
    }

    function tokenExpression() {
        return $this->definition["token_expression"];
    }

    function variables() {
        return $this->definition["variables"];
    }

    function isAppliedToToken($token) {
        return (1==preg_match($this->tokenExpression(), $token));
    }

    /**
     * Fallback rule usually has a key of "other", but that may not be necessary in all cases.
     * @return mixed
     */
    function fallbackRule() {
        if (!isset($this->fallback_rule)) {
            foreach($this->rules as $key => $rule) {
                if ($rule->isFallback()) {
                    $this->fallback_rule = $rule;
                }
            }
        }

        return $this->fallback_rule;
    }

    function vars($obj) {
        $vars = array();
        $config = $this->config();
        foreach($this->variables() as $key) {
            if (!isset($config["variables"]) || !isset($config["variables"][$key])) {
                $vars[$key] = $obj;
                continue;
            }

            $method = $config["variables"][$key];
            if (is_string($method)) {
                if (is_object($obj)) {
                    $vars[$key] = $obj->$method;
                } else if (is_array($obj)) {
                    if (isset($obj["object"])) $obj = $obj["object"];
                    if (is_object($obj))
                        $vars[$key] = $obj->$method;
                    else
                        $vars[$key] = $obj[$method];
                } else {
                    $vars[$key] = $method;
                }
            } else if (is_callable($method)) {
                $vars[$key] = $method($obj);
            } else {
                $vars[$key] = $obj;
            }
        }

        return $vars;
    }

    function findMatchingRule($obj) {
        $token_vars = $this->vars($obj);
        foreach($this->rules as $key => $rule) {
            if ($rule->isFallback()) {
                continue;
            }
            if ($rule->evaluate($token_vars))
                return $rule;
        }

        return $this->fallbackRule();
    }
}