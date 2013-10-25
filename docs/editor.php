<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/html_translator")) ?>

<h1>HTML To TML Converter and Translator</h1>

<?php

    $path = dirname(__FILE__)."/../test/fixtures/html/examples";

    $content = isset($_POST["content"]) ? $_POST["content"] : null;

    $file_name = isset($_POST["file_name"]) ? $_POST["file_name"] : null;
    if ($file_name) {
        $file_name = $file_name.'.html';
        $new_file_path = $path.'/'.$file_name;
        file_put_contents($new_file_path, $content);
    }

    $samples = array();

    $selected_sample = 0;
    if (isset($_GET["sample"])) {
        $selected_sample = intval($_GET["sample"]);
    }

    $selected_file_path = "";
    $i = 0;
    foreach (scandir($path) as $filename) {
        if (strstr($filename, '.html') === false) continue;
        if ($i == $selected_sample) $selected_file_path = $path.'/'.$filename;
        array_push($samples, $filename);
        $i++;
    }

    if ($content == null) {
        $content = file_get_contents($selected_file_path);
    } else {
//        file_put_contents($selected_file_path, $content);
    }

    $options = array();
    $options["debug"] = isset($_POST["debug"]);
    $options["split_sentences"] = isset($_POST["split"]);
    $options["data_tokens.special"] = isset($_POST["special_tokens"]);
    $options["data_tokens.numeric"] = isset($_POST["numeric_tokens"]);

?>

<h3>Input Text</h3>

<div style="margin-top:20px;">
    <form method="post" id="editor_form">
        <input type="hidden" id="file_name" name="file_name">

        <div>
            <div style="color:#888;float:right;padding-top:10px;"><?php echo $selected_file_path ?></div>
            <span style="font-size:18px; padding-right:5px;">HTML Samples:</span>
            <select id="sample" name="sample">
                <?php
                     $i = 0;
                     foreach($samples as $name) { ?>
                        <option value="<?php echo $i ?>" <?php if ($selected_sample == $i) echo "selected"; ?>  ><?php echo $name ?></option>
                        <?php $i++; ?>
                <?php } ?>
            </select>

            <script>
                $("#sample").on("change", function() {
                    var sel = $('#sample').find(":selected").val();
                    if (sel == "") return;
                    location.href = "/tr8n/docs/editor?sample=" + sel;
                });

                function saveAsNewSample() {
                    var file_name = prompt("What would you like to call the new sample?");
                    if (!file_name) return;
                    $("#file_name").val(file_name);
                    $("#editor_form").submit();
                }
            </script>
        </div>

        <textarea id="content" name="content"><?php echo $content ?></textarea>

        <div style="padding-top:30px;">
            <div style="float:right">
                <button type="submit" class="btn btn-primary">
                    Update & Translate
                </button>
                <button type="button" class="btn" onClick="saveAsNewSample()">
                    Save As...
                </button>
            </div>

            <span style="padding:10px; background:#eee; border: 1px solid #ccc; vertical-align: middle">
                Options:
                &nbsp;&nbsp;
                <input type="checkbox" name="debug" style="vertical-align:top" <?php if (isset($_POST["debug"])) {echo "checked";} ?>> Debug TML
                &nbsp;&nbsp;
                <input type="checkbox" name="split" style="vertical-align:top" <?php if (isset($_POST["split"])) {echo "checked";} ?>> Split by sentence
                &nbsp;&nbsp;
                <input type="checkbox" name="special_tokens" style="vertical-align:top" <?php if (isset($_POST["special_tokens"])) {echo "checked";} ?>> Special char tokens
                &nbsp;&nbsp;
                <input type="checkbox" name="numeric_tokens" style="vertical-align:top" <?php if (isset($_POST["numeric_tokens"])) {echo "checked";} ?>> Numeric tokens
            </span>
        </div>
    </form>
</div>

<hr>

<h3>Output and Translations</h3>

<div style="padding:10px; background:white; border: 1px solid #ccc;">
    <?php echo trh(html_entity_decode($content), "", array(), $options) ?>
</div>

<?php javascript_tag('../ckeditor/ckeditor.js') ?>
<?php javascript_tag('../ckeditor/adapters/jquery.js') ?>

<script>
    $( document ).ready( function() {
        var editor = $('textarea#content').ckeditor();
    } );
</script>

<?php tr8n_finish_block_with_options() ?>
<?php include('includes/footer.php'); ?>