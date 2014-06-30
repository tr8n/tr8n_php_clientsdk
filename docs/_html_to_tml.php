<h1><?php tre("HTML to TML Converter") ?></h1>
<p>
    When you deal with static content, you may want to just wrap entire HTML blocks in a function that would do the HTML to TML conversion for you.
    There is a function that does exactly that:
</p>

<pre><code class="language-php">trh("
    &lt;p>Tr8n can even &lt;b>convert HTML to TML&lt;/b>, &lt;i>translate TML&lt;/i> and &lt;u>substitute it back into HTML&lt;/u>.&lt;/p>
")</code></pre>

<p>
    <?php tre("Behind the scene, this HTML will result in the following TML:") ?>
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <div class="content">
        [p]Tr8n can even [bold]convert HTML to TML[/bold], [italic]translate TML[/italic] and [u]substitute it back into HTML[/u].[/p]
    </div>
</div>

<p>
    <?php tre("Try translating the following example, and see what you get:") ?>
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <div class="content">
        <?php trhe("<p>Tr8n can even <b>convert HTML to TML</b>, <i>translate TML</i> and <u>substitute it back into HTML</u>.</p>") ?>
    </div>
</div>

<p>
    <?php tre("Notice, that if you change the styling of any of the HTML components, it will not affect the translations.") ?>
</p>

<pre><code class="language-php">trh("
    &lt;p>Tr8n can even &lt;b style='font-size:20px;'>convert HTML to TML&lt;/b>,
        &lt;i style='color:blue'>translate TML&lt;/i> and &lt;u>substitute it back into HTML&lt;/u>.&lt;/p>
")</code></pre>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <div class="content">
        <?php trhe("<p>Tr8n can even <b style='font-size:20px;'>convert HTML to TML</b>, <i style='color:blue'>translate TML</i> and <u>substitute it back into HTML</u>.</p>") ?>
    </div>
</div>
