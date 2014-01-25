<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/tml_console")) ?>

    <h1 style="text-align:center"><?php tre("TML Interactive Console") ?></h1>

<?php
$examples = array(
    array("label" => "Hello World"),
    array("label" => "Invite", "description" => "An invitation"),
    array("label" => "Invite", "description" => "Action to invite someone"),
    array("label" => "Number of messages: {count}", "tokens" => array("count" => 5)),
    array("label" => "You have {count|| one: message, other: messages}", "tokens" => array("count" => 5)),
    array("label" => "You have {count|| message, messages}", "tokens" => array("count" => 5)),
    array("label" => "You have {count|| message}", "tokens" => array("count" => 5)),
    array("label" => "You have {count| message}", "tokens" => array("count" => 5)),
    array("label" => "Hello [bold: World]"),
    array("label" => "Hello [bold: {user}]", "tokens" => array("user" => "Michael")),
    array("label" => "Hello [bold: {user}], you have {count||message}.", "tokens" => array("user" => "Michael", "count" => 5)),
    array("label" => "Hello [bold: {user}], [italic: you have [bold: {count||message}]].", "tokens" => array("user" => "Michael", "count" => 1)),
    array("label" => "Hello [bold: {user}], [italic]you have [bold: {count||message}][/italic].", "tokens" => array("user" => "Michael", "count" => 3)),
    array("label" => "{user|He, She} likes this post.", "tokens" => array("user" => array("object" => array("gender" => "male", "name" => "Michael")))),
    array("label" => "{user|Dear} {user}", "tokens" => array("user" => array("object" => array("gender" => "male", "name" => "Michael"), "attribute" => "name"))),
    array("label" => "{users||likes, like} this post.", "tokens" => array("users" => array(array(array("gender" => "male", "name" => "Michael"), array("gender" => "female", "name" => "Anna")), array("attribute" => "name")))),
    array("label" => "{users||likes, like} this post.", "tokens" => array("users" => array(array(array("gender" => "female", "name" => "Anna")), array("attribute" => "name")))),
    array("label" => "{users|He likes, She likes, They like} this post.", "tokens" => array("users"=> array(array(array("gender"=> "male", "name"=>"Michael"), array("gender"=> "female", "name"=>"Anna")), array("attribute"=> "name")))),
    array("label" => "{users|He likes, She likes, They like} this post.", "tokens" => array("users"=> array(array(array("gender"=> "female", "name"=>"Anna")), array("attribute"=> "name")))),
    array("label" => "{users|He likes, She likes, They like} this post.", "tokens" => array("users"=> array(array(array("gender"=> "male", "name"=>"Michael")), array("attribute"=> "name"))))
)
?>

    <form action="<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/tml_content.php" method="get" id="tml_form" target="tml_translations">
        <input type="hidden" id="tml_label" name="tml_label" value="">
        <input type="hidden" id="tml_context" name="tml_context" value="">
        <input type="hidden" id="tml_tokens" name="tml_tokens" value="">
        <input type="hidden" id="tml_options" name="tml_options" value="">

        <div style="padding-top:15px;">
            <div style="font-size:12px;float:right;padding-top:15px;color:#888"><?php tre("The text that you would like to translate.")?></div>
            <h4 style="display:inline-block;"><?php tre("Label (required, TML)") ?></h4>
            <div class="dropdown" style="display:inline-block; padding-left:10px;">
                <a id="samples_menu_trigger" href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <?php tre("try some examples") ?><b class="caret"></b>
                </a>
                <ul class="dropdown-menu pas" style="max-height:500px;overflow:auto;">
                    <?php $index = 1 ?>
                    <?php foreach($examples as $sample) { ?>
                        <li style="font-size:13px;">
                            <a href="javascript: loadExample(<?php echo $index-1 ?>)">
                                <?php echo ($index++) ?>)
                                <strong><?php echo $sample["label"] ?></strong>

                                <?php if (isset($sample['description'])) { ?>
                                    <div style="font-size:10px;padding-left:15px;">Context: <?php echo $sample['description'] ?></div>
                                <?php } ?>

                                <?php if (isset($sample['tokens'])) { ?>
                                    <div style="font-size:10px;padding-left:15px;">Tokens: <?php echo json_encode($sample['tokens']) ?></div>
                                <?php } ?>

                                <?php if (isset($sample['options'])) { ?>
                                    <div style="font-size:10px;padding-left:15px;">Options: <?php echo json_encode($sample['options']) ?></div>
                                <?php } ?>
                            </a>
                        </li>
                        <li class="divider"></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="ace_editor" id="tml_label_editor" style="height:80px;"></div>
        </div>

        <div style="padding-top:15px;">
            <div style="font-size:12px;float:right;padding-top:15px;color:#888"><?php tre("If label is ambiguous, context provides a hint to translators as well as a unique key for the label.")?></div>
            <h4><?php tre("Context (optional, plain text)") ?></h4>
            <div class="ace_editor" id="tml_context_editor" style="height:50px;"></div>
        </div>

        <table style="width:100%">
            <tr>
                <td style="width:50%">
                    <div style="font-size:12px;float:right;padding-top:15px;color:#888"><?php tre("Dynamic data to be substituted")?></div>
                    <h4><?php tre("Tokens (optional, JSON)") ?></h4>
                    <div class="ace_editor" id="tml_tokens_editor" style="height:100px;">{}</div>
                </td>
                <td>&nbsp;</td>
                <td style="width:50%">
                    <div style="font-size:12px;float:right;padding-top:15px;color:#888"><?php tre("Translation options")?></div>
                    <h4><?php tre("Options (optional, JSON)") ?></h4>
                    <div class="ace_editor" id="tml_options_editor" style="height:100px;">{}</div>
                </td>
            </tr>
        </table>

        <div style="padding-top:10px;">
            <div style="float:right">
            </div>
            <div>
                <button type="button" class="btn btn-primary" onClick="submitTml()">
                    <?php tre("Translate") ?>
                </button>
                <button type="button" class="btn" onClick="newSample()">
                    <?php tre("Clear") ?>
                </button>
            </div>
        </div>
    </form>

    <hr>
    <div style="text-align:center;font-size:50px;color:#ccc;padding-bottom:30px;">
        &#9660;
    </div>

    <iframe id="tml_translations" name="tml_translations" src="<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/tml_content.php" style="width:100%;height:600px;background:white;border:1px solid #eee;"></iframe>

<?php tr8n_finish_block_with_options() ?>
<?php include('includes/footer.php'); ?>

<?php javascript_tag('ace/ace.js') ?>
<?php javascript_tag('ace/theme-chrome.js') ?>
<?php javascript_tag('ace/mode-html.js') ?>
<?php javascript_tag('ace/mode-json.js') ?>

<style type="text/css" media="screen">
    .ace_editor {
        position: relative;
        top: 0;
        left: 0;
        width:100%;
        height:50px;
        border:1px solid #eee;
    }
</style>

<script>
    var label_editor = ace.edit("tml_label_editor");
    label_editor.setTheme("ace/theme/chrome");
    label_editor.getSession().setMode("ace/mode/text");

    var context_editor = ace.edit("tml_context_editor");
    context_editor.setTheme("ace/theme/chrome");
    context_editor.getSession().setMode("ace/mode/text");

    var tokens_editor = ace.edit("tml_tokens_editor");
    tokens_editor.setTheme("ace/theme/chrome");
    tokens_editor.getSession().setMode("ace/mode/json");

    var options_editor = ace.edit("tml_options_editor");
    options_editor.setTheme("ace/theme/chrome");
    options_editor.getSession().setMode("ace/mode/json");

    function submitTml() {
        $("#tml_label").val(label_editor.getValue());
        $("#tml_context").val(context_editor.getValue());
        $("#tml_tokens").val(tokens_editor.getValue());
        $("#tml_options").val(options_editor.getValue());
        $("#tml_form").submit();
    }

    function newSample() {
        location.reload();
    }

    var examples = <?php echo json_encode($examples) ?>;
    function loadExample(index) {
//    alert("Loading: " + index);
        label_editor.setValue(examples[index].label);

        if (examples[index].description)
            context_editor.setValue(examples[index].description);
        else
            context_editor.setValue("");

        if (examples[index].tokens)
            tokens_editor.setValue(JSON.stringify(examples[index].tokens));
        else
            tokens_editor.setValue("{}");

        if (examples[index].options)
            options_editor.setValue(JSON.stringify(examples[index].options));
        else
            options_editor.setValue("{}");

        submitTml();
    }
</script>