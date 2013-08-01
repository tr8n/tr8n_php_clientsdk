<?php

class Token {
    private $full_name, $name, $decorated_value;
    function __construct($ch="") {
        $this->full_name = "" . $ch;
    }
    function append($ch) {
        $this->full_name = "" . $this->full_name . $ch;
    }
    function fullName() {
        return $this->full_name;
    }
    function name() {
        if (!$this->name) {
            $this->name = substr($this->full_name, 1, -1);
            $parts = explode(':', $this->name);
            $this->name = array_shift($parts);
        }
        return $this->name;
    }
    function value() {
        if (!$this->decorated_value) {
            $this->decorated_value = substr($this->full_name, 1, -1);
            $parts = explode(':', $this->decorated_value);
            array_shift($parts);
            $this->decorated_value = trim(implode(':', $parts));
        }
        return $this->decorated_value;

    }
    function isToken() {
        return preg_match('/^\[\w+:/', $this->fullName());
    }
    function isSimple() {
        return preg_match('/(\[\w+:[^\]]+\])/', $this->value());
    }
    function isNested() {
        return !$this->isSimple();
    }
}

function parseTokens($string, $include_nested = true) {
    $length = strlen($string);
    $tokens = array();
    $candidates = array();

    for ($position=0; $position <= $length; $position++) {
        $ch = $string[$position];
        foreach($tokens as $token) {
            $token->append($ch);
        }
        switch ($string[$position]) {
            case '[':
                array_push($tokens, new Token("["));
                break;

            case ']':
                if (count($tokens) > 0) {
                    array_push($candidates,  array_pop($tokens));
                }
                break;

            default:

                break;

        }
    }

    $completed_tokens = array();
    foreach($candidates as $candidate) {
        if (!$candidate->isToken()) continue;
        if (!$include_nested && !$candidate->isNested()) continue;
        array_push($completed_tokens, $candidate);
    }
    return $completed_tokens;
}

function substitute($label, $values) {
    $tokens = parseTokens($label, false);
    while (count($tokens) > 0) {
        foreach($tokens as $token) {
            $value = $values[$token->name()]($token->value());
            echo "Replacing " . $value . "<br>";
            $label = str_replace($token->fullName(), $value, $label);
        }
        $tokens = parseTokens($label, false);
    }
    return $label;
}

$string = "Hello [link: [bold: world [italic: today]]]";
$tokens = parseTokens($string);

echo $string . "<br><br>";

foreach($tokens as $token) {
    echo($token->name() . " :: " . $token->fullName() . " :: " . $token->value() . "<br>");
}

$values = array("link" => function($text) {
   return "<a href='http://google.com'>$text</a>";
}, "bold" => function($text) {
    return "<strong>$text</strong>";
}, "italic" => function($text) {
    return "<i>$text</i>";
});

$substitution = substitute($string, $values);

echo("<br>");
echo  htmlentities ($substitution, ENT_QUOTES, 'UTF-8') . "<br><br>";

echo $substitution;



//tr("This story is about {user:gender::pre::about}");