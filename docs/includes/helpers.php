<?php
    $g_base_url = $_SERVER['REQUEST_URI'];

    function url_for($path) {
        global $g_base_url;
        return $g_base_url . $path;
    }

    function stylesheet_tag($path) {
        echo '<link href="' . url_for('docs/assets/css/' .$path) . '" rel="stylesheet" />';
    }

    function javascript_tag($path) {
        global $g_base_url;
        if (strpos($path, '//') !== FALSE) {
            echo '<script type="text/javascript" src="' . $path . '"></script>';
            return;
        }
        echo '<script type="text/javascript" src="' . url_for('docs/assets/js/' . $path) . '"></script>';
    }

    function image_tag($path, $opts = array()) {
        echo '<img src="' . url_for('docs/assets/img/' . $path) . '" ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' >';
    }