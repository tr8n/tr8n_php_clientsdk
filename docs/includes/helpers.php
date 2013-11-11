<?php
    function url_for($path) {
        if ($path[0] != '/') {
            $path = '/'.$path;
        }
        return \Tr8n\Config::instance()->configValue("local.base_path") . $path;
    }

    function stylesheet_tag($path) {
        echo '<link href="' . url_for('docs/assets/css/' .$path) . '" rel="stylesheet" />';
    }

    function javascript_tag($path) {
        if (strpos($path, '//') !== FALSE) {
            echo '<script type="text/javascript" src="' . $path . '"></script>';
            return;
        }
        echo '<script type="text/javascript" src="' . url_for('docs/assets/js/' . $path) . '"></script>';
    }

    function image_tag($path, $opts = array()) {
        echo '<img src="' . url_for('docs/assets/img/' . $path) . '" ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' >';
    }

    function link_to($label, $path, $opts = array()) {
        echo '<a href="' . url_for($path) . '" ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' >' . $label . '</a>';
    }

    function link_to_function($label, $func, $opts = array()) {
        echo '<a href="javascript:void(0);" onClick="' . $func . '" ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' >' . $label . '</a>';
    }

    function active_link($path, $except = null) {
        if ($except != null && strpos($_SERVER['REQUEST_URI'], $except) !== FALSE) {
            return;
        }

        if (strpos($_SERVER['REQUEST_URI'], $path) !== FALSE) {
            echo 'class="active"';
        }
    }

    function list_link_tag($title, $path) {
        echo '<li ';
        active_link($path);
        echo '>';
        link_to(tr($title), $path);
        echo '</li>';
    }