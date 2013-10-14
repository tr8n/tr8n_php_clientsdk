<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/emails")) ?>

    <div style="background:white;padding:10px; padding-top:10px;padding-bottom:600px;margin-bottom:20px; border-radius:10px;">
        <h1>Send Email Using Template</h1>

        <?php
            if (isset($_GET["keyword"]))
                $keyword = $_GET["keyword"];
            else if (isset($_POST["keyword"]))
                $keyword = $_POST["keyword"];

        ?>

        <div>
            Template: <?php echo $keyword ?>
        </div>
        <div style="padding-top:10px;">
           <form action="" methd="post">
              <input type="hidden" name="keyword" value="<?php echo $keyword ?>">
              <div>
                   <div>Email Tokens</div>
                   <div>
                    <textarea id="tokens" style="width:800px;height:150px;"></textarea>
                   </div>
               </div>
               <div>
                <?php link_to("Cancel", "/docs/emails.php", array("class" => "btn")) ?>
                <button type="submit" class="btn btn-primary">
                    Send
                </button>
               </div>
           </form>
        </div>
    </div>

<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>