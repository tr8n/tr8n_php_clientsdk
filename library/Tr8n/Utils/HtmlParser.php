<?php

#--
# Copyright (c) 2013 Michael Berkovich, tr8nhub.com
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

namespace Tr8n\Utils;

class HtmlParser {

    public $text, $tokens, $env, $data, $decorations;

    function __construct($text) {
        $this->text = '<tr8n>' . $text . '</tr8n>';
        $this->decorations = array();
        $this->data = array();
        $this->env = array(
            "tr8n"     => function($p, $attributes, $value) { return $value; },
            "span"     => function($p, $attributes, $value) {
                $token = $p->decorationTokenName('span', $attributes);
                $p->decorations[$token] = $attributes;
                return '[' . $token . ': ' . $value . ']';
            },
            "strong"   => function($p, $attributes, $value) {
                return '[strong: ' . $value . ']';
            },
            "bold"     => function($p, $attributes, $value) {
                return '[bold: ' . $value . ']';
            },
            "a"        => function($p, $attributes, $value) {
                $token = $p->decorationTokenName('link', $attributes);
                $p->decorations[$token] = $attributes;
                return '[' . $token . ': ' . $value . ']';
            },
        );
    }

    function decorationTokenName($name, $attributes) {
        if (isset($this->decorations[$name])) {
            if ($this->decorations[$name] != $attributes) {
                $index = 0;
                if (preg_match_all("/.*?(\d+)$/", $name, $matches)>0) {
                    $index = $matches[count($matches)-1][0];
                    $name = str_replace($index, '', $name);
                }
                $name = $name . ($index + 1);
                return $this->decorationTokenName($name, $attributes);
            }
        }

        return $name;
    }

    function dataTokenName($name) {
        if (isset($this->data[$name])) {
            $index = 0;
            if (preg_match_all("/.*?(\d+)$/", $name, $matches)>0) {
                $index = $matches[count($matches)-1][0];
                $name = str_replace($index, '', $name);
            }
            $name = $name . ($index + 1);
            return $this->dataTokenName($name);
        }

        return $name;
    }

    function tokenize() {
        $this->tokens = array();
        preg_match_all('/<\/?[^>]*>|[^<]*/', $this->text, $matches);
        $this->tokens = $matches[0];
        return $this->tokens;
    }

    function peek() {
        return $this->tokens[0];
    }

    function nextToken() {
        if (count($this->tokens) == 0) return null;
        return array_shift($this->tokens);
    }

    function parse() {
        $token = $this->nextToken();

        if (preg_match('/</', $token)) {
            $token = trim($token, '<>');
            $attrs = array();
            $parts = explode(' ', $token);
            $token = $parts[0];
            $parts = array_slice($parts, 1);
            foreach($parts as $part) {
                $name_value = explode('=', $part);
                if (count($name_value) == 1)
                    array_push($name_value, true);
                $attrs[$name_value[0]] = $name_value[1];
            }
            return $this->parseDom($token, $attrs);
        }

        if (preg_match('/\d+/', $token)) {
            $name = $this->dataTokenName('count');
            $this->data[$name] = intval($token);
            return $name;
        }

        return $token;
    }

    function parseDom($name, $attributes) {
        $list = array($name, $attributes);
        while (!preg_match('/<\//', $this->peek())) {
            array_push($list, $this->parse());
        }
        $this->nextToken();
        return $list;
    }

    function apply($token, $attributes, $value) {
        if (!isset($this->env[$token])) {
            throw (new \Tr8n\Tr8nException("Unsupported HTML token: " . $token));
        }

        return $this->env[$token]($this, $attributes, $value);
    }

    function evaluate($expr) {
        if (!is_array($expr)) {
            return $expr;
        }

        $token = $expr[0];
        $attributes = $expr[1];
        $values = array_slice($expr, 2);
        $values = implode('', array_map(array(&$this, "evaluate"), $values));

        return $this->apply($token, $attributes, $values);
    }

    function process() {
        $this->tokenize();
        $expr = $this->parse();
        return $this->evaluate($expr);
    }

    static function translate($text) {
        $p = new \Tr8n\Utils\HtmlParser($text);
        return $p->process();
    }
}

