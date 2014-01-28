<p align="center">
  <img src="https://raw.github.com/tr8n/tr8n/master/doc/screenshots/tr8nlogo.png">
</p>

Tr8n for PHP
==================

PHP Client SDK for Tr8nHub Translation Platform.

[![Build Status](https://travis-ci.org/tr8n/tr8n_php_clientsdk.png?branch=master)](https://travis-ci.org/tr8n/tr8n_php_clientsdk)
[![Coverage Status](https://coveralls.io/repos/tr8n/tr8n_php_clientsdk/badge.png)](https://coveralls.io/r/tr8n/tr8n_php_clientsdk)
[![Latest Stable Version](https://poser.pugx.org/tr8n/tr8n-client-sdk/v/stable.png)](https://packagist.org/packages/tr8n/tr8n-client-sdk)
[![Dependency Status](https://www.versioneye.com/user/projects/52e36159ec1375c6f4000075/badge.png)](https://www.versioneye.com/user/projects/52e36159ec1375c6f4000075)


Installation
==================

Tr8n Client SDK for PHP can be installed using the composer dependency manager. If you don't already have composer installed on your system, you can get it using the following command:

        $ cd YOUR_APPLICATION_FOLDER
        $ curl -s http://getcomposer.org/installer | php


Create composer.json in the root folder of your application, and add the following content:

        {
            "require": {
                "tr8n/tr8n-client-sdk": "dev-master"
            }
        }

This tells composer that your application requires tr8n-client-sdk library to be installed.

Now install Tr8n SDK library by executing the following command:


        $ php composer.phar install


Composer will automatically create a vendor folder and put the SDK into vendor/tr8n/tr8n-client-sdk directory.

Now you are ready to integrate Tr8n into your application.


Integration
==================

Before you can proceed with the integration, please visit http://tr8nhub.com register as a user and create a new application.

Once you have created a new application, go to the security tab in the application administration section and copy your application key and secret.

<img src="http://wiki.tr8nhub.com/images/thumb/f/f7/Application_Settings.png/800px-Application_Settings.png">


You will need to enter them in the initialization function of the Tr8n SDK.

To make sure you have installed everything correctly, let's create a sample test file in the root folder of your app and call it tr8n.php

Paste the following content into the file:


        <?php require_once(__DIR__ . '/vendor/tr8n/tr8n-client-sdk/library/Tr8n.php'); ?>
        <?php tr8n_init_client_sdk("https://tr8nhub.com", "YOUR_APPLICATION_KEY", "YOUR_APPLICATION_SECRET"); ?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>" lang="<?php echo Tr8n\Config::instance()->current_language->locale; ?>">
        <head>
            <meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
            <?php include(__DIR__ . '/vendor/tr8n/tr8n-client-sdk/library/Tr8n/Includes/Scripts.php'); ?>
        </head>
        <body>
            <?php tre("Hello World") ?>
        </body>
        </html>
        <?php tr8n_complete_request() ?>


Make sure you replace YOUR_APPLICATION_KEY and YOUR_APPLICATION_SECRET with the key and secret you copied from tr8nhub.com

Now you can open up your browser and navigate to the file:

        http://localhost/your_app_path/tr8n.php


If everything was configured correctly, you should see a phrase "Hello World" on your page.

Press the following keys:  Ctrl+Shift+S

You should see a lightbox with Tr8n's default shortcuts. You can configure those shortcuts in the application administration section.

To close the lightbox, click on the top-right corner or simply press the Esc button.


Press Ctrl+Shift+L to switch to a different language.

        Ctrl+Shift+L  - opens language selector


Now you can press Ctrl+Shift+I to enable inline translations.

        Ctrl+Shift+I  - toggles inline translation mode


When inline translations are enabled you will see translated phrases underlined in green color and not translated phrases with red.

Right-Mouse-Click (or Ctrl+Click on Mac) on any phrase and you will see an inline translator window that will allow you to translate the phrase.

        Right-Mouse-Click on Windows or Ctrl+Click on Mac - brings inline translation window


<img src="http://wiki.tr8nhub.com/images/6/6e/Sample_Translation.png">


To learn about various integration options and TML language, please visit the following URL:

http://wiki.tr8nhub.com/index.php?title=PHP_Client_SDK
