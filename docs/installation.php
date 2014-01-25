<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/installation")) ?>

    <h2><?php tre("Installation Instructions") ?></h2>

    <p>
        <?php tre("Clone the git repository to your projects folder:") ?>
    </p>

    <pre><code class="language-php"> git clone git@github.com:tr8n/tr8n_php_clientsdk.git </code></pre>

    <p>
        <?php tre("Require Tr8n library in your application:") ?>
    </p>

    <pre><code class="language-php"> &lt;?php require_once('tr8n_php_clientsdk/library/Tr8n.php'); ?&gt; </code></pre>

    <p>
        <?php tre("Make sure to provide the correct path to where you cloned tr8n_php_clientsdk.") ?>
    </p>

    <h3><?php tre("Connecting to Tr8n service") ?></h3>
    <?php trhe("
    <p>
        To connect to a hosted tr8n service, visit <a href='tr8nhub.com'>tr8nhub.com</a>, register as a new user and create a new application.
        Then add the following line to your PHP application:
    </p>
    ") ?>

    <pre><code class="language-php"> &lt;?php tr8n_init_client_sdk('http://tr8nhub.com', APPLICATION_KEY, APPLICATION_SECRET); ?&gt; </code></pre>


<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>