<?php

namespace Tr8n\Tokens;

class TransformToken extends Base {

    public function expression() {
        return '/(\{[^_:|][\w]*(:[\w]+)?(::[\w]+)?\s*\|\|?[^{^}]+\})/';
    }

}

?>