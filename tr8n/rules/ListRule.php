<?php

namespace tr8n\rules;

class ListRule extends Base {

    public $value;

    public static function key() {
        return "list";
    }

    # FORM: [one, many]
    # we like {items | this, those} {items}
    # we like {items | one: this, other: those} {items}
    public static function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 2:
                $options["one"] = $params[0];
                $options["other"] = $params[1];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }

    public function evaluate($token) {
        $list_size = $this->tokenValue($token);
        if (!$list_size) return false;

        switch ($this->value) {
            case "one_element":
                return ($list_size == 1);
            case "at_least_two_elements":
                return ($list_size > 1);
        }

        return false;
    }
}

?>