<h2><?php tre("Nested Tokens") ?></h2>

<p>
    Decoration tokens can be nested and they may contain data tokens as well.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("You have [link: {count||message}]", array(
    "count" => 10,
    "link" => function($value) { return "&lt;a href='http://www.google.com'> $value &lt;/a>"; }
  )
)</code></pre>
    <div class="content">
        <?php tre("You have [link: {count||message}]", array("count" => 10, "link" => function($value) { return "<a href='http://www.google.com'> $value </a>"; } )) ?>
    </div>
</div>

<p>
    Notice that all of the nested tokens are still translated in-context and allow for very accurate translations.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("[bold: {user}], you have [italic: [link: [bold: {count}] {count|message}]]!", array(
    "user" => $male,
    "count" => 10,
    "italic" => '&lt;i>{$0}&lt;/i>',
    "bold" => '&lt;strong>{$0}&lt;/strong>',
    "link" => function($value) { return "&lt;a href='http://www.google.com'> $value &lt;/a>"; }
  )
)</code></pre>
    <div class="content">
        <?php tre("[bold: {user}], you have [italic: [link: [bold: {count}] {count|message}]]!", array("user" => $male, "bold" => '<strong>{$0}</strong>', "italic" => '<i>{$0}</i>', "count" => 10, "link" => function($value) { return "<a href='http://www.google.com'> $value </a>"; } )) ?>
    </div>
</div>