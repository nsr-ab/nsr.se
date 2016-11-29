<?php

namespace VcExtended\Library;

class MenuCollapsible
{

    function __construct()
    {

        add_action('init', array($this, 'integrateWithVC'));
        add_shortcode('nsr_menu-collapsible', array($this, 'renderExtend'));
    }


    /**
     * Available parameters
     * @return array
     */
    public function params()
    {
        return array(

            array(

                'type' => 'textfield',
                'value' => '',
                'heading' => 'Designation',
                'param_name' => 'designation',
            ),

            array(

                'type' => 'param_group',
                'value' => '',
                'param_name' => 'vc_extend_rows',
                'params' => array(

                    array(

                        'type' => 'textfield',
                        'holder' => 'div',
                        'class' => 'vc_extend_text_pagetitle',
                        'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                        'heading' => __('Title', 'vc_extend'),
                        'param_name' => 'vc_extend_text_pagetitle',
                        'value' => __('', 'vc_extend'),
                        'description' => __('Title, prefered maxchars 255', 'vc_extend')
                    ),

                    array(

                        'type' => 'colorpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_bordercolor',
                        'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                        'heading' => __('Left border color (Menu only)', 'js_composer'),
                        'param_name' => 'vc_extend_bordercolor',
                        'description' => __('Menu left border color', 'js_composer'),
                        'dependency' => array('element' => 'color'),
                    ),

                    array(

                        'type' => 'vc_link',
                        'holder' => 'div',
                        'class' => 'vc_extend_text_pagelink',
                        'edit_field_class' => 'vc_col-sm-8 vc_col-md-8',
                        'heading' => __('Link title to page', 'vc_extend'),
                        'param_name' => 'vc_extend_text_pagelink',
                        'value' => __('', 'vc_extend'),
                        'description' => __('If selected the title is going to be linked, if link title is selected, the main link title will be active.', 'vc_extend')
                    ),


                    array(

                        'type' => 'dropdown',
                        'holder' => 'div',
                        'class' => 'vc_extend_row_margin_bottom',
                        'edit_field_class' => 'vc_col-sm-4 vc_col-md-4',
                        'heading' => __('Margin bottom (Menus only)', 'vc_extend'),
                        'param_name' => 'vc_extend_row_margin_bottom',
                        'value' => array('No margins' => '', 'Margin' => '1' ),
                        'description' => __('Margin bottom', 'vc_extend')
                    ),


                    array(

                        'type' => 'textarea',
                        'holder' => 'div',
                        'class' => 'vc_extend_description',
                        'edit_field_class' => 'vc_col-sm-6 vc_col-md-8',
                        'heading' => __('Text', 'Description'),
                        'param_name' => 'vc_extend_description',
                        'value' => __('', 'vc_extend'),
                        'description' => __('Add your text, prefered maxchars 255.', 'vc_extend')
                    ),

                    array(

                        'type' => 'colorpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_colors',
                        'edit_field_class' => 'vc_col-sm-6 vc_col-md-4',
                        'heading' => __('Background color', 'js_composer'),
                        'param_name' => 'vc_extend_colors',
                        'description' => __('Select row background color.', 'js_composer'),
                        'dependency' => array('element' => 'color'),
                    ),


                    array(

                        'type' => 'iconpicker',
                        'holder' => 'div',
                        'class' => 'vc_extend_icon',
                        'edit_field_class' => 'vc_col-sm-12 vc_col-md-12',
                        'heading' => __('Icon', 'js_composer'),
                        'param_name' => 'vc_extend_material',
                        'settings' => array(

                            'emptyIcon' => true,
                            'type' => 'material',
                            'iconsPerPage' => 4000,
                        ),

                        'dependency' => array(

                            'element' => 'icon_type',
                            'value' => 'material',
                        ),

                        'description' => __('Select icon from library.', 'js_composer'),

                    )
                )
            ),
        );


    }


    /**
     * Mapping Add extra params
     * @return void
     */
    public function addParams()
    {
        vc_add_params('vc_extend', $this->params());
    }


    /**
     * Mapping Ad-don
     * @return void
     */
    public function integrateWithVC()
    {

        if (!defined('WPB_VC_VERSION')) {
            add_action('admin_notices', array($this, 'showVcVersionNotice'));
            return;
        }

        vc_map(array(

                'name' => __('Menu accordion', 'vc_extend'),
                'description' => __('Menu Accordion', 'vc_extend'),
                'base' => 'nsr_menu-collapsible',
                'class' => 'vc_extended ',
                'show_settings_on_create' => true,
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
     * @return string
     */
    public function renderExtend($atts, $content = null)
    {

        $vc_extend_rows = vc_param_group_parse_atts( $atts['vc_extend_rows'] );
        $content = wpb_js_remove_wpautop($content, true);
        $output = "<ul id=\"vc_id_".md5(date('YmdHis').rand(0,9999999))."\" class=\"collapsible\" data-collapsible=\"accordion\">";

        $int = 0;
        $countRows = count($vc_extend_rows);

        foreach($vc_extend_rows as $row) {

            /** @var  $vc_extend_colors */
            $vc_extend_colors = (isset($row['vc_extend_colors'])) ? $row['vc_extend_colors'] : '';

            /** @var  $vc_extend_bordercolor */
            $vc_extend_bordercolor = (isset($row['vc_extend_bordercolor'])) ? $row['vc_extend_bordercolor'] : '';

            /** @var  $vc_extend_material */
            $vc_extend_material = (isset($row['vc_extend_material'])) ? $row['vc_extend_material'] : '';

            /** @var  $vc_extend_description */
            $vc_extend_description = (isset($row['vc_extend_description'])) ? $row['vc_extend_description'] : '';

            /** @var  $vc_extend_text_pagetitle */
            $vc_extend_text_pagetitle = (isset($row['vc_extend_text_pagetitle'])) ? $row['vc_extend_text_pagetitle'] : '';

            /** @var  $vc_extend_text_pagelink */
            $vc_extend_text_pagelink = (isset($row['vc_extend_text_pagelink'])) ? $row['vc_extend_text_pagelink'] : '';

            /** @var  $vc_extend_row_margin_bottom */
            $vc_extend_row_margin_bottom = (isset($row['vc_extend_row_margin_bottom'])) ? $row['vc_extend_row_margin_bottom'] : '';

            /** @var  $colors */
            $colors = ($vc_extend_colors) ? $colors = " color:#fff; background-color:" . $vc_extend_colors . ";" : "";

            /** @var  $forceLinkColor */
            $forceLinkColor = ($vc_extend_colors) ? $forceLinkColor = " style=\"color:#fff; \"" : " style=\"color:#007586; \"";

            /** @var  $colorClass */
            $colorClass = ($vc_extend_colors) ? $colorClass = "wlink" : "";

            /** @var  $hasMargin */
            $hasMargin = ($vc_extend_row_margin_bottom) ? $hasMargin = "hasMargin" : "";

            /** @var  $menuBorderColor */
            $menuBorderColor = ($vc_extend_bordercolor) ? "border-left: 8px solid ".$vc_extend_bordercolor.";" : "";

            /** @var  $liCSS */
            $liCSS = "style=\"".$colors." ".$menuBorderColor." \"";


            /** @var  $output */
            $output .= "<li><div class=\"collapsible-header ".$colorClass." ".$hasMargin."\"  ". $liCSS ."\">";
            $output .= ($vc_extend_material) ? "<i class=\"material-icons ".$vc_extend_material."\"></i>" : "";


            /** Custom Title & Link */
            if(!$vc_extend_text_pagelink) {
                $output .= $vc_extend_text_pagetitle;
            }
            else {
                $href = vc_build_link($vc_extend_text_pagelink);
                $output .= "<a ".$forceLinkColor." href=\"".$href['url']."\">".$href['title']."</a>";
                $output .= "<i class=\"right material-icons materialIconState\">chevron_right</i></div>";
            }

            /** Custom Description */
            if($vc_extend_description)
                $output .= (!$vc_extend_text_pagelink) ?  " <i class=\"right material-icons materialIconState\">add</i></div>" : "";
                $output .= (!$vc_extend_text_pagelink) ?  "<div class=\"collapsible-body\"><p>".$vc_extend_description."</p></div>" : "";

            $output .= "</li>";
            $int++;

            /** Custom margins - splits up the ul */
            if($vc_extend_row_margin_bottom) {
                $output .= "</ul>";
                $output .= ($countRows == $int ) ?  "" : "<ul class=\"collapsible\" data-collapsible=\"accordion\">";
            }
            else {
                $menuMargin = null;
            }
        }

        $output .= "</ul> ";

        return $output;
    }


    /**
     * Show Notice if Visual Composer is activated or not.
     * @return string
     */
    public function showVcVersionNotice()
    {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']) . '</p>
        </div>';
    }


}




