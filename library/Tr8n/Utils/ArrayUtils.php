<?php

namespace Tr8n\Utils;

class ArrayUtils {

    public static function flatten(array $array) {
        $return = array();
        array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
        return $return;
    }

    public static function split($value, $delimiter = ',') {
        if (!$value) return null;
        return array_map('trim', explode($delimiter, $value));
    }

    public static function isHash($arr) {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}