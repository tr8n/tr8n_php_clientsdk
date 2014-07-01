<h2><?php tre("File System") ?></h2>
<p><?php tre("This is a readonly cache that must be generated during deploy time.") ?></p>

<h4>Configuration</h4>

<pre><code class="language-javascript">"cache": {
  "enabled": true,
  "adapter": "file",
  "path": "/cache"
}</code></pre>

<h4><?php tre("Generation") ?></h4>

<p>
    <?php tre("To generate the cache files, run the following script:") ?>
</p>

<pre><code class="language-bash">$ bin/generate_file_cache</code></pre>
<p>
    <?php tre("The files will be stored in the /cache/current folder.") ?>

    Every time the cache is generated it will timestamp the folder so you will have a historic cache that you can fallback onto in case you need to rollback.
</p>
