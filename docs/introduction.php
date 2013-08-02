<?php include('includes/header.php'); ?>
<?php include('includes/nav_header.php'); ?>

<?php tr8n_begin_block_with_options(array("source" => "/docs/introduction")) ?>

<h1>Introduction</h1>

<p>
    Tr8n Translation Engine is a framework for crowd-source translations and management of any internationalized text throughout any web or mobile application.
</p>
<p>
    The power of the engine comes from its simple and friendly user interface that allows site users to rapidly translate the site into hundreds of languages.
</p>
<p>
    The flexible and robust rules engine that powers Tr8n allows for any combination of language specific rules in any translatable sentence.
    Users can provide information on what sentences depend on gender rules, number rules or other rules.
    The language specific rules can be registered and managed for any language in the advanced user interface.
</p>
<p>
    The engine provides a set of powerful admin tools that allow site admins to manage any aspect of the engine; enabling and disabling its features and monitoring translation process.
    The Tr8n engine itself is based on a very robust and flexible pluggable architecture where rule types and even syntax of the “tr” tokens can be configured or extended for any application deployment.
</p>


<?php tr8n_finish_block_with_options() ?>

<?php include('includes/nav_footer.php'); ?>
<?php include('includes/footer.php'); ?>