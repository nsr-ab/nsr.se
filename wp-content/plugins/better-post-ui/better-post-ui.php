<?php

/**
 * Plugin Name:       Better Post UI
 * Plugin URI:        (#plugin_url#)
 * Description:       Improves the UI and UX of the WordPress admin post form.
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        (#plugin_author_url#)
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       better-post-ui
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('BETTERPOSTUI_PATH', plugin_dir_path(__FILE__));
define('BETTERPOSTUI_URL', plugins_url('', __FILE__));
define('BETTERPOSTUI_TEMPLATE_PATH', BETTERPOSTUI_PATH . 'templates/');

load_plugin_textdomain('better-post-ui', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once BETTERPOSTUI_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once BETTERPOSTUI_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new BetterPostUi\Vendor\Psr4ClassLoader();
$loader->addPrefix('BetterPostUi', BETTERPOSTUI_PATH);
$loader->addPrefix('BetterPostUi', BETTERPOSTUI_PATH . 'source/php/');
$loader->register();

// Start application
new BetterPostUi\App();
