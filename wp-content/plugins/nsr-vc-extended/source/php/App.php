<?php

namespace VcExtended;

class App
{
    public function __construct()
    {


        /**
         * Ad-dons
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

    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {

    }


    /**
     *  nsr_theme_setup
     *  Adding language file
     */
    public function after_nsr_theme_setup()
    {

    }

}
