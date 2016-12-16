<?php

/**
 * Puff ad-don for Visual Composer
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

class Puff
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_faq_list', array($this, 'renderExtend'));
        add_image_size( 'nsr-square-front-size', 620, 300 );
    }


    /**
     * Available parameters
     * @return array
     */
    static function params()
    {
        return array(

            /** @Param Color picker component for border color */
            array(
                'admin_label' => false,
                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_border_colors',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
                'heading' => __('Top border color', 'nsr-vc-extended'),
                'param_name' => 'vc_border_colors',
                'dependency' => array('element' => 'color'),

            ),

            /** @Param Designation parameter */
            array(
                'admin_label' => true,
                'type'      => 'textfield',
                'heading' => __('Title', 'nsr-vc-extended'),
                'param_name' => 'vc_title',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',

            ),


            /** @Param Icon component parameter */
            array(

                'type' => 'iconpicker',
                'param_name' => 'vc_icon',
                'edit_field_class' => 'vc_col-sm-7 vc_col-md-7',
                'heading' => __('Icon', 'nsr-vc-extended'),
                'settings' => array(
                    'emptyIcon' => true,
                    'type' => 'material',
                    'iconsPerPage' => 26,
                ),
                'description' => __('Select icon from library.', 'nsr-vc-extended'),
            ),

            /** @Param Color picker component for border color */
            array(
                'admin_label' => false,
                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_icon_colors',
                'edit_field_class' => 'vc_col-sm-2 vc_col-md-2',
                'heading' => __('Icon color', 'nsr-vc-extended'),
                'param_name' => 'vc_icon_colors',
                'dependency' => array('element' => 'color'),

            ),

            /** @Param Html area */
            array(
                'type'        => 'textarea_html',
                'heading'     => __( 'Content', 'nsr-vc-extended' ),
                'param_name'  => 'content',
                'description' => 'Lite beskrivande text',
                'admin_label' => true,
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

                'name' => __('Brick with content', 'nsr-vc-extended'),
                'description' => __('Title, icon, textarea', 'nsr-vc-extended'),
                'base' => 'nsr_faq_list',
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
                'params' => $this->params()
            )
        );
    }



    /**
     * Logic behind Ad-don output
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function renderExtend(array $atts, $content = null)
    {

        $params['vc_title'] = isset($atts['vc_title']) ? $postdate = $atts['vc_title'] : null;
        $params['vc_icon'] = isset($atts['vc_icon']) ? $postdate = $atts['vc_icon'] : null;
        $params['vc_icon_colors'] = isset($atts['vc_icon_colors']) ? $postdate = $atts['vc_icon_colors'] : null;
        $params['vc_border_colors'] = isset($atts['vc_border_colors']) ? $atts['vc_border_colors'] : null;
        $params['content'] = isset($content) ? $content : null;

        return $this->renderMarkup($param = (object) $params);

    }



    /**
     * loop object and render markup
     * @param object $get_results
     * @param array $params
     * @return string
     */
    public function renderMarkup($params)
    {

        if(!isset($params->vc_icon_colors))
            $params->vc_icon_colors = "#7e7f80";

        $vc_border_colors = isset($params->vc_border_colors)  ? " style=\"border-top:2px solid ". $params->vc_border_colors .";\" " : null;
        $vc_icon_colors = isset($params->vc_icon_colors)  ? " style=\"color:".$params->vc_icon_colors .";\" " : null;
        $output = "<div id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" ". $vc_border_colors ." class=\"card hoverable small\" >";
        $output .= "<div class=\"card-content\">";
        $output .= "<h4 class=\"faq\"><i ".$vc_icon_colors." class=\"listIcons material-icons ".$params->vc_icon."\"></i>".$params->vc_title."</h4>";
        $output .= $params->content;
        $output .= "</div></div> ";

        return $output;
    }
}




