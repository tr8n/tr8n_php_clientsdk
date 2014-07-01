<h1><?php tre("Language Cases") ?></h1>
<h2><?php tre("Possessive") ?></h2>
<pre><code class="language-php">tre("This is {user::pos} photo", array("user" => $male))</code></pre>
<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <div class="content">
        <?php tre("This is {user::pos} photo", array("user" => $male)) ?>
    </div>
</div>