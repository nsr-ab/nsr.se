<?php

/**
 * Plugin Name:       NSR OpenHours
 * Plugin URI:        https://github.com/nsr-ab/nsr.se
 * Description:       Adds the ability to add opening hours and display them via shortcodes (today, full week)
 * Version:           1.5.1
 * Author:            Sebastian Thulin, Johan Silvergrund
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       opening-hours
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('OPENHOURS_PATH', plugin_dir_path(__FILE__));
define('OPENHOURS_URL', plugins_url('', __FILE__));
define('OPENHOURS_TEMPLATE_PATH', OPENHOURS_PATH . 'templates/');

load_plugin_textdomain('nsr-open-hours', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once OPENHOURS_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once OPENHOURS_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new openhours\Vendor\Psr4ClassLoader();
$loader->addPrefix('openhours', OPENHOURS_PATH);
$loader->addPrefix('openhours', OPENHOURS_PATH . 'source/php/');
$loader->register();

// Start application
new openhours\App();

//Options page
new openhours\OpenHoursOptions();
