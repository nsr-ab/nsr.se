<?php

namespace CustomShortLinks;

class App
{
    public function __construct()
    {
        new \CustomShortLinks\Shortlinks();
        new \CustomShortLinks\Enqueue();

        add_action('init', array($this, 'init'));
        add_filter('acf/settings/load_json', array($this, 'jsonLoadPath'));
    }

    public function init()
    {
        if (!file_exists(WP_CONTENT_DIR . '/mu-plugins/AcfImportCleaner.php') && !class_exists('\\AcfImportCleaner\\AcfImportCleaner')) {
            require_once CUSTOMSHORTLINKS_PATH . 'source/php/Helper/AcfImportCleaner.php';
        }
    }

    public function jsonLoadPath($paths)
    {
        $paths[] = CUSTOMSHORTLINKS_PATH . 'acf-exports';
        return $paths;
    }
}
