<?php include('docs/includes/head.php'); ?>
<div class="container">
<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

<?php

class User {
    public $name, $gender;
    function __construct($name, $gender = "male") {
        $this->name = $name;
        $this->gender = $gender;
    }
    function __toString() {
        return $this->name;
    }
    function fullName() {
        return $this->name;
    }
}

class Number {
    public $value;
    function __construct($value) {
        $this->value = $value;
    }
    function __toString() {
        return "" . $this->value;
    }
}

$male = new User("Michael", "male");
$female = new User("Anna", "female");


?>

<?php trhe("

<p>Hello Wold!</p>

<p>
Here is some text about the site.
<br><br><br>

Here is another sentence.
<br>

This is the third sentence.
</p>

<div>
Now some text with <b>tml</b>.
</div>

<p>
Here <i>is some nested <b>TML</b></i>.
</p>

<p>
Here is a <a href='http://www.google.com'>link to <b>Google</b></a>.
</p>

") ?>

<?php trhe("

 <p>MyHeritage was founded by a team of people with a passion for genealogy and a strong grasp of Internet technology. Our vision has been to
 <img alt=\"\" src=\"/sites/myheritage.dev3.linnovate.net/files/large_10-9-2013%204-23-55%20PM_1.jpg\" style=\"width: 180px; height: 180px; float: right;\">
 make it easier for people around the world to use the power of the Internet to discover their heritage and strengthen their bonds with family and friends.
 <br><br>As of 2005, we were based in the <a href=\"http://www.myheritage.com/about-myheritage\">beautiful</a> village of Bnei Atarot, near Tel Aviv, Israel,
founded by German Templers in 1902 under the name of Wilhelma.<br><br>Inspired by the surrounding fields and orchards and Templer estates, one of which served as our headquarters,
we used the tools of tomorrow for researching the family history of yesterday. In February 2012 following our constant growth, we moved into lovely new offices in Or Yehuda,
Israel. We also have offices in the USA in Lehi, Utah and LA, California, and employees and representatives in many countries around the world.<br>

As a dynamic family history network, our innovations for family tree building and historical content search are constantly evolving to provide families with the most engaging
and rewarding experience. Our recent acquisitions of World Vital Records and Geni.com for example, have enabled us to offer billions of historical records and exciting tools
for collaboration to a wider and more international audience than ever before.<br>If you are passionate like us about researching what made you the way you are, and
sharing your experiences with family members and friends, then MyHeritage is the place for you.</p>

") ?>

<?php tr8n_finish_block_with_options() ?>
</div>

<?php include('docs/includes/foot.php'); ?>