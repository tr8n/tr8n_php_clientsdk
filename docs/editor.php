<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/html_translator")) ?>

<h1 style="text-align:center"><?php tre("Blog Translator") ?></h1>
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

    $editors = array("ckeditor", "tinymce", 'yui', 'nicedit');
    $selected_editor = (isset($_GET["editor"]) ? $_GET["editor"] : (isset($_POST["editor"]) ? $_POST["editor"] : "ckeditor"));
?>

<form action="/tr8n/docs/editor.php" method="post" id="editor_form">

<div style="margin-top:20px;" class="yui-skin-sam">
        <input type="hidden" id="file_action" name="file_action">
        <input type="hidden" id="file_name" name="file_name">

        <div style="float:right">
            <select id="editor" name="editor" style="width:130px;">
                <?php
                foreach($editors as $edt) { ?>
                    <option value="<?php echo $edt ?>" <?php if ($selected_editor == $edt) echo "selected"; ?>  ><?php echo $edt ?></option>
                <?php } ?>
            </select>

            <select id="sample" name="sample">
                <option value="">-- select --</option>
                <?php
                     foreach($samples as $name) { ?>
                        <option value="<?php echo $name ?>" <?php if ($selected_sample == $name) echo "selected"; ?>  ><?php echo $name ?></option>
                <?php } ?>
            </select>
        </div>
        <div style="padding-bottom:0px;">
            <h3><?php tre("Enter A Blog Post") ?></h3>
        </div>

        <div>
            <textarea id="content" name="content" style="width:100%; height:400px;"><?php echo $content ?></textarea>
        </div>

        <?php if (\Tr8n\Config::instance()->current_translator) { ?>
            <div style="padding-top:10px;">
                <div style="float:right">
                    <button type="button" class="btn" onClick="newSample()">
                        <?php tre("New Sample") ?>
                    </button>
                    <button type="button" class="btn btn-warning" onClick="renameSample()">
                        <?php tre("Rename") ?>
                    </button>
                    <button type="button" class="btn btn-danger" onClick="deleteSample()">
                        <?php tre("Delete") ?>
                    </button>
                    <button type="button" class="btn btn-success" onClick="saveAsNewSample()">
                        <?php tre("Save As...") ?>
                    </button>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <?php tre("Save & Translate") ?>
                    </button>
                </div>
            </div>
        <?php } ?>
</div>

<hr>
<div style="text-align:center;font-size:50px;color:#ccc;padding-bottom:30px;">
    &#9660;
</div>


<h3>
    <div style="float:right">
        <span style="font-size:11px; padding:10px; background:#eee; border: 1px solid #ccc; vertical-align: middle; margin-right:20px;">
            <input type="checkbox" id="debug_tml" name="debug_tml" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["debug_tml"])) {echo "checked";} ?>> <?php tre("Debug TML") ?>
            &nbsp;&nbsp;
            <input type="checkbox" id="split" name="split" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["split"])) {echo "checked";} ?>> <?php tre("Split by sentence") ?>
            &nbsp;&nbsp;
            <input type="checkbox" id="special_tokens" name="special_tokens" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["special_tokens"])) {echo "checked";} ?>> <?php tre("Special char tokens") ?>
            &nbsp;&nbsp;
            <input type="checkbox" id="numeric_tokens" name="numeric_tokens" style="vertical-align:middle;margin:0px;" <?php if (isset($_POST["numeric_tokens"])) {echo "checked";} ?>> <?php tre("Numeric tokens") ?>
        </span>

        <button type="button" class="btn" onClick="reloadTranslations()">
            <?php tre("Update") ?>
        </button>

        <button type="button" class="btn" onClick="detachTranslations()">
            <?php tre("Detach") ?>
        </button>
    </div>
    <?php tre("Output and Translations") ?>
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
<iframe id="translations" src="<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/editor_content.php?<?php echo http_build_query($params) ?>" name="results" style="width:100%;height:500px;background:white;border:1px solid #eee;"></iframe>

<?php if ($selected_editor == 'ckeditor') { ?>
    <?php javascript_tag('../editors/ckeditor/ckeditor.js') ?>
    <?php javascript_tag('../editors/ckeditor/adapters/jquery.js') ?>
    <script type="text/javascript">
        $( document ).ready( function() {
            $('textarea#content').ckeditor();

        } );
    </script>

<?php } else if ($selected_editor == 'tinymce') { ?>

    <?php javascript_tag('../editors/tinymce/tinymce.min.js') ?>
    <script type="text/javascript">
        tinymce.init({
            selector: "textarea#content",
            theme: "modern",
            height: 400,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ]
        });
    </script>

<?php } else if ($selected_editor == 'yui') { ?>
    <!-- Skin CSS file -->
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/assets/skins/sam/skin.css">
    <!-- Utility Dependencies -->
    <script src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
    <script src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>
    <!-- Needed for Menus, Buttons and Overlays used in the Toolbar -->
    <script src="http://yui.yahooapis.com/2.9.0/build/container/container_core-min.js"></script>
    <script src="http://yui.yahooapis.com/2.9.0/build/menu/menu-min.js"></script>
    <script src="http://yui.yahooapis.com/2.9.0/build/button/button-min.js"></script>
    <!-- Source file for Rich Text Editor-->
    <script src="http://yui.yahooapis.com/2.9.0/build/editor/editor-min.js"></script>

    <script type="text/javascript">
        var myEditor = new YAHOO.widget.Editor('content', {
            height: '400px',
            width: '100%',
            dompath: true, //Turns on the bar at the bottom
            animate: true //Animates the opening, closing and moving of Editor windows
        });
        myEditor.render();
    </script>

<?php } else if ($selected_editor == 'nicedit') { ?>

    <script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(nicEditors.allTextAreas);
    </script>

<?php } ?>

<script>
    $( document ).ready( function() {
        $("#sample").on("change", function() {
            updateSelection();
        });
        $("#editor").on("change", function() {
            updateSelection();
        });
    });

    function updateSelection() {
        var edt = $('#editor').find(":selected").val();
        var sel = $('#sample').find(":selected").val();
        location.href = "<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/editor.php?editor=" +  edt + "&sample=" + sel;
    }

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

        return "<?php echo \Tr8n\Config::instance()->configValue("local.base_path") ?>/docs/editor_content.php?sample=<?php echo $selected_sample ?>&" + $.param(params);
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