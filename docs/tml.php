<?php include('includes/header.php'); ?>
<?php include('includes/nav_header.php'); ?>

<?php tr8n_begin_block_with_options(array("source" => "/docs/introduction")) ?>

    <h1>Tr8n Syntax</h1>

    <p>
        Translation Markup Language (TML) is used to identify the non-translatable and dynamic data within the labels. It provides a way to mark data and decoration tokens within the strings that need to be translated. There are different types of applications that can use TML - web, mobile and desktop. Some use HTML, others use Wiki-Like syntax for decorating the labels. TML aims at abstracting out the decoration mechanisms of the string used by the applications and instead provides its own simple, but powerful syntax. This allows for translation sharing across multiple applications.
    </p>

    <p>
        There are two flavors of the translation method signature in Tr8n and both are supported in all Tr8n Client SDKs:
    </p>

    <pre><code class="language-php"> tr($label, $description = "", $tokens = array(), $options = array()) </code></pre>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/nav_footer.php'); ?>
<?php include('includes/footer.php'); ?>