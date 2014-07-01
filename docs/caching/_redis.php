<h2><?php tre("Redis") ?></h2>
<p>
    <?php tre("Redis is a self-building persistent cache that lazily warms up by retrieving data from the TranslationExchange service and storing it on the Redis server.") ?>
</p>

<h4>Prerequisites</h4>

<p><?php tre("You must have Redis running and accessible from your server.") ?></p>

<p>Install the <a href="https://github.com/nicolasff/phpredis">phpredis extension</a>:</p>

<pre><code class="language-bash">
$ git clone https://github.com/nicolasff/phpredis
$ cd phpredis
$ phpsize
$ ./configure
$ make
$ sudo make install
</code></pre>

<p>
Add "extension=redis.so" to php.ini and restart your Apache server.
</p>

<h4><?php tre("Configuration") ?></h4>

<p>
    <?php tre("To change cache settings, modify config/config.json file.") ?>
</p>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "redis",
  "host": "localhost",
  "port": 6379,
  "version": 1,
  "timeout": 3600
}</code></pre>

<p>
    <?php tre("You can also connect to Redis using a socket:") ?>
</p>


<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "redis",
  "socket": "/tmp/redis.sock",
  "version": 1,
  "timeout": 3600
}</code></pre>
