<?php

/**
 * OpenHours ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- Puff --
 * A Visual composer ad-don to create brick with content.
 *
 */

namespace VcExtended\Library\Addons;

class OpenHours
{
    var $dependency_error = false;

    function __construct()
    {

        if (!function_exists('get_field')) {
            echo '<div class="notice notice-error">';
            echo '<p>';
            _e("Open hours require ACF PRO to function. Please make shure that this is installed and enabled.", 'nsr-open-hours');
            echo '</p>';
            echo '</div>';
            $this->dependency_error = true;
        }


        if ( !class_exists( '\openhours\Options' ) ) {
            echo '<div class="notice notice-error">';
            echo '<p>';
            _e("NSR OpenHours plugin is required for Visual Composer OpenHour ad-don to work. Please make shure that this is installed and enabled.", 'nsr-open-hours');
            echo '</p>';
            echo '</div>';
            $this->dependency_error = true;
        }

        if( !$this->dependency_error ) {
            add_action('init', array($this, 'integrateWithVC'));
            add_shortcode('nsr_vcOpenHours', array($this, 'renderExtend'));
        }
    }


    /**
     * Available parameters
     * @return array
     */
    static function params($stack)
    {
        return array(


            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type'      => 'textfield',
                'heading' => __('Title', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',

            ),

            /** @Param Type parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Type', 'nsr-vc-extended'),
                'param_name' => 'vc_type',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => array(
                    __('Today', 'nsr-vc-extended')   => 'today',
                    __('Show 7 days', 'nsr-vc-extended')   => 'week',
                    __('Exceptions', 'nsr-vc-extended')   => 'holiday',
                ),
            ),

            /** @Param Type parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Location', 'nsr-vc-extended'),
                'param_name' => 'vc_location',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => $stack,
            ),


            /** @Param Type parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Align time', 'nsr-vc-extended'),
                'param_name' => 'vc_align',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => array(
                    __('Left', 'nsr-vc-extended')   => 'left',
                    __('Right', 'nsr-vc-extended')   => 'right',
                ),
            ),


            /** @Param Type parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Date Size', 'nsr-vc-extended'),
                'param_name' => 'vc_date_size',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => array(
                    __('Short', 'nsr-vc-extended')   => 'short',
                    __('Full', 'nsr-vc-extended')   => 'full',
                ),
            ),



        );

    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        $stack = array();
        $oph_sections = get_field('oph_sections', 'option');
        if(isset($oph_sections)) {
            foreach ($oph_sections as $city) {
                $stack[$city['location']] = substr(md5($city['location']), 0, 6);
            }
        }

        vc_map(array(

                'name' => __('NSR OpenHours', 'nsr-vc-extended'),
                'description' => __('Add Openening hours', 'nsr-vc-extended'),
                'base' => 'nsr_vcOpenHours',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => false,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_puff_links.svg' ),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) ),
                'params' => $this->params($stack)
            )
        );


    }



    /**
     * Logic behind Ad-don output
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function renderExtend($atts, $content = null)
    {

        if(!$this->dependency_error) {

            $params['title'] = isset($atts['vc_designation']) ? $params['title'] = $atts['vc_designation'] : null;
            $params['type'] = isset($atts['vc_type']) ? $params['type'] = $atts['vc_type'] : null;
            $params['section'] = isset($atts['vc_location']) ? $params['section'] = $atts['vc_location'] : null;
            $params['align'] = isset($atts['vc_align']) ? $params['align'] = $atts['vc_align'] : null;
            $params['date_size'] = isset($atts['vc_date_size']) ? $params['date_size'] = $atts['vc_date_size'] : null;

            return do_shortcode('[opening-hours  datesize="'.$params['date_size'].'" city="'.$params['title'].'" type="' . $params['type'] . '" align="'.$params['align'].'" section="' . $params['section'] . '" markup="true"]');

        }

    }

}




