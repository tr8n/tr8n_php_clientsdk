<?php include('docs/includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/home")) ?>

<style>
    h3.hr {
        background: url('<?php echo url_for("docs/assets/img/hr.png")?>') center center no-repeat;
        text-align: center;
    }
    h3 span {
        background:#fff;
        padding:20px;
    }
</style>

<div class="hero-unit">
    <p class="text-center">
        <?php image_tag('tr8n_logo.png') ?>
    </p>
    <h2 class="text-center"><?php tr("Welcome to Tr8n For PHP") ?></h2>
    <p class="text-center">
        <?php tr("Tr8n for PHP is a Client SDK library that allows PHP based applications to integrate with the Tr8n crowdsourced translation platform.") ?>
        <?php tr("This sample application demonstrates some of Tr8n's capabilities.") ?>
    </p>
    <br>
    <?php if (tr8n_current_translator() == null) { ?>
        <p class="text-center">
            <a href="<?php echo tr8n_signup_url() ?>" class="btn btn-large"><?php tr("Sign up to get started today") ?></a>
        </p>
    <?php } ?>
</div>

<h3 class="hr strong"><span><?php tr("How It Works") ?></span></h3>

<div class="row">
    <div class="span4">
        <h4><?php tr("Use your users") ?></h4>
        <p>Your multilngual users can sign up to help translate your site into over 270 different languages. Their translations can then be viewed and voted on by other users.</p>
    </div>
    <div class="span4">
        <h4><?php tr("Share translations") ?></h4>
        <p>Translations from your site get synced to our servers and can then be shared with thousands of other websites.</p>
    </div>
    <div class="span4">
        <h4><?php tr("Get what you give") ?></h4>
        <p>Regularly pull down new translations from not only your users but users of other websites as you sync your translations.</p>
    </div>
</div>

<h3 class="hr strong"><span><?php tr("Features") ?></span></h3>

<div class="row">
    <div class="span4">
        <h4>Support for over 250 languages</h4>
        <p>Every browser supported language is available at Tr8nhub. Just choose which languages you want to enabled on your app.</p>
    </div>
    <div class="span4">
        <h4>Translation management</h4>
        <p>Keep track of all the translated text on your website, through an easy to use dashboard.</p>
    </div>
    <div class="span4">
        <h4>Powerful Metrics</h4>
        <p>Stay up to date on all the important numbers you want to see.</p>
    </div>
</div>

<div class="row">
    <div class="span4">
        <h4>Inline translation tools</h4>
        <p>Intuitive translation tools allow your users to easily translate your website.</p>
    </div>
    <div class="span4">
        <h4>Translation management</h4>
        <p>Keep track of all the translated text on your website, through an easy to use dashboard.</p>
    </div>
    <div class="span4">
        <h4>Powerful Metrics</h4>
        <p>Stay up to date on all the important numbers you want to see.</p>
    </div>
</div>


<hr>

<footer>
    <p>&copy; Tr8nHub 2013</p>
</footer>

<?php tr8n_finish_block_with_options() ?>

<?php include('docs/includes/footer.php'); ?>