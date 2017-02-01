<?php

namespace Nsr\Theme;

class Enqueue
{
    public function __construct()
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'style'));
        add_action('wp_enqueue_scripts', array($this, 'script'));

        add_action( 'wp_enqueue_scripts', function() {
            wp_deregister_style('font-awesome');
        }, -1 );

        add_action( 'login_enqueue_scripts', array($this,'style' ) );
        add_action('init', array($this,'jquery_init'));



    }

    /**
     * Enqueue styles
     * @return void
     */
    public function style()
    {
        wp_register_style('hbg-prime', 'https://helsingborg-stad.github.io/styleguide-web-cdn/styleguide.dev/dist/css/hbg-prime.min.css', '', '1.0.0');
        wp_enqueue_style('hbg-prime');

        wp_enqueue_style('Nsr-css', get_stylesheet_directory_uri(). '/assets/dist/css/app.min.css', '', filemtime(get_stylesheet_directory() . '/assets/dist/css/app.min.css'));

        wp_register_style('Nsr-fontawsome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', '1.0.0');
        wp_enqueue_style('Nsr-fontawsome');
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function script()
    {

        apply_filters('Municipio/load-wp-jquery', true, 10, 2);
        wp_enqueue_script('Nsr-js', get_stylesheet_directory_uri(). '/assets/dist/js/app.min.js', '', filemtime(get_stylesheet_directory() . '/assets/dist/js/app.min.js'), true);

    }


    public function jquery_init()
    {
        if (!is_admin())
        {
            //wp_deregister_script('jquery');
            wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js', FALSE, '3.1.0', TRUE);
            wp_enqueue_script('jquery');
        }
    }



}
