<?php

namespace NsrOpenHours;

class OpenHoursWidget extends \WP_Widget
{


    public function __construct()
    {

        $widget_ops = array(
            'classname' => 'OpenHoursWidget',
            'description' => 'Display opening hours by location',
        );

        parent::__construct('OpenHoursWidget', 'NSR OpenHours', $widget_ops);
    }


    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        echo $args['before_widget'];
        $options = new \NsrOpenHours\OpenHoursOptions();
        $sections = $options->popChoices();
        $city = $sections['choices'][$instance['section']];
        echo do_shortcode('[opening-hours datesize="' . $instance['date_size'] . '" align="' . $instance['align'] . '" city="' . $instance['title'] . '" type="' . $instance['type'] . '" section="' . $instance['section'] . '" markup="true"]');
        echo $args['after_widget'];
    }


    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     * @return void
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Helsingborg', 'nsr-open-hours');
        $type = !empty($instance['type']) ? $instance['type'] : $instance['type'];
        $date_size = !empty($instance['date_size']) ? $instance['date_size'] : $instance['date_size'];
        $align = !empty($instance['align']) ? $instance['align'] : $instance['align'];

        $output = "<p></p>
                        <p>
                        
                        <label for=\"" . esc_attr($this->get_field_id('title')) . "\">" . __('Title:', 'nsr-open-hours') . "</label>
                        <input class=\"widefat\" id=\"" . esc_attr($this->get_field_id('title')) . "\" 
                               name=\"" . esc_attr($this->get_field_name('title')) . "\" type=\"text\" 
                               value=\"" . esc_attr($title) . "\"> 
                        </p>
                        
                        <p>
                        <label for=\"" . esc_attr($this->get_field_id('type')) . "\">
                            " . __('Type:', 'nsr-open-hours') . "
                        </label>
                
                        <select name=\"" . esc_attr($this->get_field_name('type')) . "\" class='widefat' id=\"" . $this->get_field_id('type') . "\">
                            <option value=\"today\" ";

        if ($type === "today")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Show todays opening hours', 'nsr-open-hours') ."</option><option value=\"week\"";

        if ($type === "week")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Show 7 days', 'nsr-open-hours') ."</option><option value=\"exceptions\"";

        if ($type === "exceptions")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Show exceptions', 'nsr-open-hours') ."</option></select></p>";

        $output .= "<p>
                        <label for=\"" . esc_attr($this->get_field_id('align')) . "\">
                            " . __('Align hours:', 'nsr-open-hours') . "
                        </label>";

        $output .= "<select name=\"" . esc_attr($this->get_field_name('align')) . "\" class='widefat' id=\"" . $this->get_field_id('align') . "\">
                            <option value=\"left\" ";

        if ($align === "left")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Left', 'nsr-open-hours') ."</option><option value=\"right\"";

        if ($align === "right")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Right', 'nsr-open-hours') ."</option></select></p>";


        $output .= "<p>
                        <label for=\"" . esc_attr($this->get_field_id('date_size')) . "\">
                            " . __('Dateformat:', 'nsr-open-hours') . "
                        </label>";

        $output .= "<select name=\"" . esc_attr($this->get_field_name('date_size')) . "\" class='widefat' id=\"" . $this->get_field_id('date_size') . "\">
                            <option value=\"left\" ";

        if ($date_size === "short")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Short', 'nsr-open-hours') ."</option><option value=\"full\"";

        if ($date_size === "full")
            $output .= "selected=\"selected\"";
        $output .= ">". __('Full', 'nsr-open-hours') ."</option></select></p>";






        $options = new \NsrOpenHours\OpenHoursOptions();
        $sections = $options->popChoices();

        $output .= "<p><label for=\"" . esc_attr($this->get_field_id('section')) . "\">" . __('City:', 'nsr-open-hours') . "</label>";
        $output .= "<select name=\"" . esc_attr($this->get_field_name('section')) . "\" class='widefat' id=\"" . $this->get_field_id('section') . "\">";

        foreach ($sections['choices'] as $city) {
            $selected = ($instance['section'] === substr(md5($city), 0, 6)) ? 'selected="selected"' : null;
            $output .= "<option value=\"" . substr(md5($city), 0, 6) . "\" " . $selected . " >";
            $output .= $city;
            $output .= "</option>";
        }
        $output .= "</select> ";

        echo $output;
    }


    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     * @return void
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['type'] = (!empty($new_instance['type'])) ? strip_tags($new_instance['type']) : '';
        $instance['align'] = (!empty($new_instance['align'])) ? strip_tags($new_instance['align']) : '';
        $instance['date_size'] = (!empty($new_instance['date_size'])) ? strip_tags($new_instance['date_size']) : '';
        $instance['section'] = (!empty($new_instance['section'])) ? strip_tags($new_instance['section']) : '';
        return $instance;
    }

}