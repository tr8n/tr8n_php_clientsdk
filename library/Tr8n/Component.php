<?php
namespace tr8n;

class Component extends Base {

    public $application, $key, $name, $description, $state;

    function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function isLive() {
        return ($this->state == 'live');
    }

}

?>