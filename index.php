<?php include('docs/includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/home")) ?>

<style>
    h3.hr {
        background: url('<?php echo url_for("docs/assets/img/hr.png")?>') center center no-repeat;
        text-align: center;
    }
    h3 span {
        background:#F7F7F7;
        padding:5px 15px 5px 15px;
    }
</style>

<div class="hero-unit">
    <p class="text-center">
        <?php image_tag('tr8n_logo.png') ?>
    </p>
    <h2 class="text-center"><?php tre("Welcome to Tr8n For PHP") ?></h2>
    <p class="text-center">
        <?php tre("Tr8n for PHP is a Client SDK library that allows PHP based applications to integrate with the Tr8n crowd-sourced translation platform.") ?>
        <?php tre("This sample application demonstrates some of Tr8n's capabilities.") ?>
    </p>
    <br>
    <?php if (tr8n_current_translator() == null) { ?>
        <p class="text-center">
            <a href="<?php echo tr8n_signup_url() ?>" class="btn btn-large"><?php tre("Sign up to get started today") ?></a>
        </p>
    <?php } ?>
</div>

<h3 class="hr strong"><span><?php tre("How It Works") ?></span></h3>

<div class="row">
    <div class="span4">
        <p class="text-center"><?php image_tag("how_it_works_1.gif") ?></p>
        <h4 class="text-center"><?php tre("Use your users") ?></h4>
        <p>
            <?php tre("Your multilingual users can sign up to help translate your site into over {count||different languages}.", array("count" => 300)) ?>
        </p>
    </div>
    <div class="span4">
        <p class="text-center"><?php image_tag("how_it_works_2.gif") ?></p>
        <h4 class="text-center"><?php tre("Share translations") ?></h4>
        <p>
            <?php tre("Translations from your site get synced to Tr8n service and can then be shared with thousands of other websites.") ?>
        </p>
    </div>
    <div class="span4">
        <p class="text-center"><?php image_tag("how_it_works_3.gif") ?></p>
        <h4 class="text-center"><?php tre("Get what you give") ?></h4>
        <p><?php tre("The Tr8n service pulls down new translations from not only your users but users of other websites as you sync your translations.") ?></p>
    </div>
</div>

<h3 class="hr strong"><span><?php tre("Features") ?></span></h3>

<div class="row">
    <div class="span4">
        <h4><?php tre("Supports all languages") ?></h4>
        <p><?php tre("All browser supported language are included. Just choose which languages you want to enabled on your app.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Inline translation tools") ?></h4>
        <p><?php tre("Intuitive translation tools allow your users to easily translate your website.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Translation management") ?></h4>
        <p><?php tre("Keep track of all the translated text on your website, through an easy to use dashboard.") ?></p>
    </div>
</div>

<div class="row">
    <div class="span4">
        <h4><?php tre("Translation ranking") ?></h4>
        <p><?php tre("Translators vote on translations and the best translations are used in your application.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Translator ranking") ?></h4>
        <p><?php tre("Translators are ranked based on the quality of the work they do. The higher the rank the more valuable they become.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Powerful Metrics") ?></h4>
        <p><?php tre("Stay up to date on the progress of your translations.") ?></p>
    </div>
</div>

<div class="row">
    <div class="span4">
        <h4><?php tre("Translation Markup Language") ?></h4>
        <p><?php tre("TML syntax makes developers happy - it simplifies the code and allows to encode complex rules using simple structures.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Language Context Rules") ?></h4>
        <p><?php tre("Tr8n supports all language rules to make translations very accurate.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Language Cases") ?></h4>
        <p><?php tre("Language cases are supported across all languages to make translations sound as native as they can be.") ?></p>
    </div>
</div>

<div class="row">
    <div class="span4">
        <h4><?php tre("Glossary") ?></h4>
        <p><?php tre("Create a glossary of the key terms of your application. Translators will see those terms and will be able to provide more accurate translations.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Language Discussion Boards") ?></h4>
        <p><?php tre("Translators can discuss translations and terms in the message boards across all languages.") ?></p>
    </div>
    <div class="span4">
        <h4><?php tre("Admin Tools") ?></h4>
        <p><?php tre("Administration tools allow you to configure every aspect of your application.") ?></p>
    </div>
</div>


<?php tr8n_finish_block_with_options() ?>

<?php include('docs/includes/footer.php'); ?>