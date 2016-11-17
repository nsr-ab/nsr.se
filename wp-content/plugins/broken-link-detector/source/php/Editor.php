<?php

namespace BrokenLinkDetector;

class Editor
{
    public function __construct()
    {
        add_filter('mce_external_plugins', array($this, 'registerMcePlugin'));
        add_action('admin_footer', array($this, 'getBrokenLinks'));
    }

    public function registerMcePlugin()
    {
        global $post;
        if (isset($post) || !empty($post->ID)) {
            $plugins['brokenlinksdetector'] = BROKENLINKDETECTOR_URL . '/dist/js/mce-broken-link-detector.min.js';
        }

        return $plugins;
    }

    public function getBrokenLinks()
    {
        global $post;

        if (!isset($post) || empty($post->ID)) {
            return;
        }

        \BrokenLinkDetector\App::checkInstall();
        $urls = \BrokenLinkDetector\ListTable::getBrokenLinks($post->ID);

        echo '<script>
                var broken_links = [
        ';

        $count = 0;
        foreach ($urls as $item) {
            echo "'" . $item->url . "'," . "\n";
            $count++;
        }

        echo '];</script>';
    }
}
