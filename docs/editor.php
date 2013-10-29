<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/html_translator")) ?>

<h1 style="text-align:center">HTML &#8594; TML Converter and Translator</h1>
<br>

<?php
    $path = dirname(__FILE__)."/../test/fixtures/html/examples";

    $content = isset($_POST["content"]) ? $_POST["content"] : null;
    $selected_sample = null;

    $file_action = isset($_POST["file_action"]) ? $_POST["file_action"] : null;

    if ($file_action!=null && $file_action!="") {
        $selected_sample = $_POST["file_name"];
        $file_name = $path.'/'.$selected_sample.'.html';

        if ($file_action == "rename") {
            rename($path.'/'.$_POST["sample"].'.html', $file_name);
        } else if ($file_action == "save_as") {
            file_put_contents($file_name, $content);
        } else if ($file_action == "delete") {
            unlink($path.'/'.$_POST["sample"].'.html');
            $selected_sample = null;
        } else if ($file_action == "new") {
            $content = "";
            file_put_contents($file_name, "");
        }
    }

    $samples = array();
    foreach (scandir($path) as $filename) {
        if (strstr($filename, '.html') === false) continue;
        array_push($samples, str_replace(".html", "", $filename));
    }

    if ($selected_sample == null) {
        $selected_sample = (isset($_GET["sample"]) ? $_GET["sample"] : (isset($_POST["sample"]) ? $_POST["sample"] : null));
        if ($selected_sample == null) {
            $selected_sample = $samples[0];
            $selected_file_path = $path.'/'.$selected_sample.'.html';
        }
    }

    $selected_file_path = null;
    if ($selected_sample != null) {
        $selected_file_path = $path.'/'.$selected_sample.'.html';
    }

    if ($selected_file_path!=null) {
        if ($content == null) {
            $content = file_get_contents($selected_file_path);
        } else {
            file_put_contents($selected_file_path, $content);
        }
    }
?>

<form action="/tr8n/docs/editor.php" method="post" id="editor_form">

<div style="margin-top:20px;">
        <input type="hidden" id="file_action" name="file_action">
        <input type="hidden" id="file_name" name="file_name">

        <div>
            <div style="color:#888;float:right;padding-top:10px;"><?php echo $selected_file_path ?></div>
            <select id="sample" name="sample">
                <option value="">-- select --</option>
                <?php
                     foreach($samples as $name) { ?>
                        <option value="<?php echo $name ?>" <?php if ($selected_sample == $name) echo "selected"; ?>  ><?php echo $name ?></option>
                <?php } ?>
            </select>
        </div>

        <textarea id="content" name="content"><?php echo $content ?></textarea>

        <div style="padding-top:10px;">
            <div style="float:right">
                <button type="submit" class="btn btn-primary">
                    Save & Translate
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
            <div>
                <button type="button" class="btn" onClick="newSample()">
                    New Sample
                </button>
            </div>
        </div>
</div>

<hr>
<div style="text-align:center;font-size:50px;color:#ccc;padding-bottom:30px;">
    &#9660;
</div>


<h3>
    <div style="float:right">
        <span style="font-size:11px; padding:10px; background:#eee; border: 1px solid #ccc; vertical-align: middle; margin-right:20px;">
            <input type="checkbox" id="debug_tml" name="debug_tml" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["debug_tml"])) {echo "checked";} ?>> Debug TML
            &nbsp;&nbsp;
            <input type="checkbox" id="split" name="split" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["split"])) {echo "checked";} ?>> Split by sentence
            &nbsp;&nbsp;
            <input type="checkbox" id="special_tokens" name="special_tokens" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["special_tokens"])) {echo "checked";} ?>> Special char tokens
            &nbsp;&nbsp;
            <input type="checkbox" id="numeric_tokens" name="numeric_tokens" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["numeric_tokens"])) {echo "checked";} ?>> Numeric tokens
        </span>

        <button type="button" class="btn" onClick="reloadTranslations()">
            Update
        </button>

        <button type="button" class="btn" onClick="detachTranslations()">
            Detach
        </button>
    </div>
    Output and Translations
</h3>

</form>

<?php
    $params = array();
    $params["sample"] = $selected_sample;
    $params["debug_tml"] = isset($_POST["debug_tml"]);
    $params["split"] = isset($_POST["split"]);
    $params["special_tokens"] = isset($_POST["special_tokens"]);
    $params["numeric_tokens"] = isset($_POST["numeric_tokens"]);
?>
<iframe id="translations" src="/tr8n/docs/editor_content.php?<?php echo http_build_query($params) ?>" name="results" style="width:100%;height:500px;background:white;"></iframe>

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

    function newSample() {
        var new_name = prompt("What would you like to name the new sample?", sel);
        if (!new_name) return;
        var sel = $('#sample').find(":selected");
        sel.removeAttr("selected");
        $("#file_action").val('new');
        $("#file_name").val(new_name);
        $("#editor_form").submit();
    }

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

    function asParam(key) {
        return $('#' + key).is(':checked') ? "1" : "0";
    }

    function generateUrl() {
        var params = {};
        params["debug_tml"] = asParam('debug_tml');
        params["split"] = asParam('split');
        params["special_tokens"] = asParam('special_tokens');
        params["numeric_tokens"] = asParam('numeric_tokens');

        return "/tr8n/docs/editor_content.php?sample=<?php echo $selected_sample ?>&" + $.param(params);
    }

    function reloadTranslations() {
        document.getElementById("translations").contentDocument.location = generateUrl();
    }

    function detachTranslations() {
        var w = 800;
        var h = 600;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        return window.open(generateUrl(), "Tr8n Email Preview", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>

<?php tr8n_finish_block_with_options() ?>
<?php include('includes/footer.php'); ?>