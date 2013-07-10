<?php


$s = "You have {user} messages from {count}.";
$pattern = '/(\{[^_:][\w]*(:[\w]+)?(::[\w]+)?\})/';
$tokens = array();

preg_match_all($pattern, $s, $matches);
print_r($matches[0]);


