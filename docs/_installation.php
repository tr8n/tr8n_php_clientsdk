<h2><?php tre("Installation") ?></h2>

<p>
    Tr8n Client SDK for PHP can be installed using the composer dependency manager. If you don't already have composer installed on your system, you can get it using the following command:
</p>

<pre><code class="language-bash">$ cd YOUR_APPLICATION_FOLDER
$ curl -s http://getcomposer.org/installer | php
</code></pre>

<p>
    Create composer.json in the root folder of your application, and add the following content:
</p>

<pre><code class="language-javascript">{
  "require": {
    "tr8n/tr8n-client-sdk": "dev-master"
  }
}
</code></pre>

<p>
    This tells composer that your application requires tr8n-client-sdk library to be installed.
</p>

<p>
    Now install Tr8n SDK library by executing the following command:
</p>

<pre><code class="language-bash">$ php composer.phar install</code></pre>

<p>
    Composer will automatically create a vendor folder and put the SDK into vendor/tr8n/tr8n-client-sdk directory.
</p>

<p>
    Now you are ready to integrate Tr8n into your application.
</p>





