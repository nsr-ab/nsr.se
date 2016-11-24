<?php

namespace VcExtended;

class App
{
    public function __construct()
    {

        /**
         * Ad-dons Collapsible menus
         */
        new \VcExtended\Library\MenuCollapsible();


        /**
         * Hooks
         */
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action( 'after_setup_theme', array( $this, 'after_nsr_theme_setup' ) );

    }



    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_register_style( 'vc_extend_style', plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended.min.css' ) );
        wp_enqueue_style( 'vc_extend_style' );
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script( 'ajax-script', plugins_url('nsr-vc-extended/dist/js/nsr-vc-extended.min.js'), array('jquery') );

        wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'johan' => 'is best' ) );
       // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value


    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function after_nsr_theme_setup()
    {

    }

}
