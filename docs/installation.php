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

    <h3><?php tre("Running your own Tr8n service") ?></h3>

    <?php trhe("
    <p>
        Alternatively, you can host a tr8n service instance yourself. Tr8n service is written using Ruby on Rails. To run the service yourself, clone the git repository:
    </p>
    ") ?>

    <pre><code class="language-php">
        $ git clone git@github.com:tr8n/tr8n.git
    </code></pre>

    <p>
        <?php tre("Run the following commands: (temporary, until the service node is ready)") ?>
    </p>

    <pre><code class="language-php">
        $ cd tr8n/test/dummy
        $ bundle
        $ bundle exec rake db:migrate
        $ bundle exec rake tr8n:init
        $ bundle exec rails s
    </code></pre>

    <p>
        <?php tre("You should see the following output:") ?>
    </p>

    <pre><code class="language-php">
        => Booting WEBrick
        => Rails 3.2.3 application starting in development on http://0.0.0.0:3000
        => Call with -d to detach
        => Ctrl-C to shutdown server
        [2013-08-14 20:21:39] INFO  WEBrick 1.3.1
        [2013-08-14 20:21:39] INFO  ruby 1.9.3 (2013-02-22) [x86_64-darwin12.2.1]
        [2013-08-14 20:21:39] INFO  WEBrick::HTTPServer#start: pid=29722 port=3000
    </code></pre>

    <p>
        <?php tre("This means that the service is running. Visit http://localhost:3000 and register as a new user. Go to the admin tools and retrieve your application secret.") ?>
    </p>

    <p>
        <?php tre("In your PHP application, provide the following information:") ?>
    </p>
    <pre><code class="language-php"> &lt;?php tr8n_init_client_sdk('http://localhost:3000', APPLICATION_KEY, APPLICATION_SECRET); ?&gt; </code></pre>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>