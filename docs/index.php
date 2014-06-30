<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/installation")) ?>

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
            <?php include('_tml.php'); ?>



            <?php include('_html_to_tml.php'); ?>

            <?php include('_context_rules.php'); ?>










            <h1><?php tre("Language Cases") ?></h1>
            <h2><?php tre("Possessive") ?></h2>
            <pre><code class="language-php">tre("This is {user::pos} photo", array("user" => $male))</code></pre>
            <div class="example">
                <div class="title"><?php tre("example") ?></div>
                <div class="content">
                    <?php tre("This is {user::pos} photo", array("user" => $male)) ?>
                </div>
            </div>

            <h1><?php tre("Caching") ?></h1>
            <?php trhe("
                <p>
                    Since pages may contain numerous translation keys, it is crucial that Tr8n is backed by a caching mechanism.
                    The caching mechanism provides a local cache of the Tr8n objects retrieved from the service. When users view the pages in non-translation mode, the translations will be served from the cache.
                    For translators, who enable inline translation mode, the SDK will always request the Tr8n service to get the most recent translations.
                </p>
                <p>
                    Tr8n supports 4 types of Cache adapters:
                    <ul>
                        <li>File based</li>
                        <li>APC</li>
                        <li>Memcache</li>
                        <li>CHDB</li>
                    </ul>
                </p>
                <p>
                    To change cache settings, modify config/config.json file.
                </p>
            ") ?>
            <pre><code class="language-javascript">"cache": {
    "enabled": true,
    "adapter": "memcache",
    "version": 1,
    "timeout": 3600
}</code></pre>

            <h2><?php tre("File Based Caching") ?></h2>
            <p><?php tre("This is a readonly cache that must be externally generated to take effect.") ?></p>
            <p>
                <?php tre("To generate the cache files, run the following script:") ?>
            </p>
            <pre><code class="language-bash">$ bin/generate_files</code></pre>
            <p>
                <?php tre("The files will be stored in the cache/files folder.") ?>
            </p>

            <h2><?php tre("CHDB") ?></h2>
            <p><?php tre("This is a readonly cache that must be externally generated to take effect.") ?></p>
            <p>
                <?php tre("To generate the cache files, run the following script:") ?>
            </p>
            <pre><code class="language-bash">$ bin/generate_chdb</code></pre>
            <p>
                <?php tre("The files will be stored in the cache/chdb folder.") ?>
            </p>

            <h2><?php tre("APC") ?></h2>
            <p>
               <?php tre("APC is a self-building cache that lazily warms up by retrieving data from the Tr8n service and storing it in the APC store.") ?>
            </p>
            <h2><?php tre("Memcache") ?></h2>
            <p>
                <?php tre("Memcache is a self-building cache that lazily warms up by retrieving data from the Tr8n service and storing it in the APC store.") ?>
            </p>
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

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>