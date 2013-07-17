<?php if (\Tr8n\Config::instance()->isEnabled()) { ?>
    <script src="<?php echo \Tr8n\Config::instance()->application->host ?>/tr8n/api/proxy/boot.js?debug=true"></script>
<?php } ?>