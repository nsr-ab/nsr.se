<?php

/**
 * Plugin Name:       Broken Link Detector
 * Plugin URI:        (#plugin_url#)
 * Description:       Detects and fixes (if possible) broken links in post_content
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        (#plugin_author_url#)
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       broken-link-detector
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('BROKENLINKDETECTOR_PATH', plugin_dir_path(__FILE__));
define('BROKENLINKDETECTOR_URL', plugins_url('', __FILE__));
define('BROKENLINKDETECTOR_TEMPLATE_PATH', BROKENLINKDETECTOR_PATH . 'templates/');

load_plugin_textdomain('broken-link-detector', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once __DIR__ . '/source/php/Vendor/admin-notice-helper.php';
require_once BROKENLINKDETECTOR_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once BROKENLINKDETECTOR_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new BrokenLinkDetector\Vendor\Psr4ClassLoader();
$loader->addPrefix('BrokenLinkDetector', BROKENLINKDETECTOR_PATH);
$loader->addPrefix('BrokenLinkDetector', BROKENLINKDETECTOR_PATH . 'source/php/');
$loader->register();

// Start application
$brokenLinkDetectorApp = new BrokenLinkDetector\App();

register_activation_hook(__FILE__, '\BrokenLinkDetector\App::install');
register_deactivation_hook(__FILE__, '\BrokenLinkDetector\App::uninstall');
