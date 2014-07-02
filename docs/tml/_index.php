<h1><?php tre("Translation Markup Language") ?></h1>

<p>
    Translation Markup Language (TML) is used to identify the non-translatable or dynamic data within the labels. It provides a way to mark data and decoration tokens within the strings that need to be translated. There are different types of applications that can use TML - web, mobile and desktop. Some use HTML, others use Wiki-Like syntax for decorating the labels. TML aims at abstracting out the decoration mechanisms of the string used by the applications and instead provides its own simple, but powerful syntax. This allows for translation sharing across multiple applications.
</p>

<?php include('_basics.php'); ?>
<?php include('_setup.php'); ?>
<?php include('_data_tokens.php'); ?>
<?php include('_method_tokens.php'); ?>
<?php include('_piped_tokens.php'); ?>
<?php include('_implied_tokens.php'); ?>
<?php include('_decoration_tokens.php'); ?>
<?php include('_nested_tokens.php'); ?>
<?php include('_array_tokens.php'); ?>
