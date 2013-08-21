<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/installation")) ?>

    <h2><?php tre("Installation Instructions") ?></h2>

    <p>
        <?php tre("Clone the git repository to your projects folder:") ?>
    </p>

    <pre><code class="language-php"> git clone git@github.com:tr8n/tr8n_php_clientsdk.git </code></pre>

    <p>
        Require tr8n_php_clientsdk/library/Tr8n.php in your application:
    </p>

    <pre><code class="language-php"> &lt;?php require_once('tr8n_php_clientsdk/library/Tr8n.php'); ?&gt; </code></pre>

    <p>
        Make sure you provide a correct path to where you cloned tr8n_php_clientsdk.
    </p>

    <h3><?php tre("Offline & online modes") ?></h3>

    <p>
        Tr8n PHP Client SDK can run in two modes: offline and online.
    </p>

    <p>
        The offline mode initializes languages and translations from your local cache files, applies rules and substitutes tokens.
        The offline mode is usually used when the application does not need any more translations.
        It is also used when a connection to the Tr8n service cannot be established.
        The offline mode does not register new keys and does not download translations.
        It also disables the inline translations tools.
        The offline mode can be enabled in the Tr8n configuration class.
    <p>

    <p>
        The online mode, on the other hand, constantly monitors for new translation keys, registers them on the service, downloads translations and provides inline translation tools.
        It also allows you to configure components, assign translators, and much more.
        As long as you continue innovating and developing your application, it is advisable to run your application in an online mode and never worry about your translations.
    </p>
    <p>
        The following section will outline how to configure the SDK in the online mode.
    </p>

    <h3><?php tre("Connecting to Tr8n service") ?></h3>
    <p>
        Tr8n is a distributed translation memory that allows multiple Tr8n instances to exchange translations with each other.
        Tr8n is open sourced so you can run your own instance of the service. Alternatively, you can connect to an existing instance of the service.
    </p>
    <p>
        To connect to a remote service, visit tr8nhub.com, register as a user and create a new application.
    </p>

    <pre><code class="language-php"> &lt;?php tr8n_init_client_sdk('http://tr8nhub.com', APPLICATION_KEY, APPLICATION_SECRET); ?&gt; </code></pre>

    <h3><?php tre("Running your own Tr8n service") ?></h3>

    <p>
        Tr8n service is written using Ruby on Rails. To run the service yourself, clone the git repository:
    </p>

    <pre><code class="language-php">
        $ git clone git@github.com:tr8n/tr8n.git
    </code></pre>

    <p>
        Run the following commands: (temporary, until the node is ready)
    </p>

    <pre><code class="language-php">
        $ cd tr8n/test/dummy
        $ bundle
        $ bundle exec rake db:migrate
        $ bundle exec rake tr8n:init
        $ bundle exec rails s
    </code></pre>

    <p>
        You should see the following output:
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

    <p>This means that the service is running. Visit http://localhost:3000 and register as a new user. Go to the admin tools and retrieve your application secret.</p>

    <p>
        In your PHP application, provide the following information:
    </p>
    <pre><code class="language-php"> &lt;?php tr8n_init_client_sdk('http://localhost:3000', 'default', APPLICATION_SECRET); ?&gt; </code></pre>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>