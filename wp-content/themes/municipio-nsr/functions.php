<?php

    define('NSR_PATH', get_stylesheet_directory() . '/');

    //Include vendor files
    if (file_exists(dirname(ABSPATH) . '/vendor/autoload.php')) {
        require_once dirname(ABSPATH) . '/vendor/autoload.php';
    }

    // Added by Fredrik to allow VC's Role Manager to select all post types
    add_action("vc_before_init", "Use_wpBakery");
    function Use_wpBakery() {
        $vc_list = array("page","capabilities","post","villa","foretag","fastighet","faq","sorteringsguide","wptm_file","vcmegafooter");
        vc_set_default_editor_post_types($vc_list);
        vc_editor_set_post_types($vc_list);
    }
    
    require_once NSR_PATH . 'library/Vendor/Psr4ClassLoader.php';
    $loader = new NSR\Vendor\Psr4ClassLoader();
    $loader->addPrefix('Nsr', NSR_PATH . 'library');
    $loader->addPrefix('Nsr', NSR_PATH . 'source/php/');
    $loader->register();

    new Nsr\App();
