<?php include('docs/includes/head.php'); ?>
<div class="container">
<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

<?php trh("

<h1>Section 1</h1>

<p>
    This is <b>very <i>very</i> interesting</b> indeed.
</p>

") ?>

<br/>

    <h1>Section 1</h1>

    <p>
        This is <b>very <i>very</i> interesting</b> indeed.
    </p>



    <?php tr8n_finish_block_with_options() ?>
</div>
<?php tr8n_complete_request() ?>
<?php include('docs/includes/foot.php'); ?>