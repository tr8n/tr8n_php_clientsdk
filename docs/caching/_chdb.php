<h2><?php tre("CHDB") ?></h2>
<p><?php tre("This is a readonly cache that must be generated during deploy time.") ?></p>

<p>
CHDB (constant hash database) is a fast key-value database for constant data, realized by using a memory-mapped file and thus providing the following features:
</p>

<ul>
    <li>Extremely fast initial load, regardless of the size of the database.</li>
    <li>Only the pages of the file which are actually used are loaded from the disk.</li>
    <li>Once a page is loaded it is shared across multiple processes.</li>
    <li>Loaded pages are cached across multiple requests and even process recycling.</li>
</ul>

<p>To learn more about CHDB, visit <a href="http://www.php.net/manual/en/intro.chdb.php">http://www.php.net/manual/en/intro.chdb.php</a></p>

<h4>Prerequisites</h4>

<p>For CHDB library to work you need to install <a href="http://cmph.sourceforge.net/">C Minimal Perfect Hashing Library</a> </p>
<p>You can download it from <a href="http://sourceforge.net/projects/cmph/">http://sourceforge.net/projects/cmph/</a>. To configure and install cmph, run the following commands:</p>

<pre><code class="language-bash">
  $ wget http://colocrossing.dl.sourceforge.net/project/cmph/cmph/cmph-2.0.tar.gz
  $ tar -zxvf cmph-2.0.tar.gz
  $ cd cmph-2.0
  $ ./configure
  $ make
  $ sudo make install
</code></pre>

<p>Now you can install CHDB by using PECL:</p>

<pre><code class="language-bash">$ sudo pecl install chdb</code></pre>

<p>
    Add "extension=chdb.so" to php.ini and restart your server.
</p>

<h4><?php tre("Configuration") ?></h4>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "chdb"
}</code></pre>

<p>
    <?php tre("To generate the cache files, run the following script:") ?>
</p>

<pre><code class="language-bash">$ bin/generate_chdb_cache</code></pre>

<?php trhe("
<p>
    The cache will be stored in the <strong>cache/current.chdb</strong> file.
    Every time the cache is generated it will timestamp the folder so you will have a historic cache that you can fallback onto in case you need to rollback.
</p>
") ?>