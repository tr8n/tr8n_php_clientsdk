<?php

namespace tr8n\rules;

class GenderRule extends Base {
    public $operator, $value;

    public static function key() {
        return "gender";
    }

    public static function genderObjectValueFor($type) {
        return \Tr8n::config()["gender"]["method_values"][$type];
    }

    # FORM: [male, female(, unknown)]
    # {user | registered on}
    # {user | he, she}
    # {user | he, she, he/she}
    # {user | male: he, female: she, unknown: he/she}
    # {user | female: she, other: he}
    public static function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 1:
                $options["other"] = $params[0];
                break;
            case 2:
                $options["male"] = $params[0];
                $options["female"] = $params[1];
                $options["other"] = "$params[0]/$params[1]";
                break;
            case 3:
                $options["male"] = $params[0];
                $options["female"] = $params[1];
                $options["other"] = $params[2];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }


    public function evaluate($token) {
        $token_value = $this->tokenValue($token);
        if (!$token_value) return false;

        if ($this->operator == "is") {
            return ($token_value == $this->genderObjectValueFor($this->value));
        }

        if ($this->operator == "is_not") {
            return ($token_value != $this->genderObjectValueFor($this->value));
        }

        return false;
    }

}

?>