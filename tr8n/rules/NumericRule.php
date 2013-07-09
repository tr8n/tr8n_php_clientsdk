<?php

namespace tr8n\rules;

class NumericRule extends Base {
    public $multipart, $part1, $value1, $part2, $value2, $operator;

    public static function key() {
        return "number";
    }

    # Simplistic implementation of pluralization
    public static function pluralize($quantity, $singular, $plural=null) {
        if($quantity==1 || empty($singular)) return $singular;
        if($plural!==null) return $plural;

        $last_letter = strtolower($singular[strlen($singular)-1]);
        switch($last_letter) {
            case 'y':
                return substr($singular,0,-1).'ies';
            case 's':
                return $singular.'es';
            default:
                return $singular.'s';
        }
    }

    # FORM: [singular(, plural)]
    # {count | message}
    # {count | person, people}
    # {count | one: person, other: people}
    # "У вас есть {count || one: сообщение, few: сообщения, many: сообщений}"
    public static function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 1:
                $options["one"] = $params[0];
                $options["many"] = self::pluralize(2, $params[0]);
                break;
            case 2:
                $options["one"] = $params[0];
                $options["many"] = $params[1];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }

    private function isMultipart() {
        return ($this->multipart == 'true');
    }

    private function evaluateRuleFragment($token_value, $name, $values) {
        if ($name == "is") {
            return (in_array($token_value, $values));
        }

        if ($name == "is_not") {
            return (!in_array($token_value, $values));
        }

        if ($name == "ends_in") {
            foreach($values as $value) {
                if (preg_match('/'.$value.'$/', $token_value)) return true;
            }
            return false;
        }

        if ($name == "does_not_end_in") {
            foreach($values as $value) {
                if (preg_match('/'.$value.'$/', $token_value)) return false;
            }
            return true;
        }

        if ($name == "starts_with") {
            foreach($values as $value) {
                if (preg_match('/^'.$value.'/', $token_value)) return true;
            }
            return false;
        }

        if ($name == "does_not_start_with") {
            foreach($values as $value) {
                if (preg_match('/^'.$value.'/', $token_value)) return false;
            }
            return true;
        }

        return false;
    }

    public function evaluate($token) {
        $value = $this->tokenValue($token);
        if (!$value) return false;

        $result1 = $this->evaluateRuleFragment($value, $this->part1, $this->sanitizeValues($this->value1));
        if (!$this->isMultipart()) return $result1;

        $result2 = $this->evaluateRuleFragment($value, $this->part2, $this->sanitizeValues($this->value2));
        if ($this->operator == "or") return ($result1 || $result2);
        return ($result1 && $result2);
    }

}

?>