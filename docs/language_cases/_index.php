<h1><?php tre("Language Cases") ?></h1>

<p>
    Language cases allow you to manipulate the values of the data passed through tokens.
</p>

<pre class="code"><code class="language-php">$mike       = new User("Mike", "male");
$tom        = new User("Tom", "male");
$alex       = new User("Alex", "male");
$peter      = new User("Peter", "male");
$anna       = new User("Anna", "female");
$kate       = new User("Kate", "female");
$jenny      = new User("Jenny", "female");
</code></pre>

<?php
$mike       = new User("Mike", "male");
$tom        = new User("Tom", "male");
$alex       = new User("Alex", "male");
$peter      = new User("Peter", "male");
$anna       = new User("Anna", "female");
$kate       = new User("Kate", "female");
$jenny      = new User("Jenny", "female");

$all = array($mike, $tom, $alex, $peter, $anna, $kate, $jenny);
?>

<pre class="code"><code class="language-php">$variants = array(
    array($mike),
    array($anna),
    array($kate, $jenny),
    array($peter, $anna, $jenny),
    array($mike, $tom, $peter)
);
</code></pre>

<?php
$variants = array(
    array($mike),
    array($anna),
    array($kate, $jenny),
    array($peter, $anna, $jenny),
    array($mike, $tom, $peter)
);
?>


<?php include('_english.php'); ?>

<?php include('_russian.php'); ?>
