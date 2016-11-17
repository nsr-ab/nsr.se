<?php

/**
 * Plugin Name:       Custom Short Links
 * Plugin URI:
 * Description:       Create short links for urls
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       custom-short-links
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (!defined('WPINC')) {
    die;
}

define('CUSTOMSHORTLINKS_PATH', plugin_dir_path(__FILE__));
define('CUSTOMSHORTLINKS_URL', plugins_url('', __FILE__));
define('CUSTOMSHORTLINKS_TEMPLATE_PATH', CUSTOMSHORTLINKS_PATH . 'templates/');

load_plugin_textdomain('custom-short-links', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once CUSTOMSHORTLINKS_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once CUSTOMSHORTLINKS_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new CustomShortLinks\Vendor\Psr4ClassLoader();
$loader->addPrefix('CustomShortLinks', CUSTOMSHORTLINKS_PATH);
$loader->addPrefix('CustomShortLinks', CUSTOMSHORTLINKS_PATH . 'source/php/');
$loader->register();

// Start application
new CustomShortLinks\App();
