<?php

function tr8n_language_name_tag($language = null, $opts = array()) {
    if ($language == null) $language = tr8n_current_language();
    if (isset($opts["flag"])) {
        tr8n_language_flag_tag($language);
        echo " ";
    }
    echo $language->native_name;
}

function tr8n_language_flag_tag($language = null) {
    if ($language == null) $language = tr8n_current_language();
    echo "<img src='" . $language->flagUrl() . "' style='margin-right:3px;'>";
}

function tr8n_link_to($dest, $title = null, $opts = array()) {
    $path = null;
    $function = "";
    switch ($dest) {
        case 'app_phrases':
            if ($title == null) $title = "Phrases";
            $path = "/tr8n/app/phrases/index";
            break;
        case 'app_settings':
            if ($title == null) $title = "Settings";
            $path = "/tr8n/app/settings/index";
            break;
        case 'app_translations':
            if ($title == null) $title = "Translations";
            $path = "/tr8n/app/translations/index";
            break;
        case 'app_translators':
            if ($title == null) $title = "Translators";
            $path = "/tr8n/app/translators/index";
            break;
        case 'assignments':
            if ($title == null) $title = "Assignments";
            $path = "/tr8n/translator/assignments";
            break;
        case 'notifications':
            if ($title == null) $title = "Notifications";
            $path = "/tr8n/translator/notifications";
            break;
        case 'following':
            if ($title == null) $title = "Following";
            $path = "/tr8n/translator/following";
            break;
        case 'preferences':
            if ($title == null) $title = "Preferences";
            $path = "/tr8n/translator/preferences";
            break;
        case 'help':
            if ($title == null) $title = "Help";
            $path = "/tr8n/help";
            break;
        case 'discussions':
            if ($title == null) $title = "Discussions";
            $path = "/tr8n/forum";
            break;
        case 'awards':
            if ($title == null) $title = "Awards";
            $path = "/tr8n/awards";
            break;
        case 'phrases':
            if ($title == null) $title = "Phrases";
            $path = "/tr8n/phrases";
            break;
        case 'translations':
            if ($title == null) $title = "Translations";
            $path = "/tr8n/translations";
            break;
        case 'toggle_inline':
            if ($title == null) $title = "Toggle inline mode";
            $function = "Tr8n.UI.LanguageSelector.toggleInlineTranslations();";
            break;
        case 'notifications_popup':
            if ($title == null) $title = "Notifications";
            $function = "Tr8n.UI.Lightbox.show('/tr8n/translator/notifications/lb_notifications', {width:600});";
            break;
        case 'shortcuts_popup':
            if ($title == null) $title = "Shortcuts";
            $function = "Tr8n.UI.Lightbox.show('/tr8n/help/lb_shortcuts', {width:400});";
            break;
        case 'login':
            if ($title == null) $title = "Login";
            $function = "Tr8n.UI.Lightbox.show('/login/index?mode=lightbox', {width:550, height:500});";
            break;
        case 'logout':
            if ($title == null) $title = "Logout";
            $function = "Tr8n.UI.Lightbox.show('/login/out?mode=lightbox', {width:400});";
            break;
    }

    if ($path != null) {
        $path = Tr8n\Config::instance()->application->host . $path;
        echo '<a ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' href="' . $path . '">' . tr($title) . '</a>';
        return;
    }

    if ($function != null) {
        echo '<a ' . \Tr8n\Utils\ArrayUtils::toHTMLAttributes($opts) . ' href="#" onClick="' . $function . '">' . tr($title) . '</a>';
        return;
    }

    echo "Invalid tr8n link key";
}

function tr8n_login_url() {
    return Tr8n\Config::instance()->application->host . '/login';
}

function tr8n_signup_url() {
    return Tr8n\Config::instance()->application->host . '/signup';
}
