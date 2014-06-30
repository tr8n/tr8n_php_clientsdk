<h2><?php tre("Piped Tokens") ?></h2>
<p>Piped tokens work in conjunction with context rules and allow you to provide substitution values based on the object values.</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("You have {count|| one: message, other: messages}", array("count" => 1))
    </code></pre>
    <div class="content">
        <?php tre("You have {count|| one: message, other: messages}", array("count" => 1)) ?><br>
    </div>
</div>

<p>
    Double pipe "||" means that the value would be displayed, followed by the word that depends on the value. In this case, if the count value meets the criteria for the rule "one", then it will display the word set to the rule. For all other cases it would display the "other" value.
</p>
<p>
    Since the sequence of parameters is mapped to the sequence of rules, you can omit naming the parameters.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("You have {count || message, messages}", array("count" => 2))
    </code></pre>
    <div class="content">
        <?php tre("You have {count || message, messages}", array("count" => 2)) ?>
    </div>
</div>

<p>
    Since the library comes with default pluralizers, you don't even need to provide the plural form. It will be automagically generated for you.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("You have {count || message}", array("count" => 5))
        </code></pre>
    <div class="content">
        <?php tre("You have {count || message}", array("count" => 5)) ?>
    </div>
</div>

<p>
The same exact concept applies to other token types and context rules.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("{user} updated {user | his, her} profile.", array("user" => $male))
        </code></pre>
    <div class="content">
        <?php tre("{user} updated {user | his, her} profile.", array("user" => $male)) ?>
    </div>
</div>

<p>
Single pipe "|" means to not display the actual token value, but display the value that follows based on the context rules.
</p>
<p>
The context rules are specific for each language. But you don't have to worry about it, translators will provide the rules and the values.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">&lt;?php foreach (array($male, $female) as $user) { ?&gt;
    tr("{user|| male: родился, female: родилась, other: родился/лась } в Ленинграде.", array(
            "user" => $user
    ), array("locale" => "ru"))
&lt;?php } ?&gt;
    </code></pre>
    <div class="content">
        <?php foreach (array($male, $female) as $user) { ?>
            <?php tre("{user|| male: родился, female: родилась, other: родился/лась} в Ленинграде.", array("user" => $user), array("locale" => "ru")) ?><br>
        <?php } ?>
    </div>
</div>

<p>Similarly, we can omit naming the parameters by adhering to the order:</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">&lt;?php foreach (array($male, $female) as $user) { ?&gt;
    tr("{user|| родился, родилась, родился/лась} в Ленинграде.", array("user" => $user), array("locale" => "ru"))
&lt;?php } ?&gt;
</code></pre>
    <div class="content">
        <?php foreach (array($male, $female) as $user) { ?>
            <?php tre("{user|| родился, родилась, родился/лась} в Ленинграде.", array("user" => $user), array("locale" => "ru")) ?><br>
        <?php } ?>
    </div>
</div>