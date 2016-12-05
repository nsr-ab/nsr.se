<?php

/**
 * NSR Visual Composer Extended
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
         * Check if Visual composer is activated
         */
        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'showVcVersionNotice'));
            return;
        }

        /**
         * Master methods
         */
        if ( !class_exists( 'MasterVCExtended' ) ) {
            new \VcExtended\Library\MasterVCExtended();
        }


        /**
         * Ad-dons Collapsible menus
         */
        if ( !class_exists( 'MenuCollapsible' ) ) {
            new \VcExtended\Library\MenuCollapsible();
        }

        /**
         * Ad-dons List News & Posts
         */
        if ( !class_exists( 'ListNewsAndPosts' ) ) {
            new \VcExtended\Library\ListNewsAndPosts();
        }

        /**
         * Ad-dons List links with a thumbnail
         */
        if ( !class_exists( 'ListLinksWithThumbnail' ) ) {
            new \VcExtended\Library\ListLinksWithThumbnail();
        }


        /**
         * Scripts & CSS
         */
        add_action('wp_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueStylesAdmin'));
        add_action( 'after_setup_theme', array( $this, 'after_nsr_theme_setup' ) );


    }

    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice()
    {

        echo '
        <div class="notice notice-error is-dismissible">
          <p>' . __('<strong>NSR Visual Composer Extended</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'nsr-vc-extended') . '</p>
        </div>';
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


