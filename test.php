<?php include('docs/includes/head.php'); ?>
<div class="container">
<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

<?php trh("

<h1>Heading</h1>

") ?>


<?php trh("

<a href=\"http://www.google.com\">Click here</a> to visit <b>Google</b> web site.

") ?>

    <?php tr8n_finish_block_with_options() ?>
</div>


<img alt="<?php trle("some info") ?>">


<style>
    .sample {
        font-weight:bold;
    }
</style>

<?php tr8n_complete_request() ?>
<?php include('docs/includes/foot.php'); ?>