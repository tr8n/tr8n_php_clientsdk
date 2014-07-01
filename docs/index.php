<?php include('includes/header.php'); ?>

<div style="background:white;padding-top:10px;padding-bottom:600px;margin-bottom:20px; border-radius:10px;">
    <div class="row">
        <div class="span3" style="">
            <div id="toc"></div>
        </div>
        <div class="span9">
            <div class="hero-unit" style="margin-right:10px;">
                <div class="text-center">
                    <?php image_tag("tr8n_logo.png") ?>
                </div>
                <h2 class="text-center"><?php tre("Tr8n Documentation & Samples") ?></h2>
            </div>

            <?php include('_introduction.php'); ?>
            <?php include('_installation.php'); ?>
            <?php include('_integration.php'); ?>
            <?php include('tml/_index.php'); ?>

            <?php include('_html_to_tml.php'); ?>

            <?php include('context_rules/_index.php'); ?>

            <?php include('language_cases/_index.php'); ?>

            <?php include('caching/_index.php'); ?>

        </div>

    </div>
</div>

<?php stylesheet_tag('jquery.tocify.css') ?>
<?php javascript_tag('jquery-ui-1.10.3.custom.min.js') ?>
<?php javascript_tag('jquery.tocify.min.js') ?>

<?php stylesheet_tag('prism.css') ?>
<?php javascript_tag('prism.js') ?>

<style>
    p {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    pre {
        margin-bottom: 15px !important;
        background-color: #f4f8f9 !important;
        border: 1px solid #eee;
    }

    pre[class*='language-'] > code[data-language]::before {
        background-color: #eee !important;
        font-size: 10px;
        padding: 3px;
    }

    .example {
        background: #f8f8f8;
        /*border: 1px solid #ccc;*/
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .example pre {
        margin: 10px;
    }
    .example .title {
        color: black;
        width: 50px;
        text-align:center;
        font-size: 11px;
        padding:3px;
        background: #eee;
        border-bottom: 1px solid #eee;
        border-right: 1px solid #eee;
        border-top-left-radius:5px;
        border-bottom-right-radius:5px;
    }

    .example .content {
        padding-left:20px;
        font-size:12px;
        padding-bottom: 10px;
    }

    h4 {
        margin-top: 35px;
    }

    #toc {
        width:240px;
    }
</style>

<script>
    $(function() {
        $("#toc").tocify({
            "selectors": "h1,h2,h3"
        });
    });
</script>

<?php include('includes/footer.php'); ?>