<?php include('docs/includes/head.php'); ?>
<div class="container">
<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

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

<?php tre("[bold: {user}], you have [italic: [link: [bold: {count}] {count|message}]]!", array(
        "user" => $male,
        "count" => 10,
        "italic" => '<i>{$0}</i>',
        "bold" => '<strong>{$0}</strong>',
        "link" => function($value) { return "<a href='http://www.google.com'> $value </a>"; }
    )
) ?>


<?php tr8n_finish_block_with_options() ?>
</div>

<?php include('docs/includes/foot.php'); ?>