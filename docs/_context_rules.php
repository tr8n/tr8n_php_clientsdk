<h1><?php tre("Context Rules") ?></h1>

<p>Context rules are used to provide translations based on a specific value of one or more tokens in a sentence.</p>

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

<h2><?php tre("Genders") ?></h2>
<p>
    Similarly to the numeric rules, some language have dependencies on the gender.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">
&lt;?php foreach (array($male, $female) as $user) { ?&gt;
    &lt;?php tre("{user} uploaded {user | his, her} photo.", array("user" =&gt; $user)) ?&gt;
&lt;?php } ?&gt;
</code></pre>
    <div class="content">
        <?php foreach (array($male, $female) as $user) { ?>
            <?php tre("{user} uploaded {user | his, her} photo.", array("user" => $user)) ?><br>
        <?php } ?>
    </div>
</div>

<p>
    Sometimes tokens need to be implied:
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">
&lt;?php foreach (array($male, $female) as $user) { ?&gt;
    &lt;?php tre("{user | Registered} on:", array("user" =&gt; $user)) ?&gt;
&lt;?php } ?&gt;
</code></pre>
    <div class="content">
        <?php foreach (array($male, $female) as $user) { ?>
            <?php tre("{user | Registered} on:", array("user" => $user)) ?><br>
        <?php } ?>
    </div>
</div>

<p>
    The above example looks the same in English. But in languages, like Russian, the translations would rely on the gender of the user.
</p>

<!---->
<!--<h2>--><?php //tre("Dates") ?><!--</h2>-->
<!---->
<!--<p>Dates can also be used for contextual evaluation. Consider the following example:</p>-->
<!---->
<!---->
<!--[Date.today, Date.today - 1.day, Date.today+1.day].each do |date|-->
<!---->
<!--  if date == Date.today-->
<!--    tr("{user} is celebrating {user| his, her} birthday today", {:user => @michael})-->
<!--  elsif date < Date.today-->
<!--    tr("{user} celebrated {user| his, her} birthday on {date}", {:user => @michael})-->
<!--  else-->
<!--    tr("{user} will celebrate {user| his, her} birthday on {date}", {:user => @michael})-->
<!--  end-->
<!---->
<!--end-->
