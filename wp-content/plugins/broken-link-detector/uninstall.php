<?php
/**
* Fired when the plugin is uninstalled.
*/

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;
$brokenLinkDetectorApp->uninstall();
