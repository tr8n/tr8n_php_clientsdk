<h1><?php tre("Caching") ?></h1>
<?php trhe("
    <p>
        Since pages may contain numerous translation keys, it is crucial that Tr8n is backed by a caching mechanism.
        The caching mechanism provides a local cache of the Tr8n objects retrieved from the service. When users view the pages in non-translation mode, the translations will be served from the cache.
        For translators, who enable inline translation mode, the SDK will always request the Tr8n service to get the most recent translations.
    </p>
    <p>
        Tr8n supports a number of various Cache adapters. To change cache settings, modify config/config.json file.
    </p>
") ?>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "memcache",
  "host": "localhost",
  "port": "11211",
  "version": 1,
  "timeout": 3600
}</code></pre>


<?php include('_file.php'); ?>

<?php include('_chdb.php'); ?>

<?php include('_apc.php'); ?>

<?php include('_memcache.php'); ?>

<?php include('_memcached.php'); ?>

<?php include('_redis.php'); ?>