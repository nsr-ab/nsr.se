<?php

namespace Municipio\Theme;

class ImageSizeFilter
{
    public function __construct()
    {
        add_filter('jpeg_quality', array($this, 'setImageQuality'));
        add_filter('wp_editor_set_quality', array($this, 'setImageQuality'));
    }

    public function setImageQuality($quaility)
    {
        return 95;
    }

    public static function removeFilter($hook_name = '', $method_name = '', $priority = 0)
    {
        global $wp_filter;

        // Take only filters on right hook name and priority
        if (!isset($wp_filter[$hook_name][$priority]) || !is_array($wp_filter[$hook_name][$priority])) {
            return false;
        }

        // Loop on filters registered
        foreach ((array) $wp_filter[$hook_name][$priority] as $unique_id => $filter_array) {
            // Test if filter is an array ! (always for class/method)
            if (isset($filter_array['function']) && is_array($filter_array['function'])) {
                // Test if object is a class and method is equal to param !
                if (is_object($filter_array['function'][0]) && get_class($filter_array['function'][0]) && $filter_array['function'][1] == $method_name) {
                    unset($wp_filter[$hook_name][$priority][$unique_id]);
                }
            }
        }

        return false;
    }
}
