<?php

namespace Tr8n\Tokens;

class DecorationToken extends Base {

    public function expression() {
        return '/(\[\w+:[^\]]+\])/';
    }

    public function sanitizedName() {
        if ($this->sanitized_name === null) {
            $this->sanitized_name = "[" . $this->name() . ": ]";
        }
        return $this->sanitized_name;
    }

}

?>