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