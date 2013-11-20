<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/tml_console")) ?>

    <h1 style="text-align:center"><?php tre("TML Interactive Console") ?></h1>


    <form action="<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/tml_content.php" method="get" id="tml_form" target="tml_translations">
        <input type="hidden" id="tml_label" name="tml_label" value="">
        <input type="hidden" id="tml_context" name="tml_context" value="">
        <input type="hidden" id="tml_tokens" name="tml_tokens" value="">
        <input type="hidden" id="tml_options" name="tml_options" value="">

        <div style="padding-top:15px;">
            <div style="font-size:12px;float:right;padding-top:15px;color:#888"><?php tre("The text that you would like to translate.")?></div>
            <h4><?php tre("Label (required, TML)") ?></h4>
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
</script>