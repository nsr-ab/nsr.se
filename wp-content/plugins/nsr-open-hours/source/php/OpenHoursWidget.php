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
        /*if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }*/

        echo $instance['title'];

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
                        
                        <label for=\"".esc_attr( $this->get_field_id( 'title' ) )."\">
                            ".__( 'Title:', 'nsr-open-hours' )."
                        </label>
                        <input class=\"widefat\"
                               id=\"".esc_attr( $this->get_field_id( 'title' ) )."\"
                               name=\"".esc_attr( $this->get_field_name( 'title' ) )."\"
                               type=\"text\" value=\"".esc_attr( $title )."\"> 
                        
                        
                        </p>
                        
                        <p>
                       
                        <label for=\"".esc_attr( $this->get_field_id( 'type' ) )."\">
                            ".__( 'Type:', 'nsr-open-hours' )."
                        </label>
                
                        <select 
                        name=\"".esc_attr( $this->get_field_name( 'type' ) )."\" 
                        class='widefat'
                        id=\"".$this->get_field_id('type')."\">
                            <option value=\"today\" ";
                        if($type === "today")
                            $output .= "selected='selected'";
                        $output .= ">Visa idag</option>
                                            <option value=\"week\"";
                        if($type === "week")
                            $output .= "selected='selected'";
                        $output .= ">Visa veckans tider</option>
                                            <option value=\"exceptions\"";
                        if($type === "exceptions")
                            $output .= "selected='selected'";
                        $output .= ">Visa Helgdagar</option>
                                        </select>
                                               
                                    </p>";

                        $options = new \openhours\Options();
                        $sections = $options->popChoices();

                        var_dump($sections);

                        foreach($sections['choices'] as $city){
                            echo $city;
                        }

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
        return $instance;
    }

}