<?php

/**
 * Plugin Name:       NSR Visual Composer Extended
 * Plugin URI:        nsr.se
 * Description:       Visual Composer Ad-dons for NSR website
 * Version:           1.0.0
 * Author:            Johan Silvergrund
 * Author URI:        hiq.se
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       nsr-vc-extended
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('NSR_VC_EXTENDED_PATH', plugin_dir_path(__FILE__));
define('NSR_VC_EXTENDED_URL', plugins_url('', __FILE__));
define('NSR_VC_EXTENDED_TEMPLATE_PATH', NSR_VC_EXTENDED_PATH . 'source/php/Templates/');

load_plugin_textdomain('nsr-vc-extended', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once NSR_VC_EXTENDED_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once NSR_VC_EXTENDED_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new VcExtended\Vendor\Psr4ClassLoader();
$loader->addPrefix('VcExtended', NSR_VC_EXTENDED_PATH);
$loader->addPrefix('VcExtended', NSR_VC_EXTENDED_PATH . 'source/php/');
$loader->register();

// Start application
new VcExtended\App();
