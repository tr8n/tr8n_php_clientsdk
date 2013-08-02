<?php require_once(__DIR__ . '/../../library/Tr8n.php'); ?>
<!-- ?php tr8n_init_client_sdk("http://sandbox.tr8nhub.com", "0c1eb03d6c6e12cb2", "5ff3d87a83c13fcdb"); ? -->
<?php tr8n_init_client_sdk("http://localhost:3000", "default", "e6ee64803c7b1cf51"); ?>

<?php include('helpers.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>" lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>">
<head>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php javascript_tag("jquery191.js") ?>
    <?php javascript_tag("bootstrap.js") ?>
    <?php javascript_tag("sh.js") ?>
    <?php stylesheet_tag("bootstrap.css") ?>
    <?php stylesheet_tag("sh.css") ?>
    <?php include(__DIR__ . '/../../library/Tr8n/Includes/Scripts.php'); ?>
    <style>
        body {
            padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
            padding-bottom: 40px;
            background: white url(<?php echo url_for('/docs/assets/img/bg-pattern.png') ?>);
        }
    </style>
</head>

<body>
<div class="navbar navbar-fixed-top">
    <?php tr8n_begin_block_with_options(array("source" => "/header/menu")) ?>
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <?php link_to(tr("Tr8n For PHP"), "index.php", array("class" => "brand")) ?>

            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li <?php active_link("index.php")?>><?php link_to(tr("Home"), "index.php") ?></li>
                    <li <?php active_link("docs/installation.php")?>><?php link_to(tr("Installation Instructions"), "docs/installation.php") ?></li>
                    <li <?php active_link("docs/", "docs/installation.php")?>><?php link_to(tr("Documentation"), "docs/introduction.php") ?></li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onClick="Tr8n.UI.LanguageSelector.show(true)"><?php tr8n_language_name_tag(tr8n_current_language(), array("flag" => true)) ?></a></li>

                    <?php if (tr8n_current_translator() == null) { ?>
                        <li role="presentation"><?php tr8n_link_to('login', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                    <?php } else { ?>
                        <li class="dropdown">
                            <a href="#" id="drop3" role="button" class="dropdown-toggle" data-toggle="dropdown">
                                <?php if (tr8n_current_translator()->email == null) { ?>
                                    <?php image_tag('silhouette.gif', array("class" => "img-polaroid", "style" => "width:10px;height:10px;border:1px solid #eee")) ?>
                                <?php } else { ?>
                                    <img src="<?php echo tr8n_current_translator()->mugshot() ?>" style="width:10px;height:10px;border:1px solid #eee" class="img-polaroid">
                                <?php } ?>
                                <?php echo tr8n_current_translator()->name ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="drop1">
                                <li role="presentation" class="text-center">
                                    <?php if (tr8n_current_translator()->email == null) { ?>
                                        <?php image_tag('silhouette.gif', array("class" => "img-circle", "style" => "width:80px;height:80px;border:1px solid #eee")) ?>
                                    <?php } else { ?>
                                        <img src="<?php echo tr8n_current_translator()->mugshot() ?>" style="width:80px;height:80px;border:1px solid #eee" class="img-circle">
                                    <?php } ?>
                                </li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><?php tr8n_link_to('dashboard', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('notifications_popup', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('toggle_inline', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><?php tr8n_link_to('help', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('shortcuts_popup', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><?php tr8n_link_to('logout', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
    <?php tr8n_finish_block_with_options() ?>
</div>

<div class="container">

