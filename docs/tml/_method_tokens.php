<h2><?php tre("Method Tokens") ?></h2>

<p>Method tokens allow you to invoke methods on the object you are passing to get the substitution value.</p>
<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user.name}, you are a {user.gender}", array("user" => $male))</code></pre>
    <div class="content">
        <?php tre("Hello {user.name}, you are a {user.gender}", array("user" => $male)) ?>
    </div>
</div>