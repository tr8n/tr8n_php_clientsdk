<?php include('docs/includes/head.php'); ?>
<div class="container">
<?php tr8n_begin_block_with_options(array("source" => "/test")) ?>

<?php trh("

<h1>Heading</h1>

<p>
    As of 2005, we were based in the beautiful village of Bnei Atarot, near Tel Aviv, Israel, founded by German Templers in 1902 under the name of Wilhelma.
</p>

<p>
Inspired by the surrounding fields and orchards and Templer estates, one of which served as our headquarters, we used the tools of tomorrow for researching the family history of yesterday. In February 2012 following our constant growth, we moved into lovely new offices in Or Yehuda, Israel. We also have offices in the USA in Lehi, Utah and LA, California, and employees and representatives in many countries around the world.
</p>

<p>
As a dynamic family history network, our innovations for family tree building and historical content search are constantly evolving to provide families with the most engaging and rewarding experience. Our recent acquisitions of World Vital Records and Geni.com for example, have enabled us to offer billions of historical records and exciting tools for collaboration to a wider and more international audience than ever before.
</p>

<p>
If you are passionate like us about researching what made you the way you are, and sharing your experiences with family members and friends, then MyHeritage is the place for you.
</p>


", array(), array("data_tokens" => false, "token_name" => "year")) ?>

<br/>



<?php trh("

    <h1>Section 1</h1>
    <p> This is <b>very <i>very</i> interesting</b> indeed. </p>

", array(), array("data_tokens" => false)) ?>

<br/>




    <?php tr8n_finish_block_with_options() ?>
</div>
<?php tr8n_complete_request() ?>
<?php include('docs/includes/foot.php'); ?>