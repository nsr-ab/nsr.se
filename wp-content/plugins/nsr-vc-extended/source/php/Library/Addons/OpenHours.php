<?php

/**
 * OpenHours ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- Open Hours --
 * A Visual composer ad-don add opening hours.
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


        if ( !class_exists( \NsrOpenHours\OpenHoursOptions::class) ) {
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
        $nisse = array(
            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type'      => 'textfield',
                'heading' => __('Title', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',

            ),

            /** @Param Type parameter*/
            /*array(
                'admin_label' => true,
                'type' => 'checkbox',
                'heading' => __('All locations', 'nsr-vc-extended'),
                'param_name' => 'vc_all_locations',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12 all-locations',
                'value' => array(
                    __('Show 1 location', 'nsr-vc-extended')   => 'true',

                )
            ),*/

            /** @Param Type parameter*/
            /*array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __('Location', 'nsr-vc-extended'),
                'param_name' => 'vc_location',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4 disable-when-all',
                'value' => $stack,
                'std'  => reset($stack),
                'save_always' => true,
                'dependency' => array(
                    'element' => 'vc_all_locations',
                    'value' => 'true',
                    'callback' => 'callbackOutsideScope'
                )
            ),*/


            /** @Param Type parameter*/
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __('Date Size', 'nsr-vc-extended'),
                'param_name' => 'vc_date_size',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4 ',
                /*'dependency' => array(
                    'element' => 'vc_all_locations',
                    'value' => 'true',
                    'callback' => 'callbackOutsideScope'
                ),*/
                'value' => array(
                    __('Choose format...', 'nsr-vc-extended')  => '',
                    __('Short', 'nsr-vc-extended')   => 'short',
                    __('Full', 'nsr-vc-extended')   => 'full',
                ),
            ),


            /** @Param Type parameter*/
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __('Type', 'nsr-vc-extended'),
                'param_name' => 'vc_type',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                /*'dependency' => array(
                    'element' => 'vc_all_locations',
                    'value' => 'true',
                    'callback' => 'callbackOutsideScope'
                ),*/
                'value'       => array(
                    __('Choose Type...', 'nsr-vc-extended') => '',
                    __('Weekends', 'nsr-vc-extended')   => 'weekends',
                    __('Week', 'nsr-vc-extended')   => 'week',
                    __('Today', 'nsr-vc-extended')   => 'today',

                ),
            ),

            /** @Param Type parameter*/
            array(
                'admin_label' => true,
                'type' => 'checkbox',
                'heading' => __('Places', 'nsr-vc-extended'),
                'param_name' => 'vc_cities',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12 vc_cities',
                'value' => $stack,
                'std' => '',
                'save_always' => true
            ),
        );
        return $nisse;
    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {
        $stack = "";//array(__('Choose Location...', 'nsr-vc-extended'));
        $oph_sections = get_field('oph_sections', 'option');

        if(isset($oph_sections)) {

            foreach ($oph_sections as $city) {
                //$keyToTheCity = substr(md5($city['location']), 0, 6);
                $stack[$city['location']] = $city['location'];
            }
        }


        vc_map(array(

                'name' => __('NSR OpenHours', 'nsr-vc-extended'),
                'description' => __('Add Openening hours', 'nsr-vc-extended'),
                'base' => 'nsr_vcOpenHours',
                "content_element" => true,
                'class' => 'vc_extended vc_openhours',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => false,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_openhours.svg' ),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) ),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/source/js/nsr-vc-extended-admin.js' ) ),
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

            $params['title'] = isset($atts['vc_designation']) ? $atts['vc_designation'] : null;
            $params['type'] = isset($atts['vc_type']) ? $atts['vc_type'] : null;
            $params['section'] = isset($atts['vc_location']) ? $atts['vc_location'] : null;
            $params['date_size'] = isset($atts['vc_date_size']) ? $atts['vc_date_size'] : null;
            $params['vc_all_locations'] = isset($atts['vc_all_locations']) ? $atts['vc_all_locations'] : null;
            $params['vc_cities'] = isset($atts['vc_cities']) ? $atts['vc_cities'] : null;

            return do_shortcode('[opening-hours  showall="'.$params['vc_all_locations'].'" datesize="'.$params['date_size'].'" city="'.$params['title'].'" type="' . $params['type'] . '"  section="' . $params['section'] . '" markup="true" cities="' . $params['vc_cities'] . '"]');
        }
    }
}