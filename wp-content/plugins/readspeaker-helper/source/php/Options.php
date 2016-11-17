<?php

namespace ReadSpeakerHelper;

class Options
{
    public function __construct()
    {
        if (function_exists('acf_add_options_page')) {
            $args = array(
                'page_title'    => __('ReadSpeaker', 'readspeaker-helper'),
                'menu_title'    => __('ReadSpeaker', 'readspeaker-helper'),
                'menu_slug'     => 'readspeaker-helper',
                'parent_slug'   => 'options-general.php',
                'capability'    => 'manage_options',
                'redirect'      => false,
                'icon_url'      => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTYuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjE2cHgiIGhlaWdodD0iMTZweCIgdmlld0JveD0iMCAwIDkzLjAzOCA5My4wMzgiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDkzLjAzOCA5My4wMzg7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4KPGc+Cgk8cGF0aCBkPSJNNDYuNTQ3LDc1LjUyMWMwLDEuNjM5LTAuOTQ3LDMuMTI4LTIuNDI5LDMuODIzYy0wLjU3MywwLjI3MS0xLjE4NywwLjQwMi0xLjc5NywwLjQwMmMtMC45NjYsMC0xLjkyMy0wLjMzMi0yLjY5Ni0wLjk3MyAgIGwtMjMuMDk4LTE5LjE0SDQuMjI1QzEuODkyLDU5LjYzNSwwLDU3Ljc0MiwwLDU1LjQwOVYzOC41NzZjMC0yLjMzNCwxLjg5Mi00LjIyNiw0LjIyNS00LjIyNmgxMi4zMDNsMjMuMDk4LTE5LjE0ICAgYzEuMjYyLTEuMDQ2LDMuMDEyLTEuMjY5LDQuNDkzLTAuNTY5YzEuNDgxLDAuNjk1LDIuNDI5LDIuMTg1LDIuNDI5LDMuODIzTDQ2LjU0Nyw3NS41MjFMNDYuNTQ3LDc1LjUyMXogTTYyLjc4NCw2OC45MTkgICBjLTAuMTAzLDAuMDA3LTAuMjAyLDAuMDExLTAuMzA0LDAuMDExYy0xLjExNiwwLTIuMTkyLTAuNDQxLTIuOTg3LTEuMjM3bC0wLjU2NS0wLjU2N2MtMS40ODItMS40NzktMS42NTYtMy44MjItMC40MDgtNS41MDQgICBjMy4xNjQtNC4yNjYsNC44MzQtOS4zMjMsNC44MzQtMTQuNjI4YzAtNS43MDYtMS44OTYtMTEuMDU4LTUuNDg0LTE1LjQ3OGMtMS4zNjYtMS42OC0xLjI0LTQuMTIsMC4yOTEtNS42NWwwLjU2NC0wLjU2NSAgIGMwLjg0NC0wLjg0NCwxLjk3NS0xLjMwNCwzLjE5OS0xLjIzMWMxLjE5MiwwLjA2LDIuMzA1LDAuNjIxLDMuMDYxLDEuNTQ1YzQuOTc3LDYuMDksNy42MDYsMTMuNDg0LDcuNjA2LDIxLjM4ICAgYzAsNy4zNTQtMi4zMjUsMTQuMzU0LTYuNzI1LDIwLjI0QzY1LjEzMSw2OC4yMTYsNjQuMDA3LDY4LjgzMiw2Mi43ODQsNjguOTE5eiBNODAuMjUyLDgxLjk3NiAgIGMtMC43NjQsMC45MDMtMS44NjksMS40NDUtMy4wNTIsMS40OTVjLTAuMDU4LDAuMDAyLTAuMTE3LDAuMDA0LTAuMTc3LDAuMDA0Yy0xLjExOSwwLTIuMTkzLTAuNDQyLTIuOTg4LTEuMjM3bC0wLjU1NS0wLjU1NSAgIGMtMS41NTEtMS41NS0xLjY1Ni00LjAyOS0wLjI0Ni01LjcwN2M2LjgxNC04LjEwNCwxMC41NjgtMTguMzk2LDEwLjU2OC0yOC45ODJjMC0xMS4wMTEtNC4wMTktMjEuNjExLTExLjMxNC0yOS44NDcgICBjLTEuNDc5LTEuNjcyLTEuNDA0LTQuMjAzLDAuMTctNS43ODNsMC41NTQtMC41NTVjMC44MjItMC44MjYsMS44OS0xLjI4MSwzLjExNS0xLjI0MmMxLjE2MywwLjAzMywyLjI2MywwLjU0NywzLjAzNiwxLjQxNyAgIGM4LjgxOCw5LjkyOCwxMy42NzUsMjIuNzE4LDEzLjY3NSwzNi4wMUM5My4wNCw1OS43ODMsODguNDk5LDcyLjIwNyw4MC4yNTIsODEuOTc2eiIgZmlsbD0iI0ZGRkZGRiIvPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+CjxnPgo8L2c+Cjwvc3ZnPgo='
            );

            if (is_multisite() && App::$multisiteLoad) {
                if (is_main_site()) {
                    acf_add_options_page($args);
                }
            } else {
                acf_add_options_page($args);
            }
        }

        add_filter('acf/load_field/name=readspeaker-helper-enable-posttypes', array($this, 'loadPostTypesToCheckbox'), 10, 3);
    }

    public function loadPostTypesToCheckbox($field)
    {
        $postTypes = array();
        foreach (get_post_types() as $key => $postType) {
            $args = get_post_type_object($postType);

            if (!$args->public) {
                continue;
            }

            $postTypes[$postType] = $args->label;
        }

        $field['choices'] = $postTypes;
        return $field;
    }
}
