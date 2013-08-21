<?php include('includes/header.php'); ?>
<?php include('includes/nav_header.php'); ?>

<?php error_reporting(E_ALL); ini_set('display_errors', 1); ?>

<?php tr8n_begin_block_with_options(array("source" => "/docs/introduction")) ?>

    <h1><?php tre("Rules Engine") ?></h1>

    <p>
        <?php
            for($i=0; $i< 30; $i++) {
        ?>
                <?php tre("you have {count||message}", "inbox label", array("count" => $i)) ?> <br>

        <?php } ?>

        <br>

        <?php tre("{actor} sent you a message", null, array("actor" => array("object" => array("gender" => "male", "name" => "Michael"), "attribute" => "name"))) ?> <br>

    </p>









<?php tr8n_finish_block_with_options() ?>

<?php include('includes/nav_footer.php'); ?>
<?php include('includes/footer.php'); ?>