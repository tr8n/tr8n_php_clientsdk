<h2><?php tre("Implied Tokens") ?></h2>
<?php tre("Implied token is a piped token that uses a single pipe.") ?> <?php tre("It indicates that the sentence translation may depend on the token value.") ?>
<?php tre("At the same time, the token itself is not displayed in the phrase. Below are some examples:") ?>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
                <pre><code class="language-php">tr("{user| male: He, female: She} likes this movie.", array("user" => $male))
                    </code></pre>
    <div class="content">
        <?php tre("{user| male: He, female: She} likes this movie.", array("user" => $male)) ?><br>
    </div>
</div>

<p>
    Similar to the previous examples, you don't have to provide the named parameter values.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{user| He, She} likes this movie.", array("user" => $male))</code></pre>
    <div class="content">
        <?php tre("{user| He, She} likes this movie.", array("user" => $male)) ?>
    </div>
</div>

<p>
    Even though the base language does not have a gender specific dependency in some cases, it is always good to wrap it with an implied token.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{user| Born on}: ", array("user" => $male))</code></pre>
    <div class="content">
        <?php tre("{user| Born on}: ", array("user" => $male)) ?>
    </div>
</div>

<p>
    As a general rule, if any of the words of your translation keys depend on a user, use implied tokens. It won't affect default translations, yet it would give translators an option make the translation accurate.
</p>