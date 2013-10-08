<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/installation")) ?>

<div style="background:white;padding-top:10px;padding-bottom:600px;margin-bottom:20px; border-radius:10px;">
    <div class="row">
        <div class="span3" style="">
            <div id="toc"></div>
        </div>
        <div class="span9">
            <div class="hero-unit" style="margin-right:10px;">
                <div class="text-center">
                    <?php image_tag("tr8n_logo.png") ?>
                </div>
                <h2 class="text-center"><?php tre("Tr8n Documentation & Samples") ?></h2>
            </div>

            <h1><?php tre("Introduction") ?></h1>
            <?php trh('
                <p>
                    This document will provide you with some examples of how to use TML for internationalizing your application. The same document is present with every Tr8n Client SDK to ensure that all samples work the same.
                </p>
            ') ?>


            <h1><?php tre("Translation Markup Language") ?></h1>

            <h2><?php tre("Basics") ?></h2>

            <?php tre("Tr8n provides a global translation function, called \"tr\".") ?>

            <?php tre("The function has two flavors, either one can be used throughout the site:") ?>

            <pre><code class="language-php">tr($label, $description = "", $tokens = array(), $options = array())</code></pre>

            <?php tre("If you don't need description, then you can use:") ?>

            <pre><code class="language-php">tr($label, $tokens = array(), $options = array())</code></pre>

            <?php tre("You can also call the language directly:") ?>

            <pre><code class="language-php">\Tr8n\Config->current_language->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

            <pre><code class="language-php">tr8n_current_language()->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

            <pre><code class="language-php">\Tr8n\Language->byLocale('ru')->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

            <p><?php tre("There is also a shorthand notation for echoing the results to the page:") ?></p>
            <pre><code class="language-php">tre($label, $description = "", $tokens = array(), $options = array())</code></pre>
            <pre><code class="language-php">tre($label, $tokens = array(), $options = array())</code></pre>

            <h4><?php tre("Setup") ?></h4>

            <?php tre("Before we begin, we need to setup a couple of users we will be using in all of the examples:") ?>

            <pre><code class="language-php">
class User {
    public $name, $gender;
    function __construct($name, $gender = "male") {
        $this->name = $name;
        $this->gender = $gender;
    }
    function __toString() {
        return $this->name;
    }
    function fullName() {
        return $this->name;
    }
}

class Number {
    public $value;
    function __construct($value) {
        $this->value = $value;
    }
    function __toString() {
        return "" . $this->value;
    }
}

$male = new User("Michael", "male");
$female = new User("Anna", "female");
                    </code></pre>

                <?php

                class User {
                    public $name, $gender;
                    function __construct($name, $gender = "male") {
                        $this->name = $name;
                        $this->gender = $gender;
                    }
                    function __toString() {
                        return $this->name;
                    }
                    function fullName() {
                        return $this->name;
                    }
                }

                class Number {
                    public $value;
                    function __construct($value) {
                        $this->value = $value;
                    }
                    function __toString() {
                        return "" . $this->value;
                    }
                }

                $male = new User("Michael", "male");
                $female = new User("Anna", "female");
                ?>


            <h2><?php tre("Data Tokens") ?></h2>
            <pre><code class="language-php">tr("Hello {user}", array("user" => "Michael"))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => "Michael")) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello {user}", array("user" => $male))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => $male)) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello {user}", array("user" => array($male, "Michael B.")))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => array($male, "Michael B"))) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => $male, "attribute" => "name")))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => array("object" => $male, "attribute" => "name"))) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => $male, "method" => "fullName")))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => array("object" => $male, "method" => "fullName"))) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => array("name" => "Alex"), "attribute" => "name")))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user}", array("user" => array("object" => array("name" => "Alex"), "attribute" => "name"))) ?>
                </div>
            </div>

            <h2><?php tre("Method Tokens") ?></h2>
            <pre><code class="language-php">tr("Hello {user.name}, you are a {user.gender}", array("user" => $male))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello {user.name}, you are a {user.gender}", array("user" => $male)) ?>
                </div>
            </div>

            <h2><?php tre("Piped Tokens") ?></h2>
            <pre><code class="language-php">tr("You have {count|| message}", array("count" => 1))
tr("You have {count|| message}", array("count" => 5))
                </code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("You have {count|| message}", array("count" => 1)) ?><br>
                    <?php tre("You have {count|| message}", array("count" => 5)) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("You have {count|| message, messages}", array("count" => 1))
tr("You have {count|| message, messages}", array("count" => 5))
                </code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("You have {count|| message, messages}", array("count" => 1)) ?><br>
                    <?php tre("You have {count|| message, messages}", array("count" => 5)) ?>
                </div>
            </div>


            <pre><code class="language-php">tr("You have {count|| one: message, other: messages}", array("count" => 5))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("You have {count|| one: message, other: messages}",  array("count" => 5)) ?><br>
                </div>
            </div>

            <pre><code class="language-php">tr("{user|| male: родился, female: родилась, other: родился/лась } в Ленинграде.", array("user" => $male), array("locale" => "ru"))
tr("{user|| male: родился, female: родилась, other: родился/лась } в Ленинграде.", array("user" => $female), array("locale" => "ru"))
                </code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("{user|| male: родился, female: родилась, other: родился/лась } в Ленинграде.", array("user" => $male), array("locale" => "ru")) ?><br>
                    <?php tre("{user|| male: родился, female: родилась, other: родился/лась } в Ленинграде.", array("user" => $female), array("locale" => "ru")) ?>
                </div>
            </div>


            <h2><?php tre("Implied Tokens") ?></h2>
            <?php tre("Implied token is a piped token that uses a single pipe.") ?> <?php tre("It indicates that the sentence translation may depend on the token value.") ?>
            <?php tre("At the same time, the token itself is not displayed in the phrase. Below are some examples:") ?>

            <pre><code class="language-php">tr("{user| He, She} likes this movie. ", array("user" => $male))
tr("{user| He, She} likes this movie. ", array("user" => $female))
                </code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("{user| He, She } likes this movie.", array("user" => $male)) ?><br>
                    <?php tre("{user| He, She } likes this movie.", array("user" => $female)) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("{user| male: He, female: She} likes this movie.", array("user" => $male))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("{user| male: He, female: She} likes this movie.", array("user" => $male)) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("{user| Born on}: ", array("user" => $male))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("{user| Born on}: ", array("user" => $male)) ?>
                </div>
            </div>


            <h2><?php tre("Decoration Tokens") ?></h2>
            <?php tre("Decoration tokens are used to inject HTML styling into translations.") ?>

            <pre><code class="language-php">tr("Hello [bold: World]", array("bold" => function($value) { return "&lt;strong>" . $value . "&lt;/strong>";} ))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello [bold: World]", array("bold" => function($value) { return "<strong>$value</strong>";} )) ?>
                </div>
            </div>

            <pre><code class="language-php">("Hello [bold: World]", array("bold" => '&lt;strong&gt;{$0}&lt;/strong&gt;'))</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello [bold: World]", array("bold" => '<strong>{$0}</strong>')) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("Hello [bold: World]")</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("Hello [bold: World]") ?>
                </div>
            </div>

            <h2><?php tre("Nested Tokens") ?></h2>
            <pre><code class="language-php">tr("You have [link: {count||message}]", array(
                        "count" => 10,
                        "link" => function($value) { return "&lt;a href='http://www.google.com'> $value &lt;/a>"; }
                    )
)</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("You have [link: {count||message}]", array("count" => 10, "link" => function($value) { return "<a href='http://www.google.com'> $value </a>"; } )) ?>
                </div>
            </div>

            <pre><code class="language-php">tr("[bold: {user}], you have [italic: [link: [bold: {count}] {count|message}]]!", array(
                        "user" => $male,
                        "count" => 10,
                        "italic" => '&lt;i>{$0}&lt;/i>',
                        "bold" => '&lt;strong>{$0}&lt;/strong>',
                        "link" => function($value) { return "&lt;a href='http://www.google.com'> $value &lt;/a>"; }
                    )
)</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("[bold: {user}], you have [italic: [link: [bold: {count}] {count|message}]]!", array("user" => $male, "bold" => '<strong>{$0}</strong>', "italic" => '<i>{$0}</i>', "count" => 10, "link" => function($value) { return "<a href='http://www.google.com'> $value </a>"; } )) ?>
                </div>
            </div>


            <h2><?php tre("HTML to TML Converter") ?></h2>
            <pre><code class="language-php">trh("
    &lt;p>Tr8n can even &lt;b>convert HTML to TML&lt;/b>, &lt;i>translate TML&lt;/i> and &lt;u>substitute it back into HTML&lt;/u>.&lt;/p>
")</code></pre>

            <p>
                Behind the scene, this HTML will result in the following TML:
            </p>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    [p]Tr8n can even [bold]convert HTML to TML[/bold], [italic]translate TML[/italic] and [u]substitute it back into HTML[/u].[/p]
                </div>
            </div>

            <br>
            <p>
                Try translating the following example, and see what you get:
            </p>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php trh("<p>Tr8n can even <b>convert HTML to TML</b>, <i>translate TML</i> and <u>substitute it back into HTML</u>.</p>") ?>
                </div>
            </div>

            <br>
            <p>Notice, that if you change the styling of any of the HTML components, it will not affect the translations.</p>

            <pre><code class="language-php">trh("
    &lt;p>Tr8n can even &lt;b style='font-size:20px;'>convert HTML to TML&lt;/b>, &lt;i style='color:blue'>translate TML&lt;/i> and &lt;u>substitute it back into HTML&lt;/u>.&lt;/p>
")</code></pre>

            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php trh("<p>Tr8n can even <b style='font-size:20px;'>convert HTML to TML</b>, <i style='color:blue'>translate TML</i> and <u>substitute it back into HTML</u>.</p>") ?>
                </div>
            </div>

            <h1><?php tre("Context Rules") ?></h1>

            <h2><?php tre("Numbers") ?></h2>
            <pre><code class="language-php">for($i=0; $i<10; $i++) {
    tr("You have {count||message}", array("count" => $i))
}</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php for($i=0; $i<10; $i++) { ?>
                        <?php tre("You have {count||message}", array("count" => $i)) ?><br>
                    <?php } ?>
                </div>
            </div>

            <h2><?php tre("Genders") ?></h2>
            <pre><code class="language-php">tre("{actor} tagged {target} in a photo {target|he, she} just uploaded.", array("actor" => $male, "target" => $female))
tre("{actor} tagged {target} in a photo {target|he, she} just uploaded.", array("actor" => $female, "target" => $male))
</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("{actor} tagged {target} in a photo {target|he, she} just uploaded.", array("actor" => $male, "target" => $female)) ?><br>
                    <?php tre("{actor} tagged {target} in a photo {target|he, she} just uploaded.", array("actor" => $female, "target" => $male)) ?><br>
                </div>
            </div>


            <h1><?php tre("Language Cases") ?></h1>
            <h2><?php tre("Possessive") ?></h2>
            <pre><code class="language-php">tre("This is {user::pos} photo", array("user" => $male))</code></pre>
            <div class="example">
                <div class="title"><?php tre("results in") ?></div>
                <div class="content">
                    <?php tre("This is {user::pos} photo", array("user" => $male)) ?>
                </div>
            </div>

            <h1><?php tre("Caching") ?></h1>
            <p>
                Since pages may contain numerous translation keys, it is crucial that Tr8n is backed by a caching mechanism.
                The caching mechanism provides a local cache of the Tr8n objects retrieved from the service. When users view the pages in non-translation mode, the translations will be served from the cache.
                For translators, who enable inline translation mode, the SDK will always request the Tr8n service to get the most recent translations.
            </p>
            <p>
                Tr8n supports 4 types of Cache adapters:
                <ul>
                    <li>File based</li>
                    <li>APC</li>
                    <li>Memcache</li>
                    <li>CHDB</li>
                </ul>
            </p>
            <p>
                To change cache settings, modify config/config.json file.
            </p>
            <pre><code class="language-javascript">"cache": {
    "enabled": true,
    "adapter": "memcache",
    "version": 1,
    "timeout": 3600
}</code></pre>

            <h2><?php tre("File Based Caching") ?></h2>
            <p>This is a readonly cache that must be externally generated to take effect.</p>
            <p>
                To generate the cache files, run the following script:
            </p>
            <pre><code class="language-bash">$ bin/generate_files</code></pre>
            <p>
                The files will be stored in the cache/files folder.
            </p>

            <h2><?php tre("CHDB") ?></h2>
            <p>This is a readonly cache that must be externally generated to take effect.</p>
            <p>
                To generate the cache files, run the following script:
            </p>
            <pre><code class="language-bash">$ bin/generate_chdb</code></pre>
            <p>
                The files will be stored in the cache/chdb folder.
            </p>

            <h2><?php tre("APC") ?></h2>
            <p>
                APC is a self-building cache that lazily warms up by retrieving data from the Tr8n service and
                storing it in the APC store.
            </p>
            <h2><?php tre("Memcache") ?></h2>
            <p>
                Memcache is a self-building cache that lazily warms up by retrieving data from the Tr8n service and
                storing it in the APC store.
            </p>
        </div>

    </div>
</div>

<?php stylesheet_tag('jquery.tocify.css') ?>
<?php javascript_tag('jquery-ui-1.10.3.custom.min.js') ?>
<?php javascript_tag('jquery.tocify.min.js') ?>

<?php stylesheet_tag('prism.css') ?>
<?php javascript_tag('prism.js') ?>

    <style>
        .example {
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .example .title {
            color: white;
            width: 80px;
            text-align:center;
            padding:3px;
            background: #ccc;
            border-bottom: 1px solid #ccc;
            border-right: 1px solid #ccc;
            border-top-left-radius:5px;
            border-bottom-right-radius:5px;
        }

        .example .content {
            padding:10px;
        }

        h4 {
            margin-top: 35px;
        }

        #toc {
            width:240px;
        }
    </style>

    <script>
        $(function() {
            $("#toc").tocify({
                "selectors": "h1,h2,h3"
            });
        });
    </script>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>