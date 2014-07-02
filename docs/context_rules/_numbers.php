<h2><?php tre("Numbers") ?></h2>

<p>
    Languages may have simple or complex numeric rules. For example, in English, there are only two rules for "one" and "other". Slovak languages, like Russian, have 3 rules. Translator can provide a translation for each rule or rule combination based on the token values.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">for($i=0; $i<10; $i++) {
    tr("You have {count||message}", array("count" => $i))
}</code></pre>

    <div class="content">
        <?php for($i=0; $i<10; $i++) { ?>
            <?php tre("You have {count||message}", array("count" => $i)) ?><br>
        <?php } ?>
    </div>
</div>