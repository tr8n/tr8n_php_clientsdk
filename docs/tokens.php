<?php include('docs/includes/header.php'); ?>

<?php echo tr("Hello world"); ?><br><br>

<?php for($i=0; $i<100; $i++) { ?>
    <?php echo tr("You have {count||message}", null, array('count'=> $i)); ?><br>
<?php } ?>


<?php include('docs/includes/footer.php'); ?>