<?php

namespace Nsr\Theme;

class Enqueue
{
    public function __construct()
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'style'));
        add_action('wp_enqueue_scripts', array($this, 'script'));

    }

    /**
     * Enqueue styles
     * @return void
     */
    public function style()
    {
        wp_register_style('hbg-prime', 'http://helsingborg-stad.github.io/styleguide-web-cdn/styleguide.dev/dist/css/hbg-prime.min.css', '', '1.0.0');
        wp_enqueue_style('hbg-prime');
        wp_enqueue_style('Nsr-css', get_stylesheet_directory_uri(). '/assets/dist/css/app.min.css', '', filemtime(get_stylesheet_directory() . '/assets/dist/css/app.min.css'));

    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function script()
    {

        //wp_register_script('jquery', 'https://code.jquery.com/jquery-2.2.4.min.js',  true);
        //wp_enqueue_script('jquery');

        apply_filters('Municipio/load-wp-jquery', true, 10, 2);
        wp_enqueue_script('Nsr-js', get_stylesheet_directory_uri(). '/assets/dist/js/app.min.js', '', filemtime(get_stylesheet_directory() . '/assets/dist/js/app.min.js'), true);


    }



}
