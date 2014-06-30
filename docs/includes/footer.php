
    <hr>

    <style>
        #navlist li {
            display: inline;
            list-style-type: none;
            padding-right: 10px;
        }
    </style>

    <footer>
        <div class="container">
            <ul id="navlist" style="float:right;">
                <li><a href="http://wiki.tr8n.io" class="quiet"><?php tre("Documentation") ?></a></li>

                <li><a href="http://github.com/tr8n" class="quiet"><?php tre("Source") ?></a></li>
                <li><a href="http://blog.tr8n.io" class="quiet"><?php tre("Blog") ?></a></li>

                <li><a href="https://www.facebook.com/translationexchange" class="quiet"><?php tre("Facebook") ?></a></li>
                <li><a href="http://twitter.com/translationx" class="quiet"><?php tre("Twitter") ?></a></li>
                <li><a href="http://www.linkedin.com/" class="quiet"><?php tre("LinkedIn") ?></a></li>

                <!-- li><a href="/extensions" class="quiet"><span class='tr8n_translatable tr8n_not_translated' data-translation_key_id='22'>Extensions</span></a></li -->
            </ul>

            &copy; <a href="https://translationexchange.com" class="quiet">TranslationExchange.com</a> 2014
        </div>
    </footer>

    <div style="padding-top:40px; color: #ccc; text-align:center; width:100%">
        Powered by <a href="http://wiki.tr8n.io" style="color:#ccc;">Tr8n</a>
        <div style="padding-top:5px;">
            <a href="http://wiki.tr8nhub.com"><?php image_tag('tr8n_logo.png', array("style" => "width:50px;")) ?></a>
        </div>
    </div>

</div>

<?php include('foot.php'); ?>