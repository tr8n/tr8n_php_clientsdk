<?php include('includes/header.php'); ?>
<?php tr8n_begin_block_with_options(array("source" => "/emails")) ?>

<div style="background:white;padding:10px; padding-top:10px;padding-bottom:600px;margin-bottom:20px; border-radius:10px;">
    <h1>Email Templates</h1>

    <div style="padding-top:10px;">
    <table class="table">
    <?php
        $templates = \Tr8n\Config::instance()->application->emailTemplates();
    ?>
        <thead>
            <tr>
                <th>Keyword</th>
                <th>Name</th>
                <th>Description</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($templates as $temp) { ?>
                <tr>
                    <td><?php echo $temp->keyword ?></td>
                    <td><?php echo $temp->name ?></td>
                    <td><?php echo $temp->description ?></td>
                    <td>
                        <?php link_to_function("Preview Html", "previewEmail('" . $temp->keyword . "', 'html')", array("class"=>"btn")) ?>
                        <?php link_to_function("Preview Text", "previewEmail('" . $temp->keyword . "', 'text')", array("class"=>"btn")) ?>
                        <?php link_to("Send", "docs/send_email.php?keyword=" . $temp->keyword, array("class"=>"btn")) ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>

<script>
    function previewEmail(id, mode) {
        var w = 800;
        var h = 600;
        var left = (screen.width/2)-(w/2);
        var top = (screen.height/2)-(h/2);
        var url = Tr8n.host + "/tr8n/app/emails/preview?keyword=" + id + "&mode=" + mode;
        return window.open(url, "Tr8n Email Preview", 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    }
</script>


<?php tr8n_finish_block_with_options() ?>

<?php include('includes/footer.php'); ?>