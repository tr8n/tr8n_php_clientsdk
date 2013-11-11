<?php include('head.php'); ?>

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
                    <li <?php active_link("docs/installation.php", "docs/index.php")?>><?php link_to(tr("Installation Instructions"), "docs/installation.php") ?></li>
                    <li <?php active_link("docs/index.php")?>><?php link_to(tr("Documentation & Samples"), "docs/index.php") ?></li>
                    <li <?php active_link("docs/tml.php")?>><?php link_to(tr("TML Interactive Console"), "docs/tml.php") ?></li>
                    <li <?php active_link("docs/editor.php")?>><?php link_to(tr("Blog Translator"), "docs/editor.php") ?></li>
                </ul>
                <ul class="nav navbar-nav pull-right">
                    <li role="presentation"><a role="menuitem" tabindex="-1" href="#" onClick="Tr8n.UI.LanguageSelector.show()"><?php tr8n_language_name_tag(tr8n_current_language(), array("flag" => true)) ?></a></li>

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
                                <li role="presentation"><?php tr8n_link_to('notifications_popup', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('toggle_inline', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><?php tr8n_link_to('app_phrases', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('app_translations', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('app_translators', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation"><?php tr8n_link_to('app_settings', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <li role="presentation" class="divider"></li>
                                <li role="presentation"><?php tr8n_link_to('shortcuts_popup', null , array("role" => "menuitem", "tabindex" => "-1")) ?></li>
                                <?php if (\Tr8n\Config::instance()->isCacheEnabled() && !\Tr8n\Cache::isReadOnly()) { ?>
                                    <li role="presentation"><?php link_to("Reset Cache (v" . \Tr8n\Config::instance()->cacheVersion() . ")", "docs/reset_cache.php") ?></li>
                                <?php } ?>
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

