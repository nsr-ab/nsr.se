<?php

namespace VcExtended\Library;


class Enqueue
{

    public function __construct()
    {


        /**
         * Scripts & CSS
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
        wp_register_style('vc_extend_style-admin', plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css'));
        wp_enqueue_style('vc_extend_style-admin');
    }


    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {

        if(NSR_DEVMODE === 1 || NSR_DEVMODE === true) {
            wp_register_style('vc_extend_style', plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended.dev.css'));
        } else {
            wp_register_style('vc_extend_style', plugins_url('nsr-vc-extended/dist/css/nsr-vc-extended.min.css'));
        }

        wp_enqueue_style('vc_extend_style');
        wp_register_style('vc_material-css', plugins_url('nsr-vc-extended/dist/css/vc-material/vc_material.css'));
        wp_enqueue_style('vc_material-css');
    }


    /**
     * Enqueue required scripts Admin
     * @return void
     */
    public function enqueueScriptsAdmin()
    {

        if (is_admin()) {
            wp_register_script('nsr-extended-admin', plugins_url('nsr-vc-extended/dist/js/nsr-vc-extended-admin.min.js'));
            wp_enqueue_script('nsr-extended-admin');
        }
    }


    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {


        wp_register_script('matchHeight', plugins_url('nsr-vc-extended/dist/js/jquery.matchHeight-min.js'), '', '', true);
        wp_enqueue_script('matchHeight');
        
        wp_register_script('vcExtended', plugins_url('nsr-vc-extended/dist/js/nsr-vc-extended.min.js'), '', '', true);


        wp_localize_script(
            'vcExtended',
            'ajax_object',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'query' => 'NSR',
                'nonce' => wp_create_nonce('wp_rest'),
                'is_user_logged_in' => is_user_logged_in(),
                'searchAutocomplete' => array(
                    'persons' => __('Persons', 'nsr-vc-extended'),
                    'content' => __('Content', 'nsr-vc-extended'),
                    'viewAll' => __('View all results', 'nsr-vc-extended')
                )
            )
        );
        wp_enqueue_script('vcExtended');


    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function after_nsr_theme_setup()
    {

        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScriptsAdmin'));

    }

}
