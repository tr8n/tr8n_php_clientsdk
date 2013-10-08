<?php include('includes/header.php'); ?>
<?php
\Tr8n\Config::instance()->incrementCache();
?>

<h4>Incrementing cache version...</h4>


<script>
    location = "<?php echo $_SERVER['HTTP_REFERER'] ?>";
</script>


<?php include('includes/footer.php'); ?>