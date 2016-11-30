<?php

/**
 * WMenuCollapsible ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ
 *
 */

namespace VcExtended;

class App
{
    public function __construct()
    {
        /**
         * Ad-dons Collapsible menus
         */
        if ( !class_exists( 'MenuCollapsible' ) ) {
            new \VcExtended\Library\MenuCollapsible();
        }

        /**
         * Hooks
         */
        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueStylesAdmin'));
        add_action( 'after_setup_theme', array( $this, 'after_nsr_theme_setup' ) );
    }


    /**
     * Enqueue required style Admin
     * @return void
     */
    public function enqueueStylesAdmin()
    {
        wp_register_style( 'vc_extend_style-admin', plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) );
        wp_enqueue_style( 'vc_extend_style-admin' );
    }


    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_register_style( 'vc_extend_style', plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended.min.css' ) );
        wp_enqueue_style( 'vc_extend_style' );
        wp_register_style( 'vc_material-css', plugins_url( 'nsr-vc-extended/dist/css/vc-material/vc_material.css' ) );
        wp_enqueue_style( 'vc_material-css' );
    }

    /**
     * Enqueue required scripts Admin
     * @return void
     */
    public function enqueueScriptsAdmin()
    {

        if (is_admin()) {
            wp_register_script('nsr-extended-admin', plugins_url('nsr-vc-extended/dist/js/nsr-vc-extended.min.js'));
            wp_enqueue_script('nsr-extended-admin');
        }
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        if (!is_admin()) {
            wp_register_script( 'nsr-extended', plugins_url( 'nsr-vc-extended/dist/js/nsr-vc-extended.min.js'), null, null, true);
            wp_enqueue_script( 'nsr-extended' );
        }
    }



    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function after_nsr_theme_setup()
    {
        add_action( 'wp_enqueue_scripts', array($this, 'enqueueScripts') );
        add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsAdmin'));


    }
}
