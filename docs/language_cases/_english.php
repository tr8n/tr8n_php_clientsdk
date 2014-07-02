<h2><?php tre("English Examples")  ?></h2>

<h4><?php tre("Possessives")  ?></h4>
<div class="example">
    <div class="title"><?php tre('example') ?></div>
    <pre class="code"><code class="language-php">tr("This is {user::pos} post", :user => $mike)</code></pre>
    <div class="content">
        <?php tre("This is {user::pos} post", array("user" => $mike))  ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">foreach($variants as $users)
    tre("{actor} updated {users::pos} profile", array("actor" => $mike, "users" => array($users, "@name")))
}</code></pre>
    <div class="content">
        <?php foreach($variants as $users) { ?>
            <?php tre("{actor} updated {users::pos} profile", array("actor" => $mike, "users" => array($users, "@name"))) ?><br>
        <?php }  ?>
    </div>
</div>

<h4><?php tre("Counters")  ?></h4>
<div class="example">
    <div class="title"><?php tre('example') ?></div>
          <pre class="code"><code class="language-php">for($i=1; $i<5; $i++) {
    tre("You have already sent this message {count::times}", array("count" => $i))
}</code></pre>
    <div class="content">
        <?php for($i=1; $i<5; $i++) { ?>
            <?php tre("You have already sent this message {count::times}", array("count" => $i)) ?><br>
        <?php }  ?>
    </div>
</div>

<h4><?php tre("Ordinals") ?></h4>
<div class="example">
    <div class="title"><?php tre('example') ?></div>
          <pre class="code"><code class="language-php">for($i=1; $i<5; $i++)
    tr("This is your {count::ord} warning", array("count" => $i))
}</code></pre>
    <div class="content">
        <?php for($i=1; $i<5; $i++) { ?>
            <?php tre("This is your {count::ord} warning", array("count" => $i)) ?><br>
        <?php }  ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre('example') ?></div>
          <pre class="code"><code class="language-php">for($i=1; $i<=5; $i++) {
    tr("This is your {count::ordinal} warning", array("count" => $i))
}</code></pre>
    <div class="content">
        <?php for($i=1; $i<=5; $i++) { ?>
            <?php tre("This is your {count::ordinal} warning", array("count" => $i)) ?><br>
        <?php }  ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre('example') ?></div>
          <pre class="code"><code class="language-php">for($i=1; $i<=5; $i++) {
    tr("This is your {count::ordinal::ord} warning", array("count" => $i))
}</code></pre>
    <div class="content">
        <?php for($i=1; $i<=5; $i++) { ?>
            <?php tre("This is your {count::ordinal::ord} warning", array("count" => $i)) ?><br>
        <?php }  ?>
    </div>
</div>