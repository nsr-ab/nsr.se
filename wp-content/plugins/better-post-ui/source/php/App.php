<?php

namespace BetterPostUi;

class App
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        new \BetterPostUi\Components\Author();
        new \BetterPostUi\Components\PageAttributes();
        new \BetterPostUi\Components\Order();
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_enqueue_style('better-post-ui', BETTERPOSTUI_URL . '/dist/css/better-post-ui.min.css', null, '1.0.0');
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_enqueue_script('better-post-ui', BETTERPOSTUI_URL . '/dist/js/better-post-ui.min.js', null, '1.0.0', true);
    }
}
