<?php

namespace openhours;

class Options extends App
{
    public function __construct()
    {
        add_action('init', array($this, 'registerOptionsPage'));
        add_action('init', array($this, 'registerOptionsOnPage'));
        add_action('acf/load_field/key=field_56d9a4880cd89', array($this, 'printCurrentDayDataMeta'));
    }

    public function printCurrentDayDataMeta($field)
    {
        $field['label'] = __("Shortcode", 'opening-hours-slug')." <em>[opening-hours]</em> ".__("will print out information displayed below.", 'opening-hours-slug');
        $field['message'] = $this->getTodaysOpeningHours();
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
            'page_title'    => __("NSR OpenHours", 'opening-hours-slug'),
            'menu_title'    => __("NSR OpenHours", 'opening-hours-slug'),
            'menu_slug'     => 'open-hours-settings',
            'capability'    => 'edit_posts',
            'icon_url'     => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB3aWR0aD0iNTEycHgiIGhlaWdodD0iNTEycHgiIHZpZXdCb3g9IjAgMCA3NS42OTUgNzUuNjk1IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA3NS42OTUgNzUuNjk1OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZD0iTTc1LjY5NSwzNy44NDZjMCwyMC44NjktMTYuOTgsMzcuODUtMzcuODQ4LDM3Ljg1QzE2Ljk4MSw3NS42OTUsMCw1OC43MTUsMCwzNy44NDZDMCwxNi45NzcsMTYuOTgxLDAsMzcuODQ4LDAgICBjNy42MjgsMCwxNS4wNTUsMi4zMzEsMjEuMzEsNi41OTJsNS44MTYtNS44MTdsNC42NzksMTcuOTQ2bC0xNy45NDktNC42NzhsNC4wNjktNC4wNzJjLTUuMzE5LTMuNDIyLTExLjUzOC01LjMtMTcuOTI5LTUuMyAgIGMtMTguMjk0LDAtMzMuMTc2LDE0Ljg4Mi0zMy4xNzYsMzMuMTc0YzAsMTguMjk0LDE0Ljg4MiwzMy4xNzgsMzMuMTc2LDMzLjE3OGMxOC4yOTMsMCwzMy4xNzUtMTQuODg0LDMzLjE3NS0zMy4xNzhINzUuNjk1eiAgICBNMjguNDI5LDM4LjE5MWMtMy4xODYsMi4yNDMtNS4zNTgsNC4xNzgtNi41MTEsNS44MTFjLTEuMTU0LDEuNjI5LTEuNzM0LDMuNTkxLTEuNzM0LDUuODgxdjAuMDA3aDE3LjA0NHYtNC4zMjdIMjYuMjkgICBsMC4wMTctMC4wMzZjMC44MjctMS4xNTIsMi4zNjMtMi40NDUsNC42MTYtMy44NzRjMi40MzItMS41NTYsNC4wOTItMi45NTQsNC45ODQtNC4xOTdjMC44ODMtMS4yNSwxLjMyMi0yLjgyMiwxLjMyMi00LjcxNiAgIGMwLTIuMzE0LTAuNzc2LTQuMTk5LTIuMzI3LTUuNjYyYy0xLjU3NC0xLjQ2NC0zLjU3OC0yLjE5My02LjA0LTIuMTkzYy0yLjYzNywwLTQuNzE4LDAuNzkzLTYuMjEyLDIuMzgxICAgYy0xLjUwMiwxLjU5My0yLjIxMywzLjczNy0yLjEzNSw2LjQ1NGg0LjgxNmMtMC4wNDYtMS40NiwwLjI0Ni0yLjYwNiwwLjg3Ni0zLjQ1NUMyNi44MzksMjkuNDIsMjcuNzEsMjksMjguODMsMjkgICBjMS4wNDMsMCwxLjg5NiwwLjMzOSwyLjUzMiwxLjAxNmMwLjY0NSwwLjY3MywwLjk1OSwxLjU2MSwwLjk1OSwyLjY3MmMwLDEuMDIxLTAuMjkyLDEuOTM5LTAuODc0LDIuNzczICAgQzMwLjg2OSwzNi4yODUsMjkuODY0LDM3LjIwMSwyOC40MjksMzguMTkxeiBNNDguNTg3LDQ5Ljg4OHYtNS41MDdoLTkuODl2LTIuMTI2di0yLjEyMWw5LjIzNy0xNS4yNDJoMi43NDZoMi43NDRWNDAuNDhoMi44MTIgICB2My45MDVoLTIuODEydjUuNTA2TDQ4LjU4Nyw0OS44ODhMNDguNTg3LDQ5Ljg4OHogTTQ4LjU4Nyw0MC40NzZWMzAuMDYybC02LjA2MywxMC4wNzFsLTAuMjA1LDAuMzQySDQ4LjU4N3ogTTM5LjM3OSw4LjUwOGgtMi4zMzYgICB2NC42ODNoMi4zMzZWOC41MDh6IE0zOS4zNzksNjEuNThoLTIuMzM2djQuNjgzaDIuMzM2VjYxLjU4eiBNNjcuMDg2LDM4LjU1M3YtMi4zMzZoLTQuNjg1djIuMzM2SDY3LjA4NnogTTE0LjAxNSwzOC41NTN2LTIuMzM2ICAgSDkuMzM0djIuMzM2SDE0LjAxNXoiIGZpbGw9IiNGRkZGRkYiLz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+'
            ));
        }
    }

    public function multipleSections(){
        return array (  array('section_id' => 1, 'section_name' => 'Helsingborg'),
                        array('section_id' => 2, 'section_name' => 'Höganäs'),
                        array('section_id' => 3, 'section_name' => 'Ängelholm')
        );


    }


    public function registerOptionsOnPage()
    {
        if (function_exists('acf_add_local_field_group')):


            foreach($this->multipleSections() as $section){

                acf_add_local_field_group(array(
                    'key' => 'group_56d9834986648_' . $section['section_id'],
                    'title' => $section['section_name'] . ' Open Hours (exceptions)',
                    'fields' => array(
                        array(
                            'key' => 'field_56d98368ebaf6_'. $section['section_id'],
                            'label' => 'Exeptions in open hours',
                            'name' => 'oph_exeptions_'.$section['section_id'],
                            'type' => 'repeater',
                            'instructions' => 'Add one or more exceptions to this scheme. ',
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
                            'button_label' => 'Add exception',
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_56d9863b80865_'. $section['section_id'],
                                    'label' => 'Datum',
                                    'name' => 'date_'.$section['section_id'],
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
                                    'key' => 'field_56d9866980865_'. $section['section_id'],
                                    'label' => 'Exeption title',
                                    'name' => 'ex_title_'.$section['section_id'],
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
                                    'placeholder' => 'eg. Juldagen',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                    'readonly' => 0,
                                    'disabled' => 0,
                                ),
                                array(
                                    'key' => 'field_56d9866980866_'. $section['section_id'],
                                    'label' => 'Exeption information',
                                    'name' => 'ex_info_'.$section['section_id'],
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
                                    'placeholder' => 'eg. 08:00 - 16:00',
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
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => 1,
                    'description' => '',
                ));


                acf_add_local_field_group(array(
                    'key' => 'group_56d97cfb40e8f_'. $section['section_id'],
                    'title' => $section['section_name']. ' Opening Hours',
                    'fields' => array(
                        array(
                            'key' => 'field_56d97d49daceb_'. $section['section_id'],
                            'label' => 'Hours monday',
                            'name' => 'oph_mon_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d985ad34d5c_'. $section['section_id'],
                            'label' => 'Hours tuesday',
                            'name' => 'oph_tue_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d985c2f3f99_'. $section['section_id'],
                            'label' => 'Hours wednesday',
                            'name' => 'oph_wed_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d985e9f3f9a_'. $section['section_id'],
                            'label' => 'Hours thursday',
                            'name' => 'oph_thu_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d985faf3f9b_'. $section['section_id'],
                            'label' => 'Hours friday',
                            'name' => 'oph_fri_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d98607f3f9c_'. $section['section_id'],
                            'label' => 'Hours saturday',
                            'name' => 'oph_sat_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
                            'prepend' => '',
                            'append' => '',
                            'maxlength' => '',
                            'readonly' => 0,
                            'disabled' => 0,
                        ),
                        array(
                            'key' => 'field_56d98611f3f9d_'. $section['section_id'],
                            'label' => 'Hours sunday',
                            'name' => 'oph_sun_'.$section['section_id'],
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
                            'placeholder' => 'eg. 08:00 - 15:00',
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
                    'menu_order' => 0,
                    'position' => 'normal',
                    'style' => 'default',
                    'label_placement' => 'top',
                    'instruction_placement' => 'label',
                    'hide_on_screen' => '',
                    'active' => 1,
                    'description' => '',
                ));


            acf_add_local_field_group(array(
                'key' => 'group_56d9a47c90e25_'. $section['section_id'],
                'title' => $section['section_name'].', Todays opening hours',
                'fields' => array(
                    array(
                        'key' => 'field_56d9a4880cd89_'. $section['section_id'],
                        'label' => 'Shortcode:<br><em>[opening-hours section="'.$section['section_id'].'"]</em><br />will print out information displayed below.',
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
                'menu_order' => 0,
                'position' => 'side',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

        }

        endif;
    }
}
