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

class LanguageCaseRule extends Base {

    /**
     * @var integer
     */
    public $id;
    /**
     * @var LanguageCase
     */
    public $language_case;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $examples;

    /**
     * @var string
     */
    public $conditions;

    /*
     * string[]
     */
    public $conditions_expression;

    /**
     * @var string
     */
    public $operations;

    /*
     * string[]
     */
    public $operations_expression;


    function conditionsExpression() {
        if (!isset($this->conditions_expression)) {
            $p = new RulesEngine\Parser($this->conditions);
            $this->conditions_expression = $p->parse();
        }
        return $this->conditions_expression;
    }

    function operationsExpression() {
        if (!isset($this->operations_expression)) {
            $p = new RulesEngine\Parser($this->operations);
            $this->operations_expression = $p->parse();
        }
        return $this->operations_expression;
    }

    /**
     * Some language cases may depend on the object gender.
     * This is the only context that is injected/hard coded into the language cases.
     *
     * The language must support Gender Context.
     *
     * @param $object
     * @return array
     */
    function genderVariables($object) {
        if (strstr($this->conditions, "@gender") == false)
            return array();

        if ($object == null)
            return array("@gender" => "unknown");

        $context = $this->language_case->language->contextByKeyword("gender");

        if ($context == null)
            return array("@gender" => "unknown");

        return $context->vars($object);
    }

    /**
     * @param mixed $value
     * @param null $object
     * @return bool|mixed
     */
    public function evaluate($value, $object = null) {
        if ($this->conditions == null)
            return false;

        $re = new RulesEngine\Evaluator();
        $re->evaluate(array("let", "@value", $value));

        $vars = $this->genderVariables($object);
        foreach($vars as $key=>$val) {
            $re->evaluate(array("let", $key, $val));
        }

        return $re->evaluate($this->conditionsExpression());
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function apply($value) {
        if ($this->operations == null)
            return $value;

        $re = new RulesEngine\Evaluator();
        $re->evaluate(array("let", "@value", $value));

        return $re->evaluate($this->operationsExpression());
    }

    /**
     * @param array $keys
     * @return array
     */
    public function toArray($keys=array()) {
        return parent::toArray(array("id", "description", "examples", "conditions", "conditions_expression", "operations", "operations_expression"));
    }

}