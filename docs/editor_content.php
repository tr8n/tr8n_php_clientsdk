<?php require_once(__DIR__ . '/../library/Tr8n.php'); ?>
<?php tr8n_init_client_sdk(); ?>

<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<?php
$path = dirname(__FILE__)."/../test/fixtures/html/examples";
$selected_sample = $_GET["sample"];
$selected_file_path = $path.'/'.$selected_sample.'.html';
$content = file_get_contents($selected_file_path);

$options = array();
$options["debug"] = ($_GET["debug_tml"] == 1);
$options["split_sentences"] = ($_GET["split"]==1);
$options["data_tokens.special"] = ($_GET["special_tokens"] == 1);
$options["data_tokens.numeric"] = ($_GET["numeric_tokens"] == 1);
?>

<script language="JavaScript" src="<?php echo \Tr8n\Config::instance()->application->jsBootUrl() ?>"></script>

<?php tr8n_begin_block_with_options(array("source" => "/examples/" . $selected_sample)) ?>

<?php echo trh($content, "", array(), $options) ?>

<?php tr8n_finish_block_with_options() ?>

<?php tr8n_complete_request() ?>