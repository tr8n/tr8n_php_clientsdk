<h2><?php tre("Lists")  ?></h2>
<p><?php tre("In languages like Hebrew, the list rules may include cases such as when all members of the list are male, female or have mixed genders. In Russian, the list rules may include cases for single member male, female, unknown or multiple members.")  ?></p>

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

<div class="example">
  <div class="title"><?php tre('example') ?></div>
  <pre class="code"><code class="language-php">foreach ($variants as $users) {
    tr("{users||likes, like} this post", array("users" =&gt; array(
          $users, function($user) { return tr($user-&gt;name); }
      )));
}
  </code></pre>
  <div class="content">
    <?php foreach ($variants as $users) {  ?>
      <?php tre("{users || likes, like} this post", array("users" => array($users, function($user) { return tr($user->name); })));  ?><br>
    <?php } ?>
  </div>
</div>


<div class="example">
  <div class="title"><?php tre('example') ?></div>
  <pre class="code"><code class="language-php">foreach ($variants as $users) {
    tr("{users || has, have} arrived at {users | his, her, their} destination.", array(
          "users" =&gt; array($users, function($user) { return tr($user-&gt;name); }
          )));
}
  </code></pre>
  <div class="content">
    <?php foreach ($variants as $users) {  ?>
      <?php tre("{users || has, have} arrived at {users | his, her, their} destination.", array("users" => array($users, function($user) { return tr($user->name); })));  ?><br>
    <?php } ?>
  </div>
</div>


<div class="example">
  <div class="title"><?php tre('example') ?></div>
  <pre class="code"><code class="language-php">foreach ($variants as $users) {
    for ($i=1; $i<3; $i++) {
        foreach (array("Los Angeles", "Paris") as $city) {
            tr("{users || перебрался, перебралась, перебрались} в {city} {count || месяц, месяца, месяцев} назад",
                  array(
                      "users" => array($users, function($user) { return tr($user->name); }),
                      "city" => tr($city),
                      "count" => $i),
                  array("locale" => 'ru')
            )
        }
    }
}</code></pre>
  <div class="content">
    <?php foreach ($variants as $users) {
        for ($i=1; $i<3; $i++) {
            foreach(array("Los Angeles", "Paris") as $city) { ?>
              <?php tre("{users || перебрался, перебралась, перебрались} в {city} {count || месяц, месяца, месяцев} назад",
                array(
                    "users" => array($users, function($user) { return tr($user->name); }),
                    "city" => tr($city),
                    "count" => $i),
                array("locale" => 'ru'))  ?><br>
        <?php }
            }
        } ?>
  </div>
</div>
