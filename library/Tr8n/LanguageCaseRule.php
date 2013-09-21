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

use \Tr8n\Utils\ArrayUtils;
use \Tr8n\Rules\GenderRule;

class LanguageCaseRule extends Base {

    public $language_case;
    public $definition, $position, $description, $examples;

    function conditions() {
        return ($this->definition["conditions"]);
    }

    function conditionsExpression() {
        if (!isset($this->definition["conditions_expression"])) {
            $p = new \Tr8n\RulesEngine\Parser($this->conditions());
            $this->definition["conditions_expression"] = $p->parse();
        }
        return $this->definition["conditions_expression"];
    }

    function operations() {
        return ($this->definition["operations"]);
    }

    function operationsExpression() {
        if (!isset($this->definition["operations_expression"])) {
            $p = new \Tr8n\RulesEngine\Parser($this->conditions());
            $this->definition["operations_expression"] = $p->parse();
        }
        return $this->definition["operations_expression"];
    }

    /**
     * Some language cases may depend on the object gender.
     * This is the only context that is injected/hard coded into the language cases.
     *
     * The language must support Gender Context.
     *
     * @param $object
     */
    function genderVariables($object) {
        if (strstr($this->conditions(), "@gender") == false)
            return array();

        if ($object == null)
            return array("@gender" => "unknown");

        // TODO: is there a better way to do this?
        $context = $this->language_case->language->context("gender");

        if ($context == null)
            return array("@gender" => "unknown");

        return $context->vars($object);
    }

    public function evaluate($value, $object = null) {
        if ($this->conditions() == null)
            return false;

        $re = new \Tr8n\RulesEngine\Evaluator();
        $re->evaluate(array("let", "@value", $value));

        $vars = $this->genderVariables($object);
        foreach($vars as $key=>$val) {
            $re->evaluate(array("let", $key, $val));
        }

        return $re->evaluate($this->conditionsExpression());
    }

    public function apply($value) {
        if ($this->operations() == null)
            return $value;

        $re = new \Tr8n\RulesEngine\Evaluator();
        $re->evaluate(array("let", "@value", $value));

        return $re->evaluate($this->operationsExpression());
    }

}