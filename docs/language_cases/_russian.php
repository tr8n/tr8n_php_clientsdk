<h2><?php tre("Russian Examples")  ?></h2>

<?php tre("Let's use Russian language to see some more advanced language cases in action.")  ?>

<?php tre("We will also be translating the names of our users, in order to get a better picture of how the names get adjusted.")  ?>

<h4>Nominative Case - Именительный Падеж</h4>
<p>
  <?php tre("No real change to the values here.")  ?>
</p>
<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor::nom} тестирует это приложение", array(
        "actor" => array($anna, tr($anna->name))
    ), array("locale" => 'ru')
)
</code></pre>
  <div class="content">
    <?php tre("{actor::nom} тестирует это приложение", array("actor" => array($anna, tr($anna->name))), array("locale" => 'ru'))  ?><br>
  </div>
</div>


<h4>Genitive Case - Родительный Падеж</h4>
<p><?php tre("In grammar, genitive (abbreviated gen; also called the possessive case or second case) is the grammatical case that marks a noun as modifying another noun. ")  ?></p>
<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor|| пригласил, пригласила} {target::gen} на вечеринку", array(
        "actor" => array($anna, tr($anna->name)),
        "target" => array($alex, tr($alex->name))
    ),
    array("locale" => 'ru')
)</code></pre>
  <div class="content">
    <?php tre("{actor|| пригласил, пригласила} {target::gen} на вечеринку", array("actor" => array($anna, tr($anna->name)), "target" => array($alex, tr($alex->name))), array("locale" => 'ru'))  ?><br>
  </div>
</div>
