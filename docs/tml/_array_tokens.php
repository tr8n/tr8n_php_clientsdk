<h2><?php tre("Array Tokens") ?></h2>
<p>Default options:</p>
<pre><code class="language-php">tr("{users} joined the site", array("users" => array(array("user1, user2, user3"), "@name", array(
  "limit" => 4,
  "separator" => ', ',
  "joiner" => 'and',
  "remainder" => function($element) { return tr("#{count||other}", array("count" => count($elements))); },
  "expandable" => true,
  "collapsable" => true
)))</code></pre>

<p>
Let's setup some users so we can use them in our examples:
</p>

<pre><code class="language-php">$mike       = new User("Mike", "male");
$tom        = new User("Tom", "male");
$alex       = new User("Alex", "male");
$peter      = new User("Peter", "male");
$anna       = new User("Anna", "female");
$kate       = new User("Kate", "female");
$jenny      = new User("Jenny", "female");

$all = array($mike, $tom, $alex, $peter, $anna, $kate, $jenny);
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


<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array(array($mike, $anna), "@name")))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array(array($mike, $anna), "@name"))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array(array($mike, $anna), "@@fullName")))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array(array($mike, $anna), "@@fullName"))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array(array($mike, $anna), '&lt;strong>{$0}&lt;/strong>')))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array(array($mike, $anna), '<strong>{$0}</strong>'))) ?>
    </div>
</div>


<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array(array($mike, $anna), function($user) {
   return "&lt;i>" . $user->name . "&lt;/i>";
})))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array(array($mike, $anna), function($user) {
            return "<i>" . $user->name . "</i>";
        }))) ?>
    </div>
</div>


<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array(array($mike, $anna), "@name", array(
    "joiner" => "or"
))))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array(array($mike, $anna), "@name",  array(
            "joiner" => "or"
        )))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array($all, "@name")))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array($all, "@name"))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array($all, "@name", array(
    "limit" => 2
))))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array($all, "@name",  array(
            "limit" => 2,
            "key" => "1"
        )))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{users} joined the site", array("users" => array($all, function($user) {
    return '&lt;a href="">' . $user->name . '&lt;/a>';
}, array(
    "limit" => 15
))))</code></pre>
    <div class="content">
        <?php tre("{users} joined the site", array("users" => array($all, function($user) {
            return '<a href="">' . $user->name . '</a>';
        },  array(
            "limit" => 15
        )))) ?>
    </div>
</div>

