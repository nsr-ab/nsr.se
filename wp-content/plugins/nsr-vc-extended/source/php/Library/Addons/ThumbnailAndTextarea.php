<?php

/**
 * ThumbnailAndTextarea ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ThumbnailAndTextarea --
 * A Visual composer ad-don to create content etc.
 *
 */

namespace VcExtended\Library\Addons;

class ThumbnailAndTextarea
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_thumb_link_desc', array($this, 'renderExtend'));
        add_image_size( 'nsr-square-front-size', 620, 300 );
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
                'admin_label' => true,
                'type'      => 'textfield',
                'heading' => __('Designation', 'nsr-vc-extended'),
                'param_name' => 'vc_designation',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',
                'description' => __('Visible in admin (only!)', 'nsr-vc-extended'),

            ),

            /** @Param Image component parameter */
            array(

                'admin_label' => false,
                'type'      => 'attach_image',
                'heading' => __('Image', 'nsr-vc-extended'),
                'param_name' => 'vc_image',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'description' => __('Image size 620px x 300px.', 'nsr-vc-extended'),

            ),


            /** @Param Color picker component for border color */
            array(
                'admin_label' => false,
                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_border_colors',
                'edit_field_class' => 'vc_col-sm-2 vc_col-md-2',
                'heading' => __('Top border color', 'nsr-vc-extended'),
                'param_name' => 'vc_border_colors',
                'dependency' => array('element' => 'color'),

            ),

            /** @Param Heading & link */
            array(
                'admin_label' => false,
                'type' => 'vc_link',
                'holder' => 'div',
                'class' => 'vc_pagelink',
                'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                'heading' => __('Heading with link', 'nsr-vc-extended'),
                'param_name' => 'vc_pagelink',

            ),

            /** @Param Html area */
            array(
                'type'        => 'textarea_html',
                'heading'     => __( 'Content', 'nsr-vc-extended' ),
                'param_name'  => 'content',
                'description' => 'Lite beskrivande text',
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

                'name' => __('Thumbnail, link, heading and text', 'nsr-vc-extended'),
                'description' => __('Image, link, heading and description', 'nsr-vc-extended'),
                'base' => 'nsr_thumb_link_desc',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => false,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
                'icon' => plugins_url( 'nsr-vc-extended/dist/img/icn_puff_link.svg' ),
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

        $params['vc_image'] = isset($atts['vc_image']) ? $postdate = $atts['vc_image'] : null;
        $params['vc_pagelink'] = isset($atts['vc_pagelink']) ? $atts['vc_pagelink'] : null;
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

        $vc_border_colors = isset($params->vc_border_colors)  ? " style=\"border-top:2px solid ".$params->vc_border_colors .";\" " : null;

        $output = "<div id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" ". $vc_border_colors ." class=\"card hoverable small\" >";
        $output .= "<div class=\"card-image\">";
        if( isset($params->vc_image) )
            $output .= wp_get_attachment_image( $params->vc_image, $size = 'nsr-rect-front-size', $icon = false, $attr = '' );
        $output .= "</div>";
        $output .= "<div class=\"card-content\">";

        if(isset($params->vc_pagelink)) {
            $href = vc_build_link($params->vc_pagelink);
            $output .= "<h4><a  href=\"".$href['url']."\">".$href['title']."</a></h4>";
        }

        $output .= $params->content;
        $output .= "</div></div> ";

        return $output;
    }
}




