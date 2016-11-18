<?php

namespace Nsr\Theme;

class SidebarsExtended
{
    public function __construct()
    {
        add_action('widgets_init', array($this, 'replaceSidebar'), 11);
    }

    public function replaceSidebar()
    {
        /**
         * Footer Area
         */

        unregister_sidebar('footer-area');

        if( class_exists('acf') ) {
            $footerBeforeWidgetClass = get_field('footer_layout', 'option') == 'compressed' ? 'grid-lg-6' : 'grid-lg-3 grid-md-3 grid-sm-6';
        }
        else {
            $footerBeforeWidgetClass = 'grid-lg-3 grid-md-3 grid-sm-6';
        }

        register_sidebar(array(
            'id'            => 'footer-area',
            'name'          => __('Footer', 'municipio'),
            'description'   => __('The footer area', 'municipio'),
            'before_widget' => '<div class="' . $footerBeforeWidgetClass . '"><div class="%2$s">',
            'after_widget'  => '</div></div>',
            'before_title'  => '<h2 class="footer-title">',
            'after_title'   => '</h2>'
        ));
    }
}
