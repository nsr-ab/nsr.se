<?php

namespace Nsr\Theme;

class NSRTemplates
{
    public function __construct()
    {
        add_action('after_setup_theme', array($this, 'addVCPageTemplate'));
    }

    public function addVCPageTemplate()
    {

        /*\Municipio\Helper\Template::add(
            __('Sida utan rubrik med sidebar', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('vc-page.blade.php')
        );

        \Municipio\Helper\Template::add(
            __('Sida utan rubrik utan sidebar (Startsida)', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('vc-page-nosidebar.blade.php')
        );*/

    }


    /**
     * Set background class
     * @return void
     */
    public function setBackgroundColor()
    {
        add_filter( 'body_class', array($this,'my_body_class' ));
    }

    function my_body_class( $color ) {

        global $post;
        $selectBackground = get_post_meta($post->ID, 'post_backgroundcolor', true);
        switch($selectBackground){
            case 0:
                $color[] = 'nsr-background-white';
                break;
            case 1:
                $color[] = 'nsr-background-grey';
                break;
            default:
                $color[] = 'nsr-background-white';
        }

        return $color;
    }



    /**
     * Outputs the html for the breadcrumb
     * @return void
     */
    public static function outputBreadcrumbs()
    {
        global $post;

        $title = get_the_title();
        $output = array();

        echo '<ol class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">';

        if (!is_front_page()) {
            if (is_single() && !is_singular( array( 'villa', 'fastighet', 'foretag' ) )) {
                $output[] = '<li>' . get_the_title() . '</li>';
            } elseif (is_category()) {
                $output[] = '<li>' . get_the_category() . '</li>';
            }
            if (is_page() || is_singular( array( 'villa', 'fastighet', 'foretag' ) ) )  {

                if ($post->post_parent) {
                    $anc = get_post_ancestors($post->ID);
                    $title = get_the_title();

                    $int = 1;

                    $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                                            <a itemprop="item" href="/" title="Start">
                                                <span itemprop="name">Start</span>
                                                <meta itemprop="position" content="-0" />
                                            </a>
                                       </li>';

                    if( is_singular( array( 'villa', 'fastighet', 'foretag' ) ) ) {

                        $output[] .= '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">';
                        if (is_singular(array('villa'))) {
                            $output[] .= '<a itemprop="item" href="'.site_url().'/villa/" title="Start">
                                                <span itemprop="name">Villa</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        if (is_singular(array('fastighet'))) {
                            $output[] .= '<a itemprop="item" href="'.site_url().'/fastighet/" title="Start">
                                                <span itemprop="name">Fastighet</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        if (is_singular(array('foretag'))) {
                            $output[] .= '<a itemprop="item" href="'.site_url().'/foretag/" title="Start">
                                                <span itemprop="name">Företag</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        $output[] .= '<meta itemprop="position" content="-0" /></a></li>';
                    }

                    foreach ($anc as $ancestor) {
                        if (get_post_status($ancestor) != 'private') {
                            $output[] .= '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                                            <a itemprop="item" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">
                                                <span itemprop="name">' . get_the_title($ancestor) . '</span>
                                                <meta itemprop="position" content="' . $int . '" />
                                            </a>
                                       </li>';
                            $int++;
                        }
                    }

                    $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                            <span itemprop="name" class="breadcrumbs-current" title="' . $title . '">' . $title . '</span>
                            <meta itemprop="position" content="' . ($int+1) . '" />
                          </li>';
                } else {
                    $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                            <span itemprop="name" class="breadcrumbs-current">' . get_the_title() . '</span>
                            <meta itemprop="position" content="1" />
                          </li>';
                }
            }
        }

        $output = apply_filters('Municipio-Nsr/Breadcrumbs/Items', $output, get_queried_object());

        echo implode("\n", $output);
        echo '</ol>';
    }




}

