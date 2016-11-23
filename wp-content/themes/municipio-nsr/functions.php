<?php

    define('NSR_PATH', get_stylesheet_directory() . '/');

    //Include vendor files
    if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
        require_once dirname(ABSPATH) . '/vendor/autoload.php';
    }

    require_once NSR_PATH . 'library/Vendor/Psr4ClassLoader.php';
    $loader = new NSR\Vendor\Psr4ClassLoader();
    $loader->addPrefix('Nsr', NSR_PATH . 'library');
    $loader->addPrefix('Nsr', NSR_PATH . 'source/php/');
    $loader->register();

    new Nsr\App();

    /**
     * Visual Composer Ad-dons
     */
    require_once ABSPATH  . 'wp-admin/includes/plugin.php';

/*

    if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
        if (file_exists(NSR_PATH . 'plugins/visual-composer-nsr-addons/')) {
            define('NSR_VC_PATH', NSR_PATH . 'plugins/visual-composer-nsr-addons/');
        }

        if (file_exists(NSR_PATH . 'plugins/visual-composer-nsr-addons/vc-menu-collapsible/vc-menu-collapsible.php')) {
            require_once NSR_PATH . 'plugins/visual-composer-nsr-addons/vc-menu-collapsible/vc-menu-collapsible.php';
            new VCMenuCollapsible();
        }
    }


*/
