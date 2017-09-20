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
        \Municipio\Helper\Template::add(
            __('Sida utan rubrik', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('page-vc.blade.php')
        );

        \Municipio\Helper\Template::add(
            __('Sida utan rubrik (Startsida)', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('page-vcnosidebar.blade.php')
        );

    }


    /**
     * Set background class
     * @return void
     */
    public function setBackgroundColor()
    {
        add_filter('body_class', array($this, 'my_body_class'));
    }

    function my_body_class($color)
    {

        global $post;
        if (!is_object($post))
            return;
        $selectBackground = get_post_meta($post->ID, 'post_backgroundcolor', true);
        switch ($selectBackground) {
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
        if (is_single() && is_singular(array('post'))) {
            $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                            <a itemprop="item" href="/" title="Start">
                                <span itemprop="name">Start</span>
                                <meta itemprop="position" content="-0" />
                            </a>
                        </li>';
            $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                            <span itemprop="name">Nyheter</span>
                            <meta itemprop="position" content="-0" />
                        </li>';
        }
        if (!is_front_page() && is_singular(array('page', 'villa', 'fastighet', 'foretag', 'faq'))) {
            if (is_single() && !is_singular(array('page', 'villa', 'fastighet', 'foretag', 'faq'))) {
                $output[] = '<li>' . get_the_title() . '</li>';
            } elseif (is_category()) {
                $output[] = '<li>' . get_the_category() . '</li>';
            }
            if (is_page() || is_singular(array('page', 'villa', 'fastighet', 'foretag', 'faq'))) {

                $showStart = true;

                if (is_page('Företag & Verksamheter'))
                    $showStart = false;
                if (is_page('Fastighetsägare & Bostadsrättsföreningar'))
                    $showStart = false;
                if (is_singular('foretag'))
                    $showStart = false;
                if(is_singular('fastighet'))
                    $showStart = false;

                if ($showStart) {

                    $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                                <a itemprop="item" href="/" title="Start">
                                    <span itemprop="name">Start</span>
                                    <meta itemprop="position" content="-0" />
                                </a>
                            </li>';
                }




                if ($post->post_parent) {
                    $anc = array_reverse(get_post_ancestors($post->ID), true);
                    $title = get_the_title();

                    $int = 1;

                    if (is_singular(array('villa', 'fastighet', 'foretag', 'faq'))) {

                        $output[] .= '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">';
                        if (is_singular(array('villa'))) {
                            $output[] .= '<a itemprop="item" href="' . site_url() . '/villa/" title="Villa">
                                                <span itemprop="name">Villa</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        if (is_singular(array('fastighet'))) {
                            $output[] .= '<a itemprop="item" href="' . site_url() . '/fastighet/" title="Fastighet">
                                                <span itemprop="name">Fastighet</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        if (is_singular(array('foretag'))) {
                            $output[] .= '<a itemprop="item" href="' . site_url() . '/foretag/" title="Företag">
                                                <span itemprop="name">Företag</span>
                                                <meta itemprop="position" content="-0" />';
                        }
                        if (is_singular(array('faq'))) {
                            $output[] .= '<a itemprop="item" href="' . site_url() . '/faq/" title="F&S">
                                                <span itemprop="name">F&S</span>
                                                <meta itemprop="position" content="-0" />';
                        }
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
                            <meta itemprop="position" content="' . ($int + 1) . '" />
                          </li>';

                } else {
                    if (is_singular(array('villa', 'fastighet', 'foretag', 'faq'))) {
                        $crumbName = is_singular(array('villa')) ? 'Villa' : '';
                        $crumbName = is_singular(array('fastighet')) ? 'Fastighet' : $crumbName;
                        $crumbName = is_singular(array('foretag')) ? 'Företag' : $crumbName;
                        $crumbName = is_singular(array('faq')) ? 'F&S' : $crumbName;
                        $output[] = '<li itemscope itemprop="itemListElement" itemtype="http://schema.org/ListItem">
                                        <a itemprop="item" href="/' . $post->post_type . '" title="' . $crumbName . '">
                                            <span itemprop="name">' . $crumbName . '</span>
                                            <meta itemprop="position" content="-0" />
                                        </a>
                                    </li>';
                    }
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

