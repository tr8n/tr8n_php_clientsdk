<h2><?php tre("Memcache") ?></h2>
<p>
    <?php tre("Memcache is a self-building cache that lazily warms up by retrieving data from the TranslationExchange service and storing it on the Memcache server.") ?>
</p>

<h4>Prerequisites</h4>

<p>You must have Memcache server running and accessible from your application.</p>
<p>You need to install the Memcache client library. This can be done using PECL:</p>

<pre><code class="language-bash">$ pecl install memcache</code></pre>

<p>
Add "extension=memcache.so" to php.ini and restart your server.
</p>

<h4>Configuration</h4>

<p>
    To change cache settings, modify config/config.json file.
</p>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "memcache",
  "host": "localhost",
  "port": 11211,
  "version": 1,
  "timeout": 3600
}</code></pre>

<p>If you have multiple Memcache servers, you can add them as a list:</p>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "memcached",
  "version": 1,
  "timeout": 3600,
  "servers": [
    {"host": "1.1.1.1", "port": 11211},
    {"host": "1.1.1.2", "port": 11211},
    {"host": "1.1.1.3", "port": 11211}
  ]
}</code></pre>
