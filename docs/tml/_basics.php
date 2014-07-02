<h2><?php tre("Basics") ?></h2>

<?php tre("Tr8n provides a global translation function, called \"tr\".") ?>

<?php tre("The function has two flavors, either one can be used throughout the site:") ?>

<pre><code class="language-php">tre($label, $description = "", $tokens = array(), $options = array())</code></pre>

<?php tre("If you don't need description, then you can use:") ?>

<pre><code class="language-php">tre($label, $tokens = array(), $options = array())</code></pre>

<?php tre("You can also call the language directly:") ?>

<pre><code class="language-php">\Tr8n\Config->current_language->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

<pre><code class="language-php">tr8n_current_language()->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

<pre><code class="language-php">\Tr8n\Language->byLocale('ru')->translate($label, $description = "", $tokens = array(), $options = array())</code></pre>

<p><?php tre("There is also a shorthand notation for echoing the results to the page:") ?></p>
<pre><code class="language-php">tre($label, $description = "", $tokens = array(), $options = array())</code></pre>
<pre><code class="language-php">tre($label, $tokens = array(), $options = array())</code></pre>

<p>
    <ul>
        <li><?php tre("[bold: label] is the only required parameter.") ?>
        <li><?php tre("[bold: description] is an optional parameter, but should always be used if the label by itself is not sufficient enough to provide the meaning of the phrase.") ?>
        <li><?php tre("[bold: tokens] is an optional parameter that contains a hash (or a dictionary) of token values to be substituted in the label.") ?>
        <li><?php tre("[bold: options] provides a mechanism for passing additional directives to the translation engine. ") ?>
    </ul>
</p>

<p><?php tre("Let's start with a simple phrase:") ?></p>

<div class="example">
    <div class="title"><?php tre('example') ?></div>
    <pre class="code"><code class="language-php">tre("Hello World")</code></pre>

    <div class="content">
        <?php tre("Hello World") ?>
    </div>
</div>

<p>
    <?php tre("The description of a phrase is not mandatory, but it should be used in cases when the label alone is not sufficient enough to determine the meaning of the sentence being translated.") ?>
    <?php tre("As a general rule, you should always provide description to words, phrases and sentences that are only meaningful within a specific context.") ?>
    <?php tre("Tr8n uses label and description together to create a unique key for each phrase.") ?>
    <?php tre("The description serves two purposes: it creates a unique key for each label and it also gives a hint to the translators for the context in which the label is used.") ?>
</p>

<p>
    <?php tre("For example, the following two phrases will be registered as two independent entries in a database even though the have the same label, but a different description. The user will have to translate each one of them separately as they will have different translated labels in other languages.") ?>
</p>

<div class="example">
    <div class="title"><?php tre('example') ?></div>
    <pre class="code"><code class="language-php">tre("Invite", "Link to invite your friends to join the site")
tre("Invite", "An invitation you received from your friend")</code></pre>

    <div class="content">
        <?php tre("Invite", "Link to invite your friends to join the site") ?><br>
        <?php tre("Invite", "An invitation you received from your friend")  ?>
    </div>
</div>

<p>
    <?php tre("It is important to provide the best possible description for each phrase from the start. Keep in mind that changing a description in the future, after it has already been translated, will register a new phrase in the database and invalidate all of its translations. On the other hand, labels that are complete sentences may not need a description as they are fully self-contained.") ?>
</p>





