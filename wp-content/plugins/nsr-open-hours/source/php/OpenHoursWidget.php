<?php

namespace openhours;

class OpenHoursWidget extends \WP_Widget{


    public function __construct() {

        $widget_ops = array(
            'classname' => 'OpenHoursWidget',
            'description' => 'Display opening hours by location',
        );
        parent::__construct( 'OpenHoursWidget', 'NSR OpenHours', $widget_ops );

    }


    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        $options = new \openhours\Options();
        $sections = $options->popChoices();
        echo do_shortcode( '[opening-hours city="'.$sections['choices'][$instance['section']].'" type="'.$instance['type'].'" section="'.$instance['section'].'" markup="true"]');
        echo $args['after_widget'];
    }


    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Helsingborg', 'nsr-open-hours' );
        $type = ! empty( $instance['type'] ) ? $instance['type'] : $instance['type'];

        $output = "<p></p>
                        <p>
                        
                        <label for=\"" . esc_attr($this->get_field_id('title')) . "\">
                            " . __('Title:', 'nsr-open-hours') . "
                        </label>
                        <input class=\"widefat\"
                               id=\"" . esc_attr($this->get_field_id('title')) . "\"
                               name=\"" . esc_attr($this->get_field_name('title')) . "\"
                               type=\"text\" value=\"" . esc_attr($title) . "\"> 
                        
                        
                        </p>
                        
                        <p>
                       
                        <label for=\"" . esc_attr($this->get_field_id('type')) . "\">
                            " . __('Type:', 'nsr-open-hours') . "
                        </label>
                
                        <select 
                        name=\"" . esc_attr($this->get_field_name('type')) . "\" 
                        class='widefat'
                        id=\"" . $this->get_field_id('type') . "\">
                            <option value=\"today\" ";
        if ($type === "today")
            $output .= "selected='selected'";
        $output .= ">Visa idag</option>
                                            <option value=\"week\"";
        if ($type === "week")
            $output .= "selected='selected'";
        $output .= ">Visa veckans tider</option>
                                            <option value=\"exceptions\"";
        if ($type === "exceptions")
            $output .= "selected='selected'";
        $output .= ">Visa Helgdagar</option>
                                        </select>
                                               
                                    </p>";

        $options = new \openhours\Options();
        $sections = $options->popChoices();

        $output .= "<p><label for=\"" . esc_attr($this->get_field_id('section')) . "\">
                      " . __('Stad/Ort:', 'nsr-open-hours') . "
                                    </label>";
        $output .= "<select name=\"" . esc_attr($this->get_field_name('section')) . "\" 
                                    class='widefat'
                                    id=\"" . $this->get_field_id('section') . "\">";

        foreach ($sections['choices'] as $city) {
            $output .= "<option value='" . substr(md5($city), 0, 6) . "' " . selected($instance['section'], substr(md5($city), 0, 6)) . " >";
            $output .= $city;
            $output .= "</option>";
        }
        $output .= "</select> ";
        $output .= "</p>";

        echo $output;
    }


    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     * @return void
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : '';
        $instance['section'] = ( ! empty( $new_instance['section'] ) ) ? strip_tags( $new_instance['section'] ) : '';
        return $instance;
    }

}