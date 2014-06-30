<h2><?php tre("Decoration Tokens") ?></h2>
<p>
    Decoration tokens are used to inject HTML styling into translations. In other libraries, like in iOS or Android, the tokens can be substituted with a native decoration framework.
</p>

<p>
    Token values can be passed as anonymous functions (or lambdas).
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello [bold: World]", array("bold" => function($value) {
    return "&lt;strong>$value&lt;/strong>";
}))</code></pre>
    <div class="content">
        <?php tre("Hello [bold: World]", array("bold" => function($value) { return "<strong>$value</strong>";} )) ?>
    </div>
</div>

<p>
    Or they can be defined as strings, where {$0} indicates the translated value being passed in.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">("Hello [bold: World]", array("bold" => '&lt;strong&gt;{$0}&lt;/strong&gt;'))</code></pre>
    <div class="content">
        <?php tre("Hello [bold: World]", array("bold" => '<strong>{$0}</strong>')) ?>
    </div>
</div>

<p>
    You can also pre-define all your tokens in the configuration. Then you don't need to pass them at all. In the following example, bold is already pre-defined in the framework.
</p>


<div class="example">
    <div class="title"><?php tre("example") ?></div>

    <pre><code class="language-php">\Tr8n\Config::instance()->setDefaultToken(
    'indent',
    '&lt;span style="font-size:15px;color:green">{$0}&lt;/span>',
    'decoration'
);</code></pre>

    <pre><code class="language-php">tr("Hello [indent: World]")</code></pre>

    <div class="content">
        <?php \Tr8n\Config::instance()->setDefaultToken("indent", '<span style="font-size:15px;color:green">{$0}</span>', 'decoration'); ?>
        <?php tre("Hello [indent: World]") ?>
    </div>
</div>

<p>
    You can define tokens in the code, or you could pre-define them in the /config/tokens.json file.
</p>

<pre><code class="language-javascript"> {
    "strong":   "&lt;strong&gt;{$0}&lt;/strong&gt;",
    "bold":     "&lt;strong&gt;{$0}&lt;/strong&gt;",
    "b":        "&lt;strong&gt;{$0}&lt;/strong&gt;",
    "em":       "&lt;em&gt;{$0}&lt;/em&gt;",
    "italic":   "&lt;i&gt;{$0}&lt;/i&gt;",
    "i":        "&lt;i&gt;{$0}&lt;/i&gt;",
    "link":     "&lt;a href='{$href}'&gt;{$0}&lt;/a&gt;",
    "br":       "&lt;br&gt;{$0}",
    "strike":   "&lt;strike&gt;{$0}&lt;/strike&gt;",
    "div":      "&lt;div id='{$id}' class='{$class}' style='{$style}'&gt;{$0}&lt;/div&gt;",
    "span":     "&lt;span id='{$id}' class='{$class}' style='{$style}'&gt;{$0}&lt;/span&gt;",
    "h1":       "&lt;h1&gt;{$0}&lt;/h1&gt;",
    "h2":       "&lt;h2&gt;{$0}&lt;/h2&gt;",
    "h3":       "&lt;h3&gt;{$0}&lt;/h3&gt;"
}</code></pre>

<p>
   You can pass extra token attributes to the tokens by providing them as dictionaries.
</p>


<div class="example">
    <div class="title"><?php tre("example") ?></div>

    <pre><code class="language-php">tr("Hello [link: World]", array("href" => "http://www.google.com"))</code></pre>

    <div class="content">
        <?php tre("Hello [link: World]", array("href" => "http://www.google.com")) ?>
    </div>
</div>

<p>
    You can also use the long notation of the decoration token.
</p>


<div class="example">
    <div class="title"><?php tre("example") ?></div>

    <pre><code class="language-php">tr("[link] Click here [/link] to view this section of the document", array("link" => array("href" => "/docs")))</code></pre>

    <div class="content">
        <?php tre("[link] Click here [/link] to view this section of the document", array("link" => array("href" => ""))) ?>
    </div>
</div>

<p>
    Decoration tokens can be nested.
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>

    <pre><code class="language-php">tr("[link] [bold: Click here] to view [underline: the introduction section] of this document [/link]", array(
    "link" => array("href" => "/docs"),
    "underline" => "<span style='text-decoration: underline'>{$0}</span>"
))</code></pre>

    <div class="content">
        <?php tre("[link] [bold: Click here] to view [underline: the introduction section] of this document [/link]", array("link" => array("href" => ""), "underline" => '<span style="text-decoration: underline">{$0}</span>')) ?>
    </div>
</div>


