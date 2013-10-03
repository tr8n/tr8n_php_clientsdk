<?php require_once(__DIR__ . '/../../library/Tr8n.php'); ?>
<?php tr8n_init_client_sdk(); ?>

<?php include('helpers.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>" lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php javascript_tag("jquery191.js") ?>
    <?php javascript_tag("bootstrap.js") ?>
    <?php javascript_tag("sh.js") ?>
    <?php stylesheet_tag("bootstrap.css") ?>
    <?php stylesheet_tag("sh.css") ?>
    <?php include(__DIR__ . '/../../library/Tr8n/Includes/Scripts.php'); ?>
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            padding-bottom: 40px;
            background: white url(<?php echo url_for('/docs/assets/img/bg-pattern.png') ?>);
        }
    </style>
</head>

<body>