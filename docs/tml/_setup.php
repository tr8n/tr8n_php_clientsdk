
<h4><?php tre("Setup") ?></h4>

<?php tre("Before we proceed to more advanced examples, we need to setup a couple of users we will be using in all of the examples:") ?>

<pre><code class="language-php">
class User {
    public $name, $gender;
    function __construct($name, $gender = "male") {
        $this->name = $name;
        $this->gender = $gender;
    }
    function __toString() {
        return $this->name;
    }
    function fullName() {
        return $this->name;
    }
}

$male = new User("Michael", "male");
$female = new User("Anna", "female");
</code></pre>


<pre><code class="language-php">
class Number {
    public $value;
    function __construct($value) {
        $this->value = $value;
    }
    function __toString() {
        return "" . $this->value;
    }
}
</code></pre>

<?php

class User {
    public $name, $gender;
    function __construct($name, $gender = "male") {
        $this->name = $name;
        $this->gender = $gender;
    }
    function __toString() {
        return $this->name;
    }
    function fullName() {
        return $this->name;
    }
}

class Number {
    public $value;
    function __construct($value) {
        $this->value = $value;
    }
    function __toString() {
        return "" . $this->value;
    }
}

$male = new User("Michael", "male");
$female = new User("Anna", "female");
?>



