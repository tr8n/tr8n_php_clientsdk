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


<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor} следит за фотоальбомами {targets::gen}", array(
        "actor" => array($actor, tr($actor->name)),
        "targets" => array(
                    $targets,
                    function ($user) { return tr($user->name); },
                    array("joiner" => tr("and"))
                )
    ),
    array("locale" => 'ru')) ?>
    }
}
</code></pre>
  <div class="content">
    <?php foreach(array($mike, $anna) as $actor) {
        foreach($variants as $targets) { ?>
        <?php tre("{actor} следит за фотоальбомами {targets::gen}", array(
                "actor" => array($actor, tr($actor->name)),
                "targets" => array($targets, function ($user) { return tr($user->name); }, array("joiner" => tr("and")) )),
            array("locale" => 'ru')) ?><br>
      <?php
        }
    } ?>
  </div>
</div>


<h4>Dative Case - Дательный Падеж</h4>
<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-ruby">foreach(array($mike, $anna) as $actor) {
    foreach(array($tom, $alex, $peter,$kate, $jenny) as $target) { ?>
        tr("{actor|| подарил, подарила} подарок {target::dat}", array("actor" => array($actor, tr($actor->name)), array($target, tr($target->name)), array("locale" => 'ru')))
    }
}
        </code></pre>
  <div class="content">
      <?php foreach(array($mike, $anna) as $actor) {
                foreach(array($tom, $alex, $peter,$kate, $jenny) as $target) { ?>
        <?php tre("{actor|| подарил, подарила} подарок {target::dat}", array("actor" => array($actor, tr($actor->name)), "target" => array($target, tr($target->name)), array("locale" => 'ru'))) ?><br>

      <?php
        }
      } ?>
  </div>
</div>


<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor|| подарил, подарила} подарок {targets::dat}", array(
        "actor" => array($actor, tr($actor->name)),
        "targets" => array(
                    $targets,
                    function ($user) { return tr($user->name); },
                    array("joiner" => tr("and"))
                )
    ),
    array("locale" => 'ru')) ?>
    }
}
</code></pre>
  <div class="content">
    <?php foreach(array($mike, $anna) as $actor) {
        foreach($variants as $targets) { ?>
        <?php tre("{actor|| подарил, подарила} подарок {targets::dat}", array(
                "actor" => array($actor, tr($actor->name)),
                "targets" => array($targets, function ($user) { return tr($user->name); }, array("joiner" => tr("and")) )),
            array("locale" => 'ru')) ?><br>
      <?php
        }
    } ?>
  </div>
</div>



<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor::dat} нравится сообщение от {targets::gen}", array(
        "actor" => array($actor, tr($actor->name)),
        "targets" => array(
                    $targets,
                    function ($user) { return tr($user->name); },
                    array("joiner" => tr("and"))
                )
    ),
    array("locale" => 'ru')) ?>
    }
}
</code></pre>
  <div class="content">
      <?php foreach(array($mike, $anna) as $actor) {
          foreach($variants as $targets) { ?>
              <?php tre("{actor::dat} нравится сообщение от {targets::gen}", array(
                      "actor" => array($actor, tr($actor->name)),
                      "targets" => array($targets, function ($user) { return tr($user->name); }, array("joiner" => tr("and")) )),
                  array("locale" => 'ru')) ?><br>
          <?php
          }
      } ?>
  </div>
</div>



<h4>Accusative Case - Винительный Падеж</h4>

<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor|| увидел, увидела} {target::acc} на вечеринке", array(
        "actor" => array($anna, tr($anna->name)),
        "target" => array($alex, tr($alex->name))
    ),
    array("locale" => 'ru')
)</code></pre>
  <div class="content">
    <?php tre("{actor|| увидел, увидела} {target::acc} на вечеринке", array("actor" => array($anna, tr($anna->name)), "target" => array($alex, tr($alex->name))), array("locale" => 'ru'))  ?><br>
  </div>
</div>



<h4>Instrumental Case - Творительный Падеж</h4>

<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor} гордится {target::ins}", array(
        "actor" => array($anna, tr($anna->name)),
        "target" => array($alex, tr($alex->name))
    ),
    array("locale" => 'ru')
)</code></pre>
  <div class="content">
    <?php tre("{actor} гордится {target::ins}", array("actor" => array($anna, tr($anna->name)), "target" => array($alex, tr($alex->name))), array("locale" => 'ru'))  ?><br>
  </div>
</div>



<div class="example">
  <div class="title"><?php tre('example') ?></div>
        <pre class="code"><code class="language-php">tre("{actor} теперь дружит с {targets::ins}", array(
        "actor" => array($actor, tr($actor->name)),
        "targets" => array(
                    $targets,
                    function ($user) { return tr($user->name); },
                    array("joiner" => tr("and"))
                )
    ),
    array("locale" => 'ru')) ?>
    }
}
</code></pre>
  <div class="content">
      <?php foreach(array($mike, $anna) as $actor) {
          foreach($variants as $targets) { ?>
              <?php tre("{actor} теперь дружит с {targets::ins}", array(
                      "actor" => array($actor, tr($actor->name)),
                      "targets" => array($targets, function ($user) { return tr($user->name); }, array("joiner" => tr("and")) )),
                  array("locale" => 'ru')) ?><br>
          <?php
          }
      } ?>
  </div>
</div>



<h4>Prepositional Case - Предложный Падеж</h4>

<div class="example">
  <div class="title"><?php tre('example') ?></div>
      <pre class="code"><code class="language-php">tr("{actor} думает {target::pre::about}", array(
    "actor" => array($anna, tr($anna->name)),
    "target" => array($alex, tr($alex->name))
  ), array("locale" => 'ru')
)

tr("{actor} думает {target::pre::about}", array(
    "target" => array($anna, tr($anna->name)),
    "actor" => array($alex, tr($alex->name))
  ), array("locale" => 'ru')
)
</code></pre>
  <div class="content">
      <?php tre("{actor} думает {target::pre::about}", array("actor" => array($anna, tr($anna->name)), "target" => array($mike, tr($mike->name))), array("locale" => 'ru'))  ?><br>
      <?php tre("{actor} думает {target::pre::about}", array("target" => array($anna, tr($anna->name)), "actor" => array($mike, tr($mike->name))), array("locale" => 'ru'))  ?><br>
  </div>
</div>
