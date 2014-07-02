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