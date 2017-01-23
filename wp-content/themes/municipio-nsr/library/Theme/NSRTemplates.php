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
            __('Sida utan rubrik med sidebar', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('vc-page.blade.php')
        );

        \Municipio\Helper\Template::add(
            __('Sida utan rubrik utan sidebar (Startsida)', 'NSRTemplates'),
            \Municipio\Helper\Template::locateTemplate('vc-page-nosidebar.blade.php')
        );

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
            if (is_single()) {
                $output[] = '<li>' . get_the_title() . '</li>';
            } elseif (is_category()) {
                $output[] = '<li>' . get_the_category() . '</li>';
            } elseif (is_page()) {
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

