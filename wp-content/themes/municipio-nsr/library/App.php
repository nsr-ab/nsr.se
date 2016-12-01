<?php
namespace Nsr;

class App
{
    public function __construct()
    {
        new \Nsr\Theme\Enqueue();
        new \Nsr\Theme\SidebarsExtended();
        new \Nsr\Theme\NSRTemplates();


        add_action( 'after_setup_theme', array( $this, 'nsr_theme_setup' ) );
        add_action( 'init', array( $this,'add_excerpts_to_pages' ) );
    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function nsr_theme_setup()
    {
        load_child_theme_textdomain( 'nsr', get_stylesheet_directory() . '/languages' );
    }


    /**
     *  add_excerpts_to_pages
     *  Adding Excerpts to page
     */
    public function add_excerpts_to_pages() {
        add_post_type_support( 'page', 'excerpt' );
    }





}
