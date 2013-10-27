<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/html_translator")) ?>

<h1>HTML To TML Converter and Translator</h1>

<?php
    $path = dirname(__FILE__)."/../test/fixtures/html/examples";

    $content = isset($_POST["content"]) ? $_POST["content"] : null;
    $selected_sample = null;

    $file_action = isset($_POST["file_action"]) ? $_POST["file_action"] : null;

    if ($file_action !=null && $file_action != "") {
        $selected_sample = $_POST["file_name"];
        $file_name = $path.'/'.$selected_sample.'.html';

        if ($file_action == "rename") {
            rename($path.'/'.$_POST["sample"].'.html', $file_name);
        } else if ($file_action == "save_as") {
            file_put_contents($file_name, $content);
        } else if ($file_action == "delete") {
            unlink($path.'/'.$_POST["sample"].'.html');
            $selected_sample = null;
        }
    }

    $samples = array();
    $selected_file_path = "";
    foreach (scandir($path) as $filename) {
        if (strstr($filename, '.html') === false) continue;
        array_push($samples, str_replace(".html", "", $filename));
    }

    if ($selected_sample == null) {
        $selected_sample = (isset($_GET["sample"]) ? $_GET["sample"] : (isset($_POST["sample"]) ? $_POST["sample"] : null));
        if ($selected_sample == null)
            $selected_sample = $samples[0];
    }

    $selected_file_path = $path.'/'.$selected_sample.'.html';

    if ($content == null) {
        $content = file_get_contents($selected_file_path);
    } else {
        file_put_contents($selected_file_path, $content);
    }

    $options = array();
    $options["debug"] = isset($_POST["debug"]);
    $options["split_sentences"] = isset($_POST["split"]);
    $options["data_tokens.special"] = isset($_POST["special_tokens"]);
    $options["data_tokens.numeric"] = isset($_POST["numeric_tokens"]);

?>

<h3>Input Text</h3>

<div style="margin-top:20px;">
    <form action="/tr8n/docs/editor.php" method="post" id="editor_form">
        <input type="hidden" id="file_action" name="file_action">
        <input type="hidden" id="file_name" name="file_name">

        <div>
            <div style="color:#888;float:right;padding-top:10px;"><?php echo $selected_file_path ?></div>
            <span style="font-size:18px; padding-right:5px;">HTML Samples:</span>
            <select id="sample" name="sample">
                <?php
                     foreach($samples as $name) { ?>
                        <option value="<?php echo $name ?>" <?php if ($selected_sample == $name) echo "selected"; ?>  ><?php echo $name ?></option>
                <?php } ?>
            </select>
        </div>

        <textarea id="content" name="content"><?php echo $content ?></textarea>

        <div style="padding-top:30px;">
            <div style="float:right">
                <button type="submit" class="btn btn-primary">
                    Update & Translate
                </button>
                <button type="button" class="btn btn-warning" onClick="renameSample()">
                    Rename
                </button>
                <button type="button" class="btn btn-danger" onClick="deleteSample()">
                    Delete
                </button>
                <button type="button" class="btn btn-success" onClick="saveAsNewSample()">
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
    <?php echo trh($content, "", array(), $options) ?>
</div>

<?php javascript_tag('../ckeditor/ckeditor.js') ?>
<?php javascript_tag('../ckeditor/adapters/jquery.js') ?>

<script>
    $( document ).ready( function() {
        var editor = $('textarea#content').ckeditor();

        $("#sample").on("change", function() {
            var sel = $('#sample').find(":selected").val();
            location.href = "/tr8n/docs/editor.php?sample=" + sel;
        });

    } );

    function renameSample() {
        var sel = $('#sample').find(":selected").val()
        var rename_to = prompt("What would you like to call the new sample?", sel);
        if (!rename_to) return;
        $("#file_action").val('rename');
        $("#file_name").val(rename_to);
        $("#editor_form").submit();
    }

    function saveAsNewSample() {
        var save_as = prompt("What would you like to call the new sample?");
        if (!save_as) return;
        $("#file_action").val('save_as');
        $("#file_name").val(save_as);
        $("#editor_form").submit();
    }

    function deleteSample() {
        if (!confirm("Are you sure you want to delete this sample?")) return;
        $("#file_action").val('delete');
        $("#editor_form").submit();
    }

</script>

<?php tr8n_finish_block_with_options() ?>
<?php include('includes/footer.php'); ?>