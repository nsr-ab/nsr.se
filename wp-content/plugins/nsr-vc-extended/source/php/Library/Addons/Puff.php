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

            /** @Param Color picker component for background color */
            array(
                'admin_label' => false,
                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_bg_colors',
                'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
                'heading' => __('Background color', 'nsr-vc-extended'),
                'param_name' => 'vc_bg_colors',
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

            /** @Param Color picker component for icon color */
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
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_puff_text.svg' ),
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
    public function renderExtend($atts, $content = null)
    {

        $params['vc_title'] = isset($atts['vc_title']) ? $postdate = $atts['vc_title'] : null;
        $params['vc_icon'] = isset($atts['vc_icon']) ? $postdate = $atts['vc_icon'] : null;
        $params['vc_icon_colors'] = isset($atts['vc_icon_colors']) ? $postdate = $atts['vc_icon_colors'] : null;
        $params['vc_border_colors'] = isset($atts['vc_border_colors']) ? $atts['vc_border_colors'] : null;
        $params['vc_txt_colors'] = isset($atts['vc_txt_colors']) ? $atts['vc_txt_colors'] : null;
        $params['vc_bg_colors'] = isset($atts['vc_bg_colors']) ? $atts['vc_bg_colors'] : null;
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


        if($params->vc_bg_colors) {
            $vc_bg_colors = "background-color:" . $params->vc_bg_colors . " !important; color:#fff !important; ";
            $vc_icon_colors = "style=\"color:#fff;\" ";
            $vc_txt_colors = "forceWhiteLinkColor";
        }else {
            $vc_icon_colors = isset($params->vc_icon_colors) ? " style=\"color:" . $params->vc_icon_colors . ";\" " : null;
        }

        if($params->vc_icon) {
            $icon = "<i " . $vc_icon_colors . " class=\"listIcons material-icons " . $params->vc_icon . "\"></i>";
        }
        else {
            $icon = false;
        }

        $vc_bg_colors = isset($vc_bg_colors) ? $vc_bg_colors : null;
        $vc_txt_colors = isset($vc_txt_colors) ? $vc_txt_colors : null;
        $vc_border_colors = isset($params->vc_border_colors)  ? " style=\"".$vc_bg_colors." border-top:3px solid ". $params->vc_border_colors .";\" " : "style=\"".$vc_bg_colors." \"";

        $output = "<div hoze=ei!, id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" ". $vc_border_colors ." class=\"".$vc_txt_colors." card hoverable small\" >";
        $output .= "<div class=\"card-content ".$vc_txt_colors." \">";
        $output .= "<h4 class=\"faq ".$vc_txt_colors." \">".$icon." ".$params->vc_title."</h4>";
        $output .= $params->content;
        $output .= "</div></div> ";

        return $output;
    }
}




