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
