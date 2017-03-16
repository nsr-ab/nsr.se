<?php

namespace Nsr\Theme;

class Enqueue
{
    public function __construct()
    {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'style'));
        add_action('wp_enqueue_scripts', array($this, 'script'));

        add_action('wp_enqueue_scripts', function () {
            wp_deregister_style('font-awesome');
        }, -1);

        add_action('login_enqueue_scripts', array($this, 'style'));
        //add_action('wp_enqueue_scripts', array($this, 'add_Captcha'));

    }

    /**
     * Enqueue styles
     * @return void
     */
    public function style()
    {

        wp_deregister_style( 'hbg-prime' );

        wp_register_style('hbg-prime', get_stylesheet_directory_uri() . '/assets/dist/css/hbg-prime.min.css', '', '1.0.0');
        wp_enqueue_style('hbg-prime');

        wp_register_style('hbg-prime', get_stylesheet_directory_uri() . '/assets/dist/css/hbg-prime-green.min.css', '', '1.0.0');
        wp_enqueue_style('hbg-prime');

        wp_enqueue_style('Nsr-css', get_stylesheet_directory_uri() . '/assets/dist/css/app.min.css', '', filemtime(get_stylesheet_directory() . '/assets/dist/css/app.min.css'));

        wp_register_style('Nsr-fontawsome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', '', '1.0.0');
        wp_enqueue_style('Nsr-fontawsome');
    }

    /**
     * Enqueue scripts
     * @return void
     */
    public function script()
    {

        wp_deregister_script( 'hbg-prime' );
        wp_enqueue_script('hbg-prime', get_stylesheet_directory_uri() . '/assets/dist/js/hbg-prime.min.js', '', filemtime(get_stylesheet_directory() . '/assets/dist/js/app.min.js'), true);

        apply_filters('Municipio/load-wp-jquery', true, 10, 2);
        wp_enqueue_script('Nsr-js', get_stylesheet_directory_uri() . '/assets/dist/js/app.min.js', '', filemtime(get_stylesheet_directory() . '/assets/dist/js/app.min.js'), true);

        /*if (!apply_filters('Municipio/load-wp-jquery', false)) {
            wp_deregister_script('jquery');
            wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js', array('jquery'), null, true);
        }*/
    }



    /**
     * Google Recaptcha
     * @return void
     */
    /*public function add_Captcha(){
        wp_register_script('recaptcha', 'https://www.google.com/recaptcha/api.js', '', '1.0.0');
        wp_enqueue_script('recaptcha');
    }*/


}
