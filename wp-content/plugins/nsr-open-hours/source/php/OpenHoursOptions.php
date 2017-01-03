<?php

namespace NsrOpenHours;

class OpenHoursOptions extends App
{
    public function __construct()
    {
        add_action('init', array($this, 'registerOptionsPage'));
        add_action('init', array($this, 'registerOptionsOnPage'));
        add_action('acf/load_field/key=field_56d9a4880cd89', array($this, 'printCurrentDayDataMeta'));

    }


    /**
     * Sidebar info
     * @return void
     */
    public function printCurrentDayDataMeta($field)
    {
        $field['label'] = __("Shortcode", 'opening-hours-slug') . " <em>[opening-hours]</em> " . __("will print out information displayed below.", 'nsr-open-hours');
        $field['message'] = $this->getOpeningHours();
        return $field;
    }


    /**
     * Register options page
     * @return void
     */
    public function registerOptionsPage()
    {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page(array(
                'page_title' => __("NSR OpenHours", 'nsr-open-hours'),
                'menu_title' => __("NSR OpenHours", 'nsr-open-hours'),
                'menu_slug' => 'open-hours-settings',
                'capability' => 'edit_posts',
                'icon_url' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiIHZpZXdCb3g9IjAgMCA3NS42OTUgNzUuNjk1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA3NS42OTUgNzUuNjk1OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZD0iTTc1LjY5NSwzNy44NDZjMCwyMC44NjktMTYuOTgsMzcuODUtMzcuODQ4LDM3Ljg1QzE2Ljk4MSw3NS42OTUsMCw1OC43MTUsMCwzNy44NDZDMCwxNi45NzcsMTYuOTgxLDAsMzcuODQ4LDAgICBjNy42MjgsMCwxNS4wNTUsMi4zMzEsMjEuMzEsNi41OTJsNS44MTYtNS44MTdsNC42NzksMTcuOTQ2bC0xNy45NDktNC42NzhsNC4wNjktNC4wNzJjLTUuMzE5LTMuNDIyLTExLjUzOC01LjMtMTcuOTI5LTUuMyAgIGMtMTguMjk0LDAtMzMuMTc2LDE0Ljg4Mi0zMy4xNzYsMzMuMTc0YzAsMTguMjk0LDE0Ljg4MiwzMy4xNzgsMzMuMTc2LDMzLjE3OGMxOC4yOTMsMCwzMy4xNzUtMTQuODg0LDMzLjE3NS0zMy4xNzhINzUuNjk1eiAgICBNMjguNDI5LDM4LjE5MWMtMy4xODYsMi4yNDMtNS4zNTgsNC4xNzgtNi41MTEsNS44MTFjLTEuMTU0LDEuNjI5LTEuNzM0LDMuNTkxLTEuNzM0LDUuODgxdjAuMDA3aDE3LjA0NHYtNC4zMjdIMjYuMjkgICBsMC4wMTctMC4wMzZjMC44MjctMS4xNTIsMi4zNjMtMi40NDUsNC42MTYtMy44NzRjMi40MzItMS41NTYsNC4wOTItMi45NTQsNC45ODQtNC4xOTdjMC44ODMtMS4yNSwxLjMyMi0yLjgyMiwxLjMyMi00LjcxNiAgIGMwLTIuMzE0LTAuNzc2LTQuMTk5LTIuMzI3LTUuNjYyYy0xLjU3NC0xLjQ2NC0zLjU3OC0yLjE5My02LjA0LTIuMTkzYy0yLjYzNywwLTQuNzE4LDAuNzkzLTYuMjEyLDIuMzgxICAgYy0xLjUwMiwxLjU5My0yLjIxMywzLjczNy0yLjEzNSw2LjQ1NGg0LjgxNmMtMC4wNDYtMS40NiwwLjI0Ni0yLjYwNiwwLjg3Ni0zLjQ1NUMyNi44MzksMjkuNDIsMjcuNzEsMjksMjguODMsMjkgICBjMS4wNDMsMCwxLjg5NiwwLjMzOSwyLjUzMiwxLjAxNmMwLjY0NSwwLjY3MywwLjk1OSwxLjU2MSwwLjk1OSwyLjY3MmMwLDEuMDIxLTAuMjkyLDEuOTM5LTAuODc0LDIuNzczICAgQzMwLjg2OSwzNi4yODUsMjkuODY0LDM3LjIwMSwyOC40MjksMzguMTkxeiBNNDguNTg3LDQ5Ljg4OHYtNS41MDdoLTkuODl2LTIuMTI2di0yLjEyMWw5LjIzNy0xNS4yNDJoMi43NDZoMi43NDRWNDAuNDhoMi44MTIgICB2My45MDVoLTIuODEydjUuNTA2TDQ4LjU4Nyw0OS44ODhMNDguNTg3LDQ5Ljg4OHogTTQ4LjU4Nyw0MC40NzZWMzAuMDYybC02LjA2MywxMC4wNzFsLTAuMjA1LDAuMzQySDQ4LjU4N3ogTTM5LjM3OSw4LjUwOGgtMi4zMzYgICB2NC42ODNoMi4zMzZWOC41MDh6IE0zOS4zNzksNjEuNThoLTIuMzM2djQuNjgzaDIuMzM2VjYxLjU4eiBNNjcuMDg2LDM4LjU1M3YtMi4zMzZoLTQuNjg1djIuMzM2SDY3LjA4NnogTTE0LjAxNSwzOC41NTN2LTIuMzM2ICAgSDkuMzM0djIuMzM2SDE0LjAxNXoiIGZpbGw9IiNGRkZGRkYiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+'
            ));
        }
    }


    /**
     * populate Combo
     * @return array
     */
    function popChoices()
    {

        $field['choices'] = array();
        $oph_sections = get_field('oph_sections', 'option');
        if(isset($oph_sections)) {
            foreach ($oph_sections as $section) {
                $locId = substr(md5($section['location']), 0, 6);
                $field['choices'][$locId] = $section['location'];
            }

            return $field;
        }
    }


    /**
     * Register custom fields
     * @return void
     */
    public function registerOptionsOnPage()
    {
        if (function_exists('acf_add_local_field_group')):

            acf_add_local_field_group(array(
                'key' => 'group_locations',
                'title' => ' NSR locations',
                'fields' => array(
                    array(
                        'key' => 'field_56d9dfdf3Df6',
                        'label' => __("Add Location", 'nsr-open-hours'),
                        'name' => 'oph_sections',
                        'type' => 'repeater',
                        'instructions' => __("Add one or more location. (Click on update after you added the location to fetch location scheme)", 'nsr-open-hours'),
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'collapsed' => '',
                        'min' => '',
                        'max' => '',
                        'layout' => 'table',
                        'button_label' => __("Add location", 'nsr-open-hours'),
                        'sub_fields' => array(
                            array(
                                'key' => 'field_DsJsd0m4rvx',
                                'label' => __("NSR has facilities on following locations", 'nsr-open-hours'),
                                'name' => 'location',
                                'type' => 'text',
                                'instructions' => '',
                                'required' => '',
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => 50,
                                    'class' => '',
                                    'id' => '',
                                ),

                            ),
                            array (
                                'post_type' => array (
                                ),
                                'taxonomy' => array (
                                ),
                                'allow_null' => 0,
                                'multiple' => 0,
                                'allow_archives' => 1,
                                'key' => 'field_5865200cd37b9',
                                'label' => 'Länk till sida',
                                'name' => 'link_to_page',
                                'type' => 'page_link',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array (
                                    'width' => 50,
                                    'class' => '',
                                    'id' => '',
                                ),
                            ),
                        ),
                    ),

                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'open-hours-settings',
                        ),
                    ),
                ),
                'menu_order' => 1,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));


            acf_add_local_field_group(array(
                'key' => 'group_select_section',
                'title' => __("Select location to edit opening hours", 'nsr-open-hours'),
                'fields' => array(
                    array(
                        'key' => 'field_select',
                        'label' => __("Location", 'nsr-open-hours'),
                        'name' => 'location-name',
                        'type' => 'select',
                        'choices' => array(
                            $this->popChoices()
                        ),
                    ),

                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'open-hours-settings',
                        ),
                    ),
                ),
                'menu_order' => 2,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

            $oph_sections = get_field('oph_sections', 'option');

            if (is_array($oph_sections) && !empty($oph_sections)) {
                foreach ($oph_sections as $section) {
                    $locId = substr(md5($section['location']), 0, 6);
                    acf_add_local_field_group(array(
                        'key' => 'group_exception_' . $locId,
                        'title' =>  __("Open Hours (exceptions)", 'nsr-open-hours') . " " . $section['location'],
                        'fields' => array(
                            array(
                                'key' => 'field_56d98368ebaf6_' . $locId,
                                'label' => __("Exeptions in open hours", 'nsr-open-hours'),
                                'name' => 'oph_exeptions_' . $locId,
                                'type' => 'repeater',
                                'instructions' => __("Add one or more exceptions to this scheme. ", 'nsr-open-hours'),
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'collapsed' => '',
                                'min' => '',
                                'max' => '',
                                'layout' => 'table',
                                'button_label' => __("Add exception", 'nsr-open-hours'),
                                'sub_fields' => array(
                                    array(
                                        'key' => 'field_56d9863b80865_' . $locId,
                                        'label' => __("Date", 'nsr-open-hours'),
                                        'name' => 'date_' . $locId,
                                        'type' => 'date_picker',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => 25,
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'display_format' => 'j F, Y',
                                        'return_format' => 'Y-m-d',
                                        'first_day' => 1,
                                    ),
                                    array(
                                        'key' => 'field_56d9866980865_' . $locId,
                                        'label' => __("Exeption title", 'nsr-open-hours'),
                                        'name' => 'ex_title_' . $locId,
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => 35,
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => __("eg. Juldagen", 'nsr-open-hours'),
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                        'readonly' => 0,
                                        'disabled' => 0,
                                    ),
                                    array(
                                        'key' => 'field_56d9866980866_' . $locId,
                                        'label' => __("Exeption information", 'nsr-open-hours'),
                                        'name' => 'ex_info_' . $locId,
                                        'type' => 'text',
                                        'instructions' => '',
                                        'required' => 1,
                                        'conditional_logic' => 0,
                                        'wrapper' => array(
                                            'width' => 40,
                                            'class' => '',
                                            'id' => '',
                                        ),
                                        'default_value' => '',
                                        'placeholder' => __("eg. (Closed)", 'nsr-open-hours'),
                                        'prepend' => '',
                                        'append' => '',
                                        'maxlength' => '',
                                        'readonly' => 0,
                                        'disabled' => 0,
                                    ),
                                ),
                            ),
                        ),
                        'location' => array(
                            array(
                                array(
                                    'param' => 'options_page',
                                    'operator' => '==',
                                    'value' => 'open-hours-settings',
                                ),
                            ),
                        ),
                        'menu_order' => 3,
                        'position' => 'normal',
                        'style' => 'default',
                        'label_placement' => 'top',
                        'instruction_placement' => 'label',
                        'hide_on_screen' => '',
                        'active' => 1,
                        'description' => '',
                    ));


                    acf_add_local_field_group(array(
                        'key' => 'group_hours_' . $locId,
                        'title' => __(" Opening Hours", 'nsr-open-hours') . " " . $section['location'] ,
                        'fields' => array(
                            array(
                                'key' => 'field_56d97d49daceb_' . $locId,
                                'label' => __("Hours monday", 'nsr-open-hours'),
                                'name' => 'oph_mon_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d985ad34d5c_' . $locId,
                                'label' => __("Hours tuesday", 'nsr-open-hours'),
                                'name' => 'oph_tue_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d985c2f3f99_' . $locId,
                                'label' => __("Hours wednesday", 'nsr-open-hours'),
                                'name' => 'oph_wed_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d985e9f3f9a_' . $locId,
                                'label' => __("Hours thursday", 'nsr-open-hours'),
                                'name' => 'oph_thu_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d985faf3f9b_' . $locId,
                                'label' => __("Hours friday", 'nsr-open-hours'),
                                'name' => 'oph_fri_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d98607f3f9c_' . $locId,
                                'label' => __("Hours saturday", 'nsr-open-hours'),
                                'name' => 'oph_sat_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                            array(
                                'key' => 'field_56d98611f3f9d_' . $locId,
                                'label' => __("Hours sunday", 'nsr-open-hours'),
                                'name' => 'oph_sun_' . $locId,
                                'type' => 'text',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'default_value' => '',
                                'placeholder' => __("eg. 08:00 - 15:00", 'nsr-open-hours'),
                                'prepend' => '',
                                'append' => '',
                                'maxlength' => '',
                                'readonly' => 0,
                                'disabled' => 0,
                            ),
                        ),
                        'location' => array(
                            array(
                                array(
                                    'param' => 'options_page',
                                    'operator' => '==',
                                    'value' => 'open-hours-settings',
                                ),
                            ),
                        ),
                        'menu_order' => 3,
                        'position' => 'normal',
                        'style' => 'default',
                        'label_placement' => 'top',
                        'instruction_placement' => 'label',
                        'hide_on_screen' => '',
                        'active' => 1,
                        'description' => '',
                    ));


                    acf_add_local_field_group(array(
                        'key' => 'group_shortcode_' . $locId,
                        'title' =>  __("Todays opening hours", 'nsr-open-hours') . " " . $section['location'],
                        'fields' => array(
                            array(
                                'key' => 'field_56d9a4880cd89_' . $locId,
                                'label' => '<div>' . __("Use Visual Composer or openHours Widget to display openHours: ", 'nsr-open-hours') . '</div>',
                                'name' => '',
                                'type' => 'message',
                                'instructions' => '',
                                'required' => 0,
                                'conditional_logic' => 0,
                                'wrapper' => array(
                                    'width' => '',
                                    'class' => '',
                                    'id' => '',
                                ),
                                'message' => '',
                                'new_lines' => '',
                                'esc_html' => 1,
                            ),
                        ),
                        'location' => array(
                            array(
                                array(
                                    'param' => 'options_page',
                                    'operator' => '==',
                                    'value' => 'open-hours-settings',
                                ),
                            ),
                        ),
                        'menu_order' => 3,
                        'position' => 'side',
                        'style' => 'default',
                        'label_placement' => 'top',
                        'instruction_placement' => 'label',
                        'hide_on_screen' => '',
                        'active' => 1,
                        'description' => '',
                    ));

                }
            }

        endif;

    }
}
