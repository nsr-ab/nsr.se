<?php

/**
 * ListNewsAndPosts ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ListNewsAndPosts --
 * A Visual composer ad-don to create lists of news topics etc.
 *
 */

namespace VcExtended\Library\Addons;

class ListNewsAndPosts extends MasterVCExtended
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_collection', array($this, 'renderExtend'));

    }


    /**
     * Available parameters
     * @return array
     */
    static function params()
    {
        return array(

            /** @Param Designation parameter */
            array(

                'type'      => 'textfield',
                'heading' => __('Designation', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'description' => __('Visible in admin (only!)', 'nsr-vc-extended'),
                'admin_label' => true
            ),


            /** @Param Show date parameter*/
            array(
                'type' => 'dropdown',
                'heading' => __('Post date', 'nsr-vc-extended'),
                'param_name' => 'vc_postdate',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'value' => array(
                    __('Include', 'nsr-vc-extended')   => 1,
                    __('Exclude', 'nsr-vc-extended')   => 2
                ),
            ),


            /** @Param Icon component parameter */
            array(

                'type' => 'iconpicker',
                'param_name' => 'vc_extend_material_list',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'heading' => __('Icon', 'nsr-vc-extended'),
                'settings' => array(
                    'emptyIcon' => true,
                    'type' => 'material',
                    'iconsPerPage' => 26,
                ),
                'description' => __('Select icon from library.', 'nsr-vc-extended'),
            ),


            /** @Param Color picker component for background color */
            array(

                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_colors',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'heading' => __('Icon color', 'nsr-vc-extended'),
                'param_name' => 'vc_extend_colors',
                'dependency' => array('element' => 'color'),
            ),


            /** @Param Post loop parameter */
            array(

                'type' => 'loop',
                'heading' => __('Select your post & categories', 'nsr-vc-extended'),
                'param_name' => 'vc_loop',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
            ),


            /** @Param Start from db query parameter */
            array(

                'type'      => 'textfield',
                'heading' => __('Skip posts', 'nsr-vc-extended'),
                'param_name' => 'vc_startfrom',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',
                'description' => __('Skip posts in query, Only use if you set Post count in build query', 'nsr-vc-extended'),
                'admin_label' => true
            ),


        );
    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        vc_map(array(

                'name' => __('List news & posts', 'nsr-vc-extended'),
                'description' => __('List news and posts', 'nsr-vc-extended'),
                'base' => 'nsr_collection',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => true,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_news.svg' ),
                'admin_enqueue_css' => array( plugins_url( 'nsr-vc-extended/dist/css/nsr-vc-extended-admin.min.css' ) ),
                'params' => $this->params()
            )
        );
    }



    /**
     * Logic behind Ad-don output
     * @param array
     * @return string
     */
    public function renderExtend(array $atts)
    {

        if ( isset( $atts['vc_loop'] ) && ! empty( $atts['vc_loop'] ) )
            $query = $atts['vc_loop'];

        if ( isset( $query ) ) {

            $params = parent::pairParams($query);
            $params['date'] = isset($atts['vc_postdate']) ? $postdate = $atts['vc_postdate'] : null;
            $params['vc_startfrom'] = isset($atts['vc_startfrom']) ? $postdate = $atts['vc_startfrom'] : null;
            $params['vc_extend_material'] = isset($atts['vc_extend_material_list']) ? $atts['vc_extend_material_list'] : null;
            $params['vc_extend_colors'] = isset($atts['vc_extend_colors']) ? $atts['vc_extend_colors'] : null;

            return parent::fetchDataFromDB($params);
        }
    }



    /**
     * loop object and render markup
     * @param object $get_results
     * @param array $params
     * @return string
     */
    public function renderMarkup($get_results, $params){

        $vc_icon_colors = isset($params->vc_extend_colors)  ? " style=\"color:".$params->vc_extend_colors .";\" " : null;
        $output = "<ul id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" class=\"collection\">";

        foreach( $get_results as $result ) {

            $output .= "<a class=\"collection-item\" href=\"".get_permalink($result->ID)."\">";
            $output .= isset($params->vc_extend_material) ? "<i ".$vc_icon_colors." class=\"listIcons material-icons ".$params->vc_extend_material."\"></i>" : null;
            $output .= "<span class=\"collection-title\">".$result->post_title."</span>";
            $output .= (isset($params->date) == 1) ? "<span class=\"right date\">".date('Y-m-d', strtotime($result->post_date))."</span>" : null;
            $output .= "</a>";
        }

        $output .= "</ul> ";

        return $output;
    }






}




