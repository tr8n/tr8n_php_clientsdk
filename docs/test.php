<?php include('includes/head.php'); ?>

<div class="container">

<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

<br>

<?php tre("You have [bold: {count || message}]", array("count" => 1, "bold" => '<a href="http://www.google.com">{$0}</a>')) ?>

<br><br>

<?php tre("You have {count || message}", array("count" => 2)) ?>

<br><br>

<?php tre("You have {count || message}", array("count" => 5)) ?>

<?php tr8n_finish_block_with_options() ?>

</div>

<?php include('includes/foot.php'); ?>