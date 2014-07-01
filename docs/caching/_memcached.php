<h2><?php tre("Memcached") ?></h2>
<p>
    <?php tre("Memcached is a self-building cache that lazily warms up by retrieving data from the TranslationExchange service and storing it on the Memcache server.") ?>
</p>

<h4>Prerequisites</h4>

<p>You must have Memcache server running and accessible from your application.</p>

<p>You need to install the Memcached client library. On linux, this can be done using:

<pre><code class="language-bash">sudo apt-get install php5-memcached</code></pre>

<p>Or using PECL:</p>

<pre><code class="language-bash">$ sudo pecl install memcached</code></pre>

<p>
Add "extension=memcached.so" to php.ini and restart your server.
</p>

<p>If you are on a Mac, you may need to download the latest <a href="https://launchpad.net/libmemcached/+download">libmemcached client library</a>.
Once downloaded, extract the archive and run the following commands:
</p>

<pre><code class="language-bash">$ ./configure
$ make
$ sudo make install
</code></pre>

<p>After that you can proceed with the PECL installation.</p>


<h4>Configuration</h4>

<p>
    To change cache settings, modify config/config.json file.
</p>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "memcached",
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
