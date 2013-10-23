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

namespace Tr8n\Utils;

class HtmlTranslator {

    /**
     * @var string
     */
    public $text;

    /**
     * Original context
     * @var mixed[]
     */
    public $context;

    /**
     * Dynamic tokens built at parse time
     * @var mixed[]
     */
    public $tokens;

    /**
     * @var mixed[]
     */
    public $options;

    /**
     * @var \DOMDocument
     */
    public $doc;

    /**
     * @param string $html
     * @param array $context
     * @param array $options
     */
    function __construct($html="", $context = array(), $options = array()) {
        $this->html = $html;
        $this->context = $context;
        $this->tokens = array_merge(array(), $this->context);
        $this->options = $options;
        $this->tml = null;
    }

    /**
     * @param string $html
     * @return array
     */
    public function translate($html = null) {
        if ($html!=null) $this->html = $html;

        $this->doc = new \DOMDocument();
        $this->doc->loadHTML($this->html);

        // remove all tabs and new lines - as they mean nothing in HTML
        $this->html = trim(preg_replace('/\t\n/', '', $this->html));

        // normalize multiple spaces to one space
        $this->html = preg_replace('/\s+/', ' ', $this->html);

        return $this->translateTree($this->doc);
    }

    private function sanitizeNodeValue($node) {
        $value = $node->wholeText;
        $value = str_replace("\n", "", $value);
        $value = trim($value);
        return $value;
    }

    /**
     * @param $node
     * @return mixed|string
     */
    private function translateTree($node) {
        if ($node->nodeType == 3) {
            return $this->generateDataTokens($node->wholeText);
        }

//        $tml = "";
//        $temp = "";
//        foreach($node->childNodes as $child) {
//            if ($child->nodeType == 3) {
//                if ($this->sanitizeNodeValue($child) != "")
//                    $temp = $temp.$child->wholeText;
//            } else if ($child->nodeType == 1) {
//                if ($this->isInlineNode($child)) {
//                    $temp = $this->apply($child, $tml);
//                } else if ($this->isSeparatorNode($child)) {
//                    $tml = $tml.$this->translateTml($temp);
//                    $tml = $tml.$this->generateHtmlToken($child);
//                    $temp = "";
//                } else {
//                    $tml = $tml.$this->translateTml($temp);
//                    $tml = $tml.$this->translateTree($child);
//                    $temp = "";
//                }
//            }
//        }
//
//        if ($temp!="") $tml = $tml.$this->translateTml($temp);
//        return $tml;

        $has_text_nodes = false;
        $has_separator_nodes = false;
        $values = array();
        if (isset($node->childNodes)) {
            foreach($node->childNodes as $child) {
                $add_as_node = false;
                if ($child->nodeType == 3) {
                    if ($has_text_nodes==false && $this->sanitizeNodeValue($child) != "")
                        $has_text_nodes=true;
                } else if ($child->nodeType == 1) {
                    if ($this->isInlineNode($child))
                        $has_text_nodes=true;
                    else if ($this->isSeparatorNode($child)) {
                        $has_separator_nodes = true;
                        $add_as_node = true;
                    }
                }
//                array_push($values, array($child, $this->translateTree($child)));
                array_push($values, $add_as_node ? $child : $this->translateTree($child));
            }
        }

//        $value = "";
//        $temp = "";
//        foreach($values as $node_val) {
//            $node = $node_val[0];
//            $val = $node_val[1];
//            if ($node->nodeType==3) {
//                $temp = $temp.$val;
//            } else if ($this->isInlineNode($node)) {
//                $temp = $temp.$this->apply($node, $val);
//            } else if ($this->isSeparatorNode($node)) {
//                if ($temp!="") $value = $value.$this->translateTml($temp);
//                $value = $value.$this->generateHtmlToken($node);
//                $temp = "";
//            } else {
//                if ($temp!="") $value = $value.$this->translateTml($temp);
//                $value = $value.$this->apply($node, $val);
//                $temp = "";
//            }
//        }
//        if ($temp!="") $value = $value.$this->translateTml($temp);
//
//        return $value;

        if ($has_separator_nodes) {
            $value = "";
            $temp = "";
            foreach($values as $val) {
                if (is_string($val)) {
                    $temp = $temp.$val;
                } else {
                    if ($temp!="") $value = $value.$this->translateTml($temp);
                    $value = $value.$this->generateHtmlToken($val);
                    $temp = "";
                }
            }
            if ($temp!="") $value = $value.$this->translateTml($temp);
        } else {
            $value = implode('', $values);
            if ($has_text_nodes && $this->isContainerNode($node)) {
                $value = $this->translateTml($value);
            }
        }
        return $this->apply($node, $value);
    }

    private function translateTml($tml) {
        if (trim($tml) == "") return $tml;
        if (isset($this->options["debug"]) && $this->options["debug"])
            $translation =  "{{ ".$tml." }}";
        else
            $translation = \Tr8n\Config::instance()->current_language->translate($tml, null, $this->tokens, $this->options);

        $this->tokens = array_merge(array(), $this->context);

        return $translation;
    }

    private function isInlineNode($node) {
        if ($node->nodeType != 1) return false;
        // TODO: move to options
        return in_array($node->tagName, array("a", "span", "i", "b", "img"));
    }

    private function isContainerNode($node) {
        return !$this->isInlineNode($node);
    }

    private function isSeparatorNode($node) {
        return ($node->nodeType == 1 && in_array($node->tagName, array("br", "hr")));
    }

    private function isSelfClosingNode($node) {
        return ($node->nodeType == 1 && in_array($node->tagName, array("br", "hr", "img")));
    }

    private function isGeneralToken($node) {
        if ($node->nodeType != 1) return true;
        return in_array($node->tagName, array("html", "body"));
    }

    private function prepareHtmlNode($node, $value) {
        if ($this->isGeneralToken($node)) return $value;
        return $this->generateHtmlToken($node, $value);
    }

    /**
     * @param $node
     * @param $value
     * @return string
     */
    private function apply($node, $value) {
        if ($node->nodeType!=1) return $value;

        if ($this->isContainerNode($node))
            return $this->prepareHtmlNode($node, $value);

        $token_context = $this->generateHtmlToken($node);
        $token = $this->adjustName($node);
        $token = $this->contextualize($token, $token_context);

        $value = $this->sanitizeValue($value);

        if ($this->isSelfClosingNode($node))
            return '{'.$token.'}';

        if ($this->isShortToken($token, $value))
            return '['.$token.': '.$value.']';

        return '['.$token.']'.$value.'[/'.$token.']';
    }

    /**
     * @param string $value
     * @return string
     */
    private function sanitizeValue($value) {
        $value = ltrim($value);
        return $value;
    }

    /**
     * @param string $text
     * @return mixed
     */
    private function generateDataTokens($text) {
        if (isset($this->options["data_tokens"]) && $this->options["data_tokens"]) {
            preg_match_all('/(\d+)/', $text, $matches);
            $matches = array_unique($matches[0]);

            $token_name = (isset($this->options["token_name"]) ? $this->options["token_name"] : 'num');

            foreach ($matches as $match) {
                $token = $this->contextualize($token_name, $match);
                $text = str_replace($match, "{" . $token . "}", $text);
            }
        }
        return $text;
    }

    /**
     * @param string $name
     * @param $attributes
     * @return string
     */
    private function generateHtmlToken($node, $value = null) {
        $name = $node->tagName;
        $attributes = $node->attributes;
        $attributes_array = array();
        $value = $value ? $value : '{$0}';
        foreach($attributes as $attr) {
            $attributes_array[$attr->name] = $attr->value;
        }
        if (count($attributes_array) == 0) {
            if ($this->isSelfClosingNode($node))
                return '<'.$name.'/>';
            return '<'.$name.'>' . $value . '</'.$name.'>';
        }

        $keys = array_keys($attributes_array);
        arsort($keys);

        $attr = array();
        foreach($keys as $key) {
            $value = $attributes_array[$key];
            $quote = "'";
            if (strpos($value, $quote) !== FALSE) $quote = '"';
            array_push($attr, $key.'='.$quote.$value.$quote);
        }
        $attr = implode(' ', $attr);

        if ($this->isSelfClosingNode($node))
            return '<'.$name.' '.$attr.'/>';
        return '<'.$name.' '.$attr.'>' . $value . '</'.$name.'>';
    }

    /**
     * @param string $name
     * @return string
     */
    private function adjustName($node) {
        $name = $node->tagName;

        $map = array(
            'b' => 'bold',
            'i' => 'italic',
            'a' => 'link',
            'img' => 'picture'
        );

        $name = isset($map[$name]) ? $map[$name] : $name;

        // TODO: adjust pictures to pic_id

        return $name;
    }

    /**
     * @param string $name
     * @param string $context
     * @return string
     */
    private function contextualize($name, $context) {
        if (isset($this->tokens[$name])) {
            if ($this->tokens[$name] != $context) {
                $index = 0;
                if (preg_match_all("/.*?(\d+)$/", $name, $matches)>0) {
                    $index = $matches[count($matches)-1][0];
                    $name = str_replace($index, '', $name);
                }
                $name = $name . ($index + 1);
                return $this->contextualize($name, $context);
            }
        }

        $this->tokens[$name] = $context;
        return $name;
    }

    /**
     * @param $node
     * @return bool
     */
    private function needsLineBreak($node) {
        if (!isset($node->tagName)) return false;
        return (in_array($node->tagName, array('p', 'h1', 'h2', 'h3', 'h4', 'h5', 'div')));

    }

    /**
     * @param string $token
     * @param string $value
     * @return bool
     */
    private function isShortToken($token, $value) {
        if (in_array($token, array('b', 'i'))) return true;
        if (strlen($value) < 10) return true;
        return false;
    }

    /**
     * @param string $token
     * @return bool
     */
    private function isTokenAllowed($token) {
        if (in_array($token, array('html', 'body'))) return false;
        return true;
    }

    public function debug() {
        print_r("\n\n");
        $this->printTree($this->doc);
        print_r("\n\n");
    }

    private function nodeInfo($node) {
        if ($node->nodeType == 1)
            return $node->tagName;

        if ($node->nodeType == 3)
            return $node->wholeText;

        return $node->nodeType;
    }

    private function printTree($node, $depth = 0) {
        $padding = str_repeat(' ', $depth);
//        print($padding . ' ' . $node->tagName);

//        print_r($node);
        print_r($padding . " => " . get_class($node) . ": " . $this->nodeInfo($node) . "\n");

        if (isset($node->childNodes)) {
            foreach($node->childNodes as $child) {
                $this->printTree($child, $depth+1);
            }
        }
    }
}