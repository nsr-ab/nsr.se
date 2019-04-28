<?php

/**
 * Plugin Name:       ElasticPress Synonyms
 * Plugin URI:
 * Description:       Enables synonyms wordlist for ElasticPress
 * Version:           1.0.0
 * Author:            Kristoffer Svanmark
 * Author URI:
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       elasticpress-synonyms
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('ELASTICPRESS_SYNONYMS_PATH', plugin_dir_path(__FILE__));
define('ELASTICPRESS_SYNONYMS_URL', plugins_url('', __FILE__));
define('ELASTICPRESS_SYNONYMS_TEMPLATE_PATH', ELASTICPRESS_SYNONYMS_PATH . 'templates/');

load_plugin_textdomain('elasticpress-synonyms', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once ELASTICPRESS_SYNONYMS_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once ELASTICPRESS_SYNONYMS_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new ElasticPressSynonyms\Vendor\Psr4ClassLoader();
$loader->addPrefix('ElasticPressSynonyms', ELASTICPRESS_SYNONYMS_PATH);
$loader->addPrefix('ElasticPressSynonyms', ELASTICPRESS_SYNONYMS_PATH . 'source/php/');
$loader->register();

// Start application
new ElasticPressSynonyms\App();
