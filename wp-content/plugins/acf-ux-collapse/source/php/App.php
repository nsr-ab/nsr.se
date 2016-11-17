<?php

namespace AcfUxCollapse;

class App
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('acf-collapser', ACFUXCOLLAPSE_URL . '/dist/css/acf-ux-collapse.min.css', null, '1.0.0');
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('acf-collapser', ACFUXCOLLAPSE_URL . '/dist/js/acf-ux-collapse.min.js', null, '1.0.0', true);
    }
}
