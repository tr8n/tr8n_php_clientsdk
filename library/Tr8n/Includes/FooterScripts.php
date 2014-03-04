<?php if (\Tr8n\Config::instance()->isEnabled()) { ?>
    <script>
        function tr8n_footer_scripts() {
            Tr8n.sources = <?php echo json_encode(\Tr8n\Config::instance()->requested_sources) ?>;
        }
    </script>
    <?php tr8n_complete_request() ?>
<?php } ?>