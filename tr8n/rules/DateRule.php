<?php

namespace tr8n\rules;

class DateRule extends Base {

    public $value;

    public static function key() {
        return "date";
    }

    # FORM: [past, present, future]
    # This event {date | took place, is taking place, will take place} on {date}.
    # This event {date | past: took place, present: is taking place, future: will take place} on {date}.
    public static function defaultTransformOptions($params, $token) {
        $options = array();
        switch (count($params)) {
            case 3:
                $options["past"] = $params[0];
                $options["present"] = $params[1];
                $options["future"] = $params[2];
                break;
            default:
                throw new Tr8nException("Invalid number of parameters in the transform token $token");
        }
        return $options;
    }

    public function evaluate($token) {
        $token_value = $this->tokenValue($token);
        if (!$token_value) return false;

        $current_date = strtotime(date("Y-m-d"));
        switch ($this->value) {
            case "past":
                return ($token_value < $current_date);
            case "present":
                return ($token_value == $current_date);
            case "future":
                return ($token_value > $current_date);
        }

        return false;
    }
}

?>