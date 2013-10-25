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

use Tr8n\Config;

class HtmlTranslator {

    const HTML_SPECIAL_CHAR_REGEX = '/(&[^;]*;)/';
    const INDEPENDENT_NUMBER_REGEX = '/^(\d+)$|^(\d+[,;\s])|(\s\d+)$|(\s\d+[,;\s])/';
    const VERBOSE_DATE_REGEX = '/(((Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)|(January|February|March|April|May|June|July|August|September|October|November|December))\s\d+(,\s\d+)*(,*\sat\s\d+:\d+(\sUTC))*)/';

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
        $this->parseDocument();
    }

    /**
     * Prepares HTML for processing
     */
    function prepareHtml() {
        // remove all tabs and new lines - as they mean nothing in HTML
        $this->html = trim(preg_replace('/\t\n/', '', $this->html));

        // normalize multiple spaces to one space
        $this->html = preg_replace('/\s+/', ' ', $this->html);

        // replace special characters like &nbsp;
        $this->html = $this->replaceSpecialCharacters($this->html);
    }

    /**
     * Parses the HTML document
     */
    function parseDocument() {
        $this->prepareHtml();

        $this->doc = new \DOMDocument();
        $this->doc->strictErrorChecking = false;
        @$this->doc->loadHTML($this->html);
    }

    /**
     * @param string $html
     * @return array
     */
    public function translate($html = null) {
        if ($html!=null)
            $this->html = $html;

        $this->parseDocument();
        return $this->translateTree($this->doc);
    }

    /**
     * @param $node
     * @return string
     */
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

        $values = array();
        if (isset($node->childNodes)) {
            foreach($node->childNodes as $child) {
                array_push($values, array($child, $this->translateTree($child)));
            }
        }

        return $this->apply($node, $values);
    }

    /**
     * @param $node
     * @param $values
     * @return string
     */
    private function apply($node, $values) {
        $value = "";

        $temp = "";
        foreach($values as $node_val) {
            $sibling = $node_val[0];
            $val = $node_val[1];
            if ($this->isSeparatorNode($sibling)) {
                if ($temp!="") $value = $value.$this->translateTml($temp);
                $value = $value.$this->generateHtmlToken($sibling);
                $temp = "";
            } else if ($this->isContainerNode($sibling)) {
                if ($temp!="") $value = $value.$this->translateTml($temp);
                $value = $value.$this->prepareHtmlNode($sibling, $val);
                $temp = "";
            } else {
                $temp = $temp.$val;
            }
        }
        if ($temp!="") $value = $value.($this->isInlineNode($node) ? $temp : $this->translateTml($temp) );

        if (!$this->isInlineNode($node)) {
            return $value;
        }

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
     * @param $name
     * @return mixed|null|string|\string[]
     */
    private function getOption($name) {
        if (isset($this->options[$name])) {
            return $this->options[$name];
        }
        return Config::instance()->configValue("html_translator." . $name);
    }

    private function debugTranslation($translation) {
        return str_replace('{$0}', $translation, $this->getOption("debug_format"));
    }

    /**
     * @param $tml
     * @return string
     */
    private function translateTml($tml) {
//        print_r('"'.trim($tml).'"');
        $tml = trim($tml, " \n\t");
        if (trim($tml) == "") return "";

        if ($this->getOption("split_sentences")) {
            $sentences = StringUtils::splitSentences($tml);
            $translation = $tml;
            foreach($sentences as $sentence) {
                $sentence_translation = $this->getOption("debug") ? $this->debugTranslation($sentence) : Config::instance()->current_language->translate($sentence, null, $this->tokens, $this->options);
                $translation = str_replace($sentence, $sentence_translation, $translation);
            }
            $this->tokens = array_merge(array(), $this->context);
            return $translation;
        }

        $translation =  $this->getOption("debug") ? $this->debugTranslation($tml) : Config::instance()->current_language->translate($tml, null, $this->tokens, $this->options);
        $this->tokens = array_merge(array(), $this->context);

        return $translation;
    }

    /**
     * @param $node
     * @return bool
     */
    private function isInlineNode($node) {
        return ($node->nodeType == 1 && in_array($node->tagName, $this->getOption("nodes.inline")));
    }

    /**
     * @param $node
     * @return bool
     */
    private function isContainerNode($node) {
        return ($node->nodeType == 1 && !$this->isInlineNode($node));
    }

    /**
     * @param $node
     * @return bool
     */
    private function isSelfClosingNode($node) {
        return ($node->nodeType == 1 && in_array($node->tagName, $this->getOption("nodes.self_closing")));
    }

    /**
     * @param $node
     * @return bool
     */
    private function isGeneralToken($node) {
        if ($node->nodeType != 1) return true;
        return in_array($node->tagName, $this->getOption("nodes.ignored"));
    }

    /**
     * @param $node
     * @param $value
     * @return string
     */
    private function prepareHtmlNode($node, $value) {
        if ($this->isGeneralToken($node)) return $value;
        return $this->generateHtmlToken($node, $value);
    }

    /**
     * @param $node
     * @return bool
     */
    private function isValidTextNode($node) {
        return ($node->nodeType == 3 && $this->sanitizeNodeValue($node) != "");
    }

    /**
     * @param $node
     * @return bool
     */
    private function hasChildrenThatAreTextOrInlineNodes($node) {
        if ($node == null) return false;
        if (!isset($node->childNodes)) return false;

        foreach($node->childNodes as $child) {
            if ($this->isValidTextNode($child))
                return true;
            if ($this->isInlineNode($child))
                return true;
        }
        return false;
    }

    /**
     * @param $node
     * @return bool
     */
    private function isSeparatorNode($node) {
        return ($node->nodeType == 1 && in_array($node->tagName, $this->getOption("nodes.splitters")));
    }

    /**
     * @param $node
     * @return bool
     */
    private function hasChildrenThatAreSeparators($node) {
        if (!isset($node->childNodes)) return false;

        foreach($node->childNodes as $child) {
            if ($this->isSeparatorNode($child))
                return true;
        }

        return false;
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
     * @param $text
     * @return mixed
     */
    private function replaceSpecialCharacters($text) {
        if (!$this->getOption("data_tokens.special")) return $text;

        preg_match_all(self::HTML_SPECIAL_CHAR_REGEX, $text, $matches);
        $matches = array_unique($matches[0]);

        foreach ($matches as $match) {
            $token = substr($match, 1, -1);
            $this->context[$token] = $match;
            $text = str_replace($match, "{" . $token . "}", $text);
        }
        return $text;
    }

    /**
     * @param string $text
     * @return mixed
     */
    private function generateDataTokens($text) {
        if (!$this->getOption("data_tokens.numeric")) return $text;

        preg_match_all(self::INDEPENDENT_NUMBER_REGEX, $text, $matches);
        $matches = array_unique($matches[0]);

        $token_name = $this->getOption("data_tokens.numeric_name");

        foreach ($matches as $match) {
            $value = trim($match, ',; ');
            $token = $this->contextualize($token_name, $value);
            $text = str_replace($match, str_replace($value, "{" . $token . "}", $match), $text);
        }
        return $text;
    }

    /**
     * @param $node
     * @param null $value
     * @return string
     */
    private function generateHtmlToken($node, $value = null) {
        $name = $node->tagName;
        $attributes = $node->attributes;
        $attributes_array = array();
        $value = $value == null ? '{$0}' : $value;
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
            $quote = (strpos($attributes_array[$key], "'") !== FALSE ? '"' : "'");
            array_push($attr, $key.'='.$quote.$attributes_array[$key].$quote);
        }
        $attr = implode(' ', $attr);

        if ($this->isSelfClosingNode($node))
            return '<'.$name.' '.$attr.'/>';

        return '<'.$name.' '.$attr.'>' . $value . '</'.$name.'>';
    }

    /**
     * @param $node
     * @return mixed
     */
    private function adjustName($node) {
        $name = $node->tagName;
        $map = $this->getOption("name_mapping");
        $name = isset($map[$name]) ? $map[$name] : $name;
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
     * @param string $token
     * @param string $value
     * @return bool
     */
    private function isShortToken($token, $value) {
        if (in_array($token, $this->getOption("nodes.short")))
            return true;

        if (strlen($value) < 10)
            return true;

        return false;
    }


    public function debug() {
        print_r("\n\n");
        $this->debugTree($this->doc);
        print_r("\n\n");
    }

    private function nodeInfo($node) {
        if ($node->nodeType == 1)
            return $node->tagName;

        if ($node->nodeType == 3)
            return '"'.$node->wholeText.'"';

        return $node->nodeType;
    }

    private function debugTree($node, $depth = 0) {
        $padding = str_repeat('=', $depth);
//        print($padding . ' ' . $node->tagName);

//        print_r($node);
        print_r($padding . "=> " . get_class($node) . ": " . $this->nodeInfo($node) . "\n");

        if (isset($node->childNodes)) {
            foreach($node->childNodes as $child) {
                $this->debugTree($child, $depth+1);
            }
        }
    }
}