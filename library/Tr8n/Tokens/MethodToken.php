<?php

namespace Tr8n\Tokens;

class MethodToken extends Base {

    public function expression() {
        return '/(\{[^_:.][\w]*(\.[\w]+)(:[\w]+)?(::[\w]+)?\})/';
    }

}

?>