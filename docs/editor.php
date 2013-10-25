<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/html_translator")) ?>

<h1>HTML To TML Converter and Translator</h1>

<h3>Input Text</h3>
<?php
    $content = isset($_POST["content"]) ? $_POST["content"] : null;
    if ($content == null) {
        $content = "
        &lt;div class=&quot;text&quot;&gt;MyHeritage was founded by a &lt;a href=&quot;http://www.myheritage.com/management&quot;&gt;team of people&lt;/a&gt; with a passion for genealogy and a strong grasp of Internet technology. Our vision has been to make it easier for people around the world to use the power of the Internet to discover their heritage and strengthen their bonds with family and friends.&lt;br /&gt;
&lt;br /&gt;
As of 2005, we were based in the beautiful village of Bnei Atarot, near Tel Aviv, Israel, founded by German Templers in 1902 under the name of Wilhelma.&lt;br /&gt;
&lt;br /&gt;
Inspired by the surrounding fields and orchards and Templer estates, one of which served as our headquarters, we used the tools of tomorrow for researching the family history of yesterday. In February 2012 following our constant growth, we moved into lovely new offices in Or Yehuda, Israel. We also have offices in the USA in Lehi, Utah and LA, California, and employees and representatives in many countries around the world.&lt;br /&gt;
As a dynamic family history network, our innovations for family tree building and historical content search are constantly evolving to provide families with the most engaging and rewarding experience. Our recent acquisitions of World Vital Records and Geni.com for example, have enabled us to offer billions of historical records and exciting tools for collaboration to a wider and more international audience than ever before.&lt;br /&gt;
If you are passionate like us about researching what made you the way you are, and sharing your experiences with family members and friends, then MyHeritage is the place for you.&lt;/div&gt;
        ";

//        $content = "&lt;h1&gt;&lt;img alt=&quot;Saturn V carrying Apollo 11&quot; class=&quot;right&quot; src=&quot;assets/sample.jpg&quot;/&gt; Apollo 11&lt;/h1&gt; &lt;p&gt;&lt;b&gt;Apollo 11&lt;/b&gt; was the spaceflight that landed the first humans, Americans &lt;a href=&quot;http://en.wikipedia.org/wiki/Neil_Armstrong&quot; title=&quot;Neil Armstrong&quot;&gt;Neil Armstrong&lt;/a&gt; and &lt;a href=&quot;http://en.wikipedia.org/wiki/Buzz_Aldrin&quot; title=&quot;Buzz Aldrin&quot;&gt;Buzz Aldrin&lt;/a&gt;, on the Moon on July 20, 1969, at 20:18 UTC. Armstrong became the first to step onto the lunar surface 6 hours later on July 21 at 02:56 UTC.&lt;/p&gt; &lt;p&gt;Armstrong spent about &lt;s&gt;three and a half&lt;/s&gt; two and a half hours outside the spacecraft, Aldrin slightly less; and together they collected 47.5 pounds (21.5&amp;nbsp;kg) of lunar material for return to Earth. A third member of the mission, &lt;a href=&quot;http://en.wikipedia.org/wiki/Michael_Collins_(astronaut)&quot; title=&quot;Michael Collins (astronaut)&quot;&gt;Michael Collins&lt;/a&gt;, piloted the &lt;a href=&quot;http://en.wikipedia.org/wiki/Apollo_Command/Service_Module&quot; title=&quot;Apollo Command/Service Module&quot;&gt;command&lt;/a&gt; spacecraft alone in lunar orbit until Armstrong and Aldrin returned to it for the trip back to Earth.&lt;/p&gt; &lt;h2&gt;Broadcasting and &lt;em&gt;quotes&lt;/em&gt; &lt;a id=&quot;quotes&quot; name=&quot;quotes&quot;&gt;&lt;/a&gt;&lt;/h2&gt; &lt;p&gt;Broadcast on live TV to a world-wide audience, Armstrong stepped onto the lunar surface and described the event as:&lt;/p&gt; &lt;blockquote&gt;&lt;p&gt;One small step for a man, one giant leap for mankind.&lt;/p&gt;&lt;/blockquote&gt; &lt;p&gt;Apollo 11 effectively ended the &lt;a href=&quot;http://en.wikipedia.org/wiki/Space_Race&quot; title=&quot;Space Race&quot;&gt;Space Race&lt;/a&gt; and fulfilled a national goal proposed in 1961 by the late U.S. President &lt;a href=&quot;http://en.wikipedia.org/wiki/John_F._Kennedy&quot; title=&quot;John F. Kennedy&quot;&gt;John F. Kennedy&lt;/a&gt; in a speech before the United States Congress:&lt;/p&gt; &lt;blockquote&gt;&lt;p&gt;[...] before this decade is out, of landing a man on the Moon and returning him safely to the Earth.&lt;/p&gt;&lt;/blockquote&gt; &lt;h2&gt;Technical details &lt;a id=&quot;tech-details&quot; name=&quot;tech-details&quot;&gt;&lt;/a&gt;&lt;/h2&gt; &lt;table align=&quot;right&quot; border=&quot;1&quot; bordercolor=&quot;#ccc&quot; cellpadding=&quot;5&quot; cellspacing=&quot;0&quot; style=&quot;border-collapse:collapse;margin:10px 0 10px 15px;&quot;&gt; &lt;caption&gt;&lt;strong&gt;Mission crew&lt;/strong&gt;&lt;/caption&gt; &lt;thead&gt; &lt;tr&gt; &lt;th scope=&quot;col&quot;&gt;Position&lt;/th&gt; &lt;th scope=&quot;col&quot;&gt;Astronaut&lt;/th&gt; &lt;/tr&gt; &lt;/thead&gt; &lt;tbody&gt; &lt;tr&gt; &lt;td&gt;Commander&lt;/td&gt; &lt;td&gt;Neil A. Armstrong&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;Command Module Pilot&lt;/td&gt; &lt;td&gt;Michael Collins&lt;/td&gt; &lt;/tr&gt; &lt;tr&gt; &lt;td&gt;Lunar Module Pilot&lt;/td&gt; &lt;td&gt;Edwin &amp;quot;Buzz&amp;quot; E. Aldrin, Jr.&lt;/td&gt; &lt;/tr&gt; &lt;/tbody&gt; &lt;/table&gt; &lt;p&gt;Launched by a &lt;strong&gt;Saturn V&lt;/strong&gt; rocket from &lt;a href=&quot;http://en.wikipedia.org/wiki/Kennedy_Space_Center&quot; title=&quot;Kennedy Space Center&quot;&gt;Kennedy Space Center&lt;/a&gt; in Merritt Island, Florida on July 16, Apollo 11 was the fifth manned mission of &lt;a href=&quot;http://en.wikipedia.org/wiki/NASA&quot; title=&quot;NASA&quot;&gt;NASA&lt;/a&gt;&amp;#39;s Apollo program. The Apollo spacecraft had three parts:&lt;/p&gt; &lt;ol&gt; &lt;li&gt;&lt;strong&gt;Command Module&lt;/strong&gt; with a cabin for the three astronauts which was the only part which landed back on Earth&lt;/li&gt; &lt;li&gt;&lt;strong&gt;Service Module&lt;/strong&gt; which supported the Command Module with propulsion, electrical power, oxygen and water&lt;/li&gt; &lt;li&gt;&lt;strong&gt;Lunar Module&lt;/strong&gt; for landing on the Moon.&lt;/li&gt; &lt;/ol&gt; &lt;p&gt;After being sent to the Moon by the Saturn V&amp;#39;s upper stage, the astronauts separated the spacecraft from it and travelled for three days until they entered into lunar orbit. Armstrong and Aldrin then moved into the Lunar Module and landed in the &lt;a href=&quot;http://en.wikipedia.org/wiki/Mare_Tranquillitatis&quot; title=&quot;Mare Tranquillitatis&quot;&gt;Sea of Tranquility&lt;/a&gt;. They stayed a total of about 21 and a half hours on the lunar surface. After lifting off in the upper part of the Lunar Module and rejoining Collins in the Command Module, they returned to Earth and landed in the &lt;a href=&quot;http://en.wikipedia.org/wiki/Pacific_Ocean&quot; title=&quot;Pacific Ocean&quot;&gt;Pacific Ocean&lt;/a&gt; on July 24.&lt;/p&gt; &lt;hr/&gt; &lt;p style=&quot;text-align: right;&quot;&gt;&lt;small&gt;Source: &lt;a href=&quot;http://en.wikipedia.org/wiki/Apollo_11&quot;&gt;Wikipedia.org&lt;/a&gt;&lt;/small&gt;&lt;/p&gt;";

    }
?>

<div style="margin-top:20px;">
    <form method="post">

        <textarea id="content" name="content"><?php echo $content ?></textarea>

        <div style="padding:30px;text-align:center">
            <button type="submit" class="btn btn-primary">
                Update & Translate
            </button>
        </div>
    </form>
</div>

<hr>

<h3>Output and Translations</h3>

<div style="padding:10px; background:white; border: 1px solid #ccc;">
    <?php echo trh(html_entity_decode($content)) ?>
</div>

<?php javascript_tag('../ckeditor/ckeditor.js') ?>
<?php javascript_tag('../ckeditor/adapters/jquery.js') ?>

<script>
    $( document ).ready( function() {
        var editor = $('textarea#content').ckeditor();
//        alert(editor);
//        editor.resize( '100%', '850', true );
    } );
</script>

<?php tr8n_finish_block_with_options() ?>
<?php include('includes/footer.php'); ?>