<?php require_once(__DIR__ . '/../library/Tr8n.php'); ?>
<?php tr8n_init_client_sdk(); ?>

<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />

<?php

$label = isset($_REQUEST["tml_label"]) ? $_REQUEST["tml_label"] : "";
$context = isset($_REQUEST["tml_context"]) ? $_REQUEST["tml_context"] : "";

$tokens = isset($_REQUEST["tml_tokens"]) ? $_REQUEST["tml_tokens"] : "{}";
$tokens = json_decode($tokens, true);

$options = isset($_REQUEST["tml_options"]) ? $_REQUEST["tml_options"] : "{}";
$options = json_decode($options, true);

?>

<script language="JavaScript" src="<?php echo \Tr8n\Config::instance()->application->jsBootUrl() ?>"></script>

<?php tr8n_begin_block_with_options(array("source" => "/examples/interactive_tml")) ?>

<div style="padding:15px;">
<?php tre($label, $context, $tokens, $options) ?>
</div>

<?php tr8n_finish_block_with_options() ?>

<?php tr8n_complete_request() ?>