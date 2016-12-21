<?php

namespace openhours;

class OpenHoursWidget extends \WP_Widget{


    /**
     * Sets up the widgets name etc
     */
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
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        echo esc_html__( 'Hello, World!', 'text_domain' );
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     * @return void
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'NSR Helsingborg', 'text_domain' );

        $output = "<p>
                        <label for=\"".esc_attr( $this->get_field_id( 'title' ) )."\">
                            ".esc_attr_e( 'Title:', 'text_domain' )."
                        </label>
                        <input class=\"widefat\"
                               id=\"".esc_attr( $this->get_field_id( 'title' ) )."\"
                               name=\"".esc_attr( $this->get_field_name( 'title' ) )."\"
                               type=\"text\" value=\"".esc_attr( $title )."\">
                    </p>";
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

        return $instance;
    }

}