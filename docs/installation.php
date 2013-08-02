<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/installation")) ?>

    <h2>Installation Instructions</h2>

    <p>
        Clone the git repository to your projects folder:
    </p>

    <pre><code class="language-php"> git clone git@github.com:tr8n/tr8n_php_clientsdk.git </code></pre>

    <p>
        Require tr8n_php_clientsdk/library/Tr8n.php in your application:
    </p>

    <pre><code class="language-php"> &lt;?php require_once('tr8n_php_clientsdk/library/Tr8n.php'); ?&gt; </code></pre>

    <p>
        Make sure you provide a correct path to where you cloned tr8n_php_clientsdk.
    </p>

    <p>

    </p>

    <h2>Configuration</h2>

    <h2>Logging</h2>

    <h2>Caching</h2>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>