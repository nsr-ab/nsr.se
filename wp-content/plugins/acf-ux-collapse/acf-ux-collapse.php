<?php

/**
 * Plugin Name:       ACF UX Collapse
 * Plugin URI:        http://github.com/helsingborg-stad/ACF-UX-collapse
 * Description:       Imrpoves the ACF repeater-field UX by adding collapsing top-level functionality
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:        http://github.com/helsingborg-stad
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       advanced-custom-fields-collapser
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('ACFUXCOLLAPSE_PATH', plugin_dir_path(__FILE__));
define('ACFUXCOLLAPSE_URL', plugins_url('', __FILE__));
define('ACFUXCOLLAPSE_TEMPLATE_PATH', ACFUXCOLLAPSE_PATH . 'templates/');

load_plugin_textdomain('advanced-custom-fields-collapser', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once ACFUXCOLLAPSE_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once ACFUXCOLLAPSE_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new AcfUxCollapse\Vendor\Psr4ClassLoader();
$loader->addPrefix('AcfUxCollapse', ACFUXCOLLAPSE_PATH);
$loader->addPrefix('AcfUxCollapse', ACFUXCOLLAPSE_PATH . 'source/php/');
$loader->register();

// Start application
new AcfUxCollapse\App();
