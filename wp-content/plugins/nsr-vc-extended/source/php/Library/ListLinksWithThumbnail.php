<?php

/**
 * ListPagesWithThumbnail ad-don for Visual Composer
 *
 * @package NSRVCExtended
 *
 * Author: Johan Silvergrund
 * Company: HIQ

 * -- ListPagesWithThumbnail --
 * A Visual composer ad-don to create lists of news topics etc.
 *
 */

namespace VcExtended\Library;

class ListLinksWithThumbnail
{

    function __construct()
    {
        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_list_with_thumb', array($this, 'renderExtend'));

        add_image_size( 'nsr-rect-front-size', 991, 264 );

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
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',
                'description' => __('Visible in admin (only!)', 'nsr-vc-extended'),
                'admin_label' => true
            ),

            /** @Param Image component parameter */
            array(

                'type'      => 'attach_image',
                'heading' => __('Image', 'nsr-vc-extended'),
                'param_name' => 'vc_image',
                'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                'description' => __('Bilder som är 991px x 264px.', 'nsr-vc-extended')
            ),


            /** @Param Color picker component for border color */
            array(

                'type' => 'colorpicker',
                'holder' => 'div',
                'class' => 'vc_extend_border_colors',
                'edit_field_class' => 'vc_col-sm-2 vc_col-md-2',
                'heading' => __('Top border color', 'nsr-vc-extended'),
                'param_name' => 'vc_border_colors',
                'dependency' => array('element' => 'color'),
            ),


            /** @Param heading parameter */
            array(

                'type'      => 'textfield',
                'heading' => __('Link group heading', 'nsr-vc-extended'),
                'param_name' => 'vc_heading',
                'edit_field_class' => 'vc_col-sm-9 vc_col-md-9',
                'admin_label' => true

            ),

            /** @Param Parameter Group */
            array(

                'type' => 'param_group',
                'param_name' => 'vc_extend_rows',
                'params' => array(

                    /** @Param Link component parameter */
                    array(

                        'type' => 'vc_link',
                        'holder' => 'div',
                        'class' => 'vc_extend_text_pagelink',
                        'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                        'heading' => __('Link', 'nsr-vc-extended'),
                        'param_name' => 'vc_extend_text_pagelink',
                        'description' => __('Select link to post, page or external webpage.', 'nsr-vc-extended')
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
                    )
                )
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

                'name' => __('List links with thumbnail', 'nsr-vc-extended'),
                'description' => __('List links, post, pages and add Thumbnail', 'nsr-vc-extended'),
                'base' => 'nsr_list_with_thumb',
                "content_element" => true,
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
                "is_container" => true,
                'admin_label' => true,
                'controls' => 'full',
                'icon' => 'vc_general vc_element-icon icon-wpb-ui-accordion',
                'category' => __('NSR', 'js_composer'),
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

        $image = wp_get_attachment_image( $atts['vc_image'], $size = 'nsr-rect-front-size', $icon = false, $attr = '' );
        $vc_extend_rows = vc_param_group_parse_atts( $atts['vc_extend_rows'] );

        $output = "<div id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" style=\"border-top:2px solid ".$atts['vc_border_colors']."; \" class=\"card hoverable small\" >";
        $output .= "<div class=\"card-image\">";
        $output .= $image;
        $output .= "</div>";
        $output .= "<div class=\"card-content\">";
        $output .= "<h4>".$atts['vc_heading']."</h4>";
        $int = 0;
        $countRows = count($vc_extend_rows);
        $output .= "<ul>";
        foreach($vc_extend_rows as $row) {

            /** @var  $vc_extend_colors */
            $vc_extend_text_pagelink = (isset($row['vc_extend_text_pagelink'])) ? $row['vc_extend_text_pagelink'] : '';

            /** @var  $vc_extend_bordercolor */
            $vc_extend_material_list = (isset($row['vc_extend_material_list'])) ? $row['vc_extend_material_list'] : '';

            /** @var  $vc_extend_material */
            $vc_extend_colors = (isset($row['vc_extend_colors'])) ? $row['vc_extend_colors'] : '';

            $href = vc_build_link($vc_extend_text_pagelink);

            $output .= "<li><a href=\"" . $href['url'] . "\">" . $href['title'] . "</a></li>";

        }
        $output .= "</ul></div> </div>";
        return $output;
    }



}




