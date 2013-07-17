<?php require_once('library/Tr8n.php'); ?>
<?php tr8n_init_client_sdk("http://sandbox.tr8nhub.com", "0c1eb03d6c6e12cb2", "5ff3d87a83c13fcdb"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <?php include('library/Tr8n/Includes/Scripts.php'); ?>
</head>

<body>

<?php echo tr("Hello world"); ?><br><br>

<?php for($i=0; $i<100; $i++) { ?>
    <?php echo tr("You have {count||message}", null, array('count'=> $i)); ?><br>
<?php } ?>

</body>
</html>