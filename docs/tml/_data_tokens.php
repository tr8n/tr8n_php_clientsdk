<h2><?php tre("Data Tokens") ?></h2>
<p>
    <?php tre("In many cases your tokens would be string objects that get substituted directly into the translated sentence.") ?>
</p>
<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => "Michael"))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => "Michael")) ?>
    </div>
</div>

<p><?php tre("Translations can be nested.") ?></p>

<div class="example">
  <div class="title"><?php tre('example') ?></div>
   <pre><code class="language-php">tr("Welcome to {city}", array("city" => tr("Los Angeles")))</code></pre>

  <div class="content">
    <?php tre("Welcome to {city}", array("city" => tr("Los Angeles")))  ?>
  </div>
</div>

<p><?php tre("But make sure that you don't take translations out of context.") ?></p>

<div class="example">
  <div class="title"><?php tre('example') ?></div>
  <pre><code class="language-php">tr("Please visit our {registration} to join our site.", array("registration" => link_to(tr("registration page"), "")))</code></pre>
  <div class="content">
    <?php tre("Please visit our {registration} to join our site.", array("registration" => "<a href=''>" . tr("registration page") . "</a>"))  ?>
  </div>
</div>

<p>
  <?php  tre("The problem with the above example, is that the \"registration page\" link text would be translated differently based on the context where it appears.") ?>
  <?php  tre("You must keep the two parts together to make sure the translations are accurate. You will later see how you can use decoration tokens to fix the above problem.") ?>
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => $male))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => $male)) ?>
    </div>
</div>

<p>
    <?php  tre("If your translation key needs to use context rules, you can pass the object and the substitution value as an array.") ?>
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array($male, "Michael B.")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array($male, "Michael B"))) ?>
    </div>
</div>


<p>
   You can also call properties of an object by using @ sign:
</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array($male, "@name")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array($male, "@name"))) ?>
    </div>
</div>

<p>
    And call object methods by using @@ sign:
</p>


<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array($male, "@@fullName")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array($male, "@@fullName"))) ?>
    </div>
</div>

<p>
    The objects themselves can be hashes:
</p>


<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array(array("name" => "Tom"), "@name")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array(array("name" => "Tom"), "@name"))) ?>
    </div>
</div>


<p>You can use hashes for the token values as well:</p>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => $male, "attribute" => "name")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array("object" => $male, "attribute" => "name"))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => $male, "method" => "fullName")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array("object" => $male, "method" => "fullName"))) ?>
    </div>
</div>

<div class="example">
    <div class="title"><?php tre("example") ?></div>
    <pre><code class="language-php">tr("Hello {user}", array("user" => array("object" => array("name" => "Alex"), "attribute" => "name")))</code></pre>
    <div class="content">
        <?php tre("Hello {user}", array("user" => array("object" => array("name" => "Alex"), "attribute" => "name"))) ?>
    </div>
</div>
