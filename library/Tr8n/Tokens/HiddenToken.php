<?php

namespace Tr8n\Tokens;

class HiddenToken extends Base {

    public function expression() {
        return '/(\{_[\w]+\})/';
    }

}

?>