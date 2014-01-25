<p align="center">
  <img src="https://raw.github.com/tr8n/tr8n/master/doc/screenshots/tr8nlogo.png">
</p>

Tr8n for PHP
==================

PHP Client SDK for Tr8n Translation Engine

[![Build Status](https://travis-ci.org/tr8n/tr8n_php_clientsdk.png?branch=master)](https://travis-ci.org/tr8n/tr8n_php_clientsdk)
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

To learn about how to get PHP SDK working, please read this document:

http://wiki.tr8nhub.com/index.php?title=PHP_Client_SDK
